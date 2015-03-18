<h1 id="pagehead">Správa článků</h1>
<h4>Celkem článků: <strong>{$active + $non_active}</strong>&nbsp;Aktivní články: <strong>{$active}</strong>&nbsp;Neaktivní články: <strong>{$non_active}</strong></h4>

<div class="page_panel">
<div class="admin_tool"><div class="tools_container"><a href="./admin.php?akce=ma&ma=add_article" class="circle_button" id="add_button">Přidat nový článek</a></div>{*include file="searchOne.tpl"*}</div>
<div align="center">{include file="pagination.tpl"}</div>
<table class="page_panel_table">
	<tr class="page_table_row">
		<td class="page_table_label">Aktivní</td>
		<td class="page_table_label">Nadpis</td>
		<td class="page_table_label">Autor</td>
		<td class="page_table_label">Datum</td>
		<td colspan="2" class="page_table_label">Možnosti</td>
		<td class="page_table_label"><input type="checkbox" name="article_{$polozka.article_id}" class="css-checkbox" id="article_{$polozka.article_id}" value="{$polozka.article_id}"/><label for="article_{$polozka.article_id}" class="css-label"></label></td>
	</tr>
	{foreach from=$ar key=klic item=polozka name=i}
	<tr class="page_table_row">

		{if $polozka.article_active == 1 }
		    <td class="active"><a href="./admin.php?akce=ma&amp;ma=show_articles&amp;set_active=1&amp;active=0&amp;article_id={$polozka.article_id}" title="Nastavit jako neaktivní"><img width="16px" src="./templates/admin/images/good.png"></a></td>
		{else}
    		<td class="active"><a href="./admin.php?akce=ma&amp;ma=show_articles&amp;set_active=1&amp;active=1&amp;article_id={$polozka.article_id}" title="Nastavit jako atkivní"><img width="16px" src="./templates/admin/images/bad.png"></a></td>
		{/if}

		<td class="page_panel_row_title">{$polozka.article_title|truncate:50:"&hellip;":true}</td>
		<td class="author_td"><a href="./index.php?akce=showUser&user_id={$polozka.user_id}">{$polozka.user_name}</a></td>
		<td class="date">{$polozka.article_time|date_format:"%d.%m.%Y %H:%M:%S"}</td>
		<td class="edit_td"><a class="edit" href="./admin.php?akce=ma&amp;ma=edit_article&amp;article_id={$polozka.article_id}">Upravit</a></td>
		<td class="del_td"><a class="circle_small" id="del_button_small" href="./admin.php?akce=ma&amp;ma=&ma=show_articles&amp;delete_article=1&amp;article_id={$polozka.article_id}"></a></td>
		<td class="del_chk_td"><input type="checkbox" name="article_{$polozka.article_id}" class="css-checkbox" id="article_{$polozka.article_id}" value="{$polozka.article_id}"/><label for="article_{$polozka.article_id}" class="css-label"></label></td>
	</tr>
	{foreachelse}
	<tr>
		<td>Žádné články&hellip;</td>
	</tr>
	{/foreach}
	</table>
</div>
{*include file="pagination.tpl"*}
