jQuery(document).ready(function ($) {
    
    $('.attribute_admin_state').chosen({
        placeholder_text_single: 'Select state'
    });
    
    $('.attribute_state').on('change', function () {
        var _this_val = $(this).val();
        var data = {
            action: 'admin_state_selection',
            state_id: _this_val
        };
        $.post(StateSelection.ajaxurl, data, function (resp) {
            if (resp.flag == true) {
                $('.attribute_city').html(resp.msg);
                $('.attribute_city').trigger("chosen:updated");
                $('.attribute_city').trigger("liszt:updated");
                //$('.adv_city').html(resp.msg);
                //$('.product-city-price').html('');
            } else {

            }
        }, 'json');
    });
    
    $('.attribute_admin_state').on('change', function () {
        var _this_val = $(this).val();
        var data = {
            action: 'sub_admin_state_selection',
            state_id: _this_val
        };
        $.post(StateSelection.ajaxurl, data, function (resp) {
            if (resp.flag == true) {
                $('.attribute_city').html(resp.msg);
                $('.attribute_city').trigger("chosen:updated");
                $('.attribute_city').trigger("liszt:updated");
                $('.adv_city').html(resp.msg);
                //$('.product-city-price').html('');
            } else {

            }
        }, 'json');
    });  


    /* Attribute additional state city selection */
    $(document.body).on('change', '.attribute_additional_admin_state', function () {
        var _this_val = $(this).val();
        var data = {
            action: 'sub_admin_state_selection',
            state_id: _this_val
        };
        $.post(StateSelection.ajaxurl, data, function (resp) {
            if (resp.flag == true) {
                $('.check-sub-admin-multi-city').last().parent('label').after(resp.msg);
                // $('.attribute_additional_city').html(resp.msg);
                // $('.attribute_additional_city').trigger("chosen:updated");
                // $('.attribute_additional_city').trigger("liszt:updated");
                //$('.adv_city').html(resp.msg);
                //$('.product-city-price').html('');
            } else {

            }
        }, 'json');
    });  

      
      $('.state-selection').on('change', function() {
        var _Id = $(this).data('target');
        var _this_val = $(this).val();
        var data = {
            action: 'admin_state_selection',
            state_id: _this_val
        };
        $.post(StateSelection.ajaxurl, data, function (resp) {
            if (resp.flag == true) {
                $('#'+_Id).html(resp.msg);
                $('#'+_Id).trigger("chosen:updated");
                $('#'+_Id).trigger("liszt:updated");
            } else {

            }
        }, 'json');
    });
});