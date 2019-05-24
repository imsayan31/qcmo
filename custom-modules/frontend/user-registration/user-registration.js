jQuery(document).ready(function ($) {

    /* Mobile number masking */
    $("#phone").mask("(99) 99999-9999");
    $("#supplier_phone").mask("(99) 9999-9999");

    /* CPF & CNPJ masking */
    $("#supplier_cpf").mask("999.999.999-99");
    $("#supplier_cnpj").mask("99.999.999/9999-99");
    $("#usr_cpf").mask("999.999.999-99");
    $("#usr_cnpj").mask("99.999.999/9999-99");

    /* Supplier type selection */
    $('.supplier-type').on('click', function () {
        var _this_val = $(this).val();
        if (_this_val == 1) {
            $('.cpf-display').show();
            $('.cnpj-display').hide();
        } else if (_this_val == 2) {
            $('.cpf-display').hide();
            $('.cnpj-display').show();
        }
    });

    /* User registration */
    $('#productorRegistration').on('submit', function () {
        var fname = $('#fname').val();
        var lname = $('#lname').val();
        var email = $('#email').val();
        var phone = $('#phone').val();
        var usr_cpf = $('#usr_cpf').val();
        var usr_cnpj = $('#usr_cnpj').val();
        var state = $('.user-state')[0].selectedIndex;
        var city = $('.user-city')[0].selectedIndex;
        var password = $('#password').val();
        var cnfpassword = $('#cnfpassword').val();
        var fname_validation = isAlphaOrParen(fname);
        var lname_validation = isAlphaOrParen(lname);
        var email_validation = isEmail(email);
        var fname_numric_check = hasNumbers(fname);
        var lname_numric_check = hasNumbers(lname);
        var phone_validation = isNumeric(phone);

        if (!fname) {
            $('#fname').siblings('div.input-error-msg').text('Digite seu primeiro nome.');
            $('div.input-error-msg').not($('#fname').siblings('div.input-error-msg')).text('');
        } else if (fname_numric_check == true) {
            $('#fname').siblings('div.input-error-msg').text('Primeiro nome não deve conter números.');
            $('div.input-error-msg').not($('#fname').siblings('div.input-error-msg')).text('');
        } else if (!lname) {
            $('#lname').siblings('div.input-error-msg').text('Digite seu sobrenome.');
            $('div.input-error-msg').not($('#lname').siblings('div.input-error-msg')).text('');
        } else if (lname_numric_check == true) {
            $('#lname').siblings('div.input-error-msg').text('O sobrenome não deve conter números.');
            $('div.input-error-msg').not($('#lname').siblings('div.input-error-msg')).text('');
        } else if (!email) {
            $('#email').siblings('div.input-error-msg').text('Digite seu e-mail.');
            $('div.input-error-msg').not($('#email').siblings('div.input-error-msg')).text('');
        } else if (email_validation == false) {
            $('#email').siblings('div.input-error-msg').text('E-mail não está no formato adequado.');
            $('div.input-error-msg').not($('#email').siblings('div.input-error-msg')).text('');
        } else if (!phone) {
            $('#phone').siblings('div.input-error-msg').text('Digite seu telefone.');
            $('div.input-error-msg').not($('#phone').siblings('div.input-error-msg')).text('');
        } /*else if (($('#usr_physical_person').is(':checked') == false) && ($('#usr_judicial_person').is(':checked') == false)) {
            $('.type-error').siblings('div.input-error-msg').text('Select your type.');
            $('div.input-error-msg').not($('.type-error').siblings('div.input-error-msg')).text('');
        } else if ($('#usr_physical_person').is(':checked') == true && !usr_cpf) {
            $('#usr_cpf').siblings('div.input-error-msg').text('Enter your CPF.');
            $('div.input-error-msg').not($('#usr_cpf').siblings('div.input-error-msg')).text('');
        } else if ($('#usr_judicial_person').is(':checked') == true && !usr_cnpj) {
            $('#usr_cnpj').siblings('div.input-error-msg').text('Enter your CNPJ.');
            $('div.input-error-msg').not($('#usr_cnpj').siblings('div.input-error-msg')).text('');
        } */
        else if (state == 0) {
            $('.user-state').siblings('div.input-error-msg').text('Selecione seu estado.');
            $('div.input-error-msg').not($('.user-state').siblings('div.input-error-msg')).text('');
        } else if (city == 0) {
            $('.user-city').siblings('div.input-error-msg').text('Selecione sua cidade.');
            $('div.input-error-msg').not($('.user-city').siblings('div.input-error-msg')).text('');
        } else if (!password) {
            $('#password').siblings('div.input-error-msg').text('Digite a senha.');
            $('div.input-error-msg').not($('#password').siblings('div.input-error-msg')).text('');
        } else if (password.length < 8) {
            $('#password').siblings('div.input-error-msg').text('O tamanho da senha deve ter no mínimo 8 caracteres.');
            $('div.input-error-msg').not($('#password').siblings('div.input-error-msg')).text('');
        } else if (!cnfpassword) {
            $('#cnfpassword').siblings('div.input-error-msg').text('Confirme sua senha com um original.');
            $('div.input-error-msg').not($('#cnfpassword').siblings('div.input-error-msg')).text('');
        } else if (cnfpassword.length != password.length) {
            $('#cnfpassword').siblings('div.input-error-msg').text("Confirme sua senha com um original.");
            $('div.input-error-msg').not($('#cnfpassword').siblings('div.input-error-msg')).text('');
        } else {
            $('div.input-error-msg').text('');
            var data = $(this).serialize();
            var l = Ladda.create(document.getElementById('productorRegSbmt'));
            l.start();
            $.post(Registration.ajaxurl, data, function (resp) {
                if (resp.flag == true) {
                    $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                    //$.notify(resp.msg, {color: "#fff", background: "#31cc32", close: true, delay: 3000});
                    site_redirect();
                } else {
                    $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
                    //$.notify(resp.msg, {color: "#fff", background: "#D44950", close: true, delay: 3000});
                }
            }, 'json').always(function () {
                l.stop();
            });
        }
    });


    /* Supplier registration */
    $('#supplierRegistration').on('submit', function () {
        var fname = $('#supplier_fname').val();
        var lname = $('#supplier_lname').val();
        var email = $('#supplier_email').val();
        var phone = $('#supplier_phone').val();
        var state = $('.supplier-user-state')[0].selectedIndex;
        var city = $('.supplier-user-city')[0].selectedIndex;
        var category = $('.supplier-category')[0].selectedIndex;
        var password = $('#supplier_password').val();
        var cnfpassword = $('#supplier_cnfpassword').val();
        var supplier_address = $('#supplier_address').val();
        var supplier_cpf = $('#supplier_cpf').val();
        var supplier_cnpj = $('#supplier_cnpj').val();
        var fname_validation = isAlphaOrParen(fname);
        var lname_validation = isAlphaOrParen(lname);
        var email_validation = isEmail(email);
        var fname_numric_check = hasNumbers(fname);
        var lname_numric_check = hasNumbers(lname);
        var phone_validation = isNumeric(phone);

        if (!fname) {
            $('#supplier_fname').siblings('div.input-error-msg').text('Digite seu nome comercial.');
            $('div.input-error-msg').not($('#supplier_fname').siblings('div.input-error-msg')).text('');
        } else if (fname_numric_check == true) {
            $('#supplier_fname').siblings('div.input-error-msg').text('O nome comercial não deve conter números.');
            $('div.input-error-msg').not($('#supplier_fname').siblings('div.input-error-msg')).text('');
        } 
        else if (!lname) {
            $('#supplier_lname').siblings('div.input-error-msg').text('Enter your last name.');
            $('div.input-error-msg').not($('#supplier_lname').siblings('div.input-error-msg')).text('');
        } 
        else if (lname && lname_numric_check == true) {
            $('#supplier_lname').siblings('div.input-error-msg').text('O nome legal não deve conter números.');
            $('div.input-error-msg').not($('#supplier_lname').siblings('div.input-error-msg')).text('');
        } else if (!email) {
            $('#supplier_email').siblings('div.input-error-msg').text('Digite seu e-mail.');
            $('div.input-error-msg').not($('#supplier_email').siblings('div.input-error-msg')).text('');
        } else if (email_validation == false) {
            $('#supplier_email').siblings('div.input-error-msg').text('E-mail não está no formato adequado.');
            $('div.input-error-msg').not($('#supplier_email').siblings('div.input-error-msg')).text('');
        } else if (!phone) {
            $('#supplier_phone').siblings('div.input-error-msg').text('Digite seu telefone.');
            $('div.input-error-msg').not($('#supplier_phone').siblings('div.input-error-msg')).text('');
        }/* else if (($('#physical_person').is(':checked') == false) && ($('#judicial_person').is(':checked') == false)) {
            $('.type-error').siblings('div.input-error-msg').text('Selecione seu tipo.');
            $('div.input-error-msg').not($('.type-error').siblings('div.input-error-msg')).text('');
        } else if ($('#physical_person').is(':checked') == true && !supplier_cpf) {
            $('#supplier_cpf').siblings('div.input-error-msg').text('Insira seu CPF.');
            $('div.input-error-msg').not($('#supplier_cpf').siblings('div.input-error-msg')).text('');
        } else if ($('#judicial_person').is(':checked') == true && !supplier_cnpj) {
            $('#supplier_cnpj').siblings('div.input-error-msg').text('Digite seu CNPJ.');
            $('div.input-error-msg').not($('#supplier_cnpj').siblings('div.input-error-msg')).text('');
        } */else if (state == 0) {
            $('.supplier-user-state').siblings('div.input-error-msg').text('Selecione seu estado.');
            $('div.input-error-msg').not($('.supplier-user-state').siblings('div.input-error-msg')).text('');
        } else if (city == 0) {
            $('.supplier-user-city').siblings('div.input-error-msg').text('Selecione sua cidade.');
            $('div.input-error-msg').not($('.supplier-user-city').siblings('div.input-error-msg')).text('');
        } else if (!supplier_address) {
            $('#supplier_address').siblings('div.input-error-msg').text('Digite seu endereço.');
            $('div.input-error-msg').not($('#supplier_address').siblings('div.input-error-msg')).text('');
        } 
       /* else if (category == 0) {
            $('.supplier-category').siblings('div.input-error-msg').text('Select your categories you supply.');
            $('div.input-error-msg').not($('.supplier-category').siblings('div.input-error-msg')).text('');
        } */
        else if (!password) {
            $('#supplier_password').siblings('div.input-error-msg').text('Digite a senha.');
            $('div.input-error-msg').not($('#supplier_password').siblings('div.input-error-msg')).text('');
        } else if (password.length < 8) {
            $('#supplier_password').siblings('div.input-error-msg').text('O tamanho da senha deve ter no mínimo 8 caracteres.');
            $('div.input-error-msg').not($('#supplier_password').siblings('div.input-error-msg')).text('');
        } else if (!cnfpassword) {
            $('#supplier_cnfpassword').siblings('div.input-error-msg').text('Confirme sua senha com um original.');
            $('div.input-error-msg').not($('#supplier_cnfpassword').siblings('div.input-error-msg')).text('');
        } else if (cnfpassword.length != password.length) {
            $('#supplier_cnfpassword').siblings('div.input-error-msg').text("Confirme sua senha com um original.");
            $('div.input-error-msg').not($('#supplier_cnfpassword').siblings('div.input-error-msg')).text('');
        } else {
            $('div.input-error-msg').text('');
            var data = $(this).serialize();
            var l = Ladda.create(document.getElementById('supplierRegSbmt'));
            l.start();
            $.post(Registration.ajaxurl, data, function (resp) {
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

    $('.login-modal-show').on('click', function () {
        $('#user_register_popup').modal('hide');
        $('#supplier_register_popup').modal('hide');
        $('#user_login_popup').modal('show');
    });
    
    
    $('.userdelete').on('click',function(){
       var _thisId=$(this).data('delete');
       var data={
           action:'delete_user',
           postId:_thisId
       };
       
           $.post(Registration.ajaxurl, data, function(resp){
               console.log(resp);
               if(resp.flag == true){
                   $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                    window.location.href = resp.url;
               }
             
           },'json');
       
       });

});

/* Site redirection */
function site_redirect(url) {
    if (url === undefined)
        url = '';
    setTimeout(function () {
        window.location.href = url;
    }, 10000);
}

/* Check has numbers */
function hasNumbers(str) {
    return /\d/.test(str);
}

/* Check only alphabets */
function isAlphaOrParen(str) {
    return /^[a-zA-Z() ]+$/.test(str);
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