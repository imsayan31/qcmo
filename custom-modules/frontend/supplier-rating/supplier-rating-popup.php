<?php
/*
 * User Reset Password Popup
 * 
 */
?>
<div id="supplier_rating_popup" role="dialog" class="modal fade modal-cs" aria-hidden="true">
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
                <h2><?php _e('Create Supplier Score', THEME_TEXTDOMAIN); ?></h2>
            </div>
            <div class="modal-body">
                <form name="supplier_rating_frm" id="supplier_rating-popup_frm" method="post" action="javascript:void(0);">
                    <input type="hidden" name="action" value="supplier_rating" />
                    <input type="hidden" name="selected_deal" id="selected_deal_val" value="">
                        <div class="form-group">
                            <div class="show-deal-suppliers"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <button class="btn btn-cs ladda-button btn-lg" data-style="expand-right" id="usr_rating_sbmt" type="submit"><?php _e('Submit', THEME_TEXTDOMAIN); ?></button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>    
</div>
<?php
