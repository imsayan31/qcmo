<?php

/*
 * --------------------------------------------
 * AJAX:: Membership Coupon Apply
 * --------------------------------------------
 */

add_action('wp_ajax_apply_coupon_code_membership', 'ajaxApplyMembershipCoupon');

if (!function_exists('ajaxApplyMembershipCoupon')) {

    function ajaxApplyMembershipCoupon() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => '', 'total_payable_price_html' => '', 'calculatePrice' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $CouponObject = new classCoupnManagement();
        $MembershipObject = new classMemberShip();
        $msg = NULL;
        $coupon_value = strip_tags(trim($_POST['coupon_value']));
        $period_val = base64_decode($_POST['period_val']);
        $plan_val = base64_decode($_POST['plan_val']);
        $planDetails = $GeneralThemeObject->getMembershipPlanDetails($plan_val);
        $userDetails = $GeneralThemeObject->user_details();

        $getMembershipDetails = $MembershipObject->getUserMembershipDetails($userDetails->data['user_id']);
        $currDate = strtotime(date('Y-m-d'));
        $userPreviousPlan = $userDetails->data['selected_plan'];
        $previousPlanDetails = $GeneralThemeObject->getMembershipPlanDetails($userPreviousPlan);

        $isCouponExists = $CouponObject->isCouponExists($coupon_value);
        $isValidCoupon = $CouponObject->isValidCoupon($coupon_value);
        $isUsableCoupon = $CouponObject->isUsableCoupon($coupon_value, $userDetails->data['user_id']);
        $checkCouponType = $CouponObject->checkCouponType($coupon_value, 'membership');

        if(empty($coupon_value)){
            $msg = __('Enter coupon code.', THEME_TEXTDOMAIN);
        } elseif ($isCouponExists == FALSE) {
            $msg = __('This coupon does not exist.', THEME_TEXTDOMAIN);
        } elseif ($isValidCoupon == FALSE) {
            $msg = __('This coupon is not valid now.', THEME_TEXTDOMAIN);
        } elseif ($isUsableCoupon == FALSE) {
            $msg = __('You have exceeded the limit to use this coupon.', THEME_TEXTDOMAIN);
        } elseif ($checkCouponType == FALSE) {
            $msg = __('This coupon is not valid for membership.', THEME_TEXTDOMAIN);
        } else if (empty($period_val)) {
            $msg = __('Please select your plan period.', THEME_TEXTDOMAIN);
        } else {

            /* Checking for condition whether user is buying the upgraded plan than it's current plan within current expiry plan date */
            if(is_array($getMembershipDetails) && count($getMembershipDetails) > 0 && ($plan_val != $userDetails->data['selected_plan']) && ($currDate <= $userDetails->data['selected_end_date'])){

                if($period_val == 3){
                    if ($planDetails->data['quarterly_price'] > $getMembershipDetails[0]->total_price) {
                        $estimatedPrice = ($planDetails->data['quarterly_price'] - $getMembershipDetails[0]->total_price);
                    } else {
                        $estimatedPrice = $planDetails->data['quarterly_price'];
                    }
                } else if($period_val == 6){
                    if ($planDetails->data['half_yearly_price'] > $getMembershipDetails[0]->total_price) {
                        $estimatedPrice = ($planDetails->data['half_yearly_price'] - $getMembershipDetails[0]->total_price);
                    } else{
                        $estimatedPrice = $planDetails->data['half_yearly_price'];
                    }
                } else if($period_val == 12){
                    if ($planDetails->data['yearly_price'] > $getMembershipDetails[0]->total_price) {
                        $estimatedPrice = ($planDetails->data['yearly_price'] - $getMembershipDetails[0]->total_price);
                    } else{
                        $estimatedPrice = $planDetails->data['yearly_price'];
                    }
                }
            } else{
                if ($period_val == 3) {
                    $estimatedPrice = $planDetails->data['quarterly_price'];
                } else if ($period_val == 6) {
                    $estimatedPrice = $planDetails->data['half_yearly_price'];
                } else if ($period_val == 12) {
                    $estimatedPrice = $planDetails->data['yearly_price'];
                }
            }

            /* Calculate Discount Price */
            $calculatePrice = $CouponObject->calculateDiscountPrice($coupon_value, $estimatedPrice);
            $CouponObject->updateUserCouponUsage($coupon_value, $userDetails->data['user_id']);

            /*echo "<pre>";
            print_r($estimatedPrice);
            echo "</pre>";
            echo "<pre>";
            print_r($calculatePrice);
            echo "</pre>";
            exit;*/

            $payablePriceHTML = '<div class="table-responsive">';
            $payablePriceHTML .= '<table class="coupon-calc-tbl">';
            $payablePriceHTML .= '<tbody>';
            /*$payablePriceHTML .= '<tr>';
            $payablePriceHTML .= '<td>Actual Price:</td>';
            $payablePriceHTML .= '<td>R$ '. number_format($announcementDetails->data['estimated_price'], 2) .'</td>';
            $payablePriceHTML .= '</tr>';*/
            $payablePriceHTML .= '<tr>';
            $payablePriceHTML .= '<td>Payable Price:</td>';
            $payablePriceHTML .= '<td>R$ '. number_format($calculatePrice, 2) .'</td>';
            $payablePriceHTML .= '</tr>';
            $payablePriceHTML .= '</tbody>';
            $payablePriceHTML .= '</table>';
            $payablePriceHTML .= '</div>';

            $resp_arr['flag'] = true;
            //$resp_arr['calculatePrice'] = number_format($estimatedPrice, 2);
            $resp_arr['total_payable_price_html'] = $payablePriceHTML;
            $msg = __('Your coupon applied successfully.', THEME_TEXTDOMAIN);
        }
        $resp_arr['msg'] = $msg;
        $resp_arr['url'] = BASE_URL;
        echo json_encode($resp_arr);
        exit;
    }

}


/*
 * --------------------------------------------
 * AJAX:: Announcement Coupon Apply
 * --------------------------------------------
 */

add_action('wp_ajax_apply_coupon_code', 'ajaxApplyAnnouncementCoupon');

if (!function_exists('ajaxApplyAnnouncementCoupon')) {

    function ajaxApplyAnnouncementCoupon() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => '', 'total_payable_price_html' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $CouponObject = new classCoupnManagement();
        $AnnouncementObject = new classAnnouncement();
        $msg = NULL;
        $coupon_value = strip_tags(trim($_POST['coupon_value']));
        $announcement_plan = strip_tags(trim(base64_decode($_POST['announcement_plan'])));
        $userDetails = $GeneralThemeObject->user_details();
        $announcementDetails = $AnnouncementObject->announcement_details($announcement_plan);

        $isCouponExists = $CouponObject->isCouponExists($coupon_value);
        $isValidCoupon = $CouponObject->isValidCoupon($coupon_value);
        $isUsableCoupon = $CouponObject->isUsableCoupon($coupon_value, $userDetails->data['user_id']);
        $checkCouponType = $CouponObject->checkCouponType($coupon_value, 'announcement');

        /*echo "<pre>";
        print_r($announcementDetails);
        echo "</pre>";
        exit;*/

        if(empty($coupon_value)){
            $msg = __('Enter coupon code.', THEME_TEXTDOMAIN);
        } elseif ($isCouponExists == FALSE) {
            $msg = __('This coupon does not exist.', THEME_TEXTDOMAIN);
        } elseif ($isValidCoupon == FALSE) {
            $msg = __('This coupon is not valid now.', THEME_TEXTDOMAIN);
        } elseif ($isUsableCoupon == FALSE) {
            $msg = __('You have exceeded the limit to use this coupon.', THEME_TEXTDOMAIN);
        } elseif ($checkCouponType == FALSE) {
            $msg = __('This coupon is not valid for announcement.', THEME_TEXTDOMAIN);
        } else {

            /* Calculate Discount Price */
            $calculatePrice = $CouponObject->calculateDiscountPrice($coupon_value, $announcementDetails->data['estimated_price']);
            $CouponObject->updateUserCouponUsage($coupon_value, $userDetails->data['user_id']);

            /*echo "<pre>";
            print_r($calculatePrice);
            echo "</pre>";
            exit;*/

            $payablePriceHTML = '<div class="table-responsive">';
            $payablePriceHTML .= '<table class="coupon-calc-tbl">';
            $payablePriceHTML .= '<tbody>';
            $payablePriceHTML .= '<tr>';
            $payablePriceHTML .= '<td>Actual Price:</td>';
            $payablePriceHTML .= '<td>R$ '. number_format($announcementDetails->data['estimated_price'], 2) .'</td>';
            $payablePriceHTML .= '</tr>';
            $payablePriceHTML .= '<tr>';
            $payablePriceHTML .= '<td>Payable Price:</td>';
            $payablePriceHTML .= '<td>R$ '. number_format($calculatePrice, 2) .'</td>';
            $payablePriceHTML .= '</tr>';
            $payablePriceHTML .= '</tbody>';
            $payablePriceHTML .= '</table>';
            $payablePriceHTML .= '</div>';

            $resp_arr['flag'] = true;
            $resp_arr['total_payable_price_html'] = $payablePriceHTML;
            $msg = __('Your coupon applied successfully.', THEME_TEXTDOMAIN);
        }
        $resp_arr['msg'] = $msg;
        $resp_arr['url'] = BASE_URL;
        echo json_encode($resp_arr);
        exit;
    }

}

/*
 * --------------------------------------------
 * AJAX:: Renew Announcement Coupon Apply
 * --------------------------------------------
 */

add_action('wp_ajax_apply_coupon_code_renew', 'ajaxApplyAnnouncementCouponRenew');

if (!function_exists('ajaxApplyAnnouncementCouponRenew')) {

    function ajaxApplyAnnouncementCouponRenew() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => '', 'total_payable_price_html' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $CouponObject = new classCoupnManagement();
        $AnnouncementObject = new classAnnouncement();
        $msg = NULL;
        $coupon_value = strip_tags(trim($_POST['coupon_value']));
        $announcement_plan = strip_tags(trim(base64_decode($_POST['announcement_plan'])));
        $announement_plan_selection = $_POST['announement_plan_selection'];
        $announcement_renewal_period = $_POST['announcement_renewal_period'];

        $userDetails = $GeneralThemeObject->user_details();
        $announcementDetails = $AnnouncementObject->announcement_details($announcement_plan);

        $getAnnouncementPrice = $AnnouncementObject->getAnnouncementPrice($announement_plan_selection, $announcement_renewal_period);

        $isCouponExists = $CouponObject->isCouponExists($coupon_value);
        $isValidCoupon = $CouponObject->isValidCoupon($coupon_value);
        $isUsableCoupon = $CouponObject->isUsableCoupon($coupon_value, $userDetails->data['user_id']);
        $checkCouponType = $CouponObject->checkCouponType($coupon_value, 'announcement');

        if(empty($coupon_value)){
            $msg = __('Enter coupon code.', THEME_TEXTDOMAIN);
        } elseif ($isCouponExists == FALSE) {
            $msg = __('This coupon does not exist.', THEME_TEXTDOMAIN);
        } elseif ($isValidCoupon == FALSE) {
            $msg = __('This coupon is not valid now.', THEME_TEXTDOMAIN);
        } elseif ($isUsableCoupon == FALSE) {
            $msg = __('You have exceeded the limit to use this coupon.', THEME_TEXTDOMAIN);
        } elseif ($checkCouponType == FALSE) {
            $msg = __('This coupon is not valid for announcement.', THEME_TEXTDOMAIN);
        } else if(empty($announement_plan_selection)){
            $msg = __('Select announcement plan.', THEME_TEXTDOMAIN);
        } else if(empty($announcement_renewal_period)){
            $msg = __('Select announcement period.', THEME_TEXTDOMAIN);
        } else {

            /* Calculate Discount Price */
            $calculatePrice = $CouponObject->calculateDiscountPrice($coupon_value, $getAnnouncementPrice);
            $CouponObject->updateUserCouponUsage($coupon_value, $userDetails->data['user_id']);

            $payablePriceHTML = '<div class="table-responsive">';
            $payablePriceHTML .= '<table class="coupon-calc-tbl">';
            $payablePriceHTML .= '<tbody>';
            $payablePriceHTML .= '<tr>';
            $payablePriceHTML .= '<td>Actual Price:</td>';
            $payablePriceHTML .= '<td>R$ '. number_format($getAnnouncementPrice, 2) .'</td>';
            $payablePriceHTML .= '</tr>';
            $payablePriceHTML .= '<tr>';
            $payablePriceHTML .= '<td>Payable Price:</td>';
            $payablePriceHTML .= '<td>R$ '. number_format($calculatePrice, 2) .'</td>';
            $payablePriceHTML .= '</tr>';
            $payablePriceHTML .= '</tbody>';
            $payablePriceHTML .= '</table>';
            $payablePriceHTML .= '</div>';

            $resp_arr['flag'] = true;
            $resp_arr['total_payable_price_html'] = $payablePriceHTML;
            $msg = __('Your coupon applied successfully.', THEME_TEXTDOMAIN);
        }
        $resp_arr['msg'] = $msg;
        $resp_arr['url'] = BASE_URL;
        echo json_encode($resp_arr);
        exit;
    }

}