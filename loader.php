<?php
if(!defined('ROOT_DIR')) {
	define('ROOT_DIR', $_SERVER['DOCUMENT_ROOT']."/../bookanchor/");
}
if(!defined('SMARTY_DIR')) {
	define('SMARTY_DIR', $_SERVER['DOCUMENT_ROOT']."/../bookanchor/libs/");
}
if(!defined('CONFIG_DIR')) {
	define('CONFIG_DIR', $_SERVER['DOCUMENT_ROOT']."/../bookanchor/config/");
}
if(!defined('LIB_DIR')) {
	define('LIB_DIR', $_SERVER['DOCUMENT_ROOT']."/../bookanchor/model/lib/");
}

require_once(ROOT_DIR."libs/Smarty.class.php");
require_once(LIB_DIR."require_all.php");
require_all(ROOT_DIR."model");
require_all(ROOT_DIR."controller");

?>
