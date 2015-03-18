<?php

namespace qw\nu;

class ArticlesSettings extends Articles {
private $db, $smarty;

	public function __construct(db $db, Smarty $smarty) {
	$this->db = $db;
	$this->smarty = $smarty;
	}
}
