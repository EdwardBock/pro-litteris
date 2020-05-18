<?php


namespace Palasthotel\ProLitteris;


/**
 * @property Plugin plugin
 */
class Pixel {
	/**
	 * Pixel constructor.
	 *
	 * @param Plugin $plugin
	 */
	public function __construct($plugin) {
		$this->plugin = $plugin;

		// TODO: better in wp footer?
		add_filter( 'the_content', array( $this, 'add_pixel' ) );
		add_action( 'amp_post_template_footer', array( $this, 'amp_post_template_footer' ) );
	}

	/**
	 * list of post types that are using pro litteris pixel
	 * @return mixed|void
	 */
	public function enabledPostTypes(){
		return apply_filters(Plugin::FILTER_POST_TYPES, array("post"));
	}

	/**
	 * check if a post type is activated for pixel
	 * @param string $postType
	 *
	 * @return bool
	 */
	public function isEnabled($postType){
		return in_array($postType, $this->enabledPostTypes());
	}

	/**
	 * request is on amp page
	 * @return bool
	 */
	private function isAmp() {
		return ( function_exists( 'is_amp_endpoint' ) && is_single() && is_amp_endpoint() );
	}

	/**
	 * Add pixel to content if exists
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	public function add_pixel( $content ) {

		if(!$this->isEnabled(get_post_type())){
			return $content;
		}

		$snippet = Service::getSnippet( get_the_ID() );

		// if is error, skip
		if( ! $this->plugin->is_valid_snippet( $snippet ) ) {
			return $content;
		}

		if ( $this->isAmp() ) {
			return $content;
		}

		return $content . '<img src="' . $snippet . '" height="1" width="1" border="0" class="pro-litteris-pixel" />';

	}

	public function amp_post_template_footer() {

		if(!$this->isEnabled(get_post_type())){
			return;
		}

		$snippet = Service::getSnippet( get_the_ID() );

		// if is error, skip
		if( ! $this->plugin->is_valid_snippet( $snippet ) ) {
			return;
		}

		?>
		<amp-pixel
			class="pro-litteris-pixel"
			src="<?php echo $snippet; ?> "
			layout="nodisplay"
		></amp-pixel>
		<?php
	}


}