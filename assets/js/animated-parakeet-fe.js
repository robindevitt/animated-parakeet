jQuery(document).ready(function ($) {
	// Make use of WooCommerce 'added_to_cart' event for ajax events.
	$(document).on('added_to_cart', function (event, fragments, cart_hash, button) {
		// Remove existing notices.
		$('#animated-parakeed-product-notice').remove();

		// Setup the variables.
		var productTitle = button.data('product_name');
		var productPrice = button.data('product_price');
		var productImage = button.data('product_image');

		// Add the html.
		$('body').append('<div id="animated-parakeed-product-notice"><span id="appn-closer" class="dashicons dashicons-no"></span><h5>Addeded To Cart</h5>' + productImage + '<div class="product-contents"><h6>' + productTitle + '</h6><p><strong>Price:</strong> ' + productPrice + '</p></div><a class="button" href="' + apvars.woo_cart_url + '">View Cart</a></div>');

		// Show the popup.
		setTimeout(function () {
			$('#animated-parakeed-product-notice').addClass("active")
		}, 100);
	});
});

// Hide the popup when the user closes it.
jQuery(document).on('click', '#appn-closer', function ($) {
	jQuery('#animated-parakeed-product-notice').removeClass('active');
});
