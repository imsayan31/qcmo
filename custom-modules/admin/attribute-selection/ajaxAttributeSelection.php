<?php

/*
 * --------------------------------------------
 * AJAX:: Attribute Selection
 * --------------------------------------------
 */
add_action('wp_ajax_attribute_based_price', 'ajaxAttributeSelection');

if (!function_exists('ajaxAttributeSelection')) {

    function ajaxAttributeSelection() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $msg = NULL;
        $attribute = $_POST['attribute'];
        //$cities = $_POST['cities'];
        /* $getAttributeDetails = get_term_by('slug', $attribute, themeFramework::$theme_prefix . 'product_attribute');
          $getAttributeCities = get_term_meta($getAttributeDetails->term_id, '_attribute_cities', TRUE);
          $getAttributeDefaultPrice = get_term_meta($getAttributeDetails->term_id, '_attribute_price', TRUE);
          $getAttributeUnit = get_term_meta($getAttributeDetails->term_id, '_attribute_unit', TRUE);
          $getAttributeParent = get_term_by('id', $getAttributeDetails->parent, themeFramework::$theme_prefix . 'product_attribute'); */

        $getProductDetails = $GeneralThemeObject->product_details($attribute);


        $msg .= '<table class="product_attribute_field">';
        $msg .= '<tbody>';
        $msg .= '<tr>';
        $msg .= '<td width="20%"><input type="hidden" name="product_cat[]" class="product_cat" value="' . $attribute . '">' . $getProductDetails->data['title'] . '</td>';
        $msg .= '<td width="20%">' . $getProductDetails->data['type'] . '</td>';
        $msg .= '<td width="20%"><input type="text" name="product_quantity[]" class="product_quantity" value="" placeholder="Quantity (in ' . $getProductDetails->data['unit'] . ')">' . '</td>';
        $msg .= '<td align="center" width="30%">R$ ' . $getProductDetails->data['default_price'] . '/' . $getProductDetails->data['unit'] . '</td>';
        $msg .= '<td width="10%"><a href="javascript:void(0);" class="delete_product_attribute">close</a></td>';
        $msg .= '</tr>';
        $msg .= '</tbody>';
        $msg .= '</table>';

        $resp_arr['flag'] = TRUE;
        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}

/*
 * --------------------------------------------
 * AJAX:: Get Attribute Total Price
 * --------------------------------------------
 */
add_action('wp_ajax_get_attribute_total_price', 'ajaxGetAttributeTotalPrice');

if (!function_exists('ajaxGetAttributeTotalPrice')) {

    function ajaxGetAttributeTotalPrice() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $msg = NULL;
        $product_cat = $_POST['product_cat'];
        $product_quantity = $_POST['product_quantity'];

        if (is_array($product_cat) && count($product_cat) > 0) {
            foreach ($product_cat as $eachCatKey => $eachCatVal) {
                $productDetails = $GeneralThemeObject->product_details($eachCatVal);
                $getAttributeDefaultPrice = $productDetails->data['default_price'];
                $totalAttrPrice = $product_quantity[$eachCatKey] * $getAttributeDefaultPrice;
                $msg = $msg + $totalAttrPrice;
            }
        }
        $resp_arr['flag'] = TRUE;
        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}

/*
 * --------------------------------------------
 * AJAX:: Get City Attribute Price
 * --------------------------------------------
 */
add_action('wp_ajax_get_city_attribute_price', 'ajaxGetCityAttributePrice');

if (!function_exists('ajaxGetCityAttributePrice')) {

    function ajaxGetCityAttributePrice() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $msg = NULL;
        $product_state = $_POST['product_state'];
        $product_city = $_POST['product_city'];
        $product_cat = $_POST['product_cat'];
        $product_quantity = $_POST['product_quantity'];
        $acf_product_price = $_POST['acf_product_price'];
        
        if ($product_city == '') {
            $msg .= '';
        } elseif ($product_city == '99999999') {
            $getCities = $GeneralThemeObject->getCities($product_state);
            if (is_array($getCities) && count($getCities) > 0) {
                foreach ($getCities as $eachCity) {
                    $getCityBy = get_term_by('id', $eachCity->term_id, themeFramework::$theme_prefix . 'product_city');
                    $productCityPrice = $GeneralThemeObject->getCityBasedPrices($eachCity->term_id, $product_cat, $product_quantity);

                    if(!$productCityPrice){
                        $productCityPrice = $acf_product_price;
                    }

                    $msg .= '<table class="product_city_price_field">';
                    $msg .= '<tr>';
                    $msg .= '<td width="50%"><input type="hidden" name="product_city[]" value="' . $eachCity->term_id . '"><strong>' . $eachCity->name . '</strong> : </td>';
                    $msg .= '<td width="40%">R$ <input type="text" name="product_city_price[]" value="' . $productCityPrice . '" placeholder="Enter price (in R$)"></td>';
                    $msg .= '<td width="10%"><a href="javascript:void(0);" class="delete_product_city">close</a></td>';
                    $msg .= '</tr>';
                    $msg .= '<table';
                }
            }
        } else {
            $getCityBy = get_term_by('id', $product_city, themeFramework::$theme_prefix . 'product_city');
            $productCityPrice = $GeneralThemeObject->getCityBasedPrices($product_city, $product_cat, $product_quantity);

            if(!$productCityPrice){
                        $productCityPrice = $acf_product_price;
                    }

            $msg .= '<table class="product_city_price_field">';
            $msg .= '<tr>';
            $msg .= '<td width="50%"><input type="hidden" name="product_city[]" value="' . $product_city . '"><strong>' . $getCityBy->name . '</strong> : </td>';
            $msg .= '<td width="40%">R$ <input type="text" name="product_city_price[]" value="' . $productCityPrice . '" placeholder="Enter price (in R$)"></td>';
            $msg .= '<td width="10%"><a href="javascript:void(0);" class="delete_product_city">close</a></td>';
            $msg .= '</tr>';
            $msg .= '<table';
        }


        $resp_arr['flag'] = TRUE;
        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}