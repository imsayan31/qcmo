<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!function_exists('deal_reviews_func')) {

    function deal_reviews_func() {
        
        $queryString = NULL;
        $ReviewRatingObject = new classReviewRating();
        if (isset($_GET['deal_id']) && $_GET['deal_id'] != ''):
            $queryString .= " AND `deal_id`='" . $_GET['deal_id'] . "'";
        endif;
        if (isset($_GET['user_name']) && $_GET['user_name'] != ''):
            $queryString .= " AND `user_id`=" . $_GET['user_name'] . "";
        endif;
        if (isset($_GET['supplier_name']) && $_GET['supplier_name'] != ''):
            $queryString .= " AND `supplier_id`=" . $_GET['supplier_name'] . "";
        endif;
        if (isset($_GET['review_status']) && $_GET['review_status'] != ''):
            $queryString .= " AND `rating_status`=" . $_GET['review_status'] . "";
        endif;
        $getAllReviewRatings = $ReviewRatingObject->getAdminAllReviewRatings($queryString);
        $getUsers = get_users(['role' => 'subscriber']);
        $getSupplier = get_users(['role' => 'supplier']);
        ?>
        <h2><?php _e('Supplier Review List', THEME_TEXTDOMAIN); ?></h2>
        <div class="wrap">
            <?php
            if (is_array($getAllReviewRatings) && count($getAllReviewRatings) > 0) :
                $i = 0;
                $data_arr = [];
                foreach ($getAllReviewRatings as $eachReviewRating) :
                    $userDetails = $ReviewRatingObject->user_details($eachReviewRating->user_id);
                    $supplierDetails = $ReviewRatingObject->user_details($eachReviewRating->supplier_id);

                    if ($eachReviewRating->rating_status == 1) {
                        $approveSelected = 'selected';
                        $rejectedSelected = '';
                    } else if ($eachReviewRating->rating_status == 2) {
                        $approveSelected = '';
                        $rejectedSelected = 'selected';
                    }

                    $selectBox = NULL;
                    $selectBox .= '<select name="change_user_review" class="change-user-review" data-deal="' . $eachReviewRating->deal_id . '" data-user="' . $eachReviewRating->user_id . '" data-supplier="' . $eachReviewRating->supplier_id . '">';
                    //$selectBox .= '<option value="">-Select Approval-</option>';
                    $selectBox .= '<option value="1" ' . $approveSelected . '>Approve</option>';
                    $selectBox .= '<option value="2" ' . $rejectedSelected . '>Reject</option>';
                    $selectBox .= '</select>';

                    $data_arr[$i] = [
                        'deal_id' => $eachReviewRating->deal_id,
                        'user_name' => $userDetails->data['fname'] . ' ' . $userDetails->data['lname'],
                        'suplier_name' => $supplierDetails->data['fname'] . ' ' . $supplierDetails->data['lname'],
                        'date' => date('d M, Y', $eachReviewRating->date),
                        'action' => $selectBox,
                        'details' => '<a href="' . admin_url() . 'admin.php?page=deal-review-details&deal_id=' . $eachReviewRating->deal_id . '&user_id=' . $eachReviewRating->user_id . '&supplier_id=' . $eachReviewRating->supplier_id . '" class="button button-primary">Details</a>',
                    ];
                    $i++;
                endforeach;
            endif;

            $DonationTblObject = new Deal_Review_List_Table();
            $DonationTblObject->prepare_items($data_arr);
            ?>
            <form name="deal_review_list_tbl" id="deal_review_list_tbl" method="get" action="<?php echo admin_url() . 'admin.php?page=deal-reviews'; ?>">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
                <input type="text" name="deal_id" value="<?php echo $_GET['deal_id']; ?>" placeholder="<?php _e('Deal ID', THEME_TEXTDOMAIN); ?>">
                <select name="user_name">
                    <option value=""><?php _e('-Select User-', THEME_TEXTDOMAIN); ?></option>
                    <?php
                    if (is_array($getUsers) && count($getUsers) > 0):
                        foreach ($getUsers as $eachUser):
                            ?>
                            <option value="<?php echo $eachUser->ID; ?>" <?php echo ($_GET['user_name'] == $eachUser->ID) ? 'selected' : ''; ?>><?php echo $eachUser->first_name . ' ' . $eachUser->last_name; ?></option>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </select>
                <select name="supplier_name">
                    <option value=""><?php _e('-Select Supplier-', THEME_TEXTDOMAIN); ?></option>
                    <?php
                    if (is_array($getSupplier) && count($getSupplier) > 0):
                        foreach ($getSupplier as $eachUser):
                            ?>
                            <option value="<?php echo $eachUser->ID; ?>" <?php echo ($_GET['supplier_name'] == $eachUser->ID) ? 'selected' : ''; ?>><?php echo $eachUser->first_name . ' ' . $eachUser->last_name; ?></option>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </select>
                <select name="review_status">
                    <option value=""><?php _e('Select Status', THEME_TEXTDOMAIN); ?></option>
                    <option value="1" <?php echo (isset($_GET['review_status']) && $_GET['review_status'] == 1) ? 'selected' : ''; ?>><?php _e('Approved', THEME_TEXTDOMAIN); ?></option>
                    <option value="2" <?php echo (isset($_GET['review_status']) && $_GET['review_status'] == 2) ? 'selected' : ''; ?>><?php _e('Rejected', THEME_TEXTDOMAIN); ?></option>
                </select>
                <button class="button button-primary" name=""><?php _e('Search', THEME_TEXTDOMAIN); ?></button>
                <a href="<?php echo admin_url() . 'admin.php?page=deal-reviews'; ?>" class="button button-primary"><?php _e('Clear Search', THEME_TEXTDOMAIN); ?></a>
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

if (!function_exists('deal_review_details_func')) {

    function deal_review_details_func() {
        
        $queryString = NULL;
        $ReviewRatingObject = new classReviewRating();
        $FinalizeObject = new classFinalize();
        if (isset($_GET['deal_id']) && $_GET['deal_id'] != ''):
            $queryString .= " AND `deal_id`='" . $_GET['deal_id'] . "'";
        endif;
        if (isset($_GET['user_id']) && $_GET['user_id'] != ''):
            $queryString .= " AND `user_id`=" . $_GET['user_id'] . "";
        endif;
        if (isset($_GET['supplier_id']) && $_GET['supplier_id'] != ''):
            $queryString .= " AND `supplier_id`=" . $_GET['supplier_id'] . "";
        endif;
        $getAllReviewRatings = $ReviewRatingObject->getAdminAllReviewRatings($queryString);
        $userDetails = $ReviewRatingObject->user_details($_GET['user_id']);
        $supplierDetails = $ReviewRatingObject->user_details($_GET['supplier_id']);
        $user_pro_pic = wp_get_attachment_image_src($userDetails->data['pro_pic'], 'full');
        $supplier_pro_pic = wp_get_attachment_image_src($supplierDetails->data['pro_pic'], 'full');
        $getUserCompletedDeals = $FinalizeObject->getUserCompletedDeals($_GET['user_id']);
        // echo "<pre>";
        // print_r($userDetails);
        // echo "</pre>";
        
        ?>
        <style>
            #deals_deatils .button-primary{text-shadow:none;}
            .wp-core-ui .button-primary{text-shadow:none !important;}
        </style>
        <h2><?php _e('Supplier Score Details', THEME_TEXTDOMAIN); ?></h2>
        
               
        <div class="wrap" id="deals_deatils">
            <!-- User Details --> 
            <table class="widefat">
                <thead>
                <th colspan="6"><strong><?php _e('User Details', THEME_TEXTDOMAIN); ?></strong></th>
                </thead>
                <tbody>
                    <tr>
                        <td><strong><?php _e('User avatar:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><img src="<?php echo ($userDetails->data['pro_pic_exists'] == TRUE) ? $user_pro_pic[0] : 'https://via.placeholder.com/100x100'; ?>" width="100" height="100" /></td>
                        <td><strong><?php _e('User name:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><?php echo $userDetails->data['fname'] . ' ' . $userDetails->data['lname']; ?></td>
                        <td><strong><?php _e('User email:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><?php echo $userDetails->data['email']; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Completed Deals:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><?php echo count($getUserCompletedDeals); ?></td>
                        <td><strong><?php _e('Member Since:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><?php echo date('d M, Y', strtotime($userDetails->data['member_since'])); ?></td>
                        <td><strong><?php echo ($userDetails->data['supplier_type'] == 1) ? 'CPF:' : 'CNPJ:'; ?></strong></td>
                        <td><?php echo ($userDetails->data['supplier_type'] == 1) ? $userDetails->data['cpf'] : $userDetails->data['cnpj']; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Full Address:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td colspan="5"><?php echo ($userDetails->data['address']) ? $userDetails->data['address'] : '-'; ?></td>
                    </tr>
                </tbody>
            </table>
            <!-- End of User Details --> 
            <br>
            <!-- Supplier Details --> 
            <table class="widefat">
                <thead>
                <th colspan="6"><strong><?php _e('Supplier Details', THEME_TEXTDOMAIN); ?></strong></th>
                </thead>
                <tbody>
                    <tr>
                        <td><strong><?php _e('Supplier avatar:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><img src="<?php echo ($supplierDetails->data['pro_pic_exists'] == TRUE) ? $supplier_pro_pic[0] : 'https://via.placeholder.com/100x100'; ?>" width="100" height="100" /></td>
                        <td><strong><?php _e('Supplier name:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><?php echo $supplierDetails->data['fname'] . ' ' . $supplierDetails->data['lname']; ?></td>
                        <td><strong><?php _e('Supplier email:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><?php echo $supplierDetails->data['email']; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Member Since:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td colspan="2"><?php echo date('d M, Y', strtotime($supplierDetails->data['member_since'])); ?></td>
                        <td><strong><?php echo ($supplierDetails->data['supplier_type'] == 1) ? 'CPF:' : 'CNPJ:'; ?></strong></td>
                        <td colspan="2"><?php echo ($supplierDetails->data['supplier_type'] == 1) ? $supplierDetails->data['cpf'] : $supplierDetails->data['cnpj']; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Full Address:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td colspan="5"><?php echo ($supplierDetails->data['user_address']) ? $supplierDetails->data['user_address'] : '-'; ?></td>
                    </tr>
                </tbody>
            </table>
            <!-- End of Supplier Details --> 
            <br>
            <!-- Review Details --> 
            <table class="widefat">
                <thead>
                <th colspan="6"><strong><?php _e('Rating Details', THEME_TEXTDOMAIN); ?></strong></th>
                </thead>
                <tbody style="background-color: #dbecf1;">
                    <?php
                    if (is_array($getAllReviewRatings) && count($getAllReviewRatings) > 0):
                        $getPriceRating = $ReviewRatingObject->getRatingHTML($getAllReviewRatings[0]->price_rate);
                        $getAttendanceRating = $ReviewRatingObject->getRatingHTML($getAllReviewRatings[0]->attendence_rate);
                        $getDeliveryRating = $ReviewRatingObject->getRatingHTML($getAllReviewRatings[0]->delivery_rate);
                        ?>
                        <tr>
                            <td><strong><?php _e('Price:', THEME_TEXTDOMAIN); ?></strong></td>
                            <td><?php echo $getPriceRating ?></td>
                            <td><strong><?php _e('Attendance:', THEME_TEXTDOMAIN); ?></strong></td>
                            <td><?php echo $getAttendanceRating ?></td>
                            <td><strong><?php _e('Delivery:', THEME_TEXTDOMAIN); ?></strong></td>
                            <td><?php echo $getDeliveryRating ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php _e('User comments:', THEME_TEXTDOMAIN); ?></strong></td>
                            <td colspan="5"><?php echo ($getAllReviewRatings[0]->user_comments) ? $getAllReviewRatings[0]->user_comments : 'No comments.'; ?></td>
                        </tr>
                        <?php
                    endif;
                    ?>
                </tbody>
            </table>
            <!-- End of Review Details --> 
        </div>
        <?php
    }

}


if (!function_exists('sub_admin_deal_reviews_func')) {

    function sub_admin_deal_reviews_func() {
        $queryString = NULL;
        $ReviewRatingObject = new classReviewRating();
        if (isset($_GET['deal_id']) && $_GET['deal_id'] != ''):
            $queryString .= " AND `deal_id`='" . $_GET['deal_id'] . "'";
        endif;
        if (isset($_GET['user_name']) && $_GET['user_name'] != ''):
            $queryString .= " AND `user_id`=" . $_GET['user_name'] . "";
        endif;
        if (isset($_GET['supplier_name']) && $_GET['supplier_name'] != ''):
            $queryString .= " AND `supplier_id`=" . $_GET['supplier_name'] . "";
        endif;
        if (isset($_GET['review_status']) && $_GET['review_status'] != ''):
            $queryString .= " AND `rating_status`=" . $_GET['review_status'] . "";
        endif;
        $getAllReviewRatings = $ReviewRatingObject->getAdminAllReviewRatings($queryString);
        $getUsers = get_users(['role' => 'subscriber']);
        $getSupplier = get_users(['role' => 'supplier']);

        $currentUserID = get_current_user_id();
        $currentUserDetails = $ReviewRatingObject->user_details($currentUserID);
        ?>
        <h2><?php _e('Supplier Review List', THEME_TEXTDOMAIN); ?></h2>
        <div class="wrap">
            <?php
            if (is_array($getAllReviewRatings) && count($getAllReviewRatings) > 0) :
                $i = 0;
                $data_arr = [];
                foreach ($getAllReviewRatings as $eachReviewRating) :
                    $userDetails = $ReviewRatingObject->user_details($eachReviewRating->user_id);
                    $supplierDetails = $ReviewRatingObject->user_details($eachReviewRating->supplier_id);

                    if ($eachReviewRating->rating_status == 1) {
                        $approveSelected = 'selected';
                        $rejectedSelected = '';
                    } else if ($eachReviewRating->rating_status == 2) {
                        $approveSelected = '';
                        $rejectedSelected = 'selected';
                    }

                    $selectBox = NULL;
                    $selectBox .= '<select name="change_user_review" class="change-user-review" data-deal="' . $eachReviewRating->deal_id . '" data-user="' . $eachReviewRating->user_id . '" data-supplier="' . $eachReviewRating->supplier_id . '">';
                    //$selectBox .= '<option value="">-Select Approval-</option>';
                    $selectBox .= '<option value="1" ' . $approveSelected . '>Approve</option>';
                    $selectBox .= '<option value="2" ' . $rejectedSelected . '>Reject</option>';
                    $selectBox .= '</select>';

                    if (is_array($currentUserDetails->data['city']) && count($currentUserDetails->data['city']) > 0 && in_array($userDetails->data['city'], $currentUserDetails->data['city'])) {
                            $data_arr[$i] = [
                            'deal_id' => $eachReviewRating->deal_id,
                            'user_name' => $userDetails->data['fname'] . ' ' . $userDetails->data['lname'],
                            'suplier_name' => $supplierDetails->data['fname'] . ' ' . $supplierDetails->data['lname'],
                            'date' => date('d M, Y', $eachReviewRating->date),
                            'action' => $selectBox,
                            'details' => '<a href="' . admin_url() . 'admin.php?page=sub-admin-deal-review-details&deal_id=' . $eachReviewRating->deal_id . '&user_id=' . $eachReviewRating->user_id . '&supplier_id=' . $eachReviewRating->supplier_id . '" class="button button-primary">Details</a>',
                        ];
                        $i++;
                    }
                endforeach;
            endif;

            $DonationTblObject = new Deal_Review_List_Table();
            $DonationTblObject->prepare_items($data_arr);
            ?>
            <form name="deal_review_list_tbl" id="deal_review_list_tbl" method="get" action="<?php echo admin_url() . 'admin.php?page=sub-admin-deal-reviews'; ?>">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
                <input type="text" name="deal_id" value="<?php echo $_GET['deal_id']; ?>" placeholder="<?php _e('Deal ID', THEME_TEXTDOMAIN); ?>">
                <select name="user_name">
                    <option value=""><?php _e('-Select User-', THEME_TEXTDOMAIN); ?></option>
                    <?php
                    if (is_array($getUsers) && count($getUsers) > 0):
                        foreach ($getUsers as $eachUser):
                            ?>
                            <option value="<?php echo $eachUser->ID; ?>" <?php echo ($_GET['user_name'] == $eachUser->ID) ? 'selected' : ''; ?>><?php echo $eachUser->first_name . ' ' . $eachUser->last_name; ?></option>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </select>
                <select name="supplier_name">
                    <option value=""><?php _e('-Select Supplier-', THEME_TEXTDOMAIN); ?></option>
                    <?php
                    if (is_array($getSupplier) && count($getSupplier) > 0):
                        foreach ($getSupplier as $eachUser):
                            ?>
                            <option value="<?php echo $eachUser->ID; ?>" <?php echo ($_GET['supplier_name'] == $eachUser->ID) ? 'selected' : ''; ?>><?php echo $eachUser->first_name . ' ' . $eachUser->last_name; ?></option>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </select>
                <select name="review_status">
                    <option value=""><?php _e('Select Status', THEME_TEXTDOMAIN); ?></option>
                    <option value="1" <?php echo (isset($_GET['review_status']) && $_GET['review_status'] == 1) ? 'selected' : ''; ?>><?php _e('Approved', THEME_TEXTDOMAIN); ?></option>
                    <option value="2" <?php echo (isset($_GET['review_status']) && $_GET['review_status'] == 2) ? 'selected' : ''; ?>><?php _e('Rejected', THEME_TEXTDOMAIN); ?></option>
                </select>
                <button class="button button-primary" name=""><?php _e('Search', THEME_TEXTDOMAIN); ?></button>
                <a href="<?php echo admin_url() . 'admin.php?page=sub-admin-deal-reviews'; ?>" class="button button-primary"><?php _e('Clear Search', THEME_TEXTDOMAIN); ?></a>
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


if (!function_exists('sub_admin_deal_review_details_func')) {

    function sub_admin_deal_review_details_func() {
        $queryString = NULL;
        $ReviewRatingObject = new classReviewRating();
        $FinalizeObject = new classFinalize();
        if (isset($_GET['deal_id']) && $_GET['deal_id'] != ''):
            $queryString .= " AND `deal_id`='" . $_GET['deal_id'] . "'";
        endif;
        if (isset($_GET['user_id']) && $_GET['user_id'] != ''):
            $queryString .= " AND `user_id`=" . $_GET['user_id'] . "";
        endif;
        if (isset($_GET['supplier_id']) && $_GET['supplier_id'] != ''):
            $queryString .= " AND `supplier_id`=" . $_GET['supplier_id'] . "";
        endif;
        $getAllReviewRatings = $ReviewRatingObject->getAdminAllReviewRatings($queryString);
        $userDetails = $ReviewRatingObject->user_details($_GET['user_id']);
        $supplierDetails = $ReviewRatingObject->user_details($_GET['supplier_id']);
        $user_pro_pic = wp_get_attachment_image_src($userDetails->data['pro_pic'], 'full');
        $supplier_pro_pic = wp_get_attachment_image_src($supplierDetails->data['pro_pic'], 'full');
        $getUserCompletedDeals = $FinalizeObject->getUserCompletedDeals($_GET['user_id']);
        
        ?>
        <h2><?php _e('Supplier Score Details', THEME_TEXTDOMAIN); ?></h2>
        <a href="<?php echo admin_url() . 'admin.php?page=sub-admin-deal-reviews'; ?>" class="button button-primary"><?php _e('< Back to Deal Review Listing', THEME_TEXTDOMAIN); ?></a>
        <div class="wrap" id="supplier_reviews">
            <!-- User Details --> 
            <table class="widefat">
                <thead>
                <th colspan="6"><strong><?php _e('User Details', THEME_TEXTDOMAIN); ?></strong></th>
                </thead>
                <tbody>
                    <tr>
                        <td><strong><?php _e('User avatar:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><img src="<?php echo ($userDetails->data['pro_pic_exists'] == TRUE) ? $user_pro_pic[0] : 'https://via.placeholder.com/100x100'; ?>" width="100" height="100" /></td>
                        <td><strong><?php _e('User name:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><?php echo $userDetails->data['fname'] . ' ' . $userDetails->data['lname']; ?></td>
                        <td><strong><?php _e('User email:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><?php echo $userDetails->data['email']; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Completed Deals:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><?php echo count($getUserCompletedDeals); ?></td>
                        <td><strong><?php _e('Member Since:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><?php echo date('d M, Y', strtotime($userDetails->data['member_since'])); ?></td>
                        <td><strong><?php echo ($userDetails->data['supplier_type'] == 1) ? 'CPF:' : 'CNPJ:'; ?></strong></td>
                        <td><?php echo ($userDetails->data['supplier_type'] == 1) ? $userDetails->data['cpf'] : $userDetails->data['cnpj']; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Full Address:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td colspan="5"><?php echo ($userDetails->data['user_address']) ? $userDetails->data['user_address'] : '-'; ?></td>
                    </tr>
                </tbody>
            </table>
            <!-- End of User Details --> 
            <br>
            <!-- Supplier Details --> 
            <table class="widefat">
                <thead>
                <th colspan="6"><strong><?php _e('Supplier Details', THEME_TEXTDOMAIN); ?></strong></th>
                </thead>
                <tbody>
                    <tr>
                        <td><strong><?php _e('Supplier avatar:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><img src="<?php echo ($supplierDetails->data['pro_pic_exists'] == TRUE) ? $supplier_pro_pic[0] : 'https://via.placeholder.com/100x100'; ?>" width="100" height="100" /></td>
                        <td><strong><?php _e('Supplier name:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><?php echo $supplierDetails->data['fname'] . ' ' . $supplierDetails->data['lname']; ?></td>
                        <td><strong><?php _e('Supplier email:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><?php echo $supplierDetails->data['email']; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Member Since:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td colspan="2"><?php echo date('d M, Y', strtotime($supplierDetails->data['member_since'])); ?></td>
                        <td><strong><?php echo ($supplierDetails->data['supplier_type'] == 1) ? 'CPF:' : 'CNPJ:'; ?></strong></td>
                        <td colspan="2"><?php echo ($supplierDetails->data['supplier_type'] == 1) ? $supplierDetails->data['cpf'] : $supplierDetails->data['cnpj']; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Full Address:', THEME_TEXTDOMAIN); ?></strong></td>
                        <td colspan="5"><?php echo ($supplierDetails->data['address']) ? $supplierDetails->data['address'] : '-'; ?></td>
                    </tr>
                </tbody>
            </table>
            <!-- End of Supplier Details --> 
            <br>
            <!-- Review Details --> 
            <table class="widefat">
                <thead>
                <th colspan="6"><strong><?php _e('Rating Details', THEME_TEXTDOMAIN); ?></strong></th>
                </thead>
                <tbody>
                    <?php
                    if (is_array($getAllReviewRatings) && count($getAllReviewRatings) > 0):
                        $getPriceRating = $ReviewRatingObject->getRatingHTML($getAllReviewRatings[0]->price_rate);
                        $getAttendanceRating = $ReviewRatingObject->getRatingHTML($getAllReviewRatings[0]->attendence_rate);
                        $getDeliveryRating = $ReviewRatingObject->getRatingHTML($getAllReviewRatings[0]->delivery_rate);
                        ?>
                        <tr>
                            <td><strong><?php _e('Price:', THEME_TEXTDOMAIN); ?></strong></td>
                            <td><?php echo $getPriceRating ?></td>
                            <td><strong><?php _e('Attendance:', THEME_TEXTDOMAIN); ?></strong></td>
                            <td><?php echo $getAttendanceRating ?></td>
                            <td><strong><?php _e('Delivery:', THEME_TEXTDOMAIN); ?></strong></td>
                            <td><?php echo $getDeliveryRating ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php _e('User comments:', THEME_TEXTDOMAIN); ?></strong></td>
                            <td colspan="5"><?php echo ($getAllReviewRatings[0]->user_comments) ? $getAllReviewRatings[0]->user_comments : 'No comments.'; ?></td>
                        </tr>
                        <?php
                    endif;
                    ?>
                </tbody>
            </table>
            <!-- End of Review Details --> 
        </div>
        <?php
    }

}