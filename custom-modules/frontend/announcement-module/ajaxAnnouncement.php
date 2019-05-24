<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * --------------------------------------------
 * AJAX:: Drag & drop image upload
 * --------------------------------------------
 */

add_action('wp_ajax_drag_and_drop_image_upload', 'ajaxDragAndDropImageUpload');

if (!function_exists('ajaxDragAndDropImageUpload')) {

    function ajaxDragAndDropImageUpload() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => '', 'attachids' => '', 'flagWarning' => '', 'attach_id_arr' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $msg = NULL;
        //$list_camera_images = $_FILES['rental_photos'];
        $list_camera_images = $_FILES['file'];
        $uploading_val = $_REQUEST['uploading_val'];

        if (empty($list_camera_images['name'])) {
            $msg = __('Image not found.', THEME_TEXTDOMAIN);
        } else if (!in_array($list_camera_images['type'], $GeneralThemeObject->file_type_arr)) {
            $msg = __('Upload an image file.', THEME_TEXTDOMAIN);
        } else {
            $file_to_be_uploaded = $GeneralThemeObject->common_file_upload($list_camera_images);
            $camera_image_ids = $GeneralThemeObject->create_attachment($file_to_be_uploaded);

            if (is_array($camera_image_ids) && count($camera_image_ids) > 0) {
                $resp_arr['flag'] = TRUE;
                $resp_arr['attachids'] = join(',', $camera_image_ids);
            } else {
                $resp_arr['flag'] = TRUE;
                $resp_arr['attachids'] = $camera_image_ids;
            }
            $resp_arr['flagWarning'] = $uploading_val;
        }

        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}


/*
 * --------------------------------------------
 * AJAX:: Ajax Get Announcement Price
 * --------------------------------------------
 */

add_action('wp_ajax_get_announcement_price', 'ajaxGetAnnouncementPrice');

if (!function_exists('ajaxGetAnnouncementPrice')) {

    function ajaxGetAnnouncementPrice() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $AnnouncementObject = new classAnnouncement();
        $msg = NULL;
        $currDate = strtotime(date('Y-m-d'));
        $announcement_plan = strip_tags(trim($_POST['announcement_plan']));
        $announcement_start_date = strtotime(strip_tags(trim($_POST['announcement_start_date'])));
        $announcement_period = strip_tags(trim($_POST['announcement_period']));

        // echo $_POST['announcement_date'];
        // echo date('Y-m-d');
        // exit;
        
        if (empty($announcement_period)) {
            $msg = __('Please select number of days for your announcement.', THEME_TEXTDOMAIN);
        } else if($currDate > $announcement_start_date){
            $msg = __('Please change your announcement start date.', THEME_TEXTDOMAIN);
        } else if ($announcement_period < 1 || $announcement_period > 31) {
            $msg = __("O período do anúncio deve ser de 1 a 31 dias.", THEME_TEXTDOMAIN);
        } else {
            $getAnnouncementPrice = $AnnouncementObject->getAnnouncementPrice($announcement_plan, $announcement_period);

            if ($getAnnouncementPrice >0) {
                $msg = __("Seu valor a pagar por este anúncio: <span>R$ " . number_format($getAnnouncementPrice, 2) . '</span>', THEME_TEXTDOMAIN);
            } else {
                $msg = __('You do not need to pay for this announcement. Go ahead to add announcement.', THEME_TEXTDOMAIN);
            }
            $resp_arr['flag'] = TRUE;
        }

        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}

/*
 * --------------------------------------------
 * AJAX:: Ajax Create Announcement
 * --------------------------------------------
 */

add_action('wp_ajax_announcement_create', 'ajaxCreateAnnouncement');

if (!function_exists('ajaxCreateAnnouncement')) {

    function ajaxCreateAnnouncement() {
                
        $resp_arr = ['flag' => FALSE, 'msg' =>'', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $AnnouncementObject = new classAnnouncement();
        $userDetails = $GeneralThemeObject->user_details();
        $msg = NULL;
        //$announcement_category=array();
        $announcement_name = strip_tags(trim($_POST['announcement_name']));
        $announcement_category = $_POST['announcement_category'];
        $announcement_date = strip_tags(trim($_POST['announcement_date']));
        $announcement_period = strip_tags(trim($_POST['announcement_period']));
        $property_main_images = $_POST['property_main_images'];
        $announcement_desc = strip_tags(trim($_POST['announcement_desc']));
        $announcement_type = $_POST['announcement_type'];
        $announcment_price = $_POST['announcment_price'];
        $announcment_state = $_POST['announcement_state'];
        $announcment_city = $_POST['announcement_city'];
        $announcment_address = $_POST['address'];
        $announcment_loc = $_POST['addressloc'];
        $announcment_addressID = $_POST['addressID'];
        $announcment_terms_condition = $_POST['announcement_terms_condi'];
        $announcement_id = $_POST['announcement_id'];
        $status_val = $_POST['status_val'];

        $announcement_order = '';

        if($announcement_type == 'gold'){
            $announcement_order = 1;
        } elseif ($announcement_type == 'silver') {
            $announcement_order = 2;
        } else {
            $announcement_order = 3;
        } 

        $noOfAnnouncementType = $AnnouncementObject->getNoOfActiveAnnouncements($userDetails->data['user_id'], $announcement_order);
    
        $get_announcement_price = get_option('_announcement_price');
        $userDetails = $GeneralThemeObject->user_details();

        if (empty($announcement_name)) {
            $msg = __('Enter your announcement name.', THEME_TEXTDOMAIN);
        } else if (empty($announcement_category)) {
            $msg = __('Select your announcement category.', THEME_TEXTDOMAIN);
        } else if (empty($announcment_state)) {
            $msg = __('Select your announcement state.', THEME_TEXTDOMAIN);
        } else if (empty($announcment_city)) {
            $msg = __('Select your announcement city.', THEME_TEXTDOMAIN);
        } else if (empty($announcment_loc)) {
            $msg = __('Address cannot be blanked.', THEME_TEXTDOMAIN);
        } else if (empty($announcment_addressID)) {
            $msg = __('Address cannot be blanked.', THEME_TEXTDOMAIN);
        } else if (empty($announcement_date)) {
            $msg = __('Select your announcement start date.', THEME_TEXTDOMAIN);
        } else if (empty($announcement_period)) {
            $msg = __('Enter your announcement time period in days.', THEME_TEXTDOMAIN);
        } else if (empty($property_main_images)) {
            $msg = __('Upload at least one image for your announcement.', THEME_TEXTDOMAIN);
        } else if (empty($announcement_type)) {
            $msg = __('Select your announcement type.', THEME_TEXTDOMAIN);
        } else if (empty($announcment_terms_condition)) {
            $msg = __('Please check the terms and conditions.', THEME_TEXTDOMAIN);
        } else if ($noOfAnnouncementType == $get_announcement_price[$announcement_type]['no_of_post']) {
            $msg = __('You can not create more than '.$get_announcement_price[$announcement_type]['no_of_post'].' '. $announcement_type .' announcements. Try to upgrade your announcement plan.', THEME_TEXTDOMAIN);
        } else {

            $getAnnouncementPrice = $AnnouncementObject->getAnnouncementPrice($announcement_type, $announcement_period);

            /* Announcement Args */
            $announcementArgs = [
                'post_type' => themeFramework::$theme_prefix . 'announcement',
                'post_title' => $announcement_name,
                'post_content' => $announcement_desc,
                'post_status' => 'publish',
                'post_author' => $userDetails->data['user_id']
            ];
            
                        
            $announcement_id = $AnnouncementObject->insertAnnouncement($announcementArgs);

            wp_set_object_terms($announcement_id, $announcement_category, themeFramework::$theme_prefix . 'announcement_category');
            
            /*update_post_meta($announcement_id, '_start_date', $announcement_date);*/
            update_post_meta($announcement_id, '_start_date', date('Y-m-d'));
            update_post_meta($announcement_id, '_number_of_days', $announcement_period);
            update_post_meta($announcement_id, '_announcement_images', $property_main_images);
            update_post_meta($announcement_id, '_announcement_plan', $announcement_type);
            update_post_meta($announcement_id, '_announcement_order', $announcement_order);
            update_post_meta($announcement_id, '_announcement_state', $announcment_state);
            update_post_meta($announcement_id, '_announcement_city', $announcment_city);
            update_post_meta($announcement_id, '_announcement_address', $announcment_address);
            update_post_meta($announcement_id, '_announcement_address_loc', $announcment_loc);
            update_post_meta($announcement_id, '_announcement_address_id', $announcment_addressID);
            if(!$announcment_price ||$announcment_price < 0){
                update_post_meta($announcement_id, '_announcement_price', 0.00);
            } else {
                update_post_meta($announcement_id, '_announcement_price', $announcment_price);
            }
            update_post_meta($announcement_id, '_estimated_price', $getAnnouncementPrice);
            $imageId = explode(',',$property_main_images);
            update_field('_add_announcement_image',$imageId,$announcement_id);
            update_post_meta($announcement_id, '_admin_approval', 2);//Disapprove
            update_post_meta($announcement_id, '_announcement_enabled', 2);//Inactive

            $resp_arr['flag'] = TRUE;
            $resp_arr['url'] = MY_ANNOUNCEMENTS_PAGE;
            $msg = __('Your announcement is waiting for admin approval. Once it approved will send you a mail for further process.', THEME_TEXTDOMAIN);
            
        }


        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}

/*
 * --------------------------------------------
 * AJAX:: Ajax Update Announcement
 * --------------------------------------------
 */

add_action('wp_ajax_announcement_update', 'ajaxUpdateAnnouncement');

if (!function_exists('ajaxUpdateAnnouncement')) {

    function ajaxUpdateAnnouncement() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $AnnouncementObject = new classAnnouncement();
        $msg = NULL;
        $announcement_name = strip_tags(trim($_POST['announcement_name']));
        $announcement_category = $_POST['announcement_category'];
        $property_main_images = $_POST['property_main_images'];
        $announcement_desc = strip_tags(trim($_POST['announcement_desc']));
        $announcment_price = $_POST['announcment_price'];
        $announcement_id = base64_decode($_POST['announcement_id']);
        $announcment_state = $_POST['announcement_state'];
        $announcment_city = $_POST['announcement_city'];
        $announcment_address = $_POST['address'];
        $announcment_loc = $_POST['addressloc'];
        $announcment_addressID = $_POST['addressID'];
        
        $announcement_details = $AnnouncementObject->announcement_details($announcement_id);

        $userDetails = $GeneralThemeObject->user_details();

        if (empty($announcement_name)) {
            $msg = __('Enter your announcement name.', THEME_TEXTDOMAIN);
        } else if (empty($announcement_category)) {
            $msg = __('Select your announcement category.', THEME_TEXTDOMAIN);
        } else if (empty($announcment_state)) {
            $msg = __('Select your announcement state.', THEME_TEXTDOMAIN);
        } else if (empty($announcment_city)) {
            $msg = __('Select your announcement city.', THEME_TEXTDOMAIN);
        } else if (empty($announcment_loc)) {
            $msg = __('Address cannot be blanked.', THEME_TEXTDOMAIN);
        } else if (empty($announcment_addressID)) {
            $msg = __('Address cannot be blanked.', THEME_TEXTDOMAIN);
        } 
        /*else if (empty($announcment_price)) {
            $msg = __('Enter your announcement price.', THEME_TEXTDOMAIN);
        }*/ else if (empty($property_main_images)) {
            $msg = __('Upload at least one image for your announcement.', THEME_TEXTDOMAIN);
        } else {

            /* Update Old Announcement Content */
            update_post_meta($announcement_id, '_announcement_old_content', $announcement_details->data['content']);

            /* Announcement Args */
            $announcementArgs = [
                'ID' => $announcement_id,
                'post_title' => $announcement_name,
                'post_content' => $announcement_desc,
            ];

            $announcement_id = $AnnouncementObject->updateAnnouncement($announcementArgs);

            wp_set_object_terms($announcement_id, $announcement_category, themeFramework::$theme_prefix . 'announcement_category');

            //$ss = wp_get_object_terms($announcement_id, themeFramework::$theme_prefix . 'announcement_category');
            
            update_post_meta($announcement_id, '_announcement_images', $property_main_images);
            update_post_meta($announcement_id, '_announcement_state', $announcment_state);
            update_post_meta($announcement_id, '_announcement_city', $announcment_city);
            update_post_meta($announcement_id, '_announcement_address', $announcment_address);
            update_post_meta($announcement_id, '_announcement_address_loc', $announcment_loc);
            update_post_meta($announcement_id, '_announcement_address_id', $announcment_addressID);
            if(!$announcment_price ||$announcment_price < 0){
                update_post_meta($announcement_id, '_announcement_price', 0.00);
            } else {
                update_post_meta($announcement_id, '_announcement_price', $announcment_price);
            }
            update_post_meta($announcement_id, '_announcement_plan', $announcement_details->data['announcement_plan']);
            update_post_meta($announcement_id, '_admin_approval', 1);
            $imageId = explode(',',$property_main_images);
            update_field('_add_announcement_image',$imageId,$announcement_id);
            $resp_arr['flag'] = TRUE;
            $resp_arr['url'] = MY_ANNOUNCEMENTS_PAGE;
            $msg = __('Your announcement successfully saved.', THEME_TEXTDOMAIN);
        }

        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}


/*
 * --------------------------------------------
 * AJAX:: Ajax Renewal Announcement
 * --------------------------------------------
 */

add_action('wp_ajax_announcement_renew', 'ajaxRenewAnnouncement');

if (!function_exists('ajaxRenewAnnouncement')) {

    function ajaxRenewAnnouncement() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $AnnouncementObject = new classAnnouncement();
        $CouponObject = new classCoupnManagement();
        $payPalObj = new WC_PP_PRO_Gateway();
        $msg = NULL;
        $announcement_name = strip_tags(trim($_POST['announcement_name']));
        $announcement_category = $_POST['announcement_category'];
        $announcement_date = strip_tags(trim($_POST['announcement_date']));
        $announcement_period = strip_tags(trim($_POST['announcement_period']));
        $property_main_images = $_POST['property_main_images'];
        $announcement_desc = strip_tags(trim($_POST['announcement_desc']));
        $announcement_type = $_POST['announcement_type'];
        $announcement_renew = base64_decode($_POST['announcement_renew']);
        $announcment_price = $_POST['announcment_price'];
        $membership_coupon = $_POST['membership_coupon'];
        $userDetails = $GeneralThemeObject->user_details();

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

        if (empty($announcement_name)) {
            $msg = __('Enter your announcement name.', THEME_TEXTDOMAIN);
        } else if (empty($announcement_category)) {
            $msg = __('Select your announcement category.', THEME_TEXTDOMAIN);
        } else if (empty($announcement_date)) {
            $msg = __('Select your announcement start date.', THEME_TEXTDOMAIN);
        } else if (empty($announcement_period)) {
            $msg = __('Enter your announcement time period in days.', THEME_TEXTDOMAIN);
        } else if (empty($property_main_images)) {
            $msg = __('Upload at least one image for your announcement.', THEME_TEXTDOMAIN);
        } else if (empty($announcement_type)) {
            $msg = __('Select your announcement type.', THEME_TEXTDOMAIN);
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

            $getAnnouncementPrice = $AnnouncementObject->getAnnouncementPrice($announcement_type, $announcement_period);
            $getCityDetails = get_term_by('id', $userDetails->data['city'], themeFramework::$theme_prefix . 'product_city');

            /* Calculate Discount Price */
            if($membership_coupon){
                $calculatePrice = $CouponObject->calculateDiscountPrice($membership_coupon, $getAnnouncementPrice);
            } else{
                $calculatePrice = NULL;
            }

            /* Announcement Args */
            $announcementArgs = [
                'ID' => $announcement_renew,
                'post_title' => $announcement_name,
                'post_content' => $announcement_desc,
            ];

            $announcement_id = $AnnouncementObject->updateAnnouncement($announcementArgs);

            wp_set_object_terms($announcement_id, $announcement_category, themeFramework::$theme_prefix . 'announcement_category');

            update_post_meta($announcement_id, '_start_date', $announcement_date);
            update_post_meta($announcement_id, '_number_of_days', $announcement_period);
            update_post_meta($announcement_id, '_announcement_images', $property_main_images);
            update_post_meta($announcement_id, '_announcement_plan', $announcement_type);
            update_post_meta($announcement_id, '_announcement_state', $userDetails->data['state']);
            update_post_meta($announcement_id, '_announcement_city', $userDetails->data['city']);
            update_post_meta($announcement_id, '_announcement_address', $userDetails->data['address']);
            update_post_meta($announcement_id, '_announcement_address_loc', $userDetails->data['address_loc']);
            update_post_meta($announcement_id, '_announcement_address_id', $userDetails->data['address_id']);
            if(!$announcment_price ||$announcment_price < 0){
                update_post_meta($announcement_id, '_announcement_price', 0.00);
            } else {
                update_post_meta($announcement_id, '_announcement_price', $announcment_price);
            }
            $imageId = explode(',',$property_main_images);
            update_field('_add_announcement_image',$imageId,$announcement_id);
            $generateRandomString = $GeneralThemeObject->generateRandomString(8);

            if ($announcement_type == 'bronze') {

                update_post_meta($announcement_id, '_admin_approval', 2);
                update_post_meta($announcement_id, '_announcement_enabled', 1);

                $resp_arr['flag'] = TRUE;
                $resp_arr['url'] = MY_ANNOUNCEMENTS_PAGE;
                $msg = __('Thanks for renewing this announcement.', THEME_TEXTDOMAIN);
            } else {
                /* Announcement Payment Data */
                $announcementPaymentData = [
                    'user_id' => $userDetails->data['user_id'],
                    'unique_announcement_code' => $generateRandomString,
                    'announcement_id' => $announcement_id,
                    'total_price' => ($calculatePrice) ? $calculatePrice : $getAnnouncementPrice,
                    'transaction_id' => '',
                    'payment_status' => 2,
                    'payment_date' => '',
                    'plan_type' => $announcement_type,
                ];

                $insertedAnnouncementPaymentID = $AnnouncementObject->insertIntoAnnouncementPayment($announcementPaymentData);

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
                    'AMT' => ($calculatePrice) ? $calculatePrice : $getAnnouncementPrice,
                    'CURRENCYCODE' => 'USD'
                ];

                $paymentProcess = $payPalObj->process_payment($creditCardDetails);

                if ($paymentProcess['msg'] == 'success') {

                    /* Get Announcement Data */
                    $queryAnnouncementString = " AND `unique_announcement_code`='" . $generateRandomString . "'";
                    $getAnnouncementPaymentData = $AnnouncementObject->getAnnouncementPaymentData($queryAnnouncementString);

                    if (is_array($getAnnouncementPaymentData) && count($getAnnouncementPaymentData) > 0) {
                        update_post_meta($announcement_id, '_admin_approval', 1); //Approved
                        update_post_meta($announcement_id, '_announcement_enabled', 1); //Active
                        
                        /*update user active announcment counter*/
                        $_counter = 0;
                        $_counter = get_user_meta($userDetails->data['user_id'], '_'.$announcemenDetails->data['announcement_plan'].'_count', true);
                        update_user_meta($userDetails->data['user_id'], '_'.$announcemenDetails->data['announcement_plan'].'_count', $_counter+1 );

                        $updatedAnnouncementData = [
                            'transaction_id' => $paymentProcess['transaction_id'],
                            'payment_date' => strtotime(date('Y-m-d')),
                            'payment_status' => 1
                        ];

                        $whereAnnouncementData = [
                            'unique_announcement_code' => $generateRandomString
                        ];

                        $AnnouncementObject->updateAnnouncementPayment($updatedAnnouncementData, $whereAnnouncementData);

                        /* Sending email to User */
                        $customer_email_content = $GeneralThemeObject->getEmailContents('mail-to-user-for-renewing-announcement', ['{%user_name%}', '{%total_price%}', '{%announcement_name%}', '{%payment_date%}', '{%unique_code%}'], [$userDetails->data['fname'] . ' ' . $userDetails->data['lname'], 'R$ ' . $getAnnouncementPaymentData[0]->total_price, get_the_title($getAnnouncementPaymentData[0]->announcement_id), date('d M, Y'), $generateRandomString]);
                        $customer_email_subject = get_bloginfo('name') . ' :: ' . $customer_email_content[0];
                        $customer_email_template = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $customer_email_content[1]);
                        $GeneralThemeObject->send_mail_func($userDetails->data['email'], $customer_email_subject, $customer_email_template);

                        /* Sending email to Administrator */
                        $admin_email = get_option('admin_email');
                        $admin_email_content = $GeneralThemeObject->getEmailContents('mail-to-administrator-for-renewing-announcement', ['{%user_name%}', '{%total_price%}', '{%announcement_name%}', '{%payment_date%}', '{%unique_code%}'], [$userDetails->data['fname'] . ' ' . $userDetails->data['lname'], 'R$ ' . $getAnnouncementPaymentData[0]->total_price, get_the_title($getAnnouncementPaymentData[0]->announcement_id), date('d M, Y'), $generateRandomString]);
                        $admin_email_subject = get_bloginfo('name') . ' :: ' . $admin_email_content[0];
                        $admin_email_template = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $admin_email_content[1]);
                        $GeneralThemeObject->send_mail_func($admin_email, $admin_email_subject, $admin_email_template);
                    }
                    $resp_arr['flag'] = TRUE;
                    $resp_arr['url'] = MY_ANNOUNCEMENTS_PAGE;
                    $msg = 'Thanks for renewing announcement and making payment. It is sucessfull.';
                } else {
                    $msg = $paymentProcess['msg'];
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
 * AJAX:: Ajax Announcement Image Delete
 * --------------------------------------------
 */

add_action('wp_ajax_announcement_image_delete', 'ajaxDeleteAnnouncementImage');

if (!function_exists('ajaxDeleteAnnouncementImage')) {

    function ajaxDeleteAnnouncementImage() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => '', 'images' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $msg = NULL;
        $announcement_image = base64_decode($_POST['announcement_image']);
        $announcement = base64_decode($_POST['announcement']);
        $get_announcement_images = get_post_meta($announcement, '_announcement_images', TRUE);
        $explodedImages = explode(',', $get_announcement_images);
        $countAnnouncementImages = count($explodedImages);

        if ($countAnnouncementImages == 1) {
            $msg = __('You do not have sufficient images for your announcement. You have to keep at least one.', THEME_TEXTDOMAIN);
        } else if (empty($announcement)) {
            $msg = __('Announcement not found.', THEME_TEXTDOMAIN);
        } else if (empty($announcement_image)) {
            $msg = __('Announcement image not found.', THEME_TEXTDOMAIN);
        } else {
            if (is_array($explodedImages) && count($explodedImages) > 0) {
                foreach ($explodedImages as $eachAnnouncementKey => $eachAnnouncementVal) {
                    if ($announcement_image == $eachAnnouncementVal) {
                        unset($explodedImages[$eachAnnouncementKey]);
                    }
                }
                $updatedAnnouncementImages = array_values($explodedImages);
                $updatedImages = join(',', $updatedAnnouncementImages);
            }
            update_post_meta($announcement, '_announcement_images', $updatedImages);
            $resp_arr['flag'] = TRUE;
            $resp_arr['images'] = $updatedImages;
            $msg = __('Image successfully deleted.', THEME_TEXTDOMAIN);
        }

        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}

/*
 * --------------------------------------------
 * AJAX:: Ajax Announcement De-Activate
 * --------------------------------------------
 */

add_action('wp_ajax_announcement_deactivate', 'ajaxDeactivateAnnouncement');

if (!function_exists('ajaxDeactivateAnnouncement')) {

    function ajaxDeactivateAnnouncement() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $AnnouncementObject = new classAnnouncement();
        $msg = NULL;
        $announcement = base64_decode($_POST['announcement']);
        $user_id = get_current_user_id();
        if (empty($announcement)) {
            $msg = __('Announcement not found.', THEME_TEXTDOMAIN);
        } else {
            $announcemenDetails = $AnnouncementObject->announcement_details($announcement);
            /* updated data */
            update_post_meta($announcement, '_announcement_enabled', 2);//Deactivate
            
            $_counter = get_user_meta($user_id, '_'.$announcemenDetails->data['announcement_plan'].'_count', true);
            $_counter = ($_counter == '') ? 0 : $_counter -1;
            update_user_meta($user_id, '_'.$announcemenDetails->data['announcement_plan'].'_count', $_counter );
            
            /* $updatedData = [
              'ID' => $announcement,
              'post_status' => 'draft'
              ];
              $AnnouncementObject->updateAnnouncement($updatedData); */
            $resp_arr['flag'] = TRUE;
            $resp_arr['url'] = MY_ANNOUNCEMENTS_PAGE;
            $msg = __('Announcement has been de activated.', THEME_TEXTDOMAIN);
        }


        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}

/*
 * --------------------------------------------
 * AJAX:: Ajax Announcement Activate
 * --------------------------------------------
 */

add_action('wp_ajax_announcement_activate', 'ajaxActivateAnnouncement');

if (!function_exists('ajaxActivateAnnouncement')) {

    function ajaxActivateAnnouncement() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $AnnouncementObject = new classAnnouncement();
        $msg = NULL;
        $announcement = base64_decode($_POST['announcement']);
        $announcement_details = $AnnouncementObject->announcement_details($announcement);
        $currDate = strtotime(date('Y-m-d'));
        $user_id = get_current_user_id();

        if (empty($announcement)) {
            $msg = __('Announcement not found.', THEME_TEXTDOMAIN);
        } else if ($currDate > strtotime($announcement_details->data['end_date'])) {
            $msg = __('This announcement has already expired due to it\'s end period. Please renew it to activate.', THEME_TEXTDOMAIN);
        } else {
            
            /* updated data */
            update_post_meta($announcement, '_announcement_enabled', 1);//Active
            $_counter = get_user_meta($user_id, '_'.$announcement_details->data['announcement_plan'].'_count', true);
            update_user_meta($user_id, '_'.$announcement_details->data['announcement_plan'].'_count', $_counter+1 );
            
            /* $updatedData = [
              'ID' => $announcement,
              'post_status' => 'publish'
              ];
              $AnnouncementObject->updateAnnouncement($updatedData); */
            $resp_arr['flag'] = TRUE;
            $resp_arr['url'] = MY_ANNOUNCEMENTS_PAGE;
            $msg = __('Announcement has been activated.', THEME_TEXTDOMAIN);
        }


        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}

/*
 * --------------------------------------------
 * AJAX:: Ajax Announcement Delete
 * --------------------------------------------
 */

add_action('wp_ajax_delete_user_announcement', 'ajaxDeleteAnnouncement');

if (!function_exists('ajaxDeleteAnnouncement')) {

    function ajaxDeleteAnnouncement() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $AnnouncementObject = new classAnnouncement();
        $msg = NULL;
        $announcement = base64_decode($_POST['ID']);
        $user_id = get_current_user_id();
        if (empty($announcement)) {
            $msg = __('Announcement not found.', THEME_TEXTDOMAIN);
        } else {
            $announcement_details = $AnnouncementObject->announcement_details($announcement);
            
            wp_delete_post($announcement, TRUE);
            
            $_counter = get_user_meta($user_id, '_'.$announcement_details->data['announcement_plan'].'_count', true);
            $_counter = ($_counter == '') ? 0 : $_counter -1;
            update_user_meta($user_id, '_'.$announcement_details->data['announcement_plan'].'_count', $_counter );
            
            $resp_arr['flag'] = TRUE;
            $resp_arr['url'] = MY_ANNOUNCEMENTS_PAGE;
            $msg = __('Your announcement has been deleted.', THEME_TEXTDOMAIN);
        }


        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}


/*
 * --------------------------------------------
 * AJAX:: Announcement Payment
 * --------------------------------------------
 */
add_action('wp_ajax_announcement_payment', 'ajaxAnnouncementPayment');

if (!function_exists('ajaxAnnouncementPayment')) {

    function ajaxAnnouncementPayment() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $AnnouncementObject = new classAnnouncement();
        $CouponObject = new classCoupnManagement();
        $payPalObj = new WC_PP_PRO_Gateway();
        $msg = NULL;
        $selected_announcement_plan = strip_tags(trim(base64_decode($_POST['selected_announcement_plan'])));
        $subscription_card_name = strip_tags(trim($_POST['subscription_card_name']));
        $subscription_card_type = $_POST['subscription_card_type'];
        $subscription_card_number = strip_tags(trim($_POST['subscription_card_number']));
        $subscription_card_cvv = strip_tags(trim($_POST['subscription_card_cvv']));
        $subscription_card_exp_month = $_POST['subscription_card_exp_month'];
        $subscription_card_exp_year = $_POST['subscription_card_exp_year'];
        $membership_coupon = strip_tags(trim($_POST['membership_coupon']));
        $announcement = base64_decode($_POST['ID']);
        $user_id = get_current_user_id();
        $userDetails = $GeneralThemeObject->user_details();
        $announcementDetails = $AnnouncementObject->announcement_details($selected_announcement_plan);

        $usernameValidation = $GeneralThemeObject->userNameValidation($subscription_card_name);
        $cardNumberValidation = $GeneralThemeObject->is_valid_card_number($subscription_card_number);
        $cvvValidation = $GeneralThemeObject->is_valid_cvv_number($subscription_card_cvv);
        $cardExpiryValidation = $GeneralThemeObject->is_valid_expiry($subscription_card_exp_month, $subscription_card_exp_year);

        /* Calculate Discount Price */
        if($membership_coupon){
            $calculatePrice = $CouponObject->calculateDiscountPrice($membership_coupon, $announcementDetails->data['estimated_price']);
        } else{
            $calculatePrice = NULL;
        }

        if (empty($subscription_card_name)) {
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

            $generateRandomString = $GeneralThemeObject->generateRandomString(8);
            $getCityDetails = get_term_by('id', $userDetails->data['city'], themeFramework::$theme_prefix . 'product_city');

            /* Announcement Payment Data */
            $announcementPaymentData = [
                'user_id' => $userDetails->data['user_id'],
                'unique_announcement_code' => $generateRandomString,
                'announcement_id' => $selected_announcement_plan,
                'total_price' => ($calculatePrice) ? $calculatePrice : $announcementDetails->data['estimated_price'],
                'transaction_id' => '',
                'payment_status' => 2,
                'payment_date' => '',
                'plan_type' => $announcementDetails->data['announcement_plan'],
            ];

            $insertedAnnouncementPaymentID = $AnnouncementObject->insertIntoAnnouncementPayment($announcementPaymentData);

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
                'AMT' => ($calculatePrice) ? $calculatePrice : $announcementDetails->data['estimated_price'],
                'CURRENCYCODE' => 'USD'
            ];

            $paymentProcess = $payPalObj->process_payment($creditCardDetails);

            if ($paymentProcess['msg'] == 'success') {

                /* Get Announcement Data */
                $queryAnnouncementString = " AND `unique_announcement_code`='" . $generateRandomString . "'";
                $getAnnouncementPaymentData = $AnnouncementObject->getAnnouncementPaymentData($queryAnnouncementString);

                if (is_array($getAnnouncementPaymentData) && count($getAnnouncementPaymentData) > 0) {

                    update_post_meta($selected_announcement_plan, '_admin_approval', 1); //Approved
                    update_post_meta($selected_announcement_plan, '_announcement_enabled', 1); //Active
                    
                    /*update user active announcment counter*/
                    $_counter = 0;
                    $_counter = get_user_meta($user_id, '_'.$announcemenDetails->data['announcement_plan'].'_count', true);
                    update_user_meta($user_id, '_'.$announcemenDetails->data['announcement_plan'].'_count', $_counter+1 );

                    $updatedAnnouncementData = [
                        'transaction_id' => $paymentProcess['transaction_id'],
                        'payment_date' => strtotime(date('Y-m-d')),
                        'payment_status' => 1
                    ];

                    $whereAnnouncementData = [
                        'unique_announcement_code' => $generateRandomString
                    ];

                    $AnnouncementObject->updateAnnouncementPayment($updatedAnnouncementData, $whereAnnouncementData);

                    /* Sending email to User */
                    $customer_email_content = $GeneralThemeObject->getEmailContents('mail-to-user-for-new-announcement', ['{%user_name%}', '{%total_price%}', '{%announcement_name%}', '{%payment_date%}', '{%unique_code%}'], [$userDetails->data['fname'] . ' ' . $userDetails->data['lname'], 'R$ ' . $getAnnouncementPaymentData[0]->total_price, get_the_title($getAnnouncementPaymentData[0]->announcement_id), date('d M, Y'), $generateRandomString]);
                    $customer_email_subject = get_bloginfo('name') . ' :: ' . $customer_email_content[0];
                    $customer_email_template = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $customer_email_content[1]);
                    $GeneralThemeObject->send_mail_func($userDetails->data['email'], $customer_email_subject, $customer_email_template);

                    /* Sending email to Administrator */
                    $admin_email = get_option('admin_email');
                    $admin_email_content = $GeneralThemeObject->getEmailContents('mail-to-administrator-for-new-announcement', ['{%user_name%}', '{%total_price%}', '{%announcement_name%}', '{%payment_date%}', '{%unique_code%}'], [$userDetails->data['fname'] . ' ' . $userDetails->data['lname'], 'R$ ' . $getAnnouncementPaymentData[0]->total_price, get_the_title($getAnnouncementPaymentData[0]->announcement_id), date('d M, Y'), $generateRandomString]);
                    $admin_email_subject = get_bloginfo('name') . ' :: ' . $admin_email_content[0];
                    $admin_email_template = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $admin_email_content[1]);
                    $GeneralThemeObject->send_mail_func($admin_email, $admin_email_subject, $admin_email_template);
                }

                $resp_arr['flag'] = TRUE;
                $resp_arr['url'] = MY_ANNOUNCEMENTS_PAGE;
                $msg = 'Thanks for making payment. It is sucessfull.';
            } else{
                $msg = $paymentProcess['msg'];
            }

        }


        
        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}