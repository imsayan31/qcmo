<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */
get_header();
?>
<style>
    .author-img{margin-right: 10px; display: inline-block;}
    .author-img img{height: 75px;
    width: 75px;
    border-radius: 100%;}
    .company-link{width:100%;}
    .sup-dts{width: 30px !important;
    height: 30px;
    border-radius: 100%;
    padding: 3px 7px;
    margin-top: -4px;}
</style>
<?php
$getQueriedObject = get_queried_object();
$GeneralThemeObject = new GeneralTheme();
$WishlistObject = new classWishList();
$AnnouncementObject = new classAnnouncement();
$getLandingCity = $GeneralThemeObject->getLandingCity();
$announcement_details = $AnnouncementObject->announcement_details($getQueriedObject->ID);
$userDetails = $GeneralThemeObject->user_details();
if($announcement_details->data['announcement_plan']=='bronze'){$imagePlan = 'bronze-badge.png';}elseif($announcement_details->data['announcement_plan']=='gold'){$imagePlan = 'gold-badge.png';}elseif($announcement_details->data['announcement_plan']=='silver'){$imagePlan = 'silver-badge.png';}
$announcementAuthorDetails = $GeneralThemeObject->user_details($announcement_details->data['author']);
$authorImage = wp_get_attachment_url($announcementAuthorDetails->data['pro_pic']);
$getStateDetails = get_term_by('id', $announcement_details->data['announcement_state'], themeFramework::$theme_prefix . 'product_city');
$getCityDetails = get_term_by('id', $announcement_details->data['announcement_city'], themeFramework::$theme_prefix . 'product_city');
$announcementImages = $announcement_details->data['announcement_images'];
$getSupplierForMapsArgs = ['post_type' => themeFramework::$theme_prefix . 'announcement', 'include' => $getQueriedObject->ID];
$getSupplierForMaps = $AnnouncementObject->getAnnouncementForMap($getSupplierForMapsArgs);
$dateDiff = $GeneralThemeObject->date_difference(date('Y-m-d'), $announcement_details->data['start_date']);
$isItemInWishlist = $WishlistObject->isItemInWishList($getQueriedObject->ID, $userDetails->data['user_id'], $getLandingCity);
$infoWindowContent = NULL;
$infoWindowContentArr = [];
if (is_array($getSupplierForMaps) && count($getSupplierForMaps) > 0) {
    $infoContent = 0;
    foreach ($getSupplierForMaps as $eachSupplierMap) {
        $infoWindowContent .= '<div class="media">';
        $infoWindowContent .= '<div class="media-left"><img src="' . $eachSupplierMap['thumbnail'] . '" style="width:100px;height:100px;"></div>';
        $infoWindowContent .= '<div class="media-body">';
        $infoWindowContent .= '<div class="supp-title"><a href="javascript:void(0);">' . $eachSupplierMap['name'] . '</a></div>';
        $infoWindowContent .= '<div class="supp-addr">' . $eachSupplierMap['address'] . '</div>';
        $infoWindowContent .= '</div></div>';
        $infoWindowContentArr[$infoContent]['content'] = $infoWindowContent;
        $infoContent++;
    }
}
 $getAllAnnouncementCategory= $announcement_details->data['announcement_category'];
 
if (isset($_GET['search_by_announce_category']) && $_GET['search_by_announce_category'] != '') :
    $getProductsQuery['tax_query'] = [
        [
            'taxonomy' => themeFramework::$theme_prefix . 'product_category',
            'field' => 'slug',
            'terms' => $_GET['search_by_announce_category']
        ]
    ];
endif;



/* Sliders */
$currentDate = strtotime(date('d-m-Y'));
$currentTime = strtotime(date('H:i'));
$getTopSlider = $GeneralThemeObject->getAdvertisements($getLandingCity, 1, 4);
$getMiddleSlider = $GeneralThemeObject->getAdvertisements($getLandingCity, 2, 4);
$getBottomSlider = $GeneralThemeObject->getAdvertisements($getLandingCity, 3, 4);
?>
<!--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDnO0V9m09BUXB-JqwuuFno7efIs4FD5nM&libraries=places&callback=initMap" async defer></script>-->
<script type="text/javascript">
    function initMap() {
        var markersLoc = <?php echo json_encode($getSupplierForMaps); ?>;
        var infoWindowContent = <?php echo json_encode($infoWindowContentArr); ?>;
        var getLat = parseFloat(Number(markersLoc[0]['lat']).toFixed(7));
        var getLng = parseFloat(Number(markersLoc[0]['lng']).toFixed(7));

        //var myLatLng = {lat: markersLoc[0]['lat'], lng: markersLoc[0]['lng']};
        var myLatLng = {lat: getLat, lng: getLng};

        var map = new google.maps.Map(document.getElementById('map_canvas_announcement_details'), {
            zoom: 17,
            center: myLatLng
        });

        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            icon: markersLoc[0]['marker'],
            title: markersLoc[0]['name']
        });

        var infoWindow = new google.maps.InfoWindow()

        // Allow each marker to have an info window    
        google.maps.event.addListener(marker, 'click', (function (marker) {
            return function () {
                infoWindow.setContent(infoWindowContent[0]['content']);
                infoWindow.open(map, marker);
                setTimeout(function () {
                    infoWindow.close();
                }, 7000);
            };
        })(marker));
    }

    //google.maps.event.addDomListener(window, 'load', initialize);
</script>
<style type="text/css">
    #map_wrapper_announcement_details {
        height: 400px;
    }

    #map_canvas_announcement_details {
        width: 100%;
        height: 100%;
    }
</style>
<div class="profile-details-sec">
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

        <!-- Announcement Details Section -->
        <div class="row">
            <div class="profile-details">
                <div class="col-md-4 col-sm-4">

                    <section class="slider">
                        <div id="slider" class="flexslider">
                            <ul class="slides">
                                <?php
                                if (is_array($announcementImages) && count($announcementImages) > 0):
                                    foreach ($announcementImages as $eachImage):
                                        $sliderTopImage = wp_get_attachment_image_src($eachImage, 'product_listing_image');
                                        $sliderTopImagePath = get_attached_file($eachImage);
                                        ?>
                                        <li>
                                            <?php if($dateDiff->days <= $AnnouncementObject->new_flag_announcement): ?>
                                                <div class="novo-flag"><img src="<?php echo ASSET_URL.'/images/novo.png'; ?>" alt="Novo"/></div>
                                            <?php endif; ?>
                                            <img src="<?php echo ($sliderTopImagePath) ? $sliderTopImage[0] : get_template_directory_uri() . '/assets/images/slider-1.jpg'; ?>" />
                                        </li>  
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </ul>
                        </div>
                        <div id="carousel" class="flexslider thumb">
                            <ul class="slides">
                                <?php
                                if (is_array($announcementImages) && count($announcementImages) > 0):
                                    foreach ($announcementImages as $eachImage):
                                        $sliderBottomImage = wp_get_attachment_image_src($eachImage, 'full');
                                        $sliderBottomImagePath = get_attached_file($eachImage);
                                        ?>
                                        <li>
                                            <img src="<?php echo ($sliderBottomImagePath) ? $sliderBottomImage[0] : get_template_directory_uri() . '/assets/images/slider-1.jpg'; ?>" />
                                        </li>  
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </ul>
                        </div>
                    </section>
                     
                </div>
            </div>
            <div class="col-md-8 col-sm-8">
                <div class="profile-dtls">
                    <div class="desktop-rating-view">
                        <h2><span class="supp-name"><?php echo $announcement_details->data['title']; ?></span><span class="sup-dts <?php echo $announcement_details->data['announcement_plan'] ?>"><img src="<?php echo get_template_directory_uri() . '/assets/images/bull-horn/'. $announcement_details->data['announcement_plan'].'.png'; ?>" alt="" /></span></h2>
                        
                    </div>
                    <div class="mobile-rating-view">
                        <h2><span class="supp-name"><?php echo $announcement_details->data['title']; ?></span></h2>
                    </div>
                    <div class="profile-review">
                        <div class="profile-divider">
                            <div class="col-md-9 col-sm-12 left-pad">
                                <h3><span class="author-img"><img src="<?php echo $authorImage; ?>"/></span><?php echo $announcementAuthorDetails->data['fname'] . ' ' . $announcementAuthorDetails->data['lname']; ?></h3>
                                <div class="profile-contact-dtls">
                                    <ul>
                                        <li><i class="fa fa-envelope" aria-hidden="true"></i> <?php echo $announcementAuthorDetails->data['email']; ?></li>
                                        <li><i class="fa fa-phone" aria-hidden="true"></i> <?php echo ($announcementAuthorDetails->data['phone']) ? $announcementAuthorDetails->data['phone'] : 'Não fornecido'; ?></li>
                                        <li><i class="fa fa-location-arrow" aria-hidden="true"></i> <?php echo ($announcement_details->data['announcement_address']) ? $announcement_details->data['announcement_address'] : 'Nenhum endereço fornecido'; ?></li>
                                        <li><i class="fa fa-location-arrow" aria-hidden="true"></i> <?php echo $getCityDetails->name . ', ' . $getStateDetails->name; ?></li>
                                        <li><i class="fa fa-hashtag" aria-hidden="true"></i> <span><?php _e('Categorias: ', THEME_TEXTDOMAIN); ?></span>
                                        <?php 
                                        foreach($getAllAnnouncementCategory as $getEachAnnouncementCategory):
                                            $getAnnouncementCategoryDetails = get_term_by('slug', $getEachAnnouncementCategory , themeFramework::$theme_prefix . 'product_category'); 
                                            $aaa= '<a href="'.ANNOUNCEMENT_LISTING_PAGE .'?search_by_announce_category='.$getAnnouncementCategoryDetails->slug. '">' . $getAnnouncementCategoryDetails->name. ','.'</a>'; 
                                            echo $aaa;
                                        endforeach;
                                         ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-12 right-pad">
                                <a href="javascript:void(0);" class="company-link-price"> <?php echo ($announcement_details->data['announcement_price'] > 0) ? 'R$ '.number_format($announcement_details->data['announcement_price'], 2) : 'Grátis'; ?></a>
                                <div class="plane-badge" style="padding-top: 15px;"><img src="<?php echo ASSET_URL . '/images/'.$imagePlan; ?>"/></div>
                            </div>

                            <div class="clearfix"></div>
                        </div>
                        <p class="profile-txt"><?php echo ($announcement_details->data['content']) ? $announcement_details->data['content'] : 'Nenhuma descrição fornecida'; ?></p>
                        <!-- <a onclick="return ss_plugin_loadpopup_js(this);" rel="external nofollow" class="button-facebook" href="http://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fquantocustaminhaobra.com.br%2Fbeta%2Fauthor%2Ffoxlaminadoshotmail-com%2F" target="_blank">Compartilhar <i id="my_fb" class="fa fa-facebook" aria-hidden="true"></i><i class="fa fa-facebook" aria-hidden="true"></i></a> -->
                        <p style="margin-top: 20px;">
                            <div class="desktop-view">
                                <?php //echo do_shortcode("[wp_social_sharing social_options='facebook,twitter,googleplus,linkedin,pinterest,xing' facebook_text='Compartilhar' icon_order='f' show_icons='' before_button_text='' text_position='' social_image='']"); ?>
                                <?php if ($userDetails->data['role'] == 'supplier'): ?>
                                <?php else: ?>
                                    <a href="javascript:void(0);" class="add-to-wishlist <?php echo ($isItemInWishlist == TRUE) ? 'wish-active' : ''; ?>" title="<?php _e('Add to wishlist', THEME_TEXTDOMAIN); ?>" data-pro="<?php echo base64_encode($announcement_details->data['ID']); ?>" data-type="supplier"><?php _e('Add to wishlist', THEME_TEXTDOMAIN); ?> <i class="fa fa-heart" aria-hidden="true"></i></a>
                                <?php endif; ?>
                                <a onclick="return ss_plugin_loadpopup_js(this);" rel="external nofollow" class="facebook-share" style="background-color: #2b4170;color: #fff;" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink($announcement_details->data['ID']); ?>" target="_blank"><?php _e('Share with Facebook ', THEME_TEXTDOMAIN); ?> <i id="my_fb" class="fa fa-facebook" aria-hidden="true"></i><i class="fa fa-facebook" aria-hidden="true"></i></a>
                            </div>
                            <div class="mobile-view">
                                <?php if ($userDetails->data['role'] == 'supplier'): ?>
                                <?php else: ?>
                                    <a href="javascript:void(0);" class="add-to-wishlist <?php echo ($isItemInWishlist == TRUE) ? 'wish-active' : ''; ?>" title="<?php _e('Add to wishlist', THEME_TEXTDOMAIN); ?>" data-pro="<?php echo base64_encode($announcement_details->data['ID']); ?>" data-type="supplier" style="background: #f7c02e;color: #fff;"> <i class="fa fa-heart" aria-hidden="true"></i></a>
                                <?php endif; ?>
                                <a onclick="return ss_plugin_loadpopup_js(this);" rel="external nofollow" class="facebook-share" style="background-color: #2b4170;color: #fff;" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink($announcement_details->data['ID']); ?>" target="_blank"> <i id="my_fb" class="fa fa-facebook" aria-hidden="true"></i><i class="fa fa-facebook" aria-hidden="true"></i></a>
                            </div>
                            
                        </p>
                        
                        <?php //echo do_shortcode("[wp_social_sharing social_options='facebook,twitter,googleplus,linkedin,pinterest,xing' facebook_text='Compartilhar' icon_order='f' show_icons='' before_button_text='' text_position='' social_image='']"); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Announcement Details Section -->

    <!-- Middle Slider -->
    <?php
    if (is_array($getMiddleSlider) && count($getMiddleSlider) > 0):
        ?>
        <div class="viewer-slider" style="margin-top:0; margin-bottom: 15px;">
            <div class="owl-carousel view-slider">
                <?php
                foreach ($getMiddleSlider as $eachSlider):
                    $advDetails = $GeneralThemeObject->advertisement_details($eachSlider->ID);
                    /* echo $eachSlider->ID . ' - ' . date('H:i') . ' - ' . $advDetails->data['adv_init_time']. ' - ' . $advDetails->data['adv_final_time'];
                      echo '<br>'; */
                    if ($currentDate >= strtotime($advDetails->data['adv_init_date']) && $currentDate <= strtotime($advDetails->data['adv_final_date']) && $currentTime >= strtotime($advDetails->data['adv_init_time']) && $currentTime <= strtotime($advDetails->data['adv_final_time'])):
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

    <!-- Announcement Map -->
    <div class="container">
        <div class="row">
        <div class="profile-details">
            <div class="profile-location col-sm-12">
                <div id="map_wrapper_announcement_details">
                    <div id="map_canvas_announcement_details" class="mapping"></div>
                </div>
            </div>
        </div>
    </div>
    </div>
    
    <!-- End of Announcement Map -->

    <!-- Bottom Slider -->
    <?php
    if (is_array($getBottomSlider) && count($getBottomSlider) > 0):
        ?>
        <div class="viewer-slider" style="margin-top:25px;">
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
</div>
<script>
    jQuery(document).ready(function ($) {
        var owl1 = $('.view-slider');
        owl1.owlCarousel({
//            items: 4,
            loop: true,
            responsiveClass: true,
            nav: true,
            dots: false,
            //autoplayTimeout:<?php echo ($globalAdvTiming) ? $globalAdvTiming : '5000'; ?>,
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
