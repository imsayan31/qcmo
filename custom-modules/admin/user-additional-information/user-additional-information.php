<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

add_action('show_user_profile', 'andr_extra_customer_profile_fields');
add_action('edit_user_profile', 'andr_extra_customer_profile_fields');

/**
 * Adding form fields in edit screen
 */
if (!function_exists('andr_extra_customer_profile_fields')) {

    function andr_extra_customer_profile_fields($user) {
        global $pagenow;
        $GeneralThemeFunc = new GeneralTheme();
        $get_user_details = $GeneralThemeFunc->user_details($user->ID);
        $getStates = $GeneralThemeFunc->getCities();
        $getCities = $GeneralThemeFunc->getCities($get_user_details->data['state']);
        $getProductCategories = get_terms(themeFramework::$theme_prefix . 'product_category', ['hide_empty' => FALSE]);
        $user_pro_pic = wp_get_attachment_image_src($get_user_details->data['pro_pic'], 'full');
        $admin_activate = get_user_meta($user->ID, '_admin_approval', TRUE);
        if ($admin_activate == 1) {
        $admin_active = 'selected="selected"';
    } elseif ($admin_activate == 2) {
        $admin_disabled = 'selected="selected"';
    }
        if (($pagenow == 'user-edit.php') && ($get_user_details->data['role'] == 'subscriber')):
            ?>
            <h3><?php _e("User Additional Information", "blank"); ?></h3>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e("Avatar"); ?></th>
                    <td>
                        <img src="<?php echo ($get_user_details->data['pro_pic_exists'] == TRUE) ? $user_pro_pic[0] : 'http://via.placeholder.com/100x100'; ?>" width="100" height="100" />
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e("State"); ?></th>
                    <td>
                        <select name="state" class="attribute_state chosen">
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
                        <select name="city" class="attribute_city chosen">
                            <?php if (is_array($getCities) && count($getCities) > 0): ?>                        
                                <?php foreach ($getCities as $eachCity): ?>
                                    <option value="<?php echo $eachCity->term_id; ?>" <?php echo ($eachCity->term_id == $get_user_details->data['city']) ? 'selected' : ''; ?>><?php echo $eachCity->name; ?></option>
                                    <!-- <option value="<?php echo $eachCity->term_id; ?>" <?php echo (is_array($get_user_details->data['city']) && count($get_user_details->data['city']) > 0 && in_array($eachCity->term_id, $get_user_details->data['city'])) ? 'selected' : ''; ?>><?php echo $eachCity->name; ?></option> -->
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e("Phone number"); ?></th>
                    <td>
                        <input type="text" name="phone" readonly value="<?php echo $get_user_details->data['phone']; ?>" placeholder="<?php _e('Phone number', THEME_TEXTDOMAIN); ?>"/>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e("Commercial Phone number"); ?></th>
                    <td>
                        <input type="text" name="cphone" readonly value="<?php echo $get_user_details->data['lphone']; ?>" placeholder="<?php _e('Commercial Phone number', THEME_TEXTDOMAIN); ?>"/>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e("Date of Birth"); ?></th>
                    <td>
                        <input type="text" name="dob" readonly value="<?php echo $get_user_details->data['dob']; ?>" placeholder="<?php _e('Date of birth', THEME_TEXTDOMAIN); ?>"/>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e("Genre"); ?></th>
                    <td>
                        <input type="radio" name="genre" value="male" readonly <?php echo ($get_user_details->data['genre'] == 'male') ? 'checked' : ''; ?>/> <?php _e('Male', THEME_TEXTDOMAIN); ?>
                        <input type="radio" name="genre" value="female" readonly <?php echo ($get_user_details->data['genre'] == 'female') ? 'checked' : ''; ?>/> <?php _e('Female', THEME_TEXTDOMAIN); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e("Address"); ?></th>
                    <td>
                        <input type="text" name="address" size="70" readonly value="<?php echo $get_user_details->data['address']; ?>" placeholder="<?php _e('Address', THEME_TEXTDOMAIN); ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e("status do usuário",THEME_TEXTDOMAIN); ?></th>
                    <td>
                        <select name='user_activation_status'>
                            <option ><?php _e("--Choose Status--",THEME_TEXTDOMAIN); ?></option>
                            <option <?php echo  $admin_active;?> value=1 ><?php _e("Activate",THEME_TEXTDOMAIN); ?></option>
                            <option <?php echo $admin_disabled;?>  value=2 ><?php _e("Deactivate",THEME_TEXTDOMAIN); ?></option>
                        </select>
                    </td>
                </tr>
            </table>
            <?php
        endif;
    }

}

add_action('personal_options_update', 'customer_save_extra_profile_fields');
add_action('edit_user_profile_update', 'customer_save_extra_profile_fields');

function customer_save_extra_profile_fields($user_id) {

    $state = $_POST['state'];
    $city = $_POST['city'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $genre = $_POST['genre'];
    $address = $_POST['address'];
    $cphone = $_POST['cphone'];
    $userActiveStatus = $_POST['user_activation_status'];

    if (!current_user_can('edit_user', $user_id))
        return false;
    update_user_meta($user_id, '_state', $state);
    update_user_meta($user_id, '_city', $city);
    update_user_meta($user_id, '_mobile_no', $phone);
    update_user_meta($user_id, '_commercial_no', $cphone);
    update_user_meta($user_id, '_dob', $dob);
    update_user_meta($user_id, '_genre', $genre);
    update_user_meta($user_id, '_address', $address);
    update_user_meta($user_id, '_admin_approval', $userActiveStatus);
}
