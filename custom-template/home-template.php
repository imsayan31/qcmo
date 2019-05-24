<?php
/**
 * Template Name: Home Template
 * 
 * 
 */
get_header();
$GeneralThemeObject = new GeneralTheme();
$WishlistObject = new classWishList();
$AnnouncementObject = new classAnnouncement();
$userCity = $GeneralThemeObject->getLandingCity();
$getCategories = get_terms(themeFramework::$theme_prefix . 'product_category', ['hide_empty' => false, 'parent' => 0]);
$get_show_banner_option = get_option('show_banner_option');
$getHomepageBannerImage = $GeneralThemeObject->getHomeBannerImage();
$getFeaturedProducts = $GeneralThemeObject->getProducts($userCity, 'featured');
$getMostSeenProducts = $GeneralThemeObject->getProducts($userCity, 'most_seen');
$userDetails = $GeneralThemeObject->user_details();
$getAnnouncements = $AnnouncementObject->getAllAnnouncements($userCity);

$getSupplierArgs = ['role' => 'supplier', 'meta_query' => [
        [
            'key' => '_city',
            'value' => $userCity,
            'compare' => '='
        ],
        [
            'key' => '_allow_where_to_buy',
            'value' => 1,
            'compare' => '='
        ]
        ], 'meta_key' => '_selected_plan', 'orderby' => 'meta_value_num', 'order' => 'ASC'];

$getSupplierForMaps = $GeneralThemeObject->getSupplierForMap($getSupplierArgs);

//$getProductDetails = $GeneralThemeObject->product_details(30);

/* Sliders */
$getQueriedObject = get_queried_object();
$currentDate = strtotime(date('d-m-Y'));
$currentTime = strtotime(date('H:i'));
$globalAdvTiming = get_option('advertisement_timing');
$getTopSlider = $GeneralThemeObject->getAdvertisements($userCity, 1, $getQueriedObject->post_name);
$getMiddleSlider = $GeneralThemeObject->getAdvertisements($userCity, 2, $getQueriedObject->post_name);
$getBottomSlider = $GeneralThemeObject->getAdvertisements($userCity, 3, $getQueriedObject->post_name);
 
/* Message for after successful donation */
if (isset($_GET['action']) && $_GET['action'] != ''):
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $.notify({message: 'Thank you, your donation has been made successfully.'}, {type: 'success', z_index: 20000, close: true, delay: 3000});
            window.location.href = '<?php echo BASE_URL; ?>';
        });
    </script>
    <?php
endif;
?>
<style>
        .author-image-title{font-size: 20px;
    text-align: center;}
    .author-image-title a{
            white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: inherit;
    }
        .author-image-title a img{
    display: inline-block !important;
    /* padding: 0px 0px; */
    margin: 0px 8px;
    border-radius: 50%;}
    
    .product-single .product-img .user-ann-icon.bronze{    background-position: center center;}
    </style>
<div class="container">

    <!-- Top Slider -->


    <?php
    if (is_array($getTopSlider) && count($getTopSlider) > 0):
        ?>
        <div class="viewer-slider" style="margin-bottom: 15px;">
            <div class="owl-carousel view-slider">
                <?php
                foreach ($getTopSlider as $eachSlider):
                    $advDetails = $GeneralThemeObject->advertisement_details($eachSlider->ID);
                    if ($currentDate >= strtotime($advDetails->data['adv_init_date']) && $currentDate < strtotime($advDetails->data['adv_final_date']) && $currentTime >= strtotime($advDetails->data['adv_init_time']) && $currentTime < strtotime($advDetails->data['adv_final_time'])):
                        ?>
                        <a href="<?php echo $advDetails->data['adv_url']; ?>" target="_blank" class="advert-click" data-adv="<?php echo base64_encode($advDetails->data['ID']) ?>">
                            <div class="item">
                                <img src="<?php echo ($advDetails->data['thumbnail_path']) ? $advDetails->data['thumbnail'] : 'https://via.placeholder.com/1140x150'; ?>" alt="" />
                                <div class="slide-caption">
                                    <div class="slide-caption-inner">
                                        <?php if ($advDetails->data['adv_enable_banner_text'] == 1): ?>
                                            <h2><?php echo $advDetails->data['title']; ?></h2>
                                        <?php endif; ?>
                                        <?php if ($advDetails->data['adv_enable_view_button'] == 1): ?>
                                            <div class="slide-btn"><span><?php _e('View', THEME_TEXTDOMAIN); ?></span></div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($advDetails->data['adv_view'] > 0 && $advDetails->data['adv_enable_view_counter'] == 1): ?>
                                        <div class="view-count-btn">
                                            <i class="fa fa-eye" aria-hidden="true"></i> <span><?php echo $advDetails->data['adv_view']; ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                        <?php
                    endif;
                endforeach;
                ?>
            </div>
        </div>
        <?php
    endif;
    ?>


    <!-- End of Top Slider -->


    <section class="block-row">
        <div class="row">
            <!-- Left Category Section  -->
            <div class="col-sm-4">
                <?php theme_template_part('category-sidebar/category-sidebar'); ?>
            </div>
            <!-- End of Left Category Section  -->

            <!-- Right Banner Section  -->
            <div class="col-sm-8">
                <div class="home-supplier-search">
                    <form method="get" id="supplier-location-frm" action="<?php echo SUPPLIER_LISTING_PAGE; ?>">
                        <div class="row">
                            <div class="col-md-9 col-sm-8 col-xs-9">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="supplier_location" name="supplier_location" value="" placeholder="<?php _e('Search your nearest supplier...', THEME_TEXTDOMAIN); ?>"/>
                                    <input type="hidden" class="form-control" id="supplier_location_loc" name="supplier_location_loc" value=""/>
                                    <input type="hidden" class="form-control" id="supplier_location_id" name="supplier_location_id" value=""/>
                                    <input type="hidden" class="form-control" id="supplier_radius" name="supplier_radius" value="50"/>
                                    
                                </div>
                               
                            </div>
                            <div class="col-md-3 col-sm-4 col-xs-3">
                                <div class="home-supplier-register supplier-search-btn">
                                    <a href="javascript:void(0);" class="supplier-where-to-buy"><?php _e('Where to buy', THEME_TEXTDOMAIN); ?></a>
                                </div>
                            </div>
                        </div>


                    </form>
                </div>
                <div class="home-slider">
                    <?php
                    if (is_front_page()) :
                        echo do_shortcode('[masterslider id="1"]');
                    endif;
                    ?>
                </div>
                <div class="cat-gallery">
                    <ul class="row">
                        <?php
                        $randomCategoryArr = [];
                        if (is_array($getCategories) && count($getCategories) > 0):
                            foreach ($getCategories as $val):
                                $randomCategoryArr[] = $val->term_id;
                            endforeach;
                        endif;
                        shuffle($randomCategoryArr);
                        if (is_array($randomCategoryArr) && count($randomCategoryArr) > 0) :
                            $catShowCount = 1;
                            foreach ($randomCategoryArr as $eachRandomVal) :
                                if ($catShowCount <= 4):
                                    $getCategoryDetails = get_term_by('id', $eachRandomVal, themeFramework::$theme_prefix . 'product_category');
                                    $getCategoryImg = get_field('product_category_image', themeFramework::$theme_prefix . 'product_category' . '_' . $getCategoryDetails->term_id);
                                    $getCategoryImgListing = wp_get_attachment_image_src($getCategoryImg['ID'], 'product_category_image');
                                    ?>
                                    <li class="col-sm-6">
                                        <a href="<?php echo get_term_link($getCategoryDetails); ?>">
                                            <img src="<?php echo ($getCategoryImgListing[0]) ? $getCategoryImgListing[0] : get_template_directory_uri() . '/assets/images/cat-img-4.jpg'; ?>" width="360" height="100" alt="" style="height: 100px;"/>
                                            <h3><?php echo $getCategoryDetails->name; ?></h3>
                                        </a>
                                    </li>
                                    <?php
                                    $catShowCount++;
                                endif;
                            endforeach;
                        else:
                            ?>
                            <li class="col-sm-6">
                                <a href="#">
                                    <img src="<?php echo get_template_directory_uri() . '/assets/images/cat-img-1.jpg'; ?>" alt="" />
                                    <h3>Lumber & composites</h3>
                                </a>
                            </li>
                            <li class="col-sm-6">
                                <a href="#">
                                    <img src="<?php echo get_template_directory_uri() . '/assets/images/cat-img-2.jpg'; ?>" alt="" />
                                    <h3>Insulation</h3>
                                </a>
                            </li>
                            <li class="col-sm-6">
                                <a href="#">
                                    <img src="<?php echo get_template_directory_uri() . '/assets/images/cat-img-3.jpg'; ?>" alt="" />
                                    <h3>Drywall</h3>
                                </a>
                            </li>
                            <li class="col-sm-6">
                                <a href="#">
                                    <img src="<?php echo get_template_directory_uri() . '/assets/images/cat-img-4.jpg'; ?>" alt="" />
                                    <h3>Moulding & millwork</h3>
                                </a>
                            </li>
                        <?php
                        endif;
                        ?>
                    </ul>
                </div>
            </div>
            <!-- End of Right Banner Section  -->
        </div>
    </section>

    <!-- Advertisement Section  -->
    <?php if ($get_show_banner_option == 1): ?>
        <section class="block-row">
            <div class="block-add">
                <img src="<?php echo $getHomepageBannerImage; ?>" alt="banner_image">
            </div>
        </section>
    <?php endif; ?>
    <!-- End of Advertisement Section  -->

    <!-- Middle Slider -->


    <?php
    if (is_array($getMiddleSlider) && count($getMiddleSlider) > 0):
        ?>
        <div class="viewer-slider" style="margin-top:15px; margin-bottom: 15px;">
            <div class="owl-carousel view-slider">
                <?php
                foreach ($getMiddleSlider as $eachSlider):
                    $advDetails = $GeneralThemeObject->advertisement_details($eachSlider->ID);
                    if ($currentDate >= strtotime($advDetails->data['adv_init_date']) && $currentDate < strtotime($advDetails->data['adv_final_date']) && $currentTime >= strtotime($advDetails->data['adv_init_time']) && $currentTime < strtotime($advDetails->data['adv_final_time'])):
                        ?>
                        <a href="<?php echo $advDetails->data['adv_url']; ?>" target="_blank" class="advert-click" data-adv="<?php echo base64_encode($advDetails->data['ID']) ?>">
                            <div class="item">
                                <img src="<?php echo ($advDetails->data['thumbnail_path']) ? $advDetails->data['thumbnail'] : 'https://via.placeholder.com/1140x150'; ?>" alt="" />
                                <div class="slide-caption">
                                    <div class="slide-caption-inner">
                                        <?php if ($advDetails->data['adv_enable_banner_text'] == 1): ?>
                                            <h2><?php echo $advDetails->data['title']; ?></h2>
                                        <?php endif; ?>
                                        <?php if ($advDetails->data['adv_enable_view_button'] == 1): ?>
                                            <div class="slide-btn"><span><?php _e('View', THEME_TEXTDOMAIN); ?></span></div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($advDetails->data['adv_view'] > 0 && $advDetails->data['adv_enable_view_counter'] == 1): ?>
                                        <div class="view-count-btn">
                                            <i class="fa fa-eye" aria-hidden="true"></i> <span><?php echo $advDetails->data['adv_view']; ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                        <?php
                    endif;
                endforeach;
                ?>
            </div>
        </div>
        <?php
    endif;
    ?>


    <!-- End of Middle Slider -->


    <!-- Supplier Registration Link -->
    <?php if (!is_user_logged_in()): ?>
        <section class="block-row">
            <div class="dashboard">
                <div class="right home-supplier-register"><?php _e('Are you a supplier?', THEME_TEXTDOMAIN); ?><a href="#supplier_register_popup" data-toggle="modal" class="supplier-reg-pop"><?php _e(' Register your company here.', THEME_TEXTDOMAIN); ?></a><?php _e(' Its 100% free.', THEME_TEXTDOMAIN); ?></div>
            </div>
        </section>
        
    <?php endif; ?>
    <!-- End of Supplier Registration Link -->

    <!-- Most Seen Product Section  -->

    <?php
    if (is_array($getMostSeenProducts) && count($getMostSeenProducts) > 0) :
        ?>
        <section class="block-row">
            <div class="section-heading">
                <h2><?php _e('MOST SEEN', THEME_TEXTDOMAIN); ?></h2>
                <a class="view-all-listings" href="<?php echo ALL_PRODUCTS_PAGE.'?product_feature=most_seen'; ?>" title="<?php _e('View All', THEME_TEXTDOMAIN); ?>"><i class="fa fa-globe" aria-hidden="true"></i></a>
            </div>
            <div class="owl-carousel featured-product-slider product-carousel list-product-carousal most-seen-carousal tstt">
                <?php
                foreach ($getMostSeenProducts as $eachFeaturedProduct) :
                    $getProductDetails = $GeneralThemeObject->product_details($eachFeaturedProduct->ID);
                    $getProductImg = wp_get_attachment_image_src($getProductDetails->data['thumbnail_id'], 'product_listing_image');
                    $isItemInWishlist = $WishlistObject->isItemInWishList($eachFeaturedProduct->ID, $userDetails->data['user_id'], $userCity);
                    ?>
                    <div>
                        <div class="product-single">
                            <div class="product-img">
                                <?php if ($userDetails->data['role'] == 'supplier'): ?>
                                <?php else: ?>
                                    <a href="javascript:void(0);" class="add-to-wishlist <?php echo ($isItemInWishlist == TRUE) ? 'wish-active' : ''; ?>" title="<?php _e('Add to wishlist', THEME_TEXTDOMAIN); ?>" data-pro="<?php echo base64_encode($eachFeaturedProduct->ID); ?>" data-type="product"><i class="fa fa-heart" aria-hidden="true"></i></a>
                                <?php endif; ?>
                                <img src="<?php echo ($getProductImg[0]) ? $getProductImg[0] : 'https://via.placeholder.com/240x200'; ?>" alt="<?php echo $eachFeaturedProduct->post_title; ?>">
                                <?php if ($getProductDetails->data['is_simple'] == FALSE): ?>
                                    <div class="bundle-ann-icon">
                                        <a href="javascript:void(0);"><img src="<?php echo ASSET_URL . '/images/bundle-products-icon.png'; ?>"></i></a>
                                    </div>
                                <?php endif; ?>
                                <div class="hover">
                                    <?php if ($userDetails->data['role'] == 'supplier'): ?>
                                    <?php else: ?>
                                        <a href="javascript:void(0);" class="add-to-cart" title="<?php _e('Add to cart', THEME_TEXTDOMAIN); ?>" data-pro="<?php echo base64_encode($eachFeaturedProduct->ID); ?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                                    <?php endif; ?>
                                    <a href="<?php echo get_permalink($eachFeaturedProduct->ID); ?>" class="view" title="<?php _e('View', THEME_TEXTDOMAIN); ?>"><i class="fa fa-search-plus" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            <h3><a href="<?php echo get_permalink($eachFeaturedProduct->ID); ?>"><?php echo $getProductDetails->data['title']; ?></a></h3>
                            <!--<div class="desc" style="min-height: 42px;"><?php echo (strlen($getProductDetails->data['description']) > 25) ? substr($getProductDetails->data['description'], 0, 25) . '..' : $getProductDetails->data['description']; ?></div>-->
                            <div class="price"><?php echo $getProductDetails->data['price']; ?><?php echo ($getProductDetails->data['unit']) ? '/' . $getProductDetails->data['unit'] : ''; ?></div>
                            <!--<div class="price"><?php echo $getProductDetails->data['view_counter']; ?></div>-->
                        </div>
                    </div>
                    <?php
                endforeach;
                ?>
            </div>
        </section>
        <?php
    endif;
    ?>

    <!-- End of Most Seen Product Section  -->

    <!-- Featured Product Section  -->

    <?php
    if (is_array($getFeaturedProducts) && count($getFeaturedProducts) > 0) :
        ?>
        <section class="block-row">
            <div class="section-heading">
                <h2><?php _e('FEATURED PRODUCTS', THEME_TEXTDOMAIN); ?></h2>
                <a class="view-all-listings" href="<?php echo ALL_PRODUCTS_PAGE.'?product_feature=featured'; ?>" title="<?php _e('View All', THEME_TEXTDOMAIN); ?>"><i class="fa fa-globe" aria-hidden="true"></i></a>
            </div>
            <div class="owl-carousel featured-product-slider product-carousel list-product-carousal">   
                <?php
                foreach ($getFeaturedProducts as $eachFeaturedProduct) :
                    $getProductDetails = $GeneralThemeObject->product_details($eachFeaturedProduct->ID);
                    $getProductImg = wp_get_attachment_image_src($getProductDetails->data['thumbnail_id'], 'product_listing_image');
                    $isItemInWishlist = $WishlistObject->isItemInWishList($eachFeaturedProduct->ID, $userDetails->data['user_id'], $userCity);
                    ?>
                    <div>
                        <div class="product-single">
                            <div class="product-img">
                                <?php if ($userDetails->data['role'] == 'supplier'): ?>
                                <?php else: ?>
                                    <a href="javascript:void(0);" class="add-to-wishlist <?php echo ($isItemInWishlist == TRUE) ? 'wish-active' : ''; ?>" title="<?php _e('Add to wishlist', THEME_TEXTDOMAIN); ?>" data-pro="<?php echo base64_encode($eachFeaturedProduct->ID); ?>" data-type="product"><i class="fa fa-heart" aria-hidden="true"></i></a>
                                <?php endif; ?>
                                <img src="<?php echo ($getProductImg[0]) ? $getProductImg[0] : 'https://via.placeholder.com/240x200'; ?>" alt="<?php echo $eachFeaturedProduct->post_title; ?>">
                                <?php if ($getProductDetails->data['is_simple'] == FALSE): ?>
                                    <div class="bundle-ann-icon">
                                        <a href="javascript:void(0);"><img src="<?php echo ASSET_URL . '/images/bundle-products-icon.png'; ?>"></i></a>
                                    </div>
                                <?php endif; ?>
                                <div class="hover">
                                    <?php if ($userDetails->data['role'] == 'supplier'): ?>
                                    <?php else: ?>
                                        <a href="javascript:void(0);" class="add-to-cart" title="<?php _e('Add to cart', THEME_TEXTDOMAIN); ?>" data-pro="<?php echo base64_encode($eachFeaturedProduct->ID); ?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                                    <?php endif; ?>
                                    <a href="<?php echo get_permalink($eachFeaturedProduct->ID); ?>" class="view" title="<?php _e('View', THEME_TEXTDOMAIN); ?>"><i class="fa fa-search-plus" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            <h3><a href="<?php echo get_permalink($eachFeaturedProduct->ID); ?>"><?php echo $getProductDetails->data['title']; ?></a></h3>
                            <!--<div class="desc" style="min-height: 42px;"><?php echo (strlen($getProductDetails->data['description']) > 25) ? substr($getProductDetails->data['description'], 0, 25) . '..' : $getProductDetails->data['description']; ?></div>-->
                            <div class="price"><?php echo $getProductDetails->data['price']; ?><?php echo ($getProductDetails->data['unit']) ? '/' . $getProductDetails->data['unit'] : ''; ?></div>
                        </div>
                    </div>
                    <?php
                endforeach;
                ?>
            </div>
        </section>
        <?php
    endif;
    ?>

    <!-- End of Featured Product Section  -->


    <!-- Announcement Section  -->
<section class="block-row">
            <div class="dashboard">
                <div class="right home-supplier-register">
                    <?php if(is_user_logged_in()): ?>
                    <a href="<?php echo ANNOUNCEMENT_MANAGEMENT_PAGE; ?>" class="supplier-reg-pop"><?php _e(' Click here to create your announce 100% free.', THEME_TEXTDOMAIN); ?></a>
                    <?php else: ?>
                    <a href="javascript:void(0);" class="supplier-reg-pop reg-modal-show"><?php _e(' Click here to create your announce 100% free.', THEME_TEXTDOMAIN); ?></a>
                    <?php endif; ?>
                </div>
            </div>
</section>
    <?php
    if (is_array($getAnnouncements) && count($getAnnouncements) > 0) :
        ?>
        <section class="block-row">
            <div class="section-heading">
                <h2><?php _e('ANNOUNCEMENTS', THEME_TEXTDOMAIN); ?></h2>
                <a class="view-all-listings" href="<?php echo ANNOUNCEMENT_LISTING_PAGE; ?>" title="<?php _e('View All', THEME_TEXTDOMAIN); ?>"><i class="fa fa-globe" aria-hidden="true"></i></a>
            </div>
            <!-- <div class="owl-carousel featured-product-slider product-carousel list-announcement-carousal"> -->
            <div class="owl-carousel featured-product-slider product-carousel list-announcement-carousal">
                <?php
                foreach ($getAnnouncements as $eachAnnouncement) :
                    $getAnnouncementDetails = $AnnouncementObject->announcement_details($eachAnnouncement);
                    $announcementAuthorDetails = $GeneralThemeObject->user_details($getAnnouncementDetails->data['author']);
                    $user_pro_pic = wp_get_attachment_image_src($announcementAuthorDetails->data['pro_pic'], 'full');
                    $getProductImg = wp_get_attachment_image_src($getAnnouncementDetails->data['announcement_single_image'], 'product_listing_image');
                    $dateDiff = $GeneralThemeObject->date_difference(date('Y-m-d'), $getAnnouncementDetails->data['start_date']);
                    $isItemInWishlist = $WishlistObject->isItemInWishList($eachAnnouncement, $userDetails->data['user_id'], $userCity);
                    ?>
                    <div>
                        <div class="product-single">
                            <?php if($dateDiff->days <= $AnnouncementObject->new_flag_announcement): ?>
                                <!-- <div class="new-flag"><span><?php _e('Novo', THEME_TEXTDOMAIN); ?></span></div> -->
                                <div class="novo-flag"><img src="<?php echo ASSET_URL.'/images/novo.png'; ?>" alt="Novo"/></div>
                            <?php endif; ?>
                            <div class="product-img">
                                <?php if ($userDetails->data['role'] == 'supplier'): ?>
                                <?php else: ?>
                                    <a href="javascript:void(0);" class="add-to-wishlist <?php echo ($isItemInWishlist == TRUE) ? 'wish-active' : ''; ?>" title="<?php _e('Add to wishlist', THEME_TEXTDOMAIN); ?>" data-pro="<?php echo base64_encode($eachAnnouncement); ?>" data-type="announcement"><i class="fa fa-heart" aria-hidden="true"></i></a>
                                <?php endif; ?>
                                <img src="<?php echo ($getProductImg[0]) ? $getProductImg[0] : 'https://via.placeholder.com/240x200'; ?>" alt="<?php echo $eachAnnouncement->post_title; ?>">
                                <div class="user-ann-icon bronze"></div>
                                <div class="hover">
                                    <a href="<?php echo get_permalink($eachAnnouncement); ?>" class="view" title="<?php _e('View', THEME_TEXTDOMAIN); ?>"><i class="fa fa-search-plus" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            <h3 style="height:auto;"><a href="<?php echo get_permalink($eachAnnouncement); ?>"><?php echo $GeneralThemeObject->limit_text($getAnnouncementDetails->data['title'], 5) ; ?></a></h3>
                            
                            <div class="author-image-title">
                               <a href="javascript:void(0);" title="<?php echo $announcementAuthorDetails->data['fname'] . ' ' . $announcementAuthorDetails->data['lname']; ?>"><img src="<?php echo ($announcementAuthorDetails->data['pro_pic_exists'] == TRUE) ? $user_pro_pic[0] : 'https://via.placeholder.com/50x50'; ?>" style="width:40px; height:40px;" /><?php echo $announcementAuthorDetails->data['fname'] . ' ' . $announcementAuthorDetails->data['lname']; ?></a>
                               
                            </div>
                            
<!--                            <div class="desc" style="min-height: 42px;"><?php echo (str_word_count($getAnnouncementDetails->data['content']) > 5) ? $GeneralThemeObject->limit_text($getAnnouncementDetails->data['content'], 5) . '..' : $getAnnouncementDetails->data['content']; ?></div>-->
                            <div class="price"><?php echo ($getAnnouncementDetails->data['announcement_price'] > 0) ? 'R$ ' . number_format($getAnnouncementDetails->data['announcement_price'], 2) : 'Grátis'; ?></div>
                        </div>
                    </div>
                    <?php
                endforeach;
                ?>
            </div>
        </section>
        <?php
    endif;
    ?>

    <!-- End of Announcement Section  -->


    <!-- Supplier Slider Section -->

    <?php
    if (is_array($getSupplierForMaps) && count($getSupplierForMaps) > 0) :
        ?>
        <section class="block-row">
            <div class="section-heading">
                <h2><?php _e('SPPLIERS AROUND YOU', THEME_TEXTDOMAIN); ?></h2>
                <a class="view-all-listings" href="<?php echo SUPPLIER_LISTING_PAGE.'?supplier_lists=1'; ?>" title="<?php _e('View All', THEME_TEXTDOMAIN); ?>"><i class="fa fa-globe" aria-hidden="true"></i></a>
            </div>
            <div class="owl-carousel featured-product-slider product-carousel supplier-carousal">
                <?php
                foreach ($getSupplierForMaps as $eachSupplierMap) :
                    $supplierDetails = $GeneralThemeObject->user_details($eachSupplierMap['user_id']);
                    $supplierPlan = $GeneralThemeObject->getMembershipPlanDetails($supplierDetails->data['selected_plan']);
                    if ($supplierPlan->data['name'] == 'gold'):
                        $imgClass = 'gold-class';
                    elseif ($supplierPlan->data['name'] == 'silver'):
                        $imgClass = 'silver-class';
                    else:
                        $imgClass = 'bronze-class';
                    endif;
                    $memberSince = date('Y-m-d', strtotime($supplierDetails->data['member_since']));
                    $dateDiff = $GeneralThemeObject->date_difference(date('Y-m-d'), $memberSince);
                    $isItemInWishlist = $WishlistObject->isItemInWishList($eachSupplierMap['user_id'], $userDetails->data['user_id'], $userCity);
                    ?>
                    <div>
                        <div class="product-single">
                            <?php if($dateDiff->days <= $GeneralThemeObject->new_flag_suppliers): ?>
                                <div class="novo-flag"><img src="<?php echo ASSET_URL.'/images/novo.png'; ?>" alt="Novo"/></div>
                            <?php endif; ?>
                            <div class="supp-thumb supp-cat-slide">
                                <?php if ($userDetails->data['role'] == 'supplier'): ?>
                                <?php else: ?>
                                    <a href="javascript:void(0);" class="add-to-wishlist <?php echo ($isItemInWishlist == TRUE) ? 'wish-active' : ''; ?>" title="<?php _e('Add to wishlist', THEME_TEXTDOMAIN); ?>" data-pro="<?php echo base64_encode($eachSupplierMap['user_id']); ?>" data-type="supplier"><i class="fa fa-heart" aria-hidden="true"></i></a>
                                <?php endif; ?>
                                <!--<a href="<?php echo $eachSupplierMap['where_to_buy']; ?>" target="_blank"><img class="<?php echo $imgClass; ?>" src="<?php echo $eachSupplierMap['thumbnail']; ?>"></a>-->
                                <a href="<?php echo get_author_posts_url($eachSupplierMap['user_id']); ?>"><img class="<?php echo $imgClass; ?>" src="<?php echo $eachSupplierMap['thumbnail']; ?>"></a>
                            </div>
                            <div class="supp-title"><a href="<?php echo get_author_posts_url($eachSupplierMap['user_id']); ?>"><?php echo $eachSupplierMap['cname']; ?></a></div>
                            <!--<div class="supp-title"><a href="<?php echo get_author_posts_url($eachSupplierMap['user_id']); ?>"><?php echo $eachSupplierMap['lname']; ?></a></div>-->
                            <div class="supp-carousal-rating"><?php echo $eachSupplierMap['rating']; ?></div>
                        </div>
                    </div>
                    <?php
                endforeach;
                ?>
            </div>
        </section>
        <?php
    endif;
    ?>

    <!--End of Supplier Slider Section -->

    <!-- Bottom Slider -->


    <?php
    if (is_array($getBottomSlider) && count($getBottomSlider) > 0):
        ?>
        <div class="viewer-slider">
            <div class="owl-carousel view-slider">                
                <?php
                foreach ($getBottomSlider as $eachSlider):
                    $advDetails = $GeneralThemeObject->advertisement_details($eachSlider->ID);
                    if ($currentDate >= strtotime($advDetails->data['adv_init_date']) && $currentDate < strtotime($advDetails->data['adv_final_date']) && $currentTime >= strtotime($advDetails->data['adv_init_time']) && $currentTime < strtotime($advDetails->data['adv_final_time'])):
                        ?>
                        <a href="<?php echo $advDetails->data['adv_url']; ?>" target="_blank" class="advert-click" data-adv="<?php echo base64_encode($advDetails->data['ID']) ?>">
                            <div class="item">
                                <img src="<?php echo ($advDetails->data['thumbnail_path']) ? $advDetails->data['thumbnail'] : 'https://via.placeholder.com/1140x150'; ?>" alt="" />
                                <div class="slide-caption">
                                    <div class="slide-caption-inner">
                                        <?php if ($advDetails->data['adv_enable_banner_text'] == 1): ?>
                                            <h2><?php echo $advDetails->data['title']; ?></h2>
                                        <?php endif; ?>
                                        <?php if ($advDetails->data['adv_enable_view_button'] == 1): ?>
                                            <div class="slide-btn"><span><?php _e('View', THEME_TEXTDOMAIN); ?></span></div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($advDetails->data['adv_view'] > 0 && $advDetails->data['adv_enable_view_counter'] == 1): ?>
                                        <div class="view-count-btn">
                                            <i class="fa fa-eye" aria-hidden="true"></i> <span><?php echo $advDetails->data['adv_view']; ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                        <?php
                    endif;
                endforeach;
                ?>
            </div>
        </div>
        <?php
    endif;
    ?>


    <!-- End of Bottom Slider -->


</div>
<!--
<script type="text/javascript">
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(sendLocation);
    } else {
        alert("Geolocation is not supported by this browser.");
    }

    function sendLocation(position) {
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
        geocoder.geocode({'latLng': latlng}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[1]) {
                    //find country name
                    for (var i = 0; i < results[0].address_components.length; i++) {
                        for (var b = 0; b < results[0].address_components[i].types.length; b++) {
                            //there are different types that might hold a city admin_area_lvl_1 usually does in come cases looking for sublocality type will be more appropriate
                            if (results[0].address_components[i].types[b] == "administrative_area_level_1") {
                                //this is the object you are looking for
                                city = results[0].address_components[i];
                                break;
                            }
                        }
                    }
                    jQuery('#supplier_location').val(results[0].formatted_address);
                    jQuery('#supplier_location_id').val(results[0].place_id);
                } else {
                    alert("No results found");
                }
            } else {
                alert("Geocoder failed due to: " + status);
            }
        });
        jQuery('#supplier_location_loc').val(position.coords.latitude + ',' + position.coords.longitude);
    }
</script>
-->
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.supplier-where-to-buy').on('click', function () {
            $('#supplier-location-frm').submit();
            
           
        });
    });
</script>



<script>
    jQuery(document).ready(function ($) {

        $('.supplier-carousal').owlCarousel({
            items: 4,
            loop: <?php echo (is_array($getSupplierForMaps) && count($getSupplierForMaps) > 4) ? 'true' : 'false'; ?>,
            margin: 20,
            responsiveClass: true,
            autoplay: true,
            autoplaySpeed: 300,
            nav: true,
            //pagination: true,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 4
                }
            }
        });

        $('.most-seen-carousal').owlCarousel({
            items: 4,
            loop: <?php echo (count($getMostSeenProducts) > 4) ? 'true' : 'false'; ?>,
            margin: 20,
            responsiveClass: true,
            autoplay: true,
            autoplaySpeed: 300,
            nav: true,
            //pagination: true,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 4
                }
            }
        });

        $('.list-product-carousal').owlCarousel({
            items: 4,
            loop: <?php echo (count($getFeaturedProducts) > 4) ? 'true' : 'false'; ?>,
            margin: 20,
            responsiveClass: true,
            autoplay: true,
            autoplaySpeed: 300,
            nav: true,
            //pagination: true,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 4
                }
            }
        });

       $('.list-announcement-carousal').owlCarousel({
            items: 4,
            loop: <?php echo (count($getAnnouncements) > 4) ? 'true' : 'false'; ?>,
            margin: 20,
            responsiveClass: true,
            autoplay: false,
            autoplaySpeed: 300,
            nav: true,
            //pagination: true,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 4
                }
            }
        });


        var owl1 = $('.view-slider');
        owl1.owlCarousel({
            loop: true,
            responsiveClass: true,
            dots: false,
            nav: true,
            autoplayTimeout:<?php echo ($globalAdvTiming) ? $globalAdvTiming : '5000'; ?>,
            autoplay: true,
            navText: [
                "<i class='fa fa-angle-left' style='padding:4px 12px;'></i>",
                "<i class='fa fa-angle-right' style='padding:4px 12px;'></i>"
            ],
            beforeInit: function (elem) {
                //Parameter elem pointing to $("#owl-demo")
                random(elem);
            },
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 1
                },
                1000: {
                    items: 1
                }
            }
        });
    });</script>
<?php
get_footer();
