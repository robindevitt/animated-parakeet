jQuery(document).ready(function ($) {
	// Make use of WooCommerce 'added_to_cart' event for ajax events.
	$(document).on('added_to_cart', function (event, fragments, cart_hash, button) {
		// Remove existing notices.
		$('#animated-parakeed-product-notice').remove();

		// Setup the variables.
		var productTitle = button.data('product_name');
		var productPrice = button.data('product_price');
		var productImage = button.data('product_image');

		if (apvars.layput = 'background') {
			// Add the html for the background layput.
			var style = 'style="background-image: url(' + productImage + ' );"';
			$('body').append('<div id="animated-parakeed-product-notice" ' + style + ' class="' + apvars.position + '"><div id="appn-overlay"></div><span id="appn-closer" class="dashicons dashicons-no"></span><h5>Addeded To Cart</h5><div class="product-contents"><h6>' + productTitle + '</h6><p><strong>Price:</strong> ' + productPrice + '</p></div><a class="button" href="' + apvars.woo_cart_url + '">View Cart</a></div>');
		} else {
			// Add the html for the default layput.
			$('body').append('<div id="animated-parakeed-product-notice" class="' + apvars.position + '"><span id="appn-closer" class="dashicons dashicons-no"></span><h5>Addeded To Cart</h5><img src="' + productImage + '"/><div class="product-contents"><h6>' + productTitle + '</h6><p><strong>Price:</strong> ' + productPrice + '</p></div><a class="button" href="' + apvars.woo_cart_url + '">View Cart</a></div>');
		}

		// Show the popup.
		setTimeout(function () {
			$('#animated-parakeed-product-notice').addClass("active")
		}, 10);

		// Remove the banner after the time specified by the user.
		setTimeout(function () {
			if ($('#animated-parakeed-product-notice').length > 0) {
				$('#animated-parakeed-product-notice').remove();
			}
		}, ( apvars.close * 1000 ) );

	});
});

// Hide the popup when the user closes it.
jQuery(document).on('click', '#appn-closer', function ($) {
	jQuery('#animated-parakeed-product-notice').removeClass('active');
});
