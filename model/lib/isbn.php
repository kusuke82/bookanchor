<?php
require_once(LIB_DIR.'debug.php');

//文字列から数字のみを抽出して返す
function getOnlyDigit($str){
//	if(ctype_digit($str)	return $str;
	if(strlen($str) != mb_strlen($str))	return "";
	return mb_ereg_replace('[^0-9]', '', $str);
}

//数字列からチェックサムも含め正しいISBNコードを返す
function getIsbn($str){
	if(!($code = getOnlyDigit($str)))	return "";
	debugDump($code,"isbncode:");
//	$code = $str;//get_only_digit($str);
	$digit = 0;
	switch(strlen($code)){
		case 9:
		case 10:
			return getIsbn10($code);
			//$code = substr($code, 0, 9);	break;
		case 12:
		case 13:
			return getIsbn13($code);
			//$code = substr($code, 3, 9);	break;
		default:
			return "";
	}
}

//数字列からチェックサムも含め正しいISBN10コードを返す
function getIsbn10($str){
	if(!($code = getOnlyDigit($str)))	return "";
	debugMsg("getIsbn10:code:".$code);
	$digit = 0;
	switch(strlen($code)){
		case 9:
			break;
		case 10:
			$code = substr($code, 0, 9);	break;
		case 12:
		case 13:
			if(substr($code, 0, 3) == "978"){
				$code = substr($code, 3, 9);	break;
			}
		default:
			return "";
	}
	for($i=0; $i<9; $i++)
		$digit += $code[$i] * (10 - $i);
	$digit = 11 - ($digit % 11);
	switch($digit){
		case 10:	$digit = 'X';break;
		case 11:	$digit = '0';break;
	}
	return $code.$digit;
}

//数字列からチェックサムも含め正しいISBN13コードを返す
function getIsbn13($str){
	if(!($code = getOnlyDigit($str)))	return "";
	debugMsg("getIsbn13:code:".$code."<br />");
//	$code = $str;//get_only_digit($str);
	$digit = 0;
	switch(strlen($code)){
		case 9:
			$code = "978".$code;	break;
		case 10:
			$code = "978".substr($code, 0, 9);	break;
		case 12:
		case 13:
			if(substr($code, 0, 3) == "978"){
				$code = substr($code, 0, 12);	break;
			}
		default:
			return "";
	}
	for($i=0; $i<12; $i+=2)
		$digit += $code[$i] + $code[$i+1] * 3;
	$digit = 10 - ($digit % 10);
	return $code.$digit;
}


?>
