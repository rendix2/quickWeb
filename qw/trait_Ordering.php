<?php

//namespace qw;

/**
 * Class Ordering
 */
trait Ordering {
    /**
     * @param $orderBy
     * @return mixed
     * @throws OrderException
     */
    private function order($orderBy) {
		if ( !is_array($orderBy) || !count($orderBy) )
		    throw new OrderException('Prázdný seznam pro řazení');
		
	    $this->smarty->assign('order_by', $_POST['orderBy']);
	    $this->smarty->assign('orderByArray', $orderBy);
	    $this->smarty->display('orderBy.tpl');
	
	    return $_POST['orderBy'];
	}
}