<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

add_action('show_user_profile', 'andr_extra_subadmin_profile_fields');
add_action('edit_user_profile', 'andr_extra_subadmin_profile_fields');
add_action("user_new_form", "andr_extra_subadmin_profile_fields");

/**
 * Adding form fields in edit screen
 */
if (!function_exists('andr_extra_subadmin_profile_fields')) {

    function andr_extra_subadmin_profile_fields($user) {
        global $pagenow;
        $GeneralThemeFunc = new GeneralTheme();
        $get_user_details = $GeneralThemeFunc->user_details($user->ID);
        $getStates = $GeneralThemeFunc->getCities();
        //$getCities = ($get_user_details->data['state']) ? $GeneralThemeFunc->getCities($get_user_details->data['state']) : '';
        $getCities = $get_user_details->data['city'];

        if (($pagenow == 'user-edit.php') && ($get_user_details->data['role'] == 'sub_admin')):
            ?>
            <h3><?php _e("Sub Administrator Additional Information", "blank"); ?></h3>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e("State"); ?></th>
                    <td>
                        <select name="state" class="attribute_additional_admin_state chosen">
                            <option value=""><?php _e('-Select State-', THEME_TEXTDOMAIN); ?></option>
                            <?php if (is_array($getStates) && count($getStates) > 0): ?>                        
                                <?php foreach ($getStates as $eachState): ?>
                                    <!-- <option value="<?php echo $eachState->term_id; ?>" <?php echo ($eachState->term_id == $get_user_details->data['state']) ? 'selected' : ''; ?>><?php echo $eachState->name; ?></option> -->
                                    <option value="<?php echo $eachState->term_id; ?>"><?php echo $eachState->name; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e("City"); ?></th>
                    <td>
                        <?php if (is_array($getCities) && count($getCities) > 0): ?>                        
                                <?php foreach ($getCities as $eachCity): ?>
                                    <?php $getCityDetails = get_term_by('id', $eachCity, themeFramework::$theme_prefix . 'product_city'); ?>
                                    
                                    <label for="<?php echo $getCityDetails->slug; ?>">
                                        <input type="checkbox" name="city[]" id="<?php echo $getCityDetails->slug; ?>" class="check-sub-admin-multi-city" value="<?php echo $getCityDetails->term_id; ?>" <?php echo (is_array($get_user_details->data['city']) && count($get_user_details->data['city']) > 0 && in_array($getCityDetails->term_id, $get_user_details->data['city'])) ? 'checked' : ''; ?> /><?php echo $getCityDetails->name; ?>
                                    </label>
                                <?php endforeach; ?>
                                <?php else: ?>
                                	<?php $getCityDetails = get_term_by('id', $getCities, themeFramework::$theme_prefix . 'product_city'); ?>
                                    <label for="<?php echo $getCityDetails->slug; ?>">
                                        <input type="checkbox" name="city[]" id="<?php echo $getCityDetails->slug; ?>" class="check-sub-admin-multi-city" value="<?php echo $getCityDetails->term_id; ?>" <?php echo (is_array($get_user_details->data['city']) && count($get_user_details->data['city']) > 0 && in_array($getCityDetails->term_id, $get_user_details->data['city'])) ? 'checked' : ''; ?> /><?php echo $getCityDetails->name; ?>
                                    </label>
                            <?php endif; ?>
                    </td>
                </tr>
                <tr>
                <?php //print_r($getCities[0])?><br/>
                <?php //print_r($getCities[0]->name)?><br/>
                    <th scope="row"><?php _e("Phone number"); ?></th>
                    <td>
                        <input type="text" name="phone" value="<?php echo $get_user_details->data['phone']; ?>" placeholder="<?php _e('Phone number', THEME_TEXTDOMAIN); ?>"/>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e("Capabilities"); ?></th>
                    <td>
                        <select name="subadmin_capabilities[]" class="chosen subadmin_capabilities" multiple>
                            <!--<option value="manage_export_import" <?php echo (is_array($get_user_details->data['sub_admin_capabilities']) && count($get_user_details->data['sub_admin_capabilities']) > 0 && in_array('manage_export_import', $get_user_details->data['sub_admin_capabilities'])) ? 'selected' : ''; ?>><?php _e('Manage Export/Import', THEME_TEXTDOMAIN); ?></option>-->
                            <option value="manage_product_prices" <?php echo (is_array($get_user_details->data['sub_admin_capabilities']) && count($get_user_details->data['sub_admin_capabilities']) > 0 && in_array('manage_product_prices', $get_user_details->data['sub_admin_capabilities'])) ? 'selected' : ''; ?>><?php _e('Manage Products Price', THEME_TEXTDOMAIN); ?></option>
                            <option value="manage_membership_transaction" <?php echo (is_array($get_user_details->data['sub_admin_capabilities']) && count($get_user_details->data['sub_admin_capabilities']) > 0 && in_array('manage_membership_transaction', $get_user_details->data['sub_admin_capabilities'])) ? 'selected' : ''; ?>><?php _e('Manage Membership Transaction', THEME_TEXTDOMAIN); ?></option>
                            <option value="manage_deal_list" <?php echo (is_array($get_user_details->data['sub_admin_capabilities']) && count($get_user_details->data['sub_admin_capabilities']) > 0 && in_array('manage_deal_list', $get_user_details->data['sub_admin_capabilities'])) ? 'selected' : ''; ?>><?php _e('Manage Deal List', THEME_TEXTDOMAIN); ?></option>
                            <option value="manage_deal_review_list" <?php echo (is_array($get_user_details->data['sub_admin_capabilities']) && count($get_user_details->data['sub_admin_capabilities']) > 0 && in_array('manage_deal_review_list', $get_user_details->data['sub_admin_capabilities'])) ? 'selected' : ''; ?>><?php _e('Manage Deal Review List', THEME_TEXTDOMAIN); ?></option>
                            <option value="manage_users" <?php echo (is_array($get_user_details->data['sub_admin_capabilities']) && count($get_user_details->data['sub_admin_capabilities']) > 0 && in_array('manage_users', $get_user_details->data['sub_admin_capabilities'])) ? 'selected' : ''; ?>><?php _e('Manage Users', THEME_TEXTDOMAIN); ?></option>
                            <option value="manage_suppliers" <?php echo (is_array($get_user_details->data['sub_admin_capabilities']) && count($get_user_details->data['sub_admin_capabilities']) > 0 && in_array('manage_suppliers', $get_user_details->data['sub_admin_capabilities'])) ? 'selected' : ''; ?>><?php _e('Manage Suppliers', THEME_TEXTDOMAIN); ?></option>
                            <!--<option value="manage_donation_list" <?php echo (is_array($get_user_details->data['sub_admin_capabilities']) && count($get_user_details->data['sub_admin_capabilities']) > 0 && in_array('manage_donation_list', $get_user_details->data['sub_admin_capabilities'])) ? 'selected' : ''; ?>><?php _e('Manage Donation List', THEME_TEXTDOMAIN); ?></option>-->
                        </select>                        
                    </td>
                </tr>
            </table>
            <?php
        elseif (($pagenow == 'user-edit.php') && ($get_user_details->data['role'] == 'moderator')):
            ?>
            <h3><?php _e("Sub Administrator Additional Information", "blank"); ?></h3>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e("State"); ?></th>
                    <td>
                        <select name="state" class="attribute_admin_state chosen">
                            <option value=""><?php _e('-Select State-', THEME_TEXTDOMAIN); ?></option>
                            <?php if (is_array($getStates) && count($getStates) > 0): ?>                        
                                <?php foreach ($getStates as $eachState): ?>
                                    <option value="<?php echo $eachState->term_id; ?>" <?php echo ($eachState->term_id == $get_user_details->data['state']) ? 'selected' : ''; ?>><?php echo $eachState->name; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e("City"); ?></th>
                    <td>
                        <select name="city" class="attribute_city chosen" >
                            <option value=""><?php _e('-Select City-', THEME_TEXTDOMAIN); ?></option>
                            <?php if (is_array($getCities) && count($getCities) > 0): ?>                        
                                <?php foreach ($getCities as $eachCity): ?>
                                    <option value="<?php echo $eachCity->term_id; ?>" <?php echo (is_array($get_user_details->data['city']) && count($get_user_details->data['city']) > 0 && in_array($eachCity->term_id, $get_user_details->data['city'])) ? 'selected' : ''; ?>><?php echo $eachCity->name; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
            </table>
            <?php
        elseif ($pagenow == 'user-new.php'):
            ?>
            <table class="form-table">
                <tbody>
                    <tr class="form-field">
                        <th scope="row"><?php _e("State"); ?></th>
                        <td>
                            <select name="state" class="attribute_state chosen">
                                <option value=""><?php _e('-Select state-', THEME_TEXTDOMAIN); ?></option>
                                <?php if (is_array($getStates) && count($getStates) > 0): ?>                        
                                    <?php foreach ($getStates as $eachState): ?>
                                        <option value="<?php echo $eachState->term_id; ?>" <?php echo ($eachState->term_id == $get_user_details->data['state']) ? 'selected' : ''; ?>><?php echo $eachState->name; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </td>
                    </tr>
                    <tr class="form-field">
                        <th scope="row"><?php _e("City"); ?></th>
                        <td>
                            <select name="city" class="attribute_city chosen ttgs" multiple>
                                <?php if (is_array($getCities) && count($getCities) > 0): ?>                        
                                    <?php foreach ($getCities as $eachCity): ?>
                                        <option value="<?php echo $eachCity->term_id; ?>" <?php echo ($eachCity->term_id == $get_user_details->data['city']) ? 'selected' : ''; ?>><?php echo $eachCity->name; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php
        endif;
    }

}

add_action('personal_options_update', 'subadmin_save_extra_profile_fields');
add_action('edit_user_profile_update', 'subadmin_save_extra_profile_fields');
add_action('user_register', 'subadmin_save_extra_profile_fields');

function subadmin_save_extra_profile_fields($user_id) {

    $state = $_POST['state'];
    $city = $_POST['city'];
    $phone = $_POST['phone'];
    $subadmin_capabilities = $_POST['subadmin_capabilities'];

    if (!current_user_can('edit_user', $user_id))
        return false;
    update_user_meta($user_id, '_state', $state);
    if(is_array($city) && count($city) > 0){
        $uniqueCities = array_unique($city);
        update_user_meta($user_id, '_city', $uniqueCities);
    }
    update_user_meta($user_id, '_mobile_no', $phone);
    
        $GeneralThemeFunc = new GeneralTheme();
        $get_user_details = $GeneralThemeFunc->user_details($user_id);
    if($get_user_details->data['role'] == 'sub_admin') {
        update_user_meta($user_id, '_sub_admin_capabilities', $subadmin_capabilities);
       if(in_array('manage_users', $subadmin_capabilities)) {
           $subadmin = get_role('sub_admin');
           $subadmin->add_cap('list_users');
           $subadmin->add_cap('delete_users');
           $subadmin->add_cap('edit_users');
       } else {
           $subadmin = get_role('sub_admin');
           $subadmin->add_cap('list_users');
           $subadmin->add_cap('delete_users');
           $subadmin->add_cap('edit_users');
       } 
    } 
}
