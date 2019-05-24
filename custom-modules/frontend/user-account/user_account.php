<?php
/*
 * This page consists user account updation form
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
// echo $userDetails->data['user_id'];
// echo $userDetails->data['city'];
// echo "<pre>";
// print_r($userDetails);
// echo "</pre>";
// echo "<pre>";
// print_r($getAllCities);
// echo "</pre>";
?>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDnO0V9m09BUXB-JqwuuFno7efIs4FD5nM&libraries=places&callback=initAutocomplete" async defer></script>
<script type="text/javascript">
    var placeSearch, autocomplete;
    function initAutocomplete() {
        autocomplete = new google.maps.places.Autocomplete(
                (document.getElementById('update_address')),
                {types: ['geocode']});
        autocomplete.addListener('place_changed', fillInAddress);
    }

    function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();
        var place_id = place.place_id;
        var lat = place.geometry.viewport.f.f;
        var lng = place.geometry.viewport.b.b;
        jQuery('#addressID').val(place_id);
        jQuery('#addressloc').val(lat + ',' + lng);
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
    <form name="productorAccount" id="productorAccount" action="javascript:void(0);" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="productor_account_update">
        <div class="row">
            <div class="text-center account-logo-sec">
                <img src="<?php echo ($getProPic[0]) ? $getProPic[0] : 'https://via.placeholder.com/240x200' ?>" width="200" height="200" id="user_logo" alt="User Profile Picture"/>
            </div>
            <div class="col-sm-12 form-group">
                <div class="text-center">
                    <div class="text-center">
                        <div class="fileUpload btn sub-btn">   
                            <input type="file" name="user_logo" id="select_pro_pic" value="" class="no-bdr mar-top-10 upload">
                            <span><?php _e('Upload Profile Picture', THEME_TEXTDOMAIN); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 form-group">
                <input type="text" class="form-control input-lg" id="update_fname" name="fname" value="<?php echo $userDetails->data['fname']; ?>" placeholder="<?php _e('First name*', THEME_TEXTDOMAIN); ?>">
                <div class="input-error-msg"></div>
            </div>
            <div class="col-sm-6 form-group">
                <input type="text" class="form-control input-lg" id="update_lname" name="lname" value="<?php echo $userDetails->data['lname']; ?>" placeholder="<?php _e('Last name*', THEME_TEXTDOMAIN); ?>">
                <div class="input-error-msg"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 form-group">
                <input type="email" class="form-control input-lg" id="update_email" name="email" readonly value="<?php echo $userDetails->data['email']; ?>" placeholder="<?php _e('Email*', THEME_TEXTDOMAIN); ?>">
                <div class="input-error-msg"></div>
            </div>
            <div class="col-sm-6 form-group">
                <input type="text" class="form-control input-lg" id="update_dob" name="dob" value="<?php echo $userDetails->data['dob']; ?>" placeholder="<?php _e('Date of birth', THEME_TEXTDOMAIN); ?>">
                <div class="input-error-msg"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 form-group">
                <input type="text" class="form-control input-lg" id="update_lphone" name="cphone" value="<?php echo $userDetails->data['lphone']; ?>" placeholder="<?php _e('Commercial Phone', THEME_TEXTDOMAIN); ?>">
                <div class="input-error-msg"></div>
            </div>
            <div class="col-sm-6 form-group">
                <input type="text" class="form-control input-lg" id="update_phone" name="phone" value="<?php echo $userDetails->data['phone']; ?>" placeholder="<?php _e('Mobile Phone*', THEME_TEXTDOMAIN); ?>">
                <div class="input-error-msg"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 form-group">
                <select name="state" class="user-state state-city-selection chosen" id="update_user_state">
                    <option value=""><?php _e('Choose your state', THEME_TEXTDOMAIN); ?></option>
                    <?php
                    if (is_array($getStates) && count($getStates) > 0):
                        foreach ($getStates as $eachState):
                            ?>
                            <option value="<?php echo $eachState->term_id; ?>" <?php selected($userDetails->data['state'], $eachState->term_id); ?>><?php echo $eachState->name; ?></option>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </select>
                <div class="input-error-msg"></div>
            </div>
            <div class="col-sm-6 form-group">
                <select name="city" class="user-city select-your-city chosen" id="update_user_city">
                    <option value=""><?php _e('Choose your city', THEME_TEXTDOMAIN); ?></option>
                    <?php
                    if (is_array($getAllCities) && count($getAllCities) > 0) :
                        foreach ($getAllCities as $eachCity) :
                            ?>
                            <option value="<?php echo $eachCity->term_id; ?>" <?php selected($userDetails->data['city'], $eachCity->term_id); ?>><?php echo $eachCity->name; ?></option>
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
                <input type="text" class="form-control input-lg" id="update_address" name="address" value="<?php echo $userDetails->data['address']; ?>" placeholder="<?php _e('Address', THEME_TEXTDOMAIN); ?>"/>
                <input type="hidden" id="addressloc" name="addressloc" value="<?php echo $userDetails->data['address_loc']; ?>"/>
                <input type="hidden" id="addressID" name="addressID" value="<?php echo $userDetails->data['address_id']; ?>"/>
                <div class="input-error-msg"></div>
            </div>
            <div class="col-sm-6 form-group radio-group">
                <label><?php _e('Genre: ', THEME_TEXTDOMAIN); ?></label>
                <label class="control control--radio" for="update_male">
                    <input type="radio" class="form-control input-lg" id="update_male" name="genre" <?php echo ($userDetails->data['genre'] == 'male') ? 'checked' : ''; ?> value="male"/>
                    <span></span><?php _e('Male', THEME_TEXTDOMAIN); ?>
                    <div class="control__indicator"></div>
                </label>
                <label class="control control--radio" for="update_female">
                    <input type="radio" class="form-control input-lg" id="update_female" name="genre" <?php echo ($userDetails->data['genre'] == 'female') ? 'checked' : ''; ?> value="female"/>
                    <span></span><?php _e('Female', THEME_TEXTDOMAIN); ?>
                    <div class="control__indicator"></div>
                </label>
                <div class="input-error-msg"></div>
            </div>
        </div>
        

       

            <label><?php _e('Select your type', THEME_TEXTDOMAIN); ?></label>
            <div class="row type-error">
                <div class="col-sm-6 form-group">
                    <label class="control control--radio" for="physical_person">
                    <?php  if($userDetails->data['supplier_type']== 1) {?>
                        <input class="form-control supplier-type" id="physical_person" type="radio" name="supplier_type" value="1" checked/>
                        <?php } else { ?>
                            <input class="form-control supplier-type" id="physical_person" type="radio" name="supplier_type" value="1"/>
                       <?php }?>

                        <span></span>
                        <?php _e('Physical person', THEME_TEXTDOMAIN); ?>
                        <div class="control__indicator"></div>
                    </label>
                    <!--<<div class="input-error-msg"></div>-->
                </div>
                <div class="col-sm-6 form-group">
                    <label class="control control--radio" for="judicial_person">
                    <?php  if($userDetails->data['supplier_type']==2) {?>
                        <input class="form-control supplier-type" id="judicial_person" type="radio" name="supplier_type" value="2" checked/>
                        <?php } else { ?>
                        <input class="form-control supplier-type" id="judicial_person" type="radio" name="supplier_type" value="2" />
                        <?php }?>
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
            <div class="row cnpj-display" >
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
        <div class="form-group text-center">
            <button type="submit" class="btn btn-cs ladda-button btn-lg" data-style="expand-right" name="productorAccountSbmt" id="productorAccountSbmt"><?php _e('Update', THEME_TEXTDOMAIN); ?></button>
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
