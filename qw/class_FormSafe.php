<?php

//namespace qw;

/**
 * Class SafeForm
 */
final class FormSafe {
    private $templateName, $arrayName;
    private $earlyTime, $lateTime, $maxSubmitTries, $time, $name;

    /**
     * @param Smarty $smarty
     * @param $templateName
     * @param string $name
     * @param int $earlyTime
     * @param int $lateTime
     * @param int $maxSubmitTries
     * @param int $formLockedTime
     * @throws FormException
     */
    public function __construct(Smarty $smarty, $templateName, $name = 'hash', $earlyTime = 2, $lateTime = 120, $maxSubmitTries = 5, $formLockedTime = 120) {
        if ( DEBUG )
            cs($language, $this);

		if ( !is_numeric($earlyTime) || !is_numeric($lateTime) || !is_numeric($maxSubmitTries) || !is_numeric($formLockedTime) )
		    throw new FormException('Některý z parametrů pro kontrolu formulářů neni číselný.');

        if ( $earlyTime > $lateTime )
            throw new FormException('Čas brzského odeslání je větší než čas pozdního odeslání.');

        $this->earlyTime = $earlyTime;
	    $this->lateTime = $lateTime;
	    $this->maxSubmitTries = $maxSubmitTries;
	    $this->formLockedTime = $formLockedTime;
	    $this->name = $name;
	    $this->templateName = $templateName;
	    $this->arrayName = ( $smarty instanceof TemplateAdmin ) ? 'admin/'.$templateName : $smarty->templateDir.'/'.$templateName;
	
	    $_SESSION[$this->arrayName] = isset($_SESSION[$this->arrayName]) ? $_SESSION[$this->arrayName] : '';
        $_SESSION[$this->arrayName]['time'] = isset($_SESSION[$this->arrayName]['time']) ? $_SESSION[$this->arrayName]['time'] : time();
        $_SESSION[$this->arrayName]['time1'] = isset($_SESSION[$this->arrayName]['time1']) ? $_SESSION[$this->arrayName]['time1'] : time();
        $_SESSION[$this->arrayName]['time2'] = isset($_SESSION[$this->arrayName]['time2']) ? $_SESSION[$this->arrayName]['time2'] : time();
        $_SESSION[$this->arrayName][$this->name] = isset($_SESSION[$this->arrayName][$this->name]) ? $_SESSION[$this->arrayName][$this->name] : md5(uniqid(true));

	    $smarty->assign($this->name, $_SESSION[$this->arrayName][$this->name]);
	}

    /**
     *
     */
    public function __destruct(){
        if ( DEBUG )
            echo 'Destruktor: '.get_class($this).'<br>';
    }



    /**
     * @return mixed
     */
    public function getTemplateName() {
	    return $this->templateName;
	}

    /**
     * @throws FormException
     */
    public function doChecks() {
	    $this->checkFormTry();
	    $this->csfrCheck();
	    $this->checkEarlyFormSent();
	    $this->checkLateFormSent();
	}

	// check if form is sent from this domain .-) 
    /**
     * @throws FormException
     */
    public function csfrCheck() {
	//if ( !array_key_exists($this->name, $_POST) )
		if ( !isset($_POST[$this->name]) ) {
    		$array = array();

			foreach ( $_POST as $k => $v)
    			$array[] = ( preg_match('#'.$this->name.'#', $k) ) ? '<b>'.$k.'</b>' : $k;

	    	throw new FormException('Neexistující HTML prvek s kontrolním součtem jménem: <b>'.$this->name.'</b>.<br> Existující prvky: '.implode(', ', $array).'.<br>');
		}

		if ( $_SESSION[$this->arrayName][$this->name] != $_POST[$this->name] )
    		throw new FormException('Zkuste odeslat formulář znovu.');
	}

	// check zda formulář nebyl odeslán do 5 vteřin 
    /**
     * @throws FormException
     */
    public function checkEarlyFormSent() {
		if ( ( $_SESSION[$this->arrayName]['time'] + $this->earlyTime ) > time() ) {
    		$_SESSION[$this->arrayName]['time'] = time();
		    throw new FormException('Formulář odeslán příliš brzy.');
		}

	    $_SESSION[$this->arrayName]['time'] = 0;
	}

	// check zda formulář nebyl odeslán nad nějaký čas
    /**
     * @throws FormException
     */
    public function checkLateFormSent() {
		if ( ( $_SESSION[$this->arrayName]['time1'] + $this->lateTime ) < time() ) {
		    $_SESSION[$this->arrayName]['time1'] = time();
		    throw new FormException('Formulář odeslán příliš pozdě.');
		}
		
	    $_SESSION[$this->arrayName]['time1'] = 0;
	}

	// check submit tries
    /**
     * @throws FormException
     */
    public function checkFormTry() {
		if ( $_SESSION[$this->arrayName]['count_tries'] == $this->maxSubmitTries ) {
			if ( ( $_SESSION[$this->arrayName]['time2'] + $this->formLockedTime ) > time() ) {
			$_SESSION[$this->arrayName]['time2'] = time();
			throw new FormException('Formulář odeslán mockrát!. Povolení formuláře: <b>'.date("j.n.Y H:i:s", ($_SESSION[$this->arrayName]['time2'] + $this->formLockedTime)).'</b>');
			}
			else {
			$_SESSION[$this->arrayName]['count_tries'] = 0;
			$_SESSION[$this->arrayName]['time1'] = time() + $this->lateTime;
			$_SESSION[$this->arrayName]['time2'] = 0;
			}
		}
		else
		$_SESSION[$this->arrayName]['count_tries']++;
	}
}