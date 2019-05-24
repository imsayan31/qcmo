<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
get_header();
$getQueriedObject = get_queried_object();
$GeneralThemeObject = new GeneralTheme();
$WishlistObject = new classWishList();
$getLandingCity = $GeneralThemeObject->getLandingCity();

/* Sliders */
$currentDate = strtotime(date('d-m-Y'));
$currentTime = strtotime(date('H:i'));
$globalAdvTiming = get_option('advertisement_timing');
$getTopSlider = $GeneralThemeObject->getAdvertisements($getLandingCity, 1, 2, $getQueriedObject->term_id);
$getMiddleSlider = $GeneralThemeObject->getAdvertisements($getLandingCity, 2, 2, $getQueriedObject->term_id);
$getBottomSlider = $GeneralThemeObject->getAdvertisements($getLandingCity, 3, 2, $getQueriedObject->term_id);

/* Suppliers */
$getSupplierArgs = ['role' => 'supplier', 'meta_query' => [
        [
            'key' => '_city',
            'value' => $getLandingCity,
            'compare' => '='
        ],
        [
            'key' => '_supplier_categories',
            'value' => serialize(strval($getQueriedObject->term_id)),
            'compare' => 'LIKE'
        ],
        [
            'key' => '_allow_where_to_buy',
            'value' => 1,
            'compare' => '='
        ]
        ]];
$getSupplierForMaps = $GeneralThemeObject->getSupplierForMap($getSupplierArgs);
$userDetails = $GeneralThemeObject->user_details();
?>
<section class="block-row listing-product">
    <div class="container">
        <!-- Top Slider -->
        <?php
        if (is_array($getTopSlider) && count($getTopSlider) > 0):
            ?>
            <div class="viewer-slider" style="margin-bottom: 15px; margin-top: 0;">
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

        <div class="row">
            <!-- Product Category Sidebar -->
            <div class="col-sm-3">
                <?php theme_template_part('category-sidebar/category-sidebar'); ?>
            </div>
            <!-- End of Product Category Sidebar -->

            <!-- Product Listing -->
            <div class="col-sm-9">
                <?php theme_template_part('product-listing/product-listing'); ?>
            </div>
            <!-- End of Product Listing -->
        </div>


        <!-- Middle Slider -->
        <?php
        if (is_array($getMiddleSlider) && count($getMiddleSlider) > 0):
            ?>
            <div class="viewer-slider" style="margin-top:0; margin-bottom: 15px;">
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

        <!-- Supplier Slider Section -->

        <?php
        if (is_array($getSupplierForMaps) && count($getSupplierForMaps) > 0) :
            ?>
            <section class="block-row">
                <div class="section-heading">
                    <h2><?php _e('WHERE TO BUY', THEME_TEXTDOMAIN); ?></h2>
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
                        $isItemInWishlist = $WishlistObject->isItemInWishList($eachSupplierMap['user_id'], $userDetails->data['user_id'], $getLandingCity);
                        ?>
                        <div>
                            <div class="product-single">
                                <div class="supp-thumb supp-cat-slide">
                                    <?php if ($userDetails->data['role'] == 'supplier'): ?>
                                <?php else: ?>
                                    <a href="javascript:void(0);" class="add-to-wishlist <?php echo ($isItemInWishlist == TRUE) ? 'wish-active' : ''; ?>" title="<?php _e('Add to wishlist', THEME_TEXTDOMAIN); ?>" data-pro="<?php echo base64_encode($eachSupplierMap['user_id']); ?>" data-type="supplier"><i class="fa fa-heart" aria-hidden="true"></i></a>
                                <?php endif; ?>
                                    <!--<a href="<?php echo $eachSupplierMap['where_to_buy']; ?>" target="_blank"><img class="<?php echo $imgClass; ?>" src="<?php echo $eachSupplierMap['thumbnail']; ?>"></a>-->
                                    <a href="<?php echo get_author_posts_url($eachSupplierMap['user_id']); ?>"><img class="<?php echo $imgClass; ?>" src="<?php echo $eachSupplierMap['thumbnail']; ?>"></a>
                                </div>
                                <div class="supp-title"><a href="<?php echo get_author_posts_url($eachSupplierMap['user_id']); ?>"><?php echo $eachSupplierMap['cname']; ?></a></div>
                                <!--<div class="supp-title"><a href="<?php echo $eachSupplierMap['where_to_buy']; ?>" target="_blank"><?php echo $eachSupplierMap['lname']; ?></a></div>-->
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
            <div class="viewer-slider" style="margin-top:0;">
                <div class="owl-carousel view-slider">
                    <?php
                    foreach ($getBottomSlider as $eachSlider):
                        $advDetails = $GeneralThemeObject->advertisement_details($eachSlider->ID);
                        if ($currentDate >= strtotime($advDetails->data['adv_init_date']) && $currentDate <= strtotime($advDetails->data['adv_final_date']) && $currentTime >= strtotime($advDetails->data['adv_init_time']) && $currentTime < strtotime($advDetails->data['adv_final_time'])):
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
</section>

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

        var owl1 = $('.view-slider');
        owl1.owlCarousel({
//            items: 4,
            loop: true,
            responsiveClass: true,
            nav: true,
            dots: false,
            autoplayTimeout:<?php echo ($globalAdvTiming) ? $globalAdvTiming : '5000'; ?>,
            autoplay: true,
            //autoplaySpeed: <?php echo $globalAdvTiming; ?>,
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
    });
</script>

<?php
get_footer();
