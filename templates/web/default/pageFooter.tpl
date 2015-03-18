{if $s_ulogged && s_uauthor}
<div style="font-size:12px" align="center"><a href="./admin.php">{$lang.administration}</a></div>
{/if}
{if $DEBUG == 1}
<div style="font-size:12px" align="center">
    {$lang.query_count_db|sprintf:$pf.pg_query}<br>
    {$lang.higgest_ram|sprintf:$pf.used_ram[0] : $pf.used_ram[1] : $pf.used_ram[2]}<br>
    {$lang.generation_page_time|sprintf:$pf.generation_time}<br>
    {$lang.php_version}<strong>{$php_version}</strong></div>
{/if}
</body>
</html>
