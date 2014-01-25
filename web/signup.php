<?php
//libsフォルダにある共通関数を読み込む
require_once($_SERVER["DOCUMENT_ROOT"]."/../bookanchor/loader.php");

//初期化を行なう
init();

//エラーメッセージや登録メッセージを保持する
$mes = array();

//登録ボタンをクリックされたときの処理
if (isset($_POST["regist"])) {
	//ここにエラーチェックとデータを登録する処理を追記します
	$name = $_POST["name"];
	$loginid = $_POST["loginid"];
	$password = $_POST["password"];
	
	$db = connectDB();
	
	if($name == ""){
		$mes[] = "名前を入力してください";
	}elseif(mb_strlen($name) > 100){
		$mes[] = "名前は100文字以内で入力してください";
	}
	
	if($login_id == ""){
		$mes[] = "ログインIDを入力してください";
	}elseif(strlen($loginid) != mb_strlen($loginid)){
		$mes[] = "ログインIDは半角英数字のみ使用してください";
	}else{
		$sql = "select count(*) from account where loginid='{$loginid}' and delflag='0'";
		$login_id_count = $db->getOne($sql);
		if($login_id_count != "0"){
			$mes[] = "入力されたログインIDはすでに使用されています";
		}
	}
	
	if($password == ""){
		$mes[] = "パスワードを入力してください";
	}elseif(strlen($login_id) != mb_strlen($login_id)){
		$mes[] = "パスワードは半角英数字のみ使用してください";
	}
	
	if(!count($mes)){
		$name		= $db->quoteSmart($name);
		$login_id	= $db->quoteSmart($login_id);
		$password	= md5($password);
		$del_flag	= "0";
		
		$sql = "insert into account(name,loginid,password,delflag,timestamp) values ({$name},{$loginid},'{$password}','{$delflag}',now())";
		$res = $db->query($sql);
		
		if(!DB::isError($res)){
			$mes[] = "データの登録に成功しました";
		}else{
			$mes[] = "データの登録に失敗しました";
		}
	}
}

debugMsg("signup ---");

//Smartyを生成する
$smarty = new MySmarty();

//メッセージを送る
$smarty->assign("mes", $mes);

//ページを表示する
$smarty->display("signup.tpl");
?>
