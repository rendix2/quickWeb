<?php
$start = microtime(true);
include('autoloader.php');

try {
    $language = new LanguageAdmin($_SERVER['REDIRECT_GEOIP_COUNTRY_CODE']);
}
catch ( LanguageException $e )
{
    die($e->getMessage());
}

$smarty = new TemplateAdmin();
$db = new db('server', 'name', 'pass', 'db', $smarty);
$admin = new Admin($db, $language, $smarty);

//$smarty->display('pageHeader.tpl');
my_page_header($smarty, array('user_name' => $_SESSION['user']['user_name']), $language->LanguageGetPack(), $language->languageGetMetaPack(), true);
sm($smarty);

	if ( ( !count($_SESSION['admin']) && $_SESSION['user']['user_logged'] ) || !$_SESSION['admin']['user_logged'] )
		try {
		    $admin->userLogin();
		}
		catch ( UsersException $e ) {
		    am($ $e->getMessage());
            $e = null;
		}
	else {
	    $smarty->assign('user_name', $_SESSION['admin']['user_name']);

		if ( !$_SESSION['admin']['user_author'] ) {
    		p('Nejsi admin!');
    		my_page_footer($smarty, array( 'pg_query' => $db->getQueryCount(), 'used_ram' => usedRAM() ), $language->languageGetPack());
		}
		else 
		{
			switch ( $_GET['akce'] ) {
				case '': {
					try{
                        $ah = new ArticlesAdminHome($db, $language, $smarty);
                        $ah->articlesShow();
                        $ah->articlesAdd();

                        $ph = new PagesAdminHome($db, $smarty);
					    $ph->pagesShow();
					}
					catch ( ArticlesException $e ) {
                        am($e->getMessage());
                        $e = null;
                    }
                    catch ( PagesException $e ){
                        am($e->getMessage());
                        $e = null;
                    }
                    finally {
                        $ah = null;
                        $ph = null;
                    }

				    $smarty->assign('load','adminHome.tpl');
				}
				break;
				case 'ma': {
					try {
                        $articles = new ArticlesAdmin($db, $language, $smarty);

						switch ( $_GET['ma'] ) {
							case 'add_article':
					    		$articles->articlesAdd();
							break;
							case 'show_articles':
						    	$articles->articlesShow();
							break;
							case 'edit_article':
							    $articles->articlesEdit();
							break;
							case 'settings_article':
							    $articles->articlesSettings();
							break;
							default:
							    echo 'Chyba';
						}
					}
					catch ( ArticlesException $e ) {
					    am($e->getMessage());
                        $e = null;
					}
                    finally {
                        $articles = null;
                    }
				}
				break;
				case 'mu':
					try {					
						switch ( $_GET['mu'] ) {
							case 'show_users':
						    	$admin->adminShowUsers();
							break;
    						case 'add_user':
    							$admin->usersRegister();
							break;					
							default:
	    						echo 'Chyba';
						}
					}
					catch ( UsersException $e ) {
					    am($e->getMessage());
                        $e = null;
					}
				break;
				case 'logout':
				    $admin->userLogout();
				break;
				case 'mc': {
					try {
                        $cats = new CategoriesAdmin($db, $smarty);

						switch ( $_GET['mc'] ) {
						case 'show_cat':
		    				$cats->categoriesShow();
						break;
						case 'add_cat':
			    			$cats->categoriesAdd();
						break;
						case 'edit_cat':
				    		$cats->categoriesEdit();
						break;
						case 'delete_cat':
					    	$cats->categoriesDelete();
						break;
						default:
						    echo 'Chyba';
						}
					}
					catch ( CategoriesException $e ) {
    					am($e->getMessage());
                        $e = null;
					}
                    finally {
                        $cats = null;
                    }
				}
				break;
				case 'mp': {
    				$pages = new PagesAdmin($db, $smarty);
				
					try {
						switch ( $_GET['mp'] ) {
						case 'show_pages':
						    $pages->pagesShow();
						break;
						case 'add_pages':
						    $pages->pagesAdd();
						break;
						case 'edit_page':
						    $pages->pagesEdit();
						break;
						case 'delete_page':
						    $pages->pagesDelete($_GET['page_id']);
						break;
						default:
						    echo 'chyba';
						}
					}
					catch ( PagesException $e ) {
					    am($e->getMessage());
                        $e = null;
					}
                    finally {
                        $pages = null;
                    }
				}
			}
		}

	$smarty->display('pageMiddle.tpl');
	}

$dbqc = $db->getQueryCount();
$lp = $language->languageGetPack();

$admin = null;
$db = null;
$language = null;

my_page_footer($smarty, array( 'pg_query' => $dbqc, 'used_ram' => usedRAM(), 'generation_time' => microtime(true) - $start ), $lp);
$dbqc = null;
$lp = null;
$smarty = null;

?>
