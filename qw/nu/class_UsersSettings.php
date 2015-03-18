<?php

namespace qw\nu;

class UsersSettings extends Users {
private $db, $smarty;
use userSettings;

	public function __construct(db $db, Smarty $smarty) {
	$this->db = $db;
	$this->smarty = $smarty;
	}

	public function start() {
	}
}
