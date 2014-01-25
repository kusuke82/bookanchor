<?php
require_once(LIB_DIR.'debug.php');
require_once(LIB_DIR.'dblist.php');
require_once(LIB_DIR.'form.php');

function init() {
	//セッションを開始する
	session_start();
	session_regenerate_id(true);
	
	mb_regex_encoding('UTF-8');

}

//カテゴリのリスト（配列）を返す関数
function getCategoryList() {
	$value = array(
		"1" => "文芸",
		"2" => "新書・文庫",
		"3" => "コミック",
		"4" => "ライトノベル",
		"5" => "雑誌",
		"6" => "IT",
		"7" => "学問",
		"8" => "趣味"
	);
	return $value;
}

//タグのリスト（配列）を返す関数
function getTagList() {
/*	natsort($value);*/
	$value = getDBTagList();
//	print "<pre>";var_dump($value);print "</pre>";
	return $value;
}
function getDBTagList(){
	$db = connectDB();
	$sql = "select * from taglist order by tagword asc";
	$list = $db->getAll($sql, DB_FETCHMODE_ASSOC);
	$taglist = array();
	foreach($list as $tag){
		$taglist += array($tag["id"]=> $tag["tagword"]);
	}
	return $taglist;
}
function splitSearch($keyword) {
	$keyword = preg_replace('/%/', '\%', $keyword);
	$keyword = preg_replace('/_/', '\_', $keyword);
	$keyword = preg_replace('/\+/', '\+', $keyword);
	$keyword = preg_replace('/\*/', '\*', $keyword);
	$keyword = preg_replace('/\#/', '\*', $keyword);
	$keyword = preg_replace('/\//', '\/', $keyword);
	$words = mb_split(",", 
			preg_replace("/( |　|,)/", ",",
			trim($keyword, " \t\n\r\0\x0B\'\"\\^,")
			));
	//空タグを削除
	$tmpwords = array();
	foreach($words as $word)
		if($word != "")	$tmpwords[] = trim($word, " \t\n\r\0\x0B\'\"\\^,");
	$words = $tmpwords;
	
	return $words;
}
function search2tag($keywords) {
	if(!is_array($keywords))	$keywords = splitSearch($keywords);
	$taglist = getTagList();
	$tags = array();
	foreach($keywords as $key => $word){
	foreach($taglist as $id => $tag){
		if(mb_eregi($word, $tag) !== false)	$tags[] = $id;
	}}
	
	return $tags;
}
function splitNewTag($newtag) {
	$tags = mb_split(",", 
				mb_ereg_replace("　", " ",
					trim($newtag, " \t\n\r\0\x0B\'\"\\^,")));
	for($i=0; $i < count($tags); $i++){
		$tags[$i] = mb_ereg_replace(" ", "_", trim($tags[$i]));
	}
	//空タグを削除
	$temptag = array();
	foreach($tags as $tag)
		if($tag != "")	$temptag[] = $tag;
	$tags = $temptag;
	
	return $tags;
}
function checkNewTag($newtagary) {
/*	$db = connectDB();
	$sql = "select tagword from taglist order by tagword asc";
	$taglist = $db->getAll($sql, DB_FETCHMODE_ASSOC);
	print "<pre>"; var_dump($taglist); print "</pre>";*/
	$taglist = getTagList();
	$errorlist = array();
	$newtags = array();
	foreach($newtagary as $newtag){
		$newtags[] = mb_convert_case(
						mb_convert_kana($newtag, 'asKCV', 'UTF-8'),
						MB_CASE_LOWER, 'UTF-8');
	}
//	for($i=0; $i < count($taglist); $i++)
	foreach($taglist as $i => $tag)
		$taglist[$i] = mb_convert_case(
							mb_convert_kana($tag, 'asKCV', 'UTF-8'),
							MB_CASE_LOWER, 'UTF-8');
	for($i=0; $i < count($newtags); $i++){
	foreach($taglist as $tag){
		//print "tag:".$tag." newtag:".$newtags[$i]."<br/>";
		if($tag === $newtags[$i]){
	//	if(mb_stristr($tag, $newtag[$i]) !== false || mb_stristr($newtag[$i], $tag) !== false){
			$errorlist[] = "タグ[{$newtagary[$i]}]は既に存在しています";
			//print "tag:".$tag." newtag:".$newtags[$i]." result:".mb_stristr($tag, $newtag[$i])."<br/>";
		}
	}}
	debugDump($errorlist, "checkNewTag:errorlist:");
	return $errorlist;
}

function insertDBNewTag(){
	$db = connectDB();
	$sql = "select * from taglist order by tagword asc";
	$list = $db->getAll($sql, DB_FETCHMODE_ASSOC);
	$taglist = array();
	foreach($list as $tag){
		$taglist += array($tag["id"]=> $tag["tagword"]);
	}
	return $taglist;
}

//状態のリスト（配列）を返す関数
function getStateList() {
	$value = array(
		"1" => "蔵庫",
		"2" => "ｳｨｯｼｭ",
		"3" => "借用中",
		"4" => "貸出中",
		"5" => "返却済",
		"6" => "処分済"
	);
	return $value;
}

//読書状態のリスト（配列）を返す関数
function getMarkerList() {
	$value = array(
		"1" => "積み",
		"2" => "読みかけ",
		"3" => "読了"
	);
	return $value;
}

//エラーチェック
function checkError($check_data) {
	$error_list = array();
	
	//名前のエラーチェック
	//  title変数が存在＆空白以外の文字なし
	//  =title変数が存在しないor有効な文字列なし　←!titleでもtrimされるので×?
	if(!isset($check_data["title"]) || trim($check_data["title"]) === "") {
		//$error_list[] = "タイトルを入力してください";
	}// name変数の文字数が100以上
	elseif(mb_strlen($check_data["title"]) > 250) {
		$error_list[] = "タイトルは250文字以内で入力してください";
	}
	//ISBNのエラーチェック
	if(isset($check_data["isbn"]) && trim($check_data["isbn"]) !== "") {
		$check_data["isbn"] = getIsbn($check_data["isbn"]);
		if($check_data["isbn"] === ""){
			$error_list[] = "ISBNの値が不正です";
		}else{
			$_SESSION["isbn"] = $check_data["isbn"];
		}
	}
	//名前とISBNの両方が入力されていない場合のエラーチェック
	if(!isset($check_data["title"])	|| trim($check_data["title"]) === "")
	if(!isset($check_data["isbn"])	|| trim($check_data["isbn"]) === "") {
		$error_list[] = "タイトルとISBNのどちらかを入力してください";
	}
	//著者のエラーチェック
	if(!isset($check_data["author"]) || trim($check_data["author"]) === "") {
	//ISBNから自動入力	$error_list[] = "名前を入力してください";
	}elseif(mb_strlen($check_data["author"]) > 250) {
		$error_list[] = "著者は250文字以内で入力してください";
	}
	//出版社のエラーチェック
	if(isset($check_data["publisher"]) && trim($check_data["publisher"]) === ""
		&& mb_strlen($check_data["publisher"]) > 250) {
		$error_list[] = "出版社は250文字以内で入力してください";
	}
	//画像URLのエラーチェック
	if(isset($check_data["image"]) && trim($check_data["image"]) !== "" && !existPath($check_data["image"])) {
		$error_list[] = "画像URLもしくはパスが不正です";
	}
	//発売日のエラーチェック
	if(isset($check_data["datepub"]) && trim($check_data["datepub"]) !== ""
		&& !preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $check_data["datepub"])) {
		$error_list[] = "発売日の値が不正です";
	}
	//登録日のエラーチェック
	if(!isset($check_data["daterec"]) || trim($check_data["daterec"]) === "") {
		//ISBNから自動入力
		//$check_date["daterec"] = date("Y-m-d H-i-s");
		$_SESSION["daterec"] = date("Y-m-d H-i-s");
	}elseif(!preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/', $check_data["daterec"])) {
		$error_list[] = "登録日の値が不正です";
	}
	//状態のエラーチェック
	if(!isset($check_data["state"])) {
		$error_list[] = "状態を選択してください";
	}// sex変数がStateList中に存在する値かどうか
	elseif(!array_key_exists($check_data["state"], getStateList())) {
		$error_list[] = "正しい状態を選択してください";
	}
	//栞のエラーチェック
	if(!isset($check_data["marker"])) {
		$error_list[] = "栞を選択してください";
	}// sex変数がMarkerList中に存在する値かどうか
	elseif(!array_key_exists($check_data["marker"], getMarkerList())) {
		$error_list[] = "正しい栞を選択してください";
	}
	
	//カテゴリのエラーチェック
	//  category変数が存在＆空でない
	if(isset($check_data["category"]) && $check_data["category"] != "") {
		//age変数がAgeList中に存在する値かどうか
		if(!array_key_exists($check_data["category"], getCategoryList())) {
			$error_list[] = "正しいカテゴリを選択してください";
		}
	}
	//タグのエラーチェック
	//  tag変数or配列が存在しない
	if(isset($check_data["tag"]) && is_array($check_data["tag"])) {
		//tag変数(リスト)の値がTagList中に存在する値かどうか
		foreach($check_data["tag"] as $check_value) {
			if(!array_key_exists($check_value, getTagList())) {
				$error_list[] = "正しいタグを選択してください";
				break;
			}
		}
	}
	
	//追加タグのエラーチェック
	if(isset($check_data["newtag"]) && is_array($check_data["newtag"])) {
		$errors = checkNewTag($check_data["newtag"]);
		if(isset($errors) && $errors != ""){
			foreach($errors as $error)	$error_list[] = $error;
		}
	}
	
	//メモのエラーチェック
	//  memo変数が存在＆文字列が空
	if(!isset($check_data["memo"]) || $check_data["memo"] === "") {
	//	$error_list[] = "コメントを入力してください";
	}// memo変数に有効な文字がない
	elseif(trim($check_data["memo"]) === "") {
	//	$error_list[] = "正しいコメントを入力してください";
	}// comment変数の文字列が1000文字以上
	elseif(mb_strlen($check_data["memo"]) > 1000) {
		$error_list[] = "コメントは1000文字以内で入力してください";
	}
	
	debugDump($error_list,"error_list");
	return $error_list;
}


?>
