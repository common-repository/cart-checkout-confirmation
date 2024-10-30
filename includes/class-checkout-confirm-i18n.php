<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://bnksolution.com
 * @since      1.0.2
 *
 * @package    Checkout_Confirm
 * @subpackage Checkout_Confirm/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.2
 * @package    Checkout_Confirm
 * @subpackage Checkout_Confirm/includes
 * @author     Bnk Solution <info@bnksolution.com>
 */
class Checkout_Confirm_i18n
{


    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.2
     */
    public function load_plugin_textdomain()
    {

        load_plugin_textdomain(
            'cart-checkout-confirmation',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }

    public function checkout_confirm_heading_text($translated, $text, $domain)
    {

        if ($domain == 'cart-checkout-confirmation') {
            switch ($text) {
                case 'First name':
                    $translated = esc_html__('First name', $domain);
                    break;
                case 'Last name':
                    $translated = esc_html__('Last name', $domain);
                    break;
                case 'Company name':
                    $translated = esc_html__('Company name', $domain);
                    break;
                case 'Country/Region':
                    $translated = esc_html__('Country/Region', $domain);
                    break;
                case 'Street address':
                    $translated = esc_html__('Street address', $domain);
                    break;
                case 'Apartment, suite, unit, etc.':
                    $translated = esc_html__('Apartment, suite, unit, etc', $domain);
                    break;
                case 'Suburb':
                    $translated = esc_html__('Suburb', $domain);
                    break;
                case 'State':
                    $translated = esc_html__('State', $domain);
                    break;
                case 'Postcode':
                    $translated = esc_html__('Postcode', $domain);
                    break;
                case 'Email address':
                    $translated = esc_html__('Email address', $domain);
                    break;
            }
        }
        return $translated;
    }
}
