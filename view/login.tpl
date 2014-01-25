<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-language" content="ja">
<link href="css/base.css" rel="stylesheet" type="text/css" />
<title>ログイン画面</title>
<script language="javascript">
	<!--
	//フレーム内に表示されたらフレーム解除
	if(self != top)
		top.location.href = self.location.href;
	//-->
</script>
</head>

<body>
<div class="sub-title">
ログイン
</div>
{{if $error_message != ""}}
<div class="note">
	<font color="#FF0000">{{$error_message}}</font>
</div>
{{/if}}
<form action="login.php" method="post">
<table class="editform">
	<tr>
		<th>ログインID</th>
		<td><input name="loginid" type="text" value="{{$loginid}}" id="loginid" maxlength="50"></td>
	</tr>
	<tr>
		<th>パスワード</th>
		<td><input name="password" type="password" value="" id="password"></td>
	</tr>
</table>
<p>
	<input type="submit" name="login" id="login" value="ログイン">
</p>
</form>
</body>
</html>

