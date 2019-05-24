<?php
/*
 * This page shows the listing of all products
 * 
 */
$GeneralThemeObject = new GeneralTheme();
$WishlistObject = new classWishList();
$userCity = $GeneralThemeObject->getLandingCity();

// echo $userCity;

$userDetails = $GeneralThemeObject->user_details();
$is_taxonomy = is_tax();
$paged = ( get_query_var('paged') ) ? absint(get_query_var('paged')) : 1;
$getProductsQuery = ['post_type' => themeFramework::$theme_prefix . 'product', 'posts_per_page' => 12, 'paged' => $paged, 'meta_query' => [
        [
            'key' => '_product_cities',
            // 'value' => serialize(strval($userCity)),
            'value' => $userCity,
            'compare' => 'LIKE'
        ]
        ]];
// if(!is_tax()):
// $getProductsQuery['meta_key'] = '_product_city_price_'.$userCity;
// endif;
/*$getProductsQuery['meta_value'] = 0;
$getProductsQuery['meta_compare'] = '>';
$getProductsQuery['meta_type'] = 'numeric';*/

if (isset($_GET['product_keyword']) && $_GET['product_keyword'] != '') :
    $getProductsQuery['s'] = $_GET['product_keyword'];
endif;

if (isset($_GET['sorting_param']) && $_GET['sorting_param'] != '') :
    $getProductFilterParam = base64_decode($_GET['sorting_param']);
   
   // exit;
    if ($getProductFilterParam == 1):
        $getProductsQuery['orderby'] = 'date';
        $getProductsQuery['order'] = 'DESC';
    elseif ($getProductFilterParam == 2):
        //$getProductsQuery['meta_key'] = '_product_current_city_price';
        
        $getProductsQuery['orderby'] = 'meta_value_num';
        $getProductsQuery['order'] = 'ASC';
       
     
    elseif ($getProductFilterParam == 3):
        //$getProductsQuery['meta_key'] = '_product_current_city_price';
        
        $getProductsQuery['orderby'] = 'meta_value_num';
        $getProductsQuery['order'] = 'DESC';
    elseif ($getProductFilterParam == 4):
    endif;
endif;

if ($is_taxonomy):
    $getQueriedObject = get_queried_object();
    $getProductsQuery['tax_query'] = [
        [
            'taxonomy' => themeFramework::$theme_prefix . 'product_category',
            'field' => 'slug',
            'terms' => $getQueriedObject->slug
        ]
    ];
endif;

$getProducts = new WP_Query($getProductsQuery);

$productCount = $getProducts->post_count;
if ($productCount == 0):
    $getNotMatchedProducts = get_option('_not_matched_products');
    if (isset($_GET['product_keyword']) && $_GET['product_keyword'] != '' && str_word_count($_GET['product_keyword']) >= 2) :
        $productKeyword = $_GET['product_keyword'];
        $userSignificance = (is_user_logged_in()) ? $userDetails->data['user_id'] : 'unknown';
        $getKeyWord = $userSignificance . '%' . $productKeyword . '%' . time();
        if (is_array($getNotMatchedProducts) && count($getNotMatchedProducts) > 0):
            if (!in_array($getKeyWord, $getNotMatchedProducts)):
                $getNotMatchedProducts[] = $getKeyWord;
            endif;
        else:
            $getNotMatchedProducts[] = $getKeyWord;
        endif;
        update_option('_not_matched_products', $getNotMatchedProducts);
    endif;
endif;
?>
<div class="section-heading clearfix cat-section-heading">
    <h2 class="pull-left">
        <?php
        if ($is_taxonomy):
            if ($getQueriedObject->parent == 0):
                echo $getQueriedObject->name;
            else:
                $getCatParent = get_term_by('id', $getQueriedObject->parent, themeFramework::$theme_prefix . 'product_category');
                echo $getCatParent->name . ' > ' . $getQueriedObject->name;
            endif;
        else:
            _e('All products', THEME_TEXTDOMAIN);
        endif;
        ?>
    </h2>
    <!-- Product Filtering Section -->
    <div class="pull-right chosen-sm">
        <select name="sorting_param" class="product-filtering chosen">
            <option value=""><?php _e('Filter your products', THEME_TEXTDOMAIN); ?></option>
            <option value="<?php echo base64_encode(1); ?>" <?php echo ($getProductFilterParam == 1) ? 'selected' : ''; ?>><?php _e('New Arrival', THEME_TEXTDOMAIN); ?></option>
            <option value="<?php echo base64_encode(2); ?>" <?php echo ($getProductFilterParam == 2) ? 'selected' : ''; ?>><?php _e('Price: Low to high', THEME_TEXTDOMAIN); ?></option>
            <option value="<?php echo base64_encode(3); ?>" <?php echo ($getProductFilterParam == 3) ? 'selected' : ''; ?>><?php _e('Price: High to low', THEME_TEXTDOMAIN); ?></option>
            <!--<option value="<?php echo base64_encode(4); ?>" <?php echo ($getProductFilterParam == 4) ? 'selected' : ''; ?>><?php _e('Popularity', THEME_TEXTDOMAIN); ?></option>-->
        </select>
    </div>
    <!-- End of Product Filtering Section -->
</div>

<!-- Product Listing Section -->
<!--<div class="row">-->
    <?php

/*echo "<pre>";
print_r($getProducts);
echo "</pre>";*/
    
    if ($getProducts->have_posts()):
        while ($getProducts->have_posts()):
            $getProducts->the_post();
            $getProductDetails = $GeneralThemeObject->product_details(get_the_ID());
            $getProductImg = wp_get_attachment_image_src($getProductDetails->data['thumbnail_id'], 'product_listing_image');
            $isItemInWishlist = $WishlistObject->isItemInWishList(get_the_ID(), $userDetails->data['user_id'], $userCity);
            
            ?>
            <div class="col-sm-4">
                <div class="product-single">
                    <div class="product-img">
                        <?php if ($userDetails->data['role'] == 'supplier'): ?>
                        <?php else: ?>
                            <a href="javascript:void(0);" class="add-to-wishlist <?php echo ($isItemInWishlist == TRUE) ? 'wish-active' : ''; ?>" title="<?php _e('Add to wishlist', THEME_TEXTDOMAIN); ?>" data-pro="<?php echo base64_encode(get_the_ID()); ?>" data-type="product"><i class="fa fa-heart" aria-hidden="true"></i></a>
                        <?php endif; ?>
                        <img src="<?php echo ($getProductImg[0]) ? $getProductImg[0] : 'https://via.placeholder.com/240x200'; ?>" alt="<?php echo get_the_title(); ?>">
                        <?php if ($getProductDetails->data['is_simple'] == FALSE): ?>
                                    <div class="bundle-ann-icon">
                                        <a href="javascript:void(0);"><img src="<?php echo ASSET_URL . '/images/bundle-products-icon.png'; ?>"></i></a>
                                    </div>
                                <?php endif; ?>
                        <div class="hover">
                            <?php if ($userDetails->data['role'] == 'supplier'): ?>
                            <?php else: ?>
                                <a href="javascript:void(0);" class="add-to-cart" title="<?php _e('Add to cart', THEME_TEXTDOMAIN); ?>" data-pro="<?php echo base64_encode(get_the_ID()); ?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                            <?php endif; ?>
                            <a href="<?php echo get_permalink($getProductDetails->data['ID']); ?>" class="view" title="<?php _e('View', THEME_TEXTDOMAIN); ?>"><i class="fa fa-search-plus" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <h3><a href="<?php echo get_permalink(); ?>"><?php echo $getProductDetails->data['title']; ?></a></h3>
                    <!--<div class="desc" style="min-height: 42px;"><?php echo (strlen($getProductDetails->data['description']) > 50) ? substr($getProductDetails->data['description'], 0, 50) . '..' : $getProductDetails->data['description']; ?></div>-->
                    <div class="price"><?php echo $getProductDetails->data['price']; ?><?php echo ($getProductDetails->data['unit']) ? '/' . $getProductDetails->data['unit'] : ''; ?></div>
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
                'total' => $getProducts->max_num_pages
            ));
            ?>
        </div>
        <?php
    else:
        ?>
        <div class="alert alert-danger"><?php _e('Sorry!!! Product not found based on your search.', THEME_TEXTDOMAIN); ?></div>
    <?php
    endif;
    ?>
<!--</div>-->
<!-- End of Product Listing Section -->
    <?php
    