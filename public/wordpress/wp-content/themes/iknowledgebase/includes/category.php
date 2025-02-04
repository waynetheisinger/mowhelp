<?php

class Category {
    public static function start(){
        
        add_filter( 'template_include', [get_called_class(), 'redirect_category_template'], 99);
    }
    
    public static function redirect_category_template($template){
        if(!is_archive()){
            return $template;
        }
        
        $category = get_queried_object();
            if(empty($category->parent)){
                $template = get_template_directory() . "/archive-category.php";
        
                return $template;
            }
        return $template;
        
//         $faq = get_field('faq', $category);
        
//         if(!$faq){
//             return $template;
//         }
        

    }
}
Category::start();