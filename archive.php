<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */
get_header();
$GeneralThemeObject = new GeneralTheme();
$RatingObject = new classReviewRating();
$WishlistObject = new classWishList();
$getSupplierInfo = get_queried_object();
$getLandingCity = $GeneralThemeObject->getLandingCity();
$supplierDetails = $GeneralThemeObject->user_details($getSupplierInfo->ID);
$getProPic = wp_get_attachment_image_src($supplierDetails->data['pro_pic'], 'full');
$getBuisnessCategories = $supplierDetails->data['buisness_categories'];
$totalRating = $RatingObject->getAverageRating($getSupplierInfo->ID);
$getRatingHTML = $RatingObject->getRatingHTML($totalRating, FALSE);
$getSupplierArgs = ['role' => 'supplier', 'include' => [$getSupplierInfo->ID]];
$getSupplierForMaps = $GeneralThemeObject->getSupplierForMap($getSupplierArgs);
$supplierPlanDetails = $GeneralThemeObject->getMembershipPlanDetails($supplierDetails->data['selected_plan']);
$priceRating = $RatingObject->getAverageRating($getSupplierInfo->ID, 'price');
$attendenceRating = $RatingObject->getAverageRating($getSupplierInfo->ID, 'attendence');
$deliveryRating = $RatingObject->getAverageRating($getSupplierInfo->ID, 'delivery');
$getPriceRatingHTML = $RatingObject->getRatingHTML($priceRating, FALSE);
$getAttendenceRatingHTML = $RatingObject->getRatingHTML($attendenceRating, FALSE);
$getDeliveryRatingHTML = $RatingObject->getRatingHTML($deliveryRating, FALSE);
$reviewQueryString = " AND `supplier_id`=" . $getSupplierInfo->ID . "";
$getAllReviewRatings = $RatingObject->getAllReviewRatings($reviewQueryString);
$userDetails = $GeneralThemeObject->user_details();
$isItemInWishlist = $WishlistObject->isItemInWishList($getSupplierInfo->ID, $userDetails->data['user_id'], $getLandingCity);
$infoWindowContent = NULL;
$infoWindowContentArr = [];
if (is_array($getSupplierForMaps) && count($getSupplierForMaps) > 0) {
    $infoContent = 0;
    foreach ($getSupplierForMaps as $eachSupplierMap) {
        $infoWindowContent .= '<div class="media">';
        $infoWindowContent .= '<div class="media-left"><a href="' . $eachSupplierMap['where_to_buy'] . '" target="_blank"><img src="' . $eachSupplierMap['thumbnail'] . '" style="width:100px;height:100px;"></a></div>';
        $infoWindowContent .= '<div class="media-body">';
        $infoWindowContent .= '<div class="supp-title"><a href="' . $eachSupplierMap['where_to_buy'] . '" target="_blank">' . $eachSupplierMap['cname'] . '</a></div>';
        $infoWindowContent .= '<div class="supp-rating">' . $eachSupplierMap['rating'] . '</div>';
        $infoWindowContent .= '<div class="supp-addr">' . $eachSupplierMap['address'] . '</div>';
        $infoWindowContent .= '</div></div>';
        $infoWindowContentArr[$infoContent]['content'] = $infoWindowContent;
        $infoContent++;
    }
}
$getStateDetails = get_term_by('id', $supplierDetails->data['state'], themeFramework::$theme_prefix . 'product_city');
$getCityDetails = get_term_by('id', $supplierDetails->data['city'], themeFramework::$theme_prefix . 'product_city');
        // echo "<pre>";
        // print_r($getBuisnessCategories);
        // echo "</pre>";
if (is_array($getBuisnessCategories) && count($getBuisnessCategories) > 0):
    $buisnessCat = [];
    foreach ($getBuisnessCategories as $eachBusinessCat):
        $getCatBy = get_term_by('id', $eachBusinessCat, themeFramework::$theme_prefix . 'product_category');
        /*echo "<pre>";
        print_r($getCatBy->term_id.' - '.get_term_link($getCatBy->term_id));
        echo "</pre>";
        echo "<pre>";
        print_r($getCatBy->term_id);
        echo "</pre>";*/
        if($eachBusinessCat->parent == 0 && $getCatBy->term_id):
            $buisnessCat[] = '<a href="' . get_term_link($getCatBy->term_id) . '">' . $getCatBy->name . '</a>';
        endif;
    endforeach;
    /*$ancestors = get_ancestors($getBuisnessCategories[0], themeFramework::$theme_prefix . 'product_category');
    if(count($ancestors) > 0 ) {
        $getCatBy = get_term_by('id', $ancestors[0], themeFramework::$theme_prefix . 'product_category');
    } else {
        $getCatBy = get_term_by('id', $getBuisnessCategories[0], themeFramework::$theme_prefix . 'product_category');
    }*/
    $joinedCat = join(', ', $buisnessCat);   
else:
    $joinedCat = 'Nothing';
endif;


/* Sliders */
$currentDate = strtotime(date('d-m-Y'));
$currentTime = strtotime(date('H:i'));
$getTopSlider = $GeneralThemeObject->getAdvertisements($getLandingCity, 1, 4);
$getMiddleSlider = $GeneralThemeObject->getAdvertisements($getLandingCity, 2, 4);
$getBottomSlider = $GeneralThemeObject->getAdvertisements($getLandingCity, 3, 4);

/* New Flag Settings */
$memberSince = date('Y-m-d', strtotime($supplierDetails->data['member_since']));
$dateDiff = $GeneralThemeObject->date_difference(date('Y-m-d'), $memberSince);          
?>
<!--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDnO0V9m09BUXB-JqwuuFno7efIs4FD5nM&libraries=places&callback=initMap" async defer></script>-->
<script type="text/javascript">
    function initMap() {
        var markersLoc = <?php echo json_encode($getSupplierForMaps); ?>;
        var infoWindowContent = <?php echo json_encode($infoWindowContentArr); ?>;
        var getLat = parseFloat(Number(markersLoc[0]['lat']).toFixed(7));
        var getLng = parseFloat(Number(markersLoc[0]['lng']).toFixed(7));
        
        //var myLatLng = {lat: markersLoc[0]['lat'], lng: markersLoc[0]['lng']};
        var myLatLng = {lat: getLat, lng: getLng };

        var map = new google.maps.Map(document.getElementById('map_canvas_profile'), {
            zoom: 16,
            center: myLatLng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
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
    #map_wrapper_profile {
        height: 400px;
    }

    #map_canvas_profile {
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

        <div class="row">
            <div class="profile-details">
                <div class="col-md-3 col-sm-3">
                    <?php if($dateDiff->days <= $GeneralThemeObject->new_flag_suppliers): ?>
                            <div class="novo-flag"><img src="<?php echo ASSET_URL.'/images/novo.png'; ?>" alt="Novo"/></div>
                        <?php endif; ?>
                    <div class="profile-img">
                        <img src="<?php echo ($supplierDetails->data['pro_pic_exists'] == TRUE) ? $getProPic[0] : 'https://via.placeholder.com/240x200' ?>" width="200" height="200" id="user_logo" alt="User Profile Picture"/>
                        <?php if ($supplierPlanDetails->data['name'] == 'gold'): ?>
                            <span class="sup-dts-gold"><img src="<?php echo ($supplierPlanDetails->data['thumbnail_path']) ? $supplierPlanDetails->data['thumbnail'] : get_template_directory_uri() . '/assets/images/gold-badge.png'; ?>" alt="" /></span>
                        <?php elseif ($supplierPlanDetails->data['name'] == 'silver'): ?>
                            <span class="sup-dts-silver"><img src="<?php echo ($supplierPlanDetails->data['thumbnail_path']) ? $supplierPlanDetails->data['thumbnail'] : get_template_directory_uri() . '/assets/images/silver-badge.png'; ?>" alt="" /></span>
                        <?php elseif ($supplierPlanDetails->data['name'] == 'bronze'): ?>
                            <span class="sup-dts-bronze"><img src="<?php echo ($supplierPlanDetails->data['thumbnail_path']) ? $supplierPlanDetails->data['thumbnail'] : get_template_directory_uri() . '/assets/images/bronze-badge.png'; ?>" alt="" /></span>
                        <?php endif; ?>
                        </div>
                </div>
                <div class="col-md-9 col-sm-9">
                    <div class="profile-dtls">
                        <div class="desktop-rating-view">
                            <h2>
                                <span class="supp-name" ><?php _e('' . $supplierDetails->data['fname'], THEME_TEXTDOMAIN); ?></span><span class="supp-rating" style="float:right;"><?php echo $getRatingHTML; ?></span>
                            </h2>
                        </div>
                        <div class="mobile-rating-view">
                            <h2>
                                <span class="supp-name"><?php _e('' . $supplierDetails->data['fname'], THEME_TEXTDOMAIN); ?></span><br>
                                <span class="supp-rating"><?php echo $getRatingHTML; ?></span>
                            </h2>
                        </div>
                        <div class="profile-review">
                            <div class="profile-divider">
                                <div class="col-md-9 col-sm-12 left-pad">
                                    <h3><?php echo $supplierDetails->data['lname']; ?></h3>
                                    <div class="user-rating">
                                        <!--<div class="supp-rating"><?php echo $getRatingHTML; ?></div>-->
                                        <div class="supp-rating"><?php _e('Price: ', THEME_TEXTDOMAIN); ?><?php echo $getPriceRatingHTML; ?></div>
                                        <div class="supp-rating"><?php _e('Attendence: ', THEME_TEXTDOMAIN); ?><?php echo $getAttendenceRatingHTML; ?></div>
                                        <div class="supp-rating"><?php _e('Delivery: ', THEME_TEXTDOMAIN); ?><?php echo $getDeliveryRatingHTML; ?></div>
                                    </div>
                                    <div class="profile-contact-dtls">
                                        <ul>
                                            <li><i class="fa fa-envelope" aria-hidden="true"></i> <?php echo $supplierDetails->data['email']; ?></li>
                                            <li><i class="fa fa-phone" aria-hidden="true"></i> <?php echo ($supplierDetails->data['phone']) ? $supplierDetails->data['phone'] : 'Não informado.'; ?></li>
                                            <li><i class="fa fa-location-arrow" aria-hidden="true"></i> <?php echo ($supplierDetails->data['user_address']) ? $supplierDetails->data['user_address'] : 'Endereço no informado.'; ?></li>
                                            <li><i class="fa fa-location-arrow" aria-hidden="true"></i> <?php echo $getCityDetails->name . ', ' . $getStateDetails->name; ?></li>
                                            <li><i class="fa fa-hashtag" aria-hidden="true"></i> <span><?php _e('Supplied Categories: ', THEME_TEXTDOMAIN); ?></span><?php echo $joinedCat; ?></li>
                                        </ul>
                                        <div class="desktop-view">
                                            <div class="supplier-same-row">
                                                <?php if ($userDetails->data['role'] == 'supplier'): ?>
                                                <?php else: ?>
                                                    <a href="javascript:void(0);" class="add-to-wishlist <?php echo ($isItemInWishlist == TRUE) ? 'wish-active' : ''; ?>" title="<?php _e('Add to wishlist', THEME_TEXTDOMAIN); ?>" data-pro="<?php echo base64_encode($getSupplierInfo->ID); ?>" data-type="supplier"><?php _e('Add to wishlist', THEME_TEXTDOMAIN); ?><i class="fa fa-heart" aria-hidden="true"></i></a>
                                                <?php endif; ?>
                                            </div>
                                        	<div class="supplier-same-row">
	                                    		<?php //echo do_shortcode("[wp_social_sharing social_options='facebook,twitter,googleplus,linkedin,pinterest,xing' facebook_text='Compartilhar' icon_order='f' show_icons='' before_button_text='' text_position='' social_image='']"); ?>
	                                    		<a onclick="return ss_plugin_loadpopup_js(this);" rel="external nofollow" class="" style="background-color: #2b4170;color: #fff;" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo get_author_posts_url($supplierDetails->data['user_id']); ?>" target="_blank"><?php _e('Share on Facebook ', THEME_TEXTDOMAIN); ?> <i id="my_fb" class="fa fa-facebook" aria-hidden="true"></i><i class="fa fa-facebook" aria-hidden="true"></i></a>
	                                    	</div>
	                                    	<div class="supplier-same-row">
	                                    		<a href="<?php echo $supplierDetails->data['where_to_buy_address']; ?>" class="company-link" target="_blank"> <?php _e('Site', THEME_TEXTDOMAIN); ?> <i class="fa fa-globe"></i></a>
	                                    	</div>
                                        </div>
                                        <div class="mobile-view">
                                            <div class="supplier-same-row">
                                                <?php if ($userDetails->data['role'] == 'supplier'): ?>
                                                <?php else: ?>
                                                    <a href="javascript:void(0);" class="add-to-wishlist <?php echo ($isItemInWishlist == TRUE) ? 'wish-active' : ''; ?>" title="<?php _e('Add to wishlist', THEME_TEXTDOMAIN); ?>" data-pro="<?php echo base64_encode($getSupplierInfo->ID); ?>" data-type="supplier"><i class="fa fa-heart" aria-hidden="true"></i></a>
                                                <?php endif; ?>
                                            </div>
                                        	<div class="supplier-same-row">
                                        		<!-- <span><?php _e('Share with Facebook: ', THEME_TEXTDOMAIN); ?></span> -->
	                                    		<?php //echo do_shortcode("[wp_social_sharing social_options='facebook,twitter,googleplus,linkedin,pinterest,xing' facebook_text='Compartilhar' icon_order='f' show_icons='' before_button_text='' text_position='' social_image='']"); ?>
	                                    		<a onclick="return ss_plugin_loadpopup_js(this);" rel="external nofollow" class="" style="background-color: #2b4170;color: #fff;" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo get_author_posts_url($supplierDetails->data['user_id']); ?>" target="_blank"> <i id="my_fb" class="fa fa-facebook" aria-hidden="true"></i><i class="fa fa-facebook" aria-hidden="true"></i></a>
	                                    	</div>
	                                    	<div class="supplier-same-row">
	                                    		<a href="<?php echo $supplierDetails->data['where_to_buy_address']; ?>" class="company-link" target="_blank">  <i class="fa fa-globe"></i></a>
	                                    	</div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <?php if ($supplierDetails->data['allow_where_to_buy'] == 1): ?>
                                    <div class="col-md-3 col-sm-12 right-pad">
                                        <div class="med">
                                            <?php if ($supplierPlanDetails->data['name'] == 'gold'): ?>
                            <span class="sup-dts-gold"><img src="<?php echo ($supplierPlanDetails->data['thumbnail_path']) ? $supplierPlanDetails->data['thumbnail'] : get_template_directory_uri() . '/assets/images/gold-badge.png'; ?>" alt="" /></span>
                        <?php elseif ($supplierPlanDetails->data['name'] == 'silver'): ?>
                            <span class="sup-dts-silver"><img src="<?php echo ($supplierPlanDetails->data['thumbnail_path']) ? $supplierPlanDetails->data['thumbnail'] : get_template_directory_uri() . '/assets/images/silver-badge.png'; ?>" alt="" /></span>
                        <?php elseif ($supplierPlanDetails->data['name'] == 'bronze'): ?>
                            <span class="sup-dts-bronze"><img src="<?php echo ($supplierPlanDetails->data['thumbnail_path']) ? $supplierPlanDetails->data['thumbnail'] : get_template_directory_uri() . '/assets/images/bronze-badge.png'; ?>" alt="" /></span>
                        <?php endif; ?>
                                            </div>
                                        
                                    </div>
                                <?php endif; ?>
                                <div class="clearfix"></div>
                            </div>
                            <p class="profile-txt"><?php echo ($supplierDetails->data['bio']) ? $supplierDetails->data['bio'] : ''; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Review Comments -->
        <br>
        <div class="profile-dtls">
            <h2><?php _e('Customer Reviews', THEME_TEXTDOMAIN); ?></h2>
            <div class="profile-review">
                <div class="profile-divider">
                    <?php
                    if (is_array($getAllReviewRatings) && count($getAllReviewRatings) > 0):
                        foreach ($getAllReviewRatings as $eachReviewRating):
                            $customerDetails = $GeneralThemeObject->user_details($eachReviewRating->user_id);
                            $user_pro_pic = wp_get_attachment_image_src($customerDetails->data['pro_pic'], 'full');
                            $userPriceRating = $RatingObject->getRatingHTML($eachReviewRating->price_rate);
                            $userAttendenceRating = $RatingObject->getRatingHTML($eachReviewRating->attendence_rate);
                            $userDeliveryRating = $RatingObject->getRatingHTML($eachReviewRating->delivery_rate);
                            $getTimeElapsedString = $RatingObject->getTimeElapsedString($eachReviewRating->date);
                            ?>
                    <div class="col-sm-2"><img src="<?php echo ($customerDetails->data['pro_pic_exists'] == TRUE) ? $user_pro_pic[0] : 'https://via.placeholder.com/100x100'; ?>" width="100" height="100" /></div>
                            <div class="col-sm-10">
                                <h3><?php echo $customerDetails->data['fname'] . ' ' . $customerDetails->data['lname']; ?></h3>
                                <div class="user-rating">
                                    <div class="supp-rating"><?php _e('Price: ', THEME_TEXTDOMAIN); ?><?php echo $userPriceRating; ?></div>
                                    <div class="supp-rating"><?php _e('Attendence: ', THEME_TEXTDOMAIN); ?><?php echo $userAttendenceRating; ?></div>
                                    <div class="supp-rating"><?php _e('Delivery: ', THEME_TEXTDOMAIN); ?><?php echo $userDeliveryRating; ?></div>
                                    <div class="supp-rating"><?php echo ($eachReviewRating->user_comments) ? $eachReviewRating->user_comments : 'Nenhum comentário.'; ?></div>
                                    <div class="supp-rating"><i><?php echo $getTimeElapsedString; ?></i></div>
                                </div>
                            </div>
                            <?php
                        endforeach;
                    else:
                     ?>
                    <div class="alert alert-danger"><?php _e("Nenhuma avaliação até o momento.", THEME_TEXTDOMAIN);?></div>
                    <?php 
                    endif;
                    ?>
                    <div class="clear"></div>
                </div>
            </div>

        </div>
        <!-- End of User Review Comments -->

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

        <div class="row">
            <div class="profile-details">
                <div class="profile-location col-sm-12">
                    <div id="map_wrapper_profile">
                        <div id="map_canvas_profile" class="mapping"></div>
                    </div>
                </div>
            </div>
        </div>

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
<style>
    .med img {
    width: 35px;
    margin-right: 15px;
    margin-top: 10px;
}
</style>
<?php
get_footer();
