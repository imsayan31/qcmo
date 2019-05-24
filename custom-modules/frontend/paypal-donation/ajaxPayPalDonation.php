<?php

/*
 * --------------------------------------------
 * AJAX:: PayPal Donation
 * --------------------------------------------
 */

add_action('wp_ajax_usr_paypal_donation', 'ajaxPayPalDonation');
add_action('wp_ajax_nopriv_usr_paypal_donation', 'ajaxPayPalDonation');

if (!function_exists('ajaxPayPalDonation')) {

    function ajaxPayPalDonation() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => '', 'user_id' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $DonationObject = new classPayPalDonation();
        $payPalObj = new WC_PP_PRO_Gateway();
        $msg = NULL;
        $fname = strip_tags(trim($_POST['fname']));
        $lname = strip_tags(trim($_POST['lname']));
        $email = strip_tags(trim($_POST['email']));
        $phone = strip_tags(trim($_POST['phone']));
        $donate_amount = $_POST['donate_amount'];
        $get_paypal_donation_amount = get_option('_payapl_donation_amount');

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

        if (empty($fname)) {
            $msg = __('Enter your first name.', THEME_TEXTDOMAIN);
        } elseif (!ctype_alpha($fname)) {
            $msg = __('First name only contains alphabets.', THEME_TEXTDOMAIN);
        } elseif (empty($lname)) {
            $msg = __('Enter your last name.', THEME_TEXTDOMAIN);
        } elseif (!ctype_alpha($lname)) {
            $msg = __('Last name only contains alphabets.', THEME_TEXTDOMAIN);
        } elseif (empty($email)) {
            $msg = __('Enter your mail.', THEME_TEXTDOMAIN);
        } else if (!is_email($email)) {
            $msg = __('Email is not in proper format.', THEME_TEXTDOMAIN);
        } elseif (empty($phone)) {
            $msg = __('Enter your phone.', THEME_TEXTDOMAIN);
        } else if (empty($subscription_card_name)) {
            $msg = __('Enter your card holder name.', THEME_TEXTDOMAIN);
        } else if ($usernameValidation == FALSE) {
            $msg = __('Card holder name should contain only characters.', THEME_TEXTDOMAIN);
        } else if (empty($subscription_card_type)) {
            $msg = __('Select your card type.', THEME_TEXTDOMAIN);
        } else if (empty($subscription_card_number)) {
            $msg = __('Enter your card number.', THEME_TEXTDOMAIN);
        } else if ($cardNumberValidation == FALSE) {
            $msg = __('Your card number is not valid.', THEME_TEXTDOMAIN);
        } else if (empty($subscription_card_cvv)) {
            $msg = __('Enter your CVV.', THEME_TEXTDOMAIN);
        } else if ($cvvValidation == FALSE) {
            $msg = __('Your CVV is not valid.', THEME_TEXTDOMAIN);
        } elseif (empty($subscription_card_exp_month)) {
            $msg = __('Select your card expiry month.', THEME_TEXTDOMAIN);
        } elseif (empty($subscription_card_exp_year)) {
            $msg = __('Select your card expiry year.', THEME_TEXTDOMAIN);
        } else if ($cardExpiryValidation == FALSE) {
            $msg = __('Your card expiry month and year are not valid.', THEME_TEXTDOMAIN);
        } else {

            $donationID = $GeneralThemeObject->generateRandomString(6);

            $donationArgs = [
                'donation_id' => $donationID,
                'name' => $fname . ' ' . $lname,
                'email' => $email,
                'phone' => $phone,
                'amount' => ($donate_amount) ? $donate_amount : $get_paypal_donation_amount,
                'transaction_id' => '',
                'payment_status' => 2,
                'payment_date' => '',
            ];

            $inserted_donation_id = $DonationObject->insertIntoDonation($donationArgs);

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
                    'FIRSTNAME' => $fname,
                    'LASTNAME' => $lname,
                    'COUNTRYCODE' => 'US',
                    //'STATE' => $stateAbbreviation,
                    'CITY' => 'New York',
                    'STREET' => '123 Bekar Street',
                    //'ZIP' => $userDetails->data['zipcode'],
                    'AMT' => ($donate_amount) ? $donate_amount : $get_paypal_donation_amount,
                    'CURRENCYCODE' => 'USD'
                ];

                $paymentProcess = $payPalObj->process_payment($creditCardDetails);

                if ($paymentProcess['msg'] == 'success') {
                    $donationQuery = " AND `donation_id`='" . $donationID . "'";
                    $getDonationDetails = $DonationObject->getDonationDetails($donationQuery);
                    if (is_array($getDonationDetails) && count($getDonationDetails) > 0) {
                        foreach ($getDonationDetails as $eachDonation) {
                            if ($eachDonation->payment_status == 2) {
                                $updatedData = [
                                    'transaction_id' => $paymentProcess['transaction_id'],
                                    'payment_status' => 1,
                                    'payment_date' => strtotime(date('Y-m-d')),
                                ];
                                $whereData = [
                                    'donation_id' => $donationID
                                ];
                                $DonationObject->updatePayPalDonationData($updatedData, $whereData);

                                /* Sending email to User */
                                $customer_email_content = $GeneralThemeObject->getEmailContents('mail-to-user-for-paypal-donation', ['{%user_name%}', '{%donation_id%}', '{%transaction_id%}', '{%total_price%}', '{%payment_date%}'], [$eachDonation->name, $donationID, $paymentProcess['transaction_id'], 'R$ ' . number_format($eachDonation->amount, 2), date('d M, Y')]);
                                $customer_email_subject = get_bloginfo('name') . ' :: ' . $customer_email_content[0];
                                $customer_email_template = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $customer_email_content[1]);
                                $GeneralThemeObject->send_mail_func($eachDonation->email, $customer_email_subject, $customer_email_template);

                                /* Sending email to Administrator */
                                $admin_email = get_option('admin_email');
                                $admin_email_content = $GeneralThemeObject->getEmailContents('mail-to-admin-for-paypal-donation', ['{%user_name%}', '{%user_email%}', '{%donation_id%}', '{%transaction_id%}', '{%total_price%}', '{%payment_date%}'], [$eachDonation->name, $eachDonation->email, $donationID, $paymentProcess['transaction_id'], 'R$ ' . number_format($eachDonation->amount, 2), date('d M, Y')]);
                                $admin_email_subject = get_bloginfo('name') . ' :: ' . $admin_email_content[0];
                                $admin_email_template = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $admin_email_content[1]);
                                $GeneralThemeObject->send_mail_func($admin_email, $admin_email_subject, $admin_email_template);
                            }
                        }
                    }
                    $resp_arr['flag'] = true;
                    $msg = __('You are being redirected to payment page.', THEME_TEXTDOMAIN);
                } else{
                    $msg = __($paymentProcess['msg'], THEME_TEXTDOMAIN);
                }
        }
        $resp_arr['msg'] = $msg;
        $resp_arr['url'] = BASE_URL;
        echo json_encode($resp_arr);
        exit;
    }

}