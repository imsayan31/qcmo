<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$GeneralThemeObject = new GeneralTheme();
$AnnouncementObject = new classAnnouncement();
$userDetails = $GeneralThemeObject->user_details();
$currDate = strtotime(date('Y-m-d'));
$userAnnouncements = $AnnouncementObject->getUserAnnouncements($userDetails->data['user_id']);
$get_announcement_price = get_option('_announcement_price');
$getAnnouncementPaymentQueryString = " AND `user_id`=" . $userDetails->data['user_id'] . "";
$getUserAnnouncementPaymentData = $AnnouncementObject->getAnnouncementPaymentData($getAnnouncementPaymentQueryString);
?>
<div class="right mobile-view">
    <div class="announcement-btn-sec">
        <a href="<?php echo ANNOUNCEMENT_MANAGEMENT_PAGE; ?>"><?php _e('<i class="fa fa-plus"></i> Add Announcement', THEME_TEXTDOMAIN); ?></a>
    </div>

    <div class="section-heading" style="margin-top: 10px; margin-bottom: 10px;">
        <h2><?php _e('Announcement Plan', THEME_TEXTDOMAIN); ?></h2>
    </div>

    <div class="membership-plan-list">
        <div class="row">
            <div class="col-sm-4">
                <div class="plan-list announcement-plan-list">
                    <div class="plane-badge"><img src="<?php echo ASSET_URL . '/images/gold-badge.png'; ?>"/></div>
                    <div class="plan-title"><?php _e('Gold', THEME_TEXTDOMAIN); ?></div>
                    <div class="plan-price-list"><?php _e('1 Week Cost: ', THEME_TEXTDOMAIN); ?><span><?php echo ($get_announcement_price['gold']['7'] && $get_announcement_price['gold']['7'] > 0) ? 'R$ ' . number_format($get_announcement_price['gold']['7'], 2) . '/day' : 'R$ 0.00/day'; ?></span></div>
                    <div class="plan-price-list"><?php _e('2 Weeks Cost: ', THEME_TEXTDOMAIN); ?><span><?php echo ($get_announcement_price['gold']['14'] && $get_announcement_price['gold']['14'] > 0) ? 'R$ ' . number_format($get_announcement_price['gold']['14'], 2) . '/day' : 'R$ 0.00/day'; ?></span></div>
                    <div class="plan-price-list"><?php _e('1 Month Cost: ', THEME_TEXTDOMAIN); ?><span><?php echo ($get_announcement_price['gold']['30'] && $get_announcement_price['gold']['30'] > 0) ? 'R$ ' . number_format($get_announcement_price['gold']['30'], 2) . '/day' : 'R$ 0.00/day'; ?></span></div>
                    <div class="plan-price-list"><?php _e('Maximum no of announcement: ', THEME_TEXTDOMAIN); ?><span><?php echo ($get_announcement_price['gold']['no_of_post']) ? $get_announcement_price['gold']['no_of_post']  : 'N/A'; ?></span></div>
                    <div class="plan-price-list"><?php _e(''. $get_announcement_price['gold']['no_of_appearance'] .' times occurance per day', THEME_TEXTDOMAIN); ?></div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="plan-list announcement-plan-list">
                    <div class="plane-badge"><img src="<?php echo ASSET_URL . '/images/silver-badge.png'; ?>"/></div>
                    <div class="plan-title"><?php _e('Silver', THEME_TEXTDOMAIN); ?></div>
                    <div class="plan-price-list"><?php _e('1 Week Cost: ', THEME_TEXTDOMAIN); ?><span><?php echo ($get_announcement_price['silver']['7'] && $get_announcement_price['silver']['7'] > 0) ? 'R$ ' . number_format($get_announcement_price['silver']['7'], 2) . '/day' : 'R$ 0.00/day'; ?></span></div>
                    <div class="plan-price-list"><?php _e('2 Weeks Cost: ', THEME_TEXTDOMAIN); ?><span><?php echo ($get_announcement_price['silver']['14'] && $get_announcement_price['silver']['14'] > 0) ? 'R$ ' . number_format($get_announcement_price['silver']['14'], 2) . '/day' : 'R$ 0.00/day'; ?></span></div>
                    <div class="plan-price-list"><?php _e('1 Month Cost: ', THEME_TEXTDOMAIN); ?><span><?php echo ($get_announcement_price['silver']['30'] && $get_announcement_price['silver']['30'] > 0) ? 'R$ ' . number_format($get_announcement_price['silver']['30'], 2) . '/day' : 'R$ 0.00/day'; ?></span></div>
                    <div class="plan-price-list"><?php _e('Maximum no of announcement: ', THEME_TEXTDOMAIN); ?><span><?php echo ($get_announcement_price['silver']['no_of_post']) ? $get_announcement_price['silver']['no_of_post']  : 'N/A'; ?></span></div>
                    <div class="plan-price-list"><?php _e(''. $get_announcement_price['silver']['no_of_appearance'] .' times occurance per day', THEME_TEXTDOMAIN); ?></div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="plan-list announcement-plan-list">
                    <div class="plane-badge"><img src="<?php echo ASSET_URL . '/images/bronze-badge.png'; ?>"/></div>
                    <div class="plan-title"><?php _e('Bronze', THEME_TEXTDOMAIN); ?></div>
                    <div class="plan-price-list"><span><?php _e('Free', THEME_TEXTDOMAIN); ?></span></div>
                    <div class="plan-price-list"><?php _e('Maximum no of announcement: ', THEME_TEXTDOMAIN); ?><span><?php echo ($get_announcement_price['bronze']['no_of_post']) ? $get_announcement_price['bronze']['no_of_post']  : 'N/A'; ?></span></div>
                    <div class="plan-price-list"><?php _e(''. $get_announcement_price['bronze']['no_of_appearance'] .' times occurance per day', THEME_TEXTDOMAIN); ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>

    <div class="section-heading" style="margin-top: 10px; margin-bottom: 10px;">
        <h2><?php _e('Announcement List', THEME_TEXTDOMAIN); ?></h2>
    </div>

    <div class="">
        <?php
        if (is_array($userAnnouncements) && count($userAnnouncements) > 0):
            ?>

            <?php foreach ($userAnnouncements as $eachUserAnnouncement): ?>
                <?php
                $announcement_details = $AnnouncementObject->announcement_details($eachUserAnnouncement->ID);
                $queryAnnouncementString = ' AND announcement_id=' . $eachUserAnnouncement->ID . ' AND payment_date !=0';
                        $getAnnouncementPaymentData = $AnnouncementObject->getAnnouncementPaymentData($queryAnnouncementString);
                        $payment_required = false;
                        if ($announcement_details->data['announcement_plan'] != 'bronze' && count($getAnnouncementPaymentData) < 1 && $announcement_details->data['admin_approval'] == 1) {
                            $_status_msg = __('Waiting for payment', THEME_TEXTDOMAIN);
                            $payment_link = MY_ANNOUNCEMENTS_PAGE . '?dopayment=true&announcement=' . base64_encode($eachUserAnnouncement->ID);
                            $payment_required = TRUE;
                        } else {
                            $_status_msg = __('Waiting for admin approval', THEME_TEXTDOMAIN);
                        }
                ?>
                <div class="mobile-deals" style="font-size:12px;">
                    <div class="cart-details">
                        <div class="row">
                            <div class="col-sm-6 col-xs-6"><?php _e('Anouncement Name', THEME_TEXTDOMAIN); ?></div>
                            <div class="col-sm-6 col-xs-6"><?php echo $announcement_details->data['title']; ?></div>
                        </div>
                    </div>
                    <div class="cart-details">
                        <div class="row">
                            <div class="col-sm-6 col-xs-6"><?php _e('Status', THEME_TEXTDOMAIN); ?></div>
                            <div class="col-sm-6 col-xs-6"><?php echo ($announcement_details->data['status']) ? $announcement_details->data['status'] : 'Inactive'; ?></div>
                        </div>
                    </div>
                    <div class="cart-details">
                        <div class="row">
                            <div class="col-sm-6 col-xs-6"><?php _e('Start Date', THEME_TEXTDOMAIN); ?></div>
                            <div class="col-sm-6 col-xs-6"><?php echo ($announcement_details->data['admin_approval'] == 1 && !$payment_required) ? $announcement_details->data['start_date'] : $_status_msg; ?></div>
                        </div>
                    </div>
                    <div class="cart-details">
                        <div class="row">
                            <div class="col-sm-6 col-xs-6"><?php _e('End Date', THEME_TEXTDOMAIN); ?></div>
                            <div class="col-sm-6 col-xs-6"><?php echo ($announcement_details->data['admin_approval'] == 1 && !$payment_required) ? date('d M, Y', strtotime($announcement_details->data['end_date'])) : $_status_msg; ?></div>
                        </div>
                    </div>
                    <div class="cart-details">
                        <div class="row">
                            <div class="col-sm-6 col-xs-6"><?php _e('Selected Plan', THEME_TEXTDOMAIN); ?></div>
                            <div class="col-sm-6 col-xs-6"><?php echo ucfirst($announcement_details->data['announcement_plan']); ?></div>
                        </div>
                    </div>
                    <div class="cart-details">
                        <div class="row">
                            <div class="col-sm-6 col-xs-6"><?php _e('Action', THEME_TEXTDOMAIN); ?></div>
                            <div class="col-sm-6 col-xs-6">
                                <?php if ($announcement_details->data['admin_approval'] == 1): ?>

                                    <a href="<?php echo ANNOUNCEMENT_MANAGEMENT_PAGE . '?announcement_id=' . base64_encode($eachUserAnnouncement->ID); ?>" title="<?php _e('Edit', THEME_TEXTDOMAIN); ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> | 
                                    <a href="javascript:void(0);" class="announcement-delete" data-announcement="<?php echo base64_encode($eachUserAnnouncement->ID); ?>" title="<?php _e('Delete', THEME_TEXTDOMAIN); ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                    <?php if ($announcement_details->data['status'] == 'Active'): ?>
                                       | <a href="javascript:void(0);" class="announcement-disabling" data-announcement="<?php echo base64_encode($eachUserAnnouncement->ID); ?>" title="<?php _e('Disable', THEME_TEXTDOMAIN); ?>"><i class="fa fa-ban" aria-hidden="true"></i></a>
                                    <?php else: ?>
                                        <?php if(!$payment_required): ?>
                                            | <a href="javascript:void(0);" class="announcement-enabling" data-announcement="<?php echo base64_encode($eachUserAnnouncement->ID); ?>" title="<?php _e('Enable', THEME_TEXTDOMAIN); ?>"><i class="fa fa-check" aria-hidden="true"></i></a>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if ($currDate > strtotime($announcement_details->data['end_date'])): ?>
                                        | <a href="<?php echo ANNOUNCEMENT_MANAGEMENT_PAGE . '?announcement_renew=' . base64_encode($eachUserAnnouncement->ID); ?>" title="<?php _e('Renew', THEME_TEXTDOMAIN); ?>"><i class="fa fa-repeat" aria-hidden="true"></i></a>
                                    <?php endif; ?>

                                    <?php if($payment_required): ?>

                                            |
                                            <?php /*echo (isset($payment_link)) ? '<a title="Make Payment" href="' . $payment_link . '"><i class="fa fa-paypal" aria-hidden="true"></i></a>' : $_status_msg;*/ ?>
                                             <?php echo (isset($payment_link)) ? '<a title="Make Payment" href="#user_announcement_payment_popup" class="click-announce-pay" data-announcement="'. base64_encode($eachUserAnnouncement->ID) .'" data-toggle="modal"><i class="fa fa-paypal" aria-hidden="true"></i></a>' : $_status_msg; ?>
                                        <?php endif; ?>
                                <?php else: ?>
                                    <?php echo ' - '; ?>
                                <?php endif; ?> 
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php
        else:
            ?>
            <div class="mobile-deals" style="font-size:12px;">
                <div class="cart-details">
                    <div class="alert alert-danger"><?php _e('No announcement posted till now.', THEME_TEXTDOMAIN); ?></div>
                </div>
            </div>
        <?php
        endif;
        ?>
    </div>

    <div class="section-heading" style="margin-top: 10px; margin-bottom: 10px;">
        <h2><?php _e('Announcement Transaction', THEME_TEXTDOMAIN); ?></h2>
    </div>

    <div class="">
        <?php
        if (is_array($getUserAnnouncementPaymentData) && count($getUserAnnouncementPaymentData) > 0):
            foreach ($getUserAnnouncementPaymentData as $eachUserPaymentData):
                ?>
                <div class="mobile-deals" style="font-size:12px;">
                    <div class="cart-details">
                        <div class="row">
                            <div class="col-sm-6 col-xs-6"><?php _e('Unique Code', THEME_TEXTDOMAIN); ?></div>
                            <div class="col-sm-6 col-xs-6"><?php echo $eachUserPaymentData->unique_announcement_code; ?></div>
                        </div>
                    </div>
                    <div class="cart-details">
                        <div class="row">
                            <div class="col-sm-6 col-xs-6"><?php _e('Announcement', THEME_TEXTDOMAIN); ?></div>
                            <div class="col-sm-6 col-xs-6"><?php echo get_the_title($eachUserPaymentData->announcement_id); ?></div>
                        </div>
                    </div>
                    <div class="cart-details">
                        <div class="row">
                            <div class="col-sm-6 col-xs-6"><?php _e('Total Price', THEME_TEXTDOMAIN); ?></div>
                            <div class="col-sm-6 col-xs-6"><?php _e('R$ ', THEME_TEXTDOMAIN); ?><?php echo ($eachUserPaymentData->total_price > 0) ? number_format($eachUserPaymentData->total_price, 2) : '0.00'; ?></div>
                        </div>
                    </div>
                    <div class="cart-details">
                        <div class="row">
                            <div class="col-sm-6 col-xs-6"><?php _e('Transaction ID', THEME_TEXTDOMAIN); ?></div>
                            <div class="col-sm-6 col-xs-6"><?php echo $eachUserPaymentData->transaction_id; ?></div>
                        </div>
                    </div>
                    <div class="cart-details">
                        <div class="row">
                            <div class="col-sm-6 col-xs-6"><?php _e('Payment Status', THEME_TEXTDOMAIN); ?></div>
                            <div class="col-sm-6 col-xs-6"><?php echo ($eachUserPaymentData->payment_status == 1) ? 'Pago' : 'Não remunerado'; ?></div>
                        </div>
                    </div>
                    <div class="cart-details">
                        <div class="row">
                            <div class="col-sm-6 col-xs-6"><?php _e('Payment Date', THEME_TEXTDOMAIN); ?></div>
                            <div class="col-sm-6 col-xs-6"><?php echo ($eachUserPaymentData->payment_date) ? date('d M, Y', $eachUserPaymentData->payment_date) : '-'; ?></div>
                        </div>
                    </div>
                </div>
                <?php
            endforeach;
        else:
            ?>
            <div class="mobile-deals" style="font-size:12px;">
                <div class="cart-details">
                    <div class="alert alert-danger"><?php _e('No announcement transaction made till now.', THEME_TEXTDOMAIN); ?></div>
                </div>
            </div>
        <?php
        endif;
        ?>
    </div>
</div>
<?php
