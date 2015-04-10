<?php
/**
 * Created by PhpStorm.
 * User: xpy
 * Date: 9.4.15
 * Time: 22:15
 */

final class Session implements Superglobals{

    public static function exists($name){
        return isset($_SESSION[$name]);
    }

    public static function put($name, $value, $expire = null)  {
        return $_SESSION[$name] = $value;
    }

    public static function get($name){
        return $_SESSION[$name];
    }

    public static function delete($name){
        if ( self::exists($name) )
            unset($_SESSION[$name]);
    }
}