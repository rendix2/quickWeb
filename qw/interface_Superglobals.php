<?php

interface Superglobals{
    public static function exists($name);

    public static function get($name);

    public static  function put($name, $value, $expire = null);

    public static  function delete($name);
}