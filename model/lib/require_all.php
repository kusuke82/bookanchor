<?php
/**
 * 指定したディレクトリ以下の全ての.phpファイルをrequire_onceする
 * 
 * 相対ディレクトリにも対応
 * @param	String	dir	ディレクトリ
 * @return	bool		成功ならtrue.それ以外ならfalse
 * */
function require_all($dir){
	if(!file_exists($dir)){
		if(is_dir(getcwd().$dir))
			$dir = getcwd().$dir;
		else
			return false;
	}
	if(is_file($dir))	{
		if(is_phpfile($dir)){
			//var_dump($dir);
			require_once($dir);
			return true;
		}
		else
			return false;
	}
	$ls = scandir($dir);
	array_shift($ls);
	array_shift($ls);
//	print_r($ls);
	foreach($ls as $ford){
		require_all($dir.'/'.$ford);
	}
	return true;
}

/**
 * 指定されたパスが.phpファイルかどうかチェックする
 * 
 * @param	String	dir	対象ファイルパス
 * @return	bool	.phpファイルならtrue.それ以外ならfalse
 * */
function is_phpfile($dir){
	if( file_exists($dir) &&
		!is_dir($dir) &&
		substr($dir, -4) === '.php')
		return true;
	else return false;
}
?>
