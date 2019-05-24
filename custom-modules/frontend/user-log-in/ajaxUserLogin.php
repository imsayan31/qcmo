<?php

/*
 * --------------------------------------------
 * AJAX:: User Log in
 * --------------------------------------------
 */

add_action('wp_ajax_user_login', 'ajaxUserLogin');
add_action('wp_ajax_nopriv_user_login', 'ajaxUserLogin');

if (!function_exists('ajaxUserLogin')) {

    function ajaxUserLogin() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $msg = NULL;
        $user_login_email = strip_tags(trim($_POST['user_login_email']));
        $user_login_password = strip_tags(trim($_POST['user_login_password']));
        $user_login_remember = strip_tags(trim($_POST['user_login_remember']));
        $redirect_page = base64_decode($_POST['redirect_page']);
        $redirectToPage = get_permalink($redirect_page);

        $get_user_by = get_user_by('email', $user_login_email);

        $getUserActiveStatus = get_user_meta($get_user_by->ID, '_active_status', true);
        $getUserAdminApprovalStatus = get_user_meta($get_user_by->ID, '_admin_approval', true);

        if (empty($user_login_email)) {
            $msg = __('Enter email.', THEME_TEXTDOMAIN);
        } elseif (!is_email($user_login_email)) {
            $msg = __('Enter proper email.', THEME_TEXTDOMAIN);
        } elseif (!email_exists($user_login_email)) {
            $msg = __('Email not exists in our site.', THEME_TEXTDOMAIN);
        } elseif (empty($user_login_password)) {
            $msg = __('Enter password.', THEME_TEXTDOMAIN);
        } elseif (!$get_user_by) {
            $msg = __('Email/password is not correct.', THEME_TEXTDOMAIN);
        } elseif ($getUserActiveStatus == 2) {
            $msg = __('Your account is not activated yet. Check your registered mailbox to activate your account.', THEME_TEXTDOMAIN);
        } elseif ($getUserAdminApprovalStatus != 1 && $get_user_by->roles[0] == 'supplier') {
            $msg = __('Your account is not reviewed and approved by our administrator yet. Please wait for our confirmation email.', THEME_TEXTDOMAIN);
        } else {
            $creds = ['user_login' => $get_user_by->user_login, 'user_password' => $user_login_password];
            $user = wp_signon($creds, FALSE);
            if (is_wp_error($user)) {
                $msg = __('Email/password is not correct.', THEME_TEXTDOMAIN);
            } else {
                update_user_meta($get_user_by->ID, '_last_logged_in', strtotime(date('Y-m-d h:i:s')));
                $resp_arr['flag'] = true;
                $creds['rememberme'] = $user_login_remember;
                $GeneralThemeObject->set_site_cookie($creds);
                $msg = __('Welcome to ' . get_bloginfo('name'), THEME_TEXTDOMAIN);
                if ((strcmp($redirectToPage, BASE_URL . '/') == 0) && ($get_user_by->roles[0] == 'subscriber')) {
                    $redirectToPage = MY_ACCOUNT_PAGE;
                } else if ((strcmp($redirectToPage, BASE_URL . '/') == 0) && ($get_user_by->roles[0] == 'supplier')) {
                    $redirectToPage = SUPPLIER_DASHBOARD_PAGE;
                } else if($redirectToPage) {
                    $redirectToPage = $redirectToPage;
                } else{
                    $redirectToPage = '';
                }
                $resp_arr['url'] = $redirectToPage;
            }
        }

        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}