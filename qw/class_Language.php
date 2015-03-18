<?php

//namespace qw;

/**
 * Class Language
 */
class Language {
    private $langName, $lang, $meta, $allPackages;

    /**
     * @param $langName
     * @throws LanguageException
     */
    public function __construct($langName) {
        if ( DEBUG )
            cs($this, $this);

        $this->langName = $langName;

        if ( $this->langName != 'CZ' )
            $this->langName = 'CZ';

        if ( !file_exists('./lang/'.$this->langName) )
            throw new LanguageException('Neexistující celý jazykový balíček: <strong>'.$this->langName.'</strong>.');

        if ( !file_exists('./lang/'.$this->langName.'/lang'.$this->langName.'.ini') )
            throw new LanguageException('Neexistující jazykový balíček: <strong>'.$langName.'</strong>.');

        $this->lang = parse_ini_file('./lang/'.$this->langName.'/lang'.$this->langName.'.ini');

        if ( !file_exists('./lang/'.$this->langName.'/langMeta'.$this->langName.'.ini') )
            throw new LanguageException('Neexistující jazykový balíček meta dat: <strong>'.$langName.'</strong>.');

        $this->meta = parse_ini_file('./lang/'.$this->langName.'/langMeta'.$this->langName.'.ini');
    }

    /**
     *
     */

    public function __destruct() {
        if ( DEBUG )
            echo 'Destruktor: '.get_class($this).'<br>';

        $this->lang = null;
        $this->langName = null;
        $this->meta = null;
    }

    /**
     * @return array|string
     */
    public function languageGetPack() {
    return $this->lang;
    }

    /**
     * @return array|string
     */
    public function languageGetMetaPack(){
        return $this->meta;
    }

    /**
     *
     */
    public function languageSetAllPackages() {
        foreach ( glob('./lang/*') as $v )
            $this->allPackages[] = $v;
    }

    /**
     * @return array|string
     */
    public function  languageGetAllPackages() {
        return $this->allPackages;
    }
}