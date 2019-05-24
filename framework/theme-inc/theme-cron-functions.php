<?php
/**
 * ------------------------------------------------
 * THEME FUNC:: Contain All type of cron functions
 * ------------------------------------------------
 */

add_action('init', 'suppliersDefaultMembershipChecking');

if(!function_exists('suppliersDefaultMembershipChecking')){
    function suppliersDefaultMembershipChecking(){
        $MembershipObject = new classMemberShip();
        $getSuppliers = get_users(['role' => 'supplier']);
        $currDate = strtotime(date('Y-m-d'));
        if(is_array($getSuppliers) && count($getSuppliers) > 0){
            foreach ($getSuppliers as $eachSupplier) {
                $getMembershipDetails = $MembershipObject->getUserMembershipDetails($eachSupplier->ID);
                if($currDate > $getMembershipDetails[0]->plan_end_date){
                    update_user_meta($eachSupplier->ID, '_selected_plan', 173);
                } else {
                    update_user_meta($eachSupplier->ID, '_selected_plan', $getMembershipDetails[0]->plan_id);
                }
            }
        }
    }
}