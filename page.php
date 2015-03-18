<?php
$start = microtime(true);
include('autoloader.php');

$smarty = new TemplateUsers($_SESSION['user']['template_name']);
$db = new db('localhost', 'xpy', '19723698', 'cvutblog', $smarty);
$language = new Language($_SERVER['REDIRECT_GEOIP_COUNTRY_CODE']);

my_page_header($smarty, array('user_name' => $_SESSION['user']['user_name']), $language->LanguageGetPack(), $language->languageGetMetaPack());
// page starts there

$page = new Pages($db, $language, $smarty);

    try {
        if ($_GET['page_id'])
            $page->pagesShowOne();
        else
            $page->pagesShow();
    }
    catch ( PagesException $e ) {
        echo $e->getMessage();
    }
    finally {
        $dbqc = $db->getQueryCount();
        $lp = $language->languageGetPack();

        $page = null;
        $db = null;
        $language = null;

// page ends there
        my_page_footer($smarty, array( 'pg_query' => $dbqc, 'used_ram' => usedRAM(), 'generation_time' => microtime(true) - $start ), $lp);
    }

?>
