jQuery(document).ready(function ($) {

    /*forgot password*/
    $('#user_frgt_pass_frm').on('submit', function () {
        var user_frgt_email = $('#user_frgt_email').val();
        var terms_checked = $('#c2').is(':checked');
        if (user_frgt_email == '') {
            $('.forgt-error').html('Digite seu email.');
            $('.terms-error').html('');
        } else if (terms_checked == false) {
            $('.forgt-error').html('');
            $('.terms-error').html('Você deve ler e aceitar todos os termos e condições de uso.');
        } else {
            $('.forgt-error').html('');
            $('.terms-error').html('');
            var data = $(this).serialize();
            var l = Ladda.create(document.getElementById('usr_frgt_sbmt'));
            l.start();
            $.post(ForgotPassword.ajaxurl, data, function (resp) {
                if (resp.flag == true) {
                    //$('#reset_user').val(resp.user_id);
                    $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                    $('#user_forgot_password_popup').modal('hide');
                    //$('#user_reset_password_popup').modal('show');
                } else {
                    $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
                }
            }, 'json').always(function () {
                l.stop();
            });
        }
    });

    $('.forgot-login-modal-show').on('click', function () {
        $('#user_forgot_password_popup').modal('hide');
        $('#user_login_popup').modal('show');
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