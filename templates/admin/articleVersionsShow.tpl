<div class="admin_content_template">
	<table>
		{foreach from=$article_versions key=klic item=polozka name=i}
		<tr>
			<td><a href="./admin.php?akce=ma&ma=edit_article&amp;article_id={$polozka.article_id}&amp;version=show_one&amp;version_id={$polozka.version_id}">{$polozka.article_title}</a></td>
			<td><b>{$polozka.article_time|date_format:"%d.%m.%Y %H:%M:%S"}</b></td>
			<td><a href="./admin.php?akce=ma&ma=edit_article&article_id={$polozka.article_id}&amp;version=delete&amp;version_id={$polozka.version_id}">Smazat</a></td>
			<td><a href="./admin.php?akce=ma&ma=edit_article&article_id={$polozka.article_id}&amp;version=recovery&amp;version_id={$polozka.version_id}">Obnovit!</a></td>
			<td>Přidal : <a href="./?akce=showUser&user_id={$polozka.user_id}">{$polozka.user_name}</a>
		</tr>
		{/foreach}
	</table>
</div>
