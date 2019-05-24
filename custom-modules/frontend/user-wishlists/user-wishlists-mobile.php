<?php
/*
 * This page shows user wishlist
 * 
 */
$GeneralThemeObject = new GeneralTheme();
$WishlistObject = new classWishList();
$AnnouncementObject = new classAnnouncement();
$GeneralThemeObject->authentic();
$userDetails = $GeneralThemeObject->user_details();
$getLandingCity = $GeneralThemeObject->getLandingCity();
$getUserWishlist = $WishlistObject->getWishListItems($userDetails->data['user_id'], 'product');
$getUserAnnouncementWishlist = $WishlistObject->getWishListItems($userDetails->data['user_id'], 'announcement');
$getUserSupplierWishlist = $WishlistObject->getWishListItems($userDetails->data['user_id'], 'supplier');
?>
<div class="right mobile-view">

    <!-- Product Wishlists -->
    <div class="wishlist-heading"><h2><?php _e('Products', THEME_TEXTDOMAIN); ?></h2></div>
    <div class="">
        <?php if (is_array($getUserWishlist) && count($getUserWishlist) > 0) : ?>
            <?php foreach ($getUserWishlist as $eachWishList) : ?>
                <?php
                $productDetails = $GeneralThemeObject->product_details($eachWishList->product_id);
                $productImg = wp_get_attachment_image_src($productDetails->data['thumbnail_id'], 'full');
                $productState = get_term_by('id', $eachWishList->state, themeFramework::$theme_prefix . 'product_city');
                $productCity = get_term_by('id', $eachWishList->city, themeFramework::$theme_prefix . 'product_city');
                ?>
                <div class="mobile-wishlist">
                    <div class="cart-details">
                        <div class="col-sm-3 col-xs-4 no-padding">
                            <a href="<?php echo get_permalink($eachWishList->product_id); ?>">
                                <img src="<?php echo ($productImg[0]) ? $productImg[0] : 'https://via.placeholder.com/100x75'; ?>" width="100" height="75" alt="<?php echo $productDetails->data['title']; ?>"/>
                            </a>
                        </div>
                        <div class="col-sm-9 col-xs-8 product-name">
                            <a href="<?php echo get_permalink($eachWishList->product_id); ?>">
                                <?php echo $productDetails->data['title']; ?>
                            </a>
                            <div><?php echo $productState->name; ?></div>
                            <div><?php echo $productCity->name; ?></div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="cart-details">
                        <div class="col-sm-6 col-xs-6">
                            <span class="price"><?php echo $productDetails->data['price']; ?></span>
                        </div>
                        <div class="col-sm-6 col-xs-6 text-right">
                            <a href="javascript:void(0);" class="add-to-cart btn round cart" title="<?php _e('Add to cart', THEME_TEXTDOMAIN); ?>" data-state="<?php echo base64_encode($eachWishList->state); ?>" data-city="<?php echo base64_encode($eachWishList->city); ?>" data-pro="<?php echo base64_encode($eachWishList->product_id); ?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                            <a href="javascript:void(0);" class="remove-from-wishlist btn round del" title="<?php _e('Remove from wishlist', THEME_TEXTDOMAIN); ?>" data-state="<?php echo base64_encode($eachWishList->state); ?>" data-city="<?php echo base64_encode($eachWishList->city); ?>" data-pro="<?php echo base64_encode($eachWishList->product_id); ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="mobile-wishlist">
                <div class="cart-details">
                    <div class="col-sm-12 col-xs-12">
                        <div class="alert alert-danger"><?php _e('No items in your wishlist now.', THEME_TEXTDOMAIN); ?></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <!-- End of Product Wishlists -->

    <!-- Announcement Wishlists -->
    <div class="wishlist-heading"><h2><?php _e('Announcements', THEME_TEXTDOMAIN); ?></h2></div>
    <div class="">
        <?php if (is_array($getUserAnnouncementWishlist) && count($getUserAnnouncementWishlist) > 0) : ?>
            <?php foreach ($getUserAnnouncementWishlist as $eachWishList) : ?>
                <?php
                $productDetails = $AnnouncementObject->announcement_details($eachWishList->product_id);
                $productImg = wp_get_attachment_image_src($productDetails->data['announcement_single_image'], 'full');
                $productState = get_term_by('id', $eachWishList->state, themeFramework::$theme_prefix . 'product_city');
                $productCity = get_term_by('id', $eachWishList->city, themeFramework::$theme_prefix . 'product_city');
                ?>
                <div class="mobile-wishlist">
                    <div class="cart-details">
                        <div class="col-sm-3 col-xs-4 no-padding">
                            <a href="<?php echo get_permalink($eachWishList->product_id); ?>">
                                <img src="<?php echo ($productImg[0]) ? $productImg[0] : 'https://via.placeholder.com/100x75'; ?>" width="100" height="75" alt="<?php echo $productDetails->data['title']; ?>"/>
                            </a>
                        </div>
                        <div class="col-sm-9 col-xs-8 product-name">
                            <a href="<?php echo get_permalink($eachWishList->product_id); ?>">
                                <?php echo $productDetails->data['title']; ?>
                            </a>
                            <div><?php echo $productState->name; ?></div>
                            <div><?php echo $productCity->name; ?></div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="cart-details">
                        <div class="col-sm-6 col-xs-6">
                            <span class="price"><?php echo ($productDetails->data['announcement_price']) ? 'R$ '.number_format($productDetails->data['announcement_price'], 2) : 'R$ 0.00'; ?></span>
                        </div>
                        <div class="col-sm-6 col-xs-6 text-right">
                            <a href="javascript:void(0);" class="remove-from-wishlist btn round del" title="<?php _e('Remove from wishlist', THEME_TEXTDOMAIN); ?>" data-state="<?php echo base64_encode($eachWishList->state); ?>" data-city="<?php echo base64_encode($eachWishList->city); ?>" data-pro="<?php echo base64_encode($eachWishList->product_id); ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="mobile-wishlist">
                <div class="cart-details">
                    <div class="col-sm-12 col-xs-12">
                        <div class="alert alert-danger"><?php _e('No announcements in your wishlist now.', THEME_TEXTDOMAIN); ?></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <!-- End of Announcement Wishlists -->

    <!-- Supplier Wishlists -->
    <div class="wishlist-heading"><h2><?php _e('Suppliers', THEME_TEXTDOMAIN); ?></h2></div>
    <div class="">
        <?php if (is_array($getUserSupplierWishlist) && count($getUserSupplierWishlist) > 0) : ?>
            <?php foreach ($getUserSupplierWishlist as $eachWishList) : ?>
                <?php
                $productDetails = $GeneralThemeObject->user_details($eachWishList->product_id);
                $productImg = wp_get_attachment_image_src($productDetails->data['pro_pic'], 'full');
                $productState = get_term_by('id', $eachWishList->state, themeFramework::$theme_prefix . 'product_city');
                $productCity = get_term_by('id', $eachWishList->city, themeFramework::$theme_prefix . 'product_city');
                ?>
                <div class="mobile-wishlist">
                    <div class="cart-details">
                        <div class="col-sm-3 col-xs-4 no-padding">
                            <a href="<?php echo get_permalink($eachWishList->product_id); ?>">
                                <img src="<?php echo ($productImg[0]) ? $productImg[0] : 'https://via.placeholder.com/100x75'; ?>" width="100" height="75" alt="<?php echo $productDetails->data['title']; ?>"/>
                            </a>
                        </div>
                        <div class="col-sm-7 col-xs-6 product-name">
                            <a href="<?php echo get_permalink($eachWishList->product_id); ?>">
                                <?php echo $productDetails->data['title']; ?>
                            </a>
                            <div><?php echo $productState->name; ?></div>
                            <div><?php echo $productCity->name; ?></div>
                        </div>
                        <div class="col-sm-2 col-xs-2 text-right">
                            <a href="javascript:void(0);" class="remove-from-wishlist btn round del" title="<?php _e('Remove from wishlist', THEME_TEXTDOMAIN); ?>" data-state="<?php echo base64_encode($eachWishList->state); ?>" data-city="<?php echo base64_encode($eachWishList->city); ?>" data-pro="<?php echo base64_encode($eachWishList->product_id); ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <!-- <div class="cart-details">
                        <div class="col-sm-12 col-xs-12 text-right">
                            <a href="javascript:void(0);" class="remove-from-wishlist btn round del" title="<?php _e('Remove from wishlist', THEME_TEXTDOMAIN); ?>" data-state="<?php echo base64_encode($eachWishList->state); ?>" data-city="<?php echo base64_encode($eachWishList->city); ?>" data-pro="<?php echo base64_encode($eachWishList->product_id); ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                        </div>
                        <div class="clearfix"></div>
                    </div> -->
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="mobile-wishlist">
                <div class="cart-details">
                    <div class="col-sm-12 col-xs-12">
                        <div class="alert alert-danger"><?php _e('No suppliers in your wishlist now.', THEME_TEXTDOMAIN); ?></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <!-- End of Supplier Wishlists -->

</div>




<?php
