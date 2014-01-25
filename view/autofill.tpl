<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-language" content="ja">
<link href="./css/base.css" rel="stylesheet" type="text/css" />
<title>BookAncher - 書籍情報補完</title>
</head>
<body>
	<div class='sub-title'>書籍情報補完</div>
	<!--br />title:{{$filllist[0].title}}<br /-->
		<table class='fillform'>
			<tr><th colspan=3>対象書籍</th></tr>
			<tr><td class='fillimg' rowspan=6>
			{{if $smarty.session.tempbook.image != ""}}
				<img src="{{$smarty.session.tempbook.image}}" alt="bookimage">
			{{else}}
				<img src="noimage.png" alt="bookimage">
			{{/if}}
				</td>
				<th>タイトル</th>
				<td class='fillprm'>{{$smarty.session.tempbook.title}}</td>
			</tr>
			<tr>
				<th>ISBN</th>
				<td class='fillprm'>{{$smarty.session.tempbook.isbn}}</td>
			</tr>
			<tr>
				<th>著者</th>
				<td class='fillprm'>{{$smarty.session.tempbook.author}}</td>
			</tr>
			<tr>
				<th>出版社</th>
				<td class='fillprm'>{{$smarty.session.tempbook.publisher}}</td>
			</tr>
			<tr>
				<th>発売日</th>
				<td class='fillprm'>{{$smarty.session.tempbook.datepub}}</td>
			</tr>
			<tr>
				<th>画像</th>
				<td class='fillprm'>{{$smarty.session.tempbook.image}}</td>
			</tr>
		</table>
		<table class="submitform">
			<tr>
				<form action="confirm.php" method="get">
					<td><input type="submit" name="id={{$smarty.session.bookid}}" value="キャンセル"></td>
				</form>
			{{if $get_mode != "autofill"}}
				<form action="autofill.php?apply" method="post">
					<td><input type="submit" name="apply" value="決定"></td>
				</form>
			{{/if}}
					<td>　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　</td>
			</tr>
		</table>
	{{if $smarty.session.filllist == null}}書籍データが見つかりません<br />
	{{else}}
	{{foreach from=$smarty.session.filllist item=filldata key=key}}
		<form action="autofill.php?fill={{$key}}" method="post" name="fillform" >
		<table class='fillform'>
			<tr><th colspan=5>候補: {{$key+1}}</th></tr>
			<tr>
				<td class='fillimg' rowspan=6>
				{{if $filldata.image != ""}}
				<img src="{{$filldata.image}}" alt="bookimage">
				{{else}}
				<img src="noimage.png" alt="bookimage">
				{{/if}}
				</td>
				<th>タイトル</th>
				<td class='fillprm'>{{$filldata.title}}</td>
				<td class='fillchk'><input type="checkbox" name="cb[0]" value="title" checked></td>
				<td class='submitform' rowspan=6><input type="submit" name="{{$key}}" value="適用"></td>
			</tr>
			<tr>
				<th>ISBN</th>
				<td>{{$filldata.isbn}}</td>
				<td><input type="checkbox" name="cb[1]" value="isbn" checked></td>
			</tr>
			<tr>
				<th>著者</th>
				<td>{{$filldata.author}}</td>
				<td><input type="checkbox" name="cb[2]" value="author" checked></td>
			</tr>
			<tr>
				<th>出版社</th>
				<td>{{$filldata.publisher}}</td>
				<td><input type="checkbox" name="cb[3]" value="publisher" checked></td>
			</tr>
			<tr>
				<th>発売日</th>
				<td>{{$filldata.datepub}}</td>
				<td><input type="checkbox" name="cb[4]" value="datepub" checked></td>
			</tr>
			<tr>
				<th>画像</th>
				<td>{{$filldata.image}}</td>
				<td><input type="checkbox" name="cb[6]" value="image" checked></td>
			</tr>
		</table>
		</form>
	{{/foreach}}
	{{/if}}
</body>
</html>
