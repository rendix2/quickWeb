<?php

//use \Smarty as Smarty;

/**
 * Class TemplateUsers
 */
final class TemplateUsers extends Smarty {
    /**
     * @var string
     */
    /**
     * @var int|string
     */
    public $templateDir, $caching ;

    /**
     * @param $templateDir
     * @param int $cache
     */
    public function __construct($templateDir, $cache = 0) {
        /*
                if ( DEBUG )
                    cs($language, $this);
        */
	    parent::__construct();

		if ( empty($templateDir) )
    		$templateDir = 'default';

    //	$this->createCacheDirs();
    	$this->caching = $cache;
    	$this->templateDir = $templateDir;
    	$this->setTemplateDir('./templates/web/'.$templateDir.'/');
    	$this->setCacheDir('./cache/template/web/'.$templateDir.'/');
	    $this->setCompileDir('./cache/template_c/web/'.$templateDir.'/');
    	$this->compile_check = true;
    //	$this->enableSecurity();
	}

    /**
     *
     */
    public function __destruct() {
        if ( DEBUG )
            echo 'Destruktor: '.get_class($this).'<br>';

        parent::__destruct();
        $this->templateDir = null;
        $this->caching = null;
    }


    /**
     * @param null $template
     * @param bool $safe_form
     * @param null $cache_id
     * @param null $compile_id
     * @param null $parent
     * @return SafeForm
     */
    public function display($template = NULL, $safe_form = false, $cache_id = NULL, $compile_id = NULL, $parent = NULL) {
		try {
            $sf = ( $safe_form == true ) ? new SafeForm($this, $this->templateDir.'/'.$template) : null;
		    parent::display('./templates/web/'.$this->templateDir.'/'.$template, $cache_id, $compile_id, $parent);
		}
		catch ( SmartyException $e ) {
		    echo $e->getMessage();
            $e = null;
		}

	$this->clearAllAssign();
	return $sf;
	}
}
