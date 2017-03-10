<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       clarknikdelpowell.com/agency/people/glenn
 * @since      1.0.0
 *
 * @package    Section_Image
 * @subpackage Section_Image/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Section_Image
 * @subpackage Section_Image/includes
 * @author     Glenn Welser <glenn@clarknikdelpowell.com>
 */
class Section_Image {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Section_Image_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'section-image';
		$this->version     = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Section_Image_Loader. Orchestrates the hooks of the plugin.
	 * - Section_Image_i18n. Defines internationalization functionality.
	 * - Section_Image_Admin. Defines all hooks for the admin area.
	 * - Section_Image_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-section-image-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-section-image-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-section-image-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-section-image-public.php';

		$this->loader = new Section_Image_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Section_Image_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Section_Image_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Section_Image_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init', $plugin_admin, 'register_media_taxonomy' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {

		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {

		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Section_Image_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {

		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {

		return $this->version;
	}

	public static function get_media_by_term( $term ) {

		$args = [
			'numberposts' => 1,
			'post_type'   => 'attachment',
			'post_status' => 'any',
			'orderby'     => 'rand',
			'tax_query'   => [
				[
					'taxonomy'         => 'media-category',
					'field'            => 'slug',
					'terms'            => $term,
					'include_children' => false,
				],
			],
		];

		$term_image = get_posts( $args );

		if ( empty( $term_image ) || false === $term_image ) {
			return false;
		}

		return $term_image;
	}

	public static function find_section_image() {

		$ancestor     = [];
		$current_post = get_post();

		if ( function_exists( '\CNP\get_highest_ancestor' ) ) {
			$ancestor = \CNP\get_highest_ancestor();
		}

		$section_image_obj = false;
		$section_image_id  = '';

		if ( is_singular() ) {
			$page_slug         = apply_filters( 'section_image_slug', $current_post->post_name );
			$section_image_obj = get_section_image( $page_slug );
		}

		if ( false === $section_image_obj && ! empty( $ancestor ) ) {
			$section_image_obj = get_section_image( $ancestor['name'] );
		}

		if ( false === $section_image_obj ) {
			$section_image_obj = get_section_image( 'section-image' );
		}

		if ( ! empty( $section_image_obj ) ) {
			$section_image_id = $section_image_obj[0]->ID;
		}

		return $section_image_id;
	}
}
