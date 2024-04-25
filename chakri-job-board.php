<?php
/**
 * Plugin Name: Chakri - Job Board
 * Description: Job board plugin for wordpress
 * Plugin URI: https://noor-e-alam.com
 * Version: 1.0.0
 * Author URI: https://noor-e-alam.com
 * Requires at least: 5.7
 * Requires PHP: 7.4
 * Text Domain: chakri
 * Domain Path: /languages/
 * License: GPL2+
 */


use Chakri_Job_Board\Admin\Notice;

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/vendor/autoload.php';

if ( ! class_exists( 'Chakri_Job_Board' ) ) {

	/**
	 * Chakri_Job_Board class.
	 *
	 * @class Main class of the plugin.
	 */
	final class Chakri_Job_Board {
		private static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}


		/**
		 * Constructor
		 */
		public function __construct() {
			// Define Constants
			$this->define_constants();

			if ( ! version_compare(CHAKRI_WP_VERSION, '5.8', '>=' ) ) {
				add_action('admin_notices', [Notice::class, 'check_wp_version']);
			} elseif ( ! version_compare(CHAKRI_PHP_VERSION, '7.4', '>=' ) ) {
				add_action('admin_notices', [Notice::class, 'check_php_version']);
			} else {
				$this->includes();
			}
		}

		/**
		 * Define the plugin constants.
		 */
		private function define_constants() {
			define( 'CHAKRI_VER', '1.0.0' );
			define( 'CHAKRI_BASENAME', plugin_basename( __FILE__ ) );
			define( 'CHAKRI_DIR', plugin_dir_path( __FILE__ ) );
			define( 'CHAKRI_URL', plugin_dir_url( __FILE__ ) );
			define( 'CHAKRI_WP_VERSION', (float) get_bloginfo('version'));
			define( 'CHAKRI_PHP_VERSION', (float) phpversion());
		}

		/**
		 * Include the required files.
		 */
		private function includes() {
            require_once CHAKRI_DIR . '/includes/chakri-loader.php';
		}


	}

	/**
	 * Initialize the Chakri Job Board
	 */
	Chakri_Job_Board::instance();
}

