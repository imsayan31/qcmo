<?php

/*
 * --------------------------------------------
 * AJAX:: Select City On Landing
 * --------------------------------------------
 */

add_action('wp_ajax_landing_city_selection', 'ajaxSelectLandingCity');
add_action('wp_ajax_nopriv_landing_city_selection', 'ajaxSelectLandingCity');

if (!function_exists('ajaxSelectLandingCity')) {

    function ajaxSelectLandingCity() {
        $GeneralThemeObject = new GeneralTheme();
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $msg = NULL;
        $select_your_state = $_POST['select_your_state'];
        $select_your_city = $_POST['select_your_city'];

        if (empty($select_your_state)) {
            $msg = __('Please select your state.', THEME_TEXTDOMAIN);
        } else if (empty($select_your_city)) {
            $msg = __('Please select your city.', THEME_TEXTDOMAIN);
        } else if (is_user_logged_in()) {
            $userDetails = $GeneralThemeObject->user_details();
            update_user_meta($userDetails->data['user_id'], '_city', $select_your_city);
            $GeneralThemeObject->setLandingCity($select_your_state, $select_your_city);
            $msg = __('Your city has been updated successfully.', THEME_TEXTDOMAIN);
            $resp_arr['flag'] = TRUE;
            $resp_arr['url'] = MY_ACCOUNT_PAGE;
//        } elseif (!isset($_COOKIE['andre_anonymous_city'])) {
        } else {
            $GeneralThemeObject->setLandingCity($select_your_state, $select_your_city);
            $msg = __('Get your nearest available products.', THEME_TEXTDOMAIN);
            $resp_arr['flag'] = TRUE;
            $resp_arr['url'] = '';
        }

        $resp_arr['msg'] = $msg;

        echo json_encode($resp_arr);
        exit;
    }

}