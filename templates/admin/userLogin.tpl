<form method="post" action="">
	<div class="login_panel" id="lp">
		<div class="login_title">Přihlášení do administrace</div>
		<div class="login_input_bg">
			<input id="loginname" type="text" autocomplete="off" name="user_name" class="login_input" value="Jméno" onfocus="(this.value == 'Jméno') && (this.value = '')" onblur="(this.value == '') && (this.value = 'Jméno')" >
		</div>
		<div class="login_input_bg">
			<div id="div1">
				<input name="user_password_temp" type="text" autocomplete="off" value="Heslo" class="login_input" onfocus="changeBox()" />
			</div>
			<div id="div2" style="display:none">
				<input name="user_password" id="loginpass" autocomplete="off" type="password" value="" class="login_input" onBlur="restoreBox()" />
			</div>
		</div>
		<input type="checkbox" name="remember" id="remember" class="css-checkbox_login" />
		<label for="remember" class="css-label_login">Zapamatovat přihlášení</label>
		<input type="submit" name="submit" value="" class="circle_button" id="login_button">
		<input type="hidden" name="hash" value="{$hash}">
	</div>
</form>
