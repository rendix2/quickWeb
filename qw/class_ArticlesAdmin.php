<?php

class ArticlesAdmin extends Articles {
    private $articleCatsData, $articleCatsEditData, $articleCheckedCats;
    private $articlePagesData, $articlePagesEditData, $articleCheckedPages;
    private $articleId;

use Pagination;

    /**
     * @param db $db
     * @param Language $language
     * @param Smarty $smarty
     * @throws UsersException
     */
    public function __construct(db $db, Language $language, Smarty $smarty) {
        if ( DEBUG )
            cs($language, $this);

		if ( !$_SESSION['admin']['user_logged'] )
		throw new UsersException('Nejsi přihlášený!');

		if ( !$_SESSION['admin']['user_author'] )
		throw new UsersException('Nejsi admin!');

	    parent::__construct($db, $language, $smarty);
	
	    $this->articleCatsData = array();
	    $this->articlePagesData = array();
	    $this->articleCheckedCats = array();
	    $this->articleCheckedPages = array();
	    $this->articleCatsEditData = array();
	    $this->articlePagesEditData = array();
	}

    /**
     *
     */
    public function __destruct() {
            if (DEBUG )
                echo 'Destruktor: '.get_class($this).'<br>';

        parent::__destruct();

	    $this->articleData = null;
	    $this->articleCatsData = null;
	    $this->articlePagesData = null;
	    $this->articleCheckedCats = null;
	    $this->articleCheckedPages = null;
	    $this->articleCheckedPages = null;
	    $this->articleCatsEditData = null;
	}

	// versions begin
    /**
     * @return bool
     * @throws ArticlesException
     */
    private function articlesAddVersion() {
	    $this->db->query("INSERT INTO ".ARTICLES_VERSIONS_TABLE." (article_id, article_title, article_text, article_time, user_name, user_id)
            			VALUES (:article_id, :article_title, :article_text, :article_time, :user_name, :user_id);", __FILE__, __LINE__, array(

		        'article_id' => $_GET['article_id'],
		        'article_title' => $_POST['article_title'],
		        'article_text' => $_POST['article_text'],
		        'article_time' => time(),
		        'user_name' => $_SESSION['admin']['user_name'],
		        'user_id' => $_SESSION['admin']['user_id']));

        if ( $this->db->numRows() )
	        am('Verze přidána.', 'g');
        else
            throw new ArticlesException('Verzi se nepodařilo přidat.');

        $this->db->freeResult();
        return true;
	}

    /**
     * @return bool
     */
    private function articlesShowVersions() {
	    $this->db->query("SELECT version_id, article_id, article_title, article_time, user_id, user_name FROM ".ARTICLES_VERSIONS_TABLE." WHERE article_id = :article_id;", __FILE__, __LINE__, array(
            'article_id' => $_GET['article_id']));
		
		if ( !$this->db->numRows() )
		    p($this->smarty, 'Žádné verze.');
	
	    $this->smarty->assign('article_versions', $this->db->fetchAll());
	    $this->smarty->display('articleVersionsShow.tpl');
	    $this->db->freeResult();
        return true;
	}

    /**
     * @return bool
     */
    private function articlesShowVersion() {
	    $this->db->query("SELECT article_title, article_text FROM ".ARTICLES_VERSIONS_TABLE." WHERE version_id = :version_id LIMIT 1;", __FILE__, __LINE__, array( 'version_id' => $_GET['version_id']));
		
		if ( !$this->db->numRows() )
		    am('Žádné verze.');
		
	    $this->smarty->assign('article_versions', $this->db->fetch());
	    $this->smarty->display('articleVersionsShowOne.tpl');
	    $this->db->freeResult();
        return true;
	}

    /**
     * @throws ArticlesException
     */
    private function articlesRecoveryVersion() {
	    $this->db->query("SELECT article_title, article_text FROM ".ARTICLES_VERSIONS_TABLE." WHERE version_id = :version_id LIMIT 1;", __FILE__, __LINE__, array( 'version_id' => $_GET['version_id']));

        if ( !$this->db->numRows() )
            throw new ArticlesException('Neexistující verze.');

	    $this->articleData = $this->db->fetch();
	    $this->db->query("UPDATE ".ARTICLES_TABLE."
            			SET 	article_url = :article_url,
            				article_title = :article_title,
            				article_text = :article_text
            			WHERE article_id = :article_id
            			LIMIT 1;", __FILE__, __LINE__, array(

		'article_title' => $this->articleData['article_title'], 
		'article_text' => $this->articleData['article_text'],
		'article_url' => $this->articlesGetUrl($this->articleData['article_title']),
		'article_id' => $_GET['article_id'] ));

        if ( $this->db->numRows() )
	        am('Verze Obnovena.', 'g');
        else
            throw new ArticlesException('Nepodařilo se verzi obnovit');

        return true;
	}

    /**
     * @throws ArticlesException
     */
    private function articlesDeleteVersion() {
	    $this->db->query("DELETE FROM ".ARTICLES_VERSIONS_TABLE." WHERE version_id = :version_id LIMIT 1;", __FILE__, __LINE__, array( 'version_id' => $_GET['version_id'] ));

        if ( $this->db->numRows() )
	        am('Verze smazána.', 'g');
        else
            throw new ArticlesException('Verzi se nepodařilo smazat.');

        $this->db->freeResult();
	}	
	// versions end	

	// cats begin
    /**
     * @return bool
     */
    protected function articlesSelectCats() {
	    $this->db->query("SELECT cat_id, cat_name FROM ".CATS_TABLE." ORDER BY cat_name ASC;", __FILE__, __LINE__);
	    return $this->articleCatsData = $this->db->fetchAll();
	}

    /**
     * @return bool
     */
    protected function articlesSelectCatsEdit() {
	    $this->db->query("SELECT cat_id FROM ".ARTICLES_CATS_TABLE." WHERE article_id = :article_id ORDER BY cat_id ASC;", __FILE__, __LINE__, array( 'article_id' => $_GET['article_id']));
	    return $this->articleCatsEditData = $this->db->fetchAll();
	}

    /**
     * @param $article_id
     * @param $edit
     * @return bool
     * @throws ArticlesException
     */
    protected function articlesAdd2Cat($article_id, $edit) {
        if ( $edit == true) {
            $this->db->query("DELETE FROM " . ARTICLES_CATS_TABLE . " WHERE article_id = :article_id;", __FILE__, __LINE__, array('article_id' => $article_id));

            if (!$this->db->numRows())
                throw new ArticlesException('Článek se nepodařilo smazat z kategorií.');
        }

		foreach ( $_POST['cat'] as $k => $v )
			if ( isset($_POST['cat'][$k]) ) {
		    	$this->db->query("INSERT INTO ".ARTICLES_CATS_TABLE." (article_id, cat_id) VALUES(:article_id, :cat_id)", __FILE__, __LINE__, array ( 'article_id' => $article_id, 'cat_id' => $k ));

                if ( $this->db->numRows() )
			        am('Článek přidán do kategorie: <strong>'.$v.'</strong>.', 'g');
                else
                    am('Článek se nepodařilo přidat do kategorie: <strong>'.$v.'</strong>.', 'b');
			}

    	return true;
	}

    /**
     * @return array
     */
    protected function articlesGetCheckedCats() {
		foreach ( $this->articleCatsData as $v )
	    	$this->articleCheckedCats[$v['cat_id']] = '';
			
		foreach ( $_POST['cat'] as $k => $v )
			if ( isset($_POST['cat'][$k]) )
		    	$this->articleCheckedCats[$k] = 'checked="checked"';
	
	    return $this->articleCheckedCats;
	}

    /**
     * @return array
     */
    protected function articlesGetCheckedCatsEdit() {
	    $this->articlesSelectCatsEdit();
			
		foreach ( $this->articleCatsData as $v ) {
		    $this->articleCheckedCats[$v['cat_id']] = '';
		
			foreach ( $this->articleCatsEditData as $k => $v2 )
				if ( $v['cat_id'] == $v2['cat_id'] )
			    	$this->articleCheckedCats[$v['cat_id']] = 'checked="checked"';
		}

	    return $this->articleCheckedCats;
	}		
	// cats end
	
	// pages begin	

    /**
     * @return bool
     */
    private function articlesSelectPagesEdit() {
	    $this->db->query("SELECT page_id FROM ".ARTICLES_PAGES_TABLE." WHERE article_id = :article_id ORDER BY page_id ASC;", __FILE__, __LINE__, array( 'article_id' => $_GET['article_id']));
	    return $this->articlePagesEditData = $this->db->fetchAll();
	}

    /**
     * @return bool
     */
    private function articlesSelectPages() {
	    $this->db->query("SELECT page_id, page_name FROM ".PAGES_TABLE." WHERE page_static = 0 ORDER BY page_name ASC;", __FILE__, __LINE__);
	    return $this->articlePagesData = $this->db->fetchAll();
	}

    /**
     * @param $article_id
     * @param $edit
     * @return bool
     * @throws ArticlesException
     */
    private function articlesAdd2Page($article_id, $edit) {
        if ( $edit == true )
        {
            $this->db->query("DELETE FROM ".ARTICLES_PAGES_TABLE." WHERE article_id = :article_id;", __FILE__, __LINE__, array( 'article_id' => $article_id));

            if (!$this->db->numRows())
                throw new ArticlesException('Nepodařilo se smazat článek ze stránek.');
        }

		foreach ( $_POST['page'] as $k => $v )
			if ( isset($_POST['page'][$k]) ){
    			$this->db->query("INSERT INTO ".ARTICLES_PAGES_TABLE." (article_id, page_id) VALUES(:article_id, :page_id)", __FILE__, __LINE__, array ( 'article_id' => $article_id, 'page_id' => $k ));

                if ( $this->db->numRows() )
			        am('Článek přidán do stránky: <strong>'.$v.'</strong>.', 'g');
                else
                    am('Článek se nepodařilo přidat do stránky: <strong>'.$v.'</strong>.', 'b');
			}

	    return true;
	}

    /**
     * @return array
     */
    private function articlesGetCheckedPages() {
		foreach ( $this->articlePagesData as $v )
		    $this->articleCheckedPages[$v['page_id']] = '';

		foreach ( $_POST['page'] as $k => $v )
			if ( isset($_POST['page'][$k]) )
    			$this->articleCheckedPages[$k] = 'checked="checked"';

	    return $this->articleCheckedPages;
	}

    /**
     * @return array
     */
    private function articlesGetCheckedPagesEdit() {
	    $this->articlesSelectPagesEdit();
				
		foreach ( $this->articlePagesData as $v ){
	    	$this->articleCheckedPages[$v['page_id']] = '';

			foreach ( $this->articlePagesEditData as $k => $v2 )
				if ( $v['page_id'] == $v2['page_id'] )
    				$this->articleCheckedPages[$v['page_id']] = 'checked="checked"';
		}

	    return $this->articleCheckedPages;
	}			
	// pages end			

    /**
     * @param $article_title
     * @param $article_text
     * @return bool
     * @throws ArticlesException
     */
    protected function articlesCheckInput($article_title, $article_text) {
		if ( empty($article_title) )
    		throw new ArticlesException('Prázdný titulek článku.');

		if ( empty($article_text) )
	    	throw new ArticlesException('Prázdný text článku.');

		if ( mb_strlen($article_title) < ARTICLES_TITLE_MIN_LENGTH )
		    throw new ArticlesException('Titulek článku je krátký');

		if ( mb_strlen($article_title) > ARTICLES_TITLE_MAX_LENGTH )
		    throw new ArticlesException('Titulek článku je dlouhý');

		if ( !preg_match('#^[a-zA-Z0-9ěščřžýáíéťúůóďň \?\.\#\!\)\(]*$#', $article_title) )
		    throw new ArticlesException('Titulek článku obsahuje zakázané znaky.');

		if ( mb_strlen(trim(strip_tags($article_text))) < ARTICLES_TEXT_MIN_LENGTH )
		    throw new ArticlesException('Text článku je krátký');

		if ( mb_strlen(trim(strip_tags($article_text))) > ARTICLES_TEXT_MAX_LENGTH )
		    throw new ArticlesException('Text článku je dlouhý');

		if ( $article_title == 'Zde napište nadpis článku...' )
		    throw new ArticlesException('Výchozí hodnota nadpisu článku.');
		
		if ( $article_title == '' )
		    throw new ArticlesException('Výchozí hodnota nadpisu článku.');
		
		if ( $article_text == '' )
		    throw new ArticlesException('Výchozí hodnota textu článku.');
		
		if ( substr($article_title, 0, 31) == 'Zde napište nadpis článku...' )
		    throw new ArticlesException('Titulek obsahuje výchozí hodnotu.');

		if ( $article_text == 'Zde napište text článku...' )
		    throw new ArticlesException('Výchozí hodnota textu článku.');
				
		if ( substr($article_text, 0, 29) == 'Zde napište text článku...' )
		    throw new ArticlesException('Článek obsahuje výchozí hodnotu.');

	    return true;
	}

    /**
     * @return bool
     * @throws ArticlesException
     */
    public function articlesAdd() {
	    $this->smarty->assign('am', array(
            'article_title' => $_POST['article_title'],
		    'article_text' => $_POST['article_text'],
		    'article_comments_enable' => $_POST['article_comments_enable'] == 'on' ? 'checked="checked"' : ''));
		
	    $this->smarty->assign('cats', $this->articlesSelectCats());
	    $this->smarty->assign('cats_checked', $this->articlesGetCheckedCats());
	    $this->smarty->assign('pages', $this->articlesSelectpages());
	    $this->smarty->assign('pages_checked', $this->articlesGetCheckedPages());
	    $this->smarty->assign('load', 'articleManager.tpl');
        $this->smarty->assign('lang', $this->language->languageGetPack());

        $sf = new SafeForm($this->smarty,'articleManager.tpl');

		if ( isset($_POST['submit']) ) {
			if ( !$this->articlesCheckInput($_POST['article_title'], $_POST['article_text']) )
	    		return false;

            try {
                $sf->doChecks();
            }
            catch ( FormException $e ) {
                throw new ArticlesException($e->getMessage());
            }

		    $this->db->query("INSERT INTO ".ARTICLES_TABLE." (article_active, article_number, user_id, user_name, article_url, article_title, article_text, article_time, article_comments_enable)
        				VALUES(:article_active, :article_number, :user_id, :user_name, :article_url, :article_title, :article_text, :article_time, :article_comments_enable);", __FILE__, __LINE__, array (

		    		'article_active' => isset($_POST['article_active']),
			        'article_number' => $this->articlesGetNumber(),
			        'user_id' => $_SESSION['admin']['user_id'],
			        'user_name' => $_SESSION['admin']['user_name'],
			        'article_url' => $this->articlesGetUrl($_POST['article_title']),
			        'article_title' => $_POST['article_title'],
			        'article_text' => $_POST['article_text'],
			        'article_time' => time(),
			        'article_comments_enable' => isset($_POST['article_comments_enable'])));

            if ( $this->db->numRows() )
                am('Článek <strong>'.$_POST['article_title'].'</strong> přidán!', 'g');
            else
                throw new ArticlesException('Článek se nepodařilo přidat');
			
		$this->articleId = $this->db->lastId();			
		$this->articlesAdd2Cat($this->articleId, false);
		$this->articlesAdd2Page($this->articleId);		
		$this->db->query("UPDATE ".USERS_TABLE." SET user_articles_count = user_articles_count + 1 WHERE user_id = :user_id;", __FILE__, __LINE__, array ( 'user_id' => $_SESSION['admin']['user_id'] ));

            if ( !$this->db->numRows() )
                throw new ArticlesException('Nepodařilo se přičíst tento článek k počtu článků uživatele.');

		    Red::redirect('./admin.php');
		    return true;
		}
	}

    /**
     * @return bool
     * @throws ArticlesException
     */
    public function articlesEdit() {
		switch ( $_GET['version'] ) {
			case 'delete':
	    		$this->articlesDeleteVersion();
			break;
			case 'show_one': 
		    	$this->articlesShowVersion();
			break;
			case 'recovery':
			    $this->articlesRecoveryVersion();
			break;
			case 'show':
			default:
			    $this->articlesShowVersions();
			break;
		}
	
	    $this->db->query("SELECT article_title, article_text, article_comments_enable FROM ".ARTICLES_TABLE." WHERE article_id = :article_id;",__FILE__, __LINE__, array ( 'article_id' => $_GET['article_id'] ));

        if ( !$this->db->numRows() )
            throw new ArticlesException('Nenalezený článek.');

	    $this->articleData = $this->db->fetch();
	    $this->smarty->assign('am', array (
    		'article_title' => $this->articleData['article_title'],
		    'article_text' => $this->articleData['article_text'],
		    'article_comments_enable' => $this->articleData['article_comments_enable'] ? 'checked="checked"' : ''));
		
	    $this->smarty->assign('cats', $this->articlesSelectCats());
	    $this->smarty->assign('pages', $this->articlesSelectPages());
	    $this->smarty->assign('cats_checked', $this->articlesGetCheckedCatsEdit());
	    $this->smarty->assign('pages_checked',$this->articlesGetCheckedPagesEdit());
	    $this->smarty->assign('load','articleManager.tpl');
        $this->smarty->assign('lang', $this->language->languageGetPack());

		if ( isset($_POST['submit']) ) {
			if ( !$this->articlesCheckInput($_POST['article_title'], $_POST['article_text']) )
			    return false;

		    $this->db->query("UPDATE ".ARTICLES_TABLE."
			                	SET 	article_url = :article_url,
					                    article_title = :article_title,
					                    article_text = :article_text,
					                    article_edit_count = article_edit_count + 1,
					                    article_last_edit = :article_last_edit,
					                    article_comments_enable = :article_comments_enable
				            WHERE article_id = :article_id;", __FILE__, __LINE__, array (

			'article_url' => $this->articlesGetUrl($_POST['article_title']),
			'article_title' => $_POST['article_title'],
			'article_text' => $_POST['article_text'],
			'article_last_edit' => time(),
			'article_comments_enable' => $_POST['article_comments_enable'] == 'on' ? 1 : 0 ));

            if ( $this->db->numRows() )
                am('Článek <strong>'.$_POST['article_title'].'</strong> úspěšně editován.', 'g');
            else
                throw new ArticlesException('Nepodařilo se editovat článek.');

		$this->articlesAdd2Cat($_GET['article_id'], true);
		$this->articlesAdd2Page($_GET['article_id']);
		$this->articlesAddVersion();

		Red::redirect('./admin.php');
		return true;
		}
	}

    /**
     * @return mixed
     * @throws ArticlesException
     */
    protected function articlesGetNumber() {
	    $this->db->query("SELECT MAX(article_number) as max_number FROM ".ARTICLES_TABLE.";", __FILE__, __LINE__);

        if ( !$this->db->numRows() )
            throw new ArticlesException('Nepodařilo se získat počet článků');

	    return $this->db->fetch()['max_number'] + 1;
	}

    /**
     * @param $title
     * @return mixed|string
     */
    protected function articlesGetUrl($title) {
	    static $replace = array(
	'ä'=>'a', 'Ä'=>'A', 'á'=>'a', 'Á'=>'A', 'à'=>'a', 'À'=>'A', 'ã'=>'a', 'Ã'=>'A', 'â'=>'a',  'Â'=>'A', 'č'=>'c', 'Č'=>'C', 'ć'=>'c', 'Ć'=>'C', 'ď'=>'d',
	'Ď'=>'D', 'ě'=>'e', 'Ě'=>'E', 'é'=>'e', 'É'=>'E', 'ë'=>'e', 'Ë'=>'E', 'è'=>'e', 'È'=>'E', 'ê'=>'e', 'Ê'=>'E', 'í'=>'i', 'Í'=>'I', 'ï'=>'i', 'Ï'=>'I',
	'ì'=>'i', 'Ì'=>'I', 'î'=>'i', 'Î'=>'I', 'ľ'=>'l', 'Ľ'=>'L', 'ĺ'=>'l', 'Ĺ'=>'L', 'ń'=>'n', 'Ń'=>'N', 'ň'=>'n', 'Ň'=>'N', 'ñ'=>'n', 'Ñ'=>'N', 'ó'=>'o',
	'Ó'=>'O', 'ö'=>'o', 'Ö'=>'O', 'ô'=>'o', 'Ô'=>'O', 'ò'=>'o', 'Ò'=>'O', 'õ'=>'o', 'Õ'=>'O', 'ő'=>'o', 'Ő'=>'O', 'ř'=>'r', 'Ř'=>'R', 'ŕ'=>'r', 'Ŕ'=>'R',
	'š'=>'s', 'Š'=>'S', 'ś'=>'s', 'Ś'=>'S', 'ť'=>'t', 'Ť'=>'T', 'ú'=>'u', 'Ú'=>'U', 'ů'=>'u', 'Ů'=>'U', 'ü'=>'u', 'Ü'=>'U', 'ù'=>'u', 'Ù'=>'U', 'ũ'=>'u',
	'Ũ'=>'U', 'û'=>'u', 'Û'=>'U', 'ý'=>'y', 'Ý'=>'Y', 'ž'=>'z', 'Ž'=>'Z', 'ź'=>'z', 'Ź'=>'Z');
 
	    $title = strtr($title, $replace);
	    $title = mb_strtolower($title);
	    $title = preg_replace("/[^[:alpha:][:digit:]]/", "-", $title);
	    $title = trim ($title, "-");
	    $title = preg_replace ("/[-]+/", "-", $title);

	    return $title;
	}

    /**
     * @param $article_id
     * @return bool
     * @throws ArticlesException
     */
    protected function articlesDelete($article_id) {
	    $this->db->query("SELECT article_title FROM ".ARTICLES_TABLE." WHERE article_id = :article_id;", __FILE__, __LINE__, array( 'article_id' => $article_id ));

		if ( !$this->db->numRows() )
		    throw new ArticlesException('Nenalezený článek s ID:'.$article_id);
		
	    $this->articleData = $this->db->fetch();
	    $this->db->freeResult();
	    $this->db->query("DELETE FROM ".ARTICLES_TABLE." WHERE article_id = :article_id", __FILE__, __LINE__, array ( 'article_id' => $article_id ));
    	$this->db->freeResult();
    	$this->db->query("DELETE FROM ".ARTICLES_CATS_TABLE." WHERE article_id = :article_id;", __FILE__, __LINE__, array ( 'article_id' => $article_id ));
    	$this->db->freeResult();
    	$this->db->query("DELETE FROM ".ARTICLES_COMMENTS_TABLE." WHERE article_id = :article_id;", __FILE__, __LINE__, array ( 'article_id' => $article_id ));
    	$this->db->freeResult();
    	$this->db->query("DELETE FROM ".ARTICLES_EDIT_TABLE." WHERE article_id = :article_id;", __FILE__, __LINE__, array ( 'article_id' => $article_id ));
    	$this->db->freeResult();
    	$this->db->query("DELETE FROM ".ARTICLES_PAGES_TABLE." WHERE article_id = :article_id", __FILE__, __LINE__, array ( 'article_id' => $article_id ));
    	$this->db->freeResult();
    	$this->db->query("DELETE FROM ".ARTICLES_READINGS_TABLE." WHERE article_id = :article_id", __FILE__, __LINE__, array ( 'article_id' => $article_id ));
    	$this->db->freeResult();
    	$this->db->query("DELETE FROM ".ARTICLES_VERSIONS_TABLE." WHERE article_id = :article_id", __FILE__, __LINE__, array ( 'article_id' => $article_id ));
    	$this->db->freeResult();
    	$this->db->query("UPDATE ".ARTICLES_TABLE." SET article_number = article_number - 1 WHERE article_id >= :article_id;", __FILE__, __LINE__, array ( 'article_id' => $article_id ));
    	$this->db->freeResult();

	    am('Článek <strong>'.$this->articleData['article_title'].'</strong> smazán!', 'g');
    //	Red::redirect('./admin.php');
    	return true;
	}

    /**
     * @param $article_id
     * @param $article_active
     * @return bool
     */
    private function setActive($article_id, $article_active) {
		switch ( $article_active ) {
			case 1:
			case 0:
			$this->db->query("UPDATE ".ARTICLES_TABLE." SET article_active = :article_active WHERE article_id = :article_id;", __FILE__, __LINE__, array ( 
			
			'article_id' => $article_id, 
			'article_active' => $article_active ));
			am('Článek přenastaven.', 'g');			
			Red::redirect('./admin.php?akce=ma&ma=show_articles');
			default:
			return false;
		}
	}

    /**
     * @throws ArticlesException
     */
    public function articlesShow() {
		if ( $_GET['set_active'] == 1 )
	    	$this->setActive($_GET['article_id'], $_GET['active']);

		if ( $_GET['delete_article'] == 1 ) 
    		$this->articlesDelete($_GET['article_id']);

   	    $this->db->query("SELECT SQL_CALC_FOUND_ROWS article_active, article_id, user_id,user_name, article_title, article_text, article_time
	    	    		FROM ".ARTICLES_TABLE." a
        				ORDER BY article_id DESC
    				    LIMIT ".ARTICLES_PAGE_LIMIT."
	    			    OFFSET ".ARTICLES_PAGE_LIMIT * $_GET['page'].";", __LINE__, __FILE__);

        if ( !$this->db->numRows() )
            throw new ArticlesException('Žádné články');

        $this->smarty->assign('ar', $this->db->fetchAll());
        $this->pagination(ARTICLES_PAGE_LIMIT);
        $this->db->freeResult();
        $this->db->query("SELECT COUNT(*) as active FROM ".ARTICLES_TABLE." WHERE article_active = 1;", __FILE__, __LINE__);
        $this->smarty->assign('active', $this->db->fetch()['active']);
        $this->db->freeResult();
        $this->db->query("SELECT COUNT(*) as non_active FROM ".ARTICLES_TABLE." WHERE article_active = 0;", __FILE__, __LINE__);
        $this->smarty->assign('non_active', $this->db->fetch()['non_active']);
        $this->db->freeResult();

	    $this->smarty->assign('load','articleShow.tpl');
	}

    /**
     * @throws ArticlesException
     */
    public function articlesSettings() {
    	$this->db->query("SELECT show_author, show_time, show_comments, show_edits FROM ".ARTICLES_SETTINGS_TABLE." LIMIT 1;", __FILE__, __LINE__);

        if ( !$this->db->numRows() )
            throw new ArticlesException('Není možno nastavovat články');

    	$this->smarty->assign('ar', $this->db->fetch());
    	$this->smarty->assign('load', 'articlesSettings.tpl');
	
		if ( isset($_POST['submit']) ) {
    		$this->db->query("UPDATE ".ARTICLES_SETTINGS_TABLE." SET show_author = :show_author, show_time = :show_time, show_comments = :show_comments, show_edits = :show_edits LIMIT 1;", __FILE__, __LINE__, array(
			'show_author' => $_POST['show_author'] == 'on' ? 1 : 0,
			'show_time' => $_POST['show_time'] == 'on' ? 1 : 0,
			'show_comments' => $_POST['show_comments'] == 'on' ? 1 : 0,
			'show_edits' => $_POST['show_edits'] == 'on' ? 1 : 0));

            if ( $this->db->numRows() )
                am('Nastavení článků bylo aktualizováno');
            else
                throw new ArticlesException('Nastavení článků se nepodařilo změnit');
		}
	}	
}
