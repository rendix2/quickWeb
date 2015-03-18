{if isset($pagination) }
    {include file="pagination.tpl"}
{/if}

{foreach from=$ar key=klic item=polozka name=i}
<table>
	<tr>
		<td colspan="2"><h1><a href="./article-{$polozka.article_url}-{$polozka.article_id}">{$polozka.article_title}</a></h1></td>
	</tr>
	{if isset($polozka.user_name)}
	    <tr>
    		<td colspan="2">{$lang.article_author}<strong><a href="./?akce=show_user&amp;user_id={$polozka.user_id}">{$polozka.user_name}</a></strong></td>
    	</tr>
	{/if}

	{if isset($polozka.count_readings) AND $polozka.count_readings > 0 }
	    <tr>
        	<td colspan="2">{$lang.article_count_readings}<strong>{$polozka.count_readings}</strong></td>
    	</tr>
	{/if}
	
	{if isset($polozka.article_edit_count) AND $polozka.article_edit_count > 0 }
    	<tr>
        	<td colspan="2">{$lang.article_edits_count}<strong>{$polozka.article_edit_count}</strong></td>
	    </tr>
	{/if}	

	{if isset($polozka.article_edit_count) AND $polozka.article_edit_count > 0 }
	    <tr>
	        <td colspan="2">{$lang.article_last_edit}<strong>{$polozka.article_last_edit|date_format:"%d.%m.%Y %H:%M:%S"}</strong></td>
	    </tr>
	{/if}

	{if isset($polozka.count_comments) AND $polozka.count_comments > 0 }
	    <tr>
	        <td colspan="2">{$lang.article_count_comments}<strong>{$polozka.count_comments}</strong></td>
	    </tr>
	{/if}

	{if isset($polozka.article_time) AND $polozka.article_time > 0 }
	    <tr>
	        <td colspan="2">{$lang.article_date_add}<strong>{$polozka.article_time|date_format:"%d.%m.%Y %H:%M:%S"}</strong></td>
	    </tr>
	{/if}

	{if isset($s_ulogged) AND isset($s_uid) AND $s_ulogged == 1 AND $s_uid == $polozka.user_id}
	    <tr>
    		<td colspan="2"><a href="./?article_id={$polozka.article_id}&amp;delete_article=1">{$lang.article_delete}</a></td>
    	<tr>
	{/if}
	{if isset($polozka.cats_text) AND isset($polozka.cats) }
    	<tr>
    		<td>{$polozka.cats_text}</td>
	    	<td>{$polozka.cats}</td>
	    </tr>
	{/if}
</table>
<table>
	<tr>
		<td>{$polozka.article_text}</td>
	</tr>
</table>
{/foreach}

{if isset($pagination) }
{include file="pagination.tpl"}
{/if}