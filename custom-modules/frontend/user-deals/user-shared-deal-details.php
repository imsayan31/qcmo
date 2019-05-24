<?php
/*
 * This page shows all details of a deal
 * 
 */
$GeneralThemeObject = new GeneralTheme();
$FinalizeData = new classFinalize();
$RatingObject = new classReviewRating();
if (isset($_GET['deal']) && $_GET['deal'] != ''):
    $dealID = base64_decode($_GET['deal']);
    $dealDetails = $FinalizeData->getDealDetails($dealID);
endif;

$userID = $dealDetails->data['user_id'];

$dealProductDetails = $FinalizeData->getDealProductDetails($dealID);
echo "<pre>";
print_r($dealDetails);
echo "</pre>";
?>
<div class="right deal-dtls desktop-view">
    
    <?php if ($dealDetails->data['locking_status'] == 1): ?>
    <?php if ($dealDetails->data['deal_name']): ?>
        <h2 style="padding-top: 0px; padding-bottom: 10px; padding-left: 5px; display: inline-block; vertical-align: top;"><?php echo '<strong> Nome: </strong>' . $dealDetails->data['deal_name']; ?></h2>
    <?php else: ?>
        <h2 style="padding-top: 0px; padding-bottom: 10px; padding-left: 5px; display: inline-block; vertical-align: top;"><?php echo '<strong> Nome: </strong>' . 'No name'; ?></h2>
    <?php endif; ?>
    <div>
        <p style="padding-top: 0px; padding-bottom: 10px; padding-left: 5px; display: inline-block; vertical-align: top;"><?php echo ($dealDetails->data['deal_description']) ? $dealDetails->data['deal_description'] : 'No description' ?></p>
    </div>
    <?php echo do_shortcode("[wp_social_sharing social_options='facebook,twitter,googleplus,linkedin,pinterest,xing' facebook_text='Compartilhar' icon_order='f' show_icons='' before_button_text='' text_position='' social_image='']"); ?>
    <?php if (is_array($dealProductDetails) && count($dealProductDetails) > 0): ?>
        <?php foreach ($dealProductDetails as $eachdeals): ?>
            <?php
            $productMainImg = wp_get_attachment_image_src(get_post_thumbnail_id($eachdeals['product_id']), 'full');
            $bundleProducts = unserialize($eachdeals['bundle_products']);
            $productUnit = get_post_meta($eachdeals['product_id'], '_product_unit', TRUE);
            ?>
            <div class="table-responsive dataTable">
                <div class="text-left">
        <!--                    <img style="padding-top: 10px; padding-bottom: 10px;" src="<?php echo ($productMainImg[0]) ? $productMainImg[0] : 'https://via.placeholder.com/100x75'; ?>" width="100" height="75" alt="<?php echo get_the_title($eachBundleProduct['product_id']); ?>"/>-->
                    <h2 style="padding-top: 20px; padding-bottom: 10px; padding-left: 5px; display: inline-block; vertical-align: top;"><?php echo '<strong> State: </strong>' . $eachdeals['state'] . ' & ' . '<strong> City: </strong>' . $eachdeals['city']; ?></h2>
                </div>
                <!--<div class="text-right"></div>-->
                <table class="table cart-table">
                    <thead>

                    <th style="text-align: left;"><img style="padding-top: 10px; padding-bottom: 10px;" src="<?php echo ($productMainImg[0]) ? $productMainImg[0] : 'https://via.placeholder.com/100x75'; ?>" width="100" height="75" alt="<?php echo get_the_title($eachBundleProduct['product_id']); ?>"/></th>
                    <th colspan="2"><?php echo get_the_title($eachdeals['product_id']); ?></th>
                    <th><?php _e('Quantity: ', THEME_TEXTDOMAIN); ?><?php echo $eachdeals['no_of_items']; ?><?php echo ($productUnit) ? '/' . $productUnit : ''; ?></th>
                    <th colspan="2"><?php echo $eachdeals['price']; ?></th>
                    </thead>
                    <tbody>
                        <?php
                        if (is_array($bundleProducts) && count($bundleProducts) > 0) :
                            foreach ($bundleProducts as $eachBundleProduct) :
                                $productImg = wp_get_attachment_image_src(get_post_thumbnail_id($eachBundleProduct['product_id']), 'full');
                                ?>
                                <tr>
                                    <td style="text-align: left;"><img src="<?php echo ($productImg[0]) ? $productImg[0] : 'https://via.placeholder.com/100x75'; ?>" width="100" height="75" alt="<?php echo get_the_title($eachBundleProduct['product_id']); ?>"/></td>
                                    <td><?php echo get_the_title($eachBundleProduct['product_id']); ?></td>
                                    <td><?php echo $eachBundleProduct['product_type']; ?></td>
                                    <td><?php echo $eachBundleProduct['product_quantity'] . ' ' . $eachBundleProduct['product_unit']; ?></td>
                                    <td><?php echo 'R$ ' . number_format($eachBundleProduct['product_price'], 2) . '/' . $eachBundleProduct['product_unit']; ?></td>
                                    <td><?php echo 'R$ ' . number_format($eachBundleProduct['product_quantity'] * $eachBundleProduct['product_price'], 2); ?></td>
                                </tr>
                                <?php
                            endforeach;
                        endif;
                        ?>

                    </tbody>
                </table>
            </div>
            <br>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-danger"><?php _e('You are not allowed to view this deal details now.', THEME_TEXTDOMAIN); ?></div>
    <?php endif; ?>
    
    


</div>
    <?php
