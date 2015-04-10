<?php
/**
 * Created by PhpStorm.
 * User: xpy
 * Date: 9.4.15
 * Time: 23:28
 */

final class FormValidate{

    private $db, $language, $smarty;

    public function __construct(db $db, Language $language, Smarty $smarty){
        $this->db = $db;
        $this->language = $language;
        $this->smarty = $smarty;
    }

    private function checkEmailAdress($string){
    }

    private function checkPhoneNumber($string){
        return preg_match($string, '#^+[0-9]{12}$#');
    }

    private function checkMinLength($string, $minLength){
        return mb_strlen($string) < $minLength;
    }

    private function checkMaxLength($string, $maxLength){
        return mb_strlen($string) < $maxLength;
    }
}