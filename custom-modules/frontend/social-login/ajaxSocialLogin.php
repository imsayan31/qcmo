<?php

/*
 * --------------------------------------------
 * AJAX:: Social Login
 * --------------------------------------------
 */
add_action('wp_ajax_social_signin_process', 'ajaxSocialLogin');
add_action('wp_ajax_nopriv_social_signin_process', 'ajaxSocialLogin');

if (!function_exists('ajaxSocialLogin')) {

    function ajaxSocialLogin() {
        $response_arr = array('flag' => false, 'msg' => NULL, 'url' => '');
        $GeneralThemeFunc = new GeneralTheme();
        $username = strip_tags(trim($_POST['user_name']));
        $email = strip_tags(trim($_POST['email']));
        $first_name = strip_tags(trim($_POST['first_name']));
        $last_name = strip_tags(trim($_POST['last_name']));
        $social_img = strip_tags(trim($_POST['social_img']));

        $msg = '';
        if (empty($email)) {
            $msg = __('Email não informado.', THEME_TEXTDOMAIN);
        } else {
            if (email_exists($email)) {
                $GeneralThemeFunc->auto_wp_login($email);
            } else {
                $password = wp_generate_password();
                $userID = wp_create_user($email, $password, $email);

                update_user_meta($userID, 'first_name', $first_name);
                update_user_meta($userID, 'last_name', $last_name);
                update_user_meta($userID, '_social_login', 1);
                
                $GeneralThemeFunc->auto_wp_login($email);

                /* mail to user */
                $get_seller_email_template = $GeneralThemeFunc->getEmailContents('mail-to-user-for-registration', ['{%user%}'], [$first_name . ' ' . $last_name]);
                $mail_subject = get_bloginfo('name') . ' :: ' . $get_seller_email_template[0];
                $mail_cont = $GeneralThemeFunc->theme_email_template(get_bloginfo('name'), $get_seller_email_template[1]);
                $GeneralThemeFunc->send_mail_func($email, $mail_subject, $mail_cont);

                /* mail to administrator */
                $admin_email = get_option('admin_email');
                $get_admin_email_template = $GeneralThemeFunc->getEmailContents('mail-to-admin-for-user-social-registration', ['{%user%}', '{%email%}'], [$first_name . ' ' . $last_name, $email]);
                $admin_mail_subject = get_bloginfo('name') . ' :: ' . $get_admin_email_template[0];
                $admin_mail_cont = $GeneralThemeFunc->theme_email_template(get_bloginfo('name'), $get_admin_email_template[1]);
                $GeneralThemeFunc->send_mail_func($admin_email, $admin_mail_subject, $admin_mail_cont);
            }
            $response_arr['url'] = BASE_URL;
            $response_arr['flag'] = true;
            $msg = __('Bem vindo ao ' . get_bloginfo('name'), THEME_TEXTDOMAIN);
        }
        $response_arr['msg'] = $msg;
        echo json_encode($response_arr);
        exit;
    }

}