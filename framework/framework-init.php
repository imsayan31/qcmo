<?php

class themeFramework {

    public static $theme_prefix = 'andr_';

    function __construct() {
        
    }

    /**
     * Method call many important methods that needs for
     * the theme 
     * @param type $options
     */
    public static function init($options) {
        self::themeConstants($options);
        self::themeFunctions();
        self::themeActions();
        self::themeFilters();
    }

    /**
     * @themeConstatnts
     * define theme contants
     * @param array $options
     */
    public static function themeConstants(array $options) {
        global $wpdb;

        /* TBL define */
        if (!defined('TBL_BRAZIL_CITIES'))
            define('TBL_BRAZIL_CITIES', themeFramework::$theme_prefix . 'brazil_cities');
        if (!defined('TBL_CART'))
            define('TBL_CART', themeFramework::$theme_prefix . 'cart');
        if (!defined('TBL_WISHLIST'))
            define('TBL_WISHLIST', themeFramework::$theme_prefix . 'wishlist');
        if (!defined('TBL_DEAL_FINALIZE'))
            define('TBL_DEAL_FINALIZE', themeFramework::$theme_prefix . 'deal_finalize');
        if (!defined('TBL_DEAL_FINALIZE_PRODUCTS'))
            define('TBL_DEAL_FINALIZE_PRODUCTS', themeFramework::$theme_prefix . 'deal_finalize_details');
        if (!defined('TBL_DEAL_FINALIZE_SUPPLIERS'))
            define('TBL_DEAL_FINALIZE_SUPPLIERS', themeFramework::$theme_prefix . 'deal_finalize_supplier_details');
        if (!defined('TBL_MEMBERSHIP'))
            define('TBL_MEMBERSHIP', themeFramework::$theme_prefix . 'membership_tbl');
        if (!defined('TBL_GOOGLE_LOCATION'))
            define('TBL_GOOGLE_LOCATION', themeFramework::$theme_prefix . 'google_location');
        if (!defined('TBL_SUPPLIER_RATING'))
            define('TBL_SUPPLIER_RATING', themeFramework::$theme_prefix . 'supplier_rating');
        if (!defined('TBL_DONATION'))
            define('TBL_DONATION', themeFramework::$theme_prefix . 'donation');
        if (!defined('TBL_ADVERTISEMENT_PAYMENT'))
            define('TBL_ADVERTISEMENT_PAYMENT', themeFramework::$theme_prefix . 'adv_payment');
        if (!defined('TBL_ANNOUNCEMENT_PAYMENT'))
            define('TBL_ANNOUNCEMENT_PAYMENT', themeFramework::$theme_prefix . 'announcement_payment');


        if (!defined('THEME_NAME'))
            define('THEME_NAME', $options['theme_name']);
        if (!defined('THEME_VERSION'))
            define('THEME_VERSION', $options['theme_version']);
        /* if (!defined('THEME_TEXTDOMAIN'))
          define('THEME_TEXTDOMAIN', $options['theme_textdomain']); */
        if (!defined('THEME_TEXTDOMAIN'))
            define('THEME_TEXTDOMAIN', "blank");

        if (!defined('THEME_INC'))
            define('THEME_INC', FRAMEWORK_PATH . '/theme-inc');
        if (!defined('THEME_TEMP'))
            define('THEME_TEMP', FRAMEWORK_PATH . '/theme-temp');
        if (!defined('THEME_LIB'))
            define('THEME_LIB', FRAMEWORK_PATH . '/theme-lib');
        if (!defined('THEME_SETTINGS_GROUP'))
            define('THEME_SETTINGS_GROUP', self::$theme_prefix . 'settings_group');
        if (!defined('THEME_OPTION_NAME'))
            define('THEME_OPTION_NAME', self::$theme_prefix . 'option_name');
        if (!defined('BASE_URL'))
            define('BASE_URL', get_bloginfo('url') . '/');

        /* Static pages */



        /* Template Pages */
        if (!defined('MY_ACCOUNT_PAGE'))
            define('MY_ACCOUNT_PAGE', BASE_URL . 'my-account/');
        if (!defined('MY_WISHLIST_PAGE'))
            define('MY_WISHLIST_PAGE', BASE_URL . 'my-wishlist/');
        if (!defined('CART_PAGE'))
            define('CART_PAGE', BASE_URL . 'cart/');
        if (!defined('ALL_PRODUCTS_PAGE'))
            define('ALL_PRODUCTS_PAGE', BASE_URL . 'all-products/');
        if (!defined('MY_DEALS_PAGE'))
            define('MY_DEALS_PAGE', BASE_URL . 'my-deals/');
        if (!defined('SUPPLIER_ACCOUNT_PAGE'))
            define('SUPPLIER_ACCOUNT_PAGE', BASE_URL . 'supplier-account/');
        if (!defined('SUPPLIER_DASHBOARD_PAGE'))
            define('SUPPLIER_DASHBOARD_PAGE', BASE_URL . 'supplier-dashboard/');
        if (!defined('SUPPLIER_LISTING_PAGE'))
            define('SUPPLIER_LISTING_PAGE', BASE_URL . 'supplier-listing/');
        if (!defined('MATERIAL_LIST_PAGE'))
            define('MATERIAL_LIST_PAGE', BASE_URL . 'product-material-list/');
        if (!defined('SUPPLIER_DEAL_SETTINGS_PAGE'))
            define('SUPPLIER_DEAL_SETTINGS_PAGE', BASE_URL . 'supplier-deal-settings/');
        if (!defined('CREATE_SUPPLIER_SCORE_PAGE'))
            define('CREATE_SUPPLIER_SCORE_PAGE', BASE_URL . 'create-supplier-score/');
        if (!defined('ANNOUNCEMENT_MANAGEMENT_PAGE'))
            define('ANNOUNCEMENT_MANAGEMENT_PAGE', BASE_URL . 'announcement-management/');
        if (!defined('MY_ANNOUNCEMENTS_PAGE'))
            define('MY_ANNOUNCEMENTS_PAGE', BASE_URL . 'my-announcements/');
        if (!defined('TERMS_PAGE'))
            define('TERMS_PAGE', BASE_URL . 'terms-and-conditions/');
        if (!defined('ANNOUNCEMENT_LISTING_PAGE'))
            define('ANNOUNCEMENT_LISTING_PAGE', BASE_URL . 'announcement-listing/');
        if (!defined('ANNOUNCEMENT_MAP_LISTING_PAGE'))
            define('ANNOUNCEMENT_MAP_LISTING_PAGE', BASE_URL . 'announcement-map-listing/');
        if (!defined('SHARED_DEAL_DETAILS_PAGE'))
            define('SHARED_DEAL_DETAILS_PAGE', BASE_URL . 'shared-deal-details/');

        /* ADMIN PAGE */


        /* LOADER */
        if (!defined('LOADER_IMG'))
            define('LOADER_IMG', THEME_URL . '/images/ajax-loader2.gif');


        /* COMMON */
    }

    /**
     * @themeSupports
     * Setup the theme supports feature
     */
    public static function themeSupports() {

        $moderatorCaps = [
            'read' => true,
            'level_0' => true,
        ];

        add_role('supplier', __('Supplier'));
        add_role('moderator', __('Moderator'), $moderatorCaps);
        add_role('sub_admin', __('Sub Administrator'), $moderatorCaps);

        $sub_admin_object = get_role('sub_admin');
        $sub_admin_object->add_cap('manage_product_prices');
        $sub_admin_object->add_cap('manage_export_import');
        $sub_admin_object->add_cap('manage_membership_transaction');
        $sub_admin_object->add_cap('manage_deal_list');
        $sub_admin_object->add_cap('manage_donation_list');
        $sub_admin_object->add_cap('manage_deal_review_list');
        $sub_admin_object->add_cap('list_users');
        
        //remove_role('sub_admin');

        add_image_size('product_listing_image', 240, 200, TRUE);
        add_image_size('product_category_image', 360, 100, TRUE);
        add_image_size('product_details_image', 570, 600, TRUE);
        add_image_size('product_advertisement_image', 1140, 230, TRUE);
        //add_image_size('supplier_details_image', 375, 480, TRUE);
    }

    /**
     * @themeFunctions
     * get default theme functions for this theme
     */
    public static function themeFunctions() {

        /* THEME LIB */
        require_once THEME_LIB . '/theme-general.php';
        require_once THEME_LIB . '/class-wp-list-table.php';
        require_once THEME_LIB . '/deal-list-table.php';
        require_once THEME_LIB . '/sub-admin-simple-products-list-table.php';
        require_once THEME_LIB . '/membership-list-table.php';
        require_once THEME_LIB . '/class-valida-cpf-cnpj.php';
        require_once THEME_LIB . '/donation-list-table.php';
        require_once THEME_LIB . '/deal-review-list-table.php';
        require_once THEME_LIB . '/advertisement-payment-list-table.php';
        require_once THEME_LIB . '/unmatched-product-list-table.php';
        require_once THEME_LIB . '/announcement-transaction-list-table.php';
        require_once THEME_LIB . '/woo-paypal-pro-gateway-class.php';
        

        /* THEME INC */
        require_once THEME_INC . '/theme-admin-menu.php';
        require_once THEME_INC . '/theme-scripts.php';
        require_once THEME_INC . '/theme-functions.php';
        require_once THEME_INC . '/theme-cron-functions.php';
        require_once THEME_INC . '/theme-core.php';
        require_once THEME_INC . '/theme-metabox.php';
        require_once THEME_INC . '/theme-admin-extra-list-column.php';
        require_once THEME_INC . '/theme-admin-user-bulk-process.php';
        /* AJAX MODULES */

        /* Front End */
        require_once MODULE_PATH . '/frontend/social-login/ajaxSocialLogin.php';
        require_once MODULE_PATH . '/frontend/choose-city-on-landing/ajaxSaveCity.php';
        require_once MODULE_PATH . '/frontend/user-forgot-password/ajaxForgotPassword.php';
        require_once MODULE_PATH . '/frontend/user-log-in/ajaxUserLogin.php';
        require_once MODULE_PATH . '/frontend/user-registration/ajaxUserRegistration.php';
        require_once MODULE_PATH . '/frontend/user-reset-password/ajaxResetPassword.php';
        require_once MODULE_PATH . '/frontend/user-account/ajaxUserAccount.php';
        require_once MODULE_PATH . '/frontend/user-change-password/ajaxChangePassword.php';
        require_once MODULE_PATH . '/frontend/wishlist-module/ajaxWishlist.php';
        require_once MODULE_PATH . '/frontend/cart-module/ajaxCart.php';
        require_once MODULE_PATH . '/frontend/user-finalize/ajaxFinalize.php';
        require_once MODULE_PATH . '/frontend/state-city-selection/ajaxStateCity.php';
        require_once MODULE_PATH . '/frontend/home-search/ajaxHomeStateCitySearch.php';
        require_once MODULE_PATH . '/frontend/user-plan-purchase/ajaxUserPlanPurchase.php';
        require_once MODULE_PATH . '/frontend/supplier-listing/ajaxSupplierSearch.php';
        require_once MODULE_PATH . '/frontend/supplier-rating/ajaxSupplierRating.php';
        require_once MODULE_PATH . '/frontend/advertisement-module/ajaxAdvertisementModule.php';
        require_once MODULE_PATH . '/frontend/paypal-donation/ajaxPayPalDonation.php';
        require_once MODULE_PATH . '/frontend/user-deals/ajaxDealActions.php';
        require_once MODULE_PATH . '/frontend/supplier-deal-settings/ajaxSupplierDealSettings.php';
        require_once MODULE_PATH . '/frontend/announcement-module/ajaxAnnouncement.php';
        require_once MODULE_PATH . '/frontend/coupon-management/ajaxFrontEndCouponManage.php';
        

        /* Admin End */
        require_once MODULE_PATH . '/admin/state-city-selection/ajaxStateCity.php';
        require_once MODULE_PATH . '/admin/attribute-selection/ajaxAttributeSelection.php';
        require_once MODULE_PATH . '/admin/deal-status-change/ajaxDealStatusChange.php';
        require_once MODULE_PATH . '/admin/extra-column-user-list/ajaxSupplierActivationStatus.php';
        require_once MODULE_PATH . '/admin/membership-payment-status-change/ajaxMembershipPaymentStatus.php';
        require_once MODULE_PATH . '/admin/advertisement-section/ajaxAdvPreviewPrice.php';
        require_once MODULE_PATH . '/admin/deal-reviews-list/ajaxDealReviewAction.php';
        require_once MODULE_PATH . '/admin/user-additional-information/user-additional-information.php';
        require_once MODULE_PATH . '/admin/extra-column-user-list/extra-column-user-list.php';
        require_once MODULE_PATH . '/admin/advertisement-section/advertisement-section.php';
        require_once MODULE_PATH . '/admin/product-export-import/product-export-import.php';
        require_once MODULE_PATH . '/admin/sub-admin-additional-information/sub-admin-additional-information.php';
        require_once MODULE_PATH . '/admin/sub-admin-additional-information/sub-admin-functionalities.php';
        require_once MODULE_PATH . '/admin/advertisement-price-settings/advertisement-price-settings.php';
        require_once MODULE_PATH . '/admin/deal-reviews-list/deal-reviews-list.php';
        require_once MODULE_PATH . '/admin/unmatched-products/unmatched-products.php';
        require_once MODULE_PATH . '/admin/reset-product-view/ajaxResetProductView.php';
        require_once MODULE_PATH . '/admin/announcement-settings/announcement-settings.php';
        require_once MODULE_PATH . '/admin/announcement-settings/ajaxAnnouncementSettings.php';
        require_once MODULE_PATH . '/admin/announcement-settings/announcement-transactions.php';
        require_once MODULE_PATH . '/admin/announcement-settings/announcement-metabox-settings.php';
        require_once MODULE_PATH . '/admin/coupon-setup/ajaxCouponManagement.php';

        /* LIBRARY MODULES */

        /* Front End */
        require_once MODULE_PATH . '/frontend/wishlist-module/classWishList.php';
        require_once MODULE_PATH . '/frontend/cart-module/classCart.php';
        require_once MODULE_PATH . '/frontend/user-finalize/classFinalize.php';
        require_once MODULE_PATH . '/frontend/user-plan-purchase/classMemberShip.php';
        require_once MODULE_PATH . '/frontend/supplier-rating/classSupplierRating.php';
        require_once MODULE_PATH . '/frontend/paypal-donation/classPayPalDonation.php';
        require_once MODULE_PATH . '/frontend/announcement-module/classAnnouncement.php';
        require_once MODULE_PATH . '/frontend/coupon-management/classCouponMangement.php';

        /* PayPal Libraries */
        require_once THEME_LIB . '/paypal.php';
        require_once THEME_INC . '/theme-outsource-payment-validate.php';
    }

    /**
     * @themeActions
     * this is the heart of wordpress theme
     * Fire off all the actions for this theme
     */
    public static function themeActions() {
        /**
         * Wordpress actions
         */
        add_action('wp_head', 'activateUserAccount');
        add_action('wp_head', 'activateSuplierAccount');
        add_action('wp_head', 'resetUserPassword');
        add_action('after_setup_theme', array('themeFramework', 'themeSupports'));
        add_action('wp_enqueue_scripts', 'theme_styles_script');
        add_action('wp_enqueue_scripts', 'theme_javascript_scripts');
        add_action('admin_enqueue_scripts', 'theme_admin_javascript_scripts');
        //add_action('admin_enqueue_scripts', 'theme_styles_script');

        add_action('show_admin_bar', '__return_false');
        add_action('init', 'theme_core_setup');
        add_action('admin_menu', 'theme_admin_menu_func');
        add_action('add_meta_boxes', 'cd_meta_box_add');
        add_action('save_post', 'cd_meta_box_save');
        add_action('add_meta_boxes', 'adminAdvertisementSection');
        add_action('save_post', 'adminAdvertisementSectionSave');
        add_action('add_meta_boxes', 'adminAnnouncementSection');
        add_action('save_post', 'adminAnnouncementSectionSave');
        add_action('restrict_manage_users', 'add_course_section_filter');
        add_filter( 'pre_get_users', 'filter_users_by_state_and_city_section' );
        

        // Add the fields to the "presenters" taxonomy, using our callback function  
        add_action('andr_product_city_edit_form_fields', 'andr_product_city_management_func', 10, 2);

        // Save the changes made on the "presenters" taxonomy, using our callback function  
        add_action('edited_andr_product_city', 'save_taxonomy_custom_fields', 10, 2);
        add_action('admin_footer', 'my_admin_add_js');

        /* --------------------------------AJAX ACTION--------------- */
        

        /* For product customization */
    }

    /**
     * @themeFilters
     * Setup theme filters
     */
    public static function themeFilters() {
        add_filter('get_avatar', 'remove_avatar_from_users_list');
    }

}
