<?php

//namespace qw;

/**
 * Class CategoriesAdmin
 */
final class CategoriesAdmin extends Categories {

    /**
     * @param db $db
     * @param Smarty $smarty
     * @throws UsersException
     */
    public function __construct(db $db, Language $language, Smarty $smarty){
        if ( DEBUG )
            cs($language, $this);

        if ( !$_SESSION['user']['user_author'] )
            throw new UsersException('Nejsi admin');

        if ( !$_SESSION['admin']['user_logged'] )
            throw new UsersException('Nejsi přihlášený');

        parent::__construct($db,$smarty);
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
     * @param $catName
     * @return bool
     * @throws CategoriesException
     */
    private function categoriesValidateCatName($catName) {
		if ( empty($catName) )
		    throw new CategoriesException('Jméno kategorie je prázdné.');

		if ( mb_strlen($catName) < CAT_NAME_MIN_LENGTH )
		    throw new CategoriesException('Jméno kategorie je krátké.');

		if ( mb_strlen($catName) > CAT_NAME_MAX_LENGTH )
		    throw new CategoriesException('Jméno kategorie je dlouhé.');

		if ( !preg_match('#$[a-zA-Z0-9 -_]^#',$catName ) )
            throw new CategoriesException('Jméno kategorie není ve správném tvaru');

	    return true;
	}

    /**
     * @throws CategoriesException
     */
    public function categoriesAdd() {
	    $this->smarty->assign('load', 'catAdd.tpl');
	    $this->smarty->assign('cat', array('cat_text' => 'Přidat kategorii', 'cat_name' => $_POST['cat_name']));
	
		if ( isset($_POST['submit']) ) {
			if ( !$this->categoriesValidateCatName($_POST['cat_name']) )
			    return false;
			
		    $this->db->query("SELECT 1 FROM ".CATS_TABLE." WHERE cat_name = :cat_name LIMIT 1;", __FILE__, __LINE__, array( 'cat_name' => $_POST['cat_name'] ));
		
			if ( $this->db->numRows() )
			    throw new CategoriesException('Kategorie už existuje.');

		    $this->db->query("INSERT INTO ".CATS_TABLE." (cat_name) VALUES(:cat_name);", __FILE__, __LINE__, array( 'cat_name' => $_POST['cat_name'] ));
		
			if ( $this->db->numRows() )
			    p($this->smarty, 'Kategorie <strong>'.$_POST['cat_name'].'</strong> přidána.', 'g');
			else
			    throw new CategoriesException('Kategorie se nepodařilo přidat.');

            Red::redirect('./admin.php');
		}
	}

    /**
     * @throws CategoriesException
     */
    public function categoriesEdit() {
	    $this->db->query("SELECT cat_name FROM ".CATS_TABLE." WHERE cat_id = :cat_id LIMIT 1;", __FILE__, __LINE__, array('cat_id' => $_GET['cat_id']));

        if ( !$this->db->numRows() )
            throw new CategoriesException('Neexistující kategorie');

	    $this->smarty->assign('load', 'catAdd.tpl');
	    $this->smarty->assign('cat', array('cat_text' => 'Editovat kategorii', 'cat_name' => $this->db->fetch()['cat_name']));
	
		if ( isset($_POST['submit']) ) {
			if ( !$this->categoriesValidateCatName($_POST['cat_name']) )
			    return false;

	    	$this->db->query("UPDATE ".CATS_TABLE." SET cat_name = :cat_name WHERE cat_id = :cat_id;", __FILE__, __LINE__, array( 'cat_name' => $_POST['cat_name'], 'cat_id' => $_GET['cat_id'] ));

			if ( $this->db->numRows() )
			    p('Kategorie <strong>'.$_POST['cat_name'].'</strong> aktualizována.');
			else
			    throw new CategoriesException('Kategorii se nepodařilo aktualizovat.');

            Red::redirect('./admin.php');
		}
	}

    /**
     * @throws CategoriesException
     */
    public function categoriesDelete() {
	    $this->db->query("SELECT 1 FROM ".CATS_TABLE." WHERE cat_id = :cat_id;", __FILE__, __LINE__, array( 'cat_id' => $_GET['cat_id']));

		if ( !$this->db->numRows() )
    		throw new CategoriesException('Neexistující kategorie.');

	    $this->db->query("DELETE FROM ".CATS_TABLE." WHERE cat_id = :cat_id;", __FILE__, __LINE__, array('cat_id' => $_GET['cat_id']));
        $this->db->query("DELETE FROM ".PAGES_CATS_TABLE." WHERE cat_id = :cat_id;", __FILE__, __LINE__, array('cat_id' => $_GET['cat_id']));

		if ( $this->db->numRows() )
		    p('Kategorie smazána.');
		else
    		throw new CategoriesException('Kategorii se nepodařilo smazat.');

        Red::redirect('./admin.php');
	}

    /**
     * @throws CategoriesException
     */
    public function categoriesShow() {
	    $this->db->query("SELECT cat_id, cat_name FROM ".CATS_TABLE." ORDER BY cat_name ASC;", __FILE__, __LINE__);

        if ( !$this->db->numRows() )
            throw new CategoriesException('Žádné kategorie.');

	    $this->smarty->assign('load', 'catShow.tpl');
	    $this->smarty->assign('cats', $this->db->fetchAll());
	    $this->db->freeResult();
	}
}