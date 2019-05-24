jQuery(document).ready(function ($) {

    /*reset password*/
    $('#myrental_rst_pass_frm').on('submit', function () {
        var myrental_new_pass = $('#myrental_new_pass').val();
        var myrental_new_cnf_pass = $('#myrental_new_cnf_pass').val();
        if (myrental_new_pass == '') {
            $('.old-pass-error').html('Digite a nova senha.');
            $('.new-pass-error').html('');
        } else if (myrental_new_cnf_pass == '') {
            $('.old-pass-error').html('');
            $('.new-pass-error').html('Confirme a nova senha.');
        } else {
            $('.old-pass-error').html('');
            $('.new-pass-error').html('');
            var data = $(this).serialize();
            var l = Ladda.create(document.getElementById('myrental_rst_pass_sbmt'));
            l.start();
            $.post(ResetPassword.ajaxurl, data, function (resp) {
                if (resp.flag == true) {
                    $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                    $('#user_reset_password_popup').modal('hide');
                    $('#user_login_popup').modal('show');
                } else {
                    $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
                }
            }, 'json').always(function () {
                l.stop();
            });
        }

    });

});

/* Site redirection */
function site_redirect(url) {
    if (url === undefined)
        url = '';
    setTimeout(function () {
        window.location.href = url;
    }, 8000);
}

/* Check if is email */
function isEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}