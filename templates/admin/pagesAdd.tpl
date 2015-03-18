<div class="admin_content_template">
	<form method="post" action="">
		<div align="center">
		<table>
			<tr>
				<td><label for="page_name">Jméno stránky</label></td>
				<td><input type="text" name="page_name" value="{$pages.page_name}" id="page_name" autocomplete="off" autofocus="on"></td>
			</tr>
			<tr>
				<td>Kategorie</td>
				<td>
					<div class="show_cats_container"><input class="open_cats" type="button" value="Vyberte kategorii">
						<div id="selectCatMain">
						<div class="cat_row_all"><input type="checkbox" class="cat_check" id="select_all_cat"><label for="select_all_cat" class="select_all">vybrat všechny</label></div>

						{foreach from=$cats key=klic item=polozka name=i}
						<div class="cat_row"><input class="cat_check" type="checkbox" name="cat[{$polozka.cat_id}]" value="{$polozka.cat_name}" id="{$polozka.cat_id}" {if isset($cats_checked)}{$cats_checked[$polozka.cat_id]}>{/if}<label for="{$polozka.cat_id}">{$polozka.cat_name}</label></div>
						{foreachelse}
						Žádné kategorie&hellip;
						{/foreach}
					</div></div>
					</div>
				</td>
			</tr>
			<tr>
				<td><label for="page_static">Stránka bude statická ze souboru</label></td>
				<td><input type="checkbox" name="page_static" value="1" id="page_static" {$pages.page_static_checked}>&nbsp;</td>
			</tr>
				<td><label for="page_filename">Jméno souboru</label></td>
				<td><input type="text" name="page_filename" value="{$pages.page_filename}" id="page_filename" autocomplete="off"></td>				
			</tr>
		</table>
		</div>
		<div align="center">
		<table>
			{if $pages.page_static == 1}
			<tr>
				<td colspan="2" align="center"><textarea name="page_text" class="ckeditor">{$pages.page_text}</textarea></td>
			</tr>
			{/if}	
			<tr>
				<td colspan="2" align="center"><input type="submit" name="submit" value="{$pages.page_submit}" ></td>
			</tr>
		</table>
		</div>
	</form>
</div>
