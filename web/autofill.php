<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../bookanchor/loader.php");
init();
checkLogin();
include("./menu.php");

//$cd = array();


/*	print "<pre>";
	print "POST";
	var_dump($_POST);
	print "GET";
	var_dump($_GET);
	print "SESSION";
	var_dump($_SESSION);
	print "</pre>";
*/

if(isset($_POST["apply"])) {
	debugMsg("autofill::post::apply");
	$_SESSION["mode"] = "filled";
	
	$_SESSION['title']		= $_SESSION['tempbook']['title'];
	$_SESSION['isbn']		= $_SESSION['tempbook']['isbn'];
	$_SESSION['author']		= $_SESSION['tempbook']['author'];
	$_SESSION['publisher']	= $_SESSION['tempbook']['publisher'];
	$_SESSION['datepub']	= $_SESSION['tempbook']['datepub'];
	$_SESSION['image']		= $_SESSION['tempbook']['image'];
	
	$_SESSION["filllist"] = null;
	$_SESSION["tempbook"] = null;
	
	$url = "http://".$_SERVER["HTTP_HOST"].dirname($_SERVER["SCRIPT_NAME"])."/confirm.php?confirm";//id=".$_SESSION["bookid"];
	header("Location: ".$url);
	exit;
}


//データが選択されていたらSESSION変数にセット
//if($get_mode == "fill") {
if(isset($_GET["fill"])) {
	debugMsg("autofill::post::confirm");
	
	//アンケートデータをセッション変数にセット
	if(in_array('title',	$_POST['cb'])	&&	$_SESSION['filllist'][$_GET['fill']]['title']	 !== ''){
			$_SESSION['tempbook']['title']	  = $_SESSION['filllist'][$_GET['fill']]['title'];
	}else{	$_SESSION['tempbook']['title']	  = $_SESSION['title'];		}
	if(in_array('isbn',		$_POST['cb'])	&&	$_SESSION['filllist'][$_GET['fill']]['isbn']	 !== ''){
			$_SESSION['tempbook']['isbn']	  = $_SESSION['filllist'][$_GET['fill']]['isbn'];
	}else{	$_SESSION['tempbook']['isbn']	  = $_SESSION['isbn'];		}
	if(in_array('author',	$_POST['cb'])	&&	$_SESSION['filllist'][$_GET['fill']]['author']	 !== ''){
			$_SESSION['tempbook']['author']	  = $_SESSION['filllist'][$_GET['fill']]['author'];
	}else{	$_SESSION['tempbook']['author']	  = $_SESSION['author'];	}
	if(in_array('publisher',$_POST['cb'])	&&	$_SESSION['filllist'][$_GET['fill']]['publisher']!== ''){
			$_SESSION['tempbook']['publisher']= $_SESSION['filllist'][$_GET['fill']]['publisher'];
	}else{	$_SESSION['tempbook']['publisher']= $_SESSION['publisher'];	}
	if(in_array('datepub',	$_POST['cb'])	&&	$_SESSION['filllist'][$_GET['fill']]['datepub']	 !== ''){
			$_SESSION['tempbook']['datepub']  = $_SESSION['filllist'][$_GET['fill']]['datepub'];
	}else{	$_SESSION['tempbook']['datepub']  = $_SESSION['datepub'];	}
	if(in_array('image',	$_POST['cb'])	&&	$_SESSION['filllist'][$_GET['fill']]['image']	 !== ''){
			$_SESSION['tempbook']['image']	  = $_SESSION['filllist'][$_GET['fill']]['image'];
	}else{	$_SESSION['tempbook']['image']	  = $_SESSION['image'];		}
	
/*	if(in_array('title',	$_POST['cb'])	&&	$_SESSION['filllist'][$_GET['fill']]['title']	 !== '')
		$_SESSION['title']		= $_SESSION['filllist'][$_GET['fill']]['title'];
	if(in_array('isbn',		$_POST['cb'])	&&	$_SESSION['filllist'][$_GET['fill']]['isbn']	 !== '')
		$_SESSION['isbn']		= $_SESSION['filllist'][$_GET['fill']]['isbn'];
	if(in_array('author',	$_POST['cb'])	&&	$_SESSION['filllist'][$_GET['fill']]['author']	 !== '')
		$_SESSION['author']		= $_SESSION['filllist'][$_GET['fill']]['author'];
	if(in_array('publisher',$_POST['cb'])	&&	$_SESSION['filllist'][$_GET['fill']]['publisher']!== '')
		$_SESSION['publisher']	= $_SESSION['filllist'][$_GET['fill']]['publisher'];
	if(in_array('datepub',	$_POST['cb'])	&&	$_SESSION['filllist'][$_GET['fill']]['datepub']	 !== '')
		$_SESSION['datepub']	= $_SESSION['filllist'][$_GET['fill']]['datepub'];
	if(in_array('image',	$_POST['cb'])	&&	$_SESSION['filllist'][$_GET['fill']]['image']	 !== '')
		$_SESSION['image']		= $_SESSION['filllist'][$_GET['fill']]['image'];*/
	
	
//	$_SESSION["filllist"] = null;
	debugDump_();
	
/*	$_SESSION['author']		= isset($filllist['author'])	? 				$filllist['author']		: "";
	$_SESSION['publisher']	= isset($filllist['publisher'])	? 				$filllist['publisher']	: "";
	$_SESSION['isbn']		= isset($filllist['isbn'])		? 				$filllist['isbn']		: "";
	$_SESSION['image']		= isset($filllist['image'])		? 				$filllist['image']		: "";
	$_SESSION['datepub']	= isset($filllist['datepub'])	? 				$filllist['datepub']	: "";
	$_SESSION['daterec']	= isset($bookdata['daterec'])	? 				$filllist['daterec']	: "";
	
	$url = "http://".$_SERVER["HTTP_HOST"].dirname($_SERVER["SCRIPT_NAME"])."/confirm.php?confirm";
	header("Location: ".$url);
	exit;*/
}

//オートフィル画面遷移時のアクション
else{//}(isset($_POST["autofill"])) {
	debugMsg("method::post::autofill");
	
	$_SESSION["tempbook"] = array(
		"title" 	=> $_SESSION["title"],
		"isbn"		=> $_SESSION["isbn"],
		"author"	=> $_SESSION["author"],
		"publisher"	=> $_SESSION["publisher"],
		"image"		=> $_SESSION["image"],
		"datepub"	=> $_SESSION["datepub"],
	//	"daterec"	=> "",
	//	"category"	=> "",
	//	"tag"		=> "",
	//	"state"		=> "",
	//	"marker"	=> "",
	//	"memo"		=> "",
	);
	
	if(isset($_SESSION["isbn"]) && $_SESSION["isbn"] !== ""){
		$filllist = getGooglebooks($_SESSION["isbn"], 5);
	}elseif(isset($_SESSION["title"]) && $_SESSION["title"] !== ""){
		/*$filllist = get_googlebooks($_SESSION["title"], 5);
	}else{*/
		$filllist = getGooglebooks($_SESSION["title"], 5);
	//	$filllist = get_googlebooks(mb_ereg_replace("[ \　]", "+", $_SESSION["title"]), 5);
	}
	$_SESSION["filllist"] = $filllist;
	debugDump($filllist, "filllist");
	debugDump($_SESSION, "_SESSION");
	//書籍データ追加完了画面を表示して終了
/*	$smarty = new MySmarty();
	$smarty->assign('get_mode', "autofill");
	$smarty->display("bookanchor/autofill.tpl");
	exit;*/
//}
/*
//修正ボタンのアクション
if(isset($_POST["modify"])) {
	print "method::post::modify";
	$url = "http://".$_SERVER["HTTP_HOST"].dirname($_SERVER["SCRIPT_NAME"])."/input.php?modify";
	header("Location: ".$url);
	exit;*/
}


$get_mode	= "";
$get_value	= "";

if(isset($_GET["confirm"]))	{
	$get_mode = "confirm";
}elseif(isset($_GET["id"]))	{
	$get_mode = "id";
	$get_value = $_GET["id"];
}elseif(isset($_POST["fillform"])){
	$get_mode = "fill";
	$get_value = $_GET["fillform"];
}elseif(isset($_GET["fill"])){
	$get_mode = "fill";
	$get_value = $_GET["fill"];
}elseif(isset($_POST["autofill"])){
	$get_mode = "autofill";
}
//print "get_mode: ".$smarty.get_mode;
//print "get_value: ".$smarty.get_value;
debugMsg("get_mode	: ".$get_mode);
debugMsg("get_value: ".$get_value);

$smarty = new MySmarty();

$smarty->assign('state_value', getStateList());
$smarty->assign('marker_value', getMarkerList());
$smarty->assign('category_value', getCategoryList());
$smarty->assign('tag_value', getTagList());
//$smarty->assign('filllist', $filllist);
$smarty->assign("get_mode", $get_mode);
$smarty->assign("get_value", $get_value);
//$smarty->assign("cb", $cb);
$smarty->display("autofill.tpl");
?>
