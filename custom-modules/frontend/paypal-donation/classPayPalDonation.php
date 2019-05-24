<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of classPayPalDonation
 *
 * @author Sayanta Dey
 */
class classPayPalDonation {

    public function __construct() {
        global $wpdb;
        $this->donationDB = &$wpdb;
    }

    /*
     * Insert into paypal donation table
     */

    public function insertIntoDonation(array $data) {
        $inserted_donation_id = $this->donationDB->insert(TBL_DONATION, $data);
        return $inserted_donation_id;
    }

    /*
     * Get rows from paypal donation table
     */

    public function getDonationDetails($queryString = NULL) {
        $getDonationQuery = "SELECT * FROM " . TBL_DONATION . " WHERE `ID`!=''";
        if ($queryString) {
            $getDonationQuery .= $queryString;
        }
        $getDonationQuery .= " ORDER BY `ID` DESC";
        $getDonationQueryRes = $this->donationDB->get_results($getDonationQuery);
        return $getDonationQueryRes;
    }

    /*
     * Delete from paypal donation table
     */

    public function deleteFromDonation(array $whereData) {
        $deleted_donations = $this->donationDB->delete(TBL_DONATION, $whereData);
        return $deleted_donations;
    }

    /*
     *      Update paypal donation data
     */

    public function updatePayPalDonationData(array $updatedData, array $whereData) {
        $updated_data = $this->donationDB->update(TBL_DONATION, $updatedData, $whereData);
        return $updated_data;
    }

}
