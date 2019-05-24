jQuery(document).ready(function ($) {
    $('.deal-status-change').on('change', function () {
        var _this_val = $(this).val();
        var _this_deal = $(this).data('deal');
        var data = {
            action: 'deal_status_change',
            deal: _this_deal,
            status: _this_val
        };
        $.post(DealStatus.ajaxurl, data, function (resp) {
            if (resp.flag == true) {
                site_redirect(resp.url);
            } else {

            }
        }, 'json');
    });
});

/* Site redirection */
function site_redirect(url) {
    if (url === undefined)
        url = '';
    setTimeout(function () {
        window.location.href = url;
    }, 3000);
}