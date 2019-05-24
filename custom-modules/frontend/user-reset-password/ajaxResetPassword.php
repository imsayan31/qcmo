<?php

/**
 * --------------------------------------------
 * AJAX:: Reset Password
 * --------------------------------------------
 */
add_action('wp_ajax_myrental_rst_pass', 'ajaxResetPassword');
add_action('wp_ajax_nopriv_myrental_rst_pass', 'ajaxResetPassword');


if (!function_exists('ajaxResetPassword')) {

    function ajaxResetPassword() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $msg = NULL;
        $tenant = base64_decode($_POST['tenant']);
        $myrental_new_pass = strip_tags(trim($_POST['myrental_new_pass']));
        $myrental_new_cnf_pass = strip_tags(trim($_POST['myrental_new_cnf_pass']));
        $password_validation = $GeneralThemeObject->passwordValidation($myrental_new_pass);

        if (empty($myrental_new_pass)) {
            $msg = __('Enter new password.', THEME_TEXTDOMAIN);
        } 
       /* elseif ($password_validation == 0) {
            $msg = 'Password must contain atleast one Upper case letter.';
        }*/
        elseif (strlen($myrental_new_pass) < 8) {
            $msg = __('Password length must be greater than 8.', THEME_TEXTDOMAIN);
        } elseif (strcmp($myrental_new_pass, $myrental_new_cnf_pass)) {
            $msg = __('Please ensure both your password’s are consistent.', THEME_TEXTDOMAIN);
        } else {
            $tenant_update_args = [
                'ID' => $tenant,
                'user_pass' => $myrental_new_pass
            ];
            wp_update_user($tenant_update_args);
            $resp_arr['flag'] = TRUE;
            $resp_arr['url'] = BASE_URL;
            $msg = __('Your password has been updated successfully. Now Log in.', THEME_TEXTDOMAIN);
        }
        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}