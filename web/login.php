<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../bookanchor/loader.php");
init();
$smarty = new MySmarty();

if(isset($_POST["login"])) {
	if($_POST["loginid"] === "" || $_POST["password"] === "") {
		$error_message = "ログインIDとパスワードを入力してください";
	}else{
		if(authenticator($_POST["loginid"], $_POST["password"])){
			$_SESSION["loginname"] = $_POST["loginid"];
			
			
			$url = "http://".$_SERVER["HTTP_HOST"].dirname($_SERVER["SCRIPT_NAME"])."/index.php";
			header("Location: ".$url);
			exit;
		}else{
			$error_message = "ログインに失敗しました";
		}
	}
	$smarty->assign("loginid", $_POST["loginid"]);
	$smarty->assign("error_message", $error_message);
}

$smarty->display("login.tpl");
?>
