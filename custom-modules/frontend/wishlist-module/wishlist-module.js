jQuery(document).ready(function ($) {

    /* Add to wishlist */
    $('.add-to-wishlist').on('click', function () {
        var _this = $(this);
        var _this_pro = _this.data('pro');
        var _this_type = _this.data('type');
        var data = {
            action: 'add_to_wishlist',
            product: _this_pro,
            wish_type: _this_type
        };
        $('.loader').show();
        $.post(Wishlist.ajaxurl, data, function (resp) {
            $('.loader').hide();
            if (resp.flag == true) {
                $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                wishlist_site_redirect(resp.url);
            } else {
                $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
            }
        }, 'json');
    });

    /* Remove from wishlist */
    $('.remove-from-wishlist').on('click', function () {
        var _this = $(this);
        var _this_pro = _this.data('pro');
        var _this_state = _this.data('state');
        var _this_city = _this.data('city');
        var data = {
            action: 'remove_from_wishlist',
            product: _this_pro,
            state: _this_state,
            city: _this_city
        };
        $('.loader').show();
        $.post(Wishlist.ajaxurl, data, function (resp) {
            $('.loader').hide();
            if (resp.flag == true) {
                $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                wishlist_site_redirect(resp.url);
            } else {
                $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
            }
        }, 'json');
    });
});

/* Site redirection */
function wishlist_site_redirect(url) {
    if (url === undefined)
        url = '';
    setTimeout(function () {
        window.location.href = url;
    }, 1000);
}