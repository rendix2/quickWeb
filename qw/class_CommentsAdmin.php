<?php

//namespace qw;

/**
 * Class CommentsAdmin
 */
final class CommentsAdmin extends Comments {
    /**
     * @param db $db
     * @param Smarty $smarty
     * @throws CommentsException
     */
    public function __construct(db $db, Language $language, Smarty $smarty) {
        if ( DEBUG )
            cs($language, $this);

		if ( !$_SESSION['admin']['user_logged'] )
		    throw new CommentsException('Nejsi přihlášen');

        parent::__construct($db, $language, $smarty);
	}

    /**
     *
     */
    public function __destruct() {
        if ( DEBUG )
            echo 'Destruktor: '.get_class($this).'<br>';

        parent::__destruct();
    //	$this->db = null;
   // 	$this->smarty = null;
	}

    /**
     * @throws CommentsException
     */
    private function commentsDelete() {
		if ( !$_GET['delete_comment'] )
		    throw new CommentsException('Nelze');

        $this->db->query("SELECT FROM ".ARTICLES_COMMENTS_TABLE." WHERE comment_id = :comment_id LIMIT 1;", __FLIE__, __LINE__, array( 'comment_id' => $_GET['comment_id'] ));

        if ( !$this->db->numRows() )
            throw new CommentsException('Neexistující komentář.');

	$this->db->query->("DELETE FROM ".COMMENTS_TABLE." WHERE comment_id = :comment_id LIMIT 1;", __LINE__, __FILE__, array( 'comment_id' => $_GET['comment_id'] ));

        if ( $this->db->numRows() )
	        am('Komentář smazán.');
        else
            throw new CommentsException('Nepodařilo se smazat komentář.');

	    Red::redirect('./admin.php');
        return trur;
	}

    /**
     *
     */
    public function commentsShowReports() {
	}

    /**
     *
     */
    private function commentsDeleteReport() {
	
	// code!
	}
}