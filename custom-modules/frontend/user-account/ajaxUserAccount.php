<?php

/*
 * --------------------------------------------
 * AJAX:: User Account Update
 * --------------------------------------------
 */

add_action('wp_ajax_productor_account_update', 'ajaxUserAccountUpdate');

if (!function_exists('ajaxUserAccountUpdate')) {

    function ajaxUserAccountUpdate() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $userDetails = $GeneralThemeObject->user_details();
        $msg = NULL;
        $fname = strip_tags(trim($_POST['fname']));
        $lname = strip_tags(trim($_POST['lname']));
        $email = strip_tags(trim($_POST['email']));
        $cphone = strip_tags(trim($_POST['cphone']));
        $phone = strip_tags(trim($_POST['phone']));
        $dob = strip_tags(trim($_POST['dob']));
        $cpf = strip_tags(trim($_POST['cpf']));
        $cnpj = strip_tags(trim($_POST['cnpj']));
        $supplier_type = $_POST['supplier_type'];
       
        $genre = $_POST['genre'];
        $address = strip_tags(trim($_POST['address']));
        $addressloc = strip_tags(trim($_POST['addressloc']));
        $addressID = strip_tags(trim($_POST['addressID']));
        $user_logo = $_FILES['user_logo'];
        $file_type_arr = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $CPFObject = new ValidaCPFCNPJ($cpf);
        $CNPJObject = new ValidaCPFCNPJ($cnpj);
        $cpfValidation = $CPFObject->valida();
        $cpnjValidation = $CNPJObject->valida();

        if ($user_logo['name'] != '' && !in_array($user_logo['type'], $file_type_arr)) {
            $msg = __('Your profile picture should be an image file.', THEME_TEXTDOMAIN);
        } else if (empty($fname)) {
            $msg = __('Enter your first name.', THEME_TEXTDOMAIN);
        } 
        /*elseif (!ctype_alpha($fname)) {
            $msg = __('First name only contains alphabets.', THEME_TEXTDOMAIN);
        } */
        elseif (empty($lname)) {
            $msg = __('Enter your last name.', THEME_TEXTDOMAIN);
        } 
        /*elseif (!ctype_alpha($lname)) {
            $msg = __('Last name only contains alphabets.', THEME_TEXTDOMAIN);
        }*/
         elseif (empty($email)) {
            $msg = __('Enter your mail.', THEME_TEXTDOMAIN);
        } else if (!is_email($email)) {
            $msg = __('Email is not in proper format.', THEME_TEXTDOMAIN);
        } elseif (empty($state)) {
            $msg = __('Select your state.', THEME_TEXTDOMAIN);
        } elseif (empty($city)) {
            $msg = __('Select your city.', THEME_TEXTDOMAIN);
        } /*else if (!$userDetails->data['supplier_type'] && empty($supplier_type)) {
            $msg = __('Select your person type.', THEME_TEXTDOMAIN);
        }*/ else if (!$userDetails->data['supplier_type'] && $supplier_type == 1 && empty($cpf)) {
            $msg = __('Enter your CPF.', THEME_TEXTDOMAIN);
        } else if (!$userDetails->data['supplier_type'] && $supplier_type == 2 && empty($cnpj)) {
            $msg = __('Enter your CNPJ.', THEME_TEXTDOMAIN);
        } elseif ($userDetails->data['supplier_type'] && $supplier_type == 1 && $cpfValidation == FALSE) {
            $msg = 'CPF is not valid. Try again.';
        } elseif ($userDetails->data['supplier_type'] && $supplier_type == 2 && $cpnjValidation == FALSE) {
            $msg = 'CNPJ is not valid. Try again.';
        } else {
            wp_update_user([
                'ID' => $userDetails->data['user_id'],
                'display_name' => $fname . ' ' . $lname
            ]);

            if ($user_logo['name'] != '') {
                $user_pro_img_id = $GeneralThemeObject->common_file_upload($user_logo);
                $save_user_img_id = $GeneralThemeObject->create_attachment($user_pro_img_id);
            } else {
                $save_user_img_id = get_user_meta($userDetails->data['user_id'], '_pro_pic', true);
            }

            /* save user meta data */
            update_user_meta($userDetails->data['user_id'], 'first_name', $fname);
            update_user_meta($userDetails->data['user_id'], 'last_name', $lname);
            update_user_meta($userDetails->data['user_id'], '_city', $city);
            update_user_meta($userDetails->data['user_id'], '_state', $state);
            update_user_meta($userDetails->data['user_id'], '_commercial_no', $cphone);
            update_user_meta($userDetails->data['user_id'], '_mobile_no', $phone);
            update_user_meta($userDetails->data['user_id'], '_pro_pic', $save_user_img_id);
            update_user_meta($userDetails->data['user_id'], '_dob', $dob);
            update_user_meta($userDetails->data['user_id'], '_genre', $genre);
            update_user_meta($userDetails->data['user_id'], '_address', $address);
            update_user_meta($userDetails->data['user_id'], '_addressLoc', $addressloc);
            update_user_meta($userDetails->data['user_id'], '_addressID', $addressID);
         
                update_user_meta($userDetails->data['user_id'], '_cpf', $cpf);
                update_user_meta($userDetails->data['user_id'], '_cnpj', $cnpj);
                update_user_meta($userDetails->data['user_id'], '_supplier_type',  $supplier_type);
            

            /* Google data insert */
            $explodedAddressLoc = explode(',', $addressloc);
            $googleDataArr = [
                'place_id' => $addressID,
                'address' => $address,
                'lat' => $explodedAddressLoc[0],
                'lng' => $explodedAddressLoc[1],
            ];
            $GeneralThemeObject->insertGoogleLocation($googleDataArr);

            $GeneralThemeObject->setLandingCity($state, $city);

            $resp_arr['flag'] = TRUE;
            $msg = __('Your account has been updated successfully.', THEME_TEXTDOMAIN);
        }

        $resp_arr['msg'] = $msg;
       echo json_encode($resp_arr);
        exit;
    }

}

/*
 * --------------------------------------------
 * AJAX:: Supplier Account Update
 * --------------------------------------------
 */

add_action('wp_ajax_supplier_account_update', 'ajaxSupplierAccountUpdate');

if (!function_exists('ajaxSupplierAccountUpdate')) {

    function ajaxSupplierAccountUpdate() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $userDetails = $GeneralThemeObject->user_details();
        $msg = NULL;
        $fname = strip_tags(trim($_POST['fname']));
        $lname = strip_tags(trim($_POST['lname']));
        $email = strip_tags(trim($_POST['email']));
        $phone = strip_tags(trim($_POST['phone']));
        $address = strip_tags(trim($_POST['address']));
        $addressloc = strip_tags(trim($_POST['addressloc']));
        $addressID = strip_tags(trim($_POST['addressID']));
        $cpf = strip_tags(trim($_POST['cpf']));
        $cnpj = strip_tags(trim($_POST['cnpj']));
        $bio = strip_tags(trim($_POST['bio']));
        $where_to_buy_address = strip_tags(trim($_POST['where_to_buy_address']));
        $user_logo = $_FILES['user_logo'];
        $file_type_arr = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $supplier_category = $_POST['supplier_category'];
        $supplier_type = $_POST['supplier_type'];
        $CPFObject = new ValidaCPFCNPJ($cpf);
        $CNPJObject = new ValidaCPFCNPJ($cnpj);
        $cpfValidation = $CPFObject->valida();
        $cpnjValidation = $CNPJObject->valida();

        if ($user_logo['name'] != '' && !in_array($user_logo['type'], $file_type_arr)) {
            $msg = __('Your profile picture should be an image file.', THEME_TEXTDOMAIN);
        } else if (empty($fname)) {
            $msg = __('Enter your commercial name.', THEME_TEXTDOMAIN);
        } 
        /*elseif (!ctype_alpha($fname)) {
            $msg = __('Commercial name only contains alphabets.', THEME_TEXTDOMAIN);
        }*/
         elseif (empty($lname)) {
          $msg = __('Enter your last name.', THEME_TEXTDOMAIN);
        } 
        /*elseif (!ctype_alpha($lname)) {
            $msg = __('Legal name only contains alphabets.', THEME_TEXTDOMAIN);
        } */
        elseif (empty($email)) {
            $msg = __('Enter your mail.', THEME_TEXTDOMAIN);
        } else if (!is_email($email)) {
            $msg = __('Email is not in proper format.', THEME_TEXTDOMAIN);
        } elseif ($userDetails->data['supplier_type'] && $supplier_type == 1 && $cpfValidation == FALSE) {
          $msg = 'CPF  is not valid. Try again.';
        } elseif ($userDetails->data['supplier_type'] && $supplier_type == 2 && $cpnjValidation == FALSE) {
          $msg = 'CNPJ is not valid. Try again.';
        } elseif (empty($state)) {
            $msg = __('Select your state.', THEME_TEXTDOMAIN);
        } elseif (empty($city)) {
            $msg = __('Select your city.', THEME_TEXTDOMAIN);
        } elseif (empty($address)) {
            $msg = __('Enter your address.', THEME_TEXTDOMAIN);
        } elseif (empty($where_to_buy_address)) {
            $msg = __('Enter your company website.', THEME_TEXTDOMAIN);
        } elseif (empty($supplier_category)) {
            $msg = __('Select your category you will supply.', THEME_TEXTDOMAIN);
        } else {
            wp_update_user([
                'ID' => $userDetails->data['user_id'],
                'display_name' => $fname
            ]);

            if ($user_logo['name'] != '') {
                $user_pro_img_id = $GeneralThemeObject->common_file_upload($user_logo);
                $save_user_img_id = $GeneralThemeObject->create_attachment($user_pro_img_id);
            } else {
                $save_user_img_id = get_user_meta($userDetails->data['user_id'], '_pro_pic', true);
            }

            if(is_array($supplier_category) && count($supplier_category) > 0){
                foreach ($supplier_category as $eachCat) {
                    $getProCatDet = get_term_by('id', $eachCat, themeFramework::$theme_prefix . 'product_category');
                    if($getProCatDet->parent != 0 && !in_array($getProCatDet->parent, $supplier_category)){
                        $supplier_category[] = $getProCatDet->parent;
                    }
                }
            }

            // echo "<pre>";
            // print_r($supplier_category);
            // echo "</pre>";
            // exit;

            /* save user meta data */
            update_user_meta($userDetails->data['user_id'], 'first_name', $fname);
            update_user_meta($userDetails->data['user_id'], 'last_name', $lname);
            update_user_meta($userDetails->data['user_id'], '_city', $city);
            update_user_meta($userDetails->data['user_id'], '_state', $state);
            update_user_meta($userDetails->data['user_id'], '_cpf', $cpf);
            update_user_meta($userDetails->data['user_id'], '_cnpj', $cnpj);
            update_user_meta($userDetails->data['user_id'], '_pro_pic', $save_user_img_id);
            update_user_meta($userDetails->data['user_id'], '_supplier_categories', $supplier_category);
            update_user_meta($userDetails->data['user_id'], '_user_address', $address);
            update_user_meta($userDetails->data['user_id'], '_addressLoc', $addressloc);
            update_user_meta($userDetails->data['user_id'], '_addressID', $addressID);
            update_user_meta($userDetails->data['user_id'], '_where_to_buy_address', $where_to_buy_address);
            update_user_meta($userDetails->data['user_id'], '_bio', $bio);
            update_user_meta($userDetails->data['user_id'], '_supplier_type',  $supplier_type);

            /* Google data insert */
            $explodedAddressLoc = explode(',', $addressloc);
            $googleDataArr = [
                'place_id' => $addressID,
                'address' => $address,
                'lat' => $explodedAddressLoc[0],
                'lng' => $explodedAddressLoc[1],
            ];
            $GeneralThemeObject->insertGoogleLocation($googleDataArr);

            $GeneralThemeObject->setLandingCity($state, $city);

            $resp_arr['flag'] = TRUE;
            $msg = __('Your account has been updated successfully.', THEME_TEXTDOMAIN);
        }

        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}


add_action('wp_ajax_delete_account', 'ajaxAccountDelete');

if (!function_exists('ajaxAccountDelete')) {

    function ajaxAccountDelete() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $userDetails = $GeneralThemeObject->user_details();
        
        $user_id = strip_tags(trim($_POST['user_id'])); ;
        wp_delete_user( $user_id);
    }
    
}