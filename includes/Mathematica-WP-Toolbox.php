<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 *
 * @package    Mathematica_WP_Toolbox
 * @subpackage Mathematica_WP_Toolbox/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Mathematica_WP_Toolbox
 * @subpackage Mathematica_WP_Toolbox/includes
 * @author     Calle Ekdahl <calle@ekdahl.email>
 */
class Mathematica_WP_Toolbox {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Mathematica_WP_Toolbox_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'Mathematica-WP-Toolbox';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Mathematica_WP_Toolbox_Loader. Orchestrates the hooks of the plugin.
	 * - Mathematica_WP_Toolbox_Admin. Defines all hooks for the admin area.
	 * - Mathematica_WP_Toolbox_Public. Defines all hooks for the public side of the site.
	 *
	 * Include classes that are used to abstract away certain functionality
	 * - Mathematica_WP_Toolbox_Glyphs. Encapsulates a set of glyph replacement rules.
	 * - Mathematica_WP_Toolbox_API. Handles construction and caching of SE API requests.
	 * - Mathematica_WP_Toolbox_API_General. Retrieves questions and answer from the SE API.
	 * - Mathematica_WP_Toolbox_API_User. Retrieves user information from the SE API.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Mathematica-WP-Toolbox-loader.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/Mathematica-WP-Toolbox-admin.php';

		/**
		 * A class encapsulating glyph replacement rules.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/Mathematica-WP-Toolbox-glyphs.php';
		
		/**
		 * Classes that interface with the Stackexchange API.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/Mathematica-WP-Toolbox-API.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/Mathematica-WP-Toolbox-API-general.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/Mathematica-WP-Toolbox-API-user.php';
		
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/Mathematica-WP-Toolbox-public.php';

		$this->loader = new Mathematica_WP_Toolbox_Loader();

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Mathematica_WP_Toolbox_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_filter( 'upload_mimes', $plugin_admin, 'add_wolfram_mime_types' );
		$this->loader->add_filter( 'xmlrpc_methods', $plugin_admin, 'wl_new_xmlrpc_methods' );

		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_media_button', 11 );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Mathematica_WP_Toolbox_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );
		$this->loader->add_filter( 'wlcode_render_pre', $plugin_public, 'replace_glyphs' );
		$this->loader->add_filter( 'the_content', $plugin_public, 'replace_curly_quotes', 100 );
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
	 * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
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

}
