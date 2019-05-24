jQuery(document).ready(function ($) {

    /* Announcement Settings */
    $('.announcement_admin_approval').on('change', function () {
        var _this = $(this);
        var _this_val = _this.val();
        var _this_announcement = _this.data('announcement_id');
        var data = {
            action: 'announcement_approval',
            announcement_id: _this_announcement,
            status_val: _this_val
        };
        $.post(AnnouncementSettings.ajaxurl, data, function (resp) {
            if (resp.flag == true) {
                window.location.href = resp.url;
            } else {

            }
        }, 'json');
    });

    /* Announcement Enabling For City wise */
    $('#enable_announcement').on('click', function () {
        var _this = $(this);
        if (_this.is(':checked') == true) {
            $('.show-user-announcement-enabling').show();
        } else {
            $('.show-user-announcement-enabling').hide();
        }
    });

});