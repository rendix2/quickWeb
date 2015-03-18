<?php

//namespace qw;

/**
 * Class PagesAdmin
 */
class PagesAdmin extends Pages {
    private $db, $smarty;
    private $pagesData, $pagesCheckedCats, $pagesCatsData, $catCount, $catChecked;

    /**
     * @param db $db
     * @param Smarty $smarty
     */
    public function __construct(db $db, Smarty $smarty) {
/*
        if ( DEBUG )
            cs($language, $this);
*/
	    $this->db = $db;
	    $this->smarty = $smarty;
	    $this->pagesData = array();
	    $this->pagesCatsData = array();
	    $this->pagesCheckedCats = array();
	    $this->catCount = 0;
	    $this->catChecked = 0;
	}

    /**
     *
     */
    public function __destruct() {
        if ( DEBUG )
            echo 'Destruktor: '.get_class($this).'<br>';

        parent::__destruct();
	    $this->catChecked = null;
	    $this->catCount	= null;
	}

    /**
     * @return bool
     */
    private function pagesSelectCats() {
	    $this->db->query("SELECT cat_id, cat_name FROM ".CATS_TABLE." ORDER BY cat_name DESC;", __FILE__, __LINE__);
    	return $this->pagesCatsData = $this->db->fetchAll();
	}

    /**
     * @param $page_id
     * @return bool
     */
    private function pagesAdd2Cat($page_id) {
	    $this->db->query("DELETE FROM ".PAGES_CATS_TABLE." WHERE page_id = :page_id;", __FILE__, __LINE__, array ( 'page_id' => $page_id ));

		foreach ( $_POST['cat'] as $k => $v )
			if ( isset($_POST['cat'][$k]) ) {
		    	$this->db->query("INSERT INTO ".PAGES_CATS_TABLE." (page_id, cat_id) VALUES(:page_id, :cat_id)", __FILE__, __LINE__, array ( 'page_id' => $page_id, 'cat_id' => $k ));

                if ( $this->db->numRows() )
			        am('Kategorie <strong>'.$v.'</strong> přidána na stránku.', 'g');
                else
                    am('Kategori <strong>'.$v.'</strong> se nepodařilo přidat na stránku.', 'b');
			}

	    return true;
	}

    /**
     * @return array
     */
    private function pagesGetCheckedCats() {
		foreach ( $_POST['cat'] as $k => $v )
			if ( isset($_POST['cat'][$k]) )
		    	$this->pagesCheckedCats[$k] = 'checked="checked"';
	
	    return $this->pagesCheckedCats;
	}

    /**
     * @return bool
     */
    private function pagesSelectCatsEdit() {
	    $this->db->query("SELECT cat_id FROM ".PAGES_CATS_TABLE." WHERE page_id = :page_id ORDER BY cat_id ASC;", __FILE__, __LINE__, array('page_id' => $_GET['page_id']));
	
    	return $this->db->fetchAll();
	}

    /**
     * @return array
     */
    private function pagesGetCheckedCatsEdit() {
    	foreach ( $this->pagesCatsData as $v )
	    	foreach ( $this->pagesSelectCatsEdit() as $k => $v2 )
		    	if ( $v['cat_id'] == $v2['cat_id'] )
			        $this->pagesCheckedCats[$v['cat_id']] = 'checked="checked"';
				
	    return $this->pagesCheckedCats;
	}

    /**
     * @param $file_name
     * @param bool $new
     * @return bool
     * @throws PagesException
     */
    private function pagesCreateHTMLfile($file_name, $new = true) {
		if ( !file_exists('./pages') )
		    throw new PagesException('Neexistující složka <strong>pages</strong> pro statické stránky.');
		
		if ( !preg_match('#[a-zA-Z0-9]#', $file_name) )
		    throw new PagesException('Neplatný název souboru. Jméno souboru smí obsahovat pouze čísla, velká a malá písmena!');
		
		if ( $new === true ) {		
		    $this->db->query("SELECT 1 FROM ".PAGES_TABLE." WHERE page_file = :page_file LIMIT 1;", __FILE__, __LINE__, array( 'page_file' => $file_name ));
	
			if ( $this->db->numRows() )
    			throw new PagesException('Soubor již existuje, zvolte jiné jméno souboru.');
			
	    	file_put_contents('./pages/'.$file_name.'.html', 'Zde zatím nic není! :)');
		}
		else
		    file_put_contents('./pages/'.$file_name.'.html', $new);

	    return 	true;
	}

    /**
     * @throws PagesException
     */
    public function pagesAdd() {
	    $this->smarty->assign('pages', array(
	
		'page_name' => $_POST['page_name'],
		'page_static_checked' => isset($_POST['page_static']) ? 'checked="checked"' : '',
		'page_static' => '',
		'page_filename' => $_POST['page_filename'],
		'page_submit' => 'Přidat stránku'));
		
	    $this->smarty->assign('cats', $this->pagesSelectCats());
	    $this->smarty->assign('cats_checked', $this->pagesGetCheckedCats());
        $this->smarty->assign('load', 'pagesAdd.tpl');
	
		if ( isset($_POST['submit']) ) {
			if ( empty($_POST['page_name']) )
		    	throw new PagesException('Prázdné jméno stránky.');
			
		$this->db->query("SELECT 1 FROM ".PAGES_TABLE." WHERE page_name = :page_name LIMIT 1;", __FILE__, __LINE__, array('page_name' => $_POST['page_name']));
		
			if ( $this->db->numRows() )
		    	throw new PagesException('Stránka již existuje!');
	
		$this->catCount = count($_POST['cat']);
			
			if ( isset($_POST['page_static']) ) {
				foreach ( $_POST['cat'] as $k => $v )
					if ( isset($_POST['cat'][$k]) )
			    		throw new PagesException('Pokud bude stránka statická, nesmí se vkládat do žádné kategorie!');

				if ( empty($_POST['page_filename']) )
    				throw new PagesException('Prázdné jméno souboru.');
				else
	    			$this->pagesCreateHTMLfile($_POST['page_filename']);
			}
			else {			
				if ( !empty($_POST['page_filename']) )
		    		throw new PagesException('Pokud stránka nebude statická, nesmí být vyplněné jméno souboru.');
				
				foreach ( $_POST['cat'] as $k => $v )
					if ( isset($_POST['cat'][$k]) )
			    		$this->catChecked++;
					
				if ( $this->catChecked == $this->catCount )
    				throw new PagesException('Nevybraná žádná kategorie.');
			}

		$this->db->query("INSERT INTO ".PAGES_TABLE." (page_name, page_static, page_file) VALUES (:page_name, :page_static, :page_file);", __FILE__, __LINE__, array(
		
		'page_name' => $_POST['page_name'],
		'page_static' => isset($_POST['page_static']),
		'page_file' => $_POST['page_filename'] ));

            if ( $this->db->numRows() )
                am('Stránka vytvořena!', 'g');
            else
                throw new PagesException('Stránku se nepdoařilo vytvořit');
		
	    	$this->pagesAdd2Cat($this->db->lastId());
    		Red::redirect('./admin.php');
		}
	}

    /**
     * @param $page_id
     * @throws PagesException
     */
    public function pagesDelete($page_id) {
	    $this->db->query("SELECT page_name, page_static, page_file FROM ".PAGES_TABLE." WHERE page_id = :page_id LIMIT 1;", __FILE__, __LINE__, array('page_id' => $page_id));
	
		if ( !$this->db->numRows() )
    		throw new PagesException('Neexistující stránka');
	
    	$this->pagesData = $this->db->fetch();
	
		if ( $this->pagesData['page_static'] )
    		unlink('./pages/'.$this->pagesData['page_file'].'.html');
		
	    $this->db->query("DELETE FROM ".PAGES_TABLE." WHERE page_id = :page_id LIMIT 1;", __FILE__, __LINE__, array('page_id' => $page_id));

        if ( $this->db->numRows() )
	        am('Stránka <strong>'.$this->pagesData['page_name'].'</strong> smazána.');
        else
            throw new PagesException('Nepodařilo se smazat stránku');

        $this->db->freeResult();
	}

    /**
     * @throws PagesException
     */
    public function pagesEdit() {
	    $this->db->query("SELECT page_name, page_static, page_file FROM ".PAGES_TABLE." WHERE page_id = :page_id LIMIT 1;", __FILE__, __LINE__, array( 'page_id' => $_GET['page_id'] ));
	    $this->pagesData = $this->db->fetch();
	
		if ( $this->pagesData['page_static'] )
    		$this->pagesData['page_text'] = file_get_contents('./pages/'.$this->pagesData['page_file'].'.html');
	
    	$this->smarty->assign('pages', array(
	
		'page_name' => $this->pagesData['page_name'],
		'page_static_checked' => $this->pagesData['page_static'] ? 'checked="checked"' : '',
		'page_static' => $this->pagesData['page_static'],
		'page_filename' => $this->pagesData['page_file'],
		'page_submit' => 'Editovat stránku',
		'page_text' => isset($this->pagesData['page_text']) ? $this->pagesData['page_text'] : '' ));

	    $this->smarty->assign('cats', $this->pagesSelectCats());
	    $this->smarty->assign('cats_checked', $this->pagesGetCheckedCatsEdit());
        $this->smarty->assign('load', 'pagesAdd.tpl');
	
		if ( isset($_POST['submit']) ) {
			if ( empty($_POST['page_name']) )
	    		throw new PagesException('Prázdné jméno stránky.');
					
			if ( isset($_POST['page_static']) ) {
				foreach ( $_POST['cat'] as $k => $v )
					if ( isset($_POST['cat'][$k]) )
		    			throw new PagesException('Pokud bude stránka statická, nesmí se vkládat do žádné kategorie!');

								
				if ( empty($_POST['page_filename']) )
				throw new PagesException('Prázdné jméno souboru.');
				
				if ( file_exists('./pages/'.$this->pagesData['page_file'].'.html') && $this->pagesData['page_file'] != $_POST['page_filename'] ) {
				    unlink('./pages/'.$this->pagesData['page_file'].'.html');
				    $this->pagesCreateHTMLfile($_POST['page_filename'], $_POST['page_text']);
				}
				else if ( !file_exists('./pages/'.$this->pagesData['page_file'].'.html') && $this->pagesData['page_file'] != $_POST['page_filename'] )
				    $this->pagesCreateHTMLfile($_POST['page_filename'], true);
				else if ( file_exists('./pages/'.$this->pagesData['page_file'].'.html') && $this->pagesData['page_file'] == $_POST['page_filename'] )
    				$this->pagesCreateHTMLfile($_POST['page_filename'], $_POST['page_text']);
			}
			else {		
				if ( !empty($_POST['page_filename']) )
	    			throw new PagesException('Pokud stránka nebude statická, nesmí být vyplněné jméno souboru.');
				
				if ( !empty($_POST['page_text']) )
		    		throw new PagesException('Pokud stránka nebude statická, nesmí být vyplněný text.');
				
				if ( !$_POST['page_static'] && $this->pagesData['page_static'] )
			    	unlink('./pages/'.$this->pagesData['page_file'].'.html');
			}
			
		$this->db->query("UPDATE ".PAGES_TABLE." SET page_name = :page_name, page_static = :page_static, page_file = :page_file WHERE page_id = :page_id;", __FILE__, __LINE__, array(
		
		'page_id' => $_GET['page_id'],
		'page_name' => $_POST['page_name'],
		'page_static' => isset($_POST['page_static']),
		'page_file' => $_POST['page_filename'] ));


            if ( $this->db->numRows() )
                am('Stránka upravena', 'g');
            else
                throw new PagesException('Stránku se nepodařilo editovat.');
		
		$this->pagesAdd2Cat($_GET['page_id']);
		Red::redirect('./admin.php');	
		}						
	}

    /**
     *
     */
    public function pagesShow() {
	    $this->db->query("SELECT page_id, page_name, page_static FROM ".PAGES_TABLE." ORDER BY page_name ASC;", __FILE__, __LINE__);
	    $this->smarty->assign('pages', $this->db->fetchAll());
	    $this->smarty->assign('load', 'pagesShow.tpl');
	    $this->db->freeResult();
	}

    /**
     *
     */
    private function setActive(){
	}
}