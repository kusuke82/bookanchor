<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-language" content="ja">
<link href="css/base.css" rel="stylesheet" type="text/css" />
<title>BookAncher - 書籍登録フォーム</title>
</head>

<body>
	<div class='sub-title'>書籍登録フォーム</div>
	{{if $errorlist}}
		<div>
			{{foreach from=$errorlist item=message}}
				<font color="#FF0000">{{$message}}</font><br />
			{{/foreach}}
		</div>
	{{/if}}
	<form action="input.php" method="post">
		<table class='bookform'>
			<tr>
				<th>タイトル</th>
				<td><input type="text" size="50" name="title" value="{{$smarty.session.title}}"></td>
			</tr>
			<tr>
				<th>ISBN</th>
				<td><input type="text" size="20" name="isbn" value="{{$smarty.session.isbn}}" maxlength="17"></td>
			</tr>
			<tr>
				<th>著者</th>
				<td><input type="text" size="50" name="author" value="{{$smarty.session.author}}"</td>
			</tr>
			<tr>
				<th>出版社</th>
				<td><input type="text" size="50" name="publisher" value="{{$smarty.session.publisher}}"</td>
			</tr>
			<tr>
				<th>画像</th>
				<td><input type="text" size="50" name="image" value="{{$smarty.session.image}}"</td>
			</tr>
			<tr>
				<th>発売日</th>
				<td><input type="text" size="50" name="datepub" value="{{$smarty.session.datepub}}"</td>
			</tr>
			<tr>
				<th>状態</th>
				<td>{{html_radios name="state" options=$state_value selected=$smarty.session.state separator=" " label_ids=true}}</td>
			</tr>
			<tr>
				<th>栞</th>
				<td>{{html_radios name="marker" options=$marker_value selected=$smarty.session.marker separator=" " label_ids=true}}</td>
			</tr>
			<tr>
				<th>カテゴリ</th>
				<td>
					<select name="category">
						<option value="">選択してください</option>
						{{html_options selected=$smarty.session.category options=$category_value}}
					</select>
				</td>
			</tr>
			<tr>
				<th rowspan=2>タグ</th>
				<td>
					{{html_checkboxes name="tag" options=$tag_value selected=$smarty.session.tag separator=' <wbr>'}}
				</td>
			</tr>
			<tr>
				<td><input type="text" size="50" name="newtag" value="{{$smarty.session.newtag}}">,区切りでタグ追加</td>
			</tr>
			<tr>
				<th>メモ</th>
				<td>
					<textarea rows="4" cols="50" name="memo">{{$smarty.session.memo}}</textarea>
				</td>
			</tr>
			<td colspan=2><input type="submit" name="confirm" value="登録確認"></td>
		</table>
	</form>
<!--	{{if ($get_mode === "modify")}}<b>GET[modify] is set.</b>{{/if}}-->
</body>
</html>
