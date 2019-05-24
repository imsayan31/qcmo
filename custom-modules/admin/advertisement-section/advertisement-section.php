<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!function_exists('adminAdvertisementSection')) {

    function adminAdvertisementSection() {
        add_meta_box('my-meta-box-advertisement', 'Advertisement Fields', 'andr_meta_box_advertisements_func', themeFramework::$theme_prefix . 'advertisement', 'advanced', 'high', '');
    }

}

if (!function_exists('andr_meta_box_advertisements_func')) {

    function andr_meta_box_advertisements_func() {
        global $post;
        $res = NULL;
        $GeneralThemeObject = new GeneralTheme();
        $adv_details = $GeneralThemeObject->advertisement_details($post->ID);
        $getAllCitySelectedOrNot = get_post_meta($post->ID, '_all_city_selected', TRUE);
        $getProductCategories = get_terms(themeFramework::$theme_prefix . 'product_category', ['hide_empty' => FALSE, 'parent' => 0]);
        $getStates = $GeneralThemeObject->getCities();

        /* Advertisement price */
        $get_advert_link_price = get_option('_advert_link_price');
        $get_advert_position_price = get_option('_advert_position_price');
        $get_advert_city_price = get_option('_advert_city_price');
        $get_advert_category_price = get_option('_advert_category_price');
        $get_advert_page_price = get_option('_advert_page_price');
        $get_advert_time_price = get_option('_advert_time_price');

        if ($adv_details->data['adv_state']) {
            $getAllCities = $GeneralThemeObject->getCities($adv_details->data['adv_state']);
        } else {
            $getAllCities = [];
        }
        $advertisement_timing = get_option('advertisement_timing');
        $getTemplatePages = get_posts(['post_type' => 'page', 'posts_per_page' => -1]);

        if ($adv_details->data['adv_enabling'] == 1) {
            $enableBannerEnabling = 'checked';
        } else {
            $enableBannerEnabling = '';
        }

        if ($adv_details->data['adv_enable_banner_text'] == 1) {
            $enableBannerText = 'checked';
        } else {
            $enableBannerText = '';
        }

        if ($adv_details->data['adv_enable_view_counter'] == 1) {
            $enableViewCounter = 'checked';
        } else {
            $enableViewCounter = '';
        }

        if ($adv_details->data['adv_enable_view_button'] == 1) {
            $enableViewButton = 'checked';
        } else {
            $enableViewButton = '';
        }

        if (get_current_user_id() == 1) {
            $otherDivDisplay = 'display:block;';
        } else if (isset($_GET['action']) && $_GET['action'] == 'edit' && get_current_user_id() != 1) {
            $otherDivDisplay = 'display:none;';
        } else {
            $otherDivDisplay = 'display:block;';
        }

        if (get_current_user_id() != 1) {
            $res .= '<div style="margin-bottom: 15px;font-size: 14px;">';
            $res .= '<strong>N.B.- You have to pay for each advertisement. Total payment amount will be calculated based on your advertisement position, page, category, time. Once you submitted this form, the advertisement will be in pending state. The total price and pay now button will be available if you have not made payment for this advertisement. Upload your banner image having resolution 1140px X 300px.</strong>';
            $res .= '</div>';
        } else {
            $res .= '<div style="margin-bottom: 15px;font-size: 14px;">';
            $res .= '<strong>N.B.- Upload your banner image having resolution 1140px X 300px.</strong>';
            $res .= '</div>';
        }

        $res .= '<table class="">';
        $res .= '<tbody>';

        /* Notification */
        /* $res .= '<tr>';
          $res .= '<td colspan="4"><span style="font-size: 14px;"></span></td>';
          $res .= '</tr>'; */

        /* Enabling/disabling */
        $res .= '<tr>';
        $res .= '<td style="width:50%"><strong>Enable banner: </strong></td><td style="width:50%"><label for="adv_enbling"><input type="checkbox" name="adv_enbling" checked id="adv_enbling" value="1" ' . $enableBannerEnabling . '/>Check to show in the slider</label></td>';
        $res .= '</tr>';

        $res .= '</tbody>';
        $res .= '</table>';

        //$res .= '<form class="adv-settings-frm" action="javascript:void(0);" method="post">';
        //$res .= '<input type="hidden" name="action" value="adv_settings_get_price">';

        $res .= '<table class="" style="' . $otherDivDisplay . '">';
        //$res .= '<table class="" style="">';
        $res .= '<tbody>';

        /* Link */
        if ($get_advert_link_price['link']) {
            $linkPrice = 'R$ ' . number_format($get_advert_link_price['link'], 2);
        } else {
            $linkPrice = 'R$ 0.00';
        }
        $res .= '<tr>';
        $res .= '<td style="width:30%"><strong>Advertisement Link: </strong></td><td style="width:30%"><input type="url" name="adv_url" id="adv_url" size="40" value="' . $adv_details->data['adv_url'] . '" placeholder="Advertisement Link"/></td><td style="width:40%;"><strong style="font-size:12px;">(' . $linkPrice . ')</strong></td>';
        $res .= '</tr>';

        /* Position */
        if ($get_advert_position_price['top']) {
            $topPosPrice = 'R$ ' . number_format($get_advert_position_price['top'], 2);
        } else {
            $topPosPrice = 'R$ 0.00';
        }
        if ($get_advert_position_price['middle']) {
            $middlePosPrice = 'R$ ' . number_format($get_advert_position_price['middle'], 2);
        } else {
            $middlePosPrice = 'R$ 0.00';
        }
        if ($get_advert_position_price['bottom']) {
            $bottomPosPrice = 'R$ ' . number_format($get_advert_position_price['bottom'], 2);
        } else {
            $bottomPosPrice = 'R$ 0.00';
        }
        $res .= '<tr>';
        $res .= '<td style="width:50%"><strong>Position</strong></td>';
        $res .= '<td style="width:50%">';
        if (is_array($adv_details->data['adv_position']) && count($adv_details->data['adv_position']) > 0 && in_array(1, $adv_details->data['adv_position'])) {
            $topSelected = 'checked';
        }

        if (is_array($adv_details->data['adv_position']) && count($adv_details->data['adv_position']) > 0 && in_array(2, $adv_details->data['adv_position'])) {
            $middleSelected = 'checked';
        }

        if (is_array($adv_details->data['adv_position']) && count($adv_details->data['adv_position']) > 0 && in_array(3, $adv_details->data['adv_position'])) {
            $bottomSelected = 'checked';
        }
        $res .= '<label for="pos1" style="margin: 0px 10px 0px 10px;"><input type="checkbox" id="pos1" ' . $topSelected . ' class="adv_position" name="adv_position[]" value="1"><strong>Top</strong></label>';
        $res .= '<label for="pos2" style="margin: 0px 10px 0px 10px;"><input type="checkbox" id="pos2" ' . $middleSelected . ' class="adv_position" name="adv_position[]" value="2"><strong>Middle</strong></label>';
        $res .= '<label for="pos3" style="margin: 0px 10px 0px 10px;"><input type="checkbox" id="pos3" ' . $bottomSelected . ' class="adv_position" name="adv_position[]" value="3"><strong>Bottom</strong></label>';
        $res .= '</td>';
        $res .= '</tr>';

        $res .= '<tr>';
        $res .= '<td style="width:50%"></td>';
        $res .= '<td style="width:50%">';
        $res .= '<label style="margin: 0px 10px 0px 10px;"><strong style="font-size:12px;">(' . $topPosPrice . ')</strong></label>';
        $res .= '<label style="margin: 0px 10px 0px 10px;"><strong style="font-size:12px;">(' . $middlePosPrice . ')</strong></label>';
        $res .= '<label style="margin: 0px 10px 0px 10px;"><strong style="font-size:12px;">(' . $bottomPosPrice . ')</strong></label>';
        $res .= '</td>';
        $res .= '</tr>';

        /* State */
        $res .= '<tr>';
        $res .= '<td style="width:25%"><strong>State</strong></td>';
        $res .= '<td style="width:25%">';
        $res .= '<select name="adv_state" class="adv_state">';
        $res .= '<option value="">-Select state-</option>';
        if (is_array($getStates) && count($getStates) > 0) {
            foreach ($getStates as $eachVal) {
                if ($adv_details->data['adv_state'] == $eachVal->term_id) {
                    $stateSelected = 'selected';
                } else {
                    $stateSelected = '';
                }
                $res .= '<option value="' . $eachVal->term_id . '" ' . $stateSelected . '>' . $eachVal->name . '</option>';
            }
        }
        $res .= '</select>';
        $res .= '</td>';

        /* City */
        if ($get_advert_city_price['all']) {
            $allCityPrice = 'R$ ' . number_format($get_advert_city_price['all'], 2);
        } else {
            $allCityPrice = 'R$ 0.00';
        }
        if ($getAllCitySelectedOrNot == 1) {
            $allSelected = 'selected';
        } else {
            $allSelected = '';
        }
        $res .= '<td style="width:25%"><strong>City</strong></td>';
        $res .= '<td style="width:25%">';
        $res .= '<select name="adv_city[]" class="adv_city" multiple style="width:100%">';
        $res .= '<option value="">-Select city-</option>';
        if (is_array($getAllCities) && count($getAllCities) > 0) {
            $res .= '<option value="99999999" ' . $allSelected . '>All cities (' . $allCityPrice . ')</option>';
            foreach ($getAllCities as $eachCity) {
                if (is_array($adv_details->data['adv_city']) && count($adv_details->data['adv_city']) > 0 && in_array($eachCity->term_id, $adv_details->data['adv_city'])) {
                    $citySelected = 'selected';
                } else {
                    $citySelected = '';
                }
                if ($get_advert_city_price[$eachCity->slug]) {
                    $eachCityPrice = 'R$ ' . number_format($get_advert_city_price[$eachCity->slug], 2);
                } else {
                    $eachCityPrice = 'R$ 0.00';
                }
                $res .= '<option value="' . $eachCity->term_id . '" ' . $citySelected . '>' . $eachCity->name . ' (' . $eachCityPrice . ')</option>';
            }
        }
        $res .= '</select>';
        $res .= '</td>';
        $res .= '</tr>';

        /* Initial Date */
        $res .= '<tr>';
        $res .= '<td style="width:25%"><strong>Initial Date</strong></td>';
        $res .= '<td style="width:25%">';
        $res .= '<input type="text" id="adv_init_date" name="adv_init_date" value="' . $adv_details->data['adv_init_date'] . '" size="40" placeholder="Initial date"/>';
        $res .= '</td>';

        /* Final Date */
        $res .= '<td style="width:25%"><strong>Final Date</strong></td>';
        $res .= '<td style="width:25%">';
        $res .= '<input type="text" id="adv_final_date" name="adv_final_date" value="' . $adv_details->data['adv_final_date'] . '" size="40" placeholder="Final date"/>';
        $res .= '</td>';
        $res .= '</tr>';

        /* Set Timing */
        if ($adv_details->data['adv_select_slot'] == 1) {
            $wholeDayChecked = 'checked';
            $setTimingChecked = '';
        } else if ($adv_details->data['adv_select_slot'] == 2) {
            $wholeDayChecked = '';
            $setTimingChecked = 'checked';
        }
        $res .= '<tr>';
        $res .= '<td style="width:25%"><strong>Select Slot</strong></td>';
        $res .= '<td style="width:25%">';
        $res .= '<label for="adv_slot1">';
        $res .= '<input type="radio" id="adv_slot1" name="adv_select_slot" value="1" ' . $wholeDayChecked . '/><strong>Whole day</strong>';
        $res .= '</label>';
        $res .= '</td>';
        $res .= '<td colspan="2" style="width:100%">';
        $res .= '<label for="adv_slot2">';
        $res .= '<input type="radio" id="adv_slot2" name="adv_select_slot" value="2" ' . $setTimingChecked . '/><strong>Set timing</strong>';
        $res .= '</label>';
        $res .= '</td>';
        $res .= '</tr>';

        /* Initial Time */
        if ($get_advert_time_price['hourly']) {
            $globalTimePrice = 'R$ ' . number_format($get_advert_time_price['hourly'], 2);
        } else {
            $globalTimePrice = 'R$ 0.00';
        }
        $res .= '<tr class="timing_row">';
        $res .= '<td style="width:25%"><strong>Initial Time</strong></td>';
        $res .= '<td style="width:25%">';
        $res .= '<input type="text" id="adv_init_time" name="adv_init_time" value="' . $adv_details->data['adv_init_time'] . '" size="40" placeholder="Initial time"/>';
        $res .= '</td>';

        /* Final Time */
        $res .= '<td style="width:25%"><strong>Final Time</strong></td>';
        $res .= '<td style="width:25%">';
        $res .= '<input type="text" id="adv_final_time" name="adv_final_time" value="' . $adv_details->data['adv_final_time'] . '" size="40" placeholder="Final time"/>';
        $res .= '</td>';
        $res .= '</tr>';
        $res .= '<tr>';
        $res .= '<td style="width:100%"><strong>(' . $globalTimePrice . ')</strong></td>';
        $res .= '</tr>';


        //$res .= '</tr>';

        /* Timing */
        $res .= '<tr>';
        $res .= '<td style="width:25%"><strong>Timing (same for all adverts)</strong></td>';
        $res .= '<td style="width:25%">';
//        $res .= '<input type="number" min="1" name="adv_timing" value="' . $adv_details->data['adv_timing'] . '" size="40" placeholder="E.g. 2"/>&nbsp; seconds';
        $res .= '<input type="number" min="1" name="adv_timing" value="' . $advertisement_timing . '" size="40" placeholder="E.g. 2000"/>&nbsp; miliseconds';
        $res .= '</td>';

        /* Priority */
        $res .= '<td style="width:25%"><strong>Priority</strong></td>';
        $res .= '<td style="width:25%">';
        $res .= '<select name="adv_priority" class="adv_priority">';
        for ($i = 1; $i <= 15; $i++) {
            if ($adv_details->data['adv_priority'] == $i) {
                $prioritySelected = 'selected';
            } else {
                $prioritySelected = '';
            }
            $res .= '<option value="' . $i . '" ' . $prioritySelected . '>' . $i . '</option>';
        }
        $res .= '</select>';
        $res .= '</td>';
        $res .= '</tr>';

        /* Categories */
        $res .= '<tr>';
        $res .= '<td style="width:25%;vertical-align: top;"><strong>Categories</strong></td>';
        $res .= '<td style="width:25%;vertical-align: top;">';

        if (is_array($getProductCategories) && count($getProductCategories) > 0) {
            foreach ($getProductCategories as $eachCat) {
                $getProductSubCategories = get_terms(themeFramework::$theme_prefix . 'product_category', ['hide_empty' => FALSE, 'parent' => $eachCat->term_id]);
                if (is_array($adv_details->data['adv_cat']) && count($adv_details->data['adv_cat']) > 0 && in_array($eachCat->term_id, $adv_details->data['adv_cat'])) {
                    $catSelected = 'checked';
                } else {
                    $catSelected = '';
                }
                if ($get_advert_category_price[$eachCat->slug]) {
                    $parentCatPrice = 'R$ ' . number_format($get_advert_category_price[$eachCat->slug], 2);
                } else {
                    $parentCatPrice = 'R$ 0.00';
                }
                $res .= '<div>';
                $res .= '<label class="main-site-category-label" for="cat' . $eachCat->term_id . '" style="margin: 0px 10px 0px 10px;"><input class="main-site-category" type="checkbox" id="cat' . $eachCat->term_id . '" ' . $catSelected . ' name="adv_cat[]" value="' . $eachCat->term_id . '"><strong>' . $eachCat->name . ' (' . $parentCatPrice . ')</strong></label>';
                if (is_array($getProductSubCategories) && count($getProductSubCategories) > 0) {
                    $res .= '<div class="all-sub-cats">';
                    foreach ($getProductSubCategories as $eachSubCat) {
                        if (is_array($adv_details->data['adv_cat']) && count($adv_details->data['adv_cat']) > 0 && in_array($eachSubCat->term_id, $adv_details->data['adv_cat'])) {
                            $catSelected = 'checked';
                        } else {
                            $catSelected = '';
                        }
                        if ($get_advert_category_price[$eachSubCat->slug]) {
                            $subCatPrice = 'R$ ' . number_format($get_advert_category_price[$eachSubCat->slug], 2);
                        } else {
                            $subCatPrice = 'R$ 0.00';
                        }
                        $res .= '<label class="main-site-sub-category-label" for="cat' . $eachSubCat->term_id . '" style="margin: 0px 10px 0px 10px;"><input class="main-site-sub-category" type="checkbox" id="cat' . $eachSubCat->term_id . '" ' . $catSelected . ' name="adv_cat[]" value="' . $eachSubCat->term_id . '"> -- <strong>' . $eachSubCat->name . ' (' . $subCatPrice . ')</strong></label><br>';
                    }
                    $res .= '</div>';
                }
                $res .= '<div>';
                //$res .= '<option value="' . $eachCat->term_id . '" ' . $catSelected . '>' . $eachCat->name . '</option>';
            }
        }

        //$res .= '</select>';
        $res .= '</td>';
        //$res .= '</tr>';

        /* Page */
        if ($get_advert_page_price['category']) {
            $pageCategoryPrice = 'R$ ' . number_format($get_advert_page_price['category'], 2);
        } else {
            $pageCategoryPrice = 'R$ 0.00';
        }
        if ($get_advert_page_price['product']) {
            $pageProductPrice = 'R$ ' . number_format($get_advert_page_price['product'], 2);
        } else {
            $pageProductPrice = 'R$ 0.00';
        }
        if ($get_advert_page_price['supplier_public_profile']) {
            $pageSellerPublicPrice = 'R$ ' . number_format($get_advert_page_price['supplier_public_profile'], 2);
        } else {
            $pageSellerPublicPrice = 'R$ 0.00';
        }
        if (is_array($adv_details->data['adv_page']) && count($adv_details->data['adv_page']) > 0 && in_array(2, $adv_details->data['adv_page'])) {
            $twoSelected = 'checked';
        }

        if (is_array($adv_details->data['adv_page']) && count($adv_details->data['adv_page']) > 0 && in_array(3, $adv_details->data['adv_page'])) {
            $threeSelected = 'checked';
        }

        if (is_array($adv_details->data['adv_page']) && count($adv_details->data['adv_page']) > 0 && in_array(4, $adv_details->data['adv_page'])) {
            $fourSelected = 'checked';
        }

        //$res .= '<tr>';
        $res .= '<td style="width:25%;vertical-align: top;"><strong>Page</strong></td>';
        $res .= '<td style="width:25%;vertical-align: top;">';
        $res .= '<label for="page2" style="margin: 0px 10px 0px 10px;"><input type="checkbox" id="page2" ' . $twoSelected . ' class="adv_page" name="adv_page[]" value="2"><strong>Category (' . $pageCategoryPrice . ')</strong></label><br>';
        $res .= '<label for="page3" style="margin: 0px 10px 0px 10px;"><input type="checkbox" id="page3" ' . $threeSelected . ' class="adv_page" name="adv_page[]" value="3"><strong>Product (' . $pageProductPrice . ')</strong></label><br>';
        $res .= '<label for="page4" style="margin: 0px 10px 0px 10px;"><input type="checkbox" id="page4" ' . $fourSelected . ' class="adv_page" name="adv_page[]" value="4"><strong>Supplier Profile Page (' . $pageSellerPublicPrice . ')</strong></label><br>';
        if (is_array($getTemplatePages) && count($getTemplatePages) > 0) {
            foreach ($getTemplatePages as $eachTemplatePage) {
                $pageMetaField = get_post_meta($eachTemplatePage->ID, '_wp_page_template', TRUE);
                if (is_array($adv_details->data['adv_page']) && count($adv_details->data['adv_page']) > 0 && in_array($eachTemplatePage->post_name, $adv_details->data['adv_page'])) {
                    $pageSelected = 'checked';
                } else {
                    $pageSelected = '';
                }
                if ($get_advert_page_price[$eachTemplatePage->post_name]) {
                    $pagePrice = 'R$ ' . number_format($get_advert_page_price[$eachTemplatePage->post_name], 2);
                } else {
                    $pagePrice = 'R$ 0.00';
                }
                if ($pageMetaField != 'default') {
                    $res .= '<label for="' . $eachTemplatePage->post_name . '" style="margin: 0px 10px 0px 10px;"><input class="adv_page" type="checkbox" id="' . $eachTemplatePage->post_name . '" ' . $pageSelected . ' name="adv_page[]" value="' . $eachTemplatePage->post_name . '"><strong>' . $eachTemplatePage->post_title . ' (' . $pagePrice . ')</strong></label><br>';
                }
            }
        }
        $res .= '</td>';
        $res .= '</tr>';

        /* Enable/Disable Option */
        $res .= '<tr>';
        $res .= '<td style="width:25%"><strong>Banner Text</strong></td>';
        $res .= '<td style="width:25%">';
        $res .= '<label for="enable_banner_text">';
        $res .= '<input type="checkbox" id="enable_banner_text" name="enable_banner_text" ' . $enableBannerText . ' value="1"/> Enable';
        $res .= '</label>';
        $res .= '</td>';
        $res .= '<td style="width:25%"><strong>View Counter</strong></td>';
        $res .= '<td style="width:25%">';
        $res .= '<label for="enable_view_counter">';
        $res .= '<input type="checkbox" id="enable_view_counter" name="enable_view_counter" ' . $enableViewCounter . ' value="1"/> Enable';
        $res .= '</label>';
        $res .= '</td>';
        $res .= '</tr>';

        $res .= '<tr>';
        $res .= '<td style="width:25%"><strong>View Button</strong></td>';
        $res .= '<td style="width:25%">';
        $res .= '<label for="enable_view_button">';
        $res .= '<input type="checkbox" id="enable_view_button" name="enable_view_button" ' . $enableViewButton . ' value="1"/> Enable';
        $res .= '</label>';
        $res .= '</td>';
        $res .= '</tr>';

        $res .= '<tr>';
        $res .= '<td><strong>Total viewers: </strong><td><strong>' . $adv_details->data['adv_view'] . '</strong></td>';
        $res .= '</tr>';

        if ($adv_details->data['adv_price']) {
            $showAdvPrice = $adv_details->data['adv_price'];
        } else {
            $showAdvPrice = '0.00';
        }

        /* $res .= '<tr>';
          $res .= '<td><a href="javascript:void(0);" class="get-adv-total-val button button-primary">Get Total</a><td><strong><span class="show-total-price" style="font-size: 20px;">R$ ' . $showAdvPrice . '</span></strong></td>';
          $res .= '</tr>'; */

        $res .= '</tbody>';
        $res .= '</table>';

        $res .= '<div class="get-total-div" style="display:none;">';
        $res .= '<a href="javascript:void(0);" class="get-adv-total-val button button-primary">Get Total</a><strong><span class="show-total-price" style="font-size: 20px;">R$ ' . $showAdvPrice . '</span></strong>';
        $res .= '</div>';
        //$res .= '</form>';

        echo $res;
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {

                $(window).scroll(function (event) {
                    var scroll = $(window).scrollTop();
                    if (scroll >= 500) {
                        $('.get-total-div').show();
                    } else {
                        $('.get-total-div').hide();
                    }
                });

            });</script>
        <?php

    }

}

if (!function_exists('adminAdvertisementSectionSave')) {

    function adminAdvertisementSectionSave($post_id) {
        $GeneralThemeObject = new GeneralTheme();
        $adv_url = strip_tags(trim($_POST['adv_url']));
        $adv_position = $_POST['adv_position'];
        $adv_state = $_POST['adv_state'];
        $adv_city = $_POST['adv_city'];
        $adv_init_date = strip_tags(trim($_POST['adv_init_date']));
        $adv_final_date = strip_tags(trim($_POST['adv_final_date']));
        $adv_init_time = strip_tags(trim($_POST['adv_init_time']));
        $adv_final_time = strip_tags(trim($_POST['adv_final_time']));
        $adv_timing = strip_tags(trim($_POST['adv_timing']));
        $adv_priority = $_POST['adv_priority'];
        $adv_cat = $_POST['adv_cat'];
        $adv_page = $_POST['adv_page'];
        $enable_banner_text = $_POST['enable_banner_text'];
        $enable_view_counter = $_POST['enable_view_counter'];
        $enable_view_button = $_POST['enable_view_button'];
        $adv_enbling = $_POST['adv_enbling'];
        $adv_select_slot = $_POST['adv_select_slot'];

        $getPostDetails = get_post($post_id);

        if ($getPostDetails->post_type == themeFramework::$theme_prefix . 'advertisement') {
            /* Get Ad Payment Data */
            $queryString = " AND `adv_id`=" . $post_id . "";
            $getAdvPaymentData = $GeneralThemeObject->getAdvPaymentData($queryString);

            update_post_meta($post_id, '_adv_url', $adv_url);
            update_post_meta($post_id, '_adv_position', $adv_position);
            update_post_meta($post_id, '_adv_state', $adv_state);

            update_post_meta($post_id, '_adv_init_date', $adv_init_date);
            update_post_meta($post_id, '_adv_final_date', $adv_final_date);
            update_post_meta($post_id, '_adv_init_time', $adv_init_time);
            update_post_meta($post_id, '_adv_final_time', $adv_final_time);
            update_post_meta($post_id, '_adv_timing', $adv_timing);
            update_post_meta($post_id, '_adv_priority', $adv_priority);
            update_post_meta($post_id, '_adv_cat', $adv_cat);
            update_post_meta($post_id, '_adv_page', $adv_page);
            update_post_meta($post_id, '_adv_enable_banner_text', $enable_banner_text);
            update_post_meta($post_id, '_adv_enable_view_counter', $enable_view_counter);
            update_post_meta($post_id, '_adv_enable_view_button', $enable_view_button);
            update_post_meta($post_id, '_adv_enabling', $adv_enbling);
            update_post_meta($post_id, '_adv_select_slot', $adv_select_slot);

            if (is_array($adv_city) && count($adv_city) > 0 && in_array(99999999, $adv_city)) {
                $getStateWiseCities = [];
                update_post_meta($post_id, '_all_city_selected', 1);
                $getAllCities = $GeneralThemeObject->getCities($adv_state);
                if (is_array($getAllCities) && count($getAllCities) > 0) {
                    foreach ($getAllCities as $eachCity) {
                        $getStateWiseCities[] = $eachCity->term_id;
                    }
                    update_post_meta($post_id, '_adv_city', $getStateWiseCities);
                }
            } else {
                update_post_meta($post_id, '_all_city_selected', '');
                update_post_meta($post_id, '_adv_city', $adv_city);
            }
            update_option('advertisement_timing', $adv_timing);

            $getAdvPrice = $GeneralThemeObject->getAdvertisementPrice($post_id);

            update_post_meta($post_id, '_adv_price', $getAdvPrice);
            update_post_meta($post_id, '_adv_payment_status', 2);

            update_post_meta($post_id, '_adv_admin_approval', 1);

            if (is_array($getAdvPaymentData) && count($getAdvPaymentData) > 0) {
                /* paypal data */
                $paypal_data_params = array(
                    'no_shipping' => '1',
                    'no_note' => '1',
                    'item_name' => 'Payment For ' . get_the_title($post_id),
                    'currency_code' => 'BRL',
                    'amount' => $getAdvPrice,
                    'return' => admin_url() . "edit.php?post_type=andr_advertisement",
                    'cancel_return' => admin_url() . "edit.php?post_type=andr_advertisement",
                    'notify_url' => admin_url() . "edit.php?post_type=andr_advertisement"
                );
                $paypal_data_params['custom'] = $getAdvPaymentData[0]->unique_id . '#' . $getAdvPaymentData[0]->user_id;

                /* process to paypal */
                $Paypal = new Paypal_Standard();
                $paypalActionUrl = $Paypal->preparePaypalData($paypal_data_params);
                $updatedData = ['total_price' => $getAdvPrice, 'payment_url' => $paypalActionUrl];
                $whereData = ['unique_id' => $getAdvPaymentData[0]->unique_id];
                $GeneralThemeObject->updateAdvPaymentData($updatedData, $whereData);
            } else {
                /* Insert Payment Data */
                $generateRandomString = $GeneralThemeObject->generateRandomString(6);

                $insertPaymentData = [
                    'unique_id' => $generateRandomString,
                    'adv_id' => $post_id,
                    'user_id' => get_current_user_id(),
                    'total_price' => $getAdvPrice,
                    'transaction_id' => '',
                    'payment_url' => '',
                    'payment_status' => 2,
                    'approval_status' => 2,
                    'payment_date' => '',
                ];
                $insertedID = $GeneralThemeObject->insertAdvPayment($insertPaymentData);
            }
        }
    }

}