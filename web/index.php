<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../bookanchor/loader.php");
init();
checkLogin();
include("./menu.php");

$smarty = new MySmarty();
$smarty->display("index.tpl");

?>
