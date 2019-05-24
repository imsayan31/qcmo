<?php
/*
 * Template Name: Supplier Review Template
 * 
 */
get_header();
?>
<section class="dashboard block-row">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-4">
                <!-- Fetching Account Side bar -->
                <?php theme_template_part('account-sidebar/account_sidebar'); ?>
            </div>
            <div class="col-md-9 col-sm-8">
                <div class="section-heading">
                    <h2><?php _e('Create Supplier Score', THEME_TEXTDOMAIN); ?></h2>
                </div>
                <!-- Fetching User Dashboard Page -->
                <?php theme_template_part('supplier-rating/supplier-review-rating'); ?>
                <?php theme_template_part('supplier-rating/supplier-review-rating-mobile'); ?>
            </div>
        </div>
    </div>
</section>
<?php
get_footer();
