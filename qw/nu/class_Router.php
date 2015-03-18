<?php

namespace qw\nu;

// http://www.itnetwork.cz/objektovy-mvc-redakcni-system-v-php-router-smerovac
class Router {
private $url, $parsedUrl, $folderNumber, $className, $phpKey;

    /**
     * @param $url
     */
    public function __construct($url) {
	$this->url = $url;
	}

    public function __destruct() {
	$this->url = null;
	$this->parsedUrl = null;
	$this->folderName = null;
	$this->className = null;
	}

    /**
     * @return array
     */
    public function parseUrl() {
	    $this->parsedUrl = parse_url($this->url)['path'];
	    $this->parsedUrl = ltrim($this->parsedUrl,'/');
	    $this->parsedUrl = trim($this->parsedUrl);
	    $this->parsedUrl = explode('/', $this->parsedUrl);
	
		foreach ( $this->parsedUrl as $k => $v )
			if ( preg_match('#.php$#', $v) )
	    		$this->phpKey = $k+1;

		//if ( !$this->phpKey )
		//Red::redirect('./index.php/articles');

	return array_slice($this->parsedUrl, $this->phpKey)
	}

    /**
     * @param db $db
     * @param Smarty $smarty
     * @return mixed
     */
    public function getClassName(db $db, Smarty $smarty) {
	    $this->className = $this->parsedUrl[0];
	    $this->className = str_replace('-', ' ', $this->className);
	    $this->className = ucwords($this->className);
	    $this->className = str_replace(' ', '', $this->className);

        if ( preg_match('#Admin#', $this->className) )
            throw new RouterException('Nelze!');

		if ( $this->className && class_exists($this->className) ) {
    		return new $this->className($db, $smarty);
		}
	}
}
