{foreach from=$cats key=klic item=polozka name=i}
<div align="center"><a href="./?cat_id={$polozka.cat_id}">{$polozka.cat_name}</a>({$polozka.articles_cats})</div>
{/foreach}
