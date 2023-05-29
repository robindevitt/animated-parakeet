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
		plugin_dir_url( __FILE__ ) . ( '../assets/images/moretyme-icon.png' ),
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
		array(
			'type'              => 'string',
			'sanitize_callback' => 'validate_options',
		)
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

		echo '<form class="row" action="options.php" method="post">';
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
 *
 *
 */
function animated_parakeet_layout() {

}
/**
 *
 */
function animated_parakeet_position() {

}
/**
 *
 */
function animated_parakeet_close() {
	$options = animated_parakeet_options( 'close' );
	$value   = ( $options ? $options : '10' );
	echo '<div class="slidecontainer">';
		echo '<input type="range" name="animated_parakeet_options[close]" min="0" max="100" value="' . esc_attr( $value ) . '" class="slider" id="animatedParakeetClose"><div id="closeDisplay"></div>';
	echo '</div>';
}
/**
 *
 */
function animated_parakeet_display() {

}
