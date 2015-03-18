<form method="post" action="">
<input type="hidden" name="hash" value="{$hash}">
	<table align="center">
		<tbody>
			<tr>
				<td><label for="user_name">Uživatelské jméno</label></td>
				<td><input type="text" name="user_name" maxlength="50" id="user_name" value="{$userRegister.user_name|htmlspecialchars}" autocomplete="off" autofocus="on"></td>
			</tr>
			<tr>
				<td><label for="user_mail">Uživatelské e-mail</label></td>
				<td><input type="text" name="user_mail" maxlength="50" id="user_mail" value="{$userRegister.user_mail|htmlspecialchars}" autocomplete="off"></td>
			</tr>
			<tr>
				<td><label for="user_password">Uživatelské heslo</label></td>
				<td><input type="password" name="user_password" maxlength="50" id="user_password" autocomplete="off"></td>
			</tr>
			<tr>
				<td><label for="user_password_check">Uživatelské heslo pro potvrzení</label></td>
				<td><input type="password" name="user_password_check" maxlength="50" id="user_password_check" autocomplete="off" ></td>
			</tr>

		</tbody>
		<thead>
			<tr>
				<td colspan="3" align="center">Registrace</td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="3" align="center"><input type="submit" name="submit" value="Registrovat"></td>
			</tr>
		</tfoot>
	</table>
</form>
