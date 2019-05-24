jQuery(document).ready(function ($) {

    /* Hide Website Field from User's Profile */
    $('.user-url-wrap').hide();

    /* Advertisement form submission */
    $('.get-adv-total-val').on('click', function () {
        var adv_position = [];
        var adv_city = $('.adv_city').val();
        var adv_cat = [];
        var adv_page = [];
        var adv_url = $('#adv_url').val();
        var adv_init_date = $('#adv_init_date').val();
        var adv_final_date = $('#adv_final_date').val();
        var adv_init_time = $('#adv_init_time').val();
        var adv_final_time = $('#adv_final_time').val();
        var adv_state = $('.adv_state').val();
        $('.adv_position').each(function () {
            if ($(this).is(':checked') == true) {
                adv_position.push($(this).val());
            }
        });
        /*$('.adv_city').each(function () {
         adv_city.push($(this).val());
         });*/
        $('.main-site-category').each(function () {
            if ($(this).is(':checked') == true) {
                adv_cat.push($(this).val());
            }
        });
        $('.main-site-sub-category').each(function () {
            if ($(this).is(':checked') == true) {
                adv_cat.push($(this).val());
            }
        });
        $('.adv_page').each(function () {
            if ($(this).is(':checked') == true) {
                adv_page.push($(this).val());
            }
        });
        var data = {
            action: 'adv_settings_get_price',
            adv_url: adv_url,
            adv_init_date: adv_init_date,
            adv_final_date: adv_final_date,
            adv_init_time: adv_init_time,
            adv_final_time: adv_final_time,
            adv_position: adv_position,
            adv_state: adv_state,
            adv_city: adv_city,
            adv_cat: adv_cat,
            adv_page: adv_page
        };

        $.post(Back.ajaxurl, data, function (resp) {
            if (resp.flag == true) {
                $('.show-total-price').text('R$ ' + resp.msg);
            } else {

            }
        }, 'json');
    });

    $('#my-meta-box-advertisement').css('width', '800px');

    $('.main-site-category').on('click', function () {
        var _this = $(this);
        var nextSubCat = _this.parent('label').next('div.all-sub-cats').find('.main-site-sub-category');
        if (_this.is(':checked') == true) {
            nextSubCat.each(function () {
                $(this).attr('checked', 'checked');
            });
        } else if (_this.is(':checked') == false) {
            nextSubCat.each(function () {
                $(this).prop('checked', '');
            });
        }
    });

    /* Commercial number masking */
    $("#phone").mask("(99) 9999-9999");

    /* CPF & CNPJ masking */
    $(".cpf").mask("999.999.999-99");
    $(".cnpj").mask("99.999.999/9999-99");

    $('#adv_init_time').timepicker({
        'timeFormat': 'H:i',
        'step': 60
    });
    $('#adv_final_time').timepicker({
        'timeFormat': 'H:i',
        'step': 60
    });

    /* Supplier type selection */
    $('.supplier-type').on('click', function () {
        var _this_val = $(this).val();
        if (_this_val == 1) {
            $('.cpf-display').show();
            $('.cnpj-display').hide();
        } else if (_this_val == 2) {
            $('.cpf-display').hide();
            $('.cnpj-display').show();
        }
    });

    /* Receive deals checking */
    $('#receive_deals').on('click', function () {
        if ($(this).is(':checked') == true) {
            $('.date-selection-row').show();
        } else {
            $('.date-selection-row').hide();
            $('#deals_from_date').val('');
            $('#deals_to_date').val('');
        }
    });

    /* Date picker functionality */
    $('#deals_from_date').datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: true,
        changeYear: true,
        minDate: 0,
        onSelect: function (dateText, inst) {
            $('#deals_to_date').datepicker("option", "minDate", dateText);
        }
    });

    $('#adv_init_date').datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: true,
        changeYear: true,
        minDate: 0,
        onSelect: function (dateText, inst) {
            $('#adv_final_date').datepicker("option", "minDate", dateText);
        }
    });

    $('#deals_to_date').datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: true,
        changeYear: true,
        minDate: 0
    });

    $('#adv_final_date').datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: true,
        changeYear: true,
        minDate: 0
    });

    $('#announcement_admin_create_date').datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: true,
        changeYear: true,
        minDate: 0
    });

    $('.city-select, .attribute_city').chosen({
        placeholder_text_multiple: 'Select cities'
    });
    $('.select_product_attribute').chosen({
        placeholder_text_single: 'Select products for bundling'
    });
    $('.attribute_state').chosen({
        placeholder_text_single: 'Select state'
    });
    $('.attribute_additional_admin_state').chosen({
        placeholder_text_single: 'Select state'
    });

    $('.attribute_suppliers').chosen({
        placeholder_text_multiple: 'Select suppliers'
    });
    $('.subadmin_capabilities').chosen({
        placeholder_text_multiple: 'Select capabilities'
    });
    
    $('.attribute_additional_city').chosen({
        placeholder_text_multiple: 'Select capabilities'
    });

    $('.admin-supplier-category').chosen({
        placeholder_text_multiple: 'Assign Buisness Categories'
    });

    /* Hide user avatar */
    $('.user-profile-picture').css('display', 'none');

    /* Choose all suppliers */
    $('#choose_all_suppliers').on('click', function () {
        if ($(this).is(':checked') == true) {
            $('.select_supplier_sec').hide();
        } else {
            $('.select_supplier_sec').show();
        }
    });

    /* Choose all cities */
    $('#choose_all_cities').on('click', function () {
        if ($(this).is(':checked') == true) {
            $('.select_state_city_sec').hide();
        } else {
            $('.select_state_city_sec').show();
        }
    });
    
    $('#filtering_state,#filtering_city').on('change', function (e) { 
        e.preventDefault();      
        var state_id = $(this).val();
        //var city_id = $(this).val();
        console.log(state_id);
        //console.log(state_id);
         //$('#state').val(state_id);
        // $('#city').val(city_id);
       //$(this).closest('form').submit();
       
   }); 

    /* Click to add more cities */
    $('.click-to-add-more-city').on('click', function(){
        var _this = $(this);
        //var newCityCount = $('.new-city-cont').length;
        var newCityCount = $('.new-city-cont').first().is(':visible');
        
        if(newCityCount == false){
            $('.new-city-cont').first().show();
        } else {
            var currCityHTMLId = $('.new-city-cont').last().prop('id');
            var explodedId = currCityHTMLId.split('_');
            var newExplodedId = parseInt(explodedId[1]) + 1;
            var nextCityHTML = $('.new-city-cont').first().html();
            var nextWrapStart = '<div class="new-city-cont" id="newCityCount_'+ newExplodedId +'" style="margin-top: 20px;margin-bottom: 20px;">';
            nextWrapStart += nextCityHTML;
            nextWrapStart += '<a href="javascript:void(0);" class="remove-new-city button button-primary">Delete</a>';
            nextWrapStart += '</div>';
            $('.new-city-cont').last().after(nextWrapStart);
        }
    });

    /* Remove New Cities */
    $(document.body).on('click', '.remove-new-city', function(){
        var _this = $(this);
        _this.parent('div.new-city-cont').remove();
    });

});