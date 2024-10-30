(function ($) {
    $('.field-item').draggable({
        connectToSortable: '.group-list',
        items: '> div',
        helper: 'clone',
        start: function (event, ui) {
            $(ui).css('width', '313px');
        },
        cursor: "all-scroll"
    });
    $('.group-list').sortable({
        items: '> div',
        helper: 'clone',
        receive: function (event, ui) {
            var cnt = 0;
            $(this).children().each(function (i, e) {
                if ($(ui.item).data('key') == $(e).data('key')) {
                    cnt++;
                    if (cnt > 1) $(e).remove();
                }
            });
        },
        update: function (event, ui) {
            var sender = ui.sender,
                item = $(ui.item);
            var group = item.data('group');
            // var label = group.charAt(0).toUpperCase() + group.slice(1) + ' ' + item.find('span').html();
            var label = group.charAt(0).toUpperCase() + group.slice(1) + ' ' + item.data('label');
            item.html(label);
            item.append('<input type="hidden" name="fields[' + item.data('key') + ']" value="' + label + '"/>');
            item.append('<button type="button" name="remove_item" onclick="removeItem(this)"><span class="dashicons dashicons-trash"></span></button>')
        }
    });

    var indexTextArea = 0;
    var checkVersion = $('input[name="c3-version-name"]').val();

    $('body').on('click', '.c3-input-group__textarea .add-field__textarea', function () {
        var indexTextArea = 0;
        let tol = $('.c3-textarea-add').length;
        let textAreaIndex = [];
        let inputName = 0;

        $('.c3-textarea-add .title-name').each(function (index, value) {
            indexTextArea = index + 1;
            inputName = $(this).attr('name');
            textAreaIndex.push(parseInt(inputName.match(/\d+/g)));
        });

        let maxNumber = Math.max(...textAreaIndex);
        maxNumber += 1;

        var titlePrimary = $('input[name="title-primary"]').val();
        var textAreaTitle = $('input[name="textarea-title"]').val();

        if (indexTextArea <= 20) {

            let textAreaApply = '<textarea name="additional[textarea-' + maxNumber + ']" id="textarea-' + maxNumber + '" cols="30" rows="10" style="height: 250px;" class="c3-editor"></textarea>';

            let html = '<div class="c3-textarea-add"><label for="title" class="title-primary">' + titlePrimary + '</label>' +
                '<input type="text" name="additional[title-' + maxNumber + ']" class="title-name" value="" id="title-' + maxNumber + '">' +
                '<br/><label for="textarea-content" class="textarea-title" class="title-name">' + textAreaTitle + '</label> ' +
                textAreaApply + '<div class="c3-btn-add__fields"><p class="remove"><span class="remove-icon"></span></p>' +
                '<span class="add-field__textarea">+</span></div>';
            $(this).closest(".c3-textarea-add").after(html);
        }

        wp.editor.initialize('textarea-' + maxNumber, {
            tinymce: {
                toolbar1: 'bold,italic,bullist,numlist,undo,redo,'
            },
            quicktags: true
        });
    });

    $('body').on('click', '.c3-input-group__textarea .remove', function () {
        let textAlert = $('input[name="alert-delete-textarea"]').val();

        if (confirm(textAlert) == true) {
            $(this).closest(".c3-textarea-add").remove();
            $('.c3-textarea-add').each(function (index, value) {
                indexTextArea = index + 1;
            });
        }
    });

    if (checkVersion == 'Free') {
        $("<div class='textarea-wrap__layer'></div>").prependTo("#wp-desired_id_of_textarea-wrap");
    }

})(jQuery);

function removeItem(e) {
    jQuery(e).parent('.field-item').remove();
}