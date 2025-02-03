<?php

namespace ITJ;

class Short_Codes {

    public static function start() {
        add_shortcode( 'category_accordion',  array(get_called_class(), 'display_category_accordion') );
    }

    public static function display_category_accordion(){
        $categories = get_categories( array(
            'parent' => 0
        ) );
        $display_category = '';





        foreach($categories as $key => $category){
            $category_icon_url = get_field('category_icon', 'category_' . $category->term_id );
            $category_img = '';
            if(!empty($category_icon_url)){
                $category_img = '<img src="' . $category_icon_url . '" class="category-icon" alt="' . $category->name . '" />';
            }
            $display_category .='<div class="tab"><input type="checkbox" id="' . $key . '"><label class="tab-label" for="' . $key . '">';
            $display_category .=  $category_img . '<a href="' . esc_url( get_category_link( $category->term_id )) . '"><span class="category-name">' . $category->name . '</span></a>';
            $display_category .= '</label>';

            $child_categories = get_term_children( $category->term_id, 'category' );
            if(!empty($child_categories)){
                $display_category .= '<div class="tab-content">';
                foreach($child_categories as $child){
                    $term = get_term_by( 'id', $child, 'category' );
                    $display_category .= '<a>' . $term->name . '</a>';
                }
                $display_category .= '</div>';

            }


            $display_category .= '</div>';
        }

        $display_category .='<div class="tab"><input type="checkbox" id="website"><label class="tab-label" for="website">';
        $display_category .=  $category_img . '<a target="_blank" href="https://chipperfield.co.uk/"><span class="category-name">Visit Website</span></a>';
        $display_category .= '</label>';
        $display_category .= '</div>';

        return $display_category;
    }
}
Short_Codes::start();
