<div class="admin_content_template">
	<table>
		{foreach from=$cats key=klic item=polozka name=i}
		<tr>
			<td>{$polozka.cat_name}</td>
			<td><a href="./admin.php?akce=mc&amp;mc=edit_cat&amp;cat_id={$polozka.cat_id}">Editovat</a></td>
			<td><a href="./admin.php?akce=mc&amp;mc=delete_cat&amp;cat_id={$polozka.cat_id}">Smazat</a></td>
		</tr>
		{foreachelse}
		<tr>
			<td>Žádné kategorie&hellip;</td>
		</tr>
		{/foreach}
	</table>
</div>
