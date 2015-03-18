<?php

//namespace qw;

//use \PagesException as PagesException;

/**
 * Class Pages
 */
class Pages {
    private $db, $language, $smarty, $pagesData;

    /**
     * @param db $db
     * @param Language $language
     * @param Smarty $smarty
     */
    public function __construct(db $db, Language $language, Smarty $smarty) {
        if ( DEBUG )
            cs($language, $this);

	    $this->db = $db;
        $this->language = $language;
	    $this->smarty = $smarty;
	    $this->pagesData = array();
	}

    /**
     *
     */
    public function __destruct() {
        if ( DEBUG )
            echo 'Destruktor: '.get_class($this).'<br>';

        $this->smarty = null;
        $this->db = null;
        $this->pagesData = null;
    }

    /**
     * @throws PagesException
     */
    public function pagesShow() {
    	$this->db->query("SELECT p.page_id, p.page_name, p.page_static,
				(SELECT COUNT(*) AS count FROM ".ARTICLES_PAGES_TABLE." a WHERE a.page_id = p.page_id AND p.page_static = 0) AS articles_pages,
				(
				SELECT COUNT(DISTINCT a.article_id) AS count2
			        FROM ".ARTICLES_TABLE." a
				JOIN ".ARTICLES_CATS_TABLE." ac
				ON ac.article_id = a.article_id
					AND a.article_active = 1
				JOIN ".PAGES_CATS_TABLE." pc
				ON pc.cat_id = ac.cat_id	
				WHERE pc.page_id = p.page_id
				) AS articles_cats_pages, (SELECT articles_pages+articles_cats_pages) AS final_count
				FROM ".PAGES_TABLE." p 
				FORCE INDEX (page_name)
				ORDER BY p.page_name ASC", __FILE__, __LINE__);
				
		if ( !$this->db->numRows() )
    		throw new PagesException('Žádné stránky.');
				
    	$this->smarty->assign('pages', $this->db->fetchAll());
        $this->smarty->assign('lang',  $this->language->languageGetPack());
    	$this->smarty->display('pagesShow.tpl');
    	$this->db->freeResult();
	}

    /**
     * @throws PagesException
     */
    public function pagesShowOne() {
	    $this->db->query("SELECT page_name, page_static, page_file FROM ".PAGES_TABLE." WHERE page_id = :page_id LIMIT 1;", __FILE__, __LINE__, array( 'page_id' => $_GET['page_id'] ));

		if ( !$this->db->numRows() )
            throw new PagesException('Neexistující stránka.');
		
	    $this->pagesData = $this->db->fetch();
		
		if ( $this->pagesData['page_static'] ) {
		    $this->smarty->assign('page_name', $this->pagesData['page_name']);
		    $this->smarty->assign('page_text', file_get_contents('./pages/'.$this->pagesData['page_file'].'.html'));
		    $this->smarty->display('pagesShowOne.tpl');
		}
		else {
		    $this->db->query("
				(
				SELECT SQL_CALC_FOUND_ROWS 
					( SELECT COUNT(ar.article_id) FROM ".ARTICLES_READINGS_TABLE." ar WHERE ar.article_id = a.article_id ) as count_readings, 
					( SELECT COUNT(ac.comment_id) FROM ".ARTICLES_COMMENTS_TABLE." ac WHERE ac.article_id = a.article_id ) as count_comments,  
					a.article_id, a.article_url, a.user_id, a.user_name, a.article_title, a.article_text, a.article_time, a.article_edit_count, 
					a.article_last_edit, a.article_comments_enable 
				FROM ".ARTICLES_TABLE." a
				FORCE INDEX(PRIMARY)
				JOIN ".ARTICLES_PAGES_TABLE." ap
				ON ap.page_id = :page_id
					AND a.article_id = ap.article_id
				LEFT JOIN ".PAGES_TABLE." p
				ON p.page_id = ap.page_id
					AND p.page_static = 0
				WHERE a.article_active = 1					
				GROUP BY a.article_id DESC
				ORDER BY a.article_id DESC
				#LIMIT ".ARTICLES_PAGE_LIMIT."
				#OFFSET ".ARTICLES_PAGE_LIMIT * $_GET['page']."
				)

				UNION ALL

				(
				SELECT #SQL_CALC_FOUND_ROWS 
					( SELECT COUNT(ar.article_id) FROM ".ARTICLES_READINGS_TABLE." ar WHERE ar.article_id = a.article_id ) as count_readings, 
					( SELECT COUNT(ac.comment_id) FROM ".ARTICLES_COMMENTS_TABLE." ac WHERE ac.article_id = a.article_id ) as count_comments,  
					a.article_id, a.article_url, a.user_id, a.user_name, a.article_title, a.article_text, a.article_time, a.article_edit_count, 
					a.article_last_edit, a.article_comments_enable 
				FROM ".ARTICLES_TABLE." a
				FORCE INDEX(PRIMARY)
					LEFT JOIN ".PAGES_CATS_TABLE." pc
				ON pc.page_id = :page_id
				LEFT JOIN ".PAGES_TABLE." p
				ON pc.page_id = p.page_id
					AND p.page_static = 0
				JOIN ".ARTICLES_CATS_TABLE." c
				ON c.cat_id = pc.cat_id
					AND a.article_id = c.article_id
				WHERE a.article_active = 1					
				GROUP BY a.article_id DESC
				ORDER BY a.article_id DESC
				#LIMIT ".ARTICLES_PAGE_LIMIT."
				#OFFSET ".ARTICLES_PAGE_LIMIT * $_GET['page']."
				);",__FILE__, __LINE__, array('page_id' => $_GET['page_id']));
				
			if ( !$this->db->numRows() )
			    throw new PagesException('Žádné články ve stránce');

            $this->smarty->assign('lang', $this->language->languageGetPack());
		    $this->smarty->assign('ar', $this->db->fetchAll());
		    $this->smarty->display('articleShow.tpl');
		    $this->db->freeResult();
		}			
	}
}
