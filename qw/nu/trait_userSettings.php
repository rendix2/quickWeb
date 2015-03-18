<?php

trait userSettings {

	private function changePass() {
	}

	private function changeEmail() {
	
		if ( isset($_POST['submit']) ) {

			if ( empty($_POST['user_mail']) )
			throw new UsersException('Prázdný e-mail.');

			if ( !$this->validateMail($_POST['user_mail']) )
			throw new UsersException('E-mail není validní.');
			
		$this->db->query("SELECT 1 FROM ".USERS_TABLE." WHERE user_mail = :user_mail LIMIT 1;", __FILE__, __LINE__, array( 'user_mail' => $_POST['user_mail'] ));
		
			if ( $this->db->numRows() )
			throw new UsersException('E-mail je již zaregistrovaný.');

		$this->db->query("UDPATE ".USERS_TABLE." SET user_mail = :user_mail WHERE user_id = :user_id LIMIT 1;", __FILE__, __LINE__, array( 'user_mail' => $_POST['user_mail'], 'user_id' => ( property_exists($this, 'manageUserId' ) ? $this->manageUserId  : $this->userId)));
		
			if ( $this->db->numRows() ) {
			echo 'E-mail aktualizován.';
			return true;
			}
			else
			throw new UsersException('E-mail se nepdoařilo aktualizovat!');
		}
	}

	private function deleteUser($user_id) {
		if ( !is_numeric($user_id) )
		throw new UsersException('Identifikátor není číslo.');
		
	$this->db->query("SELECT 1 FROM ".USERS_TABLE." WHERE user_id = :user_id LIMIT 1;", __FILE__, __LINE__, array('user_id' => $user_id));

		if ( !$this->db->numRows() )
		throw new UsersException('Uživatel neexistuje.');

	$this->db->query("DELETE FROM ".USERS_TABLE." WHERE user_id = :user_id LIMIT 1;", __FILE__, __LINE__, array('user_id' => $user_id));
	$this->db->query("SELECT article_id FROM ".ARTICLES_TABLE." WHERE user_id = :user_id LIMIT 1;", __FILE__, __LINE__, array('user_id' => $user_id));
	
		if ( !$this->db->numRows() )
		echo 'Uživatel nenapsal články.';	
		
		while ( $data = $this->db->fetch() ) {
		$this->db->query("DELETE FROM ".ARTICLES_CATS_TABLE." WHERE article_id = :article_id LIMIT 1;", __FILE__, __LINE__, array('article_id' => $data['article_id']));
		$this->db->query("DELETE FROM ".ARTICLES_COMMENTS_TABLE." WHERE article_id = :article_id LIMIT 1;", __FILE__, __LINE__, array('article_id' => $data['article_id']));
		$this->db->query("DELETE FROM ".ARTICLES_EDIT_TABLE." WHERE article_id = :article_id LIMIT 1;", __FILE__, __LINE__, array('article_id' => $data['article_id']));
		$this->db->query("DELETE FROM ".ARTICLES_PAGES_TABLE." WHERE article_id = :article_id LIMIT 1;", __FILE__, __LINE__, array('article_id' => $data['article_id']));
		$this->db->query("DELETE FROM ".ARTICLES_READINGS_TABLE." WHERE article_id = :article_id LIMIT 1;", __FILE__, __LINE__, array('article_id' => $data['article_id']));
		$this->db->query("DELETE FROM ".ARTICLES_VERSIONS_TABLE." WHERE article_id = :article_id LIMIT 1;", __FILE__, __LINE__, array('article_id' => $data['article_id']));
		$this->db->query("DELETE FROM ".ARTICLES_TABLE." WHERE article_id = :article_id LIMIT 1;", __FILE__, __LINE__, array('article_id' => $data['article_id']));
		}
		
	$this->db->freeResult();
	echo 'Uživatel smazán.';

		if ( $this instanceof Admin )
		Red::redirect('./admin.php');
		else
		Red::redirect('./');
		return 1;
	}

	private function deactivateUser($user_ud) {
	$this->db->query("UPDATE ".USERS_TABLE." SET user_active = 0 WHERE user_id = :user_id LIMIT 1;", __FILE__, __LINE__, array('user_id' => $user_id));
	$this->db->freeResult();	
	}
}
