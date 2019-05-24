<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!function_exists('announcement_transactions_func')) {

    function announcement_transactions_func() {
        $GeneralThemeObject = new GeneralTheme();
        $AnnouncementObject = new classAnnouncement();
        $queryString = NULL;
        if (isset($_GET['unique_code']) && $_GET['unique_code'] != ''):
            $queryString .= " AND `unique_announcement_code`='" . $_GET['unique_code'] . "'";
        endif;
        if (isset($_GET['payment_status']) && $_GET['payment_status'] != ''):
            $queryString .= " AND `payment_status`=" . $_GET['payment_status'] . "";
        endif;
        $getAnnouncementPaymentData = $AnnouncementObject->getAnnouncementPaymentData($queryString);
        ?>
        <h2><?php _e('Announcement Transactions', THEME_TEXTDOMAIN); ?></h2>
        <div class="wrap">
            <?php
            if (is_array($getAnnouncementPaymentData) && count($getAnnouncementPaymentData) > 0) :
                $i = 0;
                $data_arr = [];
                foreach ($getAnnouncementPaymentData as $eachAnnouncementData) :
                    $userDetails = $GeneralThemeObject->user_details($eachAnnouncementData->user_id);

                    $data_arr[$i] = [
                        'unique_announcement_code' => $eachAnnouncementData->unique_announcement_code,
                        'user_name' => $userDetails->data['fname'] . ' ' . $userDetails->data['lname'],
                        'announcement_id' => get_the_title($eachAnnouncementData->announcement_id),
                        'total_price' => 'R$ ' . number_format($eachAnnouncementData->total_price, 2),
                        'transaction_id' => $eachAnnouncementData->transaction_id,
                        'payment_status' => ($eachAnnouncementData->payment_status == 1) ? 'Paid' : 'Unpaid',
                        'payment_date' => ($eachAnnouncementData->payment_date) ? date('d M, Y', $eachAnnouncementData->payment_date) : ' - ',
                        'plan_type' => ucfirst($eachAnnouncementData->plan_type)
                    ];
                    $i++;
                endforeach;
            endif;

            $DonationTblObject = new Announcement_Transaction_List_Table();
            $DonationTblObject->prepare_items($data_arr);
            ?>
            <form name="" action="" method="get">
                <input type="hidden" name="post_type" value="<?php echo $_REQUEST['post_type']; ?>">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
                <div>
                    <input type="text" name="unique_code" value="<?php echo (isset($_GET['unique_code']) && $_GET['unique_code'] != '') ? $_GET['unique_code'] : ''; ?>" placeholder="<?php _e('Unique Code', THEME_TEXTDOMAIN); ?>" autocomplete="off"/>
                    <select name="payment_status">
                        <option value=""><?php _e('-Payment Status-', THEME_TEXTDOMAIN); ?></option>
                        <option value="1" <?php selected('1', $_GET['payment_status']); ?>><?php _e('Paid', THEME_TEXTDOMAIN); ?></option>
                        <option value="2" <?php selected('2', $_GET['payment_status']); ?>><?php _e('Unpaid', THEME_TEXTDOMAIN); ?></option>
                    </select>
                    <button type="submit" name="" class="button button-primary"><?php _e('Search', THEME_TEXTDOMAIN); ?></button>
                    <a href="<?php echo admin_url() . 'edit.php?post_type=andr_announcement&page=announcement-transactions'; ?>" class="button button-primary"><?php _e('Clear Search', THEME_TEXTDOMAIN); ?></a>
                </div>
            </form>
            <form>
                <input type="hidden" name="post_type" value="<?php echo $_REQUEST['post_type']; ?>">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
                <?php $DonationTblObject->display(); ?>
            </form> 
        </div>
        <?php
    }

}