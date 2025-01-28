// Make use of WooCommerce 'adding_to_cart' event for ajax events.
jQuery(document).on('adding_to_cart', function (event, fragments, product ) {
	// Remove existing notices.
	jQuery('#animated-parakeed-product-notice').remove();

	// Setup the variables.
	var productTitle = product.product_name;
	var productPrice = product.product_price;
	var productImage = product.product_image;

	// Add the html.
	jQuery('body').append('<div id="animated-parakeed-product-notice" class="' + apvars.position + '"><span id="appn-closer" class="dashicons dashicons-no"></span><h5>Added To Cart</h5><img src="' + productImage + '"/><div class="product-contents"><h6>' + productTitle + '</h6><p><strong>Price:</strong> ' + productPrice + '</p></div><a class="button wp-block-button__link" href="' + apvars.woo_cart_url + '">View Cart</a></div>');

	// Show the popup.
	setTimeout(function () {
		jQuery('#animated-parakeed-product-notice').addClass("active")
	}, 10);

	// Remove the banner after the time specified by the user.
	setTimeout(function () {
		if (jQuery('#animated-parakeed-product-notice').length > 0) {
			jQuery('#animated-parakeed-product-notice').remove();
		}
	}, ( apvars.close * 1000 ) );

});

// Hide the popup when the user closes it.
jQuery(document).on('click', '#appn-closer', function () {
	jQuery("#animated-parakeed-product-notice").removeClass("active");
});
