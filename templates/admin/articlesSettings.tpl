<form method="post" action="">
	<table align="center">
		<tr>
			<td>Zobrazit čas, kdy by článek publikován</td>
			<td>Ne&nbsp;<input type="radio" name="user_show_article_time" value="{$userArticlesSettings.noShow}" {$userArticlesSettings.user_show_article_time3}></td>
			<td>Ano, na hlavní stránce&nbsp;<input type="radio" name="user_show_article_time" value="{$userArticlesSettings.showMainPage}" {$userArticlesSettings.user_show_article_time2}></td>
			<td>Ano, na stránce článku&nbsp;<input type="radio" name="user_show_article_time" value="{$userArticlesSettings.showArticlePage}" {$userArticlesSettings.user_show_article_time3}></td>
			<td>Ano, na obou&nbsp;<input type="radio" name="user_show_article_time" value="{$userArticlesSettings.showAllPage}" {$userArticlesSettings.user_show_article_time4}></td>
		</tr>
		<tr>
			<td>Zobrazit počet přečtení článku</td>
			<td>Ne&nbsp;<input type="radio" name="user_show_article_readings" value="{$userArticlesSettings.noShow}" {$userArticlesSettings.user_show_article_readings1}></td>
			<td>Ano, na hlavní stránce&nbsp;<input type="radio" name="user_show_article_readings" value="{$userArticlesSettings.showMainPage}" {$userArticlesSettings.user_show_article_readings2}></td>
			<td>Ano, na stránce článku&nbsp;<input type="radio" name="user_show_article_readings" value="{$userArticlesSettings.showArticlePage}" {$userArticlesSettings.user_show_article_readings3}></td>
			<td>Ano, na obou&nbsp;<input type="radio" name="user_show_article_readings" value="{$userArticlesSettings.showAllPage}" {$userArticlesSettings.user_show_article_readings4}></td>
		<tr>
		</tr>
			<td>Zobrazit poslední editaci článku</td>
			<td>Ne&nbsp;<input type="radio" name="user_show_article_edited" value="{$userArticlesSettings.noShow}" {$userArticlesSettings.user_show_article_edited1}></td>
			<td>Ano, na hlavní stránce&nbsp;<input type="radio" name="user_show_article_edited" value="{$userArticlesSettings.showMainPage}" {$userArticlesSettings.user_show_article_edited2}></td>
			<td>Ano, na stránce článku&nbsp;<input type="radio" name="user_show_article_edited" value="{$userArticlesSettings.showArticlePage}" {$userArticlesSettings.user_show_article_edited3}></td>
			<td>Ano, na obou&nbsp;<input type="radio" name="user_show_article_edited" value="{$userArticlesSettings.showAllPage}" {$userArticlesSettings.user_show_article_edited4}></td>
		</tr>
		<tr>
			<td>Zobrazit odkaz "Nahoru"</td>
			<td>Ne&nbsp;<input type="radio" name="user_show_article_up" value="{$userArticlesSettings.noShow}" {$userArticlesSettings.user_show_article_up1}></td>
			<td>Ano, na hlavní stránce&nbsp;<input type="radio" name="user_show_article_up" value="{$userArticlesSettings.showMainPage}" {$userArticlesSettings.user_show_article_up2}></td>
			<td>Ano, na stránce článku&nbsp;<input type="radio" name="user_show_article_up" value="{$userArticlesSettings.showArticlePage}" {$userArticlesSettings.user_show_article_up3}></td>
			<td>Ano, na obou&nbsp;<input type="radio" name="user_show_article_up" value="{$userArticlesSettings.showAllPage}" {$userArticlesSettings.user_show_article_up4}></td>
		</tr>
		<tr>
			<td>Zobrazit kategorie, ve kterých je článek</td>
			<td>Ne&nbsp;<input type="radio" name="user_show_article_cats" value="{$userArticlesSettings.noShow}" {$userArticlesSettings.user_show_article_cats1}></td>
			<td>&nbsp;</td>
			<td>Ano, na stránce článku&nbsp;<input type="radio" name="user_show_article_cats" value="{$userArticlesSettings.showArticlePage}" {$userArticlesSettings.user_show_article_cats3}></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>Zobrazit autora článku</td>
			<td>Ne&nbsp;<input type="radio" name="user_show_article_author" value="{$userArticlesSettings.noShow}" {$userArticlesSettings.user_show_article_author1}></td>
			<td>Ano, na hlavní stránce&nbsp;<input type="radio" name="user_show_article_author" value="{$userArticlesSettings.showMainPage}" {$userArticlesSettings.user_show_article_author2}></td>
			<td>Ano, na stránce článku&nbsp;<input type="radio" name="user_show_article_author" value="{$userArticlesSettings.showArticlePage}" {$userArticlesSettings.user_show_article_author3}></td>
			<td>Ano, na obou&nbsp;<input type="radio" name="user_show_article_author" value="{$userArticlesSettings.showAllPage}" {$userArticlesSettings.user_show_article_author4}></td>
		</tr>
		<tr>
			<td colspan="5" align="center"><input type="submit" name="submit" value="Nastavit"></td>
		</tr>
	</table>
</form>
