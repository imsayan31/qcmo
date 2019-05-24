<?php
/*
 * PayPal Donation Popup
 * 
 */
$get_paypal_donation_amount = get_option('_payapl_donation_amount');
$GeneralThemeObject = new GeneralTheme();
$getPopulatedMonths = $GeneralThemeObject->populateMonths();
if (is_user_logged_in()) {
    $userDetails = $GeneralThemeObject->user_details();
}
?>
<div id="user_paypal_donation_popup" role="dialog" class="modal fade modal-cs inputModal" aria-hidden="true">
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
                <h2><?php _e('Donate On PayPal', THEME_TEXTDOMAIN); ?></h2>
            </div>
            <div class="modal-body">
                <form name="user_donation_frm" id="user_donation_frm" method="post" action="javascript:void(0);">
                    <input type="hidden" name="action" value="usr_paypal_donation" />

                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <input type="text" class="form-control input-lg" id="donate_fname" name="fname" autocomplete="off" value="<?php echo (is_user_logged_in()) ? $userDetails->data['fname'] : ''; ?>" placeholder="<?php _e('First name*', THEME_TEXTDOMAIN); ?>">
                                <div class="input-error-msg"></div>
                        </div>
                        <div class="col-sm-6 form-group">
                            <input type="text" class="form-control input-lg" id="donate_lname" name="lname" autocomplete="off" value="<?php echo (is_user_logged_in()) ? $userDetails->data['lname'] : ''; ?>" placeholder="<?php _e('Last name*', THEME_TEXTDOMAIN); ?>">
                                <div class="input-error-msg"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <input type="email" class="form-control input-lg" id="donate_email" name="email" autocomplete="off" value="<?php echo (is_user_logged_in()) ? $userDetails->data['email'] : ''; ?>" placeholder="<?php _e('Email*', THEME_TEXTDOMAIN); ?>">
                                <div class="input-error-msg"></div>
                        </div>
                        <div class="col-sm-6 form-group">
                            <input type="text" class="form-control input-lg" id="donate_phone" name="phone" value="<?php echo (is_user_logged_in()) ? $userDetails->data['phone'] : ''; ?>" autocomplete="off" placeholder="<?php _e('Phone*', THEME_TEXTDOMAIN); ?>">
                                <div class="input-error-msg"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <div class="show-plan-price"><h2 style="font-size: 15px;"><?php _e('Amount to be donated(in R$):', THEME_TEXTDOMAIN); ?></h2></div>
                        </div>
                        <div class="col-sm-6 form-group">
                            <input type="text" class="form-control input-lg" id="donate_amount" name="donate_amount" value="<?php echo number_format($get_paypal_donation_amount, 2); ?>" autocomplete="off" placeholder="<?php _e('Amount*', THEME_TEXTDOMAIN); ?>">
                                <div class="input-error-msg"></div>
                        </div>
                    </div>

                    <div class="form-group input-check rem-txt">
                        <label class="control control--checkbox" for="c_donate">
                            <input class="" id="c_donate" type="checkbox" name="user_terms_checking" value="1">
                                <span></span><?php _e('I have agreed all ', THEME_TEXTDOMAIN); ?> <a href="<?php echo TERMS_PAGE; ?>"><?php _e(' terms and conditions.', THEME_TEXTDOMAIN); ?></a>
                                <div class="control__indicator"></div>
                        </label>    
                        <div class="terms-error input-error-msg" style="top: 140px; left: 15px;"></div>
                    </div>

                    <label><?php _e('Card Information', THEME_TEXTDOMAIN); ?></label>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="text" name="subscription_card_name" id="announcement_subscription_card_name" class="form-control input-lg" autocomplete="off" placeholder="<?php _e('Card Holder name*', THEME_TEXTDOMAIN); ?>"/>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <select name="subscription_card_type" class="chosen subscription_card_type">
                                        <option value=""><?php _e('-Select Card Type*-', THEME_TEXTDOMAIN); ?></option>
                                        <option value="Visa"><?php _e('Visa', THEME_TEXTDOMAIN); ?></option>
                                        <option value="MasterCard"><?php _e('Master Card', THEME_TEXTDOMAIN); ?></option>
                                        <option value="Amex"><?php _e('American Express', THEME_TEXTDOMAIN); ?></option>
                                        <option value="Discover"><?php _e('Discover', THEME_TEXTDOMAIN); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="text" name="subscription_card_number" id="announcement_subscription_card_number" class="form-control input-lg" autocomplete="off" placeholder="<?php _e('Card Number*', THEME_TEXTDOMAIN); ?>"/>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="text" name="subscription_card_cvv" id="announcement_subscription_card_cvv" class="form-control input-lg" autocomplete="off" placeholder="<?php _e('CVV*', THEME_TEXTDOMAIN); ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <select name="subscription_card_exp_month" class="chosen subscription_card_exp_month">
                                        <option value=""><?php _e('-Card Expiry Month*-', THEME_TEXTDOMAIN); ?></option>
                                        <?php foreach ($getPopulatedMonths as $eachMonthKey => $eachMonthVal): ?>
                                            <option value="<?php echo $eachMonthKey; ?>"><?php echo $eachMonthVal; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <select name="subscription_card_exp_year" class="chosen subscription_card_exp_year">
                                        <option value=""><?php _e('-Card Expiry Year*-', THEME_TEXTDOMAIN); ?></option>
                                        <?php for ($year = date('Y'); $year <= 2050; $year++): ?>
                                            <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                    <div class="form-group text-center">
                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">
                                <button class="btn btn-cs ladda-button btn-lg btn-block" data-style="expand-right" id="usr_donate_sbmt" type="submit"><?php _e('Donate now', THEME_TEXTDOMAIN); ?></button>
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
