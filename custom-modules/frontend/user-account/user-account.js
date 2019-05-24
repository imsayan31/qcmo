jQuery(document).ready(function ($) {
    
    /* Commercial number masking */
    $("#update_lphone").mask("(99) 9999-9999");
    
    /* Mobile number masking */
    $("#update_phone").mask("(99) 99999-9999");
    $("#supplier_update_phone").mask("(99) 9999-9999");
    
    /* CPF & CNPJ masking */
    $("#supplier_update_cpf").mask("999.999.999-99");
    $("#supplier_update_cnpj").mask("99.999.999/9999-99");
    
    var date = new Date();
    date.setMonth(date.getMonth() + 1, 1);
    var currYear = new Date().getFullYear();

    $('#update_dob').datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: '1900:' + currYear,
        minDate: new Date(1900, 0, 1),
        maxDate: new Date(currYear, 11, 31)
    });

    /* Pro pic upload */
    $('#select_pro_pic').on('change', function () {
        readURL(this, '#user_logo');
    });
    
    $('#select_supplier_pro_pic').on('change', function () {
        readURL(this, '#supplier_logo');
    });

/* User Account Update */
    $('#productorAccount').on('submit', function () {
        var fname = $('#update_fname').val();
        var lname = $('#update_lname').val();
        var phone = $('#update_phone').val();
       // var supplier_cpf = $('#supplier_cpf').val();
        //var supplier_cnpj = $('#supplier_cnpj').val();
        var city = $('#update_user_city')[0].selectedIndex;
        var state = $('#update_user_state')[0].selectedIndex;
        var fname_validation = isAlphaOrParen(fname);
        var lname_validation = isAlphaOrParen(lname);
        var fname_numric_check = hasNumbers(fname);
        var lname_numric_check = hasNumbers(lname);
        var phone_validation = isNumeric(phone);

         if (!fname) {
            $('#update_fname').siblings('div.input-error-msg').text('Digite seu primeiro nome.');
            $('div.input-error-msg').not($('#update_fname').siblings('div.input-error-msg')).text('');
        } else if (fname_numric_check == true) {
            $('#update_fname').siblings('div.input-error-msg').text('O primeiro nome contém apenas alfabetos.');
            $('div.input-error-msg').not($('#update_fname').siblings('div.input-error-msg')).text('');
        } else if (!lname) {
            $('#update_lname').siblings('div.input-error-msg').text('Digite seu sobrenome.');
            $('div.input-error-msg').not($('#update_lname').siblings('div.input-error-msg')).text('');
        } else if (lname_numric_check == true) {
            $('#update_lname').siblings('div.input-error-msg').text('O sobrenome contém apenas alfabetos.');
            $('div.input-error-msg').not($('#update_lname').siblings('div.input-error-msg')).text('');
        } else if (!phone) {
            $('#update_phone').siblings('div.input-error-msg').text('Digite seu celular.');
            $('div.input-error-msg').not($('#update_phone').siblings('div.input-error-msg')).text('');
        } else if (state <= 0) {
            $('.user-state').siblings('div.input-error-msg').text('Selecione seu estado.');
            $('div.input-error-msg').not($('.user-state').siblings('div.input-error-msg')).text('');
        } else if (city <= 0) {
            $('.user-city').siblings('div.input-error-msg').text('Selecione sua cidade.');
            $('div.input-error-msg').not($('.user-city').siblings('div.input-error-msg')).text('');
        } 
       /*else if (($('#physical_person').is(':checked') == false) && ($('#judicial_person').is(':checked') == false)) {
            $('.type-error').siblings('div.input-error-msg').text('Select your type.');
            $('div.input-error-msg').not($('.type-error').siblings('div.input-error-msg')).text('');
        }*/ else if ($('#physical_person').is(':checked') == true && !supplier_cpf) {
            $('#supplier_cpf').siblings('div.input-error-msg').text('Enter your CPF.');
            $('div.input-error-msg').not($('#supplier_cpf').siblings('div.input-error-msg')).text('');
        } else if ($('#judicial_person').is(':checked') == true && !supplier_cnpj) {
            $('#supplier_cnpj').siblings('div.input-error-msg').text('Enter your CNPJ.');
            $('div.input-error-msg').not($('#supplier_cnpj').siblings('div.input-error-msg')).text('');
        }
        else {
            $('div.input-error-msg').text('');
            var data = new FormData(this);
            var l = Ladda.create(document.getElementById('productorAccountSbmt'));
            l.start();
            $.ajax({
                url: AccountUpdate.ajaxurl,
                type: "POST",
                data: data,
                cache: false,
                processData: false,
                contentType: false,
                context: this,
                dataType: 'json',
                success: function (resp) {


                    if (resp.flag == true) {
                        $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                        site_redirect();
                    } else {
                        $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
                    }
                }
            }).always(function () {
                l.stop();
            });
        }
    });

/* Supplier Account Update */
    $('#supplierAccount').on('submit', function () {
        var fname = $('#supplier_update_fname').val();
        var lname = $('#supplier_update_lname').val();
        var phone = $('#update_phone').val();
        var supplier_cpf = $('#supplier_cpf').val();
        var supplier_cnpj = $('#supplier_cnpj').val();
        var supplier_address = $('#supplier_address').val();
        var where_to_buy_address = $('#where_to_buy_address').val();
        var state = $('.supplier-update-user-state')[0].selectedIndex;
        var city = $('.supplier-update-user-city')[0].selectedIndex;
        var supplier_category = $('.update-supplier-category')[0].selectedIndex;
        var fname_numric_check = hasNumbers(fname);
        var lname_numric_check = hasNumbers(lname);
        
        if (!fname) {
            $('#supplier_update_fname').siblings('div.input-error-msg').text('Digite seu nome comercial.');
            $('div.input-error-msg').not($('#supplier_update_fname').siblings('div.input-error-msg')).text('');
        } else if (fname_numric_check == true) {
            $('#supplier_update_fname').siblings('div.input-error-msg').text('Commercial name only contains alphabets.');
            $('div.input-error-msg').not($('#supplier_update_fname').siblings('div.input-error-msg')).text('');
        } 
        
       /* else if (!lname) {
            $('#supplier_update_lname').siblings('div.input-error-msg').text('Enter your last name.');
            $('div.input-error-msg').not($('#supplier_update_lname').siblings('div.input-error-msg')).text('');
        } */
        /*else if (lname) {
            $('#supplier_update_lname').siblings('div.input-error-msg').text('O nome legal contém apenas alfabetos.');
            $('div.input-error-msg').not($('#supplier_update_lname').siblings('div.input-error-msg')).text('');
        }*/
        else if (state < 0) {
            $('.user-state').siblings('div.input-error-msg').text('Selecione seu estado.');
            $('div.input-error-msg').not($('.user-state').siblings('div.input-error-msg')).text('');
        } else if (city < 0) {
            $('.user-city').siblings('div.input-error-msg').text('Selecione sua cidade.');
            $('div.input-error-msg').not($('.user-city').siblings('div.input-error-msg')).text('');
        } else if (!supplier_address) {
            $('#supplier_address').siblings('div.input-error-msg').text('Digite seu endereço.');
            $('div.input-error-msg').not($('#supplier_address').siblings('div.input-error-msg')).text('');
        } else if (!where_to_buy_address) {
            $('#where_to_buy_address').siblings('div.input-error-msg').text('Entre no site da sua empresa.');
            $('div.input-error-msg').not($('#where_to_buy_address').siblings('div.input-error-msg')).text('');
        } else if (supplier_category < 0) {
            $('.update-supplier-category').siblings('div.input-error-msg').text('Selecione sua categoria.');
            $('div.input-error-msg').not($('.update-supplier-category').siblings('div.input-error-msg')).text('');
        } 
        else if ($('#physical_person').is(':checked') == true && !supplier_cpf) {
            $('#supplier_cpf').siblings('div.input-error-msg').text('Enter your CPF.');
            $('div.input-error-msg').not($('#supplier_cpf').siblings('div.input-error-msg')).text('');
        } else if ($('#judicial_person').is(':checked') == true && !supplier_cnpj) {
            $('#supplier_cnpj').siblings('div.input-error-msg').text('Enter your CNPJ.');
            $('div.input-error-msg').not($('#supplier_cnpj').siblings('div.input-error-msg')).text('');
        }
        else {
            $('div.input-error-msg').text('');
            var data = new FormData(this);
            var l = Ladda.create(document.getElementById('supplierAccountSbmt'));
            l.start();
            $.ajax({
                url: AccountUpdate.ajaxurl,
                type: "POST",
                data: data,
                cache: false,
                processData: false,
                contentType: false,
                context: this,
                dataType: 'json',
                success: function (resp) {
                    if (resp.flag == true) {
                        $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                        site_redirect();
                    } else {
                        $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
                    }
                }
            }).always(function () {
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

/* Check only alphabets */
function isAlphaOrParen(str) {
    return /^[a-zA-Z() ]+$/.test(str);
}

/* Check has numbers */
function hasNumbers(str) {
    return /\d/.test(str);
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

/* User img showing on upload */
function readURL(input, file_selector) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            jQuery(file_selector).attr('src', e.target.result);
            jQuery(file_selector).attr('width', '128');
            jQuery(file_selector).attr('height', '128');
        };
        reader.readAsDataURL(input.files[0]);
    }
}