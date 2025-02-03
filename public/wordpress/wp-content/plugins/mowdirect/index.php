<?php
/**
 * Plugin Name: MowDirect
 * Plugin URI: http://mowdirect.com
 * Description: Support plugin for MowDirect blog
 * Author: Marc George
 * Version: 0.1
 * Author URI: http://marcgeorge.com
 */

namespace MowDirect {

if(!defined("ABSPATH")){
  die("Not found");
}

if(!defined("MOWDIRECT_PLUGIN_URL")){
  define("MOWDIRECT_PLUGIN_URL", plugins_url('', __FILE__ ));
}

if(!defined("MOWDIRECT_PLUGIN_DIR")){
  define("MOWDIRECT_PLUGIN_DIR", plugin_dir_path( __FILE__ ));
}

?>
<?php
  
class MowDirectAutoloader {

  public static function register(){
    spl_autoload_register("self::__autoload");
  }

  public static function __autoload($class){
    $prefix = "MowDirect";
    $base_dir = __DIR__ . DIRECTORY_SEPARATOR . "lib";
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
      return;
    }
    $className = substr($class, $len);
    $file = $base_dir . strtolower(str_replace("\\", "/", $className)) . ".php";
    //var_dump($file);
    if(file_exists($file)){
      require $file;
    }
  }

}

MowDirectAutoloader::register();

new Core\Plugin;

}

// dirty convenience...

namespace {
  function mowtalk(){
    return \MowDirect\Support\API::getInstance();
  }
}
