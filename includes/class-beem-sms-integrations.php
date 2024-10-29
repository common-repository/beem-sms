<?php

namespace BEEM_SMS;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 *
 * This class defines all code necessary to interact with the woocommerce plugin .
 *
 * @since      2.0.0
 * @package    Beem_Sms
 * @subpackage Beem_Sms/includes
 * @author     Hamid Said
 */
class Beem_Sms_Integrations
{
    public function __construct()
    {}

    /**
     * defines the woocommerce_new_order callback
     *
     * @since    2.0.0
     */
    public function beem_sms_send_sms_to_customer_on_new_order($order_id): void
    {
        //exit if admin hasn't enabled sending sms
        if (get_option('new_order_status') != 1) {
            return;
        }

        $order = wc_get_order($order_id);
        $customer_name = $order->get_billing_first_name();
        $customer_last_name = $order->get_billing_last_name();
        // Remove '+' from phone
        $phone = substr($order->get_billing_phone(), 1);
        $order_total = $order->get_total();
        $order_status = $order->get_status();
        $order_number = $order->get_order_number();

        $default_sms_message = get_option('new_order_message_body');
        // Array containing search string
        $searchVal = array("%order_id%", "%order_number%", "%status%", "%order_total%", "%billing_first_name%", "%billing_last_name%");
        // Array containing replace string from  search string
        $replaceVal = array($order_id, $order_number, $order_status, $order_total, $customer_name, $customer_last_name);
        $sms_message = str_replace($searchVal, $replaceVal, $default_sms_message);
        $this->beem_sms_send_sms_to_customer($phone, $sms_message);
    }

    /**
     * Defines the woocommerce_order_status_changed callback
     *
     * @since    2.0.0
     */
    public function beem_sms_send_sms_to_customer_on_order_status_changed($order_id, $old_status, $new_status, $order): void
    {
        //exit if admin hasn't enabled sending sms
        if (get_option('order_status_changed_status') != 1) {
            return;
        }

        $order = wc_get_order($order_id);
        // Remove '+' from phone
        $phone = substr($order->get_billing_phone(), 1);
        $order_status = $order->get_status();
        $order_number = $order->get_order_number();
        $order_date = $order->get_date_created();
        // Remove '+' from phone
        $default_sms_message = get_option('order_status_changed_message_body');
        $searchVal = array("%order_number%", "%order_status%", "%order_date%");
        // Array containing replace string from  search string
        $replaceVal = array($order_number, $order_status, $order_date);
        $sms_message = str_replace($searchVal, $replaceVal, $default_sms_message);
        $this->beem_sms_send_sms_to_customer($phone, $sms_message);
    }

    /**
     * Sends messages to customers via Beem Apis
     *
     * @since    2.0.0
     */
    public function beem_sms_send_sms_to_customer($phone = null, $sms_message)
    {

        if (null === $phone || empty($sms_message)) {
            return;
        }

        $api_key = get_option('apiKey');
        $secret_key = get_option('secretKey');
        $source_address = get_option('activesenderName');

        $postData = array(
            'source_addr' => $source_address,
            'encoding' => 0,
            'schedule_time' => '',
            'message' => $sms_message,
            'recipients' => array(
                array(
                    'recipient_id' => '1',
                    'dest_addr' => $phone,
                ),
            ),
        );

        $url = 'https://apisms.beem.africa/v1/send';

        $args = array(
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode($api_key . ':' . $secret_key),
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode($postData),
        );

        $response = wp_remote_post($url, $args);

        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            return "Something went wrong: $error_message";
        }
    }

}
