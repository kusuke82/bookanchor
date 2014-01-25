<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../bookanchor/loader.php");
init();

$_SESSION = array();

$url = "http://".$_SERVER["HTTP_HOST"].dirname($_SERVER["SCRIPT_NAME"])."/login.php";
header("Location: ".$url);
exit;
?>
