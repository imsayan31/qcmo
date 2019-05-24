<?php

/**
 * -------------------------------------
 * THEME SCRIPTS: Contain All JS,STYLES
 * -------------------------------------
 */
/**
 * ------------------------------------
 * SCRIPTS: JS
 * ------------------------------------
 */
if (!function_exists('theme_javascript_scripts')) {

    function theme_javascript_scripts() {

        wp_enqueue_script('jquery');

        wp_register_script('bootstrap-min-js', THEME_URL . '/bootstrap/js/bootstrap.min.js');
        wp_enqueue_script('bootstrap-min-js');

        wp_register_script('bootstrap-ladda-spin-js', THEME_URL . '/bootstrap/ladda/js/spin.min.js');
        wp_enqueue_script('bootstrap-ladda-spin-js');

        wp_register_script('bootstrap-ladda-js', THEME_URL . '/bootstrap/ladda/js/ladda.min.js');
        wp_enqueue_script('bootstrap-ladda-js');

        wp_register_script('bootstrap-notify-js', THEME_URL . '/bootstrap/bootstarp-notify/js/bootstrap-notify.min.js');
        wp_enqueue_script('bootstrap-notify-js');
        wp_register_script('bootstrap-confirmation-js', THEME_URL . '/bootstrap/confirmation/js/confirmation.js');
        wp_enqueue_script('bootstrap-confirmation-js');
        wp_register_script('jquery-ui-js', THEME_URL . '/assets/js/jquery-ui.js');
        wp_enqueue_script('jquery-ui-js');

        wp_register_script('chosen.jquery-js', THEME_URL . '/assets/js/chosen.jquery.js');
        wp_enqueue_script('chosen.jquery-js');

        wp_register_script('owl-carousal-js', THEME_URL . '/assets/js/owl.carousel.min.js');
        wp_enqueue_script('owl-carousal-js');
        
        wp_register_script('jquery-flexslider-js', THEME_URL . '/assets/js/jquery.flexslider.js');
        wp_enqueue_script('jquery-flexslider-js');

        wp_register_script('jquery-datatable-js', THEME_URL . '/assets/js/jquery-datatable.js');
        wp_enqueue_script('jquery-datatable-js');

        wp_register_script('hello-js', THEME_URL . '/assets/js/hello.js');
        wp_enqueue_script('hello-js');

        wp_register_script('social-js', THEME_URL . '/assets/js/social.js');
        wp_enqueue_script('social-js');

        wp_register_script('masked-js', THEME_URL . '/assets/js/jquery-masked-input.js');
        wp_enqueue_script('masked-js');
        
        
        /* Star Rating JS */
        wp_register_script('star-rating-js', THEME_URL . '/assets/js/star-rating.js');
        wp_enqueue_script('star-rating-js');

        /* Landing City Choose */
        wp_register_script('choose-city-js', MODULE_URL . '/frontend/choose-city-on-landing/choose-city.js');
        wp_enqueue_script('choose-city-js');

        /* Log In */
        wp_register_script('user-login-js', MODULE_URL . '/frontend/user-log-in/user-login.js');
        wp_enqueue_script('user-login-js');

        /* Forgot Password */
        wp_register_script('user-forgot-password-js', MODULE_URL . '/frontend/user-forgot-password/user-forgot-password.js');
        wp_enqueue_script('user-forgot-password-js');

        /* Registration */
        wp_register_script('user-registration-js', MODULE_URL . '/frontend/user-registration/user-registration.js');
        wp_enqueue_script('user-registration-js');

        /* Account Update */
        wp_register_script('user-account-js', MODULE_URL . '/frontend/user-account/user-account.js');
        wp_enqueue_script('user-account-js');

        /* Change Password */
        wp_register_script('user-change-password-js', MODULE_URL . '/frontend/user-change-password/user-change-password.js');
        wp_enqueue_script('user-change-password-js');

        /* Reset Password */
        wp_register_script('user-reset-password-js', MODULE_URL . '/frontend/user-reset-password/user-reset-password.js');
        wp_enqueue_script('user-reset-password-js');

        /* Cart Module */
        wp_register_script('cart-module-js', MODULE_URL . '/frontend/cart-module/cart-module.js');
        wp_enqueue_script('cart-module-js');

        /* Wishlist Module */
        wp_register_script('wishlist-module-js', MODULE_URL . '/frontend/wishlist-module/wishlist-module.js');
        wp_enqueue_script('wishlist-module-js');

        /* Wishlist Module */
        wp_register_script('user-finalize-js', MODULE_URL . '/frontend/user-finalize/user-finalize.js');
        wp_enqueue_script('user-finalize-js');

        /* State City Choose Module */
        wp_register_script('state-city-selection-js', MODULE_URL . '/frontend/state-city-selection/state-city-selection.js');
        wp_enqueue_script('state-city-selection-js');

        /* State City Choose Module */
        wp_register_script('home-search-js', MODULE_URL . '/frontend/home-search/home-search.js');
        wp_enqueue_script('home-search-js');

        /* User Deals Status Module */
        wp_register_script('user-deal-status-change-js', MODULE_URL . '/frontend/user-deals/user-deal-status-change.js');
        wp_enqueue_script('user-deal-status-change-js');

        /* User Plan Purchase Module */
        wp_register_script('user-plan-purchase-js', MODULE_URL . '/frontend/user-plan-purchase/user-plan-purchase.js');
        wp_enqueue_script('user-plan-purchase-js');

        /* User Plan Purchase Module */
        wp_register_script('supplier-search-js', MODULE_URL . '/frontend/supplier-listing/supplier-search.js');
        wp_enqueue_script('supplier-search-js');

        /* Supplier Rating Module */
        wp_register_script('supplier-rating-js', MODULE_URL . '/frontend/supplier-rating/supplier-rating.js');
        wp_enqueue_script('supplier-rating-js');

        /* Advertisement Module */
        wp_register_script('advertisement-module-js', MODULE_URL . '/frontend/advertisement-module/advertisement-module.js');
        wp_enqueue_script('advertisement-module-js');

        /* PayPal Donation Module */
        wp_register_script('paypal-donation-js', MODULE_URL . '/frontend/paypal-donation/paypal-donation.js');
        wp_enqueue_script('paypal-donation-js');

        /* PayPal Donation Module */
        wp_register_script('user-material-list-js', MODULE_URL . '/frontend/user-material-list/user-material-list.js');
        wp_enqueue_script('user-material-list-js');

        /* Supplier Deal Settings Module */
        wp_register_script('supplier-deal-settings-js', MODULE_URL . '/frontend/supplier-deal-settings/supplier-deal-settings.js');
        wp_enqueue_script('supplier-deal-settings-js');

        /* Announcement Module */
        wp_register_script('announcement-section-js', MODULE_URL . '/frontend/announcement-module/announcement-section.js');
        wp_enqueue_script('announcement-section-js');

        /* Custom Scrollbar JS */
        wp_register_script('jquery.mCustomScrollbar-js', THEME_URL . '/assets/js/jquery.mCustomScrollbar.concat.min.js');
        wp_enqueue_script('jquery.mCustomScrollbar-js');

        /* Multiple File Uploader JS */
        wp_register_script('jquery-dm-uploader-js', THEME_URL . '/assets/js/jquery.dm-uploader.js');
        wp_enqueue_script('jquery-dm-uploader-js');
        
        wp_register_script('demo-ui-js', THEME_URL . '/assets/js/demo-ui.js');
        wp_enqueue_script('demo-ui-js');

        /* Main Theme JS */
        wp_register_script('theme-js', THEME_URL . '/assets/js/theme.js');
        wp_enqueue_script('theme-js');

        /* Coupon Managemnet JS */
        wp_register_script('coupon-manage-js', MODULE_URL . '/frontend/coupon-management/coupon-manage.js');
        wp_enqueue_script('coupon-manage-js');

        $object_id = '';
        if (is_single()) {
            global $post;
            $object_id = $post->ID;
        }
        $passing_object_to_js = array(
            'base_url' => BASE_URL,
            'ajaxurl' => admin_url('admin-ajax.php'),
            'drag_n_drop_admin_url' => admin_url('admin-ajax.php') . '?action=drag_and_drop_image_upload&uploading_val=1',
            'object_id' => $object_id,
            'fbapp' => get_option('fb_app_id'),
            'user_logged_in' => (is_user_logged_in()) ? 1 : 2,
        );

        wp_localize_script('theme-js', 'Front', $passing_object_to_js);

        wp_localize_script('choose-city-js', 'LandingCity', $passing_object_to_js);

        wp_localize_script('state-city-selection-js', 'StateSelection', $passing_object_to_js);

        wp_localize_script('user-login-js', 'Login', $passing_object_to_js);

        wp_localize_script('user-registration-js', 'Registration', $passing_object_to_js);

        wp_localize_script('user-account-js', 'AccountUpdate', $passing_object_to_js);

        wp_localize_script('user-change-password-js', 'ChangePassword', $passing_object_to_js);

        wp_localize_script('user-forgot-password-js', 'ForgotPassword', $passing_object_to_js);

        wp_localize_script('user-reset-password-js', 'ResetPassword', $passing_object_to_js);

        wp_localize_script('wishlist-module-js', 'Wishlist', $passing_object_to_js);

        wp_localize_script('cart-module-js', 'Cart', $passing_object_to_js);

        wp_localize_script('user-finalize-js', 'Finalize', $passing_object_to_js);

        wp_localize_script('home-search-js', 'HomeStateCitySelection', $passing_object_to_js);

        wp_localize_script('user-deal-status-change-js', 'UserDealStatusChange', $passing_object_to_js);

        wp_localize_script('user-plan-purchase-js', 'UserPlanPurchase', $passing_object_to_js);

        wp_localize_script('supplier-search-js', 'SupplierSearch', $passing_object_to_js);

        wp_localize_script('supplier-rating-js', 'SupplierRating', $passing_object_to_js);

        wp_localize_script('advertisement-module-js', 'AdvertisementModule', $passing_object_to_js);

        wp_localize_script('paypal-donation-js', 'PayPalDonation', $passing_object_to_js);

        wp_localize_script('user-material-list-js', 'MaterialList', $passing_object_to_js);

        wp_localize_script('supplier-deal-settings-js', 'SupplierDealSettings', $passing_object_to_js);

        wp_localize_script('announcement-section-js', 'AnnouncementSection', $passing_object_to_js);

        wp_localize_script('coupon-manage-js', 'CouponManage', $passing_object_to_js);
    }

}

/**
 * ------------------------------------
 * SCRIPTS: ADMIN/JS
 * ------------------------------------
 */
if (!function_exists('theme_admin_javascript_scripts')) {

    function theme_admin_javascript_scripts() {

        /* Admin Scripts */
        wp_enqueue_script('jquery');

        wp_enqueue_style('jquery-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

        wp_register_script('admin-jquery-ui-js', THEME_URL . '/assets/js/jquery-ui.js');
        wp_enqueue_script('admin-jquery-ui-js');

        wp_register_style('admin-jquery-ui-css', THEME_URL . '/assets/css/jquery-ui.css');
        wp_enqueue_style('admin-jquery-ui-css');

        wp_register_style('admin-chosen-css', THEME_URL . '/assets/css/chosen.css');
        wp_enqueue_style('admin-chosen-css');

        wp_register_script('admin-chosen-js', THEME_URL . '/assets/js/chosen.jquery.js');
        wp_enqueue_script('admin-chosen-js');

        wp_register_script('theme-admin-js', THEME_URL . '/assets/js/theme-admin.js');
        wp_enqueue_script('theme-admin-js');
        
        wp_register_script('admin-timepicker-js', THEME_URL . '/assets/js/jquery.timepicker.js');
        wp_enqueue_script('admin-timepicker-js');

        wp_register_style('admin-timepicker-css', THEME_URL . '/assets/css/jquery.timepicker.css');
        wp_enqueue_style('admin-timepicker-css');

        wp_register_style('theme-admin-css', THEME_URL . '/assets/css/theme-admin-css.css');
        wp_enqueue_style('theme-admin-css');

        wp_register_script('attribute-selection-js', MODULE_URL . '/admin/attribute-selection/attribute_selection.js');
        wp_enqueue_script('attribute-selection-js');

        wp_register_script('state-city-selection-js', MODULE_URL . '/admin/state-city-selection/state-city-selection.js');
        wp_enqueue_script('state-city-selection-js');

        wp_register_script('advertisement-state-city-selection-js', MODULE_URL . '/admin/advertisement-section/advertisement-state-city-selection.js');
        wp_enqueue_script('advertisement-state-city-selection-js');

        wp_register_script('attribut-city-price-selection-js', MODULE_URL . '/admin/attribut-city-price-selection/attribut-city-price-selection.js');
        wp_enqueue_script('attribut-city-price-selection-js');

        wp_register_script('deal-status-change-js', MODULE_URL . '/admin/deal-status-change/deal-status-change.js');
        wp_enqueue_script('deal-status-change-js');

        wp_register_script('admin-supplier-status-js', MODULE_URL . '/admin/extra-column-user-list/admin-supplier-status-change.js');
        wp_enqueue_script('admin-supplier-status-js');

        wp_register_script('membership-payment-status-change-js', MODULE_URL . '/admin/membership-payment-status-change/membership-status-change.js');
        wp_enqueue_script('membership-payment-status-change-js');

        wp_register_script('admin-masked-js', THEME_URL . '/assets/js/jquery-masked-input.js');
        wp_enqueue_script('admin-masked-js');

        wp_register_script('deal-review-action-js', MODULE_URL . '/admin/deal-reviews-list/deal-review-action.js');
        wp_enqueue_script('deal-review-action-js');

        wp_register_script('reset-product-view-js', MODULE_URL . '/admin/reset-product-view/reset-product-view.js');
        wp_enqueue_script('reset-product-view-js');

        wp_register_script('announcement-settings-js', MODULE_URL . '/admin/announcement-settings/announcement-settings.js');
        wp_enqueue_script('announcement-settings-js');
        
        wp_register_script('coupon-management-js', MODULE_URL . '/admin/coupon-setup/coupon-management.js');
        wp_enqueue_script('coupon-management-js');
        
        // wp_register_script('fullcalendar-js', THEME_URL . '/assets/js/fullcalendar.min.js');
        // wp_enqueue_script('fullcalendar-js');

        global $post;
        $object_id = $post->ID;

        wp_localize_script('theme-admin-js', 'Back', array(
            'ajaxurl' => admin_url('admin-ajax.php')
        ));

        wp_localize_script('attribute-selection-js', 'AttributeSelection', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
        ));

        wp_localize_script('state-city-selection-js', 'StateSelection', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
        ));

        wp_localize_script('advertisement-state-city-selection-js', 'StateSelection', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
        ));

        wp_localize_script('deal-status-change-js', 'DealStatus', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
        ));

        wp_localize_script('membership-payment-status-change-js', 'MembershipPaymentStatus', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
        ));

        wp_localize_script('deal-review-action-js', 'DealReview', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
        ));

        wp_localize_script('reset-product-view-js', 'ResetProductView', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
        ));

        wp_localize_script('announcement-settings-js', 'AnnouncementSettings', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
        ));

        wp_localize_script('coupon-management-js', 'CouponManagement', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
        ));

        /* Admin Styles */
        
        //wp_enqueue_style('fullcalendar-css', THEME_URL . '/assets/csss/fullcalendar.min.css');
    }

}

/**
 * ------------------------------------
 * SCRIPTS: STYLES
 * ------------------------------------
 */
if (!function_exists('theme_styles_script')) {

    function theme_styles_script() {

        /* Bootstrap CSS */
        wp_register_style('bootstrap-css', THEME_URL . '/bootstrap/css/bootstrap.min.css');
        wp_enqueue_style('bootstrap-css');

        wp_register_style('bootstrap-ladda-css', THEME_URL . '/bootstrap/ladda/css/ladda-themeless.min.css');
        wp_enqueue_style('bootstrap-ladda-css');

        wp_register_style('bootstrap-notify-css', THEME_URL . '/bootstrap/bootstarp-notify/css/animate.css');
        wp_enqueue_style('bootstrap-notify-css');

        wp_register_style('bootstrap-social-css', THEME_URL . '/bootstrap/css/bootstrap-social.css');
        wp_enqueue_style('bootstrap-social-css');

        wp_register_style('chosen-css', THEME_URL . '/assets/css/chosen.css');
        wp_enqueue_style('chosen-css');

        wp_register_style('font-awesome-css', THEME_URL . '/assets/css/font-awesome.min.css');
        wp_enqueue_style('font-awesome-css');

        wp_register_style('jquery-ui-css', THEME_URL . '/assets/css/jquery-ui.css');
        wp_enqueue_style('jquery-ui-css');

        wp_register_style('custom-radio-style-css', THEME_URL . '/assets/css/custom-radio-style.css');
        wp_enqueue_style('custom-radio-style-css');

        wp_register_style('owl-carousal-css', THEME_URL . '/assets/css/owl.carousel.min.css');
        wp_enqueue_style('owl-carousal-css');
        
        wp_register_style('flexslider-css', THEME_URL . '/assets/css/flexslider.css');
        wp_enqueue_style('flexslider-css');

        wp_register_style('jquery-datatable-css', THEME_URL . '/assets/css/jquery-datatable.css');
        wp_enqueue_style('jquery-datatable-css');

        wp_register_style('jquery.mCustomScrollbar', THEME_URL . '/assets/css/jquery.mCustomScrollbar.css');
        wp_enqueue_style('jquery.mCustomScrollbar');

        wp_register_style('jquery-dm-uploader-css', THEME_URL . '/assets/css/jquery.dm-uploader.css');
        wp_enqueue_style('jquery-dm-uploader-css');

        wp_register_style('main-css', THEME_URL . '/assets/css/main.css');
        wp_enqueue_style('main-css');

        wp_register_style('star-rating-css', THEME_URL . '/assets/css/star-rating.css');
        wp_enqueue_style('star-rating-css');
    }

}
