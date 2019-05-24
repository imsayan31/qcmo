<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * --------------------------------------------
 * AJAX:: Ajax Get Adv Settings Price
 * --------------------------------------------
 */

add_action('wp_ajax_adv_settings_get_price', 'ajaxGetAdvSettingsPrice');

if (!function_exists('ajaxGetAdvSettingsPrice')) {

    function ajaxGetAdvSettingsPrice() {

        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $msg = NULL;
        $totalPrice = 0;
        $mainTimePrice = 0;

        /* Get price for advertisements */
        $get_advert_link_price = get_option('_advert_link_price');
        $get_advert_position_price = get_option('_advert_position_price');
        $get_advert_city_price = get_option('_advert_city_price');
        $get_advert_category_price = get_option('_advert_category_price');
        $get_advert_page_price = get_option('_advert_page_price');
        $get_advert_time_price = get_option('_advert_time_price');

        $adv_url = $_POST['adv_url'];
        $adv_position = $_POST['adv_position'];
        $adv_state = $_POST['adv_state'];
        $adv_city = $_POST['adv_city'];
        $adv_init_date = $_POST['adv_init_date'];
        $adv_final_date = $_POST['adv_final_date'];
        $adv_init_time = $_POST['adv_init_time'];
        $adv_final_time = $_POST['adv_final_time'];
        $adv_cat = $_POST['adv_cat'];
        $adv_page = $_POST['adv_page'];

        //$advDet = $GeneralThemeObject->getAdvertisementPrice(237);

        $dateDiffCal = $GeneralThemeObject->date_difference($adv_init_date, $adv_final_date);

        /* Link */
        if ($adv_url != '') {
            $totalPrice = $totalPrice + $get_advert_link_price['link'];
        }

        /* Position */
        if (is_array($adv_position) && count($adv_position) > 0 && in_array(1, $adv_position)) {
            $totalPrice = $totalPrice + $get_advert_position_price['top'];
        }

        if (is_array($adv_position) && count($adv_position) > 0 && in_array(2, $adv_position)) {
            $totalPrice = $totalPrice + $get_advert_position_price['middle'];
        }

        if (is_array($adv_position) && count($adv_position) > 0 && in_array(3, $adv_position)) {
            $totalPrice = $totalPrice + $get_advert_position_price['bottom'];
        }

        /* State & City */
        $getAllCities = $GeneralThemeObject->getCities($adv_state);
        if (is_array($getAllCities) && count($getAllCities) > 0) {
            foreach ($getAllCities as $eachCity) {
                if (is_array($adv_city) && count($adv_city) > 0 && in_array($eachCity->term_id, $adv_city)) {
                    $totalPrice = $totalPrice + $get_advert_city_price[$eachCity->slug];
                }
            }
        }

        /* Category */
        $getProductCategories = get_terms(themeFramework::$theme_prefix . 'product_category', ['hide_empty' => FALSE, 'parent' => 0]);
        if (is_array($getProductCategories) && count($getProductCategories) > 0) {
            foreach ($getProductCategories as $eachCat) {
                $getProductSubCategories = get_terms(themeFramework::$theme_prefix . 'product_category', ['hide_empty' => FALSE, 'parent' => $eachCat->term_id]);
                if (is_array($adv_cat) && count($adv_cat) > 0 && in_array($eachCat->term_id, $adv_cat)) {
                    $totalPrice = $totalPrice + $get_advert_category_price[$eachCat->slug];
                }
                if (is_array($getProductSubCategories) && count($getProductSubCategories) > 0) {
                    foreach ($getProductSubCategories as $eachSubCat) {
                        if (is_array($adv_cat) && count($adv_cat) > 0 && in_array($eachSubCat->term_id, $adv_cat)) {
                            $totalPrice = $totalPrice + $get_advert_category_price[$eachCat->slug];
                        }
                    }
                }
            }
        }

        /* Page */
        $getTemplatePages = get_posts(['post_type' => 'page', 'posts_per_page' => -1]);
        if (is_array($adv_page) && count($adv_page) > 0 && in_array(2, $adv_page)) {
            $totalPrice = $totalPrice + $get_advert_page_price['category'];
        }

        if (is_array($adv_page) && count($adv_page) > 0 && in_array(3, $adv_page)) {
            $totalPrice = $totalPrice + $get_advert_page_price['product'];
        }

        if (is_array($adv_page) && count($adv_page) > 0 && in_array(4, $adv_page)) {
            $totalPrice = $totalPrice + $get_advert_page_price['supplier_public_profile'];
        }

        if (is_array($getTemplatePages) && count($getTemplatePages) > 0) {
            foreach ($getTemplatePages as $eachTemplatePage) {
                $pageMetaField = get_post_meta($eachTemplatePage->ID, '_wp_page_template', TRUE);
                if ($pageMetaField != 'default') {
                    if (is_array($adv_page) && count($adv_page) > 0 && in_array($eachTemplatePage->post_name, $adv_page)) {
                        $totalPrice = $totalPrice + $get_advert_page_price[$eachTemplatePage->post_name];
                    }
                }
            }
        }

        /* Time */
        if ($adv_init_time && $adv_final_time) {
            $starTime = strtotime($adv_init_time);
            $endTime = strtotime($adv_final_time);
            $timeDifference = ($endTime - $starTime);
            $getHourVal = (($timeDifference / 3600));
        }

        /* for ($i = 1; $i <= $getHourVal; $i++) {
          $getNextHour = date('H:i', strtotime('+' . $i . ' hour', $starTime));
          $prevTime = date('H:i', strtotime('-1 hour', strtotime($getNextHour)));
          if (!empty($get_advert_time_price[$prevTime . '-' . $getNextHour])) {
          $mainTimePrice = $mainTimePrice + $get_advert_time_price[$prevTime . '-' . $getNextHour];
          } else {
          $mainTimePrice = $mainTimePrice + $get_advert_time_price['hourly'];
          }
          } */
        if ($dateDiffCal->days > 0) {
            $totalPrice = $totalPrice + ($getHourVal * $get_advert_time_price['hourly'] * $dateDiffCal->days);
        } else {
            $totalPrice = $totalPrice + ($getHourVal * $get_advert_time_price['hourly']);
        }

        $resp_arr['flag'] = TRUE;
        $resp_arr['msg'] = ($totalPrice > 0) ? number_format($totalPrice, 2) : '0.00';
        echo json_encode($resp_arr);
        exit;
    }

}

add_action('wp_ajax_admin_advertisement_state_selection', 'ajaxAdminAdvertisementStateCitySelection');

if (!function_exists('ajaxAdminAdvertisementStateCitySelection')) {

    function ajaxAdminAdvertisementStateCitySelection() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $msg = NULL;
        $state_id = $_POST['state_id'];
        $getCities = $GeneralThemeObject->getCities($state_id);

        /* Advertisement City Price */
        $get_advert_city_price = get_option('_advert_city_price');
        if ($get_advert_city_price['all']) {
            $allCityPrice = 'R$ ' . number_format($get_advert_city_price['all'], 2);
        } else {
            $allCityPrice = 'R$ 0.00';
        }

        if (is_array($getCities) && count($getCities) > 0) {
            $msg .= '<option value="">-Select cities-</option>';
            $msg .= '<option value="99999999">All cities (' . $allCityPrice . ')</option>';
            foreach ($getCities as $eachCity) {
                if ($get_advert_city_price[$eachCity->slug]) {
                    $eachCityPrice = 'R$ ' . number_format($get_advert_city_price[$eachCity->slug], 2);
                } else {
                    $eachCityPrice = 'R$ 0.00';
                }
                $msg .= '<option value="' . $eachCity->term_id . '">' . $eachCity->name . ' (' . $eachCityPrice . ')</option>';
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