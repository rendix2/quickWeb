<form method="post" action="">
<input type="hidden" name="hash" value="{$hash}">
	<table>
		<tr>
			<td><label for="comment_text">{$lang.comment_text}</label></td>
		</tr>
		<tr>
			<td><textarea name="comment_text" id="comment_text">{$comment_text|htmlspecialchars}</textarea></td>
		</tr>
		<tr>
			<td><input type="submit" name="submit" value="{$lang.comment_save}"></td>
		</tr>
	</table>
</form>
