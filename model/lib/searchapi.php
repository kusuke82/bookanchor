<?php
require_once(LIB_DIR.'debug.php');
require_once(LIB_DIR.'isbn.php');


//ISBNに対応したAmazonのページから要素を取り出す
function getGooglebooks($isbn, $nmax){
/*	$html = file_get_contents("http://www.amazon.co.jp/dp/".$isbn."/");
	$html = mb_convert_encoding($html,"utf-8","sjis-win");
	$domDocument = new DOMDocument();
	$domDocument->loadHTML($html);
	$xmlString = $domDocument->saveXML();
	$xmlObject = simplexml_load_string($xmlString);
	$amazon = json_decode(json_encode($xmlObject), true);
*/	
//	$isbn = "9784798114385";
//	$isbn = "4910071931232";
	//$isbn = "4798114383";
//	$isbn = "9784797344646";
	$isbn = mb_ereg_replace(" \- ", "+", $isbn);
	$isbn = mb_ereg_replace("[ 　＋]", "+", $isbn);
//	$isbn = mb_ereg_replace("[ -＋]", "+", $isbn);
	debugMsg("GoogleBooks: https://www.googleapis.com/books/v1/volumes?q=".$isbn);
	$json = file_get_contents("https://www.googleapis.com/books/v1/volumes?q=".$isbn);
	//$json = file_get_contents("https://www.googleapis.com/books/v1/volumes?q=".$isbn."&max-results=5");
//	$json = file_get_contents("http://books.google.com/books?bibkeys=ISBN:{$isbn}&jscmd=viewapi");
//	$json = str_replace(array("\\x26", ";", "var _GBSBookInfo = "), array("&", "", ""), $json);
	$json = json_decode($json, true);
//	$book = $book["ISBN:".$isbn];
//	print $book["info_url"];
//	$json = file_get_contents($book["info_url"]."&redir_esc=y");
//	$json = str_replace(array("\\x26", ";", "var _GBSBookInfo = "), array("&", "", ""), $json);
//	$book = json_decode($json, true);
	if($json["totalItems"] == 0)	return null;
	$books = "";
	foreach($json["items"] as $item){
		if(array_key_exists("title", $item["volumeInfo"])) {
			$book["title"]	= $item["volumeInfo"]["title"];
		}else{
			$book["title"]	= "";
		}
		if(array_key_exists("subtitle", $item["volumeInfo"])){
			$book["title"]	= $book["title"]." - ".$item["volumeInfo"]["subtitle"];
		}
		if(array_key_exists("industryIdentifiers", $item["volumeInfo"])) {
			if(array_key_exists("identifier", $item["volumeInfo"]["industryIdentifiers"][0])) {
				$book["isbn"]	= $item["volumeInfo"]["industryIdentifiers"][0]["identifier"];
			}elseif(array_key_exists("identifier", $item["volumeInfo"]["industryIdentifiers"][1])) {
				$book["isbn"]	= $item["volumeInfo"]["industryIdentifiers"][1]["identifier"];
			}else
				$book["isbn"]	= "";
		}else{
			$book["isbn"]	= "";
		}
		if(array_key_exists("authors", $item["volumeInfo"])){
			$book["author"]	 	= join(",", $item["volumeInfo"]["authors"]);
		}else{
			$book["author"]		= "";
		}
		if(array_key_exists("publisher", $item["volumeInfo"])){
			$book["publisher"]	 	= $item["volumeInfo"]["publisher"];
		}else{
			$book["publisher"]		= "";
		}
		if(array_key_exists("publishedDate", $item["volumeInfo"])){
		//	var_dump($item["volumeInfo"]["publishedDate"]);
		//	print "<br />";
		//	if(preg_match('[0-9]{4}-[0-9]{2}', $item["volumeInfo"]["publishedDate"]))
			if(preg_match('/[0-9]{4}/', $item["volumeInfo"]["publishedDate"]))
				$book["datepub"]	= $item["volumeInfo"]["publishedDate"]."-00-00";
			if(preg_match('/[0-9]{4}\-[0-9]{2}/', $item["volumeInfo"]["publishedDate"]))
				$book["datepub"]	= $item["volumeInfo"]["publishedDate"]."-00";
			else
				$book["datepub"]	= $item["volumeInfo"]["publishedDate"];
		}else{
			$book["datepub"]	= "";
		}
		if(array_key_exists("imageLinks",$item["volumeInfo"])){
			if(array_key_exists("thumbnail",$item["volumeInfo"]["imageLinks"]))
				$book["image"]	= $item["volumeInfo"]["imageLinks"]["thumbnail"];
			elseif(array_key_exists("smallThumbnail",$item["volumeInfo"]["imageLinks"]))
				$book["image"]	= $item["volumeInfo"]["imageLinks"]["smallThumbnail"];
			else
				$book["image"]	= "";
		}else	$book["image"]	= "";
		
		$books[] = $book;
		if(count($books) == $nmax)	break;
	}
//	print "<pre>";
//	var_dump($books);
//	print "</pre>";
	return $books;
}
?>
