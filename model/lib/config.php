<?php
require_once('Config.php');

function getGconf($fname, array $keys){
	$cfg = array();
	$config = new Config();
	$gconf = $config->parseConfig($fname,'GenericConf');
	if(PEAR::isError($gconf))
		die("ConfigError: GeneralConf file '".$fname."' is not found.<br />");
	$confary = $gconf->toArray();
	foreach($keys as $key){
		if(!array_key_exists($key, $confary['root']))
			die("ConfigError: Config parameter '".$key."' is not found.<br />");
		$cfg[$key] = $confary['root'][$key];
	}
	//print_r($cfg);
	return $cfg;
}
?>
