<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://bnksolution.com
 * @since      1.0.1
 *
 * @package    Checkout_Confirm
 * @subpackage Checkout_Confirm/includes
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
 * @since      1.0.1
 * @package    Checkout_Confirm
 * @subpackage Checkout_Confirm/includes
 * @author     Bnk Solution <info@bnksolution.com>
 */
class Checkout_Confirm
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.1
     * @access   protected
     * @var      Checkout_Confirm_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.1
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.1
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.1
     */
    public function __construct()
    {
        if (defined('CHECKOUT_CONFIRM_VERSION')) {
            $this->version = CHECKOUT_CONFIRM_VERSION;
        } else {
            $this->version = '1.0.1';
        }
        $this->plugin_name = 'cart-checkout-confirmation';

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
     * - Checkout_Confirm_Loader. Orchestrates the hooks of the plugin.
     * - Checkout_Confirm_i18n. Defines internationalization functionality.
     * - Checkout_Confirm_Admin. Defines all hooks for the admin area.
     * - Checkout_Confirm_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.1
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-checkout-confirm-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-checkout-confirm-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-checkout-confirm-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-checkout-confirm-public.php';

        $this->loader = new Checkout_Confirm_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Checkout_Confirm_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.1
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Checkout_Confirm_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
        $this->loader->add_filter('gettext', $plugin_i18n, 'checkout_confirm_heading_text', 10, 3);

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.1
     * @access   private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new Checkout_Confirm_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_setting_checkout_confirm_menu');
        $this->loader->add_action('checkout_confirm_config_tab', $plugin_admin, 'checkout_confirm_config_tab');
        $this->loader->add_filter('display_post_states', $plugin_admin, 'confirm_page_post_states', 10, 2);
        $this->loader->add_filter('plugin_row_meta', $plugin_admin, 'plugin_row_meta', 10, 2);
        $this->loader->add_filter('plugin_action_links_cart-checkout-confirmation-pro/cart-checkout-confirmation-pro.php', $plugin_admin, 'add_action_links_pro', 10);
        $this->loader->add_filter('plugin_action_links_cart-checkout-confirmation/cart-checkout-confirmation.php', $plugin_admin, 'add_action_links', 10);
        $this->loader->add_action('admin_head', $plugin_admin, 'add_context_menu_help');
        $this->loader->add_filter('woocommerce_order_button_html', $plugin_admin, 'cart_checkout_custom_button_html', 10, 1);
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'load_admin_scripts');
        
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.1
     * @access   private
     */
    private function define_public_hooks()
    {

        $plugin_public = new Checkout_Confirm_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('wp_head', $plugin_public, 'define_variable_checkout_confirm');
        $this->loader->add_action('init', $plugin_public, 'define_shortcode_checkout_confirm');
        $this->loader->add_action('woocommerce_checkout_update_order_review', $plugin_public, 'update_full_information_order_review');
        $this->loader->add_action('woocommerce_after_checkout_validation', $plugin_public, 'push_confirm_notification');
        $this->loader->add_action('wp_ajax_get_checkout_confirm_html', $plugin_public, 'get_checkout_confirm_html');
        $this->loader->add_action('wp_ajax_nopriv_get_checkout_confirm_html', $plugin_public, 'get_checkout_confirm_html');
        $this->loader->add_action('woocommerce_checkout_process', $plugin_public, 'check_confirm_flag_in_request');
        $this->loader->add_action('woocommerce_thankyou', $plugin_public, 'remove_confirm_flag');

    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.1
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return    string    The name of the plugin.
     * @since     1.0.1
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return    Checkout_Confirm_Loader    Orchestrates the hooks of the plugin.
     * @since     1.0.1
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     1.0.1
     */
    public function get_version()
    {
        return $this->version;
    }

    public static function get_free_fields()
    {
        $fields = WC()->checkout()->checkout_fields;
        $display_fields = [
            'billing_first_name' => esc_html__('Billing', 'cart-checkout-confirmation') . ' ' . ($fields['billing']['billing_first_name']['label'] ?? ''),
            'billing_last_name'  => esc_html__('Billing', 'cart-checkout-confirmation') . ' ' . ($fields['billing']['billing_last_name']['label'] ?? ''),
            'billing_address_1'  => esc_html__('Billing', 'cart-checkout-confirmation') . ' ' . ($fields['billing']['billing_address_1']['label'] ?? ''),
            'billing_phone'      => esc_html__('Billing', 'cart-checkout-confirmation') . ' ' . ($fields['billing']['billing_phone']['label'] ?? ''),
            'billing_email'      => esc_html__('Billing', 'cart-checkout-confirmation') . ' ' . ($fields['billing']['billing_email']['label'] ?? '')
        ];

        return $display_fields;
    }

}
