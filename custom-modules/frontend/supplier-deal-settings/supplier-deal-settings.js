jQuery(document).ready(function ($) {

    /*$('#supplier_deal_min_val_set').on('keyup', function () {
        var _this_val = $(this).val();
        var price_validation = priceSetValidation(_this_val);
        if (_this_val < 1) {
            $('.input-error-msg').text('Deal minimum value must be greater than 1.');
        } else if (price_validation == false) {
            $('.input-error-msg').text('Deal minimum value format : 1.00 or 10.00 or 100.00 or 1000.00');
        } else {
            $('.input-error-msg').text('');
            var maxVal = parseInt(_this_val * 10);
            //$( "#supplier_deal_min_val" ).slider( "option", "range", 'min');
            $( "#supplier_deal_min_val" ).slider( "option", "min", parseInt(_this_val));
            $( "#supplier_deal_min_val" ).slider( "option", "max", maxVal);
            $( "#supplier_deal_min_val" ).slider( "option", "value", parseInt(_this_val));
            $('.min-slide-val').text('R$ ' + _this_val);
            $('.max-slide-val').text('R$ ' + maxVal);
        }
    });*/

    $('#supplierDealSettings').on('submit', function () {
        var supplier_deal_min_val_set = $('#supplier_deal_min_val_set').val();

        if (!supplier_deal_min_val_set) {
            $('.input-error-msg').text('Set your deal minimum value.');
        } else {
            $('.input-error-msg').text('');
            var data = $(this).serialize();
            var l = Ladda.create(document.getElementById('supplierDealSettingsSbmt'));
            l.start();
            $.post(SupplierDealSettings.ajaxurl, data, function (resp) {
                if (resp.flag == true) {
                    $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                    site_redirect();
                } else {
                    $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
                }
            }, 'json').always(function () {
                l.stop();
            });
        }

    });
});

/* Check if is price */
function priceSetValidation(price) {
    var valid = /^\d{0,100}(\.\d{0,2})?$/.test(price),
            val = price;
    if (!valid) {
        //alert("Invalid input!");
        //this.value = val.substring(0, val.length - 1);
        return false;
    } else {
        return true;
    }
}