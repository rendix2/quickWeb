<?php

namespace qw\nu;

class Pools {
    private $db, $smarty, $language;
    private $poolId, $poolName

	public function __construct(db $db, Language $language, Smarty $smarty) {
	    $this->db = $db;
        $this->language = $language;
	    $this->smarty = $smarty;
	}

    private function poolsGetAnswers() {
        return $this->db->query("SELECT pool_answer_text, pool_answer_id FROM ".POOLS_ANSWERS_TABLE." WHERE pool_id = :pool_id;", __FILE__, __LINE__, array( 'pool_id' => $this->poolId ))->fetchAll();
    }

    private function poolsGetVotes() {
        foreach ( $this->poolsGetAnswers() as $v  )
           $return[$v['pool_answer_id']] = $this->db->query("SELECT COUNT(pool_vote_id) as count FROM ".POOLS_ANSWERS_VOTE_TABLE." WHERE pool_answer_id = :pool_answer_id;", __FILE__,__LINE__, array( 'pool_answer_id' => $v['pool_answer_id'] ))->fetchColumn();

        return $return;
    }

	
	public function __toString() {
	}
}
