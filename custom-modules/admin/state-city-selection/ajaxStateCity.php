<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * --------------------------------------------
 * AJAX:: Admin State City Selection
 * --------------------------------------------
 */
add_action('wp_ajax_admin_state_selection', 'ajaxAdminStateCitySelection');

if (!function_exists('ajaxAdminStateCitySelection')) {

    function ajaxAdminStateCitySelection() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $msg = NULL;
        $state_id = $_POST['state_id'];
        $getCities = $GeneralThemeObject->getCities($state_id);

        if (is_array($getCities) && count($getCities) > 0) {
            //$msg .= '<option value="">-Select cities-</option>';
            $msg .= '<option value="99999999">All cities</option>';
            foreach ($getCities as $eachCity) {
                $msg .= '<option value="' . $eachCity->term_id . '">' . $eachCity->name . '</option>';
            }
        } else {
            $msg = '<option value="">No city avaialable</option>';
        }
        
        $resp_arr['flag'] = TRUE;
        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}

/*
 * --------------------------------------------
 * AJAX:: Admin State City Selection
 * --------------------------------------------
 */
add_action('wp_ajax_sub_admin_state_selection', 'ajaxSubAdminStateCitySelection');

if (!function_exists('ajaxSubAdminStateCitySelection')) {

    function ajaxSubAdminStateCitySelection() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $msg = NULL;
        $state_id = $_POST['state_id'];
        $getCities = $GeneralThemeObject->getCities($state_id);

        if (is_array($getCities) && count($getCities) > 0) {
            // $msg .= '<option value="">-Select cities-</option>';
            foreach ($getCities as $eachCity) {
                //$msg .= '<option value="' . $eachCity->term_id . '">' . $eachCity->name . '</option>';
                $msg .= '<label for="'. $eachCity->slug .'"><input type="checkbox" name="city[]" id="'. $eachCity->slug .'" class="check-sub-admin-multi-city" value="' . $eachCity->term_id . '" />' . $eachCity->name . ' </label>';
            }
        } else {
            $msg = '<option value="">No city avaialable</option>';
        }
        
        $resp_arr['flag'] = TRUE;
        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}