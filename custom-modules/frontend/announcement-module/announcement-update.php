<?php
/*
 * Announcement Update
 * 
 */

$GeneralThemeObject = new GeneralTheme();
$AnnouncementObject = new classAnnouncement();
$userDetails = $GeneralThemeObject->user_details();
$getAnnouncementEnabledCities = $AnnouncementObject->getAnnouncementEnabledCities();
$getSupplierAnnEnabled = get_term_meta($userDetails->data['city'], '_enable_announcement_for_suppliers', TRUE);
$getCustomerAnnEnabled = get_term_meta($userDetails->data['city'], '_enable_announcement_for_customers', TRUE);
$getProductCategories = get_terms(themeFramework::$theme_prefix . 'announcement_category', ['hide_empty' => FALSE, 'parent' => 0]);
if (isset($_GET['announcement_id']) && $_GET['announcement_id'] != ''):
    $announcement_id = base64_decode($_GET['announcement_id']);
    $announcement_details = $AnnouncementObject->announcement_details($announcement_id);
    $announcementImages = $announcement_details->data['announcement_images'];
endif;
$getStates = $GeneralThemeObject->getCities();
$getAllCities = $GeneralThemeObject->getCities($announcement_details->data['announcement_state']);
$get_child_cities = get_term_children($announcement_details->data['announcement_state'],themeFramework::$theme_prefix . 'product_city');

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
                    {types: ['address']});
            autocomplete.addListener('place_changed', function () {
                var place = this.getPlace();
                console.log(place);
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
        <form name="announcementUpdateFrm" id="announcementUpdateFrm" method="post" action="javascript:void(0);" enctype="multipart/form-data">
            <input type="hidden" name="action" value="announcement_update"/>
            <input type="hidden" name="announcement_id" value="<?php echo (isset($_GET['announcement_id']) && $_GET['announcement_id'] != '') ? $_GET['announcement_id'] : ''; ?>"/>
            <input type="hidden" name="property_main_images" class="property_main_images" value="<?php echo $announcement_details->data['announcement_main_images']; ?>"/>
            <div class="row">
                <div class="col-sm-6 form-group">
                    <input type="text" name="announcement_name" class="form-control input-lg" autocomplete="off" id="announcement_updated_name" value="<?php echo $announcement_details->data['title']; ?>" placeholder="<?php _e('Announcement name*', THEME_TEXTDOMAIN); ?>"/>
                    <div class="input-error-msg"></div>
                </div>
                
                <div class="col-sm-6 form-group">
                    <?php $announcement_meta_category = $announcement_details->data['announcement_category']; ?>
                    <select name="announcement_category[]" class="chosen announcement_category" multiple>
                        <option value=""><?php _e('-Select Announcement Category-', THEME_TEXTDOMAIN); ?></option>
                            <?php if (is_array($getProductCategories) && count($getProductCategories) > 0):
                                    foreach ($getProductCategories as $eachProductCategory):
                                        $getProductSubCategories = get_terms(themeFramework::$theme_prefix . 'announcement_category', ['hide_empty' => FALSE, 'parent' => $eachProductCategory->term_id]);
                                        if(is_array($announcement_meta_category) && count($announcement_meta_category) > 0 && in_array($eachProductCategory->slug, $announcement_meta_category)){
                                            $catSelected = 'selected';
                                        } else {
                                            $catSelected = '';
                                        }
                                        ?>
                                        <option value="<?php echo $eachProductCategory->slug; ?>" <?php echo $catSelected ; ?> ><?php echo $eachProductCategory->name; ?></option>
                                        <?php
                                        if (is_array($getProductSubCategories) && count($getProductSubCategories) > 0):
                                            foreach ($getProductSubCategories as $eachSubCategory):
                                                if(in_array($eachSubCategory->slug, $announcement_meta_category)):
                                                    $subcatSelected = 'selected';
                                                else:
                                                    $subcatSelected = '';
                                                endif;
                                    ?>
                                                    <option value="<?php echo $eachSubCategory->slug; ?>" <?php echo $subcatSelected; ?> ><?php echo '--'.$eachSubCategory->name; ?></option>
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
                        <select name="announcement_state" class="chosen state-selection" id="announcement_states" data-target="announcement_city"  disabled>
                            <option  value=""><?php _e('Selecione o estado', THEME_TEXTDOMAIN); ?></option>
                                <?php
                                if (is_array($getStates) && count($getStates) > 0) :
                                    foreach ($getStates as $eachCountry) :
                                        ?>
                            <option <?php selected($eachCountry->term_id, $announcement_details->data['announcement_state'], TRUE) ?> value="<?php echo $eachCountry->term_id; ?>"  disabled><?php echo $eachCountry->name; ?></option>
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                        </select>
                        <div class="input-error-msg"></div>
                    </div>
                    <div class="col-sm-6 form-group">
                           <select name="announcement_city" class="chosen city-selection attribute_city"  id="announcement_city"  disabled>
                                    <option hidden value=""><?php _e('Selecione a cidade', THEME_TEXTDOMAIN); ?></option>
                                    <option <?php echo ($announcement_details->data['announcement_city']== 99999999)?'selected':'' ?> value=99999999 > <?php _e('Todas as cidades', THEME_TEXTDOMAIN) ; ?>  </option>
                                    <?php
                                    if (is_array($get_child_cities) && count($get_child_cities) > 0) :
                                        foreach ($get_child_cities as $eachCity) :
                                            $theCity = get_term_by('id',$eachCity,themeFramework::$theme_prefix . 'product_city');
                                            ?>
                                            <option  hidden<?php selected($eachCity, $announcement_details->data['announcement_city'], TRUE) ?> value="<?php echo $eachCity ; ?>"><?php echo $theCity->name ; ?></option>
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
                    <input type="text" class="form-control input-lg address_field" readonly id="announcement_address" name="address" value="<?php echo $announcement_details->data['announcement_address']; ?>" placeholder="<?php _e('Address*', THEME_TEXTDOMAIN); ?>"/>
                    <input type="hidden" id="announcement_addressloc" name="addressloc" value="<?php echo $announcement_details->data['announcement_location']; ?>"/>
                    <input type="hidden" id="announcement_addressID" name="addressID" value="<?php echo $announcement_details->data['announcement_id']; ?>"/>
                    <div class="input-error-msg"></div>
                    </div>
                    </div>
                    <!-- End Address location -->
            <div class="row">
                <div class="col-sm-6 form-group">
                    <input type="text" name="announcement_date" class="form-control input-lg" autocomplete="off" readonly id="announcement_updated_create_date" value="<?php echo date('Y-m-d', $announcement_details->data['start_date']); ?>" placeholder="<?php _e('Starts from*', THEME_TEXTDOMAIN); ?>"/>
                    <div class="input-error-msg"></div>
                </div>
                
                <div class="col-sm-6 form-group">
                    <input type="number" name="announcement_period" class="form-control input-lg" readonly id="announcement_updated_period" min="1" max="31" value="<?php echo $announcement_details->data['no_of_days']; ?>" placeholder="<?php _e('Number of days*', THEME_TEXTDOMAIN); ?>"/>
                    <div class="input-error-msg"></div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-12">
                    <input type="text" name="announcment_price" id="announcment_updated_price" autocomplete="off" class="form-control input-lg" value="<?php echo $announcement_details->data['announcement_price']; ?>" placeholder="<?php _e('Price (in R$)', THEME_TEXTDOMAIN); ?>"/>
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
                <div class="card-header"><?php _e('File List', THEME_TEXTDOMAIN); ?></div>

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

            <div class="form-group text-center">
                <button type="submit" class="btn btn-cs ladda-button btn-lg" data-style="expand-right" name="announcementCreationSbmt" id="announcementUpdateSbmt"><?php _e('Save', THEME_TEXTDOMAIN); ?></button>
            </div>
        </form>
    <?php elseif (is_array($getAnnouncementEnabledCities) && count($getAnnouncementEnabledCities) > 0 && in_array($userDetails->data['city'], $getAnnouncementEnabledCities) && $userDetails->data['role'] == 'supplier' && $getSupplierAnnEnabled == 1): ?>
    <form name="announcementUpdateFrm" id="announcementUpdateFrm" method="post" action="javascript:void(0);" enctype="multipart/form-data">
        <input type="hidden" name="action" value="announcement_update"/>
        <input type="hidden" name="announcement_id" value="<?php echo (isset($_GET['announcement_id']) && $_GET['announcement_id'] != '') ? $_GET['announcement_id'] : ''; ?>"/>
        <input type="hidden" name="property_main_images" class="property_main_images" value="<?php echo $announcement_details->data['announcement_main_images']; ?>"/>
        <div class="row">
            <div class="col-sm-6 form-group">
                <input type="text" name="announcement_name" autocomplete="off" class="form-control input-lg" id="announcement_updated_name" value="<?php echo $announcement_details->data['title']; ?>" placeholder="<?php _e('Announcement name*', THEME_TEXTDOMAIN); ?>"/>
                <div class="input-error-msg"></div>
            </div>
            <div class="col-sm-6 form-group">
                <select name="announcement_category[]" class="chosen announcement_category" multiple>
                    <option value=""><?php _e('-Select Announcement Category-', THEME_TEXTDOMAIN); ?></option>
                    <?php
                    if (is_array($getProductCategories) && count($getProductCategories) > 0):
                        foreach ($getProductCategories as $eachProductCategory):
                            $getProductSubCategories = get_terms(themeFramework::$theme_prefix . 'announcement_category', ['hide_empty' => FALSE, 'parent' => $eachProductCategory->term_id]);
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
                                    if(in_array($eachSubCategory->slug, $announcement_details->data['announcement_category'])){
                                            $subCatSelected = 'selected';
                                        } else {
                                            $subCatSelected = '';
                                        }
                                    ?>
                                    <option value="<?php echo $eachSubCategory->slug; ?>" <?php echo $subCatSelected; ?>><?php echo '--'.$eachSubCategory->name; ?></option>
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
                    <select name="announcement_state" class="chosen state-selection" id="announcement_states" data-target="announcement_city" >
                        <option value=""><?php _e('Selecione o estado', THEME_TEXTDOMAIN); ?></option>
                            <?php
                            if (is_array($getStates) && count($getStates) > 0) :
                                foreach ($getStates as $eachCountry) :
                                    ?>
                        <option  <?php selected($eachCountry->term_id, $announcement_details->data['announcement_state'], TRUE) ?> value="<?php echo $eachCountry->term_id; ?>"><?php echo $eachCountry->name; ?></option>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                    </select>
                    <div class="input-error-msg"></div>
                </div>
                <div class="col-sm-6 form-group" >
                    <select name="announcement_city" class="chosen city-selection attribute_city"  id="announcement_city">
                                <option hidden value=""><?php _e('Selecione a cidade', THEME_TEXTDOMAIN); ?></option>
                                <option <?php echo ($announcement_details->data['announcement_city']== 99999999)?'selected':'' ?> value=99999999 > <?php _e('Todas as cidades', THEME_TEXTDOMAIN) ; ?>  </option>
                                <?php
                                if (is_array($get_child_cities) && count($get_child_cities) > 0) :
                                    foreach ($get_child_cities as $eachCity) :
                                        $theCity = get_term_by('id',$eachCity,themeFramework::$theme_prefix . 'product_city');
                                        ?>
                                        <option  hidden<?php selected($eachCity, $announcement_details->data['announcement_city'], TRUE) ?> value="<?php echo $eachCity ; ?>"><?php echo $theCity->name ; ?></option>
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
                <input type="text" class="form-control input-lg address_field" readonly id="announcement_address" name="address"  value="<?php echo $announcement_details->data['announcement_address']; ?>" placeholder="<?php _e('Address*', THEME_TEXTDOMAIN); ?>"/>
                <input type="hidden" id="announcement_addressloc" name="addressloc" value="<?php echo $announcement_details->data['announcement_location']; ?>"/>
                <input type="hidden" id="announcement_addressID" name="addressID" value="<?php echo $announcement_details->data['announcement_id']; ?>"/>
                <div class="input-error-msg"></div>
                </div>
                </div>
                <!-- End Address location -->
                
        <div class="row">
            <div class="col-sm-6 form-group">
                 <input type="text" name="announcement_date" class="form-control input-lg" autocomplete="off" readonly id="announcement_updated_create_date"
                  value="<?php echo $announcement_details->data['start_date']; ?>" placeholder="<?php _e('Starts from*', THEME_TEXTDOMAIN); ?>"/>
                 
                <div class="input-error-msg"></div>
            </div>
            <div class="col-sm-6 form-group">
                <input type="number" name="announcement_period" class="form-control input-lg" readonly id="announcement_updated_period" min="1" max="31" value="<?php echo $announcement_details->data['no_of_days']; ?>" placeholder="<?php _e('Number of days*', THEME_TEXTDOMAIN); ?>"/>
                <div class="input-error-msg"></div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-sm-12">
                <input type="text" name="announcment_price" id="announcment_updated_price" autocomplete="off" class="form-control input-lg" value="<?php echo $announcement_details->data['announcement_price']; ?>" placeholder="<?php _e('Price (in R$)', THEME_TEXTDOMAIN); ?>"/>
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
                <div class="card-header"><?php _e('File List', THEME_TEXTDOMAIN); ?></div>

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

            <div class="form-group text-center">
                <button type="submit" class="btn btn-cs ladda-button btn-lg" data-style="expand-right" name="announcementCreationSbmt" id="announcementUpdateSbmt"><?php _e('Save', THEME_TEXTDOMAIN); ?></button>
            </div>
        </form>
    <?php else: ?>
    <div class="alert alert-danger"><?php _e('You are not allowed to update any announcement now.', THEME_TEXTDOMAIN); ?></div>
    <?php endif; ?>
    
    </div>
<?php 