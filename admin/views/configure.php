<?php

function pp_admin_tabs()
{
    $tabs = array(
        'fields' => esc_html__('Display Fields', 'cart-checkout-confirmation'),
        'option' => esc_html__('Options', 'cart-checkout-confirmation'),
        'additional' => esc_html__('Additional Display', 'cart-checkout-confirmation')
    );
    return apply_filters('pp_admin_tabs', $tabs);
}

?>
<div id="wpbody-content">
    <?php
    $tabs = pp_admin_tabs();
    $current = sanitize_text_field($_GET['tab'] ?? '');
    $license = get_option('license_checkout_confirm', '');

    if (!$current) $current = 'fields';
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">
            <?php
            if (!$license || $license == 'Free') {
                echo esc_html__('Cart Checkout Confirmation', 'cart-checkout-confirmation');
            } else {
                echo esc_html__('Cart Checkout Confirmation Pro', 'cart-checkout-confirmation');
            }
            ?>
        </h1>
        <h3 class="nav-tab-wrapper">
            <?php if (!empty($tabs)) {
                foreach ($tabs as $key => $value) {
                    $class = ($key == $current) ? ' nav-tab-active' : '';
            ?>
                    <a href="?page=cart-checkout-confirmation&tab=<?php echo esc_attr($key); ?>" class="nav-tab<?php echo esc_attr($class); ?>"><?php echo esc_attr($value); ?></a>
            <?php
                }
            }
            ?>
        </h3>
        <div class="pp-admin-content">
            <?php do_action('checkout_confirm_config_tab', $current); ?>
        </div>
    </div>
</div>