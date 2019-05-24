<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.2
 */
?>

</div><!-- #content -->
</div><!-- .site-content-contain -->


<footer id="colophon" class="site-footer" role="contentinfo">
    <div class="container">
        <div class="row">
            <!-- Footer Menu Section -->
            <div class="col-sm-3">
                <?php if (is_active_sidebar('sidebar-2')) : ?>
                    <?php dynamic_sidebar('sidebar-2'); ?>
                <?php endif; ?>
            </div>
            <!-- End of Footer Menu Section -->

            <!-- Footer Follow Us Section -->
            <div class="col-sm-3">
                <?php if (is_active_sidebar('sidebar-3')) : ?>
                    <?php dynamic_sidebar('sidebar-3'); ?>
                <?php endif; ?>
            </div>
            <!-- End of Footer Follow Us Section -->

            <!-- Footer Contact Info Section -->
            <div class="col-sm-4">
                <?php if (is_active_sidebar('sidebar-4')) : ?>
                    <?php dynamic_sidebar('sidebar-4'); ?>
                <?php endif; ?>
            </div>
            <!-- End of Footer Contact Info Section -->

            <!-- Footer Map Section -->
            <div class="col-sm-2">
                <!--<a href="javascript:void(0);" class="all-brazil-click" data-addrs="<?php _e('Brazil', THEME_TEXTDOMAIN); ?>" data-lt_lng="<?php _e('-22.9034,-43.1917', THEME_TEXTDOMAIN); ?>" data-placeid="<?php _e('ChIJzyjM68dZnAARYz4p8gYVWik', THEME_TEXTDOMAIN); ?>"><img src="<?php echo get_template_directory_uri() . '/assets/images/footer-map.png'; ?>" alt="image" /></a>-->
                <!--<a href="<?php echo SUPPLIER_LISTING_PAGE . '?supplier_location=Brazil&supplier_location_loc=-22.9034,-43.1917&supplier_location_id=ChIJzyjM68dZnAARYz4p8gYVWik'; ?>"><img src="<?php echo get_template_directory_uri() . '/assets/images/footer-map.png'; ?>" alt="image" /></a>-->
                <a href="<?php echo SUPPLIER_LISTING_PAGE; ?>"><img src="<?php echo get_template_directory_uri() . '/assets/images/footer-map.png'; ?>" alt="image" /></a>
            </div>
            <!-- End of Footer Map Section -->
        </div>
    </div>

    <!-- Footer Copyright Section -->
    <div class="f-btm">
        <div class="container">
            <?php get_template_part('template-parts/footer/site', 'info'); ?>
        </div><!-- .container -->
    </div>
    <!--End of Footer Copyright Section -->
</footer><!-- #colophon -->
</div><!-- #page -->
<?php
theme_template_part('choose-city-on-landing/choose-city-popup');
theme_template_part('user-forgot-password/user-forgot-password-popup');
theme_template_part('user-log-in/user-login-popup');
theme_template_part('user-registration/user-registration-popup');
theme_template_part('user-registration/supplier-registration-popup');
theme_template_part('user-reset-password/user-reset-password-popup');
theme_template_part('user-plan-purchase/user-plan-purchase-popup');
theme_template_part('supplier-rating/supplier-rating-popup');
theme_template_part('paypal-donation/paypal-donation-popup');
theme_template_part('user-finalize/user-deal-finalize-popup');
theme_template_part('user-finalize/user-deal-finalize-update-popup');
theme_template_part('user-cart/user-cart-category-popup');
theme_template_part('announcement-module/user-announcement-payment-popup');
theme_template_part('user-deals/user-deal-locking-popup');

wp_footer();
$displaySupplierMap = (is_page('fornecedores') || is_page('mapa-de-anuncios')) ? true : false;
$displayAuthorMap = (is_author() || is_singular(themeFramework::$theme_prefix . 'announcement') ) ? true : false;
?>
<script>
    // Can also be used with $(document).ready()
    var $ = jQuery;
    $(window).load(function () {
        // The slider being synced must be initialized first
        $('#carousel').flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: true,
            slideshow: true,
            itemWidth: 85,
            itemHidth: 90,
            itemMargin: 5,
            asNavFor: '#slider'
        });

        $('#slider').flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: true,
            slideshow: true,
            sync: "#carousel"
        });
    });
</script>
<script type="text/javascript">
        var placeSearch, autocomplete, autocomplete1,autocomplete2, displaySupplierMap = '<?php echo $displaySupplierMap; ?>', displayAuthorMap = '<?php echo  $displayAuthorMap; ?>';
        function initAutocomplete() {
            autocomplete = new google.maps.places.Autocomplete(
                    (document.getElementById('supplier_location')),
                    {types: ['geocode']});
            autocomplete.inputId = 'supplier_location';
            autocomplete1 = new google.maps.places.Autocomplete(
                    (document.getElementById('usr_reg_address')),
                    {types: ['geocode']});
            autocomplete1.inputId = 'usr_reg_address';        
            autocomplete2 = new google.maps.places.Autocomplete(
                    (document.getElementById('supplier_address')),
                    {types: ['geocode']});
            autocomplete2.inputId = 'supplier_address'; 
            
            autocomplete.addListener('place_changed', fillInAddress);
            autocomplete1.addListener('place_changed', fillInAddress);
            autocomplete2.addListener('place_changed', fillInAddress);
            
            if(displaySupplierMap === '1') {
                initSupplierMap();
            }
            
            if(displayAuthorMap === '1') {
                initMap();
            }
            
        }

        function fillInAddress() {
            // Get the place details from the autocomplete object.
            var place = this.getPlace();
            var place_id = place.place_id;
            var lat = place.geometry.location.lat();
            var lng = place.geometry.location.lng();
            
            if(this.inputId == 'supplier_location') {
                jQuery('#supplier_location_id').val(place_id);
                jQuery('#supplier_location_loc').val(Number(lat).toFixed(7) + ',' + Number(lng).toFixed(7));
            } else if(this.inputId == 'usr_reg_address') {
                jQuery('#usr_reg_address_id').val(place_id);
                jQuery('#usr_reg_address_loc').val(Number(lat).toFixed(7) + ',' + Number(lng).toFixed(7));
            } else if(this.inputId == 'supplier_address') {
                jQuery('#supplier_address_id').val(place_id);
                jQuery('#supplier_address_loc').val(Number(lat).toFixed(7) + ',' + Number(lng).toFixed(7));
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
<?php  if(!is_page('gerenciar-anuncios')){?>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDnO0V9m09BUXB-JqwuuFno7efIs4FD5nM&libraries=places&callback=initAutocomplete" async defer></script>
<!-- <script src="https://code.jquery.com/mobile/1.1.1/jquery.mobile-1.1.1.min.js"></script> -->
<?php  }?>
</body>
</html>
