<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


define( 'ITJL_BLOG_VERSION', '1.0.0' );
define('ITJL_BLOG_VERSION_API_VERSION', 'v1/itjl');

define('ITJL_BLOG_DIR', get_stylesheet_directory() . '/');
define('ITJL_BLOG_URL', get_stylesheet_directory_uri(). '/');

define('ITJL_BLOG_TEMPLATES_PATH', ITJL_BLOG_DIR . 'templates/');
define('ITJL_BLOG_TEMPLATES_URI', ITJL_BLOG_URL . 'templates/');

define('ITJL_BLOG_THEME_TEMPLATES_PATH', ITJL_BLOG_TEMPLATES_PATH . 'theme-templates/');
define('ITJL_BLOG_THEME_TEMPLATES_URI', ITJL_BLOG_TEMPLATES_URI . 'theme-templates/');
 
 define('ITJL_BLOG_ASSETS_PATH', ITJL_BLOG_DIR . 'assets/');
 define('ITJL_BLOG_ASSETS_URI',  ITJL_BLOG_URL . 'assets/');
 
 define('ITJL_BLOG_CLASSES_PATH', ITJL_BLOG_DIR . 'classes/ITJL/');
 
 define('ITJL_BLOG_CLASSES_NAMESPACE', 'ITJL_BLOG');
 
 define('ITJL_BLOG_ASSETS_IMAGE_PATH', ITJL_BLOG_ASSETS_PATH . 'images/');
 define('ITJL_BLOG_ASSETS_IMAGE_URI', ITJL_BLOG_ASSETS_URI . 'images/');

 define('ITJL_BLOG_ASSETS_STYLE_PATH', ITJL_BLOG_ASSETS_PATH . 'css/');
 define('ITJL_BLOG_ASSETS_STYLE_URI', ITJL_BLOG_ASSETS_URI . 'css/');

 define('ITJL_BLOG_ASSETS_SCRIPT_PATH', ITJL_BLOG_ASSETS_PATH . 'js/');
 define('ITJL_BLOG_ASSETS_SCRIPT_URI', ITJL_BLOG_ASSETS_URI . 'js/');
 
 define('ITJL_BLOG_ASSETS_VIDEO_PATH', ITJL_BLOG_ASSETS_PATH . 'videos/');
 define('ITJL_BLOG_ASSETS_VIDEO_URI', ITJL_BLOG_ASSETS_URI . 'videos/');

 define('ITJL_BLOG_DEBUG_MODE', true);
 
define('ITJL_BLOG_TEXT_DOMAIN', 'itjl-blog');


if ( ! class_exists( 'ITJL_BLOG' ) ) {
	include_once ITJL_BLOG_CLASSES_PATH . ITJL_BLOG_CLASSES_NAMESPACE . '.php';
}