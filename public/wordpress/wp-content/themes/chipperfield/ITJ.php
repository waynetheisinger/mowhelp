<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


define( 'ITJ_VERSION', '1.0.0' );
define('ITJ_VERSION_API_VERSION', 'v1');

define('ITJ_DIR', get_stylesheet_directory() . '/');
define('ITJ_URL', get_stylesheet_directory_uri(). '/');

define('ITJ_TEMPLATES_PATH', ITJ_DIR . 'templates/');
define('ITJ_TEMPLATES_URI', ITJ_URL . 'templates/');

define('ITJ_THEME_TEMPLATES_PATH', ITJ_TEMPLATES_PATH . 'theme-templates/');
define('ITJ_THEME_TEMPLATES_URI', ITJ_TEMPLATES_URI . 'theme-templates/');
 
 define('ITJ_ASSETS_PATH', ITJ_DIR . 'assets/');
 define('ITJ_ASSETS_URI',  ITJ_URL . 'assets/');
 
 define('ITJ_CLASSES_PATH', ITJ_DIR . 'classes/ITJ/');
 
 define('ITJ_CLASSES_NAMESPACE', 'ITJ');
 
 define('ITJ_ASSETS_IMAGE_PATH', ITJ_ASSETS_PATH . 'images/');
 define('ITJ_ASSETS_IMAGE_URI', ITJ_ASSETS_URI . 'images/');

 define('ITJ_ASSETS_STYLE_PATH', ITJ_ASSETS_PATH . 'css/');
 define('ITJ_ASSETS_STYLE_URI', ITJ_ASSETS_URI . 'css/');

 define('ITJ_ASSETS_SCRIPT_PATH', ITJ_ASSETS_PATH . 'js/');
 define('ITJ_ASSETS_SCRIPT_URI', ITJ_ASSETS_URI . 'js/');
 
 define('ITJ_ASSETS_VIDEO_PATH', ITJ_ASSETS_PATH . 'videos/');
 define('ITJ_ASSETS_VIDEO_URI', ITJ_ASSETS_URI . 'videos/');

 define('ITJ_DEBUG_MODE', true);
 
define('ITJ_TEXT_DOMAIN', 'reka-child');

if ( ! class_exists( 'IT_JONCTION' ) ) {
	include_once ITJ_CLASSES_PATH . ITJ_CLASSES_NAMESPACE . '.php';
}