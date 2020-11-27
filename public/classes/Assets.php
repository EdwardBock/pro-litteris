<?php


namespace Palasthotel\ProLitteris;


class Assets extends _Component {

	function enqueueGutenberg(){
		$info = include $this->plugin->path . "/assets/gutenberg/pro-litteris.asset.php";
		wp_enqueue_script(
			Plugin::HANDLE_GUTENBERG_JS,
			$this->plugin->url . "/assets/gutenberg/pro-litteris.js",
			$info["dependencies"],
			$info["version"]
		);

		if(file_exists($this->plugin->path."/assets/gutenberg/pro-litteris.css")){
			wp_enqueue_style(
				Plugin::HANDLE_GUTENBERG_CSS,
				$this->plugin->url."/assets/gutenberg/pro-litteris.css",
				[],
				filemtime($this->plugin->path."/assets/gutenberg/pro-litteris.css")
			);
		}
	}

}
