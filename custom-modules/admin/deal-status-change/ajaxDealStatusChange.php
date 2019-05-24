<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * --------------------------------------------
 * AJAX:: Deal Status Change
 * --------------------------------------------
 */

add_action('wp_ajax_deal_status_change', 'ajaxDealStatusChange');

if (!function_exists('ajaxDealStatusChange')) {

    function ajaxDealStatusChange() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $FinalizeDealObj = new classFinalize();
        $msg = NULL;
        $deal = $_POST['deal'];
        $status = $_POST['status'];
        
        if($status == 1){
            $dealUpdateDate = strtotime(date('Y-m-d'));
        } else{
            $dealUpdateDate = '';
        }

        $updateData = [
            'deal_status' => $status,
            'deal_completed_on' => $dealUpdateDate
        ];
        $whereData = [
            'deal_id' => $deal
        ];
        $updateDealData = $FinalizeDealObj->updateDealFinalizeData($updateData, $whereData);

        if ($updateDealData) {
            $resp_arr['flag'] = TRUE;
            $msg = 'Status do orçamento atualizado.';

            $dealDetails = $FinalizeDealObj->getDealDetails($deal);
            $userDetails = $GeneralThemeObject->user_details($dealDetails->data['user_id']);

            /* Sending email to User */
            $get_forgot_password_email_template = $GeneralThemeObject->getEmailContents('mail-to-user-for-deal-status-change', ['{%user_name%}', '{%deal_id%}', '{%deal_status%}'], [$userDetails->data['fname'] . ' ' . $userDetails->data['lname'], $dealDetails->data['deal_id'], $dealDetails->data['status']]);
            $owner_mail_subject = get_bloginfo('name') . ' :: ' . $get_forgot_password_email_template[0];
            $owner_email_template = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $get_forgot_password_email_template[1]);
            $GeneralThemeObject->send_mail_func($userDetails->data['email'], $owner_mail_subject, $owner_email_template);
        }

        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}