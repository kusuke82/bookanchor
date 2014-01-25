<?php

//ログイン認証
function authenticator($loginid, $password){
	$db = connectDB();
	
	$q_loginid = $db->quoteSmart($loginid);
	$q_password = md5($password);
	
	$sql = "select * from account where loginid={$q_loginid} and password='{$q_password}' and delflag ='0'";
	
	$res = $db->query($sql);
	
	if(!DB::isError($res)){
		$row = $res->fetchRow(DB_FETCHMODE_ASSOC);
	}else{
		return false;
	}
	
	if($row["loginid"] != ""){
		return true;
	}else{
		return false;
	}
}

//ログイン状態のチェック
function checkLogin(){
	//ログインしてなかったらlogin画面にジャンプ
	if(!isset($_SESSION["loginname"])){
		$url = "http://".$_SERVER["HTTP_HOST"].dirname($_SERVER["SCRIPT_NAME"])."/login.php";
		header("Location: ".$url);
		exit;
	}
	return true;
}

?>
