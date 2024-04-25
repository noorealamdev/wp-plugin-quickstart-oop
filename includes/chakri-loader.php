<?php
/**
 * Chakri Job Board Loader.
 * @package Chakri
 */

defined( 'ABSPATH' ) || exit;

class Chakri_Loader {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action('plugins_loaded', [$this, 'plugins_loaded']);
	}


	/**
	 * Do all things after the plugins loaded.
	 * @return void
	 */
	public function plugins_loaded(): void {

		$this->load_text_domain();

		\Carbon_Fields\Carbon_Fields::boot();

		\Chakri_Job_Board\Admin\CustomPost::instance();
		\Chakri_Job_Board\Admin\CustomMetaBox::instance();
		\Chakri_Job_Board\Classes\Enqueue::instance();
		\Chakri_Job_Board\Classes\Utils::instance();

	}

	/**
	 * Load plugin text domain.
	 */
	private function load_text_domain(): void {
		load_plugin_textdomain( 'chakri', false, CHAKRI_DIR . '/languages' );
	}

}

// Chakri_Loader Instance
if ( class_exists('Chakri_Loader') ) {
	new Chakri_Loader();
}