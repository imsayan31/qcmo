jQuery(document).ready(function ($) {
    /* Deal review action */
    $('.change-user-review').on('change', function () {
        var _this = $(this);
        var _this_val = _this.val();
        var _this_deal = _this.data('deal');
        var _this_user = _this.data('user');
        var _this_supplier = _this.data('supplier');
        
        var data = {
            action: 'admin_change_deal_review_status',
            status_val: _this_val,
            deal: _this_deal,
            user: _this_user,
            supplier: _this_supplier
        };
        $.post(DealReview.ajaxurl, data, function (resp) {
            if (resp.flag == true) {
                window.location.href = resp.url;
            } else {

            }
        }, 'json');
    });
});