<?php
//require_once($_SERVER["DOCUMENT_ROOT"]."/../php/mylib.php");
//init();

$smarty = new MySmarty();

$smarty->assign("loginid", $_SESSION["loginname"]);
//$smarty->assign("id", $_SESSION["login_name"]);
//$_SESSION["login_name"]="kusuke";

$smarty->display("menu.tpl");
?>
