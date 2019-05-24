<?php

/*
 * --------------------------------------------
 * AJAX:: Add To Wishlist
 * --------------------------------------------
 */
add_action('wp_ajax_add_to_wishlist', 'ajaxAddToWishlist');
add_action('wp_ajax_nopriv_add_to_wishlist', 'ajaxAddToWishlist');

if (!function_exists('ajaxAddToWishlist')) {

    function ajaxAddToWishlist() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $WishlistObject = new classWishList();
        $msg = NULL;
        $product = base64_decode($_POST['product']);
        $wish_type = $_POST['wish_type'];
        $productDetails = $GeneralThemeObject->product_details($product);
        $userDetails = $GeneralThemeObject->user_details();
        $getLandingCity = $GeneralThemeObject->getLandingCity();
        $isItemInWishlist = $WishlistObject->isItemInWishList($product, $userDetails->data['user_id'], $getLandingCity);

        if (isset($_COOKIE['andre_anonymous_state']) && $_COOKIE['andre_anonymous_state'] != '') {
            $selectedState = $_COOKIE['andre_anonymous_state'];
        }
        if (isset($_COOKIE['andre_anonymous_city']) && $_COOKIE['andre_anonymous_city'] != '') {
            $selectedCity = $_COOKIE['andre_anonymous_city'];
        }

        if (!is_user_logged_in()) {
            $msg = __('Faça login para continuar.', THEME_TEXTDOMAIN);
        } else if (empty($product)) {
            $msg = __('Produto não encontrado.', THEME_TEXTDOMAIN);
        } 
        /*elseif (empty($productDetails->data['title'])) {
            $msg = __('Produto em falta.', THEME_TEXTDOMAIN);
        }*/
         elseif (empty($wish_type)) {
            $msg = __('Item type not found.', THEME_TEXTDOMAIN);
        } else if ($isItemInWishlist == TRUE) {
            $msg = __('Produto já adicionado na sua lista de favoritos.', THEME_TEXTDOMAIN);
        } else {
            /* Generate wishlist data */
            $wishlistDataArr = [
                'user_id' => $userDetails->data['user_id'],
                'product_id' => $product,
                'wishlist_type' => $wish_type,
                'state' => $selectedState,
                'city' => $selectedCity,
                'date' => strtotime(date('Y-m-d'))
            ];
            $wishlistID = $WishlistObject->addToWishList($wishlistDataArr);
            if ($wishlistID) {
                $resp_arr['flag'] = TRUE;
                $msg = __('Produto adicionado à sua lista de favorito.', THEME_TEXTDOMAIN);
            } else {
                $msg = __('Erro no banco de dados. Verifique com o administrador.', THEME_TEXTDOMAIN);
            }
        }

        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}

add_action('wp_ajax_remove_from_wishlist', 'ajaxRemoveFromWishlist');

/*
 * --------------------------------------------
 * AJAX:: Remove From Wishlist
 * --------------------------------------------
 */
if (!function_exists('ajaxRemoveFromWishlist')) {

    function ajaxRemoveFromWishlist() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $WishlistObject = new classWishList();
        $msg = NULL;
        $product = base64_decode($_POST['product']);
        $state = base64_decode($_POST['state']);
        $city = base64_decode($_POST['city']);
        $productDetails = $GeneralThemeObject->product_details($product);
        $userDetails = $GeneralThemeObject->user_details();
        $getLandingCity = $GeneralThemeObject->getLandingCity();
        $isItemInWishlist = $WishlistObject->isItemInWishList($product, $userDetails->data['user_id'], $getLandingCity);

        if (empty($product)) {
            $msg = __('Produto não encontrado.', THEME_TEXTDOMAIN);
        } elseif (empty($productDetails->data['title'])) {
            $msg = __('Produto em falta.', THEME_TEXTDOMAIN);
        } else if ($isItemInWishlist == FALSE) {
            $msg = __('Este produto não existe na sua lista de favoritos.', THEME_TEXTDOMAIN);
        } else {

            $wishlistID = $WishlistObject->removeFromWishList($productDetails->data['ID'], $userDetails->data['user_id'], $state, $city);
            if ($wishlistID) {
                $resp_arr['flag'] = TRUE;
                $msg = __('Produto removido da sua lista de favoritos.', THEME_TEXTDOMAIN);
            } else {
                $msg = __('Erro no banco de dados. Verifique com o administrador.', THEME_TEXTDOMAIN);
            }
        }

        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}