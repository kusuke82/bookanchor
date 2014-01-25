<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../bookanchor/loader.php");
init();
checkLogin();
include("./menu.php");

debugDump_();

//オートフィル後の処理
//if(isset($_GET["filled"])
	
//データが選択されていたらSESSION変数にセット
if(isset($_GET["id"])) {
	debugMsg("confirm::get::id");
	$_SESSION["bookid"] = $_GET["id"];
	//アンケートIDのデータをDBからロード
	$db = connectDB();
	$bookid = mb_ereg_replace("[^0-9]", "", $_GET["id"]);
//	$bookid = $db->quoteSmart($_GET["id"]);
	$sql = "select * from booklist where id = {$bookid}";
	$bookdata = $db->getRow($sql, DB_FETCHMODE_ASSOC);
	
	//DBに指定したIDのデータが存在しなければアンケート結果一覧画面へジャンプ
	if(!$bookdata){
		$url = "http://".$_SERVER["HTTP_HOST"].dirname($_SERVER["SCRIPT_NAME"])."/booklist.php";
		header("Location: ".$url);
		exit;
	}
	//アンケートデータをセッション変数にセット
	$_SESSION["bookid"]		= $bookid;//$bookdata["bookid"];
	$_SESSION['title']		= isset($bookdata['title'])		? 				$bookdata['title']		: "";
	$_SESSION['author']		= isset($bookdata['author'])	? 				$bookdata['author']		: "";
	$_SESSION['publisher']	= isset($bookdata['publisher'])	? 				$bookdata['publisher']	: "";
	$_SESSION['isbn']		= isset($bookdata['isbn'])		? 				$bookdata['isbn']		: "";
	$_SESSION['image']		= isset($bookdata['image'])		? 				$bookdata['image']		: "";
	$_SESSION['datepub']	= isset($bookdata['datepub'])	? 				$bookdata['datepub']	: "";
	$_SESSION['daterec']	= isset($bookdata['daterec'])	? 				$bookdata['daterec']	: "";
	$_SESSION['category']	= isset($bookdata['category'])	? 				$bookdata['category']	: "";
	$_SESSION['tag']		= isset($bookdata['tag'])		? explode(",",	$bookdata['tag'])		: "";
	$_SESSION['state']		= isset($bookdata['state'])		? 				$bookdata['state']		: "";
	$_SESSION['marker']		= isset($bookdata['marker'])	? 				$bookdata['marker']		: "";
	$_SESSION['memo']		= isset($bookdata['memo'])		? 				$bookdata['memo']		: "";
//	$amazon = get_googlebooks($_SESSION["isbn"]);
//	print "amazon:: ".$amazon;
}

//登録ボタンのアクション
if(isset($_POST["register"])) {
	debugMsg("confirm::post::register");
	//DBに接続
	$db = connectDB();
	//フォームの内容をDB向けに整形して変数にセット
	$vtitle		 = $db->quoteSmart($_SESSION["title"]);
	$vauthor	 = $db->quoteSmart($_SESSION["author"]);
	$vpublisher	 = $db->quoteSmart($_SESSION["publisher"]);
	$visbn		 = $db->quoteSmart($_SESSION["isbn"]);
	$vimage		 = $db->quoteSmart($_SESSION["image"]);
	$vdatepub	 = $db->quoteSmart($_SESSION["datepub"]);
	$vdaterec	 = $db->quoteSmart($_SESSION["daterec"]);
	$vcategory	 = $db->quoteSmart($_SESSION["category"]);
	$vstate		 = $db->quoteSmart($_SESSION["state"]);
	$vmarker	 = $db->quoteSmart($_SESSION["marker"]);
	$vmemo		 = $db->quoteSmart($_SESSION["memo"]);
	
	//追加タグが存在しているならDBに追加
	if(isset($_SESSION["newtag"]) && $_SESSION["newtag"] !== ""){
		$tags = split(",", $_SESSION["newtag"]);
		$_SESSION["newtag"] = null;
		foreach($tags as $tag){
			$sql = "insert into taglist values (null, '{$tag}')";
			$res = $db->query($sql);
			if(DB::isError($res)){
				print "タグ追加時にクエリ実行エラーが発生しました<br />";
				exit;
			}
			$sql = "select id from taglist where tagword = '{$tag}'";
			$res = $db->query($sql);
			if(DB::isError($res)){
				print "追加タグ検索時にクエリ実行エラーが発生しました<br />";
				exit;
			}
			$tagid = $db->getRow($sql, DB_FETCHMODE_ASSOC);
			debugMsg("tagid:".var_dump($tagid));
			$_SESSION["newtag"][] = $tagid["id"];
		}
		$vtag = $db->quoteSmart(join(",", $_SESSION["tag"]).
							",".join(",", $_SESSION["newtag"]));
	}else if(is_array($_SESSION["tag"])){
		$vtag = $db->quoteSmart(join(",", $_SESSION["tag"]));
	}else{
		$vtag = "null";
	}
	
	//bookid値が存在すれば修正クエリ送信前にIDのデータが存在しているかチェック
	if(isset($_SESSION["bookid"]) && trim($_SESSION["bookid"]) !== "") {
	//	$vid = $db->quoteSmart($_SESSION["bookid"]);
		$vid = mb_ereg_replace('[^0-9]', '', $_SESSION["bookid"]);
		debugMsg("vid:".$vid);
		$sql = "select * from booklist where id = {$vid}";
		$bookdata = $db->getRow($sql, DB_FETCHMODE_ASSOC);
		debugDump($bookdata, "bookdata");
		if(!DB::isError($bookdata)){
			//bookデータが存在しているなら修正するクエリを送信
			$sql = "update booklist set title={$vtitle},
										author={$vauthor},
										publisher={$vpublisher}, 
										isbn={$visbn}, 
										image={$vimage}, 
										datepub={$vdatepub}, 
										daterec={$vdaterec}, 
										category={$vcategory}, 
										tag={$vtag}, 
										state={$vstate}, 
										marker={$vmarker}, 
										memo={$vmemo} where id={$vid}";
			$res = $db->query($sql);
			debugMsg("res:".$res);
			//DBのアンケートデータ修正が失敗したらエラーメッセージ
			if(DB::isError($res)) {
				print "書籍データ修正時にクエリ実行エラーが発生しました<br />";
				exit;
			}
			//セッションの書籍データをクリア
			$_SESSION["bookid"]		= "";
			$_SESSION["title"]		= "";
			$_SESSION["author"]		= "";
			$_SESSION["publisher"]	= "";
			$_SESSION["isbn"]		= "";
			$_SESSION["image"]		= "";
			$_SESSION["datepub"]	= "";
			$_SESSION["daterec"]	= "";
			$_SESSION["category"]	= "";
			$_SESSION["tag"]		= "";
			$_SESSION["state"]		= "";
			$_SESSION["marker"]		= "";
			$_SESSION["memo"]		= "";
			$_SESSION["newtag"]		= null;
			$_SESSION["filllist"]	= null;
			
			debugDump_();
			
			//書籍データ修正完了画面を表示して終了
			$smarty = new MySmarty();
			$smarty->assign('get_mode', "update");
			$smarty->display("complete.tpl");
			exit;
		}
		print "書籍データ修正時にID指定エラーが発生しました<br />";
		exit;
	}else{
		//bookデータが存在していないなら追加するクエリを送信
		$sql = "insert into booklist (id, title, author, publisher, isbn, image, datepub, daterec, category, tag, state, marker, memo) 
							values (null, {$vtitle}, {$vauthor}, {$vpublisher}, {$visbn}, {$vimage}, {$vdatepub}, {$vdaterec}, {$vcategory}, {$vtag}, {$vstate}, {$vmarker}, {$vmemo})";
		/*$sql = "insert into booklist values ({$vtitle}, {$vauthor}, {$vpublisher}, {$visbn}, {$vimage}, {$vdatepub}, {$vdaterec}, {$vcategory}, {$vtag}, {$vstate}, {$vmarker}, {$vmemo})";*/
		$res = $db->query($sql);
		//DBのアンケートデータ修正が失敗したらエラーメッセージ
		if(PEAR::isError($res)) {
			print "書籍データ追加時にクエリ実行エラーが発生しました<br />";
			print $sql."<br />";
			exit;
		}
		//セッションの書籍データをクリア
		$_SESSION["title"]		= "";
		$_SESSION["author"]		= "";
		$_SESSION["publisher"]	= "";
		$_SESSION["isbn"]		= "";
		$_SESSION["image"]		= "";
		$_SESSION["datepub"]	= "";
		$_SESSION["daterec"]	= "";
		$_SESSION["category"]	= "";
		$_SESSION["tag"]		= "";
		$_SESSION["state"]		= "";
		$_SESSION["marker"]		= "";
		$_SESSION["memo"]		= "";
		$_SESSION["newtag"]		= null;
		$_SESSION["filllist"]	= null;
		
		debugDump_();
		
		//書籍データ追加完了画面を表示して終了
		$smarty = new MySmarty();
		$smarty->assign('get_mode', "insert");
		$smarty->display("complete.tpl");
		exit;
	}
}
/*
//修正ボタンのアクション
if(isset($_POST["modify"])) {
	print "method::post::modify";
	$url = "http://".$_SERVER["HTTP_HOST"].dirname($_SERVER["SCRIPT_NAME"])."/input.php?modify";
	header("Location: ".$url);
	exit;
}
*/

$smarty = new MySmarty();

$smarty->assign('state_value', getStateList());
$smarty->assign('marker_value', getMarkerList());
$smarty->assign('category_value', getCategoryList());
$smarty->assign('tag_value', getTagList());
//$smarty->assign('error_list', $error_list);
if(isset($_GET["id"]))	{
	$smarty->assign('get_mode', "id");
	$smarty->assign('get_value', $_GET["id"]);
}elseif(isset($_GET["confirm"]))	
	$smarty->assign('get_mode', "confirm");
elseif(isset($_POST["autofill"]))
	$smarty->asssign("get_mode", "autofill");
$smarty->display("confirm.tpl");
?>
