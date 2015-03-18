<h1 id="pagehead">Správa uživatelů</h1>
<h4>Celkem uživatelů: <strong>{$active + $non_active}</strong>&nbsp;Aktivní uživatelé: <strong>{$active}</strong>&nbsp;Neaktivní uživatelé: <strong>{$non_active}</strong></h4>

<div class="page_panel">
<div class="admin_tool"><div class="tools_container"><a href="./admin.php?akce=mu&amp;mu=add_user" class="circle_button" id="add_button">Přidat uživatele</a></div>{*include file="searchOne.tpl"*}</div>
{include file="pagination.tpl"}

<table class="page_panel_table">
	{foreach from=$users key=klic item=polozka name=i}
	<tr class="page_table_row_user">
   <td class="page_panel_row_title">{$polozka.user_name}<div class="table_drop">{include file='userEditMenu.tpl'}</div></td>
	</tr>
{foreachelse}
	<tr class="page_table_row">
		<td><div class="no_stats">Žádní uživatelé.</div></td>
	</tr>
{/foreach}
</table>
</div>


