<?php
/**
 * Plugin Name: Animated Parakeet
 * Plugin URI: https://github.com/robindevitt/animated-parakeet
 * Description: A custom developed plugin for add to cart notificaitons.
 * Version: 1.0.0
 * Author: Robin Devitt
 * Author URI: https://github.com/robindevitt
 * License: GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: animated-parakeet
 * Domain Path: /languages
 *
 * @package AnimatedParakeet
 */

namespace AnimatedParakeet;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'ANIMIATED_PARAKEET_VER', '1.0.0' );
define( 'ANIMIATED_PARAKEET_URL', plugin_dir_url( __FILE__ ) );

add_action( 'plugins_loaded', __NAMESPACE__ . '\action__plugin_activate' );
add_action( 'admin_menu', __NAMESPACE__ . '\Settings\options' );
add_action( 'admin_init', __NAMESPACE__ . '\Settings\settings' );
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\be__style_and_scripts', 99 );
add_action( 'wp_footer', __NAMESPACE__ . '\fe__style_and_scripts' );

/**
 * WooCommerce Deactivated Notice.
 */
function action__plugin_activate() {
	if ( ! class_exists( 'woocommerce' ) ) {
		/* translators: %s: WooCommerce link */
		echo '<div class="error"><p>' . sprintf( esc_html__( 'Animated Parakeet requires %s to be installed and active.', 'animated-parakeet' ), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>' ) . '</p></div>';
	}

	require_once 'admin/admin-settings.php';
	require_once 'woocommerce/cart-notice.php';
}

/**
 * Backend/ Admin Script and Styles.
 **/
function be__style_and_scripts() {
	$current_page = get_current_screen()->base;
	if ( 'toplevel_page_animated-parakeet' === $current_page && is_admin() ) {
		wp_enqueue_script( 'animated-parakeet-be-script', plugins_url( '/assets/js/animated-parakeet-be.min.js', __FILE__ ), array( 'jquery' ), ANIMIATED_PARAKEET_VER, true );
	}
}

/**
 * Frontend Scripts and Styles.
 */
function fe__style_and_scripts() {
	// TODO! Check if the condition is met to shoW!!
	wp_enqueue_script( 'animated-parakeet-fe-script', plugins_url( '/assets/js/animated-parakeet-fe.min.js', __FILE__ ), array( 'jquery' ), ANIMIATED_PARAKEET_VER, true );
}