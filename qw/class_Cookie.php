<?php
/**
 * Created by PhpStorm.
 * User: xpy
 * Date: 9.4.15
 * Time: 23:02
 */

final class Cookie implements Superglobals{


    public static function exists($name){
        return isset($_COOKIE[$name]);
    }

    public static function get($name){
        return $_COOKIE[$name];
    }

    /**
     * @param $name
     * @param $value
     * @param $expire
     * @return bool
     */
    public static function put($name, $value, $expire){
        return setcookie($name,$value, $expire, '/' ) ? true : false;
    }

    public  static function delete($name){
        self::put($name, '', time()-1);
    }
}