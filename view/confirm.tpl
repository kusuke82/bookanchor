<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-language" content="ja">
<link href="./css/base.css" rel="stylesheet" type="text/css" />
<title>BookAncher - 書籍情報確認</title>
</head>

<body>
	<div class='sub-title'>書籍情報確認</div>
	<!--form action="input.php" method="post"-->
		<table class='bookform'>
			<tr>
				<th>タイトル</th>
				<td colspan=2>{{$smarty.session.title}}</td>
			</tr>
			<tr>
				<th>ISBN</th>
				<td>{{$smarty.session.isbn}}</td>
				<td rowspan=9 id="imgwidth">
					{{if $smarty.session.image != ""}}
					<img src="{{$smarty.session.image}}" alt="bookimage">
					{{else}}
					<img src="noimage.png" alt="bookimage">
					{{/if}}
				</td>
			</tr>
			<tr>
				<th>著者</th>
				<td>{{$smarty.session.author}}</td>
			</tr>
			<tr>
				<th>出版社</th>
				<td>{{$smarty.session.publisher}}</td>
			</tr>
			<tr>
				<th>画像</th>
				<td <!--id="ofscroll"-->{{$smarty.session.image}}</td>
			</tr>
			<tr>
				<th>発売日</th>
				<td>{{$smarty.session.datepub}}</td>
			</tr>
			<tr>
				<th>登録日</th>
				<td>{{$smarty.session.daterec}}</td>
			</tr>
			<tr>
				<th>状態</th>
				<td>{{$state_value[$smarty.session.state]}}</td>
			</tr>
			<tr>
				<th>栞</th>
				<td>{{$marker_value[$smarty.session.marker]}}</td>
			</tr>
			<tr>
				<th>カテゴリ</th>
				<td>{{$category_value[$smarty.session.category]}}</td>
			</tr>
			<tr>
				<th rowspan=2>タグ</th>
				<td colspan=2>
					{{foreach from=$smarty.session.tag item=tag name=tag}}
						{{if $smarty.foreach.tag.first}}
							{{$tag_value[$tag]}}
						{{else}}
							, {{$tag_value[$tag]}}
						{{/if}}
					{{/foreach}}
				</td>
			</tr>
			<tr>
				<td colspan=2 id="emptyhide">{{$smarty.session.newtag}}</td>
			</tr>
			<tr>
				<th>メモ</th>
				<td colspan=2>{{$smarty.session.memo|nl2br}}</td>
			</tr>
		</table>
		<table class="submitform">
			<tr>
				<!--td colspan=2><table><tr-->
				<form action="input.php" method="post">
					<td><input type="submit" name="modify" value="修正"></td>
				</form>
				<form action="autofill.php" method="post">
					<td><input type="submit" name="autofill" value="オートフィル"></td>
				</form>
				{{if ($get_mode === "confirm") or ($smarty.session.mode === "filled")}}
				<form action="confirm.php" method="post">
					<td><input type="submit" name="register" value="登録"></td>
				</form>
				{{else}}
					<td></td>
				<!--/form-->
				{{/if}}
					<td>　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　</td>
				<!--/tr><!--/table></td-->
			<!--	<form action="confirm.php" method="post">
					<td id="traction"><input type="submit" name="modify" value="修正"></td>
				</form>
				{{if $get_mode === "confirm"}}
				<form action="confirm.php" method="post">
					<td id="traction"><input type="submit" name="register" value="登録"></td>
				</form>	
				{{/if}}-->
			<!--<td colspan="2" id="wsnowrap">
					<form action="confirm.php" method="post"><input type="submit" name="modify" value="修正"></form>{{if $get_mode === "confirm"}}<form action="confirm.php" method="post"><input type="submit" name="register" value="登録"></form>	{{/if}}
				</td>-->
			<!--	<td colspan="2">
					<form action="confirm.php" method="post">
						<input type="submit" name="modify" value="修正"> 
						{{if $get_mode === "confirm"}}   <input type="submit" name="register" value="登録">{{/if}}
					</form>
				</td>-->
			</tr>
		</table>
	<!--/form-->
</body>
</html>
