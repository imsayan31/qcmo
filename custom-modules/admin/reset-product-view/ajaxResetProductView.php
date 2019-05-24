<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * --------------------------------------------
 * AJAX:: Ajax Function
 * --------------------------------------------
 */

add_action('wp_ajax_reset_product_view', 'ajaxResetProductView');

if (!function_exists('ajaxResetProductView')) {

    function ajaxResetProductView() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $msg = NULL;
        $getAllProducts = get_posts(['post_type' => themeFramework::$theme_prefix . 'product', 'posts_per_page' => -1]);

        if (is_array($getAllProducts) && count($getAllProducts) > 0) {
            foreach ($getAllProducts as $eachProduct) {
                update_post_meta($eachProduct->ID, '_product_views', 0);
            }
        }
        update_option('_last_product_reset_time', strtotime(date('Y-m-d h:i:s a')));
        $resp_arr['msg'] = $msg;
        $resp_arr['url'] = admin_url().'edit.php?post_type=andr_product';
        echo json_encode($resp_arr);
        exit;
    }

}