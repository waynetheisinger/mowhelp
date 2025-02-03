<?php

namespace ITJL_BLOG;

class Theme_Option {

    public static function start() {
        
        add_action('customize_register', array(get_called_class(), 'theme_custom_settings') );
        
        
        add_action('after_setup_theme', array(get_called_class(), 'theme_setup'));
        
       
    }
    
    public static function theme_custom_settings( $wp_customize ){
        
        $wp_customize->add_panel( 'mowblog_setting', array(
    		'title'       => esc_attr__( 'Theme Options', ITJL_BLOG_TEXT_DOMAIN ),
    		'description' => esc_attr__( 'Main theme settings.', ITJL_BLOG_TEXT_DOMAIN ),
    		'priority'    => 10,
    	) );
        
        $wp_customize->add_section( 'mowblog_home_settings', array(
    		'title'    => esc_attr__( 'Home page Category', ITJL_BLOG_TEXT_DOMAIN ),
    		'priority' => 10,
    		'panel' => 'mowblog_setting'
    	) );
    	


    	$wp_customize->add_setting( 'mowblog_home_settings[feature_category]', array(
    		'capability'        => 'edit_theme_options',
    		'sanitize_callback' => 'sanitize_text_field',
    		'default'           => '',
    		'type'              => 'option',
    	) );

        $wp_customize->add_control('mowblog_home_settings[feature_category]',array(
            'label'       => esc_attr__( 'Feature Category', ITJL_BLOG_TEXT_DOMAIN ),
    		'section'     => 'mowblog_home_settings',
    		'description' => esc_attr__( 'Enter category id seprated by commas.', ITJL_BLOG_TEXT_DOMAIN ),
    		'type'        => 'text',
    		'input_attrs' => array(
    			'placeholder' => 'category id seprated by commas',
    		),
        ));
        
    }
    
    
    public static function get_feature_categories(){
        $option = get_option( 'mowblog_home_settings', 0 );
        return  empty($option['feature_category'])? 0 : explode (",", $option['feature_category'] );
    }
    
    public static function theme_setup(){
        add_theme_support('post-thumbnails');
        add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio'));
        
    }

    
}
Theme_Option::start();
