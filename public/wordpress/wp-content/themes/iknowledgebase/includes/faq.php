<?php

class Faq {
    public static function start(){
        //self::register_custom_post_type("Faq","faq");
        
        add_filter( 'template_include', [get_called_class(), 'redirect_faq_template'], 99);
        
        //add_action( 'pre_get_posts', array(get_called_class(), 'add_faq_posts') );
    }
    
    public static function add_faq_posts($query){
        
        if($query->is_main_query() && !is_admin() && is_archive()){
            
            $category = get_queried_object();
        
            $faq = get_field('faq', $category);
            
            if($faq){
                // var_dump($query);
                // die();
                $query->set('post__in', [2053]);
                $query->set('post_type', ['post', 'faq']);
            }
            
        
        }
        
    }
    
    public static function redirect_faq_template($template){
        if(!is_archive()){
            return $template;
        }
        
        $category = get_queried_object();
        
        $faq = get_field('faq', $category);
        
        if(!$faq){
            return $template;
        }
        
        $template = get_template_directory() . "/archive-faq.php";
        
        return $template;
    }
    
    public static function register_custom_post_type($name, $slug, $supports = []) {

        if (empty($name) || empty($slug)) {
            return false;
        }

        if (empty($supports)) {
            $supports = array('title', 'editor', 'thumbnail', 'page-attributes');
        }

        $labels = array(
            'name' => __($name, 'iknowledgebase'),
            'singular_name' => __($name, 'iknowledgebase'),
            'menu_name' => __($name, 'iknowledgebase'),
            'parent_item_colon' => __('Parent Item:', 'iknowledgebase'),
            'all_items' => __('All ' . $name, 'iknowledgebase'),
            'view_item' => __('View ' . $name, 'iknowledgebase'),
            'add_new_item' => __('Add New ' . $name, 'iknowledgebase'),
            'add_new' => __('Add New', 'iknowledgebase'),
            'edit_item' => __('Edit ' . $name, 'iknowledgebase'),
            'update_item' => __('Update ' . $name, 'iknowledgebase'),
            'search_items' => __('Search ' . $name, 'iknowledgebase'),
            'not_found' => __($name . ' Not found', 'iknowledgebase'),
            'not_found_in_trash' => __($name . ' Not found in Trash', 'iknowledgebase'),
        );
        $args = array(
            'label' => __($name, 'iknowledgebase'),
            'description' => __('Description', 'iknowledgebase'),
            'labels' => $labels,
            'supports' => $supports,
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'menu_position' => 8,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'post',
        );
        add_theme_support('post-thumbnails');
        register_post_type($slug, $args);
        return true;
    }
}
Faq::start();