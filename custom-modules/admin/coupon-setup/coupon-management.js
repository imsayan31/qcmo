jQuery(document).ready(function ($) {

    /* Add product attribute */
    $('.select-coupon-user-type').on('change', function () {
        var _this_val = $(this).val();

        var data = {
            action: 'coupon_user_type_selection',
            user_type: _this_val
        };
        $.post(CouponManagement.ajaxurl, data, function (resp) {
            if (resp.flag == true) {
                $('.populate_coupon_users').html(resp.msg);
            } else {

            }
        }, 'json');
    });

    /* Send email to users after setting up coupon */
    $('.click-coupon-send-email').on('click', function(){
        var val = $(this).data('coupon_id');
        var data = {
            action: 'send_email_after_coupon_created',
            coupon: val
        };
        $.post(CouponManagement.ajaxurl, data, function (resp) {
            if (resp.flag == true) {
                $('.coupon-success-msg').show();
            } else {
                alert(resp.msg);
                $('.coupon-success-msg').hide();
            }
        }, 'json');
    });

});