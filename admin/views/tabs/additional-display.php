<?php
$additionals = get_option('check_confirm_page_additional');

$title = '';
$textArea = '';
$disabled = '';
$disabledClass = '';

if (!empty($additionals['title-0']) && !empty($license) && $license != 'Free') {
    $title = $additionals['title-0'];
}

if (!empty($additionals['textarea-0']) && !empty($license) && $license != 'Free') {
    $textArea = $additionals['textarea-0'];
}

if (!$license || $license == 'Free') {
    $disabled = 'disabled';
    $disabledClass = '--disabled';
}

$argType = [
    'textarea_name' => 'additional[textarea-0]',
    'media_buttons' => false,
    'editor_height' => 250,
    'tinymce' => [
        'toolbar1' => 'bold,italic,bullist,numlist,undo,redo,',
    ]
];
?>
<div class="drag-main ui-tabs-panel">
    <div class="container-main-tags">
        <form action="" method="POST">

            <div class="c3-input-group c3-input-group__textarea">
                <div class="c3-textarea-add">
                    <label for="title" class="title-primary"><?php echo esc_html__('Title', 'cart-checkout-confirmation'); ?></label>
                    <input type="text" name="additional[title-0]" class="title-name" <?php echo esc_attr($disabled); ?> value="<?php echo esc_attr($title); ?>" id="title">
                    <br />

                    <label for="textarea-content" class="textarea-title"><?php echo esc_html__('Content', 'cart-checkout-confirmation'); ?></label>

                    <?php
                    wp_editor(
                        htmlspecialchars_decode($textArea),
                        'desired_id_of_textarea',
                        $argType
                    );
                    ?>
                    <div class="c3-btn-add__fields">
                        <span class="add-field__textarea<?php echo esc_attr($disabledClass); ?>">+</span>
                    </div>
                </div>
                <?php if ($license && $license != 'Free') : ?>
                    <?php if (!empty($additionals)) : ?>
                        <?php foreach ($additionals as $additionalKey => $additional) : ?>
                            <?php if ($additionals[$additionalKey] && $additionalKey != 'textarea-0' && $additionalKey != 'title-0') : ?>
                                <?php
                                $titleNumbers = explode("title-", $additionalKey);

                                if (!empty($titleNumbers)) :
                                    foreach ($titleNumbers as $titleNumber) :
                                        $titleNumberKey = 'title-' . $titleNumber;
                                        if ($additionalKey == $titleNumberKey) :
                                ?>
                                            <div class="c3-textarea-add">
                                                <label for="title" class="title-primary"><?php echo esc_html__('Title', 'cart-checkout-confirmation'); ?></label>
                                                <input type="text" name="additional[title-<?php echo $titleNumber; ?>]" <?php echo esc_attr($disabled); ?> class="title-name" value="<?php echo htmlspecialchars_decode($additionals['title-' . $titleNumber]); ?>" id="title">
                                                <br />

                                                <label for="textarea-content" class="textarea-title"><?php echo esc_html__('Content', 'cart-checkout-confirmation'); ?></label>

                                                <?php
                                                $argTypes = [
                                                    'textarea_name' => 'additional[textarea-' . $titleNumber . ']',
                                                    'media_buttons' => false,
                                                    'editor_height' => 250,
                                                    'tinymce' => [
                                                        'toolbar1' => 'bold,italic,bullist,numlist,undo,redo,',
                                                    ]
                                                ];

                                                wp_editor(
                                                    htmlspecialchars_decode(
                                                        $additionals['textarea-' . $titleNumber]
                                                    ),
                                                    'desired_id_of_textarea' . $additionalKey,
                                                    $argTypes
                                                );
                                                ?>
                                                <div class="c3-btn-add__fields">
                                                    <p class="remove"><span class="remove-icon"></span></p>
                                                    <span class="add-field__textarea<?php echo esc_attr($disabledClass); ?>">+</span>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <input type="hidden" name="c3-version-name" value="<?php echo esc_html__($license); ?>" />
            <input type="hidden" name="title-primary" value="<?php echo esc_html__('Title', 'cart-checkout-confirmation'); ?>" />
            <input type="hidden" name="textarea-title" value="<?php echo esc_html__('Content', 'cart-checkout-confirmation'); ?>" />
            <input type="hidden" name="alert-delete-textarea" value="<?php echo esc_html__('Do you want to clear the text area field ?', 'cart-checkout-confirmation'); ?>" />
            <input type="hidden" name="alert-add-textarea" value="<?php echo esc_html__('Do you want to add a textarea field?', 'cart-checkout-confirmation'); ?>" />
            <input type="hidden" name="submit_additional_form" value="1" />
            <div class="c3-input-group">
                <button class="button button-primary" type="submit" <?php echo esc_attr($disabled); ?>><?php echo esc_html__('Save', 'cart-checkout-confirmation'); ?></button>
            </div>
        </form>
    </div>
</div>
<?php
