<?php
namespace Chakri_Job_Board\Admin;

defined( 'ABSPATH' ) || exit;

class Notice {

	public function __construct() {
		//
	}

	/**
	 * PHP Version Related Admin Notice
	 */
	public static function check_php_version() {
		$message = sprintf(
			'Chakri Job Board requires minimum PHP version %s where your current PHP version is %2s. Please update your PHP version.',
			'7.4',
			CHAKRI_PHP_VERSION
		);
		$html_message = sprintf('<div class="error">%s</div>', wpautop($message));
		echo wp_kses_post($html_message);
	}

	/**
	 * WordPress Version Related Admin Notice
	 */
	public static function check_wp_version() {
		$message = sprintf(
			'Chakri Job Board requires minimum WordPress version %s where your current WordPress version is %2s. Please update your WordPress version.',
			'5.8',
			CHAKRI_WP_VERSION
		);
		$html_message = sprintf('<div class="error">%s</div>', wpautop($message));
		echo wp_kses_post($html_message);
	}
}