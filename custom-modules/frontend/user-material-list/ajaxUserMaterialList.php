<?php

/*
 * --------------------------------------------
 * AJAX:: Ajax Function
 * --------------------------------------------
 */

add_action('wp_ajax_get_material_list', 'ajaxGetMaterialList');
add_action('wp_ajax_nopriv_get_material_list', 'ajaxGetMaterialList');

if (!function_exists('ajaxGetMaterialList')) {

    function ajaxGetMaterialList() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $FinalizeObject = new classFinalize();
        $CartObject = new classCart();
        $msg = NULL;
        $productArr = [];
        $deal_id = base64_decode($_POST['deal_id']);
        $material_category_value = $_POST['material_category_value'];
        $userDetails = $GeneralThemeObject->user_details();

        $getCategoryDetails = get_term_by('name', $material_category_value, themeFramework::$theme_prefix . 'product_category');

        $getProductsByCategory = get_posts(['post_type' => themeFramework::$theme_prefix . 'product', 'posts_per_page' => -1, 'tax_query' => [
                [
                    'taxonomy' => themeFramework::$theme_prefix . 'product_category',
                    'field' => 'slug',
                    'terms' => $getCategoryDetails->slug
                ]
        ]]);

        if (is_array($getProductsByCategory) && count($getProductsByCategory) > 0) {
            foreach ($getProductsByCategory as $eachCategory) {
                $productArr[] = $eachCategory->ID;
            }
        }

        if ($deal_id != '') {
            $dealProductDetails = $FinalizeObject->getDealProductDetails($deal_id);
            if (is_array($dealProductDetails) && count($dealProductDetails) > 0) {
                foreach ($dealProductDetails as $eachProductCheck) {
                    //if(is_array($eachProductCheck['product_id']))
                }
            }
        } else {
            $getUserCartData = $CartObject->getCartItems($userDetails->data['user_id']);
        }



        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}