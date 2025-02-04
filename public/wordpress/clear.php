<?php 
if(!isset($_GET["force"])){
	die('may the force be with youuuuu');
}

#var_dump(opcache_get_status());

try{
    opcache_reset();
    echo "OPcache has been cleared successfully!";
}
catch(\Exception $e){
    echo "Oops.. OPcache could not be cleared!";
	print_r ($e);
}
