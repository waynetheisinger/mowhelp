<?php

namespace MowDirect\Core;

class Plugin {

  public function __construct(){
    new \MowDirect\Support\CategoryImage;
    $this->add_image_sizes();
    $this->add_global_filters();
    $this->add_sidebars();
  }

  public function add_image_sizes(){
    add_image_size( "category_image", 273, 250, true );
    add_image_size( "featured_image", 658, null, false );
  }

  public function add_global_filters(){
    add_filter("admin_init", array($this, 'admin_init'));
    add_filter( "get_the_archive_title", array($this, "get_the_archive_title") );
    add_filter('wp_trim_excerpt', array($this, 'wp_trim_excerpt'));
  }

  public function admin_init(){
    include(MOWDIRECT_PLUGIN_DIR . "/lib/includes/acf.php");
  }

  public function get_the_archive_title($title){
    return str_replace("Category: ", "", $title);
  }

  public function add_sidebars(){
       /**
      * Creates a sidebar
      * @param string|array  Builds Sidebar based off of 'name' and 'id' values.
      */
      $args = array(
        'name'          => __( 'Homepage', 'mowdirect' ),
        'id'            => 'homepage-sidebar',
        'description'   => '',
        'class'         => '',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget'  => '</li>',
        'before_title'  => '<h2 class="widgettitle"><span class="icon"></span>',
        'after_title'   => '</h2>'
      );
    
      register_sidebar( $args );
    
  }

  public function wp_trim_excerpt( $text ){
    $text = strip_shortcodes( $text );
    $text = apply_filters('the_content', $text);
    $text = str_replace(']]>', ']]&gt;', $text);
    $excerpt_length = apply_filters('excerpt_length', 55);
    $excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
    return wp_trim_words( $text, $excerpt_length, $excerpt_more );
  }

}