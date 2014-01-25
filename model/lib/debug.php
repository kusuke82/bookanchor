<?php
require_once(LIB_DIR.'config.php');

if(!defined('DEBUG')) {
	$config = getGconf(CONFIG_DIR.'.debug', array('debug'));
	if($config)	define('DEBUG', $config['debug']);
	else		define('DEBUG', 0);
}

function debugDump_(){
	if(DEBUG){
		print "<pre>";
		print "<b>POST</b><br/>";
		var_dump($_POST);
		print "<b>GET</b><br/>";
		var_dump($_GET);
		print "<b>SESSION</b><br/>";
		var_dump($_SESSION);
		print "</pre>";
	}
}

function debugDump($var, $name){
	if(DEBUG){
		print "<pre>";
		print "<b>".$name."</b><br/>";
		var_dump($var);
		print "<br/>";
		print "</pre>";
	}
}

function debugMsg($str){
	if(DEBUG){
		print "<pre>";
		var_dump($str);
		print "<br/>";
		print "</pre>";
	}
}
?>
