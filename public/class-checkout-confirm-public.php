<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://bnksolution.com
 * @since      1.0.1
 *
 * @package    Checkout_Confirm
 * @subpackage Checkout_Confirm/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Checkout_Confirm
 * @subpackage Checkout_Confirm/public
 * @author     Bnk Solution <info@bnksolution.com>
 */
class Checkout_Confirm_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.1
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.1
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.1
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.1
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Checkout_Confirm_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Checkout_Confirm_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/checkout-confirm-public.css', array(), $this->version, 'all');
        if (is_checkout()) {
            wp_enqueue_style($this->plugin_name . '-sweetalert2', plugin_dir_url(__FILE__) . 'css/sweetalert2.min.css', array(), $this->version, 'all');
        }
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.1
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Checkout_Confirm_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Checkout_Confirm_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        if (is_checkout()) {
            wp_enqueue_script($this->plugin_name . '-popup', plugin_dir_url(__FILE__) . 'js/sweetalert2.all.min.js', array('jquery'), $this->version, false);
        }

        wp_enqueue_script($this->plugin_name . '-script', plugin_dir_url(__FILE__) . 'js/checkout-confirm-public.js', array('jquery'), $this->version, true);
        wp_localize_script($this->plugin_name . '-script', 'c3_params_ajax', [
            'home_url' => home_url(),
        ]);
    }

    public function define_variable_checkout_confirm()
    {
        $_option = array(
            'type' => 'popup',
            'confirm_button' => 'Order',
            'text_color_button' => '#FFFFFF',
            'background_color_button' => '#7367F0',
            'checkout_button' => 'Place order',
            'text_color_checkout_button' => '#FFFFFF',
            'background_color_checkout_button' => '#333333'
        );
        $option = get_option('check_confirm_page_option');
        if (get_option('license_checkout_confirm', '') == 'Free' || !is_plugin_active('cart-checkout-confirmation-pro/cart-checkout-confirmation-pro.php')) {
            $option = $_option;
        }

        $option = array_merge($_option, ($option) ? $option : []);
        $script = '<script>var confirmation_type = "' . $option['type'] . '";</script>';
        $script .= '<script>var checkout_confirm_page = "' . get_permalink(get_page_by_path('cart-checkout-confirmation')) . '";</script>';
        $confirmButton = $option['confirm_button'] ? esc_html__($option['confirm_button'], 'cart-checkout-confirmation') : esc_html__('Order', 'cart-checkout-confirmation');
        $backButton = $option['back_button'] ? esc_html__($option['confirm_button'], 'cart-checkout-confirmation') : esc_html__('Back', 'cart-checkout-confirmation');
        $script .= '<script>var title_langague = "<strong>' . esc_html__('Confirm order', 'cart-checkout-confirmation') . '</strong>"; var confirm_button = "' . $confirmButton . '"; var back_button = "' . $backButton . '";</script>';
        echo wp_kses($script, ['script' => [], 'strong' => []]);
    }

    public function get_checkout_confirm_html()
    {
        ob_start();
        require plugin_dir_path(__FILE__) . 'partials' . DIRECTORY_SEPARATOR . 'checkout-confirm-public-display.php';
        $html = ob_get_clean();
        ob_end_flush();
        wp_send_json_success(['html' => $html]);
    }

    public function define_shortcode_checkout_confirm()
    {
        add_shortcode('cart-checkout-confirmation', array($this, 'checkout_confirm_shortcode'));
    }

    public function push_confirm_notification($posted)
    {
        if (!WC()->session->get('checkout_confirm')) {
            wc_add_notice("Need confirm before place order", 'error');
        }
    }

    public function check_confirm_flag_in_request()
    {
        if (isset($_POST['checkout_confirm'])) WC()->session->set('checkout_confirm', 1);
    }

    public function remove_confirm_flag()
    {
        if (WC()->session->has_session('checkout_confirm')) {
            WC()->session->set('checkout_confirm', 0);
        }
    }

    public function checkout_confirm_shortcode()
    {
        ob_start();
        require plugin_dir_path(__FILE__) . 'partials' . DIRECTORY_SEPARATOR . 'checkout-confirm-public-display.php';
        $output = ob_get_clean();
        ob_end_flush();
        return $output;
    }

    public function update_full_information_order_review($post_data)
    {
        parse_str($post_data, $data);
        if (isset($data['billing_first_name'])) WC()->customer->set_billing_first_name($data['billing_first_name']);
        if (isset($data['billing_last_name'])) WC()->customer->set_billing_last_name($data['billing_last_name']);
        if (isset($data['shipping_first_name'])) WC()->customer->set_shipping_first_name($data['shipping_first_name']);
        if (isset($data['shipping_last_name'])) WC()->customer->set_shipping_last_name($data['shipping_last_name']);
        if (isset($data['billing_company'])) WC()->customer->set_shipping_last_name($data['billing_company']);
        if (isset($data['shipping_city'])) WC()->customer->set_shipping_last_name($data['shipping_city']);
    }
}
