<?php

/**
 * ----------------------------------------------
 * GENERAL:: GENERAL TRAIT FOR THIS THEME
 * THIS IS COMMON FUNC USED TRAIT FEATURES OF PHP
 * ----------------------------------------------
 */
/**
 * ------------------------------------------------
 * GENERAL:: GENERAL CLASS FUNC FOR THIS THEME
 * ------------------------------------------------
 */
if (!class_exists('GeneralTheme')) {

    class GeneralTheme {

        var $cookie_arr = array();
        var $image_files_extension = ['jpg', 'jpeg', 'png', 'gif'];
        protected $db;

        public function __construct() {
            global $wpdb;
            $this->db = &$wpdb;
            $this->google_api_key = get_option('google_api_key');
            $this->file_type_arr = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
            $this->new_flag_suppliers = get_option('new_flag_suppliers');
        }

        /**
         * Used For Short content
         * @param type $content
         * @param type $limit
         * @return type
         */
        public function short_content($content, $limit) {

            if (strlen($content) > $limit)
                $content = substr($content, 0, $limit);

            return $content;
        }

        public function generateRandomString($length) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }
            return $randomString;
        }

        /**
         * 
         * @param type $subject
         * @return type
         */
        public function alphaMatch($subject) {
            $pattern = '/^[a-z][a-z ]*$/i';
            return preg_match($pattern, $subject);
        }

        /**
         * 
         * @param type $url
         * @param type $match_for
         * @return string|boolean
         */
        public function verify_url($url, $match_for) {
            $parse_URL = parse_url($url);

            switch ($match_for) {
                case 'youtube' :
                    $pattern = 'www.youtube.com';
                    break;
                default :
                    break;
                    return $pattern;
            }

            if ($parse_URL['host'] == $pattern) {
                return TRUE;
            } else {
                return FALSE;
            }
        }

        /**
         * 
         * @param type $to
         * @param type $subject
         * @param type $message
         * @param type $attachments
         */
        public function send_mail_func($to, $subject, $message, $attachments = array()) {

            $headers = "From: " . get_bloginfo("name") . ' <' . get_option('admin_email') . '>' . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            //$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            wp_mail($to, $subject, $message, $headers, $attachments);
        }

        /**
         * 
         * @param type $temp_title
         * @param type $email_cont
         */
        public function theme_email_template($temp_title, $email_cont) {

            return $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' .
                    '<html xmlns="https://www.w3.org/1999/xhtml">' .
                    '<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>' . $temp_title . '</title></head>' .
                    '<body style="margin:0; padding:0; font-family: helvetica;">' .
                    '<table width="800" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="" style="background-color: transparent; margin-bottom: 5px; margin-top: 5px;">' .
                    ' <tr>
                        <td style="text-align:center; background-color: #fff;"><h1>
                        <a title="' . get_bloginfo("name") . '" href="' . BASE_URL . '" style="">
                           <img src="' . THEME_URL . '/assets/images/logo.png" alt="' . get_bloginfo("name") . '" title="' . get_bloginfo("name") . '" height="70" width="275" />
                    </a>
                </h1></td>
                    </tr>' .
                    '<tr>
                            <td bgcolor="#ffffff" style="">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#ffffff">
                                  <tr>
                                    <td style="padding: 0px 0px 0px 0px;">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
                                          <tr>
                                            <td colspan="3" style="padding:0px 0px;font:normal 12px/18px helvetica;color:#606060;">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                                  <tr>
                                                    <td style="font-size:14px;font-family:helvetica">' . $email_cont . '</td>
                                                  </tr>
                                                </table>
                                            </td>
                                          </tr>
                                        </table>
                                    </td>
                                  </tr>
                                </table>
                            </td>
                </tr>' .
                    '</table></body></html>';
        }

        /**
         * 
         * @param type $login_creds
         */
        public function set_site_cookie($login_creds) {
            if (isset($login_creds['rememberme']) && $login_creds['rememberme'] != '') {
                setcookie('myrentalpal_siteCookie_login', $login_creds['user_login'], time() + (60 * 60 * 24 * 7), '/');
                setcookie('myrentalpal_siteCookie_pass', $login_creds['user_password'], time() + (60 * 60 * 24 * 7), '/');
            } else {
                setcookie('myrentalpal_siteCookie_login', $login_creds['user_login'], time() - (60 * 60 * 24 * 7), '/');
                setcookie('myrentalpal_siteCookie_pass', $login_creds['user_password'], time() - (60 * 60 * 24 * 7), '/');
            }
        }

        /**
         * 
         * @return type
         */
        public function get_site_cookie() {
            if (isset($_COOKIE['myrentalpal_siteCookie_login']) && $_COOKIE['myrentalpal_siteCookie_login'] != '') {
                $this->cookie_arr['username'] = $_COOKIE['myrentalpal_siteCookie_login'];
                $this->cookie_arr['pass'] = $_COOKIE['myrentalpal_siteCookie_pass'];
                $this->cookie_arr['remember'] = '1';
            } else {
                $this->cookie_arr['username'] = '';
                $this->cookie_arr['pass'] = '';
                $this->cookie_arr['remember'] = '';
            }

            return $this->cookie_arr;
        }

        /**
         * 
         * @return type
         */

        /**
         * 
         * @param type $file
         * @return type
         */
        public function common_file_upload($file) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');

            $overrides = array('test_form' => false);
            $upload = wp_handle_upload($file, $overrides);
            return $upload;
        }

        /**
         * 
         * @param type $file
         * @param type $post_id
         * @param type $post_data
         * @return type
         */
        public function common_media_upload($file, $post_id, $post_data = array()) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');

            $overrides = array('test_form' => false);
            $attach_id = media_handle_upload($file, $post_id, $post_data, $overrides);
            return $attach_id;
        }

        /**
         * 
         * @param type $upload
         * @return type
         */
        public function create_attachment($upload, $post_id = NULL) {

            $attachment = array(
                'guid' => $upload['url'],
                'post_mime_type' => $upload['type'],
            );
            if ($post_id)
                $attachment['post_parent'] = $post_id;

            $attach_id = wp_insert_attachment($attachment, $upload['file']);
// you must first include the image.php file
// for the function wp_generate_attachment_metadata() to work
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
            wp_update_attachment_metadata($attach_id, $attach_data);

            return $attach_id;
        }

        /**
         * 
         * @param type $files
         * @return type
         */
        public function multiple_file_uploader($files = array(), $post_id = NULL) {

            for ($i = 0; $i < COUNT($files['name']); $i++) {
                $file['name'] = '';
                $file['type'] = '';
                $file['tmp_name'] = '';
                $file['error'] = '';
                $file['size'] = '';
                $file['name'] = $files['name'][$i];
                $file['type'] = $files['type'][$i];
                $file['tmp_name'] = $files['tmp_name'][$i];
                $file['error'] = $files['error'][$i];
                $file['size'] = $files['size'][$i];

                $upload = $this->common_file_upload($file);
                $image_ids[$i] = $this->create_attachment($upload, $post_id);
            }
            return $image_ids;
        }

        /**
         * 
         * @param type $user_id
         * @return type
         */
        public function user_details($user_id = NULL) {
            $user = (!empty($user_id)) ? new WP_User($user_id) : wp_get_current_user();

            $new_user_obj = new stdClass();
            $new_user_obj->data = array();

            $getProPicPath = get_attached_file($user->get('_pro_pic'));

            $user_arr = array(
                'user_name' => $user->user_login,
                'password' => $user->user_pass,
                'email' => $user->user_email,
                'user_id' => $user->ID,
                'role' => isset($user->roles[0]) ? $user->roles[0] : '',
                'url' => isset($user->user_url) ? $user->user_url : '',
                'nicename' => isset($user->user_nicename) ? $user->user_nicename : '',
                'member_since' => $user->user_registered,
                'fname' => $user->get('first_name'),
                'lname' => $user->get('last_name'),
                'lphone' => $user->get('_commercial_no'),
                'phone' => $user->get('_mobile_no'),
                'supplier_type' => $user->get('_supplier_type'),
                'cpf' => $user->get('_cpf'),
                'cnpj' => $user->get('_cnpj'),
                'pro_pic' => $user->get('_pro_pic'),
                'pro_pic_exists' => ($getProPicPath) ? TRUE : FALSE,
                'city' => $user->get('_city'),
                'state' => $user->get('_state'),
                'dob' => $user->get('_dob'),
                'genre' => $user->get('_genre'),
                'address' => $user->get('_address'),
                'user_address' => $user->get('_user_address'),
                'address_loc' => $user->get('_addressLoc'),
                'address_id' => $user->get('_addressID'),
                'where_to_buy_address' => $user->get('_where_to_buy_address'),
                'where_to_buy_address_loc' => $user->get('_where_to_buy_address_loc'),
                'where_to_buy_address_id' => $user->get('_where_to_buy_address_id'),
                'buisness_categories' => $user->get('_supplier_categories'),
                'selected_plan' => $user->get('_selected_plan'),
                'selected_start_date' => $user->get('_selected_start_date'),
                'selected_end_date' => $user->get('_selected_end_date'),
                'selected_plan_payment_status' => $user->get('_selected_plan_payment_status'),
                //'buisness_categories' => $user->get('_supplier_categories'),
                'buisness_categories' => $user->get('_supplier_categories'),
                'receive_deals' => $user->get('_receive_deals'),
                'deal_start_date' => $user->get('_deals_from_date'),
                'deal_end_date' => $user->get('_deals_to_date'),
                'allow_where_to_buy' => $user->get('_allow_where_to_buy'),
                'bio' => $user->get('_bio'),
                'sub_admin_capabilities' => $user->get('_sub_admin_capabilities'),
                'minimum_deal_amount' => $user->get('_minimum_deal_amount'),
                'check_user_address' => $user->get('_check_user_address'),
                'check_user_contact_no' => $user->get('_check_user_contact_no'),
                'check_user_cpf_cnpj' => $user->get('_check_user_cpf_cnpj'),
                'minimum_deal_amount_set' => $user->get('_minimum_deal_amount_set'),
                'announcement_limit' => $this->getUserAnnouncementLimit($user->ID)
            );

            $new_user_obj->data = $user_arr;

            return (object) $new_user_obj;
        }

        /**
         * 
         * @param type $user_id
         * @return type
         */
        public function getUserAnnouncementLimit($user_id) {
            return array(
                'bronze' => get_user_meta($user_id, '_bronze_count', true),
                'silver' => get_user_meta($user_id, '_silver_count', true),
                'gold' => get_user_meta($user_id, '_gold_count', true),
            );
        }

        /**
         * 
         * @param type $from
         * @param type $to
         * @return type
         */
        public function date_difference($from, $to) {

            $date1 = date_create($from);
            $date2 = date_create($to);
            $diff = date_diff($date1, $date2);
            /*
              if ($diff->y > 0) {
              $diffrence = $diff->y . ' Years';
              } elseif ($diff->d > 0) {
              $diffrence = $diff->d . ' days';
              } elseif ($diff->h > 0) {
              $diffrence = $diff->h . ' hours';
              } elseif ($diff->i > 0) {
              $diffrence = $diff->i . ' minutes';
              } else {
              $diffrence = $diff->s . ' seconds';
              }
             * 
             */
            return $diff;
        }

        public function auto_wp_login($user_login) {
            $user = get_user_by('email', $user_login);
            $user_id = $user->ID;

//login
            wp_set_current_user($user_id, $user->user_login);
            wp_set_auth_cookie($user_id);
            do_action('wp_login', $user->user_login);
        }

        /**
         * 
         * @param type $username
         * @return type
         */
        public function validUsername($username) {

            if (username_exists($username)) {
                $user_data = get_user_by('login', $username);
                return (int) $user_data->ID;
            } else {
                wp_safe_redirect(MYACCOUNT_PAGE);
                exit;
            }
        }

        /**
         * 
         * @param type $transientArr
         * @param type $time
         */
        public function setTransient($transientArr = array(), $time = 30) {
            if (count($transientArr) > 0) {
                foreach ($transientArr as $key => $val) {
                    set_transient($key, $val, $time);
                }
            }
        }

        /**
         * 
         * @param type $transientArr
         */
        public function deleteTransient($transientArr = array()) {
            if (count($transientArr) > 0) {
                foreach ($transientArr as $key) {
                    delete_transient($key);
                }
            }
        }

        /**
         * 
         * @param type $post_id
         * @param type $metaArr
         */
        public function updateMetaFields($post_id, $metaArr = array()) {
            if (count($metaArr) > 0) {
                foreach ($metaArr as $key => $val) {
                    update_post_meta($post_id, $key, $val);
                }
            }
        }

        /**
         * 
         * @param type $post_id
         * @return type
         */
        public function getAttachments($post_id = NULL) {
            $files_arr = array();
            $attachments = get_posts(['posts_per_page' => -1, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_parent' => $post_id]);

            if (is_array($attachments) && count($attachments) > 0) {
                $i = 0;
                foreach ($attachments as $value) {
                    $size = null;
                    $tmp_arr = wp_get_attachment_image_src($value->ID, $size);
                    $files_arr [$i]['img'] = $tmp_arr[0];
                    $files_arr [$i]['ID'] = $value->ID;
                    $i++;
                }
            }

            return $files_arr;
        }

        /**
         * 
         * @param type $payment_mode
         * @return boolean
         */
        public function daysAddToDate($date, $days) {

            $final_date = date('Y-m-d h:i:s', strtotime($date . "+" . $days));
            return $final_date;
        }

        /**
         * 
         * @param type $type
         * @param type $value
         * @param type $meta_key
         * @param type $compare
         * @return type
         */
        public function checkValidCode($type = 'user', $value, $meta_key, $compare = '=') {
            $user_id = false;
            $results = get_users(
                    array(
                        'meta_query' => array(
                            array(
                                'key' => $meta_key,
                                'value' => $value,
                                'compare' => $compare
                            )
                        )
                    )
            );

            if (is_array($results) && count($results) > 0) {
                $user_id = $results[0]->ID;
            }

            return $user_id;
        }

        public function authentic() {
            if (!is_user_logged_in()) {
                wp_safe_redirect(BASE_URL);
                exit;
            }
        }

        public function get_client_ip() {
            $ipaddress = '';
            if ($_SERVER['HTTP_CLIENT_IP'])
                $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
            else if ($_SERVER['HTTP_X_FORWARDED_FOR'])
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            else if ($_SERVER['HTTP_X_FORWARDED'])
                $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
            else if ($_SERVER['HTTP_FORWARDED_FOR'])
                $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
            else if ($_SERVER['HTTP_FORWARDED'])
                $ipaddress = $_SERVER['HTTP_FORWARDED'];
            else if ($_SERVER['REMOTE_ADDR'])
                $ipaddress = $_SERVER['REMOTE_ADDR'];
            else
                $ipaddress = 'UNKNOWN';
            return $ipaddress;
        }

        /**
         * 
         * @param type $data
         * @param type $tbl
         */
        public function insertRow($data, $tbl) {
            $this->db->insert($tbl, $data);
        }

        public function monthsAddToDate($date, $month) {

            $final_date = date('Y-m-d h:i:s', strtotime('+' . $month . ' months', strtotime($date)));
            return $final_date;
        }

        public function passwordValidation($password) {
//            if (!preg_match('@[A-Z]@', $password) || !preg_match('#[\W]#', $password)) {
            if (!preg_match('@[A-Z]@', $password)) {
                $str = 0;
            } else {
                $str = 1;
            }
            return $str;
        }

        public function priceValidation($price) {
            if (!preg_match('/^\d+(?:\.\d{2})?$/', $price)) {
                $str = 0;
            } else {
                $str = 1;
            }
            return $str;
        }

        public function userNameValidation($name) {
//            if (!preg_match('/^[a-zA-Z]+$/', $name)) {
            if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
                $str_match = 0;
            } else {
                $str_match = 1;
            }
            return $str_match;
        }

        public function userBankAccountValidation($name) {
            if (!preg_match('/^[a-zA-Z0-9]+$/', $name)) {
                $str_match = 0;
            } else {
                $str_match = 1;
            }
            return $str_match;
        }

        public function getEmailContents($mail_name, array $to_be_replaced, array $replacing_values) {
            $mail_query = new WP_Query(array('post_type' => themeFramework::$theme_prefix . 'mail_template', 'name' => $mail_name));
            if ($mail_query->have_posts()) {
                while ($mail_query->have_posts()) {
                    $mail_query->the_post();
                    $mail_content = get_the_content();
                    $main_mail_subject = get_post_meta(get_the_ID(), 'mail_subject', true);
                    $main_mail_content = str_replace($to_be_replaced, $replacing_values, $mail_content);
                }
            }
            $return_mail_content[0] = $main_mail_subject;
            $return_mail_content[1] = $main_mail_content;
            return $return_mail_content;
        }

        public function getNearby($lat, $lng, $limit = 50, $distance = 5, $unit = 'km') {

            // radius of earth; @note: the earth is not perfectly spherical, but this is considered the 'mean radius'
            if ($unit == 'km'):
                $radius = 6371.009; // in kilometers
            elseif ($unit == 'mi'):
                $radius = 3959; // in miles
            endif;

            /* $d = $distance;
              $r = 3959; */
            // latitude boundaries
            $maxLat = (float) $lat + rad2deg($distance / $radius);
            $minLat = (float) $lat - rad2deg($distance / $radius);

            // longitude boundaries (longitude gets smaller when latitude increases)
            $maxLng = (float) $lng + rad2deg($distance / $radius / cos(deg2rad((float) $lat)));
            $minLng = (float) $lng - rad2deg($distance / $radius / cos(deg2rad((float) $lat)));
            $nearby_query = "SELECT *, $radius * 2 * ASIN(SQRT(POWER(SIN(( $lat - abs(`lat`)) * pi() / 180 / 2),2) + COS( $lat * pi()/180) * COS(abs(`lat`) * pi() / 180) * POWER(SIN(( $lng - `lng`) * pi() / 180 / 2), 2) )) AS distance FROM `andr_google_location` HAVING distance < $distance";

            $nearby_query_res = $this->db->get_results($nearby_query);
            return $nearby_query_res;
        }

        public function getDistance($point1_lat, $point1_long, $unit = 'mi', $destinationPlace = NULL) {


            if ($unit == 'km'):
                $radius = 6371.009; // in kilometers
            elseif ($unit == 'mi'):
                $radius = 3959; // in miles
            endif;

            $getDistanceQuery = "SELECT *, $radius * 2 * ASIN(SQRT(POWER(SIN(( $point1_lat - abs(`lat`)) * pi() / 180 / 2),2) + COS( $point1_lat * pi()/180) * COS(abs(`lat`) * pi() / 180) * POWER(SIN(( $point1_long - `lng`) * pi() / 180 / 2), 2) )) AS distance FROM `andr_google_location` HAVING `place_id`='" . $destinationPlace . "'";
            $distance = $this->db->get_results($getDistanceQuery);
            return $distance[0]->distance;
        }

        public function insertGoogleLocation(array $data) {
            $getGoogleLocation = $this->getGoogleLocation($data['place_id']);
            if (is_array($getGoogleLocation) && count($getGoogleLocation) > 0) {
                $googleLocationID = $this->db->update(TBL_GOOGLE_LOCATION, [
                    'address' => $data['address'],
                    'lat' => $data['lat'],
                    'lng' => $data['lng']
                        ], [
                    'place_id' => $data['place_id']
                ]);
            } else {
                $googleLocationID = $this->db->insert(TBL_GOOGLE_LOCATION, $data);
            }
            return $googleLocationID;
        }

        public function getGoogleLocation($placeID) {
            $googleLocationQuery = "SELECT * FROM " . TBL_GOOGLE_LOCATION . " WHERE `place_id`='" . $placeID . "'";
            $googleLocationQueryRes = $this->db->get_results($googleLocationQuery);
            return $googleLocationQueryRes;
        }

        public function getGoogleLocationAddress() {
            $allAddress = [];
            $googleLocationQuery = "SELECT `address` FROM " . TBL_GOOGLE_LOCATION . "";
            $googleLocationQueryRes = $this->db->get_results($googleLocationQuery);
            if (is_array($googleLocationQueryRes) && count($googleLocationQueryRes) > 0) {
                foreach ($googleLocationQueryRes as $val) {
                    $allAddress[] = $val->address;
                }
            }
            return $allAddress;
        }

        /**
         * 
         * @param type $cityID
         * @return array
         */
        public function getBrazilCities($cityID = NULL) {
            $getAllCitiesQuery = "SELECT * FROM " . TBL_BRAZIL_CITIES . " WHERE `ID`!=''";
            if ($cityID) {
                $getAllCitiesQuery .= " AND `ibge_id`=" . $cityID . "";
            }
            $getAllCitiesQueryRes = $this->db->get_results($getAllCitiesQuery);
            return $getAllCitiesQueryRes;
        }

        /**
         * 
         * @return string
         */
        public function getHomeBannerImage() {
            $homepageBannerID = get_option('homepage_banner_image');
            $homepage_banner_image = wp_get_attachment_image_src($homepageBannerID, 'product_advertisement_image');
            if ($homepage_banner_image[0]) {
                $returnedImg = $homepage_banner_image[0];
            } else {
                $returnedImg = '';
            }
            return $returnedImg;
        }

        /**
         * 
         * @param type $user_city
         */
        public function setLandingCity($user_state, $user_city) {
            if (isset($user_city) && $user_city != '') {
                setcookie('andre_anonymous_state', $user_state, time() + (60 * 60 * 24 * 7), '/');
                setcookie('andre_anonymous_city', $user_city, time() + (60 * 60 * 24 * 7), '/');
            } else {
                setcookie('andre_anonymous_state', $user_state, time() - (60 * 60 * 24 * 7), '/');
                setcookie('andre_anonymous_city', $user_city, time() - (60 * 60 * 24 * 7), '/');
            }
        }

        /**
         * 
         * @param type 
         * @return current city of user
         */
        public function getLandingCity() {
            $currentCity = NULL;
            if (isset($_COOKIE['andre_anonymous_city']) && $_COOKIE['andre_anonymous_city'] != '') {
                $currentCity = $_COOKIE['andre_anonymous_city'];
            }
            return $currentCity;
        }

        /**
         * 
         * @param type $user_city
         * @return array
         */
        public function getProducts($user_city = NULL, $featured = NULL) {
            $getProductsQuery = ['post_type' => themeFramework::$theme_prefix . 'product', 'posts_per_page' => -1];
            if ($user_city) {
                $getProductsQuery['meta_query'][] = [
                    'key' => '_product_cities',
                    'value' => serialize(strval($user_city)),
                    'compare' => 'LIKE'
                ];
            }

            if ($featured && $featured == 'featured') {
                $getProductsQuery['meta_query'][] = [
                    'key' => '_make_it_featured',
                    'value' => serialize(strval(1)),
                    'compare' => 'LIKE'
                ];
            } else if ($featured && $featured == 'most_seen') {
                /* $getProductsQuery['meta_key'] = '_product_views';
                  $getProductsQuery['orderby'] = 'meta_value_num';
                  $getProductsQuery['order'] = 'DESC'; */
                $getProductsQuery['meta_key'] = '_product_views';
                $getProductsQuery['orderby'] = 'meta_value_num';
                $getProductsQuery['order'] = 'DESC';
                $getProductsQuery['meta_query'][] = [
                    'key' => '_product_views',
                    'value' => 2,
                    'compare' => '>='
                ];
            }

            /* echo '<pre>';
              print_r($getProductsQuery);
              echo '</pre>';
              exit; */

            $getAllProducts = get_posts($getProductsQuery);
            return $getAllProducts;
        }

        /**
         * 
         * @param type $product_id
         * @return object
         */
        public function product_details($product_id) {
            $get_product_details = new stdClass();
            $get_product_details->data = [];
            $getProductDetails = get_post($product_id);
            $userCity = $this->getLandingCity();
            $getProductPrice = $this->getProductPrice($product_id, $userCity);
            $getDefaultPrice = get_post_meta($product_id, '_product_price', TRUE);
            $getProductTotalEstimatedPrice = get_post_meta($product_id, '_product_total_estimated_price', true);
            $getProductSumAttributePrices = $this->getProductSumAttributePrices($product_id);

            /* get product price */
            if ($getProductPrice > 0) {
                $getProductPrice = number_format($getProductPrice, 2);
                $getCalculatedPrice = explode(',', $getProductPrice);
                $joinedPrice = join('', $getCalculatedPrice);
                $getMainPrice = $getProductPrice;
            } else if ($getDefaultPrice) {
                $getProductPrice = number_format($getDefaultPrice, 2);
                $getMainPrice = $getDefaultPrice;
            } else if ($getProductSumAttributePrices) {
                $getProductPrice = number_format($getProductSumAttributePrices, 2);
                $getMainPrice = $getProductSumAttributePrices;
            } else if ($getProductTotalEstimatedPrice) {
                $getProductPrice = number_format($getProductTotalEstimatedPrice, 2);
                $getMainPrice = $getProductTotalEstimatedPrice;
            }
            /* else {
              $getProductPrice = 0.00;
              } */
            /* get product categories */
            $getProductCategories = wp_get_object_terms($product_id, themeFramework::$theme_prefix . 'product_category');
            if (is_array($getProductCategories) && count($getProductCategories) > 0) {
                $productCat = [];
                $productCatArr = [];
                foreach ($getProductCategories as $eachCat) {
                    $productCat[] = '<a href="' . get_term_link($eachCat) . '">' . $eachCat->name . '</a>';
                    $productCatArr[] = $eachCat->term_id;
                    $productCatNameArr[] = $eachCat->name;
                }
                $joinedProductCats = join(', ', $productCat);
                $joinedProductNameCats = join(', ', $productCatNameArr);
            }

            /* check product type */
            $getProductType = get_post_meta($product_id, '_simple_product', true);

            $product_data_arr = [
                'ID' => $product_id,
                'title' => $getProductDetails->post_title,
                'description' => ($getProductDetails->post_content) ? $getProductDetails->post_content : 'Nenhuma descrição fornecida',
                'thumbnail_id' => get_post_thumbnail_id($product_id),
                'price' => 'R$ ' . $getProductPrice,
                'show_price' => $getProductPrice,
                //'calculated_price' => $joinedPrice,
                'calculated_price' => $getProductPrice,
                'main_price' => $getMainPrice,
                'product_cats' => get_post_meta($product_id, '_product_cats', TRUE),
                'product_cat_quantity' => get_post_meta($product_id, '_product_cat_quantity', TRUE),
                'product_attributes' => get_post_meta($product_id, '_product_cat_price', TRUE),
                'product_cities' => get_post_meta($product_id, '_product_cities', TRUE),
                'unit' => get_post_meta($product_id, '_product_unit', TRUE),
                'default_price' => get_post_meta($product_id, '_product_price', TRUE),
                'type' => get_post_meta($product_id, '_product_type', TRUE),
                'city_price' => get_post_meta($product_id, '_product_cities_prices', TRUE),
                'suppliers' => get_post_meta($product_id, '_product_suppliers', TRUE),
                'all_suppliers' => get_post_meta($product_id, '_product_all_suppliers', TRUE),
                'all_cities' => get_post_meta($product_id, '_product_all_cities', TRUE),
                'product_categories' => $joinedProductCats,
                'product_categories_arr' => $productCatArr,
                'product_categories_name_arr' => $joinedProductNameCats,
                'is_simple' => (!empty($getProductType) && is_array($getProductType) && count($getProductType) > 0 && $getProductType[0] == 1) ? TRUE : FALSE,
                'material_category' => get_post_meta($product_id, '_material_category', TRUE),
                'view_counter' => get_post_meta($product_id, '_product_views', TRUE),
                'product_city_price' => get_post_meta($product_id, '_product_current_city_numeric_price', TRUE),
            ];

            $get_product_details->data = $product_data_arr;
            return (object) $get_product_details;
        }

        /**
         * @return create city taxonomy
         * 
         */
        public function createCities() {
            $cityQuery = "SELECT DISTINCT(`mesoregion`) FROM `andr_brazil_cities`";
            $cityQueryRes = $this->db->get_results($cityQuery);
            if (is_array($cityQueryRes) && count($cityQueryRes) > 0) {
                foreach ($cityQueryRes as $val) {
                    $createTerm = wp_insert_term($val->mesoregion, themeFramework::$theme_prefix . 'product_city');
                    $childTermQuery = "SELECT * FROM `andr_brazil_cities` WHERE `mesoregion` = '$val->mesoregion'";
                    $childTermQueryRes = $this->db->get_results($childTermQuery);
                    if (is_array($childTermQueryRes) && count($childTermQueryRes) > 0) {
                        foreach ($childTermQueryRes as $eachChild) {
                            $args['parent'] = $createTerm['term_id'];
                            $createChildTerm = wp_insert_term($eachChild->name, themeFramework::$theme_prefix . 'product_city', $args);
                            update_term_meta($createChildTerm['term_id'], 'ibge_id', $eachChild->ibge_id);
                            update_term_meta($createChildTerm['term_id'], 'uf', $eachChild->uf);
                            update_term_meta($createChildTerm['term_id'], 'lat', $eachChild->lat);
                            update_term_meta($createChildTerm['term_id'], 'lng', $eachChild->lng);
                            update_term_meta($createChildTerm['term_id'], 'no_accents', $eachChild->no_accents);
                            update_term_meta($createChildTerm['term_id'], 'microregion', $eachChild->microregion);
                            update_term_meta($createChildTerm['term_id'], 'mesoregion', $eachChild->mesoregion);
                        }
                    }
                }
            }
        }

        /*
         * @param type $state
         * @return type array
         * @description It returns all parent cities and child cities if parent(state) supplied
         */

        public function getCities($state = NULL) {
            $getCitiesArgs = ['hide_empty' => false, 'parent' => NULL];
            if ($state) {
                $getCitiesArgs['parent'] = $state;
            }
            $getCities = get_terms(themeFramework::$theme_prefix . 'product_city', $getCitiesArgs);
            return $getCities;
        }

        /*
         * @param type $product_attribute
         * @return type array
         * @description It returns all suppliers and specific suppliers if product attribute supplied
         */

        public function getSuppliers($product_attribute = NULL) {
            $getSuppliersArgs = ['role' => 'supplier'];
            if ($product_attribute) {
                $getProductAttributeSuppliers = get_term_meta($product_attribute, '_attribute_suppliers', TRUE);
                $getSuppliersArgs['include'] = $getProductAttributeSuppliers;
            }
            $getSuppliers = get_users($getSuppliersArgs);
            return $getSuppliers;
        }

        /*
         * @param type $product_cities, $product_attributes, $product_quantity
         * @return type array
         * @description It returns city prices when prices not set by admin
         */

        public function getCityBasedPrices($product_cities, $product_attribute, $product_quantity) {
            $priceArr = NULL;
            if (is_array($product_attribute) && count($product_attribute) > 0) {
                foreach ($product_attribute as $eachKeyAttr => $eachVal) {
                    $eachCityPrice = $this->getEachCityPriceForAttribute($eachVal, $product_cities, $product_quantity[$eachKeyAttr]);
                    $priceArr = $priceArr + $eachCityPrice;
                }
            }
            return $priceArr;
        }

        /*
         * @param type $attributeId, $requestedCities, $requestedQuantity
         * @return type array
         * @description It returns each city prices for attributes
         */

        public function getEachCityPriceForAttribute($attributeId, $requestedCities, $requestedQuantity) {
            $productDetails = $this->product_details($attributeId);
            /* $term_cities = get_term_meta($attributeId, '_attribute_cities', true);
              $term_cities_price = get_term_meta($attributeId, '_attribute_price', true);
              $term_cities_var_price = get_term_meta($attributeId, '_attribute_var_price', true); */
            $term_cities = $productDetails->data['product_cities'];
            $term_cities_price = $productDetails->data['default_price'];
            $term_cities_var_price = $productDetails->data['city_price'];

            if (is_array($term_cities) && count($term_cities) > 0) {
                foreach ($term_cities as $eachCityKey => $eachCityVal) {
                    if ($eachCityVal == $requestedCities) {
                        if ($term_cities_var_price) {
                            $explodedCityPrice = $term_cities_var_price;
                            $cityPrice = $explodedCityPrice[$eachCityKey] * $requestedQuantity;
                            break;
                        } else {
                            $cityPrice = $term_cities_price * $requestedQuantity;
                        }
                    } else {
                        $cityPrice = $term_cities_price * $requestedQuantity;
                    }
                }
            }
            return $cityPrice;
        }

        /*
         * @param type $product_id
         * @return type string
         * @description It returns actual price of a product based on city
         */

        public function getProductPrice($pro_id, $city = NULL) {
            $getProductCities = get_post_meta($pro_id, '_product_cities', true);
            $getProductCityPrice = get_post_meta($pro_id, '_product_cities_prices', true);
            $getProductDefaultPrice = get_post_meta($pro_id, '_product_price', true);
            $getProductTotalEstimatedPrice = get_post_meta($pro_id, '_product_total_estimated_price', true);
            $getProductSumAttributePrices = $this->getProductSumAttributePrices($pro_id);
            $getProductType = get_post_meta($pro_id, '_simple_product', true);
            if ($city) {
                $getCurrentCity = $city;
            } else {
                $getCurrentCity = $this->getLandingCity();
            }

            if (is_array($getProductCities) && count($getProductCities) > 0 && is_array($getProductCityPrice) && count($getProductCityPrice) > 0 && array_search($getCurrentCity, $getProductCities) != FALSE && (!empty($getProductType) && is_array($getProductType) && count($getProductType) > 0 && $getProductType[0] == 1)) {     // Only for simple products
                $productCityKey = array_search($getCurrentCity, $getProductCities);
                $productPrice = $getProductCityPrice[$productCityKey];
            } else if (is_array($getProductCities) && count($getProductCities) > 0 && is_array($getProductCityPrice) && count($getProductCityPrice) > 0 && array_search($getCurrentCity, $getProductCities) == FALSE && (!empty($getProductType) && is_array($getProductType) && count($getProductType) > 0 && $getProductType[0] == 1)) {      // Only for simple products
                $productPrice = $getProductDefaultPrice;
            } elseif (is_array($getProductCities) && count($getProductCities) > 0 && empty($getProductCityPrice) && (!empty($getProductType) && is_array($getProductType) && count($getProductType) > 0 && $getProductType[0] == 1)) {     // Only for simple products
                $productPrice = $getProductDefaultPrice;
            } elseif (is_array($getProductCities) && count($getProductCities) > 0 && empty($getProductCityPrice) && !empty($getProductSumAttributePrices) && (!empty($getProductType) && is_array($getProductType) && count($getProductType) > 0 && $getProductType[0] != 1)) {     // Only for bundle products
                $productPrice = $getProductSumAttributePrices;
            } else {        //For all products
                $productPrice = $getProductDefaultPrice;
            }
            if ($productPrice == '' || $productPrice == '0') {
                $productPrice = $getProductDefaultPrice;
            }
            /* elseif (is_array($getProductCities) && count($getProductCities) > 0 && empty($getProductCityPrice) && !empty($getProductTotalEstimatedPrice)) {
              $productPrice = $getProductTotalEstimatedPrice;
              } elseif (is_array($getProductCities) && count($getProductCities) > 0 && empty($getProductCityPrice) && !empty($getProductDefaultPrice)) {
              $productPrice = $getProductDefaultPrice;
              } */
            return $productPrice;
        }

        public function getProductSumAttributePrices($product_id) {
            $get_product_cat = get_post_meta($product_id, '_product_cats', true);
            $get_product_cat_quantity = get_post_meta($product_id, '_product_cat_quantity', true);
            $totalReturnPrice = 0;
            if (is_array($get_product_cat) && count($get_product_cat) > 0) {
                foreach ($get_product_cat as $key => $val) {
                    $productPrice = $this->getProductPrice($val);
                    $productQuantityPrice = ($get_product_cat_quantity[$key] * $productPrice);
//                    echo '<pre>';
//                    print_r($val . ' => ' . $productPrice . ' -> ' . $productQuantityPrice);
//                    echo '</pre>';
                    $totalReturnPrice = $totalReturnPrice + $productQuantityPrice;
                }
            }
            return $totalReturnPrice;
        }

        /*
         * @param type null
         * @return type array
         * @description It returns all simple products
         */

        public function getSimpleProducts() {
            $getProductArgs = ['post_type' => themeFramework::$theme_prefix . 'product', 'posts_per_page' => -1, 'meta_query' => [
                    [
                        'key' => '_simple_product',
                        'value' => '',
                        'compare' => '!='
                    ]
//                    [
//                        'key' => '_simple_product',
//                        'value' => serialize(strval(1)),
//                        'compare' => 'LIKE'
//                    ]
            ]];
            $getSimpleProducts = get_posts($getProductArgs);
            return $getSimpleProducts;
        }

        /*
         * @param type $product_id
         * @return type null
         * @description It sets the number of viewers of a product
         */

        public function setProductViewCounter($product_id) {
            $getProductViews = get_post_meta($product_id, '_product_views', TRUE);
            $generateProductViewer = $this->generateRandomString(8);
            if (isset($_COOKIE['andre_product_view']) && $_COOKIE['andre_product_view'] != '') {
                
            } else {
                setcookie('andre_product_view', $generateProductViewer, time() + (60 * 60 * 24 * 7), '/');
                update_post_meta($product_id, '_product_views', $getProductViews + 1);
            }
        }

        /*
         * @param type null
         * @return type array of objects
         * @description It returns all the membership plans
         */

        public function getMembershipPlans($order = NULL, $number = NULL) {
            if ($number == NULL) {
                $number = -1;
            }
            if ($order == NULL) {
                $order = 'ASC';
            }

            $getMembershipArgs = ['post_type' => themeFramework::$theme_prefix . 'membership', 'posts_per_page' => $number, 'orderby' => 'date', 'order' => $order];
            $getMembershipPlans = get_posts($getMembershipArgs);
            return $getMembershipPlans;
        }

        /*
         * @param type $plan_id
         * @return type objects
         * @description It returns the details of a membership plan
         */

        public function getMembershipPlanDetails($plan_id) {
            $planDetails = new stdClass();
            $getPlanDetails = get_post($plan_id);

            /* Fetch meta value */
            $priceForQuarterly = get_post_meta($plan_id, '3_months_plan_price', TRUE);
            $priceForHalfYearly = get_post_meta($plan_id, '6_months_plan_price', TRUE);
            $priceForYearly = get_post_meta($plan_id, 'annual_plan_price', TRUE);
            $noOfAcceptence = get_post_meta($plan_id, 'number_of_deal_acceptance', TRUE);
            $maxDealCount = get_post_meta($plan_id, 'max_deal_count', TRUE);

            /* Fetch thumnail */
            $thumbnailID = get_post_thumbnail_id($plan_id);
            $thumbnailImg = wp_get_attachment_image_src($thumbnailID, 'full');
            $thumbnailPath = get_attached_file($thumbnailID);

            $planDetails->data = [
                'ID' => $getPlanDetails->ID,
                'title' => $getPlanDetails->post_title,
                'name' => $getPlanDetails->post_name,
                'description' => $getPlanDetails->post_content,
                'thumbnail' => $thumbnailImg[0],
                'thumbnail_id' => $thumbnailID,
                'thumbnail_path' => $thumbnailPath,
                'quarterly_price' => $priceForQuarterly,
                'half_yearly_price' => $priceForHalfYearly,
                'yearly_price' => $priceForYearly,
                'no_of_acceptence' => $noOfAcceptence,
                'max_deal_count' => $maxDealCount
            ];

            return (object) $planDetails;
        }

        public function getSupplierForMap(array $args) {
            $supplierArr = [];
            $RatingObject = new classReviewRating();
            $getSuppliers = get_users($args);
            if (is_array($getSuppliers) && count($getSuppliers) > 0) {
                $i = 0;
                foreach ($getSuppliers as $eachSupplier) {
                    $getUserDetails = $this->user_details($eachSupplier->ID);
                    $user_pro_pic = wp_get_attachment_image_src($getUserDetails->data['pro_pic'], 'full');
                    $mapIcon = $this->setSupplierMapIconAsMember($eachSupplier->ID);
                    $totalRating = $RatingObject->getAverageRating($eachSupplier->ID);
                    $getRatingHTML = $RatingObject->getRatingHTML($totalRating, TRUE);
                    $explodedAddress = explode(',', $getUserDetails->data['address_loc']);
                    $userActiveStatus = get_user_meta($eachSupplier->ID, '_admin_approval', true);
                    if ($userActiveStatus == 1) {
                        $supplierArr[$i]['user_id'] = $getUserDetails->data['user_id'];
                        $supplierArr[$i]['name'] = $getUserDetails->data['fname'] . ' ' . $getUserDetails->data['lname'];
                        $supplierArr[$i]['cname'] = $getUserDetails->data['fname'];
                        $supplierArr[$i]['lname'] = $getUserDetails->data['lname'];
                        $supplierArr[$i]['url'] = get_author_posts_url($eachSupplier->ID);
                        $supplierArr[$i]['thumbnail'] = ($getUserDetails->data['pro_pic_exists']) ? $user_pro_pic[0] : 'https://via.placeholder.com/100x100';
                        $supplierArr[$i]['phone'] = $getUserDetails->data['phone'];
                        $supplierArr[$i]['address'] = $getUserDetails->data['user_address'];
                        $supplierArr[$i]['where_to_buy'] = ($getUserDetails->data['allow_where_to_buy'] == 1) ? $getUserDetails->data['where_to_buy_address'] : 'javascript:void(0);';
                        $supplierArr[$i]['lat'] = $explodedAddress[0];
                        $supplierArr[$i]['lng'] = $explodedAddress[1];
                        $supplierArr[$i]['marker'] = $mapIcon;
                        $supplierArr[$i]['rating'] = $getRatingHTML;
                        $i++;
                    }
                }
            }
            return $supplierArr;
        }

        public function setSupplierMapIconAsMember($supplierID) {
            $supplierDetails = $this->user_details($supplierID);
            $getSupplierPlanDetails = $this->getMembershipPlanDetails($supplierDetails->data['selected_plan']);
            if ($getSupplierPlanDetails->data['name'] == 'gold') {
                $iconURL = ASSET_URL . '/images/gold.png';
            } else if ($getSupplierPlanDetails->data['name'] == 'silver') {
                $iconURL = ASSET_URL . '/images/silver.png';
            } else {
                $iconURL = ASSET_URL . '/images/bronze.png';
            }
            return $iconURL;
        }

        public function advertisement_details($adv_id) {
            $advObject = new stdClass();
            $getAdvDetails = get_post($adv_id);

            $getAdvThumbnailId = get_post_thumbnail_id($adv_id);
            $getAdvThumbnailPath = get_attached_file($getAdvThumbnailId);
            $getAdvThumbnail = wp_get_attachment_image_src($getAdvThumbnailId, 'product_advertisement_image');

            $advObject->data = [
                'ID' => $getAdvDetails->ID,
                'title' => $getAdvDetails->post_title,
                'content' => $getAdvDetails->post_content,
                'name' => $getAdvDetails->post_name,
                'author' => $getAdvDetails->post_author,
                'thumbnail_path' => $getAdvThumbnailPath,
                'thumbnail' => $getAdvThumbnail[0],
                'adv_url' => get_post_meta($adv_id, '_adv_url', TRUE),
                'adv_position' => get_post_meta($adv_id, '_adv_position', TRUE),
                'adv_state' => get_post_meta($adv_id, '_adv_state', TRUE),
                'adv_city' => get_post_meta($adv_id, '_adv_city', TRUE),
                'adv_init_date' => get_post_meta($adv_id, '_adv_init_date', TRUE),
                'adv_final_date' => get_post_meta($adv_id, '_adv_final_date', TRUE),
                'adv_select_slot' => get_post_meta($adv_id, '_adv_select_slot', TRUE),
                'adv_init_time' => get_post_meta($adv_id, '_adv_init_time', TRUE),
                'adv_final_time' => get_post_meta($adv_id, '_adv_final_time', TRUE),
                'adv_timing' => get_post_meta($adv_id, '_adv_timing', TRUE),
                'adv_priority' => get_post_meta($adv_id, '_adv_priority', TRUE),
                'adv_cat' => get_post_meta($adv_id, '_adv_cat', TRUE),
                'adv_page' => get_post_meta($adv_id, '_adv_page', TRUE),
                'adv_view' => get_post_meta($adv_id, '_adv_view_counter', TRUE),
                'adv_enable_banner_text' => get_post_meta($adv_id, '_adv_enable_banner_text', TRUE),
                'adv_enable_view_counter' => get_post_meta($adv_id, '_adv_enable_view_counter', TRUE),
                'adv_enable_view_button' => get_post_meta($adv_id, '_adv_enable_view_button', TRUE),
                'adv_enabling' => get_post_meta($adv_id, '_adv_enabling', TRUE),
                'adv_price' => get_post_meta($adv_id, '_adv_price', TRUE),
                'adv_payment_status' => get_post_meta($adv_id, '_adv_payment_status', TRUE)
            ];

            return (object) $advObject;
        }

        public function getAdvertisements($city, $position, $page, $category = NULL, $init_date = NULL, $final_date = NULL) {
            $adv_args = ['post_type' => themeFramework::$theme_prefix . 'advertisement',
                'posts_per_page' => -1,
                'post_status' => ['publish', 'pending'],
                'orderby' => 'meta_value_num',
                'order' => 'ASC',
                'meta_key' => '_adv_priority',
                'meta_query' => [
                    'relation' => 'AND',
                    [
                        'key' => '_adv_city',
                        'value' => serialize(strval($city)),
                        'compare' => 'LIKE'
                    ],
                    /* [
                      'key' => '_adv_admin_approval',
                      'value' => 1,
                      'compare' => '='
                      ], */
                    [
                        'key' => '_adv_position',
                        'value' => serialize(strval($position)),
                        'compare' => 'LIKE'
                    ],
                    [
                        'key' => '_adv_page',
                        'value' => serialize(strval($page)),
                        'compare' => 'LIKE'
                    ],
                    [
                        'key' => '_adv_enabling',
                        'value' => 1,
                        'compare' => '='
                    ]
                ]
            ];

            /* Time */

            /* Category */
            if ($category) {
                $adv_args['meta_query'][] = [
                    'key' => '_adv_cat',
                    'value' => serialize(strval($category)),
                    'compare' => 'LIKE'
                ];
            }

            /* Initial date */
             if ($init_date) {
              $adv_args['meta_query'][] = [
              'key' => '_adv_init_date',
              'value' => $init_date,
              'compare' => '>='
              ];
              } 

            /* Final date */
             if ($final_date) {
              $adv_args['meta_query'][] = [
              'key' => '_adv_final_date',
              'value' => $final_date,
              'compare' => '<='
              ];
              } 

            $getAdvertisements = get_posts($adv_args);

            return $getAdvertisements;
        }

        public function outputCsv($fileName, $assocDataArray) {
            ob_clean();
            header('Pragma: public');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Cache-Control: private', false);
            //header('Content-Type: text/csv');
            //header("Content-Type: text/html; charset=UTF-8\r\n");

            header('Content-Encoding: UTF-8');
            header('Content-type: text/csv; charset=UTF-8');

            // header("Content-Type: application/octet-stream");
            // header("Content-Transfer-Encoding: binary");
            header('Content-Disposition: attachment;filename=' . $fileName);
            header('Pragma: public');
            if (isset($assocDataArray['0'])) {
                ob_start();
                $fp = fopen('php://output', 'w');
                // fputcsv($fp, array_keys($assocDataArray['0']));
                foreach ($assocDataArray AS $values) {

                    fputcsv($fp, $values);
                }
                fclose($fp);
            }
            ob_flush();
        }

        public function getPlanMaxDeals($plan_name) {
            $getPlanDetails = new WP_Query(['post_type' => themeFramework::$theme_prefix . 'membership', 'name' => $plan_name]);
            if ($getPlanDetails->have_posts()) {
                while ($getPlanDetails->have_posts()) {
                    $getPlanDetails->the_post();
                    $planMaxDealsCount = get_post_meta(get_the_ID(), 'max_deal_count', TRUE);
                }
            }
            return $planMaxDealsCount;
        }

        public function getHourlyTimeSlots() {
            $splittedTimeArr = [];
            $startTime = date('H:i', strtotime('today midnight'));
            //$endTime = date('H:i', strtotime($startTime) + 60 * 60 * 2);
            for ($i = 1; $i <= 24; $i++):
                $getNextHour = date('H:i', strtotime('+' . $i . ' hour', strtotime($startTime)));
                $prevTime = date('H:i', strtotime('-1 hour', strtotime($getNextHour)));
                $splittedTimeArr[$prevTime . '-' . $getNextHour] = $prevTime . ' to ' . $getNextHour;
            endfor;
            return $splittedTimeArr;
        }

        public function getAdvertisementPrice($adv_id) {
            $totalPrice = 0;
            $adv_details = $this->advertisement_details($adv_id);
            /* Get price for advertisements */
            $get_advert_link_price = get_option('_advert_link_price');
            $get_advert_position_price = get_option('_advert_position_price');
            $get_advert_city_price = get_option('_advert_city_price');
            $get_advert_category_price = get_option('_advert_category_price');
            $get_advert_page_price = get_option('_advert_page_price');
            $get_advert_time_price = get_option('_advert_time_price');

            /* Link */
            if ($adv_details->data['adv_url'] != '') {
                $totalPrice = $totalPrice + $get_advert_link_price['link'];
            }

            /* Position */
            if (is_array($adv_details->data['adv_position']) && count($adv_details->data['adv_position']) > 0 && in_array(1, $adv_details->data['adv_position'])) {
                $totalPrice = $totalPrice + $get_advert_position_price['top'];
            }

            if (is_array($adv_details->data['adv_position']) && count($adv_details->data['adv_position']) > 0 && in_array(2, $adv_details->data['adv_position'])) {
                $totalPrice = $totalPrice + $get_advert_position_price['middle'];
            }

            if (is_array($adv_details->data['adv_position']) && count($adv_details->data['adv_position']) > 0 && in_array(3, $adv_details->data['adv_position'])) {
                $totalPrice = $totalPrice + $get_advert_position_price['bottom'];
            }



            /* State & City */
            $getAllCities = $this->getCities($adv_details->data['adv_state']);
            if (is_array($getAllCities) && count($getAllCities) > 0) {
                foreach ($getAllCities as $eachCity) {
                    if (is_array($adv_details->data['adv_city']) && count($adv_details->data['adv_city']) > 0 && in_array($eachCity->term_id, $adv_details->data['adv_city'])) {
                        $totalPrice = $totalPrice + $get_advert_city_price[$eachCity->slug];
                    }
                }
            }



            /* Category */
            $getProductCategories = get_terms(themeFramework::$theme_prefix . 'product_category', ['hide_empty' => FALSE, 'parent' => 0]);
            if (is_array($getProductCategories) && count($getProductCategories) > 0) {
                foreach ($getProductCategories as $eachCat) {
                    $getProductSubCategories = get_terms(themeFramework::$theme_prefix . 'product_category', ['hide_empty' => FALSE, 'parent' => $eachCat->term_id]);
                    if (is_array($adv_details->data['adv_cat']) && count($adv_details->data['adv_cat']) > 0 && in_array($eachCat->term_id, $adv_details->data['adv_cat'])) {
                        $totalPrice = $totalPrice + $get_advert_category_price[$eachCat->slug];
                    }
                    if (is_array($getProductSubCategories) && count($getProductSubCategories) > 0) {
                        foreach ($getProductSubCategories as $eachSubCat) {
                            if (is_array($adv_details->data['adv_cat']) && count($adv_details->data['adv_cat']) > 0 && in_array($eachSubCat->term_id, $adv_details->data['adv_cat'])) {
                                $totalPrice = $totalPrice + $get_advert_category_price[$eachCat->slug];
                            }
                        }
                    }
                }
            }



            /* Page */
            $getTemplatePages = get_posts(['post_type' => 'page', 'posts_per_page' => -1]);
            if (is_array($adv_details->data['adv_page']) && count($adv_details->data['adv_page']) > 0 && in_array(2, $adv_details->data['adv_page'])) {
                $totalPrice = $totalPrice + $get_advert_page_price['category'];
            }

            if (is_array($adv_details->data['adv_page']) && count($adv_details->data['adv_page']) > 0 && in_array(3, $adv_details->data['adv_page'])) {
                $totalPrice = $totalPrice + $get_advert_page_price['product'];
            }

            if (is_array($adv_details->data['adv_page']) && count($adv_details->data['adv_page']) > 0 && in_array(4, $adv_details->data['adv_page'])) {
                $totalPrice = $totalPrice + $get_advert_page_price['supplier_public_profile'];
            }

            if (is_array($getTemplatePages) && count($getTemplatePages) > 0) {
                foreach ($getTemplatePages as $eachTemplatePage) {
                    $pageMetaField = get_post_meta($eachTemplatePage->ID, '_wp_page_template', TRUE);
                    if ($pageMetaField != 'default') {
                        if (is_array($adv_details->data['adv_page']) && count($adv_details->data['adv_page']) > 0 && in_array($eachTemplatePage->post_name, $adv_details->data['adv_page'])) {
                            $totalPrice = $totalPrice + $get_advert_page_price[$eachTemplatePage->post_name];
                        }
                    }
                }
            }

            /* Days */
            $dateDiffCal = $this->date_difference($adv_details->data['adv_init_date'], $adv_details->data['adv_final_date']);

            /* Time */
            if ($adv_details->data['adv_init_time'] && $adv_details->data['adv_final_time']) {
                $mainTimePrice = $this->getAdvHourlyPrice($adv_id);
                if ($dateDiffCal->days > 0) {
                    $totalPrice = $totalPrice + ($mainTimePrice * $get_advert_time_price['hourly'] * $dateDiffCal->days);
                } else {
                    $totalPrice = $totalPrice + ($mainTimePrice * $get_advert_time_price['hourly']);
                }
                //$totalPrice = $totalPrice + $mainTimePrice;
            }

            if ($totalPrice > 0) {
                $totalPrice = number_format($totalPrice, 2);
            } else {
                $totalPrice = '0.00';
            }

            return $totalPrice;
        }

        public function getAdvHourlyPrice($adv_id) {
            $mainTimePrice = 0;
            $get_advert_time_price = get_option('_advert_time_price');
            $adv_details = $this->advertisement_details($adv_id);
            if ($adv_details->data['adv_init_time'] && $adv_details->data['adv_final_time']) {
                $starTime = strtotime($adv_details->data['adv_init_time']);
                $endTime = strtotime($adv_details->data['adv_final_time']);
                $timeDifference = ($endTime - $starTime);
                $getHourVal = (($timeDifference / 3600));
            }
            for ($i = 1; $i <= $getHourVal; $i++) {
                $getNextHour = date('H:i', strtotime('+' . $i . ' hour', $starTime));
                $prevTime = date('H:i', strtotime('-1 hour', strtotime($getNextHour)));
                if (!empty($get_advert_time_price[$prevTime . '-' . $getNextHour])) {
                    $mainTimePrice = $mainTimePrice + $get_advert_time_price[$prevTime . '-' . $getNextHour];
                } else {
                    $mainTimePrice = $mainTimePrice + $get_advert_time_price['hourly'];
                }
            }
            return $getHourVal;
        }

        public function insertAdvPayment(array $data) {
            $inserted_adv_payment_id = $this->db->insert(TBL_ADVERTISEMENT_PAYMENT, $data);
            return $inserted_adv_payment_id;
        }

        public function getAdvPaymentData($queryString = NULL) {
            $getAdvPaymentDataQuery = "SELECT * FROM " . TBL_ADVERTISEMENT_PAYMENT . " WHERE `ID`!=''";
            if ($queryString) {
                $getAdvPaymentDataQuery .= $queryString;
            }
            $getAdvPaymentDataQuery .= " ORDER BY `ID` DESC";
            $getAdvPaymentDataQueryRes = $this->db->get_results($getAdvPaymentDataQuery);
            return $getAdvPaymentDataQueryRes;
        }

        public function deleteAdvPaymentData(array $whereData) {
            $deletedRow = $this->db->delete(TBL_ADVERTISEMENT_PAYMENT, $whereData);
            return $deletedRow;
        }

        public function updateAdvPaymentData(array $updatedData, array $whereData) {
            $updatedRow = $this->db->update(TBL_ADVERTISEMENT_PAYMENT, $updatedData, $whereData);
            return $updatedRow;
        }

        public function getProtugeeseFormattedDate($date) {
            $getMonthFromDate = date('n', $date);
            if ($getMonthFromDate == 1) {
                $monthRep = 'Janeiro';
            } else if ($getMonthFromDate == 2) {
                $monthRep = 'Fevereiro';
            } else if ($getMonthFromDate == 3) {
                $monthRep = 'Março';
            } else if ($getMonthFromDate == 4) {
                $monthRep = 'Abril';
            } else if ($getMonthFromDate == 5) {
                $monthRep = 'Maio';
            } else if ($getMonthFromDate == 6) {
                $monthRep = 'Junho';
            } else if ($getMonthFromDate == 7) {
                $monthRep = 'Julho';
            } else if ($getMonthFromDate == 8) {
                $monthRep = 'Agosto';
            } else if ($getMonthFromDate == 9) {
                $monthRep = 'Setembro';
            } else if ($getMonthFromDate == 10) {
                $monthRep = 'Outubro';
            } else if ($getMonthFromDate == 11) {
                $monthRep = 'Novembro';
            } else if ($getMonthFromDate == 12) {
                $monthRep = 'Dezembro';
            }
            $totalMonthFormat = date('d', $date) . ' ' . $monthRep . ', ' . date('Y', $date);
            return $totalMonthFormat;
        }

        public function getTimeElapsedString($time) {
            $time_difference = time() - $time;

            if ($time_difference < 1) {
                return 'less than 1 second ago';
            }
            $condition = array(12 * 30 * 24 * 60 * 60 => 'year',
                30 * 24 * 60 * 60 => 'month',
                24 * 60 * 60 => 'day',
                60 * 60 => 'hour',
                60 => 'minute',
                1 => 'second'
            );

            foreach ($condition as $secs => $str) {
                $d = $time_difference / $secs;

                if ($d >= 1) {
                    $t = round($d);
                    return 'about ' . $t . ' ' . $str . ( $t > 1 ? 's' : '' ) . ' ago';
                }
            }
        }

        public function limit_text($text, $limit) {
            if (str_word_count($text, 0) > $limit) {
                $words = str_word_count($text, 2);
                $pos = array_keys($words);
                $text = substr($text, 0, $pos[$limit]) . '...';
            }
            return $text;
        }

        public function getOnlyCities(){
            $getOnlyCities = [];
            $getStates = get_terms(themeFramework::$theme_prefix . 'product_city',['hide_empty' => FALSE, 'parent' => 0]);
            if(is_array($getStates) && count($getStates) > 0){
                foreach ($getStates as $eachState) {
                    $getStateCities = get_terms(themeFramework::$theme_prefix . 'product_city',['hide_empty' => FALSE, 'parent' => $eachState->term_id]);
                    if(is_array($getStateCities) && count($getStateCities) > 0){
                        foreach ($getStateCities as $eachStateCity) {
                            $getOnlyCities[] = $eachStateCity->name;
                        }
                    }
                }
            }
            return $getOnlyCities;
        }

        public function populateMonths() {
        $monthArr = [
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];
        return $monthArr;
    }

    public function is_valid_card_number($toCheck) {
        if (!is_numeric($toCheck))
            return false;

        $number = preg_replace('/[^0-9]+/', '', $toCheck);
        $strlen = strlen($number);
        $sum = 0;

        if ($strlen < 13)
            return false;

        for ($i = 0; $i < $strlen; $i++) {
            $digit = substr($number, $strlen - $i - 1, 1);
            if ($i % 2 == 1) {
                $sub_total = $digit * 2;
                if ($sub_total > 9) {
                    $sub_total = 1 + ($sub_total - 10);
                }
            } else {
                $sub_total = $digit;
            }
            $sum += $sub_total;
        }

        if ($sum > 0 AND $sum % 10 == 0)
            return true;

        return false;
    }

    public function is_valid_expiry($month, $year) {
        $now = time();
        $thisYear = (int) date('Y', $now);
        $thisMonth = (int) date('m', $now);

        if (is_numeric($year) && is_numeric($month)) {
            $thisDate = mktime(0, 0, 0, $thisMonth, 1, $thisYear);
            $expireDate = mktime(0, 0, 0, $month, 1, $year);

            return $thisDate <= $expireDate;
        }

        return false;
    }

    public function is_valid_cvv_number($toCheck) {
        $length = strlen($toCheck);
        return is_numeric($toCheck) AND $length > 2 AND $length < 5;
    }

    }

}