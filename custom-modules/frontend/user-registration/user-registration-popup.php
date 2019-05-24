<?php
/*
 * User Registration Popup
 * 
 */
$GeneralThemeObject = new GeneralTheme();
$getAllCities = $GeneralThemeObject->getBrazilCities();
$getStates = $GeneralThemeObject->getCities();
?>
<div id="user_register_popup" role="dialog" class="modal fade modal-cs inputModal" aria-hidden="true">
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
                <h2><?php _e('Registration', THEME_TEXTDOMAIN); ?></h2>
            </div>
            <div class="modal-body">
                <form name="productorRegistration" id="productorRegistration" action="javascript:void(0);" method="post">
                    <input type="hidden" name="action" value="productor_registration">
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <input type="text" class="form-control input-lg" id="fname" name="fname" autocomplete="off" value="" placeholder="<?php _e('First name*', THEME_TEXTDOMAIN); ?>">
                                    <div class="input-error-msg"></div>
                            </div>
                            <div class="col-sm-6 form-group">
                                <input type="text" class="form-control input-lg" id="lname" name="lname" autocomplete="off" value="" placeholder="<?php _e('Last name*', THEME_TEXTDOMAIN); ?>">
                                    <div class="input-error-msg"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <input type="email" class="form-control input-lg" id="email" name="email" autocomplete="off" value="" placeholder="<?php _e('Email*', THEME_TEXTDOMAIN); ?>">
                                    <div class="input-error-msg"></div>
                            </div>
                            <div class="col-sm-6 form-group">
                                <input type="text" class="form-control input-lg" id="phone" name="phone" value="" autocomplete="off" placeholder="<?php _e('Phone*', THEME_TEXTDOMAIN); ?>">
                                    <div class="input-error-msg"></div>
                            </div>
                        </div>
                        <label><?php _e('Select your type', THEME_TEXTDOMAIN); ?></label>
                        <div class="row type-error">
                            <div class="col-sm-6 form-group">
                                <label class="control control--radio" for="usr_physical_person">
                                    <input class="form-control supplier-type" id="usr_physical_person" type="radio" name="supplier_type" value="1"/>
                                    <span></span>
                                    <?php _e('Physical person', THEME_TEXTDOMAIN); ?>
                                    <div class="control__indicator"></div>
                                </label>
                                <!--<div class="input-error-msg"></div>-->
                            </div>
                            <div class="col-sm-6 form-group">
                                <label class="control control--radio" for="usr_judicial_person">
                                    <input class="form-control supplier-type" id="usr_judicial_person" type="radio" name="supplier_type" value="2"/>
                                    <span></span>
                                    <?php _e('Judicial person', THEME_TEXTDOMAIN); ?>
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                        <div class="input-error-msg"></div>
                        <div class="row cpf-display" style="display: none;">
                            <div class="col-sm-12 form-group">
                                <input type="text" class="form-control input-lg" id="usr_cpf" name="cpf" autocomplete="off" value="" placeholder="<?php _e('CPF', THEME_TEXTDOMAIN); ?>">
                                    <!--div class="input-error-msg"></div-->
                            </div>
                        </div>
                        <div class="row cnpj-display" style="display: none;">
                            <div class="col-sm-12 form-group">
                                <input type="text" class="form-control input-lg" id="usr_cnpj" name="cnpj" value="" autocomplete="off" placeholder="<?php _e('CNPJ', THEME_TEXTDOMAIN); ?>">
                                    <!--div class="input-error-msg"></div-->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <input type="text" class="form-control input-lg" id="usr_reg_address" name="address" value="" placeholder="<?php _e('Address', THEME_TEXTDOMAIN); ?>"/>
                                <input type="hidden" id="usr_reg_address_loc" name="usr_reg_address_loc" value=""/>
                                    <input type="hidden" id="usr_reg_address_id" name="usr_reg_address_id" value=""/>
                                <div class="input-error-msg"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <select name="state" class="user-state state-city-selection chosen">
                                    <option value=""><?php _e('Choose your state', THEME_TEXTDOMAIN); ?></option>
                                    <?php
                                    if (is_array($getStates) && count($getStates) > 0) :
                                        foreach ($getStates as $eachCity) :
                                            ?>
                                            <option value="<?php echo $eachCity->term_id; ?>"><?php echo $eachCity->name; ?></option>
                                            <?php
                                        endforeach;
                                    endif;
                                    ?>
                                </select>
                                <div class="input-error-msg"></div>
                            </div>
                            <div class="col-sm-6 form-group">
                                <select name="city" class="user-city select-your-city chosen">
                                    <option value=""><?php _e('Choose your city', THEME_TEXTDOMAIN); ?></option>
                                </select>
                                <div class="input-error-msg"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <input type="password" class="form-control input-lg" id="password" name="password" value="" placeholder="<?php _e('Password*', THEME_TEXTDOMAIN); ?>">
                                    <div class="input-error-msg"></div>
                            </div>
                            <div class="col-sm-6 form-group">
                                <input type="password" class="form-control input-lg" id="cnfpassword" name="cnfpassword" value="" placeholder="<?php _e('Confirm Password*', THEME_TEXTDOMAIN); ?>">
                                    <div class="input-error-msg"></div>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <div class="row">
                                <div class="col-sm-8 col-sm-offset-2">
                                    <button type="submit" class="btn btn-cs ladda-button btn-lg btn-block" data-style="expand-right" name="productorRegSbmt" id="productorRegSbmt"><?php _e('Register', THEME_TEXTDOMAIN); ?></button>
                                </div>
                            </div>
                        </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="text-center"><?php _e('Already registered?', THEME_TEXTDOMAIN); ?><a href="javascript:void(0);" class="login-modal-show"><?php _e(' Login now.', THEME_TEXTDOMAIN); ?></a></div>
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
