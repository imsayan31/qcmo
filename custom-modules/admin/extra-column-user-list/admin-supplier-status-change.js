jQuery(document).ready(function ($) {
    
    /* Supplier Active Status Changing */
   $('.activate_user_cls').on('change', function () {
        var _this = $(this);
        var _this_val = _this.val();
        var _this_user = _this.data('uid');
        var data = {
            action: 'supplier_status_change',
            this_val: _this_val,
            this_user: _this_user
        };
        $.post(Back.ajaxurl, data, function (resp) {
            if (resp.flag == true) {
                site_redirect(resp.url);
            } else {

            }
        }, 'json');
    }); 
    
    /* Supplier Membership Status Changing */
    $('.change_user_membership').on('change', function(){
        var _this = $(this);
        var _this_val = _this.val();
        var _this_user = _this.data('uid');
        var data = {
            action: 'supplier_membership_status_change',
            this_val: _this_val,
            this_user: _this_user
        };
        $.post(Back.ajaxurl, data, function (resp) {
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
    }, 200);
}