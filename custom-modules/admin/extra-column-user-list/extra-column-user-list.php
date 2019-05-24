<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

add_filter('manage_users_columns', 'add_extra_user_columns');
add_filter('manage_users_custom_column', 'modify_user_column_content', 10, 3);

function add_extra_user_columns($column) {
    $column['activate_user'] = 'Activate Supplier';
    $column['member_type'] = 'Membership Plan';
    return $column;
}

function modify_user_column_content($val, $column_name, $user_id) {
    $GeneralTheme = new GeneralTheme();
    $user_details = $GeneralTheme->user_details($user_id);
    $get_selected_plan_details = $GeneralTheme->getMembershipPlanDetails($user_details->data['selected_plan']);
    $getMembershipPlans = $GeneralTheme->getMembershipPlans();
    $admin_activate = get_user_meta($user_id, '_admin_approval', TRUE);
    if ($admin_activate == 1) {
        $admin_active = 'selected="selected"';
    } elseif ($admin_activate == 2) {
        $admin_disabled = 'selected="selected"';
    }

    switch ($column_name) {
        case 'activate_user':
            if ($user_details->data['role'] == 'supplier') {
                $select_box = '<select class="activate_user_cls" data-uid="' . $user_details->data['user_id'] . '"><option value="">-Choose option-</option><option ' . $admin_active . ' value="1" >Activate</option><option ' . $admin_disabled . ' value="2">Deactivate</option></select>';
            } else {
                $select_box = 'N/A';
            }
            return $select_box;
            break;
        case 'member_type':
            if ($user_details->data['role'] == 'supplier') {
                $select_box = ($user_details->data['selected_plan']) ? $get_selected_plan_details->data['title'] : '';
                $select_box = '<select class="change_user_membership" data-uid="' . $user_details->data['user_id'] . '">';
                if (is_array($getMembershipPlans) && count($getMembershipPlans) > 0) {
                    foreach ($getMembershipPlans as $eachMemberbershipplan) {
                        if($user_details->data['selected_plan'] == ''){
                            $user_details->data['selected_plan'] = 173;
                        }
                        if ($eachMemberbershipplan->ID == $user_details->data['selected_plan']) {
                            $membershipPlanSelected = 'selected';
                        } else {
                            $membershipPlanSelected = '';
                        }
                        
                        $select_box .= '<option value="' . $eachMemberbershipplan->ID . '" ' . $membershipPlanSelected . '>' . $eachMemberbershipplan->post_title . '</option>';
                    }
                }
                $select_box .= '</select>';
            }
            else {
                $select_box = 'N/A';
            }
            return $select_box;
            break;
        default:
    }
}
