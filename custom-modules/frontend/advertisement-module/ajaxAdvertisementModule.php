<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * --------------------------------------------
 * AJAX:: Advertisement Module
 * --------------------------------------------
 */

add_action('wp_ajax_advertisement_click', 'ajaxAdvertisementClick');
add_action('wp_ajax_nopriv_advertisement_click', 'ajaxAdvertisementClick');

if (!function_exists('ajaxAdvertisementClick')) {

    function ajaxAdvertisementClick() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $msg = NULL;
        $adv_id = base64_decode($_POST['adv_id']);
        $advViewer = get_post_meta($adv_id, '_adv_view_counter', TRUE);
        $advURL = get_post_meta($adv_id, '_adv_url', TRUE);
        $newCounter = ($advViewer > 0) ? $advViewer + 1 : 1;
        update_post_meta($adv_id, '_adv_view_counter', $newCounter);
        $resp_arr['flag'] = TRUE;
        $resp_arr['msg'] = $msg;
        $resp_arr['url'] = $advURL;
        echo json_encode($resp_arr);
        exit;
    }

}