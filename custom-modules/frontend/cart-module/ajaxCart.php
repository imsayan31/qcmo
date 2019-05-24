<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * --------------------------------------------
 * AJAX:: Add To Cart
 * --------------------------------------------
 */

add_action('wp_ajax_add_to_cart', 'ajaxAddToCart');
add_action('wp_ajax_nopriv_add_to_cart', 'ajaxAddToCart');

if (!function_exists('ajaxAddToCart')) {

    function ajaxAddToCart() {
     
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $CartObject = new classCart();
        $WishListObject = new classWishList();
        $msg = NULL;
        $product = base64_decode($_POST['product']);
        $state = base64_decode($_POST['state']);
        $city = base64_decode($_POST['city']);
        $single_no_of_items = strip_tags(trim($_POST['single_no_of_items']));
        $productDetails = $GeneralThemeObject->product_details($product);
        $userDetails = $GeneralThemeObject->user_details();
        $getLandingCity = $GeneralThemeObject->getLandingCity();
     
        if (isset($_COOKIE['andre_anonymous_state']) && $_COOKIE['andre_anonymous_state'] != '') {
            $selectedState = $_COOKIE['andre_anonymous_state'];
        }
        if (isset($_COOKIE['andre_anonymous_city']) && $_COOKIE['andre_anonymous_city'] != '') {
            $selectedCity = $_COOKIE['andre_anonymous_city'];
        }

        if ($state && $city) {
            $selectedState = $state;
            $selectedCity = $city;
        } else {
            $selectedState = $selectedState;
            $selectedCity = $selectedCity;
        }

        $isItemInWishList = $WishListObject->isItemInWishList($productDetails->data['ID'], $userDetails->data['user_id'], $selectedCity);
        $isItemAlreadyInCart = $CartObject->isItemInCart($productDetails->data['ID'], $userDetails->data['user_id'], $selectedState, $selectedCity);

        if (!is_user_logged_in()) {
            $msg = __('Please login to continue.', THEME_TEXTDOMAIN);
        } else if (empty($product)) {
            $msg = __('Item not found.', THEME_TEXTDOMAIN);
        } elseif (empty($productDetails->data['title'])) {
            $msg = __('Item is missing.', THEME_TEXTDOMAIN);
        } else {
            if ($single_no_of_items) {
                $totalNoOfItems = $single_no_of_items;
            } else {
                $totalNoOfItems = $CartObject->updatedNoOfItems($productDetails->data['ID'], $userDetails->data['user_id'], $selectedState, $selectedCity);
            }

            $totalPrice = $CartObject->updatedCartPrice($productDetails->data['ID'], $userDetails->data['user_id'], $selectedState, $selectedCity, $totalNoOfItems);

            if ($isItemInWishList == TRUE) {
                $WishListObject->removeFromWishList($productDetails->data['ID'], $userDetails->data['user_id'], $selectedState, $selectedCity);
            }
            if ($isItemAlreadyInCart == TRUE) {

                /* If Item already in cart */
                //$totalNoOfItems = $CartObject->updatedNoOfItems($productDetails->data['ID'], $userDetails->data['user_id'], $selectedState, $selectedCity, 0.01);
                $updatedCartData = [
                    'no_of_items' => $totalNoOfItems,
                    'total_price' => $totalPrice,
                ];
                $whereData = [
                    'user_id' => $userDetails->data['user_id'],
                    'product_id' => $product,
                    'state' => $selectedState,
                    'city' => $selectedCity
                ];
                $cartID = $CartObject->updateCartItems($updatedCartData, $whereData);
            } else {
                /* If Item not exists in cart */
                /* Generate cart data */
                $cartDataArr = [
                    'user_id' => $userDetails->data['user_id'],
                    'product_id' => $product,
                    'product_material_category' => $productDetails->data['material_category'],
                    'state' => $selectedState,
                    'city' => $selectedCity,
                    'no_of_items' => $totalNoOfItems,
                    'product_price' => $productDetails->data['main_price'],
                    'total_price' => $totalPrice,
                    'date' => strtotime(date('Y-m-d'))
                ];
              
                $cartID = $CartObject->addToCart($cartDataArr);
            }
            if ($cartID) {
                $resp_arr['flag'] = TRUE;
                $msg = __('Item added to your cart.', THEME_TEXTDOMAIN);
            } else {
                $msg = __('Product already in your cart.', THEME_TEXTDOMAIN);
            }
        }

        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}

/*
 * --------------------------------------------
 * AJAX:: Remove From Cart
 * --------------------------------------------
 */

add_action('wp_ajax_remove_from_cart', 'ajaxRemoveFromCart');

if (!function_exists('ajaxRemoveFromCart')) {

    function ajaxRemoveFromCart() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $CartObject = new classCart();
        $msg = NULL;
        $product = base64_decode($_POST['product']);
        $state = base64_decode($_POST['state']);
        $city = base64_decode($_POST['city']);
        $productDetails = $GeneralThemeObject->product_details($product);
        $userDetails = $GeneralThemeObject->user_details();

        if (empty($product)) {
            $msg = __('Item not found.', THEME_TEXTDOMAIN);
        } elseif (empty($productDetails->data['title'])) {
            $msg = __('Item is missing.', THEME_TEXTDOMAIN);
        } else {
            $cartID = $CartObject->removeFromCart($productDetails->data['ID'], $userDetails->data['user_id'], $state, $city);
            if ($cartID) {
                $resp_arr['flag'] = TRUE;
                $msg = __('Item removed from your cart.', THEME_TEXTDOMAIN);
            } else {
                $msg = __('Database error. Check your code.', THEME_TEXTDOMAIN);
            }
        }
        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}

/*
 * --------------------------------------------
 * AJAX:: Clear Cart Items
 * --------------------------------------------
 */

add_action('wp_ajax_clear_cart_items', 'ajaxClearCartItems');

if (!function_exists('ajaxClearCartItems')) {

    function ajaxClearCartItems() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $CartObject = new classCart();
        $msg = NULL;
        $userDetails = $GeneralThemeObject->user_details();

        $removeFromCart = $CartObject->emptyCart($userDetails->data['user_id']);

        if ($removeFromCart) {
            $resp_arr['flag'] = TRUE;
            $msg = __('Your cart items has been cleared.', THEME_TEXTDOMAIN);
        } else {
            $msg = __('Database error.', THEME_TEXTDOMAIN);
        }

        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}

/*
 * --------------------------------------------
 * AJAX:: Update Cart Items
 * --------------------------------------------
 */

add_action('wp_ajax_cart_update', 'ajaxUpdateCartItems');

if (!function_exists('ajaxUpdateCartItems')) {

    function ajaxUpdateCartItems() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $CartObject = new classCart();
        $msg = NULL;
        $userDetails = $GeneralThemeObject->user_details();
        $updated_items = $_POST['no_of_items'];
        $checkCartQuantity = $CartObject->checkCartQuantity($updated_items);

        if ($checkCartQuantity == FALSE) {
            $msg = __('Invalid item quantity.', THEME_TEXTDOMAIN);
        } else {
            if (is_array($updated_items) && count($updated_items) > 0) {
                foreach ($updated_items as $each_item_key => $each_item_val) {
                    if ($each_item_val[0] > 0) {
                        $explodedDetails = explode('%', $each_item_key);
//                        $totalPrice = $CartObject->getProductPrice($each_item_key);

                        $totalPrice = base64_decode($explodedDetails[1]);
                        $newPrice = ($each_item_val[0] * $totalPrice);
                        $updatedCartData = [
                            'no_of_items' => $each_item_val[0],
                            'total_price' => $newPrice,
                        ];
                        $whereData = [
                            'user_id' => $userDetails->data['user_id'],
                            'product_id' => base64_decode($explodedDetails[0]),
                            'state' => base64_decode($explodedDetails[2]),
                            'city' => base64_decode($explodedDetails[3])
                        ];
                        $CartObject->updateCartItems($updatedCartData, $whereData);
                    }
                }
            }

            $resp_arr['flag'] = TRUE;
            $msg = __('Your cart items has been updated.', THEME_TEXTDOMAIN);
        }


        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}

/*
 * --------------------------------------------
 * AJAX:: Get Cart Materials By Category
 * --------------------------------------------
 */

add_action('wp_ajax_cart_category_data', 'ajaxGetCartCategoryMaterials');

if (!function_exists('ajaxGetCartCategoryMaterials')) {

    function ajaxGetCartCategoryMaterials() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $msg = NULL;
        $user_deal_id = $_POST['user_deal_id'];
        $select_cart_category = $_POST['select_cart_category'];

        /* if ($select_cart_category == 9999) {
          $getProducts = $CartObject->getUserCartCategoryProducts($userDetails->data['user_id']);
          } else {
          $getProducts = $CartObject->getUserCartCategoryProducts($userDetails->data['user_id'], $select_cart_category);
          } */
        $resp_arr['flag'] = TRUE;
        if ($user_deal_id) {
            $resp_arr['url'] = MATERIAL_LIST_PAGE . '?selected_cat=' . $select_cart_category . '&deal_id=' . $user_deal_id;
        } else {
            $resp_arr['url'] = MATERIAL_LIST_PAGE . '?selected_cat=' . $select_cart_category;
        }
        $msg = __('Get the list of materials here.', THEME_TEXTDOMAIN);
        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}
