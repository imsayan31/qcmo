<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
    <head>
        <?php
        $GeneralThemeObject = new GeneralTheme();
        $AnnouncementObject = new classAnnouncement();
        $FinalizeData = new classFinalize();
        if(is_archive()){
            $getQueriedObject = get_queried_object();
            $supplierDetails = $GeneralThemeObject->user_details($getQueriedObject->ID);
            $getProPic = wp_get_attachment_image_src($supplierDetails->data['pro_pic'], 'full');
            $url = urlencode( get_author_posts_url($getQueriedObject->ID) );
            $shareTitle = $getQueriedObject->first_name.' '.$getQueriedObject->last_name;
            $shareDescription = strip_tags($supplierDetails->data['bio']);
            $shareImage = ($supplierDetails->data['pro_pic_exists'] == TRUE) ? $getProPic[0] : 'https://via.placeholder.com/240x200';
        } else if(is_page('shared-deal-detail')){
            $dealID = base64_decode($_GET['deal']);
            $dealDetails = $FinalizeData->getDealDetails($dealID);
            $shareTitle = $dealDetails->data['deal_name'];
            $shareDescription = strip_tags($dealDetails->data['deal_description']);
            $url = urlencode( site_url().'/shared-deal-detail?deal='. $_GET['deal'] );
        } else {
            $getPostDetails = get_queried_object();

            if($getPostDetails->post_type == themeFramework::$theme_prefix . 'product'){
                $getAnnouncementDetails = $GeneralThemeObject->product_details(get_the_ID());
                /*echo "<pre>";
            print_r($getPostDetails->post_type);
            echo "</pre>";
            exit;*/
                $shareTitle = $getAnnouncementDetails->data['title'];
                $url = urlencode( get_permalink() );
                $getProductImg = wp_get_attachment_image_src(get_post_thumbnail_id($getAnnouncementDetails->data['ID']), 'product_listing_image');
                $shareImage = ($getProductImg[0]) ? $getProductImg[0] : 'https://via.placeholder.com/240x200';
                $shareDescription = strip_tags($getAnnouncementDetails->data['description']);
            } else{
                $getAnnouncementDetails = $AnnouncementObject->announcement_details(get_the_ID());
                $shareTitle = $getAnnouncementDetails->data['title'];
                $url = urlencode( get_permalink() );
                $getProductImg = wp_get_attachment_image_src($getAnnouncementDetails->data['announcement_single_image'], 'product_listing_image');
                $shareImage = ($getProductImg[0]) ? $getProductImg[0] : 'https://via.placeholder.com/240x200';
                $shareDescription = strip_tags($getAnnouncementDetails->data['content']);
            }
            
        }
        ?>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <meta property="og:title" content="<?php echo $shareTitle; ?>">
        <meta property="og:image" content="<?php echo $shareImage; ?>">
        <meta property="og:description" content="<?php echo ($shareDescription) ? $shareDescription : 'No description available for this.'; ?>">
        <meta property="og:url" content="<?php echo $url; ?>">
        <link rel="profile" href="https://gmpg.org/xfn/11">
        <!-- <link rel="stylesheet" href="https://code.jquery.com/mobile/1.1.1/jquery.mobile-1.1.1.min.css"/> -->
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>

        <!-- Basic Loader -->
        <div class="loader" style="display: none;">
            <div class="cssload-thecube">
                <div class="cssload-cube cssload-c1"></div>
                <div class="cssload-cube cssload-c2"></div>
                <div class="cssload-cube cssload-c4"></div>
                <div class="cssload-cube cssload-c3"></div>
            </div>
        </div>
        <!-- End of Basic Loader -->

        <div id="page" class="site">
            <a class="skip-link screen-reader-text" href="#content"><?php _e('Skip to content', 'twentyseventeen'); ?></a>

            <!-- Basic Modals -->
            <?php
            $GeneralThemeObject = new GeneralTheme();
            $WishListObject = new classWishList();
            $CartItemObject = new classCart();
            $RatingObject = new classReviewRating();
            $FinalizeObject = new classFinalize();
            $cartItems = $CartItemObject->countCartItems(get_current_user_id());
            $wishlistItems = $WishListObject->countWishListItems(get_current_user_id());
            $loggedInUserDetails = $GeneralThemeObject->user_details(get_current_user_id());
            $getDeals = $FinalizeObject->getDeals(get_current_user_id());
            if (is_array($getDeals) && count($getDeals) > 0):
                $i = 0;
                foreach ($getDeals as $eachDeal):
                    $hasReviewed = $RatingObject->hasUserReviewed(get_current_user_id(), $eachDeal->deal_id);
                    if ($eachDeal->deal_status == 1 && $hasReviewed == FALSE):
                        $i++;
                    endif;
                endforeach;
            endif;
            
            ?>
            <!-- End of Basic Modals -->
            <header id="masthead" class="site-header" role="banner">
                <div class="top-bar">
                    <div class="container">
                        <div class="row">

                            <!-- Welcome Text -->
                            <div class="col-sm-4 col-xs-12">
                                <ul class="links">
                                    <!--<li><?php _e('Welcome to our online store!', THEME_TEXTDOMAIN); ?></li>-->
                                </ul>
                            </div>
                            <!-- End of Welcome Text -->

                            <div class="col-sm-8 col-xs-12">
                                <ul class="text-right links">
                                    <?php //do_action('wpml_add_language_selector'); ?>
                                    <!-- Multilingual Section -->
                                    <!--                                    <li>
                                    <?php echo do_shortcode('[google-translator]'); ?>
                                                                        </li>-->
                                    <!-- End of Multilingual Section -->

                                    <!-- Cart& Wishlist Section -->
                                    <?php if ($loggedInUserDetails->data['role'] == 'supplier'): ?>
                                    <?php else: ?>
                                        <li><a href="<?php echo CART_PAGE; ?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i> <?php _e('Cart', "blank"); ?><?php echo (is_user_logged_in()) ? '(' . $cartItems . ')' : ''; ?></a></li>
                                        <li><a href="<?php echo MY_WISHLIST_PAGE; ?>"><i class="fa fa-heart" aria-hidden="true"></i> <?php _e('Wishlist', "blank"); ?><?php echo (is_user_logged_in()) ? '(' . $wishlistItems . ')' : ''; ?></a></li>
                                    <?php endif; ?>
                                    <!-- End of Cart& Wishlist Section -->

                                    <!-- User Account Section -->
                                    <li>
                                        <?php if (is_user_logged_in()): ?>
                                            <?php
                                            ?>
                                            <a href="<?php echo ($loggedInUserDetails->data['role'] == 'subscriber') ? MY_ACCOUNT_PAGE : SUPPLIER_DASHBOARD_PAGE; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="<?php echo ($loggedInUserDetails->data['role'] == 'supplier') ? 'fa fa-building' : 'fa fa-user'; ?>" aria-hidden="true"></i> <?php _e('My Account', THEME_TEXTDOMAIN); ?></a>
                                            <?php if ($i > 0): ?>
                                                <span class="exclaim-sign" data-toggle="tooltip" title="<?php _e('You have ' . $i . ' more deal(s) to score suppliers.', "blank"); ?>"><i style="margin: 0;" class="fa fa-exclamation" aria-hidden="true"></i></span>
                                            <?php endif; ?>
                                            <ul class="dropdown-menu">
                                                <li><a href="<?php echo ($loggedInUserDetails->data['role'] == 'subscriber') ? MY_ACCOUNT_PAGE : SUPPLIER_DASHBOARD_PAGE; ?>"><?php _e('My Account', "blank"); ?></a></li>
                                                <li><a href="<?php echo wp_logout_url(BASE_URL); ?>"><?php _e('Logout', THEME_TEXTDOMAIN); ?></a></li>
                                            </ul>
                                        <?php else: ?>
                                            <a href="#supplier_register_popup" data-toggle="modal"><i class="fa fa-building" aria-hidden="true"></i> <?php _e('Are you a supplier? Register now', "blank"); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <a href="#user_login_popup" data-toggle="modal"><i class="fa fa-user" aria-hidden="true"></i> <?php _e('Log In', "blank"); ?></a>
                                        <?php endif; ?>
                                    </li>
                                    <!-- End of User Account Section -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container">
                    <div class="row">
                        <!-- Website Logo -->
                        <div class="col-sm-5">
                            <?php the_custom_logo(); ?>
                        </div>
                        <!-- End of Website Logo -->

                        <!-- Header Search -->
                        <div class="col-sm-7">
                            <?php theme_template_part('home-search/home-search'); ?>
                        </div>
                        <!-- End of Header Search -->
                    </div>
                </div>
            </header><!-- #masthead -->

            <?php
            /*
             * If a regular post or page, and not the front page, show the featured image.
             * Using get_queried_object_id() here since the $post global may not be set before a call to the_post().
             */
            /* if (( is_single() || ( is_page() && !twentyseventeen_is_frontpage() ) ) && has_post_thumbnail(get_queried_object_id())) :
              echo '<div class="single-featured-image-header">';
              echo get_the_post_thumbnail(get_queried_object_id(), 'twentyseventeen-featured-image');
              echo '</div><!-- .single-featured-image-header -->';
              endif; */
            
            ?>

            <!-- State & City Selection Modal Showing -->
            <?php
            if (is_user_logged_in() && !isset($_COOKIE['andre_anonymous_city'])):

                $userDetails = $GeneralThemeObject->user_details();
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function ($) {
                        $('#choose_city_modal').modal({backdrop: 'static', keyboard: false});
                        $('#choose_city_modal').modal('show');
                    });
                </script>
                <?php
            elseif (!isset($_COOKIE['andre_anonymous_city'])):
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function ($) {
                        $('#choose_city_modal').modal({backdrop: 'static', keyboard: false});
                        $('#choose_city_modal').modal('show');
                    });
                </script>
                <?php
            endif;
            ?>

            <!-- End of State & City Selection Modal Showing -->
            <div class="site-content-contain">
                <div id="content" class="site-content">
