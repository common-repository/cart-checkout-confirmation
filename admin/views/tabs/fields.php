<?php
if (!isset($fields)) {
  $fields = [];
}

$getOption = get_option('confirm_on_off_text_bill');
?>
<form action="" method="post" class="form-fields">
  <div class="drag-main ui-tabs-panel version-<?php echo esc_attr($license); ?>">
    <div class="drag-row">
      <div class="drag-left">
        <?php foreach ($fields as $key => $group) : ?>
          <?php $key_i18n = ''; ?>
          <?php if (!$group) continue; ?>
          <div class="group-fields" id="fields-<?php echo esc_attr($key); ?>">
            <h3><?php
                switch ($key) {
                  case 'billing':
                    echo esc_html__('Billing', 'cart-checkout-confirmation');
                    $key_i18n = esc_html__('Billing', 'cart-checkout-confirmation');
                    break;
                  case 'shipping':
                    echo esc_html__('Shipping', 'cart-checkout-confirmation');
                    $key_i18n = esc_html__('Shipping', 'cart-checkout-confirmation');
                    break;
                  case 'order':
                    echo esc_html__('Order', 'cart-checkout-confirmation');
                    $key_i18n = esc_html__('Order', 'cart-checkout-confirmation');
                    break;
                }
                ?>
            </h3>

            <?php foreach ($group as $field_key => $item) : ?>
              <div class="field-item" data-key="<?php echo esc_attr($field_key); ?>" data-label="<?php echo esc_attr($item['label']); ?>" data-group="<?php echo esc_attr($key_i18n); ?>">
                <span><?php echo esc_attr($item['label']) ?></span>
              </div>
            <?php endforeach; ?>

          </div>
        <?php endforeach; ?>
      </div>
      <div class="drag-right">
        <div class="drag-group">
          <div class="field-custom">
            <label class="switch">
              <input type="checkbox" id="field_on_text" name="field_on_off_text" value="On" <?php echo ($getOption == 'On') ? 'checked' : '' ?> <?php if (!$license || $license == 'Free') echo 'disabled'; ?> />
              <span class="slider round"></span>
              <input type="hidden" value="Off" name="field_on_off_text" id="field_off_text_hidden" />
            </label>
            <label class="group-fields" id="switch-text-fields"><?php echo esc_html__('Hide the text "Billing"', 'cart-checkout-confirmation') ?></label>
          </div>
          <h3><?php echo esc_html__('Display fields', 'cart-checkout-confirmation'); ?></h3>

          <div class="group-list" style="min-height: 500px">
            <?php if ($display_fields) : ?>
              <?php foreach ($display_fields as $field_key => $item) : ?>
                <div class="field-item" data-key="<?php echo esc_attr($field_key); ?>">
                  <span><?php echo esc_attr($item); ?></span>
                  <input type="hidden" name="fields[<?php echo esc_attr($field_key); ?>]" value="<?php echo esc_attr($item); ?>">
                  <button type="button" name="remove_item" onclick="removeItem(this)"><span class="dashicons dashicons-trash"></span></button>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
          <input type="hidden" name="save_config_fields" value="1" />
          <button type="submit" class="button button-primary" <?php echo ($license == 'Free') ? 'disabled="disabled"' : ''; ?>><?php echo esc_html__('Save', 'cart-checkout-confirmation') ?></button>


        </div>
      </div>
    </div>
  </div>
</form>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
  $('.form-fields').submit(function(e) {
    if ($('#field_on_text').is(':checked')) {
      $('#field_off_text_hidden').attr('disabled', 'disabled');
    }
  });
</script>