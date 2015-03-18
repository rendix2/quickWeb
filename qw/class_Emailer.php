<?php

//namespace qw;

/**
 * Class Emailer
 */
final class Emailer {
    /**
     * @var
     */
    /**
     * @var
     */
    /**
     * @var
     */
    /**
     * @var
     */
    private $userName, $email, $subject, $text;
    /**
     * @var string
     */
    private static $headers = "MIME-Version: 1.0\nContent-Type: text/plain; charset=utf-8\nContent-Transfer-Encoding: 8bit\nFrom: ".WEB_MAIL."\nReply-email: ".WEB_MAIL."\n"; // I added Reply-email :P

    /**
     * @param $userName
     * @param $email
     * @param $subject
     * @param $text
     */
    public function __construct($userName, $email, $subject, $text) {
        if ( DEBUG )
            cs($language, $this);

	    $this->userName = $userName;
	    $this->email = $email;
	    $this->text = $text;
	    $this->subject = $subject;
	}

    /**
     *
     */
    public function __destruct() {
        if ( DEBUG )
            echo 'Destruktor: '.get_class($this).'<br>';

	    $this->userName = null;
	    $this->email = null;
	    $this->text = null;
	    $this->subject = null;
	    $this->headers = null;
	}

	// Jakub Vrána php.vrana.cz
    /**
     * @param $text
     * @param string $encoding
     * @return string
     */
    private function mimeHeaderEncode($text, $encoding = 'utf-8') {
	    return '=?'.$encoding.'?Q?'.imap_8bit($text).'?=';
	}

	// Jakub Vrána php.vrana.cz
	// I added Reply-email header
    /**
     * @return bool
     */
    public function sendMail() {
	    return mb_send_mail($this->mimeHeaderEncode($this->userName).'<'.$this->email.'>', $this->mimeHeaderEncode($this->subject), $this->text, self::$headers);
	}
}