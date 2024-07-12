<?php
/**
 * Plugin Name: Woo Min Price
 * Version: 0.1.0
 * Author: The WordPress Contributors
 * Author URI: https://woo.com
 * Text Domain: woo-min-price
 * Domain Path: /languages
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package extension
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'MAIN_PLUGIN_FILE' ) ) {
	define( 'MAIN_PLUGIN_FILE', __FILE__ );
}

// automatically include all extension PHP files from src
$src_files = glob(plugin_dir_path(__FILE__) . 'src/*.php');
foreach ($src_files as $src_file) {
    require_once $src_file;
}

require_once plugin_dir_path( __FILE__ ) . '/vendor/autoload_packages.php';

use WooMinPrice\Admin\Setup;

// phpcs:disable WordPress.Files.FileName

/**
 * WooCommerce fallback notice.
 *
 * @since 0.1.0
 */
function woo_min_price_missing_wc_notice() {
	/* translators: %s WC download URL link. */
	echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'Woo Min Price requires WooCommerce to be installed and active. You can download %s here.', 'woo_min_price' ), '<a href="https://woo.com/" target="_blank">WooCommerce</a>' ) . '</strong></p></div>';
}

register_activation_hook( __FILE__, 'woo_min_price_activate' );

/**
 * Activation hook.
 *
 * @since 0.1.0
 */
function woo_min_price_activate() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', 'woo_min_price_missing_wc_notice' );
		return;
	}
}

if ( ! class_exists( 'woo_min_price' ) ) :
	/**
	 * The woo_min_price class.
	 */
	class woo_min_price {
		/**
		 * This class instance.
		 *
		 * @var \woo_min_price single instance of this class.
		 */
		private static $instance;

		/**
		 * Constructor.
		 */
		public function __construct() {
			if ( is_admin() ) {
				new Setup();
			}
		}

		/**
		 * Cloning is forbidden.
		 */
		public function __clone() {
			wc_doing_it_wrong( __FUNCTION__, __( 'Cloning is forbidden.', 'woo_min_price' ), $this->version );
		}

		/**
		 * Unserializing instances of this class is forbidden.
		 */
		public function __wakeup() {
			wc_doing_it_wrong( __FUNCTION__, __( 'Unserializing instances of this class is forbidden.', 'woo_min_price' ), $this->version );
		}

		/**
		 * Gets the main instance.
		 *
		 * Ensures only one instance can be loaded.
		 *
		 * @return \woo_min_price
		 */
		public static function instance() {

			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}
endif;

add_action( 'plugins_loaded', 'woo_min_price_init', 10 );

/**
 * Initialize the plugin.
 *
 * @since 0.1.0
 */
function woo_min_price_init() {
	load_plugin_textdomain( 'woo_min_price', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', 'woo_min_price_missing_wc_notice' );
		return;
	}

	woo_min_price::instance();

}
