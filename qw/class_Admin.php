<?php

//namespace qw;

/**
 * Class Admin
 */
final class Admin extends Users {

use Ordering;
use Pagination;

    /**
     * @param db $db
     * @param Language $language
     * @param Smarty $smarty
     */
    public function __construct(db $db, Language $language, Smarty $smarty) {
        if ( DEBUG )
            echo cs($language, $this);

        parent::__construct($db, $language, $smarty);
    }

    /**
     *
     */
    public function __destruct() {
        if ( DEBUG )
            echo 'Destruktor: '.get_class($this).'<br>';

        parent::__destruct();
    }

    /**
     * @return bool
     * @throws UsersException
     */

    public function userLogin() {
		if ( $_SESSION['admin']['user_logged'] )
    		throw new UsersException('Jsi přihlášený');

		for ( $i = 0; $i <= rand(6,32); $i++ )
	    	session_regenerate_id(true);

	    $sf = new SafeForm($this->smarty, 'userLogin.tpl');
	    $this->smarty->display('userLogin.tpl', false);

		if ( isset($_POST['submit']) ) {
			try {
			    $sf->doChecks();
			}
			catch ( FormException $e ) {
			    throw new UsersException($e->getMessage());
			}

			if ( !$this->setUserName($_POST['user_name']) || !$this->setUserPassword($_POST['user_password']) )
			    return false;
			
		    $this->db->query("SELECT user_id, user_name, user_password, user_author, user_active, user_last_login, user_login_count FROM ".USERS_TABLE." WHERE user_name = :user_name LIMIT 1;", __LINE__, __FILE__, array( 'user_name' => $this->userName ));
		
			if ( $this->db->numRows() <= 0 )
		    	throw new UsersException('Nenalezený účet');

    		$this->userData = $this->db->fetch();

			if ( !$this->userData['user_author'] )
			    throw new UsersException('Nejsi admin!');

		    // check login count begin
			if ( $this->userData['user_login_count'] >= MAX_LOGIN_TRIES )
				if ( ( $this->userData['user_last_login'] + MAX_LOGIN_TIME ) < time() )
    				$this->db->query("UPDATE ".USERS_TABLE." SET user_last_login = :time, user_login_count = 0 WHERE user_id = :user_id; LIMIT 1", __LINE__, __FILE__, array( 'time' => time(), 'user_id' => $this->userData['user_id']) );
				else
	    			throw new UsersException('Překročen limit <b>'.MAX_LOGIN_TRIES.'</b> pokusů o přihlášení.<br> Další přihlášení bude umožněno <b>'.date('H:i d.m.Y', ( $this->userData['user_last_login'] + MAX_LOGIN_TIME )).'</b><br>Pozor! Každým dalším pokusem se posunuje čas povolení přihlášení i se zadáním správného hesla.');

		    $this->db->query("UPDATE ".USERS_TABLE." SET user_last_login = :time, user_login_count = user_login_count + 1 WHERE user_id = :user_id; LIMIT 1", __LINE__, __FILE__, array( 'time' => time(), 'user_id' => $this->userData['user_id'] ));
		    // check login count end

			if ( !( $this->userData['user_name'] === $this->userName ) )
		    	throw new UsersException('Nesprávné uživatelské jméno');

			if ( !$this->userData['user_active'] )
			    throw new UsersException('Účet není aktivní!');

			if ( !( $this->userData['user_password'] === $this->userPassword ) )
			    throw new UsersException('Nesprávné heslo');

		    // reset login count begin
	    	$this->db->query("UPDATE ".USERS_TABLE." SET user_last_login = :time, user_login_count = 0 WHERE user_id = :user_id LIMIT 1;", __LINE__, __FILE__, array( 'time' => time(), 'user_id' => $this->userData['user_id'] ));

            if ( !$this->db->numRows() )
                throw new UsersException('Nepodařilo se zresetovat údaje o přihlášení');
		    // reset login count end

		    unset($this->userData['user_password']);

		    $_SESSION['admin'] = $this->userData;
		    $_SESSION['admin']['user_logged'] = true;
		    $this->userData = array();

		    am('Přihlášen', 'g');
		    Red::redirect('./admin.php');
		    return true;
		}
	}

    /**
     * @throws UsersException
     */
    public function userLogout() {
		if ( !$_SESSION['admin']['user_logged'] )
	    	throw new UsersException('Nejsi přihlášený!');

		unset($_SESSION['admin']);
		echo 'Úspěšně odhlášen!';
		Red::redirect('./');
	}

    /**
     * @throws UsersException
     */
    public function adminShowUsers(){
    //	$order = $this->order(array('Uživatelské jméno' => 'user_name', 'Uživatelské ID' => 'user_id'));
	    $this->db->query("SELECT SQL_CALC_FOUND_ROWS user_id, user_name, user_author FROM ".USERS_TABLE." ORDER BY user_id ASC LIMIT ".ADMIN_USERS_PAGE_LIMIT." OFFSET ".ADMIN_USERS_PAGE_LIMIT * $_GET['page'].";", __FILE__, __LINE__);

        if ( !$this->db->numRows() )
            throw new UsersException('Žádní uživatelé');

	    $this->smarty->assign('load', 'usersShow.tpl');
	    $this->smarty->assign('users', $this->db->fetchAll());
	    $this->pagination(ADMIN_USERS_PAGE_LIMIT);
	    $this->db->freeResult();
	    $this->db->query("SELECT COUNT(*) as active FROM ".USERS_TABLE." WHERE user_active = 1;", __FILE__, __LINE__);
	    $this->smarty->assign('active', $this->db->fetch()['active']);
	    $this->db->freeResult();
	    $this->db->query("SELECT COUNT(*) as non_active FROM ".USERS_TABLE." WHERE user_active = 0;", __FILE__, __LINE__);
	    $this->smarty->assign('non_active', $this->db->fetch()['non_active']);
	    $this->db->freeResult();
	}

    /**
     * @param $admin
     */
    private function adminSetRight($admin) {
	}
}