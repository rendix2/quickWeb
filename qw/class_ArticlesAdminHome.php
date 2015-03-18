<?php

//namespace qw;

/**
 * Class ArticlesAdminHome
 */
final class ArticlesAdminHome extends ArticlesAdmin {

    /**
     * @param db $db
     * @param Language $language
     * @param Smarty $smarty
     * @throws UsersException
     */
    public function __construct(db $db, Language $language, Smarty $smarty) {
        if ( DEBUG )
            cs($language, $this);

	    parent::__construct($db, $language, $smarty);
	}

    /**
     *
     */
    public function __destruct() {
        if ( DEBUG )
            echo 'Destruktor: '.get_class($this).'<br>';

        parent::__destruct();
    }


    /**
     * @return bool
     * @throws ArticlesException
     */
    public function articlesShow() {
	    $this->db->query("SELECT article_id, article_title
    		FROM ".ARTICLES_TABLE."
			ORDER BY article_id DESC
			LIMIT ".ARTICLES_ADMIN_HOME_PAGE_LIMIT.";", __FILE__, __LINE__);

	    $this->smarty->assign('articles', $this->db->fetchAll());

		if ( isset($_POST['article_submit']) ) {
			foreach ( $_POST['article'] as $k => $v )
				if ( isset($_POST['article'][$k]))
			    	$this->articlesDelete($k);

		    Red::redirect('./admin.php');
		}

	    return true;
	}

    /**
     * @return bool
     * @throws ArticlesException
     */
    public function articlesAdd() {
        $this->smarty->assign('cats', $this->articlesSelectCats());
	    $this->smarty->assign('cats_checked', $this->articlesGetCheckedCats());
		
		if ( isset($_POST['submit']) ) {
			try {
                parent::articlesCheckInput($_POST['article_title'], $_POST['article_text']);
			}
			catch ( ArticlesException $e ) {
                am($e->getMessage());
			}

		    $this->db->query("INSERT INTO ".ARTICLES_TABLE." (article_active, article_url, article_number, user_id, user_name, article_title, article_text, article_time, article_comments_enable)
            				VALUES (1, :article_url, :article_number, :user_id, :user_name, :article_title, :article_text, :time, 1);", __FILE__, __LINE__, array (

                    'article_url' => $this->articlesGetUrl($_POST['article_title']),
		            'article_number' => $this->articlesGetNumber(),
		            'user_id' => $_SESSION['admin']['user_id'],
		            'user_name' => $_SESSION['admin']['user_name'],
		            'article_title' => $_POST['article_title'],
		            'article_text' => $_POST['article_text'],
		            'time' => time() ));

            if ( $this->db->numRows() )
                am('Článek <strong>'.$_POST['article_title'].'</strong> přidán.', 'g');
            else
                throw new ArticlesException('Článek se nepodařilo přidat.');

            $this->articlesAdd2Cat($this->db->lastId(), false);
    		$this->db->freeResult();
	    	$this->db->query("UPDATE ".USERS_TABLE." SET user_articles_count = user_articles_count + 1 WHERE user_id = :user_id;", __FILE__, __LINE__, array ( 'user_id' => $_SESSION['admin']['user_id'] ));

                if ( !$this->db->numRows() )
                    throw new ArticlesException('Nepodařilo se aktualizovat počet článků.');

		    $this->db->freeResult();
		    Red::redirect('./admin.php');
		    return true;
		}
	}
}