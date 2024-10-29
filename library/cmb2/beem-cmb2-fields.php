<?php
/**
 * CMB2 Fields for the Beem SMS Plugin
 *
 * @package      CoreFunctionality
 * @author       Samedi Amba
 * @since        1.0.0
 * @license      GPL-2.0+
**/

/**
 * Hook in and register a metabox to handle a theme options page and adds a menu item.
 * https://github.com/CMB2/CMB2-Snippet-Library/blob/master/options-and-settings-pages/options-pages-with-submenus.php
 */

add_action( 'cmb2_admin_init', 'beem_register_subscriber_details' );

 // Hook in and register a metabox to handle a theme options page and adds a menu item.

function beem_register_subscriber_details() {

	$cmb_options = new_cmb2_box( array(
		'id'           => 'yourprefix_theme_options_page',
		'title'        => 'Subscriber Details',
		'object_types' => array( 'beem_subscriber' ),
	) );

	
	$cmb_options->add_field( array(
		'name' => esc_html__( 'First Name', 'cmb2' ),
		'desc' => esc_html__( '', 'cmb2' ),
		'id'   => 'beem_subscriber_name',
		'type' => 'text',
	) );
	
	$cmb_options->add_field( array(
		'name' => esc_html__( 'Phone Number', 'cmb2' ),
		'desc' => esc_html__( 'Format: 255710100200', 'cmb2' ),
		'id'   => 'beem_subscriber_phone',
		'type' => 'text',
	) );

}