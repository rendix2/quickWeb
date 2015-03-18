<?php

//namespace qw;

/**
 * Class LanguageAdmin
 */
final class LanguageAdmin extends Language {
    private $langName, $langAdmin, $metaAdmin;

    /**
     * @param $langName
     * @throws LanguageException
     */
    public function __construct($langName) {
        if ( DEBUG )
            cs($this, $this);

        $this->langName = $langName;

        if ( !file_exists('./lang/'.$this->langName.'/langAdmin'.$this->langName.'.ini') )
            throw new LanguageException('Neexistující jazykový balíček administrace: <strong>'.$langName.'</strong>.');

        $this->langAdmin = parse_ini_file('./lang/'.$this->langName.'/langAdmin'.$this->langName.'.ini');

        if ( !file_exists('./lang/'.$this->langName.'/langAdminMeta'.$this->langName.'.ini') )
            throw new LanguageException('Neexistující jazykový balíček meta dat admiostrace: <strong>'.$langName.'</strong>.');

        $this->metaAdmin = parse_ini_file('./lang/'.$this->langName.'/langAdminMeta'.$this->langName.'.ini');
    }

    /**
     *
     */
    public function __destruct() {
        if ( DEBUG )
            echo 'Destruktor: '.get_class($this).'<br>';

        parent::__destruct();
        $this->langName = null;
        $this->langAdmin = null;
        $this->metaAdmin = null;
    }

    /**
     * @return array
     */
    public function languageGetPack() {
        return $this->langAdmin;
    }

    /**
     * @return array
     */
    public function languageGetMetaPack() {
        return $this->metaAdmin;
    }
}