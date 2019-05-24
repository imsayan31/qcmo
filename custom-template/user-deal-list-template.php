<?php
/*
 * Template Name: User Deal List Template
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
$FinalizeData = new classFinalize();
$RatingObject = new classReviewRating();
if (isset($_GET['deal']) && $_GET['deal'] != ''):
    $dealID = base64_decode($_GET['deal']);
    $dealDetails = $FinalizeData->getDealDetails($dealID);
endif;
if (isset($_GET['user']) && $_GET['user'] != ''):
    $userID = base64_decode($_GET['user']);
endif;
$getDeals = $FinalizeData->getDeals($userDetails->data['user_id'], '', FALSE);
$dealProductDetails = $FinalizeData->getDealProductDetails($dealID, $userID);
$hasReviewed = $RatingObject->hasUserReviewed($userID, $dealID);

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
                <!-- Fetching User Deals Page -->
                <?php if (isset($_GET['deal']) && $_GET['deal'] != '' && isset($_GET['user']) && $_GET['user'] != ''): ?>
                    <div class="section-heading">
                        <h2 style="display: inline-block;"><?php _e('ID da oferta: ', THEME_TEXTDOMAIN);?><?php echo ($dealDetails->data['deal_id']); ?></h2>
                        <div class="pull-right desktop-view" style="margin-top: -4px;">
                                <?php if($dealDetails->data['locking_status'] == 1): ?>
                            <a href="javascript:void(0);" class="finalize-cart-items btn animated-bg-btn deal-unlock-click" data-deal_id="<?php echo base64_encode($dealDetails->data['deal_id']); ?>" title="<?php _e('Lock Your Deal', THEME_TEXTDOMAIN); ?>"><i class="fa fa-unlock-alt" aria-hidden="true"></i></a>
                            <a href="javascript:void(0);" class="finalize-cart-items btn animated-bg-btn deal-lock-click " data-deal_id="<?php echo base64_encode($dealDetails->data['deal_id']); ?>" title="<?php _e('Unlock Your Deal', THEME_TEXTDOMAIN); ?>"style="display:none"><i class="fa fa-lock" aria-hidden="true"></i></a>
                                <?php else: ?>
                            <a href="javascript:void(0);" class="finalize-cart-items btn animated-bg-btn deal-lock-click " data-deal_id="<?php echo base64_encode($dealDetails->data['deal_id']); ?>" title="<?php _e('Unlock Your Deal', THEME_TEXTDOMAIN); ?>"><i class="fa fa-lock" aria-hidden="true"></i></a>
                            <a href="javascript:void(0);" class="finalize-cart-items btn animated-bg-btn deal-unlock-click" data-deal_id="<?php echo base64_encode($dealDetails->data['deal_id']); ?>" title="<?php _e('Lock Your Deal', THEME_TEXTDOMAIN); ?>" style="display:none"><i class="fa fa-unlock-alt" aria-hidden="true"></i></a>
                                <?php endif; ?>
                            <a href="javascript:void(0);" class="finalize-cart-items btn animated-bg-btn deal-share-link-click" data-deal_id="<?php echo base64_encode($dealDetails->data['deal_id']); ?>" title="<?php _e('Get Shareable Link', THEME_TEXTDOMAIN); ?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
                            <!----------------------->
                           
                              <!--  <a  href="javascript:void(0);" class="finalize-cart-items btn animated-bg-btn provide-score" data-deal_id="<?php echo base64_encode($dealDetails->data['deal_id']); ?>" title="<?php _e('Click to provide supplier score', THEME_TEXTDOMAIN); ?>"><i class="fa fa-star" aria-hidden="true"></i><?php _e('Your Review', THEME_TEXTDOMAIN); ?></a>-->
                            
                           <!-- <?php if ($dealDetails->data['original_status'] != 4 && $hasReviewed == FALSE): ?>
                                <a style="background: #4d5967;" class="btn round cart provide-score" data-deal="<?php echo base64_encode($dealID); ?>" title="<?php _e('Click to provide supplier score', THEME_TEXTDOMAIN); ?>" href="javascript:void(0);"><i class="fa fa-star" aria-hidden="true"></i>&nbsp; <?php _e('Your Review', THEME_TEXTDOMAIN); ?></a>
                            <?php endif; ?>-->
                            <?php if ($dealDetails->data['original_status'] != 4): ?>
                                <a style="background: #4d5967; font-size: 12px;" class="btn round cart provide-score" data-deal="<?php echo base64_encode($dealID); ?>" title="<?php _e('Click to provide supplier score', THEME_TEXTDOMAIN); ?>" href="<?php echo CREATE_SUPPLIER_SCORE_PAGE . '?deal_id=' .base64_encode($dealID) ?>"
                                ><i class="fa fa-star" aria-hidden="true"></i></a>
                            <?php endif; ?>
                            <!--<a style="background: #4d5967;" class="btn round cart open-material-list-popup" data-deal_id="<?php echo base64_encode($dealID); ?>" href="javascript:void(0);" title="<?php _e('Material List', THEME_TEXTDOMAIN); ?>"><i class="fa fa-align-left" aria-hidden="true"></i>&nbsp; <?php _e('Material List', THEME_TEXTDOMAIN); ?></a>-->
                            <a style="background: #4d5967;font-size: 12px;" class="btn round cart" href="<?php echo MATERIAL_LIST_PAGE . '?selected_cat=9999&deal_id=' . $_GET['deal']; ?>"  target="_blank" title="<?php _e('Material List', THEME_TEXTDOMAIN); ?>"><i class="fa fa-align-left" aria-hidden="true"></i></a>
                            <a style="font-size: 12px;" href="javascript:void(0);" data-deal_name="<?php echo $dealDetails->data['deal_name']; ?>" data-deal_desc="<?php echo $dealDetails->data['deal_description']; ?>" class="finalize-cart-items update-finalize-deal btn animated-bg-btn"><i class="fa fa-wrench"></i></a>
                             <?php if(is_array($getDeals) && count($getDeals) > 0): ?>
                                <a style="background: #4d5967;font-size: 12px;" class="btn round cart erase-deal" href="javascript:void(0);" data-deal_id="<?php echo (isset($_GET['deal']) && $_GET['deal'] != '') ? $_GET['deal'] : ''; ?>" title="<?php _e('Erase Deals', THEME_TEXTDOMAIN); ?>"><i class="fa fa-eraser" aria-hidden="true"></i></a>
                             <?php endif; ?>
                        </div>
                        <div class="mobile-view" style="margin-top: 5px;text-align: left;">
                            <?php if ($dealDetails->data['original_status'] == 1 && $hasReviewed == FALSE): ?>
                                <a style="background: #4d5967;" class="btn round cart provide-score" data-deal="<?php echo base64_encode($dealID); ?>" title="<?php _e('Click to provide supplier score', THEME_TEXTDOMAIN); ?>" href="<?php echo CREATE_SUPPLIER_SCORE_PAGE . '?deal_id=' . base64_encode($dealDetails->data['deal_id']) ?>"><i class="fa fa-star" aria-hidden="true"></i></a>
                            <?php endif; ?>
                            <a style="background: #4d5967;" class="btn round cart" href="<?php echo MATERIAL_LIST_PAGE . '?selected_cat=9999&deal_id=' . $_GET['deal']; ?>"  target="_blank" title="<?php _e('Material List', THEME_TEXTDOMAIN); ?>"><i class="fa fa-align-left" aria-hidden="true"></i></a>
                            <a href="javascript:void(0);" data-deal_name="<?php echo $dealDetails->data['deal_name']; ?>" data-deal_desc="<?php echo $dealDetails->data['deal_description']; ?>" class="finalize-cart-items update-finalize-deal btn animated-bg-btn"><i class="fa fa-wrench"></i></a>
                            <?php if(is_array($getDeals) && count($getDeals) > 0): ?>
                                <a style="background: #4d5967;" class="btn round cart erase-deal" href="javascript:void(0);" data-deal_id="<?php echo (isset($_GET['deal']) && $_GET['deal'] != '') ? $_GET['deal'] : ''; ?>" title="<?php _e('Erase Deals', THEME_TEXTDOMAIN); ?>"><i class="fa fa-eraser" aria-hidden="true"></i></a>
                            <?php endif; ?>
                        </div>
                       
                            <!-- <div class="pull-right desktop-view" style="margin-top: -4px;margin-right: 3px;">
                                
                            </div>
                            <div class="mobile-view" style="margin-top: -28px;text-align: right;">
                                
                            </div> -->
                        

                    </div>
                    <?php theme_template_part('user-deals/user-deal-details'); ?>
                    <?php theme_template_part('user-deals/user-deal-details-mobiles'); ?>
                <?php else: ?>
                    <div class="section-heading">
                        <h2><?php _e('My Deals', THEME_TEXTDOMAIN); ?></h2>
                        <?php if(is_array($getDeals) && count($getDeals) > 0): ?>
                            <div class="pull-right desktop-view" style="margin-top: -26px;">
                                <a style="background: #4d5967;font-size: 12px;" class="btn round cart erase-deal" href="javascript:void(0);" data-deal_id="<?php echo (isset($_GET['deal']) && $_GET['deal'] != '') ? $_GET['deal'] : ''; ?>" title="<?php _e('Erase Deals', THEME_TEXTDOMAIN); ?>"><i class="fa fa-eraser" aria-hidden="true"></i>&nbsp; <?php _e('Erase Deals', THEME_TEXTDOMAIN); ?></a>
                            </div>
                            <div class="mobile-view" style="margin-top: -28px;text-align: right;">
                                <a style="background: #4d5967;" class="btn round cart erase-deal" href="javascript:void(0);" data-deal_id="<?php echo (isset($_GET['deal']) && $_GET['deal'] != '') ? $_GET['deal'] : ''; ?>" title="<?php _e('Erase Deals', THEME_TEXTDOMAIN); ?>"><i class="fa fa-eraser" aria-hidden="true"></i></a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php theme_template_part('user-deals/user-deals'); ?>
                    <?php theme_template_part('user-deals/user-deals-mobiles'); ?>
                <?php endif; ?>
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
