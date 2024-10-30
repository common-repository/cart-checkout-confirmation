<?php if (!is_admin() || is_ajax()) : ?>
    <?php
    $displayFields = get_option('check_confirm_page_fields_setting');
    if (get_option('license_checkout_confirm', '') == 'Free' || !is_plugin_active('cart-checkout-confirmation-pro/cart-checkout-confirmation-pro.php')) {
        $displayFields = Checkout_Confirm::get_free_fields();
    }
    $customer = WC()->session->get('customer');
    ?>
    <?php
    $option = get_option('check_confirm_page_option');
    $additionals = get_option('check_confirm_page_additional');
    $confirmOnOffText = get_option('confirm_on_off_text_bill');

    $textColor = '#FFFFFF';
    $bgColor = '7367F0';
    $conFirmButton = esc_html__('Order', 'cart-checkout-confirmation');

    if (!empty($option['text_color_button'])) {
        $textColor = $option['text_color_button'];
    }

    if (!empty($option['background_color_button'])) {
        $bgColor = $option['background_color_button'];
    }

    if (!empty($option['confirm_button'])) {
        $conFirmButton = esc_html__($option['confirm_button'], 'cart-checkout-confirmation');
    }

    ?>
    <style>
        button.swal2-confirm,
        .place_order.button-place_order {
            background-color: <?php echo esc_attr($bgColor); ?> !important;
            color: <?php echo esc_attr($textColor); ?> !important;
        }
    </style>
    <div class="checkout-confirm-wrap">
        <h3><?php echo esc_html__('Customer information', 'cart-checkout-confirmation') ?></h3>
        <table>
            <?php if ($displayFields) : ?>
                <?php foreach ($displayFields as $key => $displayField) : ?>
                    <?php
                    if ($confirmOnOffText == 'On') {
                        $displayField = str_replace('Billing', '', $displayField);
                        $displayField = str_replace('請求処理', '', $displayField);
                    }
                    ?>
                    <?php if (preg_match('/^billing_+(.*)/', $key)) $key = str_replace('billing_', '', $key); ?>

                    <tr>
                        <td>
                            <label for="<?php echo esc_attr($key); ?>"><?php echo esc_attr($displayField); ?></label>
                        </td>
                        <td>
                            <?php echo esc_attr($customer[$key]); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
        <div class="additional-display">
            <?php
            if (get_option('license_checkout_confirm', '') != 'Free') {
                if (!empty($additionals)) {
                    foreach ($additionals as $additionalKey => $additional) {
                        $titleNumbers = explode("title-", $additionalKey);

                        if (!empty($titleNumbers)) {
                            foreach ($titleNumbers as $titleNumber) {
                                $title = '';
                                $additionalContent = '';
                                $titleNumberKey = 'title-' . $titleNumber;
                                $textareaKey = 'textarea-' . $titleNumber;

                                if ($additionalKey == $titleNumberKey) {
                                    if (!empty($additionals[$titleNumberKey])) {
                                        $title =  $additionals[$titleNumberKey];
                                    }

                                    if (!empty($additionals[$textareaKey])) {
                                        $additionalContent =  $additionals[$textareaKey];
                                    }

            ?>
                                    <h3><?php echo esc_attr($title); ?></h3>
                                    <?php if (!empty($additionalContent)) : ?>
                                        <div class="additional-content"><?php echo htmlspecialchars_decode($additionalContent); ?></div>
                                    <?php endif ?>
            <?php
                                }
                            }
                        }
                    }
                }
            }
            ?>
        </div>
        <h3><?php echo esc_html__('Cart information', 'cart-checkout-confirmation') ?></h3>
        <table>
            <thead>
                <tr>
                    <th class="product-name"><?php echo esc_html__('Product', 'cart-checkout-confirmation') ?></th>
                    <th class="product-price"><?php echo esc_html__('Price', 'cart-checkout-confirmation') ?></th>
                    <th class="product-quantity"><?php echo esc_html__('Quantity', 'cart-checkout-confirmation') ?></th>
                    <th class="product-subtotal"><?php echo esc_html__('Subtotal', 'cart-checkout-confirmation') ?></th>
                </tr>
            </thead>
            <?php foreach (WC()->cart->get_cart() as $cartItemKey => $cartItem) : ?>
                <tr>
                    <?php $product = $cartItem['data']; ?>
                    <td><?php echo esc_attr($product->name); ?></td>
                    <td><?php echo WC()->cart->get_product_price($product); ?></td>
                    <td><?php echo esc_attr($cartItem['quantity']); ?></td>
                    <td><?php echo WC()->cart->get_product_subtotal($product, $cartItem['quantity']); ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3"><?php echo esc_html__("Delivery", 'cart-checkout-confirmation'); ?></td>
                <td><?php echo wc_price(WC()->cart->get_shipping_total()); ?></td>
            </tr>
            <tr>
                <td colspan="3"><?php echo esc_html__("Subtotal", 'cart-checkout-confirmation'); ?></td>
                <td><?php echo WC()->cart->get_total(); ?></td>
            </tr>
            <?php $discount = WC()->cart->get_discount_total(); ?>
            <?php if ($discount > 0) : ?>
                <tr>
                    <td colspan="3"><?php echo esc_html__("Discount", 'cart-checkout-confirmation') ?></td>
                    <td><?php echo wc_price($discount); ?></td>
                </tr>
            <?php endif; ?>
        </table>
        <?php if (!is_ajax()) : ?>
            <div class="confirm-button-groups">
                <div class="button-left">
                    <a href="#" class="cc-button-back button-back"><?php echo esc_html__("Back", 'cart-checkout-confirmation'); ?></a>
                </div>
                <div class="center-button">
                    <button style="background: #1262a2;color: #fff;width: 250px;" class="place_order button-place_order"><?php echo $conFirmButton; ?></button>
                </div>
                <div class="button-right"></div>
            </div>
            <div class="confirm-overlay"></div>
        <?php endif; ?>
    </div>
<?php endif; ?>