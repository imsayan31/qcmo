<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of classMemberShip
 *
 * @author Sayanta Dey
 */
class classMemberShip {

    public function __construct() {
        global $wpdb;
        $this->membershipDB = &$wpdb;
    }

    public function insertIntoMemberShip(array $data) {
        $insertedID = $this->membershipDB->insert(TBL_MEMBERSHIP, $data);
        return $insertedID;
    }

    public function getMembershipDetails($order_id = NULL, $queryString = NULL) {
        $getMembershipQuery = "SELECT * FROM " . TBL_MEMBERSHIP . " WHERE `ID`!=''";
        if ($order_id) {
            $getMembershipQuery .= " AND `order_id`='" . $order_id . "'";
        }
        if ($queryString) {
            $getMembershipQuery .= $queryString;
        }
        $getMembershipQuery .= " ORDER BY `ID` DESC";
        $getMembershipQueryRes = $this->membershipDB->get_results($getMembershipQuery);
        return $getMembershipQueryRes;
    }

    public function updateMembershipData(array $updatedData, array $whereData) {
        $updatedRow = $this->membershipDB->update(TBL_MEMBERSHIP, $updatedData, $whereData);
        return $updatedRow;
    }

    public function getUserMembershipDetails($userID) {
        $getMembershipQuery = "SELECT * FROM " . TBL_MEMBERSHIP . " WHERE `user_id`=" . $userID . " ORDER BY `ID` DESC";
        $getMembershipQueryRes = $this->membershipDB->get_results($getMembershipQuery);
        return $getMembershipQueryRes;
    }

    public function deleteMembershipData($orderID) {
        $this->membershipDB->delete(TBL_MEMBERSHIP, ['order_id' => $orderID]);
    }

    public function getUserPlanFormat($userID) {
        $GeneralThemeObject = new GeneralTheme();
        $userDetails = $GeneralThemeObject->user_details($userID);
        $userPlan = $userDetails->data['selected_plan'];
        $planCountAcceptValue = get_post_meta($userPlan, 'number_of_deal_acceptance', TRUE);
        $getGreaterPlan = get_posts(['post_type' => themeFramework::$theme_prefix . 'membership', 'meta_key' => 'number_of_deal_acceptance', 'meta_value_num' => $planCountAcceptValue, 'meta_compare' => '>']);
        if (is_array($getGreaterPlan) && count($getGreaterPlan) > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
