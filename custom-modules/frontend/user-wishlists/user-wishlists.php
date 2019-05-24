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
<div class="right desktop-view">

    <!-- Product Wishlist -->
    <div class="wishlist-heading"><h2><?php _e('Products', THEME_TEXTDOMAIN); ?></h2></div>
    <div class="table-responsive">

        <?php if (is_array($getUserWishlist) && count($getUserWishlist) > 0) : ?>
            <table class="tbl-wishlist" id="tbl-wishlist">
                <thead>
                    <tr>
                        <th><?php _e('Product', THEME_TEXTDOMAIN); ?></th>
                        <th><?php _e('State', THEME_TEXTDOMAIN); ?></th>
                        <th><?php _e('City', THEME_TEXTDOMAIN); ?></th>
                        <th><?php _e('Price', THEME_TEXTDOMAIN); ?></th>
                        <th><?php _e('Action', THEME_TEXTDOMAIN); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($getUserWishlist as $eachWishList) : ?>
                        <?php
                        $productDetails = $GeneralThemeObject->product_details($eachWishList->product_id);
                        $productImg = wp_get_attachment_image_src($productDetails->data['thumbnail_id'], 'full');
                        $productState = get_term_by('id', $eachWishList->state, themeFramework::$theme_prefix . 'product_city');
                        $productCity = get_term_by('id', $eachWishList->city, themeFramework::$theme_prefix . 'product_city');
                        ?>
                        <tr>
                            <!-- <td>
                                
                            </td> -->
                            <td width="35%">
                                <a href="<?php echo get_permalink($eachWishList->product_id); ?>">
                                    <img src="<?php echo ($productImg[0]) ? $productImg[0] : 'https://via.placeholder.com/100x75'; ?>" width="100" height="75" alt="<?php echo $productDetails->data['title']; ?>"/>
                                </a>
                                <br>
                                <a href="<?php echo get_permalink($eachWishList->product_id); ?>">
                                    <?php echo $productDetails->data['title']; ?>
                                </a>
                            </td>
                            <td width="15%"><?php echo $productState->name; ?></td>
                            <td width="15%"><?php echo $productCity->name; ?></td>
                            <td width="15%"><?php echo $productDetails->data['price']; ?></td>
                            <td width="20%">
                                <a href="javascript:void(0);" class="add-to-cart btn round cart" title="<?php _e('Add to cart', THEME_TEXTDOMAIN); ?>" data-state="<?php echo base64_encode($eachWishList->state); ?>" data-city="<?php echo base64_encode($eachWishList->city); ?>" data-pro="<?php echo base64_encode($eachWishList->product_id); ?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                                <a href="javascript:void(0);" class="remove-from-wishlist btn round del" title="<?php _e('Remove from wishlist', THEME_TEXTDOMAIN); ?>" data-state="<?php echo base64_encode($eachWishList->state); ?>" data-city="<?php echo base64_encode($eachWishList->city); ?>" data-pro="<?php echo base64_encode($eachWishList->product_id); ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-danger"><?php _e('No items in your wishlist now.', THEME_TEXTDOMAIN); ?></div>
        <?php endif; ?>
    </div>
    <!-- End of Product Wishlist -->
    <br>
    <!-- Announcement Wishlist -->
    <div class="wishlist-heading"><h2><?php _e('Announcements', THEME_TEXTDOMAIN); ?></h2></div>
    <div class="table-responsive">

        <?php if (is_array($getUserAnnouncementWishlist) && count($getUserAnnouncementWishlist) > 0) : ?>
            <table id="tbl-announcement-wishlist">
                <thead>
                    <tr>
                        <th><?php _e('Announcement', THEME_TEXTDOMAIN); ?></th>
                        <th><?php _e('State', THEME_TEXTDOMAIN); ?></th>
                        <th><?php _e('City', THEME_TEXTDOMAIN); ?></th>
                        <th><?php _e('Price', THEME_TEXTDOMAIN); ?></th>
                        <th><?php _e('Action', THEME_TEXTDOMAIN); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($getUserAnnouncementWishlist as $eachWishList) : ?>
                        <?php
                        $productDetails = $AnnouncementObject->announcement_details($eachWishList->product_id);
                        $productImg = wp_get_attachment_image_src($productDetails->data['announcement_single_image'], 'full');
                        $productState = get_term_by('id', $eachWishList->state, themeFramework::$theme_prefix . 'product_city');
                        $productCity = get_term_by('id', $eachWishList->city, themeFramework::$theme_prefix . 'product_city');
                        ?>
                        <tr>
                            <td>
                                <a href="<?php echo get_permalink($eachWishList->product_id); ?>">
                                    <img src="<?php echo ($productImg[0]) ? $productImg[0] : 'https://via.placeholder.com/100x75'; ?>" width="100" height="75" alt="<?php echo $productDetails->data['title']; ?>"/>
                                </a> 
                                <br>
                                <a href="<?php echo get_permalink($eachWishList->product_id); ?>">
                                    <?php echo $productDetails->data['title']; ?>
                                </a>
                            </td>
                            <td><?php echo $productState->name; ?></td>
                            <td><?php echo $productCity->name; ?></td>
                            <td><?php echo ($productDetails->data['announcement_price']) ? 'R$ '.number_format($productDetails->data['announcement_price'], 2) : 'R$ 0.00'; ?></td>
                            <td>
                                <a href="javascript:void(0);" class="remove-from-wishlist btn round del" title="<?php _e('Remove from wishlist', THEME_TEXTDOMAIN); ?>" data-state="<?php echo base64_encode($eachWishList->state); ?>" data-city="<?php echo base64_encode($eachWishList->city); ?>" data-pro="<?php echo base64_encode($eachWishList->product_id); ?>"><i class="fa fa-trash" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-danger"><?php _e('No announcements in your wishlist now.', THEME_TEXTDOMAIN); ?></div>
        <?php endif; ?>
    </div>
    <!-- End of Announcement Wishlist -->
    <br>
    <!-- Supplier Wishlist -->
    <div class="wishlist-heading"><h2><?php _e('Suppliers', THEME_TEXTDOMAIN); ?></h2></div>
    <div class="table-responsive">
        <?php if (is_array($getUserSupplierWishlist) && count($getUserSupplierWishlist) > 0) : ?>
            <table id="tbl-supplier-wishlist">
                <thead>
                    <tr>
                        <th><?php _e('Suppliers', THEME_TEXTDOMAIN); ?></th>
                        <th><?php _e('State', THEME_TEXTDOMAIN); ?></th>
                        <th><?php _e('City', THEME_TEXTDOMAIN); ?></th>
                        <th><?php _e('Action', THEME_TEXTDOMAIN); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($getUserSupplierWishlist as $eachWishList) : ?>
                        <?php
                        $productDetails = $GeneralThemeObject->user_details($eachWishList->product_id);
                        $productImg = wp_get_attachment_image_src($productDetails->data['pro_pic'], 'full');
                        $productState = get_term_by('id', $eachWishList->state, themeFramework::$theme_prefix . 'product_city');
                        $productCity = get_term_by('id', $eachWishList->city, themeFramework::$theme_prefix . 'product_city');
                        ?>
                        <tr>
                            <td width="35%">
                                <a href="<?php echo get_author_posts_url($eachWishList->product_id); ?>">
                                    <img src="<?php echo ($productImg[0]) ? $productImg[0] : 'https://via.placeholder.com/100x75'; ?>" width="100" height="75" alt="<?php echo $productDetails->data['fname']; ?>"/>
                                </a>
                                <br>
                                <a href="<?php echo get_author_posts_url($eachWishList->product_id); ?>">
                                    <?php echo $productDetails->data['fname']; ?>
                                </a>
                            </td>
                            <td width="15%"><?php echo $productState->name; ?></td>
                            <td width="15%"><?php echo $productCity->name; ?></td>
                            <td width="15%">
                                <a href="javascript:void(0);" class="remove-from-wishlist btn round del" title="<?php _e('Remove from wishlist', THEME_TEXTDOMAIN); ?>" data-state="<?php echo base64_encode($eachWishList->state); ?>" data-city="<?php echo base64_encode($eachWishList->city); ?>" data-pro="<?php echo base64_encode($eachWishList->product_id); ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-danger"><?php _e('No suppliers in your wishlist now.', THEME_TEXTDOMAIN); ?></div>
        <?php endif; ?>
    </div>
    <!-- End of Supplier Wishlist -->

</div>


<?php
