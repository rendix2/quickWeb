<div id="page_middle">
{*include file='pageHeaderLoggedIn.tpl'*}
{include file='pagePanelTop.tpl'}
{nocache}
<div class="admin_middle" id="d" >
<div class="dragd"></div><div class="dragu"></div>
{if isset($load) }
{include file=$load}
{else}
Nenalezený obsah&hellip;
{/if}
</div>
{/nocache}
</div>


