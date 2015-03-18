<form method="post" action="">
<select name="orderBy">
{$order_by}
	{foreach from=$orderByArray key=klic item=polozka name=i}
	<option value="{$polozka}"{if $order_by == $polozka} selected="selected"{/if}>{$klic}</value>		
	{foreachelse}
	<option>Nelze řadit</option>
	{/foreach}
</select>
<input type="submit" name="submitOrderBy" value="Seřadit">
</form>
