<?php

/**
 * ----------------------------------
 * Paypal:: Paypal class library
 * ----------------------------------
 */
if (!class_exists('Paypal_Standard')) {

    class Paypal_Standard {

        /**
         * @paypal mode of Payment
         *
         */
        CONST STNADARD = 'standard';
        CONST STNADARD_RECURRING = 'standard_recurring';

        /*
         * 
         */

        private $payment_mode = '';

        /**
         * Paypal Marchant ID
         */
        private $bussiness_id;

        /**
         * Paypal Gateway
         */
        protected $gateway = 'https://www.paypal.com/cgi-bin/webscr?';
        /*
         * 
         */
        var $test_mode = FALSE;

        public function __construct($recurring_payment = NULL) {

            $this->payment_mode = (!empty($payment_mode)) ? self::STNADARD_RECURRING : self::STNADARD;
            /* $payment_options = get_option('theme_payment_options');
              $this->bussiness_id = $payment_options['business_id'];
              $this->test_mode = $payment_options['standard_sandbox_mode']; */
            $this->bussiness_id = get_option('business_id');
            $this->test_mode = get_option('testmode');

            if ($this->test_mode)
                $this->gateway = 'https://www.sandbox.paypal.com/cgi-bin/webscr?';
        }

        /**
         * 
         * @param array $data
         * @return type
         */
        public function preparePaypalData(array $data) {
            $new_data = [];
            $new_data['business'] = $this->bussiness_id;
            $new_data['cmd'] = ($this->payment_mode === self::STNADARD_RECURRING) ? '_xclick-subscriptions' : '_xclick';
            $merged_data = array_merge($new_data, $data);
            $query_string = http_build_query($merged_data);
            return $this->gateway . $query_string;
        }

        public function ipnValidate(array $data) {
            //wp_mail('sayanta.dey@infoway.us', 'Test ipnSubject', $data['custom']);
            $GeneralThemeObject = new GeneralTheme();
            $MemberShipObject = new classMemberShip();
            $DonationObject = new classPayPalDonation();
            $AnnouncementObject = new classAnnouncement();
            $custom_attribute = explode('#', $data['custom']);
            $order_id = $custom_attribute[0];
            $user_id = $custom_attribute[1];
            $announce_payment_code = $custom_attribute[2];
            $announce_payment_renew = $custom_attribute[3];
            $transaction_id = $data['txn_id'];

            /* Get Announcement Data */
            $queryAnnouncementString = " AND `unique_announcement_code`='" . $announce_payment_code . "'";
            $getAnnouncementPaymentData = $AnnouncementObject->getAnnouncementPaymentData($queryAnnouncementString);

            /* Get Ad Payment Data */
            $queryString = " AND `unique_id`='" . $order_id . "'";
            $getAdvPaymentData = $GeneralThemeObject->getAdvPaymentData($queryString);

            if ($user_id == 9999999999) {
                $donationQuery = " AND `donation_id`='" . $order_id . "'";
                $getDonationDetails = $DonationObject->getDonationDetails($donationQuery);
                if (is_array($getDonationDetails) && count($getDonationDetails) > 0) {
                    foreach ($getDonationDetails as $eachDonation) {
                        if ($eachDonation->payment_status == 2) {
                            $updatedData = [
                                'transaction_id' => $transaction_id,
                                'payment_status' => 1,
                                'payment_date' => strtotime(date('Y-m-d')),
                            ];
                            $whereData = [
                                'donation_id' => $order_id
                            ];
                            $DonationObject->updatePayPalDonationData($updatedData, $whereData);
                            /* Sending email to User */
                            $customer_email_content = $GeneralThemeObject->getEmailContents('mail-to-user-for-paypal-donation', ['{%user_name%}', '{%donation_id%}', '{%transaction_id%}', '{%total_price%}', '{%payment_date%}'], [$eachDonation->name, $order_id, $transaction_id, 'R$ ' . number_format($eachDonation->amount, 2), date('d M, Y')]);
                            $customer_email_subject = get_bloginfo('name') . ' :: ' . $customer_email_content[0];
                            $customer_email_template = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $customer_email_content[1]);
                            $GeneralThemeObject->send_mail_func($eachDonation->email, $customer_email_subject, $customer_email_template);

                            /* Sending email to Administrator */
                            $admin_email = get_option('admin_email');
                            $admin_email_content = $GeneralThemeObject->getEmailContents('mail-to-admin-for-paypal-donation', ['{%user_name%}', '{%user_email%}', '{%donation_id%}', '{%transaction_id%}', '{%total_price%}', '{%payment_date%}'], [$eachDonation->name, $eachDonation->email, $order_id, $transaction_id, 'R$ ' . number_format($eachDonation->amount, 2), date('d M, Y')]);
                            $admin_email_subject = get_bloginfo('name') . ' :: ' . $admin_email_content[0];
                            $admin_email_template = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $admin_email_content[1]);
                            $GeneralThemeObject->send_mail_func($admin_email, $admin_email_subject, $admin_email_template);
                        }
                    }
                }
            } else if (is_array($getAdvPaymentData) && count($getAdvPaymentData) > 0) {
                $updatedData = [
                    'transaction_id' => $transaction_id,
                    'payment_status' => 1,
                    'payment_date' => strtotime(date('Y-m-d')),
                ];
                $whereData = [
                    'unique_id' => $order_id
                ];
                $GeneralThemeObject->updateAdvPaymentData($updatedData, $whereData);
            } else if (is_array($getAnnouncementPaymentData) && count($getAnnouncementPaymentData) > 0) {
                
                $announcemenDetails = $AnnouncementObject->announcement_details($order_id);
                
                update_post_meta($order_id, '_admin_approval', 1); //Approved
                update_post_meta($order_id, '_announcement_enabled', 1); //Active
                
                /*update user active announcment counter*/
                $_counter = 0;
                $_counter = get_user_meta($user_id, '_'.$announcemenDetails->data['announcement_plan'].'_count', true);
                update_user_meta($user_id, '_'.$announcemenDetails->data['announcement_plan'].'_count', $_counter+1 );
                
                
                //$_announce_start_date = strtotime(date('Y-m-d'));
                //$_announce_end_date = strtotime('+' . $announcemenDetails->data['no_of_days'] . ' days', $_announce_start_date);
                
                // update_post_meta($order_id, '_start_date', $_announce_start_date);
                // update_post_meta($order_id, '_end_date', $_announce_end_date);

                $updatedAnnouncementData = [
                    'transaction_id' => $transaction_id,
                    'payment_date' => strtotime(date('Y-m-d')),
                    'payment_status' => 1
                ];

                $whereAnnouncementData = [
                    'unique_announcement_code' => $announce_payment_code
                ];

                $AnnouncementObject->updateAnnouncementPayment($updatedAnnouncementData, $whereAnnouncementData);

                $user_details = $GeneralThemeObject->user_details($user_id);

                if ($announce_payment_renew) { /* Check for renewing */
                    /* Sending email to User */
                    $customer_email_content = $GeneralThemeObject->getEmailContents('mail-to-user-for-renewing-announcement', ['{%user_name%}', '{%total_price%}', '{%announcement_name%}', '{%payment_date%}', '{%unique_code%}'], [$user_details->data['fname'] . ' ' . $user_details->data['lname'], 'R$ ' . $getAnnouncementPaymentData[0]->total_price, get_the_title($getAnnouncementPaymentData[0]->announcement_id), date('d M, Y'), $announce_payment_code]);
                    $customer_email_subject = get_bloginfo('name') . ' :: ' . $customer_email_content[0];
                    $customer_email_template = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $customer_email_content[1]);
                    $GeneralThemeObject->send_mail_func($user_details->data['email'], $customer_email_subject, $customer_email_template);

                    /* Sending email to Administrator */
                    $admin_email = get_option('admin_email');
                    $admin_email_content = $GeneralThemeObject->getEmailContents('mail-to-administrator-for-renewing-announcement', ['{%user_name%}', '{%total_price%}', '{%announcement_name%}', '{%payment_date%}', '{%unique_code%}'], [$user_details->data['fname'] . ' ' . $user_details->data['lname'], 'R$ ' . $getAnnouncementPaymentData[0]->total_price, get_the_title($getAnnouncementPaymentData[0]->announcement_id), date('d M, Y'), $announce_payment_code]);
                    $admin_email_subject = get_bloginfo('name') . ' :: ' . $admin_email_content[0];
                    $admin_email_template = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $admin_email_content[1]);
                    $GeneralThemeObject->send_mail_func($admin_email, $admin_email_subject, $admin_email_template);
                    
                } else { /* Check for new */
                    /* Sending email to User */
                    $customer_email_content = $GeneralThemeObject->getEmailContents('mail-to-user-for-new-announcement', ['{%user_name%}', '{%total_price%}', '{%announcement_name%}', '{%payment_date%}', '{%unique_code%}'], [$user_details->data['fname'] . ' ' . $user_details->data['lname'], 'R$ ' . $getAnnouncementPaymentData[0]->total_price, get_the_title($getAnnouncementPaymentData[0]->announcement_id), date('d M, Y'), $announce_payment_code]);
                    $customer_email_subject = get_bloginfo('name') . ' :: ' . $customer_email_content[0];
                    $customer_email_template = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $customer_email_content[1]);
                    $GeneralThemeObject->send_mail_func($user_details->data['email'], $customer_email_subject, $customer_email_template);

                    /* Sending email to Administrator */
                    $admin_email = get_option('admin_email');
                    $admin_email_content = $GeneralThemeObject->getEmailContents('mail-to-administrator-for-new-announcement', ['{%user_name%}', '{%total_price%}', '{%announcement_name%}', '{%payment_date%}', '{%unique_code%}'], [$user_details->data['fname'] . ' ' . $user_details->data['lname'], 'R$ ' . $getAnnouncementPaymentData[0]->total_price, get_the_title($getAnnouncementPaymentData[0]->announcement_id), date('d M, Y'), $announce_payment_code]);
                    $admin_email_subject = get_bloginfo('name') . ' :: ' . $admin_email_content[0];
                    $admin_email_template = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $admin_email_content[1]);
                    $GeneralThemeObject->send_mail_func($admin_email, $admin_email_subject, $admin_email_template);
                    
                }
            } 
        }

    }

}
