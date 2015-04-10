<?php
/**
 * Created by PhpStorm.
 * User: xpy
 * Date: 10.4.15
 * Time: 0:45
 */

class FactoryForm {

    private $safeForm, $validateForm;

    public function  __construct(db $db, Language $language, Smarty $smarty, $formName)
    {
        $this->safeForm = new FormSafe($smarty,$formName);
        $this->safeForm = new FormValidate($db, $language, $smarty);
    }

    public function  getSafeForm(){
        return $this->safeForm;
    }

    public function getValidateForm(){
        return $this->validateForm;
    }


}