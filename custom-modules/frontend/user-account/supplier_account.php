<?php
/*
 * This page consists supplier account updation form
 *  
 */
?>
<?php
$GeneralThemeObject = new GeneralTheme();
$userDetails = $GeneralThemeObject->user_details();
$getProPic = wp_get_attachment_image_src($userDetails->data['pro_pic'], 'full');
$getStates = $GeneralThemeObject->getCities();
$getAllCities = $GeneralThemeObject->getCities($userDetails->data['state']);
$avlSocialLogin = get_user_meta($userDetails->data['user_id'], '_social_login', true);
$getBuisnessCategories = $userDetails->data['buisness_categories'];
$getProductCategories = get_terms(themeFramework::$theme_prefix . 'product_category', ['hide_empty' => FALSE,'parent' => 0]);
?>
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
                    {types: ['geocode']});
            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
                var place_id = place.place_id;
                var lat = place.geometry.viewport.f.f;
                var lng = place.geometry.viewport.b.b;
                jQuery('#' + addressFieldID + '').next('input[type="hidden"]').val(lat + ',' + lng);
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
 
  
</script>

<!-- <script src="http://code.jquery.com/jquery-1.7.1.min.js"></script> -->

<div class="right">
    <form name="supplierAccount" id="supplierAccount" action="javascript:void(0);" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="supplier_account_update">
        <div class="row">
            <div class="text-center account-logo-sec">
                <img src="<?php echo ($getProPic[0]) ? $getProPic[0] : 'https://via.placeholder.com/240x200' ?>" width="200" height="200" id="supplier_logo" alt="User Profile Picture"/>
            </div>
            <div class="col-sm-12 form-group">
                <div class="text-center">
                    <div class="text-center">
                        <div class="fileUpload btn sub-btn">   
                            <input type="file" name="user_logo" id="select_supplier_pro_pic" value="" class="no-bdr mar-top-10 upload">
                            <span><?php _e('Upload Logo', THEME_TEXTDOMAIN); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 form-group">
                <input type="text" class="form-control input-lg" id="supplier_update_fname" name="fname" value="<?php echo $userDetails->data['fname']; ?>" placeholder="<?php _e('Commercial name*', THEME_TEXTDOMAIN); ?>">
                <div class="input-error-msg"></div>
            </div>
            <div class="col-sm-6 form-group">
                <input type="text" class="form-control input-lg" id="supplier_update_lname" name="lname" value="<?php echo $userDetails->data['lname']; ?>" placeholder="<?php _e('Legal name', THEME_TEXTDOMAIN); ?>">
                <div class="input-error-msg"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 form-group">
                <input type="email" class="form-control input-lg" id="supplier_update_email" name="email" readonly value="<?php echo $userDetails->data['email']; ?>" placeholder="<?php _e('Email*', THEME_TEXTDOMAIN); ?>">
                <div class="input-error-msg"></div>
            </div>
            <div class="col-sm-6 form-group">
                <input type="text" class="form-control input-lg" id="supplier_update_phone" name="phone" value="<?php echo $userDetails->data['phone']; ?>" placeholder="<?php _e('Commercial Phone*', THEME_TEXTDOMAIN); ?>">
                <div class="input-error-msg"></div>
            </div>
        </div>

               <!--<div class="row">
                    <div class="col-sm-6 form-group">
                        <input type="text" class="form-control input-lg" id="supplier_update_cpf"   name="cpf" autocomplete="off" value="<?php //echo $userDetails->data['cpf']; ?>" placeholder="<?php _e('CPF*', THEME_TEXTDOMAIN); ?>">
                        <div class="input-error-msg"></div>
                    </div>
                    <div class="col-sm-6 form-group">
                        <input type="text" class="form-control input-lg" id="supplier_update_cnpj"   name="cnpj" value="<?php //echo $userDetails->data['cnpj']; ?>" autocomplete="off" placeholder="<?php _e('CNPJ*', THEME_TEXTDOMAIN); ?>">
                        <div class="input-error-msg"></div>
                    </div>
                </div>-->

        <div class="row">
            <div class="col-sm-6 form-group">
                <select name="state" class="supplier-update-user-state state-city-selection chosen" id="update_state">
                    <option value=""><?php _e('Choose your state', THEME_TEXTDOMAIN); ?></option>
                    <?php
                    if (is_array($getStates) && count($getStates) > 0) :
                        foreach ($getStates as $eachCity) :
                            ?>
                            <option value="<?php echo $eachCity->term_id; ?>" <?php echo ($userDetails->data['state'] == $eachCity->term_id) ? 'selected' : '' ?>><?php echo $eachCity->name; ?></option>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </select>
                <div class="input-error-msg"></div>
            </div>
            <div class="col-sm-6 form-group">
                <select name="city" class="supplier-update-user-city select-your-city chosen" id="update_city">
                    <option value=""><?php _e('Choose your city', THEME_TEXTDOMAIN); ?></option>
                    <?php
                    if (is_array($getAllCities) && count($getAllCities) > 0) :
                        foreach ($getAllCities as $eachCity) :
                            ?>
                            <option value="<?php echo $eachCity->term_id; ?>" <?php echo ($userDetails->data['city'] == $eachCity->term_id) ? 'selected' : '' ?>><?php echo $eachCity->name; ?></option>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </select>
                <div class="input-error-msg"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6 form-group">
                <input type="text" class="form-control input-lg address_field" id="supplier_address" name="address" value="<?php echo $userDetails->data['user_address']; ?>" placeholder="<?php _e('Address*', THEME_TEXTDOMAIN); ?>"/>
                <input type="hidden" id="supplier_addressloc" name="addressloc" value="<?php echo $userDetails->data['address_loc']; ?>"/>
                <input type="hidden" id="supplier_addressID" name="addressID" value="<?php echo $userDetails->data['address_id']; ?>"/>
                <div class="input-error-msg"></div>
            </div>
            <div class="col-sm-6 form-group">
                <input type="url" class="form-control input-lg" id="where_to_buy_address" name="where_to_buy_address" value="<?php echo $userDetails->data['where_to_buy_address']; ?>" placeholder="<?php _e('Company Website*', THEME_TEXTDOMAIN); ?>"/>
                <div class="input-error-msg"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 form-group">
                <lable><?php _e('Select categories you supply', THEME_TEXTDOMAIN); ?></lable>
                <select name="supplier_category[]" class="chosen update-supplier-category"  multiple>
                     <?php if (is_array($getProductCategories) && count($getProductCategories) > 0): ?>                        
                        <?php foreach ($getProductCategories as $eachCategory): ?>
                        <?php $getProductSubCategories = get_terms(themeFramework::$theme_prefix . 'product_category', ['hide_empty' => FALSE, 'parent' => $eachCategory->term_id]); ?>
                                <option value="<?php echo $eachCategory->term_id; ?>" <?php echo (is_array($getBuisnessCategories) && count($getBuisnessCategories) > 0 && in_array($eachCategory->term_id, $getBuisnessCategories)) ? 'selected' : ''; ?>><?php echo $eachCategory->name; ?></option>
                                <?php  if (is_array($getProductSubCategories) && count($getProductSubCategories) > 0):
                                            foreach ($getProductSubCategories as $eachProductSubCategory):
                                            ?>
                                            <option value="<?php echo $eachProductSubCategory->term_id; ?>" <?php echo (in_array($eachProductSubCategory->term_id, $getBuisnessCategories)) ? 'selected' : ''; ?>"><?php echo '--'.$eachProductSubCategory->name; ?></option>
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    </select>
                                     <div class="input-error-msg"></div>
            </div>
        </div>
  <!--div class="row">
            <div class="col-sm-12 form-group">
                <lable><?php// _e('Select categories you supply', THEME_TEXTDOMAIN); ?></lable>
                <select name="supplier_category[]" class="update-supplier-category chosen" multiple>
                    <?php// if (is_array($getProductCategories) && count($getProductCategories) > 0): ?>                        
                        <?php /*foreach ($getProductCategories as $eachCategory): ?>
                            <option value="<?php echo $eachCategory->term_id; ?>" <?php echo (is_array($getBuisnessCategories) && count($getBuisnessCategories) > 0 && in_array($eachCategory->term_id, $getBuisnessCategories)) ? 'selected' : ''; ?>><?php echo $eachCategory->name; ?></option>
                        <?php endforeach; ?>
                    <?php endif;*/ ?>
                </select>
                <div class="input-error-msg"></div>
            </div>
        </div-->
        <div class="row">
            <div class="col-sm-12 form-group">
                <textarea name="bio" class="form-control" placeholder="<?php _e('Tell us something about your company', THEME_TEXTDOMAIN); ?>"><?php echo $userDetails->data['bio']; ?></textarea>
                <div class="input-error-msg"></div>
            </div>
        </div>

    

<!--own edit-->
<label><?php _e('Select your type', THEME_TEXTDOMAIN); ?></label>
            <div class="row type-error">
                <div class="col-sm-6 form-group">
                    <label class="control control--radio" for="physical_person">
                    <?php  if($userDetails->data['supplier_type']== 1) {?>
                        <input class="form-control supplier-type" id="physical_person" type="radio" name="supplier_type" value="1" checked/>
                    <?php } else { ?>
                        <input class="form-control supplier-type" id="physical_person" type="radio" name="supplier_type" value="1" />
                    <?php } ?>
                        <span></span>
                        <?php _e('Physical person', THEME_TEXTDOMAIN); ?>
                        <div class="control__indicator"></div>
                    </label>
                    <!--<<div class="input-error-msg"></div>-->
               </div>
                <div class="col-sm-6 form-group">
                    <label class="control control--radio" for="judicial_person">
                    <?php  if($userDetails->data['supplier_type']== 2) {?>
                        <input class="form-control supplier-type" id="judicial_person" type="radio" name="supplier_type" value="2" checked/>
                    <?php } else {?>
                        <input class="form-control supplier-type" id="judicial_person" type="radio" name="supplier_type" value="2"/>
                    <?php } ?>
                        <span></span>
                        <?php _e('Judicial person', THEME_TEXTDOMAIN); ?>
                        <div class="control__indicator"></div>
                    </label>
                </div>
            </div>
            <div class="input-error-msg"></div>
            <?php  if($userDetails->data['supplier_type']== 1) {?>
            <div class="row cpf-display">
                <div class="col-sm-12 form-group">
                    <input type="text" class="form-control input-lg" id="supplier_cpf" name="cpf" autocomplete="off" value="<?php echo $userDetails->data['cpf']; ?>" placeholder="<?php _e('CPF*', THEME_TEXTDOMAIN); ?>">
                    <div class="input-error-msg"></div>
                </div>
            </div>
            <?php }else{ ?>
                <div class="row cpf-display" style="display: none;">
                <div class="col-sm-12 form-group">
                    <input type="text" class="form-control input-lg" id="supplier_cpf" name="cpf" autocomplete="off" value="<?php echo $userDetails->data['cpf']; ?>" placeholder="<?php _e('CPF*', THEME_TEXTDOMAIN); ?>">
                    <div class="input-error-msg"></div>
                </div>
            </div>
            <?php } ?>
            <?php  if($userDetails->data['supplier_type']== 2) {?>
            <div class="row cnpj-display">
                <div class="col-sm-12 form-group">
                    <input type="text" class="form-control input-lg" id="supplier_cnpj" name="cnpj" value="<?php echo $userDetails->data['cnpj']; ?>" autocomplete="off" placeholder="<?php _e('CNPJ*', THEME_TEXTDOMAIN); ?>">
                    <div class="input-error-msg"></div>
                </div>
            </div>
            <?php }else{ ?>
                <div class="row cnpj-display" style="display: none;">
                <div class="col-sm-12 form-group">
                    <input type="text" class="form-control input-lg" id="supplier_cnpj" name="cnpj" value="<?php echo $userDetails->data['cnpj']; ?>" autocomplete="off" placeholder="<?php _e('CNPJ*', THEME_TEXTDOMAIN); ?>">
                    <div class="input-error-msg"></div>
                </div>
            </div>
            <?php } ?>
<!--end own edit-->

        <div class="form-group text-center">
            <button type="submit" class="btn btn-cs ladda-button btn-lg" data-style="expand-right" name="supplierAccountSbmt" id="supplierAccountSbmt"><?php _e('Update', THEME_TEXTDOMAIN); ?></button>
        </div>
    </form>
</div>
<?php if (!$avlSocialLogin): ?>
    <div class="right">
        <form name="changePassword" id="changePassword" action="javascript:void(0);" method="post">
            <input type="hidden" name="action" value="change_password">
            <div class="form-group">
                <input type="password" class="form-control input-lg" id="oldpassword" name="oldpassword" value="" placeholder="<?php _e('Old password*', THEME_TEXTDOMAIN) ?>">
                <div class="input-error-msg"></div>
            </div>
            <hr />
            <div class="form-group">
                <input type="password" class="form-control input-lg" id="newpassword" name="password" value="" placeholder="<?php _e('New password*', THEME_TEXTDOMAIN) ?>">
                <div class="input-error-msg"></div>
            </div>
            <div class="form-group">
                <input type="password" class="form-control input-lg" id="cnfnewpassword" name="cnfpassword" value="" placeholder="<?php _e('Confirm new password*', THEME_TEXTDOMAIN) ?>">
                <div class="input-error-msg"></div>
            </div>
            <div class="form-group text-center btn-sec">
                <button type="submit" class="btn btn-cs ladda-button btn-lg" data-style="expand-right" name="productorChangePassword" id="productorChangePassword"><?php _e('Change password', THEME_TEXTDOMAIN); ?></button>
            </div>
        </form>

    </div>
<?php endif; ?>
<?php
