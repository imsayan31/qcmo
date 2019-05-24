<?php

/*
 * --------------------------------------------
 * AJAX:: Coupon Users Population
 * --------------------------------------------
 */
add_action('wp_ajax_coupon_user_type_selection', 'ajaxCouponUserPopulation');

if (!function_exists('ajaxCouponUserPopulation')) {

    function ajaxCouponUserPopulation() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $msg = NULL;
        $user_type = $_POST['user_type'];

        if($user_type){
            $getUsers = get_users(['role' => $user_type, 'orderby' => 'display_name', 'order' => 'ASC']);
        } else{
            $getUsers = [];
        }

        if(is_array($getUsers) && count($getUsers) > 0){
            foreach ($getUsers as $eachUser) {
                $msg .= '<p><label for="'. $eachUser->user_email .'">';
                $msg .= '<input type="checkbox" name="coupon_selected_users[]" id="'. $eachUser->user_email .'" value="'. $eachUser->ID .'"/> '. $eachUser->first_name .' '. $eachUser->last_name;
                $msg .= '</label></p>';
            }
        } else{
            $msg .= '<p><div class="update error">No user found.</div></p>';
        }

        $resp_arr['flag'] = TRUE;
        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}


add_action('wp_ajax_send_email_after_coupon_created', 'ajaxSendEmailToUserCouponAssignment');

if(!function_exists('ajaxSendEmailToUserCouponAssignment')){
    function ajaxSendEmailToUserCouponAssignment(){
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $CouponObject = new classCoupnManagement();
        $msg = NULL;
        $coupon = $_POST['coupon'];
        $coupon_details = $CouponObject->coupon_details($coupon);
        $couponUsers = $coupon_details->data['users'];

        

        if($coupon){
            if(is_array($couponUsers) && count($couponUsers) > 0){
                foreach ($couponUsers as $eachUser) {
                    $userDetails = $GeneralThemeObject->user_details($eachUser);

                    /* mail to user */
                    $get_seller_email_template = $GeneralThemeObject->getEmailContents('mail-to-users-for-coupon-assignment', ['{%user_name%}', '{%coupon_type%}', '{%coupon_code%}', '{%discount%}', '{%coupon_desc%}'], [$userDetails->data['fname'] . ' ' . $userDetails->data['lname'], $coupon_details->data['coupon_type'], $coupon_details->data['title'], $coupon_details->data['discount'].'%', $coupon_details->data['content']]);
                   
                    $mail_subject = get_bloginfo('name') . ' :: ' . $get_seller_email_template[0];
                    $mail_cont = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $get_seller_email_template[1]);
                    $GeneralThemeObject->send_mail_func($userDetails->data['email'], $mail_subject, $mail_cont);
                    $GeneralThemeObject->send_mail_func('sayantadey123@gmail.com', $mail_subject, $mail_cont);
                }
            }
        }

        $resp_arr['flag'] = TRUE;
        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }
}