<?php
/*
 * This page shows user cart
 * 
 */
$GeneralThemeObject = new GeneralTheme();
$CartObject = new classCart();
$GeneralThemeObject->authentic();
$userDetails = $GeneralThemeObject->user_details();
$getUserCartItems = $CartObject->getCartItems($userDetails->data['user_id']);
?>
<div class="right mobile-view">
    <form name="user-cart-frm" id="user-cart-mobile-frm" action="javascript:void(0);" method="post">
        <input type="hidden" name="action" value="cart_update">
        <div class="">
            <?php if (is_array($getUserCartItems) && count($getUserCartItems) > 0) : ?>
                <?php foreach ($getUserCartItems as $eachCartItem) : ?>
                    <?php
                    $productDetails = $GeneralThemeObject->product_details($eachCartItem->product_id);
                    $productImg = wp_get_attachment_image_src($productDetails->data['thumbnail_id'], 'full');
                    $productState = get_term_by('id', $eachCartItem->state, themeFramework::$theme_prefix . 'product_city');
                    $productCity = get_term_by('id', $eachCartItem->city, themeFramework::$theme_prefix . 'product_city');
                    $productUnit = get_post_meta($eachCartItem->product_id, '_product_unit', TRUE);
                    ?>
                    <div class="cart-details">
                        <div class="col-sm-3 col-xs-4 no-padding">
                            <a href="<?php echo get_permalink($eachCartItem->product_id); ?>">
                                <img src="<?php echo ($productImg[0]) ? $productImg[0] : 'https://via.placeholder.com/100x75'; ?>" width="100" height="75" alt="<?php echo $productDetails->data['title']; ?>"/>
                            </a>
                        </div>
                        <div class="col-sm-6 col-xs-6 product-name">
                            <a href="<?php echo get_permalink($eachCartItem->product_id); ?>">
                                <?php echo $productDetails->data['title']; ?>
                            </a>
                            <h2><?php echo 'R$ ' . number_format($eachCartItem->product_price, 2); ?><?php echo ($productUnit) ? '/' . $productUnit : '' ?></h2>
                            <span><?php echo $productState->name; ?></span>
                            <span><?php echo $productCity->name; ?></span>
                        </div>
                        <div class="col-sm-3 col-xs-2 text-right">
                            <a href="javascript:void(0);" class="remove-from-cart btn round del" title="<?php _e('Remove from cart', THEME_TEXTDOMAIN); ?>" data-state="<?php echo base64_encode($eachCartItem->state); ?>" data-city="<?php echo base64_encode($eachCartItem->city); ?>" data-pro="<?php echo base64_encode($eachCartItem->product_id); ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                        </div>   
                        <div class="clearfix"></div>
                    </div>
                    <div class="cart-details">
                        <div class="cart-quantity col-sm-9 col-xs-6">
                            <input type="number" name="no_of_items[<?php echo base64_encode($eachCartItem->product_id) . '%' . base64_encode($eachCartItem->product_price) . '%' . base64_encode($eachCartItem->state) . '%' . base64_encode($eachCartItem->city); ?>][]" min="0.01" step="0.01" value="<?php echo $eachCartItem->no_of_items; ?>" placeholder="<?php _e('No fo items', THEME_TEXTDOMAIN); ?>" />
                        </div>
                        <div class="col-sm-3 col-xs-6">
                            <span class="price"><?php echo 'R$ ' . number_format($eachCartItem->total_price, 2); ?></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                <?php endforeach; ?>
                <div class="cart-details">
                    <div class="col-sm-12 pull-right">
                        <span class="text-right"><?php _e('Cart Total: ', THEME_TEXTDOMAIN); ?><?php echo 'R$ ' . number_format($CartObject->getCartTotal($userDetails->data['user_id']), 2); ?></span>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="cart-button-sec">
                    <a href="javascript:void(0);" class="clear-cart-items btn round del pull-left"><?php _e('Clear Cart Items', THEME_TEXTDOMAIN); ?></a>
                    <a href="javascript:void(0);" class="update-cart-items btn round cart pull-left"><?php _e('Update Cart Items', THEME_TEXTDOMAIN); ?></a>
                    <!--<a href="#user_cart_category_popup" class="finalize-cart-items btn animated-bg-btn" data-toggle="modal"><i class="fa fa-align-left" aria-hidden="true"></i>&nbsp; <?php _e('Material List', THEME_TEXTDOMAIN); ?></a>-->
                    <a href="<?php echo MATERIAL_LIST_PAGE . '?selected_cat=9999'; ?>" target="_blank" class="finalize-cart-items btn animated-bg-btn"><i class="fa fa-align-left" aria-hidden="true"></i>&nbsp; <?php _e('Material List', THEME_TEXTDOMAIN); ?></a>
                    <!--<a href="<?php echo MATERIAL_LIST_PAGE; ?>" class="finalize-cart-items btn animated-bg-btn"><i class="fa fa-align-left" aria-hidden="true"></i>&nbsp; <?php _e('Material List', THEME_TEXTDOMAIN); ?></a>-->
                    <!--<a href="javascript:void(0);" class="finalize-cart-items btn animated-bg-btn"><i class="fa fa-handshake-o" aria-hidden="true"></i>&nbsp; <?php _e('Finalize Deal', THEME_TEXTDOMAIN); ?></a>-->
                    <a href="#user_deal_finalize_popup" data-toggle="modal" class="finalize-cart-items btn animated-bg-btn"><i class="fa fa-handshake-o" aria-hidden="true"></i>&nbsp; <?php _e('Finalize Deal', THEME_TEXTDOMAIN); ?></a>
                </div>

                <div class="clearfix"></div>
            <?php else: ?>
                <div class="cart-details">
                    <div class="cart-quantity col-sm-12">
                        <div class="alert alert-danger" style=""><?php _e('No items in your cart now.', THEME_TEXTDOMAIN); ?><a style="color: #22b68d;" href="<?php echo ALL_PRODUCTS_PAGE; ?>"><?php _e(' Click to get more products.', THEME_TEXTDOMAIN); ?></a></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            <?php endif; ?>
        </div>
    </form>
</div>
<?php
