<?php
/**
 * --------------------------------------
 * ADMIN MENU:: Add admin menu page
 * --------------------------------------
 */
if (!function_exists('theme_admin_menu_func')) {

    function theme_admin_menu_func() {
        $GeneralThemeObject = new GeneralTheme();
        $getUserDetails = $GeneralThemeObject->user_details();

        /* Sub Administrator Module */

        if (is_array($getUserDetails->data['sub_admin_capabilities']) && count($getUserDetails->data['sub_admin_capabilities']) > 0 && in_array('manage_product_prices', $getUserDetails->data['sub_admin_capabilities'])) {
            add_menu_page(_x('Manage Product Prices', THEME_TEXTDOMAIN), _x('Manage Product Prices', THEME_TEXTDOMAIN), 'manage_product_prices', 'sub-admin-manage-product-prices', 'manage_product_prices_func');
            add_submenu_page('sub-admin-manage-product-prices', _x('Manage Product City Prices', THEME_TEXTDOMAIN), '', 'manage_product_prices', 'sub-admin-manage-product-city-prices', 'manage_product_city_prices_func');
        }
        if (is_array($getUserDetails->data['sub_admin_capabilities']) && count($getUserDetails->data['sub_admin_capabilities']) > 0 && in_array('manage_export_import', $getUserDetails->data['sub_admin_capabilities'])) {
            add_menu_page(_x('Export/ Import', THEME_TEXTDOMAIN), _x('Export/ Import', THEME_TEXTDOMAIN), 'manage_export_import', 'sub-admin-product-export-import', 'product_export_import_func');
        }
        if (is_array($getUserDetails->data['sub_admin_capabilities']) && count($getUserDetails->data['sub_admin_capabilities']) > 0 && in_array('manage_membership_transaction', $getUserDetails->data['sub_admin_capabilities'])) {
            add_menu_page(_x('Membership Transaction', THEME_TEXTDOMAIN), _x('Membership Transaction', THEME_TEXTDOMAIN), 'manage_membership_transaction', 'sub-admin-membership-transaction', 'sub_admin_membership_transaction_func');
        }
        if (is_array($getUserDetails->data['sub_admin_capabilities']) && count($getUserDetails->data['sub_admin_capabilities']) > 0 && in_array('manage_deal_list', $getUserDetails->data['sub_admin_capabilities'])) {
            add_menu_page(_x('Deal List', THEME_TEXTDOMAIN), _x('Deal List', THEME_TEXTDOMAIN), 'manage_deal_list', 'sub-admin-deal-lists', 'sub_admin_deal_lists_func');
            add_submenu_page('sub-admin-deal-lists', '', '', 'manage_deal_list', 'sub-admin-deal-lists-details', 'sub_admin_deal_lists_details_func');
        }
        if (is_array($getUserDetails->data['sub_admin_capabilities']) && count($getUserDetails->data['sub_admin_capabilities']) > 0 && in_array('manage_donation_list', $getUserDetails->data['sub_admin_capabilities'])) {
            add_menu_page(_x('Donation List', THEME_TEXTDOMAIN), _x('Donation List', THEME_TEXTDOMAIN), 'manage_donation_list', 'sub-admin-donation-list', 'sub_admin_donation_lists_func');
        }
        if (is_array($getUserDetails->data['sub_admin_capabilities']) && count($getUserDetails->data['sub_admin_capabilities']) > 0 && in_array('manage_deal_review_list', $getUserDetails->data['sub_admin_capabilities'])) {
            add_menu_page(_x('Deal Reviews', THEME_TEXTDOMAIN), _x('Deal Reviews', THEME_TEXTDOMAIN), 'manage_deal_list', 'sub-admin-deal-reviews', 'sub_admin_deal_reviews_func');
            add_submenu_page('sub-admin-deal-reviews', _x('Deal Review Details', THEME_TEXTDOMAIN), '', 'manage_deal_list', 'sub-admin-deal-review-details', 'sub_admin_deal_review_details_func');
        }

        /* Main Administrator Module */

        add_menu_page(_x('QCMO Management', THEME_TEXTDOMAIN), _x('QCMO Management', THEME_TEXTDOMAIN), 'manage_options', 'andre-settings', 'andre_settings_func');
        //add_menu_page(_x('Membership Transaction', THEME_TEXTDOMAIN), _x('Membership Transaction', THEME_TEXTDOMAIN), 'manage_options', 'membership-transaction', 'membership_transaction_func');
        add_submenu_page('andre-settings', _x('Unmatched Products', THEME_TEXTDOMAIN), _x('Unmatched Products', THEME_TEXTDOMAIN), 'manage_options', 'unmatched-products', 'unmatched_products_func');
        add_submenu_page('andre-settings',_x('Donation List', THEME_TEXTDOMAIN), _x('Donation List', THEME_TEXTDOMAIN), 'manage_options', 'donation-list', 'donation_lists_func');
        add_submenu_page('andre-settings', _x('Export/ Import', THEME_TEXTDOMAIN), _x('Export/ Import', THEME_TEXTDOMAIN), 'manage_options', 'product-export-import', 'product_export_import_func');
        add_submenu_page('andre-settings',_x('Deal Reviews', THEME_TEXTDOMAIN), _x('Deal Reviews', THEME_TEXTDOMAIN), 'manage_options', 'deal-reviews', 'deal_reviews_func');
        add_submenu_page('deal-reviews', _x('Deal Review Details', THEME_TEXTDOMAIN), '', 'manage_options', 'deal-review-details', 'deal_review_details_func');
        
        add_submenu_page('andre-settings', _x('Deal List', THEME_TEXTDOMAIN), _x('Deal List', THEME_TEXTDOMAIN), 'manage_options', 'deal-lists', 'deal_lists_func');
        add_submenu_page('deal-lists', '', '', 'manage_options', 'deal-lists-details', 'deal_lists_details_func');
        
        add_submenu_page('edit.php?post_type=andr_membership', _x('Membership Transaction', THEME_TEXTDOMAIN), _x('Membership Transaction', THEME_TEXTDOMAIN), 'manage_options', 'membership-transaction', 'membership_transaction_func');

        add_submenu_page('edit.php?post_type=' . themeFramework::$theme_prefix . 'advertisement', _x('Advertisement Price Settings', THEME_TEXTDOMAIN), _x('Advertisement Price Settings', THEME_TEXTDOMAIN), 'manage_options', 'advertisement-price-settings', 'advertisement_price_settings_func');
        add_submenu_page('edit.php?post_type=' . themeFramework::$theme_prefix . 'advertisement', _x('Advertisement Payment List', THEME_TEXTDOMAIN), _x('Advertisement Payment List', THEME_TEXTDOMAIN), 'manage_options', 'ad-payment-list', 'ad_payment_lists_func');
        add_submenu_page('edit.php?post_type=' . themeFramework::$theme_prefix . 'advertisement', _x('Advertisement Price Chart', THEME_TEXTDOMAIN), _x('Advertisement Price Chart', THEME_TEXTDOMAIN), 'edit_published_andr_advertisement', 'ad-price-chart', 'ad_price_chart_func');
        
        //add_menu_page(_x('Announcement Settings', THEME_TEXTDOMAIN), _x('Announcement Settings', THEME_TEXTDOMAIN), 'manage_options', 'announcement-settings', 'announcement_settings_func');
        add_submenu_page('edit.php?post_type=andr_announcement', _x('Announcement Settings', THEME_TEXTDOMAIN), _x('Announcement Settings', THEME_TEXTDOMAIN), 'manage_options', 'announcement-settings', 'announcement_settings_func');
        add_submenu_page('edit.php?post_type=andr_announcement', _x('Announcement Transactions', THEME_TEXTDOMAIN), _x('Announcement Transactions', THEME_TEXTDOMAIN), 'manage_options', 'announcement-transactions', 'announcement_transactions_func');
    }

}

if (!function_exists('donation_lists_func')) {

    function donation_lists_func() {
        
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
            <form>
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
                <?php
                $DonationTblObject->display();
                ?>
            </form>            
        </div>
        <?php
    }

}

if (!function_exists('membership_transaction_func')) {

    function membership_transaction_func() {
        $GeneralThemeObject = new GeneralTheme();
        $MembershipObject = new classMemberShip();
        $queryString = NULL;
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
                endforeach;
            endif;

            $MemberShipObject = new Membership_List_Table();
            $MemberShipObject->prepare_items($data_arr);
            ?>
            <!--<form name="membership_payment_list_tbl" id="membership_payment_list_tbl" method="get" action="<?php echo admin_url() . 'admin.php?page=membership-transaction'; ?>">-->
            <form name="membership_payment_list_tbl" id="membership_payment_list_tbl" method="get" action="">
                <input type="hidden" name="post_type" value="<?php echo $_REQUEST['post_type']; ?>">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
                <input type="text" name="membership_id" value="<?php echo $_GET['membership_id']; ?>" placeholder="<?php _e('Membership ID', THEME_TEXTDOMAIN); ?>">
                <input type="text" name="transaction_id" value="<?php echo $_GET['transaction_id']; ?>" placeholder="<?php _e('Transaction ID', THEME_TEXTDOMAIN); ?>">
                <select name="payment_status">
                    <option value=""><?php _e('-Payment status-', THEME_TEXTDOMAIN); ?></option>
                    <option value="1" <?php echo ($_GET['payment_status'] == 1) ? 'selected' : ''; ?>><?php _e('Paid', THEME_TEXTDOMAIN); ?></option>
                    <option value="2" <?php echo ($_GET['payment_status'] == 2) ? 'selected' : ''; ?>><?php _e('Unpaid', THEME_TEXTDOMAIN); ?></option>
                </select>
                <button class="button button-primary" name=""><?php _e('Search', THEME_TEXTDOMAIN); ?></button>
                <a href="<?php echo admin_url() . 'edit.php?post_type=andr_membership&page=membership-transaction'; ?>" class="button button-primary"><?php _e('Clear Search', THEME_TEXTDOMAIN); ?></a>
            </form>
            <form>
                <input type="hidden" name="post_type" value="<?php echo $_REQUEST['post_type']; ?>">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
                <?php
                $MemberShipObject->display();
                ?>
            </form>
        </div>
        <?php
    }

}

if (!function_exists('deal_lists_details_func')) {

    function deal_lists_details_func() {
        
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
        <a href="<?php echo admin_url() . 'admin.php?page=deal-lists'; ?>" class="button button-primary"><?php _e('< Back to list', THEME_TEXTDOMAIN); ?></a>
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
                                    $unserializedData = unserialize($eachSupplier['bundled_product_details']);
                                    ?>
                                    <tr>
                                        <!--<td><?php echo ($eachSupplier['supplier_id'] == 9999999999 || $eachSupplier['supplier_id'] == 1) ? 'Administrator' : $eachSupplier['supplier_name']; ?><?php echo ' (' . $eachSupplier['state'] . ', ' . $eachSupplier['city'] . ')'; ?></td>-->
                                        <td>
                                            <?php
                                            if ($eachSupplier['supplier_id'] == 9999999999):
                                                echo 'Supplier not found';
                                            else:
                                                echo $eachSupplier['supplier_name'] . ' (' . $eachSupplier['state'] . ', ' . $eachSupplier['city'] . ')';
                                            endif;
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if (is_array($unserializedData) && count($unserializedData) > 0) :
                                                foreach ($unserializedData as $eachUnserializedData) :
                                                    $explodedData = explode('-', $eachUnserializedData);
                                                    echo get_the_title($explodedData[0]) . ' - ' . $explodedData[1] . ' ' . $explodedData[2] . ', ';
                                                endforeach;
                                            else:
                                                $explodedData = explode('-', $unserializedData);
                                                echo get_the_title($explodedData[0]) . ' - ' . $explodedData[1] . ' ' . $explodedData[2] . ', ';
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
                    <br>
                    <?php
                endforeach;
            endif;
            ?>
            <?php
//            $ss = $FinalizeData->sortingSuppliersToEmailAboutDealFinalization($dealID, $userID);
//            echo '<pre>';
//            print_r($ss);
//            echo '</pre>';
            ?>
        </div>
        <?php
    }

}

if (!function_exists('deal_lists_func')) {

    function deal_lists_func() {
        
        $GenerelThemeObject = new GeneralTheme();
        $FinalizeObject = new classFinalize();
        $queryString = NULL;
        if (isset($_GET['deal_id']) && $_GET['deal_id'] != '') {
            $queryString .= " AND `deal_id`='" . $_GET['deal_id'] . "'";
        }

        if (isset($_GET['deal_status']) && $_GET['deal_status'] != '') {
            $queryString .= " AND `deal_status`='" . $_GET['deal_status'] . "'";
        }

        $getAllDeals = $FinalizeObject->getDeals('', $queryString, TRUE);
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
                        'details' => '<a href="' . admin_url() . 'admin.php?page=deal-lists-details&deal=' . base64_encode($dealDetails->data['deal_id']) . '&user=' . base64_encode($dealDetails->data['user_id']) . '" class="button button-primary">Details</a>'
                    ];
                    $i++;
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
                <a href="<?php echo admin_url() . 'admin.php?page=deal-lists'; ?>" class="button button-primary"><?php _e('Clear Search', THEME_TEXTDOMAIN); ?></a>
            </form>
            <form method="get" action="<?php echo admin_url() . 'admin.php?page=deal-lists'; ?>">
                
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
                <?php
                $DealObject->display();
                ?>
            </form>
        </div>
        <?php
    }

}

if (!function_exists('andre_settings_func')) {

    function andre_settings_func() {
        $GeneralThemeObject = new GeneralTheme();
        $get_fb_app_id = get_option('fb_app_id');
        $get_google_api_key = get_option('google_api_key');
        $get_show_banner_option = get_option('show_banner_option');
        $get_business_id = get_option('business_id');
        $get_testmode = get_option('testmode');
        $get_global_deal_acceptance = get_option('_global_deal_acceptance');
        $get_paypal_donation_amount = get_option('_payapl_donation_amount');
        $get_new_flag_announements = get_option('new_flag_announements');
        $get_new_flag_suppliers = get_option('new_flag_suppliers');
        $getHomepageBannerImage = $GeneralThemeObject->getHomeBannerImage();

        $get_paypal_api_username = get_option('_paypal_api_username');
        $get_paypal_api_password = get_option('_paypal_api_password');
        $get_paypal_api_signature = get_option('_paypal_api_signature');
        $get_paypal_mode = get_option('_paypal_mode');

        /* PayPal Settings Submit */
        if (isset($_POST['payment_settings_sbmt'])) {
            $paypal_api_username = strip_tags(trim($_POST['paypal_api_username']));
            $paypal_api_password = strip_tags(trim($_POST['paypal_api_password']));
            $paypal_api_signature = strip_tags(trim($_POST['paypal_api_signature']));
            $paypal_api_mode = $_POST['paypal_api_mode'];

            update_option('_paypal_api_username', $paypal_api_username);
            update_option('_paypal_api_password', $paypal_api_password);
            update_option('_paypal_api_signature', $paypal_api_signature);
            update_option('_paypal_mode', $paypal_api_mode);

            wp_redirect(admin_url() . 'admin.php?page=andre-settings');
            exit;
        }

        /* Social Settings Submit */
        if (isset($_POST['social_settings_sbmt'])) {
            $fb_app_id = strip_tags(trim($_POST['fb_app_id']));
            $google_api_key = strip_tags(trim($_POST['google_api_key']));

            update_option('fb_app_id', $fb_app_id);
            update_option('google_api_key', $google_api_key);
            wp_redirect(admin_url() . 'admin.php?page=andre-settings');
            exit;
        }

        /* 'New' Flag Settings Submit */
        if (isset($_POST['new_flag_sbmt'])) {
            $new_flag_day_for_announcements = strip_tags(trim($_POST['new_flag_day_for_announcements']));
            $new_flag_day_for_suppliers = strip_tags(trim($_POST['new_flag_day_for_suppliers']));

            update_option('new_flag_announements', $new_flag_day_for_announcements);
            update_option('new_flag_suppliers', $new_flag_day_for_suppliers);
            wp_redirect(admin_url() . 'admin.php?page=andre-settings');
            exit;
        }

        /* Home Banner Settings Submit */
        if (isset($_POST['homebanner_settings_sbmt'])) {
            $homepage_banner = $_FILES['homepage_banner'];
            $show_homepage_banner = $_POST['show_homepage_banner'];
            if ($homepage_banner['name'] != '') {
                $bannerExt = pathinfo($homepage_banner['name'], PATHINFO_EXTENSION);
                if (in_array($bannerExt, $GeneralThemeObject->image_files_extension)) {
                    $uploadedBanner = $GeneralThemeObject->common_file_upload($homepage_banner);
                    $uploadedBannerID = $GeneralThemeObject->create_attachment($uploadedBanner);
                    update_option('homepage_banner_image', $uploadedBannerID);
                    wp_redirect(admin_url() . 'admin.php?page=andre-settings');
                    exit;
                } else {
                    ?>
                    <div class="error notice"><p><strong><?php _e('Uploaded file must be image.', THEME_TEXTDOMAIN); ?></strong></p></div>
                    <?php
                }
            }
            update_option('show_banner_option', $show_homepage_banner);
            wp_redirect(admin_url() . 'admin.php?page=andre-settings');
            exit;
        }

        /* Social Settings Submit */
        /*if (isset($_POST['payment_settings_sbmt'])) {
            $business_id = strip_tags(trim($_POST['business_id']));
            $testmode = $_POST['testmode'];

            update_option('business_id', $business_id);
            update_option('testmode', $testmode);

            wp_redirect(admin_url() . 'admin.php?page=andre-settings');
            exit;
        }*/

        /* Deal Settings Submit */
        if (isset($_POST['deal_settings_sbmt'])) {
            $global_deal_acceptance = strip_tags(trim($_POST['global_deal_acceptance']));

            update_option('_global_deal_acceptance', $global_deal_acceptance);

            wp_redirect(admin_url() . 'admin.php?page=andre-settings');
            exit;
        }

        /* Donation Settings Submit */
        if (isset($_POST['donation_settings_sbmt'])) {
            $paypal_donation_amount = strip_tags(trim($_POST['paypal_donation_amount']));
            update_option('_payapl_donation_amount', $paypal_donation_amount);
            wp_redirect(admin_url() . 'admin.php?page=andre-settings');
            exit;
        }
        ?>

        <!-- <h2><?php _e('Payment Settings', THEME_TEXTDOMAIN); ?></h2>
        <div class="wrap">
            <form name="payment_settings" method="post" action="">
                <table class="widefat">
                    <tr>
                        <td><strong><?php _e('Buisness ID:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><input type="text" name="business_id" value="<?php echo $get_business_id; ?>" placeholder="<?php _e('Buisness ID', THEME_TEXTDOMAIN); ?>" size="50"></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Test mode:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><input type="checkbox" name="testmode" value="1" size="50" <?php echo ($get_testmode == 1) ? 'checked' : ''; ?>></td>
                    </tr>
                    <tr>
                        <td colspan="2"><button class="button button-primary" type="submit" name="payment_settings_sbmt"><?php _e('Save', THEME_TEXTDOMAIN); ?></button></td>
                    </tr>
                </table>
            </form>
        </div> -->

        <h2><?php _e('Payment Settings', THEME_TEXTDOMAIN); ?></h2>
        <div class="wrap">
            <form name="payment_settings_frm" action="<?php echo admin_url() . 'admin.php?page=andre-settings'; ?>" method="post">
                <table class="widefat">
                    <tbody>
                        <tr>
                            <td><strong>PayPal API Username</strong></td>
                            <td><input type="text" name="paypal_api_username" size="100" value="<?php echo ($get_paypal_api_username) ? $get_paypal_api_username : ''; ?>" autocomplete="off" placeholder="PayPal API Username"/></td>
                        </tr>
                        <tr>
                            <td><strong>PayPal API Password</strong></td>
                            <td><input type="password" name="paypal_api_password"  size="100" value="<?php echo ($get_paypal_api_password) ? $get_paypal_api_password : ''; ?>"  autocomplete="off" placeholder="PayPal API Password"/></td>
                        </tr>
                        <tr>
                            <td><strong>PayPal API Signature</strong></td>
                            <td><input type="text" name="paypal_api_signature"  size="100" value="<?php echo ($get_paypal_api_signature) ? $get_paypal_api_signature : ''; ?>"  autocomplete="off" placeholder="PayPal API Signature"/></td>
                        </tr>
                        <tr>
                            <td><strong>Payment Mode</strong></td>
                            <td>
                                <label for="sandbox">
                                    <input type="radio" name="paypal_api_mode" id="sandbox" value="1" <?php echo checked($get_paypal_mode, 1); ?>/><strong>Sandbox</strong>
                                </label>
                                <label for="live">
                                    <input type="radio" name="paypal_api_mode" id="live" value="2" <?php echo checked($get_paypal_mode, 2); ?>/><strong>Live</strong>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button type="submit" name="payment_settings_sbmt" class="button button-primary">Save</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>


        <h2><?php _e('PayPal Donation Settings', THEME_TEXTDOMAIN); ?></h2>
        <div class="wrap">
            <form name="donation_settings" method="post" action="">
                <table class="widefat">
                    <tr>
                        <td><strong><?php _e('PayPal Donation Amount:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><?php _e('R$ ', THEME_TEXTDOMAIN); ?><input type="text" name="paypal_donation_amount" value="<?php echo $get_paypal_donation_amount; ?>" placeholder="<?php _e('E.g. 50.00', THEME_TEXTDOMAIN); ?>" size="50"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><button class="button button-primary" type="submit" name="donation_settings_sbmt"><?php _e('Save', THEME_TEXTDOMAIN); ?></button></td>
                    </tr>
                </table>
            </form>
        </div>

        <h2><?php _e('Deal Settings', THEME_TEXTDOMAIN); ?></h2>
        <div class="wrap">
            <form name="deal_settings" method="post" action="">
                <table class="widefat">
                    <tr>
                        <td><strong><?php _e('Global Deal Acceptance Count:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><input type="number" min="1" name="global_deal_acceptance" value="<?php echo $get_global_deal_acceptance; ?>" placeholder="<?php _e('E.g. 10', THEME_TEXTDOMAIN); ?>" size="50"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><button class="button button-primary" type="submit" name="deal_settings_sbmt"><?php _e('Save', THEME_TEXTDOMAIN); ?></button></td>
                    </tr>
                </table>
            </form>
        </div>

        <h2><?php _e('Social Settings', THEME_TEXTDOMAIN); ?></h2>
        <div class="wrap">
            <form name="social_settings" method="post" action="">
                <table class="widefat">
                    <tr>
                        <td><strong><?php _e('Facebook App ID:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><input type="text" name="fb_app_id" value="<?php echo $get_fb_app_id; ?>" placeholder="<?php _e('Facebook App ID', THEME_TEXTDOMAIN); ?>" size="50"></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Google API Key:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><input type="text" name="google_api_key" value="<?php echo $get_google_api_key; ?>" placeholder="Google API Key" size="50"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><button class="button button-primary" type="submit" name="social_settings_sbmt"><?php _e('Save', THEME_TEXTDOMAIN); ?></button></td>
                    </tr>
                </table>
            </form>
        </div>

        <h2><?php _e('Homepage Banner Settings', THEME_TEXTDOMAIN); ?></h2>
        <div class="wrap">
            <div class=""><img src="<?php echo $getHomepageBannerImage; ?>" alt="banner_image" width="1140" height="230"/></div>
            <form name="homebanner_settings" method="post" action="" enctype="multipart/form-data">
                <table class="widefat">
                    <tr>
                        <td><strong><?php _e('Upload Homepage Banner:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><input type="file" name="homepage_banner"><p class="description"><?php _e('File size: 1140px X 230px', THEME_TEXTDOMAIN); ?></p></td>
                    </tr>
                    <tr>
                        <td colspan="2"><label for="show_homepage_banner"><input id="show_homepage_banner" type="checkbox" name="show_homepage_banner" value="1" <?php echo ($get_show_banner_option == 1) ? 'checked' : ''; ?>><strong><?php _e('Show banner', THEME_TEXTDOMAIN); ?></strong></label><p class="description"><?php _e('If you check, this image will show at home page', THEME_TEXTDOMAIN); ?></p></td>
                    </tr>
                    <tr>
                        <td colspan="2"><button class="button button-primary" type="submit" name="homebanner_settings_sbmt"><?php _e('Save', THEME_TEXTDOMAIN); ?></button></td>
                    </tr>
                </table>
            </form>
        </div>

        <h2><?php _e('New Flag Settings For Announcements & Suppliers', THEME_TEXTDOMAIN); ?></h2>
        <div class="wrap">
            <form name="new_flag_settings" method="post" action="">
                <table class="widefat">
                    <tr>
                        <td><strong><?php _e('Number of days for announcements:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><input type="number" name="new_flag_day_for_announcements" value="<?php echo $get_new_flag_announements; ?>" placeholder="<?php _e('Number of days for announcements:', THEME_TEXTDOMAIN); ?>" size="50"></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Number of days for suppliers:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><input type="number" name="new_flag_day_for_suppliers" value="<?php echo $get_new_flag_suppliers; ?>" placeholder="<?php _e('Number of days for suppliers:', THEME_TEXTDOMAIN); ?>" size="50"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><button class="button button-primary" type="submit" name="new_flag_sbmt"><?php _e('Save', THEME_TEXTDOMAIN); ?></button></td>
                    </tr>
                </table>
            </form>
        </div>

        <?php
    }

}

if (!function_exists('ad_payment_lists_func')) {

    function ad_payment_lists_func() {
        
        $GeneralThemeObject = new GeneralTheme();
        $queryString = NULL;
        if (isset($_GET['unique_id']) && $_GET['unique_id'] != ''):
            $queryString .= " AND `unique_id`='" . $_GET['unique_id'] . "'";
        endif;
        if (isset($_GET['transaction_id']) && $_GET['transaction_id'] != ''):
            $queryString .= " AND `transaction_id`='" . $_GET['transaction_id'] . "'";
        endif;
        if (isset($_GET['payment_status']) && $_GET['payment_status'] != ''):
            $queryString .= " AND `payment_status`='" . $_GET['payment_status'] . "'";
        endif;
        if (isset($_GET['approval_status']) && $_GET['approval_status'] != ''):
            $queryString .= " AND `approval_status`='" . $_GET['approval_status'] . "'";
        endif;
        $membershipDetails = $GeneralThemeObject->getAdvPaymentData($queryString);
        ?>
        <h2><?php _e('Advertisement Payment Transaction History', THEME_TEXTDOMAIN); ?></h2>
        <div class="wrap">
            <?php
            if (is_array($membershipDetails) && count($membershipDetails) > 0) :
                $i = 0;
                $data_arr = [];
                foreach ($membershipDetails as $eachMembershipDetails) :
                    $userDetails = $GeneralThemeObject->user_details($eachMembershipDetails->user_id);
                    $data_arr[$i] = [
                        'unique_id' => $eachMembershipDetails->unique_id,
                        'name' => $userDetails->data['fname'] . ' ' . $userDetails->data['lname'],
                        'adv_name' => get_the_title($eachMembershipDetails->adv_id),
                        'transaction_id' => $eachMembershipDetails->transaction_id,
                        'total_price' => 'R$ ' . number_format($eachMembershipDetails->total_price, 2),
                        'payment_status' => ($eachMembershipDetails->payment_status == 1) ? 'Paid' : 'Unpaid',
                        'approval_status' => ($eachMembershipDetails->approval_status == 1) ? 'Approved' : 'Pending',
                        'payment_date' => ($eachMembershipDetails->payment_date) ? date('d M, Y', $eachMembershipDetails->payment_date) : 'N/A',
                    ];
                    $i++;
                endforeach;
            endif;

            $DonationTblObject = new Advertisement_Payment_List_Table();
            $DonationTblObject->prepare_items($data_arr);
            ?>
            <form name="adv_payment_list_tbl" id="adv_payment_list_tbl" method="get" action="<?php echo admin_url() . 'admin.php?page=ad-payment-list'; ?>">
                <input type="hidden" name="post_type" value="<?php echo $_REQUEST['post_type']; ?>">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
                <input type="text" name="unique_id" value="<?php echo $_GET['unique_id']; ?>" placeholder="<?php _e('Unique Payment ID', THEME_TEXTDOMAIN); ?>">
                <input type="text" name="transaction_id" value="<?php echo $_GET['transaction_id']; ?>" placeholder="<?php _e('Transaction ID', THEME_TEXTDOMAIN); ?>">
                <select name="payment_status">
                    <option value=""><?php _e('-Payment status-', THEME_TEXTDOMAIN); ?></option>
                    <option value="1" <?php echo ($_GET['payment_status'] == 1) ? 'selected' : ''; ?>><?php _e('Paid', THEME_TEXTDOMAIN); ?></option>
                    <option value="2" <?php echo ($_GET['payment_status'] == 2) ? 'selected' : ''; ?>><?php _e('Unpaid', THEME_TEXTDOMAIN); ?></option>
                </select>
                <select name="approval_status">
                    <option value=""><?php _e('-Approval status-', THEME_TEXTDOMAIN); ?></option>
                    <option value="1" <?php echo ($_GET['approval_status'] == 1) ? 'selected' : ''; ?>><?php _e('Approved', THEME_TEXTDOMAIN); ?></option>
                    <option value="2" <?php echo ($_GET['approval_status'] == 2) ? 'selected' : ''; ?>><?php _e('Pending', THEME_TEXTDOMAIN); ?></option>
                </select>
                <button class="button button-primary" name="">Search</button>
                <a href="<?php echo admin_url() . 'admin.php?page=ad-payment-list'; ?>" class="button button-primary"><?php _e('Clear Search', THEME_TEXTDOMAIN); ?></a>
            </form>
            <form>
                 <input type="hidden" name="post_type" value="<?php echo $_REQUEST['post_type']; ?>">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
                <?php
                $DonationTblObject->display();
                ?>
            </form>            
        </div>
        <?php
    }

}

if (!function_exists('ad_price_chart_func')) {

    function ad_price_chart_func() {
       
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
        ?>
        <h2><?php _e('Advertisement Price Chart', THEME_TEXTDOMAIN); ?></h2>
        <div class="wrap">
            <table class="widefat">
                <thead>
                <th colspan="2"><span style="font-size: 20px;"><strong><?php _e('Link', THEME_TEXTDOMAIN); ?></strong></span></th>
                </thead>
                <tbody>
                    <tr>
                        <td><strong><?php _e('Price For Advertisement Link:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><?php echo ($get_advert_link_price['link']) ? 'R$ ' . number_format($get_advert_link_price['link'], 2) : 'N/A'; ?></td>
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
                            <?php echo ($get_advert_position_price['top']) ? 'R$ ' . number_format($get_advert_position_price['top'], 2) : 'N/A'; ?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Price For Middle Position:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td>
                            <?php echo ($get_advert_position_price['middle']) ? 'R$ ' . number_format($get_advert_position_price['middle'], 2) : 'N/A'; ?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Price For Bottom Position:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td>
                            <?php echo ($get_advert_position_price['bottom']) ? 'R$ ' . number_format($get_advert_position_price['bottom'], 2) : 'N/A'; ?>
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
                        <tr>
                            <td><strong><?php _e('Price for all cities per one state:', THEME_TEXTDOMAIN); ?></strong></td>
                            <td><?php echo ($get_advert_city_price['all']) ? 'R$ ' . number_format($get_advert_city_price['all'], 2) : 'N/A'; ?></td>
                        </tr>
                        <?php
                        if (is_array($getStates) && count($getStates) > 0):
                            foreach ($getStates as $eachState):
                                $getStateCities = $GeneralThemeObject->getCities($eachState->term_id);
                                ?>
                                <tr>
                                    <td><strong><?php _e('Price For State ' . $eachState->name . ':', THEME_TEXTDOMAIN); ?></strong></td>
                                    <td>
                                        <?php
                                        if (is_array($getStateCities) && count($getStateCities) > 0):
                                            foreach ($getStateCities as $eachStateCity):
                                                ?>
                                                <div class="state-city-section" style="margin: 5px 0px 20px 0px;">
                                                    <span><strong><?php echo $eachStateCity->name; ?></strong></span>
                                                    <span style="float: right;"><?php echo ($get_advert_city_price[$eachStateCity->slug]) ? 'R$ ' . number_format($get_advert_city_price[$eachStateCity->slug], 2) : 'N/A'; ?>
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
                                    <td><strong><?php _e('Price For Category ' . $eachCategory->name . ':', THEME_TEXTDOMAIN); ?></strong><?php echo ($get_advert_category_price[$eachCategory->slug]) ? 'R$ ' . number_format($get_advert_category_price[$eachCategory->slug], 2) : 'N/A'; ?>
                                    <td>
                                        <?php
                                        if (is_array($getSubCategories) && count($getSubCategories) > 0):
                                            foreach ($getSubCategories as $eachSubCategory):
                                                ?>
                                                <div class="state-city-section" style="margin: 5px 0px 20px 0px;">
                                                    <span><strong><?php echo $eachSubCategory->name; ?></strong></span>
                                                    <?php echo ($get_advert_category_price[$eachSubCategory->slug]) ? 'R$ ' . number_format($get_advert_category_price[$eachSubCategory->slug], 2) : 'N/A'; ?>
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
                                            <?php echo ($get_advert_page_price[$eachTemplatePage->post_name]) ? 'R$ ' . number_format($get_advert_page_price[$eachTemplatePage->post_name], 2) : 'N/A'; ?>
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
                                <?php echo ($get_advert_page_price['category']) ? 'R$ ' . number_format($get_advert_page_price['category'], 2) : 'N/A'; ?>
                            </td>
                        </tr> 
                        <tr>
                            <td><strong><?php _e('Price For Product Page:', THEME_TEXTDOMAIN); ?></strong></td>
                            <td>
                                <?php echo ($get_advert_page_price['product']) ? 'R$ ' . number_format($get_advert_page_price['product'], 2) : 'N/A'; ?>
                            </td>
                        </tr> 
                        <tr>
                            <td><strong><?php _e('Price For Supplier Public Profile Page:', THEME_TEXTDOMAIN); ?></strong></td>
                            <td>
                                <?php echo ($get_advert_page_price['supplier_public_profile']) ? 'R$ ' . number_format($get_advert_page_price['supplier_public_profile'], 2) : 'N/A'; ?>
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
                                <?php echo ($get_advert_time_price['hourly']) ? 'R$ ' . number_format($get_advert_time_price['hourly'], 2) : 'N/A'; ?>
                            </td>
                        </tr> 
                        <!--?php
                        if (is_array($getHourlyTimeSlots) && count($getHourlyTimeSlots) > 0):
                            foreach ($getHourlyTimeSlots as $eachTimeSlotKey => $eachTimeSlotVal):
                                ?-->
                                <tr>
                                    <td><strong><!--?php _e('Price For ' . $eachTimeSlotVal . ' :', THEME_TEXTDOMAIN); ?></strong></td>
                                    <td>
                                        <!--?php echo ($get_advert_time_price[$eachTimeSlotKey]) ? 'R$ ' . number_format($get_advert_time_price[$eachTimeSlotKey], 2) : 'N/A'; ?-->
                                    </td>
                                </tr> 
                                <!--?php
                            endforeach;
                        endif;
                        ?-->
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }

}