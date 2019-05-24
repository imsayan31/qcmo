<?php
/*
 * Announcement Renewal
 * 
 */
$GeneralThemeObject = new GeneralTheme();
$AnnouncementObject = new classAnnouncement();
$getProductCategories = get_terms(themeFramework::$theme_prefix . 'product_category', ['hide_empty' => FALSE, 'parent' => 0]);
$user_details = $GeneralThemeObject->user_details();
$getAnnouncementEnabledCities = $AnnouncementObject->getAnnouncementEnabledCities();
$getSupplierAnnEnabled = get_term_meta($user_details->data['city'], '_enable_announcement_for_suppliers', TRUE);
$getCustomerAnnEnabled = get_term_meta($user_details->data['city'], '_enable_announcement_for_customers', TRUE);
if (isset($_GET['announcement_renew']) && $_GET['announcement_renew'] != ''):
    $announcement_id = base64_decode($_GET['announcement_renew']);
    $announcement_details = $AnnouncementObject->announcement_details($announcement_id);
    $announcementImages = $announcement_details->data['announcement_images'];
endif;
$getPopulatedMonths = $GeneralThemeObject->populateMonths();
?>
<div class="right">
    <?php if (is_array($getAnnouncementEnabledCities) && count($getAnnouncementEnabledCities) > 0 && in_array($user_details->data['city'], $getAnnouncementEnabledCities) && $user_details->data['role'] == 'subscriber' && $getCustomerAnnEnabled == 1): ?>
    <form name="announcementRenewalFrm" id="announcementRenewalFrm" method="post" action="javascript:void(0);" enctype="multipart/form-data">
        <input type="hidden" name="action" value="announcement_renew"/>
        <input type="hidden" name="announcement_renew" id="renew_announcement_plan" value="<?php echo (isset($_GET['announcement_renew']) && $_GET['announcement_renew'] != '') ? $_GET['announcement_renew'] : ''; ?>"/>
        <input type="hidden" name="property_main_images" class="property_main_images" value="<?php echo $announcement_details->data['announcement_main_images']; ?>"/>
        <div class="row">
            <div class="col-sm-6 form-group">
                <input type="text" name="announcement_name" autocomplete="off" class="form-control input-lg" id="announcement_renewal_name" value="<?php echo $announcement_details->data['title']; ?>" placeholder="<?php _e('Announcement name*', THEME_TEXTDOMAIN); ?>"/>
                <div class="input-error-msg"></div>
            </div>
            <div class="col-sm-6 form-group">
                <select name="announcement_category[]" class="chosen announcement_category" multiple>
                    <option value=""><?php _e('-Select Announcement Category-', THEME_TEXTDOMAIN); ?></option>
                    <?php
                    if (is_array($getProductCategories) && count($getProductCategories) > 0):
                        foreach ($getProductCategories as $eachProductCategory):
                            $getProductSubCategories = get_terms(themeFramework::$theme_prefix . 'product_category', ['hide_empty' => FALSE, 'parent' => $eachProductCategory->term_id]);
                            if(is_array($announcement_details->data['announcement_category']) && count($announcement_details->data['announcement_category']) > 0 && in_array($eachProductCategory->slug, $announcement_details->data['announcement_category'])){
                                            $catSelected = 'selected';
                                        } else {
                                            $catSelected = '';
                                        }
                            ?>
                            <option value="<?php echo $eachProductCategory->slug; ?>" <?php echo $catSelected; ?>><?php echo $eachProductCategory->name; ?></option>
                            <?php
                            if (is_array($getProductSubCategories) && count($getProductSubCategories) > 0):
                                foreach ($getProductSubCategories as $eachSubCategory):
                                    if(is_array($announcement_details->data['announcement_category']) && count($announcement_details->data['announcement_category']) > 0 && in_array($eachSubCategory->slug, $announcement_details->data['announcement_category'])){
                                            $subCatSelected = 'selected';
                                        } else {
                                            $subCatSelected = '';
                                        }
                                    ?>
                                    <option value="<?php echo $eachSubCategory->slug; ?>" <?php echo $subCatSelected; ?>><?php _e('--', THEME_TEXTDOMAIN); ?><?php echo $eachSubCategory->name; ?></option>
                                    <?php
                                endforeach;
                            endif;
                        endforeach;
                    endif;
                    ?>
                </select>
                <div class="input-error-msg"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 form-group">
                <input type="text" name="announcement_date" class="form-control input-lg" autocomplete="off" id="announcement_renewal_create_date" value="<?php echo $announcement_details->data['start_date']; ?>" placeholder="<?php _e('Starts from*', THEME_TEXTDOMAIN); ?>"/>
                <div class="input-error-msg"></div>
            </div>
            <div class="col-sm-6 form-group">
                <input type="number" name="announcement_period" class="form-control input-lg" id="announcement_renewal_period" min="1" max="31" value="<?php echo $announcement_details->data['no_of_days']; ?>" placeholder="<?php _e('Número de dias*', THEME_TEXTDOMAIN); ?>"/>
                <div class="input-error-msg"></div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-sm-12">
                <input type="text" name="announcment_price" id="announcment_renewal_price" autocomplete="off" class="form-control input-lg" value="<?php echo $announcement_details->data['announcement_price']; ?>" placeholder="<?php _e('Price (in R$)', THEME_TEXTDOMAIN); ?>"/>
                <div class="input-error-msg"></div>
            </div>
        </div>
        
        <div class="row" style="margin-top: 15px;">
            <?php
            if (is_array($announcementImages) && count($announcementImages) > 0):
                foreach ($announcementImages as $eachAnnouncementImage):
                    $imagePath = get_attached_file($eachAnnouncementImage);
                    $imageSrc = wp_get_attachment_image_src($eachAnnouncementImage, 'thumbnail');
                    ?>
                    <div class="col-sm-3">
                        <div class="indiv-announcement-img">
                            <img src="<?php echo ($imagePath) ? $imageSrc[0] : 'https://via.placeholder.com/200x175'; ?>" alt=""/>
                            <a href="javascript:void(0);" class="delete-announcement-image" data-announcement="<?php echo (isset($_GET['announcement_id']) && $_GET['announcement_id'] != '') ? $_GET['announcement_id'] : ''; ?>" data-img="<?php echo base64_encode($eachAnnouncementImage); ?>"><i class="fa fa-times" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <?php
                endforeach;
            endif;
            ?>
        </div>

        <div class="row col-sm-12">
            <!-- Multiple File Uploader -->
            <div id="drag-and-drop-zone" class="dm-uploader p-5">
                <h3 class="mb-5 mt-5 text-muted"><?php _e('Drag &amp; drop files here', THEME_TEXTDOMAIN); ?></h3>

                <div class="btn btn-primary btn-block mb-5">
                    <span><?php _e('Open the file Browser', THEME_TEXTDOMAIN); ?></span>
                    <input type="file" name="file[]" title='Click to add Files' />
                </div>
            </div>
            <div class="card h-100">
                <div class="card-header">
                    File List
                </div>

                <ul class="list-unstyled p-2 d-flex flex-column col" id="files">
                    <li class="text-muted text-center empty"><?php _e('No files uploaded.', THEME_TEXTDOMAIN); ?></li>
                </ul>
            </div>

            <!-- File item template -->
            <script type="text/html" id="files-template">
                <li class="media">
                    <div class="media-body mb-1">
                        <p class="mb-2">
                            <strong>%%filename%%</strong> <?php _e('- Status:', THEME_TEXTDOMAIN); ?> <span class="text-muted"><?php _e('Waiting', THEME_TEXTDOMAIN); ?></span>
                        </p>
                        <div class="progress mb-2">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
                                 role="progressbar"
                                 style="width: 0%" 
                                 aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <hr class="mt-1 mb-1" />
                    </div>
                </li>
                </script>

                <!-- Debug item template -->

                <!-- End of Multiple File Uploader -->
            </div>

            <div class="row">
                <div class="col-sm-12 form-group">
                    <textarea name="announcement_desc" class="form-control" placeholder="<?php _e('Description', THEME_TEXTDOMAIN); ?>"><?php echo $announcement_details->data['content']; ?></textarea>
                    <div class="input-error-msg"></div>
                </div>
            </div>
            <div class="row col-sm-12">
                <div class="cols-m-3">
                    <label><?php _e('Select your plan: ', THEME_TEXTDOMAIN); ?></label>
                </div>
                <div class="col-sm-3 form-group">
                    <label class="control control--radio" for="gold_plan">
                        <input class="form-control announement-plan-selection" id="gold_plan" type="radio" name="announcement_type" value="gold"/>
                        <span></span>
                        <?php _e('Gold', THEME_TEXTDOMAIN); ?>
                        <div class="control__indicator"></div>
                    </label>
                </div>
                <div class="col-sm-3 form-group">
                    <label class="control control--radio" for="silver_plan">
                        <input class="form-control announement-plan-selection" id="silver_plan" type="radio" name="announcement_type" value="silver"/>
                        <span></span>
                        <?php _e('Silver', THEME_TEXTDOMAIN); ?>
                        <div class="control__indicator"></div>
                    </label>
                </div>
                <div class="col-sm-3 form-group">
                    <label class="control control--radio" for="bronze_plan">
                        <input class="form-control announement-plan-selection" id="bronze_plan" type="radio" name="announcement_type" value="bronze"/>
                        <span></span>
                        <?php _e('Bronze', THEME_TEXTDOMAIN); ?>
                        <div class="control__indicator"></div>
                    </label>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="row col-sm-12">
                <div class="show-announce-plan-price"></div>
            </div>

            <label><?php _e('Coupon Information', THEME_TEXTDOMAIN); ?></label>
            <div class="row">
                <div class="col-sm-9">
                    <div class="form-group">
                        <input type="text" name="membership_coupon" id="membership_coupon_renew" class="form-control input-lg" value="" autocomplete="off" placeholder="<?php _e('Enter coupon code', THEME_TEXTDOMAIN); ?>" />
                    </div>
                </div>
                <div class="col-sm-3">
                    <button class="btn btn-cs ladda-button btn-lg" data-style="expand-right" id="usr_coupon_renew_sbmt" type="submit"><?php _e('Apply', THEME_TEXTDOMAIN); ?></button>
                </div>
            </div>

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

            <div class="form-group text-center">
                <button type="submit" class="btn btn-cs ladda-button btn-lg" data-style="expand-right" name="announcementRenewalSbmt" id="announcementRenewalSbmt"><?php _e('Renew', THEME_TEXTDOMAIN); ?></button>
            </div>
        </form>
    <?php elseif (is_array($getAnnouncementEnabledCities) && count($getAnnouncementEnabledCities) > 0 && in_array($user_details->data['city'], $getAnnouncementEnabledCities) && $user_details->data['role'] == 'supplier' && $getSupplierAnnEnabled == 1): ?>
    <form name="announcementRenewalFrm" id="announcementRenewalFrm" method="post" action="javascript:void(0);" enctype="multipart/form-data">
        <input type="hidden" name="action" value="announcement_renew"/>
        <input type="hidden" name="announcement_renew" id="renew_announcement_plan" value="<?php echo (isset($_GET['announcement_renew']) && $_GET['announcement_renew'] != '') ? $_GET['announcement_renew'] : ''; ?>"/>
        <input type="hidden" name="property_main_images" class="property_main_images" value="<?php echo $announcement_details->data['announcement_main_images']; ?>"/>
        <div class="row">
            <div class="col-sm-6 form-group">
                <input type="text" name="announcement_name" class="form-control input-lg" autocomplete="off" id="announcement_renewal_name" value="<?php echo $announcement_details->data['title']; ?>" placeholder="<?php _e('Announcement name*', THEME_TEXTDOMAIN); ?>"/>
                <div class="input-error-msg"></div>
            </div>
            <div class="col-sm-6 form-group">
                <select name="announcement_category[]" class="chosen announcement_category" multiple>
                    <option value=""><?php _e('-Select Announcement Category-', THEME_TEXTDOMAIN); ?></option>
                    <?php
                    if (is_array($getProductCategories) && count($getProductCategories) > 0):
                        foreach ($getProductCategories as $eachProductCategory):
                            $getProductSubCategories = get_terms(themeFramework::$theme_prefix . 'product_category', ['hide_empty' => FALSE, 'parent' => $eachProductCategory->term_id]);
                            if(is_array($announcement_details->data['announcement_category']) && count($announcement_details->data['announcement_category']) > 0 && in_array($eachProductCategory->slug, $announcement_details->data['announcement_category'])){
                                            $catSelected = 'selected';
                                        } else {
                                            $catSelected = '';
                                        }
                            ?>
                            <option value="<?php echo $eachProductCategory->slug; ?>" <?php echo $catSelected; ?>><?php echo $eachProductCategory->name; ?></option>
                            <?php
                            if (is_array($getProductSubCategories) && count($getProductSubCategories) > 0):
                                foreach ($getProductSubCategories as $eachSubCategory):
                                    if(is_array($announcement_details->data['announcement_category']) && count($announcement_details->data['announcement_category']) > 0 && in_array($eachSubCategory->slug, $announcement_details->data['announcement_category'])){
                                            $subCatSelected = 'selected';
                                        } else {
                                            $subCatSelected = '';
                                        }
                                    ?>
                                    <option value="<?php echo $eachSubCategory->slug; ?>" <?php echo $subCatSelected; ?>><?php _e('--', THEME_TEXTDOMAIN); ?><?php echo $eachSubCategory->name; ?></option>
                                    <?php
                                endforeach;
                            endif;
                        endforeach;
                    endif;
                    ?>
                </select>
                <div class="input-error-msg"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 form-group">
                <input type="text" name="announcement_date" class="form-control input-lg" autocomplete="off" id="announcement_renewal_create_date" value="<?php echo $announcement_details->data['start_date']; ?>" placeholder="<?php _e('Starts from*', THEME_TEXTDOMAIN); ?>"/>
                <div class="input-error-msg"></div>
            </div>
            <div class="col-sm-6 form-group">
                <input type="number" name="announcement_period" class="form-control input-lg" id="announcement_renewal_period" min="1" max="31" value="<?php echo $announcement_details->data['no_of_days']; ?>" placeholder="<?php _e('Number of days*', THEME_TEXTDOMAIN); ?>"/>
                <div class="input-error-msg"></div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-sm-12">
                <input type="text" name="announcment_price" id="announcment_renewal_price" autocomplete="off" class="form-control input-lg" value="<?php echo $announcement_details->data['announcement_price']; ?>" placeholder="<?php _e('Price (in R$)', THEME_TEXTDOMAIN); ?>"/>
                <div class="input-error-msg"></div>
            </div>
        </div>
        
        <div class="row" style="margin-top: 15px;">
            <?php
            if (is_array($announcementImages) && count($announcementImages) > 0):
                foreach ($announcementImages as $eachAnnouncementImage):
                    $imagePath = get_attached_file($eachAnnouncementImage);
                    $imageSrc = wp_get_attachment_image_src($eachAnnouncementImage, 'thumbnail');
                    ?>
                    <div class="col-sm-3">
                        <div class="indiv-announcement-img">
                            <img src="<?php echo ($imagePath) ? $imageSrc[0] : 'https://via.placeholder.com/200x175'; ?>" alt=""/>
                            <a href="javascript:void(0);" class="delete-announcement-image" data-announcement="<?php echo (isset($_GET['announcement_id']) && $_GET['announcement_id'] != '') ? $_GET['announcement_id'] : ''; ?>" data-img="<?php echo base64_encode($eachAnnouncementImage); ?>"><i class="fa fa-times" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <?php
                endforeach;
            endif;
            ?>
        </div>

        <div class="row col-sm-12">
            <!-- Multiple File Uploader -->
            <div id="drag-and-drop-zone" class="dm-uploader p-5">
                <h3 class="mb-5 mt-5 text-muted"><?php _e('Drag &amp; drop files here', THEME_TEXTDOMAIN); ?></h3>

                <div class="btn btn-primary btn-block mb-5">
                    <span><?php _e('Open the file Browser', THEME_TEXTDOMAIN); ?></span>
                    <input type="file" name="file[]" title='Click to add Files' />
                </div>
            </div>
            <div class="card h-100">
                <div class="card-header">
                    File List
                </div>

                <ul class="list-unstyled p-2 d-flex flex-column col" id="files">
                    <li class="text-muted text-center empty"><?php _e('No files uploaded.', THEME_TEXTDOMAIN); ?></li>
                </ul>
            </div>

            <!-- File item template -->
            <script type="text/html" id="files-template">
                <li class="media">
                    <div class="media-body mb-1">
                        <p class="mb-2">
                            <strong>%%filename%%</strong> <?php _e('- Status:', THEME_TEXTDOMAIN); ?> <span class="text-muted"><?php _e('Waiting', THEME_TEXTDOMAIN); ?></span>
                        </p>
                        <div class="progress mb-2">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
                                 role="progressbar"
                                 style="width: 0%" 
                                 aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <hr class="mt-1 mb-1" />
                    </div>
                </li>
                </script>

                <!-- Debug item template -->

                <!-- End of Multiple File Uploader -->
            </div>

            <div class="row">
                <div class="col-sm-12 form-group">
                    <textarea name="announcement_desc" class="form-control" placeholder="<?php _e('Description', THEME_TEXTDOMAIN); ?>"><?php echo $announcement_details->data['content']; ?></textarea>
                    <div class="input-error-msg"></div>
                </div>
            </div>
            <div class="row col-sm-12">
                <div class="cols-m-3">
                    <label><?php _e('Select your plan: ', THEME_TEXTDOMAIN); ?></label>
                </div>
                <div class="col-sm-3 form-group">
                    <label class="control control--radio" for="gold_plan">
                        <input class="form-control announement-plan-selection" id="gold_plan" type="radio" name="announcement_type" value="gold"/>
                        <span></span>
                        <?php _e('Gold', THEME_TEXTDOMAIN); ?>
                        <div class="control__indicator"></div>
                    </label>
                </div>
                <div class="col-sm-3 form-group">
                    <label class="control control--radio" for="silver_plan">
                        <input class="form-control announement-plan-selection" id="silver_plan" type="radio" name="announcement_type" value="silver"/>
                        <span></span>
                        <?php _e('Silver', THEME_TEXTDOMAIN); ?>
                        <div class="control__indicator"></div>
                    </label>
                </div>
                <div class="col-sm-3 form-group">
                    <label class="control control--radio" for="bronze_plan">
                        <input class="form-control announement-plan-selection" id="bronze_plan" type="radio" name="announcement_type" value="bronze"/>
                        <span></span>
                        <?php _e('Bronze', THEME_TEXTDOMAIN); ?>
                        <div class="control__indicator"></div>
                    </label>
                </div>
                <div class="clearfix"></div>
            </div>

            <label><?php _e('Coupon Information', THEME_TEXTDOMAIN); ?></label>
            <div class="row">
                <div class="col-sm-9">
                    <div class="form-group">
                        <input type="text" name="membership_coupon" id="membership_coupon_renew" class="form-control input-lg" value="" autocomplete="off" placeholder="<?php _e('Enter coupon code', THEME_TEXTDOMAIN); ?>" />
                    </div>
                </div>
                <div class="col-sm-3">
                    <button class="btn btn-cs ladda-button btn-lg" data-style="expand-right" id="usr_coupon_renew_sbmt" type="submit"><?php _e('Apply', THEME_TEXTDOMAIN); ?></button>
                </div>
            </div>

            <div class="row col-sm-12">
                <div class="coupon-applied-data"></div>
                <div class="show-announce-plan-price"></div>
            </div>

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

            <div class="form-group text-center">
                <button type="submit" class="btn btn-cs ladda-button btn-lg" data-style="expand-right" name="announcementRenewalSbmt" id="announcementRenewalSbmt"><?php _e('Renew', THEME_TEXTDOMAIN); ?></button>
            </div>
        </form>        
        <?php else: ?>
                <div class="alert alert-danger"><?php _e('You are not allowed to renew any announcement now.', THEME_TEXTDOMAIN); ?></div>
        <?php endif; ?>
    
    </div>
<?php
