<?php

namespace Palasthotel\ProLitteris;

use Html2Text\Html2Text;
use WP_Error;

/**
 * @property Plugin plugin
 */
class Post extends _Component {

	/**
	 * @param string|int|null $post_id
	 *
	 * @return string
	 */
	public function getPostText( $post_id = null ) {

		// generate from content
		$post = get_post( $post_id );

		$html    = "";
		$excerpt = get_the_excerpt( $post_id );
		$content = apply_filters( 'the_content', $post->post_content );
		if ( !empty($excerpt) && strpos( $content, $excerpt ) === false ) {
			$html = $excerpt . $content;
		} else {
			$html = $content;
		}

		$html = apply_filters( Plugin::FILTER_POST_MESSAGE_CONTENT, $html, $content, $excerpt );

		$html2text = new Html2Text( $html, array( 'do_links' => 'none', 'width' => 0 ) );

		return $html2text->getText();
	}

	/**
	 * @param string|int|null $post_id
	 * @param null $text
	 *
	 * @return bool
	 */
	public function needsPixel( $post_id = null, $text = null ) {
		$text = ( $text == null ) ? $this->getPostText( $post_id ) : $text;

		return strlen( $text ) >= Options::getMinCharCount();
	}

	/**
	 * @param null $post_id
	 *
	 * @return array|WP_Error
	 */
	public function getPostMessage( $post_id = null ) {

		$authorIds = apply_filters(
			Plugin::FILTER_POST_AUTHORS,
			[
				get_post_field( 'post_author', $post_id )
			],
			$post_id
		);

		$participants = array();
		foreach ( $authorIds as $authorId ) {
			$litterisFirstName = $this->plugin->user->getProLitterisName( $authorId );
			$litterisLastName  = $this->plugin->user->getProLitterisSurname( $authorId );
			$user              = get_user_by( "ID", $authorId );
			$firstName         = ( ! empty( $litterisFirstName ) ) ? $litterisFirstName : $user->first_name;
			$lastName          = ( ! empty( $litterisLastName ) ) ? $litterisLastName : $user->last_name;

			if ( empty( $firstName ) || empty( $lastName ) ) {
				continue;
			}

			$proLitterisId = $this->plugin->user->getProLitterisId( $authorId );

			if ( empty( $proLitterisId ) ) {
				continue;
			}

			$participants[] = MessageUtils::buildParticipant(
				$proLitterisId,
				$authorId,
				"AUTHOR",
				$firstName,
				$lastName
			);
		}

		if ( count( $participants ) < 1 ) {
			return new WP_Error(
				Plugin::ERROR_CODE_PUSH_MESSAGE,
				"No valid participants."
			);
		}

		$pixel = $this->plugin->repository->getPostPixel( $post_id );

		$title = get_the_title( $post_id );
		$text  = $this->getPostText( $post_id );

		return MessageUtils::buildMessage(
			$title,
			base64_encode( $text ),
			$participants,
			$pixel->uid
		);

	}

}
