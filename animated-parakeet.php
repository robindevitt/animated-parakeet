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

use AnimatedParakeet\Settings;

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
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\fe__style_and_scripts' );

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
		wp_enqueue_script( 'animated-parakeet-be-script', ANIMIATED_PARAKEET_URL . 'assets/js/animated-parakeet-be.min.js', array( 'jquery' ), ANIMIATED_PARAKEET_VER, true );
		wp_enqueue_style( 'animated-parakeet-be--style', ANIMIATED_PARAKEET_URL . 'assets/css/animated-parakeet-be.css', array(), ANIMIATED_PARAKEET_VER, 'all' );
	}
}

/**
 * Frontend Scripts and Styles.
 */
function fe__style_and_scripts() {
	$optionvalues = Settings\animated_parakeet_options();
	if ( action__display_options( $optionvalues['display'] ) ) {
		wp_enqueue_style( 'animated-parakeet-fe--style', ANIMIATED_PARAKEET_URL . 'assets/css/animated-parakeet-fe.css', array(), ANIMIATED_PARAKEET_VER, 'all' );
		wp_enqueue_script( 'animated-parakeet-fe-script', ANIMIATED_PARAKEET_URL . 'assets/js/animated-parakeet-fe.min.js', array( 'jquery' ), ANIMIATED_PARAKEET_VER, true );
		wp_localize_script(
			'animated-parakeet-fe-script',
			'apvars',
			array(
				'woo_cart_url' => wc_get_cart_url(),
				'position'     => ( isset( $optionvalues['position'] ) ? 'bottom' : 'top' ),
				'layout'       => ( isset( $optionvalues['layput'] ) ? 'background' : 'default' ),
				'close'        => apply_filters( 'filter_animated_parakeet_close', ( isset( $optionvalues['close'] ) ? $optionvalues['close'] : '10' ) ),
			)
		);
	}
}

/**
 * Determine if the Display options are met.
 *
 * @param array $displayoptions Array of the stored display options.
 */
function action__display_options( $displayoptions = array() ) {

	if ( empty( $displayoptions ) ) {
		return false;
	}

	$is_pages          = ( is_woocommerce() || is_cart() || is_checkout() && in_array( 'pages', $displayoptions, true ) );
	$is_shop_archive   = ( is_shop() && in_array( 'shoparchive', $displayoptions, true ) );
	$is_product_cat    = ( is_product_category() && in_array( 'productcategories', $displayoptions, true ) );
	$is_product_tags   = ( is_product_tag() && in_array( 'producttags', $displayoptions, true ) );
	$is_product_attr   = ( ( is_product_taxonomy() & is_tax() ) && in_array( 'productattributes', $displayoptions, true ) );
	$is_product_single = ( 'product' === get_post_type( get_queried_object_id() ) ) && in_array( 'productsingle', $displayoptions, true );

	// Check the values and return.
	switch ( true ) {
		case $is_pages:
		case $is_shop_archive:
		case $is_product_cat:
		case $is_product_tags:
		case $is_product_attr:
		case $is_product_single:
			return true;
		default:
			return false;
	}
}
