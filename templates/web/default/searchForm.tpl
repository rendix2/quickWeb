<form method="post" action="">
<input type="hidden" value="{$hash}">

<input type="text" name="search_word" value="{$search_word}" autocomplete="off" autofocus="on"><br>
Články<input type="radio" name="type" value="ar"{$ar_checked}> Uživatelé<input type="radio" name="type" value="us"{$us_checked}> Stránky<input type="radio" name="type" value="pa"{$pa_checked}> Kategorie<input type="radio" name="type" value="ca"{$ca_checked}> 
<input type="submit" name="submit" value="Hledat!">
</form>
