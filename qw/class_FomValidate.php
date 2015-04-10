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
        static $atom = '[-a-z0-9!#$%&\'*+/=?^_`{|}~]';
        static $domain = '[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';

        return preg_match(":^$atom+(\\.$atom+)*@($domain?\\.)+$domain\$:i", $string);
    }

    // +xxx xxx xxx xxx
    private function checkPhoneNumber($string){
        return preg_match($string, '#^\+[0-9]{3,3} [0-9]{3,3} [0-9]{3,3} [0-9]{3,3}$#');
    }

    private function checkMinLength($string, $minLength){
        return mb_strlen($string) < $minLength;
    }

    private function checkMaxLength($string, $maxLength){
        return mb_strlen($string) < $maxLength;
    }
}