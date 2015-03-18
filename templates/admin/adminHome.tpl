<h1 id="admin_home_welcome">Vítejte v administraci, jste přihlášen jako <span id="welcome_name">{$user_name}</span></h1>
<div class="col">
<div class="admin_home_panel">
	  <div class="admin_home_panel_title">Vložit rychlý článek:</div>
	<form method="POST" action="">
		<input type="hidden" name="hash" value="{$hash}">
		<table class="admin_home_quick_art">
			<tr>
				<td class="td_input"><input type="text" class="admin_home_article_title_input" name="article_title" value="Zde napište nadpis článku..." id="article_title" maxlength="200"  onfocus="(this.value == 'Zde napište nadpis článku...') && (this.value = '')"
       onblur="(this.value == '') && (this.value = 'Zde napište nadpis článku...')" /></td><td class="td_button"><div class="show_cats_container"><input class="open_cats" type="button" value="Vyberte kategorii">
<div id="selectCatMain">
<div class="cat_row_all"><input type="checkbox" class="cat_check" id="select_all"><label for="select_all" class="select_all">Vybrat všechny</label></div>

{foreach from=$cats key=klic item=polozka name=i}
<div class="cat_row"><input class="cat_check" type="checkbox" name="cat[{$polozka.cat_id}]" value="{$polozka.cat_name}" id="{$polozka.cat_id}" {$cats_checked[$polozka.cat_id]}><label for="{$polozka.cat_id}">{$polozka.cat_name}</label></div>
{foreachelse}
Žádné kategorie&hellip;
{/foreach}
</div></div></div></td>
			</tr>				
			<tr>
				<td colspan="2"><textarea name="article_text" id="article_text" class="admin_home_article_text" onfocus="clearContents(this);">Zde napište text článku...</textarea></td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" class="circle_button" value="" name="submit" id="ok_button"></td>
			</tr>
		</table>
	</form>
</div>
</div>
<div class="col">
<div class="admin_home_panel">
<form method="post" action="">
<input type="hidden" name="hash" value="{$hash}">
<div class="admin_home_panel_title">Poslední přidané články:</div>
<table class="admin_home_panel_table">
{foreach from=$articles key=klic item=polozka name=i}
	<tr class="admin_table_row">
		<td class="admin_home_panel_row_title"><a href="#">{$polozka.article_title|truncate:75:"&hellip;":true}</a></td>
		<td><a class="edit" href="./admin.php?akce=ma&amp;ma=edit_article&amp;article_id={$polozka.article_id}">Upravit</a></td>
		<td><input type="checkbox" name="article[{$polozka.article_id}]" class="css-checkbox" id="article_{$polozka.article_id}" value="{$polozka.article_title}"/><label for="article_{$polozka.article_id}" class="css-label"></label></td>
	</tr>
{foreachelse}
	<tr class="admin_table_row">
		<td><div class="no_stats">Nenalezeny žádné články. <a class="edit" href="./admin.php?akce=manageArticles&manageArticles=addArticle"> Začněte zde</a></div></td>
	</tr>
{/foreach}
</table>
<input type="submit" class="circle_button" value="" name="article_submit" id="del_button">
</form>
</div>
<div class="admin_home_panel">
<form method="post" action="">
<input type="hidden" name="hash" value="{$hash}">
<div class="admin_home_panel_title">Poslední přidané položky menu:</div>
<table class="admin_home_panel_table">

{foreach from=$pages key=klic item=polozka name=i}
<tr class="admin_table_row">
	<td class="admin_home_panel_row_title"><a href="#">{$polozka.page_name|truncate:30:"&hellip;":true}</a></td>
	<td><a class="edit" href="./admin.php?akce=mp&mp=edit_page&amp;page_id={$polozka.page_id}">Upravit</a></td>
	<td><input type="checkbox" name="page[{$polozka.page_id}]" class="css-checkbox" id="page_{$polozka.page_id}" value="{$polozka.page_name}"/><label for="page_{$polozka.page_id}" class="css-label"></label></td>
</tr>
{foreachelse}
<tr class="admin_table_row">
	<td><div class="no_stats">Nenalezeny žádné položky menu. <a class="edit" href="./admin.php?akce=mp&mp=add_page"> Začněte zde</a></div></td>
</tr>  
{/foreach}
</table>

<input type="submit" class="circle_button" value="" name="page_submit" id="del_button">
</form>
</div>
</div>
