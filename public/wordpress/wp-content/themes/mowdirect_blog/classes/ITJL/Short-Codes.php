<?php

namespace ITJL_BLOG;

class Short_Codes {

    public static function start() {
       add_shortcode( 'navigation_category',  array(get_called_class(), 'display_navigation_category') );
       
        add_shortcode( 'home_category',  array(get_called_class(), 'home_category') );
       
    }

    public static function display_navigation_category(){
        $categories = Featured_Category::get_categories();
        $output = '<div class="category-navigation"> <ul class="category-list"> <li class="category"><a href="/">All</a></li>';
        $current_category = get_queried_object();
        
        foreach($categories as $category){
            $class = (!empty($current_category->term_id) && $current_category->term_id == $category['id'])? 'active' : '';
            $output .= '<li class="category ' . $class . '"> ';
            $output .= '<a href="' . $category['url'] . '"><img src="' . $category['icon'] . '" alt="catergory-name"/><span>' . $category['name'] .'</span> </a> </li>';
            
        }
        $output .= '</ul> </div>';
       
       return $output;
    }
    
    public static function home_category($atts){
        
        $offset = empty($atts['offset'])?0:$atts['offset'];
        $categories = array_slice(Featured_Category::get_categories(), $offset, 2);
        
        if(empty($categories)){
            return '<div class="no-feeds"><p>no more feeds</p></div>';
        }
        
        $output = '<section class="homeCategory">';
        
        foreach($categories as $term ){
            $output .= '<div class="home-category home-category' . $term['id'] . '">';
            $output .= '<div class="d-flex align-center justify-between title-section"><a class="d-flex align-center" href="' . $term['url'] . '"><img src="' . $term['icon'] . '" alt="catergory-name"/><h3>' . $term['name'] .'</h3> </a>';
            $output .= '<span class="seperator"></span>';
            $output .= '<a  class="view-all" href="' . $term['url'] . '"><span>View all</span> </a></div>';
            
            
            $args = array(
            'numberposts' => 6,
            'tax_query' => array(
                array(
                'taxonomy' => 'category',
                'field' => 'term_id',
                'terms' => $term['id']
                 )
              )
            );
            $posts = get_posts($args);
            $output .= '<div class="posts"> <div class="category-posts-slider flag-slick">';
            foreach($posts as $post){
                 $thumb_url = has_post_thumbnail($post)?get_the_post_thumbnail_url($post):'/wp-content/plugins/elementor/assets/images/placeholder.png';
                 $output .= '<div class="category-card home-category-post-' . $post->ID . '">';
                 $output .= '<div class="category-image"><img src="' . $thumb_url . '" onerror="this.onerror=null;this.src=\'/wp-content/uploads/2022/10/blog_default_imgae.png\'" /></div>';
                 $output .= '<span class="badge">' . $term['name'] . '</span>';
                 $output .= '<div class="category-title"><h2><a href="' . get_permalink($post) . '">' . $post->post_title . '</a></h2></div>';
                 
                 $output .= '<div class="category-description"><p>' . $post->post_excerpt . '</p></div>';
                 
                 $output .= '<div class="post-card-footer d-flex align-end justify-between"> ';
                 $output .= '<div class="left-align"> <div class="post-date"> ' . date(" F d, Y ", strtotime($post->post_date)) . '</div>';
                 $output .= '<div class="post-authour">' . get_the_author_meta('display_name', $post->post_author) . '</div></div>';
                 $output .= '<div class="right-align"> <div> <a class="btn-outline" href="' . get_permalink($post) . '"> Read more </a></div> </div>';
                 
                 $output .= '</div></div>';
            }
            $output .= '<div class="category-card view-all" ><a href="'.  $term['url'] .'"><span>View all</span></a></div>';
            
            $output .= '</div></div></div>';
        }
        $output .= '<div class="homeCategorySeeMore" data-offset="' . ($offset + 2) . '" data-ajax="0" ><span class="btn-outline">See more</span></div>';
        
        $output .= '</section>';
        
        return $output;
        
        
    }
}
Short_Codes::start();
