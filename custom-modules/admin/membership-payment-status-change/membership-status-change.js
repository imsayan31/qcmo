jQuery(document).ready(function ($) {
    $('.membership-status-change').on('change', function () {
        var _this = $(this);
        var _this_order = _this.data('order');
        var _this_val = _this.val();
        var data = {
            action: 'membership_payment_status_change',
            order: _this_order,
            status: _this_val
        };
        $.post(MembershipPaymentStatus.ajaxurl, data, function (resp) {
            if (resp.flag == true) {
                window.location.href = resp.url;
            } else {

            }
        }, 'json');
    });
});