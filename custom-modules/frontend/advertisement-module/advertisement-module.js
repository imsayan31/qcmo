jQuery(document).ready(function ($) {
    jQuery('.advert-click').on('click', function () {
        var _this_adv = $(this).data('adv');
        var data = {
            action: 'advertisement_click',
            adv_id: _this_adv
        };
        jQuery.post(AdvertisementModule.ajaxurl, data, function (resp) {
            if (resp.flag == true) {
                /*var win = window.open(resp.url, '_blank');
                win.focus();*/
            } else {

            }
        }, 'json');
    });
});



