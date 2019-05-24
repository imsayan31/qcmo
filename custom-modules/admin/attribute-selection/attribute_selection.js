jQuery(document).ready(function ($) {

    /* Add product attribute */
    $('.select_product_attribute').on('change', function () {
        var _this_val = $(this).val();
        var _this_cities = $('.selected_attribute_city').val();
        var display_attributes_block_html = $('.display_attributes_block').html();

        var data = {
            action: 'attribute_based_price',
            attribute: _this_val,
            cities: _this_cities
        };
        $.post(AttributeSelection.ajaxurl, data, function (resp) {
            if (resp.flag == true) {
                if (display_attributes_block_html == '') {
                    $('.display_attributes_block').append(resp.msg);
                } else {
                    $('.product_attribute_field').last().after(resp.msg);
                }
                $('#get_attribute_total').parent('div').show();
            } else {

            }
        }, 'json');
    });

    /* Delete product attribute */
    $(document.body).on('click', '.delete_product_attribute', function () {
        $(this).closest('table.product_attribute_field').remove();
    });

    /* Get total attribute price */
    $(document.body).on('click', '#get_attribute_total', function () {
        var product_cat = [];
        var product_quantity = [];
        $('.product_cat').each(function () {
            product_cat.push($(this).val());
        });
        $('.product_quantity').each(function () {
            product_quantity.push($(this).val());
        });
        var data = {
            action: 'get_attribute_total_price',
            product_cat: product_cat,
            product_quantity: product_quantity
        };
        $.post(AttributeSelection.ajaxurl, data, function (resp) {
            if (resp.flag == true) {
                $('.fetch-total-price').val(resp.msg);
                $('.product-city-price').html('');
            } else {

            }
        }, 'json');
    });

    /* Product city respective price selection */
    $('.selected_attribute_city').on('change', function () {
        var _this_val = $(this).val();
        var _this_state_val = $('.attribute_state').val();
        var _this_text = $('.selected_attribute_city option:selected').text();
        var display_attributes_block_html = $('.product-city-price').html();
        var product_cat = [];
        var product_quantity = [];
        $('.product_cat').each(function () {
            product_cat.push($(this).val());
        });
        $('.product_quantity').each(function () {
            product_quantity.push($(this).val());
        });
        var acf_product_price = $('#acf-field_597c30c2df0ea').val();
        var data = {
            action: 'get_city_attribute_price',
            product_state: _this_state_val,
            product_city: _this_val,
            product_cat: product_cat,
            product_quantity: product_quantity,
            acf_product_price: acf_product_price
        };
        $.post(AttributeSelection.ajaxurl, data, function (resp) {
            if (resp.flag == true) {
                if (display_attributes_block_html == '') {
                    $('.product-city-price').append(resp.msg);
                } else {
                    $('.product_city_price_field').last().after(resp.msg);
                }
            } else {

            }
        }, 'json');

        /*var appendData = '';
         appendData += '<table class="product_city_price_field">';
         appendData += '<tr>';
         appendData += '<td width="50%"><input type="hidden" name="product_city[]" value="' + _this_val + '"><strong>' + _this_text + '</strong> : </td>';
         appendData += '<td width="30%"><input type="text" name="product_city_price[]" value="" placeholder="Enter price (in R$)"></td>';
         appendData += '<td width="20%"><a href="javascript:void(0);" class="delete_product_city">close</a></td>';
         appendData += '</tr>';
         appendData += '</table>';*/

    });

    /* Delete product city */
    $(document.body).on('click', '.delete_product_city', function () {
        $(this).closest('table.product_city_price_field').remove();
    });

});