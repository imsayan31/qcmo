<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of classWishList
 *
 * @author Sayanta Dey
 */
if (!class_exists('classWishList')) {

    class classWishList extends GeneralTheme {

        //put your code here

        public function __construct() {
            global $wpdb;
            $this->db = &$wpdb;
        }

        public function addToWishList(array $data) {
            $wishlist_insert_id = $this->db->insert(TBL_WISHLIST, $data);
            return $wishlist_insert_id;
        }

        public function removeFromWishList($product_id, $user_id, $state, $city) {
            $wishlist_deleted_row = $this->db->delete(TBL_WISHLIST, ['user_id' => $user_id, 'product_id' => $product_id, 'state' => $state, 'city' => $city]);
            return $wishlist_deleted_row;
        }

        public function isItemInWishList($product_id, $user_id, $city) {
            $isItemInWishlistQuery = "SELECT * FROM " . TBL_WISHLIST . " WHERE `user_id`=" . $user_id . " AND `product_id`=" . $product_id . "";
            if ($city) {
                $isItemInWishlistQuery .= " AND `city`=" . $city . "";
            }
            $isItemInWishlistQueryRes = $this->db->get_results($isItemInWishlistQuery);
            if (is_array($isItemInWishlistQueryRes) && count($isItemInWishlistQueryRes) > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }

        public function getWishListItems($user_id = NULL, $type = NULL) {
            $getWishlistItemsQuery = "SELECT * FROM " . TBL_WISHLIST . " WHERE `ID` !=''";
            if ($user_id) {
                $getWishlistItemsQuery .= " AND `user_id`=" . $user_id . "";
            }
            if($type){
                $getWishlistItemsQuery .= " AND `wishlist_type`='" . $type . "'";
            }
            $getWishlistItemsQueryRes = $this->db->get_results($getWishlistItemsQuery);
            return $getWishlistItemsQueryRes;
        }

        public function countWishListItems($user_id = NULL) {
            $getWishlist = $this->getWishListItems($user_id);
            $totalNumber = count($getWishlist);
            return $totalNumber;
        }

    }

}
