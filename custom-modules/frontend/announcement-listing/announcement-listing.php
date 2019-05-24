<?php
/*
 * This is the listing of all announcements
 */

$GeneralThemeObject = new GeneralTheme();
$AnnouncementObject = new classAnnouncement();
$userCity = $GeneralThemeObject->getLandingCity();
$WishlistObject = new classWishList();

$paged = ( get_query_var('paged') ) ? absint(get_query_var('paged')) : 1;
$getProductsQuery = ['post_type' => themeFramework::$theme_prefix . 'announcement', 'posts_per_page' => 12, 'paged' => $paged];


if (isset($_GET['search_by_announce_name']) && $_GET['search_by_announce_name'] != '') :
    $getProductsQuery['s'] = $_GET['search_by_announce_name'];
endif;
if (isset($_GET['announcement_filtering']) && $_GET['announcement_filtering'] != '') :
    $getProductFilterParam = base64_decode($_GET['announcement_filtering']);
    if ($getProductFilterParam == 1):
        $getProductsQuery['orderby'] = 'date';
        $getProductsQuery['order'] = 'DESC';
    elseif ($getProductFilterParam == 2):
        $getProductsQuery['orderby'] = 'date';
        $getProductsQuery['order'] = 'ASC';
    endif;
endif;
if (isset($_GET['announcement_type']) && $_GET['announcement_type'] != '') :
    $getProductsMetaQuery[] = [
        'key' => '_announcement_plan',
        'value' => $_GET['announcement_type'],
        'compare' => 'LIKE'
    ];
endif;
if (isset($_GET['search_by_announce_category']) && $_GET['search_by_announce_category'] != '') :
    $getProductsQuery['tax_query'] = [
        [
            'taxonomy' => themeFramework::$theme_prefix . 'announcement_category',
            'field' => 'slug',
            'terms' => $_GET['search_by_announce_category']
        ]
    ];
endif;
$getProductsMetaQuery[] = [
    'key' => '_admin_approval',
    'value' => 1,
    'compare' => '='
];
$getProductsMetaQuery[] = [
    'key' => '_announcement_enabled',
    'value' => 1,
    'compare' => '='
];
 $getProductsMetaQuery[] = [
  'key' => '_announcement_city',
  'value' => $userCity,
  'compare' => 'LIKE'
  ];
$getProductsQuery['meta_query'] = $getProductsMetaQuery;
// echo "<pre>";
// print_r($getProductsQuery);
// echo "</pre>";
$getAnnouncements = new WP_Query($getProductsQuery);
?>
<div class="section-heading clearfix cat-section-heading">
    <h2 class="pull-left">
       <?php


$getQueriedObject = get_queried_object();
$getCategories = get_terms(themeFramework::$theme_prefix . 'product_category', ['hide_empty' => FALSE, 'parent' => 0]);
$userDetails = $GeneralThemeObject->user_details();
?>

<?php   if ($_GET['search_by_announce_category'] == ''): ?>
<?php _e('All anuncios', THEME_TEXTDOMAIN); ?>
 <?php endif;  ?>


   
        <div class="cus" >
            <?php
            if (is_array($getCategories) && count($getCategories) > 0) :
                ?>
                <ul class="nav" id="demo">
                    <?php
                    foreach ($getCategories as $eachcat) :
                        $getSubCategories = get_terms(themeFramework::$theme_prefix . 'product_category', ['hide_empty' => FALSE, 'parent' => $eachcat->term_id]);
                        ?>
                        <li>
                            <a style="<?php echo ($eachcat->name == $_GET['search_by_announce_category']) ? 'color: #fff;background: #4d5967;' : ''; ?>" class="<?php echo ($eachcat->slug == $getQueriedObject->slug) ? 'cat-active' : ''; ?>" data-toggle="collapse" href="<?php echo '#' . $eachcat->slug; ?>"><?php echo $eachcat->name; ?></a>
                           
                                        <li class="<?php echo ($eachcat->slug == $_GET['search_by_announce_category']) ? 'cat-active' : ''; ?>"><?php echo $eachcat->name; ?></li>
                                        <?php 
                                        if (is_array($getSubCategories) && count($getSubCategories) > 0):
                                            foreach ($getSubCategories as $eachSubCategory):
                                                ?>
                                              
                                                <li class="<?php echo ($eachSubCategory->slug == $_GET['search_by_announce_category']) ? 'cat-active' : ''; ?>"><?php echo $eachcat->name; ?><?php echo  ' > ' ; ?><?php echo $eachSubCategory->name; ?> </li>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                                   
                        </li>    
                        <?php
                    endforeach;
                    ?>
                </ul>     
                <?php
            endif;
            ?>
        </div>
   

          
    </h2>
     <div class="pull-right chosen-sm">
    <form name="" id="announcement_filtering_frm" method="get" action="">
        <input type="hidden" name="search_by_announce_name" value="<?php echo $_GET['search_by_announce_name']; ?>"/>
        <!-- Announcement Filtering Section -->
        <div class="chosen-sm">
            <div class="col-sm-6">
                <select name="announcement_filtering" class="announcement-filtering chosen">
                    <option value=""><?php _e('-Ordenar por-', THEME_TEXTDOMAIN); ?></option>
                    <option value="<?php echo base64_encode(1); ?>" <?php echo ($getProductFilterParam == 1) ? 'selected' : ''; ?>><?php _e('New Arrival', THEME_TEXTDOMAIN); ?></option>
                    <option value="<?php echo base64_encode(2); ?>" <?php echo ($getProductFilterParam == 2) ? 'selected' : ''; ?>><?php _e('Most Older', THEME_TEXTDOMAIN); ?></option>
                </select>
            </div>
            <div class="col-sm-6">
                <select name="announcement_type" class="announcement-type-filtering chosen">
                    <option value=""><?php _e('-Tipo de anúncio-', THEME_TEXTDOMAIN); ?></option>
                    <option value="gold" <?php selected('gold', $_GET['announcement_type']); ?>><?php _e('Gold', THEME_TEXTDOMAIN); ?></option>
                    <option value="silver" <?php selected('silver', $_GET['announcement_type']); ?>><?php _e('Silver', THEME_TEXTDOMAIN); ?></option>
                    <option value="bronze" <?php selected('bronze', $_GET['announcement_type']); ?>><?php _e('Bronze', THEME_TEXTDOMAIN); ?></option>
                </select>
            </div>
        </div>
        <!-- End of Announcement Filtering Section --> 
    </form>
</div>
</div>

<!-- Product Listing Section -->
<!--<div class="row">-->
<?php
if ($getAnnouncements->have_posts()):
    while ($getAnnouncements->have_posts()):
        $getAnnouncements->the_post();
        $getAnnouncementDetails = $AnnouncementObject->announcement_details(get_the_ID());
        $announcementAuthorDetails = $GeneralThemeObject->user_details($getAnnouncementDetails->data['author']);
        $user_pro_pic = wp_get_attachment_image_src($announcementAuthorDetails->data['pro_pic'], 'full');
        $getProductImg = wp_get_attachment_image_src($getAnnouncementDetails->data['announcement_single_image'], 'product_listing_image');
        $isItemInWishlist = $WishlistObject->isItemInWishList(get_the_ID(), $userDetails->data['user_id'], $userCity);
        ?>
        <div class="col-sm-4">
            <div class="product-single" style="height:363px;">
                <div class="product-img">
                    <?php if ($userDetails->data['role'] == 'supplier'): ?>
                    <?php else: ?>
                        <a href="javascript:void(0);" class="add-to-wishlist <?php echo ($isItemInWishlist == TRUE) ? 'wish-active' : ''; ?>" title="<?php _e('Add to wishlist', THEME_TEXTDOMAIN); ?>" data-pro="<?php echo base64_encode(get_the_ID()); ?>" data-type="announcement"><i class="fa fa-heart" aria-hidden="true"></i></a>
                    <?php endif; ?>
                    <img src="<?php echo ($getProductImg[0]) ? $getProductImg[0] : 'https://via.placeholder.com/240x200'; ?>" alt="<?php echo $eachAnnouncement->post_title; ?>">
                    <div class="user-ann-icon bronze"></div>
                    <div class="hover">
                        <a href="<?php echo get_permalink($eachAnnouncement->ID); ?>" class="view" title="<?php _e('View', THEME_TEXTDOMAIN); ?>"><i class="fa fa-search-plus" aria-hidden="true"></i></a>
                    </div>
                </div>
               <h3 style="height:auto;"><a href="<?php echo get_permalink($eachAnnouncement->ID); ?>"><?php echo $GeneralThemeObject->limit_text($getAnnouncementDetails->data['title'], 5) ; ?></a></h3>
                <div class="author-image-title">
                    
                   <a href="javascript:void(0);" title="<?php echo $announcementAuthorDetails->data['fname'] . ' ' . $announcementAuthorDetails->data['lname']; ?>"><img src="<?php echo ($announcementAuthorDetails->data['pro_pic_exists'] == TRUE) ? $user_pro_pic[0] : 'https://via.placeholder.com/50x50'; ?>" style="width:40px; height:40px;border-radius: 20px;" />
                   <?php echo $announcementAuthorDetails->data['fname'] . ' ' . $announcementAuthorDetails->data['lname']; ?></a>
                   
                </div>      
            <div class="desc"><?php echo (str_word_count($getAnnouncementDetails->data['content']) > 5) ? $GeneralThemeObject->limit_text($getAnnouncementDetails->data['content'], 5) . '...' : $getAnnouncementDetails->data['content']; ?></div>
            <div class="price"><?php echo ($getAnnouncementDetails->data['announcement_price'] > 0) ? 'R$ ' . number_format($getAnnouncementDetails->data['announcement_price'], 2) : 'Grátis'; ?></div>
            </div>
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
            'total' => $getAnnouncements->max_num_pages
        ));
        ?>
    </div>
    <?php
else:
    ?>
    <div class="alert alert-danger"><?php _e('Sorry!!! Announcement not found based on your search.', THEME_TEXTDOMAIN); ?></div>
<?php
endif;
?>



<style>
ul#li3 li
{
     text-transform: capitalize;
}
    ul#demo li 
    {
   
  text-transform: capitalize;

    display: none !important;
color: white !important;
    }
    ul#demo li.cat-active 
    {
     text-transform: capitalize;
    display: block !important;
color: white !important;
    }
/*   .col .product-single {
    width: 250px;
    margin: 10px !important;
    float: left;
    height: 400px;
}*/
</style>