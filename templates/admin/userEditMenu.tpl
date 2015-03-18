<a class="ajax_link" href="./admin.php?akce=mu&amp;mu=changePass&amp;user_id={$polozka.user_id}">Změna hesla</a>
<a href="./admin.php?akce=mu&amp;mu=changeMail&amp;user_id={$polozka.user_id}">Změna E-mailu</a>
<a href="./admin.php?akce=mu&amp;mu=deleteUser&amp;user_id={$polozka.user_id}">Smazat účet</a>
<a href="./admin.php?akce=mu&amp;mu=deactivateUser&amp;user_id={$polozka.user_id}">Deaktitovat účet</a>
<a href="./admin.php?akce=mu&amp;mu=logoutUser&amp;user_id={$polozka.user_id}">Odhlásit ze všech zařízení</a>

{if $polozka.user_author == 1}
<a href="./admin.php?akce=mu&amp;mu=set_user_author&amp;user_author=0&amp;user_id={$polozka.user_id}">Nastavit jako uživatel</a>
{else}
<a href="./admin.php?akce=mu&amp;mu=set_user_author&amp;user_author=1&amp;user_id={$polozka.user_id}">Nastavit jako admin</a>
{/if}
