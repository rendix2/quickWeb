<?php
/**
 * Created by PhpStorm.
 * User: xpy
 * Date: 10.4.15
 * Time: 16:00
 */

class Logger {

    private const PATH = './logs/';
    private const EXT = '.log';

    private $type, $message, $fileName;

    public function __construct($message, $type) {
        if ( $type == null )
            throw new LogException('Neznámý typ chyby.');

        $this->fileName = self::PATH.$type.self::EXT;
        $this->message = $message;
    }

    private function isWritable(){
        if ( !file_exists($this->fileName) )
            throw new LogException('Neexistující soubor chyb.');

        if ( !is_writable($this->fileName) )
            throw new LogException('Do souboru chyb nelze zapisovat.');

        return true;
    }

    public function log(){
        if ( $this->isWritable() )
            file_put_contents(self::PATH.$this->type-self::EXT, $this->message);
    }





}