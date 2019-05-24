<?php

/*
 * --------------------------------------------
 * AJAX:: User Plan Purchase
 * --------------------------------------------
 */

add_action('wp_ajax_plan_purchase', 'ajaxUserPlanPurchase');

if (!function_exists('ajaxUserPlanPurchase')) {

    function ajaxUserPlanPurchase() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $MembershipObject = new classMemberShip();
        $CouponObject = new classCoupnManagement();
        $payPalObj = new WC_PP_PRO_Gateway();
        $msg = NULL;
        $selected_plan = base64_decode($_POST['selected_plan']);
        $plan_val = base64_decode($_POST['plan_val']);
        $membership_coupon = strip_tags(trim($_POST['membership_coupon']));
        $userDetails = $GeneralThemeObject->user_details();
        $getPlanDetails = $GeneralThemeObject->getMembershipPlanDetails($selected_plan);
        $subscription_card_name = strip_tags(trim($_POST['subscription_card_name']));
        $subscription_card_type = $_POST['subscription_card_type'];
        $subscription_card_number = strip_tags(trim($_POST['subscription_card_number']));
        $subscription_card_cvv = strip_tags(trim($_POST['subscription_card_cvv']));
        $subscription_card_exp_month = $_POST['subscription_card_exp_month'];
        $subscription_card_exp_year = $_POST['subscription_card_exp_year'];

        $usernameValidation = $GeneralThemeObject->userNameValidation($subscription_card_name);
        $cardNumberValidation = $GeneralThemeObject->is_valid_card_number($subscription_card_number);
        $cvvValidation = $GeneralThemeObject->is_valid_cvv_number($subscription_card_cvv);
        $cardExpiryValidation = $GeneralThemeObject->is_valid_expiry($subscription_card_exp_month, $subscription_card_exp_year);

        $getMembershipDetails = $MembershipObject->getUserMembershipDetails($userDetails->data['user_id']);
        $currDate = strtotime(date('Y-m-d'));
        $userPreviousPlan = $userDetails->data['selected_plan'];
        $previousPlanDetails = $GeneralThemeObject->getMembershipPlanDetails($userPreviousPlan);

        $getCityDetails = get_term_by('id', $userDetails->data['city'], themeFramework::$theme_prefix . 'product_city');

        if (empty($selected_plan)) {
            $msg = __('Please select your membership plan.', THEME_TEXTDOMAIN);
        } else if (empty($plan_val)) {
            $msg = __('Please select your plan period.', THEME_TEXTDOMAIN);
        } else if (empty($subscription_card_name)) {
            $msg = 'Enter your card holder name.';
        } else if ($usernameValidation == FALSE) {
            $msg = 'Card holder name should contain only characters.';
        } else if (empty($subscription_card_type)) {
            $msg = 'Select your card type.';
        } else if (empty($subscription_card_number)) {
            $msg = 'Enter your card number.';
        } else if ($cardNumberValidation == FALSE) {
            $msg = 'Your card number is not valid.';
        } else if (empty($subscription_card_cvv)) {
            $msg = 'Enter your CVV.';
        } else if ($cvvValidation == FALSE) {
            $msg = 'Your CVV is not valid.';
        } elseif (empty($subscription_card_exp_month)) {
            $msg = 'Select your card expiry month.';
        } elseif (empty($subscription_card_exp_year)) {
            $msg = 'Select your card expiry year.';
        } else if ($cardExpiryValidation == FALSE) {
            $msg = 'Your card expiry month and year are not valid.';
        } else {

            $orderVal = $GeneralThemeObject->generateRandomString(6);

            /* Checking for condition whether user is buying the upgraded plan than it's current plan within current expiry plan date */
            if(is_array($getMembershipDetails) && count($getMembershipDetails) > 0 && $plan_val != $userDetails->data['selected_plan'] && $currDate <= $userDetails->data['selected_end_date']){

                if($plan_val == 3){
                    if ($getPlanDetails->data['quarterly_price'] > $getMembershipDetails[0]->total_price) {
                        $payableAmount = ($getPlanDetails->data['quarterly_price'] - $getMembershipDetails[0]->total_price);
                        $payableService = $getPlanDetails->data['title'] . ' - Quarterly';
                    } else {
                        $payableAmount = $getPlanDetails->data['quarterly_price'];
                        $payableService = $getPlanDetails->data['title'] . ' - Quarterly';
                    }
                } else if($plan_val == 6){
                    if ($getPlanDetails->data['half_yearly_price'] > $getMembershipDetails[0]->total_price) {
                        $payableAmount = ($getPlanDetails->data['half_yearly_price'] - $getMembershipDetails[0]->total_price);
                        $payableService = $getPlanDetails->data['title'] . ' - Half Yearly';
                    } else{
                        $payableAmount = $getPlanDetails->data['half_yearly_price'];
                        $payableService = $getPlanDetails->data['title'] . ' - Half Yearly';
                    }
                } else if($plan_val == 12){
                    
                    if ($getPlanDetails->data['yearly_price'] > $getMembershipDetails[0]->total_price) {
                        $payableAmount = ($getPlanDetails->data['yearly_price'] - $getMembershipDetails[0]->total_price);
                        $payableService = $getPlanDetails->data['title'] . ' - Annualy';
                    } else{
                        $payableAmount = $getPlanDetails->data['yearly_price'];
                        $payableService = $getPlanDetails->data['title'] . ' - Annualy';
                    }
                }
                
            } else{
                if ($plan_val == 3) {
                    $payableAmount = $getPlanDetails->data['quarterly_price'];
                    $payableService = $getPlanDetails->data['title'] . ' - Quarterly';
                } else if ($plan_val == 6) {
                    $payableAmount = $getPlanDetails->data['half_yearly_price'];
                    $payableService = $getPlanDetails->data['title'] . ' - Half Yearly';
                } else if ($plan_val == 12) {
                    $payableAmount = $getPlanDetails->data['yearly_price'];
                    $payableService = $getPlanDetails->data['title'] . ' - Annualy';
                }
            }

            /* Calculate Discount Price */
            if($membership_coupon){
                $payableAmount = $CouponObject->calculateDiscountPrice($membership_coupon, $payableAmount);
            }



            /* Memebership data */
            $membershipData = [
                'user_id' => $userDetails->data['user_id'],
                'order_id' => $orderVal,
                'transaction_id' => '',
                'total_price' => $payableAmount,
                'plan_id' => $selected_plan,
                'plan_period' => $plan_val,
                'payment_status' => 2,
                'payment_date' => '',
                'next_payment_date' => '',
                'plan_start_date' => '',
                'plan_end_date' => ''
            ];

            $membershipInsertion = $MembershipObject->insertIntoMemberShip($membershipData);

            /* PayPal data */
            /*$paypal_data_params = array(
                'no_shipping' => '1',
                'no_note' => '1',
                'item_name' => $payableService,
                'currency_code' => 'BRL',
                'amount' => $payableAmount,
                'return' => SUPPLIER_DASHBOARD_PAGE . "/?action=success",
                'cancel_return' => SUPPLIER_DASHBOARD_PAGE . "/?action=cancel",
                'notify_url' => BASE_URL
            );
            $paypal_data_params['custom'] = $orderVal . '#' . $userDetails->data['user_id'];*/

            $cardExpirationDate = $subscription_card_exp_month . $subscription_card_exp_year;

            $creditCardDetails = [
                'IPADDRESS' => $_SERVER['REMOTE_ADDR'],
                'VERSION' => '84.0',
                'METHOD' => 'DoDirectPayment',
                'PAYMENTACTION' => 'Sale',
                'CREDITCARDTYPE' => $subscription_card_type,
                'ACCT' => $subscription_card_number,
                'EXPDATE' => $cardExpirationDate,
                'CVV2' => $subscription_card_cvv,
                'FIRSTNAME' => $userDetails->data['fname'],
                'LASTNAME' => $userDetails->data['lname'],
                'COUNTRYCODE' => 'US',
                //'STATE' => $stateAbbreviation,
                'CITY' => $getCityDetails->name,
                'STREET' => $userDetails->data['address'],
                //'ZIP' => $userDetails->data['zipcode'],
                'AMT' => $payableAmount,
                'CURRENCYCODE' => 'USD'
            ];

            $paymentProcess = $payPalObj->process_payment($creditCardDetails);

            if ($paymentProcess['msg'] == 'success') {
                $order_id = $orderVal;
                $user_id = $userDetails->data['user_id'];
                $transaction_id = $paymentProcess['transaction_id'];
                $user_details = $GeneralThemeObject->user_details($user_id);
                $getMembershipDetails = $MembershipObject->getMembershipDetails($order_id);

                if ($getMembershipDetails[0]->plan_period == 3) {
                    $planLimitDate = 3;
                    $planStartDate = strtotime(date('Y-m-d'));
                    $planEndDate = strtotime('+' . $planLimitDate . ' months', strtotime(date('Y-m-d')));
                    $planNextPaymentDate = $planEndDate;
                } else if ($getMembershipDetails[0]->plan_period == 6) {
                    $planLimitDate = 6;
                    $planStartDate = strtotime(date('Y-m-d'));
                    $planEndDate = strtotime('+' . $planLimitDate . ' months', strtotime(date('Y-m-d')));
                    $planNextPaymentDate = $planEndDate;
                } else if ($getMembershipDetails[0]->plan_period == 12) {
                    $planLimitDate = 12;
                    $planStartDate = strtotime(date('Y-m-d'));
                    $planEndDate = strtotime('+' . $planLimitDate . ' months', strtotime(date('Y-m-d')));
                    $planNextPaymentDate = $planEndDate;
                }

                if ($getMembershipDetails[0]->payment_status == 2) {
                    
                    $updatedData = [
                        'transaction_id' => $transaction_id,
                        'payment_status' => 1,
                        'payment_date' => strtotime(date('Y-m-d')),
                        'next_payment_date' => $planNextPaymentDate,
                        'plan_start_date' => $planStartDate,
                        'plan_end_date' => $planEndDate
                    ];
                 
                    $whereData = [
                        'order_id' => $order_id,
                        'user_id' => $user_details->data['user_id'],
                    ];

                    $updateMembershipData = $MembershipObject->updateMembershipData($updatedData, $whereData);
                    
                    update_user_meta($user_details->data['user_id'], '_selected_plan', $getMembershipDetails[0]->plan_id);
                    update_user_meta($user_details->data['user_id'], '_selected_start_date', $planStartDate);
                    update_user_meta($user_details->data['user_id'], '_selected_end_date', $planEndDate);
                    update_user_meta($user_details->data['user_id'], '_selected_plan_payment_status', 1);

                    /* Sending email to Supplier */
                    $customer_email_content = $GeneralThemeObject->getEmailContents('mail-to-supplier-for-new-membership', ['{%user_name%}', '{%plan_name%}', '{%membership_id%}', '{%total_price%}', '{%start_date%}', '{%end_date%}'], [$user_details->data['fname'], get_the_title($getMembershipDetails[0]->plan_id), $order_id, 'R$ ' . number_format($getMembershipDetails[0]->total_price), date('d M, Y', $planStartDate), date('d M, Y', $planEndDate)]);
                    $customer_email_subject = get_bloginfo('name') . ' :: ' . $customer_email_content[0];
                    $customer_email_template = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $customer_email_content[1]);
                    $GeneralThemeObject->send_mail_func($user_details->data['email'], $customer_email_subject, $customer_email_template);

                    /* Sending email to Administrator */
                    $admin_email = get_option('admin_email');
                    $admin_email_content = $GeneralThemeObject->getEmailContents('mail-to-administrator-for-new-membership', ['{%user_name%}', '{%plan_name%}', '{%membership_id%}', '{%total_price%}', '{%start_date%}', '{%end_date%}'], [$user_details->data['fname'] . ' ' . $user_details->data['lname'], get_the_title($getMembershipDetails[0]->plan_id), $order_id, 'R$ ' . number_format($getMembershipDetails[0]->total_price, 2), date('d M, Y', $planStartDate), date('d M, Y', $planEndDate)]);
                    $admin_email_subject = get_bloginfo('name') . ' :: ' . $admin_email_content[0];
                    $admin_email_template = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $admin_email_content[1]);
                    $GeneralThemeObject->send_mail_func($admin_email, $admin_email_subject, $admin_email_template);
                }

                $msg = 'Your payment has been made successfully.';
                $resp_arr['url'] = SUPPLIER_DASHBOARD_PAGE;
                $resp_arr['flag'] = TRUE; 

            } else {
                $msg = $paymentProcess['msg'];
            }
        }

        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}

/*
 * --------------------------------------------
 * AJAX:: Show Plan Price
 * --------------------------------------------
 */

add_action('wp_ajax_show_plan_price', 'ajaxShowPlanPrice');

if (!function_exists('ajaxShowPlanPrice')) {

    function ajaxShowPlanPrice() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $MembershipObject = new classMemberShip();
        $msg = 0;
        $period_val = base64_decode($_POST['period_val']);
        $plan_val = base64_decode($_POST['plan_val']);
        $planDetails = $GeneralThemeObject->getMembershipPlanDetails($plan_val);
        $userDetails = $GeneralThemeObject->user_details();
        $getMembershipDetails = $MembershipObject->getUserMembershipDetails($userDetails->data['user_id']);
        $currDate = strtotime(date('Y-m-d'));
        $userPreviousPlan = $userDetails->data['selected_plan'];
        $previousPlanDetails = $GeneralThemeObject->getMembershipPlanDetails($userPreviousPlan);

        /* Checking for condition whether user is buying the upgraded plan than it's current plan within current expiry plan date */
        if(is_array($getMembershipDetails) && count($getMembershipDetails) > 0 && $plan_val != $userDetails->data['selected_plan'] && $currDate <= $userDetails->data['selected_end_date']){

            if($period_val == 3){
                if ($planDetails->data['quarterly_price'] > $getMembershipDetails[0]->total_price) {
                    $msg = ($planDetails->data['quarterly_price'] - $getMembershipDetails[0]->total_price);
                } else {
                    $msg = $planDetails->data['quarterly_price'];
                }
            } else if($period_val == 6){
                if ($planDetails->data['half_yearly_price'] > $getMembershipDetails[0]->total_price) {
                    $msg = ($planDetails->data['half_yearly_price'] - $getMembershipDetails[0]->total_price);
                } else{
                    $msg = $planDetails->data['half_yearly_price'];
                }
            } else if($period_val == 12){
                if ($planDetails->data['yearly_price'] > $getMembershipDetails[0]->total_price) {
                    $msg = ($planDetails->data['yearly_price'] - $getMembershipDetails[0]->total_price);
                } else{
                    $msg = $planDetails->data['yearly_price'];
                }
            }
        } else{
            if ($period_val == 3) {
                $msg = $planDetails->data['quarterly_price'];
            } else if ($period_val == 6) {
                $msg = $planDetails->data['half_yearly_price'];
            } else if ($period_val == 12) {
                $msg = $planDetails->data['yearly_price'];
            }
        }

        $resp_arr['flag'] = TRUE;
        $resp_arr['msg'] = number_format($msg, 2);
        echo json_encode($resp_arr);
        exit;
    }

}