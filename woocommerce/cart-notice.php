<?php
/**
 * WooCommerce related functionality for Animated Parakeet.
 *
 * @package AnimatedParakeet
 */

namespace AnimatedParakeet\WooCommerce;

use AnimatedParakeet;
use AnimatedParakeet\Settings;

add_action( 'woocommerce_add_to_cart', __NAMESPACE__ . '\add__added_to_cart_notice', 999, 6 );
add_filter( 'woocommerce_loop_add_to_cart_args', __NAMESPACE__ . '\filter_woocommerce_loop_add_to_cart_args', 10, 2 );

/**
 * Add product attributes to buttons.
 *
 * @param array  $args Arguements that are added to the button.
 * @param object $product Product Object.
 *
 * @return $args.
 */
function filter_woocommerce_loop_add_to_cart_args( $args, $product ) {
	$optionvalues = Settings\animated_parakeet_options( 'display' );
	if ( AnimatedParakeet\action__display_options( $optionvalues ) ) {
		$args['attributes']['data-product_name']  = $product->get_name();
		$args['attributes']['data-product_price'] = wp_strip_all_tags( wc_price( $product->get_price() ) );
		$args['attributes']['data-product_image'] = (
			$product->get_image_id() // Check the product has an image id.
			? wp_get_attachment_image_src( $product->get_image_id(), 'thumbnail' )[0] // Get the image source.
			: wc_placeholder_img_src() // If no image, get the default placeholder image.
		);
		return $args;
	}
}

/**
 * Display the added to cart notice.
 *
 * @param string $cart_item_key – The cart item key.
 * @param int    $product_id – The product id.
 * @param int    $quantity – The quantity.
 * @param int    $variation_id – The variation id.
 * @param array  $variation – The variation.
 * @param array  $cart_item_data – The cart item data.
 */
function add__added_to_cart_notice( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
	$optionvalues = Settings\animated_parakeet_options();

	$is_product_single = ( 'product' === get_post_type( $product_id ) ) && in_array( 'productsingle', $optionvalues['display'], true );
	if ( $is_product_single ) {
		// Setup the allowed HTML so scripts and dics are rendered.
		$allowed_html = array(
			'h6'     => array(),
			'h5'     => array(),
			'a'      => array(
				'href'  => true,
				'class' => true,
			),
			'button' => array(
				'id' => true,
			),
			'div'    => array(
				'class' => true,
				'id'    => true,
				'style' => true,
			),
			'select' => array(
				'option' => true,
				'name'   => true,
			),
			'option' => array(
				'value'    => true,
				'selected' => true,
				'value'    => true,
			),
			'span'   => array(
				'class' => true,
				'id'    => true,
			),
			'p'      => array(
				'strong' => true,
			),
			'img'    => array(
				'class' => true,
				'src'   => true,
			),
		);
		// Render thep notice.
		echo wp_kses( render__product( ( $variation_id ? $variation_id : $product_id ), $optionvalues, $quantity ), $allowed_html );
	}
}

/**
 * Render the product block.
 *
 * @param int   $product_id Product ID.
 * @param array $optionvalues Option display values.
 * @param int   $quantity Product QTY being added to the cart.
 *
 * @return string $html HTML is returned.
 */
function render__product( $product_id, $optionvalues, $quantity = 1 ) {

	$position = ( isset( $optionvalues['position'] ) ? 'bottom' : 'top' );
	$layout   = ( isset( $optionvalues['layput'] ) ? 'background' : 'default' );
	$close    = apply_filters( 'filter_animated_parakeet_close', ( isset( $optionvalues['close'] ) ? $optionvalues['close'] : '10' ) );

	$product = wc_get_product( $product_id );

	$image_url = ( $product->get_image_id() // Check the product has an image id.
		? wp_get_attachment_image_src( $product->get_image_id(), 'thumbnail' )[0] // Get the image source.
		: wc_placeholder_img_src() // If no image, get the default placeholder image.
	);

	$style = '';

	if ( 'background' === $layout ) {
		$style = 'style="background-image: url(' . $image_url . ' );"';
	}

	$html  = '<div id="animated-parakeed-product-notice" class="' . $position . ' active" ' . $style . '>'; // Open .product-notice-wrapper.
	$html .= '<span id="appn-closer" class="dashicons dashicons-no"></span>';
	$html .= '<h5>' . esc_html( 'Addeded To Cart' ) . '</h5>';

		// Setup the image if it's enabled in the settings.
		if ( 'default' === $layout ) { // phpcs:ignore
		$html .= '<img class="product-image" src="' . $image_url . '" width="150" height="150"/>';
		} // phpcs:ignore
		$html .= '<div class="product-contents">'; // Open .product-contents.

			$html .= '<h6>' . esc_html( $product->get_name() ) . '</h6>';
			$html .= '<p><strong>' . esc_html__( 'Quantity' ) . ':</strong> ' . $quantity . '</p>';
			$html .= '<p><strong>' . esc_html__( 'Price' ) . ':</strong> ' . wc_price( $product->get_price() ) . '</p>';

		$html .= '</div>'; // End .product-contents.

		$html .= '<a class="button wp-block-button__link" href="' . wc_get_cart_url() . '">' . esc_html( 'View Cart' ) . '</a>';

	$html .= '</div>'; // End .product-notice-wrapper.

	return $html;
}
