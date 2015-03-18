<?php

/*
namespace qw;

use \Smarty as Smarty;
use \PDO as PDO;
*/

// http://www.itnetwork.cz/php-prace-s-mysql-pouziti-ovladace-pdo
/**
 * Class db
 */
class db {
    private $dbDataBaseName, $dbConnection, $result, $queryCount, $smarty;

    /**
     * @param $dbServer
     * @param $dbUserName
     * @param $dbUserPassword
     * @param $dbDataBaseName
     * @param Smarty $smarty
     */
    public function __construct($dbServer, $dbUserName, $dbUserPassword, $dbDataBaseName, Smarty $smarty) {
        /*
                if ( DEBUG )
                    cs($language, $this);
        */
    	$this->dbDataBaseName = $dbDataBaseName;
    	$this->queryCount = 0;
    	$this->smarty = $smarty;

		try {
    		$this->dbConnection = new PDO("mysql:host=".$dbServer.";dbname=".$this->dbDataBaseName.";charset=utf8", $dbUserName, $dbUserPassword, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		}
		catch ( PDOException $e ) {
    		my_page_header($this->smarty);

			if ( $e->getCode() == 1045 )
    			echo "Nesprávné údaje pro přihlášení k DB serveru: <b>".$dbServer."</b>";
			else if ( $e->getCode() == 2002 )
	    		echo "Nepodařilo se připojit k DB serveru: <b>".$dbServer."</b>";
			else if ( $e->getCode() == 1044 )
    			echo "Nepodařilo se vybrat databázi na DB serveru: <b>".$dbServer."</b>";
			else
    			echo "Neočekávaná chyba při připojení DB serveru: <b>".$dbServer."</b>";

    		my_page_footer($this->smarty, array ( 'pg_query' => 0, 'used_ram' => usedRAM()));
		}
	}

    /**
     *
     */
    public function __destruct() {
        if ( DEBUG )
            echo 'Destruktor: '.get_class($this).'<br>';

	    $this->dbConnection = null;
        $this->dbDataBaseName = null;
        $this->smarty = null;
        $this->result = null;
	}

    /**
     * @param $query
     * @param $file
     * @param $line
     * @param array $params
     */
    public function query($query, $file, $line, $params = array())
	{
	$this->result = $this->dbConnection->prepare($query);

		try {
            if ( DEBUG )
    		      $this->queryCount++;

		$this->result->execute($params);
		}
		catch ( PDOException $e ) {
            if ( DEBUG ) {
                $this->smarty->assign('dbQE', array('line' => $line, 'file' => $file, 'error_number' => $this->result->errorInfo()[1], 'error_text' => $this->result->errorInfo()[2]));
                $this->smarty->display('dbQeuryException.tpl');

                // do log
                file_put_contents('./sql.txt', 'Line: ' . $line . '; File: ' . $file . '; Error number: ' . $this->result->errorInfo()[1] . '; Error text: ' . $this->result->errorInfo()[2] . ";\n", FILE_APPEND);
            }
            else
                echo 'Došlo k chybě ve spojení s databází. Po zjištení povolte DEBUG';

		    my_page_footer($this->smarty, array ('pg_query' => $this->queryCount,'used_ram' => usedRAM()));
		}
	}

    /**
     * @return bool
     */
    public function numRows() {
	    return $this->result ? $this->result->rowCount() : false;
	}

	// if need go throw array only once
    /**
     * @return bool|string
     */
    public function fetch() {
	    return $this->result ? $this->result->fetch(PDO::FETCH_ASSOC) : false;
	}

	// if need go throw array not only once
    /**
     * @return bool
     */
    public function fetchAll() {
	    return $this->result ? $this->result->fetchAll(PDO::FETCH_ASSOC) : false;
	}

    /**
     * @return bool
     */
    public function fetchColumn() {
        return $this->result ? $this->result->fetch(PDO::FETCH_ASSOC)[0] : false;
    }

    /**
     * @param $rowNumber
     */
    public function dataSeek($rowNumber) {
		if ( !$this->result || !is_numeric($rowNumber)  )
    		return false;

	    $this->result->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_LAST, $rowNumber);
	}

    /**
     *
     */
    public function freeResult() {
		if ( !$this->result )
	    	return false;

	    $this->result->closeCursor();
	    $this->result = null;
	}

    /**
     * @return string
     */
    public function lastId() {
	    return $this->dbConnection->lastInsertId();
	}

    /**
     * @return int|PDO|Smarty
     */
    public function getQueryCount() {
	    return  DEBUG ? $this->queryCount : 0;
	}

    /**
     * @return array|bool
     */
    public function getError() {
	    return $this->result ? $this->result->errorInfo() : false;
	}
}