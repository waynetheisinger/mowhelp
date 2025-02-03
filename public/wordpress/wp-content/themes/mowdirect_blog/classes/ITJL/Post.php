<?php

namespace ITJL_BLOG;

class Post {

    public static function start() {
      add_action( 'pre_get_posts', array(get_called_class(), 'filter_posts') );
    }
    
    public static function filter_posts($query){
        if($query->is_main_query() && !is_admin() && is_archive()){
            if(!empty($_GET['s']) ){
                $category = get_queried_object();
                $query->set( 'category__in' , [$category->term_id]);
                $query->set( 's' , $_GET['s'] );
               
            }
            
        }
    }
    
    
    
}
Post::start();
