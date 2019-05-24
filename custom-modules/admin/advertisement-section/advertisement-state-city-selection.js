jQuery(document).ready(function ($) {

    $('#adv_slot1').on('click', function () {
        $('#adv_init_time').val('00:00');
        $('#adv_final_time').val('23:59');
    });
    $('#adv_slot2').on('click', function () {
        $('#adv_init_time').val('');
        $('#adv_final_time').val('');
    });

    $('.adv_state').on('change', function () {
        var _this_val = $(this).val();
        var data = {
            action: 'admin_advertisement_state_selection',
            state_id: _this_val
        };
        $.post(StateSelection.ajaxurl, data, function (resp) {
            if (resp.flag == true) {
                $('.adv_city').html(resp.msg);
            } else {

            }
        }, 'json');
    });
});