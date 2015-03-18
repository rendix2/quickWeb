<?php

if ( DEBUG )
    error_reporting(E_ALL); // only for development.
else
    error_reporting(0);

session_start();
mb_internal_encoding("UTF-8");
setlocale(LC_ALL, 'cs_CZ');
date_default_timezone_set('Europe/Prague');

include('constants.php');
include('checks.php');

if ( USING_NAME_SPACEC ) {
    spl_autoload_register('autoloaderNameSpaces');

    if ( DEBUG )
        echo 'I am <strong>using</strong> namespaces.<br>';
}
else {
    spl_autoload_register('autoloader');

    if ( DEBUG )
    echo 'I am <strong>not using</strong> namespaces.<br>';
}

include('functions.php');
include('exceptions.php');

if ( file_exists('./Smarty/libs/Smarty.class.php') )
    include('./Smarty/libs/Smarty.class.php');
else
    die('Neexistující soubor šablonového systému Smarty!<br>');

function autoloaderNameSpaces($class)
{
    $class = explode('\\', $class);
    $c = $t = $if = &$class;

    if (count($class) == 1) {
        $c = array('./qw/class_'.$class[0].'.php');
        $t = array('./qw/trait_'.$class[0].'.php');
        $if = array('./qw/interface_'.$class[0].'.php');
    }
    else {
        $p = $class[count($class) - 1];
        $p1 = count($class) - 1;

        $c[0] = './'.$c[0];
        $t[0] = './'.$t[0];
        $if[0] = './'.$if[0];

        $c[$p1] = 'class_'.$p.'.php';
        $t[$p1] = 'trait_'.$p.'.php';
        $if[$p1] = 'interface_'.$p.'.php';
    }

    $c = implode('/',$c);
    $t = implode('/',$t);
    $if = implode('/',$if);

    //echo $c.'<br>';
    //echo $t.'<br>';
    //echo $if.'<br>';

    if ( file_exists($c) && is_readable($c) )
        require_once($c);
    else if ( file_exists($t) && is_readable($t) )
        require_once($t);
    else if ( file_exists($if) && is_readable($if) )
        require_once($if);
    else
        die('Subor modulu ' .  $class . ' neni čitelný nebo neexistuje . ');

}

function autoloader($class) {
    // Smarty templates
    if ( preg_match('#Smarty#', $class) ) {
        if ( file_exists('./Smarty/libs/Smarty.class.php') )
            require_once('./Smarty/libs/Smarty.class.php');
        else
            die('Neexistující soubor šablonového systému Smarty!<br>');
    }
    // my normal classes
    else {
        // class
        if ( file_exists('./qw/class_'.$class.'.php') )
            require_once('./qw/class_'.$class.'.php');
        // trait
        else if ( file_exists('./qw/trait_'.$class.'.php') )
            require_once('./qw/trait_'.$class.'.php');
        // interface
        else if ( file_exists('./qw/interface_'.$class.'.php') )
            require_once('./qw/interface_'.$class.'.php');
        else
            die('<br><br><div align="center">Neexistující modul nebo soubor modulu: '.$class.'.</div>');
   }
}

?>

