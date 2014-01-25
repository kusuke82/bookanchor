<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-language" content="ja">
<link href="css/base.css" rel="stylesheet" type="text/css" />
<title>BookAncher - 書籍一覧画面</title>
<script type="text/javascript">
	<!--
	function deleteBook() {
		if(window.confirm("選択した項目を削除します。よろしですか？")){
			document.booklist.submit();
		}
	}
	//-->
</script>
</head>

<body>
<div class="sub-title">
書籍一覧
</div>

<form action="booklist.php" method="post">
<table class="editform">
	<tr>
		<th colspan='5'>絞り込み選択</th>
	</tr>
	<tr>
		<td>キーワード:
			<input name="keyword" type="text" value="{{$smarty.session.keyword}}">
		</td>
		<td>カテゴリ:
			<select name="keyctg">
				<option value=""></option>
				{{html_options options=$category_value selected=$smarty.session.keyctg}}
			</select>
		</td>
		<td>状態:
			<select name="keystt">
				<option value=""></option>
				{{html_options options=$state_value selected=$smarty.session.keystt}}
			</select>
		</td>
		<td>栞:
			<select name="keymark">
				<option value=""></option>
				{{html_options options=$marker_value selected=$smarty.session.keymark}}
			</select>
		</td>
		<td>
			<input name="search" type="submit" value="検索">
		</td>
	</tr>
	<!--<tr>
		<th>検索キーワード</th>
		<td><input name="keyword" type="text" value="{{$smarty.session.keyword}}"></td>
	</tr>
	<tr>
		<th>検索カテゴリ</th>
		<td>
			<select name="keyctg">
				<option value=""></option>
				{{html_options options=$category_value selected=$smarty.session.keyctg}}
			</select>
		</td>
	</tr>
	<tr>
		<th>検索状態</th>
		<td>
			<select name="keystt">
				<option value=""></option>
				{{html_options options=$state_value selected=$smarty.session.keystt}}
			</select>
		</td>
	</tr>
	<tr>
		<th>検索栞</th>
		<td>
			<select name="keymark">
				<option value=""></option>
				{{html_options options=$marker_value selected=$smarty.session.keymark}}
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<input name="search" type="submit" value="検索">
		</td>
	</tr>-->
</table>
</form>


<form action="booklist.php" method="post" name="booklist">
<!--チケットをhiddenでPOST-->
<input type="hidden" name="ticket" value="{{$ticket}}" />
<table class="stocklist">
	<div align="center">{{$links|smarty:nodefaults}}</div>
	{{foreach item=book key=key from=$booklist name=booklist}}
		{{if $smarty.foreach.booklist.first}}
			<tr>
				<th width="20em">選択</th>
				<th width="30em">状態</th>
				<th width="38em">栞</th>
				<th width="280em">タイトル</th>
			<!--<th width="56em">発売日</th> -->
				<th width="52em">カテゴリ</th>
				<th width="180em">タグ</th>
				<th width="70em">メモ</th><!-- width="150em" width="80em"-->
			</tr>
		{{/if}}
		<tr>
			<td id="aligncenter"><input type="checkbox" name="selectid[]" value="{{$book.id}}" /></td>
			<td id="aligncenter">{{$state_value[$book.state]}}</td>
			<td id="aligncenter">{{$marker_value[$book.marker]}}</td>
			<td><a href="confirm.php?id={{$book.id}}">{{$book.title | nl2br}}</a></td>
		<!--	<td>{{$book.datepub | date_format:"%Y-%m-%d"|}}</td>-->
			<td>{{$category_value[$book.category]}}</td>
			<td>
				{{foreach item=tag from=$book.tag name=tag}}
					{{if $smarty.foreach.tag.first}}<nobr>{{$tag_value[$tag]}}
					{{else}},</nobr> <wbr><nobr>{{$tag_value[$tag]}}</nobr>
					{{/if}}
				{{/foreach}}
			</td>
			<td>{{$book.memo | nl2br}}</td>
		</tr>
	{{foreachelse}}
		<tr>
			<td colspan="8">書籍データがありません</td>
		</tr>
	{{/foreach}}
	<tr>
		<td colspan="8" align="right">
			<input type="button" onclick="deleteBook();" value="選択した書籍を削除" />
		</td>
	</tr>
</table>
<!--<input type="button" value="検索内容のCSV出力" onClick="location.href='csvout.php?mode=search'" />-->
<div align="center">{{$links|smarty:nodefaults}}</div>
</form>


</body>
</html>
