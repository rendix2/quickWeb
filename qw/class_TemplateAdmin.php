<?php

//use \Smarty as Smarty;

/**
 * Class TemplateAdmin
 */
final class TemplateAdmin extends Smarty {
    /**
     * @param int $cache
     */
    public function __construct($cache = 0) {
        if ( DEBUG )
            cs($language, $this);

	    parent::__construct();

	    $this->caching = $cache;
    	$this->setTemplateDir('./templates/admin/');
	    $this->setCacheDir('./cache/template/admin/');
	    $this->setCompileDir('./cache/template_c/admin/');
	    $this->compile_check = true;
    //	$this->enableSecurity();
	}

    /**
     *
     */
    public function __destruct() {
        if ( DEBUG )
            echo 'Destruktor: '.get_class($this).'<br>';

        $this->caching = null;
        parent::__destruct();
    }

    /**
     * @param null $template
     * @param null $cache_id
     * @param null $compile_id
     * @param null $parent
     */
    public function display($template = NULL, $cache_id = NULL, $compile_id = NULL, $parent = NULL) {
		try {
		    parent::display('./templates/admin/'.$template, $cache_id, $compile_id, $parent);
		}
		catch ( SmartyException $e ) {
		    echo $e->getMessage();
            $e = null;
		}

    	$this->clearAllAssign();
	}
}
