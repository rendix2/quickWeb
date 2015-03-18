<h1>{$user.user_name}</h1>

<ul>
<li>
{if ( $user.user_active )}
    {$lang.users_active}
{else}
    {$lang.users_nonactive}
{/if}

</li>
<li>
{if ( $user.user_author )}
    {$lang.users_author}
{else}
    {$lang.users_user}
{/if}
</li>
<li>
{$lang.users_registered} {$user.user_regdate|date_format:"%d.%m.%Y %H:%M:%S"}
</li>
<li>
{if $user.user_last_login}
     {$lang.users_last_login} {$user.user_last_login|date_format:"%d.%m.%Y %H:%M:%S"}
{else}
    {$lang.users_user_never_logged_in}
{/if}
</li>
</ul>
<br>

<h2>{$lang.users_articles}</h2>
{foreach from=$article key=klic item=polozka name=i}
{$polozka.article_time|date_format:"%d.%m.%Y %H:%M:%S"}:<br>{$polozka.article_text}<br><br>
{foreachelse}
{$lang.users_no_articles}
{/foreach}

<h2>{$lang.users_comments}</h2>
{foreach from=$comment key=klic item=polozka name=i}
{$polozka.comment_time|date_format:"%d.%m.%Y %H:%M:%S"}:<br>{$polozka.comment_text}<br><br>
{foreachelse}
{$lang.users_no_comments}
{/foreach}
