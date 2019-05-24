<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


if (!function_exists('sub_admin_donation_lists_func')) {

    function sub_admin_donation_lists_func() {
        $GeneralThemeObject = new GeneralTheme();
        $DonationObject = new classPayPalDonation();
        $queryString = NULL;

        if (isset($_GET['membership_id']) && $_GET['membership_id'] != ''):
            $queryString .= " AND `donation_id`='" . $_GET['membership_id'] . "'";
        endif;
        if (isset($_GET['transaction_id']) && $_GET['transaction_id'] != ''):
            $queryString .= " AND `transaction_id`='" . $_GET['transaction_id'] . "'";
        endif;
        if (isset($_GET['payment_status']) && $_GET['payment_status'] != ''):
            $queryString .= " AND `payment_status`='" . $_GET['payment_status'] . "'";
        endif;
        $membershipDetails = $DonationObject->getDonationDetails($queryString);
        ?>
        <h2><?php _e('Membership Transaction History', THEME_TEXTDOMAIN); ?></h2>
        <div class="wrap">
            <?php
            if (is_array($membershipDetails) && count($membershipDetails) > 0) :
                $i = 0;
                $data_arr = [];
                foreach ($membershipDetails as $eachMembershipDetails) :
                    $data_arr[$i] = [
                        'donation_id' => $eachMembershipDetails->donation_id,
                        'name' => $eachMembershipDetails->name,
                        'email' => $eachMembershipDetails->email,
                        'phone' => $eachMembershipDetails->phone,
                        'transaction_id' => $eachMembershipDetails->transaction_id,
                        'total_price' => 'R$ ' . number_format($eachMembershipDetails->amount, 2),
                        'payment_date' => date('d M, Y', $eachMembershipDetails->payment_date),
                        'payment_status' => ($eachMembershipDetails->payment_status == 1) ? 'Paid' : 'Unpaid',
                    ];
                    $i++;
                endforeach;
            endif;

            $DonationTblObject = new Donation_List_Table();
            $DonationTblObject->prepare_items($data_arr);
            ?>
            <form name="donation_payment_list_tbl" id="donation_payment_list_tbl" method="get" action="<?php echo admin_url() . 'admin.php?page=donation-list'; ?>">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
                <input type="text" name="membership_id" value="<?php echo $_GET['membership_id']; ?>" placeholder="<?php _e('Donation ID', THEME_TEXTDOMAIN); ?>">
                <input type="text" name="transaction_id" value="<?php echo $_GET['transaction_id']; ?>" placeholder="<?php _e('Transaction ID', THEME_TEXTDOMAIN); ?>">
                <select name="payment_status">
                    <option value=""><?php _e('-Payment status-', THEME_TEXTDOMAIN); ?></option>
                    <option value="1" <?php echo ($_GET['payment_status'] == 1) ? 'selected' : ''; ?>><?php _e('Paid', THEME_TEXTDOMAIN); ?></option>
                    <option value="2" <?php echo ($_GET['payment_status'] == 2) ? 'selected' : ''; ?>><?php _e('Unpaid', THEME_TEXTDOMAIN); ?></option>
                </select>
                <button class="button button-primary" name="">Search</button>
                <a href="<?php echo admin_url() . 'admin.php?page=donation-list'; ?>" class="button button-primary"><?php _e('Clear Search', THEME_TEXTDOMAIN); ?></a>
            </form>
            <?php
            $DonationTblObject->display();
            ?>
        </div>
        <?php
    }

}

if (!function_exists('manage_product_prices_func')) {

    function manage_product_prices_func() {
        $GeneralThemeObject = new GeneralTheme();
        $currentUserDetails = $GeneralThemeObject->user_details();
        $currentUserCities = $currentUserDetails->data['city'];
        $productArr = [];
        $data_arr = [];

        /* Sub admin city products */
        if (is_array($currentUserCities) && count($currentUserCities) > 0) {
            foreach ($currentUserCities as $eachcity) {

                $getProductsArgs = ['post_type' => themeFramework::$theme_prefix . 'product', 'posts_per_page' => -1, 
                'meta_query' => [
                        [
                            'key' => '_simple_product',
                            // 'value' => serialize(strval(1)),
                            'value' => ['a:1:{i:0;i:1;}', 'a:1:{i:0;s:1:"1";}'],
                            'compare' => 'IN'
                        ]
                ]
            ];

                $getCityDetails = get_term_by('id', $eachcity, themeFramework::$theme_prefix . 'product_city');

                if(isset($_GET['product_name']) && $_GET['product_name'] != ''){
                    $getProductsArgs['s'] = $_GET['product_name'];
                }

                $getProducts = get_posts($getProductsArgs);
                if (is_array($getProducts) && count($getProducts) > 0) {
                    $productNameArr = [];
                    foreach ($getProducts as $eachProduct) {
                        $productArr[] = $eachProduct->ID;
                    }
                }
            }
        }

        if (is_array($productArr) && count($productArr) > 0) {
            $uniqueProducts = array_unique($productArr);
        }
        ?>
        <h2><?php _e('Manage Product Price', THEME_TEXTDOMAIN); ?></h2>
        <div class="wrap">
            
                    <?php
                    if (is_array($uniqueProducts) && count($uniqueProducts) > 0):
                        $i = 0;
                        foreach ($uniqueProducts as $eachProduct):
                            $productDetails = $GeneralThemeObject->product_details($eachProduct);
                            $productThumbnail = wp_get_attachment_image_src($productDetails->data['thumbnail_id'], 'full');
                            $productPath = get_attached_file($productDetails->data['thumbnail_id']);

                            if($productPath){
                                $productImg = '<img src="'. $productThumbnail[0] .'" width="100" height="100"/>';
                            } else{
                                $productImg = '<img src="https://via.placeholder.com/100x100" />';
                            }

                            $simpleProductAction = '<a class="button button-primary" href="'. admin_url() . 'admin.php?page=sub-admin-manage-product-city-prices&product_edit=' . base64_encode($productDetails->data['ID']) .'">Manage Prices</a>';


                            $data_arr[$i] = [
                            'pro_id' => $eachProduct,
                            'thumbnail' => $productImg,
                            'title' => $productDetails->data['title'],
                            'price' => ($productDetails->data['default_price']) ? 'R$ ' . number_format($productDetails->data['default_price'], 2) : 'R$ ' . '0.00',
                            'action' => $simpleProductAction
                            ];
                            $i++;
                        endforeach;
                    endif;
                    ?>
                    <?php
                    $SubAdminSimpleProductsObject = new Sub_Admin_Simple_Products_List_Table();
                    $SubAdminSimpleProductsObject->prepare_items($data_arr);
                    ?>
            <form name="sub_admin_simple_product_list_tbl" id="sub_admin_simple_product_list_tbl" method="get" action="<?php echo admin_url() . 'admin.php?page=sub-admin-manage-product-prices'; ?>">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
                <input type="text" name="product_name" value="<?php echo $_GET['product_name']; ?>" autocomplete="off" placeholder="<?php _e('Product name', THEME_TEXTDOMAIN); ?>">
                <button class="button button-primary" name="">Search</button>
                <a href="<?php echo admin_url() . 'admin.php?page=sub-admin-manage-product-prices'; ?>" class="button button-primary"><?php _e('Clear Search', THEME_TEXTDOMAIN); ?></a>
            </form>
            <?php
                    $SubAdminSimpleProductsObject->display();
                     ?>
               
        </div>
        <?php
    }

}

if (!function_exists('manage_product_city_prices_func')) {

    function manage_product_city_prices_func() {
        $GeneralThemeObject = new GeneralTheme();
        $currentUserDetails = $GeneralThemeObject->user_details();
        $currentUserCities = $currentUserDetails->data['city'];
        ?>
        <h2><?php _e('Manage Product Prices Based on Cities', THEME_TEXTDOMAIN); ?></h2>
        <a  class="button button-primary" href="<?php echo admin_url() . 'admin.php?page=sub-admin-manage-product-prices'; ?>"><?php _e('< Back to list', THEME_TEXTDOMAIN); ?></a>
        <div class="wrap">
            <?php
            if (isset($_GET['product_edit']) && $_GET['product_edit'] != ''):
                $productID = base64_decode($_GET['product_edit']);
                $getProductDetails = $GeneralThemeObject->product_details($productID);
                $productCities = get_post_meta($productID, '_product_cities', TRUE);
                $getProductCitiesPrices = get_post_meta($productID, '_product_cities_prices', true);

                /* Update product prices */
                if (isset($_POST['update_product_prices'])) {
                    $productCityKeys = $_POST['product_city_key'];
                    $productCitiesPrice = $_POST['product_city_price'];
                    $product_id = $_POST['product_id'];

                    if (is_array($productCityKeys) && count($productCityKeys) > 0) {
                        foreach ($productCityKeys as $eachKey) {
                            $getProductCitiesPrices[$eachKey] = $productCitiesPrice[$eachKey];
                        }
                    }

                    update_post_meta($product_id, '_product_cities_prices', $getProductCitiesPrices);
                    wp_redirect(admin_url() . 'admin.php?page=sub-admin-manage-product-city-prices&product_edit=' . base64_encode($product_id));
                    exit;
                }
                ?>
                <form name="" method="post" action="">
                    <input type="hidden" name="product_id" value="<?php echo $productID; ?>"/>
                    <table class="widefat">
                        <thead>
                        <th colspan=2""><strong><?php _e('Price for ' . get_the_title($productID), THEME_TEXTDOMAIN); ?></strong></th>
                        </thead>
                        <?php
                        if (is_array($productCities) && count($productCities) > 0) {
                            foreach ($productCities as $eachUserCityKey => $eachCityVal) {
                                // if (is_array($productCities) && count($productCities) > 0 && in_array($eachCityVal, $currentUserCities)) {
                                    $getCityBy = get_term_by('id', $eachCityVal, themeFramework::$theme_prefix . 'product_city');
                                    ?>
                                    <tr>
                                        <td width="50%"><input type="hidden" name="product_city_key[]" value="<?php echo $eachUserCityKey; ?>"><strong><?php echo $getCityBy->name; ?></strong> : </td>
                                        <td width="40%">R$ <input type="text" name="product_city_price[<?php echo $eachUserCityKey; ?>]" value="<?php echo $getProductCitiesPrices[$eachUserCityKey]; ?>" placeholder="Enter price (in R$)"></td>
                                    </tr>
                                    <?php
                                //}
                            }
                        }
                        ?>
                        <tr>
                            <td colspan="2"><button class="button button-primary" type="submit" name="update_product_prices"><?php _e('Update Prices', THEME_TEXTDOMAIN); ?></button></td>
                        </tr>
                    </table>
                </form>
                <?php
            endif;
            ?>
        </div>
        <?php
    }

}

if (!function_exists('sub_admin_membership_transaction_func')) {

    function sub_admin_membership_transaction_func() {
        $GeneralThemeObject = new GeneralTheme();
        $MembershipObject = new classMemberShip();
        $queryString = NULL;
        $currentUserID = get_current_user_id();
        $currentUserDetails = $GeneralThemeObject->user_details($currentUserID);
        if (isset($_GET['membership_id']) && $_GET['membership_id'] != ''):
            $queryString .= " AND `order_id`='" . $_GET['membership_id'] . "'";
        endif;
        if (isset($_GET['transaction_id']) && $_GET['transaction_id'] != ''):
            $queryString .= " AND `transaction_id`='" . $_GET['transaction_id'] . "'";
        endif;
        if (isset($_GET['payment_status']) && $_GET['payment_status'] != ''):
            $queryString .= " AND `payment_status`='" . $_GET['payment_status'] . "'";
        endif;
        $membershipDetails = $MembershipObject->getMembershipDetails('', $queryString);
        ?>
        <h2><?php _e('Membership Transaction History', THEME_TEXTDOMAIN); ?></h2>
        <div class="wrap">
            <?php
            if (is_array($membershipDetails) && count($membershipDetails) > 0) :
                $i = 0;
                $data_arr = [];
                foreach ($membershipDetails as $eachMembershipDetails) :

                    if ($eachMembershipDetails->payment_status == 1):
                        $paidSelected = 'selected';
                        $unPaidSelected = '';
                    elseif ($eachMembershipDetails->payment_status == 2):
                        $paidSelected = '';
                        $unPaidSelected = 'selected';
                    endif;

                    $userDetails = $GeneralThemeObject->user_details($eachMembershipDetails->user_id);

                    $actionSelectBox = '<select name="" class="membership-status-change" data-order="' . $eachMembershipDetails->order_id . '" style="width:100%;">';
                    $actionSelectBox .= '<option value="">-Payment status-</option>';
                    $actionSelectBox .= '<option value="1" ' . $paidSelected . '>Paid</option>';
                    $actionSelectBox .= '<option value="2" ' . $unPaidSelected . '>Unpaid</option>';
                    $actionSelectBox .= '</select>';

                    if (is_array($currentUserDetails->data['city']) && count($currentUserDetails->data['city']) > 0 && in_array($userDetails->data['city'], $currentUserDetails->data['city'])) {
                        $data_arr[$i] = [
                            'order_id' => $eachMembershipDetails->order_id,
                            'supplier' => $userDetails->data['fname'] . ' ' . $userDetails->data['lname'],
                            'transaction_id' => $eachMembershipDetails->transaction_id,
                            'total_price' => 'R$ ' . number_format($eachMembershipDetails->total_price, 2),
                            'payment_date' => date('d M, Y', $eachMembershipDetails->payment_date),
                            'next_payment_date' => date('d M, Y', $eachMembershipDetails->next_payment_date),
                            'duration' => date('d M, Y', $eachMembershipDetails->plan_start_date) . ' - ' . date('d M, Y', $eachMembershipDetails->plan_end_date),
                            'action' => $actionSelectBox,
                        ];
                        $i++;
                    }
                endforeach;
            endif;

            $MemberShipObject = new Membership_List_Table();
            $MemberShipObject->prepare_items($data_arr);
            ?>
            <form name="membership_payment_list_tbl" id="membership_payment_list_tbl" method="get" action="<?php echo admin_url() . 'admin.php?page=membership-transaction'; ?>">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
                <input type="text" name="membership_id" value="<?php echo $_GET['membership_id']; ?>" placeholder="<?php _e('Membership ID', THEME_TEXTDOMAIN); ?>">
                <input type="text" name="transaction_id" value="<?php echo $_GET['transaction_id']; ?>" placeholder="<?php _e('Transaction ID', THEME_TEXTDOMAIN); ?>">
                <select name="payment_status">
                    <option value=""><?php _e('-Payment status-', THEME_TEXTDOMAIN); ?></option>
                    <option value="1" <?php echo ($_GET['payment_status'] == 1) ? 'selected' : ''; ?>><?php _e('Paid', THEME_TEXTDOMAIN); ?></option>
                    <option value="2" <?php echo ($_GET['payment_status'] == 2) ? 'selected' : ''; ?>><?php _e('Unpaid', THEME_TEXTDOMAIN); ?></option>
                </select>
                <button class="button button-primary" name="">Search</button>
                <a href="<?php echo admin_url() . 'admin.php?page=sub-admin-membership-transaction'; ?>" class="button button-primary"><?php _e('Clear Search', THEME_TEXTDOMAIN); ?></a>
            </form>
            <?php
            $MemberShipObject->display();
            ?>
        </div>
        <?php
    }

}


if (!function_exists('sub_admin_deal_lists_details_func')) {

    function sub_admin_deal_lists_details_func() {
        $FinalizeData = new classFinalize();
        if (isset($_GET['deal']) && $_GET['deal'] != ''):
            $dealID = base64_decode($_GET['deal']);
        endif;
        if (isset($_GET['user']) && $_GET['user'] != ''):
            $userID = base64_decode($_GET['user']);
        endif;
        $dealDetails = $FinalizeData->getDealDetails($dealID);
        $dealProductDetails = $FinalizeData->getDealProductDetails($dealID, $userID);
        $getDistinctProduct = $FinalizeData->selectDistinctProductIDs($dealID, $userID);
        ?>
        <br>
        <a href="<?php echo admin_url() . 'admin.php?page=sub-admin-deal-lists'; ?>" class="button button-primary"><?php _e('< Back to list', THEME_TEXTDOMAIN); ?></a>
        <h2><?php _e('Details for deal ID:', THEME_TEXTDOMAIN); ?> <?php echo $dealID; ?></h2>
        <div class="wrap">
            <table class="widefat">
                <tbody>
                    <tr>
                        <td><?php echo '<strong>Deal Name: </strong>' . $dealDetails->data['deal_name']; ?></td>
                        <td><?php echo '<strong>Deal Description: </strong>' . $dealDetails->data['deal_description']; ?></td>
                    </tr>
                </tbody>
            </table>
            <br>
            <?php if (is_array($dealProductDetails) && count($dealProductDetails) > 0): ?>
                <?php foreach ($dealProductDetails as $eachdeals): ?>
                    <?php
                    $bundleProducts = unserialize($eachdeals['bundle_products']);
                    ?>
            
                    <table class="widefat">
                        <thead>
                        <th colspan="3"><?php echo '<strong>State: </strong>' . $eachdeals['state'] . '; ' . '<strong>City: </strong>' . $eachdeals['city']; ?></th>
                        </thead>
                        <thead>
                        <th><strong><?php _e('Product: ', THEME_TEXTDOMAIN); ?></strong><?php echo get_the_title($eachdeals['product_id']); ?></th>
                        <th><strong><?php _e('Quantity: ', THEME_TEXTDOMAIN); ?></strong><?php echo $eachdeals['no_of_items']; ?></th>
                        <th><strong><?php echo $eachdeals['price']; ?></strong></th>
                        </thead>
                        <tbody>
                            <?php
                            if (is_array($bundleProducts) && count($bundleProducts) > 0) :
                                foreach ($bundleProducts as $eachBundleProduct) :
                                    ?>
                                    <tr>
                                        <td><?php echo get_the_title($eachBundleProduct['product_id']) . ' - ' . $eachBundleProduct['product_quantity'] . ' ' . $eachBundleProduct['product_unit']; ?></td>
                                        <td><?php echo $eachBundleProduct['product_type']; ?></td>
                                        <td><?php echo 'R$ ' . number_format($eachBundleProduct['product_price'], 2) . '/' . $eachBundleProduct['product_unit']; ?></td>
                                    </tr>
                                    <?php
                                endforeach;
                            endif;
                            ?>

                        </tbody>
                    </table>
                    <br>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <br>
        <h2><?php _e('Supplier Details:', THEME_TEXTDOMAIN); ?></h2>
        <div class="wrap">
            <?php
            if (is_array($getDistinctProduct) && count($getDistinctProduct) > 0) :
                foreach ($getDistinctProduct as $eachDistinctProduct) :
                    $dealSupplierDetails = $FinalizeData->getDealSupplierDetails($dealID, $userID, $eachDistinctProduct->product_id);
                    ?>
                    <table class="widefat">
                        <thead>
                        <th colspan="2"><strong><?php _e('Product: ', THEME_TEXTDOMAIN); ?></strong><?php echo get_the_title($eachDistinctProduct->product_id); ?></th>
                        </thead>
                        <tbody>
                            <?php
                            if (is_array($dealSupplierDetails) && count($dealSupplierDetails) > 0) :
                                foreach ($dealSupplierDetails as $eachSupplier) :
                                    ?>
                                    <tr>
                                        <td><?php echo ($eachSupplier['supplier_id'] == 9999999999 || $eachSupplier['supplier_id'] == 1) ? 'Administrator' : $eachSupplier['supplier_name']; ?><?php echo ' (' . $eachSupplier['state'] . ', ' . $eachSupplier['city'] . ')'; ?></td>
                                        <td><?php echo $eachSupplier['bundled_product_details']; ?></td>
                                    </tr>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                        </tbody>
                    </table>
                    <br>
                    <?php
                endforeach;
            endif;
            ?>
        </div>
        <?php
    }

}

if (!function_exists('sub_admin_deal_lists_func')) {

    function sub_admin_deal_lists_func() {
        $GenerelThemeObject = new GeneralTheme();
        $FinalizeObject = new classFinalize();
        $queryString = NULL;
        $data_arr = [];
        if (isset($_GET['deal_id']) && $_GET['deal_id'] != '') {
            $queryString .= " AND `deal_id`='" . $_GET['deal_id'] . "'";
        }

        if (isset($_GET['deal_status']) && $_GET['deal_status'] != '') {
            $queryString .= " AND `deal_status`='" . $_GET['deal_status'] . "'";
        }

        $getAllDeals = $FinalizeObject->getDeals('', $queryString, TRUE);
        $currentUserID = get_current_user_id();
        $currentUserDetails = $GenerelThemeObject->user_details($currentUserID);
        
        ?>
        <h2><?php _e('All Deals', THEME_TEXTDOMAIN); ?></h2>
        <div class="wrap">
            <?php
            if (is_array($getAllDeals) && count($getAllDeals) > 0) :
                $i = 0;
                foreach ($getAllDeals as $eachDeal) :
                    $dealDetails = $FinalizeObject->getDealDetails($eachDeal->deal_id);

                    if ($eachDeal->deal_status == 1) {
                        $dealCompletedSelected = 'selected';
                        $dealInitiatedSelected = '';
                        $dealProcessingSelected = '';
                        $dealRejectedSelected = '';
                        $dealErasedSelected = '';
                    } else if ($eachDeal->deal_status == 2) {
                        $dealCompletedSelected = '';
                        $dealInitiatedSelected = 'selected';
                        $dealProcessingSelected = '';
                        $dealRejectedSelected = '';
                        $dealErasedSelected = '';
                    } else if ($eachDeal->deal_status == 3) {
                        $dealCompletedSelected = '';
                        $dealInitiatedSelected = '';
                        $dealProcessingSelected = 'selected';
                        $dealRejectedSelected = '';
                        $dealErasedSelected = '';
                    } else if ($eachDeal->deal_status == 4) {
                        $dealCompletedSelected = '';
                        $dealInitiatedSelected = '';
                        $dealProcessingSelected = '';
                        $dealRejectedSelected = 'selected';
                        $dealErasedSelected = '';
                    } else if ($eachDeal->deal_status == 5) {
                        $dealCompletedSelected = '';
                        $dealInitiatedSelected = '';
                        $dealProcessingSelected = '';
                        $dealRejectedSelected = '';
                        $dealErasedSelected = 'selected';
                    }

                    $actionSelectBox = '<select name="" class="deal-status-change" data-deal="' . $dealDetails->data['deal_id'] . '">';
                    $actionSelectBox .= '<option value="">-Select status-</option>';
                    $actionSelectBox .= '<option value="1" ' . $dealCompletedSelected . '>Completed</option>';
                    $actionSelectBox .= '<option value="2" ' . $dealInitiatedSelected . '>Initiated</option>';
                    $actionSelectBox .= '<option value="3" ' . $dealProcessingSelected . '>Processing</option>';
                    $actionSelectBox .= '<option value="4" ' . $dealRejectedSelected . '>Rejected</option>';
                    $actionSelectBox .= '<option value="5" ' . $dealErasedSelected . '>Deleted</option>';
                    $actionSelectBox .= '</select>';

                    $userDetails = $GenerelThemeObject->user_details($eachDeal->user_id);

                    if (is_array($currentUserDetails->data['city']) && count($currentUserDetails->data['city']) > 0 && in_array($userDetails->data['city'], $currentUserDetails->data['city'])) {
                        $data_arr[$i] = [
                            'deal_id' => $dealDetails->data['deal_id'],
                            'name' => $dealDetails->data['user_name'],
                            'deal_name' => $dealDetails->data['deal_name'],
                            //'transaction_id' => $dealDetails->data['transaction_id'],
                            'total_price' => $dealDetails->data['total_price'],
                            'status' => $dealDetails->data['status'],
                            'initiated' => $dealDetails->data['initiated'],
                            'completed' => $dealDetails->data['completed'],
                            'action' => $actionSelectBox,
                            'details' => '<a href="' . admin_url() . 'admin.php?page=sub-admin-deal-lists-details&deal=' . base64_encode($dealDetails->data['deal_id']) . '&user=' . base64_encode($dealDetails->data['user_id']) . '" class="button button-primary">Details</a>'
                        ];
                        $i++;
                    }
                endforeach;
            endif;



            $DealObject = new Deal_List_Table();
            $DealObject->prepare_items($data_arr);
            ?>
            <form name="application_payment_list_tbl" id="application_payment_list_tbl" method="get" action="<?php echo admin_url() . 'admin.php?page=deal-lists'; ?>">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
                <input type="text" name="deal_id" value="<?php echo $_GET['deal_id']; ?>" placeholder="<?php _e('Deal ID', THEME_TEXTDOMAIN); ?>">
                <select name="deal_status">
                    <option value=""><?php _e('-Deal status-', THEME_TEXTDOMAIN); ?></option>
                    <option value="1" <?php echo ($_GET['deal_status'] == 1) ? 'selected' : ''; ?>><?php _e('Completed', THEME_TEXTDOMAIN); ?></option>
                    <option value="2" <?php echo ($_GET['deal_status'] == 2) ? 'selected' : ''; ?>><?php _e('Initiated', THEME_TEXTDOMAIN); ?></option>
                    <option value="3" <?php echo ($_GET['deal_status'] == 3) ? 'selected' : ''; ?>><?php _e('Processing', THEME_TEXTDOMAIN); ?></option>
                    <option value="4" <?php echo ($_GET['deal_status'] == 4) ? 'selected' : ''; ?>><?php _e('Rejected', THEME_TEXTDOMAIN); ?></option>
                </select>
                <button class="button button-primary" name="">Search</button>
                <a href="<?php echo admin_url() . 'admin.php?page=sub-admin-deal-lists'; ?>" class="button button-primary"><?php _e('Clear Search', THEME_TEXTDOMAIN); ?></a>
            </form>
            <?php
            $DealObject->display();
            ?>
        </div>
        <?php
    }

}