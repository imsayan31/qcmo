<?php
/*
 * Template Name: User Announcement List Template
 *
 */
get_header();
$GeneralThemeObject = new GeneralTheme();
$GeneralThemeObject->authentic();
$userDetails = $GeneralThemeObject->user_details();
/* Sliders */
$currentDate = strtotime(date('d-m-Y'));
$currentTime = strtotime(date('H:i'));
$getLandingCity = $GeneralThemeObject->getLandingCity();
$getQueriedObject = get_queried_object();
$getTopSlider = $GeneralThemeObject->getAdvertisements($getLandingCity, 1, $getQueriedObject->post_name);
$getMiddleSlider = $GeneralThemeObject->getAdvertisements($getLandingCity, 2, $getQueriedObject->post_name);
$getBottomSlider = $GeneralThemeObject->getAdvertisements($getLandingCity, 3, $getQueriedObject->post_name);


/* Prepare for Announcement payment */

if (isset($_GET['dopayment']) && $_GET['dopayment'] == true && isset($_GET['announcement']) && strip_tags($_GET['announcement']) != '') {
    $AnnouncementObject = new classAnnouncement();
    $announcementID = base64_decode(trim(strip_tags($_GET['announcement'])));

    $announcementDetails = $AnnouncementObject->announcement_details($announcementID);


    //Check announcement is free or not
    if ($announcementDetails->data['estimated_price']) {

        $generateRandomString = $GeneralThemeObject->generateRandomString(8);
        /* Announcement Payment Data */
        $announcementPaymentData = [
            'user_id' => $userDetails->data['user_id'],
            'unique_announcement_code' => $generateRandomString,
            'announcement_id' => $announcementID,
            'total_price' => $announcementDetails->data['estimated_price'],
            'transaction_id' => '',
            'payment_status' => 2,
            'payment_date' => '',
            'plan_type' => $announcementDetails->data['announcement_plan'],
        ];

        $insertedAnnouncementPaymentID = $AnnouncementObject->insertIntoAnnouncementPayment($announcementPaymentData);

        /* PayPal Args */
        $paypal_data_params = array(
            'no_shipping' => '1',
            'no_note' => '1',
            'item_name' => 'Payment for ' . $announcementDetails->data['title'],
            'currency_code' => 'BRL',
            'amount' => $announcementDetails->data['estimated_price'],
            'return' => MY_ANNOUNCEMENTS_PAGE . "/?action=success",
            'cancel_return' => MY_ANNOUNCEMENTS_PAGE . "/?action=cancel",
            'notify_url' => BASE_URL
        );
        $paypal_data_params['custom'] = $announcementID . '#' . $userDetails->data['user_id'] . '#' . $generateRandomString;

        /* process to paypal */
        $Paypal = new Paypal_Standard();
        $paypalActionUrl = $Paypal->preparePaypalData($paypal_data_params);
        
        wp_redirect($paypalActionUrl);
        exit;
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'success'):
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $.notify({message: '<?php _e('Thanks for making payment. It is sucessfull.', THEME_TEXTDOMAIN); ?>'}, {type: 'success', z_index: 20000, close: true, delay: 3000});
            window.location.href = '<?php echo MY_ANNOUNCEMENTS_PAGE; ?>';
        });
    </script>
    <?php
endif;

if (isset($_GET['action']) && $_GET['action'] == 'cancel'):
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $.notify({message: '<?php _e('Sorry!!! Your payment can not be made right now.', THEME_TEXTDOMAIN); ?>'}, {type: 'success', z_index: 20000, close: true, delay: 3000});
        });
    </script>
    <?php
endif;
?>

<section class="dashboard block-row">
    <div class="container">

        <!-- Top Slider -->
        <?php
        if (is_array($getTopSlider) && count($getTopSlider) > 0):
            ?>
            <div class="viewer-slider" style="margin-bottom: 15px; margin-top: 0;">
                <div class="owl-carousel view-slider">
                    <?php
                    foreach ($getTopSlider as $eachSlider):
                        $advDetails = $GeneralThemeObject->advertisement_details($eachSlider->ID);
                        if ($currentDate >= strtotime($advDetails->data['adv_init_date']) && $currentDate < strtotime($advDetails->data['adv_final_date']) && $currentTime >= strtotime($advDetails->data['adv_init_time']) && $currentTime < strtotime($advDetails->data['adv_final_time'])):
                            ?>
                            <a href="<?php echo $advDetails->data['adv_url']; ?>" target="_blank" class="advert-click" data-adv="<?php echo base64_encode($advDetails->data['ID']) ?>">
                                <div class="item">
                                    <img src="<?php echo ($advDetails->data['thumbnail_path']) ? $advDetails->data['thumbnail'] : 'https://via.placeholder.com/1140x150'; ?>" alt="" />
                                    <div class="slide-caption">
                                        <div class="slide-caption-inner">
                                            <?php if ($advDetails->data['adv_enable_banner_text'] == 1): ?>
                                                <h2><?php echo $advDetails->data['title']; ?></h2>
                                            <?php endif; ?>
                                            <?php if ($advDetails->data['adv_enable_view_button'] == 1): ?>
                                                <div class="slide-btn"><span><?php _e('View', THEME_TEXTDOMAIN); ?></span></div>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($advDetails->data['adv_view'] > 0 && $advDetails->data['adv_enable_view_counter'] == 1): ?>
                                            <div class="view-count-btn">
                                                <i class="fa fa-eye" aria-hidden="true"></i> <span><?php echo $advDetails->data['adv_view']; ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                            <?php
                        endif;
                    endforeach;
                    ?>
                </div>
            </div>
            <?php
        endif;
        ?>
        <!-- End of Top Slider -->

        <div class="row">
            <div class="col-md-3 col-sm-4">
                <!-- Fetching Account Side bar -->
                <?php theme_template_part('account-sidebar/account_sidebar'); ?>
            </div>
            <div class="col-md-9 col-sm-8">
                <div class="section-heading">
                    <h2><?php _e('My Announcements', THEME_TEXTDOMAIN); ?></h2>
                </div>
                <!-- Fetching User Announcement Page -->
                <?php theme_template_part('user-announcements/user-announcements'); ?>
                <?php theme_template_part('user-announcements/user-announcements-mobile'); ?>

            </div>
        </div>

        <!-- Middle Slider -->
        <?php
        if (is_array($getMiddleSlider) && count($getMiddleSlider) > 0):
            ?>
            <div class="viewer-slider" style="margin-top:0; margin-bottom: 15px;">
                <div class="owl-carousel view-slider">
                    <?php
                    foreach ($getMiddleSlider as $eachSlider):
                        $advDetails = $GeneralThemeObject->advertisement_details($eachSlider->ID);
                        if ($currentDate >= strtotime($advDetails->data['adv_init_date']) && $currentDate < strtotime($advDetails->data['adv_final_date']) && $currentTime >= strtotime($advDetails->data['adv_init_time']) && $currentTime < strtotime($advDetails->data['adv_final_time'])):
                            ?>
                            <a href="<?php echo $advDetails->data['adv_url']; ?>" target="_blank" class="advert-click" data-adv="<?php echo base64_encode($advDetails->data['ID']) ?>">
                                <div class="item">
                                    <img src="<?php echo ($advDetails->data['thumbnail_path']) ? $advDetails->data['thumbnail'] : 'https://via.placeholder.com/1140x150'; ?>" alt="" />

                                    <div class="slide-caption">
                                        <div class="slide-caption-inner">
                                            <?php if ($advDetails->data['adv_enable_banner_text'] == 1): ?>
                                                <h2><?php echo $advDetails->data['title']; ?></h2>
                                            <?php endif; ?>
                                            <?php if ($advDetails->data['adv_enable_view_button'] == 1): ?>
                                                <div class="slide-btn"><span><?php _e('View', THEME_TEXTDOMAIN); ?></span></div>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($advDetails->data['adv_view'] > 0 && $advDetails->data['adv_enable_view_counter'] == 1): ?>
                                            <div class="view-count-btn">
                                                <i class="fa fa-eye" aria-hidden="true"></i> <span><?php echo $advDetails->data['adv_view']; ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                            <?php
                        endif;
                    endforeach;
                    ?>
                </div>
            </div>
            <?php
        endif;
        ?>
        <!-- End of Middle Slider -->

        <!-- Bottom Slider -->
        <?php
        if (is_array($getBottomSlider) && count($getBottomSlider) > 0):
            ?>
            <div class="viewer-slider" style="margin-top:0;">
                <div class="owl-carousel view-slider">
                    <?php
                    foreach ($getBottomSlider as $eachSlider):
                        $advDetails = $GeneralThemeObject->advertisement_details($eachSlider->ID);
                        if ($currentDate >= strtotime($advDetails->data['adv_init_date']) && $currentDate <= strtotime($advDetails->data['adv_final_date']) && $currentTime >= strtotime($advDetails->data['adv_init_time']) && $currentTime < strtotime($advDetails->data['adv_final_time'])):
                            ?>
                            <a href="<?php echo $advDetails->data['adv_url']; ?>" target="_blank" class="advert-click" data-adv="<?php echo base64_encode($advDetails->data['ID']) ?>">
                                <div class="item">
                                    <img src="<?php echo ($advDetails->data['thumbnail_path']) ? $advDetails->data['thumbnail'] : 'https://via.placeholder.com/1140x150'; ?>" alt="" />
                                    <div class="slide-caption">
                                        <div class="slide-caption-inner">
                                            <?php if ($advDetails->data['adv_enable_banner_text'] == 1): ?>
                                                <h2><?php echo $advDetails->data['title']; ?></h2>
                                            <?php endif; ?>
                                            <?php if ($advDetails->data['adv_enable_view_button'] == 1): ?>
                                                <div class="slide-btn"><span><?php _e('View', THEME_TEXTDOMAIN); ?></span></div>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($advDetails->data['adv_view'] > 0 && $advDetails->data['adv_enable_view_counter'] == 1): ?>
                                            <div class="view-count-btn">
                                                <i class="fa fa-eye" aria-hidden="true"></i> <span><?php echo $advDetails->data['adv_view']; ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                            <?php
                        endif;
                    endforeach;
                    ?>
                </div>
            </div>
            <?php
        endif;
        ?>
        <!-- End of Bottom Slider -->

    </div>
</section>
<script>
    jQuery(document).ready(function ($) {
        var owl1 = $('.view-slider');
        owl1.owlCarousel({
//            items: 4,
            loop: true,
            responsiveClass: true,
            nav: true,
            dots: false,
            autoplayTimeout:<?php echo ($globalAdvTiming) ? $globalAdvTiming : '5000'; ?>,
            autoplay: true,
            //autoplaySpeed: <?php echo $globalAdvTiming; ?>,
            navText: [
                "<i class='fa fa-angle-left' style='padding:4px 12px;'></i>",
                "<i class='fa fa-angle-right' style='padding:4px 12px;'></i>"
            ],
            beforeInit: function (elem) {
                //Parameter elem pointing to $("#owl-demo")
                random(elem);
            },
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 1
                },
                1000: {
                    items: 1
                }
            }
        });
    });
</script>
<?php
get_footer();
