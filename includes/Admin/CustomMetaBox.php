<?php

namespace Chakri_Job_Board\Admin;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

defined( 'ABSPATH' ) || exit;

class CustomMetaBox {
	protected static $instance = null;

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
		$this->chakri_listing_custom_post_metabox();
	}

	private function chakri_listing_custom_post_metabox() {
		Container::make( 'post_meta', 'Custom Data' )
		         ->where( 'post_type', '=', 'chakri_listing' )
		         ->add_fields( array(
			         Field::make( 'map', 'crb_location' )
			              ->set_position( 37.423156, -122.084917, 14 ),
			         Field::make( 'sidebar', 'crb_custom_sidebar' ),
			         Field::make( 'image', 'crb_photo' ),
		         ));
	}


}