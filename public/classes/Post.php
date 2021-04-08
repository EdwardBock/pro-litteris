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
	 * @param int|string $post_id
	 *
	 * @return array|WP_Error
	 */
	public function getPostMessage( $post_id ) {

		$authorIds = apply_filters(
			Plugin::FILTER_POST_AUTHORS,
			[
				get_post_field( 'post_author', $post_id )
			],
			$post_id
		);

		$participants = array();
		foreach ( $authorIds as $authorId ) {
			$participant = $this->getParticipant($authorId);
			if(is_array($participant)){
				$participants[] = $participant;
			}
		}

		// add image participants
		$post_object = get_post($post_id);
		if ( has_blocks( $post_object->post_content ) ) {
			$blocks = parse_blocks( $post_object->post_content );
			$ids = [];
			foreach ($blocks as $block){
				if ( $block['blockName'] === 'core/image' ) {
					$ids[] = $block["attrs"]["id"];
				} else if ($block['blockName'] === 'core/gallery') {
					foreach ($block["attrs"]["ids"] as $id){
						$ids[] = $id;
					}
				}
			}
			$imageAuthorIds = array_map(function($id){
				return get_post_field("post_author", $id);
			}, array_unique($ids));

			foreach ( array_unique($imageAuthorIds) as $author){
				$participant = $this->getParticipant($author, "IMAGE_ORIGINATOR");
				if(is_array($participant)){
					$participants[] = $participant;
				}
			}
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

	private function getParticipant($user_id, $participation = "AUTHOR"){
		$litterisFirstName = $this->plugin->user->getProLitterisName( $user_id );
		$litterisLastName  = $this->plugin->user->getProLitterisSurname( $user_id );
		$user              = get_user_by( "ID", $user_id );
		$firstName         = ( ! empty( $litterisFirstName ) ) ? $litterisFirstName : $user->first_name;
		$lastName          = ( ! empty( $litterisLastName ) ) ? $litterisLastName : $user->last_name;

		if ( empty( $firstName ) || empty( $lastName ) ) {
			return false;
		}

		$proLitterisId = $this->plugin->user->getProLitterisId( $user_id );

		if ( empty( $proLitterisId ) ) {
			return false;
		}

		return MessageUtils::buildParticipant(
			$proLitterisId,
			$user_id,
			$participation,
			$firstName,
			$lastName
		);
	}

}
