<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://robert.austin
 * @since      1.0.0
 *
 * @package    Map_R
 * @subpackage Map_R/includes
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
 * @package    Map_R
 * @subpackage Map_R/includes
 * @author     Robert Austin <robert@conquermaths.com>
 */
class Map_R {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Map_R_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'MAP_R_VERSION' ) ) {
			$this->version = MAP_R_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'map_r';

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
	 * - Map_R_Loader. Orchestrates the hooks of the plugin.
	 * - Map_R_i18n. Defines internationalization functionality.
	 * - Map_R_Admin. Defines all hooks for the admin area.
	 * - Map_R_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-map-r-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-map-r-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-map-r-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-map-r-public.php';

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/lib/class-map-r-settings-api.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/lib/class-map-r-main.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/lib/class-map-r-memberpress-api.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/lib/class-map-r-register-txn-api.php';

		$this->loader = new Map_R_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Map_R_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Map_R_i18n();

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

		$plugin_admin = new Map_R_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'check_memberpress_installed' );        
        $this->loader->add_filter( 'wp_mail', $plugin_admin, 'wp_mail');


        $settings_api = new Map_R_Settings_Api( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'admin_menu', $settings_api, 'register_options_page' );
        $this->loader->add_action( 'admin_head', $settings_api, 'options_page_css' );
        $this->loader->add_action( 'admin_init', $settings_api, 'register_settings' );

        $main = new Map_R_Main( new Map_R_Memberpress_Api( $this->get_plugin_name(), $this->get_version() ), $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'admin_menu', $main, 'add_menu_pages' );
        $this->loader->add_action( 'admin_post_map_r_add_new_map_r', $main, 'save_new_map_r' );
        $this->loader->add_action( 'admin_post_map_r_delete_map_r', $main, 'delete_map_r' );

        $register_txn = new Map_R_Register_Txn_Api();
        $this->loader->add_action( 'mepr-txn-status-complete', $register_txn, 'mepr_txn_status_complete' );


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Map_R_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

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
	 * @return    Map_R_Loader    Orchestrates the hooks of the plugin.
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
