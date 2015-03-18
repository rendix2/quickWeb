<?php

namespace qw\nu;

interface iuser {
    public function setUserPassword($userPassword);
    public function setUserName($userPassword);
    public function userValidateMail($email);
}

abstract class User implements iuser {
    private static $userLever;
    private $userName, $userPassword;
    public $userId;

    private const PASSWORD_SALT = '';

    public abstract function userLogin();
    public abstract function userLogout();

    protected function setUserName($userName) {
        if ( empty($userName) )
            throw new UsersException('Prázdné jméno');

        if ( mb_strlen($userName) < USER_NAME_MIN_LENGTH )
            throw new UsersException('Krátké jméno');

        if ( mb_strlen($userName) > USER_NAME_MAX_LENGTH )
            throw new UsersException('Dlouhé jméno');

        $this->userName = $userName;
        return true;
    }

    protected function setUserPassword($userPassword) {
        if ( empty($userPassword) )
            throw new UsersException('Prázdné heslo.');

        if ( mb_strlen($userPassword) < USER_PASSWORD_MIN_LENGTH )
            throw new UsersException('Krátké heslo');

        if ( mb_strlen($userPassword) > USER_PASSWORD_MAX_LENGTH )
            throw new UsersException('Dlouhé heslo');
        /*
                if ( !preg_match('#^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(.){8,}$#', $userPassword) )
                throw new UsersException('Heslo musí obsahovat malá, velká písmena a čísla');
        */

        usleep(250);
        $this->userPassword = hash('sha512', $this->userName.$userPassword.PASSWORD_SALT);
        usleep(250);
        return true;
    }

    public function usersValidateMail($email) {
        $atom = '[-a-z0-9!#$%&\'*+/=?^_`{|}~]';
        $domain = '[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';

        return preg_match(":^$atom+(\\.$atom+)*@($domain?\\.)+$domain\$:i", $email);
    }

}