<?php
/*
 * User Cart Category Popup
 * 
 */
$CartObject = new classCart();
$getCartCategories = $CartObject->getUserCartProductCategories(get_current_user_id());
?>
<div id="user_cart_category_popup" role="dialog" class="modal fade modal-cs" aria-hidden="true">
    <div class="modal-dialog reset-pop">
        <div class="modal-content">
            <div class="modal-header">
                <a class="close" data-dismiss="modal">
                    <svg xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" width="18px" height="18px" version="1.1" height="512px" viewBox="0 0 64 64" enable-background="new 0 0 64 64">
                        <g>
                            <path fill="#000000" d="M28.941,31.786L0.613,60.114c-0.787,0.787-0.787,2.062,0,2.849c0.393,0.394,0.909,0.59,1.424,0.59   c0.516,0,1.031-0.196,1.424-0.59l28.541-28.541l28.541,28.541c0.394,0.394,0.909,0.59,1.424,0.59c0.515,0,1.031-0.196,1.424-0.59   c0.787-0.787,0.787-2.062,0-2.849L35.064,31.786L63.41,3.438c0.787-0.787,0.787-2.062,0-2.849c-0.787-0.786-2.062-0.786-2.848,0   L32.003,29.15L3.441,0.59c-0.787-0.786-2.061-0.786-2.848,0c-0.787,0.787-0.787,2.062,0,2.849L28.941,31.786z"/>
                        </g>
                    </svg>
                </a>  
                <h2><?php _e('Get Materials', THEME_TEXTDOMAIN); ?></h2>
            </div>
            <div class="modal-body">
                <form name="user_cart_category_frm" id="user_cart_category_frm" method="post" action="javascript:void(0);">
                    <input type="hidden" name="action" value="cart_category_data" />
                    <input type="hidden" name="user_deal_id" id="user_deal_id" value="" />

                    <div class="form-group">
                        <label><?php _e('Select Category to get the list of materials', THEME_TEXTDOMAIN); ?></label>
                        <select name="select_cart_category" class="chosen select-cart-category" style="font-size:16px;">
                            <option value="9999"><?php _e('', THEME_TEXTDOMAIN); ?></option>
                            <?php
                            if (is_array($getCartCategories) && count($getCartCategories) > 0):
                                foreach ($getCartCategories as $eachCategory):
                                    $getCatDetails = get_term_by('slug', $eachCategory, themeFramework::$theme_prefix . 'product_category');
                                    ?>
                                    <option value="<?php echo $getCatDetails->slug; ?>" <?php echo (isset($_GET['selected_cat']) && $_GET['selected_cat'] != '' && $_GET['selected_cat'] == $getCatDetails->slug) ? 'selected' : '' ?>><?php 
                            echo $getCatDetails->name; 
                            
                                    ?></option>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </div>

                    <div class="form-group text-center">
                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">
                                <button class="btn btn-cs ladda-button btn-lg btn-block" data-style="expand-right" id="usr_cart_category_sbmt" type="submit"><?php _e('Ver', THEME_TEXTDOMAIN); ?></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="text-center"> <?php _e('Choose your category to get the list of the materials in your cart. You will be able to print the product lists. All categories will allow you to fetch the list of all materials.', THEME_TEXTDOMAIN); ?></div>
            </div>
        </div>
    </div>    
</div>
<?php
