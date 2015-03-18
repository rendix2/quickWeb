<div class="admin_content_template">
	<table>
		{foreach from=$pages key=klic item=polozka name=i}
		<tr>
			<td><a href="./page.php?page_id={$polozka.page_id}">{$polozka.page_name}</a></td>
			<td><a href="./admin.php?akce=mp&mp=edit_page&amp;page_id={$polozka.page_id}">Editovat</a></td>
			<td><a href="./admin.php?akce=mp&mp=delete_page&amp;page_id={$polozka.page_id}">Smazat</a></td>
			{if $polozka.page_static}
                <td>Statická</td>
            {else}
                <td></td>
            {/if}
		</tr>
		{foreachelse}
		<tr>
			<td>Žádné stránky&hellip;</td>
		</tr>
		{/foreach}
	</table>
</div>
