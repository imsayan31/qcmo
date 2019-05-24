<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * --------------------------------------------
 * AJAX:: Ajax Function
 * --------------------------------------------
 */

add_action('wp_ajax_admin_change_deal_review_status', 'ajaxAdminChangeDealReviewStatus');

if (!function_exists('ajaxAdminChangeDealReviewStatus')) {

    function ajaxAdminChangeDealReviewStatus() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $ReviewObject = new classReviewRating();
        $msg = NULL;
        $status_val = $_POST['status_val'];
        $deal = $_POST['deal'];
        $user = $_POST['user'];
        $supplier = $_POST['supplier'];

        $updatedData = [
            'rating_status' => $status_val
        ];

        $whereData = [
            'deal_id' => $deal,
            'user_id' => $user,
            'supplier_id' => $supplier
        ];

        $updatedRow = $ReviewObject->updateReviewRating($updatedData, $whereData);

        if ($updatedRow) {
            $resp_arr['flag'] = TRUE;
            $resp_arr['url'] = (get_current_user_id() == 1) ? admin_url() . 'admin.php?page=deal-reviews' : admin_url() . 'admin.php?page=sub-admin-deal-reviews';
        } else {
            $msg = 'Data can not be updated.';
        }
        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}