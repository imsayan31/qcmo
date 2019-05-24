<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */
get_header();
?>
<?php
$categoryArr = [];
$getQueriedObject = get_queried_object();
$GeneralThemeObject = new GeneralTheme();
$getAuthorPostDetails = get_post($getQueriedObject->ID);
$getAuthorPostImg = wp_get_attachment_image_src(get_post_thumbnail_id($getQueriedObject->ID), 'full');
$getAuthorPostImgPath = get_attached_file(get_post_thumbnail_id($getQueriedObject->ID));
$getAuthorPostObject = wp_get_post_categories($getQueriedObject->ID);
$getAuthorDetails = $GeneralThemeObject->user_details($getAuthorPostDetails->post_author);
if (is_array($getAuthorPostObject) && count($getAuthorPostObject) > 0):
    foreach ($getAuthorPostObject as $eachAuthorPost):
        $getCategoryDetails = get_category($eachAuthorPost);
        $categoryArr[] = $getCategoryDetails->name;
    endforeach;
    $joinedCategoryNames = join(', ', $categoryArr);
else:
    $joinedCategoryNames = 'Uncategorized';
endif;
$postDate = date('d M, Y', strtotime($getAuthorPostDetails->post_date));
                    $explodeDate = explode(" ",$postDate);
                    if($explodeDate[1]=='Jan,')
                    {$explodeDate[1]==='Janeiro,';}else if($explodeDate[1]=='Feb,'){$explodeDate[1]='Fevereiro,';}
                    else if($explodeDate[1]=='Mar,'){$explodeDate[1]='Março,';}
                    else if($explodeDate[1]=='Apr,'){$explodeDate[1]='Abril,';}
                    else if($explodeDate[1]=='May,'){$explodeDate[1]='Maio,';}
                    else if($explodeDate[1]=='Jun,'){$explodeDate[1]='Junho,';}
                    else if($explodeDate[1]=='Jul,'){$explodeDate[1]='Julho,';}
                    else if($explodeDate[1]=='Aug,'){$explodeDate[1]='Agosto,';}
                    else if($explodeDate[1]=='Sep,'){$explodeDate[1]='Setembro,';}
                    else if($explodeDate[1]=='Oct,'){$explodeDate[1]='Outubro,';}
                    else if($explodeDate[1]=='Nov,'){$explodeDate[1]='Novembro,';}
                    else if($explodeDate[1]=='Dec,'){$explodeDate[1]='Dezembro,';}
                    
                    
?>
<style>
    @media only screen and (max-width: 500px){
    .blog_editor_content a{font-size:16px;}    
    }
   div.social-sharing  .fa-facebook{display:none;}
   #my_fb{display:inline-block;}
</style>
<section class="block-row">
    <div class="container">
        <div class="product-single-display">
            <div class="row">
                <div class="col-sm-5">
                    <div class="product-details-image">
                        <img class="img-thumbnail" src="<?php echo ($getAuthorPostImgPath) ? $getAuthorPostImg[0] : 'https://via.placeholder.com/570x600'; ?>" alt="<?php echo $getAuthorPostDetails->post_title; ?>">
                    </div>
                </div>
                <div class="col-sm-7">
                    <h2 class="product-title"><?php echo $getAuthorPostDetails->post_title; ?></h2>
                    <div class="product-category"><label><?php _e('Category: ', THEME_TEXTDOMAIN); ?></label>&nbsp;<?php echo $joinedCategoryNames; ?></div>
                    <div class="product-category"><label><?php _e('Posted by: ', THEME_TEXTDOMAIN); ?></label>&nbsp;<?php echo ($getAuthorDetails->data['fname'] || $getAuthorDetails->data['lname']) ? $getAuthorDetails->data['fname'] . ' ' . $getAuthorDetails->data['lname'] : 'No name'; ?></div>
                    <div class="product-category"><label><?php _e('Posted on: ', THEME_TEXTDOMAIN); ?></label>&nbsp;<?php echo $explodeDate[0].' '.$explodeDate[1].' '.$explodeDate[2]; ?></div>
                    <div class="blog_editor_content"><?php echo apply_filters('the_content', $getAuthorPostDetails->post_content); ?></div>
                </div>
            </div>
        </div>
    </div>
</section>


<div class="wrap">
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <?php
            /* Start the Loop */
            /* while ( have_posts() ) : the_post();

              get_template_part( 'template-parts/post/content', get_post_format() );

              // If comments are open or we have at least one comment, load up the comment template.
              if ( comments_open() || get_comments_number() ) :
              comments_template();
              endif;

              the_post_navigation( array(
              'prev_text' => '<span class="screen-reader-text">' . __( 'Previous Post', 'twentyseventeen' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . __( 'Previous', 'twentyseventeen' ) . '</span> <span class="nav-title"><span class="nav-title-icon-wrapper">' . twentyseventeen_get_svg( array( 'icon' => 'arrow-left' ) ) . '</span>%title</span>',
              'next_text' => '<span class="screen-reader-text">' . __( 'Next Post', 'twentyseventeen' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . __( 'Next', 'twentyseventeen' ) . '</span> <span class="nav-title">%title<span class="nav-title-icon-wrapper">' . twentyseventeen_get_svg( array( 'icon' => 'arrow-right' ) ) . '</span></span>',
              ) );

              endwhile; // End of the loop. */
            ?>

        </main><!-- #main -->
    </div><!-- #primary -->
    <?php //get_sidebar(); ?>
</div><!-- .wrap -->

<?php
get_footer();
