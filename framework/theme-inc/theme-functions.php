<?php
/**
 * ------------------------------------------
 * THEME FUNC:: Contain All type of functions
 * and filters.
 * ------------------------------------------
 */

/**
 * -----------------------------------------
 * LOCATE TEMP::Template locator
 * -----------------------------------------
 */
function theme_template_part($file) {
    $theme_temp = MODULE_PATH . "/frontend/{$file}.php";
    load_template($theme_temp, true);
}

function add_theme_caps() {

    $admins = get_role('administrator');
    $moderator_object = get_role('moderator');
    $sub_admin_object = get_role('sub_admin');

    $allCaps = ['publish', 'delete', 'delete_others', 'delete_private', 'delete_published', 'edit', 'edit_others', 'edit_private', 'edit_published', 'read_private'];
    //$allCaps = ['publish', 'read_private'];
    foreach ($allCaps as $cap) {
        $admins->add_cap("{$cap}_" . themeFramework::$theme_prefix . "advertisement");
        $moderator_object->add_cap("{$cap}_" . themeFramework::$theme_prefix . "advertisement");
        $sub_admin_object->add_cap("{$cap}_" . themeFramework::$theme_prefix . "announcement");
        $sub_admin_object->add_cap("{$cap}_" . themeFramework::$theme_prefix . "announcements");
    }
    $sub_admin_object->add_cap("read");
    $sub_admin_object->add_cap("read_" . themeFramework::$theme_prefix . "announcement");

    $sub_admin_object->add_cap('edit_' . themeFramework::$theme_prefix . 'product');
    $sub_admin_object->add_cap('edit_others_' . themeFramework::$theme_prefix . 'product');
    $sub_admin_object->add_cap('edit_private_' . themeFramework::$theme_prefix . 'product');
    $sub_admin_object->add_cap('edit_published_' . themeFramework::$theme_prefix . 'product');
    //$sub_admin_object->add_cap('list_users');

    /* $sub_admin_object->add_cap('read');
      $sub_admin_object->add_cap('read_post');
      $sub_admin_object->add_cap('edit_' . themeFramework::$theme_prefix . 'announcement');
      $sub_admin_object->add_cap('edit_others_' . themeFramework::$theme_prefix . 'announcement');
      $sub_admin_object->add_cap('edit_private_' . themeFramework::$theme_prefix . 'announcement');
      $sub_admin_object->add_cap('edit_published_' . themeFramework::$theme_prefix . 'announcement'); */
    //$moderator_object->add_cap("upload_files");

    /* $admins->add_cap('edit_' . themeFramework::$theme_prefix . 'advertisements');
      $admins->add_cap('edit_other_' . themeFramework::$theme_prefix . 'advertisements');
      $admins->add_cap('edit_private_' . themeFramework::$theme_prefix . 'advertisements');
      $admins->add_cap('edit_published_' . themeFramework::$theme_prefix . 'advertisements');
      $admins->add_cap('publish_' . themeFramework::$theme_prefix . 'advertisements');
      $admins->add_cap('read_' . themeFramework::$theme_prefix . 'advertisement');
      $admins->add_cap('read_private_' . themeFramework::$theme_prefix . 'advertisements');
      $admins->add_cap('delete_' . themeFramework::$theme_prefix . 'advertisement');
      $admins->add_cap('delete_published_' . themeFramework::$theme_prefix . 'advertisements');
      $admins->add_cap('delete_private_' . themeFramework::$theme_prefix . 'advertisements'); */
}

add_action('admin_init', 'add_theme_caps');

/*
 * Remove avatar from user list
 * 
 */

if (!function_exists('remove_avatar_from_users_list')) {

    function remove_avatar_from_users_list($avatar) {
        if (is_admin()) {
            global $current_screen;
            if ($current_screen->base == 'users') {
                $avatar = '';
            }
        }
        return $avatar;
    }

}

/*
 * --------------------------------------------
 * This funtion will activate user account
 * --------------------------------------------
 */
if (!function_exists('activateUserAccount')) {

    function activateUserAccount() {
        if (isset($_GET['actv_code']) && $_GET['actv_code'] != '') {
            $activeCode = $_GET['actv_code'];
            $getUser = get_users(['role' => 'subscriber', 'meta_key' => '_active_code', 'meta_value' => $activeCode]);
            if (is_array($getUser) && count($getUser) > 0) {
                foreach ($getUser as $eachUser) {
                    update_user_meta($eachUser->ID, '_active_status', 1);
                    update_user_meta($eachUser->ID, '_active_code', NULL);
                }
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function ($) {
                        $.notify({message: '<?php echo __('Your account has been activated successfully.', THEME_TEXTDOMAIN); ?>'}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                        $('#user_login_popup').modal('show');
                    });
                </script>
                <?php
            } else {
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function ($) {
                        $.notify({message: '<?php echo __('Sorry!!! User not found as per your request.', THEME_TEXTDOMAIN); ?>'}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
                        window.location.href = "<?php echo BASE_URL; ?>";
                    });
                </script>
                <?php
            }
        }
    }

}

/*
 * --------------------------------------------
 * This funtion will activate user account
 * --------------------------------------------
 */
if (!function_exists('activateSuplierAccount')) {

    function activateSuplierAccount() {
        if (isset($_GET['supplier_actv_code']) && $_GET['supplier_actv_code'] != '') {
            $activeCode = $_GET['supplier_actv_code'];
            $getUser = get_users(['role' => 'supplier', 'meta_key' => '_active_code', 'meta_value' => $activeCode]);
            if (is_array($getUser) && count($getUser) > 0) {
                foreach ($getUser as $eachUser) {
                    update_user_meta($eachUser->ID, '_active_status', 1);
                    update_user_meta($eachUser->ID, '_active_code', NULL);
                }
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function ($) {
                        $.notify({message: '<?php echo __('Your account has been activated successfully.', THEME_TEXTDOMAIN); ?>'}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                        $('#user_login_popup').modal('show');
                    });
                </script>
                <?php
            } else {
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function ($) {
                        $.notify({message: '<?php echo __('Sorry!!! User not found as per your request.', THEME_TEXTDOMAIN); ?>'}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
                        window.location.href = "<?php echo BASE_URL; ?>";
                    });
                </script>
                <?php
            }
        }
    }

}


/*
 * --------------------------------------------
 * This funtion will reset user password
 * --------------------------------------------
 */
if (!function_exists('resetUserPassword')) {

    function resetUserPassword() {
        if (isset($_GET['resetUser']) && $_GET['resetUser'] != '') {
            $activeCode = base64_decode($_GET['resetUser']);
            $getUserBy = get_user_by('id', $activeCode);
            if ($getUserBy) {
                $getResetLink = get_user_meta($getUserBy->ID, '_reset_pass_link', TRUE);
                if ($getResetLink) {
                    delete_user_meta($getUserBy->ID, '_reset_pass_link');
                    ?>
                    <script type="text/javascript">
                        jQuery(document).ready(function ($) {
                            $.notify({message: '<?php echo __('Reset your password here.', THEME_TEXTDOMAIN); ?>'}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                            $('#reset_user').val('<?php echo $_GET['resetUser'] ?>');
                            $('#user_reset_password_popup').modal('show');
                        });
                    </script>
                    <?php
                } else {
                    ?>
                    <script type="text/javascript">
                        jQuery(document).ready(function ($) {
                            $.notify({message: '<?php echo __('Sorry!!! Password already has been reset.', THEME_TEXTDOMAIN); ?>'}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
                            window.location.href = "<?php echo BASE_URL; ?>";
                        });
                    </script>
                    <?php
                }
            } else {
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function ($) {
                        $.notify({message: '<?php echo __('Sorry!!! User not found as per your request.', THEME_TEXTDOMAIN); ?>'}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
                        window.location.href = "<?php echo BASE_URL; ?>";
                    });
                </script>
                <?php
            }
        }
    }

}

/*
 * --------------------------------------------
 * Set Product Current Price in Meta
 * --------------------------------------------
 */
if (!function_exists('setProductCurrentPriceInMeta')) {

    function setProductCurrentPriceInMeta() {
        $GeneralThemeObject = new GeneralTheme();
        $getProducts = get_posts(['post_type' => themeFramework::$theme_prefix . 'product', 'posts_per_page' => -1]);
        if (is_array($getProducts) && count($getProducts) > 0) {
            foreach ($getProducts as $eachProduct) {
                $userCity = $GeneralThemeObject->getLandingCity();
                $getProductPrice = $GeneralThemeObject->getProductPrice($eachProduct->ID, $userCity);
                /* echo '<pre>';
                  print_r($eachProduct->ID . ' - ' . $getProductPrice);
                  echo '</pre>'; */
                update_post_meta($eachProduct->ID, '_product_current_city_price', $getProductPrice);

                if (is_numeric($getProductPrice)) {

                    update_post_meta($eachProduct->ID, '_product_current_city_numeric_price', ($getProductPrice * 100));
                }
            }
        }
    }

}
//add_action('init', 'setProductCurrentPriceInMeta', 10);

/*
 * --------------------------------------------
 * Set Announcement Activity
 * --------------------------------------------
 */
if (!function_exists('setAnnouncementActivity')) {

    function setAnnouncementActivity() {
        if (is_admin()) {
            return;
        }
        $currDate = strtotime(date('Y-m-d'));
        $getAnnouncements = get_posts(['post_type' => themeFramework::$theme_prefix . 'announcement', 'posts_per_page' => -1]);
        if (is_array($getAnnouncements) && count($getAnnouncements) > 0) {
            foreach ($getAnnouncements as $eachAnnouncement) {
                $get_start_date = get_post_meta($eachAnnouncement->ID, '_start_date', TRUE);
                $get_number_of_days = get_post_meta($eachAnnouncement->ID, '_number_of_days', TRUE);
                $end_date = get_post_meta($eachAnnouncement->ID, '_end_date', TRUE);
                //$get_end_date = strtotime('+' . $get_number_of_days . ' days', $get_start_date);
                if ($get_start_date != '' && $end_date != '') {
                    if ($currDate > $get_end_date) {
                        update_post_meta($eachAnnouncement->ID, '_announcement_enabled', 2);
                    }
                }
            }
        }
    }

}
add_action('init', 'setAnnouncementActivity');

/*
 * --------------------------------------------
 * Sent Announcement Notification to the author.
 * --------------------------------------------
 */
if (!function_exists('sentNotificationForRenew')) {

    function sentNotificationForRenew() {
        $GeneralThemeObject = new GeneralTheme();
        $currDate = strtotime(date('Y-m-d'));
        $author = get_the_author();
        $getAnnouncements = get_posts(['post_type' => themeFramework::$theme_prefix . 'announcement', 'posts_per_page' => -1]);


        if (is_array($getAnnouncements) && count($getAnnouncements) > 0) {
            foreach ($getAnnouncements as $eachAnnouncement) {

                $author_id = $eachAnnouncement->post_author;
                $user_data = get_userdata($author_id);
                $email = $user_data->user_login;

                $get_start_date = get_post_meta($eachAnnouncement->ID, '_start_date', TRUE);
                $get_number_of_days = get_post_meta($eachAnnouncement->ID, '_number_of_days', TRUE);
                $get_end_date = strtotime('+' . $get_number_of_days . ' days', strtotime($get_start_date));
                if ($currDate > $get_end_date) {


                    $customer_email_content = $GeneralThemeObject->getEmailContents('sent-notification-to-the-author-for-renew', ['{%user_name%}', '{%announce_title%}'], [$user_data->first_name, $eachAnnouncement->post_title]);
                    $customer_email_subject = get_bloginfo('name') . ' :: ' . $customer_email_content[0];
                    $customer_email_template = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $customer_email_content[1]);
                    $GeneralThemeObject->send_mail_func($email, $customer_email_subject, $customer_email_template);
                }
            }
        }
    }

}
//add_action('init', 'sentNotificationForRenew');
//setProductCurrentPriceInMeta();

add_filter('manage_andr_product_posts_columns', 'andre_products_columns_head');
add_action('manage_andr_product_posts_custom_column', 'andre_products_columns_content', 10, 2);

add_filter('manage_andr_advertisement_posts_columns', 'andre_advertisement_columns_head');
add_action('manage_andr_advertisement_posts_custom_column', 'andre_advertisement_columns_content', 10, 2);

add_filter('manage_andr_announcement_posts_columns', 'andre_announcements_columns_head');
add_action('manage_andr_announcement_posts_custom_column', 'andre_announcements_columns_content', 10, 2);

// ADD NEW COLUMN
function andre_products_columns_head($defaults) {
    unset($defaults['taxonomy-andr_product_city']);
    unset($defaults['date']);
    $defaults['product_type'] = 'Product Type';
    $defaults['product_views'] = 'Views';
    $defaults['product_city'] = 'Available in';
    //$defaults['total_phone_contacts'] = 'Total Phones Tracked';
    return $defaults;
}

// SHOW THE FEATURED IMAGE
function andre_products_columns_content($column_name, $post_ID) {
    $GeneralThemeObject = new GeneralTheme();
    $productDetails = $GeneralThemeObject->product_details($post_ID);
    if ($column_name == 'product_type') {
        echo ($productDetails->data['is_simple'] == true) ? 'Simple' : 'Bundle';
    } else if ($column_name == 'product_views') {
        echo ($productDetails->data['view_counter']) ? $productDetails->data['view_counter'] : 0;
    } else if ($column_name == 'product_city') {
        $productCities = get_post_meta($post_ID, '_product_cities', TRUE);
        $newProductNameArr = [];
        if(is_array($productCities) && count($productCities) > 0){
            foreach ($productCities as $eachCity) {
                $getCityDetails = get_term_by('id', $eachCity, themeFramework::$theme_prefix . 'product_city');
                $newProductNameArr[] = $getCityDetails->name;
            }
            $joinedCityName = join(', ', $newProductNameArr);
        }
        echo $joinedCityName;
    }
}

// ADD NEW COLUMN
function andre_advertisement_columns_head($defaults) {
    $defaults['adv_price'] = _e('Advertisement Price', THEME_TEXTDOMAIN);
    $defaults['adv_payment_link'] = _e('Payment Link', THEME_TEXTDOMAIN);
    return $defaults;
}

// SHOW THE FEATURED IMAGE
function andre_advertisement_columns_content($column_name, $post_ID) {
    $GeneralThemeObject = new GeneralTheme();
    $advDetails = $GeneralThemeObject->advertisement_details($post_ID); /* Get Ad Payment Data */
    $queryString = " AND `adv_id`=" . $post_ID . "";
    $getAdvPaymentData = $GeneralThemeObject->getAdvPaymentData($queryString);
    if ($column_name == 'adv_price') {
        echo ($advDetails->data['adv_price'] > 0) ? 'R$ ' . $advDetails->data['adv_price'] : 'N/A';
    } else if ($column_name == 'adv_payment_link') {
        if ($advDetails->data['author'] == get_current_user_id() && $getAdvPaymentData[0]->payment_status == 2):
            ?>
            <a href="<?php echo $getAdvPaymentData[0]->payment_url; ?>" class="button button-primary"><?php _e('Pay now', THEME_TEXTDOMAIN); ?></a>
            <?php
        elseif ($advDetails->data['author'] == get_current_user_id() && $getAdvPaymentData[0]->payment_status == 2):
            echo 'Paid';
        else:
            echo 'N/A';
        endif;
    }
}

// ADD NEW COLUMN
function andre_announcements_columns_head($defaults) {
    $defaults['announcement_approval'] = __('Approval Status', THEME_TEXTDOMAIN);
    return $defaults;
}

// SHOW THE FEATURED IMAGE
function andre_announcements_columns_content($column_name, $post_ID) {
    $AnnouncementObject = new classAnnouncement();
    $announcement_details = $AnnouncementObject->announcement_details($post_ID);
    if ($column_name == 'announcement_approval') {
        ?>
        <select name="announcement_admin_approval" class="announcement_admin_approval" data-announcement_id="<?php echo $post_ID; ?>">
            <option value=""><?php _e('-Select for approval-', THEME_TEXTDOMAIN); ?></option>
            <option value="1" <?php echo ($announcement_details->data['admin_approval'] == 1) ? 'selected' : ''; ?>><?php _e('Approve', THEME_TEXTDOMAIN); ?></option>
            <option value="2" <?php echo ($announcement_details->data['admin_approval'] == 2) ? 'selected' : ''; ?>><?php _e('Disapprove', THEME_TEXTDOMAIN); ?></option>
        </select>
        <?php
    }
}

/* User restriction on Media Library */
add_filter('ajax_query_attachments_args', 'wpb_show_current_user_attachments');

function wpb_show_current_user_attachments($query) {
    $user_id = get_current_user_id();
    if ($user_id && !current_user_can('activate_plugins') && !current_user_can('edit_others_posts')) {
        $query['author'] = $user_id;
    }
    return $query;
}

/* Reset Product View Counter Button */
add_action('admin_head', 'custom_js_to_head');

function custom_js_to_head() {
    $get_last_product_reset_time = get_option('_last_product_reset_time');
    ?>
    <script type="text/javascript">
        jQuery(function () {
            var last_reset_time = '<?php echo $get_last_product_reset_time; ?>';
            var last_reset_time_formatted = '<?php echo date('d/m/Y', $get_last_product_reset_time); ?>';
            jQuery("body.post-type-andr_product .wrap a.page-title-action").after('<a href="javascript:void(0);" class="button button-primary click-to-reset-product-view" onclick="viewResetFunction()" style="margin-left: 10px;margin-top: 10px;"><?php _e('Reset View', THEME_TEXTDOMAIN); ?></a>');
            if (last_reset_time) {
                jQuery("a.click-to-reset-product-view").after('<span class="last-reset-text">Last reset : ' + last_reset_time_formatted + '</span>');
            }

            jQuery('form#your-profile').attr('enctype', 'multipart/form-data');
        });
    </script>
    <?php
}

/* Add Featured Filter Option */
add_action('restrict_manage_posts', 'wisdom_filter_tracked_plugins');

function wisdom_filter_tracked_plugins() {
    global $typenow;
    global $wp_query;
    if ($typenow == 'andr_product') : // Your custom post type slug
        if (isset($_GET['product_featured'])) :
            $current_plugin = $_GET['product_featured']; // Check if option has been selected
        endif;
        ?>
        <select name="product_featured" id="product_featured">
            <option value="all" <?php selected('all', $current_plugin); ?>><?php _e('All Products', 'blank'); ?></option>
            <option value="featured" <?php selected('featured', $current_plugin); ?>><?php _e('Featured Products', 'blank'); ?></option>
            <option value="simple" <?php selected('simple', $current_plugin); ?>><?php _e('Simple Products', 'blank'); ?></option>
            <option value="not_featured" <?php selected('not_featured', $current_plugin); ?>><?php _e('Non-Featured Products', 'blank'); ?></option>
        </select>
        <?php
    endif;
}

/* Featured Product Query Update */
add_filter('parse_query', 'wisdom_sort_plugins_by_slug');

function wisdom_sort_plugins_by_slug($query) {
    global $pagenow;
    // Get the post type
    $post_type = isset($_GET['post_type']) ? $_GET['post_type'] : '';
    if (is_admin() && $pagenow == 'edit.php' && $post_type == 'andr_product' && isset($_GET['product_featured']) && $_GET['product_featured'] == 'featured') {
        $query->query_vars['meta_key'] = '_make_it_featured';
        $query->query_vars['meta_value'] = serialize(strval(1));
        $query->query_vars['meta_compare'] = 'LIKE';
    }
     else if (is_admin() && $pagenow == 'edit.php' && $post_type == 'andr_product' && isset($_GET['product_featured']) && $_GET['product_featured'] == 'simple') {
      $query->query_vars['meta_key'] = '_simple_product';
      // $query->query_vars['meta_value'] = 'a:1:{i:0;i:1;}';
      // $query->query_vars['meta_compare'] = 'LIKE';
      $query->query_vars['meta_value'] = ['a:1:{i:0;i:1;}', 'a:1:{i:0;s:1:"1";}'];
      $query->query_vars['meta_compare'] = 'IN';
      // $query->query_vars['meta_key'] = '_simple_product';
      // $query->query_vars['meta_value'] = '';
      // $query->query_vars['meta_compare'] = '!=';
      }  else if (is_admin() && $pagenow == 'edit.php' && $post_type == 'andr_product' && isset($_GET['product_featured']) && $_GET['product_featured'] == 'not_featured') {
        $query->query_vars['meta_key'] = '_make_it_featured';
        $query->query_vars['meta_value'] = '';
        $query->query_vars['meta_compare'] = 'LIKE';
    }
}

//add_filter( 'pre_get_users', 'filter_users_by_course_section' );

function add_course_section_filter($which) {
    global $pagenow;

    $currentUserInfo = wp_get_current_user();

    /* Available for all users */
    // if (!current_user_can('manage_options'))
    //     return;
    $get_state = ($_GET['filter_state'][0]);
    $GeneralThemeObject = new GeneralTheme();
    $userDetails = $GeneralThemeObject->user_details();
    $getProductCategories = get_terms(themeFramework::$theme_prefix . 'product_category', ['hide_empty' => FALSE, 'parent' => 0]);
    $getAllCities = $GeneralThemeObject->getCities($get_state);
    $getStates = $GeneralThemeObject->getCities();

    if($_GET['filter_status'][0]==1){
      $acselected ='selected';
      $dacselected ='';
    } elseif($_GET['filter_status'][0]==2){
      $dacselected ='selected';
      $acselected = '';
    }

    if($_GET['filter_users_per_page'][0]==20){
      $twentyselected ='selected';
      $fiftyselected ='';
      $hundredselected ='';
      $twohundredselected ='';
    } elseif($_GET['filter_users_per_page'][0]==50){
      $twentyselected ='';
      $fiftyselected ='selected';
      $hundredselected ='';
      $twohundredselected ='';
    } elseif($_GET['filter_users_per_page'][0]==100){
      $twentyselected ='';
      $fiftyselected ='';
      $hundredselected ='selected';
      $twohundredselected ='';
    } elseif($_GET['filter_users_per_page'][0]==200){
      $twentyselected ='';
      $fiftyselected ='';
      $hundredselected ='';
      $twohundredselected ='selected';
    }

    echo ' <select name="filter_status[]" style="float:none;"><option value="">Pesquisar por status</option>';
    echo '<option value = "1" '. $acselected .'> Activate User </option>';
    echo '<option value = "2" '. $dacselected .'> De-Activate User </option>';
    echo '</select>';

    echo ' <select name="filter_users_per_page[]" style="float:none;"><option value="">Número de usuários por página</option>';
    echo '<option value = "20" '. $twentyselected .'> 20 </option>';
    echo '<option value = "50" '. $fiftyselected .'> 50 </option>';
    echo '<option value = "100" '. $hundredselected .'> 100 </option>';
    echo '<option value = "200" '. $twohundredselected .'> 200 </option>';
    echo '</select>';

    echo ' <select name="filter_state[]" style="float:none;"><option value="">Selecione o estado</option>';
    if (is_array($getStates) && count($getStates) > 0) :
        foreach ($getStates as $eachCountry) :
            $selected = ($eachCountry->term_id == $_GET['filter_state'][0]) ? 'selected ="selected"' : '';
            echo '<option value="' . $eachCountry->term_id . '"' . $selected . '> ' . $eachCountry->name . '</option>';

        endforeach;
    endif;
    echo '</select>';
    echo '<select name="filter_city[]" style="float:none;"><option value="">Selecione a cidade</option>';
    if ($get_state && is_array($getAllCities) && count($getAllCities) > 0) :
        foreach ($getAllCities as $eachCity) :
            $selected = ($eachCity->term_id == $_GET['filter_city'][0]) ? 'selected ="selected"' : '';
            echo '<option value="' . $eachCity->term_id . '"' . $selected . '> ' . $eachCity->name . '</option>';

        endforeach;
    endif;
    echo '</select>';

    /* User role selection only for sub administrators */
    if ($currentUserInfo->roles[0] == 'sub_admin') {

    	if($_GET['filter_user_role'][0]== 'subscriber'){
	      $subselected ='selected';
	      $suppselected ='';
	    } elseif($_GET['filter_user_role'][0]== 'supplier'){
	      $suppselected ='selected';
	      $subselected = '';
	    }
        
	    if(is_array($currentUserInfo->get('_sub_admin_capabilities')) && count($currentUserInfo->get('_sub_admin_capabilities')) > 0){
        	if(in_array('manage_users', $currentUserInfo->get('_sub_admin_capabilities')) && in_array('manage_suppliers', $currentUserInfo->get('_sub_admin_capabilities'))){
        		echo '<select name="filter_user_role[]" style="float:none;"><option value="">Select role</option>';
        		echo '<option value="subscriber"' . $subselected . '>Subscriber</option>';
        		echo '<option value="supplier"' . $suppselected . '>Supplier</option>';
        		echo '</select>';
        	} else if(in_array('manage_users', $currentUserInfo->get('_sub_admin_capabilities')) && !in_array('manage_suppliers', $currentUserInfo->get('_sub_admin_capabilities'))){
        		echo '<select name="filter_user_role[]" style="float:none;"><option value="">Select role</option>';
        		echo '<option value="subscriber"' . $subselected . '>Subscriber</option>';
        		echo '</select>';
        	} else if(!in_array('manage_users', $currentUserInfo->get('_sub_admin_capabilities')) && in_array('manage_suppliers', $currentUserInfo->get('_sub_admin_capabilities'))){
        		echo '<select name="filter_user_role[]" style="float:none;"><option value="">Select role</option>';
        		echo '<option value="supplier"' . $suppselected . '>Supplier</option>';
        		echo '</select>';
        	} 
        }
    	
    }

    echo '<input type="submit" class="button" value="Filter">';
}

function filter_users_by_state_and_city_section($query) {
    global $pagenow, $wpdb;

    $totalUsers = $wpdb->get_var( "SELECT COUNT(`ID`) FROM {$wpdb->prefix}users" );

    /* Sub admin only have the list_users cap excpet the admin */
    if (current_user_can('list_users') && !current_user_can('manage_options')) {
        $currentUserInfo = wp_get_current_user();

        /* Filtering result for sub administrator city */
        $meta_query = array(
            'relation' => 'AND',
            array(
                'key' => '_city',
                'value' => $currentUserInfo->get('_city'),
                'compare' => 'IN'
            ),
        );

        /* Filtering result for states and cities */
        $get_city = ($_GET['filter_city'][0]);
        if (NULL !== $get_city && '' !== $get_city) {
            $meta_query[] = array(
                'key' => '_city',
                'value' => $get_city,
                'compare' => 'IN'
            );
        }

        /* Filtering result for user status for admin approval */
        $get_user_status = $_GET['filter_status'][0];
        if (NULL !== $get_user_status && '' !== $get_user_status) {
            $meta_query = [
              'relation' => 'AND',
                [
                  'key' => '_admin_approval',
                  'value' => $get_user_status,
                  'compare' => '='
                ]
            ];
        }

        $query->set('meta_query', $meta_query);

        
        if(is_array($currentUserInfo->get('_sub_admin_capabilities')) && count($currentUserInfo->get('_sub_admin_capabilities')) > 0){
        	if(in_array('manage_users', $currentUserInfo->get('_sub_admin_capabilities')) && in_array('manage_suppliers', $currentUserInfo->get('_sub_admin_capabilities'))){

        		/* Filtering result for user role */
		        $get_user_role = $_GET['filter_user_role'][0];
		        if (NULL !== $get_user_role && '' !== $get_user_role) {
		            $query->set('role', $get_user_role);
		        } else{
		        	$query->set('role__in', ['subscriber', 'supplier']);
		        }

        		
        	} else if(in_array('manage_users', $currentUserInfo->get('_sub_admin_capabilities')) && !in_array('manage_suppliers', $currentUserInfo->get('_sub_admin_capabilities'))){

        		/* Filtering result for user role */
		        $get_user_role = $_GET['filter_user_role'][0];
		        if (NULL !== $get_user_role && '' !== $get_user_role && $get_user_role == 'subscriber') {
		            $query->set('role', $get_user_role);
		        } else{
		        	$query->set('role__in', ['subscriber']);
		        }

        		
        	} else if(!in_array('manage_users', $currentUserInfo->get('_sub_admin_capabilities')) && in_array('manage_suppliers', $currentUserInfo->get('_sub_admin_capabilities'))){

		        /* Filtering result for user role */
		        $get_user_role = $_GET['filter_user_role'][0];
		        if (NULL !== $get_user_role && '' !== $get_user_role && $get_user_role == 'supplier') {
		            $query->set('role', $get_user_role);
		        } else{
		        	$query->set('role__in', ['supplier']);
		        }
        		
        	} else if(!in_array('manage_users', $currentUserInfo->get('_sub_admin_capabilities')) && !in_array('manage_suppliers', $currentUserInfo->get('_sub_admin_capabilities'))){
        		wp_redirect(admin_url());
        	}
        } 
        
    }

    /* This is for Super Administrator */
    if (is_admin() && 'users.php' == $pagenow && current_user_can('manage_options')) {
        $meta_query = [];

        $get_state = ($_GET['filter_state'][0]);
        $get_city = ($_GET['filter_city'][0]);

        if (NULL !== $get_state && '' !== $get_state) {
            $meta_query[] = array(
                'key' => '_state',
                'value' => $get_state,
                'compare' => '='
            );
        }

        if (NULL !== $get_city && '' !== $get_city) {
            $meta_query[] = array(
                'key' => '_city',
                'value' => $get_city,
                'compare' => 'IN'
            );
        }

        if (count($meta_query) > 1) {
            $meta_query['relation'] = 'AND';
        }

        if (count($meta_query) > 0) {
            $query->set('meta_query', $meta_query);
        }
    }


    if(is_admin() && 'users.php' == $pagenow && current_user_can('manage_options')){
        if(isset($_GET['filter_users_per_page'][0]) && $_GET['filter_users_per_page'][0] != ''){
            $query->set('number', $_GET['filter_users_per_page'][0]);
        } 
        /*else {
            $query->set('number', 10);
        }*/
    }
    

    return $query;
}

// Creating a shortcode to display user count
add_shortcode('user_count', 'wpb_user_count');
function wpb_user_count() {
    $usercount = count_users();
    $result = $usercount['total_users'];
    return $result;
}

add_filter('views_users', 'iw_modify_views_users');
if (!function_exists('iw_modify_views_users')) {

    function iw_modify_views_users($views) {
        $current_user = wp_get_current_user();
        if ($current_user->roles[0] == 'sub_admin') {
            return;
        }
        return $views;
    }

}

// $GeneralThemeObject = new GeneralTheme();
// $customer_email_content = $GeneralThemeObject->getEmailContents('mail-to-user-for-announcement-approval', ['{%user_name%}', '{%total_price%}', '{%announcement_name%}', '{%payment_link%}'], ['Sayan D', 'R$ 10.00', 'Test Announcement', '']);
//             $customer_email_subject = get_bloginfo('name') . ' :: ' . $customer_email_content[0];
//             $customer_email_template = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $customer_email_content[1]);
//             $GeneralThemeObject->send_mail_func('sayantadey123@gmail.com', $customer_email_subject, $customer_email_template);

/*
 *
 * Expiration Notice to User for Upgrading Membership Plans
 *
 *
*/
if(!function_exists('sendingMembershipEndingNotification')){
    function sendingMembershipEndingNotification(){
        $getSuppliers = get_users(['role' => 'supplier']);
        $MembershipObject = new classMemberShip();
        $GeneralThemeObject = new GeneralTheme();
        $currDate = date('Y-m-d');
        if(is_array($getSuppliers) && count($getSuppliers) > 0){
            foreach ($getSuppliers as $eachSupplier) {
                $getUserMembershipDetails = $MembershipObject->getUserMembershipDetails($eachSupplier->ID);
                $dateDiff = $GeneralThemeObject->date_difference($currDate, date('Y-m-d', $getUserMembershipDetails[0]->next_payment_date));
                $perDayMailSentToUser = get_user_meta($eachSupplier->ID, '_send_mail_membership_alert_'. $currDate, true);
                $supplierDetails = $GeneralThemeObject->user_details($eachSupplier->ID);
                if($dateDiff->days <= 7 && !$perDayMailSentToUser){

                    /* Mail To Supplier */
                    $get_seller_email_template = $GeneralThemeObject->getEmailContents('mail-to-supplier-for-membership-expiring-notification', ['{%user_name%}', '{%expiring_date%}', '{%membership_id%}'], [$supplierDetails->data['fname'] . ' ' . $supplierDetails->data['lname'], date('d M, Y', $getUserMembershipDetails[0]->next_payment_date, $getUserMembershipDetails[0]->order_id)]);
                   
                    $mail_subject = get_bloginfo('name') . ' :: ' . $get_seller_email_template[0];
                    $mail_cont = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $get_seller_email_template[1]);
                    $GeneralThemeObject->send_mail_func($supplierDetails->data['email'], $mail_subject, $mail_cont);

                    update_user_meta($eachSupplier->ID, '_send_mail_membership_alert_'. $currDate, 1);
                }
            }
        }
    }
}

/*
 *
 * Expiration Notice to User for Upgrading Announcement Plans
 *
 *
*/
if(!function_exists('sendingAnnouncementEndingNotification')){
    function sendingAnnouncementEndingNotification(){
        $getSuppliers = get_users(['role__in' => ['supplier', 'subscriber']]);
        $AnnouncementObject = new classAnnouncement();
        $GeneralThemeObject = new GeneralTheme();
        $currDate = date('Y-m-d');
        $getAnnouncements = get_posts(['post_type' => themeFramework::$theme_prefix . 'announcement', 'posts_per_page' => -1]);
        if(is_array($getAnnouncements) && count($getAnnouncements) > 0){
            foreach ($getAnnouncements as $eachAnnouncement) {
                $announcementDetails = $AnnouncementObject->announcement_details($eachAnnouncement->ID);
                $dateDiff = $GeneralThemeObject->date_difference($currDate, date('Y-m-d', $announcementDetails->data['end_date']));
                $perDayMailSentToUser = get_user_meta($eachSupplier->ID, '_send_mail_announcement_alert_'. $currDate, true);
                $userDetails = $GeneralThemeObject->user_details($eachSupplier->ID);
                if($dateDiff->days <= 7 && !$perDayMailSentToUser){

                    /* Mail To User */
                    $get_seller_email_template = $GeneralThemeObject->getEmailContents('mail-to-user-for-announcement-expiring-notification', ['{%user_name%}', '{%expiring_date%}', '{%announcement_title%}'], [$userDetails->data['fname'] . ' ' . $userDetails->data['lname'], date('d M, Y', strtotime($announcementDetails->data['end_date'])), $announcementDetails->data['title']]);
                   
                    $mail_subject = get_bloginfo('name') . ' :: ' . $get_seller_email_template[0];
                    $mail_cont = $GeneralThemeObject->theme_email_template(get_bloginfo('name'), $get_seller_email_template[1]);
                    $GeneralThemeObject->send_mail_func($userDetails->data['email'], $mail_subject, $mail_cont);

                    update_user_meta($eachSupplier->ID, '_send_mail_announcement_alert_'. $currDate, 1);
                }
            }
        }
    }
}

/*
 * --------------------------------------------
 * AJAX:: Price Improvement Module
 * --------------------------------------------
 */

/*add_action('wp_ajax_price_improvement_process', 'ajaxPriceImprovementProcess');

if (!function_exists('ajaxPriceImprovementProcess')) {

    function ajaxPriceImprovementProcess() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $msg = NULL;
        $improvement_type = $_POST['improvement_type'];
        $product_id = base64_decode($_POST['product_id']);
        $price_val = $_POST['price_val'];

        if (empty($list_camera_images['name'])) {
            $msg = __('Image not found.', THEME_TEXTDOMAIN);
        } else if (!in_array($list_camera_images['type'], $GeneralThemeObject->file_type_arr)) {
            $msg = __('Upload an image file.', THEME_TEXTDOMAIN);
        } else {
            $file_to_be_uploaded = $GeneralThemeObject->common_file_upload($list_camera_images);
            $camera_image_ids = $GeneralThemeObject->create_attachment($file_to_be_uploaded);

            if (is_array($camera_image_ids) && count($camera_image_ids) > 0) {
                $resp_arr['flag'] = TRUE;
                $resp_arr['attachids'] = join(',', $camera_image_ids);
            } else {
                $resp_arr['flag'] = TRUE;
                $resp_arr['attachids'] = $camera_image_ids;
            }
            $resp_arr['flagWarning'] = $uploading_val;
        }

        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}*/