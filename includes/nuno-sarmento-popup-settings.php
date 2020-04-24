<?php defined('ABSPATH') or die();


class Nuno_Sarmento_PopUp {

	private $nuno_sarmento_popup_options;
	private $options_about;
	private $options_report;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'nuno_sarmento_popup_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'nuno_sarmento_popup_page_init' ) );
	}

	public function nuno_sarmento_popup_add_plugin_page() {
		add_menu_page(
			'NS PopUp', // page_title
			'NS PopUp', // menu_title
			'manage_options', // capability
			'nuno-sarmento-popup', // menu_slug
			array( $this, 'nuno_sarmento_popup_create_admin_page' ), // function
			'dashicons-external', // icon_url
			20 // position
		);
	}

	public function nuno_sarmento_popup_create_admin_page() {

		$this->nuno_sarmento_popup_options = get_option( 'nuno_sarmento_popup_option_name' );
		$this->options_about = get_option( 'ns_popup_about' );
		$this->options_report = get_option( 'ns_popup_report' );
		$about_Screen = ( isset( $_GET['action'] ) && 'about' == $_GET['action'] ) ? true : false;
    $report_Screen = ( isset( $_GET['action'] ) && 'report' == $_GET['action'] ) ? true : false;


		?>

		<style media="screen">
		.header__ns_nsss:after { content: " "; display: block; height: 29px; width: 15%; position: absolute;
			top: 3%; right: 25px; background-image: url(//ps.w.org/nuno-sarmento-social-icons/assets/icon-128x128.png?rev=1588574); background-size:128px 128px; height: 128px; width: 128px;
		}
		.header__ns_nsss{ background: white; height: 150px; width: 100%; float: left;}
		.header__ns_nsss h2 {padding: 35px;font-size: 27px;}
		@media only screen and (max-width: 480px) {
			.header__ns_nsss:after { content: " "; display: block; height: 29px; width: 15%; position: absolute;
				top: 6%; right: 25px; background-image: url(//ps.w.org/nuno-sarmento-social-icons/assets/icon-128x128.png?rev=1588574); background-size:50px 50px; height: 50px; width: 50px;
			}
		}
		</style>

		<div class="wrap">
		<div class="header__ns_nsss">
			<h2><?php echo NUNO_SARMENTO_POPUP_NAME; ?></h2>
		</div>
			<h2 class="nav-tab-wrapper">
				<a href="<?php echo admin_url( 'admin.php?page=nuno-sarmento-popup' ); ?>" class="nav-tab<?php if ( ! isset( $_GET['action'] ) || isset( $_GET['action'] ) && 'about' != $_GET['action']  && 'report' != $_GET['action'] ) echo ' nav-tab-active'; ?>"><?php esc_html_e( 'Settings' ); ?></a>
				<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'about' ), admin_url( 'admin.php?page=nuno-sarmento-popup' ) ) ); ?>" class="nav-tab<?php if ( $about_Screen ) echo ' nav-tab-active'; ?>"><?php esc_html_e( 'Other Plugins' ); ?></a>
				<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'report' ), admin_url( 'admin.php?page=nuno-sarmento-popup' ) ) ); ?>" class="nav-tab<?php if ( $report_Screen ) echo ' nav-tab-active'; ?>"><?php esc_html_e( 'System Report' ); ?></a>
			</h2>
		 <form method="post" action="options.php">
			 <?php
				 if($about_Screen) {
					settings_fields( 'ns_popup_about' );
					do_settings_sections( 'ns-popup-setting-about' );

				} elseif($report_Screen) {
					settings_fields( 'ns_popup_report' );
					do_settings_sections( 'ns-popup-setting-report' );

				}else {
					settings_fields( 'nuno_sarmento_popup_option_group' );
					do_settings_sections( 'nuno-sarmento-popup-admin' );
					submit_button();
				}
			?>
		</form>
	 </div>

	<?php

  }


	public function nuno_sarmento_popup_page_init() {

		if( isset($_POST['nates_nonce_field']) &&
				check_admin_referer('nates_nonce_action', 'nates_nonce_field')){

		 if(isset($_POST['special_content'])){
			 update_option('special_content', $_POST['special_content']);
		 }
		}

		register_setting(
			'nuno_sarmento_popup_option_group', // option_group
			'nuno_sarmento_popup_option_name', // option_name
			array( $this, 'nuno_sarmento_popup_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'nuno_sarmento_popup_setting_section', // id
			__( '', 'nuno-sarmento-popup' ),
			array( $this, 'nuno_sarmento_popup_section_info' ), // callback
			'nuno-sarmento-popup-admin' // page
		);

		add_settings_field(
			'enable_disabled_0', // id
			__( 'Enable / Disabled', 'nuno-sarmento-popup' ), // title
			array( $this, 'enable_disabled_0_callback' ), // callback
			'nuno-sarmento-popup-admin', // page
			'nuno_sarmento_popup_setting_section' // section
		);

		add_settings_field(
			'special_content',
			__( 'PopUp Content', 'nuno-sarmento-popup' ), // title
			array( $this, 'popup_content_visual_fn' ), // callback
			'nuno-sarmento-popup-admin', // page
			'nuno_sarmento_popup_setting_section' // section
		);


		add_settings_field(
			'timer_3', // id
			__( 'Timer <br> <br> Examples: <br>  2000 = 2 seconds <br> 4000 = 4 seconds <br> 6000 = 5 seconds', 'nuno-sarmento-popup' ), // title
			array( $this, 'timer_3_callback' ), // callback
			'nuno-sarmento-popup-admin', // page
			'nuno_sarmento_popup_setting_section' // section
		);

    add_settings_field(
			'colorpicker_1', // id
			__( 'PopUp Text Colour', 'nuno-sarmento-popup' ), // title
			array( $this, 'colorpicker_1_callback' ), // callback
			'nuno-sarmento-popup-admin', // page
			'nuno_sarmento_popup_setting_section' // section
		);

		add_settings_field(
			'colorpicker_2', // id
			__( 'PopUp Background Colour', 'nuno-sarmento-popup' ), // title
			array( $this, 'colorpicker_2_callback' ), // callback
			'nuno-sarmento-popup-admin', // page
			'nuno_sarmento_popup_setting_section' // section
		);


		// About Page register
		register_setting(
				'ns_popup_about', // Option group
				'ns_popup_about', // Option name
				array( $this, 'ns_popup_about_callback' ) // Sanitize
		);

		add_settings_section(
				'nuno-sarmento-popup-admin', // ID
				'', // Title
				array( $this, 'ns_popup_about_callback' ), // Callback
				'ns-popup-setting-about' // Page
		);

		// Sytem Report register
		register_setting(
				'ns_popup_report', // Option group
				'ns_popup_report', // Option name
				array( $this, 'ns_popup_report_callback' ) // Sanitize
		);

		add_settings_section(
				'nuno-sarmento-popup-admin', // ID
				'', // Title
				array( $this, 'ns_popup_report_callback' ), // Callback
				'ns-popup-setting-report' // Page
		);


	}

	public function nuno_sarmento_popup_sanitize($input) {
		$sanitary_values = array();

		if ( isset( $input['enable_disabled_0'] ) ) {
			$sanitary_values['enable_disabled_0'] = $input['enable_disabled_0'];
		}

		if ( isset( $input['special_content'] ) ) {
			$sanitary_values['special_content'] = sanitize_text_field ( $input['special_content'] );
		}

		if ( isset( $input['timer_3'] ) ) {
			$sanitary_values['timer_3'] = sanitize_text_field( $input['timer_3'] );
		}

    if ( isset( $input['colorpicker_1'] ) ) {
			$sanitary_values['colorpicker_1'] = $input['colorpicker_1'];
		}

    if ( isset( $input['colorpicker_2'] ) ) {
      $sanitary_values['colorpicker_2'] = $input['colorpicker_2'];
    }

		return $sanitary_values;
	}

	public function nuno_sarmento_popup_section_info() {

	}

	public function enable_disabled_0_callback() {
		?> <select name="nuno_sarmento_popup_option_name[enable_disabled_0]" id="enable_disabled_0">
			<?php $selected = (isset( $this->nuno_sarmento_popup_options['enable_disabled_0'] ) && $this->nuno_sarmento_popup_options['enable_disabled_0'] === 'none') ? 'selected' : '' ; ?>
			<option value="none" <?php echo $selected; ?>> Disabeld</option>
			<?php $selected = (isset( $this->nuno_sarmento_popup_options['enable_disabled_0'] ) && $this->nuno_sarmento_popup_options['enable_disabled_0'] === 'block') ? 'selected' : '' ; ?>
			<option value="block" <?php echo $selected; ?>> Enabled</option>
		</select> <?php
	}

	public function popup_content_visual_fn() {
		wp_nonce_field('nates_nonce_action', 'nates_nonce_field');
		$content = get_option('special_content');
		wp_editor( $content, 'special_content' );
	}

	public function timer_3_callback() {
		printf(
			'<input class="regular-text" type="text" name="nuno_sarmento_popup_option_name[timer_3]" id="timer_3" value="%s">',
			isset( $this->nuno_sarmento_popup_options['timer_3'] ) ? esc_attr( $this->nuno_sarmento_popup_options['timer_3']) : ''
		);
	}

  public function colorpicker_1_callback() {
    $val = ( isset( $this->nuno_sarmento_popup_options['colorpicker_1'] ) ) ? $this->nuno_sarmento_popup_options['colorpicker_1'] : '';
    echo '<input type="text" name="nuno_sarmento_popup_option_name[colorpicker_1]" value="' . $val . '" class="tend-tdss-color-picker" >';
  }

  public function colorpicker_2_callback() {
    $val = ( isset( $this->nuno_sarmento_popup_options['colorpicker_2'] ) ) ? $this->nuno_sarmento_popup_options['colorpicker_2'] : '';
    echo '<input type="text" name="nuno_sarmento_popup_option_name[colorpicker_2]" value="' . $val . '" class="tend-tdss-color-picker" >';
  }

	/**
	 * helper function for number conversions
	 *
	 * @access public
	 * @param mixed $v
	 * @return void
	 */

	public function num_convt( $v ) {
		$l   = substr( $v, -1 );
		$ret = substr( $v, 0, -1 );

		switch ( strtoupper( $l ) ) {
			case 'P': // fall-through
			case 'T': // fall-through
			case 'G': // fall-through
			case 'M': // fall-through
			case 'K': // fall-through
				$ret *= 1024;
				break;
			default:
				break;
		}

		return $ret;
	}

	public function ns_popup_about_callback() {

				?>
				<h1>'Nuno Sarmento' Plugins Colection</h1>

					<div class="wrap">

								<p class="clear"></p>

								<div class="plugin-group">

								<div class="plugin-card">

									 <div class="plugin-card-top">

											 <a href="https://en-gb.wordpress.org/plugins/nuno-sarmento-slick-slider/" class="plugin-icon" target="_blank">
											 	 <style type="text/css">#plugin-icon-nuno-sarmento-slick-slider { width:128px; height:128px; background-image: url(//ps.w.org/nuno-sarmento-slick-slider/assets/icon-128x128.png?rev=1588561); background-size:128px 128px; }@media only screen and (-webkit-min-device-pixel-ratio: 1.5) { #plugin-icon-nuno-sarmento-slick-slider { background-image: url(//ps.w.org/nuno-sarmento-slick-slider/assets/icon-256x256.png?rev=1588561); } }</style>
												 <div class="plugin-icon" id="plugin-icon-nuno-sarmento-slick-slider" style="float:left; margin: 3px 6px 6px 0px;"></div>
											 </a>

											 <div class="name column-name" style="float: right;">
											    <h4><a href="https://en-gb.wordpress.org/plugins/nuno-sarmento-slick-slider/" target="_blank">Nuno Sarmento Slick Slider</a></h4>
										 	 </div>

									</div>

									<div class="plugin-card-bottom">
										<p class="authors"><cite>By: <a href="//profiles.wordpress.org/nunosarmento/" target="_blank">Nuno Morais Sarmento</a>.</cite></p>
									</div>

								</div>

								<div class="plugin-card">

									 <div class="plugin-card-top">

											 <a href="https://en-gb.wordpress.org/plugins/nuno-sarmento-custom-css-js/" class="plugin-icon" target="_blank">
											 	 <style type="text/css">#plugin-icon-nuno-sarmento-custom-css-js { width:128px; height:128px; background-image: url(//ps.w.org/nuno-sarmento-custom-css-js/assets/icon-128x128.png?rev=1588566); background-size:128px 128px; }@media only screen and (-webkit-min-device-pixel-ratio: 1.5) { #plugin-icon-nuno-sarmento-custom-css-js { background-image: url(//ps.w.org/nuno-sarmento-custom-css-js/assets/icon-256x256.png?rev=1588566); } }</style>
												 <div class="plugin-icon" id="plugin-icon-nuno-sarmento-custom-css-js" style="float:left; margin: 3px 6px 6px 0px;"></div>
											 </a>

											 <div class="name column-name" style="float: right;">
											 		<h4><a href="https://en-gb.wordpress.org/plugins/nuno-sarmento-custom-css-js/" target="_blank">Nuno Sarmento Custom CSS - JS</a></h4>
										 	 </div>

									</div>

									<div class="plugin-card-bottom">
										<p class="authors"><cite>By: <a href="//profiles.wordpress.org/nunosarmento/" target="_blank">Nuno Morais Sarmento</a>.</cite></p>
									</div>

								</div>

								<div class="plugin-card">

									 <div class="plugin-card-top">

											 <a href="https://en-gb.wordpress.org/plugins/nuno-sarmento-popup/" class="plugin-icon" target="_blank">
												 <style type="text/css">#plugin-icon-nuno-sarmento-popup { width:128px; height:128px; background-image: url(//ps.w.org/nuno-sarmento-popup/assets/icon-128x128.png?rev=1593940); background-size:128px 128px; }@media only screen and (-webkit-min-device-pixel-ratio: 1.5) { #plugin-icon-nuno-sarmento-popup { background-image: url(//ps.w.org/nuno-sarmento-popup/assets/icon-256x256.png?rev=1593940); } }</style>
												 <div class="plugin-icon" id="plugin-icon-nuno-sarmento-popup" style="float:left; margin: 3px 6px 6px 0px;"></div>
											 </a>

											 <div class="name column-name" style="float: right;">
											    <h4><a href="https://en-gb.wordpress.org/plugins/nuno-sarmento-popup/" target="_blank" >Nuno Sarmento PopUp</a></h4>
										   </div>

									</div>

									<div class="plugin-card-bottom">
										<p class="authors"><cite>By: <a href="//profiles.wordpress.org/nunosarmento/" target="_blank">Nuno Morais Sarmento</a>.</cite></p>
									</div>

							 </div>


							 <div class="plugin-card">

							 	 <div class="plugin-card-top">

							 		 <a href="https://en-gb.wordpress.org/plugins/nuno-sarmento-api-to-post/" class="plugin-icon">
										 <style type="text/css">#plugin-icon-nuno-sarmento-api-to-post { width:128px; height:128px; background-image: url(//ps.w.org/nuno-sarmento-api-to-post/assets/icon-128x128.png?rev=1594469); background-size:128px 128px; }@media only screen and (-webkit-min-device-pixel-ratio: 1.5) { #plugin-icon-nuno-sarmento-api-to-post { background-image: url(//ps.w.org/nuno-sarmento-api-to-post/assets/icon-256x256.png?rev=1594469); } }</style>
										 <div class="plugin-icon" id="plugin-icon-nuno-sarmento-api-to-post" style="float:left; margin: 3px 6px 6px 0px;"></div>
								 	 </a>

							 		 <div class="name column-name">
							 			 <h4><a href="https://en-gb.wordpress.org/plugins/nuno-sarmento-api-to-post/">Nuno Sarmento API To Post</a></h4>
							 		 </div>

							 	</div>

								<div class="plugin-card-bottom">
									<p class="authors"><cite>By: <a href="//profiles.wordpress.org/nunosarmento/" target="_blank">Nuno Morais Sarmento</a>.</cite></p>
								</div>

							 </div>


							 <div class="plugin-card">

							 	 <div class="plugin-card-top">

							 		 <a href="https://wordpress.org/plugins/change-wp-admin-login/" class="plugin-icon">
										 <style type="text/css">#plugin-icon-change-wp-admin-login { width:128px; height:128px; background-image: url(//ps.w.org/change-wp-admin-login/assets/icon-256x256.png?rev=2040699); background-size:128px 128px; }@media only screen and (-webkit-min-device-pixel-ratio: 1.5) { #plugin-icon-nuno-sarmento-api-to-post { background-image: url(//ps.w.org/change-wp-admin-login/assets/icon-256x256.png?rev=2040699); } }</style>
										 <div class="plugin-icon" id="plugin-icon-change-wp-admin-login" style="float:left; margin: 3px 6px 6px 0px;"></div>
								 	 </a>

							 		 <div class="name column-name">
							 			 <h4><a href="https://wordpress.org/plugins/change-wp-admin-login/">Change wp-admin login</a></h4>
							 		 </div>

							 	</div>

								<div class="plugin-card-bottom">
									<p class="authors"><cite>By: <a href="//profiles.wordpress.org/nunosarmento/" target="_blank">Nuno Morais Sarmento</a>.</cite></p>
								</div>

							 </div>


						</div>

				  </div>

			<?php

		}


		public function ns_popup_report_callback() {

		?>
			<div class="wrap nuno-sarmento-system-wrap">
				<div class="icon32" id="icon-tools"><br></div>
				<h2><?php _e( 'Server Details', 'nuno-sarmento-popup' ) ?></h2>
				<p><?php echo $this->ns_popup_report_data(); ?></p>
			</div>
			<style media="screen">
				div.nuno-sarmento-system-wrap h2 {margin: 0 0 1em;}
				div.nuno-sarmento-system-wrap p {margin: 0 0 1em;}
				div.nuno-sarmento-system-wrap p input.snapshot-highlight {margin: 0 0 0 10px;}
				div.nuno-sarmento-system-wrap textarea#nuno-sarmento-system-textarea {
				background: #ebebeb;display: block;font-family: Menlo,Monaco,monospace;height: 600px;overflow: auto;white-space: pre;width: 1500px;max-width: 95%;color: #000;padding: 10px 0 10px 10px;}
			</style>
		 <?php

		}

		public function ns_popup_report_data() {

			// call WP database
			global $wpdb;

			// check for browser class add on
			if ( ! class_exists( 'Browser' ) ) {
				require_once NUNO_SARMENTO_POPUP_PATH . 'includes/nuno-sarmento-popup-browser.php';
			}

			// do WP version check and get data accordingly
			$browser = new Browser();
			if ( get_bloginfo( 'version' ) < '3.4' ) :
				$theme_data = get_theme_data( get_stylesheet_directory() . '/style.css' );
				$theme      = $theme_data['Name'] . ' ' . $theme_data['Version'];
			else:
				$theme_data = wp_get_theme();
				$theme      = $theme_data->Name . ' ' . $theme_data->Version;
			endif;

			// data checks for later
			$frontpage	= get_option( 'page_on_front' );
			$frontpost	= get_option( 'page_for_posts' );
			$mu_plugins = get_mu_plugins();
			$plugins	= get_plugins();
			$active		= get_option( 'active_plugins', array() );

			// multisite details
			$nt_plugins	= is_multisite() ? wp_get_active_network_plugins() : array();
			$nt_active	= is_multisite() ? get_site_option( 'active_sitewide_plugins', array() ) : array();
			$ms_sites	= is_multisite() ? get_blog_list() : null;

			// yes / no specifics
			$ismulti	= is_multisite() ? __( 'Yes', 'nuno-sarmento-system-report' ) : __( 'No', 'nuno-sarmento-system-report' );
			$safemode	= ini_get( 'safe_mode' ) ? __( 'Yes', 'nuno-sarmento-system-report' ) : __( 'No', 'nuno-sarmento-system-report' );
			$wpdebug	= defined( 'WP_DEBUG' ) ? WP_DEBUG ? __( 'Enabled', 'nuno-sarmento-system-report' ) : __( 'Disabled', 'nuno-sarmento-system-report' ) : __( 'Not Set', 'nuno-sarmento-system-report' );
			$tbprefx	= strlen( $wpdb->prefix ) < 16 ? __( 'Acceptable', 'nuno-sarmento-system-report' ) : __( 'Too Long', 'nuno-sarmento-system-report' );
			$fr_page	= $frontpage ? get_the_title( $frontpage ).' (ID# '.$frontpage.')'.'' : __( 'n/a', 'nuno-sarmento-system-report' );
			$fr_post	= $frontpage ? get_the_title( $frontpost ).' (ID# '.$frontpost.')'.'' : __( 'n/a', 'nuno-sarmento-system-report' );
			$errdisp	= ini_get( 'display_errors' ) != false ? __( 'On', 'nuno-sarmento-system-report' ) : __( 'Off', 'nuno-sarmento-system-report' );

			$jquchk		= wp_script_is( 'jquery', 'registered' ) ? $GLOBALS['wp_scripts']->registered['jquery']->ver : __( 'n/a', 'nuno-sarmento-system-report' );

			$sessenb	= isset( $_SESSION ) ? __( 'Enabled', 'nuno-sarmento-system-report' ) : __( 'Disabled', 'nuno-sarmento-system-report' );
			$usecck		= ini_get( 'session.use_cookies' ) ? __( 'On', 'nuno-sarmento-system-report' ) : __( 'Off', 'nuno-sarmento-system-report' );
			$useocck	= ini_get( 'session.use_only_cookies' ) ? __( 'On', 'nuno-sarmento-system-report' ) : __( 'Off', 'nuno-sarmento-system-report' );
			$hasfsock	= function_exists( 'fsockopen' ) ? __( 'Supports fsockopen.', 'nuno-sarmento-system-report' ) : __( 'Not support fsockopen.', 'nuno-sarmento-system-report' );
			$hascurl	= function_exists( 'curl_init' ) ? __( 'Supports cURL.', 'nuno-sarmento-system-report' ) : __( 'Not support cURL.', 'nuno-sarmento-system-report' );
			$hassoap	= class_exists( 'SoapClient' ) ? __( 'SOAP Client enabled.', 'nuno-sarmento-system-report' ) : __( 'Does not have the SOAP Client enabled.', 'nuno-sarmento-system-report' );
			$hassuho	= extension_loaded( 'suhosin' ) ? __( 'Server has SUHOSIN installed.', 'nuno-sarmento-system-report' ) : __( 'Does not have SUHOSIN installed.', 'nuno-sarmento-system-report' );
			$openssl	= extension_loaded('openssl') ? __( 'OpenSSL installed.', 'nuno-sarmento-system-report' ) : __( 'Does not have OpenSSL installed.', 'nuno-sarmento-system-report' );

			// start generating report
			$report	= '';
			$report	.= '<textarea readonly="readonly" id="nuno-sarmento-system-textarea" name="nuno-sarmento-system-textarea">';
			$report	.= '--- Begin System Info ---'."\n";
			// add filter for adding to report opening
			$report	.= apply_filters( 'snapshot_report_before', '' );

			$report	.= "\n\t".'-- SERVER DATA --'."\n";
			$report	.= 'jQuery Version'."\t\t\t\t".$jquchk."\n";
			$report	.= 'PHP Version:'."\t\t\t\t".PHP_VERSION."\n";
			$report	.= 'MySQL Version:'."\t\t\t\t".$wpdb->db_version()."\n";
			$report	.= 'Server Software:'."\t\t\t".$_SERVER['SERVER_SOFTWARE']."\n";

			$report	.= "\n\t".'-- PHP CONFIGURATION --'."\n";
			$report	.= 'Safe Mode:'."\t\t\t\t".$safemode."\n";
			$report	.= 'Memory Limit:'."\t\t\t\t".ini_get( 'memory_limit' )."\n";
			$report	.= 'Upload Max:'."\t\t\t\t".ini_get( 'upload_max_filesize' )."\n";
			$report	.= 'Post Max:'."\t\t\t\t".ini_get( 'post_max_size' )."\n";
			$report	.= 'Time Limit:'."\t\t\t\t".ini_get( 'max_execution_time' )."\n";
			$report	.= 'Max Input Vars:'."\t\t\t\t".ini_get( 'max_input_vars' )."\n";
			$report	.= 'Display Errors:'."\t\t\t\t".$errdisp."\n";
			$report	.= 'Sessions:'."\t\t\t\t".$sessenb."\n";
			$report	.= 'Session Name:'."\t\t\t\t".esc_html( ini_get( 'session.name' ) )."\n";
			$report	.= 'Cookie Path:'."\t\t\t\t".esc_html( ini_get( 'session.cookie_path' ) )."\n";
			$report	.= 'Save Path:'."\t\t\t\t".esc_html( ini_get( 'session.save_path' ) )."\n";
			$report	.= 'Use Cookies:'."\t\t\t\t".$usecck."\n";
			$report	.= 'Use Only Cookies:'."\t\t\t".$useocck."\n";
			$report	.= 'FSOCKOPEN:'."\t\t\t\t".$hasfsock."\n";
			$report	.= 'cURL:'."\t\t\t\t\t".$hascurl."\n";
			$report	.= 'SOAP Client:'."\t\t\t\t".$hassoap."\n";
			$report	.= 'SUHOSIN:'."\t\t\t\t".$hassuho."\n";
			$report	.= 'OpenSSL:'."\t\t\t\t".$openssl."\n";

			$report	.= "\n\t".'-- WORDPRESS DATA --'."\n";
			$report	.= 'Multisite:'."\t\t\t\t".$ismulti."\n";
			$report	.= 'SITE_URL:'."\t\t\t\t".site_url()."\n";
			$report	.= 'HOME_URL:'."\t\t\t\t".home_url()."\n";
			$report	.= 'WP Version:'."\t\t\t\t".get_bloginfo( 'version' )."\n";
			$report	.= 'Permalink:'."\t\t\t\t".get_option( 'permalink_structure' )."\n";
			$report	.= 'Cur Theme:'."\t\t\t\t".$theme."\n";
			$report	.= 'Post Types:'."\t\t\t\t".implode( ', ', get_post_types( '', 'names' ) )."\n";
			$report	.= 'Post Stati:'."\t\t\t\t".implode( ', ', get_post_stati() )."\n";
			$report	.= 'User Count:'."\t\t\t\t".count( get_users() )."\n";

			$report	.= "\n\t".'-- WORDPRESS CONFIG --'."\n";
			$report	.= 'WP_DEBUG:'."\t\t\t\t".$wpdebug."\n";
			$report	.= 'WP Memory Limit:'."\t\t\t".$this->num_convt( WP_MEMORY_LIMIT )/( 1024 ).'MB'."\n";
			$report	.= 'Table Prefix:'."\t\t\t\t".$wpdb->base_prefix."\n";
			$report	.= 'Prefix Length:'."\t\t\t\t".$tbprefx.' ('.strlen( $wpdb->prefix ).' characters)'."\n";
			$report	.= 'Show On Front:'."\t\t\t\t".get_option( 'show_on_front' )."\n";
			$report	.= 'Page On Front:'."\t\t\t\t".$fr_page."\n";
			$report	.= 'Page For Posts:'."\t\t\t\t".$fr_post."\n";

			if ( is_multisite() ) :
				$report	.= "\n\t".'-- MULTISITE INFORMATION --'."\n";
				$report	.= 'Total Sites:'."\t\t\t\t".get_blog_count()."\n";
				$report	.= 'Base Site:'."\t\t\t\t".$ms_sites[0]['domain']."\n";
				$report	.= 'All Sites:'."\n";
				foreach ( $ms_sites as $site ) :
					if ( $site['path'] != '/' )
						$report	.= "\t\t".'- '. $site['domain'].$site['path']."\n";

				endforeach;
				$report	.= "\n";
			endif;

			$report	.= "\n\t".'-- BROWSER DATA --'."\n";
			$report	.= 'Platform:'."\t\t\t\t".$browser->getPlatform()."\n";
			$report	.= 'Browser Name'."\t\t\t\t". $browser->getBrowser() ."\n";
			$report	.= 'Browser Version:'."\t\t\t".$browser->getVersion()."\n";
			$report	.= 'Browser User Agent:'."\t\t\t".$browser->getUserAgent()."\n";

			$report	.= "\n\t".'-- PLUGIN INFORMATION --'."\n";
			if ( $plugins && $mu_plugins ) :
				$report	.= 'Total Plugins:'."\t\t\t\t".( count( $plugins ) + count( $mu_plugins ) + count( $nt_plugins ) )."\n";
			endif;

			// output must-use plugins
			if ( $mu_plugins ) :
				$report	.= 'Must-Use Plugins: ('.count( $mu_plugins ).')'. "\n";
				foreach ( $mu_plugins as $mu_path => $mu_plugin ) :
					$report	.= "\t".'- '.$mu_plugin['Name'] . ' ' . $mu_plugin['Version'] ."\n";
				endforeach;
				$report	.= "\n";
			endif;

			// if multisite, grab active network as well
			if ( is_multisite() ) :
				// active network
				$report	.= 'Network Active Plugins: ('.count( $nt_plugins ).')'. "\n";

				foreach ( $nt_plugins as $plugin_path ) :
					if ( array_key_exists( $plugin_base, $nt_plugins ) )
						continue;

					$plugin = get_plugin_data( $plugin_path );

					$report	.= "\t".'- '.$plugin['Name'] . ' ' . $plugin['Version'] ."\n";
				endforeach;
				$report	.= "\n";

			endif;

			// output active plugins
			if ( $plugins ) :
				$report	.= 'Active Plugins: ('.count( $active ).')'. "\n";
				foreach ( $plugins as $plugin_path => $plugin ) :
					if ( ! in_array( $plugin_path, $active ) )
						continue;
					$report	.= "\t".'- '.$plugin['Name'] . ' ' . $plugin['Version'] ."\n";
				endforeach;
				$report	.= "\n";
			endif;

			// output inactive plugins
			if ( $plugins ) :
				$report	.= 'Inactive Plugins: ('.( count( $plugins ) - count( $active ) ).')'. "\n";
				foreach ( $plugins as $plugin_path => $plugin ) :
					if ( in_array( $plugin_path, $active ) )
						continue;
					$report	.= "\t".'- '.$plugin['Name'] . ' ' . $plugin['Version'] ."\n";
				endforeach;
				$report	.= "\n";
			endif;

			// add filter for end of report
			$report	.= apply_filters( 'snapshot_report_after', '' );

			// end it all
			$report	.= "\n".'--- End System Info ---';
			$report	.= '</textarea>';

			return $report;
		}


}
if ( is_admin() )
	$nuno_sarmento_popup = new Nuno_Sarmento_PopUp();

/*
 * Retrieve this value with:
 * $nuno_sarmento_popup_options = get_option( 'nuno_sarmento_popup_option_name' ); // Array of All Options
 * $enable_disabled_0 = $nuno_sarmento_popup_options['enable_disabled_0']; // Enable / Disabled
 * $timer_3 = $nuno_sarmento_popup_options['timer_3']; // Timer
 * $colorpicker_1 = $nuno_sarmento_popup_options['colorpicker_1']; // Color
 * $colorpicker_2 = $nuno_sarmento_popup_options['colorpicker_2']; // Color
 * $content = get_option('special_content');
*/
