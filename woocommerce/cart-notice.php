<?php
/**
 * WooCommerce related functionality for Animated Parakeet.
 *
 * @package AnimatedParakeet
 */

namespace AnimatedParakeet\WooCommerce;

// add_action( 'woocommerce_add_to_cart', __NAMESPACE__ . '\add__added_to_cart_notice', 999, 6 );
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
	$args['attributes']['data-product_name']  = $product->get_name();
	$args['attributes']['data-product_price'] = wp_strip_all_tags( wc_price( $product->get_price() ) );
	$args['attributes']['data-product_image'] = (
		$product->get_image_id() // Check the product has an image id.
		? wp_get_attachment_image_src( $product->get_image_id(), 'thumbnail' )[0] // Get the image source.
		: wc_placeholder_img_src() // If no image, get the default placeholder image.
	);
	return $args;
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
	// Trigger the AJAX request.
	wp_remote_post(
		admin_url( 'admin-ajax.php' ),
		array(
			'method'      => 'POST',
			'timeout'     => 5,
			'redirection' => 0,
			'httpversion' => '1.0',
			'blocking'    => false,
			'headers'     => array(),
			'body'        => array( 'action' => 'add_added_to_cart_notice' ),
			'cookies'     => array(),
		)
	);
}

/**
 * Render the product block.
 *
 * @return string $html HTML is returned.
 */
function render__product( $product ) {
	$product = wc_get_product( $product );

	$layout = 'image';

	$html = '<div id="animated-parakeed-product-notice">'; // Open .product-notice-wrapper.

	$html .= '<h5>' . esc_html__( 'Addeded To Cart', 'animated-parakeet' ) . '</h5>';

		// Setup the image if it's enabled in the settings.
		if ( 'image' === $layout ) { // phpcs:ignore

		$html .= '<img class="product-image" src="' . $product->get_image() . '" width="150" height="150"/>';
		} // phpcs:ignore
		$html .= '<div class="product-contents">'; // Open .product-contents.

			$html .= '<h6>' . esc_html( $product->get_name() ) . '</h6>';
			$html .= '<p><strong>' . esc_html__( 'Price' ) . '</strong>' . wc_price( $product->get_price() ) . '</p>';

		$html .= '</div>'; // End .product-contents.

		$html .= '<a class="button" href="' . wc_get_endpoint_url( 'cart' ) . '">' . esc_html( 'View Cart' ) . '</a>';

	$html .= '</div>'; // End .product-notice-wrapper.

	return $html;
}
