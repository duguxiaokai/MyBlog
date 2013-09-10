<?php
// echo 'ckj';exit;
	define('APP_NAME','app');
	define('APP_PATH','./'.APP_NAME);
	define('APP_DEBUG','true');
	
	define('TP_PATH','ThinkPHP');
    define('ROOT_PATH',dirname(__FILE__).DIRECTORY_SEPARATOR);
	require TP_PATH.'/ThinkPHP.php';
	//require '/crm/Lib/ORG/System.class.php';
	App::run(); 
?>
