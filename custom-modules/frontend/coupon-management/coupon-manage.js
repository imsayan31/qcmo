jQuery(document).ready(function ($) {
    
    //$("#donate_amount").mask("9.99");

    /* Apply Announcement Coupon */
    $('#usr_coupon_sbmt').on('click', function(){
        console.log(123);
        var membership_coupon = $('#membership_coupon').val();
        var announcement_plan = $('#selected_announcement_plan').val();
        var data = {
            action: 'apply_coupon_code',
            coupon_value: membership_coupon,
            announcement_plan: announcement_plan
        };
        var l = Ladda.create(document.getElementById('usr_coupon_sbmt'));
        l.start();
        $.post(CouponManage.ajaxurl, data, function(resp){
            if(resp.flag == true){
                $('.coupon-applied-data').html(resp.total_payable_price_html);
                $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
            } else{
                $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
            }
        },'json').always(function () {
            l.stop();
        });
    });

    /* Renew Announcement Coupon */
    $('#usr_coupon_renew_sbmt').on('click', function(){
        console.log(123);
        var membership_coupon = $('#membership_coupon_renew').val();
        var announcement_plan = $('#renew_announcement_plan').val();
        var announement_plan_selection = $('.announement-plan-selection:checked').val();
        var announcement_renewal_period = $('#announcement_renewal_period').val();

        var data = {
            action: 'apply_coupon_code_renew',
            coupon_value: membership_coupon,
            announcement_plan: announcement_plan,
            announement_plan_selection: announement_plan_selection,
            announcement_renewal_period: announcement_renewal_period,
        };
        var l = Ladda.create(document.getElementById('usr_coupon_renew_sbmt'));
        l.start();
        $.post(CouponManage.ajaxurl, data, function(resp){
            if(resp.flag == true){
                $('.coupon-applied-data').html(resp.total_payable_price_html);
                $('.show-announce-plan-price').hide();
                $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
            } else{
                $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
            }
        },'json').always(function () {
            l.stop();
        });
    });

    /* Apply Membership Coupon */
    $('#usr_membership_coupon_sbmt').on('click', function(event){
        event.preventDefault();
        console.log(456);
        var membership_coupon = $('#new_membership_coupon').val();
        var plan_val = $('#selected_plan').val();
        var period_val = $('.click-plan-price:checked').val();
        var data = {
            action: 'apply_coupon_code_membership',
            coupon_value: membership_coupon,
            plan_val: plan_val,
            period_val: period_val
        };
        var l = Ladda.create(document.getElementById('usr_membership_coupon_sbmt'));
        l.start();
        $.post(CouponManage.ajaxurl, data, function(resp){
            if(resp.flag == true){
                //$('.show-plan-price').html('<h2>Valor a ser pago: <span>R$ ' + resp.calculatePrice + '</span></h2>');
                $('.coupon-applied-data').html(resp.total_payable_price_html);
                $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
            } else{
                $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
            }
        },'json').always(function () {
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
    }, 6000);
}
