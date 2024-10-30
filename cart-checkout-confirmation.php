<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wp.and-bro.com
 * @since             1.0.2
 * @package           Checkout_Confirm
 *
 * @wordpress-plugin
 * Plugin Name:       Cart Checkout Confirmation
 * Plugin URI:        https://wp.and-bro.com/shop/plugin/cart-checkout-confirmation
 * Description:       Cart Checkout Confirmationは、WooCommerceの購入画面（顧客情報入力）と決済完了画面の間に確認ページを入れることのできるプラグインです。
 * Version:           1.0.2
 * Author:            ANDShop
 * Author URI:        https://wp.and-bro.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cart-checkout-confirmation
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.1 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('CHECKOUT_CONFIRM_VERSION', '1.0.2');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-checkout-confirm-activator.php
 */
function activate_checkout_confirm()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-checkout-confirm-activator.php';
    Checkout_Confirm_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-checkout-confirm-deactivator.php
 */
function deactivate_checkout_confirm()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-checkout-confirm-deactivator.php';
    Checkout_Confirm_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_checkout_confirm');
register_deactivation_hook(__FILE__, 'deactivate_checkout_confirm');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-checkout-confirm.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.2
 */
function run_checkout_confirm()
{

    $plugin = new Checkout_Confirm();
    $plugin->run();
}

run_checkout_confirm();


/**
 * Change text label billing fields
 */
add_filter('woocommerce_billing_fields', 'wpslash_change_billing_fields', 20, 1);

if (!function_exists('wpslash_change_billing_fields')) {
    function wpslash_change_billing_fields($fields)
    {
        $fields['billing_first_name']['label'] = esc_html__('First Name', 'cart-checkout-confirmation');
        $fields['billing_last_name']['label'] = esc_html__('Last Name', 'cart-checkout-confirmation');
        $fields['billing_company']['label'] = esc_html__('Company Name', 'cart-checkout-confirmation');
        $fields['billing_address_1']['label'] = esc_html__('Street Address', 'cart-checkout-confirmation');
        $fields['billing_country']['label'] = esc_html__('Country / Region', 'cart-checkout-confirmation');
        $fields['billing_address_2']['label'] = esc_html__('Apartment, Suite, Unit, etc', 'cart-checkout-confirmation');
        $fields['billing_city']['label'] = esc_html__('Town / City', 'cart-checkout-confirmation');
        $fields['billing_postcode']['label'] = esc_html__('Postcode / ZIP', 'cart-checkout-confirmation');
        $fields['billing_state']['label'] = esc_html__('Prefecture', 'cart-checkout-confirmation');
        $fields['billing_email']['label'] = esc_html__('Email Address', 'cart-checkout-confirmation');
        $fields['billing_phone']['label'] = esc_html__('Phone', 'cart-checkout-confirmation');
        return $fields;
    }
}


/**
 * Change text label shipping fields
 */
add_filter('woocommerce_shipping_fields', 'wpslash_change_shipping_fields', 20, 1);

if (!function_exists('wpslash_change_shipping_fields')) {
    function wpslash_change_shipping_fields($fields)
    {

        $fields['shipping_first_name']['label'] = esc_html__('First Name', 'cart-checkout-confirmation');
        $fields['shipping_last_name']['label'] = esc_html__('Last Name', 'cart-checkout-confirmation');
        $fields['shipping_company']['label'] = esc_html__('Company Name', 'cart-checkout-confirmation');
        $fields['shipping_address_1']['label'] = esc_html__('Street Address', 'cart-checkout-confirmation');
        $fields['shipping_address_2']['label'] = esc_html__('Apartment, Suite, Unit, etc', 'cart-checkout-confirmation');
        $fields['shipping_city']['label'] = esc_html__('Town / City', 'cart-checkout-confirmation');
        $fields['shipping_postcode']['label'] = esc_html__('Postcode / ZIP', 'cart-checkout-confirmation');
        $fields['shipping_country']['label'] = esc_html__('Country / Region', 'cart-checkout-confirmation');
        $fields['shipping_state']['label'] = esc_html__('Prefecture', 'cart-checkout-confirmation');
        return $fields;
    }
}

/**
 * Change text label order fields and other fields
 */
add_filter('woocommerce_checkout_fields', 'c3_change_order_fields', 20, 1);
if (!function_exists('c3_change_order_fields')) {
    function c3_change_order_fields($fields)
    {
        $fields['order']['order_comments']['label'] = esc_html__('Order Notes', 'cart-checkout-confirmation');
        return $fields;
    }
}
