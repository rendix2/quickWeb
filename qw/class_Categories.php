<?php

//namespace qw;

class Categories {
protected $db, $language, $smarty;

	public function __construct(db $db, Language $language, Smarty $smarty) {
        if ( DEBUG )
            cs($language, $this);

	    $this->db = $db;
        $this->language = $language;
	    $this->smarty = $smarty;
	}

    public function __destruct() {
        if ( DEBUG )
            echo 'Destruktor: '.get_class($this).'<br>';

        $this->db = null;
        $this->smarty = null;
    }

	public function categoriesShow() {
	    $this->db->query("SELECT c.cat_id, c.cat_name,

		    				(SELECT COUNT(a.article_id) AS count
				              FROM ".ARTICLES_CATS_TABLE." ac
                              LEFT JOIN ".ARTICLES_TABLE." a
                                ON ac.article_id = a.article_id
					            AND a.article_active = 1
                                WHERE ac.cat_id = c.cat_id ) as articles_cats
                            FROM ".CATS_TABLE." c
			                FORCE INDEX (cat_name)
			                ORDER BY c.cat_name ASC", __FILE__, __LINE__);

        if ( !$this->db->numRows() )
            throw new CategoriesException('Žádné kategorie');

	    $this->smarty->assign('cats', $this->db->fetchAll());
	    $this->smarty->display('catShow.tpl', false);
        $this->db->freeResult();
        return true;
	}
}
