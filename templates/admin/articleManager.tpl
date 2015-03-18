<table class="auto">
<form method="post" action="">
<input type="hidden" name="hash" value="{$hash}">
      <tr>
		<td class="td_input"><input type="text" class="admin_home_article_title_input" name="article_title" value="{$am.article_title}" id="articleTitle" maxlength="200"/></td>
		<td class="td_button">

			<div class="show_cats_container"><input class="open_cats" type="button" value="{$lang.article_select_cat}">
			<div id="selectCatMain">
			<div class="cat_row_all"><input type="checkbox" class="cat_check" id="select_all_cat"><label for="select_all_cat" class="select_all">{$lang.article_select_cat_all}</label></div>

				{foreach from=$cats key=klic item=polozka name=i}
				<div class="cat_row"><input class="cat_check" type="checkbox" name="cat[{$polozka.cat_id}]" value="{$polozka.cat_name}" id="{$polozka.cat_id}" {if isset($cats_checked)}{$cats_checked[$polozka.cat_id]}>{/if}<label for="{$polozka.cat_id}">{$polozka.cat_name}</label></div>
				{foreachelse}
				<a href="./admin.php?akce=mc&mc=add_cat">{$lang.article_add_cat}</a>
				{/foreach}
			</div></div>
			</div>
		</td>
		<td class="td_button">	
			<div class="show_pages_container"><input class="open_pages" type="button" value="{$lang.article_select_page}">
			<div id="selectPagesMain">
			<div class="pages_row_all"><input type="checkbox" class="pages_check" id="select_all_pages"><label for="select_all_pages" class="select_all">{$lang.article_select_page_all}</label></div>

				{foreach from=$pages key=klic item=polozka name=i}
				<div class="pages_row"><input class="pages_check" type="checkbox" name="page[{$polozka.page_id}]" value="{$polozka.page_name}" id="{$polozka.page_id}" {if isset($pages_checked)}{$pages_checked[$polozka.page_id]}{/if}<label for="{$polozka.page_id}">{$polozka.page_name}</label></div>
				{foreachelse}
				<a href="./admin.php?akce=mp&mp=add_pages">{$lang.article_add_page}</a>
				{/foreach}
			</div></div>
			</div>						
		</td>
		<td>{$lang.article_enable_comments}<input type="checkbox" name="article_comments_enable" {$am.article_comments_enable}></td>
	</tr>

	<tr>
		<td colspan="5"><textarea name="article_text" class="ckeditor" id="article_text">{$am.article_text}</textarea></td>
	</tr>
	<tr>
		<td colspan="5"><input type="submit" class="circle_button" value="" name="submit" id="ok_button" title="{$lang.article_save_article}"></td>
	</tr>
</form>
</table>
