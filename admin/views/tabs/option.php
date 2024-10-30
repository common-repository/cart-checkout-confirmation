<?php
$option = get_option('check_confirm_page_option');
$disabledClass = '';
$checkoutButton = esc_html__('Place order', 'cart-checkout-confirmation');
$confirmButton = esc_html__('Order', 'cart-checkout-confirmation');

if (!empty($option['checkout_button']) && !empty($license) && $license != 'Free') {
  $checkoutButton = esc_html__($option['checkout_button']);
}

if (!empty($option['confirm_button']) && !empty($license) && $license != 'Free') {
  $checkoutButton = esc_html__($option['confirm_button']);
}

if (!$license || $license == 'Free') {
  $disabledClass = '--active';
}
?>
<div class="drag-main ui-tabs-panel">
  <h3><?php echo esc_html__('Display Option', 'cart-checkout-confirmation'); ?></h3>
  <h3 class="free-text<?php echo $disabledClass; ?>">
    <?php echo esc_html__('Various settings can be changed by downloading the Pro version plugin (paid).', 'cart-checkout-confirmation'); ?>
  </h3>
  <form action="" method="POST">
    <table class="form-table">
      <?php
      $textColor = $option['text_color_button'] ? $option['text_color_button'] : '#FFFFFF';
      $bgColor = $option['background_color_button'] ? $option['background_color_button'] : '#7367F0';
      $checkoutColor = $option['text_color_checkout_button'] ? $option['text_color_checkout_button'] : '#FFFFFF';
      $bgCheckoutColor = $option['background_color_checkout_button'] ? $option['background_color_checkout_button'] : '#333333';
      ?>
      <tbody>
        <tr>
          <th scope="row">
            <label><?php echo esc_html__('Type', 'cart-checkout-confirmation'); ?></label>
          </th>
          <td>
            <input type="radio" id="popup" name="option[type]" value="popup" <?php if ($option['type'] == 'popup' || $license == 'Free') echo esc_attr('checked'); ?> />
            <label for="popup" style="margin-right: 20px;"><?php echo esc_html__('Popup', 'cart-checkout-confirmation'); ?></label>
            <input type="radio" id="redirect" name="option[type]" value="redirect" <?php if ($option['type'] == 'redirect' && $license != 'Free') echo esc_attr('checked'); ?> <?php if (!$license || $license == 'Free') echo esc_attr('disabled'); ?> />
            <label for="redirect"><?php echo esc_html__('Redirect to Confirm Page', 'cart-checkout-confirmation'); ?></label>
          </td>
        </tr>
        <tr>
          <th class="row">
            <h3><?php echo esc_html__('Checkout Button', 'cart-checkout-confirmation'); ?></h3>
          </th>
        </tr>
        <tr>
          <th scope="row">
            <label for="checkout_button"><?php echo esc_html__('Change Text', 'cart-checkout-confirmation'); ?></label>
          </th>
          <td class="dp-table">
            <input type="text" id="checkout_button" name="option[checkout_button]" <?php if (!$license || $license == 'Free') echo esc_attr('disabled'); ?> value="<?php echo esc_attr($checkoutButton); ?>">
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label><?php echo esc_html__('Change Text Color', 'cart-checkout-confirmation'); ?></label>
          </th>
          <td class="dp-table btn-text-color__checkout">
            <input type="text" name="option[text_color_checkout_button]" <?php if (!$license || $license == 'Free') echo esc_attr('disabled'); ?> value="<?php echo esc_attr($checkoutColor); ?>" />
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label><?php echo esc_html__('Change Background Color', 'cart-checkout-confirmation'); ?></label>
          </th>
          <td class="dp-table btn-bg-color__checkout">
            <input type="text" name="option[background_color_checkout_button]" <?php if (!$license || $license == 'Free') echo esc_attr('disabled'); ?> value="<?php echo esc_attr($bgCheckoutColor); ?>" />
          </td>
        </tr>
        <tr>
          <th class="row">
            <h3><?php echo esc_html__('Order Button', 'cart-checkout-confirmation'); ?></h3>
          </th>
        </tr>
        <tr>
          <th scope="row">
            <label for="confirm_button"><?php echo esc_html__('Change Text', 'cart-checkout-confirmation'); ?></label>
          </th>
          <td class="dp-table">
            <input type="text" id="confirm_button" name="option[confirm_button]" value="<?php esc_attr_e($confirmButton, 'cart-checkout-confirmation'); ?>" <?php if (!$license || $license == 'Free') echo esc_attr('disabled'); ?> />
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label><?php echo esc_html__('Change Text Color', 'cart-checkout-confirmation'); ?></label>
          </th>
          <td class="dp-table btn-text-color">
            <input type="text" name="option[text_color_button]" <?php if (!$license || $license == 'Free') echo esc_attr('disabled'); ?> value="<?php echo esc_attr($textColor); ?>" />
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label><?php echo esc_html__('Change Background Color', 'cart-checkout-confirmation'); ?></label>
          </th>
          <td class="dp-table btn-bg-color">
            <input type="text" name="option[background_color_button]" <?php if (!$license || $license == 'Free') echo esc_attr('disabled'); ?> value="<?php echo esc_attr($bgColor); ?>" />
          </td>
        </tr>
      </tbody>
    </table>
    <input type="hidden" name="submit_option_form" value="1" />
    <button class="button button-primary" type="submit"><?php echo esc_html__('Save', 'cart-checkout-confirmation'); ?></button>
  </form>
</div>

<script>
  jQuery(document).ready(function($) {
    $('input[name="option[text_color_button]"]').wpColorPicker();
    $('input[name="option[background_color_button]"]').wpColorPicker();
    $('input[name="option[text_color_checkout_button]"]').wpColorPicker();
    $('input[name="option[background_color_checkout_button]"]').wpColorPicker();

    <?php if (!$license || $license == 'Free') : ?>
      $('.btn-text-color .wp-color-result').attr('disabled', 'disabled');
      $('.btn-bg-color .wp-color-result').attr('disabled', 'disabled');
      $('.btn-text-color__checkout .wp-color-result').attr('disabled', 'disabled');
      $('.btn-bg-color__checkout .wp-color-result').attr('disabled', 'disabled');
    <?php endif; ?>
  });
</script>