<?php
require_once(LIB_DIR.'debug.php');

/**
 * URLが存在するかチェックする
 * */
function existUrl($url){
	if(strlen($url) <= 0)
		return false;
	else if(fopen($url, 'r'))
		return true;
	else
		return false;
}

/**
 * ファイルパスが存在するかチェックする
 * */
function existPath($path){
	if(strlen($path) <= 0)
		return false;
	else if(substr($path, 0, 7)=='http://')
		return existUrl($path);
	else
		return file_exists($path);
}


?>
