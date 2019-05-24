<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="profile" href="https://gmpg.org/xfn/11">
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>

        <!-- Basic Loader -->
        <div class="loader" style="display: none;">
            <div class="cssload-thecube">
                <div class="cssload-cube cssload-c1"></div>
                <div class="cssload-cube cssload-c2"></div>
                <div class="cssload-cube cssload-c4"></div>
                <div class="cssload-cube cssload-c3"></div>
            </div>
        </div>
        <!-- End of Basic Loader -->

        <div id="page" class="site">
            <!-- Basic Modals -->
            <!-- End of Basic Modals -->
            <header id="masthead" class="site-header" role="banner">

                <div class="container">
                    <div class="row">
                        <!-- Website Logo -->
                        <div class="col-sm-12" style="text-align:center; padding-top: 10px;">
                            <a href="<?php echo BASE_URL; ?>"><img src="<?php echo ASSET_URL . '/images/modal-logo.png'; ?>"/></a>
                        </div>
                        <!-- End of Website Logo -->
                    </div>
                </div>
            </header><!-- #masthead -->

            <div class="site-content-contain">
                <div id="content" class="site-content">
