<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!function_exists('advertisement_price_settings_func')) {

    function advertisement_price_settings_func() {
        $GeneralThemeObject = new GeneralTheme();
        $getStates = $GeneralThemeObject->getCities();
        $getCategories = get_terms(themeFramework::$theme_prefix . 'product_category', ['hide_empty' => FALSE, 'parent' => 0]);
        $getTemplatePages = get_posts(['post_type' => 'page', 'posts_per_page' => -1]);
        $getHourlyTimeSlots = $GeneralThemeObject->getHourlyTimeSlots();

        $get_advert_link_price = get_option('_advert_link_price');
        $get_advert_position_price = get_option('_advert_position_price');
        $get_advert_city_price = get_option('_advert_city_price');
        $get_advert_category_price = get_option('_advert_category_price');
        $get_advert_page_price = get_option('_advert_page_price');
        $get_advert_time_price = get_option('_advert_time_price');

        if (isset($_POST['advert_price_settings_sbmt'])) {
            $advert_link_price = $_POST['advert_link_price'];
            $advert_position_price = $_POST['advert_position_price'];
            $advert_city_price = $_POST['advert_city_price'];
            $advert_category_price = $_POST['advert_category_price'];
            $advert_page_price = $_POST['advert_page_price'];
            $advert_time_price = $_POST['advert_time_price'];

            /* Link price */
            update_option('_advert_link_price', $advert_link_price);
            /* Position price */
            update_option('_advert_position_price', $advert_position_price);
            /* City price */
            update_option('_advert_city_price', $advert_city_price);
            /* Category price */
            update_option('_advert_category_price', $advert_category_price);
            /* Page price */
            update_option('_advert_page_price', $advert_page_price);
            /* Time price */
            update_option('_advert_time_price', $advert_time_price);

            wp_redirect(admin_url() . 'admin.php?page=advertisement-price-settings');
            exit;
        }
        ?>
        <h2><?php _e('Advertisement Price Settings(in R$)', THEME_TEXTDOMAIN); ?></h2>
        <div class="wrap">
            <form name="advert_price_settings_frm" id="advert_price_settings_frm" method="post" action="">
                <table class="widefat">
                    <thead>
                    <th colspan="2"><span style="font-size: 20px;"><strong><?php _e('Link', THEME_TEXTDOMAIN); ?></strong></span></th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong><?php _e('Price For Advertisement Link:', THEME_TEXTDOMAIN); ?></strong></td>
                            <td>
                                <input type="text" name="advert_link_price[link]" value="<?php echo ($get_advert_link_price['link']) ? $get_advert_link_price['link'] : ''; ?>" placeholder="<?php _e('E.g. 10.00', THEME_TEXTDOMAIN); ?>"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <table class="widefat">
                    <thead>
                    <th colspan="2"><span style="font-size: 20px;"><strong><?php _e('Position', THEME_TEXTDOMAIN); ?></strong></span></th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong><?php _e('Price For Top Position:', THEME_TEXTDOMAIN); ?></strong></td>
                            <td>
                                <input type="text" name="advert_position_price[top]" value="<?php echo ($get_advert_position_price['top']) ? $get_advert_position_price['top'] : ''; ?>" placeholder="<?php _e('E.g. 10.00', THEME_TEXTDOMAIN); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td><strong><?php _e('Price For Middle Position:', THEME_TEXTDOMAIN); ?></strong></td>
                            <td>
                                <input type="text" name="advert_position_price[middle]" value="<?php echo ($get_advert_position_price['middle']) ? $get_advert_position_price['middle'] : ''; ?>" placeholder="<?php _e('E.g. 10.00', THEME_TEXTDOMAIN); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td><strong><?php _e('Price For Bottom Position:', THEME_TEXTDOMAIN); ?></strong></td>
                            <td>
                                <input type="text" name="advert_position_price[bottom]" value="<?php echo ($get_advert_position_price['bottom']) ? $get_advert_position_price['bottom'] : ''; ?>" placeholder="<?php _e('E.g. 10.00', THEME_TEXTDOMAIN); ?>"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br>
                
                <table class="widefat">
                    <thead>
                    <th colspan="2"><span style="font-size: 20px;"><strong><?php _e('States & Cities', THEME_TEXTDOMAIN); ?></strong></span></th>
                    </thead>
                </table>
                <div style="max-height: 400px; overflow: auto;">
                    <table class="widefat">
                        <tbody>
                            <!-- <tr>
                                <td><strong><?php _e('Price for all cities per one state:', THEME_TEXTDOMAIN); ?></strong></td>
                                <td><input type="text" name="advert_city_price[all]" value="<?php echo ($get_advert_city_price['all']) ? $get_advert_city_price['all'] : ''; ?>" placeholder="<?php _e('E.g. 10.00', THEME_TEXTDOMAIN); ?>"/></td>
                            </tr> -->
                            <?php
                            if (is_array($getStates) && count($getStates) > 0):
                                foreach ($getStates as $eachState):
                                    $getStateCities = $GeneralThemeObject->getCities($eachState->term_id);
                                    ?>
                                    <tr>
                                    <td><strong><?php _e('Price for all cities in state ' . $eachState->name . ':', THEME_TEXTDOMAIN); ?></strong></td>
                                    <td><input type="text" name="advert_city_price[<?php echo $eachState->slug; ?>_all]" value="<?php echo ($get_advert_city_price[$eachState->slug.'_all']) ? $get_advert_city_price[$eachState->slug.'_all'] : ''; ?>" placeholder="<?php _e('E.g. 10.00', THEME_TEXTDOMAIN); ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td><strong><?php _e('Price For State ' . $eachState->name . ':', THEME_TEXTDOMAIN); ?></strong></td>
                                        <td>
                                            <?php
                                            if (is_array($getStateCities) && count($getStateCities) > 0):
                                                foreach ($getStateCities as $eachStateCity):
                                                    ?>
                                                    <div class="state-city-section" style="margin: 5px 0px 20px 0px;">
                                                        <span><strong><?php echo $eachStateCity->name; ?></strong></span>
                                                        <span style="float: right;"><input type="text" name="advert_city_price[<?php echo $eachStateCity->slug; ?>]" value="<?php echo ($get_advert_city_price[$eachStateCity->slug]) ? $get_advert_city_price[$eachStateCity->slug] : $get_advert_city_price[$eachState->slug.'_all']; ?>" placeholder="<?php _e('E.g. 10.00', THEME_TEXTDOMAIN); ?>"/></span>
                                                    </div>
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                endforeach;
                            endif;
                            ?>     
                        </tbody>
                    </table>
                </div>
                <br>
                <table class="widefat">
                    <thead>
                    <th colspan="2"><span style="font-size: 20px;"><strong><?php _e('Categories', THEME_TEXTDOMAIN); ?></strong></span></th>
                    </thead>
                </table>
                <div style="max-height: 400px; overflow: auto;">
                    <table class="widefat">
                        <tbody>
                            <?php
                            if (is_array($getCategories) && count($getCategories) > 0):
                                foreach ($getCategories as $eachCategory):
                                    $getSubCategories = get_terms(themeFramework::$theme_prefix . 'product_category', ['hide_empty' => FALSE, 'parent' => $eachCategory->term_id]);
                                    ?>
                                    <tr>
                                        <td><strong><?php _e('Price For Category ' . $eachCategory->name . ':', THEME_TEXTDOMAIN); ?></strong><input style="float: right;" type="text" name="advert_category_price[<?php echo $eachCategory->slug; ?>]" value="<?php echo ($get_advert_category_price[$eachCategory->slug]) ? $get_advert_category_price[$eachCategory->slug] : ''; ?>" placeholder="<?php _e('E.g. 10.00', THEME_TEXTDOMAIN); ?>"/></td>
                                        <td>
                                            <?php
                                            if (is_array($getSubCategories) && count($getSubCategories) > 0):
                                                foreach ($getSubCategories as $eachSubCategory):
                                                    ?>
                                                    <div class="state-city-section" style="margin: 5px 0px 20px 0px;">
                                                        <span><strong><?php echo $eachSubCategory->name; ?></strong></span>
                                                        <span style="float: right;"><input type="text" name="advert_category_price[<?php echo $eachSubCategory->slug; ?>]" value="<?php echo ($get_advert_category_price[$eachSubCategory->slug]) ? $get_advert_category_price[$eachSubCategory->slug] : ''; ?>" placeholder="<?php _e('E.g. 10.00', THEME_TEXTDOMAIN); ?>"/></span>
                                                    </div>
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                endforeach;
                            endif;
                            ?>     
                        </tbody>
                    </table>
                </div>
                <br>
                <table class="widefat">
                    <thead>
                    <th colspan="2"><span style="font-size: 20px;"><strong><?php _e('Page', THEME_TEXTDOMAIN); ?></strong></span></th>
                    </thead>
                </table>
                <div style="max-height: 400px; overflow: auto;">
                    <table class="widefat">
                        <tbody>
                            <?php
                            if (is_array($getTemplatePages) && count($getTemplatePages) > 0):
                                foreach ($getTemplatePages as $eachTemplatePage):
                                    $pageMetaField = get_post_meta($eachTemplatePage->ID, '_wp_page_template', TRUE);
                                    if ($pageMetaField != 'default'):
                                        ?>
                                        <tr>
                                            <td><strong><?php _e('Price For ' . $eachTemplatePage->post_title . ':', THEME_TEXTDOMAIN); ?></strong></td>
                                            <td>
                                                <input type="text" name="advert_page_price[<?php echo $eachTemplatePage->post_name; ?>]" value="<?php echo ($get_advert_page_price[$eachTemplatePage->post_name]) ? $get_advert_page_price[$eachTemplatePage->post_name] : ''; ?>" placeholder="<?php _e('E.g. 10.00', THEME_TEXTDOMAIN); ?>"/>
                                            </td>
                                        </tr> 
                                        <?php
                                    endif;
                                endforeach;
                            endif;
                            ?>
                            <tr>
                                <td><strong><?php _e('Price For Category Page:', THEME_TEXTDOMAIN); ?></strong></td>
                                <td>
                                    <input type="text" name="advert_page_price[category]" value="<?php echo ($get_advert_page_price['category']) ? $get_advert_page_price['category'] : ''; ?>" placeholder="<?php _e('E.g. 10.00', THEME_TEXTDOMAIN); ?>"/>
                                </td>
                            </tr> 
                            <tr>
                                <td><strong><?php _e('Price For Product Page:', THEME_TEXTDOMAIN); ?></strong></td>
                                <td>
                                    <input type="text" name="advert_page_price[product]" value="<?php echo ($get_advert_page_price['product']) ? $get_advert_page_price['product'] : ''; ?>" placeholder="<?php _e('E.g. 10.00', THEME_TEXTDOMAIN); ?>"/>
                                </td>
                            </tr> 
                            <tr>
                                <td><strong><?php _e('Price For Supplier Public Profile Page:', THEME_TEXTDOMAIN); ?></strong></td>
                                <td>
                                    <input type="text" name="advert_page_price[supplier_public_profile]" value="<?php echo ($get_advert_page_price['supplier_public_profile']) ? $get_advert_page_price['supplier_public_profile'] : ''; ?>" placeholder="<?php _e('E.g. 10.00', THEME_TEXTDOMAIN); ?>"/>
                                </td>
                            </tr> 
                        </tbody>
                    </table>
                </div>
                <br>
                <table class="widefat">
                    <thead>
                    <th colspan="2"><span style="font-size: 20px;"><strong><?php _e('Time', THEME_TEXTDOMAIN); ?></strong></span></th>
                    </thead>
                </table>
                <div style="max-height: 400px; overflow: auto;">
                    <table class="widefat">
                        <tbody>
                            <tr>
                                <td><strong><?php _e('Global price per hour:', THEME_TEXTDOMAIN); ?></strong></td>
                                <td>
                                    <input type="text" name="advert_time_price[hourly]" value="<?php echo ($get_advert_time_price['hourly']) ? $get_advert_time_price['hourly'] : ''; ?>" placeholder="<?php _e('E.g. 10.00', THEME_TEXTDOMAIN); ?>"/>
                                </td>
                            </tr> 
                            <?php
                            if (is_array($getHourlyTimeSlots) && count($getHourlyTimeSlots) > 0):
                                foreach ($getHourlyTimeSlots as $eachTimeSlotKey => $eachTimeSlotVal):
                                ?>
<!--                                <tr>
                                    <td><strong><?php _e('Price For '. $eachTimeSlotVal .' :', THEME_TEXTDOMAIN); ?></strong></td>
                                    <td>
                                        <input type="text" name="advert_time_price[<?php echo $eachTimeSlotKey; ?>]" value="<?php echo ($get_advert_time_price[$eachTimeSlotKey]) ? $get_advert_time_price[$eachTimeSlotKey] : ''; ?>" placeholder="<?php _e('E.g. 10.00', THEME_TEXTDOMAIN); ?>"/>
                                    </td>
                                </tr> -->
                                <?php
                                endforeach;
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="" style="margin-top: 10px;">
                    <button class="button button-primary" name="advert_price_settings_sbmt"><?php _e('Submit', THEME_TEXTDOMAIN); ?></button>
                </div>
            </form>
        </div>

        <?php
    }

}