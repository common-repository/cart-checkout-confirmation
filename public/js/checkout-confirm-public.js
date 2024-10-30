(function ($) {
    $(document.body).on('checkout_error', function () {
        var error_text = $('.woocommerce-error').find('li').first().text();
        if (error_text.trim() == 'Need confirm before place order') {
            if (confirmation_type == 'popup') {
                var html = '';
                $.ajax({
                    url: wc_add_to_cart_params.ajax_url,
                    type: 'POST',
                    dataType: 'json',
                    data: {'action': 'get_checkout_confirm_html'},
                    success: function (res) {
                        Swal.fire({
                            title: title_langague,
                            icon: 'info',
                            html: res.data.html,
                            showCloseButton: true,
                            showCancelButton: true,
                            focusConfirm: false,
                            confirmButtonText:
                            confirm_button,
                            cancelButtonText:
                            back_button,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                if ($('form[name="checkout"] input[name="checkout_confirm"]').length == 0) {
                                    $('form[name="checkout"]').append('<input name="checkout_confirm" type="hidden" value="' + 1 + '"/>');
                                }
                                $('#place_order').trigger("click");
                            }
                        });
                    }
                });
            } else if ($('.woocommerce-error').find('li').length == 1) {
                window.location.href = checkout_confirm_page;
            }
        }
    });

    $(document).on("click", ".place_order", function (e) {
        e.preventDefault();

        if ($(".checkout-confirm-wrap").is('.processing')) {
            return false;
        }
        
        if (sessionStorage.checkout_data) {
            $(".checkout-confirm-wrap").addClass("processing");

            $.ajax({
                type: 'POST',
                url: c3_params_ajax.home_url +'/?wc-ajax=checkout',
                data: sessionStorage.checkout_data + '&checkout_confirm=1',
                dataType: 'json',
                success: function (result) {
                    // Detach the unload handler that prevents a reload / redirect
                    $(".checkout-confirm-wrap").removeClass("processing");
                    try {
                        if ('success' === result.result) {
                            localStorage.removeItem("checkout_data");
                            if (-1 === result.redirect.indexOf('https://') || -1 === result.redirect.indexOf('http://')) {
                                window.location = result.redirect;
                            } else {
                                window.location = decodeURI(result.redirect);
                            }
                        } else if ('failure' === result.result) {
                            throw 'Result failure';
                        } else {
                            throw 'Invalid response';
                        }
                    } catch (err) {
                        // Reload page
                        if (true === result.reload) {
                            window.location.reload();
                            return;
                        }
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    // Detach the unload handler that prevents a reload / redirect
                    $(".checkout-confirm-wrap").removeClass("processing");
                }
            });
        }
    });

    $('form.woocommerce-checkout').on(
        'checkout_place_order', function () {
            sessionStorage.setItem('checkout_data', $('form[name="checkout"]').serialize());
        }
    );

    $(document).ready(function () {
        $(".cc-button-back").on("click", function (e) {
            e.preventDefault();
            localStorage.removeItem("checkout_data");
            window.history.back();
        })
    });
})(jQuery);

