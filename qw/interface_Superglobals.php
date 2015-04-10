<?php
/**
 * Created by PhpStorm.
 * User: xpy
 * Date: 9.4.15
 * Time: 22:56
 */

interface Superglobals{

    /**
     * @param $name
     * @return mixed
     */
    public function exists($name);

    /**
     * @param $name
     * @return mixed
     */
    public function get($name);

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function put($name, $value);

    /**
     * @param $name
     * @return mixed
     */
    public function delete($name);

}