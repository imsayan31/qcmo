<?php

/*
 * --------------------------------------------
 * AJAX:: Ajax Supplier Deal Settings
 * --------------------------------------------
 */

add_action('wp_ajax_supplier_deal_settings', 'ajaxSupplierDealSettings');

if (!function_exists('ajaxSupplierDealSettings')) {

    function ajaxSupplierDealSettings() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $msg = NULL;
        $supplier_deal_min_val_set = strip_tags(trim($_POST['supplier_deal_min_val_set']));
        $supplier_deal_min_val = strip_tags(trim($_POST['supplier_deal_min_val']));
        $user_cmplt_addrs = $_POST['user_cmplt_addrs'];
        $user_com_mob = $_POST['user_com_mob'];
        $user_cpf_cnpj = $_POST['user_cpf_cnpj'];
        $userDetails = $GeneralThemeObject->user_details();

        if (empty($supplier_deal_min_val_set)) {
            $msg = __('Select deal minimum value for settings.', THEME_TEXTDOMAIN);
        } elseif (empty($supplier_deal_min_val)) {
            $msg = __('Select minimum value to accept deals.', THEME_TEXTDOMAIN);
        } 
        /*else if (empty($user_cmplt_addrs)) {
            $msg = 'Check user address to receive deals.';
        } else if (empty($user_com_mob)) {
            $msg = 'Check user commercial/mobile number to receive deals.';
        } else if (empty($user_cpf_cnpj)) {
            $msg = 'Check user cpf/cnpj to receive deals.';
        }*/
        else {

            update_user_meta($userDetails->data['user_id'], '_minimum_deal_amount_set', $supplier_deal_min_val_set);
            update_user_meta($userDetails->data['user_id'], '_minimum_deal_amount', $supplier_deal_min_val);
            update_user_meta($userDetails->data['user_id'], '_check_user_address', $user_cmplt_addrs);
            update_user_meta($userDetails->data['user_id'], '_check_user_contact_no', $user_com_mob);
            update_user_meta($userDetails->data['user_id'], '_check_user_cpf_cnpj', $user_cpf_cnpj);
            $resp_arr['flag'] = TRUE;
            $msg = __('Your deal settings data successfully saved.', THEME_TEXTDOMAIN);
        }


        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}