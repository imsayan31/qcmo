<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of classAnnouncement
 *
 * @author Sayanta Dey
 */
class classAnnouncement {

    public function __construct() {
        global $wpdb;
        $this->db = &$wpdb;
        $this->new_flag_announcement = get_option('new_flag_announements');
    }

    public function getAnnouncementPrice($plan_type, $number_of_days) {
        $get_announcement_price = get_option('_announcement_price');
        if ($number_of_days <= 7) {
            $getUserAnnouncementPrice = ($get_announcement_price[$plan_type]['7'] * $number_of_days);
        } else if ($number_of_days >= 8 && $number_of_days <= 14) {
            $getUserAnnouncementPrice = ($get_announcement_price[$plan_type]['14'] * $number_of_days);
        } else {
            $getUserAnnouncementPrice = ($get_announcement_price[$plan_type]['30'] * $number_of_days);
        }
        return $getUserAnnouncementPrice;
    }

    public function insertAnnouncement(array $data) {
        $insertedID = wp_insert_post($data);
        return $insertedID;
    }

    public function updateAnnouncement(array $data) {
        $updatedID = wp_update_post($data);
        return $updatedID;
    }

    public function deleteAnnouncement($post_id) {
        wp_delete_post($post_id);
    }

    public function getAnnouncementCategory($parent = NULL) {
        $getAnnouncementCatArgs = ['hide_empty' => FALSE];
        if ($parent) {
            $getAnnouncementCatArgs['parent'] = $parent;
        }
        $getAnnouncementCategory = get_terms(themeFramework::$theme_prefix . 'announcement_category', $getAnnouncementCatArgs);
        return $getAnnouncementCategory;
    }

    public function announcement_details($post_id) {
        $announcementObject = new stdClass();
        $getAnnouncementDetails = get_post($post_id);
       
        $get_start_date = get_post_meta($post_id, '_start_date', TRUE);
        $get_number_of_days = get_post_meta($post_id, '_number_of_days', TRUE);
        $get_end_date = date('Y-m-d', strtotime($get_start_date. ' +'. $get_number_of_days .' days'));
        $get_announcement_images = get_post_meta($post_id, '_announcement_images', TRUE);
        $get_announcement_plan = get_post_meta($post_id, '_announcement_plan', TRUE);
        $get_admin_approval = get_post_meta($post_id, '_admin_approval', TRUE);
        $get_announcement_enabled = get_post_meta($post_id, '_announcement_enabled', TRUE);
        $get_announcement_state = get_post_meta($post_id, '_announcement_state', TRUE);
        $get_announcement_city = get_post_meta($post_id, '_announcement_city', TRUE);
        $get_announcement_address = get_post_meta($post_id, '_announcement_address', TRUE);
        $get_announcement_address_loc = get_post_meta($post_id, '_announcement_address_loc', TRUE);
        $get_announcement_address_id = get_post_meta($post_id, '_announcement_address_id', TRUE);
        $get_announcement_price = get_post_meta($post_id, '_announcement_price', TRUE);
        $get_announcement_old_content = get_post_meta($post_id, '_announcement_old_content', TRUE);
        $get_estimated_price = get_post_meta($post_id, '_estimated_price', TRUE);
        $explodedImages = explode(',', $get_announcement_images);

        /* if ($getAnnouncementDetails->post_status == 'publish') {
          $announcementStatus = 'Active';
          } else if ($getAnnouncementDetails->post_status == 'draft') {
          $announcementStatus = 'Inactive';
          } */

        if ($get_announcement_enabled == 1) {
            $announcementStatus = 'Active';
        } else if ($get_announcement_enabled == 2) {
            $announcementStatus = 'Inactive';
        }

        $announcementCategories = wp_get_object_terms($post_id, themeFramework::$theme_prefix . 'announcement_category');

        $categoryNameArr = [];
        $categorySlugArr = [];
        if (is_array($announcementCategories) && count($announcementCategories) > 0) {
            foreach ($announcementCategories as $eachCategory) {
                $categoryNameArr[] = $eachCategory->name;
                $categorySlugArr[] = $eachCategory->slug;
            }
            $joinedCatNames = join(', ', $categoryNameArr);
        }
        $announcementObject->data = [
            'ID' => $post_id,
            'title' => $getAnnouncementDetails->post_title,
            'content' => $getAnnouncementDetails->post_content,
            'old_content' => $get_announcement_old_content,
            'author' => $getAnnouncementDetails->post_author,
            'name' => $getAnnouncementDetails->post_name,
            'status' => $announcementStatus,
            'start_date' => $get_start_date,
            'end_date' => $get_end_date,
            'no_of_days' => $get_number_of_days,
            'announcement_single_image' => $explodedImages[0],
            'announcement_main_images' => $get_announcement_images,
            'announcement_images' => $explodedImages,
            'announcement_plan' => $get_announcement_plan,
            'admin_approval' => $get_admin_approval,
            'announcement_enabled' => $get_announcement_enabled,
            'announcement_category_names' => $joinedCatNames,
            'announcement_category' => $categorySlugArr,
            'announcement_state' => $get_announcement_state,
            'announcement_city' => $get_announcement_city,
            'announcement_address' => $get_announcement_address,
            'announcement_location' => $get_announcement_address_loc,
            'announcement_id' => $get_announcement_address_id,
            'announcement_price' => $get_announcement_price,
            'estimated_price' => $get_estimated_price,
        ];

        return (object) $announcementObject;
    }

    public function insertIntoAnnouncementPayment(array $data) {
        $inserted_id = $this->db->insert(TBL_ANNOUNCEMENT_PAYMENT, $data);
        return $inserted_id;
    }

    public function updateAnnouncementPayment(array $updatedData, array $whereData) {
        $updated_rows = $this->db->update(TBL_ANNOUNCEMENT_PAYMENT, $updatedData, $whereData);
        return $updated_rows;
    }

    public function deleteAnnouncementPayment(array $whereData) {
        $deletedRows = $this->db->delete(TBL_ANNOUNCEMENT_PAYMENT, $whereData);
        return $deletedRows;
    }

    public function getAnnouncementPaymentData($queryString = NULL) {
        $getQueryData = "SELECT * FROM " . TBL_ANNOUNCEMENT_PAYMENT . " WHERE `ID`!=''";
        if ($queryString) {
            $getQueryData .= $queryString;
        }
        $getQueryDataRes = $this->db->get_results($getQueryData);
        return $getQueryDataRes;
    }

    public function getUserAnnouncements($user_id = NULL, $posts_per_page = NULL, $admin_approval = FALSE, $activity = TRUE, $packageType = NULL) {
        $getUserAnnArgs = ['post_type' => themeFramework::$theme_prefix . 'announcement', 'orderby' => 'date', 'order' => 'DESC'];
        if ($user_id) {
            $getUserAnnArgs['author'] = $user_id;
        }
        if ($posts_per_page) {
            $getUserAnnArgs['posts_per_page'] = $posts_per_page;
        } else {
            $getUserAnnArgs['posts_per_page'] = -1;
        }
        if($packageType){
            $getUserAnnArgs['meta_query'][] = [
                'key' => '_announcement_plan',
                'value' => $packageType,
                'compare' => 'LIKE'
            ];
        }
        //$getUserAnnArgs['meta_query'] = $getUserAnnMetaArgs;
        $getUserAnnouncements = get_posts($getUserAnnArgs);
        return $getUserAnnouncements;
    }

    public function getAnnouncementEnabledCities() {
        $cityArr = [];
        /* Get Cities By Announcement Enabling */
        $getCities = get_terms(themeFramework::$theme_prefix . 'product_city', ['hide_empty' => FALSE, 'meta_query' => [
                [
                    'key' => '_enable_announcement',
                    'value' => 1,
                    'compare' => 'EXISTS'
                ]
        ]]);

        if (is_array($getCities) && count($getCities) > 0) {
            foreach ($getCities as $eachCity) {
                $cityArr[] = $eachCity->term_id;
            }
        }
        return $cityArr;
    }

    public function getAllAnnouncements($city = NULL) {
        
        $cityArr = [];
        $get_announcement_price = get_option('_announcement_price');

        /* Get Cities By Announcement Enabling */
        $getCities = get_terms(themeFramework::$theme_prefix . 'product_city', ['hide_empty' => FALSE, 'meta_query' => [
                [
                    'key' => '_enable_announcement',
                    'value' => 1,
                    'compare' => 'EXISTS'
                ]
        ]]);

        if (is_array($getCities) && count($getCities) > 0) {
            foreach ($getCities as $eachCity) {
                $cityArr[] = $eachCity->term_id;
            }
        }
        

        $getUserAnnArgs = ['post_type' => themeFramework::$theme_prefix . 'announcement', 'orderby' =>'meta_value_num', 'meta_key'=> '_announcement_order','order' => 'ASC'];
       
        $getUserAnnArgs['posts_per_page'] = 3;
        $getUserAnnMetaArgs[] = [
            'key' => '_admin_approval',
            'value' => 1,
            'compare' => '='
        ];
        $getUserAnnMetaArgs[] = [
            'key' => '_announcement_enabled',
            'value' => 1,
            'compare' => '='
        ];
        $city_value = $cityArr;
        $compare = 'IN';
        
        
        
        if($city) {
            $city_value = $city;
            $compare = '=';
        }
        
        $getUserAnnMetaArgs[] = [
            'key' => '_announcement_city',
            'value' => $city_value,
            'compare' => $compare
        ];

        $getUserAnnArgs['meta_query'] = $getUserAnnMetaArgs;
       
        $getUserAnnouncements = get_posts($getUserAnnArgs);

        $newAnnouncements = [];

        if(is_array($getUserAnnouncements) && count($getUserAnnouncements) > 0){
            foreach ($getUserAnnouncements as $eachAnnouncement) {
                $getAnnouncementDetails = $this->announcement_details($eachAnnouncement->ID);
                if($getAnnouncementDetails->data['announcement_plan'] == 'gold'){
                    $appearanceTimes = $get_announcement_price['gold']['no_of_appearance'];
                    for($i = 1; $i <= $appearanceTimes; $i++){
                        $newAnnouncements[] = $eachAnnouncement->ID;
                    }
                } else if($getAnnouncementDetails->data['announcement_plan'] == 'silver'){
                    $appearanceTimes = $get_announcement_price['silver']['no_of_appearance'];
                    for($i = 1; $i <= $appearanceTimes; $i++){
                        $newAnnouncements[] = $eachAnnouncement->ID;
                    }
                } else if($getAnnouncementDetails->data['announcement_plan'] == 'bronze'){
                    $appearanceTimes = $get_announcement_price['bronze']['no_of_appearance'];
                    for($i = 1; $i <= $appearanceTimes; $i++){
                        $newAnnouncements[] = $eachAnnouncement->ID;
                    }
                }
            }
            shuffle($newAnnouncements);
        } 
        return $newAnnouncements;

        
    }

    public function setSupplierMapIconAsAnnouncement($announcementID) {
        $supplierDetails = $this->announcement_details($announcementID);
        if ($supplierDetails->data['announcement_plan'] == 'gold') {
            $iconURL = ASSET_URL . '/images/announces-plan/gold.png';
        } else if ($supplierDetails->data['announcement_plan'] == 'silver') {
            $iconURL = ASSET_URL . '/images/announces-plan/silver.png';
        } else {
            $iconURL = ASSET_URL . '/images/announces-plan/bronze.png';
        }
        return $iconURL;
    }

    public function getAnnouncementForMap(array $args) {
        $supplierArr = [];
        $getSuppliers = get_posts($args);
        if (is_array($getSuppliers) && count($getSuppliers) > 0) {
            $i = 0;
            foreach ($getSuppliers as $eachSupplier) {
                $getUserDetails = $this->announcement_details($eachSupplier->ID);
                $user_pro_pic = wp_get_attachment_image_src($getUserDetails->data['announcement_single_image'], 'full');
                $mapIcon = $this->setSupplierMapIconAsAnnouncement($eachSupplier->ID);
                $explodedAddress = explode(',', $getUserDetails->data['announcement_location']);
                $imagePath = get_attached_file($getUserDetails->data['announcement_single_image']);

                $supplierArr[$i]['announcement_id'] = $getUserDetails->data['ID'];
                $supplierArr[$i]['name'] = $getUserDetails->data['title'];
                $supplierArr[$i]['author'] = $getUserDetails->data['author'];
                $supplierArr[$i]['thumbnail'] = ($imagePath) ? $user_pro_pic[0] : 'https://via.placeholder.com/100x100';
                $supplierArr[$i]['address'] = $getUserDetails->data['announcement_address'];
                $supplierArr[$i]['lat'] = $explodedAddress[0];
                $supplierArr[$i]['lng'] = $explodedAddress[1];
                $supplierArr[$i]['marker'] = $mapIcon;
                $i++;
            }
        }
        return $supplierArr;
    }

    public function getNoOfActiveAnnouncements($user_id, $type){
        $getUserAnnouncementArgs = ['post_type' => themeFramework::$theme_prefix . 'announcement', 'post_status' => 'publish', 'author' => $user_id, 'meta_query' => [
            [
                'key' => '_announcement_order',
                'value' => $type,
                'compare' => '='
            ],
            /*[
                'key' => '_announcement_enabled',
                'value' => 1,
                'compare' => '='
            ]*/
        ]];
        
        $getUserAnnouncements = get_posts($getUserAnnouncementArgs);
        $getNumber = count($getUserAnnouncements);
        return $getNumber;
    }
    
}


