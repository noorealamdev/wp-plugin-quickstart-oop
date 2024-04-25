<?php
namespace Chakri_Job_Board\Classes;

if ( ! defined('ABSPATH') ) exit; // Exit if accessed directly

class Utils {
	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_filter( 'display_post_states', [$this, 'add_display_post_states'], 10, 2 );
		add_action('wp_head', [$this, 'global_dynamic_css']);
	}

	/**
	 * Add a post display state for Chakri pages in the page list table.
	 *
	 * @param array   $post_states An array of post display states.
	 * @param WP_Post $post        The current post object.
	 */
	public function add_display_post_states( $post_states, $post ) {
		$jobs_page_check     = \Chakri_Utils::get_page_by_title('All Jobs');
		$company_page_check  = \Chakri_Utils::get_page_by_title('Company');

		if ( $jobs_page_check && $jobs_page_check->ID === $post->ID ) {
			$post_states['chakri_page_for_jobs'] = __( 'Jobs Page', 'chakri' );
		}

		return $post_states;
	}

	/**
	 * Get page by title
	 */
	public static function get_page_by_title( $page_title, $output = OBJECT, $post_type = 'page' ) {
		global $wpdb;
		if ( is_array( $post_type ) ) {
			$post_type           = esc_sql( $post_type );
			$post_type_in_string = "'" . implode( "','", $post_type ) . "'";
			$sql                 = $wpdb->prepare(
				"
			            SELECT ID
			            FROM $wpdb->posts
			            WHERE post_title = %s
			            AND post_type IN ($post_type_in_string)
			        ",
				$page_title
			);
		} else {
			$sql = $wpdb->prepare(
				"
			            SELECT ID
			            FROM $wpdb->posts
			            WHERE post_title = %s
			            AND post_type = %s
			        ",
				$page_title,
				$post_type
			);
		}

		$page = $wpdb->get_var( $sql );

		if ( $page ) {
			return get_post( $page, $output );
		}

		return null;
	}


	/**
	 * Get current user role
	 * 'administrator'
	 */
	public static function current_user_role() {
		if(is_user_logged_in()) {
			$user = wp_get_current_user();
			$role = (array) $user->roles;
			return $role[0];
		}
		else {
			return false;
		}
	}


	/**
	 * Get uploaded image from 'hrm' directory and database
	 */
	public static function get_uploaded_image($file_name): string {
		$upload_dir = wp_upload_dir();
		return $upload_dir['baseurl'] . '/hrm/' . $file_name . '';
	}

	/**
	 * Collect system data to track.
	 *
	 * @return array
	 */
	public static function get_system_data() {
		global $wp_version;

		/**
		 * Current active theme.
		 *
		 * @var WP_Theme $theme
		 */
		$theme = wp_get_theme();

		$system_data                         = self::get_base_system_data();
		$system_data['wp_version']           = $wp_version;
		$system_data['php_version']          = PHP_VERSION;
		$system_data['locale']               = get_locale();
		$system_data['multisite']            = is_multisite() ? 1 : 0;
		$system_data['active_theme']         = $theme['Name'];
		$system_data['active_theme_version'] = $theme['Version'];

		$plugin_data = self::get_plugin_data();
		foreach ( $plugin_data as $plugin_name => $plugin_version ) {
			$plugin_friendly_name       = preg_replace( '/[^a-z0-9]/', '_', $plugin_name );
			$plugin_key                 = 'plugin_' . $plugin_friendly_name;
			$system_data[ $plugin_key ] = $plugin_version;
		}

		return $system_data;
	}

	/**
	 * Gets a list of activated plugins.
	 *
	 * @return array List of plugins. Index is friendly name, value is version.
	 */
	protected static function get_plugin_data() {
		$plugins = [];
		foreach ( self::get_plugins() as $plugin_basename => $plugin ) {
			$plugin_name             = self::get_plugin_name( $plugin_basename );
			$plugins[ $plugin_name ] = $plugin['Version'];
		}

		return $plugins;
	}

	/**
	 * Partial wrapper for for `get_plugins()` function. Filters out non-active plugins.
	 *
	 * @return array Key is the plugin file path and the value is an array of the plugin data.
	 */
	protected static function get_plugins() {
		if ( ! function_exists( 'get_plugins' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$plugins = get_plugins();
		foreach ( $plugins as $plugin_basename => $plugin_data ) {
			if ( ! is_plugin_active( $plugin_basename ) ) {
				unset( $plugins[ $plugin_basename ] );
			}
		}

		return $plugins;
	}

	/**
	 * Returns a friendly slug for a plugin.
	 *
	 * @param string $basename Plugin basename.
	 *
	 * @return string
	 */
	private static function get_plugin_name( $basename ) {
		$basename = strtolower( $basename );
		if ( false === strpos( $basename, '/' ) ) {
			return basename( $basename, '.php' );
		}

		return dirname( $basename );
	}

	/**
	 * Gets the base data returned with system information.
	 *
	 * @return array
	 */
	protected static function get_base_system_data() {
		return [];
	}


	public function global_dynamic_css() {
		$css  = '';
		$css .= '<style>
                        :root {
                          --body: '.get_theme_mod( 'bg_color', '#5e8fe4' ).';
                          --black: #000;
                          --white: #fff;
                          --theme: #001659;
                          --theme2: #FF5E15;
                          --header: #001659;
                          --base: #001659;
                          --text: #666;
                          --text2: #CFCFCF;
                          --border: #C5C5C5;
                          --border2: #E8E8E9;
                          --button: #1C2539;
                          --button2: #030734;
                          --ratting: #FF9F0D;
                          --bg: #F2F3F5;
                          --bg2: #DF0A0A0D;
                        }
                    </style>';

		echo $css;
	}



}