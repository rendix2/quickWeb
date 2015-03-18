<?php

//namespace qw;

//use \CommentsException as CommentsException;

/**
 * Class Comments
 */
class Comments {
    protected $db, $smarty, $language;
    private $enabledComments, $articleId;

    /**
     * @param db $db
     * @param Smarty $smarty
     * @param $enabledComments
     * @param $articleId
     */
    public function __construct(db $db, Language $language, Smarty $smarty, $enabledComments, $articleId) {
        if ( DEBUG )
            cs($language, $this);

	    $this->db = $db;
	    $this->smarty = $smarty;
        $this->language = $language;
	    $this->enabledComments = $enabledComments;
	    $this->articleId = $articleId;
	}

    /**
     *
     */
    public function __destruct() {
        if ( DEBUG )
            echo 'Destruktor: '.get_class($this).'<br>';

        $this->smarty = null;
        $this->db = null;
    }

    /**
     * @throws CommentsException
     */
    public function commentsAdd() {
		if ( !$_SESSION['user']['user_logged'] )
		    throw new CommentsException('Nejsi přihlášen.');

		if ( !$this->enabledComments )
		    throw new CommentsException('Pro tento článek nejsou povoleny komentáře.');

	    $this->smarty->assign('comment_text', $_POST['comment_text']);
        $this->smarty->assign('lang', $this->language->languageGetPack());
	    $sf = $this->smarty->display('commentAdd.tpl', true);

		if ( isset($_POST['submit']) ) {
			try {
			    $sf->doChecks();
			}
			catch ( FormException $e ) {
			    throw new CommentsException($e->getMessage()); // WODO :O :O
                $e = null;
			}
            finally {
                $sf = null;
            }

			if ( mb_strlen($_POST['comment_text']) < COMMENT_TEXT_MIN_LENGTH )
    			throw new CommentsException('Text není dost dlouhý.');

		    $this->db->query("INSERT INTO ".ARTICLES_COMMENTS_TABLE." (user_id, user_name, article_id, comment_text, comment_time)
				VALUES(:user_id, :user_name, :article_id, :comment_text, :comment_time);", __LINE__, __FILE__, array(

					'user_id' => $_SESSION['user']['user_id'],
    				'user_name' => $_SESSION['user']['user_name'],
    				'article_id' => $_GET['article_id'],
	    			'comment_text' => $_POST['comment_text'],
		    		'comment_time' => time() ));

			if ( $this->db->numRows() )
			    echo 'Komentář uložen.';
			else
			    throw new CommentsException('Nepodařilo se přidat komentář.');

            Red::redirect('./');
		}
	}

    /**
     * @return bool
     * @throws CommentsException
     */
    public function commentsShow() {
		if ( $_GET['delete_comment'] == 1 )
		    $this->commentsDelete();

	    $this->db->query("SELECT comment_id, user_id, user_name, comment_text, comment_time, article_id
                		FROM ".ARTICLES_COMMENTS_TABLE."
			            WHERE article_id = :article_id
			            ORDER BY article_id ASC", __LINE__, __FILE__, array( 'article_id' => $_GET['article_id'] ));

		if ( !$this->db->numRows() )
		    throw new CommentsException('Článek nemá komentáře');

	    $this->smarty->assign('s_uid', $_SESSION['user']['user_id']);
	    $this->smarty->assign('cm', $this->db->fetchAll());
        $this->smarty->assign('lang', $this->language->languageGetPack());
	    $this->db->freeResult();
    	$this->smarty->display('commentShow.tpl');
    	return true;
	}

    /**
     * @return bool
     * @throws CommentsException
     */
    private function commentsDelete() {
		if ( !$_SESSION['user']['user_logged'] )
		    throw new CommentsException('Nejsi přihlášen.');

		if ( !$_GET['delete_comment'] )
		    throw new CommentsException('Nelze');

        $this->db->query("SELECT FROM ".ARTICLES_COMMENTS_TABLE." WHERE comment_id = :comment_id LIMIT 1;", __FLIE__, __LINE__, array( 'comment_id' => $_GET['comment_id'] ));

        if ( !$this->db->numRows() )
            throw new CommentsException('Neexistující komentář.');

	    $this->db->query("DELETE FROM ".ARTICLES_COMMENTS_TABLE." WHERE user_id = :user_id AND comment_id = :comment_id AND article_id = :article_id LIMIT 1;", __LINE__, __FILE__, array(
			
			'user_id' => $_SESSION['user']['user_id'],
			'comment_id' => $_GET['comment_id'],
			'article_id' => $this->articleId ));

        if ( !$this->db->numRows() )
            throw new CommentsException('Komentář se nepodařilo smazat!');

	    Red::redirect('./');
	    return true;
	}

    /**
     *
     */
    public function commentsAddReport() {
	}
}