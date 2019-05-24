<?php

/*
 * --------------------------------------------
 * AJAX:: Finalize Cart Items
 * --------------------------------------------
 */
add_action('wp_ajax_finalize_cart_data', 'ajaxFinalizeCartItems');

if (!function_exists('ajaxFinalizeCartItems')) {

    function ajaxFinalizeCartItems() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $CartObject = new classCart();
        $FinalizeObject = new classFinalize();
        $msg = NULL;
        $deal_name = strip_tags(trim($_POST['deal_name']));
        $deal_description = strip_tags(trim($_POST['deal_description']));
        $userDetails = $GeneralThemeObject->user_details();
        $getUserCartData = $CartObject->getCartItems($userDetails->data['user_id']);
        $getStateDetails = get_term_by('id', $userDetails->data['state'], themeFramework::$theme_prefix . 'product_city');
        $getCityDetails = get_term_by('id', $userDetails->data['city'], themeFramework::$theme_prefix . 'product_city');

        if (!$deal_description) {
            $deal_description = 'No details provided.';
        }

        /* Finalize data */
        $finalizeData = $FinalizeObject->prepareFinalizeData($deal_name, $deal_description);

        /* Finalize product data */
        $finalizeProductData = $FinalizeObject->prepareFinalizeProductData($getUserCartData, $finalizeData);

        /* Finalize Supplier data */
        $finalizeSupplierData = $FinalizeObject->prepareFinalizeSupplierData($getUserCartData, $finalizeData);

        /* Insert finalize data */
        $insertedID = $FinalizeObject->insertIntoTBLFinalize($finalizeData);

        /* Insert finalize product data */
        if (is_array($finalizeProductData) && count($finalizeProductData) > 0) {
            foreach ($finalizeProductData as $eachFinalizeProductData) {
                $insertedFinalizedProductaData[] = $FinalizeObject->insertIntoTBLFinalizeProducts($eachFinalizeProductData);
            }
        }

        /* Insert finalize supplier data */
        if (is_array($finalizeSupplierData) && count($finalizeSupplierData) > 0) {
            foreach ($finalizeSupplierData as $eachFinalizeSupplierData) {
                $insertedFinalizedProductaData[] = $FinalizeObject->insertIntoTBLFinalizeSuppliers($eachFinalizeSupplierData);
            }
        }

        if ($insertedID && is_array($insertedFinalizedProductaData) && count($insertedFinalizedProductaData) > 0 && is_array($insertedFinalizedProductaData) && count($insertedFinalizedProductaData) > 0) {
            $resp_arr['flag'] = TRUE;
            /* if (empty($userDetails->data['phone']) && empty($userDetails->data['lphone'])) {
              $msg = 'Your deal request may not reach all suppliers because your registry is not complete. Please fill in your address, telephone and CPF & CNPJ.';
              } else if (empty($userDetails->data['address'])) {
              $msg = 'Your deal request may not reach all suppliers because your registry is not complete. Please fill in your address, telephone and CPF & CNPJ.';
              } else if (empty($userDetails->data['cpf']) && empty($userDetails->data['cnpj'])) {
              $msg = 'Your deal request may not reach all suppliers because your registry is not complete. Please fill in your address, telephone and CPF & CNPJ.';
              } else {
              } */
            $msg = __('Thanks for finalizing the deal. The suppliers will contact you soon and don’t forget to score them.', THEME_TEXTDOMAIN);
            $CartObject->emptyCart($userDetails->data['user_id']);

            $dealDetails = $FinalizeObject->getDealDetails($finalizeData['deal_id']);

            $supplierMailDetails = $FinalizeObject->sortingSuppliersToEmailAboutDealFinalization($finalizeData['deal_id'], $userDetails->data['user_id']);

            /* Making deal status rejected if no supplier */
            $getDealSuppliersStatus = $FinalizeObject->getDealSuppliersStatus($finalizeData['deal_id']);
            if ($getDealSuppliersStatus == 2) {
                $updatedDealData = [
                    'deal_status' => 4,
                ];
                $whereData = [
                    'deal_id' => $dealDetails->data['deal_id']
                ];
                $updatedData = $FinalizeObject->updateDealFinalizeData($updatedDealData, $whereData);
            }

            /* Sending email to User */
            $get_forgot_password_email_template = $GeneralThemeObject->getEmailContents('mail-to-user-for-deal-finalize', ['{%user_name%}', '{%deal_id%}', '{%total_price%}', '{%status%}', '{%date%}'], [$userDetails->data['fname'] . ' ' . $userDetails->data['lname'], $dealDetails->data['deal_id'], $dealDetails->data['total_price'], $dealDetails->data['status'], $dealDetails->data['initiated']]);
            $owner_mail_subject = get_bloginfo('name') . ' :: ' . $get_forgot_password_email_template[0];
            $owner_email_template = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $get_forgot_password_email_template[1]);
            $GeneralThemeObject->send_mail_func($userDetails->data['email'], $owner_mail_subject, $owner_email_template);

            /* mail to administrator */
            $admin_email = get_option('admin_email');
            $get_admin_email_template = $GeneralThemeObject->getEmailContents('mail-to-admin-for-deal-finalization', ['{%user_name%}', '{%deal_id%}', '{%total_price%}', '{%status%}', '{%date%}'], [$userDetails->data['fname'] . ' ' . $userDetails->data['lname'], $dealDetails->data['deal_id'], $dealDetails->data['total_price'], $dealDetails->data['status'], $dealDetails->data['initiated']]);
            $admin_mail_subject = get_bloginfo('name') . ' :: ' . $get_admin_email_template[0];
            $admin_mail_cont = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $get_admin_email_template[1]);
            $GeneralThemeObject->send_mail_func($admin_email, $admin_mail_subject, $admin_mail_cont);

            if ($userDetails->data['pro_pic_exists'] == TRUE) {
                $user_pro_pic = wp_get_attachment_image_src($userDetails->data['pro_pic'], 'full');
                $userImg = $user_pro_pic[0];
            } else {
                $userImg = 'https://via.placeholder.com/75x75';
            }

            /* mail to suppier */
            if (is_array($supplierMailDetails) && count($supplierMailDetails) > 0) {
                foreach ($supplierMailDetails as $eachSuplierMailKey => $eachSuplierMailVal) {
                    $getSupplierDetails = $GeneralThemeObject->user_details($eachSuplierMailKey);
                    $generateSupplierEmailProductData = $FinalizeObject->generateSupplierEmailProductData($dealDetails->data['deal_id'], $eachSuplierMailVal, $deal_name, $deal_description);
                    if ($userDetails->data['supplier_type'] == 1) {
                        $cpfVal = 'CPF No: ' . $userDetails->data['cpf'];
                    } elseif ($userDetails->data['supplier_type'] == 2) {
                        $cpfVal = 'CNPJ No: ' . $userDetails->data['cnpj'];
                    }
                    $get_supplier_email_template = $GeneralThemeObject->getEmailContents('mail-to-supplier-for-new-deal-finalize', ['{%supplier_name%}', '{%deal_id%}', '{%deal_name%}', '{%deal_desc%}', '{%deal_items%}', '{%user_image%}', '{%user_name%}', '{%cpf%}', '{%user_email%}', '{%user_state%}', '{%user_phone%}'], [$getSupplierDetails->data['fname'], $dealDetails->data['deal_id'], $deal_name, $deal_description, $generateSupplierEmailProductData, $userImg, $userDetails->data['fname'] . ' ' . $userDetails->data['lname'], $cpfVal, $userDetails->data['email'], ($userDetails->data['address']) ? $userDetails->data['address'] : ' Address not provided ', ($userDetails->data['phone']) ? $userDetails->data['phone'] : ' - ']);
                    $supplier_mail_subject = get_bloginfo('name') . ' :: ' . $get_supplier_email_template[0];
                    $supplier_mail_cont = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $get_supplier_email_template[1]);
                    $GeneralThemeObject->send_mail_func($getSupplierDetails->data['email'], $supplier_mail_subject, $supplier_mail_cont);
                }
            } else {
                $msg = __('Database error.', THEME_TEXTDOMAIN);
            }
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

add_action('wp_ajax_finalize_deal_update', 'ajaxFinalizeDealUpdate');

if (!function_exists('ajaxFinalizeDealUpdate')) {

    function ajaxFinalizeDealUpdate() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $FinalizeData = new classFinalize();
        $msg = NULL;
        $deal_id = base64_decode($_POST['deal_id']);
        $deal_name = $_POST['deal_name'];
        $deal_description = $_POST['deal_description'];
        $getDealDetails = $FinalizeData->getDealDetails($deal_id);

        if (empty($deal_id)) {
            $msg = __('Deal ID missing.', THEME_TEXTDOMAIN);
        } else if (is_array($getDealDetails) && count($getDealDetails) == 0) {
            $msg = __('Deal not found with this ID', THEME_TEXTDOMAIN);
        } else {

            $updatedDealData = [
                'deal_name' => $deal_name,
                'deal_description' => $deal_description
            ];

            $whereData = [
                'deal_id' => $deal_id
            ];

            $updatedData = $FinalizeData->updateDealFinalizeData($updatedDealData, $whereData);
            if ($updatedData) {
                $resp_arr['flag'] = TRUE;
                $msg = __('Deal name and description updated.', THEME_TEXTDOMAIN);
            } else {
                $msg = __('Deal name or description need to be changed to update.', THEME_TEXTDOMAIN);
            }
        }


        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}