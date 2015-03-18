<?php

trait Pagination {
    private $paginationData = array();
    private $count, $last, $page;

    private function setCalcRows() {
	    $this->db->query("SELECT FOUND_ROWS() AS rows;", __FILE__, __LINE__);
        $this->count =  $this->db->fetch()['rows'];
	}

    private function  getCalcRows(){
        return $this->count;
    }

    /**
     * @param $limit
     */
    private function pagination($limit) {
	    $this->setCalcRows();

	    $this->last = (int) ( $this->getCalcRows() / $limit );

        if (  $this->last == 0 ) {
            $this->smarty->assign('pagination', array('first' => 0, 'last' => 0, 'page' => 0));
            $this->smarty->assign('data', array());

            return false;
        }


	    $this->paginationData['first'] = 0;
	    $this->paginationData['last'] = 0;
	    $this->page = isset($_GET['page']) ? $_GET['page'] : 0;

		if ( $this->page >= 3 )
    		$this->paginationData['first'] = 1;

        if ( $this->last - 3 > $this->page )
    		$this->paginationData['last'] = 1;

    	$this->paginationData['squeryString'] = preg_replace("#&page=[0-9]*$#","", $_SERVER['QUERY_STRING']);
    	$this->paginationData['PHPSelf'] = $_SERVER['PHP_SELF'];
    	$this->paginationData['page'] = $this->page;
    	$this->paginationData['lastPage'] = $this->last;


            for ( $i = $this->page - 3 > 0  ? $this->page - 3 : 0; $i <= ($this->page + 3) && $i <= $this->last; $i++ )
            $data[] = $i;


        $this->smarty->assing('lang', $this->language->languageGetMetaPack());
        $this->smarty->assign('data', $data);
    	$this->smarty->assign('pagination', $this->paginationData);
	}
}
