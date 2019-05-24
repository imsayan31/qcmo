<?php
/*
 * Announcement Creation
 * 
 */
$GeneralThemeObject = new GeneralTheme();
$AnnouncementObject = new classAnnouncement();
$userDetails = $GeneralThemeObject->user_details();
$getProductCategories = get_terms(themeFramework::$theme_prefix . 'product_category', ['hide_empty' => FALSE, 'parent' => 0]);

$getAnnouncementEnabledCities = $AnnouncementObject->getAnnouncementEnabledCities();
$getSupplierAnnEnabled = get_term_meta($userDetails->data['city'], '_enable_announcement_for_suppliers', TRUE);
$getCustomerAnnEnabled = get_term_meta($userDetails->data['city'], '_enable_announcement_for_customers', TRUE);
$getAllCities = $GeneralThemeObject->getCities($userDetails->data['state']);
$getStates = $GeneralThemeObject->getCities();
$get_announcement_price = get_option('_announcement_price');

// if(is_array($getProductCategories) && count($getProductCategories) > 0){
//     foreach ($getProductCategories as $eachProductCategory) {
//         $termCreated = wp_insert_term($eachProductCategory->name, themeFramework::$theme_prefix.'announcement_category');
//         $getProSubCats = get_terms(themeFramework::$theme_prefix . 'product_category',['hide_empty' => FALSE, 'parent' => $eachProductCategory->term_id]);
//         if(is_array($getProSubCats) && count($getProSubCats) > 0){
//             foreach ($getProSubCats as $eachProSubCat) {
//                 wp_insert_term($eachProSubCat->name, themeFramework::$theme_prefix.'announcement_category', ['parent' => $termCreated['term_id']]);
//             }
//         }
//     }
// }
?>
<style>
    .mycontainer .chosen-container-multi .chosen-choices {
    height: 46px !important;
   
}

</style>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDnO0V9m09BUXB-JqwuuFno7efIs4FD5nM&libraries=places&callback=initAutocompleteSupplier" async defer></script>
<script type="text/javascript">
    var placeSearch, autocomplete;

    function initAutocompleteSupplier() {

        var address_field = jQuery('.address_field');
        var i;
        for (i = 0; i < address_field.length; i++) {
            var addressFieldID = address_field[i].id;
            autocomplete = new google.maps.places.Autocomplete(
                    (document.getElementById(addressFieldID)),
                    {types: ['address']});
            autocomplete.addListener('place_changed', function () {
                var place = this.getPlace();
                var place_id = place.place_id;
                var lat = place.geometry.location.lat();
                var lng = place.geometry.location.lng();
                jQuery('#' + addressFieldID + '').next('input[type="hidden"]').val(Number(lat).toFixed(7) + ',' + Number(lng).toFixed(7));
                jQuery('#' + addressFieldID + '').next('input[type="hidden"]').next('input[type="hidden"]').val(place_id);
            });
            /*autocomplete.setComponentRestrictions(
             {'country': ['br']});*/
        }
    }

    function geolocate() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var geolocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                var circle = new google.maps.Circle({
                    center: geolocation,
                    radius: position.coords.accuracy
                });
                autocomplete.setBounds(circle.getBounds());
            });
        }
    }
</script>
<div class="right">
    <?php if (is_array($getAnnouncementEnabledCities) && count($getAnnouncementEnabledCities) > 0 && in_array($userDetails->data['city'], $getAnnouncementEnabledCities) && $userDetails->data['role'] == 'subscriber' && $getCustomerAnnEnabled == 1): ?>
        <form name="announcementCreationFrm" id="announcementCreationFrm" method="post" action="javascript:void(0);" enctype="multipart/form-data">
            <input type="hidden" name="action" value="announcement_create"/>
            <input type="hidden" name="property_main_images" class="property_main_images" value=""/>
            <div class="row">
                <div class="col-sm-6 form-group">
                    <input type="text" name="announcement_name" autocomplete="off" class="form-control input-lg" id="announcement_name" value="" placeholder="<?php _e('Announcement name*', THEME_TEXTDOMAIN); ?>"/>
                    <div class="input-error-msg"></div>
                </div>
                <div class="col-sm-6 form-group mycontainer">
                    <select name="announcement_category[]" class="chosen announcement_category" multiple>
                        <option value=""><?php _e('-Select Announcement Category-', THEME_TEXTDOMAIN); ?></option>
                        <?php
                        if (is_array($getProductCategories) && count($getProductCategories) > 0):
                            foreach ($getProductCategories as $eachProductCategory):
                                $getProductSubCategories = get_terms(themeFramework::$theme_prefix . 'product_category', ['hide_empty' => FALSE, 'parent' => $eachProductCategory->term_id]);
                                ?>
                                <option value="<?php echo $eachProductCategory->slug; ?>"><?php echo $eachProductCategory->name; ?></option>
                                <?php
                                if (is_array($getProductSubCategories) && count($getProductSubCategories) > 0):
                                    foreach ($getProductSubCategories as $eachSubCategory):
                                        ?>
                                        <option value="<?php echo $eachSubCategory->slug; ?>"><?php _e('---', THEME_TEXTDOMAIN); ?><?php echo $eachSubCategory->name; ?></option>
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
                    <select name="announcement_state" class="chosen state-selection" id="announcement_states" data-target="announcement_city">
                        <option value=""><?php _e('Selecione o estado', THEME_TEXTDOMAIN); ?></option>
                        <?php
                        if (is_array($getStates) && count($getStates) > 0) :
                            foreach ($getStates as $eachCountry) :
                                ?>
                                <option <?php selected($eachCountry->term_id, $userDetails->data['state'], TRUE) ?> value="<?php echo $eachCountry->term_id; ?>"><?php echo $eachCountry->name; ?></option>
                                
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </select>
                    <div class="input-error-msg"></div>
                </div>
                <div class="col-sm-6 form-group">
                    <select name="announcement_city" class="chosen city-selection" id="announcement_city">
                        <option value=""><?php _e('Selecione a cidade', THEME_TEXTDOMAIN); ?></option>
                        <?php
                        if (is_array($getAllCities) && count($getAllCities) > 0) :
                            foreach ($getAllCities as $eachCity) :
                                ?>
                                <option <?php selected($eachCity->term_id, $userDetails->data['city'], TRUE) ?> value="<?php echo $eachCity->term_id; ?>"><?php echo $eachCity->name; ?></option>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </select>
                    <div class="input-error-msg"></div>
                </div>
            </div>
            <!-- Address location -->
            <div class="row">
                <div class="col-sm-12 form-group">
                    <input type="text" class="form-control input-lg address_field" id="announcement_address" name="address" value="<?php echo $userDetails->data['user_address']; ?>" placeholder="<?php _e('Address*', THEME_TEXTDOMAIN); ?>"/>
                    <input type="hidden" id="announcement_addressloc" name="addressloc" value="<?php echo $userDetails->data['address_loc']; ?>"/>
                    <input type="hidden" id="announcement_addressID" name="addressID" value="<?php echo $userDetails->data['address_id']; ?>"/>
                    <div class="input-error-msg"></div>
                </div>
            </div>
            <!-- End Address location -->
            <div class="row">
                <div class="col-sm-6 form-group">
                    <input type="number" name="announcement_period" class="form-control input-lg" id="announcement_period" min="1" max="31" value="" placeholder="<?php _e('Número de dias*', THEME_TEXTDOMAIN); ?>"/>
                    <div class="input-error-msg"></div>
                </div>
                <div class="col-sm-6 form-group">
                    <input type="text" name="announcment_price" id="announcment_price" autocomplete="off" class="form-control input-lg" value="" placeholder="<?php _e('Price (in R$)', THEME_TEXTDOMAIN); ?>"/>
                    <div class="input-error-msg"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 form-group">
                    <input type="text" name="announcement_date" class="form-control input-lg" autocomplete="off" id="announcement_create_date" value="" placeholder="<?php _e('Announcement Start Date*', THEME_TEXTDOMAIN); ?>"/>
                    <div class="input-error-msg"></div>
                </div>
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
                        Lista de Arquivos
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
                        <textarea name="announcement_desc" class="form-control" placeholder="<?php _e('Description', THEME_TEXTDOMAIN); ?>"></textarea>
                        <div class="input-error-msg"></div>
                    </div>
                </div>
            
                <div class="membership-plan-list">
                         <div class="cols-m-3">
                            <label><?php _e('Select your plan: ', THEME_TEXTDOMAIN); ?></label>
                        </div>
                         <div class="row">
                            <div class="col-sm-4">
                                <div class="plan-list announcement-plan-list plan-marker" id="gold">
                                    <div class="plane-badge"><img src="<?php echo ASSET_URL . '/images/gold-badge.png'; ?>"/></div>
                                    <div class="plan-title"><?php _e('Gold', THEME_TEXTDOMAIN); ?></div>
                                    <div class="plan-price-list"><?php _e('1 Week Cost: ', THEME_TEXTDOMAIN); ?><span><?php echo ($get_announcement_price['gold']['7'] && $get_announcement_price['gold']['7'] > 0) ? 'R$ ' . number_format($get_announcement_price['gold']['7'], 2) . '/dia' : 'R$ 0.00/dia'; ?></span></div>
                                    <div class="plan-price-list"><?php _e('2 Weeks Cost: ', THEME_TEXTDOMAIN); ?><span><?php echo ($get_announcement_price['gold']['14'] && $get_announcement_price['gold']['14'] > 0) ? 'R$ ' . number_format($get_announcement_price['gold']['14'], 2) . '/dia' : 'R$ 0.00/dia'; ?></span></div>
                                    <div class="plan-price-list"><?php _e('1 Month Cost: ', THEME_TEXTDOMAIN); ?><span><?php echo ($get_announcement_price['gold']['30'] && $get_announcement_price['gold']['30'] > 0) ? 'R$ ' . number_format($get_announcement_price['gold']['30'], 2) . '/dia' : 'R$ 0.00/dia'; ?></span></div>
                                    <div class="plan-price-list"><?php _e('10x more visibility on the main page ', THEME_TEXTDOMAIN); ?></div>
                                    <div class="plan-price-list"><?php _e('10x more Hightlight on announces search ', THEME_TEXTDOMAIN); ?></div>
                                    <div class="plan-price-list"><?php _e('Brither tooltips on announces maps ', THEME_TEXTDOMAIN); ?></div>
                                    <div class="plan-price-list"><?php _e('Maximum no of announcement:Umlimited ', THEME_TEXTDOMAIN); ?></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="plan-list announcement-plan-list plan-marker" id="silver">
                                    <div class="plane-badge"><img src="<?php echo ASSET_URL . '/images/silver-badge.png'; ?>"/></div>
                                    <div class="plan-title"><?php _e('Silver', THEME_TEXTDOMAIN); ?></div>
                                    <div class="plan-price-list"><?php _e('1 Week Cost: ', THEME_TEXTDOMAIN); ?><span><?php echo ($get_announcement_price['silver']['7'] && $get_announcement_price['silver']['7'] > 0) ? 'R$ ' . number_format($get_announcement_price['silver']['7'], 2) . '/dia' : 'R$ 0.00/dia'; ?></span></div>
                                   <div class="plan-price-list"><?php _e('2 Weeks Cost: ', THEME_TEXTDOMAIN); ?><span><?php echo ($get_announcement_price['silver']['14'] && $get_announcement_price['silver']['14'] > 0) ? 'R$ ' . number_format($get_announcement_price['silver']['14'], 2) . '/dia' : 'R$ 0.00/dia'; ?></span></div>
                                     <div class="plan-price-list"><?php _e('1 Month Cost: ', THEME_TEXTDOMAIN); ?><span><?php echo ($get_announcement_price['silver']['30'] && $get_announcement_price['silver']['30'] > 0) ? 'R$ ' . number_format($get_announcement_price['silver']['30'], 2) . '/dia' : 'R$ 0.00/dia'; ?></span></div>
                                    <div class="plan-price-list"><?php _e('2x more visibility than bronze ', THEME_TEXTDOMAIN); ?></div>
                                    <div class="plan-price-list"><?php _e('Maximum no of announcement: ', THEME_TEXTDOMAIN); ?><span><?php echo ($get_announcement_price['silver']['no_of_post']) ? $get_announcement_price['silver']['no_of_post'] : 'N/A'; ?></span></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="plan-list announcement-plan-list plan-marker" id="bronze">
                                    <div class="plane-badge"><img src="<?php echo ASSET_URL . '/images/bronze-badge.png'; ?>"/></div>
                                    <div class="plan-title"><?php _e('Bronze', THEME_TEXTDOMAIN); ?></div>
                                    <div class="plan-price-list"><?php _e('1 Week Cost: ', THEME_TEXTDOMAIN); ?><span><?php _e('Free', THEME_TEXTDOMAIN); ?></span></div>
                                    <div class="plan-price-list"><?php _e('2 Weeks Cost: ', THEME_TEXTDOMAIN); ?><span><?php _e('Free', THEME_TEXTDOMAIN); ?></span></div>
                                    <div class="plan-price-list"><?php _e('1 Month Cost: ', THEME_TEXTDOMAIN); ?><span><?php _e('Free', THEME_TEXTDOMAIN); ?></span></div>
                                    <div class="plan-price-list"><?php _e('Maximum no of announcement: ', THEME_TEXTDOMAIN); ?><span><?php echo ($get_announcement_price['bronze']['no_of_post']) ? $get_announcement_price['bronze']['no_of_post'] : 'N/A'; ?></span></div>
                                </div>
                            </div>
                        </div>
                </div>
            
                <div class="row col-sm-12" style="display: none;">
                    <div class="cols-m-3">
                        <label><?php _e('Select your plan: ', THEME_TEXTDOMAIN); ?></label>
                    </div>
                    <div class="col-sm-3 form-group" style="padding-left: 0;">
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
                <div class="row">
                    <div class="col-sm-12">
                        <div class="show-announce-plan-price"></div>
                    </div>
                </div>    
                <div class="row">
                    <div class="col-sm-12">
                        <label class="control control--checkbox" for="terms_cond_plan">
                            <input class="form-control" id="terms_cond_plan" type="checkbox"  name="announcement_terms_condi" value="1"/>
                            <span></span>
                            <a target="blank" href="<?php echo TERMS_PAGE; ?>">
                                <?php _e('I have read and agreed to all terms and conditions', THEME_TEXTDOMAIN); ?>
                            </a>    
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                </div>
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-cs ladda-button btn-lg" data-style="expand-right" name="announcementCreationSbmt" id="announcementCreationSbmt"><?php _e('Add', THEME_TEXTDOMAIN); ?></button>
                </div>
            </form>
        <?php elseif (is_array($getAnnouncementEnabledCities) && count($getAnnouncementEnabledCities) > 0 && in_array($userDetails->data['city'], $getAnnouncementEnabledCities) && $userDetails->data['role'] == 'supplier' && $getSupplierAnnEnabled == 1): ?>
            <form name="announcementCreationFrm" id="announcementCreationFrm" method="post" action="javascript:void(0);" enctype="multipart/form-data">
                <input type="hidden" name="action" value="announcement_create"/>
                <input type="hidden" name="property_main_images" class="property_main_images" value=""/>
                <div class="row">
                    <div class="col-sm-6 form-group">
                        <input type="text" name="announcement_name" class="form-control input-lg" autocomplete="off" id="announcement_name" value="" placeholder="<?php _e('Announcement name*', THEME_TEXTDOMAIN); ?>"/>
                        <div class="input-error-msg"></div>
                    </div>
                    <div class="col-sm-6 form-group">
                        <select name="announcement_category[]" class="chosen announcement_category" multiple>
                           <option value=""><?php _e('-Select Announcement Category-', THEME_TEXTDOMAIN); ?></option>
                          <?php
                            if (is_array($getProductCategories) && count($getProductCategories) > 0):
                                foreach ($getProductCategories as $eachProductCategory):
                                    $getProductSubCategories = get_terms(themeFramework::$theme_prefix . 'product_category', ['hide_empty' => FALSE, 'parent' => $eachProductCategory->term_id]);
                                    ?>
                                    <option value="<?php echo $eachProductCategory->slug; ?>"><?php echo $eachProductCategory->name; ?></option>
                                    <?php
                                    if (is_array($getProductSubCategories) && count($getProductSubCategories) > 0):
                                        foreach ($getProductSubCategories as $eachSubCategory):
                                            ?>
                                            <option value="<?php echo $eachSubCategory->slug; ?>"><?php _e('---', THEME_TEXTDOMAIN); ?><?php echo $eachSubCategory->name; ?></option>
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
                        <select name="announcement_state" class="chosen state-selection" id="announcement_states" data-target="announcement_city">
                            <option value=""><?php _e('Selecione o estado', THEME_TEXTDOMAIN); ?></option>
                            <?php
                            if (is_array($getStates) && count($getStates) > 0) :
                                foreach ($getStates as $eachCountry) :
                                    ?>
                                    <option <?php selected($eachCountry->term_id, $userDetails->data['state'], TRUE) ?> value="<?php echo $eachCountry->term_id; ?>"><?php echo $eachCountry->name; ?></option>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                        </select>
                        <div class="input-error-msg"></div>
                    </div>
                    <div class="col-sm-6 form-group">
                        <select name="announcement_city" class="chosen city-selection" id="announcement_city">
                            <option value=""><?php _e('Selecione a cidade', THEME_TEXTDOMAIN); ?></option>
                            <?php
                            if (is_array($getAllCities) && count($getAllCities) > 0) :
                                foreach ($getAllCities as $eachCity) :
                                    ?>
                                    <option <?php selected($eachCity->term_id, $userDetails->data['city'], TRUE) ?> value="<?php echo $eachCity->term_id; ?>"><?php echo $eachCity->name; ?></option>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                        </select>
                        <div class="input-error-msg"></div>
                    </div>
                </div>
  
                <!-- Address location -->
                <div class="row">
                    <div class="col-sm-12 form-group">
                        <input type="text" class="form-control input-lg address_field" id="announcement_address" name="address" value="<?php echo $userDetails->data['user_address']; ?>" placeholder="<?php _e('Address*', THEME_TEXTDOMAIN); ?>"/>
                        <input type="hidden" id="announcement_addressloc" name="addressloc" value="<?php echo $userDetails->data['address_loc']; ?>"/>
                        <input type="hidden" id="announcement_addressID" name="addressID" value="<?php echo $userDetails->data['address_id']; ?>"/>
                        <div class="input-error-msg"></div>
                    </div>
                </div>
                <!-- End Address location -->
                <div class="row">
                    <div class="col-sm-6 form-group">
                        <input type="number" name="announcement_period" class="form-control input-lg" id="announcement_period" min="1" max="31" value="" placeholder="<?php _e('Número de dias*', THEME_TEXTDOMAIN); ?>"/>
                        <div class="input-error-msg"></div>
                    </div>
                    <div class="col-sm-6 form-group">
                        <input type="text" name="announcment_price" id="announcment_price" autocomplete="off" class="form-control input-lg" value="" placeholder="<?php _e('Price (in R$)', THEME_TEXTDOMAIN); ?>"/>
                        <div class="input-error-msg"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 form-group">
                        <input type="text" name="announcement_date" class="form-control input-lg" autocomplete="off" id="announcement_create_date" value="" placeholder="<?php _e('Announcement Start Date*', THEME_TEXTDOMAIN); ?>"/>
                        <div class="input-error-msg"></div>
                    </div>
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
                            <?php _e('File List', THEME_TEXTDOMAIN); ?>
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
                            <textarea name="announcement_desc" class="form-control" placeholder="<?php _e('Description', THEME_TEXTDOMAIN); ?>"></textarea>
                            <div class="input-error-msg"></div>
                        </div>
                    </div>

                    <div class="membership-plan-list">
                         <div class="cols-m-3">
                            <label><?php _e('Select your plan: ', THEME_TEXTDOMAIN); ?></label>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="plan-list announcement-plan-list plan-marker" id="gold">
                                    <div class="plane-badge"><img src="<?php echo ASSET_URL . '/images/gold-badge.png'; ?>"/></div>
                                    <div class="plan-title"><?php _e('Gold', THEME_TEXTDOMAIN); ?></div>
                                    <div class="plan-price-list"><?php _e('1 Week Cost: ', THEME_TEXTDOMAIN); ?><span><?php echo ($get_announcement_price['gold']['7'] && $get_announcement_price['gold']['7'] > 0) ? 'R$ ' . number_format($get_announcement_price['gold']['7'], 2) . '/dia' : 'R$ 0.00/dia'; ?></span></div>
                                    <div class="plan-price-list"><?php _e('2 Weeks Cost: ', THEME_TEXTDOMAIN); ?><span><?php echo ($get_announcement_price['gold']['14'] && $get_announcement_price['gold']['14'] > 0) ? 'R$ ' . number_format($get_announcement_price['gold']['14'], 2) . '/dia' : 'R$ 0.00/dia'; ?></span></div>
                                    <div class="plan-price-list"><?php _e('1 Month Cost: ', THEME_TEXTDOMAIN); ?><span><?php echo ($get_announcement_price['gold']['30'] && $get_announcement_price['gold']['30'] > 0) ? 'R$ ' . number_format($get_announcement_price['gold']['30'], 2) . '/dia' : 'R$ 0.00/dia'; ?></span></div>
                                    <div class="plan-price-list"><?php _e('10x more visibility on the main page ', THEME_TEXTDOMAIN); ?></div>
                                    <div class="plan-price-list"><?php _e('10x more Hightlight on announces search ', THEME_TEXTDOMAIN); ?></div>
                                    <div class="plan-price-list"><?php _e('Brither tooltips on announces maps ', THEME_TEXTDOMAIN); ?></div>
                                    <div class="plan-price-list"><?php _e('Maximum no of announcement:Umlimited ', THEME_TEXTDOMAIN); ?></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="plan-list announcement-plan-list plan-marker" id="silver">
                                    <div class="plane-badge"><img src="<?php echo ASSET_URL . '/images/silver-badge.png'; ?>"/></div>
                                    <div class="plan-title"><?php _e('Silver', THEME_TEXTDOMAIN); ?></div>
                                    <div class="plan-price-list"><?php _e('1 Week Cost: ', THEME_TEXTDOMAIN); ?><span><?php echo ($get_announcement_price['silver']['7'] && $get_announcement_price['silver']['7'] > 0) ? 'R$ ' . number_format($get_announcement_price['silver']['7'], 2) . '/dia' : 'R$ 0.00/dia'; ?></span></div>
                                   <div class="plan-price-list"><?php _e('2 Weeks Cost: ', THEME_TEXTDOMAIN); ?><span><?php echo ($get_announcement_price['silver']['14'] && $get_announcement_price['silver']['14'] > 0) ? 'R$ ' . number_format($get_announcement_price['silver']['14'], 2) . '/dia' : 'R$ 0.00/dia'; ?></span></div>
                                     <div class="plan-price-list"><?php _e('1 Month Cost: ', THEME_TEXTDOMAIN); ?><span><?php echo ($get_announcement_price['silver']['30'] && $get_announcement_price['silver']['30'] > 0) ? 'R$ ' . number_format($get_announcement_price['silver']['30'], 2) . '/dia' : 'R$ 0.00/dia'; ?></span></div>
                                    <div class="plan-price-list"><?php _e('2x more visibility than bronze ', THEME_TEXTDOMAIN); ?></div>
                                    <div class="plan-price-list"><?php _e('Maximum no of announcement: ', THEME_TEXTDOMAIN); ?><span><?php echo ($get_announcement_price['silver']['no_of_post']) ? $get_announcement_price['silver']['no_of_post'] : 'N/A'; ?></span></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="plan-list announcement-plan-list plan-marker" id="bronze">
                                    <div class="plane-badge"><img src="<?php echo ASSET_URL . '/images/bronze-badge.png'; ?>"/></div>
                                    <div class="plan-title"><?php _e('Bronze', THEME_TEXTDOMAIN); ?></div>
                                    <div class="plan-price-list"><?php _e('1 Week Cost: ', THEME_TEXTDOMAIN); ?><span><?php _e('Free', THEME_TEXTDOMAIN); ?></span></div>
                                    <div class="plan-price-list"><?php _e('2 Weeks Cost: ', THEME_TEXTDOMAIN); ?><span><?php _e('Free', THEME_TEXTDOMAIN); ?></span></div>
                                    <div class="plan-price-list"><?php _e('1 Month Cost: ', THEME_TEXTDOMAIN); ?><span><?php _e('Free', THEME_TEXTDOMAIN); ?></span></div>
                                    <div class="plan-price-list"><?php _e('Maximum no of announcement: ', THEME_TEXTDOMAIN); ?><span><?php echo ($get_announcement_price['bronze']['no_of_post']) ?></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="row col-sm-12" style="display:none;">

                        <div class="cols-m-3">
                            <label><?php _e('Select your plan: ', THEME_TEXTDOMAIN); ?></label>
                        </div>
                        <div class="col-sm-3 form-group" style="padding-left: 0;">
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
                   
                    <div class="row">
                        <div class="row col-sm-12">
                            <div class="show-announce-plan-price"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="control control--checkbox" for="terms_cond_plan">
                                <input class="form-control" id="terms_cond_plan" type="checkbox"  name="announcement_terms_condi" value="1"/>
                                <span></span>
                                <a target="blank" href="<?php echo TERMS_PAGE; ?>">
                                    <?php _e('I have read and agreed to all terms and conditions', THEME_TEXTDOMAIN); ?>
                                </a>    
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-cs ladda-button btn-lg" data-style="expand-right" name="announcementCreationSbmt" id="announcementCreationSbmt"><?php _e('Add', THEME_TEXTDOMAIN); ?></button>
                    </div>
                </form>
            <?php else: ?>
                <div class="alert alert-danger"><?php _e('You are not allowed to post any announcement now.', THEME_TEXTDOMAIN); ?></div>
            <?php endif; ?>

        </div>
<?php
