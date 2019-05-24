jQuery(document).ready(function ($) {
    $('#changePassword').on('submit', function () {
        var user_old_pass = $('#oldpassword').val();
        var user_new_pass = $('#newpassword').val();
        var user_cnfrm_pass = $('#cnfnewpassword').val();
        if (user_old_pass == '') {
            $('#oldpassword').siblings('div.input-error-msg').text('Insira sua senha atual.');
            $('div.input-error-msg').not($('#oldpassword').siblings('div.input-error-msg')).text('');
        } else if (user_new_pass == '') {
            $('#newpassword').siblings('div.input-error-msg').text('Insira sua nova senha com no minimo 8 caracteres');
            $('div.input-error-msg').not($('#newpassword').siblings('div.input-error-msg')).text('');
        } else if(user_new_pass.length < 8){
            $('#newpassword').siblings('div.input-error-msg').text('Sua nova senha deve conter no minimo 8 caracteres');
            $('div.input-error-msg').not($('#newpassword').siblings('div.input-error-msg')).text('');
        } else if (user_cnfrm_pass != user_new_pass) {
            $('#cnfnewpassword').siblings('div.input-error-msg').text('Confirme sua nova senha.');
            $('div.input-error-msg').not($('#cnfnewpassword').siblings('div.input-error-msg')).text('');
        } else {
            $('div.input-error-msg').text('');
            var data = $(this).serialize();
            var l = Ladda.create(document.getElementById('productorChangePassword'));
            l.start();
            $.post(ChangePassword.ajaxurl, data, function (resp) {
                if (resp.flag == true) {
                    $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                    site_redirect(resp.url, 4000);
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
    }, 3000);
}

/* Check only alphabets */
function isAlphaOrParen(str) {
    return /^[a-zA-Z()]+$/.test(str);
}

/* Check only numeric */
function isNumeric(str) {
    return /^[0-9()]+$/.test(str);
}

/* Check if is email */
function isEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}