<?php
//libsフォルダにある共通関数を読み込む
require_once($_SERVER["DOCUMENT_ROOT"]."/../bookanchor/loader.php");

//初期化関数を呼び出す
init();
//ログイン状態をチェックする関数を呼び出す
checkLogin();
//メニューの呼び出し
include("./menu.php");
	
	debugDump_();
	
//必要ならセッションの検索条件をクリア
if(isset($_GET["mode"]) && $_GET["mode"] == "new"){
	debugMsg("booklist::get::mode=new");
	$_SESSION["keyword"] = "";
	$_SESSION["keytag"]	 = "";
	$_SESSION["keyctg"]  = "";
	$_SESSION["keystt"]  = "";
	$_SESSION["keymark"] = "";
	$_SESSION["where"]	 = "";
	
	$_SESSION["bookid"]	 = "";
	
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
	
	$_SESSION["tempbook"] = null;
	$_SESSION["filllist"] = null;
}

//データベースに接続する関数を呼び出す
$db = connectDB();

//POSTに削除フラグが存在するならDBのdelflagを立てる
if(isset($_POST["selectid"]) && is_array($_POST["selectid"])){
	//POSTされたチケットとセッションに保存されたチケットが一致したら削除処理を行う
	if(isset($_POST["ticket"], $_SESSION["ticket"]) && $_POST["ticket"] === $_SESSION["ticket"]){
		//del_id配列の要素を整形してwhere_idに格納
		foreach($_POST["selectid"] as $deleteid){
			if(!is_numeric($deleteid)) continue;
			$selectidlist[] = $db->quoteSmart($deleteid);
		}
		//$whereid = join(",", $selectidlist);
		foreach($selectidlist as $whereid){
			//SQL文を作成して送信
			$sql = "update booklist set delflag = '1' where id = {$whereid}";
			$res = $db->query($sql);
			debugMsg("SQL:".$sql);
			//エラーチェック
			if(DB::isError($res)){
				print "エラーが発生しました<br />操作をやりなおしてください";
				exit;
			}
		}
	}
}

//条件文のセット
$where = "";
//検索ボタンクリック時の処理
if(isset($_POST["search"])){
	//検索条件のセット
	$keyword	= $_POST["keyword"];
	$keytag		= "";
	$keycategory= $_POST["keyctg"];
	$keystate	= $_POST["keystt"];
	$keymarker	= $_POST["keymark"];
	
	//条件文を生成するための一時変数
	$searchwhere = "";
	
	//キーワードが入力されていたら検索文を作成
	if($keyword != ""){
		//$keyword = str_replace('%','\%', $keyword);
		//$keyword = str_replace('_','\_', $keyword);
	/*	$keyword = preg_replace('/%/', '\%', $keyword);
		$keyword = preg_replace('/_/', '\_', $keyword);
		$keyword = preg_replace('/( |　)/', ',', $keyword);
		$keyword = preg_replace('/,+/', ',', $keyword);
		$keyword = mb_split(',', $keyword);
	*/	//$keyword = preg_split('/[　 ,]/', $keyword,0, PREG_SPLIT_NO_EMPTY);
	//	$keyword = mb_split('　| |,', $keyword,0);
	/*	$taglist = getTagList();
		foreach($taglist as $tagk => $tagv){
			
		}*/
		$keyword = splitSearch($keyword);
		$keytaglist = search2tag($keyword);
		debugDump($keyword, "keyword");
		debugDump($keytaglist, "keytaglist");
		$keytitle = array();
		$keymemo = array();
		$keytag = array();
		for($i=0; $i<count($keyword); $i++){
			//$keyword[$i] = $db->quoteSmart("%".$keyword[$i]."%");
			$keytitle[] = "title like(".$db->quoteSmart("%".$keyword[$i]."%").")";
			$keymemo[]	= "memo like(".$db->quoteSmart("%".$keyword[$i]."%").")";
		}
		for($i=0; $i<count($keytaglist); $i++){
			$keytag[] = "tag like(".$db->quoteSmart(	 $keytaglist[$i].",%").")".
					" or tag like(".$db->quoteSmart("%,".$keytaglist[$i].",%").")".
					" or tag like(".$db->quoteSmart("%,".$keytaglist[$i]	 ).")";
		}
		//$keyword = join(' or ', $keyword);
		//$searchwhere .= "title like (".$keyword.") or memo like (".$keyword.")";
		$searchwhere .= "(".join(' or ', $keytitle).")".
					" or (".join(' or ', $keymemo).")".
					" or (".join(' or ', $keytag).")";
		
	}
	
	//カテゴリが選択されていたら検索文を生成
	if($keycategory != ""){
		$keycategory = $db->quoteSmart($keycategory);
		if($searchwhere != "")	$searchwhere .= " and";
		$searchwhere .= " category = {$keycategory}";
	}
	
	//状態が選択されていたら検索文を生成
	if($keystate != ""){
		$keystate = $db->quoteSmart($keystate);
		if($searchwhere != "")	$searchwhere .= " and";
		$searchwhere .= " state = {$keystate}";
	}
	
	//栞が選択されていたら検索文を生成
	if($keymarker != ""){
		$keymarker = $db->quoteSmart($keymarker);
		if($searchwhere != "")	$searchwhere .= " and";
		$searchwhere .= " marker = {$keymarker}";
	}
	
	//入力した内容をセッションに保存
	$_SESSION["keyword"] = isset($_POST["keyword"]) ? $_POST["keyword"] : "";
	$_SESSION["keyctg"]  = isset($_POST["keyctg"])	? $_POST["keyctg"]	: "";
	$_SESSION["keystt"]  = isset($_POST["keystt"])	? $_POST["keystt"]	: "";
	$_SESSION["keymark"] = isset($_POST["keymark"])	? $_POST["keymark"]	: "";
	
	//条件文がセットされているか確認
	if($searchwhere != ""){
	//	$_SESSION["where"] = " where (".$searchwhere.")";//." sel_flag != '1'";
		$_SESSION["where"] = " where (".$searchwhere.") and delflag != '1'";
	}else{
		$_SESSION["where"] = " where delflag != '1'";
	}
}

//条件文があったらセットする
if(isset($_SESSION["where"]) && $_SESSION["where"] != ""){
//	$where = $_SESSION["where"];
	$sql = "select * from booklist{$_SESSION["where"]} order by title asc";
}else{
	//$where = " where delflag != '1'";
	$sql = "select * from booklist where delflag != '1' order by title asc";
}

//条件を付加したアンケートデータを取得
//$sql = "select * from booklist{$where} order by daterec desc";
debugDump($sql, "sql");
//$anq_list = $db->getAll($sql,DB_FETCHMODE_ASSOC);
//print "query:".$sql."<br />";
$data = pagerSqlList($sql, $db);

//タグのデータを','区切り文字列から配列に変換
/*foreach((array)$anq_list as $key => $value){
	$anq_list[$key]["tag"] = explode(",", $value["tag"]);
}*/
debugDump($data,"data");
foreach((array)$data["data"] as $key => $value){
	$data["data"][$key]["tag"] = explode(",", $value["tag"]);
}

//smartyを生成
$smarty = new MySmarty();
$smarty->assign("booklist", $data["data"]);
$smarty->assign("links", $data["links"]);
$smarty->assign("category_value",	getCategoryList());
$smarty->assign("state_value",	getStateList());
$smarty->assign("marker_value",	getMarkerList());
$smarty->assign("tag_value",	getTagList());
//CSRF対策: チケットを生成してセッションに登録
$_SESSION["ticket"] = md5(uniqid().mt_rand());
$smarty->assign("ticket", $_SESSION["ticket"]);

$smarty->display("booklist.tpl");

?>
