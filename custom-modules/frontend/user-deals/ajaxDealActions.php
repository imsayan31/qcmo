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

add_action('wp_ajax_open_deal_update', 'ajaxOpenDealDetailsPopup');

if (!function_exists('ajaxOpenDealDetailsPopup')) {

    function ajaxOpenDealDetailsPopup() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => '', 'deal_id' => '', 'deal_name' => '', 'deal_desc' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $DealFinalizeObject = new classFinalize();
        $msg = NULL;
        $dealID = base64_decode($_POST['deal_id']);
        $getDealDetails = $DealFinalizeObject->getDealDetails($dealID);

        if (is_array($getDealDetails) && count($getDealDetails) == 0) {
            $msg = __('Deal not found with this id.', THEME_TEXTDOMAIN);
        } else {
            $resp_arr['deal_id'] = base64_encode($getDealDetails->data['deal_id']);
            $resp_arr['deal_name'] = $getDealDetails->data['deal_name'];
            $resp_arr['deal_desc'] = $getDealDetails->data['deal_description'];
            $resp_arr['flag'] = TRUE;
        }

        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}

/*
 * --------------------------------------------
 * AJAX:: Ajax Function
 * --------------------------------------------
 */

add_action('wp_ajax_open_deal_material_list', 'ajaxOpenDealMaterialList');

if (!function_exists('ajaxOpenDealMaterialList')) {

    function ajaxOpenDealMaterialList() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => '', 'deal_id' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $FinalizeObject = new classFinalize();
        $msg = NULL;
        $deal_id = base64_decode($_POST['deal_id']);
        $getDealDetails = $FinalizeObject->getDealDetails($deal_id);

        if (!$getDealDetails->data['deal_id']) {
            $msg = __('Deal does not exist with this ID.', THEME_TEXTDOMAIN);
        } else {
            $getUserDealProductCategories = $FinalizeObject->getUserDealProductCategories($deal_id);
            if (is_array($getUserDealProductCategories) && count($getUserDealProductCategories) > 0) {
                $msg .= '<option value="9999">Todas Categorias</option>';
                foreach ($getUserDealProductCategories as $eachDealProductCat) {
                    $getCatDet = get_term_by('slug', $eachDealProductCat, themeFramework::$theme_prefix . 'product_category');
                    $msg .= '<option value="' . $getCatDet->slug . '">' . $getCatDet->name . '</option>';
                }
            } else {
                $msg .= '<option value="">Nenhuma categoria encontrada.</option>';
            }
            $resp_arr['deal_id'] = base64_encode($deal_id);
            $resp_arr['flag'] = true;
        }

        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}

/*
 * --------------------------------------------
 * AJAX:: Ajax Deal Locking
 * --------------------------------------------
 */

add_action('wp_ajax_deal_locking', 'ajaxDealLocking');

if (!function_exists('ajaxDealLocking')) {

    function ajaxDealLocking() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $FinalizeObject = new classFinalize();
        $msg = NULL;
        $deal_id = base64_decode($_POST['deal_id']);
        $getDealData = $FinalizeObject->getDealDetails($deal_id);

        if (empty($deal_id)) {
            $msg = __('Deal ID not found.', THEME_TEXTDOMAIN);
        } else if (is_array($getDealData) && count($getDealData) == 0) {
            $msg = __('Deal not found with this ID.', THEME_TEXTDOMAIN);
        } else {

            /* updated data */
            $updatedData = [
                'deal_locking_status' => 1
            ];

            /* where data */
            $whereData = [
                'deal_id' => $deal_id
            ];

            $updateDealData = $FinalizeObject->updateDealFinalizeData($updatedData, $whereData);

            //$sharedDealLink = SHARED_DEAL_DETAILS_PAGE . '?deal=' . base64_encode($deal_id);

            if ($updateDealData) {
                $resp_arr['flag'] = TRUE;
             //   $resp_arr['url'] = $sharedDealLink;
                $msg = __('Your deal has been unlocked now.', THEME_TEXTDOMAIN);
            } else {
               // $msg = __('Your deal can not be unlocked now.', THEME_TEXTDOMAIN);
               /* updated data */
            $updatedData = [
                'deal_locking_status' => 2
            ];

            /* where data */
            $whereData = [
                'deal_id' => $deal_id
            ];

            $updateDealData = $FinalizeObject->updateDealFinalizeData($updatedData, $whereData);

            if ($updateDealData) {
                $resp_arr['flag'] = TRUE;
                $msg = __('Your deal has been locked now.', THEME_TEXTDOMAIN);
            }
            }
        }


        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}

/*
 * --------------------------------------------
 * AJAX:: Ajax Deal Unlocking
 * --------------------------------------------
 */

add_action('wp_ajax_deal_unlocking', 'ajaxDealUnlocking');

if (!function_exists('ajaxDealUnlocking')) {

    function ajaxDealUnlocking() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $FinalizeObject = new classFinalize();
        $msg = NULL;
        $deal_id = base64_decode($_POST['deal_id']);
        $getDealData = $FinalizeObject->getDealDetails($deal_id);

        if (empty($deal_id)) {
            $msg = __('Deal ID not found.', THEME_TEXTDOMAIN);
        } else if (is_array($getDealData) && count($getDealData) == 0) {
            $msg = __('Deal not found with this ID.', THEME_TEXTDOMAIN);
        } else {

            /* updated data */
            $updatedData = [
                'deal_locking_status' => 2
            ];

            /* where data */
            $whereData = [
                'deal_id' => $deal_id
            ];

            $updateDealData = $FinalizeObject->updateDealFinalizeData($updatedData, $whereData);

            if ($updateDealData) {
                $resp_arr['flag'] = TRUE;
                $msg = __('Your deal has been locked now.', THEME_TEXTDOMAIN);
            } else {
               // $msg = __('Your deal can not be locked now.', THEME_TEXTDOMAIN);
               /* updated data */
            $updatedData = [
                'deal_locking_status' => 1
            ];

            /* where data */
            $whereData = [
                'deal_id' => $deal_id
            ];

            $updateDealData = $FinalizeObject->updateDealFinalizeData($updatedData, $whereData);

            //$sharedDealLink = SHARED_DEAL_DETAILS_PAGE . '?deal=' . base64_encode($deal_id);

            if ($updateDealData) {
                $resp_arr['flag'] = TRUE;
               // $resp_arr['url'] = $sharedDealLink;
                $msg = __('Your deal has been unlocked now.', THEME_TEXTDOMAIN);
            } 
            }
        }


        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}

/*
 * --------------------------------------------
 * AJAX:: Ajax Deal Get Shareable Link
 * --------------------------------------------
 */

add_action('wp_ajax_deal_get_shareable_link', 'ajaxDealGetShareableLink');

if (!function_exists('ajaxDealGetShareableLink')) {

    function ajaxDealGetShareableLink() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $FinalizeObject = new classFinalize();
        $msg = NULL;
        $deal_id = base64_decode($_POST['deal_id']);
        $getDealData = $FinalizeObject->getDealDetails($deal_id);

        if (empty($deal_id)) {
            $msg = __('Deal ID not found.', THEME_TEXTDOMAIN);
        } else if (is_array($getDealData) && count($getDealData) == 0) {
            $msg = __('Deal not found with this ID.', THEME_TEXTDOMAIN);
        } else {

            // $sharedDealLink = 'https://quantocustaminhaobra.com.br/beta/shared-deal-detail'. '?deal=' . base64_encode($deal_id);
            $sharedDealLink = BASE_URL. '/shared-deal-detail'. '?deal=' . base64_encode($deal_id);

            if ($sharedDealLink) {
                $resp_arr['flag'] = TRUE;
                $resp_arr['url'] = $sharedDealLink;
            } else {
                $msg = __('Your deal shared link is not available right now.', THEME_TEXTDOMAIN);
            }
        }

        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}

/*
 * --------------------------------------------
 * AJAX:: Ajax Copy To Cart
 * --------------------------------------------
 */

add_action('wp_ajax_copy_to_cart', 'ajaxCopyToCart');

if (!function_exists('ajaxCopyToCart')) {

    function ajaxCopyToCart() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $FinalizedObject = new classFinalize();
        $CartObject = new classCart();
        $WishListObject = new classWishList();
        $msg = NULL;
        $productArr = [];
        $cartIDArr = [];
        $deal_id = base64_decode($_POST['deal_id']);
        $dealProductDetails = $FinalizedObject->getDealProductDetails($deal_id);
        $getDealData = $FinalizedObject->getDealDetails($deal_id);
        $userDetails = $GeneralThemeObject->user_details();

        if (empty($deal_id)) {
            $msg = __('Deal ID not found.', THEME_TEXTDOMAIN);
        } else if (is_array($getDealData) && count($getDealData) == 0) {
            $msg = __('Deal not found with this ID.', THEME_TEXTDOMAIN);
        } 
        else {

            if (is_array($dealProductDetails) && count($dealProductDetails) > 0) {
                foreach ($dealProductDetails as $eachProduct) {
                    $productArr[] = $eachProduct['product_id'];
                }
            }

            if (isset($_COOKIE['andre_anonymous_state']) && $_COOKIE['andre_anonymous_state'] != '') {
                $selectedState = $_COOKIE['andre_anonymous_state'];
            }
            if (isset($_COOKIE['andre_anonymous_city']) && $_COOKIE['andre_anonymous_city'] != '') {
                $selectedCity = $_COOKIE['andre_anonymous_city'];
            }
            
            

            if (is_array($productArr) && count($productArr) > 0) {
                foreach ($productArr as $product) {
                    $productDetails = $GeneralThemeObject->product_details($product);
                    $isItemInWishList = $WishListObject->isItemInWishList($productDetails->data['ID'], $userDetails->data['user_id'], $selectedCity);
                    $isItemAlreadyInCart = $CartObject->isItemInCart($productDetails->data['ID'], $userDetails->data['user_id'], $selectedState, $selectedCity);

                    /* Check Item in Wishlist */
                    if ($isItemInWishList == TRUE) {
                        $WishListObject->removeFromWishList($productDetails->data['ID'], $userDetails->data['user_id'], $selectedState, $selectedCity);
                    }

                    /* Check Item in Cart */
                    if ($isItemAlreadyInCart) {
                        $totalNoOfItems = $CartObject->updatedNoOfItems($productDetails->data['ID'], $userDetails->data['user_id'], $selectedState, $selectedCity);
                    } else {
                        $totalNoOfItems = 1;
                    }
                    $totalPrice = $CartObject->updatedCartPrice($productDetails->data['ID'], $userDetails->data['user_id'], $selectedState, $selectedCity, $totalNoOfItems);

                    if ($isItemAlreadyInCart == TRUE) {
                     

                        /* If Item already in cart */
                        $totalNoOfItems = $CartObject->updatedNoOfItems($productDetails->data['ID'], $userDetails->data['user_id'], $selectedState, $selectedCity, 0.01);
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
                        $cartIDArr[] = $CartObject->updateCartItems($updatedCartData, $whereData);
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
                    
                        $cartIDArr[] = $CartObject->addToCart($cartDataArr);
                    }
                }
            }
            
            if (is_array($cartIDArr) && count($cartIDArr) > 0) {
                $resp_arr['flag'] = TRUE;
                $msg = __('Item added to your cart', THEME_TEXTDOMAIN);
            } else{
                $msg = __('Item cannot be added to your cart.', THEME_TEXTDOMAIN);
            }
        }

        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}


/*
 * --------------------------------------------
 * AJAX:: Erase Deal Data
 * --------------------------------------------
 */

add_action('wp_ajax_erase_user_deals', 'ajaxEraseUserDeals');

if (!function_exists('ajaxEraseUserDeals')) {

    function ajaxEraseUserDeals() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $FinalizedObject = new classFinalize();
        $msg = NULL;
        $productArr = [];
        $cartIDArr = [];
        $deal_id = base64_decode($_POST['deal_id']);
        $dealProductDetails = $FinalizedObject->getDealProductDetails($deal_id);
        $getDealData = $FinalizedObject->getDealDetails($deal_id);
        $userDetails = $GeneralThemeObject->user_details();

        $updateData = [
            'deal_status' => 5,
        ];
        $whereData = [
            'user_id' => $userDetails->data['user_id']
        ];

        if($deal_id){
            $whereData['deal_id'] = $deal_id;
            $msg = __('This deal data has been erased.', THEME_TEXTDOMAIN);
        } else{
            $msg = __('All your deals are erased.', THEME_TEXTDOMAIN);
        }

        $updateDealData = $FinalizedObject->updateDealFinalizeData($updateData, $whereData);

        $resp_arr['flag'] = true;
        $resp_arr['msg'] = $msg;
        $resp_arr['url'] = MY_DEALS_PAGE;
        echo json_encode($resp_arr);
        exit;
    }

}