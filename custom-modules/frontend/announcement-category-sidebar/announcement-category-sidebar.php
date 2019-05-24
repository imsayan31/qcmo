<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$getQueriedObject = get_queried_object();
$getCategories = get_terms(themeFramework::$theme_prefix . 'product_category', ['hide_empty' => FALSE, 'parent' => 0]);

if (isset($_GET['search_by_announce_name']) && $_GET['search_by_announce_name'] != ''):
    $search_announce_keyword = $_GET['search_by_announce_name'];
endif;
?>

<!-- Announcement Search Sidebar -->
<div class="announcement-search-sidebar">
    <h2><i class="fa fa-bullhorn"></i> <?php _e('Search Announcements', THEME_TEXTDOMAIN); ?> <a href="<?php echo ANNOUNCEMENT_MAP_LISTING_PAGE; ?>" class="right-icon"><i class="fa fa-globe"></i></a></h2>
    <form name="announcement_search_frm" id="announcement_search_frm" method="get" action="<?php echo ANNOUNCEMENT_LISTING_PAGE; ?>">
        <div class="form-group">
            <input type="text" name="search_by_announce_name" class="form-control" autocomplete="off" value="<?php echo (isset($_GET['search_by_announce_name']) && $_GET['search_by_announce_name'] != '') ? $search_announce_keyword : ''; ?>" placeholder="<?php _e('Type keyword', THEME_TEXTDOMAIN); ?>"/>
        </div>
    </form>
</div>
<!-- End of Announcement Search Sidebar -->

<div class="navbar-header cat-sidebar-header visible-xs">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-sidebar-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="javascript:void(0);" data-toggle="collapse" data-target="#bs-sidebar-navbar-collapse-1"><?php _e('CATEGORIAS', THEME_TEXTDOMAIN); ?></a>
</div>

<div class="sidebar navbar-collapse collapse" id="bs-sidebar-navbar-collapse-1" aria-expanded="false" style="height: 1px;">
    <div class="widget-title hidden-xs">
        <h2><?php _e('CATEGORIAS DE ANÚNCIOS', THEME_TEXTDOMAIN); ?></h2>
    </div>
    <div class="cust-scroll" style="max-height: 625px;">
        <div class="cust-scroll" style="">
            <?php
            if (is_array($getCategories) && count($getCategories) > 0) :
                ?>
                <ul class="nav navbar-nav" id="li3">
                    <?php
                    foreach ($getCategories as $eachcat) :
                        $getSubCategories = get_terms(themeFramework::$theme_prefix . 'product_category', ['hide_empty' => FALSE, 'parent' => $eachcat->term_id]);
                        ?>
                        <li>
                            <a style="<?php echo ($eachcat->slug == $_GET['search_by_announce_category']) ? 'color: #fff;background: #4d5967;' : ''; ?>" class="<?php echo ($eachcat->slug == $_GET['search_by_announce_category']) ? 'cat-active' : ''; ?>" data-toggle="collapse" href="<?php echo '#' . $eachcat->slug; ?>"><?php echo $eachcat->name; ?></a>
                            <div id="<?php echo $eachcat->slug; ?>" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <ul class="nav navbar-nav">
                                        <li class="<?php echo ($eachcat->slug == $_GET['search_by_announce_category']) ? 'cat-active' : ''; ?>"><a href="<?php echo ANNOUNCEMENT_LISTING_PAGE . '?search_by_announce_category=' . $eachcat->slug; ?>"><?php _e('All products', THEME_TEXTDOMAIN); ?></a></li>
                                        <?php
                                        if (is_array($getSubCategories) && count($getSubCategories) > 0):
                                            foreach ($getSubCategories as $eachSubCategory):
                                                ?>
                                                <li class="<?php echo ($eachSubCategory->slug == $_GET['search_by_announce_category']) ? 'cat-active' : ''; ?>"><a href="<?php echo ANNOUNCEMENT_LISTING_PAGE . '?search_by_announce_category=' . $eachSubCategory->slug; ?>"><?php echo $eachSubCategory->name; ?></a></li>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </li>    
                        <?php
                    endforeach;
                    ?>
                </ul>     
                <?php
            endif;
            ?>
        </div>
    </div>
</div>

<?php
