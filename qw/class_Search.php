<?php

//namespace qw;

/**
 * Class Search
 */
class Search {
    /**
     * @var
     */
    private $word;
    private $db, $language, $smarty;

    /**
     * @param db $db
     * @param Smarty $smarty
     */
    public function __construct(db $db, Language $language,  Smarty $smarty) {
        if ( DEBUG )
            cs($language, $this);

	    $this->db = $db;
        $this->language = $language;
	    $this->smarty = $smarty;
	}

    public  function  __destruct(){
        if ( DEBUG )
            echo 'Destruktor: '.get_class($this).'<br>';
    }

    /**
     * @throws ArticlesException
     * @throws SearchException
     */
    public function searchShowForm() {
	    $this->smarty->assign('search_word', $_POST['search_word']);
	    $this->smarty->assign('ar_checked', $_POST['type'] == "ar" ? 'checked="checked"' : "");
	    $this->smarty->assign('us_checked', $_POST['type'] == "us" ? 'checked="checked"' : "");
	    $this->smarty->assign('pa_checked', $_POST['type'] == "pa" ? 'checked="checked"' : "");
	    $this->smarty->assign('ca_checked', $_POST['type'] == "ca" ? 'checked="checked"' : "");
	    $this->smarty->display('searchForm.tpl');

		if ( isset($_POST['submit']) ) {
			if ( !$this->searchCheckWord() )
			    return;

			switch ( $_POST['type'] ) {
				case 'ar':
					if ( !$this->searchArticlesFulltext() )
    					$this->searchArticlesLike();
				break;
				case 'us':
					if ( !$this->searchUsersFulltext() )
    					$this->searchUsersLike();
				break;
				case 'pa':
					if ( !$this->searchPagesFulltext() )
    					$this->searchPagesLike();
				break;
				case 'ca':
					if ( !$this->searchCategoriesFulltext() )
    					$this->searchcategoriesLike();
				break;
				default:
    				throw new SearchException('Nevybrán typ hledání!');
			}
		}
	}

    /**
     * @param Object $what
     */
    public function searchShowFormOne(Object $what) {
		switch ( $what ) {
			case ( $what instanceof Articles ) : {
			}
			break;
			case ( $what instanceof Users ) : {
			}
			break;
			case ( $what instanceof Categories ) : {
			}
			break;
			case ( $what instanceof Pages ) : {
			}
			break;
			default:
    			throw new SearchException('Nevybraný typ hledání.');
		}
	}

    /**
     * @return bool
     * @throws SearchException
     */
    private function searchCheckWord() {
		if ( empty($_POST['search_word']) )
		    throw new SearchException('Hledaný výraz je prázdný');

		if ( mb_strlen($_POST['search_word']) < SEARCH_WORD_MIN_LENGTH )
		    throw new SearchException('Hledaný výraz je příliš krátký');

		if ( mb_strlen($_POST['search_word']) > SEARCH_WORD_MAX_LENGTH )
		    throw new SearchException('Hledaný výraz je příliš dlouhý');

	    return true;
	}

    /**
     * @return bool
     * @throws ArticlesException
     */
    private function searchArticlesLike() {
	    $query = "SELECT SQL_CALC_FOUND_ROWS ";
	    $query .= ( $_SESSION['user']['user_show_article_readings'] == SHOW_MAIN_PAGE || $_SESSION['user']['user_show_article_readings'] == SHOW_ALL_PAGE ) ? "( SELECT COUNT(ar.article_id) FROM articles_readings ar WHERE ar.article_id = a.article_id ) as count_readings, " : "";
	    $query .= ( $_SESSION['user']['user_show_comments_count'] == SHOW_MAIN_PAGE || $_SESSION['user']['user_show_comments_count'] == SHOW_ALL_PAGE ) ? "( SELECT COUNT(ac.comment_id) FROM ".ARTICLES_COMMENTS_TABLE." ac WHERE ac.article_id = a.article_id ) as count_comments, " : "";
	    $query .= "a.article_id, a.article_url, ";
	    $query .= ( $_SESSION['user']['user_show_article_author'] == SHOW_MAIN_PAGE || $_SESSION['user']['user_show_article_author'] == SHOW_ALL_PAGE ) ? "a.user_id, a.user_name, " : "";
	    $query .= "a.article_title, a.article_text ";
	    $query .= ( $_SESSION['user']['user_show_article_time'] == SHOW_MAIN_PAGE || $_SESSION['user']['user_show_article_time'] == SHOW_ALL_PAGE ) ? "a.article_date, " : "";
	    $query .= ( $_SESSION['user']['user_show_article_edited'] == SHOW_MAIN_PAGE || $_SESSION['user']['user_show_article_edited'] == SHOW_ALL_PAGE) ? "a.article_edit_count, a.article_last_edit " : "";
	    $query .= "FROM ".ARTICLES_TABLE." a
			FORCE INDEX(PRIMARY)
			WHERE ( a.article_title LIKE :search_word1
				OR a.article_title LIKE :search_word2
				OR a.article_title LIKE :search_word3
				OR a.article_title LIKE :search_word4
				OR a.article_text LIKE :search_word1
				OR a.article_text LIKE :search_word2
				OR a.article_text LIKE :search_word3
				OR a.article_text LIKE :search_word4 )
				AND a.article_active = 1
			ORDER BY 5 * MATCH(a.article_title) AGAINST (:search_word) + MATCH(a.article_text) AGAINST (:search_word) DESC
			#LIMIT ".ARTICLES_PAGE_LIMIT."
			#OFFSET ".ARTICLES_PAGE_LIMIT * $page.";";

	    $this->db->query($query, __LINE__, __FILE__, array(

		'search_word1' => $_POST['search_word'],
		'search_word2' => '%'.$_POST['search_word'].'%',
		'search_word3' => $_POST['search_word'].'%',
		'search_word4' => '%'.$_POST['search_word'] ));

		if ( !$this->db->numRows() )
		    throw new ArticlesException('Žádné aktivní nalezené články');

	    $this->smarty->assign('s_uid', $_SESSION['user']['user_id']);
	    $this->smarty->assignGlobal('ar', $this->db->fetchAll());
        $this->db->freeResult();
	    $this->smarty->display('articleShow.tpl');

	    return true;
	}

    /**
     * @return bool
     * @throws ArticlesException
     */
    private function searchArticlesFulltext() {
	    $query = "SELECT SQL_CALC_FOUND_ROWS ";
	    $query .= ( $_SESSION['user']['user_show_article_readings'] == SHOW_MAIN_PAGE || $_SESSION['user']['user_show_article_readings'] == SHOW_ALL_PAGE ) ? "( SELECT COUNT(ar.article_id) FROM articles_readings ar WHERE ar.article_id = a.article_id ) as count_readings, " : "";
	    $query .= ( $_SESSION['user']['user_show_comments_count'] == SHOW_MAIN_PAGE || $_SESSION['user']['user_show_comments_count'] == SHOW_ALL_PAGE ) ? "( SELECT COUNT(ac.comment_id) FROM ".ARTICLES_COMMENTS_TABLE." ac WHERE ac.article_id = a.article_id ) as count_comments, " : "";
        $query .= "a.article_id, a.article_url, ";
	    $query .= ( $_SESSION['user']['user_show_article_author'] == SHOW_MAIN_PAGE || $_SESSION['user']['user_show_article_author'] == SHOW_ALL_PAGE ) ? "a.user_id, a.user_name, " : "";
	    $query .= "a.article_title, a.article_text ";
    	$query .= ( $_SESSION['user']['user_show_article_time'] == SHOW_MAIN_PAGE || $_SESSION['user']['user_show_article_time'] == SHOW_ALL_PAGE ) ? "a.article_date, " : "";
	    $query .= ( $_SESSION['user']['user_show_article_edited'] == SHOW_MAIN_PAGE || $_SESSION['user']['user_show_article_edited'] == SHOW_ALL_PAGE) ? "a.article_edit_count, a.article_last_edit " : "";
	    $query .= "FROM ".ARTICLES_TABLE." a
			FORCE INDEX(PRIMARY)
			WHERE MATCH(a.article_title, a.article_text) AGAINST (:search_word IN BOOLEAN MODE)
				AND a.article_active = 1
			ORDER BY 5 * MATCH(a.article_title) AGAINST (:search_word) + MATCH(a.article_text) AGAINST (:search_word) DESC
			#LIMIT ".ARTICLES_PAGE_LIMIT."
			#OFFSET ".ARTICLES_PAGE_LIMIT * $page.";";

	    $this->db->query($query, __LINE__, __FILE__, array( 'search_word' => $_POST['search_word']));

		if ( !$this->db->numRows() )
		    throw new ArticlesException('Žádné aktivní nalezené články');

	    $this->smarty->assign('s_uid', $_SESSION['user']['user_id']);
	    $this->smarty->assignGlobal('ar', $this->db->fetchAll());
    	$this->db->freeResult();
	    $this->smarty->display('articleShow.tpl');
	    return true;
	}

    /**
     * @return bool
     */
    private function searchUsersLike() {
	    $this->db->query("SELECT user_id, user_name, user_active
			FROM ".USERS_TABLE."
			WHERE user_name LIKE :search_word1
				OR user_name LIKE :search_word2
				OR user_name LIKE :search_word3
				OR user_name LIKE :search_word4	
			ORDER BY user_name DESC;", __FILE__, __LINE__, array( 

			'search_word1' => $_POST['search_word'],
			'search_word2' => "%".$_POST['search_word']."%",
			'search_word3' => $_POST['search_word']."%",
			'search_word4' => "%".$_POST['search_word']));

			if ( !$this->db->numRows() )
			    return false;

	    $this->smarty->assign('users', $this->db->fetchAll());
	    $this->smarty->display('memberlist.tpl');
    	$this->db->freeResult();
	    return true;
	}

    /**
     * @return bool
     */
    private function searchUsersFulltext() {
	    $this->db->query("SELECT user_id, user_name, user_active
			FROM ".USERS_TABLE."
			WHERE MATCH(user_name) AGAINST (:search_word IN BOOLEAN MODE)
			ORDER BY MATCH(user_name) AGAINST (:search_word) DESC;", __FILE__, __LINE__, array( 'search_word' => $_POST['search_word']));

		if ( !$this->db->numRows() )
		    return false;

	    $this->smarty->assign('users', $this->db->fetchAll());
	    $this->smarty->display('memberlist.tpl');
	    $this->db->freeResult();
	    return true;
	}

    /**
     *
     */
    private function searchPagesLike() {
	}

    /**
     *
     */
    private function searchPagesFulltext() {
	}

    /**
     *
     */
    private function searchCategoriesLike() {
	}

    /**
     *
     */
    private function searchCategoriesFulltext() {
	}
}
