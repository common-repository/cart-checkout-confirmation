<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://bnksolution.com
 * @since      1.0.2
 *
 * @package    Checkout_Confirm
 * @subpackage Checkout_Confirm/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Checkout_Confirm
 * @subpackage Checkout_Confirm/admin
 * @author     Bnk Solution <info@bnksolution.com>
 */
class Checkout_Confirm_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.2
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.2
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;


    /**
     * The path admin plugin
     * @since   1.0.1
     * @access  private
     * @var     string
     */
    private $admin_path;


    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.1
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->admin_path = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'cart-checkout-confirmation' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR;
    }

    /**
     * Register the stylesheets for the admin area.
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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/checkout-confirm-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
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

        wp_enqueue_script('wp-color-picker');
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script($this->plugin_name . '-script', plugin_dir_url(__FILE__) . 'js/checkout-confirm-admin.js', array('jquery'), $this->version, true);
    }

    public function add_setting_checkout_confirm_menu()
    {
        add_menu_page(
            'Cart Checkout Confirmation',
            esc_html__('Cart Checkout Confirmation', 'cart-checkout-confirmation'),
            'manage_options',
            'cart-checkout-confirmation',
            array(&$this, 'show_plugin_options'),
            plugin_dir_url(__FILE__) . '/images/approval.svg',
            56
        );
        add_submenu_page(
            'cart-checkout-confirmation',
            esc_html__('Edit', 'cart-checkout-confirmation'),
            esc_html__('Edit', 'cart-checkout-confirmation'),
            'manage_options',
            'cart-checkout-confirmation',
            array(&$this, 'show_plugin_options'),
        );
        if (get_option('license_checkout_confirm') === 'Free' || !get_option('license_checkout_confirm')) {
            add_submenu_page(
                'cart-checkout-confirmation',
                esc_html__('Install Paid Version', 'cart-checkout-confirmation'),
                esc_html__('Install Paid Version', 'cart-checkout-confirmation'),
                'manage_options',
                'checkout-confirm-configure-fields',
                array(&$this, 'configure_fields_admin_page')
            );
        }

        global $menu;
        foreach ($menu as $key => $item) {
            if ($item[0] === 'ページ編集') {
                $menu[$key][0] = 'Cart Checkout Confirm';
            }
        }
    }

    public function confirm_page_post_states($post_states, $post)
    {
        if ($post->post_name == 'cart-checkout-confirmation') {
            $post_states[] = 'Checkout confirm pages';
        }
        return $post_states;
    }


    public function show_plugin_options()
    {
        require_once $this->admin_path . 'views' . DIRECTORY_SEPARATOR . 'configure.php';
    }

    public function checkout_confirm_config_tab($current)
    {
        switch ($current) {
            case 'fields':
                $this->checkout_confirm_tab_fields();
                break;
            case 'option':
                $this->checkout_confirm_tab_option();
                $orderButtonText = '';
                $this->cart_checkout_custom_button_html($orderButtonText);
                break;
            case 'additional':
                $this->checkout_confirm_additional_display();
                break;
        }
    }

    public function alert_upgrade_pro_version()
    {
        require $this->admin_path . 'views' . DIRECTORY_SEPARATOR . 'alert.php';
    }

    public function checkout_confirm_tab_fields()
    {
        $license = get_option('license_checkout_confirm', '');
        $display_fields = get_option('check_confirm_page_fields_setting');
        $fields = WC()->checkout()->checkout_fields;

        if ($license == 'Free') {
            $display_fields = Checkout_Confirm::get_free_fields();
            $update_field = update_option('confirm_on_off_text_bill', 'Off');
        }

        if (!empty($_POST['save_config_fields']) && $license == 'Pro' && !empty($_POST['fields'])) {
            $update_fields = array_map('sanitize_text_field', $_POST['fields']);
            $update = update_option('check_confirm_page_fields_setting', $update_fields);
            $display_fields = $update_fields;
        }

        if (!empty($_POST['save_config_fields']) && $license == 'Pro' && !empty($_POST['field_on_off_text'])) {
            $update_field = update_option('confirm_on_off_text_bill', $_POST['field_on_off_text']);
        }

        require $this->admin_path . 'views' . DIRECTORY_SEPARATOR . 'tabs' . DIRECTORY_SEPARATOR . 'fields.php';
    }

    public function checkout_confirm_tab_option()
    {
        $license = get_option('license_checkout_confirm', '');
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

        $option = array_merge($_option, ($option) ? $option : []);
        if (isset($_POST['submit_option_form'])) {
            if (isset($_POST['option'])) {
                $update_option = array_map('sanitize_text_field', $_POST['option']);
                $update = update_option('check_confirm_page_option', $update_option);
                $option = $update_option;
            }
        }
        if (get_option('license_checkout_confirm', '') == 'Free' || !is_plugin_active('cart-checkout-confirmation-pro/cart-checkout-confirmation-pro.php')) {
            $option = $_option;
        }

        require $this->admin_path . 'views' . DIRECTORY_SEPARATOR . 'tabs' . DIRECTORY_SEPARATOR . 'option.php';
    }

    public function checkout_confirm_additional_display()
    {
        $license = get_option('license_checkout_confirm', '');
        $_additional = array(
            'title-0' => '',
            'textarea-0' => '',
        );
        $additional = get_option('check_confirm_page_additional');
        $additional = array_merge($_additional, ($additional) ? $additional : []);

        if (isset($_POST['submit_additional_form'])) {
            $additionalArray = [];
            $additionals = $_POST['additional'];
            if (!empty(($additionals))) {
                foreach ($additionals as $additionalKey  => $additional) {
                    $titleNumbers = explode("title-", $additionalKey);
                    if (!empty($titleNumbers)) {
                        foreach ($titleNumbers as $titleNumber) {
                            $titleNumberKey = 'title-' . $titleNumber;
                            if ($additionalKey == $titleNumberKey) {
                                $additionalArray[$additionalKey] = $additional;
                            } else {
                                $additionalArray[$additionalKey] = htmlentities(wpautop($additional));
                            }
                        }
                    }
                }

                $update_additional = array_map('sanitize_text_field', $additionalArray);
                $update = update_option('check_confirm_page_additional', $update_additional);
                $additional = $update_additional;
            }
        }
        if (get_option('license_checkout_confirm', '') == 'Free' || !is_plugin_active('cart-checkout-confirmation-pro/cart-checkout-confirmation-pro.php')) {
            $additional = $_additional;
        }
        require $this->admin_path . 'views' . DIRECTORY_SEPARATOR . 'tabs' . DIRECTORY_SEPARATOR . 'additional-display.php';
    }

    public function plugin_row_meta($links, $file)
    {
        if ($file == 'cart-checkout-confirmation/cart-checkout-confirmation.php') {
            $row_meta = array(
                'apidocs' => '<a target="_blank" href="' . 'https://wp.and-bro.com/shop/plugin/cart-checkout-confirmation' . '" aria-label="' . esc_html__('Paid Version', 'cart-checkout-confirmation') . '">' . esc_html__('Paid version', 'cart-checkout-confirmation') . '</a>',
            );
            return array_merge($links, $row_meta);
        } else {
            return $links;
        }
    }

    public function add_action_links_pro($actions)
    {
        if (is_plugin_active('cart-checkout-confirmation-pro/cart-checkout-confirmation-pro.php')) {
            $settings_links = array(
                '<a href="' . admin_url('admin.php?page=cart-checkout-confirmation') . '">' . esc_html__("Settings", "cart-checkout-confirmation") . '</a>',
            );
            $actions = array_merge($settings_links, $actions);
        }
        return $actions;
    }

    public function add_action_links($actions)
    {
        if (is_plugin_active('cart-checkout-confirmation/cart-checkout-confirmation.php')) {
            $settings_links = array(
                '<a href="' . admin_url('admin.php?page=cart-checkout-confirmation') . '">' . esc_html__("Settings", "cart-checkout-confirmation") . '</a>',
            );
            $actions = array_merge($settings_links, $actions);
        }
        return $actions;
    }

    public function add_context_menu_help()
    {
        $current_screen = get_current_screen();
        $content = '<h2>ヘルプとサポート</h2><p>WooCommerce の理解、利用、または拡張にヘルプが必要な場合は、<a href="https://docs.woocommerce.com/documentation/plugins/woocommerce/?utm_source=helptab&amp;utm_medium=product&amp;utm_content=docs&amp;utm_campaign=woocommerceplugin">こちらのドキュメントをお読みください</a>。スニペット、チュートリアルなど、あらゆる種類のリソースを見つけることができます。</p><p>WooCommerce コアに関するサポートが必要な場合は、<a href="https://wordpress.org/support/plugin/woocommerce">コミュニティーフォーラム</a>をご利用ください。WooCommerce.com で販売されているプレミアム拡張機能に関するサポートが必要な場合は、<a href="https://woocommerce.com/my-account/create-a-ticket/?utm_source=helptab&amp;utm_medium=product&amp;utm_content=tickets&amp;utm_campaign=woocommerceplugin">WooCommerce.com でサポートリクエストをオープン</a>してください。</p><p>お問い合わせの前に、システムステータスページをチェックして構成に何らかの問題がないか確認することをおすすめします。</p><p><a href="http://demowordpress.bnksolution.xyz/wp-admin/admin.php?page=wc-status" class="button button-primary">システム状況</a> <a href="https://wordpress.org/support/plugin/woocommerce" class="button">コミュニティフォーラム</a> <a href="https://woocommerce.com/my-account/create-a-ticket/?utm_source=helptab&amp;utm_medium=product&amp;utm_content=tickets&amp;utm_campaign=woocommerceplugin" class="button">WooCommerce.com サポート</a></p>';
        if ($current_screen->id == 'toplevel_page_checkout-confirm' || strpos($current_screen->id, 'page_checkout-confirm-configure-fields') !== false) {

            $current_screen->add_help_tab(
                array(
                    'id'      => 'checkout-confirm-help-tab',
                    'title'   => esc_html__('Basic Help', 'cart-checkout-confirmation'),
                    'content' => $content
                )
            );
            $current_screen->add_help_tab(
                array(
                    'id'      => 'checkout-confirm-bug-report-tab',
                    'title'   => esc_html__('Found a bug?', 'cart-checkout-confirmation'),
                    'content' => '<h2>バグを発見した場合</h2><p>WooCommerce コア内にバグを見つけた場合は、<a href="https://github.com/woocommerce/woocommerce/issues?state=open">GitHub イシュー</a>経由でチケットを作成できます。 レポートの送信前に、必ず<a href="https://github.com/woocommerce/woocommerce/blob/trunk/.github/CONTRIBUTING.md">参加ガイド</a>をお読みください。 問題解決に役立てるため、できるだけ詳しく説明し、<a href="http://demowordpress.bnksolution.xyz/wp-admin/admin.php?page=wc-status">システムステータスレポート</a>を含めるようにしてください。</p><p><a href="https://github.com/woocommerce/woocommerce/issues/new?assignees=&amp;labels=&amp;template=1-bug-report.yml" class="button button-primary">バグを報告</a> <a href="http://demowordpress.bnksolution.xyz/wp-admin/admin.php?page=wc-status" class="button">システム状況</a></p>'
                )
            );
        }
    }

    public function configure_fields_admin_page()
    {
        require $this->admin_path . 'views' . DIRECTORY_SEPARATOR . 'pro_version.php';
    }

    public function cart_checkout_custom_button_html($orderButtonText)
    {
        $option = get_option('check_confirm_page_option');
        $license = get_option('license_checkout_confirm', '');

        $checkoutButtonText = esc_html__($option['checkout_button'], 'cart-checkout-confirmation');
        $checkoutColor = $option['text_color_checkout_button'];
        $bgCheckoutColor = $option['background_color_checkout_button'];
        $checkoutColor = $checkoutColor ? $checkoutColor : '#FFFFFF';
        $bgCheckoutColor = $bgCheckoutColor ? $bgCheckoutColor : '#333333';
        $html = '';

        if (!empty($checkoutButtonText) && !empty($license) && $license != 'Free') {
            $html = '<button type="submit" style="background-color: ' . $bgCheckoutColor . '; color: ' . $checkoutColor . '" 
                class="button alt cart-checkout-style" 
                name="woocommerce_checkout_place_order" id="place_order" 
                value="' . esc_attr($checkoutButtonText) . '" 
                data-value="' . esc_attr($checkoutButtonText) . '">' . esc_html($checkoutButtonText) . '</button>';
        } else {
            $styleHtml = "<style>.button.alt{background-color: $bgCheckoutColor !important;color: $checkoutColor !important;}</style>";
            $html = $orderButtonText . $styleHtml;
        }

        return $html;
    }

    /**
     * wp_editor() call is initialized if needed after page load.
     */
    public function load_admin_scripts()
    {
        wp_enqueue_editor();
    }
}
