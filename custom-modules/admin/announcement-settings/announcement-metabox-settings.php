<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!function_exists('adminAnnouncementSection')) {

    function adminAnnouncementSection() {
        add_meta_box('my-meta-box-announcement', 'Announcement Fields', 'andr_meta_box_announcement_func', themeFramework::$theme_prefix . 'announcement', 'advanced', 'high', '');
    }

}

if (!function_exists('andr_meta_box_announcement_func')) {

    function andr_meta_box_announcement_func() {
        global $post;
        $GeneralThemeObject = new GeneralTheme();
        $AnnouncementObject = new classAnnouncement();
        $userDetails = $GeneralThemeObject->user_details();
        $getProductCategories = get_terms(themeFramework::$theme_prefix . 'announcement_category', ['hide_empty' => FALSE, 'parent' => 0]);
        $announcement_details = $AnnouncementObject->announcement_details($post->ID);
        $post_id= $announcement_details->ID;        
      
        $getAnnouncementEnabledCities = $AnnouncementObject->getAnnouncementEnabledCities();
        $getSupplierAnnEnabled = get_term_meta($userDetails->data['city'], '_enable_announcement_for_suppliers', TRUE);
        $getCustomerAnnEnabled = get_term_meta($userDetails->data['city'], '_enable_announcement_for_customers', TRUE);
        $getAllCities = $GeneralThemeObject->getCities($userDetails->data['state']);
        $getStates = $GeneralThemeObject->getCities();
        $get_child_cities = get_term_children($announcement_details->data['announcement_state'],themeFramework::$theme_prefix . 'product_city');
        $announcementImages = $announcement_details->data['announcement_images'];
        ?>
        <div class="wrap">
            <table class="widefat">
                <tbody>
                    <tr>
                        <td><strong><?php _e('Announcement Category', THEME_TEXTDOMAIN); ?></strong></td>
                        <td>
                         <?php
                             $announcement_meta_category = $announcement_details->data['announcement_category'];
                            ?>
                             <select name="announcement_category[]" class="chosen announcement_category" multiple>
                                <option value=""><?php _e('-Select Announcement Category-', THEME_TEXTDOMAIN); ?></option>
                                <?php
                                
                                if (is_array($getProductCategories) && count($getProductCategories) > 0):
                                    foreach ($getProductCategories as $eachProductCategory):
                                        $getProductSubCategories = get_terms(themeFramework::$theme_prefix . 'announcement_category', ['hide_empty' => FALSE, 'parent' => $eachProductCategory->term_id]);
                                        $announcement_meta_category = $announcement_details->data['announcement_category'];
                                        if(is_array($announcement_details->data['announcement_category']) && count($announcement_details->data['announcement_category']) > 0 && in_array($eachProductCategory->slug, $announcement_details->data['announcement_category'])){
                                            $catSelected = 'selected';
                                        } else {
                                            $catSelected = '';
                                        }
                                ?>
                                        <option value="<?php echo $eachProductCategory->slug; ?>" <?php echo $catSelected ; ?> ><?php echo $eachProductCategory->name; ?></option>
                                        <?php
                                        if (is_array($getProductSubCategories) && count($getProductSubCategories) > 0):
                                            foreach ($getProductSubCategories as $eachSubCategory):
                                                if(is_array($announcement_details->data['announcement_category']) && count($announcement_details->data['announcement_category']) > 0 && in_array($eachSubCategory->slug, $announcement_details->data['announcement_category'])):
                                                $subCatSelected = 'selected';
                                            else:
                                                $subCatSelected = '';
                                            endif;
                                ?>
                                                <option value="<?php echo $eachSubCategory->slug; ?>" <?php echo $subCatSelected; ?>><?php echo '--'.$eachSubCategory->name; ?></option>
                                                <?php
                                            endforeach;
                                        endif;
                                    endforeach;
                                endif;
                                ?>
                            </select>
                          
                        </td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Announce State', THEME_TEXTDOMAIN); ?></strong></td>
                        <td> 
                            <select name="announcement_state" class="chosen state-selection attribute_state" id="announcement_states" data-target="announcement_city">
                                <option value=""><?php _e('Selecione o estado', THEME_TEXTDOMAIN); ?></option>
                                <?php
                                if (is_array($getStates) && count($getStates) > 0) :
                                    foreach ($getStates as $eachCountry) :
                                        ?>
                                        <option <?php selected($eachCountry->term_id, $announcement_details->data['announcement_state'], TRUE) ?> value="<?php echo $eachCountry->term_id; ?>"><?php echo $eachCountry->name; ?></option>
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Announce City', THEME_TEXTDOMAIN); ?></strong></td>
                        <td>
                           <!--  <select name="announcement_city" class="chosen city-selection" id="announcement_city">
                            <option value=""><?php// _e('Selecione a cidade', THEME_TEXTDOMAIN); ?></option>
                            <?php
                           /* if (is_array($getAllCities) && count($getAllCities) > 0) :
                                foreach ($getAllCities as $eachCity) :
                                    ?>
                                    <option <?php selected($eachCity->term_id, $userDetails->data['city'], TRUE) ?> value="<?php echo $eachCity->term_id; ?>"><?php echo $eachCity->name; ?></option>
                                    <?php
                                endforeach;
                            endif;*/
                            ?>
                        </select>-->
                           <!-- <select name="announcement_city[]" class="chosen city-selection attribute_city" id="announcement_city">
                                <option value=""><?php _e('Selecione a cidade', THEME_TEXTDOMAIN); ?></option>
                                <option <?php echo ($announcement_details->data['announcement_city']== 99999999)?'selected':'' ?> value=99999999 > <?php _e('Todas as cidades', THEME_TEXTDOMAIN) ; ?>  </option>
                                <?php
                               
                               if (is_array($get_child_cities) && count($get_child_cities) > 0) :
                                    foreach ($get_child_cities as $eachCity) :
                                        $theCity = get_term_by('id',$eachCity,themeFramework::$theme_prefix . 'product_city');
                                        ?>
                                        <option <?php selected($eachCity, $announcement_details->data['announcement_city'], TRUE) ?> value="<?php echo $eachCity ; ?>"><?php echo $theCity->name ; ?></option>
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </select>-->
                             <select name="announcement_city" class="chosen city-selection attribute_city"  id="announcement_city"  >
                                <option value=""><?php _e('Selecione a cidade', THEME_TEXTDOMAIN); ?></option>
                                <option <?php echo ($announcement_details->data['announcement_city']== 99999999)?'selected':'' ?> value=99999999 > <?php _e('Todas as cidades', THEME_TEXTDOMAIN) ; ?>  </option>
                                <?php
                                if (is_array($get_child_cities) && count($get_child_cities) > 0) :
                                    foreach ($get_child_cities as $eachCity) :
                                        $theCity = get_term_by('id',$eachCity,themeFramework::$theme_prefix . 'product_city');
                                        ?>
                                        <option  <?php selected($eachCity, $announcement_details->data['announcement_city'], TRUE) ?> value="<?php echo $eachCity ; ?>"><?php echo $theCity->name ; ?></option>
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </select>
                        </td>
                    </tr>
                    
                    
                    <!--ANNOUNCEMENT PLAN-->
                    
                    <tr>
                        <td><strong><?php _e('Announce Plan', THEME_TEXTDOMAIN); ?></strong></td>
                        <td>
                            <select name="announcement_type"  id="announcement_type">
                                <option <?php echo ($announcement_details->data['announcement_plan']== 'gold')?'selected':'' ?> value='gold' > <?php _e('Ouro', THEME_TEXTDOMAIN) ; ?> </option>
                                <option <?php echo ($announcement_details->data['announcement_plan']== 'silver')?'selected':'' ?> value='silver' > <?php _e('Prata', THEME_TEXTDOMAIN) ; ?> </option>
                                <option <?php echo ($announcement_details->data['announcement_plan']== 'bronze')?'selected':'' ?> value='bronze' > <?php _e('Bronze', THEME_TEXTDOMAIN) ; ?> </option>
                                ?>
                            </select>
                        </td>
                    </tr>
                    
                    <!--END ANNOUNCEMENT PLAN-->
                    
                    <!--ADD GOOGLE AUTOCOMPLETE-->
                    
                    <tr>
                        <td><strong><?php _e('Address', THEME_TEXTDOMAIN); ?></strong></td>
                        <td>
                    <input type="text" required style="width:100%;" class="form-control input-lg address_field" id="announcement_address" name="address" value="<?php echo $announcement_details->data['announcement_address']; ?>" placeholder="<?php _e('Address*', THEME_TEXTDOMAIN); ?>"/>
                    <input type="hidden" id="announcement_addressloc" name="addressloc" value="<?php echo $announcement_details->data['announcement_location']; ?>"/>
                    <input type="hidden" id="announcement_addressID" name="addressID" value="<?php echo $announcement_details->data['announcement_id']; ?>"/>
                        </td>
                    </tr>
                    
                    <!--END GOOGLE AUTOCOMPLETE-->
                    
                    
                    
                    <tr>
                        <td><strong><?php _e('Starts from', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><input type="text" name="announcement_date" id="announcement_admin_create_date" value="<?php echo ($announcement_details->data['start_date']!='')?$announcement_details->data['start_date']:date('d M, Y'); ?>" placeholder="<?php _e('Starts from', THEME_TEXTDOMAIN); ?>"/>
                       </td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Number of days', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><input type="number" name="announcement_period" min="1" max="31" value="<?php echo $announcement_details->data['no_of_days']; ?>" placeholder="<?php _e('Number of days*', THEME_TEXTDOMAIN); ?>"/></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Price(in R$)', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><input type="text" name="announcment_price" value="<?php echo $announcement_details->data['announcement_price']; ?>" placeholder="<?php _e('Price (in R$)', THEME_TEXTDOMAIN); ?>"/></td>
                    </tr>
                      <tr>
                        <td colspan="2">
                            <?php
                            if (is_array($announcementImages) && count($announcementImages) > 0):
                                foreach ($announcementImages as $eachAnnouncementImage):
                                    $imagePath = get_attached_file($eachAnnouncementImage);
                                    $imageSrc = wp_get_attachment_image_src($eachAnnouncementImage, 'thumbnail');
                                    ?>
                                    <div class="col-sm-3">
                                        <div class="indiv-announcement-img">
                                            <img src="<?php echo ($imagePath) ? $imageSrc[0] : 'https://via.placeholder.com/200x175'; ?>" alt=""/>
                                        </div>
                                    </div>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Previous Content', THEME_TEXTDOMAIN); ?></strong></td>
                        <td><span><?php echo ($announcement_details->data['old_content']) ? $announcement_details->data['old_content'] : $announcement_details->data['content']; ?></span></td>
                    </tr>
                    <?php
                    $announcementStatus=get_post_meta($post->ID,'_announcement_enabled',TRUE);
                    ?>
                    <tr>
                        <td><strong><?php _e('-Selecione para Aprovar/Desaprovar-', THEME_TEXTDOMAIN); ?></strong></td>
                        <td> 
                            <select name="announcement_approve" >
                                <option value=""><?php _e('-Selecione para Aprovar/Desaprovar-', THEME_TEXTDOMAIN); ?></option>
                                <option <?php echo ($announcementStatus==1)?'selected':'' ;?> value="1"><?php _e('Aprovar', THEME_TEXTDOMAIN); ?></option>
                                <option <?php echo ($announcementStatus==2)?'selected':'' ;?> value="2"><?php _e('Desaprovar', THEME_TEXTDOMAIN); ?></option>
                            </select>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <?php
        }

    }

    if (!function_exists('adminAnnouncementSectionSave')) {

        function adminAnnouncementSectionSave($post_id) {

           
            $getPostDetails = get_post($post_id);
            $announcement_category = $_POST['announcement_category'];
            $announcement_date = strip_tags(trim($_POST['announcement_date']));
            $announcement_period = strip_tags(trim($_POST['announcement_period']));
            $announcment_price = $_POST['announcment_price'];
            $announcment_state = $_POST['announcement_state'];
            $announcment_city = $_POST['announcement_city'];
            $announcement_type = $_POST['announcement_type'];
            $announcment_address = $_POST['address'];
            $announcment_loc = $_POST['addressloc'];
            $announcment_addressID = $_POST['addressID'];
            $announcment_approve = $_POST['announcement_approve'];
            if($announcement_type == 'gold'){
                 $announcement_order = 1;
             } elseif ($announcement_type == 'silver') {
                 $announcement_order = 2;
             }else {
                 $announcement_order = 3;
             } 
             
             

            if ($getPostDetails->post_type == themeFramework::$theme_prefix . 'announcement') {

                wp_set_object_terms($post_id, $announcement_category, themeFramework::$theme_prefix . 'announcement_category');
                
                $imge_arr=array();
                update_post_meta($post_id, '_start_date', $announcement_date);
                update_post_meta($post_id, '_number_of_days', $announcement_period);
                update_post_meta($post_id, '_announcement_price', $announcment_price);
                update_post_meta($post_id, '_announcement_state', $announcment_state);
                update_post_meta($post_id, '_announcement_city', $announcment_city);
                update_post_meta($post_id, '_announcement_plan', $announcement_type);
                update_post_meta($post_id, '_announcement_order', $announcement_order);
                update_post_meta($post_id, '_announcement_address', $announcment_address);
                update_post_meta($post_id, '_announcement_address_loc', $announcment_loc);
                update_post_meta($post_id, '_announcement_address_id', $announcment_addressID);
                $galleryImages = get_field('_add_announcement_image', $post_id);
                foreach($galleryImages as $eachgalleryImages){
                    array_push($imge_arr,$eachgalleryImages['ID']);
                }
                if(is_array($imge_arr) && count($imge_arr)>0){
                    $imgIds = join(',' , $imge_arr) ;
                }
                update_post_meta($post_id, '_announcement_images', $imgIds);
                update_post_meta($post_id, '_admin_approval', $announcment_approve);
                if ($announcment_approve == 2){
                    update_post_meta($post_id, '_announcement_disable', 2); // Make disable the announcement
                }
                if($announcment_approve == 1){
                    update_post_meta($post_id, '_announcement_enabled', 1);} //make active
            }
        }

    }
    
    function my_admin_add_js() {
        ?>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDnO0V9m09BUXB-JqwuuFno7efIs4FD5nM&libraries=places&callback=initAutocompleteSupplier" async defer></script>
        <script> var placeSearch, autocomplete;

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
    jQuery(".announcement_category").chosen();  
    </script>
        <?php

    }