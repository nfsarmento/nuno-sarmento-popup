<?php
/*
Plugin Name: Nuno Sarmento PopUp
Description: This plugin allows you to create lightweight popup window in your blog with custom content and custom styling. In this popup we can display any content such as Video, Image, Advertisement and much more.
Author: Nuno Morais Sarmento
Version: 1.0.2
Plugin URI: https://en-gb.wordpress.org/plugins/nuno-sarmento-popup/
Author URI: https://www.nuno-sarmento.com
Donate link: https://www.nuno-sarmento.com
Text Domain: nuno-sarmento-popup
Domain Path: /languages
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

defined( 'ABSPATH' ) || die( '°_°’' );
/* ------------------------------------------
// Constants --------------------------------
--------------------------------------------- */

/* Set plugin name. */

if( ! defined( 'NUNO_SARMENTO_POPUP_NAME' ) ) {
	define( 'NUNO_SARMENTO_POPUP_NAME', 'Nuno Sarmento - POPUP' );
}

/* Set plugin version constant. */

if( ! defined( 'NUNO_SARMENTO_POPUP_VERSION' ) ) {
	define( 'NUNO_SARMENTO_POPUP_VERSION', '1.0.1' );
}

/* Set constant path to the plugin directory. */

if ( ! defined( 'NUNO_SARMENTO_POPUP_PATH' ) ) {
	define( 'NUNO_SARMENTO_POPUP_PATH', plugin_dir_path( __FILE__ ) );
}

/* ------------------------------------------
// Require Admin Page  ----------------------
--------------------------------------------- */

if ( ! @include( 'nuno-sarmento-popup-settings.php' ) ) {
	require_once( NUNO_SARMENTO_POPUP_PATH . 'includes/nuno-sarmento-popup-settings.php' );
}

/* ------------------------------------------
// i18n -------------------------------------
--------------------------------------------- */

function nuno_sarmento_popup_fancybox_textdomain()
{
	load_plugin_textdomain( 'nuno-sarmento-popup', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

/* ------------------------------------------
// Enqueue JS -------------------------------
--------------------------------------------- */

function nuno_sarmento_popup_fancybox_scripts() {
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker');
	wp_enqueue_script( 'nuno-sarmento-popup-picker', plugins_url( 'assets/js/nuno-sarmento-popup-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
  wp_enqueue_style( 'css-ns-popup', plugins_url( '/assets/css/nuno-sarmento-popup.css', __FILE__ ) );
	wp_enqueue_script( 'script-ns-popup', plugin_dir_url( __FILE__ ) . '/assets/js/nuno-sarmento-popup.js', array('jquery'), '', true );
}
add_action( 'wp_enqueue_scripts', 'nuno_sarmento_popup_fancybox_scripts' );


/* ------------------------------------------
// Print out  JS ----------------------------
--------------------------------------------- */
function nuno_sarmento_popup_print_script() {
	$nuno_sarmento_popup_options = get_option( 'nuno_sarmento_popup_option_name' ); // Array of All Options
	$timer_3 = $nuno_sarmento_popup_options['timer_3']; // Timer

print "
<script>
(function ($) {
	function nunoSarmentoPopupTimer() {
		$('#popup-welcome-message').firstVisitPopup({
			cookieName : 'homepage',
			showAgainSelector: '#show-message'
		});
	}
	$(function() {
	    setTimeout(nunoSarmentoPopupTimer, $timer_3);
	});
})(jQuery);
</script>
";

}
add_action('wp_footer', 'nuno_sarmento_popup_print_script', 100);

/* ------------------------------------------
// Print out  PopUp Content -----------------
--------------------------------------------- */
function nuno_sarmento_popup_print_content() {
	$nuno_sarmento_popup_options = get_option( 'nuno_sarmento_popup_option_name' );
	$content = get_option('special_content');
	?>

<div class='disabled__popup'>
	<div id='popup-welcome-message'>
		<div id='nuno-sarmento-popup-dialog'>
			<?php echo $content ; ?>
		</div>
		<a id='nuno-sarmento-popup-close'>&#10006;</a>
	</div>
</div>
<?php }

add_action('wp_footer', 'nuno_sarmento_popup_print_content', 100);

/* ------------------------------------------
// Print out PopUp CSS  ---------------------
--------------------------------------------- */
function nuno_sarmento_popup_print_css() {
	$nuno_sarmento_popup_options = get_option( 'nuno_sarmento_popup_option_name' );
	$enable_disabled_0 = $nuno_sarmento_popup_options['enable_disabled_0']; // Enable / Disabled
	$colorpicker_1 = $nuno_sarmento_popup_options['colorpicker_1']; // Text Color
  $colorpicker_2 = $nuno_sarmento_popup_options['colorpicker_2']; // Background Color

print '<style>
.disabled__popup {
  display: '.$enable_disabled_0.';
}
#popup-welcome-message {
	color: '.$colorpicker_1.';
  background: '.$colorpicker_2.';
}
</style>';
}

add_action('wp_head', 'nuno_sarmento_popup_print_css', 100);
