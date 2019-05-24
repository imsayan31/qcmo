<?php

/*
 * --------------------------------------------
 * AJAX:: Forgot Password
 * --------------------------------------------
 */

add_action('wp_ajax_usr_forgot_pass', 'ajaxUserForgotPassword');
add_action('wp_ajax_nopriv_usr_forgot_pass', 'ajaxUserForgotPassword');

if (!function_exists('ajaxUserForgotPassword')) {

    function ajaxUserForgotPassword() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => '', 'user_id' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $msg = NULL;
        $user_frgt_email = strip_tags(trim($_POST['user_frgt_email']));
        if (empty($user_frgt_email)) {
            $msg = __('Enter your registered email.', THEME_TEXTDOMAIN);
        } elseif (!is_email($user_frgt_email)) {
            $msg = __('Email is not in proper format.', THEME_TEXTDOMAIN);
        } elseif (!email_exists($user_frgt_email)) {
            $msg = __('This is not a registered email. Please enter your registered email.', THEME_TEXTDOMAIN);
        } else {
            $get_user_by_email = get_user_by('email', $user_frgt_email);
             $system_password = wp_generate_password(8);
              $update_passwrd_data = [
              'ID' => $get_user_by_email->ID,
              'user_pass' => $system_password
              ];
              wp_update_user($update_passwrd_data); 

            $resp_arr['flag'] = true;
            $resp_arr['user_id'] = base64_encode($get_user_by_email->ID);
            $msg = __('Check your email to reset your password.', THEME_TEXTDOMAIN);

            $resetPasswordLink = BASE_URL . '?resetUser=' . base64_encode($get_user_by_email->ID);
            update_user_meta($get_user_by_email->ID, '_reset_pass_link', $resetPasswordLink);

            /* Sending email to User */
            $get_forgot_password_email_template = $GeneralThemeObject->getEmailContents('mail-to-user-for-forgot-password', ['{%user%}', '{%reset_password_link%}'], [$get_user_by_email->first_name . ' ' . $get_user_by_email->last_name, $resetPasswordLink]);
            $owner_mail_subject = get_bloginfo('name') . ' :: ' . $get_forgot_password_email_template[0];
            $owner_email_template = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $get_forgot_password_email_template[1]);
            $GeneralThemeObject->send_mail_func($user_frgt_email, $owner_mail_subject, $owner_email_template);
        }
        $resp_arr['msg'] = $msg;
        $resp_arr['url'] = BASE_URL;
        echo json_encode($resp_arr);
        exit;
    }

}