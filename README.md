# Animated Parakeet
This plugin works with WooCommerce and very specifically around adding products to the cart.

When adding a product to the cart there is a notificaiton with a popup and it contians the product details.

There is an admin settings page with a few options that contain the following options:
1. Position - Top or Bottom
2. Close - Select howmany seconds until the popup closes.
3. Display Conditions - Select which pages you want the notification to show on.
4. Background and text colour
5. Button Background and text colour

There is also a filter that allows you to set the value of the "Close". This filter is with the following snippet and it can be added to your functions.php file in your theme. Using the filter will override the value that is set in the admin settings page.

In the below option we will set the filter value to be 55 ( seconds ).

```php
function animated_parakeet_close_override( $default ) {
	return 55;
}
add_filter( 'filter_animated_parakeet_close', 'animated_parakeet_close_override' );
```

## Download and Installation
1. [Download a copy of the repo](https://github.com/robindevitt/animated-parakeet/archive/refs/heads/main.zip)
2. Log in to your WordPress admin dashboard.
3. Navigate to the "Plugins" menu on the left-hand side.
4. Click on the "Add New" button at the top of the page.
5. Click on the "Upload Plugin" button at the top of the page.
6. "Choose" the file or drag and drop the zip folder you downloaded in step 1 and click on the "Install Now" buton.
7. Once installed click on the "Activate Plugin" button.
8. Navigate to the "Animated Parakeet" menu option located in the left hand menu of your dashboard.
