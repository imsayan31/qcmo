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
if (isset($_GET['user']) && $_GET['user'] != ''):
    $userID = base64_decode($_GET['user']);
endif;
$dealProductDetails = $FinalizeData->getDealProductDetails($dealID, $userID);
$hasReviewed = $RatingObject->hasUserReviewed($userID, $dealID);
?>
<div class="right deal-dtls desktop-view">
    <!--    <div class="" style="margin-bottom: 10px;text-align: right;">
            <a href="#user_deal_finalize_update_popup" data-toggle="modal" data-deal_name="<?php echo $dealDetails->data['deal_name']; ?>" data-deal_desc="<?php echo $dealDetails->data['deal_description']; ?>" class="finalize-cart-items update-finalize-deal btn animated-bg-btn"><i class="fa fa-handshake-o" aria-hidden="true"></i>&nbsp; <?php _e('Update Deal', THEME_TEXTDOMAIN); ?></a>
    <?php if ($dealDetails->data['original_status'] == 1 && $hasReviewed == FALSE): ?>
                    <a style="background: #4d5967;" class="btn round cart provide-score" data-deal="<?php echo base64_encode($dealID); ?>" title="<?php _e('Click to provide supplier score', THEME_TEXTDOMAIN); ?>" href="javascript:void(0);"><i class="fa fa-star" aria-hidden="true"></i>&nbsp; <?php _e('Your Review', THEME_TEXTDOMAIN); ?></a>
    <?php endif; ?>
            <a style="background: #4d5967;" class="btn round cart open-material-list-popup" data-deal_id="<?php echo base64_encode($dealID); ?>" href="javascript:void(0);" title="<?php _e('Material List', THEME_TEXTDOMAIN); ?>"><i class="fa fa-align-left" aria-hidden="true"></i>&nbsp; <?php _e('Material List', THEME_TEXTDOMAIN); ?></a>
            <a href="javascript:void(0);" data-deal_name="<?php echo $dealDetails->data['deal_name']; ?>" data-deal_desc="<?php echo $dealDetails->data['deal_description']; ?>" class="finalize-cart-items update-finalize-deal btn animated-bg-btn"><i class="fa fa-wrench"></i>&nbsp; <?php _e('Update Deal', THEME_TEXTDOMAIN); ?></a>
        </div>-->
    <?php if ($dealDetails->data['deal_name']): ?>
        <h2 style="padding-top: 0px; padding-bottom: 10px; padding-left: 5px; display: inline-block; vertical-align: top;"><?php echo '<strong> Nome: </strong>' . $dealDetails->data['deal_name']; ?></h2>
    <?php else: ?>
        <h2 style="padding-top: 0px; padding-bottom: 10px; padding-left: 5px; display: inline-block; vertical-align: top;"><?php echo '<strong> Nome: </strong>' . 'No name'; ?></h2>
    <?php endif; ?>
    <div>
        <p style="padding-top: 0px; padding-bottom: 10px; padding-left: 5px; display: inline-block; vertical-align: top;"><?php echo ($dealDetails->data['deal_description']) ? $dealDetails->data['deal_description'] : 'No description' ?></p>
    </div>
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
                    <h2 style="padding-top: 20px; padding-bottom: 10px; padding-left: 5px; display: inline-block; vertical-align: top;"><?php echo '<strong> Estado: </strong>' . $eachdeals['state'] . ' & ' . '<strong> Cidade: </strong>' . $eachdeals['city']; ?></h2>
                </div>
                <!--<div class="text-right"></div>-->
                <table class="table cart-table">
                    <thead>

                    <th style="text-align: left;"><img style="padding-top: 10px; padding-bottom: 10px;height:95px;" src="<?php echo ($productMainImg[0]) ? $productMainImg[0] : 'https://via.placeholder.com/100x75'; ?>" width="100" height="75" alt="<?php echo get_the_title($eachBundleProduct['product_id']); ?>"/></th>
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
                                    <td style="text-align: left;"><img style="height:75px;" src="<?php echo ($productImg[0]) ? $productImg[0] : 'https://via.placeholder.com/100x75'; ?>" width="100" height="75" alt="<?php echo get_the_title($eachBundleProduct['product_id']); ?>"/></td>
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


</div>
    <?php
