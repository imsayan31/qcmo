jQuery(document).ready(function ($) {

    hello.init({
        facebook: Front.fbapp,
        //google: Front.googleclient
    }, {redirect_uri: Front.base_url});
    /*hello('facebook').login({ scope: 'email,public_profile,friends, photos, publish'});
     hello('google').login({ scope: 'email,https://www.googleapis.com/auth/userinfo.email' });*/


    /*Authenticate the FB Connection*/
    hello.on('auth', function (auth) {

        // call user information, for the given network
        hello(auth.network).api('/me').then(function (r) {
            //Open The Modal For SignUP With FB

            var data = {
                action: 'social_signin_process',
                login_type: 'social',
                user_name: r.email,
                email: r.email,
                first_name: r.first_name,
                last_name: r.last_name,
                social_img: r.thumbnail
            };
            //$('#social_popup').modal('show');
            //setSocialDataToForm(r, '#social_popup');
            $.post(Front.ajaxurl, data, function (resp) {
                if (resp.flag === true) {
                    $.notify({message: resp.msg}, {type: 'success', z_index: 20000, delay: 3000});
                    window.location.href = resp.url;
                } else {
                    $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, delay: 5000});
//                    $.notify(resp.msg, {color: "#fff", background: "#D44950", close: true, delay: 4000});
                }
            }, 'json');


        });
    });

    $('div.modal').on('shown.bs.modal', function () {
        activeModal = $(this).attr('id');


    });

});

function setSocialDataToForm(obj, formID) {
    var $ = jQuery;
    console.log(obj);
    $(formID).find('#welcome_txt').text("Welcome, " + obj.first_name + "! Processing...");
    $(formID).find('#usr_img img').attr('src', obj.thumbnail);


}
