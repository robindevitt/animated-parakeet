<?php
/**
 * WooCommerce related functionality for Animated Parakeet.
 *
 * @package AnimatedParakeet
 */

namespace AnimatedParakeet\WooCommerce;

add_action( 'woocommerce_add_to_cart', __NAMESPACE__ . '\add__added_to_cart_notice', 100, 6 );

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
	// TODO! Check if the condition is met to shoW!!

	// Get the product add to the cart.
	$product = wc_get_product( $product_id );

	render__product( $product );

	// remove the id and content

	// re-add the content

	wp_add_inline_script(
		'animated-parakeet-fe-script',
		'const APFE = ' . wp_json_encode(
			array(
				'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
				'productName' => $product->get_name(),
			)
		),
		'before'
	);

};

/**
 * Render the product block.
 *
 * @return string $html HTML is returned.
 */

function render__product( $product ) {
	$product = wc_get_product( $product );

	$prouct_image = ( $product->get_image_id() ) ? $product->get_image_id() : '';

	$layout = 'image';

	$html = '<div id="animated-parakeed-product-notice">'; // Open .product-notice-wrapper.

	$html .= '<h5>' . esc_html__( 'Addeded To Cart', 'animated-parakeet' ) . '</h5>';

	// Setup the image if it's enabled in the settings.
	if ( 'image' === $layout ) {
		$image = wp_get_attachment_image_src( $prouct_image, 'thumbnail' );

		$html .= '<img class="product-image" src="' . $image[0] . '" width="' . esc_attr( $image[1] ) . '" height="' . esc_attr( $image[2] ) . '"/>';
	}
		$html .= '<div class="product-contents">'; // Open .product-contents.

			$html .= '<h6>' . esc_html( $product->get_name() ) . '</h6>';
			$html .= '<p><strong>' . esc_html__( 'Price' ) . '</strong>' . wc_price( $product->get_price() ) . '</p>';

		$html .= '</div>'; // Close .product-contents.

		$html .= '<a class="button" href="' . wc_get_endpoint_url( 'cart' ) . '">' . esc_html( 'View Cart' ) . '</a>';

	$html .= '</div>'; // Close .product-notice-wrapper.

	error_log( '### html ' . print_r( $html, true ) );
}
