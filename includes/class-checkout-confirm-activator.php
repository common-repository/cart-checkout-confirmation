<?php

/**
 * Fired during plugin activation
 *
 * @link       https://bnksolution.com
 * @since      1.0.1
 *
 * @package    Checkout_Confirm
 * @subpackage Checkout_Confirm/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.1
 * @package    Checkout_Confirm
 * @subpackage Checkout_Confirm/includes
 * @author     Bnk Solution <info@bnksolution.com>
 */
class Checkout_Confirm_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.1
     */
    public static function activate()
    {
        $objPage = get_page_by_path('cart-checkout-confirmation', 'OBJECT', 'page');
        if (empty($objPage)) {

            wp_insert_post(
                array(
                    'comment_status' => 'close',
                    'ping_status'    => 'close',
                    'post_author'    => 1,
                    'post_title'     => esc_html__('Cart Checkout Confirmation', 'cart-checkout-confirmation'),
                    'post_name'      => 'cart-checkout-confirmation',
                    'post_status'    => 'publish',
                    'post_content'   => '[cart-checkout-confirmation]',
                    'post_type'      => 'page'
                )
            );
        }

        $license = get_option('license_checkout_confirm', '');
        if (!$license) {
            add_option('license_checkout_confirm', 'Free');
        }

        $confirmOnOffText = get_option('confirm_on_off_text_bill', '');
        if (!$confirmOnOffText) {
            add_option('confirm_on_off_text_bill', 'Off');
        }
    }
}
