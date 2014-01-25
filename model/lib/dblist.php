<?php
require_once(LIB_DIR.'debug.php');
require_once(LIB_DIR.'config.php');

/**
 * データベースに接続
 * 
 * @return object	DBコネクタオブジェクト
 * 
 * @todo	DataSourceName直打ちなので対策
 * */
function connectDB(){
	require_once("DB.php");
	
//	$dsn = 'phptype://username:password@hostspec/database';
/*	$dsn = array(
		'phptype' => '',
		'username'=> '',
		'password'=> '',
		'hostspec'=> '',
		'database'=> '');
*/	$dsn = getGconf(CONFIG_DIR.".dblist",
					array(	'phptype',
							'username',
							'password',
							'hostspec',
							'database'));
	$db = DB::connect($dsn);
	
	if(PEAR::isError($db)){
		print "DB connect Error<br />";
		print $db->getCode()."<br />";
		print $db->getDebuginfo()."<br />";
		die($db->getMessage());
	}
	return $db;
}

/**
 * SQL文の実行結果をリストを求める
 * 
 * @param	object		SQL文
 * @param	objoct		DBコネクタ
 * @return	array[2][]	ページャ整形済みリスト
 * 
 * @todo	1ページの要素数を指定できるようにする
 * */
function pagerSqlList($sql, $db){
	require_once('Pager/Pager.php');
	$pagelength = "10";
	$data_array = array();
	
	$list = $db->getAll($sql, DB_FETCHMODE_ASSOC);
	$total = count($list);
	
	$page=array(
		"itemData"=>$list,
		"totalItems"=>$total,
		"perPage"=>$pagelength,
		"mode"=>"Jumping",
		"altFirst"=>"First",
		"altPrev"=>"",
		"prevImg"=>"&lt;&lt; 前を表示",
		"altNext"=>"",
		"nextImg"=>"次を表示 &gt;&gt;",
		"altLast"=>"Last",
		"altPage"=>"",
		"separator"=>" ",
		"append"=>1,
		"urlVar"=>"page"
		);
	
	$pager = Pager::factory($page);
	$data_array["data"]	 = $pager->getPageData();
	$data_array["links"] = $pager->links;
	return $data_array;
}

?>
