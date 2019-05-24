jQuery(document).ready(function ($) {

    $('.material_category_value').chosen();

    $('.material_category_value').on('change', function () {
        var data = $('#get_material_list_frm').serialize();
        $('.loader').show();
        $.post(MaterialList.ajaxurl, data, function (resp) {
            $('.loader').hide();
            if (resp.flag == true) {
                $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 6000});
                site_redirect(resp.url);
            } else {
                $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
            }
        }, 'json');
    });

});
