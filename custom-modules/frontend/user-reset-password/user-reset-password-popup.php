<?php
/*
 * User Reset Password Popup
 * 
 */
?>
<div id="user_reset_password_popup" role="dialog" class="modal fade modal-cs inputModal" aria-hidden="true">
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
                <h2><?php _e('Reset Password', THEME_TEXTDOMAIN); ?></h2>
            </div>
            <div class="modal-body">
                <form name="myrental_rst_pass_frm" id="myrental_rst_pass_frm" method="post" action="javascript:void(0);">
                    <input type="hidden" name="action" value="myrental_rst_pass" />
                    <input type="hidden" name="tenant" id="reset_user" value="">
                        <div class="form-group">
                            <input class="form-control input-lg" name="myrental_new_pass" id="myrental_new_pass" type="password" placeholder="<?php _e('Enter New Password*', THEME_TEXTDOMAIN) ?>" value="" />
                            <div class="old-pass-error input-error-msg"></div>
                        </div>
                        <div class="form-group">
                            <input class="form-control input-lg" name="myrental_new_cnf_pass" id="myrental_new_cnf_pass" type="password" placeholder="<?php _e('Confirm New Password*', THEME_TEXTDOMAIN) ?>" value="" />
                            <div class="new-pass-error input-error-msg"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">
                                <button class="btn btn-cs ladda-button btn-lg btn-block" data-style="expand-right" id="myrental_rst_pass_sbmt" type="submit"><?php _e('Reset Password', THEME_TEXTDOMAIN); ?></button>
                            </div>
                        </div>
                </form>
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
