jQuery(document).ready(function ($) {
    $('.state-city-selection').on('change', function () {
        var _this_val = $(this).val();
        var data = {
            action: 'state_selection',
            state_id: _this_val
        };
        $('.loader').show();
        $.post(StateSelection.ajaxurl, data, function (resp) {
            $('.loader').hide();
            if (resp.flag == true) {
                $('.select-your-city').html(resp.msg);
                $('.select-your-city').trigger("chosen:updated");
                $('.select-your-city').trigger("liszt:updated");
            } else {

            }
        }, 'json');
    });
});