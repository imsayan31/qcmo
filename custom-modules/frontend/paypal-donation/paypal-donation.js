jQuery(document).ready(function ($) {

    $("#donate_phone").mask("(99) 99999-9999");
    
    //$("#donate_amount").mask("9.99");

    /* paypal donation */
    $('#user_donation_frm').on('submit', function () {
        var fname = $('#donate_fname').val();
        var lname = $('#donate_lname').val();
        var email = $('#donate_email').val();
        var phone = $('#donate_phone').val();
        var donate_amount = $('#donate_amount').val();
        var terms_checked = $('#c_donate').is(':checked');
        var fname_validation = isAlphaOrParen(fname);
        var lname_validation = isAlphaOrParen(lname);
        var email_validation = isEmail(email);
        var price_validation = priceValidation(donate_amount);

        if (!fname) {
            $('#donate_fname').siblings('div.input-error-msg').text('Enter your first name.');
            $('div.input-error-msg').not($('#donate_fname').siblings('div.input-error-msg')).text('');
        } else if (fname_validation == false) {
            $('#donate_fname').siblings('div.input-error-msg').text('First name should not contain numbers.');
            $('div.input-error-msg').not($('#donate_fname').siblings('div.input-error-msg')).text('');
        } else if (!lname) {
            $('#donate_lname').siblings('div.input-error-msg').text('Enter your last name.');
            $('div.input-error-msg').not($('#donate_lname').siblings('div.input-error-msg')).text('');
        } else if (lname_validation == false) {
            $('#donate_lname').siblings('div.input-error-msg').text('Last name should not contain numbers.');
            $('div.input-error-msg').not($('#donate_lname').siblings('div.input-error-msg')).text('');
        } else if (!email) {
            $('#donate_email').siblings('div.input-error-msg').text('Enter your mail.');
            $('div.input-error-msg').not($('#donate_email').siblings('div.input-error-msg')).text('');
        } else if (email_validation == false) {
            $('#donate_email').siblings('div.input-error-msg').text('Email is not in proper format.');
            $('div.input-error-msg').not($('#donate_email').siblings('div.input-error-msg')).text('');
        } else if (!phone) {
            $('#donate_phone').siblings('div.input-error-msg').text('Enter your phone.');
            $('div.input-error-msg').not($('#donate_phone').siblings('div.input-error-msg')).text('');
        } else if (!donate_amount) {
            $('#donate_amount').siblings('div.input-error-msg').text('Enter the amount to be donated.');
            $('div.input-error-msg').not($('#donate_amount').siblings('div.input-error-msg')).text('');
        } else if (donate_amount < 1) {
            $('#donate_amount').siblings('div.input-error-msg').text('Donated amount should be greater than 1.00');
            $('div.input-error-msg').not($('#donate_amount').siblings('div.input-error-msg')).text('');
        } else if (price_validation == false) {
            $('#donate_amount').siblings('div.input-error-msg').text('Donated amount format : 1.00 or 10.00 or 100.00 or 1000.00');
            $('div.input-error-msg').not($('#donate_amount').siblings('div.input-error-msg')).text('');
        } else if (terms_checked == false) {
            $('.forgt-error').html('');
            $('.terms-error').html('Ensure that you have agreed all our terms and conditions.');
            $('div.input-error-msg').not($('.terms-error')).text('');
        } else {
            $('.forgt-error').html('');
            $('.terms-error').html('');
            var data = $(this).serialize();
            var l = Ladda.create(document.getElementById('usr_donate_sbmt'));
            l.start();
            $.post(PayPalDonation.ajaxurl, data, function (resp) {
                if (resp.flag == true) {
                    $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                    site_redirect(resp.url);
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
    }, 6000);
}

/* Check if is email */
function isEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

/* Check if is price */
function priceValidation(price) {
    var valid = /^\d{0,100}(\.\d{0,2})?$/.test(price),
            val = price;
    if (!valid) {
        //alert("Invalid input!");
        //this.value = val.substring(0, val.length - 1);
        return false;
    } else {
        return true;
    }
}