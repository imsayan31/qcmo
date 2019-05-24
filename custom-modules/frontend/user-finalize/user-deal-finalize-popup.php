<?php
/*
 * User Deal Finalize Popup
 * 
 */
$GeneralThemeObject = new GeneralTheme();
$userDetails = $GeneralThemeObject->user_details();
if (empty($userDetails->data['phone']) && empty($userDetails->data['lphone'])) :
    $alertMsg = __('Your deal request may not reach all suppliers because your registry is not complete. Please fill in your address, telephone and CPF & CNPJ.', THEME_TEXTDOMAIN);
elseif (empty($userDetails->data['address'])):
    $alertMsg = __('Your deal request may not reach all suppliers because your registry is not complete. Please fill in your address, telephone and CPF & CNPJ.', THEME_TEXTDOMAIN);
elseif (empty($userDetails->data['cpf']) && empty($userDetails->data['cnpj'])):
    $alertMsg = __('Your deal request may not reach all suppliers because your registry is not complete. Please fill in your address, telephone and CPF & CNPJ.', THEME_TEXTDOMAIN);
else:
    $alertMsg = '';
endif;
//$alertMsg = ' Your deal request may not reach all suppliers because your registry is not complete. Please fill in your address, telephone and CPF & CNPJ.';
?>
<div id="user_deal_finalize_popup" role="dialog" class="modal fade modal-cs inputModal" aria-hidden="true">
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
                <h2><?php _e('Deal Finalize Details', THEME_TEXTDOMAIN); ?></h2>
            </div>
            <div class="modal-body">
                <form name="user_deal_finalize_frm" id="user_deal_finalize_frm" method="post" action="javascript:void(0);">
                    <input type="hidden" name="action" value="finalize_cart_data" />

                    <div class="form-group">
                        <input class="form-control enter-email input-lg" id="user_deal_name" autocomplete="off" name="deal_name" type="text" placeholder="<?php _e('Deal name(E.g. My Deal)', THEME_TEXTDOMAIN) ?>" value="" />
                        <p><?php _e('*Place your deal name here and we will generate an unique ID along with your deal name, E.g. if you write "My deal" as your deal name your final deal name will be as "My deal #er53t"', THEME_TEXTDOMAIN); ?></p>
                        <div class="forgt-error input-error-msg"></div>
                    </div>

                    <div class="form-group">
                        <textarea name="deal_description" placeholder="<?php _e('Deal description', THEME_TEXTDOMAIN); ?>" class="form-control" style="resize: none;"></textarea>
                        <p><?php _e('*E.g. Construction of a house with 2 rooms, a garage, dinning room, roof made with steel structure, foundations made of concrete, grass on the ground.', THEME_TEXTDOMAIN); ?></p>
                        <div class="forgt-error input-error-msg"></div>
                    </div>
                    <?php if ($alertMsg): ?>
                    <div class="alert alert-warning spl-warning-msg"><i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?php echo $alertMsg; ?></div>
                    <?php endif; ?>

                    <div class="form-group text-center">
                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">
                                <button class="btn btn-cs ladda-button btn-lg btn-block" data-style="expand-right" id="usr_deal_finalize_sbmt" type="submit"><?php _e('Submit', THEME_TEXTDOMAIN); ?></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <!--<div class="text-center"> <?php _e('Password remembered?', THEME_TEXTDOMAIN); ?><a href="javascript:void(0);" class="forgot-login-modal-show"><?php _e(' Login now.', THEME_TEXTDOMAIN); ?></a></div>-->
            </div>
        </div>
    </div>    
</div>
<script>
                                jQuery(document).ready(function ($) {

                                    // Detect ios 11_x_x affected
                                    // NEED TO BE UPDATED if new versions are affected 
                                    (function iOS_CaretBug() {

                                        var ua = navigator.userAgent,
                                                scrollTopPosition,
                                                iOS = /iPad|iPhone|iPod/.test(ua),
                                                iOS11 = /OS 11_0_1|OS 11_0_2|OS 11_0_3|OS 11_1|OS 11_1_1|OS 11_1_2|OS 11_2|OS 11_2_1/.test(ua);

                                        // ios 11 bug caret position
                                        if (iOS && iOS11) {

                                            $(document.body).on('show.bs.modal', function (e) {
                                                if ($(e.target).hasClass('inputModal')) {
                                                    // Get scroll position before moving top
                                                    scrollTopPosition = $(document).scrollTop();

                                                    // Add CSS to body "position: fixed"
                                                    $("body").addClass("iosBugFixCaret");
                                                }
                                            });

                                            $(document.body).on('hide.bs.modal', function (e) {
                                                if ($(e.target).hasClass('inputModal')) {
                                                    // Remove CSS to body "position: fixed"
                                                    $("body").removeClass("iosBugFixCaret");

                                                    //Go back to initial position in document
                                                    $(document).scrollTop(scrollTopPosition);
                                                }
                                            });

                                        }
                                    })();
                                });
                            </script>
<?php
