<?php

namespace qw\nu;

/**
 * Class PoolsAdmin
 */
class PoolsAdmin extends Pools {
private $db, $smarty;

	public function __construct(db $db, Smarty $smarty) {
	$this->db = $db;
	$this->smarty = $smarty;

        if ( !$_SESSION['admin']['user_logged'] )
            throw new UsersException('Nejsi přihlášený');

	}

    /**
     * @throws CheckInputException
     * @throws PoolsException
     */
    public function PoolsAdd(){
        if ( isset($_POST['submit']) ) {
            try {
                checkInput(3, 15, '^[a-zA-Z0-9]$', $_POST['pool_name']);
            } catch (PoolsException $e) {
                echo $e->getMessage();
            }

            $this->db->query("INSERT INTO " . POOLS_TABLE . " (pool_name) VALUES (:pool_name);", __FILE__, __FILE__, array('pool_name' => $_POST['pool_name']));

            if ($this->db->numRows()){
                am('Anketa přidána', 'g');
            return $this->db->numRows();
            }
            else
                throw new PoolsException('Nepodařilo se přidat Anketu.');
        }
    }
}
