<?php

namespace ITJ;

class Posts {

    public static function start() {
        add_filter( 'elementor/utils/get_the_archive_title', array(get_called_class(), 'prepend_category_icon') );
        
        add_filter( 'list_cats', array(get_called_class(), 'show_category_walker_icon'), 10 , 2);
        add_shortcode( 'category_name_with_icon',  array(get_called_class(), 'custom_post_meta_shortcode') );
        
    }
    
    public static function show_category_walker_icon ($category_name , $category ){
        
        $category_icon_url = get_field('category_icon', $category);
        
        $category_img = '';
        if(!empty($category_icon_url)){
            $category_img = '<img src="' . $category_icon_url . '" class="category-icon" alt="' . $category_name . '" />';
        }
        
        return $category_img .  '<span>' . $category_name . '</span>';
    }
    
    public static function prepend_category_icon( $title ) {
        if ( is_category() ) {
            $title = self::process_category_title();
        } 
      
        return $title;
    }
    
    
    

    public static function process_category_title(){
        // get the current taxonomy term
        $term = get_queried_object();
        $category_icon_url = get_field('category_icon', $term);
        
        $category_img = '';
        if(!empty($category_icon_url)){
            $category_img = '<img src="' . $category_icon_url . '" class="category-icon" alt="category image" />';
        }
        
        return single_cat_title($category_img, false );
    }
    
    public static function custom_post_meta_shortcode(){
        
        global $post;
        
        $post_meta_box = '';
        $postcat = get_the_category( $post->ID );
        if ( ! empty( $postcat ) ) {
            $category_icon_url = get_field('category_icon', 'category_' . $postcat[0]->term_id );
        
            $category_img = '';
            if(!empty($category_icon_url)){
                $category_img = '<img src="' . $category_icon_url . '" class="category-icon" alt="' . $postcat[0]->name . '" />';
            }
            
            $post_meta_box .=  '<a href="' . esc_url( get_category_link( $postcat[0]->term_id )) . '">' . $category_img . '<span class="category-name">' . $postcat[0]->name . '</span></a>'; 
        }
        
        //$post_meta_box .= '<time datetime="' . get_the_date('c') . '" itemprop="datePublished">' . get_the_date() . '</time>';

        
        
        return $post_meta_box;
    }
            
 


}

Posts::start();




