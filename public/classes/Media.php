<?php


namespace Palasthotel\ProLitteris;


use WP_Post;

class Media extends _Component {

	public function onCreate() {
		/**
		 * add fields to attachments
		 */
		add_filter( 'attachment_fields_to_edit', [$this,'attachment_fields_to_edit'], 10, 2 );

		/**
		 * save custom meta field values
		 */
		add_action( 'edit_attachment', [$this, 'edit_attachment']);
	}

	/**
	 *
	 * @param $form_fields array fields for attachment
	 * @param $post WP_Post the post object of the attachment
	 *
	 * @return array modified
	 *
	 */
	public function attachment_fields_to_edit($form_fields, $post){

		/**
		 * get values and append to form fields
		 */
		$form_fields[Plugin::POST_META_IMAGE_AUTHOR] = [
			'label' => __('ProLitteris Author', Plugin::DOMAIN),
			'input' => 'text',
			'helps' => __('Pro-Litteris image author.', Plugin::DOMAIN),
			'value' => get_post_meta($post->ID, Plugin::POST_META_IMAGE_AUTHOR, true),
		];

		return $form_fields;
	}

	/**
	 * @param $attachment_id integer
	 */
	public function edit_attachment($attachment_id) {
		if(
			isset($_POST["attachments"]) &&
			isset($_POST["attachments"][$attachment_id])
		){
			$attachment_meta = $_POST["attachments"][$attachment_id];

			if(isset($attachment_meta[Plugin::POST_META_IMAGE_AUTHOR])){
				update_post_meta(
					$attachment_id,
					Plugin::POST_META_IMAGE_AUTHOR,
					intval($attachment_meta[Plugin::POST_META_IMAGE_AUTHOR])
				);
			}
		}

	}
}