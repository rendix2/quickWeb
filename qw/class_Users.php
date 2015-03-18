<?php

//namespace qw;

/**
 * Class Users
 */
class Users {
    protected $userName, $userPassword, $userId $userData;
    public $userLogged;
    protected $db, $smarty, $language;

    /**
     * @param db $db
     * @param Language $language
     * @param Smarty $smarty
     */
    public function __construct(db $db, Language $language, Smarty $smarty) {
        if ( DEBUG )
            cs($language, $this);

        $this->db = $db;
        $this->language = $language;
        $this->smarty = $smarty;

        $this->userName = '';
	    $this->userPassword = '';
	    $this->userData = array();
	}

    /**
     *
     */
    public function  __destruct()
    {
        if ( DEBUG )
            echo 'Destruktor: '.get_class($this).'<br>';

        $this->language = null;
        $this->smarty = null;
        $this->db = null;
        $this->userData = null;
        $this->userName = null;
        $this->userPassword = null;
    }

    /**
     * @return bool
     * @throws UsersException
     */
    public function usersActivate() {
		if ( empty($_GET['user_register_hash']) )
		    throw new UsersException('Prazdný aktivační kód.');

    	$this->db->query("SELECT user_activation_time FROM ".USERS_TABLE." WHERE user_id = :user_id AND user_activation_code = :user_activation_code AND user_active = 0 LIMIT 1;", __FILE__, __LINE__, array(
		'user_id' => $_GET['user_id'],
		'user_activation_code' => $_GET['user_register_hash']));

		if ( !$this->db->numRows() )
		    throw new UsersException('Neexistující aktivační kód nebo uživatel nebo jsi již aktivovaný.');

		if ( time() > ( $this->db->fetch()['user_activation_time'] + 259200 ) )
		    throw new UsersException('Neplatný aktivační kód.');

    	$this->db->query("UPDATE ".USERS_TABLE." SET user_active = 1, user_activation_code = '', user_activation_time = 0 WHERE user_id = :user_id LIMIT 1;", __FILE__, __LINE__, array(
    	'user_id' => $_GET['user_id'] ));

		if ( $this->db->numRows() )
		    echo 'Aktivace byla úspěšná.';
		else
		    throw new UsersException('Aktivace se nezdařila!');

    	return true;
	}

	// setters begin
    /**
     * @param $userName
     * @return bool
     * @throws UsersException
     */
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

    /**
     * @param $userPassword
     * @return bool
     * @throws UsersException
     */
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
	// setters end

    /**
     *
     */
    protected function usersDoRedirect() {
	    $link = $_SERVER['REDIRECT_QUERY_STRING'];

		if ( preg_match('#[&?]redirect=[a-zA-Z0-9@$-]#', $link, $matches) ) {
    		$link = preg_replace('#@#','&', $link);
    		$link = preg_replace('#-#','=', $link);
    		$link = preg_replace('#$#','?', $link);
		}
	}

    /**
     * @throws UsersException
     */
    public final function usersMemberList() {
	    $this->db->query("SELECT user_id, user_name, user_active FROM ".USERS_TABLE." ORDER BY user_id ASC;", __FILE__, __LINE__);

        if ( !$this->db->numRows() )
            throw new UsersException('Žádní uživatelé');

	    $this->smarty->assign('users', $this->db->fetchAll());
        $this->smarty->assign('lang', $this->language->languageGetPack());
	    $this->smarty->display('memberlist.tpl');
	    $this->db->freeResult();
	}

    /**
     * @return bool
     * @throws UsersException
     */
    public function usersLogin() {
		if ( $_SESSION['user']['user_logged'] )
		    throw new UsersException('Již jsi přihlášený');

	    $this->smarty->assign('u', array (
		    'user_name' => $_POST['user_name'],
		    'user_name_max_length' => USER_NAME_MAX_LENGTH,
		    'user_password_max_length' => USER_PASSWORD_MAX_LENGTH ));

        $this->smarty->assign('lang', $this->language->languageGetPack());
	    $sf = $this->smarty->display('userLogin.tpl', true);

		if ( isset($_POST['submit']) ) {
			try {
			    $sf->doChecks();
			}
			catch ( FormException $e ) {
			    throw new UsersException($e->getMessage()); // WODO :O :O
			}

			if ( !$this->setUserName($_POST['user_name']) || !$this->setUserPassword($_POST['user_password']) )
			return false;

		$this->db->query("SELECT u.user_id, u.user_name, u.user_password, 
					u.user_author, u.user_active, u.user_template,
					u.user_last_login, u.user_login_count, u.user_show_article_time,
					u.user_show_article_readings, u.user_show_article_edited, u.user_show_article_author,
					u.user_show_comments_count, t.template_name
				FROM ".USERS_TABLE." u
				JOIN ".TEMPLATES_TABLE." t
				ON t.template_id = u.user_template 
				WHERE user_name = :user_name
				LIMIT 1;", __LINE__, __FILE__, array ( 'user_name' => $this->userName ));

			if ( $this->db->numRows() <= 0 )
			    throw new UsersException('Nenalezený účet');

		$this->userData = $this->db->fetch();

		// check login count begin
			if ( $this->userData['user_login_count'] >= MAX_LOGIN_TRIES )
				if ( ( $this->userData['user_last_login'] + MAX_LOGIN_TIME ) < time() )
				$this->db->query("UPDATE ".USERS_TABLE." SET user_last_login = :time, user_login_count = 0 WHERE user_id = :user_id LIMIT 1;", __LINE__, __FILE__, array (
				'time' => time(),
				'user_id' => $this->userData['user_id']) );
				
				else
    				throw new UsersException('Překročen limit <b>'.MAX_LOGIN_TRIES.'</b> pokusů o přihlášení.<br> Další přihlášení bude umožněno <b>'
    				.date('H:i d.m.Y', ( $this->userData['user_last_login'] + MAX_LOGIN_TIME )).'</b>
	    			<br>Pozor! Každým dalším pokusem se posunuje čas povolení přihlášení i se zadáním správného hesla.');

		$this->db->query("UPDATE ".USERS_TABLE." SET user_last_login = :time, user_login_count = user_login_count + 1 WHERE user_id = :user_id LIMIT 1;", __LINE__, __FILE__, array (
		    'time' => time(),
            'user_id' => $this->userData['user_id'] ));
		// check login count end

			if ( !( $this->userData['user_name'] === $this->userName ) )
    			throw new UsersException('Nesprávné uživatelské jméno');

			if ( !$this->userData['user_active'] )
			    throw new UsersException('Účet není aktivní!');

			if ( !( $this->userData['user_password'] === $this->userPassword ) )
			    throw new UsersException('Nesprávné heslo');

		    // reset login count begin
		    $this->db->query("UPDATE ".USERS_TABLE." SET user_last_login = :time, user_login_count = 0 WHERE user_id = :user_id;", __LINE__, __FILE__, array (
		        'time' => time(),
		        'user_id' => $this->userData['user_id'] ));
		    // reset login count end

		    for ( $i = 0; $i <= rand(6,32); $i++ )
		        session_regenerate_id(true);

		    unset($this->userData['user_password']);
		    $_SESSION['user'] = $this->userData;
		    $_SESSION['user']['user_logged'] = true;
		    $this->userData = array ();

		    echo 'Přihlášen';
		    Red::redirect('./');
		    return true;
		}
	}

    /**
     * @throws UsersException
     */
    public function usersLogout() {
        if ( !$_SESSION['user']['user_logged'] )
		    throw new UsersException('Nejsi přihlášený!');

		unset($_SESSION['user']);
		echo 'Úspěšně odhlášen!';
		Red::redirect('./');
	}

    /**
     * @throws UsersException
     */
    protected function usersFullLogout() {
		if ( !$_SESSION['user']['user_logged'] )
    		throw new UsersException('Nejsi přihlášený!');

		if ( session_destroy() )
		    echo 'Úspěšně odhlášen!';
		else
		    throw new UsersException('Nepodařilo se odhlášení');

        Red::redirect('./');
	}

	// function Jakub Vrana php.vrana.cz
	// edited http://milsa.info/programovanie/internet/94-kontrola-emailovej-adresy-pomocou-preg-match
    /**
     * @param $email
     * @return int
     */
    protected function usersValidateMail($email) {
	 static $atom = '[-a-z0-9!#$%&\'*+/=?^_`{|}~]';
	 static $domain = '[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';

	    return preg_match(":^$atom+(\\.$atom+)*@($domain?\\.)+$domain\$:i", $email);
	}

    /**
     * @throws UsersException
     */
    public function usersRegister() {
		if ( !($this instanceof Admin) && $_SESSION['user']['user_logged'] )
    		throw new UsersException('Bohužel, jste přihlášený.');

        $this->smarty->assign('lang', $this->language->languageGetPack());
	    $this->smarty->assign( 'userRegister', array (

    	'user_name' => $_POST['user_name'],
    	'user_mail' => $_POST['user_mail'],
	     ));

		if ( $this instanceof Admin )
	    	$this->smarty->assign('load', 'userRegister.tpl');
		else
    		$sf = $this->smarty->display('userRegister.tpl', true);

		if ( isset($_POST['submit']) ) {
			if ( !( $this instanceof Admin ) )
				try {
	    			$sf->doChecks();
				}
				catch ( FormException $e ) {
		    		throw new UsersException($e->getMessage());
				}

			if ( !$this->setUserName($_POST['user_name']) )
    			return false;

			if ( empty($_POST['user_mail']) )
	    		throw new UsersException('Prázdný uživatelský e-mail.');

			if ( !$this->setUserPassword($_POST['user_password']) )
		    	return false;

			if ( empty($_POST['user_password_check']) )
			    throw new UsersException('Prázdné heslo pro kontrolu.');

			if ( !$this->usersValidateMail($_POST['user_mail']) )
			    throw new UsersException('Uživatelský e-mail není ve správném tvaru.');

			if ( !( $_POST['user_password'] === $_POST['user_password_check'] ) )
			    throw new UsersException('Uživatelská hesla se neshodují.');

		    $this->db->query("SELECT 1 FROM ".USERS_TABLE." WHERE user_name = :user_name LIMIT 1;", __LINE__, __FILE__, array ( 'user_name' => $this->userName));

			if ( $this->db->numRows() )
    			throw new UsersException('Uživatelské jméno '.$this->userName.' je již obsazeno.');

	    	$this->db->freeResult();
    		$this->db->query("SELECT 1 FROM ".USERS_TABLE." WHERE user_mail = :user_mail LIMIT 1;", __LINE__, __FILE__, array ( 'user_mail' => $_POST['user_mail']));

			if ( $this->db->numRows() )
	    		throw new UsersException('Uživatelský e-mail '.$_POST['user_mail'].' je již obsazen.');

    		$this->db->freeResult();
	    	$this->userData['hash'] = hash('sha512', uniqid(true));

    		$this->db->query("INSERT INTO ".USERS_TABLE." (user_name, user_password, user_regdate, user_active, user_template, user_mail, user_activation_code, user_activation_time)
	        		VALUES (:user_name, :user_password, :time, 0, 1, :user_mail, :user_register_hash, :time);", __LINE__, __FILE__, array (

		            'user_name' => $this->userName,
		            'user_password' => $this->userPassword,
		            'user_mail' => $_POST['user_mail'],
		            'user_register_hash' => $this->userData['hash'],
		            'time' => time()
		            ));

            if ( $this->db->numRows() ) {
			    $this->smarty->assign('ua', array ( 'user_name' => $this->userName, 'web_adress' => WEB_ADRESS, 'register_hash' => $this->userData['hash'], 'user_id' => $this->db->lastId() ));
			    $emailer = new Emailer($this->userName, $_POST['user_mail'], 'Registrace uživatele', $this->smarty->fetch('./emails/user_activation.tpl'));

				if ( $emailer->sendMail() ) {
    				am('Registrace uživatele <b>'.$this->userName.'</b> byla úspěšná.', 'g');

					if ( $this instanceof Admin )
				    	Red::redirect('./admin.php');
   					else
    					Red::redirect('./');
				}
				else
				    throw new UsersException('Nepodařilo se odeslat aktivační email');
			}
			else if ( !$this->db->numRows() )
			    throw new UsersException('Registrace uživatele <b>'.$this->userName.'</b> se nezdařila.');
		}
	}

    /**
     *
     */
    public function usersProfileShow() {
        $this->smarty->assign('lang',$this->language->languageGetPack());
	    $this->db->query("SELECT user_name, user_active, user_author, user_regdate, user_last_login FROM ".USERS_TABLE." WHERE user_id = :user_id LIMIT 1;", __FILE__, __LINE__, array( 'user_id' => $_GET['user_id']));
    	$this->smarty->assign('user', $this->db->fetch());
	    $this->db->freeResult();
	    $this->db->query("SELECT comment_text, comment_time FROM ".ARTICLES_COMMENTS_TABLE." WHERE user_id = :user_id ORDER BY comment_id DESC LIMIT 5;", __FILE__, __LINE__, array( 'user_id' => $_GET['user_id']));
	    $this->smarty->assign('comment', $this->db->fetchAll());
	    $this->db->freeResult();
	    $this->db->query("SELECT article_text, article_time FROM ".ARTICLES_TABLE." WHERE user_id = :user_id ORDER BY article_id DESC LIMIT 5;", __FILE__, __LINE__, array( 'user_id' => $_GET['user_id']));
	    $this->smarty->assign('article', $this->db->fetchAll());
	    $this->db->freeResult();
	    $this->smarty->display('userProfile.tpl');
	}

    private function usersSetAutoLoginCookie(){
        // 2592000 = 1 month
        setcookie('user_autologin',$this->userName.'#'.$this->userId,time()+2592000, '/', WEB_ADRESS, true);
    }

    private function usersAutoLogin() {

        if ( isset($_COOKIE['user_autologin']) ){
            $this->db->query("SELECT user_id, user_name FROM ".USERS_TABLE." WHERE user_id = :user_id AND user_name = :user_name LIMIT 1;", __FILE__, __LINE__, array( 'user_id' => $this->userId, 'user_name' => $this->userName ));

            $_SESSION['user'] = $this->db->fetch();
        }
    }
}
