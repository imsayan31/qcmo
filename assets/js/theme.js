jQuery(document).ready(function ($) {
    
    //<i class="fa fa-facebook" aria-hidden="true"></i>
    $('.social-sharing').children('a').append('<i class="fa fa-facebook" aria-hidden="true"></i>');

    $('.subscription_card_type, .subscription_card_exp_month, .subscription_card_exp_year').chosen({
        width: '100%'
    });

    /* Place holder text change for google translator */
    var placeholders = document.querySelectorAll('input[placeholder]');
    if (placeholders.length) {
        // convert to array
        placeholders = Array.prototype.slice.call(placeholders);

        // copy placeholder text to a hidden div
        var div = $('<div id="placeholders" style="display:none;"></div>');

        placeholders.forEach(function (input) {
            var text = input.placeholder;
            div.append('<div>' + text + '</div>');
        });

//        $('body').append(div);
        $('.loader').after(div);

        // save the first placeholder in a closure
        var originalPH = placeholders[0].placeholder;

        // check for changes and update as needed
        setInterval(function () {
            if (isTranslated()) {
                updatePlaceholders();
                originalPH = placeholders[0].placeholder;
            }
        }, 500);

        // hoisted ---------------------------
        function isTranslated() { // true if the text has been translated
            var currentPH = $($('#placeholders > div')[0]).text();
            return !(originalPH == currentPH);
        }

        function updatePlaceholders() {
            $('#placeholders > div').each(function (i, div) {
                placeholders[i].placeholder = $(div).text();
            });
        }
    }

    /* Price Improvement Module - By Sayan - Date - 19.05.2019 */
    var price_val = $( "#price_val" );
    var allFields = $( [] ).add( price_val );
    var dialog = $( "#price-improve-dialog-form" ).dialog({
      autoOpen: false,
      height: 200,
      width: 450,
      modal: true,
      buttons: {
        "Submit": function(){
            var data = $('#priceImprovementFrm').serialize();
            $('.loader').show();
            $.post(Front.ajaxurl, data, function(resp){
                $('.loader').hide();
                if(resp.flag == true){
                    $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 5000});
                    dialog.dialog( "close" );
                } else{
                    $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
                }
            },'json');
        },
        Cancel: function() {
          dialog.dialog( "close" );
        }
      },
      close: function() {
        form[ 0 ].reset();
        allFields.removeClass( "ui-state-error" );
      }
    });

   var form = dialog.find( "form" ).on( "submit", function( event ) {
      event.preventDefault();
    });
    
    $('.click-price-improve').on('click', function(){
        var _this = $(this);
        var _this_improve = _this.data('improve_type');
        var _this_product = _this.data('product');
        $('#improvement_type').val(_this_improve);
        $('#product_id').val(_this_product);
        dialog.dialog( "open" );
    });

    /* End of Price Improvement Module - By Sayan - Date - 19.05.2019 */

    
    $('.state-city-selection').chosen({placeholder_text_single: 'Escolha seu estado'});

    $('.select-your-city').chosen({placeholder_text_single: 'Escolha sua cidade'});

    $('.user-state').chosen({placeholder_text_single: 'Selecione seu estado padrão'});

    $('.user-city').chosen({placeholder_text_single: 'Selecione sua cidade padrão'});

    $('.home_search_city').chosen({placeholder_text_single: 'Pesquisar por cidade'});

    $('.product-filtering').chosen({placeholder_text_single: 'Filter your products'});

    $('.announcement-filtering').chosen({placeholder_text_single: 'Filter your products'});

    $('.announcement-type-filtering').chosen({placeholder_text_single: 'Filter your products'});

    $('.home_search_state').chosen({placeholder_text_single: 'Filter your products'});

    $('.user-deal-status-change').chosen();

    $('.supplier-category').chosen({width: '100%'});

    $('.update-supplier-category').chosen({width: '100%'});

    $('.supplier_radius').chosen({width: '100%'});
    
    $('.select-cart-category').chosen({width: '100%'});
    
    $('.announcement_category,.state-selection,.city-selection').chosen({width: '100%'});
    
    $('[data-toggle="tooltip"]').tooltip({
        delay: {show: 500, hide: 100}
    });
    
    $('.announcement-filtering, .announcement-type-filtering').on('change', function(){
        $('#announcement_filtering_frm').submit();
    });


    /*$('.tbl-wishlist').DataTable({
        langugae:{
            "lengthMenu" : "Exibindo _MENU_ registros",
            "info": "Mostrando de _START_ to _END_ of _TOTAL_ registros"
        }
    });*/

    $('table.display').DataTable();

    $('#tbl-wishlist').DataTable({
        /*langugae:{
            "lengthMenu" : "Exibindo _MENU_ registros",
            "info": "Mostrando de _START_ to _END_ of _TOTAL_ registros"
        }*/
    });

    $('#tbl-announcement-wishlist').DataTable({
        /*langugae:{
            "lengthMenu" : "Exibindo _MENU_ registros",
            "info": "Mostrando de _START_ to _END_ of _TOTAL_ registros"
        }*/
    });

    $('#tbl-supplier-wishlist').DataTable({
        /*langugae:{
            "lengthMenu" : "Exibindo _MENU_ registros",
            "info": "Mostrando de _START_ to _END_ of _TOTAL_ registros"
        }*/
    });

    /*$('.tbl-wishlist').DataTable({
     
     });*/

    //"order": [[1, "desc"]]

    adjustWinHeight();

    $(window).resize(function () {
        adjustWinHeight();
    });

    function adjustWinHeight() {
        var $ = jQuery;
        var winHeight = $(window).height();
        var footerHeight = $('.site-footer').css('height');
        var headerHeight = $('.site-header').css('height');
        var mainHeight = winHeight - (parseInt(footerHeight) + parseInt(headerHeight));

        $('.site-content-contain').css('min-height', mainHeight);
    }

    $(".cust-scroll").mCustomScrollbar({
        theme: "minimal-dark"
    });

    $(document).ready(function (e) {
        $('.search-panel .dropdown-menu').find('a').click(function (e) {
            e.preventDefault();
            var param = $(this).attr("href").replace("#", "");
            var concept = $(this).text();
            $('.search-panel span#search_concept').text(concept);
            $('.input-group #search_param').val(param);
        });
    });

    $(window).scroll(function () {
        var scroll = $(window).scrollTop();

        if (scroll >= 300) {
            $(".site-header").addClass("fixed");
        } else {
            $(".site-header").removeClass("fixed");
        }
    });

    jQuery('<div class="quantity-nav"><div class="quantity-button quantity-up"><i class="fa fa-plus" aria-hidden="true"></i></div><div class="quantity-button quantity-down"><i class="fa fa-minus" aria-hidden="true"></i></div></div>').insertAfter('.quantity input');
    jQuery('.quantity').each(function () {
        var spinner = jQuery(this),
                input = spinner.find('input[type="number"]'),
                btnUp = spinner.find('.quantity-up'),
                btnDown = spinner.find('.quantity-down'),
                min = input.attr('min'),
                max = input.attr('max');

        btnUp.click(function () {
            var newVal;
            var oldValue = parseFloat(input.val());
            console.log(oldValue);
            if (oldValue >= max) {
                newVal = oldValue;
            } else {
                newVal = oldValue + parseFloat(0.01);
            }
            spinner.find("input").val(parseFloat(newVal));
            spinner.find("input").trigger("change");
        });

        btnDown.click(function () {
            var newVal;
            var oldValue = parseFloat(input.val());
            console.log(oldValue);
            if (oldValue <= min) {
                newVal = oldValue;
            } else {
                newVal = oldValue - parseFloat(0.01);
            }
            spinner.find("input").val(parseFloat(newVal));
            spinner.find("input").trigger("change");
        });

    });
});


