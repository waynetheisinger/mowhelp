<?php
/*
Plugin Name:    QuBit OpenTag
Plugin URI:     
Description:    QuBit OpenTag is an open Tag Management platform for developing, deploying and managing JavaScript or other HTML Tags without touching your website templates. OpenTag is FREE for small sites with less than one million page views per month, no credit card details required. This WordPress plugin automatically adds your configured container tag to every page. If you haven't registered, you should <a href="https://opentag.qubitproducts.com/QDashboard/register.html" target="_blank">SIGN UP FOR FREE</a>!
Version:        1.0.0
Author:         QuBit OpenTag
Author URI:     http://opentag.qubitproducts.com
*/

function opentag_activate_plugin() {
  add_option('opentag_disabled', 'false');
  add_option('opentag_loading_method', 'async');
  add_option('opentag_container_id', '');
}

function opentag_is_enabled() {
  return get_option('opentag_disabled') == 'false';
}

function opentag_get_script() {
  $base_src = 'd3c3cq33003psk.cloudfront.net';
  $defer = ' async defer';
  $container_id = get_option('opentag_container_id');
  $method = get_option('opentag_loading_method');

  if($container_id && opentag_is_enabled()) {
    if($method == 'sync') {
      $defer = '';
    }
    return '<script src="//'.$base_src.'/opentag-'.$container_id.'.js"'.$defer.'></script>';
  } else {
    return '';
  }
}

function opentag_activate_script() {
  echo opentag_get_script();
}

function opentag_base_dir() {
  return basename(dirname(__FILE__));
}

require_once dirname( __FILE__ ) . '/admin/settings.php';

register_activation_hook( __FILE__, 'opentag_activate_plugin');
add_action('wp_head', 'opentag_activate_script');

?>