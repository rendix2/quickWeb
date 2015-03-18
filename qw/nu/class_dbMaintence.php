<?php

namespace nu;

class dbMaintence extends db{
private $listTables;

	private function listTables() {
	    $this->query("SHOW TABLES", __FILE__, __LINE__);
	    $this->listTables = $this->fetchAll();
	}
	
	private function optimizeTables() {
	    return $this->query("OPTIMIZE TABLE ".implode(', ', $this->listTables).";", __FILE__,__LINE__);
	}

	private function repairTables() {
	    return $this->query("REAPIR TABLE ".implode(', ', $this->listTables).";", __FILE__,__LINE__);
	}
	
	private function checkPageFileExists() {
	    $this->query("SELECT page_id, page_file FROM ".PAGES_TABLE." WHERE page_static = 1;", __FILE__, __LINE__);
	
		foreach ( $this->fetch() as $v )
			if ( !file_exists('./pages/'.$v['page_file'].'.html') )
    			 $this->query("DELETE FROM ".PAGES_TABLE." WHERE page_id = :page_id LIMIT 1;", __FILE__, __LINE__, array('page_id' => $v['page_id']));
	}
	
	private function checkFilePageExists() {	
		foreach ( glob('./pages/*.html') as $v ) {		
    		$this->query("SELECT 1 FROM ".PAGES_TABLE." WHERE page_file = :page_file LIMIT 1;", __FILE__, __LINE__, array('page_file' => substr($v, 0, strlen($v)-4));
		
			if ( !$this->numRows() )
    			unlink('/pages/'.$v);
		}
	}

	public function doIt() {
	}
}
