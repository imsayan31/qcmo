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
            <!-- Footer Contact Info Section -->
            <div class="col-sm-12">
                <div style="text-align: center; padding-bottom: 15px;">
               Email: contato@quantocustaminhaobra.com.br
            </div>
            </div>
            <!-- End of Footer Contact Info Section -->
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
theme_template_part('user-cart/user-cart-category-popup');

wp_footer();
?>

</body>
</html>
