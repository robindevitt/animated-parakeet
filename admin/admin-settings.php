<?php
/**
 * Create a settings page for different options.
 *
 * @package AnimatedParakeet
 */

namespace AnimatedParakeet\Settings;

/**
 * Create top level menu item.
 */
function options() {

	add_menu_page(
		__( 'Animated Parakeet Settings', 'animated-parakeet' ),
		__( 'Animated Parakeet', 'animated-parakeet' ),
		'manage_options',
		'animated-parakeet',
		__NAMESPACE__ . '\animated_parakeet_content',
		'dashicons-megaphone',
		50
	);

}

/**
 * Create settings
 */
function settings() {
	register_setting(
		'animated_parakeet_settings',
		'animated_parakeet_options',
	);
	animated_parakeet_settings();
}

/**
 * Top level menu item callback function.
 */
function animated_parakeet_content() {
	// check user capabilities.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( isset( $_GET['settings-updated'] ) ) { // phpcs:ignore
		// add settings saved message with the class of "updated".
		add_settings_error( 'animated_parakeet_message', 'animated_parakeet_message', __( 'Settings Saved', 'animated-parakeet' ), 'updated' );
	}

	// show error/update messages.
	settings_errors( 'animated_parakeet_message' );

	// Setup the allowed HTML so scripts and dics are rendered.
	$allowed_html = array(
		'div'    => array(
			'class' => true,
			'id'    => true,
		),
		'script' => array(
			'async' => true,
			'src'   => true,
			'type'  => 'text/javascript',
		),
	);

	echo '<div class="wrap">';

		echo '<h1>' . esc_html( get_admin_page_title() ) . '</h1>';

		echo '<form id="admin-animated-parakeet" class="row" action="options.php" method="post">';
			settings_fields( 'animated_parakeet_settings' );
			do_settings_sections( 'animated_parakeet_settings' );
			submit_button( 'Save Settings' );
		echo '</form>';
	echo '</div>'; // Wrap ends.
}

/**
 * Cart notice settings.
 */
function animated_parakeet_settings() {
	add_settings_section(
		'animated_parakeet_section',
		'',
		'',
		'animated_parakeet_settings'
	);

	$settings = array(
		'\animated_parakeet_layout'   => 'Layout',
		'\animated_parakeet_position' => 'Position',
		'\animated_parakeet_close'    => 'Close after (seconds)',
		'\animated_parakeet_display'  => 'Display',
	);

	foreach ( $settings as $key => $value ) {
		add_settings_field(
			$key,
			$value,
			__NAMESPACE__ . $key,
			'animated_parakeet_settings',
			'animated_parakeet_section'
		);
	}
}

/**
 * Get Animated Parakeet Options
 *
 * @param string $option Define the option to return.
 *
 * @return array
 */
function animated_parakeet_options( $option = '' ) {
	$options = get_option( 'animated_parakeet_options' );
	if ( ! empty( $option ) ) {
		if ( isset( $options[ $option ] ) ) {
			return $options[ $option ];
		} else {
			return false;
		}
	}
	return $options;
}

/**
 * Display the option for the layout setting,
 */
function animated_parakeet_layout() {
	$options = animated_parakeet_options( 'layout' );
	$checked = ( $options ? 'checked' : '' );
	echo '<div class="animated-parakeet-layout-checkbox">';
		echo '<input type="checkbox" ' . esc_attr( $checked ) . ' name="animated_parakeet_options[layout]" id="ap_layout">';
		echo '<label for="ap_layout"><span class="default">' . esc_html( 'Defaukt' ) . '</span><span class="background">' . esc_html( 'Background' ) . '</span></label>';
	echo '</div>';
}
/**
 * Display the option for the position setting,
 */
function animated_parakeet_position() {
	$options = animated_parakeet_options( 'position' );
	$checked = ( $options ? 'checked' : '' );
	echo '<div class="animated-parakeet-position-checkbox"><input type="checkbox" ' . esc_attr( $checked ) . ' name="animated_parakeet_options[position]" id="ap_position"><label for="ap_position"><span class="top"></span><span class="bottom"></span></div>';
}
/**
 * Display the option for the close setting,
 */
function animated_parakeet_close() {
	$options = animated_parakeet_options( 'close' );
	$value   = apply_filters( 'filter_animated_parakeet_close', ( $options ? $options : '10' ) );
	echo '<div class="slidecontainer">';
		echo '<input type="range" name="animated_parakeet_options[close]" min="0" max="100" value="' . esc_attr( $value ) . '" class="slider" id="animatedParakeetClose"><div id="closeDisplay"></div>';
	echo '</div>';
}
/**
 * Display the option for the display setting,
 */
function animated_parakeet_display() {

	// Setup the allowed HTML so scripts and dics are rendered.
	$allowed_html = array(
		'button' => array(
			'id' => true,
		),
		'div'    => array(
			'class' => true,
			'id'    => true,
		),
		'select' => array(
			'option' => true,
			'name'   => true,
		),
		'option' => array(
			'value'     => true,
			'selectted' => true,
			'value'     => true,
		),
		'span'   => array(
			'class' => true,
		),
	);

	$options = animated_parakeet_options( 'display' );
	echo '<div id="display-conditions">';
		if ( $options && 1 < count( $options ) ) { //phpcs:ignore
		foreach ( $options as $key => $value ) { //phpcs:ignore
			echo wp_kses( render__display_condition( $value ), $allowed_html );
			} //phpcs:ignore
		} else { //phpcs:ignore
		echo wp_kses( render__display_condition(), $allowed_html );
		} //phpcs:ignore
	echo '</div>';
	echo '<button id="add-condition">' . esc_html( 'Add Display Condition' ) . '</button>';
}

/**
 * Render the display-condition div and contents.
 *
 *  @param string $selectedvalue Selected Value.
 */
function render__display_condition( $selectedvalue = '' ) {
	$rdc  = '<div class="display-condition">'; //phpcs:ignore
		$rdc .= '<select name="animated_parakeet_options[display][]" class="condition-select">'; //phpcs:ignore
			$rdc .= render__display_condition_options( $selectedvalue );
		$rdc .= '</select>'; //phpcs:ignore
		$rdc .= '<span class="remove-condition dashicons dashicons-trash"></span>'; //phpcs:ignore
	$rdc .= '</div>'; //phpcs:ignore

	return $rdc;

}

/**
 * Render the select options.
 *
 * @param string $selectedvalue Selected Value.
 */
function render__display_condition_options( $selectedvalue ) {
	$optionarray = array(
		''                  => __( 'Please select', 'animated-parakeet' ),
		'pages'             => __( 'All Pages', 'animated-parakeet' ),
		'shoparchive'       => __( 'Shop Archive', 'animated-parakeet' ),
		'productcategories' => __( 'Product Categories', 'animated-parakeet' ),
		'producttags'       => __( 'Product Tags', 'animated-parakeet' ),
		'productattributes' => __( 'Product Attributes', 'animated-parakeet' ),
		'productsingle'     => __( 'Single Products', 'animated-parakeet' ),
	);

	$options = '';
	foreach ( $optionarray as $key => $value ) {
		$select   = ( $selectedvalue === $key ? 'selected' : '' );
		$options .= '<option ' . $select . ' value="' . $key . '">' . $value . '</option>';
	}
	return $options;
}
