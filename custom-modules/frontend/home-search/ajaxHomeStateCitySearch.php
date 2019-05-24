<?php

/*
 * --------------------------------------------
 * AJAX:: Home State City Search
 * --------------------------------------------
 */

add_action('wp_ajax_home_state_city_search', 'ajaxHomeStateCitySearch');
add_action('wp_ajax_nopriv_home_state_city_search', 'ajaxHomeStateCitySearch');

if (!function_exists('ajaxHomeStateCitySearch')) {

    function ajaxHomeStateCitySearch() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $msg = NULL;
        $state = $_POST['state'];
        $city = $_POST['city'];
        $GeneralThemeObject->setLandingCity($state, $city);
        $resp_arr['flag'] = TRUE;
        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}