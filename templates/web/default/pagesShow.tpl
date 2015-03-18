{foreach from=$pages key=klic item=polozka name=i}
    <div align="center"><a href="./page.php?page_id={$polozka.page_id}">{$polozka.page_name}</a>
        {if $polozka.page_static}
            {$lang.pages_static}
        {else}
            ({$polozka.final_count})
        {/if}
    </div>
{foreachelse}
    {$lang.pages_no_pages}&hellip;
{/foreach}
