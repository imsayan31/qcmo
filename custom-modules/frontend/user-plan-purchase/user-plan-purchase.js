jQuery(document).ready(function ($) {

    /* Purchase link click */
    $('.click-purchase').on('click', function () {
        var _this = $(this);
        var _this_plan = _this.data('plan');
        $('#selected_plan').val(_this_plan);
        $('.click-plan-price').prop('checked', '');
        $('.show-plan-price').html('');
    });

    /* Purchase period click */
    $(document.body).on('change', '.click-plan-price', function () {
        var _this = $(this);
        var _plan_val = $('#selected_plan').val();
        var data = {
            action: 'show_plan_price',
            period_val: _this.val(),
            plan_val: _plan_val
        };
        $('.loader').show();
        $.post(UserPlanPurchase.ajaxurl, data, function (resp) {
            $('.loader').hide();
            if (resp.flag == true) {
                $('.show-plan-price').html('<h2>Valor a ser pago: <span>R$ ' + resp.msg + '</span></h2>');
            } else {

            }
        }, 'json');
    });


    $('#usr_plan_purchase_frm').on('submit', function () {
        var data = $(this).serialize();
        var l = Ladda.create(document.getElementById('usr_plan_purchase_sbmt'));
        l.start();
        $.post(UserPlanPurchase.ajaxurl, data, function (resp) {
            if (resp.flag == true) {
                $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                site_redirect(resp.url);
            } else {
                $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
            }
        }, 'json').always(function () {
            l.stop();
        });
    });
});

/* Site redirection */
function site_redirect(url) {
    if (url === undefined)
        url = '';
    setTimeout(function () {
        window.location.href = url;
    }, 2000);
}