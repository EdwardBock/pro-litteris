<?php

namespace Palasthotel\ProLitteris;

use Html2Text\Html2Text;

/**
 * @property Plugin plugin
 */
class Post {

	/**
	 * Post constructor.
	 *
	 * @param Plugin $plugin
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
	}

	public function getPostText( $post_id = NULL ) {

		// generate from content
		$post      = get_post( $post_id );
		$html      = apply_filters( 'the_content', $post->post_content );
		$html2text = new Html2Text( $html, array('do_links' => 'none', 'width' => 0) );

		return $html2text->getText();
	}

	public function savePostText($post_id, $text){
		update_post_meta($post_id, Plugin::POST_META_PRO_LITTERIS_RAW_TEXT, $text);
	}

	public function needsPixel( $post_id = NULL, $text = NULL ) {
		$text = ( $text == NULL ) ? $this->getPostText( $post_id ) : $text;

		return strlen( $text ) >= Plugin::PRO_LITTERIS_MIN_CHAR_COUNT;
	}

	public function saveNeedsPixel($post_id, $needsPixel){
		if(!$needsPixel){
			update_post_meta($post_id, Plugin::POST_META_PRO_LITTERIS_NOT_NEEDED, "1");
		} else {
			delete_post_meta($post_id, Plugin::POST_META_PRO_LITTERIS_NOT_NEEDED);
		}
	}

	/**
	 * @param null $post_id
	 *
	 * @return array
	 * @throws NoParticipantException
	 */
	public function getPostMessage($post_id = NULL){

		$authorIds = apply_filters(
			Plugin::FILTER_POST_AUTHORS,
			[
				get_post_field('post_author', $post_id)
			],
            $post_id
		);

		$participants = array();
		foreach ($authorIds as $authorId){
			$litterisFirstName = $this->plugin->user->getProLitterisName($authorId);
			$litterisLastName = $this->plugin->user->getProLitterisSurname($authorId);
			$user = get_user_by("ID", $authorId);
			$firstName = (!empty($litterisFirstName))? $litterisFirstName : $user->first_name;
			$lastName = (!empty($litterisLastName))? $litterisLastName : $user->last_name;

			if(empty($firstName) || empty($lastName)) continue;

			$proLitterisId = $this->plugin->user->getProLitterisId($authorId);

			if(empty($proLitterisId)) continue;

			$participants[] = Service::buildParticipant(
				$proLitterisId,
				$authorId,
				"AUTHOR",
				$firstName,
				$lastName
			);
		}

		if(count($participants) < 1) throw new NoParticipantException();

		$pixelUid = Service::getUid($post_id);
		$title = get_the_title($post_id);
		$text = $this->getPostText($post_id);

		return Service::buildMessage(
			$title,
			base64_encode($text),
			$participants,
			$pixelUid
		);

	}

	/**
	 * @param int $post_id
	 * @param array $message
	 */
	public function saveReportedPostMessage($post_id, $message){
		update_post_meta($post_id, Plugin::POST_META_PRO_LITTERIS_MESSAGE_OBJECT, $message);
		update_post_meta($post_id, Plugin::POST_META_PRO_LITTERIS_MESSAGE_SENT_DATE, date("Y-m-d H:i:s"));
	}

	/**
	 * @param $post_id
	 *
	 * @return mixed
	 */
	public function getReportedPostMessage($post_id){
		return get_post_meta($post_id, Plugin::POST_META_PRO_LITTERIS_MESSAGE_OBJECT, true);
	}

	/**
	 * @param int $post_id
	 *
	 * @return mixed
	 */
	public function getReportedPostMessageDate($post_id){
		return get_post_meta($post_id, Plugin::POST_META_PRO_LITTERIS_MESSAGE_SENT_DATE, true);
	}

	public function getReportResponse($post_id){
		return get_post_meta($post_id, Plugin::POST_META_PRO_LITTERIS_MESSAGE_RESPONSE, true);
	}

	public function isReported($post_id){
		return is_object($this->getReportResponse($post_id));
	}

}