jQuery(document).ready(function ($) {

    /* Click to open rating pop-up */
    /*$('.provide-score').on('click', function () {
     var _this = $(this);
     var _this_deal = _this.data('deal');
     $('#selected_deal').val(_this_deal);
     var data = {
     action: 'open_supplier_popup',
     deal_id: _this_deal
     };
     $('.loader').show();
     $.post(SupplierRating.ajaxurl, data, function (resp) {
     $('.loader').hide();
     if (resp.flag == true) {
     $('.show-deal-suppliers').html(resp.supplierTableHTML);
     $('#supplier_rating_popup').modal('show');
     $(".new-rating").rating({min: 0, max: 5, step: 1, size: 'xs', readonly: false});
     } else {
     
     }
     }, 'json');
     });*/

    /* Rating Submit */
    $('.supplier_rating_frm').on('submit', function () {
        var data = $(this).serialize();
        /*var l = Ladda.create(document.getElementById('usr_rating_sbmt'));
         l.start();*/
        $('.loader').show();
        $.post(SupplierRating.ajaxurl, data, function (resp) {
            $('.loader').hide();
            if (resp.flag == true) {
                $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
               // site_redirect(resp.url);
            } else {

            }
        }, 'json');
        /* .always(function () {
         l.stop();
         });*/
    });

});