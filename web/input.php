<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../bookanchor/loader.php");
init();
checkLogin();
include("./menu.php");


$errorlist = array();

//$state_default = '1';
//$marker_default = '1';

// デフォルト値の設定
if(isset($_GET["mode"]) && $_GET["mode"] == "new"){
	debugMsg("input::get::mode=new");
	$_SESSION['title']		= "";
	$_SESSION['author']		= "";
	$_SESSION['publisher']	= "";
	$_SESSION['isbn']		= "";
	$_SESSION['image']		= "";
	$_SESSION['datepub']	= "";
	$_SESSION['daterec']	= "";
	$_SESSION['category']	= "";
	$_SESSION['tag']		= "";
	$_SESSION['state']		= "1";
	$_SESSION['marker']		= "1";
	$_SESSION['memo']		= "";
	$_SESSION['newtag']		= null;
	$_SESSION["filllist"]	= null;
	
}

//キャンセルボタンがクリックされたらセッション変数をクリア
if(isset($_POST["cancel"])) {
	$_SESSION['title']		= "";
	$_SESSION['author']		= "";
	$_SESSION['publisher']	= "";
	$_SESSION['isbn']		= "";
	$_SESSION['image']		= "";
	$_SESSION['datepub']	= "";
	$_SESSION['daterec']	= "";
	$_SESSION['category']	= "";
	$_SESSION['tag']		= "";
	$_SESSION['state']		= "";
	$_SESSION['marker']		= "";
	$_SESSION['memo']		= "";
	$_SESSION['newtag']		= null;
	$_SESSION["filllist"]	= null;
	
	$url = "http://".$_SERVER["HTTP_HOST"].dirname($_SERVER["SCRIPT_NAME"])."/booklist.php";
	header("Location: ".$url);
	exit;
}

// 登録確認ボタンクリック時の処理
if(isset($_POST['confirm'])) {
	$_SESSION['title']		= isset($_POST['title'])		? $_POST['title']		: "";
	$_SESSION['isbn']		= isset($_POST['isbn'])			? $_POST['isbn']		: "";
	$_SESSION['author']		= isset($_POST['author'])		? $_POST['author']		: 
							 (isset($_SESSION['author'])	? $_SESSION['author']	: "");
	$_SESSION['publisher']	= isset($_POST['publisher'])	? $_POST['publisher']	:
							 (isset($_SESSION['publisher'])	? $_SESSION['publisher']: "");
	$_SESSION['image']		= isset($_POST['image'])		? $_POST['image']		: 
							 (isset($_SESSION['image'])		? $_SESSION['image']	: "");
	$_SESSION['datepub']	= isset($_POST['datepub'])		? $_POST['datepub']		:
							 (isset($_SESSION['datepub'])	? $_SESSION['datepub']	: "");
	$_SESSION['daterec']	= isset($_POST['daterec'])		? $_POST['daterec']		: "";
	$_SESSION['category']	= isset($_POST['category'])		? $_POST['category']	: "";
	$_SESSION['tag']		= isset($_POST['tag'])			? $_POST['tag']			: "";
	$_SESSION['state']		= isset($_POST['state'])		? $_POST['state']		: "";
	$_SESSION['marker']		= isset($_POST['marker'])		? $_POST['marker']		: "";
	$_SESSION['memo']		= isset($_POST['memo'])			? $_POST['memo']		: "";
	// 追加タグを文字列から配列に整形
	$_SESSION['newtag']		= isset($_POST['newtag'])		? splitNewTag($_POST['newtag'])		: "";
	
	// エラーチェックとエラーメッセージ出力
	$errorlist = checkError($_SESSION);
	debugDump($errorlist, "errorlist");
	$_SESSION['newtag']		= join(',', $_SESSION['newtag']);
	// エラーがなければ確認画面へ遷移
	if(count($errorlist)==0) {
		$url = "http://".$_SERVER["HTTP_HOST"].dirname($_SERVER["SCRIPT_NAME"])."/confirm.php?confirm";
		header("Location: ".$url);
		exit;
	}
}

debugDump_();

$smarty = new MySmarty();

$smarty->assign('state_value', getStateList());
$smarty->assign('marker_value', getMarkerList());
$smarty->assign('category_value', getCategoryList());
$smarty->assign('tag_value', getTagList());
$smarty->assign('errorlist', $errorlist);
if(isset($_GET["modify"]))
	$smarty->assign('get_mode', "modify");

$smarty->display('input.tpl');
?>
