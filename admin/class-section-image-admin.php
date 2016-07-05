<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       clarknikdelpowell.com/agency/people/glenn
 * @since      1.0.0
 *
 * @package    Section_Image
 * @subpackage Section_Image/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Section_Image
 * @subpackage Section_Image/admin
 * @author     Glenn Welser <glenn@clarknikdelpowell.com>
 */
class Section_Image_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	public function register_media_taxonomy() {

		$taxonomy = 'media-category';

		if ( $this->has_taxonomy( $taxonomy ) ) {
			return;
		}

		$proper_name = ucwords( str_replace( '-', ' ', $taxonomy ) );
		$object_type = 'attachment';
		$labels      = array(
			'name'                  => $proper_name,
			'singular_name'         => $proper_name,
			'menu_name'             => $proper_name,
			'all_items'             => 'All ' . $proper_name,
			'edit_item'             => 'Edit ' . $proper_name,
			'view_item'             => 'View ' . $proper_name,
			'update_item'           => 'Update ' . $proper_name,
			'add_new_item'          => 'Add New ' . $proper_name,
			'new_item_name'         => 'New ' . $proper_name . ' Name',
			'search_items'          => 'Search ' . $proper_name,
			'popular_items'         => 'Popular ' . $proper_name,
			'add_or_remove_items'   => 'Add or Remove ' . $proper_name,
			'choose_from_most_used' => 'Most Used ' . $proper_name,
			'not_found'             => 'No ' . $proper_name . ' Found',
		);
		$args        = array(
			'public'                => true,
			'show_ui'               => true,
			'show_in_nav_menus'     => true,
			'show_tagcloud'         => true,
			'meta_box_cb'           => null,
			'show_admin_column'     => true,
			'hierarchical'          => false,
			'update_count_callback' => null,
			'rewrite'               => true,
			'sort'                  => null,
			'labels'                => $labels,
		);

		register_taxonomy( $taxonomy, $object_type, $args );

	}

	private function has_taxonomy( $slug ) {

		$tax = get_taxonomy( $slug );
		if ( ! $tax ) {
			return false;
		}

		return true;
	}
}
