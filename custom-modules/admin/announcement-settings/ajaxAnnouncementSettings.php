<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * --------------------------------------------
 * AJAX:: Ajax Announcement Approval
 * --------------------------------------------
 */

add_action('wp_ajax_announcement_approval', 'ajaxAnnouncementSettings');

if (!function_exists('ajaxAnnouncementSettings')) {

    function ajaxAnnouncementSettings() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $AnnouncementObject = new classAnnouncement();
        $msg = NULL;
        $announcement_id = $_POST['announcement_id'];
        $status_val = $_POST['status_val'];

        $announcement_details = $AnnouncementObject->announcement_details($announcement_id);
        $announcementAuthorDetails = $GeneralThemeObject->user_details($announcement_details->data['author']);

        update_post_meta($announcement_id, '_admin_approval', $status_val);
        $queryAnnouncementString = ' AND announcement_id=' . $announcement_id . ' AND payment_date !=0';
        $getAnnouncementPaymentData = $AnnouncementObject->getAnnouncementPaymentData($queryAnnouncementString);

        if ($status_val == 2) {
            update_post_meta($announcement_id, '_announcement_enabled', 2); // Make disable the announcement
            /* Sending Email to User */
            $customer_email_content = $GeneralThemeObject->getEmailContents('mail-to-user-for-announcement-disapproval', ['{%user%}', '{%announcement%}'], [$announcementAuthorDetails->data['fname'] . ' ' . $announcementAuthorDetails->data['lname'], $announcement_details->data['title']]);
            $customer_email_subject = get_bloginfo('name') . ' :: ' . $customer_email_content[0];
            $customer_email_template = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $customer_email_content[1]);
            $GeneralThemeObject->send_mail_func($announcementAuthorDetails->data['email'], $customer_email_subject, $customer_email_template);
        } else if ($status_val == 1 && count($getAnnouncementPaymentData) < 1) {

            /* Sending Email to User */
            $payment_link = ($announcement_details->data['announcement_plan'] != 'bronze') ? MY_ANNOUNCEMENTS_PAGE . '?dopayment=true&announcement=' . base64_encode($announcement_id) : 'N/A';
            $customer_email_content = $GeneralThemeObject->getEmailContents('mail-to-user-for-announcement-approval', ['{%user_name%}', '{%total_price%}', '{%announcement_name%}', '{%payment_link%}'], [$announcementAuthorDetails->data['fname'] . ' ' . $announcementAuthorDetails->data['lname'], 'R$ ' . $announcement_details->data['estimated_price'], $announcement_details->data['title'], $payment_link]);
            $customer_email_subject = get_bloginfo('name') . ' :: ' . $customer_email_content[0];
            $customer_email_template = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $customer_email_content[1]);
            $GeneralThemeObject->send_mail_func($announcementAuthorDetails->data['email'], $customer_email_subject, $customer_email_template);

            if ($announcement_details->data['announcement_plan'] == 'bronze') {
                if ($announcement_details->data['start_date'] == '') {
                    
                    $_announce_start_date = strtotime(date('Y-m-d'));
                    $_announce_end_date = strtotime('+' . $announcement_details->data['no_of_days'] . ' days', $_announce_start_date);

                    update_post_meta($order_id, '_start_date', $_announce_start_date);
                    update_post_meta($order_id, '_end_date', $_announce_end_date);
                }

                update_post_meta($announcement_id, '_announcement_enabled', 1); //make active
            }
        }

        $resp_arr['flag'] = TRUE;
        $resp_arr['msg'] = $msg;
        $resp_arr['url'] = admin_url() . 'edit.php?post_type=andr_announcement';

        echo json_encode($resp_arr);
        exit;
    }

}