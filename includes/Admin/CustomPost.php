<?php

namespace Chakri_Job_Board\Admin;

defined( 'ABSPATH' ) || exit;

class CustomPost {
	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_action( 'init', [$this, 'chakri_custom_post_type_register'] );
		add_filter( 'manage_chakri_listing_posts_columns', [$this, 'set_custom_chakri_listing_columns'] );
		add_action( 'manage_chakri_listing_posts_custom_column' , [$this, 'custom_chakri_listing_column_data'], 10, 2 );
	}

	public function chakri_custom_post_type_register() {

		// Set UI labels for Custom Post Type
		$labels = array(
			'name'          => __( 'Chakri Job Board', 'chakri' ),
			'all_items'     => __( 'All Jobs', 'chakri' ),
			'singular_name' => __( 'Job', 'chakri' ),
			'add_new_item'  => __( 'Add New Job', 'twentytwentyone' ),
			'add_new'       => __( 'Add New', 'twentytwentyone' ),
			'edit_item'     => __( 'Edit Job', 'twentytwentyone' ),
			'update_item'   => __( 'Update Job', 'twentytwentyone' ),
			'search_items'  => __( 'Search Job', 'twentytwentyone' ),
			'not_found'     => __( 'No Job Found', 'twentytwentyone' ),
		);

		// Set other options for Custom Post Type
		$args = array(
			'labels'              => $labels,
			// Features this CPT supports in Post Editor
			'supports'            => array( 'title', 'editor' ),
			// You can associate this CPT with a taxonomy or custom taxonomy.
			'taxonomies'          => array( 'Job Types' ),
			/* A hierarchical CPT is like Pages and can have
			* Parent and child items. A non-hierarchical CPT
			* is like Posts.
			*/
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'menu_icon'           => 'dashicons-book',
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'show_in_rest'        => true,
			'rewrite'             => array('slug' => 'chakri'),

		);

		register_post_type( 'chakri_listing', $args);

		register_taxonomy('chakri_listing_type', ['chakri_listing'], [
			'label' => __('Job Type', 'chakri'),
			'hierarchical' => true,
			'rewrite' => ['slug' => 'chakri-type'],
			'show_admin_column' => true,
			'show_in_rest' => true,
			'labels' => [
				'singular_name'     => __('Job Type', 'chakri'),
				'all_items'         => __('All Types', 'chakri'),
				'edit_item'         => __('Edit Type', 'chakri'),
				'view_item'         => __('View Type', 'chakri'),
				'update_item'       => __('Update Type', 'chakri'),
				'add_new_item'      => __('Add New Type', 'chakri'),
				'new_item_name'     => __('New Type Name', 'chakri'),
				'search_items'      => __('Search Types', 'chakri'),
				'parent_item'       => __('Parent Type', 'chakri'),
				'parent_item_colon' => __('Parent Type:', 'chakri'),
				'not_found'         => __('No Types found', 'chakri'),
			]
		]);

	}

	/**
	 * Add the custom columns to the chakri_listing post type
	 */
	public function set_custom_chakri_listing_columns($columns) {

		if ( ! is_array( $columns ) ) {
			$columns = [];
		}

		unset( $columns['date'], $columns['author'] );

		$columns['job_posted']      = __( 'Date', 'chakri' );
		$columns['job_expires']     = __( 'Expires', 'chakri' );
		$columns['job_status']      = __( 'Status', 'chakri' );
		$columns['job_featured']    = __( 'Featured', 'chakri' );

		return $columns;
	}

	/**
	 * Add the data to the custom columns for the chakri_listing post type
	 */
	public function custom_chakri_listing_column_data( $column, $post_id ) {
		switch ( $column ) {

			case 'job_type' :
				$terms = get_the_term_list( $post_id , 'chakri_listing_type' , '' , ',' , '' );
				if ( is_string( $terms ) )
					echo $terms;
				else
					_e( 'Unable to get author(s)', 'chakri' );
				break;

			case 'publisher' :
				echo get_post_meta( $post_id , 'publisher' , true );
				break;

		}
	}



}