<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * the admin area.
 *
 * @link       https://https://beem.africa/
 * @since      2.0.0
 *
 * @package    Beem_Sms
 * @subpackage Beem_Sms/includes
 */

use BEEM_SMS\Beem_Sms_Integrations;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      2.0.0
 * @package    Beem_Sms
 * @subpackage Beem_Sms/includes
 * @author     Beem Africa <contact@beem.africa>
 */
class Beem_Sms {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      Beem_Sms_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area
	 *
	 * @since    2.0.0
	 */
	public function __construct() {
		if ( defined( 'BEEM_SMS_VERSION' ) ) {
			$this->version = BEEM_SMS_VERSION;
		} else {
			$this->version = '2.0.0';
		}
		$this->plugin_name = 'beem-sms';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Beem_Sms_Loader. Orchestrates the hooks of the plugin.
	 * - Beem_Sms_i18n. Defines internationalization functionality.
	 * - Beem_Sms_Admin. Defines all hooks for the admin area.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-beem-sms-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-beem-sms-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-beem-sms-admin.php';

		$this->loader = new Beem_Sms_Loader();

		/**
		 * The class responsible for woocommerce integrations
		 * with the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-beem-sms-integrations.php';


	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Beem_Sms_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Beem_Sms_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Beem_Sms_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

        //add custom admin page
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'beem_sms_add_admin_menu' );

        //register custom general settings
        $this->loader->add_action('admin_init',$plugin_admin,'beem_sms_register_custom_settings');

        //fetch sender_ids
        $this->loader->add_action('admin_init',$plugin_admin,'beem_sms_fetch_active_sender_names');

		//fetch sender names
		$this->loader->add_action('admin_init',$plugin_admin,'beem_sms_fetch_credit_balance');

		//event triggered when a new order is made
		$plugin_integrations = new Beem_Sms_Integrations();
		$this->loader->add_action('woocommerce_new_order', $plugin_integrations,'beem_sms_send_sms_to_customer_on_new_order',1,1);

		//event triggered when the order status is changed
		$this->loader->add_action('woocommerce_order_status_changed', $plugin_integrations,'beem_sms_send_sms_to_customer_on_order_status_changed',10,4);
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    2.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     2.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     2.0.0
	 * @return    Beem_Sms_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     2.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
