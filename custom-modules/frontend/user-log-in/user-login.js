jQuery(document).ready(function ($) {
    /* Productor login */
    $('#user_login_frm').on('submit', function () {
        var user_login_email = $('#user_login_email').val();
        var user_login_password = $('#user_login_password').val();

        if (user_login_email == '' && user_login_password == '') {
            $('.user-login-email-error').html('Digite seu email.');
            $('.user-login-passwrd-error').html('Digite sua senha.');
        } else if (user_login_email != '' && user_login_password == '') {
            $('.user-login-email-error').html('');
            $('.user-login-passwrd-error').html('Digite sua senha.');
        } else if (user_login_email == '' && user_login_password != '') {
            $('.user-login-email-error').html('Digite seu email.');
            $('.user-login-passwrd-error').html('');
        } else {
            var data = $(this).serialize();
            $('.user-login-email-error').html('');
            $('.user-login-passwrd-error').html('');
            var l = Ladda.create(document.getElementById('user_login_button'));
            l.start();
            
            $.post(Login.ajaxurl, data, function (resp) {
                if (resp.flag == true) {
                    $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 2500});
                    site_redirect(resp.url);
                } else {
                     $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 2500});
                }
            }, 'json').always(function () {
                l.stop();
            });
        }
    });

    $('.reg-modal-show').on('click', function () {
        $('.modal').modal('hide');
        $('#user_register_popup').modal('show');
        
        
    });
    
     $('#user_register_popup').on('shown.bs.modal', function(){
         $('body').addClass('modal-open');
     });

    $('.forgot-popup-show').on('click', function () {
        $('#user_login_popup').modal('hide');
        $('#user_forgot_password_popup').modal('show');
    });
});

/* Site redirection */
function site_redirect(url) {
    if (url === undefined)
        url = '';
    setTimeout(function () {
        window.location.href = url;
    }, 3000);
}