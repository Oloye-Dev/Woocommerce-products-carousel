<?php
/**
 * Plugin Name: WooCommerce Products Carousel
 * Plugin URI:  https://example.com/woocommerce-products-carousel
 * Description: A lightweight plugin that adds a Splide.js-powered product carousel shortcode for WooCommerce products, with customizable admin settings.
 * Version:     1.0.0
 * Author:      Murital M. Oshiomole
 * Author URI:  wpsaul.com
 * Text Domain: woocommerce-products-carousel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants
define( 'WPC_VERSION', '1.0.0' );
define( 'WPC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include the shortcode file
require_once WPC_PLUGIN_DIR . 'includes/shortcode.php';

/**
 * Enqueue frontend assets
 */
function wpc_enqueue_front_assets() {
	// Splide CSS and JS
	wp_enqueue_style( 'splide-css', WPC_PLUGIN_URL . 'assets/splide/splide.min.css', array(), '4.1.3' );
	wp_enqueue_script( 'splide-js', WPC_PLUGIN_URL . 'assets/splide/splide.min.js', array(),
	 '4.1.3', true );
	 wp_enqueue_script( 'splide-js', WPC_PLUGIN_URL . 'assets/splide/autoscroll.min.js', array(),
	 '4.1.3', true );

	// Frontend CSS
	wp_enqueue_style( 'wpc-frontend-css', WPC_PLUGIN_URL . 'assets/css/wpc-frontend.css', array(), WPC_VERSION );

	// Frontend JS
	wp_enqueue_script( 'wpc-frontend', WPC_PLUGIN_URL . 'assets/js/wpc-frontend.js', array( 'splide-js', 'jquery' ), WPC_VERSION, true );

	// Get saved settings
	$options = get_option( 'wpc_splide_settings', array(
		'perPage'    => 4,
		'perMove'    => 1,
		'autoplay'   => false,
		'arrows'     => true,
		'pagination' => false,
		'loop'       => true,
		'gap'        => '1rem',
	) );

	// Pass settings to JS
	wp_localize_script( 'wpc-frontend', 'wpcSplideSettings', $options );
}
add_action( 'wp_enqueue_scripts', 'wpc_enqueue_front_assets' );

/**
 * Register settings
 */
function wpc_register_settings() {

	register_setting( 'wpc_settings_group', 'wpc_splide_settings', array(
		'type'              => 'array',
		'sanitize_callback' => 'wpc_sanitize_splide_settings',
		'default'           => array(
			'perPage'    => 4,
			'perMove'    => 1,
			'autoplay'   => false,
			'arrows'     => true,
			'pagination' => false,
			'loop'       => true,
			'gap'        => '1rem',
		),
	) );

	add_settings_section(
		'wpc_main_section',
		__( 'Splide Carousel Settings', 'woocommerce-products-carousel' ),
		function() {
			echo '<p>' . esc_html__( 'Adjust the default Splide.js options used for product carousels.', 'woocommerce-products-carousel' ) . '</p>';
		},
		'wpc-settings-page'
	);

	add_settings_field(
		'wpc_perPage',
		__( 'Products per Slide', 'woocommerce-products-carousel' ),
		'wpc_field_perPage_callback',
		'wpc-settings-page',
		'wpc_main_section'
	);

	add_settings_field(
		'wpc_autoplay',
		__( 'Enable Autoplay', 'woocommerce-products-carousel' ),
		'wpc_field_autoplay_callback',
		'wpc-settings-page',
		'wpc_main_section'
	);

	add_settings_field(
		'wpc_arrows',
		__( 'Show Arrows', 'woocommerce-products-carousel' ),
		'wpc_field_arrows_callback',
		'wpc-settings-page',
		'wpc_main_section'
	);

	add_settings_field(
		'wpc_pagination',
		__( 'Show Pagination', 'woocommerce-products-carousel' ),
		'wpc_field_pagination_callback',
		'wpc-settings-page',
		'wpc_main_section'
	);

	add_settings_field(
		'wpc_gap',
		__( 'Gap Between Items', 'woocommerce-products-carousel' ),
		'wpc_field_gap_callback',
		'wpc-settings-page',
		'wpc_main_section'
	);
}
add_action( 'admin_init', 'wpc_register_settings' );

/**
 * Sanitize settings
 */
function wpc_sanitize_splide_settings( $input ) {
	return array(
		'perPage'    => absint( $input['perPage'] ?? 4 ),
		'perPage'    => absint( $input['perMove'] ?? 1 ),
		'autoplay'   => ! empty( $input['autoplay'] ),
		'arrows'     => ! empty( $input['arrows'] ),
		'pagination' => ! empty( $input['pagination'] ),
		'loop'       => true,
		'gap'        => sanitize_text_field( $input['gap'] ?? '1rem' ),
	);
}

/**
 * Field Callbacks
 */
function wpc_field_perPage_callback() {
	$options = get_option( 'wpc_splide_settings' );
	$value = isset( $options['perPage'] ) ? intval( $options['perPage'] ) : 4;
	echo '<input type="number" name="wpc_splide_settings[perPage]" value="' . esc_attr( $value ) . '" min="1" max="10" />';
}

function wpc_field_autoplay_callback() {
	$options = get_option( 'wpc_splide_settings' );
	$checked = ! empty( $options['autoplay'] ) ? 'checked' : '';
	echo '<label><input type="checkbox" name="wpc_splide_settings[autoplay]" ' . $checked . '> ' . esc_html__( 'Enable automatic sliding', 'woocommerce-products-carousel' ) . '</label>';
}

function wpc_field_arrows_callback() {
	$options = get_option( 'wpc_splide_settings' );
	$checked = ! empty( $options['arrows'] ) ? 'checked' : '';
	echo '<label><input type="checkbox" name="wpc_splide_settings[arrows]" ' . $checked . '> ' . esc_html__( 'Display navigation arrows', 'woocommerce-products-carousel' ) . '</label>';
}

function wpc_field_pagination_callback() {
	$options = get_option( 'wpc_splide_settings' );
	$checked = ! empty( $options['pagination'] ) ? 'checked' : '';
	echo '<label><input type="checkbox" name="wpc_splide_settings[pagination]" ' . $checked . '> ' . esc_html__( 'Show pagination dots', 'woocommerce-products-carousel' ) . '</label>';
}

function wpc_field_gap_callback() {
	$options = get_option( 'wpc_splide_settings' );
	$value = isset( $options['gap'] ) ? esc_attr( $options['gap'] ) : '1rem';
	echo '<input type="text" name="wpc_splide_settings[gap]" value="' . $value . '" placeholder="e.g. 1rem or 10px" />';
}

/**
 * Settings Page Output
 */
function wpc_settings_page_callback() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'WooCommerce Products Carousel Settings', 'woocommerce-products-carousel' ); ?></h1>
		<form method="post" action="options.php">
			<?php
				settings_fields( 'wpc_settings_group' );
				do_settings_sections( 'wpc-settings-page' );
				submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Add Admin Menu & Styles
 */
add_action( 'admin_menu', function() {
	add_menu_page(
		__( 'Products Carousel', 'woocommerce-products-carousel' ),
		__( 'Products Carousel', 'woocommerce-products-carousel' ),
		'manage_options',
		'wpc-settings-page',
		'wpc_settings_page_callback',
		'dashicons-images-alt2',
		58
	);
});

function wpc_enqueue_admin_assets( $hook ) {
	if ( $hook === 'toplevel_page_wpc-settings-page' ) {
		wp_enqueue_style( 'wpc-admin-css', WPC_PLUGIN_URL . 'assets/css/wpc-admin.css', array(), WPC_VERSION );
	}
}
add_action( 'admin_enqueue_scripts', 'wpc_enqueue_admin_assets' );
