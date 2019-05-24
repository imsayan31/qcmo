jQuery(document).ready(function ($) {
    $('#select_city_frm').on('submit', function () {
        var data = $(this).serialize();
        var l = Ladda.create(document.getElementById('select_city_button'));
        l.start();
        $.post(LandingCity.ajaxurl, data, function (resp) {
            if (resp.flag == true) {
                $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                city_site_redirect(resp.url);
                //$('#choose_city_modal').modal('hide');
            } else {
                $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
            }
        }, 'json').always(function () {
            l.stop();
        });
    });
});
function city_site_redirect(url) {
    if (url === undefined)
        url = '';
    setTimeout(function () {
        window.top.location.href = url;
    }, 1000);
}