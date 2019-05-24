<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * --------------------------------------------
 * AJAX:: Get Cities w.r.t. State
 * --------------------------------------------
 */
add_action('wp_ajax_state_selection', 'ajaxStateCitySelection');
add_action('wp_ajax_nopriv_state_selection', 'ajaxStateCitySelection');

if (!function_exists('ajaxStateCitySelection')) {

    function ajaxStateCitySelection() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $msg = NULL;
        $state_id = $_POST['state_id'];
        $getCities = $GeneralThemeObject->getCities($state_id);

        if (is_array($getCities) && count($getCities) > 0) {
            $msg .= '<option value=""></option>';
            foreach ($getCities as $eachCity) {
                $msg .= '<option value="' . $eachCity->term_id . '">' . $eachCity->name . '</option>';
            }
        } else {
            $msg = '<option value="">Nenhuma cidade disponível.</option>';
        }
        $resp_arr['flag'] = TRUE;
        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}