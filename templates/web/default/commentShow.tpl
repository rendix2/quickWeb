{foreach from=$cm key=klic item=polozka name=i}
<a name="{$polozka.comment_id}"></a>
<table>
	<tr>
		<td>{$lang.comment_author}</td>
		<td><a href="./?akce=showUser&amp;user_id={$polozka.user_id}">{$polozka.user_name}</a></td>
	</tr>
	<tr>
		<td>{$lang.comment_added}</td>
		<td>{$polozka.comment_time|date_format:"%d.%m.%Y %H:%M:%S"}</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2">{$polozka.comment_text}</td>
	</tr>
	{if $s_uid == $polozka.user_id}
	<a href="./?article_id={$polozka.article_id}&amp;comment_id={$polozka.comment_id}&delete_comment=1">{$lang.comment_delete}</a>
	{/if}
</table>
{/foreach}
