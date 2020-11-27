<?php


namespace Palasthotel\ProLitteris;


class DashboardWidget extends _Component {

	public function onCreate() {
		parent::onCreate();
		add_action( 'wp_dashboard_setup', array( $this, 'setup' ) );
	}

	public function setup(){
		wp_add_dashboard_widget(
			Plugin::DASHBOARD_WIDGET_ID,
			__("Pro Litteris", Plugin::DOMAIN),
			[$this, 'widget'],
			[$this, 'config']
		);
	}

	public function widget(){
		$submit_button_name = "submit_refill_pixel_pool";
		if(isset($_POST[$submit_button_name])){
			$this->plugin->repository->refillPixelPool(Options::getPixelPoolSize());
		}

		$aspired = Options::getPixelPoolSize();
		$size = $this->plugin->database->countAvailablePixels();
		printf("<p>%s</p>", sprintf(__("%d/%d pixels available in pool.", Plugin::DOMAIN), $size, $aspired));

		printf(
			"<p class='description'>%s</p>",
			__("The pixel pool is filled up every hour. If you need new pixels immediately, you can manually request new ones here.", Plugin::DOMAIN)
		);

		$needPixels = $size < $aspired;
		$attrs = [];
		if(!$needPixels){
			$attrs["disabled"] = true;
		}
		echo "<form method='POST'>";
		submit_button(
			__("Refill", Plugin::DOMAIN),
			"primary",
			$submit_button_name,
			false,
			$attrs
		);
		echo "</form>";
	}

	public function config(){
		if(isset($_POST[Plugin::OPTION_PIXEL_POOL_SIZE])){
			Options::setPixelPoolSize(intval($_POST[Plugin::OPTION_PIXEL_POOL_SIZE]));
		}
		?>
		<div style="padding-bottom: 10px;">
			<label>Aspired pixel pool size:<br/>
				<input
					type="number"
					min="0"
					max="100"
					style="width: 100px;"
					name="<?= Plugin::OPTION_PIXEL_POOL_SIZE; ?>"
					value="<?= Options::getPixelPoolSize(); ?>"
				/>
			</label>
		</div>
		<?php
	}


}