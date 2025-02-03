<?php

namespace ITJL_BLOG;

class Featured_Category {

    public static function start() {
            
        self::get_categories();
            
        add_action('rest_api_init', [get_called_class(), 'register_routes']);
       
    }
    
    
    public static function register_routes(){
        register_rest_route(ITJL_BLOG_VERSION_API_VERSION, '/home_category', array(
                'methods' => 'GET',
                'callback' => array(get_called_class(), 'home_category'),
                'args' => ['offset' ]

            ));
    }
    
    
    public static function home_category($args){
        $length = 2;
        $next_offset = $args['offset'] + $length;
        return [
            'next_offset' => $next_offset,
            "render_html" => do_shortcode('[home_category offset="' . $args['offset'] . '"]')
            ];
    }
    
    public static function get_categories(){
       $category_ids = Theme_Option::get_feature_categories();
       $categories = [];
       foreach($category_ids as $cat_id ){
           $terms = get_term( $cat_id );
           $categories[]=[
               "id" => $cat_id,
               "name" => $terms->name,
               "icon" => ITJL_BLOG_ASSETS_IMAGE_URI . "category/icon/" . $cat_id . ".png",
               "url" => get_term_link($terms)
            ];
       }
       return $categories;
    }
}
Featured_Category::start();
