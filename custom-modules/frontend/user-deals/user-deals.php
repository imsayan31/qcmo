<?php
/*
 * This page will list all the deals of a user
 * 
 */
$GeneralThemeObject = new GeneralTheme();
$FinalizeObject = new classFinalize();
$RatingObject = new classReviewRating();
$userDetails = $GeneralThemeObject->user_details();
$getDeals = $FinalizeObject->getDeals($userDetails->data['user_id'], '', FALSE);
?>
<div class="right desktop-view">
    <div class="table-responsive">

        <?php if (is_array($getDeals) && count($getDeals) > 0): ?>
            <table class="tbl-wishlist">
                <thead>
                <th><?php _e('', THEME_TEXTDOMAIN); ?></th>
                <th><?php _e('Deal', THEME_TEXTDOMAIN); ?></th>
                <th><?php _e('Total Price', THEME_TEXTDOMAIN); ?></th>
                <th><?php _e('Deal Status', THEME_TEXTDOMAIN); ?></th>
    <!--            <th><?php _e('Deal Initialized On', THEME_TEXTDOMAIN); ?></th>
                <th><?php _e('Deal Completed On', THEME_TEXTDOMAIN); ?></th>-->
                <th><?php _e('Action', THEME_TEXTDOMAIN); ?></th>
                </thead>
                <tbody>
                    <?php foreach ($getDeals as $eachDeal): ?>
                        <?php
                        $dealDetails = $FinalizeObject->getDealDetails($eachDeal->deal_id);
                        $hasReviewed = $RatingObject->hasUserReviewed($userDetails->data['user_id'], $eachDeal->deal_id);
                        $getDealSuppliersStatus = $FinalizeObject->getDealSuppliersStatus($eachDeal->deal_id);

                        if ($eachDeal->deal_status == 1) :
                            $dealCompletedSelected = 'selected';
                            $dealInitiatedSelected = '';
                            $dealProcessingSelected = '';
                            $dealRejectedSelected = '';
                        elseif ($eachDeal->deal_status == 2) :
                            $dealCompletedSelected = '';
                            $dealInitiatedSelected = 'selected';
                            $dealProcessingSelected = '';
                            $dealRejectedSelected = '';
                        elseif ($eachDeal->deal_status == 3) :
                            $dealCompletedSelected = '';
                            $dealInitiatedSelected = '';
                            $dealProcessingSelected = 'selected';
                            $dealRejectedSelected = '';
                        elseif ($eachDeal->deal_status == 4):
                            $dealCompletedSelected = '';
                            $dealInitiatedSelected = '';
                            $dealProcessingSelected = '';
                            $dealRejectedSelected = 'selected';
                        endif;

                        $actionSelectBox = '<select name="" class="user-deal-status-change chosen" data-deal="' . $dealDetails->data['deal_id'] . '">';
                        $actionSelectBox .= '<option value="">-Select status-</option>';
                        $actionSelectBox .= '<option value="1" ' . $dealCompletedSelected . '>Concluído</option>';
                        $actionSelectBox .= '<option value="2" ' . $dealInitiatedSelected . '>Iniciado em ' . $dealDetails->data['initiated'] . '</option>';
                        $actionSelectBox .= '<option value="3" ' . $dealProcessingSelected . '>Em processamento</option>';
                        $actionSelectBox .= '<option value="4" ' . $dealRejectedSelected . '>Rejeitado</option>';
                        $actionSelectBox .= '</select>';
                        ?>
                        <tr>
                            <td>
                                <?php if ($eachDeal->deal_locking_status == 1): ?>
                                    <a href="javascript:void(0);" class="btn round cart deal-unlock-click" data-deal_id="<?php echo base64_encode($dealDetails->data['deal_id']); ?>" title="<?php _e('Lock Your Deal', THEME_TEXTDOMAIN); ?>"><i class="fa fa-unlock-alt" aria-hidden="true"></i></a>
                                    <a href="javascript:void(0);" class="btn round cart deal-lock-click" data-deal_id="<?php echo base64_encode($dealDetails->data['deal_id']); ?>" title="<?php _e('Unlock Your Deal', THEME_TEXTDOMAIN); ?>" style="display:none"><i class="fa fa-lock" aria-hidden="true"></i></a>
                                <?php else: ?>
                                    <a href="javascript:void(0);" class="btn round cart deal-lock-click" data-deal_id="<?php echo base64_encode($dealDetails->data['deal_id']); ?>" title="<?php _e('Unlock Your Deal', THEME_TEXTDOMAIN); ?>"><i class="fa fa-lock" aria-hidden="true"></i></a>
                                    <a href="javascript:void(0);" class="btn round cart deal-unlock-click" data-deal_id="<?php echo base64_encode($dealDetails->data['deal_id']); ?>" title="<?php _e('Lock Your Deal', THEME_TEXTDOMAIN); ?>" style="display:none"><i class="fa fa-unlock-alt" aria-hidden="true"></i></a>
                                <?php endif; ?>
                                <a href="javascript:void(0);" class="btn round cart deal-share-link-click" data-deal_id="<?php echo base64_encode($dealDetails->data['deal_id']); ?>" title="<?php _e('Get Shareable Link', THEME_TEXTDOMAIN); ?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
                                <a href="javascript:void(0);" class="btn round cart erase-deal" data-deal_id="<?php echo base64_encode($dealDetails->data['deal_id']); ?>" title="<?php _e('Erase Deals', THEME_TEXTDOMAIN); ?>"><i class="fa fa-eraser" aria-hidden="true"></i></a>
                            </td>
                            <td><?php echo $dealDetails->data['deal_name']; ?></td>
                            <td><?php echo $dealDetails->data['total_price']; ?></td>
                            <!--<td><?php echo ($eachDeal->deal_status == 1) ? $dealDetails->data['status'] . ' em ' . $dealDetails->data['completed'] : $actionSelectBox; ?></td>-->
                            <td>
                                <?php
                                if ($eachDeal->deal_status == 1):
                                    _e($dealDetails->data['status'] . ' em ' . $dealDetails->data['completed'], THEME_TEXTDOMAIN);
                                elseif ($eachDeal->deal_status == 4):
                                    _e('Rejeitado', THEME_TEXTDOMAIN);
                                else:
                                    echo $actionSelectBox;
                                endif;
                                ?>
                            </td>
        <!--                            <td><?php echo $dealDetails->data['initiated']; ?></td>
                            <td><?php echo $dealDetails->data['completed']; ?></td>-->
                            <td>
                                <a class="btn round cart" title="<?php _e('View details', THEME_TEXTDOMAIN); ?>" href="<?php echo MY_DEALS_PAGE . '?deal=' . base64_encode($dealDetails->data['deal_id']) . '&user=' . base64_encode($dealDetails->data['user_id']); ?>"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <?php if ($eachDeal->deal_status != 4 && $hasReviewed == FALSE && $getDealSuppliersStatus == 1): ?>
                                                        <!--<a class="btn round cart provide-score" data-deal="<?php echo base64_encode($dealDetails->data['deal_id']); ?>" title="<?php _e('Click to provide supplier score', THEME_TEXTDOMAIN); ?>" href="javascript:void(0);"><i class="fa fa-star" aria-hidden="true"></i></a>-->
                                    <a class="btn round cart provide-score" title="<?php _e('Click to provide supplier score', THEME_TEXTDOMAIN); ?>" href="<?php echo CREATE_SUPPLIER_SCORE_PAGE . '?deal_id=' . base64_encode($dealDetails->data['deal_id']) ?>"><i class="fa fa-star" aria-hidden="true"></i></a>
                                <?php endif; ?>
                <!--<a class="btn round cart open-material-list-popup" data-deal_id="<?php echo base64_encode($dealDetails->data['deal_id']); ?>" href="javascript:void(0);" title="<?php _e('Material List', THEME_TEXTDOMAIN); ?>"><i class="fa fa-align-left" aria-hidden="true"></i></a>-->
                                <a class="btn round cart" href="<?php echo MATERIAL_LIST_PAGE . '?selected_cat=9999&deal_id=' . base64_encode($dealDetails->data['deal_id']); ?>"  target="_blank" title="<?php _e('Material List', THEME_TEXTDOMAIN); ?>"><i class="fa fa-align-left" aria-hidden="true"></i></a>
                                <a class="btn round cart open-deal-details-update-popup" data-deal_id="<?php echo base64_encode($dealDetails->data['deal_id']); ?>" href="javascript:void(0);" title="<?php _e('Deal Update', THEME_TEXTDOMAIN); ?>"><i class="fa fa-wrench" aria-hidden="true"></i></a>
                            </td>
        <!--                            <td>
                                <a class="btn round cart" href="<?php echo MATERIAL_LIST_PAGE . '?deal_id=' . base64_encode($dealDetails->data['deal_id']); ?>"><i class="fa fa-wrench" aria-hidden="true"></i></a>
                            </td>-->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-danger"><?php _e('No items in your deal list now.', THEME_TEXTDOMAIN); ?></div>
        <?php endif; ?>

    </div>
</div>

<?php
