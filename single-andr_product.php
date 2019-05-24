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
<?php
$getQueriedObject = get_queried_object();
$GeneralThemeObject = new GeneralTheme();
$WishlistObject = new classWishList();
$RatingObject = new classReviewRating();
$CartObject = new classCart();
$getLandingCity = $GeneralThemeObject->getLandingCity();
$userDetails = $GeneralThemeObject->user_details();
$productDetails = $GeneralThemeObject->product_details($getQueriedObject->ID);
$productImg = wp_get_attachment_image_src($productDetails->data['thumbnail_id'], 'product_details_image');
$isItemInWishlist = $WishlistObject->isItemInWishList($getQueriedObject->ID, $userDetails->data['user_id'], $getLandingCity);
$getProductAttributes = $productDetails->data['product_cats'];
$getAttributeQuantity = $productDetails->data['product_cat_quantity'];
$getCartItem = $CartObject->getCartItems($userDetails->data['user_id'], $productDetails->data['ID'], '', $getLandingCity);

$getSupplierArgs = ['role' => 'supplier', 'meta_query' => [
        'relation' => 'AND',
        [
            'key' => '_city',
            'value' => $getLandingCity,
            'compare' => '='
        ],
        [
            'key' => '_allow_where_to_buy',
            'value' => 1,
            'compare' => '='
        ]
        ],'meta_key' => '_selected_plan', 'orderby' => 'meta_value_num', 'order' => 'ASC'];
$getSupplierForMaps = $GeneralThemeObject->getSupplierForMap($getSupplierArgs);

/* Sliders */
$currentDate = strtotime(date('d-m-Y'));
$currentTime = strtotime(date('H:i'));
$globalAdvTiming = get_option('advertisement_timing');
$getTopSlider = $GeneralThemeObject->getAdvertisements($getLandingCity, 1, 3);
$getMiddleSlider = $GeneralThemeObject->getAdvertisements($getLandingCity, 2, 3);
$getBottomSlider = $GeneralThemeObject->getAdvertisements($getLandingCity, 3, 3);
?>
<section class="block-row">
    <div class="container">

        <!-- Top Slider -->


        <?php
        if (is_array($getTopSlider) && count($getTopSlider) > 0):
            ?>
            <div class="viewer-slider" style="margin-bottom: 15px; margin-top:0;">
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

        <div class="product-single-display">
            <div class="row">
                <!-- Product Image -->
                <div class="col-sm-5">
                    <div class="product-details-image">
                        <img class="img-thumbnail" src="<?php echo ($productImg[0]) ? $productImg[0] : 'https://via.placeholder.com/570x600'; ?>" alt="<?php echo $productDetails->data['title']; ?>">
                        <?php if ($productDetails->data['is_simple'] == FALSE): ?>
                            <div class="bundle-ann-icon">
                                <a href="javascript:void(0);"><img src="<?php echo ASSET_URL . '/images/bundle-products-icon.png'; ?>"></i></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- End of Product Image -->
                <!-- Product Details -->
                <div class="col-sm-7">
                    <h2 class="product-title"><?php echo $productDetails->data['title']; ?></h2>
                    <div class="main-price-system">
                        <div class="product-price price-layout"><?php echo $productDetails->data['price'] . '/' . $productDetails->data['unit']; ?></div>
                        <div class="price-improv price-layout">
                            <?php if($productDetails->data['is_simple'] == true): ?>
                                <div class="price-up-arrow">
                                    <a href="javascript:void(0);" class="click-price-improve" data-improve_type="increase" data-product="<?php echo base64_encode($getQueriedObject->ID); ?>" title="<?php _e('Increase Price', THEME_TEXTDOMAIN); ?>"><i class="fa fa-angle-up" aria-hidden="true"></i></a>
                                </div>
                                <div class="price-down-arrow">
                                    <a href="javascript:void(0);" class="click-price-improve" data-improve_type="decrease" data-product="<?php echo base64_encode($getQueriedObject->ID); ?>" title="<?php _e('Decrease Price', THEME_TEXTDOMAIN); ?>"><i class="fa fa-angle-down" aria-hidden="true"></i></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="product-category"><label><?php _e('Category: ', THEME_TEXTDOMAIN); ?></label> <?php echo $productDetails->data['product_categories']; ?></div>
                    <div class="product-desc-bundles tab-cs">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#tab-2" aria-controls="tab-2" role="tab" data-toggle="tab"><?php _e('DESCRIPTION', THEME_TEXTDOMAIN); ?></a></li>
                            <?php if ($productDetails->data['is_simple'] == FALSE): ?>
                                <li role="presentation"><a href="#tab-1" aria-controls="tab-1" role="tab" data-toggle="tab"><?php _e('ATTRIBUTES', THEME_TEXTDOMAIN); ?></a></li>
                            <?php endif; ?>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="tab-2">
                                <p class="alert bg-grey cust-scroll product-dtl-scorll"><?php echo $productDetails->data['description']; ?></p>
                            </div>
                            <?php if ($productDetails->data['is_simple'] == FALSE): ?>
                                <div role="tabpanel" class="tab-pane" id="tab-1">
                                    <div class="table-responsive cust-scroll product-dtl-scorll"><!--style="max-height: 350px;" -->
                                        <?php
                                        if (is_array($getProductAttributes) && count($getProductAttributes) > 0) :
                                            ?>
                                            <table class="table table-striped table-hover">
                                                <?php
                                                foreach ($getProductAttributes as $eachAttrKey => $eachAttrVal) :
                                                    $attrProductDetails = $GeneralThemeObject->product_details($eachAttrVal);
                                                    ?>
                                                    <tr>
                                                        <td style="width:25%;"><?php echo $attrProductDetails->data['title']; ?></td>
                                                        <td><?php echo $getAttributeQuantity[$eachAttrKey] . ' ' . $attrProductDetails->data['unit']; ?></td>
                                                        <!--<td><?php echo $getAttributeQuantity[$eachAttrKey] . ' ' . $attrProductDetails->data['unit']; ?></td>-->
                                                        <td>
                                                            <?php echo 'R$ ' . number_format($attrProductDetails->data['show_price'], 2) . '/' . $attrProductDetails->data['unit']; ?>
                                                                
                                                        </td>
                                                        <td><?php echo 'R$ ' . number_format($getAttributeQuantity[$eachAttrKey] * $attrProductDetails->data['show_price'], 2); ?></td>
                                                        <!--<td><?php echo '(' . $getAttributeQuantity[$eachAttrKey] . ' ' . $attrProductDetails->data['unit'] . ' * '; ?><?php echo 'R$ ' . number_format($attrProductDetails->data['show_price'], 2) . ') = '; ?><?php echo 'R$ ' . number_format($getAttributeQuantity[$eachAttrKey] * $attrProductDetails->data['default_price'], 2); ?></td>-->
                                                    </tr>
                                                    <?php
                                                endforeach;
                                                ?>
                                            </table>
                                            <?php
                                        else:
                                            ?>
                                            <p class="alert bg-grey"><?php _e('Nenhum atributo disponível.', THEME_TEXTDOMAIN); ?></p>
                                        <?php
                                        endif;
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if ($userDetails->data['role'] == 'supplier'): ?>
                    <?php else: ?>
                        <div class="cart-quantity">
                            <input type="number" id="single_no_of_items" name="no_of_items" min="0.01" step="0.01" value="<?php echo ($getCartItem[0]->no_of_items) ? number_format($getCartItem[0]->no_of_items, 2) : '1.00'; ?>" placeholder="<?php _e('No fo items', THEME_TEXTDOMAIN); ?>" style="height: 53px; margin-right: 10px; margin-top: -2px;" />
                        </div>
                        <div class="cart-wishlist">
                            <a href="javascript:void(0);" class="btn btn-outline btn-lg btn-icon-right add-to-cart" title="<?php _e('Add to cart', THEME_TEXTDOMAIN); ?>" data-pro="<?php echo base64_encode($getQueriedObject->ID); ?>"><?php _e('Add To Cart', THEME_TEXTDOMAIN); ?><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                            <a href="javascript:void(0);" class="btn btn-outline btn-lg btn-icon-right add-to-wishlist" title="<?php echo ($isItemInWishlist == TRUE) ? 'Added to wishlist' : 'Add to wishlist'; ?>" data-pro="<?php echo base64_encode($getQueriedObject->ID); ?>" data-type="product"><?php echo ($isItemInWishlist == TRUE) ? __('Added to wishlist', THEME_TEXTDOMAIN) : __('Add to wishlist', THEME_TEXTDOMAIN);
                            ; ?><i class="fa fa-heart" aria-hidden="true"></i></a>
                            <a onclick="return ss_plugin_loadpopup_js(this);" rel="external nofollow" class="btn btn-outline btn-lg btn-icon-right facebook-share-desk" style="background-color: #2b4170;color: #fff;padding: 15px 10px 18px 10px;font-weight: 700;" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink($getQueriedObject->ID); ?>" target="_blank" title="<?php _e('Share with Facebook ', THEME_TEXTDOMAIN); ?>"><?php _e('Share with Facebook ', THEME_TEXTDOMAIN); ?> <i id="my_fb" class="fa fa-facebook" aria-hidden="true"></i><i class="fa fa-facebook" aria-hidden="true"></i></a>
                            <a onclick="return ss_plugin_loadpopup_js(this);" rel="external nofollow" class="btn btn-outline btn-lg btn-icon-right facebook-share-mob" style="background-color: #2b4170;color: #fff;font-weight: 700;font-size: 22px;" title="<?php _e('Share with Facebook ', THEME_TEXTDOMAIN); ?>" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink($getQueriedObject->ID); ?>" target="_blank"> <i id="my_fb" class="fa fa-facebook" aria-hidden="true"></i><i class="fa fa-facebook" aria-hidden="true"></i></a>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- End of Product Details -->
            </div>
        </div>

        <?php
        $availableSuppliers = [];
        if (is_array($getSupplierForMaps) && count($getSupplierForMaps) > 0) :
            foreach ($getSupplierForMaps as $eachSupplierMap) :
                $mapSupplierDetails = $GeneralThemeObject->user_details($eachSupplierMap['user_id']);
                if (is_array($productDetails->data['product_categories_arr']) && count($productDetails->data['product_categories_arr']) > 0 && is_array($mapSupplierDetails->data['buisness_categories']) && count($mapSupplierDetails->data['buisness_categories']) > 0) {
                    $mapCategoryIntersect = array_intersect($mapSupplierDetails->data['buisness_categories'], $productDetails->data['product_categories_arr']);
                }
                if (is_array($mapCategoryIntersect) && count($mapCategoryIntersect) > 0):
                    $availableSuppliers[] = $eachSupplierMap['user_id'];
                endif;
            endforeach;
        endif;
        ?>

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

        <!-- Supplier Slider Section -->
        <?php
        if (is_array($availableSuppliers) && count($availableSuppliers) > 0) :
            ?>
            <div class="section-heading" style="margin-top: 15px;">
                <h2><?php _e('WHERE TO BUY', THEME_TEXTDOMAIN); ?></h2>
                <a class="view-all-listings" href="<?php echo SUPPLIER_LISTING_PAGE.'?supplier_lists=1'; ?>" title="<?php _e('View All', THEME_TEXTDOMAIN); ?>"><i class="fa fa-globe" aria-hidden="true"></i></a>
            </div>
            <div class="owl-carousel featured-product-slider product-carousel supplier-carousal">
                <?php
                foreach ($availableSuppliers as $val) :
                    $mapSupplierDetails = $GeneralThemeObject->user_details($val);
                    $user_pro_pic = wp_get_attachment_image_src($mapSupplierDetails->data['pro_pic'], 'full');
                    $supplierPlan = $GeneralThemeObject->getMembershipPlanDetails($mapSupplierDetails->data['selected_plan']);
                    $totalRating = $RatingObject->getAverageRating($val);
                    $getRatingHTML = $RatingObject->getRatingHTML($totalRating, TRUE);
                    if ($supplierPlan->data['name'] == 'gold'):
                        $imgClass = 'gold-class';
                    elseif ($supplierPlan->data['name'] == 'silver'):
                        $imgClass = 'silver-class';
                    else:
                        $imgClass = 'bronze-class';
                    endif;
                    $memberSince = date('Y-m-d', strtotime($mapSupplierDetails->data['member_since']));
                    $dateDiff = $GeneralThemeObject->date_difference(date('Y-m-d'), $memberSince);
                    $isItemInWishlist = $WishlistObject->isItemInWishList($val, $userDetails->data['user_id'], $getLandingCity);
                    ?>
                    <div>
                        <div class="product-single">
                        	<?php if($dateDiff->days <= $GeneralThemeObject->new_flag_suppliers): ?>
                                <div class="novo-flag"><img src="<?php echo ASSET_URL.'/images/novo.png'; ?>" alt="Novo"/></div>
                            <?php endif; ?>
                            <div class="supp-thumb supp-cat-slide">
                                <?php if ($userDetails->data['role'] == 'supplier'): ?>
                                <?php else: ?>
                                    <a href="javascript:void(0);" class="add-to-wishlist <?php echo ($isItemInWishlist == TRUE) ? 'wish-active' : ''; ?>" title="<?php _e('Add to wishlist', THEME_TEXTDOMAIN); ?>" data-pro="<?php echo base64_encode($val); ?>" data-type="supplier"><i class="fa fa-heart" aria-hidden="true"></i></a>
                                <?php endif; ?>
                                <!--<a href="<?php echo ($mapSupplierDetails->data['allow_where_to_buy'] == 1) ? $mapSupplierDetails->data['where_to_buy_address'] : 'javascript:void(0);' ?>" target="_blank"><img class="<?php echo $imgClass; ?>" src="<?php echo ($mapSupplierDetails->data['pro_pic_exists']) ? $user_pro_pic[0] : 'https://via.placeholder.com/100x100'; ?>"></a>-->
                                <a href="<?php echo get_author_posts_url($mapSupplierDetails->data['user_id']); ?>"><img class="<?php echo $imgClass; ?>" src="<?php echo ($mapSupplierDetails->data['pro_pic_exists']) ? $user_pro_pic[0] : 'https://via.placeholder.com/100x100'; ?>"></a>
                            </div>
                            <div class="supp-title"><a href="<?php echo get_author_posts_url($mapSupplierDetails->data['user_id']); ?>" ><?php echo $mapSupplierDetails->data['fname']; ?></a></div>
                            <!--<div class="supp-title"><a href="<?php echo ($mapSupplierDetails->data['allow_where_to_buy'] == 1) ? $mapSupplierDetails->data['where_to_buy_address'] : 'javascript:void(0);' ?>" target="_blank"><?php echo $mapSupplierDetails->data['lname']; ?></a></div>-->
                            <div class="supp-carousal-rating"><?php echo $getRatingHTML; ?></div>
                        </div>
                    </div>
                    <?php
                endforeach;
                ?>
            </div>
            <?php
        endif;
        ?>
        <!--End of Supplier Slider Section -->

        <!-- Bottom Slider -->

        <?php
        if (is_array($getBottomSlider) && count($getBottomSlider) > 0):
            ?>
            <div class="viewer-slider" style="margin-top:15px;">
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

        <!-- Price Improvement Dialog Box -->
        <div id="price-improve-dialog-form" title="<?php _e('Improve Product Price', THEME_TEXTDOMAIN); ?>">
          <form name="priceImprovementFrm" id="priceImprovementFrm" action="javascript:void(0);" method="post">
            <input type="hidden" name="action" value="price_improvement_process">
            <input type="hidden" name="improvement_type" id="improvement_type" value="">
            <input type="hidden" name="product_id" id="product_id" value="">
              <div class="row">
                  <div class="col-sm-12">
                      <div class="form-group cart-quantity">
                          <input type="number" min="1" step="0.05" name="price_val" id="price_val" class="form-control" autocomplete="off" value="<?php echo $productDetails->data['show_price']; ?>" placeholder="<?php _e('Enter price', THEME_TEXTDOMAIN); ?>" style="height: 40px;width: 100%;" />
                      </div>
                  </div>
              </div>
          </form>
        </div>
        <!-- End of Price Improvement Dialog Box -->

    </div>
</section>
<script>
    jQuery(document).ready(function ($) {

        $('.supplier-carousal').owlCarousel({
            items: 4,
            loop: <?php echo (count($availableSuppliers) > 4) ? 'true' : 'false'; ?>,
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
    });
</script>

<?php
$GeneralThemeObject->setProductViewCounter($getQueriedObject->ID);
get_footer();
