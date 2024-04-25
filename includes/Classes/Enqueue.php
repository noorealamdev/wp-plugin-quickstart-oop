<?php
namespace Chakri_Job_Board\Classes;

defined( 'ABSPATH' ) || exit;

class Enqueue {
	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	public function __construct() {
		add_action( 'admin_enqueue_scripts', [$this, 'chakri_admin_enqueue_scripts'] );
		add_action( 'wp_enqueue_scripts', [$this, 'chakri_enqueue_scripts'] );
	}

	public function chakri_admin_enqueue_scripts() {
		// CSS
		wp_enqueue_style('chakri-admin-style', CHAKRI_URL . 'assets/admin/admin.css' );

		// JS
		wp_enqueue_script('chakri-admin-script', CHAKRI_URL . 'assets/admin/admin.js', array('jquery'), '1.0', true);
		wp_localize_script( 'chakri-admin-script', 'chakriAdminAjax', array('ajaxurl' => admin_url( 'admin-ajax.php' )));
	}


	public function chakri_enqueue_scripts() {
		// CSS
		wp_enqueue_style('chakri-style', CHAKRI_URL . 'assets/css/chakri.css' );

		// JS
		wp_enqueue_script('chakri-script', CHAKRI_URL . 'assets/js/chakri.js', array('jquery'), '1.0', true);
	}

}