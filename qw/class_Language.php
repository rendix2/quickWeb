<?php

class Language implements ILanguage {
    private $langName, $lang, $meta, $allPackages;

    const PATH = './lang/';
    const PREFIX_NAME = '/lang';
    const PREFIX_NAME_META = '/langMeta';
    const EXT = '.ini';

    public function __construct($langName) {
        if ( DEBUG )
            cs($this, $this);

        $this->langName = $langName;

        // we dont have any other langs yet
        if ( $this->langName != 'CZ' )
            $this->langName = 'CZ';

        if ( !file_exists(self::PATH.$this->langName) )
            throw new LanguageException('Neexistující celý jazykový balíček: <strong>'.$this->langName.'</strong>.');

        if ( !file_exists(self::PATH.$this->langName.self::PREFIX_NAME.$this->langName.self::EXT) )
            throw new LanguageException('Neexistující jazykový balíček: <strong>'.$langName.'</strong>.');

        if ( !file_exists(self::PATH.$this->langName.self::PREFIX_NAME_META.$this->langName.self::EXT) )
            throw new LanguageException('Neexistující jazykový balíček meta dat: <strong>'.$langName.'</strong>.');

        $this->lang = parse_ini_file(self::PATH.$this->langName.self::PREFIX_NAME.$this->langName.self::EXT);
        $this->meta = parse_ini_file(self::PATH.$this->langName.self::PREFIX_NAME_META.$this->langName.self::EXT);
    }

    public function __destruct() {
        if ( DEBUG )
            echo 'Destruktor: '.get_class($this).'<br>';

        $this->lang = null;
        $this->langName = null;
        $this->meta = null;
    }

    public function languageGetPack() {
    return $this->lang;
    }

    public function languageGetMetaPack(){
        return $this->meta;
    }

    public function languageSetAllPackages() {
        foreach ( glob(self::PATH.'*') as $v )
            $this->allPackages[] = $v;
    }

    public function  languageGetAllPackages() {
        return $this->allPackages;
    }
}