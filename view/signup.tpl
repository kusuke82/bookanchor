<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-language" content="ja">
<link href="css/base.css" rel="stylesheet" type="text/css" />
<title>メンバー登録フォーム</title>
</head>
<body>
<div class="sub-title">
メンバー登録フォーム
</div>
{{if $mes}}
	<div>
		{{foreach from=$mes item=message}}
			<font color="#FF0000">{{$message}}</font><br />
		{{/foreach}}
	</div>
{{/if}}
<form action="signup.php" method="post">
<table class="editform">
	<tr>
		<th>名前</th>
		<td><input type="text" name="name" value="{{$smarty.post.name}}"></td>
	</tr>
	<tr>
		<th>ログインID</th>
		<td><input type="text" name="login_id" value="{{$smarty.post.loginid}}"></td>
	</tr>
	<tr>
		<th>パスワード</th>
		<td><input type="password" name="password" value="{{$smarty.post.password}}"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="regist" value="データ登録"></td>
	</tr>
</table>
</form>

</body>
</html>
