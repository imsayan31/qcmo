jQuery(document).ready(function ($) {
    $('.home-city-search').on('change', function () {
        var _this_city = $(this).val();
        var _this_state = $('.home-state-search').val();
        var data = {
            action: 'home_state_city_search',
            state: _this_state,
            city: _this_city
        };
        $('.loader').show();
        $.post(HomeStateCitySelection.ajaxurl, data, function (resp) {
            $('.loader').hide();
            if (resp.flag == true) {
                home_site_redirect();
            } else {

            }
        }, 'json');
    });

    $('.product-filtering').on('change', function () {
        var _this_val = $(this).val();
        $('#sorting_param').val(_this_val);
        $('#home_page_search_frm').submit();
    });

});

/* Site redirection */
function home_site_redirect(url) {
    if (url === undefined)
        url = '';
    setTimeout(function () {
        window.location.href = url;
    }, 100);
}