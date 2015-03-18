<?php

/*
 * Author PHP of code: Tomas Babicky
 * Graphic designer: Roman Liberda
 *
 * Testers: Petra Homzová
 *
 *
 *
 */

$start = microtime(true);
include('autoloader.php');

$smarty = new TemplateUsers($_SESSION['user']['template_name']);
$db = new db('server', 'name', 'pass', 'db', $smarty);
$language = new Language($_SERVER['REDIRECT_GEOIP_COUNTRY_CODE']);
$users = new Users($db, $language, $smarty);

my_page_header($smarty, array('user_name' => $_SESSION['user']['user_name']), $language->LanguageGetPack(), $language->languageGetMetaPack());
// page starts there

sm($smarty);

try {
	switch ( $_GET['akce'] ) {
		case '': {
            $articles = new Articles($db, $language, $smarty);

            if (!$_GET['article_id'] && !$_GET['cat_id'])
                $articles->articlesShow();
            else if ($_GET['article_id'] && !$_GET['cat_id'])
                $articles->articleShow();
            else
                $articles->articlesCatShow();

            $articles = null;
		}
		break;
		case 'login':
		    $users->usersLogin();
		break;
		case 'register':
		    $users->usersRegister();
		break;
		case 'logout':
    		$users->usersLogout();
		break;
		case 'memberlist':
    		$users->usersMemberList();
		break;
		case 'activate':
	    	$users->usersActivate();
		break;
		case 'show_user':
		    $users->usersProfileShow();
		break;
		case 'cats': {
		    $cats = new Categories($db, $language, $smarty);
		    $cats->categoriesShow();
		    $cats = null;
		}
		break;
		case 'search': {
		    $s = new Search($db, $language, $smarty);
		    $s->searchShowForm();
		    $s = null;
		}
		break;
		default:
		echo 'Nesprávný parametr.';
	}
}
catch ( Exception $e ) {
echo $e->getMessage();
    $e = null;
}
finally {
    $dbqc = $db->getQueryCount();
    $lp = $language->languageGetPack();

    $users = null;
    $db = null;
    $language = null;

    my_page_footer($smarty, array( 'pg_query' => $dbqc, 'used_ram' => usedRAM(), 'generation_time' => microtime(true) - $start ), $lp);

    $lp = null;
    $dbqc = null;
}
?>
