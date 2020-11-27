<?php


namespace Palasthotel\ProLitteris;


class Gutenberg extends _Component {
	public function onCreate() {
		parent::onCreate();
		add_action( 'enqueue_block_editor_assets', function () {
			$this->plugin->assets->enqueueGutenberg();
		});
	}
}
