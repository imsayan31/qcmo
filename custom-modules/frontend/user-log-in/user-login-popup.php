<?php
/*
 * User Login Popup
 * 
 */
$GeneralThemeObject = new GeneralTheme();
$GeneralThemeObject->get_site_cookie();
$getQueriedObject = get_queried_object();
$custom_logo_id = get_theme_mod('custom_logo');
$image = wp_get_attachment_image_src($custom_logo_id, 'full');
?>
<div id="user_login_popup" role="dialog" class="modal fade modal-cs inputModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a class="close" data-dismiss="modal">
                    <svg xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" width="18px" height="18px" version="1.1" height="512px" viewBox="0 0 64 64" enable-background="new 0 0 64 64">
                        <g>
                            <path fill="#000000" d="M28.941,31.786L0.613,60.114c-0.787,0.787-0.787,2.062,0,2.849c0.393,0.394,0.909,0.59,1.424,0.59   c0.516,0,1.031-0.196,1.424-0.59l28.541-28.541l28.541,28.541c0.394,0.394,0.909,0.59,1.424,0.59c0.515,0,1.031-0.196,1.424-0.59   c0.787-0.787,0.787-2.062,0-2.849L35.064,31.786L63.41,3.438c0.787-0.787,0.787-2.062,0-2.849c-0.787-0.786-2.062-0.786-2.848,0   L32.003,29.15L3.441,0.59c-0.787-0.786-2.061-0.786-2.848,0c-0.787,0.787-0.787,2.062,0,2.849L28.941,31.786z"/>
                        </g>
                    </svg>
                </a>  
                <h2><?php _e('Log In', THEME_TEXTDOMAIN); ?></h2>
            </div>
            <div class="modal-body">
                <div class="modal-logo">
                    <img src="<?php echo $image[0]; ?>">
                </div>
                <form name="user_login_frm" id="user_login_frm" action="javascript:void(0);" method="post">
                    <input type="hidden" name="action" value="user_login">
                        <input type="hidden" name="redirect_page" value="<?php echo base64_encode($getQueriedObject->ID); ?>">
                            <div class="form-group">
                                <input type="email" class="form-control input-lg" id="user_login_email" autocomplete="off" name="user_login_email" value="<?php echo $GeneralThemeObject->cookie_arr['username']; ?>" placeholder="<?php _e('Email adress*', THEME_TEXTDOMAIN); ?>">
                                    <div class="user-login-email-error input-error-msg"></div>
                            </div>

                            <div class="form-group">                           
                                <input type="password" class="form-control input-lg" id="user_login_password" name="user_login_password" value="<?php echo $GeneralThemeObject->cookie_arr['pass']; ?>" placeholder="<?php _e('Password*', THEME_TEXTDOMAIN); ?>">
                                    <div class="user-login-passwrd-error input-error-msg"></div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6 col-xs-6 form-group input-check rem-txt">
                                    <label class="control control--checkbox" for="c20">
                                        <input class="" id="c20" type="checkbox" name="user_login_remember" value="1" <?php echo ($GeneralThemeObject->cookie_arr['remember'] == 1) ? 'checked="checked"' : ''; ?>>
                                            <span></span>
                                            <?php _e('Remember me', THEME_TEXTDOMAIN); ?>
                                            <div class="control__indicator"></div>
                                    </label>                   
                                </div>
                                <div class="col-sm-6 col-xs-6 forgot-txt text-right">
                                    <a href="javascript:void(0);" class="forgot-popup-show"><?php _e('Forgot Password?', THEME_TEXTDOMAIN); ?></a>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <div class="row">
                                    <div class="col-sm-8 col-sm-offset-2">
                                        <button type="submit" class="btn btn-cs ladda-button btn-lg btn-block" data-style="expand-right" name="user_login_button" id="user_login_button"><?php _e('Submit', THEME_TEXTDOMAIN); ?></button>
                                    </div>
                                </div>
                            </div>
                            </form>
                            <div class="or"><span><?php _e('Or', THEME_TEXTDOMAIN); ?></span></div>
                            <div class="row">
                                <div class="col-sm-8 col-sm-offset-2">
                                    <a class="btn btn-block btn-lg btn-social btn-facebook" href="javascript:void(0);" onclick="hello('facebook').login({scope: 'email,public_profile,photos'})">
                                        <i class="fa fa-facebook"></i> <?php _e('Sign in with Facebook', THEME_TEXTDOMAIN); ?>
                                    </a>
                                </div>
                            </div>
                            </div>   
                            <div class="modal-footer">
                                <div class="text-center"> <?php _e('Not registered?', THEME_TEXTDOMAIN); ?><a href="javascript:void(0);" class="reg-modal-show"><?php _e(' Register now.', THEME_TEXTDOMAIN); ?></a></div>
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
