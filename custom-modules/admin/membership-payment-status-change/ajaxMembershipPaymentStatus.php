<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * --------------------------------------------
 * AJAX:: Membership Payment Status
 * --------------------------------------------
 */

add_action('wp_ajax_membership_payment_status_change', 'ajaxMembershipPaymentStatusChange');

if (!function_exists('ajaxMembershipPaymentStatusChange')) {

    function ajaxMembershipPaymentStatusChange() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $MembershipObject = new classMemberShip();
        $msg = NULL;
        $order = $_POST['order'];
        $status = $_POST['status'];

        $updateData = [
            'payment_status' => $status
        ];

        $whereData = [
            'order_id' => $order
        ];
        $MembershipObject->updateMembershipData($updateData, $whereData);
        $resp_arr['flag'] = TRUE;

        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}