<?php
/*
 * User Announcement Payment Popup
 * 
 */
$GeneralThemeObject = new GeneralTheme();
$getPopulatedMonths = $GeneralThemeObject->populateMonths();
?>
<div id="user_announcement_payment_popup" role="dialog" class="modal fade modal-cs" aria-hidden="true">
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
                <h2><?php _e('Announcement Payment', THEME_TEXTDOMAIN); ?></h2>
            </div>
            <div class="modal-body">
                <form name="usr_announcement_payment_frm" id="usr_announcement_payment_frm" method="post" action="javascript:void(0);">
                    <input type="hidden" name="action" value="announcement_payment" />
                    <input type="hidden" name="selected_announcement_plan" id="selected_announcement_plan" value="">

                        <label><?php _e('Coupon Information', THEME_TEXTDOMAIN); ?></label>
                        <div class="row">
                            <div class="col-sm-9">
                                <div class="form-group">
                                    <input type="text" name="membership_coupon" id="membership_coupon" class="form-control input-lg" value="" autocomplete="off" placeholder="<?php _e('Enter coupon code', THEME_TEXTDOMAIN); ?>" />
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <button class="btn btn-cs ladda-button btn-lg" data-style="expand-right" id="usr_coupon_sbmt" type="submit"><?php _e('Apply', THEME_TEXTDOMAIN); ?></button>
                            </div>
                        </div>

                        <div class="coupon-applied-data"></div>
                        
                        <label><?php _e('Card Information', THEME_TEXTDOMAIN); ?></label>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="text" name="subscription_card_name" class="form-control input-lg" autocomplete="off" placeholder="<?php _e('Card Holder name*', THEME_TEXTDOMAIN); ?>"/>
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
                                    <input type="text" name="subscription_card_number" class="form-control input-lg" autocomplete="off" placeholder="<?php _e('Card Number*', THEME_TEXTDOMAIN); ?>"/>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="text" name="subscription_card_cvv" class="form-control input-lg" autocomplete="off" placeholder="<?php _e('CVV*', THEME_TEXTDOMAIN); ?>"/>
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

                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2 payple-btn">
                                <button class="btn btn-cs ladda-button btn-lg" data-style="expand-right" id="usr_announcement_payment_sbmt" type="submit"><?php _e('', THEME_TEXTDOMAIN); ?></button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>    
</div>
<?php
