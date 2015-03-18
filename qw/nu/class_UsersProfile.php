<?php

namespace qw\nu;

class UsersProfile extends Users {
private $db, $smarty;

	public function __construct(db $db, Smarty $smarty) {
	$this->db = $db;
	$this->smarty = $smarty;
	}


}
