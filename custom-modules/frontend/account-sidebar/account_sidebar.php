<?php
$GeneralThemeObject = new GeneralTheme();
$RatingObject = new classReviewRating();
$FinalizeObject = new classFinalize();
$AnnouncementObject = new classAnnouncement();
$userCity = $GeneralThemeObject->getLandingCity();
$user_details = $GeneralThemeObject->user_details();
$getAnnouncementEnabledCities = $AnnouncementObject->getAnnouncementEnabledCities();
$getSupplierAnnEnabled = get_term_meta($user_details->data['city'], '_enable_announcement_for_suppliers', TRUE);
$getCustomerAnnEnabled = get_term_meta($user_details->data['city'], '_enable_announcement_for_customers', TRUE);
$user_pro_pic = wp_get_attachment_image_src($user_details->data['pro_pic'], 'full');
$avlSocialLogin = get_user_meta($user_details->data['user_id'], '_social_login', true);
$totalRating = $RatingObject->getAverageRating($user_details->data['user_id']);
$getRatingHTML = $RatingObject->getRatingHTML($totalRating, FALSE);
$getDeals = $FinalizeObject->getDeals($user_details->data['user_id'], '', FALSE);
if (is_array($getDeals) && count($getDeals) > 0):
    $i = 0;
    foreach ($getDeals as $eachDeal):
        $hasReviewed = $RatingObject->hasUserReviewed($user_details->data['user_id'], $eachDeal->deal_id);
        if ($eachDeal->deal_status == 1 && $hasReviewed == FALSE):
            $i++;
        endif;
    endforeach;
endif;
?>

<nav class="navbar-dash ss" role="navigation">
    <div class="profile-pic">
        <div class="user-pic">
            <img src="<?php echo ($user_details->data['pro_pic_exists'] == TRUE) ? $user_pro_pic[0] : 'https://via.placeholder.com/100x100'; ?>" width="100" height="100" />
        </div>
        <?php if ($user_details->data['role'] == 'subscriber'): ?>
            <h4 class="media-heading"><?php _e('Hello, ', THEME_TEXTDOMAIN); ?><?php echo $user_details->data['fname'] . ' ' . $user_details->data['lname']; ?></h4>
            <?php if ($i > 0): ?>
                <span class="exclaim-sign" style="color: #fff;" data-toggle="tooltip" title="<?php _e('You have ' . $i . ' more deal(s) to score suppliers.', THEME_TEXTDOMAIN); ?>"><i class="fa fa-exclamation" aria-hidden="true"></i></span>
            <?php endif; ?>
        <?php elseif ($user_details->data['role'] == 'supplier'): ?>
            <h4 class="media-heading"><?php _e('Hello, ', THEME_TEXTDOMAIN); ?><?php echo $user_details->data['fname']; ?></h4>
            <p><?php echo $user_details->data['lname']; ?></p>
            <p><?php echo $getRatingHTML; ?></p>
            <?php //echo '<span style="font-weight:900;color: black;font-size: 13px;">CNPJ: '.$user_details->data['cnpj'];'</span>'?>
        <?php endif; ?>

        <p>
            <?php
            if ($user_details->data['supplier_type'] == 1) :
            echo '<span style="font-weight:900;color: black;font-size: 13px;">CPF : ' . $user_details->data['cpf'].'</span>';
            elseif ($user_details->data['supplier_type'] == 2) :
            echo '<span style="font-weight:900;color: black;font-size: 13px;">CNPJ : ' . $user_details->data['cnpj'].'</span>';
            endif;
            ?>
        </p>

    </div>
    <ul class="nav" id="main-menu">
        <!--<li class="<?php echo (is_page('dashoboard-template')) ? 'active' : ''; ?>"><a href="<?php echo USER_DASHBOARD_PAGE; ?>"><i class="fa fa-tachometer" aria-hidden="true"></i>Dashboard</a></li>-->
        <?php if ($user_details->data['role'] == 'subscriber'): ?>
            <li class="<?php echo (is_page('my-account')) ? 'active' : ''; ?>"><a href="<?php echo MY_ACCOUNT_PAGE; ?>"><i class="fa fa-user" aria-hidden="true"></i> <?php _e(' My Account', THEME_TEXTDOMAIN); ?></a></li>
            <li class="<?php echo (is_page('my-wishlist')) ? 'active' : ''; ?>"><a href="<?php echo MY_WISHLIST_PAGE; ?>"><i class="fa fa-heart" aria-hidden="true"></i> <?php _e(' My Wishlist', THEME_TEXTDOMAIN); ?></a></li>
            <li class="<?php echo (is_page('cart')) ? 'active' : ''; ?>"><a href="<?php echo CART_PAGE; ?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i> <?php _e(' My Cart', THEME_TEXTDOMAIN); ?></a></li>
            <li class="<?php echo (is_page('my-deals')) ? 'active' : ''; ?>"><a href="<?php echo MY_DEALS_PAGE; ?>"><i class="fa fa-handshake-o" aria-hidden="true"></i> <?php _e(' My Deals', THEME_TEXTDOMAIN); ?></a></li>
            <?php if (is_array($getAnnouncementEnabledCities) && count($getAnnouncementEnabledCities) > 0 && in_array($user_details->data['city'], $getAnnouncementEnabledCities) && $getCustomerAnnEnabled == 1): ?>
                <li class="<?php echo (is_page('my-announcements')) ? 'active' : ''; ?>"><a href="<?php echo MY_ANNOUNCEMENTS_PAGE; ?>"><i class="fa fa-bullhorn" aria-hidden="true"></i> <?php _e('My Announcements', THEME_TEXTDOMAIN); ?></a></li>
            <?php endif; ?>
        <?php elseif ($user_details->data['role'] == 'supplier'): ?>
            <li class="<?php echo (is_page('supplier-dashboard')) ? 'active' : ''; ?>"><a href="<?php echo SUPPLIER_DASHBOARD_PAGE; ?>"><i class="fa fa-tachometer" aria-hidden="true"></i> <?php _e(' Dashboard', THEME_TEXTDOMAIN); ?></a></li>
            <li class="<?php echo (is_page('supplier-account')) ? 'active' : ''; ?>"><a href="<?php echo SUPPLIER_ACCOUNT_PAGE; ?>"><i class="fa fa-building" aria-hidden="true"></i> <?php _e(' My Account', THEME_TEXTDOMAIN); ?></a></li>
            <li class="<?php echo (is_page('supplier-deal-settings')) ? 'active' : ''; ?>"><a href="<?php echo SUPPLIER_DEAL_SETTINGS_PAGE; ?>"><i class="fa fa-wrench" aria-hidden="true"></i> <?php _e(' Deal Settings', THEME_TEXTDOMAIN); ?></a></li>
            <?php if (is_array($getAnnouncementEnabledCities) && count($getAnnouncementEnabledCities) > 0 && in_array($user_details->data['city'], $getAnnouncementEnabledCities) && $getSupplierAnnEnabled == 1): ?>
                <li class="<?php echo (is_page('my-announcements')) ? 'active' : ''; ?>"><a href="<?php echo MY_ANNOUNCEMENTS_PAGE; ?>"><i class="fa fa-bullhorn" aria-hidden="true"></i> <?php _e('My Announcements', THEME_TEXTDOMAIN); ?></a></li>
            <?php endif; ?>
        <?php endif; ?>
    <li><a href="#deleteModal" data-toggle="modal" data-toggle="tooltip" ><i class="fa fa-trash-o" aria-hidden="true"></i> <?php _e('Deletar Conta', THEME_TEXTDOMAIN); ?></a></li>
        <li><a href="<?php echo wp_logout_url(BASE_URL); ?>"><i class="fa fa-sign-out" aria-hidden="true"></i> <?php _e('Logout', THEME_TEXTDOMAIN); ?></a></li>
    </ul>

</nav>
<div id="deleteModal" class="modal fade custom-modal">
	<div class="modal-dialog modal-confirm">
		<div class="modal-content">
			<div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Are you sure?</h4>	
                        </div>
			<div class="modal-body">
				<p>Do you really want to delete ?</p>
			</div>
			<div class="modal-body">
				<p>This action cannot be undone</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-info cancel" data-dismiss="modal">NO</button>
				<a type="button" class="btn btn-danger userdelete" data-delete="<?php echo $user_details->data['user_id'];?>">YES</a>
			</div>
		</div>
	</div>
</div>
<?php
