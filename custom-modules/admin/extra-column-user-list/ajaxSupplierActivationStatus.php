<?php

/*
 * --------------------------------------------
 * AJAX:: Change Supplier Status
 * --------------------------------------------
 */

add_action('wp_ajax_supplier_status_change', 'ajaxSupplierActivationStatus');

if (!function_exists('ajaxSupplierActivationStatus')) {

    function ajaxSupplierActivationStatus() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $msg = NULL;
        $this_val = $_POST['this_val'];
        $this_user = $_POST['this_user'];
        $this_user_details = $GeneralThemeObject->user_details($this_user);

        if ($this_val == 1) {
            $approvalStatus = 'activated';
        } else if ($this_val == 2) {
            $approvalStatus = 'deactivated';
        }

        update_user_meta($this_user, '_admin_approval', $this_val);
        $get_seller_activation_email_template = $GeneralThemeObject->getEmailContents('mail-to-supplier-for-admin-approval', ['{%user%}', '{%approval_status%}'], [$this_user_details->data['fname'], $approvalStatus]);
        $mail_subject = get_bloginfo('name') . ' :: ' . $get_seller_activation_email_template[0];
        $mail_cont = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $get_seller_activation_email_template[1]);
        $GeneralThemeObject->send_mail_func($this_user_details->data['email'], $mail_subject, $mail_cont);
        $msg = 'Supplier status updated.';
        $resp_arr['flag'] = TRUE;
        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}

/*
 * -----------------------------------------------
 * AJAX:: Ajax Supplier Membership Status Change
 * -----------------------------------------------
 */

add_action('wp_ajax_supplier_membership_status_change', 'ajaxSupplierMembershipStatusChange');

if (!function_exists('ajaxSupplierMembershipStatusChange')) {

    function ajaxSupplierMembershipStatusChange() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $MemberShipObject = new classMemberShip();
        $msg = NULL;
        $this_val = $_POST['this_val'];
        $this_user = $_POST['this_user'];
        $getPlanDetails = $GeneralThemeObject->getMembershipPlanDetails($this_val);
        $planLimitDate = 3;
        $planStartDate = strtotime(date('Y-m-d'));
        $planEndDate = strtotime('+' . $planLimitDate . ' months', strtotime(date('Y-m-d')));
        $planNextPaymentDate = $planEndDate;

        $getUserMembershipData = $MemberShipObject->getUserMembershipDetails($this_user);
        $user_details = $GeneralThemeObject->user_details($this_user);

        $userPreviousPlan = $user_details->data['selected_plan'];

        /* updated data */
        $updatedData = [
            'total_price' => $getPlanDetails->data['quarterly_price'],
            'plan_id' => $this_val,
            'plan_period' => $planLimitDate,
            'payment_status' => 1,
            'payment_date' => strtotime(date('Y-m-d')),
            'next_payment_date' => $planNextPaymentDate,
            'plan_start_date' => $planStartDate,
            'plan_end_date' => $planEndDate
        ];

        /* where data */
        $whereData = [
            'order_id' => $getUserMembershipData[0]->order_id,
            'user_id' => $user_details->data['user_id'],
        ];

        $updateMembershipData = $MemberShipObject->updateMembershipData($updatedData, $whereData);

        /* Update user meta */
        update_user_meta($this_user, '_selected_plan', $this_val);
        update_user_meta($this_user, '_selected_start_date', $planStartDate);
        update_user_meta($this_user, '_selected_end_date', $planEndDate);
        update_user_meta($this_user, '_selected_plan_payment_status', 1);

        /* Sending Email to User */
        $customer_email_content = $GeneralThemeObject->getEmailContents('mail-to-supplier-for-changing-membership-plan-by-administrator', ['{%user%}', '{%previous_plan%}', '{%new_plan%}'], [$user_details->data['fname'] . ' ' . $user_details->data['lname'], get_the_title($userPreviousPlan), get_the_title($this_val)]);
        $customer_email_subject = get_bloginfo('name') . ' :: ' . $customer_email_content[0];
        $customer_email_template = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $customer_email_content[1]);
        $GeneralThemeObject->send_mail_func($user_details->data['email'], $customer_email_subject, $customer_email_template);

        $resp_arr['flag'] = TRUE;
        $resp_arr['msg'] = $msg;
        //$resp_arr['url'] = admin_url() . 'users.php?role=supplier';
        $resp_arr['url'] = '';
        echo json_encode($resp_arr);
        exit;
    }

}