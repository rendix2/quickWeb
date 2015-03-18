<?php

//namespace qw;

// redirect

/**
 * Class Red
 */
class Red {
    /**
     * @return mixed|string
     */
    public static function getURLRedirect() {
		if ( preg_match('#[\?&]{0,1}redirect=[a-zA-Z0-9@\!-._+]*#', $_SERVER['REDIRECT_QUERY_STRING'], $matches) ) {
		    $link = $matches[0];
		    $link = preg_replace('#@#','&', $link);
		    $link = preg_replace('#-#','=', $link);
		    $link = preg_replace('#!#','?', $link);
		    $link = substr($link, 9);
		    return $link;
		}
	}

    /**
     * @return mixed
     */
    public static function setURLRedirect() {
	    $link = $_SERVER['REQUEST_URI'];

		if ( $link ) {
    		$link = preg_replace('#&#','@', $link);
    		$link = preg_replace('#=#','-', $link);
    		$link = preg_replace('&\?&','!', $link);

	    	return $link;
		}
	}

    /**
     * @param $url
     * @param float $time
     */
    public static function redirect($url, $time = 0.0001) {
	    $page = '';

		if ( headers_sent() ) {
    		$time = $time * 1000;
		/*

			if ( count($_GET) > 1 ) {
				if ( $_GET['page']  )
				$page = '&page='.$_GET['page'];
			}
			else if ( count($_GET) <= 1 )
				if ( $_GET['page']  )
				$page = '?page='.$_GET['page'];
				*/

//		echo '<script type="text/javascript">window.setTimeout(function() {window.location = "'.WEB_ADRESS.'/'.$url.$page.'"; location.href="'.WEB_ADRESS.'/'.$url.$page.'";}, '.$time.');</script>';
    		echo '<script type="text/javascript">window.setTimeout(function() {window.location = "'.$url.'"; location.href="'.$url.'";}, '.$time.');</script>';
    		exit();
		}
		else {
		/*
			if ( count($_GET) > 1 ) {
				if ( $_GET['page']  )
				$page = '&page='.$_GET['page'];
			}
			else if ( count($_GET) <= 1 )
				if ( $_GET['page']  )
				$page = '?page='.$_GET['page'];
				*/

    		header("Location:".$url);
    		exit();
		}
	}
}
