<?php

namespace nu;

class FileUpload {
private $maxUploadSize = 0, $maxFileSize = 0, $mimeType, $uploadDir, $newInfo;
public $mimeTypeCount = 0, $foundCount = 0, $totalSize = 0;
private $db;
private static $disabledMime = array( 'application/x-httpd-php' => 'php', 'application/x-php' => 'php' );

	public function __construct(db $db, Smarty $smarty, $smartyData, $mimeType, $multi, $maxUploadSize = 0, $maxFileSize = 0, $uploadDir = './upload' ) {
		switch ( $multi )
		{
			case true:
			$smarty->assign('multi', 'multiple');
			break;
			case false:
			default:
			$smarty->assign('multi', '');
		}	

		if ( $maxUploadSize == 0 )
		$maxUploadSize = return_bytes(ini_get('post_max_size'));

		if ( $maxFileSize == 0 )
		$maxFileSize = return_bytes(ini_get('upload_max_filesize'));

		if ( $maxUploadSize <= return_bytes(ini_get('post_max_size')) )
		$this->maxUploadSize = $maxUploadSize;
		else
		throw new FileUploadException('Nelze uploadovat tak velké množství dat.');

		if ( $maxFileSize <= return_bytes(ini_get('upload_max_filesize')) )
		$this->maxFileSize = $maxFileSize;
		else
		throw new FileUploadException('Nelze uploadovat tak velký soubor.');

		if ( file_exists($uploadDir) )
		$this->uploadDir = $uploadDir;
		else
		throw new FileUploadException('Neexistující cílová složka.');

	$this->mimeTypeCount = count($_FILES['upload_file']['name']);
	$this->multi = $multi;
	$this->mimeType = $mimeType;
	$this->newInfo = array();
	$this->db = $db;
	$smarty->assign('file_upload', $smartyData);
	$smarty->assign('enabled_extension', implode(', ', array_values(array_unique($this->mimeType))));
	$smarty->display('fileUpload.tpl', true);	
	}

	public function fileUploadIntoDB() {
		for ( $i = 0; $i < $this->mimeTypeCount; ++$i )
		$this->db->query("INSERT INTO ".UPLOAD_FILES_TABLE." (file_name, file_dir, file_size, file_mime_type, file_time) VALUES (:file_name, :file_dir, :file_size, :file_mime_type, :file_time);", __FILE__, __LINE__, array(

		'file_name' => $this->newInfo['name'][$i],
		'file_dir' => $this->uploadDir,
		'file_size' => $this->newInfo['size'][$i],
		'file_mime_type' => $this->newInfo['type'][$i],
		'file_time' => time() ));
	}

	public function fileUploadDoIt() {
		if ( isset($_POST['file_upload_submit']) && ( ( !$this->multi && $_FILES['upload_file']['size'] ) || ( $this->multi && !empty($_FILES['upload_file']['name'][0]) ) ) ) {
		$this->fileUploadCheck();
		$this->fileUploadMove();
		$this->fileUploadIntoDB();
		}
		else if ( isset($_POST['file_upload_submit']) && !( ( !$this->multi && $_FILES['upload_file']['size'] ) || ( $this->multi && !empty($_FILES['upload_file']['name'][0]) ) ) )
		throw new FileUploadException('Nepřiložen žádný soubor.');
	}

	private function fileUploadCheck() {
		if ( $this->multi ) {
			for ( $i = 0; $i < $this->mimeTypeCount; ++$i ) {
				if ( $_FILES['upload_file']['size'][$i] > return_bytes(ini_get('upload_max_filesize')) )
				throw new FileUploadException('Soubor je větší než povolená mez.');

			$this->foundCount = 0;
			
				foreach ( self::$disabledMime as $k => $v )
					if ( $k == $_FILES['upload_file']['type'][$i] || $v == pathinfo($_FILES['upload_file']['name'][$i], PATHINFO_EXTENSION) )
					throw new FileUploadException('Zakázaný typ souboru: '.pathinfo($_FILES['upload_file']['name'][$i], PATHINFO_EXTENSION) );			

				foreach ( $this->mimeType as $k => $v ) {
					if ( $k == $_FILES['upload_file']['type'][$i] && $v == pathinfo($_FILES['upload_file']['name'][$i], PATHINFO_EXTENSION) ) {
					$this->foundCount++;
					break;
					}
				}

				if ( $this->foundCount == 0 )
				throw new FileUploadException('Nesprávný typ souboru. Uploadovat lze jen: '. implode(', ', array_values(array_unique($this->mimeType))));

			$this->totalSize += $_FILES['upload_file']['size'][$i];
			}

			if ( $this->totalSize > $this->maxUploadSize && $this->mimeTypeCount > 1 )
			throw new FileUploadException('Soubory jsou velké');
			else if ( $this->totalSize > $this->maxUploadSize )
			throw new FileUploadException('Soubor je velkýw.');
		}
		else {
			if ( $_FILES['upload_file']['size'] > $this->maxUploadSize )
			throw new FileUploadException('Soubor je větší než povolená mez.');
		}

	return true;
	}

	private function fileUploadMove() {
		for ( $i = 0; $i < $this->mimeTypeCount; ++$i ) {
		$new_name = uniqid(true).'.'.pathinfo($_FILES['upload_file']['name'][$i], PATHINFO_EXTENSION);
		
		$this->newInfo['name'][] = $new_name;
		$this->newInfo['size'][] = $_FILES['upload_file']['size'][$i];
		$this->newInfo['type'][] = $_FILES['upload_file']['type'][$i];

			if ( move_uploaded_file($_FILES['upload_file']['tmp_name'][$i], $this->uploadDir.'/'.$new_name) )
			echo 'Soubor '.$_FILES['upload_file']['name'][$i].' nahrán<br>';
			else
			throw new FileUploadException('Soubor '. $_FILES['upload_file']['name'][$i].' se nepodařilo nahrát');
		}
	}
}
