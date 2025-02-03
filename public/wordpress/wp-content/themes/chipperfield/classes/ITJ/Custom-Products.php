<?php

namespace ITJ;

class Custom_Products {

    public static function start() {
        add_action( 'init', array(get_called_class(), 'custom_products'), 0);
    }
    
    // Register Custom Post Type
    public static function custom_products() {
        $labels = array(
            'name'                  => _x( 'Custom Products', 'Post Type General Name', 'ITJ' ),
            'singular_name'         => _x( 'Custom Product', 'Post Type Singular Name', 'ITJ' ),
            'menu_name'             => __( 'Custom Products', 'ITJ' ),
            'name_admin_bar'        => __( 'Custom Products', 'ITJ' ),
            'archives'              => __( 'Products Archives', 'ITJ' ),
            'attributes'            => __( 'Products Attributes', 'ITJ' ),
            'parent_item_colon'     => __( 'Parent Products:', 'ITJ' ),
            'all_items'             => __( 'All Products', 'ITJ' ),
            'add_new_item'          => __( 'Add New Product', 'ITJ' ),
            'add_new'               => __( 'Add New', 'ITJ' ),
            'new_item'              => __( 'New Product', 'ITJ' ),
            'edit_item'             => __( 'Edit Product', 'ITJ' ),
            'update_item'           => __( 'Update Product', 'ITJ' ),
            'view_item'             => __( 'View Product', 'ITJ' ),
            'view_items'            => __( 'View Product', 'ITJ' ),
            'search_items'          => __( 'Search Product', 'ITJ' ),
            'not_found'             => __( 'Not found', 'ITJ' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'ITJ' ),
            'featured_image'        => __( 'Featured Image', 'ITJ' ),
            'set_featured_image'    => __( 'Set featured image', 'ITJ' ),
            'remove_featured_image' => __( 'Remove featured image', 'ITJ' ),
            'use_featured_image'    => __( 'Use as featured image', 'ITJ' ),
            'insert_into_item'      => __( 'Insert into Product', 'ITJ' ),
            'uploaded_to_this_item' => __( 'Uploaded to this Product', 'ITJ' ),
            'items_list'            => __( 'Products list', 'ITJ' ),
            'items_list_navigation' => __( 'Products list navigation', 'ITJ' ),
            'filter_items_list'     => __( 'Filter Products list', 'ITJ' ),
        );
        $args = array(
            'label'                 => __( 'Custom Product', 'ITJ' ),
            'description'           => __( 'External Products', 'ITJ' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions' ),
            'taxonomies'            => array( 'category', 'post_tag' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-products',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => false,
        );
        register_post_type( 'custom_products', $args );
    }



}

Custom_Products::start();
