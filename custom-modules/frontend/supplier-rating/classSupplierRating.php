<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of classReviewRating
 *
 * @author Sayanta Dey
 */
class classReviewRating extends GeneralTheme {

    public function __construct() {
        global $wpdb;
        $this->review_db = &$wpdb;
    }

    /**
     * @param type array
     * @param $data
     * @return type integer
     * @description It will insert review & rating for a vendor
     */
    public function insertIntoReviewRating(array $data) {
        $insertedReviewRatingId = $this->review_db->insert(TBL_SUPPLIER_RATING, $data);
        return $insertedReviewRatingId;
    }

    /**
     * @param type array
     * @param $data
     * @return type integer
     * @description It will insert review & rating for a vendor
     */
    public function hasUserReviewed($user_id, $deal_id) {
        $hasUserReviewedQuery = "SELECT * FROM " . TBL_SUPPLIER_RATING . " WHERE `user_id`=" . $user_id . " AND `deal_id`='" . $deal_id . "'";
        $hasUserReviewedQueryRes = $this->review_db->get_results($hasUserReviewedQuery);
        if (is_array($hasUserReviewedQueryRes) && count($hasUserReviewedQueryRes) > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * @param type $vendor_id
     * @return type
     */
    public function getAverageRating($vendor_id, $type = NULL) {
        global $wpdb;
        $total_rating = 0;
        $rating_query = "SELECT * FROM " . TBL_SUPPLIER_RATING . " WHERE `supplier_id`=" . $vendor_id . " AND `rating_status`=1";
        $rating_query_res = $wpdb->get_results($rating_query);
        if (is_array($rating_query_res) && count($rating_query_res) > 0):
            foreach ($rating_query_res as $value) :
                if ($type && $type == 'price') :
                    $total_rating = ($total_rating + $value->price_rate);
                elseif ($type && $type == 'attendence'):
                    $total_rating = ($total_rating + $value->attendence_rate);
                elseif ($type && $type == 'delivery'):
                    $total_rating = ($total_rating + $value->delivery_rate);
                elseif (!$type):
                    $total_rating = ($total_rating + (($value->price_rate + $value->attendence_rate + $value->delivery_rate) / 3));
                endif;
            endforeach;
            //$avg_rating = round($total_rating / count($rating_query_res));
            $avg_rating = $total_rating / count($rating_query_res);
        else:
            $avg_rating = 3;
        endif;
        return number_format($avg_rating, 1);
    }

    /**
     * @param type $totalRating, $smallStar
     * @return type
     */
    public function getRatingHTML($totalRating, $smallStar = TRUE) {

        $roundOfRating = round($totalRating);
        if ($roundOfRating <= $totalRating) {
            $roundOfRatingLess = $totalRating;
            $totalBlankRating = 5 - $roundOfRatingLess;
        } else if ($roundOfRating > $totalRating) {
            $roundOfRatingLess = $roundOfRating - 1;
            //$roundOfRatingLess = $roundOfRating;
            $totalBlankRating = 5 - $roundOfRating;
        }

        if ($smallStar == TRUE) {
            $selectStar = 'small-star.png';
            $selectHalfStar = 'small-star-half.png';
            $selectEmptyStar = 'small-star-empty.png';
        } else {
            $selectStar = 'big-star.png';
            $selectHalfStar = 'big-star-half.png';
            $selectEmptyStar = 'big-star-empty.png';
        }
        $i = 1;
        $ratingHTML = '';
        for ($i = 1; $i <= $roundOfRatingLess; $i++) {
            $ratingHTML .= '<img src="' . THEME_URL . '/assets/images/' . $selectStar . '">';
        }
//        if ($roundOfRating != $totalRating) {
        if ($roundOfRating > $totalRating && $totalRating < 5) {
            $ratingHTML .= '<img src="' . THEME_URL . '/assets/images/' . $selectHalfStar . '">';
        }
        for ($i = 1; $i <= $totalBlankRating; $i++) {
            $ratingHTML .= '<img src="' . THEME_URL . '/assets/images/' . $selectEmptyStar . '">';
        }
        return $ratingHTML;
    }

    /**
     * @param type NULL
     * @return type
     */
    public function getAllCustomerRating() {
        $allData = [];
        $ratingObject = new stdClass();
        $getCustomers = get_users(['role' => 'customer', 'meta_query' => [
                [
                    'key' => '_active_status',
                    'value' => 1,
                    'compare' => '='
                ]
        ]]);
        if (is_array($getCustomers) && count($getCustomers) > 0) {
            foreach ($getCustomers as $eachCustomer) {
                $ratingQuery = "SELECT * FROM " . TBL_SUPPLIER_RATING . " WHERE `user_id`=" . $eachCustomer->ID . " LIMIT 0,1";
                $ratingQueryRes = $this->review_db->get_results($ratingQuery);
                if (is_array($ratingQueryRes) && count($ratingQueryRes) > 0) {
                    foreach ($ratingQueryRes as $eachRes) {
                        $customerDetails = $this->user_details($eachRes->user_id);
                        $user_pro_pic = wp_get_attachment_image_src($customerDetails->data['pro_pic'], 'full');
                        $ratingHTML = $this->getRatingHTML($eachRes->rating);
                        $ratingObject->ID = $eachRes->ID;
                        $ratingObject->user_id = $eachRes->user_id;
                        $ratingObject->user_name = $customerDetails->data['fname'] . ' ' . $customerDetails->data['lname'];
                        $ratingObject->user_pic = ($customerDetails->data['pro_pic_exists'] == TRUE) ? $user_pro_pic[0] : 'https://via.placeholder.com/100x100';
                        $ratingObject->vendor_id = $eachRes->vendor_id;
                        $ratingObject->rating = $eachRes->rating;
                        $ratingObject->ratingHTML = $ratingHTML;
                        $ratingObject->comments = $eachRes->comments;
                        $ratingObject->date = $eachRes->date;
                        $allData[] = $ratingObject;
                    }
                }
            }
        }
        return $allData;
    }

    public function getAdminAllReviewRatings($queryString = NULL) {
        $getQuery = "SELECT * FROM " . TBL_SUPPLIER_RATING . " WHERE `ID`!=''";
        if ($queryString) {
            $getQuery .= $queryString;
        }
        $getQuery .= " ORDER BY `ID` DESC";
        $getQueryRes = $this->review_db->get_results($getQuery);
        return $getQueryRes;
    }

    public function getAllReviewRatings($queryString = NULL) {
        $getQuery = "SELECT * FROM " . TBL_SUPPLIER_RATING . " WHERE `ID`!='' AND `rating_status`=1";
        if ($queryString) {
            $getQuery .= $queryString;
        }
        $getQuery .= " ORDER BY `ID` DESC";
        $getQueryRes = $this->review_db->get_results($getQuery);
        return $getQueryRes;
    }

    public function updateReviewRating(array $updatedData, array $whereData) {
        $updatedRows = $this->review_db->update(TBL_SUPPLIER_RATING, $updatedData, $whereData);
        return $updatedRows;
    }

    public function deleteReviewRating(array $whereData) {
        $deletedRows = $this->review_db->delete(TBL_SUPPLIER_RATING, $whereData);
        return $deletedRows;
    }

}
