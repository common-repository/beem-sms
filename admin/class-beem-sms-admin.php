<?php /** @noinspection ALL */

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://beem.africa/
 * @since      2.0.0
 *
 * @package    Beem_Sms
 * @subpackage Beem_Sms/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Beem_Sms
 * @subpackage Beem_Sms/admin
 * @author     Beem Africa <contact@beem.africa>
 */
class Beem_Sms_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/beem-sms-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'bootstrap-css', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/beem-sms-admin.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script('bootstrap-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery' ), $this->version, false );

	}

    /**
     * Adds a menu in the admin area dashboard.
     *
     * @since    2.0.0
     */
    public function beem_sms_add_admin_menu()
    {
        add_menu_page (
            'Beem Sms', // page title
            'Beem Sms', // menu title
            'manage_options', // capability
            'beem_sms',  // menu-slug
            array($this, 'beem_sms_admin_menu_page'),   // function that will render its output
            'https://i.ibb.co/dB1zsNf/beem-logo.png',  // link to the icon that will be displayed in the sidebar
            59   // position of the menu option
        );
    }

    /**
     * Returns a view.
     *
     * @since    2.0.0
     */
    public function beem_sms_admin_menu_page()
    {
	    // check user capabilities
	    if ( ! current_user_can( 'manage_options' ) ) {
		    return;
	    }

        require_once 'partials/beem-sms-admin-display.php';
    }

    /**
     * Register custom settings for the plugin.
     *
     * @since    2.0.0
     */
    public function beem_sms_register_custom_settings(){
        register_setting('beem_sms_custom_settings','apiKey');
        register_setting('beem_sms_custom_settings','secretKey');
        register_setting('beem_sms_custom_settings','activeSenderName');


	    //New Order Notification Settings
	    register_setting('beem_sms_new_order_settings', 'new_order_status');
	    register_setting('beem_sms_new_order_settings', 'new_order_message_body');

		//status change settings
	    register_setting('beem_sms_order_status_changed_settings', 'order_status_changed_status');
	    register_setting('beem_sms_order_status_changed_settings', 'order_status_changed_message_body');
    }

    /**
     * Fetches the active sender names/ids.
     *
     * @since    2.0.0
     */
    public function beem_sms_fetch_active_sender_names() {
	    $api_key    = get_option( 'apiKey' );
	    $secret_key = get_option( 'secretKey' );
	    $Url        = 'https://apisms.beem.africa/public/v1/sender-names?status=active';

	    if ( ! empty( $api_key ) && ! empty( $secret_key ) ) {
		    $args = array(
			    'headers' => array(
				    'Authorization' => 'Basic ' . base64_encode( $api_key . ':' . $secret_key ),
				    'Content-Type'  => 'application/json'
			    )
		    );
		    // Send the request
		    $response = wp_remote_get( $Url, $args );

		    if ( is_wp_error( $response ) ) {
			    return false;
		    }
		    //Let's now get the actual data
		    $body           = wp_remote_retrieve_body( $response );
		    $senderid_array = json_decode( $body, true );
		    $senderid_data  = $senderid_array['data'];

		    //Next, we will work on the senderIDs. We only need to capture those values, nothing else
		    $sender_array = array();
		    foreach ( $senderid_data as $senderdata ) {
			    $sendername     = $senderdata['senderid'];
			    $sender_array[] = $sendername;
		    }
		    //save the array of options to the database
		    add_option( 'sender_names', $sender_array );
	    }

    }

	/**
	 * Fetches the credit balance.
	 *
	 * @since    2.0.0
	 */
	public function beem_sms_fetch_credit_balance() {
		$api_key    = get_option( 'apiKey' );
		$secret_key = get_option( 'secretKey' );
		$Url        = 'https://apisms.beem.africa/public/v1/vendors/balance';

		if ( ! empty( $api_key ) && ! empty( $secret_key ) ) {

			$args = array(
				'headers' => array(
					'Authorization' => 'Basic ' . base64_encode( $api_key . ':' . $secret_key ),
					'Content-Type'  => 'application/json'
				),
			);

			$response = wp_remote_get( $Url, $args );

			if ( is_wp_error( $response ) ) {
				die( "Something went wrong" );
			}

			$body = wp_remote_retrieve_body( $response );
			$data = json_decode( $body );

			$balance = $data->data->credit_balance;
			//save and update the balance in the database
			update_option('credit_balance', $balance);

		}
	}

}
