<?php

/**
 * Provide an admin area view for the plugin
 *
 * @link       https://https://beem.africa/
 * @since      2.0.0
 *
 * @package    Beem_Sms
 * @subpackage Beem_Sms/admin/partials
 */


//Get the active tab from the $_GET param
$default_tab = null;
$tab         = isset( $_GET['tab'] ) ? sanitize_text_field($_GET['tab']) : $default_tab;
?>
<div class="wrap">
    <nav class="navbar navbar-expand-lg navbar-light bg-light flex">
        <div class="container-fluid">
            <img width="35%" src="<?php echo esc_url(plugin_dir_url( dirname( __FILE__ ) ) . '../assets/beem.png') ?>"
                 alt="Beem logo">

            <div class="d-flex justify-content-lg-end">
                <div class="d-flex align-items-center fw-bold">
					<?php
					if ( ! empty( get_option( 'credit_balance' ) ) ) {
						echo "Credit balance:" . esc_html(get_option( 'credit_balance' ));
					}
					?>
                </div>
                <a href="?page=beem_sms"
                   class="btn beem-btn btn-outline-secondary px-5 mx-2 <?php if ( $tab === null ): ?>active<?php endif; ?>"
                   type="submit">
                    Settings
                </a>
                <a href="?page=beem_sms&tab=notifications"
                   class="btn beem-btn btn-outline-secondary px-5 mx-2 <?php if ( $tab === 'notifications' ): ?>active<?php endif; ?>"
                   type="submit">
                    Notifications
                </a>
            </div>
        </div>
    </nav>


	<?php
	switch ( $tab ) :
	case 'notifications':
	?>
    <div class="m-5">
        <div class="d-grid gap-3 ">
            <div class="p-2 bg-light border rounded fw-bold text-black-50">WooCommerce</div>
        </div>
        <div class="accordion" id="accordionPanelsStayOpenExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                            aria-controls="panelsStayOpen-collapseOne">
                        New Order notification
                    </button>
                </h2>
                <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show"
                     aria-labelledby="panelsStayOpen-headingOne">
                    <form method="post" action="options.php">
                        <div class="accordion-body">
							<?php
							settings_fields( 'beem_sms_new_order_settings' );
							do_settings_sections( 'beem_sms_new_order_settings' );

							$new_order_message_body = get_option( 'new_order_message_body' );
							$new_order_status       = get_option( 'new_order_status' );
							?>
                            <div class="d-flex justify-content-between">
                                <div>
                                    Status
                                    <p class="text-black-50">Send a notification when a customer makes a new
                                        order</p>
                                </div>
                                <label class="switch">
                                    <input type="checkbox" value="1" name="new_order_status"
										<?php checked( '1', esc_html(get_option( 'new_order_status' ) ) ); ?> >
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div>
                                <div class="mb-2">Message Body</div>
                                <div class="mb-3">
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"
                                                  name="new_order_message_body"><?php echo esc_html($new_order_message_body) ?>
                                        </textarea>
                                    <p class="text-black-50 fs-6">Enter the contents of the SMS message. <br>
                                        keywords: Order id: %order_id%, Order number: %order_number%, Order status:
                                        %status%,
                                        Order Total: %order_total%, Customer name: %billing_first_name%, Customer
                                        family: %billing_last_name%
                                    </p>

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn save-btn px-3">Save</button>
                                    </div>
                                </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false"
                    aria-controls="panelsStayOpen-collapseTwo">
                Order Status notification
            </button>
        </h2>
        <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse"
             aria-labelledby="panelsStayOpen-headingTwo">
            <form method="post" action="options.php">
                <div class="accordion-body">
					<?php
					settings_fields( 'beem_sms_order_status_changed_settings' );
					do_settings_sections( 'beem_sms_order_status_changed_settings' );

					$message_body         = get_option( 'order_status_changed_message_body' );
					$status_change_status = get_option( 'order_status_changed_status' );
					?>
                    <div class="d-flex justify-content-between">
                        <div>
                            Status
                            <p class="text-black-50">Send SMS to customer when order status is changed</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" value="1" name="order_status_changed_status"
								<?php checked( '1', esc_html(get_option( 'order_status_changed_status' ) ) ); ?> >
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div>
                        <div class="mb-2">Message Body</div>
                        <div class="mb-3">
                         <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"
                                   name="order_status_changed_message_body"><?php echo esc_html($message_body) ?>
                          </textarea>
                            <p class="text-black-50 fs-6">Enter the contents of the SMS message. <br>
                                keywords: Order number: %order_number% Status: %order_status% Order Date: %order_date%
                            </p>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn save-btn px-3">Save</button>
                            </div>
                        </div>
            </form>
        </div>
    </div>
</div>

<?php
break;
default:
	?>
    <div class="d-flex justify-content-center align-items-center">
        <div class="card container">
            <div class="card-body p-5">
                <form method="post" action="options.php">
					<?php
					settings_fields( 'beem_sms_custom_settings' );
					do_settings_sections( 'beem_sms_custom_settings' );
					?>
                    <div class="form-text mb-3">Login to your Beem dashboard to get your api key and secret key.
                        Get them <a href="https://beem.africa/" target="_blank">here</a>
                    </div>
                    <div class="mb-3">
						<?php
						$api_key    = get_option( 'apiKey' );
						$secret_key = get_option( 'secretKey' );
						?>
                        <label for="apikey" class="form-label">API Key</label>
                        <input type="text" class="form-control" value="<?php echo esc_attr(stripslashes($api_key)) ?>" name="apiKey"
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="secretKey" class="form-label">Secret Key</label>
                        <input type="text" class="form-control" value="<?php echo esc_attr(stripslashes($secret_key)) ?>"
                               name="secretKey" required>
                    </div>
                    <div class="w-5">
                        <select class="selectpicker" name="activeSenderName" required>
                            <option selected disabled>Select SenderName</option>
							<?php
							$sender_names = get_option( 'sender_names' );
							foreach ( $sender_names as $sender_name ) {
								if ( $sender_name == get_option( 'activeSenderName' ) ) {
									echo "<option value='". esc_attr($sender_name) . "' selected>". esc_html($sender_name) . "</option>";
									continue;
								}
								echo "<option value='". esc_attr($sender_name) . "' >". esc_html($sender_name) . "</option>";
							}
							?>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn save-btn px-5">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
	<?php
	break;
endswitch;
?>

