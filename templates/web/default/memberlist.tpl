<div align="center">{foreach from=$users key=klic item=polozka name=i}
<div><a href="./?akce=show_user&amp;user_id={$polozka.user_id}">{$polozka.user_name}</a> {if $polozka.user_active}({$lang.users_active}){/if}</div>
{/foreach}
</div>

