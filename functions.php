<?php

/**
 * @param Smarty $smarty
 * @param array $data
 * @param array $lang
 * @param array $meta
 * @return bool
 */
function my_page_header(Smarty $smarty, array $data, array $lang, array $meta, $admin = false) {
    $smarty->assign('ph', $data);
    $smarty->assign('meta', $meta);
    $smarty->assign('DEBUG', DEBUG);
    $smarty->display('pageHeader.tpl', false);


    if ( $admin == false )
	    if ( $_SESSION['user']['user_logged'] ) {
    	    $smarty->assign('user_name', $data['user_name']);
            $smarty->assign('lang', $lang);
    	    $smarty->display('pageHeaderLoggedIn.tpl', false);
    	}
    	else {
            $smarty->assign('lang', $lang);
            $smarty->display('pageHeaderLoggedOut.tpl', false);
        }

    return true;
}

/**
 * @param Smarty $smarty
 * @param array $data
 * @param array $lang
 */
function my_page_footer(Smarty $smarty, array $data, array $lang) {
    $smarty->assign('s_ulogged', $_SESSION['user']['user_logged']);
    $smarty->assign('s_uauthor', $_SESSION['user']['user_author']);
    $smarty->assign('lang', $lang);
    $smarty->assign('DEBUG', DEBUG);

        if ( DEBUG )
            $smarty->assign('php_version', phpversion());

    $smarty->assign('pf', $data);
    $smarty->display('pageFooter.tpl', false);
}

// print unbuffered error
/**
 * @param Smarty $smarty
 * @param $message
 * @param string $type
 * @return bool
 */
function p(Smarty $smarty, $message, $type = 'b') {
    $smarty->assign('message', $message);

	if ( $type == 'b' )
	$smarty->display('messageB.tpl');
	else if ( $type == 'g' )
	$smarty->display('messageG.tpl');
	else
	return false;
	
return true;		
}

/**
 * @param $min
 * @param $max
 * @param $type
 * @param $string
 * @param $name
 * @return bool
 * @throws CheckInputException
 */
function checkInput($min, $max, $type, $string, $name){
    if ( mb_strlen($string) < $min )
        throw new CheckInputException('Vstup: <strong>'.$name.'</strong> je kratký.');

    if ( mb_strlen($string) > $max )
        throw new CheckInputException('Vstup: <strong>'.$name.'</strong> je dlouhý.');

    if ( !preg_match('#'.$type.'#', $string) )
        throw new CheckInputException('Vstup: <strong>'.$name.'</strong> má nesprávný tvar.');

    return true;
}

// add message to buffer
/**
 * @param $message
 * @param string $type
 * @return bool
 */
function am($message, $type = 'b') {
	if ( $type == 'b' )
	$_SESSION['buff_mess']['bad'][] = $message;
	else if ( $type == 'g' )
	$_SESSION['buff_mess']['good'][] = $message;	
	else 
	return false;

    return true;
}

// show buffered message
/**
 * @param Smarty $smarty
 * @return bool
 */
function sm(Smarty $smarty) {
	if ( count($_SESSION['buff_mess']['good']) ) {
    	$smarty->assign('good', $_SESSION['buff_mess']['good']);
    	$smarty->display('messageG.tpl');
	    $_SESSION['buff_mess']['good'] = array();
        return true;
	}
		
	if ( count($_SESSION['buff_mess']['bad']) ) {	
	    $smarty->assign('bad', $_SESSION['buff_mess']['bad']);
	    $smarty->display('messageB.tpl');
	    $_SESSION['buff_mess']['bad'] = array();
	    return true;
	}
	
    return false;
}

function asm(Smarty $smarty, $message, $type = 'b') {
    am($message, $type);
    sm($smarty);
    return true;
}

// return fb like date
function my_date($time) {
}

// http://www.abclinuxu.cz/poradna/programovani/show/302447
// withour for loop, using log()
/**
 * @param $bytes
 * @return string
 */
function fsize_unit_convert($bytes) {
static $units = array(
        array(  ' B', 1),
        array(' KiB', 1024.),
        array(' MiB', 1048576.),
        array(' GiB', 1073741824.),
        array(' TiB', 1099511627776.),
);

$log = (int) log($bytes, 2) / 10;

return round($bytes / $units[$log][1], 2).$units[$log][0];
}

/**
 * @return string
 */
function my_hash() {
    $hash = '';

    for ( $i = 0; $i <= 15; $i++ )
        $hash = iniqueid(true);

return hash('sha512', $hash);
}

/**
 * @return array
 */
function usedRAM() {
return array( fsize_unit_convert(memory_get_peak_usage(0)), fsize_unit_convert(memory_get_usage(0)), fsize_unit_convert(return_bytes(ini_get('memory_limit'))) );
}

// http://stackoverflow.com/questions/6846445/get-byte-value-from-shorthand-byte-notation-in-php-ini
/**
 * @param $val
 * @return int|string
 */
function return_bytes($val) {
$val = trim($val);
$last = strtolower($val[strlen($val)-1]);

	switch($last) {
        // The 'G' modifier is available since PHP 5.1.0
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }

return $val;
}

/**
 * @param Language $language
 * @param $o
 * @return string
 */
function cs(Language $language, $o){
    return sprintf('%s: <strong>%s</strong><br>', $language->languageGetPack()['construct'], get_class($o));
}

/**
 * @param Language $language
 * @param $o
 * @return string
 */
function ds(Language $language, $o){
    return sprintf('%s: <strong>%s</strong><br>', $language->languageGetPack()['destruct'], get_class($o));
}

?>
