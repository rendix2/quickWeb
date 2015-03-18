<?php

//namespace qw;

/**
 * Class PagesAdminHome
 */
final class PagesAdminHome extends PagesAdmin {
    /**
     * @var db
     */
    /**
     * @var db|Smarty
     */
    private $db, $smarty;

    /**
     * @param db $db
     * @param Smarty $smarty
     */
    public function __construct(db $db, Smarty $smarty) {
        if ( DEBUG )
            cs($language, $this);

        //parent::__construct($db, $smarty);
	    $this->db = $db;
	    $this->smarty = $smarty;
	}

    /**
     *
     */
    public function __destruct(){
        if ( DEBUG )
            echo 'Destruktor: '.get_class($this).'<br>';

        parent::__destruct();
        $this->db = null;
        $this->smarty = null;
    }

    /**
     * @throws PagesException
     */
    public function pagesShow() {
	    $this->db->query("SELECT page_id, page_name FROM ".PAGES_TABLE." ORDER BY page_id DESC LIMIT ".PAGES_ADMIN_HOME_PAGE_LIMIT.";", __FILE__, __LINE__);
	    $this->smarty->assign('pages', $this->db->fetchAll());
	    $this->smarty->assign('load', 'pagesShow.tpl');
	    $this->db->freeResult();

		if ( isset($_POST['page_submit']) ) {
			foreach ( $_POST['page'] as $k => $v )
				if ( isset($_POST['page'][$k]))
		    		$this->pagesDelete($k);
				
		    Red::redirect('./admin.php');
		}
	}		
}
