<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * --------------------------------------------
 * AJAX:: Function Description
 * --------------------------------------------
 */

add_action('wp_ajax_find_suppliers', 'ajaxSupplierSearch');
add_action('wp_ajax_nopriv_find_suppliers', 'ajaxSupplierSearch');

if (!function_exists('ajaxSupplierSearch')) {

    function ajaxSupplierSearch() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => '', 'marker' => '', 'infoWindowContent' => '', 'countSupplier' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $userCity = $GeneralThemeObject->getLandingCity();
        $msg = NULL;
        $supplier_location = strip_tags(trim($_POST['supplier_location']));
        $supplier_location_loc = strip_tags(trim($_POST['supplier_location_loc']));
        $supplier_radius = $_POST['supplier_radius'];
        $explodedLoc = explode(',', $supplier_location_loc);
        $getSupplierArgs = ['role' => 'supplier'];
        $testMetaArr = [];
        $infoWindowContent = NULL;
        $infoWindowContentArr = [];
        $listAddress = [];

        if (empty($supplier_location)) {
            $msg = __('Please enter an address.', THEME_TEXTDOMAIN);
        } else if (empty($supplier_location_loc)) {
            $msg = __('Your entered address does not supply any proper location. Please select from our suggested list.', THEME_TEXTDOMAIN);
        } else if (is_array($explodedLoc) && count($explodedLoc) == 0) {
            $msg = __('Your entered address does not supply any proper location.', THEME_TEXTDOMAIN);
        } else {
            $getNearBy = $GeneralThemeObject->getNearby($explodedLoc[0], $explodedLoc[1], $supplier_radius, $supplier_radius, 'km');

            if (is_array($getNearBy) && count($getNearBy) > 0) {
                //$testMetaArr['relation'] = 'OR';
                foreach ($getNearBy as $eachNearBy) {
                    $listAddress[] = $eachNearBy->address;
                }
                $testMetaArr[] = [
                    'key' => '_user_address',
                    'value' => $listAddress,
                    'compare' => 'IN'
                ];
            } else {
                $testMetaArr[] = [
                    'key' => '_user_address',
                    'value' => $supplier_location,
                    'compare' => 'LIKE'
                ];
            }
            $testMetaArr[] = [
                'key' => '_allow_where_to_buy',
                'value' => 1,
                'compare' => '='
            ];

            /* if ($userCity) {
              $testMetaArr['relation'] = 'AND';
              $testMetaArr[] = [
              'key' => '_city',
              'value' => $userCity,
              'compare' => '='
              ];
              } */

            $getSupplierArgs['meta_query'] = $testMetaArr;
            /* echo '<pre>';
              print_r($getSupplierArgs);
              echo '</pre>';
              exit; */
            $getSupplierForMaps = $GeneralThemeObject->getSupplierForMap($getSupplierArgs);
            if (is_array($getSupplierForMaps) && count($getSupplierForMaps) > 0) {
                $infoContent = 0;
                foreach ($getSupplierForMaps as $eachSupplierMap) {
                    $getUserDetails = $GeneralThemeObject->user_details($eachSupplierMap['user_id']);
                    $supplierPlan = $GeneralThemeObject->getMembershipPlanDetails($getUserDetails->data['selected_plan']);
                    if ($supplierPlan->data['name'] == 'gold'):
                        $imgClass = 'gold-class';
                    elseif ($supplierPlan->data['name'] == 'silver'):
                        $imgClass = 'silver-class';
                    else:
                        $imgClass = 'bronze-class';
                    endif;
                    $infoWindowContent = NULL;
                    $infoWindowContent .= '<div class="media">';
                    //$infoWindowContent .= '<div class="media-left"><a href="' . $eachSupplierMap['where_to_buy'] . '" target="_blank"><img class="' . $imgClass . '" src="' . $eachSupplierMap['thumbnail'] . '" style="width: 100px; height: 100px;"></a></div>';
                    $infoWindowContent .= '<div class="media-left"><a href="' . get_author_posts_url($eachSupplierMap['user_id']) . '" target="_blank"><img class="' . $imgClass . '" src="' . $eachSupplierMap['thumbnail'] . '" style="width: 100px; height: 100px;"></a></div>';
                    $infoWindowContent .= '<div class="media-body">';
                    //$infoWindowContent .= '<div class="supp-title"><a href="' . $eachSupplierMap['where_to_buy'] . '" target="_blank">' . $eachSupplierMap['cname'] . '</a></div>';
                    $infoWindowContent .= '<div class="supp-title"><a href="' . get_author_posts_url($eachSupplierMap['user_id']) . '" target="_blank">' . $eachSupplierMap['cname'] . '</a></div>';
                    //$infoWindowContent .= '<div class="supp-title"><a href="' . $eachSupplierMap['where_to_buy'] . '" target="_blank">' . $eachSupplierMap['lname'] . '</a></div>';
                    $infoWindowContent .= '<div class="supp-rating">' . $eachSupplierMap['rating'] . '</div>';
                    $infoWindowContent .= '<div class="supp-addr">' . $eachSupplierMap['phone'] . '</div>';
                    $infoWindowContent .= '<div class="supp-addr">' . $eachSupplierMap['address'] . '</div>';
                    $infoWindowContent .= '</div></div>';
                    $infoWindowContentArr[$infoContent]['content'] = $infoWindowContent;
                    $infoContent++;
                }
                $resp_arr['flag'] = TRUE;
                $resp_arr['marker'] = $getSupplierForMaps;
                $resp_arr['infoWindowContent'] = $infoWindowContentArr;
                $resp_arr['countSupplier'] = __('Total ' . count($getSupplierForMaps) . ' supplier(s) found according to your search.', THEME_TEXTDOMAIN);
            } else {
                $msg = __('Sorry, no supplier found according to your search.', THEME_TEXTDOMAIN);
                $getNoSupplierForMaps = [];
                $getUserEnteredLatLng = explode(',', $supplier_location_loc);
                $getNoSupplierForMaps[0]['lat'] = $getUserEnteredLatLng[0];
                $getNoSupplierForMaps[0]['lng'] = $getUserEnteredLatLng[1];
                $resp_arr['marker'] = $getNoSupplierForMaps;
            }
        }
        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}