jQuery(document).ready(function ($) {
    /* Add to cart */
    $('.add-to-cart').on('click', function () {
        var _this = $(this);
        var _this_pro = _this.data('pro');
        var _this_state = _this.data('state');
        var _this_city = _this.data('city');
        var single_no_of_items = $('#single_no_of_items').val();
        var data = {
            action: 'add_to_cart',
            product: _this_pro,
            state: _this_state,
            city: _this_city,
            single_no_of_items: single_no_of_items
        };
        $('.loader').show();
        $.post(Cart.ajaxurl, data, function (resp) {
            $('.loader').hide();
            if (resp.flag == true) {
                $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                cart_site_redirect(resp.url);
            } else {
                $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
            }
        }, 'json');
    });

    /* Remove from cart */
    $('.remove-from-cart').on('click', function () {
        var _this = $(this);
        var _this_pro = _this.data('pro');
        var _this_state = _this.data('state');
        var _this_city = _this.data('city');
        var data = {
            action: 'remove_from_cart',
            product: _this_pro,
            state: _this_state,
            city: _this_city
        };
        $('.loader').show();
        $.post(Cart.ajaxurl, data, function (resp) {
            $('.loader').hide();
            if (resp.flag == true) {
                $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                cart_site_redirect(resp.url);
            } else {
                $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
            }
        }, 'json');
    });

    /* Clear from cart */
    $('.clear-cart-items').on('click', function () {
        var data = {
            action: 'clear_cart_items'
        };
        $('.loader').show();
        $.post(Cart.ajaxurl, data, function (resp) {
            $('.loader').hide();
            if (resp.flag == true) {
                $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                cart_site_redirect(resp.url);
            } else {
                $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
            }
        }, 'json');
    });

    /* Update cart items */
    $('.update-cart-items').on('click', function () {
        var data;
        if ($(window).width() >= 1024) {
            data = $('#user-cart-frm').serialize();
        } else {
            data = $('#user-cart-mobile-frm').serialize();
        }
        $('.loader').show();
        $.post(Cart.ajaxurl, data, function (resp) {
            $('.loader').hide();
            if (resp.flag == true) {
                $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                cart_site_redirect(resp.url);
            } else {
                $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
            }
        }, 'json');
    });

    /* Get Material List */
    $('#user_cart_category_frm').on('submit', function () {
        var data = $(this).serialize();
        var l = Ladda.create(document.getElementById('usr_cart_category_sbmt'));
        l.start();
        $.post(Cart.ajaxurl, data, function (resp) {
            if (resp.flag == true) {
                $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                //window.open(resp.url, '_blank', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');
                /*var win = window.open(resp.url, '_blank');
                if (win) {
                    //Browser has allowed it to be opened
                    win.focus();
                }*/
                cart_site_redirect(resp.url);
            } else {
                $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
            }
        }, 'json').always(function () {
            l.stop();
        });
    });

});

/* Site redirection */
function cart_site_redirect(url) {
    if (url === undefined)
        url = '';
    setTimeout(function () {
        window.location.href = url;
    }, 1000);
}