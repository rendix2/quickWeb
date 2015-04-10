<?php

final class LanguageAdmin extends Language {
    private $langName, $langAdmin, $metaAdmin;

    const PATH = './lang/';
    const PREFIX_NAME = '/langAdmin';
    const PREFIX_NAME_META = '/langAdminMeta';
    const EXT = '.ini';

    public function __construct($langName) {
        if ( DEBUG )
            cs($this, $this);

        $this->langName = $langName;

        if ( !file_exists(self::PATH.$this->langName.self::PREFIX_NAME.$this->langName.self::EXT) )
            throw new LanguageException('Neexistující jazykový balíček administrace: <strong>'.$langName.'</strong>.');

        if ( !file_exists(self::PATH.$this->langName.self::PREFIX_NAME_META.$this->langName.self::EXT) )
            throw new LanguageException('Neexistující jazykový balíček meta dat admiostrace: <strong>'.$langName.'</strong>.');

        $this->langAdmin = parse_ini_file(self::PATH.$this->langName.self::PREFIX_META.$this->langName.self::EXT);
        $this->metaAdmin = parse_ini_file(self::PATH.$this->langName.self::PREFIX_NAME_META.$this->langName.self::EXT);
    }

    public function __destruct() {
        if ( DEBUG )
            echo 'Destruktor: '.get_class($this).'<br>';

        parent::__destruct();
        $this->langName = null;
        $this->langAdmin = null;
        $this->metaAdmin = null;
    }

    public function languageGetPack() {
        return $this->langAdmin;
    }

    public function languageGetMetaPack() {
        return $this->metaAdmin;
    }
}