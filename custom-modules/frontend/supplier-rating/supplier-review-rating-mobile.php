<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$GeneralThemeObject = new GeneralTheme();
$FinalizeObject = new classFinalize();
if (isset($_GET['deal_id']) && $_GET['deal_id'] != ''):
    $deal_id = base64_decode($_GET['deal_id']);
endif;
$userDetails = $GeneralThemeObject->user_details();
$getDealSupplierDetails = $FinalizeObject->selectDistinctSupplierIDs($deal_id, $userDetails->data['user_id']);
?>
<div class="right mobile-view">
     <?php
        if (is_array($getDealSupplierDetails) && count($getDealSupplierDetails) > 0):
            foreach ($getDealSupplierDetails as $eachDealSupplier):
                $supplierDetails = $GeneralThemeObject->user_details($eachDealSupplier->supplier_id);
                if ($supplierDetails->data['pro_pic_exists'] == TRUE) :
                    $user_pro_pic = wp_get_attachment_image_src($supplierDetails->data['pro_pic'], 'full');
                    $supplierThumb = $user_pro_pic[0];
                else:
                   $supplierThumb = 'https://via.placeholder.com/100x100';
                endif;
                ?>
    <form name="supplier_rating_frm" class="supplier_rating_frm" method="post" action="javascript:void(0);">
        <input type="hidden" name="action" value="supplier_rating" />
        <input type="hidden" name="selected_deal" value="<?php echo $_GET['deal_id']; ?>">
       
               
               
         <div class="row">
                    <div class="supplier-mobile-view-setup">
                        <div class="col-sm-5 col-xs-4 no-padding text-center">
                            <div class="supp-comm-name"><?php echo $supplierDetails->data['fname']; ?></div>
                            <div class="profile-pic"><div class="user-pic"><img src="<?php echo $supplierThumb; ?>" alt="" width="100" height="100"/></div></div>
                            <div class="supp-legal-name"><?php echo $supplierDetails->data['lname']; ?></div>
                        </div>
                        <div class="col-sm-7 col-xs-8" style="padding-left: 1px;">
                            <div class="supp-rating-section">
                                <div class="rating-field">
                                    <span><?php _e('Price', THEME_TEXTDOMAIN); ?></span><input name="price_rate[<?php echo $eachDealSupplier->supplier_id; ?>]" type="text" class="rating new-rating" value="" data-show-clear="false" data-size="xs" data-show-caption="false" data-readonly="false" />
                                </div>
                                <div class="rating-field">
                                    <span><?php _e('Attendance', THEME_TEXTDOMAIN); ?></span><input name="attendence_rate[<?php echo $eachDealSupplier->supplier_id; ?>]" type="text" class="rating new-rating" value="" data-show-clear="false" data-size="xs" data-show-caption="false" data-readonly="false" />
                                </div>
                                <div class="rating-field">
                                    <span><?php _e('Delivery', THEME_TEXTDOMAIN); ?></span><input name="delivery_rate[<?php echo $eachDealSupplier->supplier_id; ?>]" type="text" class="rating new-rating" value="" data-show-clear="false" data-size="xs" data-show-caption="false" data-readonly="false" />
                                </div>
                            </div>
                            <div class="supp-rating-section">
                                <textarea class="form-control" name="supplier_rating_comments[<?php echo $eachDealSupplier->supplier_id; ?>]" placeholder="<?php _e('Leave your comments here', THEME_TEXTDOMAIN); ?>" style="resize: none;"></textarea>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
               
        <div class="row">
            <div class="col-sm-12 text-center">
                <button class="btn btn-cs ladda-button btn-lg" data-style="expand-right" class="usr_rating_sbmt" type="submit"><?php _e('Submit', THEME_TEXTDOMAIN); ?></button>
            </div>
        </div>
    </form>
      <?php
            endforeach;
        endif;
        ?>
</div>
<?php
