jQuery(document).ready(function ($) {

    /* Deal Status Change */
    $('.user-deal-status-change').on('change', function () {
        var _this_val = $(this).val();
        var _this_deal = $(this).data('deal');
        var data = {
            action: 'deal_status_change',
            deal: _this_deal,
            status: _this_val
        };
        $('.loader').show();
        $.post(UserDealStatusChange.ajaxurl, data, function (resp) {
            $('.loader').hide();
            if (resp.flag == true) {
                $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                deal_site_redirect(resp.url);
            } else {
                $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
            }
        }, 'json');
    });

    /* Deal Details Popup */
    $('.open-deal-details-update-popup').on('click', function () {
        var _this = $(this);
        var _this_deal = _this.data('deal_id');
        var data = {
            action: 'open_deal_update',
            deal_id: _this_deal
        };
        $('.loader').show();
        $.post(UserDealStatusChange.ajaxurl, data, function (resp) {
            $('.loader').hide();
            if (resp.flag == true) {
                $('#deal_id').val(resp.deal_id);
                $('#user_frgt_deal_name').val(resp.deal_name);
                $('#user_frgt_deal_desc').val(resp.deal_desc);
                $('#user_deal_finalize_update_popup').modal('show');
            } else {
                $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
            }
        }, 'json');
    });

    /* Deal Materials Popup */
    $('.open-material-list-popup').on('click', function () {
        var _this = $(this);
        var _this_deal = _this.data('deal_id');
        var data = {
            action: 'open_deal_material_list',
            deal_id: _this_deal
        };
        $('.loader').show();
        $.post(UserDealStatusChange.ajaxurl, data, function (resp) {
            $('.loader').hide();
            if (resp.flag == true) {
                $('#user_deal_id').val(resp.deal_id);
                $('.select-cart-category').html(resp.msg);
                $('.select-cart-category').trigger("chosen:updated");
                $('.select-cart-category').trigger("liszt:updated");
                $('#user_cart_category_popup').modal('show');
            } else {
                $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
            }
        }, 'json');
    });

    /* Deal Locking Popup */
    $('.deal-lock-click').on('click', function () {
        var _this = $(this);
        var _this_deal = _this.data('deal_id');
        var data = {
            action: 'deal_locking',
            deal_id: _this_deal
        };
        $('.loader').show();
        $.post(UserDealStatusChange.ajaxurl, data, function (resp) {
            $('.loader').hide();
            if (resp.flag == true) {
                 $('.deal-unlock-click').show();
                 $('.deal-lock-click').hide();
                //_this.html('<i class="fa fa-unlock-alt" aria-hidden="true"></i>');
               // _this.removeClass('deal-lock-click');
               // _this.addClass('deal-unlock-click');
               // $('.deal-details-link').html(resp.url);
               // $('#user_deal_locking_popup').modal('show');
                $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
            } else {
                $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
            }
        }, 'json');
    });

    /* Deal Unlocking Popup */
    $('.deal-unlock-click').on('click', function () {
        var _this = $(this);
        var _this_deal = _this.data('deal_id');
        var data = {
            action: 'deal_unlocking',
            deal_id: _this_deal
        };
        $('.loader').show();
        $.post(UserDealStatusChange.ajaxurl, data, function (resp) {
            $('.loader').hide();
            if (resp.flag == true) {
                $('.deal-lock-click').show();
                 $('.deal-unlock-click').hide();
               // _this.html('<i class="fa fa-lock" aria-hidden="true"></i>');
                //_this.removeClass('deal-unlock-click');
                //_this.addClass('deal-lock-click');
                $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
            } else {
                $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
            }
        }, 'json');
    });

    /* Deal Get Shareable Link */
    $('.deal-share-link-click').on('click', function () {
        var _this = $(this);
        var _this_deal = _this.data('deal_id');
        var data = {
            action: 'deal_get_shareable_link',
            deal_id: _this_deal
        };
        $('.loader').show();
        $.post(UserDealStatusChange.ajaxurl, data, function (resp) {
            $('.loader').hide();
            if (resp.flag == true) {
                $('.deal-details-msg').hide();
                $('.deal-details-link input').val(resp.url);
                //$('#deal_details_link').val(resp.url);
                $('#user_deal_locking_popup').modal('show');
            } else {
                $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
            }
        }, 'json');
    });

    /* Copy To Cart */
    $('.copy-to-cart').on('click', function () {
        var _this = $(this);
        var _this_deal = _this.data('deal_id');
        var data = {
            action: 'copy_to_cart',
            deal_id: _this_deal
        };
        
        if(UserDealStatusChange.user_logged_in == 2) {
            $('a[href="#user_login_popup"]').trigger('click');
            
        } else {
        
        $('.loader').show();
        $.post(UserDealStatusChange.ajaxurl, data, function (resp) {
            $('.loader').hide();
            if (resp.flag == true) {
                $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                deal_site_redirect(resp.url);
            } else {
                $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
            }
        }, 'json');
        }    
    });

    /* Erasing Deals */
    $('.erase-deal').on('click', function(){
        if (confirm('Tem certeza de que deseja apagar todas as suas ofertas?')) {
            var deal_id = $(this).data('deal_id');
            var data = {
               action: 'erase_user_deals',
               deal_id: deal_id
            };
            $('.loader').show();
            $.post(UserDealStatusChange.ajaxurl, data, function (resp) {
                $('.loader').hide();
                if (resp.flag == true) {
                    $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                    deal_site_redirect(resp.url);
                } else {
                    $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
                }
            }, 'json');
        } else {
            
        }
    });

});

/* Site redirection */
function deal_site_redirect(url) {
    if (url === undefined)
        url = '';
    setTimeout(function () {
        window.location.href = url;
    }, 2000);
}