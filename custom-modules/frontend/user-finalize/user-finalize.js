jQuery(document).ready(function ($) {
    /* Finalize Cart Items */
    $('#user_deal_finalize_frm').on('submit', function () {
        var data = $(this).serialize();
        var l = Ladda.create(document.getElementById('usr_deal_finalize_sbmt'));
        l.start();
        //$('.loader').show();
        $.post(Finalize.ajaxurl, data, function (resp) {
            //$('.loader').hide();
            if (resp.flag == true) {
                $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                site_redirect_finalize(resp.url);
            } else {
                $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 3000});
            }
        }, 'json').always(function () {
            l.stop();
        });
    });


    /* Finalize Update Items */
    $('#user_deal_finalize_update_frm').on('submit', function () {
        var data = $(this).serialize();
        var l = Ladda.create(document.getElementById('usr_deal_finalize_update_sbmt'));
        l.start();
        //$('.loader').show();
        $.post(Finalize.ajaxurl, data, function (resp) {
            //$('.loader').hide();
            if (resp.flag == true) {
                $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                site_redirect_finalize(resp.url);
            } else {
                $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 3000});
            }
        }, 'json').always(function () {
            l.stop();
        });
    });

    $('.update-finalize-deal').on('click', function () {
       var _this = $(this);
       var _this_deal_name = _this.data('deal_name');
       var _this_deal_desc = _this.data('deal_desc');
        /*console.log(_this_deal_name);
        console.log(_this_deal_desc);
        return;*/
       $('#user_frgt_deal_name').val(_this_deal_name);
       $('#user_frgt_deal_desc').val(_this_deal_desc);
       $('#user_deal_finalize_update_popup').modal('show');
    });


});

/* Site redirection */
function site_redirect_finalize(url) {
    if (url === undefined)
        url = '';
    setTimeout(function () {
        window.location.href = url;
    }, 10000);
}