<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of classPayPalDonation
 *
 * @author Sayanta Dey
 */
class classCoupnManagement {

    public function __construct() {
        global $wpdb;
        $this->couponDB = &$wpdb;
    }

    /*
     * Get coupon details
     *
     */
    public function coupon_details($coupon_id) {
        $couponObject = new stdClass();
        $getCouponDetails = get_post($coupon_id);

        $discount = get_post_meta($coupon_id, 'discount', TRUE);
        $valid_up_to = get_post_meta($coupon_id, 'valid_up_to', TRUE);
        $applied_for = get_post_meta($coupon_id, 'applied_for', TRUE);
        $number_of_usage = get_post_meta($coupon_id, 'number_of_usage', TRUE);
        $user_type = get_post_meta($coupon_id, 'select_coupon_user_type', TRUE);
        $coupon_selected_users = get_post_meta($coupon_id, 'coupon_selected_users', TRUE);

        $couponObject->data = [
            'ID' => $coupon_id,
            'title' => $getCouponDetails->post_title,
            'content' => $getCouponDetails->post_content,
            'author' => $getCouponDetails->post_author,
            'name' => $getCouponDetails->post_name,
            'discount' => $discount,
            'expiry' => strtotime($valid_up_to),
            'coupon_type' => $applied_for,
            'limit' => $number_of_usage,
            'user_type' => $user_type,
            'users' => $coupon_selected_users,
        ];

        return (object) $couponObject;
    }

    /*
     * Check coupon exists or not
     *
     */
    public function isCouponExists($couponCode){
        $getCouponData = get_posts(['post_type' => themeFramework::$theme_prefix . 'coupon', 'posts_per_page' => -1, 'name' => sanitize_title($couponCode)]);
        if(is_array($getCouponData) && count($getCouponData) > 0){
            return true;
        } else {
            return false;
        }
    }

    /*
     * Check coupon expired or not
     *
     */
    public function isValidCoupon($couponCode) {
        $currDate = strtotime(date('Y-m-d'));
        $returnVal = false;
        $getCouponData = get_posts(['post_type' => themeFramework::$theme_prefix . 'coupon', 'posts_per_page' => -1, 'name' => sanitize_title($couponCode)]);
        if(is_array($getCouponData) && count($getCouponData) > 0){
            foreach ($getCouponData as $eachCoupon) {
                $couponDetails = $this->coupon_details($eachCoupon->ID);
                /*echo "<pre>";
                print_r($couponDetails->data['expiry']);
                echo "</pre>";
                exit;*/
                if($currDate > $couponDetails->data['expiry']){
                    $returnVal = false;
                } else{
                    $returnVal = true;
                }
            }
        }
        return $returnVal;
    }

    /*
     * Check coupon can be used
     *
     */
    public function isUsableCoupon($couponCode, $userID) {
        $returnVal = true;
        $GeneralThemeObject = new GeneralTheme();
        $userDetails = $GeneralThemeObject->user_details($userID);
        $getCouponData = get_posts(['post_type' => themeFramework::$theme_prefix . 'coupon', 'posts_per_page' => -1, 'name' => sanitize_title($couponCode)]);
        if(is_array($getCouponData) && count($getCouponData) > 0){
            foreach ($getCouponData as $eachCoupon) {
                $couponDetails = $this->coupon_details($eachCoupon->ID);
                $userApplied = get_user_meta($userID, '_applied_coupon_'. $couponCode, true);
                if($userApplied < $couponDetails->data['limit']){
                    $returnVal = true;
                } else{
                    $returnVal = n2cf;
                }
            }
        }
        return $returnVal;
    }

    /*
     * Check coupon type
     *
     */
    public function checkCouponType($couponCode, $type) {
        $returnVal = true;
        $GeneralThemeObject = new GeneralTheme();
        $userDetails = $GeneralThemeObject->user_details($userID);
        $getCouponData = get_posts(['post_type' => themeFramework::$theme_prefix . 'coupon', 'posts_per_page' => -1, 'name' => sanitize_title($couponCode)]);
        if(is_array($getCouponData) && count($getCouponData) > 0){
            foreach ($getCouponData as $eachCoupon) {
                $couponDetails = $this->coupon_details($eachCoupon->ID);
                if($type == $couponDetails->data['coupon_type']){
                    $returnVal = true;
                } else{
                    $returnVal = false;
                }
            }
        }
        return $returnVal;
    }



    /*
     * Updte User's Coupon Usage
     *
     */
    public function updateUserCouponUsage($couponCode, $userID){
        $userApplied = get_user_meta($userID, '_applied_coupon_' . $couponCode, true);
        if($userApplied){
            $userApplied = $userApplied + 1;
            update_user_meta($userID, '_applied_coupon_' . $couponCode, $userApplied);
        } else{
            update_user_meta($userID, '_applied_coupon_' . $couponCode, 1);
        }
    }

    /*
     * Calculate discount price
     *
    */
    public function calculateDiscountPrice($couponCode, $mainPrice){
        $getCouponData = get_posts(['post_type' => themeFramework::$theme_prefix . 'coupon', 'posts_per_page' => -1, 'name' => sanitize_title($couponCode)]);
        $couponDetails = $this->coupon_details($getCouponData[0]->ID);
        $calculateDiscount = (($mainPrice * $couponDetails->data['discount'])/100);
        $totalPayableAmount = ($mainPrice - $calculateDiscount);
        return $totalPayableAmount;
    }

}
