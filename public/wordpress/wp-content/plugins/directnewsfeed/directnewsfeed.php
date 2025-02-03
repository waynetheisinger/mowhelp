<?php
/*
Plugin Name: Direct News Feed
Plugin URI: http://www.directnews.co.uk
Description: Takes the DirectNews feed and creates categorized posts.
Version: 0.1
Author: Wayne Theisinger
Author URI: http://www.mowdirect.co.uk
 * =======================================================================
 */
 
# directnews paths. With trailing slash.
define('DNDIR', dirname(__FILE__) . '/');                
    
# Set up the options screen                            
require_once( DNDIR . 'options.php' );  

?>
