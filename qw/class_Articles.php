<?php

//namespace qw;

/**
 * Class Articles
 */
class Articles {
    /**
     * @var db
     */
    /**
     * @var db|Smarty
     */
    /**
     * @var array|db|Smarty
     */
    /**
     * @var array|db|Language|Smarty
     */
    protected $db, $smarty, $articleData, $language;

    use Pagination;

    /**
     * @param db $db
     * @param Language $language
     * @param Smarty $smarty
     */
    public function __construct(db $db, Language $language, Smarty $smarty) {
        if ( DEBUG )
           echo cs($language, $this);

    	$this->db = $db;
    	$this->smarty = $smarty;
    	$this->articleData = array();
        $this->language = $language;
	}

    /**
     *
     */
    public function __destruct() {
        if ( DEBUG )
            echo 'Destruktor: '.get_class($this).'<br>';

        $this->db = null;
        $this->smarty = null;
        $this->language = null;
    }


    /**
     * @return bool
     * @throws ArticlesException
     */
    public function articleShow() {
		if ( $_GET['delete_article'] == 1 )
		    $this->articlesDelete();

	    $this->db->query("INSERT IGNORE INTO ".ARTICLES_READINGS_TABLE." (article_id, user_ip, user_time, user_date, user_browser)
		            	VALUES (:article_id, :ip_adress, :time, NOW(), :browser_hash);", __LINE__, __FILE__, array(

			    'article_id' => $_GET['article_id'],
			    'ip_adress' => ip2long($_SERVER['REMOTE_ADDR']),
			    'time' => time(),
			    'browser_hash' => md5($_SERVER['HTTP_USER_AGENT']) ));

      //  if ( !$this->db->numRows() )
        //    throw new ArticlesException('Nepodařilo se přidat přečtení.');

	    $query = "SELECT ";
	    $query .= ( $_SESSION['user']['user_show_article_readings'] == SHOW_ARTICLE_PAGE || $_SESSION['user']['user_show_article_readings'] == SHOW_ALL_PAGE ) ? "( SELECT COUNT(ar.article_id) FROM ".ARTICLES_READINGS_TABLE." ar WHERE ar.article_id = a.article_id ) as count_readings, " : "";
	    $query .= ( $_SESSION['user']['user_show_comments_count'] == SHOW_ARTICLE_PAGE || $_SESSION['user']['user_show_comments_count'] == SHOW_ALL_PAGE ) ? "( SELECT COUNT(ac.comment_id) FROM ".ARTICLES_COMMENTS_TABLE." ac WHERE ac.article_id = a.article_id ) as count_comments, " : "";
	    $query .= "a.article_id, a.article_url, ";
	    $query .= ( $_SESSION['user']['user_show_article_author'] == SHOW_ARTICLE_PAGE || $_SESSION['user']['user_show_article_author'] == SHOW_ALL_PAGE ) ? "a.user_id, a.user_name, " : "";
	    $query .= "a.article_title, a.article_text, ";
	    $query .= ( $_SESSION['user']['user_show_article_time'] == SHOW_ARTICLE_PAGE || $_SESSION['user']['user_show_article_time'] == SHOW_ALL_PAGE ) ? "a.article_time, " : "";
	    $query .= ( $_SESSION['user']['user_show_article_edited'] == SHOW_ARTICLE_PAGE || $_SESSION['user']['user_show_article_edited'] == SHOW_ALL_PAGE) ? "a.article_edit_count, a.article_last_edit, " : "";
	    $query .= "a.article_comments_enable

			FROM ".ARTICLES_TABLE." a
			FORCE INDEX(PRIMARY)
			WHERE a.article_id = :article_id
			AND a.article_active = 1
			ORDER BY a.article_id DESC
			LIMIT 1;";

	    $this->db->query($query, __LINE__, __FILE__, array ( 'article_id' => $_GET['article_id'] ));

		if ( !$this->db->numRows() )
    		throw new ArticlesException('Žádný článek');

    	$this->articleData = $this->db->fetchAll();
    	$this->db->freeResult();
    	$this->smarty->assign('s_uid', $_SESSION['user']['user_id']);
    	$this->smarty->assign('s_ulogged', $_SESSION['user']['user_logged']);
    	$this->smarty->assign('ar', $this->articleData);
        $this->smarty->assign('lang', $this->language->languageGetPack());
    	$this->smarty->display('articleShow.tpl');

    	$comments = new Comments($this->db, $this->language, $this->smarty, $this->articleData[0]['article_comments_enable'],$this->articleData[0]['article_id']);

		try {
	    	$comments->commentsAdd();
		}
		catch ( CommentsException $e ) {
		    echo $e->getMessage();
		}

		try {
		    $comments->commentsShow();
		}
		catch ( CommentsException $e ) {
		    echo $e->getMessage();
		}

    	$comments = null;
    	unset($comments);
        return true;

	}

    /**
     * @return bool
     * @throws ArticlesException
     */
    public function articlesShow() {
	    $query = "SELECT SQL_CALC_FOUND_ROWS ";
	    $query .= ( $_SESSION['user']['user_show_article_readings'] == SHOW_MAIN_PAGE || $_SESSION['user']['user_show_article_readings'] == SHOW_ALL_PAGE ) ? "( SELECT COUNT(ar.article_id) FROM articles_readings ar WHERE ar.article_id = a.article_id ) as count_readings, " : "";
	    $query .= ( $_SESSION['user']['user_show_comments_count'] == SHOW_MAIN_PAGE || $_SESSION['user']['user_show_comments_count'] == SHOW_ALL_PAGE ) ? "( SELECT COUNT(ac.comment_id) FROM ".ARTICLES_COMMENTS_TABLE." ac WHERE ac.article_id = a.article_id ) as count_comments, " : "";
	    $query .= "a.article_id, a.article_url, ";
	    $query .= ( $_SESSION['user']['user_show_article_author'] == SHOW_MAIN_PAGE || $_SESSION['user']['user_show_article_author'] == SHOW_ALL_PAGE ) ? "a.user_id, a.user_name, " : "";
	    $query .= "a.article_title, a.article_text ";
	    $query .= ( $_SESSION['user']['user_show_article_time'] == SHOW_MAIN_PAGE || $_SESSION['user']['user_show_article_time'] == SHOW_ALL_PAGE ) ? "a.article_time, " : "";
	    $query .= ( $_SESSION['user']['user_show_article_edited'] == SHOW_MAIN_PAGE || $_SESSION['user']['user_show_article_edited'] == SHOW_ALL_PAGE) ? "a.article_edit_count, a.article_last_edit " : "";
	    $query .= "FROM ".ARTICLES_TABLE." a
			FORCE INDEX(PRIMARY)
			WHERE a.article_active = 1
			ORDER BY a.article_id DESC
			LIMIT ".ARTICLES_PAGE_LIMIT."
			OFFSET ".ARTICLES_PAGE_LIMIT * $_GET['page'].";";

    	$this->db->query($query, __LINE__, __FILE__);
	
		if ( !$this->db->numRows() )
	    	throw new ArticlesException('Žádné aktivní články');
		
	    $this->smarty->assign('s_uid', $_SESSION['user']['user_id']);
	    $this->smarty->assign('s_ulogged', $_SESSION['user']['user_logged']);
	    $this->smarty->assign('ar', $this->db->fetchAll());
        $this->smarty->assign('lang', $this->language->languageGetPack());
	    $this->db->freeResult();
    	$this->pagination(ARTICLES_PAGE_LIMIT);
    	$this->smarty->display('articleShow.tpl');

        return true;
	}

    /**
     * @return bool
     * @throws ArticlesException
     */
    public function articlesCatShow() {
	    $query = "SELECT SQL_CALC_FOUND_ROWS ";
	    $query .= ( $_SESSION['user']['user_show_article_readings'] == SHOW_MAIN_PAGE || $_SESSION['user']['user_show_article_readings'] == SHOW_ALL_PAGE ) ? "( SELECT COUNT(ar.article_id) FROM articles_readings ar WHERE ar.article_id = a.article_id ) as count_readings, " : "";
	    $query .= ( $_SESSION['user']['user_show_comments_count'] == SHOW_MAIN_PAGE || $_SESSION['user']['user_show_comments_count'] == SHOW_ALL_PAGE ) ? "( SELECT COUNT(ac.comment_id) FROM ".ARTICLES_COMMENTS_TABLE." ac WHERE ac.article_id = a.article_id ) as count_comments, " : "";
	    $query .= "a.article_id, a.article_url, ";
	    $query .= ( $_SESSION['user']['user_show_article_author'] == SHOW_MAIN_PAGE || $_SESSION['user']['user_show_article_author'] == SHOW_ALL_PAGE ) ? "a.user_id, a.user_name, " : "";
	    $query .= "a.article_title, a.article_text ";
	    $query .= ( $_SESSION['user']['user_show_article_time'] == SHOW_MAIN_PAGE || $_SESSION['user']['user_show_article_time'] == SHOW_ALL_PAGE ) ? "a.article_time, " : "";
	    $query .= ( $_SESSION['user']['user_show_article_edited'] == SHOW_MAIN_PAGE || $_SESSION['user']['user_show_article_edited'] == SHOW_ALL_PAGE) ? "a.article_edit_count, a.article_last_edit " : "";
	    $query .= "FROM ".ARTICLES_TABLE." a
			FORCE INDEX(PRIMARY)
			JOIN ".ARTICLES_CATS_TABLE." c
			ON c.cat_id = :cat_id
			AND a.article_id = c.article_id
			WHERE a.article_active = 1
			ORDER BY a.article_id DESC
			LIMIT ".ARTICLES_PAGE_LIMIT."
			OFFSET ".ARTICLES_PAGE_LIMIT * $_GET['page'];
			
	    $this->db->query($query, __LINE__, __FILE__, array( 'cat_id' => $_GET['cat_id'] ));

		if ( !$this->db->numRows() )
	    	throw new ArticlesException('Žádné aktivní články');

	    $this->smarty->assign('s_uid', $_SESSION['user']['user_id']);
	    $this->smarty->assign('s_ulogged', $_SESSION['user']['user_logged']);
	    $this->smarty->assign('ar', $this->db->fetchAll());
        $this->smarty->assign('lang', $this->language->languageGetPack());
	    $this->db->freeResult();
	    $this->pagination(ARTICLES_PAGE_LIMIT);
	    $this->smarty->display('articleShow.tpl');

        return true;
	}
}