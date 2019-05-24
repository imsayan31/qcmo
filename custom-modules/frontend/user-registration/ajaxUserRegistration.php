<?php

/*
 * --------------------------------------------
 * AJAX:: User Registration
 * --------------------------------------------
 */

add_action('wp_ajax_productor_registration', 'ajaxUserRegistration');
add_action('wp_ajax_nopriv_productor_registration', 'ajaxUserRegistration');

if (!function_exists('ajaxUserRegistration')) {

    function ajaxUserRegistration() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        
        $msg = NULL;
        $fname = strip_tags(trim($_POST['fname']));
        $lname = strip_tags(trim($_POST['lname']));
        $email = strip_tags(trim($_POST['email']));
        $phone = strip_tags(trim($_POST['phone']));
        $address = strip_tags(trim($_POST['address']));
        $state = $_POST['state'];
        $city = $_POST['city'];
        $cpf = strip_tags(trim($_POST['cpf']));
        $cnpj = strip_tags(trim($_POST['cnpj']));
        $supplier_type = $_POST['supplier_type'];
        $password = strip_tags(trim($_POST['password']));
        $cnfpassword = strip_tags(trim($_POST['cnfpassword']));
        $passwordValidation = $GeneralThemeObject->passwordValidation($password);
        $CPFObject = new ValidaCPFCNPJ($cpf);
        $CNPJObject = new ValidaCPFCNPJ($cnpj);
        $cpfValidation = $CPFObject->valida();
        $cpnjValidation = $CNPJObject->valida();

        if (empty($fname)) {
            $msg = __('Enter your first name.', THEME_TEXTDOMAIN);
        } /*elseif (!ctype_alpha($fname)) {
            $msg = __('First name only contains alphabets.', THEME_TEXTDOMAIN);
        } */elseif (empty($lname)) {
            $msg = __('Enter your last name.', THEME_TEXTDOMAIN);
        } /*elseif (!ctype_alpha($lname)) {
            $msg = __('Last name only contains alphabets.', THEME_TEXTDOMAIN);
        } */elseif (empty($email)) {
            $msg = __('Enter your mail.', THEME_TEXTDOMAIN);
        } else if (!is_email($email)) {
            $msg = __('Email is not in proper format.', THEME_TEXTDOMAIN);
        } elseif (email_exists($email)) {
            $msg = __('Email already used by another user. Try another.', THEME_TEXTDOMAIN);
        } /*elseif (empty($supplier_type)) {
            $msg = __('Select your type.', THEME_TEXTDOMAIN);
        } elseif (($supplier_type == 1) && ($cpfValidation == FALSE)) {
            $msg = __('CPF is not valid. Try again.', THEME_TEXTDOMAIN);
        } elseif (($supplier_type == 2) && ($cpnjValidation == FALSE)) {
            $msg = __('CNPJ is not valid. Try again.', THEME_TEXTDOMAIN);
        } */elseif (empty($state)) {
            $msg = __('Select your state.', THEME_TEXTDOMAIN);
        } elseif (empty($city)) {
            $msg = __('Select your city.', THEME_TEXTDOMAIN);
        } elseif (empty($password)) {
            $msg = __('Enter password.', THEME_TEXTDOMAIN);
        } elseif (strlen($password) < 8) {
            $msg = __('Password length should be minimum 8 characters.', THEME_TEXTDOMAIN);
        }
        /* elseif ($passwordValidation == 0) {
          $msg = 'Password should contain one Upper case letter.';
          } */ elseif (strcmp($password, $cnfpassword) != 0) {
            $msg = __('Confirm your password with original one.', THEME_TEXTDOMAIN);
        } else {
            /* create user */
            $userID = wp_create_user($email, $password, $email);

            wp_update_user([
                'ID' => $userID,
                'display_name' => $fname
            ]);

            $activeCode = $GeneralThemeObject->generateRandomString(6);

            $activationLink = BASE_URL . '?actv_code=' . $activeCode;

            /* save user meta data */
            update_user_meta($userID, 'first_name', $fname);
            update_user_meta($userID, 'last_name', $lname);
            update_user_meta($userID, '_state', $state);
            update_user_meta($userID, '_city', $city);
            update_user_meta($userID, '_active_status', 2);
            update_user_meta($userID, '_active_code', $activeCode);
            update_user_meta($userID, '_activation_url', $activationLink);
            update_user_meta($userID, '_mobile_no', $phone);
            update_user_meta($userID, '_user_address', $address);
            update_user_meta($userID, '_receive_deals', 1);
            update_user_meta($userID, '_allow_where_to_buy', 1);
            update_user_meta($userID, '_cpf', $cpf);
            update_user_meta($userID, '_cnpj', $cnpj);
            update_user_meta($userID, '_supplier_type', $supplier_type);

            $stateDetails = get_term_by('id', $state, themeFramework::$theme_prefix . 'product_city');
            $cityDetails = get_term_by('id', $city, themeFramework::$theme_prefix . 'product_city');

            $GeneralThemeObject->setLandingCity($state, $city);
            //$GeneralThemeObject->auto_wp_login($email);


            $resp_arr['flag'] = TRUE;
            $msg = __('Thank you for registration. Check your mailbox to activate your account.', THEME_TEXTDOMAIN);
            $resp_arr['url'] = BASE_URL;

            /* mail to user */
            $get_seller_email_template = $GeneralThemeObject->getEmailContents('mail-to-user-for-registration', ['{%user%}', '{%activation_link%}'], [$fname . ' ' . $lname, $activationLink]);
           
            $mail_subject = get_bloginfo('name') . ' :: ' . $get_seller_email_template[0];
            $mail_cont = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $get_seller_email_template[1]);
            $GeneralThemeObject->send_mail_func($email, $mail_subject, $mail_cont);

            /* mail to administrator */
            $admin_email = get_option('admin_email');
            $get_admin_email_template = $GeneralThemeObject->getEmailContents('mail-to-admin-for-user-registration', ['{%user%}', '{%email%}', '{%state%}', '{%city%}'], [$fname . ' ' . $lname, $email, $stateDetails->name, $cityDetails->name]);
            $admin_mail_subject = get_bloginfo('name') . ' :: ' . $get_admin_email_template[0];
            $admin_mail_cont = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $get_admin_email_template[1]);
            $GeneralThemeObject->send_mail_func($admin_email, $admin_mail_subject, $admin_mail_cont);
        }

        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}

/*
 * --------------------------------------------
 * AJAX:: Supplier Registration
 * --------------------------------------------
 */

add_action('wp_ajax_supplier_registration', 'ajaxSupplierRegistration');
add_action('wp_ajax_nopriv_supplier_registration', 'ajaxSupplierRegistration');

if (!function_exists('ajaxSupplierRegistration')) {

    function ajaxSupplierRegistration() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $msg = NULL;
        $fname = strip_tags(trim($_POST['fname']));
        $lname = strip_tags(trim($_POST['lname']));
        $email = strip_tags(trim($_POST['email']));
        $phone = strip_tags(trim($_POST['phone']));
        $cpf = strip_tags(trim($_POST['cpf']));
        $cnpj = strip_tags(trim($_POST['cnpj']));
        $state = $_POST['state'];
        $city = $_POST['city'];
        $supplier_category = $_POST['supplier_category'];
        $password = strip_tags(trim($_POST['password']));
        $cnfpassword = strip_tags(trim($_POST['cnfpassword']));
        $getMembershipPlan = $GeneralThemeObject->getMembershipPlans('DESC', 1);
        $getPlanDetails = $GeneralThemeObject->getMembershipPlanDetails($getMembershipPlan[0]->ID);
        $CPFObject = new ValidaCPFCNPJ($cpf);
        $CNPJObject = new ValidaCPFCNPJ($cnpj);
        $cpfValidation = $CPFObject->valida();
        $cpnjValidation = $CNPJObject->valida();
        $supplier_type = $_POST['supplier_type'];
        $address = $_POST['address'];
        $supplier_address_loc = $_POST['supplier_address_loc'];
        $supplier_address_id = $_POST['supplier_address_id'];

        if (empty($fname)) {
            $msg = __('Enter your commercial name.', THEME_TEXTDOMAIN);
        } 
        /*elseif (!ctype_alpha($fname)) {
            $msg = __('Commercial name only contains alphabets.', THEME_TEXTDOMAIN);
        } */
        elseif (empty($lname)) {
            $msg = 'Enter your legal name.';
        }  
        /*elseif ($lname && !ctype_alpha($lname)) {
            $msg = __('Legal name only contains alphabets.', THEME_TEXTDOMAIN);
        }*/elseif (empty($email)) {
            $msg = __('Enter your mail.', THEME_TEXTDOMAIN);
        } else if (!is_email($email)) {
            $msg = __('Email is not in proper format.', THEME_TEXTDOMAIN);
        } elseif (email_exists($email)) {
            $msg = __('Email already used by another user. Try another.', THEME_TEXTDOMAIN);
        } elseif (empty($supplier_type)) {
            $msg = __('Select your type.', THEME_TEXTDOMAIN);
        } elseif (($supplier_type == 1) && ($cpfValidation == FALSE)) {
            $msg = __('CPF is not valid. Try again.', THEME_TEXTDOMAIN);
        } elseif (($supplier_type == 2) && ($cpnjValidation == FALSE)) {
            $msg = __('CNPJ is not valid. Try again.', THEME_TEXTDOMAIN);
        } elseif (empty($state)) {
            $msg = __('Select your state.', THEME_TEXTDOMAIN);
        } elseif (empty($city)) {
            $msg = __('Select your city.', THEME_TEXTDOMAIN);
        } elseif (empty($address)) {
            $msg = __('Enter your address.', THEME_TEXTDOMAIN);
        } elseif (empty($supplier_category)) {
            $msg = __('Select your categories you supply.', THEME_TEXTDOMAIN);
        } elseif (empty($password)) {
            $msg = __('Enter password.', THEME_TEXTDOMAIN);
        } elseif (strlen($password) < 8) {
            $msg = __('Password length should be minimum 8 characters.', THEME_TEXTDOMAIN);
        } elseif (strcmp($password, $cnfpassword) != 0) {
            $msg = __('Confirm your password with original one.', THEME_TEXTDOMAIN);
        } else {
            /* create user */
            $userID = wp_create_user($email, $password, $email);
            $getUserDetails = new WP_User($userID);
            $getUserDetails->remove_role('subscriber');
            $getUserDetails->add_role('supplier');

            wp_update_user([
                'ID' => $userID,
                'display_name' => $fname . ' ' . $lname
            ]);

            $activeCode = $GeneralThemeObject->generateRandomString(6);

            $activationLink = BASE_URL . '?supplier_actv_code=' . $activeCode;

            $deals_from_date = date('d-m-Y');
            $deals_to_date = date('d-m-Y', strtotime("+20 years"));

            /* save user meta data */
            update_user_meta($userID, 'first_name', $fname);
            update_user_meta($userID, 'last_name', $lname);
            update_user_meta($userID, '_state', $state);
            update_user_meta($userID, '_city', $city);
            update_user_meta($userID, '_supplier_categories', $supplier_category);
            update_user_meta($userID, '_active_status', 2);
            update_user_meta($userID, '_active_code', $activeCode);
            update_user_meta($userID, '_activation_url', $activationLink);
            update_user_meta($userID, '_mobile_no', $phone);
            update_user_meta($userID, '_selected_plan', $getPlanDetails->data['ID']);
            update_user_meta($userID, '_selected_start_date', strtotime(date('Y-m-d')));
            update_user_meta($userID, '_selected_plan_payment_status', 1);
            update_user_meta($userID, '_cpf', $cpf);
            update_user_meta($userID, '_cnpj', $cnpj);
            update_user_meta($userID, '_supplier_type', $supplier_type);
            update_user_meta($userID, '_user_address', $address);
            update_user_meta($userID, '_addressLoc', $supplier_address_loc);
            update_user_meta($userID, '_addressID', $supplier_address_id);
            update_user_meta($user_id, '_receive_deals', 1);
            update_user_meta($user_id, '_deals_from_date', $deals_from_date);
            update_user_meta($user_id, '_deals_to_date', $deals_to_date);
                
            $stateDetails = get_term_by('id', $state, themeFramework::$theme_prefix . 'product_city');
            $cityDetails = get_term_by('id', $city, themeFramework::$theme_prefix . 'product_city');

            $GeneralThemeObject->setLandingCity($state, $city);
            
            /* Google data insert */
            $explodedAddressLoc = explode(',', $supplier_address_loc);
            $googleDataArr = [
                'place_id' => $supplier_address_id,
                'address' => $address,
                'lat' => $explodedAddressLoc[0],
                'lng' => $explodedAddressLoc[1],
            ];
            $GeneralThemeObject->insertGoogleLocation($googleDataArr);


            $resp_arr['flag'] = TRUE;
            $msg = __('Thank you for registration. Check your mailbox to activate your account.', THEME_TEXTDOMAIN);
            $resp_arr['url'] = BASE_URL;

            /* mail to user */
            $get_seller_email_template = $GeneralThemeObject->getEmailContents('mail-to-supplier-for-registration', ['{%user%}', '{%activation_link%}'], [$fname, $activationLink]);
            $mail_subject = get_bloginfo('name') . ' :: ' . $get_seller_email_template[0];
            $mail_cont = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $get_seller_email_template[1]);
            $GeneralThemeObject->send_mail_func($email, $mail_subject, $mail_cont);

            /* mail to administrator */
            $admin_email = get_option('admin_email');
            $get_admin_email_template = $GeneralThemeObject->getEmailContents('mail-to-admin-for-supplier-registration', ['{%user%}', '{%email%}', '{%state%}', '{%city%}'], [$fname . ' ' . $lname, $email, $stateDetails->name, $cityDetails->name]);
            $admin_mail_subject = get_bloginfo('name') . ' :: ' . $get_admin_email_template[0];
            $admin_mail_cont = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $get_admin_email_template[1]);
            $GeneralThemeObject->send_mail_func($admin_email, $admin_mail_subject, $admin_mail_cont);
        }

        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }
}

add_action('wp_ajax_delete_user', 'ajaxUserDelete');
    
    function ajaxUserDelete(){
        $resp_arr = ['msg'=>'Eliminou com sucesso sua conta','flag'=>true,'url'=>BASE_URL];
        $userId = $_POST['postId'];
        $delete = wp_delete_user($userId);
        echo json_encode($resp_arr);
        exit();
    }