<?php

/**
 * -----------------------------------
 * METABOX:
 * -----------------------------------
 */
function cd_meta_box_add() {
    //add_meta_box('my-meta-box-product-suppliers', 'Select Suppliers For Simple Products', 'andr_meta_box_product_suppliers', themeFramework::$theme_prefix . 'product', 'advanced', 'high', '');
    add_meta_box('my-meta-box-product-attribute', 'Select Products For Bundling', 'andr_meta_box_product_attribute', themeFramework::$theme_prefix . 'product', 'advanced', 'high', '');
    add_meta_box('my-meta-box-product-city', 'Product Cities', 'andr_meta_box_product_city', themeFramework::$theme_prefix . 'product', 'advanced', 'high', '');
    add_meta_box('my-meta-box-product-material-category', 'Product Material Category', 'andr_meta_box_product_material_category', themeFramework::$theme_prefix . 'product', 'advanced', 'high', '');
    add_meta_box('my-meta-box-coupon-users', 'Select User', 'andr_meta_box_coupon_users', themeFramework::$theme_prefix . 'coupon', 'advanced', 'high', '');
}

function andr_meta_box_product_suppliers() {
    global $post;
    $GeneralThemeObject = new GeneralTheme();
    $getPostDetails = get_post($post->ID);
    $getSuppliers = $GeneralThemeObject->getSuppliers();
    $getProductSuppliers = get_post_meta($post->ID, '_product_suppliers', true);
    $getProductDetails = $GeneralThemeObject->product_details($post->ID);

    if ($getProductDetails->data['all_suppliers'] == 1) {
        $checkedVal = 'checked';
    } else {
        $checkedVal = '';
    }

    $res = '';
    $res .= '<div class="">';
    $res .= '<label for="choose_all_suppliers">';
    $res .= '<input type="checkbox" name="choose_all_suppliers" id="choose_all_suppliers" value="1" ' . $checkedVal . '><strong>Choose all suppliers</strong>';
    $res .= '</label>';
    $res .= '</div>';
    $res .= '<br>';
    $res .= '<div class="select_supplier_sec">';
    $res .= '<select name="attribute_suppliers[]" class="attribute_suppliers chosen" multiple>';
    if (is_array($getSuppliers) && count($getSuppliers) > 0) {
        foreach ($getSuppliers as $eachSupplier) {
            if (is_array($getProductSuppliers) && count($getProductSuppliers) > 0 && in_array($eachSupplier->ID, $getProductSuppliers)) {
                $supplierSelected = 'selected';
            } else {
                $supplierSelected = '';
            }
            $res .= '<option value="' . $eachSupplier->ID . '" ' . $supplierSelected . '>' . $eachSupplier->first_name . ' ' . $eachSupplier->last_name . '</option>';
        }
    }
    $res .= '</select>';
    $res .= '</div>';
    echo $res;
}

function andr_meta_box_product_attribute() {
    global $post, $pagenow;
    $GeneralThemeObject = new GeneralTheme();
    $getPostDetails = get_post($post->ID);
    $getProductDetails = $GeneralThemeObject->product_details($post->ID);
    $getProductAttributes = $GeneralThemeObject->getSimpleProducts();
    $get_product_cat = get_post_meta($post->ID, '_product_cats', true);
    $get_product_cat_quantity = get_post_meta($post->ID, '_product_cat_quantity', true);
    $get_product_total_price = get_post_meta($post->ID, '_product_total_estimated_price', true);

    $res = '';
    $res .= '<div class="">';
    $res .= '<select name="select_product_attribute" class="select_product_attribute chosen">';
    $res .= '<option value=""></option>';
    if (is_array($getProductAttributes) && count($getProductAttributes) > 0):
        foreach ($getProductAttributes as $eachCat):
            $res .= '<option value="' . $eachCat->ID . '">' . $eachCat->post_title . '</option>';
        endforeach;
    endif;
    $res .= '</select>';
    $res .= '</div>';
    $res .= '<br>';
    $res .= '<div class="display_attributes_block">';
    if (is_array($get_product_cat) && count($get_product_cat) > 0) {
        foreach ($get_product_cat as $key => $val) {
            $productDetails = $GeneralThemeObject->product_details($val);
            $res .= '<table class="product_attribute_field">';
            $res .= '<tbody>';
            $res .= '<tr>';
            $res .= '<td width="20%"><input type="hidden" name="product_cat[]" class="product_cat" value="' . $productDetails->data['ID'] . '">' . $productDetails->data['title'] . '</td>';
            $res .= '<td width="20%">' . $productDetails->data['type'] . '</td>';
            $res .= '<td width="20%"><input type="text" name="product_quantity[]" class="product_quantity" value="' . $get_product_cat_quantity[$key] . '" placeholder="Quantity (in ' . $productDetails->data['unit'] . ')">' . '</td>';
            $res .= '<td align="center" width="30%">R$ ' . $productDetails->data['default_price'] . '/' . $productDetails->data['unit'] . '</td>';
            $res .= '<td width="10%"><a href="javascript:void(0);" class="delete_product_attribute">close</a></td>';
            $res .= '</tr>';
            $res .= '</tbody>';
            $res .= '</table>';
        }
    }
    $res .= '</div>';
    if ($pagenow == 'post-new.php') {
        $res .= '<div style="display:none; text-align:left">';
        $res .= '<a href="javascript:void(0);" id="get_attribute_total" class="button button-primary">Get Total</a>';
        $res .= '</div>';
    } elseif ($pagenow == 'post.php' && $getProductDetails->data['is_simple'] == FALSE) {
        $res .= '<div style="text-align:left">';
        $res .= '<a href="javascript:void(0);" id="get_attribute_total" class="button button-primary">Get Total</a>';
        $res .= '</div>';
    }
    $res .= '<div style="text-align:right">';
    $res .= '<strong>Total price: R$ <input type="text" name="fetch_total_price" class="fetch-total-price" value="' . $get_product_total_price . '"></strong>';
    $res .= '</div>';

    echo $res;
}

function andr_meta_box_product_city() {
    global $post;
    $GeneralThemeObject = new GeneralTheme();
    $getBrazilCities = $GeneralThemeObject->getBrazilCities();
    $getProductState = get_post_meta($post->ID, '_product_state', true);
    $getProductCities = get_post_meta($post->ID, '_product_cities', true);
    $getProductCitiesPrices = get_post_meta($post->ID, '_product_cities_prices', true);
    $getStates = $GeneralThemeObject->getCities();
    $getCities = $GeneralThemeObject->getCities($getProductState);
    $getProductDetails = $GeneralThemeObject->product_details($post->ID);

     // echo '<pre>';
     //  print_r($getProductCities);
     //  echo '</pre>';
     //  echo '<pre>';
     //  print_r($getProductCitiesPrices);
     //  echo '</pre>'; 

    if ($getProductDetails->data['all_cities'] == 1) {
        $checkedVal = 'checked';
        $selectBoxDisplay = 'display:none;';
    } else {
        $checkedVal = '';
        $selectBoxDisplay = 'display:block;';
    }

    $res .= '<div class="">';
    $res .= '<label for="choose_all_cities">';
    $res .= '<input type="checkbox" name="choose_all_cities" id="choose_all_cities" value="1" ' . $checkedVal . '><strong>Choose all cities</strong>';
    $res .= '</label>';
    $res .= '</div>';
    $res .= '<br>';
    $res .= '<div class="">';
    $res .= '<div class="select_state_city_sec" style="'. $selectBoxDisplay .'">';
    $res .= '<select name="attribute_state" class="attribute_state chosen">';
    if (is_array($getStates) && count($getStates) > 0):
        $res .= '<option value="">-Select State-</option>';
        foreach ($getStates as $eachState) :
            if ($eachState->term_id == $getProductState):
                $stateSelected = 'selected';
            else:
                $stateSelected = '';
            endif;
            $res .= '<option value="' . $eachState->term_id . '" ' . $stateSelected . '>' . $eachState->name . '</option>';
        endforeach;
    endif;
    $res .= '</select>';
    $res .= '</div>';

    $res .= '<br>';

    $res .= '<div class="select_state_city_sec" style="'. $selectBoxDisplay .'">';
    $res .= '<select name="select_product_city[]" class="attribute_city selected_attribute_city chosen">';
    $res .= '<option value=""></option>';
    if (is_array($getCities) && count($getCities) > 0 && $getProductState) :
        $res .= '<option value="99999999">All cities</option>';
        foreach ($getCities as $eachCountry) :
            $res .= '<option value="' . $eachCountry->term_id . '" >' . $eachCountry->name . '</option>';
        endforeach;
    endif;
    $res .= '</select>';
    $res .= '</div>';

    $res .= '<div class="product-city-price">';
    if (is_array($getProductCitiesPrices) && count($getProductCitiesPrices) > 0) {
        foreach ($getProductCitiesPrices as $key => $val) {
            $getCityBy = get_term_by('id', $getProductCities[$key], themeFramework::$theme_prefix . 'product_city');
            $res .= '<table class="product_city_price_field">';
            $res .= '<tr>';
            $res .= '<td width="50%"><input type="hidden" name="product_city[]" value="' . $getProductCities[$key] . '"><strong>' . $getCityBy->name . '</strong> : </td>';
            $res .= '<td width="40%">R$ <input type="text" name="product_city_price[]" value="' . $val . '" placeholder="Enter price (in R$)"></td>';
            $res .= '<td width="10%"><a href="javascript:void(0);" class="delete_product_city">close</a></td>';
            $res .= '</tr>';
            $res .= '</table>';
        }
    }
    $res .= '</div>';
    $res .= '</div>';
    echo $res;
}

function andr_meta_box_product_material_category() {
    global $post;
    $GeneralThemeObject = new GeneralTheme();
    $getProductCategories = get_terms(themeFramework::$theme_prefix . 'product_category', ['hide_empty' => FALSE, 'parent' => 0]);
    $getProductMaterialCategory = get_post_meta($post->ID, '_material_category', TRUE);
    ?>
    <select name="product_material_category" style="width: 100%;">
        <option value=""><?php _e('-Select From List-', THEME_TEXTDOMAIN); ?></option>
        <?php
        if (is_array($getProductCategories) && count($getProductCategories) > 0):
            foreach ($getProductCategories as $eachProductCategory):
                ?>
                <option value="<?php echo $eachProductCategory->slug; ?>" <?php echo ($getProductMaterialCategory == $eachProductCategory->slug) ? 'selected' : ''; ?>><?php echo $eachProductCategory->name; ?></option>
                <?php
            endforeach;
        endif;
        ?>
    </select>
    <?php
}

function cd_meta_box_save($post_id) {
    $GeneralThemeObject = new GeneralTheme();
    
    $productCat = $_POST['product_cat'];
    $productCatQuantity = $_POST['product_quantity'];
    $productTotalEstimatedPrice = $_POST['fetch_total_price'];
    $productState = $_POST['attribute_state'];
    $productCities = $_POST['product_city'];
    $productCitiesPrice = $_POST['product_city_price'];
    $productAttributeSuppliers = $_POST['attribute_suppliers'];
    $choose_all_suppliers = $_POST['choose_all_suppliers'];
    $choose_all_cities = $_POST['choose_all_cities'];
    $product_material_category = $_POST['product_material_category'];
    $getSuppliers = $GeneralThemeObject->getSuppliers();
    $getStates = $GeneralThemeObject->getCities();

    $select_coupon_user_type = $_POST['select_coupon_user_type'];
    $coupon_selected_users = $_POST['coupon_selected_users'];

    $getPostDetails = get_post($post_id);

    if ($getPostDetails->post_type == themeFramework::$theme_prefix . 'product') {
        update_post_meta($post_id, '_material_category', $product_material_category);

        if (is_array($getSuppliers) && count($getSuppliers) > 0) {
            foreach ($getSuppliers as $eachSupplier) {
                $allSuppliers[] = (string) $eachSupplier->term_id;
            }
        }

        if (is_array($getStates) && count($getStates) > 0) {
            foreach ($getStates as $eachState) {
                $getCities = $GeneralThemeObject->getCities($eachState->term_id);
                if (is_array($getCities) && count($getCities) > 0) {
                    foreach ($getCities as $eachCity) {
                        $allCities[] = (string) $eachCity->term_id;
                    }
                }
            }
        }

        if (!empty($productCat)) {
            update_post_meta($post_id, '_product_cats', $productCat);
        }

        if (!empty($productCatQuantity)) {
            update_post_meta($post_id, '_product_cat_quantity', $productCatQuantity);
        }

        if (!empty($productTotalEstimatedPrice)) {
            update_post_meta($post_id, '_product_total_estimated_price', $productTotalEstimatedPrice);
        }

        if (!empty($choose_all_suppliers)) {
            update_post_meta($post_id, '_product_suppliers', $allSuppliers);
            update_post_meta($post_id, '_product_all_suppliers', 1);
        } else {
            update_post_meta($post_id, '_product_suppliers', $productAttributeSuppliers);
            update_post_meta($post_id, '_product_all_suppliers', NULL);
        }

        if (!empty($choose_all_cities)) {
            update_post_meta($post_id, '_product_cities', $allCities);
            update_post_meta($post_id, '_product_all_cities', 1);

            update_post_meta($post_id, '_product_state', NULL);
            update_post_meta($post_id, '_product_cities_prices', NULL);
        } else {
            if (!empty($productState)) {
                update_post_meta($post_id, '_product_state', $productState);
            }
            if (!empty($productCities)) {
                update_post_meta($post_id, '_product_cities', $productCities);
            }

            if (!empty($productCitiesPrice)) {
                update_post_meta($post_id, '_product_cities_prices', $productCitiesPrice);
            }
            update_post_meta($post_id, '_product_all_cities', NULL);
        }
    }

    if ($getPostDetails->post_type == themeFramework::$theme_prefix . 'coupon') {
        update_post_meta($post_id, 'select_coupon_user_type', $select_coupon_user_type);
        update_post_meta($post_id, 'coupon_selected_users', $coupon_selected_users);
    }
}

function andr_meta_box_coupon_users(){
    global $post;
    $GeneralThemeObject = new GeneralTheme();
    $CouponObject = new classCoupnManagement();
    $couponDetails = $CouponObject->coupon_details($post->ID);

    $getUsersList = get_users(['role' => $couponDetails->data['user_type']
            /*, 'meta_query' => [
                [
                    'key' => '_active_status',
                    'value' => 1,
                    'compare' => '='
                ]
            ]*/
        ]);
    ?>
    <div class="wrap">
        <table class="widefat">
            <tbody>
                <tr>
                    <td><strong><?php _e('Select User Type', THEME_TEXTDOMAIN); ?></strong></td>
                    <td>
                        <select name="select_coupon_user_type" class="select-coupon-user-type">
                            <option value="">-Select from list-</option>
                            <option value="subscriber" <?php selected($couponDetails->data['user_type'], 'subscriber'); ?>>Customer</option>
                            <option value="supplier" <?php selected($couponDetails->data['user_type'], 'supplier'); ?>>Supplier</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php _e('-Select Users-', THEME_TEXTDOMAIN); ?></strong></td>
                    <td>
                        <div class="populate_coupon_users">
                            <?php
                            if(is_array($getUsersList) && count($getUsersList) > 0):
                                foreach ($getUsersList as $eachUser) :
                                    ?>
                                    <p>
                                        <label for="<?php echo $eachUser->user_email; ?>">
                                        <input type="checkbox" name="coupon_selected_users[]" id="<?php echo $eachUser->user_email; ?>" <?php echo (is_array($couponDetails->data['users']) && count($couponDetails->data['users']) > 0 && in_array($eachUser->ID, $couponDetails->data['users'])) ? 'checked' : ''; ?> value="<?php echo $eachUser->ID; ?>"/> <?php echo $eachUser->first_name .' '. $eachUser->last_name; ?>
                                        </label>
                                    </p>
                                    <?php
                                endforeach;
                            endif;
                             ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php _e('Send Email', THEME_TEXTDOMAIN); ?></strong></td>
                    <td>
                        <a href="javascript:void(0);" class="button button-primary click-coupon-send-email" data-coupon_id="<?php echo (isset($_GET['post']) && $_GET['post'] != '') ? $_GET['post'] : ''; ?>"><?php _e('Send Email to Users', THEME_TEXTDOMAIN); ?></a>
                    </td>
                </tr>
                <tr class="coupon-success-msg" style="display: none;">
                    <td colspan="2">
                        <p class="notice notice-success"><strong><?php _e('Email has been sent to user(s) successfully.', THEME_TEXTDOMAIN); ?></strong></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
}