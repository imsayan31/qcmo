<?php

/**
 * ------------------------------------------------
 * CORE:: THEME CUSTOM POST TYPE CREATION
 * ------------------------------------------------ 
 */
if (!function_exists('theme_core_setup')) {

    function theme_core_setup() {
        theme_register_post_type_func(themeFramework::$theme_prefix . 'mail_template', 'mail_template', 'Mail Template', 'Mail Template', 'dashicons-email', ['title', 'editor']);
        theme_register_post_type_func(themeFramework::$theme_prefix . 'advertisement', 'advertisement', 'Advertisement', 'Advertisement', 'dashicons-layout', ['title', 'editor', 'thumbnail'], themeFramework::$theme_prefix . 'advertisement');
        theme_register_post_type_func(themeFramework::$theme_prefix . 'product', 'product', 'Product', 'Product', 'dashicons-screenoptions', ['title', 'editor', 'thumbnail']);
        theme_register_post_type_func(themeFramework::$theme_prefix . 'membership', 'membership', 'Membership Plan', 'Membership Plan', 'dashicons-awards', ['title', 'editor', 'thumbnail']);
        theme_register_post_type_func(themeFramework::$theme_prefix . 'announcement', 'announcement', 'Announcement', 'Announcement', 'dashicons-controls-volumeon', ['title', 'editor', 'thumbnail']);
        theme_register_post_type_func(themeFramework::$theme_prefix . 'coupon', 'coupon', 'Coupon', 'Coupon', THEME_URL.'/assets/images/coupon_icon.png', ['title', 'editor', 'thumbnail']);
        theme_register_taxonomy_func(themeFramework::$theme_prefix . 'product_category', themeFramework::$theme_prefix . 'product', 'product-category', 'Product Category', 'Product Category');
        theme_register_taxonomy_func(themeFramework::$theme_prefix . 'announcement_category', themeFramework::$theme_prefix . 'announcement', 'announcement-category', 'Announcement Category', 'Announcement Category');
        //theme_register_taxonomy_func(themeFramework::$theme_prefix . 'product_attribute', themeFramework::$theme_prefix . 'product', 'product-attribute', 'Product Attribute', 'Product Attribute');
        theme_register_taxonomy_func(themeFramework::$theme_prefix . 'product_city', themeFramework::$theme_prefix . 'product', 'product-city', 'Product City', 'Product City');
    }

}

/**
 * -----------------------------------------------
 * POST TYPE: REGISTER POST TYPE FUNC
 * -----------------------------------------------
 */
if (!function_exists('theme_register_post_type_func')) {

    function theme_register_post_type_func($post_type, $slug, $name, $menu_name, $menu_icon, $supports = [], $capability_type = NULL) {

        $lastChar = substr($name, -1);
        if ($lastChar == 'y'):
            $pluralTerm = 'ies';
            $explodedName = explode('y', $name);
            $name = $explodedName[0];
            $singleTerm = 'y';
        else:
            $singleTerm = '';
            $pluralTerm = 's';
        endif;

        /* Used for custom role */
        if ($capability_type):
            $capability_type = $capability_type;
        else:
            $capability_type = 'post';
        endif;
        
        if ($slug == 'advertisement' && current_user_can('edit_user')):
            $capability_type = 'post';
        endif;
        /* Used for custom role */

        $labels = array(
            'name' => _x($name . $pluralTerm, 'post type general name', THEME_TEXTDOMAIN),
            'singular_name' => _x($name, 'post type singular name', THEME_TEXTDOMAIN),
            'menu_name' => _x($menu_name, 'admin menu', THEME_TEXTDOMAIN),
            'name_admin_bar' => _x($name, 'add new on admin bar', THEME_TEXTDOMAIN),
            'add_new' => _x('Add New', $slug, THEME_TEXTDOMAIN),
            'add_new_item' => __('Add New ' . $name . $singleTerm, THEME_TEXTDOMAIN),
            'new_item' => __('New ' . $name, THEME_TEXTDOMAIN),
            'edit_item' => __('Edit ' . $name . $singleTerm, THEME_TEXTDOMAIN),
            'view_item' => __('View ' . $name . $singleTerm, THEME_TEXTDOMAIN),
            'all_items' => __('All ' . $name . $pluralTerm, THEME_TEXTDOMAIN),
            'search_items' => __('Search ' . $name . $pluralTerm, THEME_TEXTDOMAIN),
            'parent_item_colon' => __('Parent ' . $name . 's:', THEME_TEXTDOMAIN),
            'not_found' => __('No ' . $name . $pluralTerm . ' found.', THEME_TEXTDOMAIN),
            'not_found_in_trash' => __('No ' . $name . $pluralTerm . ' found in Trash.', THEME_TEXTDOMAIN),
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => $slug),
            'capability_type' => $capability_type,
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'menu_icon' => $menu_icon,
            'supports' => $supports
        );

        register_post_type($post_type, $args);
    }

}

/**
 * ------------------------------------------------
 * TAXONOMY: REGISTER TAXONOMY FUNC
 * ------------------------------------------------
 */
if (!function_exists('theme_register_taxonomy_func')) {

    function theme_register_taxonomy_func($taxonomy, $object = array(), $slug, $title, $hierarchical = true, $meta_box_cb = NULL) {

        $labels = array(
            'name' => __($title),
            'singular_name' => __($title),
            'all_items' => __('All ' . $title),
            'edit_item' => __('Edit ' . $title),
            'view_item' => __('View ' . $title),
            'update_item' => __('Update ' . $title),
            'add_new_item' => __('Add New ' . $title),
            'new_item_name' => __('New ' . $title . ' Name'),
            'parent_item' => __('Parent ' . $title),
            'parent_item_colon' => __('Parent ' . $title . ':'),
            'search_items' => __('Search ' . $title . 's'),
            'popular_items' => __('Popular ' . $title . 's'),
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'show_ui' => true,
            'rewrite' => array(
                'slug' => $slug,
                'with_front' => true
            ),
            'hierarchical' => $hierarchical,
            'show_admin_column' => true,
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'edit_posts'
            ),
            'sort' => false
        );
        if ($slug != 'product-category') {
            $args['meta_box_cb'] = FALSE;
        }
        register_taxonomy($taxonomy, $object, $args);
    }

}