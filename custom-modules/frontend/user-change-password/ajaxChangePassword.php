<?php

/*
 * --------------------------------------------
 * AJAX:: User Change Password
 * --------------------------------------------
 */

add_action('wp_ajax_change_password', 'ajaxChangePassword');
add_action('wp_ajax_nopriv_change_password', 'ajaxChangePassword');

if (!function_exists('ajaxChangePassword')) {

    function ajaxChangePassword() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $user_details = get_userdata(get_current_user_id());
        $msg = NULL;
        $customer_old_pass = strip_tags(trim($_POST['oldpassword']));
        $customer_new_pass = strip_tags(trim($_POST['password']));
        $customer_confirm_pass = strip_tags(trim($_POST['cnfpassword']));
        $user_pass = $user_details->user_pass;
        $password_validation = $GeneralThemeObject->passwordValidation($customer_new_pass);

        if (empty($customer_old_pass)) {
            $msg = __('Enter your old password.', THEME_TEXTDOMAIN);
        } elseif (!wp_check_password($customer_old_pass, $user_pass)) {
            $msg = __('Your old password does not matched.', THEME_TEXTDOMAIN);
        } elseif (empty($customer_new_pass) || (strlen($customer_new_pass) < 8)) {
            $msg = __('Enter your new 8 digit alphanumeric password.', THEME_TEXTDOMAIN);
        }
       /* elseif ($password_validation == 0) {
            $msg = 'Your password should contain atleast one Upper case letter.';
        }*/
        elseif (strcmp($customer_old_pass, $customer_new_pass) == 0) {
            $msg = __('Your new password should be different from old one.', THEME_TEXTDOMAIN);
        } elseif (strcmp($customer_new_pass, $customer_confirm_pass) != 0) {
            $msg = __('Please confirm your new password.', THEME_TEXTDOMAIN);
        } else {
            $update_user_data = [
                'ID' => $user_details->ID,
                'user_pass' => $customer_new_pass
            ];
            wp_update_user($update_user_data);
            $resp_arr['flag'] = true;

            $msg = __('Your password has been successfully changed.', THEME_TEXTDOMAIN);
        }

        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}