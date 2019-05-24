<?php
/*
 * Admin Screen Extra Column
 */

/* -----------------FOR USER----------------- */

add_action('show_user_profile', 'andr_extra_user_profile_fields');
add_action('edit_user_profile', 'andr_extra_user_profile_fields');
add_action('user_new_form', 'andr_extra_user_profile_fields');

/**
 * Adding form fields in edit screen
 */
if (!function_exists('andr_extra_user_profile_fields')) {

    function andr_extra_user_profile_fields($user) {
        global $pagenow;
        $GeneralThemeFunc = new GeneralTheme();
        $get_user_details = $GeneralThemeFunc->user_details($user->ID);
        $getStates = $GeneralThemeFunc->getCities();
        $getCities = $GeneralThemeFunc->getCities($get_user_details->data['state']);
        $getProductCategories = get_terms(themeFramework::$theme_prefix . 'product_category', ['hide_empty' => FALSE, 'parent' => 0]);

        $getBuisnessCategories = $get_user_details->data['buisness_categories'];
        $get_receive_deals = $get_user_details->data['receive_deals'];
        $get_allow_where_to_buy = $get_user_details->data['allow_where_to_buy'];
        $get_selected_plan = $get_user_details->data['selected_plan'];
        $get_selected_plan_details = $GeneralThemeFunc->getMembershipPlanDetails($get_selected_plan);
         
   
        $admin_activate = get_user_meta($user->ID, '_admin_approval', TRUE);
        if ($admin_activate == 1) {
            $admin_active = 'selected="selected"';
        } elseif ($admin_activate == 2) {
            $admin_disabled = 'selected="selected"';
        }
   
        if (($pagenow == 'user-edit.php') && ($get_user_details->data['role'] == 'supplier')):
            $save_user_img_id = get_user_meta($user->ID, '_pro_pic', true);
            if($save_user_img_id) {
                $img_src = wp_get_attachment_image_src($save_user_img_id, 'full');
            }
           $MemberShipObject = new classMemberShip();
            $userMembership = $MemberShipObject->getUserMembershipDetails($user->ID);
         
            ?>
            <h3><?php _e("User Additional Information", "blank"); ?></h3>
            <table class="form-table">
            <tr>
                <th scope="row"><?php _e("Upload logo"); ?></th>
                <td>
                    <?php wp_enqueue_media(); ?>
                    <input id="upload_image_button" type="button" class="button" value="<?php _e( 'Upload logo' ); ?>" />
                    <input type='hidden' name='supplier_logo' id='supplier_logo' value='<?php echo $save_user_img_id; ?>'>
                    <img id="image-preview" src="<?php echo $img_src[0]; ?>" width="100" height="75"/>
                    <!-- <input type="file" name="supplier_logo" value="" /> -->
                </td>
                <?php if($save_user_img_id): ?>
                <?php endif; ?>

                <!-- Script for uploading images by Wordpress Media Element -->
                <script type='text/javascript'>
                    jQuery( document ).ready( function( $ ) {
                        // Uploading files
                        var file_frame;
                        var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
                        var set_to_post_id = <?php echo $save_user_img_id; ?>; // Set this
                        jQuery('#upload_image_button').on('click', function( event ){
                            event.preventDefault();
                            // If the media frame already exists, reopen it.
                            if ( file_frame ) {
                                // Set the post ID to what we want
                                file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
                                // Open frame
                                file_frame.open();
                                return;
                            } else {
                                // Set the wp.media post id so the uploader grabs the ID we want when initialised
                                wp.media.model.settings.post.id = set_to_post_id;
                            }
                            // Create the media frame.
                            file_frame = wp.media.frames.file_frame = wp.media({
                                title: 'Select a image to upload',
                                button: {
                                    text: 'Set as Supplier Logo',
                                },
                                multiple: false // Set to true to allow multiple files to be selected
                            });
                            // When an image is selected, run a callback.
                            file_frame.on( 'select', function() {
                                // We set multiple to false so only get one image from the uploader
                                attachment = file_frame.state().get('selection').first().toJSON();
                                // Do something with attachment.id and/or attachment.url here
                                $( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
                                $( '#supplier_logo' ).val( attachment.id );
                                // Restore the main post ID
                                wp.media.model.settings.post.id = wp_media_post_id;
                            });
                                // Finally, open the modal
                                file_frame.open();
                        });
                        // Restore the main ID when the add media button is pressed
                        jQuery( 'a.add_media' ).on( 'click', function() {
                            wp.media.model.settings.post.id = wp_media_post_id;
                        });
                    });
                </script>
                <!-- End of Script for uploading images by Wordpress Media Element -->
            </tr>
                <tr>
                    <th scope="row"><?php _e("Supplier type"); ?></th>
                    <td>
                        <label for="physical">
                            <input type="radio" name="supplier_type" id="physical" class="supplier-type" value="1" <?php echo ($get_user_details->data['supplier_type'] == 1) ? 'checked' : ''; ?>/><?php _e('Physical person', THEME_TEXTDOMAIN); ?>
                        </label>
                        <label for="judicial">
                            <input type="radio" name="supplier_type" id="judicial" class="supplier-type" value="2" <?php echo ($get_user_details->data['supplier_type'] == 2) ? 'checked' : ''; ?>/><?php _e('Judicial person', THEME_TEXTDOMAIN); ?>
                        </label>
                    </td>
                </tr>
                <tr class="cpf-display" style="<?php echo ($get_user_details->data['supplier_type'] == 1) ? 'display:block' : 'display:none'; ?>">
                    <th scope="row"><?php _e("CPF"); ?></th>
                    <td><input type="text" name="cpf" class="cpf" value="<?php echo ($get_user_details->data['cpf']) ? $get_user_details->data['cpf'] : 'Não fornecido.'; ?>" placeholder="CPF*"/></td>
                </tr>
                <tr class="cnpj-display" style="<?php echo ($get_user_details->data['supplier_type'] == 2) ? 'display:block' : 'display:none'; ?>">
                    <th scope="row"><?php _e("CNPJ"); ?></th>
                    <td><input type="text" name="cnpj" class="cnpj" value="<?php echo ($get_user_details->data['cnpj']) ? $get_user_details->data['cnpj'] : 'Não fornecido.'; ?>" placeholder="CNPJ*"/></td>
                </tr>
                <?php if ($get_selected_plan): ?>
                    <tr>
                        <th scope="row"><?php _e("Member type"); ?></th>
                        <td>
                            <select name="membership_type"  id="membership_type">
                                <option <?php echo ( $get_selected_plan_details->data['name'] == 'gold')?'selected':'' ?> value='171' > <?php _e('Ouro', THEME_TEXTDOMAIN) ; ?> </option>
                                <option <?php echo ($get_selected_plan_details->data['name'] == 'silver')?'selected':'' ?> value='172' > <?php _e('Prata', THEME_TEXTDOMAIN) ; ?> </option>
                                <option <?php echo ($get_selected_plan_details->data['name']== 'bronze')?'selected':'' ?> value='173' > <?php _e('Bronze', THEME_TEXTDOMAIN) ; ?> </option>
                                ?>
                            </select>
                       <!-- <td><!--?php echo $get_selected_plan_details->data['title']; ?></td>-->
                        </td>
                    </tr>
                <?php endif; ?>
                
                <?php if ($get_selected_plan && $get_selected_plan_details->data['selected_end_date']): ?>
                    <tr>
                        <th scope="row"><?php _e("Member plan duration"); ?></th>
                        <td><?php echo date('d M, Y', $get_selected_plan_details->data['selected_start_date']) . ' - ' . date('d M, Y', $get_selected_plan_details->data['selected_end_date']); ?></td>
                    </tr>
                   <?php endif; ?>
                <?php if ($get_selected_plan && $get_user_details->data['selected_plan_payment_status'] == 1): ?>
                    <tr>
                        <th scope="row"><?php _e("Member plan payment status"); ?></th>
                        <td><?php echo 'Paid'; ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <th scope="row"><?php _e("State"); ?></th>
                    <td>
                        <select name="state" class="attribute_state chosen">
                            <?php if (is_array($getStates) && count($getStates) > 0): ?>                        
                                <?php foreach ($getStates as $eachState): ?>
                                    <option value="<?php echo $eachState->term_id; ?>" <?php echo ($eachState->term_id == $get_user_details->data['state']) ? 'selected' : ''; ?>><?php echo $eachState->name; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e("City"); ?></th>
                    <td>
                        <select name="city" class="attribute_city chosen yyy">
                            <?php if (is_array($getCities) && count($getCities) > 0): ?>                        
                                <?php foreach ($getCities as $eachCity): ?>
                                    <option value="<?php echo $eachCity->term_id; ?>" <?php echo ($eachCity->term_id == $get_user_details->data['city']) ? 'selected' : ''; ?>><?php echo $eachCity->name; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e("Address"); ?></th>
                   
        
                    <td>
                        <input type="text" id="supplier_location" onfocus="initAutocomplete()" name="admin_supplier_location" value="<?php echo $get_user_details->data['user_address']; ?>" size="100" placeholder="Address*"/>
                        <input type="hidden" id="supplier_location_loc" name="supplier_location_loc" value="<?php echo $get_user_details->data['address_loc']; ?>"/>
                        <input type="hidden" id="supplier_location_id" name="supplier_location_id" value="<?php echo $get_user_details->data['address_id']; ?>"/>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e("Biographical/Company description"); ?></th>
                    <td><?php echo ($get_user_details->data['bio']) ? $get_user_details->data['bio'] : 'Não fornecido.'; ?></td>
                </tr>
                <tr >
                    <th scope="row"><?php _e("Where to buy"); ?></th>
                    <td><input type="url" name="where_to_buy" size="100" value="<?php echo $get_user_details->data['where_to_buy_address']; ?>" placeholder="<?php _e('Where to Buy URL', THEME_TEXTDOMAIN); ?>" /> </td>
                </tr> 
                <tr>
                    <th scope="row"><?php _e("Assign Buisness Categories"); ?></th>
                    <td>
                                       <select name="select_product_categories[]" class="admin-supplier-category chosen" multiple style="width:400px; height:300px">
                            <?php if (is_array($getProductCategories) && count($getProductCategories) > 0): ?>                        
                                <?php foreach ($getProductCategories as $eachCategory): 
                                     $getProductSubCategories = get_terms(themeFramework::$theme_prefix . 'product_category', ['hide_empty' => FALSE, 'parent' => $eachCategory->term_id]);
                                     
                                ?>
                                    <option  value="<?php echo $eachCategory->term_id; ?>" <?php echo (is_array($getBuisnessCategories) && count($getBuisnessCategories) > 0 && in_array($eachCategory->term_id, $getBuisnessCategories)) ? 'selected' : ''; ?>><?php echo $eachCategory->name; ?></option>
                                   
                                    <?php
                                    if (is_array($getProductSubCategories) && count($getProductSubCategories) > 0):
                                        foreach ($getProductSubCategories as $eachSubCategory):
                                            ?>

                                    <option value="<?php echo $eachSubCategory->term_id; ?>" <?php echo (is_array($getBuisnessCategories) && count($getBuisnessCategories) > 0 && in_array($eachSubCategory->term_id, $getBuisnessCategories)) ? 'selected' : ''; ?>><?php _e('---', THEME_TEXTDOMAIN); ?><?php echo $eachSubCategory->name; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                             <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e("Legal Phone number"); ?></th>
                    <td>
                        <input type="text" name="phone" id="phone" value="<?php echo $get_user_details->data['phone']; ?>" placeholder="<?php _e('Phone number', THEME_TEXTDOMAIN); ?>"/>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e("Allow where to buy"); ?></th>
                    <td>
                        <input type="checkbox" name="allow_where_to_buy" id="allow_where_to_buy" value="1" <?php echo ($get_allow_where_to_buy == 1) ? 'checked' : ''; ?>/>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e("Receive Deals?"); ?></th>
                    <td>
                        <input type="checkbox" name="receive_deals" id="receive_deals" value="1" <?php echo ($get_receive_deals == 1) ? 'checked' : ''; ?>/>
                        
                    </td>
                </tr>
                <tr class="date-selection-row" style="<?php echo ($get_receive_deals == 1) ? 'display:table-row;' : 'display:none;'; ?>">
                    <th scope="row"><?php _e("Receive Deals?"); ?></th>
                        <td>
                        <input type="text" name="deals_from_date" id="deals_from_date" value="<?php echo $get_user_details->data['deal_start_date']; ?>" placeholder="<?php _e('Deal start date', THEME_TEXTDOMAIN); ?>"/>
                        <input type="text" name="deals_to_date" id="deals_to_date" value="<?php echo $get_user_details->data['deal_end_date']; ?>" placeholder="<?php _e('Deal end date', THEME_TEXTDOMAIN); ?>"/>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e("status do usuário",THEME_TEXTDOMAIN); ?></th>
                    <td>
                        <select name='user_activation_status'>
                            <option ><?php _e("--Choose Status--",THEME_TEXTDOMAIN); ?></option>
                            <option <?php echo $admin_active;?> value=1 ><?php _e("Activate",THEME_TEXTDOMAIN); ?></option>
                            <option <?php echo $admin_disabled;?>  value=2 ><?php _e("Deactivate",THEME_TEXTDOMAIN); ?></option>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php _e("Período de adesão",THEME_TEXTDOMAIN); ?></th>
                    <td>
                        <select name='membership_period'>
                            <option ><?php _e("--Choose Period--",THEME_TEXTDOMAIN); ?></option>
                            <option <?php echo ($userMembership[0]->plan_period==3)?'selected':'' ; ?> value=3 ><?php _e("Quaterly",THEME_TEXTDOMAIN); ?></option>
                            <option <?php echo ($userMembership[0]->plan_period==6)?'selected':'' ; ?>  value=6 ><?php _e("Half Yearly",THEME_TEXTDOMAIN); ?></option>
                            <option <?php echo ($userMembership[0]->plan_period==12)?'selected':'' ; ?>  value=12 ><?php _e("Annualy",THEME_TEXTDOMAIN); ?></option>
                        </select>
                    </td>
                </tr>
                
            </table>
            
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    $('tr.user-first-name-wrap').find('label').text('Commercial Name');
                    $('tr.user-last-name-wrap').find('label').text('Legal Name');
                });

                var placeSearch, autocomplete;
                function initAutocomplete() {
                    autocomplete = new google.maps.places.Autocomplete(
                            (document.getElementById('supplier_location')),
                            {types: ['geocode']});
                    autocomplete.addListener('place_changed', fillInAddress);
                }

                function fillInAddress() {
                    // Get the place details from the autocomplete object.
                    var place = autocomplete.getPlace();
                    
                    var place_id = place.place_id;
                    var lat = place.geometry.location.lat();
                    var lng = place.geometry.location.lng();
                    
                    jQuery('#supplier_location_id').val(place_id);
                    jQuery('#supplier_location_loc').val(Number(lat).toFixed(7) + ',' + Number(lng).toFixed(7));
                }
            </script>
            
            
          <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDnO0V9m09BUXB-JqwuuFno7efIs4FD5nM&libraries=places&callback=initAutocomplete" async defer></script>
         
            
            <?php
        elseif ($pagenow == 'user-new.php'):
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    $('tr.user-first-name-wrap').find('label').text('Commercial Name');
                    $('tr.user-last-name-wrap').find('label').text('Legal Name');
                });
            </script>
            <?php
        endif;
    }

}

add_action('personal_options_update', 'my_save_extra_profile_fields');
add_action('edit_user_profile_update', 'my_save_extra_profile_fields');
add_action('user_register', 'my_save_extra_profile_fields');

function my_save_extra_profile_fields($user_id) {
    $GeneralThemeObject = new GeneralTheme();
    $state = $_POST['state'];
    $city = $_POST['city'];
    $phone = $_POST['phone'];
    $receive_deals = $_POST['receive_deals'];
    $deals_from_date = $_POST['deals_from_date'];
    $deals_to_date = $_POST['deals_to_date'];
    $allow_where_to_buy = $_POST['allow_where_to_buy'];
    $supplier_type = $_POST['supplier_type'];
    $cpf = $_POST['cpf'];
    $cnpj = $_POST['cnpj'];
    $where_to_buy = $_POST['where_to_buy'];
    $supplier_location = $_POST['admin_supplier_location'];
    $supplier_location_loc = $_POST['supplier_location_loc'];
    $supplier_location_id = $_POST['supplier_location_id'];
    $select_product_categories = $_POST['select_product_categories'];
    //$user_logo = $_FILES['supplier_logo'];
    $user_logo = $_POST['supplier_logo'];
    $userActiveStatus = $_POST['user_activation_status'];
    $membership_type = $_POST['membership_type'];
    
    $userMemberShipPeriod = $_POST['membership_period'];
    $MemberShipObject = new classMemberShip();
    $userMembership = $MemberShipObject->getUserMembershipDetails($user_id);


    /* Membership Period */
    if ($userMemberShipPeriod == 3) {
        $planLimitDate = 3;
        $planStartDate = strtotime(date('Y-m-d'));
        $planEndDate = strtotime('+' . $planLimitDate . ' months', strtotime(date('Y-m-d')));
        $planNextPaymentDate = $planEndDate;
    } else if ($userMemberShipPeriod == 6) {
        $planLimitDate = 6;
        $planStartDate = strtotime(date('Y-m-d'));
        $planEndDate = strtotime('+' . $planLimitDate . ' months', strtotime(date('Y-m-d')));
        $planNextPaymentDate = $planEndDate;
    } else if ($userMemberShipPeriod == 12) {
        $planLimitDate = 12;
        $planStartDate = strtotime(date('Y-m-d'));
        $planEndDate = strtotime('+' . $planLimitDate . ' months', strtotime(date('Y-m-d')));
        $planNextPaymentDate = $planEndDate;
    }
   
    if ($userMembership[0]->payment_status == 2){
        /* updated data */
        $updatedData = [
           'plan_period' => $userMemberShipPeriod,
        ];
        /* where data */
        $whereData = [
            'user_id' => $user_id,
        ];
                 
        $updateMembershipData = $MemberShipObject->updateMembershipData($updatedData, $whereData);
    }
    $my_posts = get_posts(array('slug'=>'bronze','post_type'=>'andr_membership'));
    
    if($_POST['action'] == 'createuser' && $_POST['role']=='supplier'){
          update_user_meta($user_id, '_selected_plan', $my_posts[0]->ID);
    }

    if(is_array($select_product_categories) && count($select_product_categories) > 0){
        foreach ($select_product_categories as $eachCat) {
            $getProCatDet = get_term_by('id', $eachCat, themeFramework::$theme_prefix . 'product_category');
            if($getProCatDet->parent != 0 && !in_array($getProCatDet->parent, $select_product_categories)){
                $select_product_categories[] = $getProCatDet->parent;
            }
        }
    }
    
    if (!current_user_can('edit_user', $user_id))
        return false;
    update_user_meta($user_id, '_state', $state);
    update_user_meta($user_id, '_city', $city);
    update_user_meta($user_id, '_mobile_no', $phone);
    update_user_meta($user_id, '_receive_deals', $receive_deals);
    update_user_meta($user_id, '_allow_where_to_buy', $allow_where_to_buy);
    if(!$deals_from_date && !$deals_to_date){
        $deals_from_date = date('d-m-Y');
        $deals_to_date = date('d-m-Y', strtotime("+20 years"));
        update_user_meta($user_id, '_deals_from_date', $deals_from_date);
        update_user_meta($user_id, '_deals_to_date', $deals_to_date);
    } else{
        update_user_meta($user_id, '_deals_from_date', $deals_from_date);
        update_user_meta($user_id, '_deals_to_date', $deals_to_date);
    }
    
    update_user_meta($user_id, '_supplier_categories', $select_product_categories);
    update_user_meta($user_id, '_supplier_type', $supplier_type);
    update_user_meta($user_id, '_cpf', $cpf);
    update_user_meta($user_id, '_cnpj', $cnpj);
    update_user_meta($user_id, '_addressLoc', $supplier_location_loc);
    update_user_meta($user_id, '_addressID', $supplier_location_id);
    //update_user_meta($user_id, '_address', $supplier_location);
    update_user_meta($user_id, '_user_address', $supplier_location);
    update_user_meta($user_id, '_where_to_buy_address', $where_to_buy);
    update_user_meta($user_id, '_admin_approval', $userActiveStatus);
    update_user_meta($user_id, '_selected_start_date', $planStartDate);
    update_user_meta($user_id, '_selected_end_date', $planEndDate);
    update_user_meta($user_id, '_selected_plan_payment_status', 1); 
    update_user_meta($user_id,'_selected_plan',$membership_type );
    
    /* Upload user logo */
    if ($user_logo != '') {
        // $user_pro_img_id = $GeneralThemeObject->common_file_upload($user_logo);
        // $save_user_img_id = $GeneralThemeObject->create_attachment($user_pro_img_id);
        update_user_meta($user_id, '_pro_pic', $user_logo);
    } else {
        $save_user_img_id = get_user_meta($user_id, '_pro_pic', true);
    }
    /* Google data insert */
    $explodedAddressLoc = explode(',', $supplier_location_loc);
    $googleDataArr = [
        'place_id' => $supplier_location_id,
        'address' => $supplier_location,
        'lat' => $explodedAddressLoc[0],
        'lng' => $explodedAddressLoc[1],
    ];
    $GeneralThemeObject->insertGoogleLocation($googleDataArr);
}
