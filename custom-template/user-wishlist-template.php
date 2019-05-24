<?php
/*
 * Template Name: User Wishlist Template
 *
 */
get_header();
$GeneralThemeObject = new GeneralTheme();
$GeneralThemeObject->authentic();
$userDetails = $GeneralThemeObject->user_details();
if ($userDetails->data['role'] == 'supplier'):
    wp_redirect(SUPPLIER_DASHBOARD_PAGE);
    exit;
endif;

/* Sliders */
$currentDate = strtotime(date('d-m-Y'));
$currentTime = strtotime(date('H:i'));
$getLandingCity = $GeneralThemeObject->getLandingCity();
$getQueriedObject = get_queried_object();
$getTopSlider = $GeneralThemeObject->getAdvertisements($getLandingCity, 1, $getQueriedObject->post_name);
$getMiddleSlider = $GeneralThemeObject->getAdvertisements($getLandingCity, 2, $getQueriedObject->post_name);
$getBottomSlider = $GeneralThemeObject->getAdvertisements($getLandingCity, 3, $getQueriedObject->post_name);
?>
<section class="dashboard block-row">
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
            <div class="col-md-3 col-sm-4">
                <!-- Fetching Account Side bar -->
                <?php theme_template_part('account-sidebar/account_sidebar'); ?>
            </div>
            <div class="col-md-9 col-sm-8">
                <div class="section-heading">
                    <h2><?php _e('Wishlist', THEME_TEXTDOMAIN); ?></h2>
                </div>
                <!-- Fetching User Wishlist Page -->
                <?php theme_template_part('user-wishlists/user-wishlists'); ?>
                <?php theme_template_part('user-wishlists/user-wishlists-mobile'); ?>
                <!-- End of Fetching User Wishlist Page -->
            </div>
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
