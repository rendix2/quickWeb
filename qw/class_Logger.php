<?php

class Logger {

    const PATH = './logs/';
    const EXT = '.log';

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

    private function isReadable(){
        if ( !file_exists($this->fileName) )
            throw new LogException('Neexistující soubor chyb.');

        if ( !is_readable($this->fileName) )
            throw new LogException('Ze souboru chyb nelze číst.');

        return true;
    }

    public function log(){
        if ( $this->isWritable() )
            file_put_contents(self::PATH.$this->type-self::EXT, $this->message);
    }

    public function show(){
        if ( $this->isReadable() ){
            return file_get_contents($this->fileName);
        }
    }
}