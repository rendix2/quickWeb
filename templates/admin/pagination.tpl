{if $data|count > 0 }
    <br>
{/if}

{if $pagination.first}
    <a href="{$pagination.PHPSelf|htmlspecialchars}?{$pagination.squeryString}">První</a>
{/if}

{foreach from=$data key=klic item=polozka name=i}
<a href="{$pagination.PHPSelf|htmlspecialchars}?{$pagination.squeryString}&page={$polozka}">

    {if $polozka == $pagination.page}
        <strong>{$polozka+1}</strong></a>
    {else}
        {$polozka+1}</a>
    {/if}
{/foreach}

{if $pagination.last}
    <a href="{$pagination.PHPSelf|htmlspecialchars}?{$pagination.squeryString}&page={$pagination.lastPage}">Poslední</a>
{/if}

{if $data|count > 0 }
    <br>
{/if}
