<form method="post" action="">
<input type="hidden" name="hash" value="{$hash}">
<table align="center">
<thead>
<tr>
<td colspan="2" align="center">{$lang.users_login}</td>
</tr>
</thead>
<tr>
<td><label for="user_name">{$lang.users_user_name}</label>
<td><input type="text" name="user_name" value="{$u.user_name|htmlspecialchars}" maxlength="{$u.user_name_max_length}" autocomplete="off" autofocus="on" id="user_name"></td>
</tr>
<tr>
<td><label for="user_password">{$lang.users_user_password}</label>
<td><input type="password" name="user_password" maxlength="{$u.user_passWord_max_length}" autocomplete="off" id="user_password"></td>
</tr>
<tr>
<td align="center" colspan="2"><input type="submit" name="submit" value="{$lang.users_login_submit}"></td>
</tr>
</table>
</form>
