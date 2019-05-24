<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!function_exists('announcement_settings_func')) {

    function announcement_settings_func() {
        $get_announcement_price = get_option('_announcement_price');
        if (isset($_POST['announcement_settings_sbmt'])) {
            $announcement_price = $_POST['announcement_price'];

            update_option('_announcement_price', $announcement_price);
            //wp_redirect(admin_url().'admin.php?page=announcement-settings');
            wp_redirect(admin_url() . 'edit.php?post_type=andr_announcement&page=announcement-settings');
            exit;
        }
        ?>
        <h2><?php _e('Announcement Settings', THEME_TEXTDOMAIN); ?></h2>
        <div class="wrap">
            <form name="announcement_settings_frm" id="announcement_settings_frm" method="post" action="">
                <table class="widefat">
                    <thead>
                    <th colspan="2"><strong><?php _e('Gold Announcement Settings', THEME_TEXTDOMAIN); ?></strong></th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong><?php _e('Price for 1 week (per day): ', THEME_TEXTDOMAIN); ?></strong></td>
                            <td><input type="text" name="announcement_price[gold][7]" value="<?php echo $get_announcement_price['gold']['7']; ?>" placeholder="<?php _e('E.g. 15.00', THEME_TEXTDOMAIN); ?>" autocomplete="off"/></td>
                        </tr>
                        <tr>
                            <td><strong><?php _e('Price for 2 weeks (per day): ', THEME_TEXTDOMAIN); ?></strong></td>
                            <td><input type="text" name="announcement_price[gold][14]" value="<?php echo $get_announcement_price['gold']['14']; ?>" placeholder="<?php _e('E.g. 10.00', THEME_TEXTDOMAIN); ?>" autocomplete="off"/></td>
                        </tr>
                        <tr>
                            <td><strong><?php _e('Price for 1 month (per day): ', THEME_TEXTDOMAIN); ?></strong></td>
                            <td><input type="text" name="announcement_price[gold][30]" value="<?php echo $get_announcement_price['gold']['30']; ?>" placeholder="<?php _e('E.g. 5.00', THEME_TEXTDOMAIN); ?>" autocomplete="off"/></td>
                        </tr>
                        <tr>
                            <td><strong><?php _e('Maximum No of post can create: ', THEME_TEXTDOMAIN); ?></strong></td>
                            <td><input type="text" name="announcement_price[gold][no_of_post]" value="<?php echo $get_announcement_price['gold']['no_of_post']; ?>" placeholder="<?php _e('E.g. 20', THEME_TEXTDOMAIN); ?>" autocomplete="off"/></td>
                        </tr>
                        <tr>
                            <td><strong><?php _e('No of appearance on slider a day: ', THEME_TEXTDOMAIN); ?></strong></td>
                            <td><input type="text" name="announcement_price[gold][no_of_appearance]" value="<?php echo $get_announcement_price['gold']['no_of_appearance']; ?>" placeholder="<?php _e('E.g. 5', THEME_TEXTDOMAIN); ?>" autocomplete="off"/></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <table class="widefat">
                    <thead>
                    <th colspan="2"><strong><?php _e('Silver Announcement Settings', THEME_TEXTDOMAIN); ?></strong></th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong><?php _e('Price for 1 week (per day): ', THEME_TEXTDOMAIN); ?></strong></td>
                            <td><input type="text" name="announcement_price[silver][7]" value="<?php echo $get_announcement_price['silver']['7']; ?>" placeholder="<?php _e('E.g. 15.00', THEME_TEXTDOMAIN); ?>" autocomplete="off"/></td>
                        </tr>
                        <tr>
                            <td><strong><?php _e('Price for 2 weeks (per day): ', THEME_TEXTDOMAIN); ?></strong></td>
                            <td><input type="text" name="announcement_price[silver][14]" value="<?php echo $get_announcement_price['silver']['14']; ?>" placeholder="<?php _e('E.g. 10.00', THEME_TEXTDOMAIN); ?>" autocomplete="off"/></td>
                        </tr>
                        <tr>
                            <td><strong><?php _e('Price for 1 month (per day): ', THEME_TEXTDOMAIN); ?></strong></td>
                            <td><input type="text" name="announcement_price[silver][30]" value="<?php echo $get_announcement_price['silver']['30']; ?>" placeholder="<?php _e('E.g. 5.00', THEME_TEXTDOMAIN); ?>" autocomplete="off"/></td>
                        </tr>
                        <tr>
                            <td><strong><?php _e('Maximum No of post can create: ', THEME_TEXTDOMAIN); ?></strong></td>
                            <td><input type="text" name="announcement_price[silver][no_of_post]" value="<?php echo $get_announcement_price['silver']['no_of_post']; ?>" placeholder="<?php _e('E.g. 15', THEME_TEXTDOMAIN); ?>" autocomplete="off"/></td>
                        </tr>
                        <tr>
                            <td><strong><?php _e('No of appearance on slider a day: ', THEME_TEXTDOMAIN); ?></strong></td>
                            <td><input type="text" name="announcement_price[silver][no_of_appearance]" value="<?php echo $get_announcement_price['silver']['no_of_appearance']; ?>" placeholder="<?php _e('E.g. 3', THEME_TEXTDOMAIN); ?>" autocomplete="off"/></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <table class="widefat">
                    <thead>
                    <th colspan="2"><strong><?php _e('Bronze Announcement Settings', THEME_TEXTDOMAIN); ?></strong></th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong><?php _e('Price for 1 week (per day): ', THEME_TEXTDOMAIN); ?></strong></td>
                            <td><input type="text" name="announcement_price[bronze][7]" value="<?php echo $get_announcement_price['bronze']['7']; ?>" placeholder="<?php _e('E.g. 15.00', THEME_TEXTDOMAIN); ?>" autocomplete="off"/></td>
                        </tr>
                        <tr>
                            <td><strong><?php _e('Price for 2 weeks (per day): ', THEME_TEXTDOMAIN); ?></strong></td>
                            <td><input type="text" name="announcement_price[bronze][14]" value="<?php echo $get_announcement_price['bronze']['14']; ?>" placeholder="<?php _e('E.g. 10.00', THEME_TEXTDOMAIN); ?>" autocomplete="off"/></td>
                        </tr>
                        <tr>
                            <td><strong><?php _e('Price for 1 month (per day): ', THEME_TEXTDOMAIN); ?></strong></td>
                            <td><input type="text" name="announcement_price[bronze][30]" value="<?php echo $get_announcement_price['bronze']['30']; ?>" placeholder="<?php _e('E.g. 5.00', THEME_TEXTDOMAIN); ?>" autocomplete="off"/></td>
                        </tr>
                        <tr>
                            <td><strong><?php _e('Maximum no of post can create: ', THEME_TEXTDOMAIN); ?></strong></td>
                            <td><input type="text" name="announcement_price[bronze][no_of_post]" value="<?php echo $get_announcement_price['bronze']['no_of_post']; ?>" placeholder="<?php _e('E.g. 5', THEME_TEXTDOMAIN); ?>" autocomplete="off"/></td>
                        </tr>
                        <tr>
                            <td><strong><?php _e('No of appearance on slider a day: ', THEME_TEXTDOMAIN); ?></strong></td>
                            <td><input type="text" name="announcement_price[bronze][no_of_appearance]" value="<?php echo $get_announcement_price['bronze']['no_of_appearance']; ?>" placeholder="<?php _e('E.g. 1', THEME_TEXTDOMAIN); ?>" autocomplete="off"/></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <div>
                    <button class="button button-primary" name="announcement_settings_sbmt"><?php _e('Save', THEME_TEXTDOMAIN); ?></button>
                </div>
            </form>
        </div>
        <?php
    }

}

if (!function_exists('andr_product_city_management_func')) {

    function andr_product_city_management_func($tag) {
        $t_id = $tag->term_id; // Get the ID of the term you're editing 
        $get_enable_announcement = get_term_meta($t_id, '_enable_announcement', TRUE);
        $get_enable_announcement_for_suppliers = get_term_meta($t_id, '_enable_announcement_for_suppliers', TRUE);
        $get_enable_announcement_for_customers = get_term_meta($t_id, '_enable_announcement_for_customers', TRUE);
        ?>  

        <tr class="form-field">  
            <th scope="row" valign="top">  
                <label><?php _e('Enable Announcement Module'); ?></label>  
            </th>  
            <td>
                <input type="checkbox" name="enable_announcement" id="enable_announcement" value="1" <?php checked('1', $get_enable_announcement); ?>><?php _e('Enable', THEME_TEXTDOMAIN); ?>
                <p class="description"><?php _e('Checking this will enable announcement for this city.'); ?></p><br/>
                <div class="show-user-announcement-enabling" style="<?php echo ($get_enable_announcement == 1) ? 'display:block;' : 'display:none;'; ?>">
                    <!--<label for="enable_announcement_for_suppliers">-->
                    <input type="checkbox" name="enable_announcement_for_suppliers" id="enable_announcement_for_suppliers" value="1" <?php checked('1', $get_enable_announcement_for_suppliers); ?>><?php _e('Enable for suppliers', THEME_TEXTDOMAIN); ?>
                    <!--</label>-->
                    <p class="description"><?php _e('Checking this will enable announcement for suppliers.'); ?></p><br/>
                    <!--<label for="enable_announcement_for_customers">-->
                    <input type="checkbox" name="enable_announcement_for_customers" id="enable_announcement_for_customers" value="1" <?php checked('1', $get_enable_announcement_for_customers); ?>><?php _e('Enable for customers', THEME_TEXTDOMAIN); ?>
                    <!--</label>-->
                    <p class="description"><?php _e('Checking this will enable announcement for customers.'); ?></p><br/>
                </div>
            </td>  
        </tr>  

        <?php
    }

}

if (!function_exists('save_taxonomy_custom_fields')) {

    function save_taxonomy_custom_fields($term_id) {
        $t_id = $term_id;
        $enable_announcement_for_suppliers = $_POST['enable_announcement_for_suppliers'];
        $enable_announcement_for_customers = $_POST['enable_announcement_for_customers'];
        update_term_meta($term_id, '_enable_announcement', $_POST['enable_announcement']);
        if (isset($_POST['enable_announcement'])) {
            update_term_meta($term_id, '_enable_announcement_for_suppliers', $enable_announcement_for_suppliers);
            update_term_meta($term_id, '_enable_announcement_for_customers', $enable_announcement_for_customers);
        }
    }

}