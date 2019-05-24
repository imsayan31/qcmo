<?php
/*
 * Template Name: Blog Listing Template
 * 
 */
get_header();
$GeneralThemeObject = new GeneralTheme();
$paged = ( get_query_var('paged') ) ? absint(get_query_var('paged')) : 1;
$getAuthorPosts = new WP_Query(['post_type' => 'post', 'posts_per_page' => 12, 'paged' => $paged]);
?>
<style>
    .list-post-date{padding-bottom: 15px;}
</style>
<section class="block-row listing-posts">
    <div class="container">
        <div class="section-heading">
            <h2><?php _e('Author Blogs', THEME_TEXTDOMAIN); ?></h2>
        </div>
        <div class="row">
            <?php
            if ($getAuthorPosts->have_posts()):
                while ($getAuthorPosts->have_posts()):
                    $getAuthorPosts->the_post();
                    $getPostDetails = get_post(get_the_ID());
                    $getPostImage = wp_get_attachment_image_src(get_post_thumbnail_id(), 'product_listing_image');
                    $getPostImagePath = get_attached_file(get_post_thumbnail_id());
                    $getAuthorName = $GeneralThemeObject->user_details($getPostDetails->post_author);
                    $postDate = date('d M, Y', strtotime($getPostDetails->post_date));
                    $explodeDate = explode(" ",$postDate);
                    if($explodeDate[1]=='Jan,')
                    {$explodeDate[1]='Janeiro,';}else if($explodeDate[1]=='Feb,'){$explodeDate[1]='Fevereiro,';}
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
                    <div class="col-sm-3" style="text-align:center;">
                        <div class="list-post-img"><a href="<?php echo get_permalink(); ?>"><img src="<?php echo ($getPostImagePath) ? $getPostImage[0] : 'https://via.placeholder.com/240x200'; ?>"/></a></div>
                        <div class="list-post-title"><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></div>
                        <div class="list-post-author"><?php _e('Posted by: ', THEME_TEXTDOMAIN); ?><?php echo ($getAuthorName->data['fname'] || $getAuthorName->data['lname']) ? $getAuthorName->data['fname'] . ' ' . $getAuthorName->data['lname'] : 'No name'; ?></div>
                        <div class="list-post-date"><?php _e('Posted on: ', THEME_TEXTDOMAIN); ?><?php echo $explodeDate[0].' '.$explodeDate[1].' '.$explodeDate[2];; ?></div>
                    </div>
                    <?php
                endwhile;
                ?>
                <div class="clearfix"></div>
                <div class="pagination-links">
                    <?php
                    $big = 999999999;
                    echo paginate_links(array(
                        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                        'format' => '?paged=%#%',
                        'current' => max(1, get_query_var('paged')),
                        'total' => $getAuthorPosts->max_num_pages
                    ));
                    ?>
                </div>
                <?php
            endif;
            ?>
        </div>
    </div>
</section>
<?php
get_footer();
