<?php

class MMSession extends Session {
	
	// Constructor - checks for existing session or cookie.
	// Caches the User object.
	
	public function __construct() {
		require(ROOT.DS.'app'.DS.'config'.DS.'mmsession.php');
		parent::__construct();
		Cookie::set('mmcookie', 'Cookies enabled');
		if (isset($_SESSION['User'])) {
			session_regenerate_id(true);
			if ($_SESSION['cache'] < time() - (60 * USER_CACHE)) {
				$_SESSION['User'] = new User($_SESSION['User']->User_id);
				if ($_SESSION['User']->load(USER_FIELDS)) {
					$_SESSION['cache'] = time();
				} else {
					$this->logout();
				}
			}
		} elseif (Cookie::exists('mmtoken')) {
			if (!$this->authenticatetoken($this->cookieid(), $this->cookietoken())) {
				Cookie::delete('mmtoken');
			}
		}
		if (!isset($this->_csrf)) $this->newcsrftoken();
	}
	
	
	/* * * * * * * * * *
	 USER AUTHENTICATION
	* * * * * * * * * */
	
	// Checks email and password against database.
	// Logs user in, creates a new token and cookie if 'remember' is set.
	// Returns true if valid login, false otherwise.
	
	public function authenticatelogin($email, $password, $remember = NULL, $redirurl = NULL) {
		$Users = new Set('User');
		$User = $Users->where('User_email=?', $email)->get(USER_FIELDS);
		print_r($User);
		if (!$User) return false;
		$Hasher = new PasswordHash;
		if ($Hasher->checkPassword($password, $User->User_password)) {
			$_SESSION['User'] = $User;
			if ($redirurl) $this->redirurl = $redirurl;
			if ($remember) Cookie::set('mmtoken', $User->User_id.'_'.$User->addtoken());
			$this->newcsrftoken();
			$_SESSION['cache'] = time();
			return true;
		}
		return false;
	}
	
	// Checks user id and token against database.
	// Logs user in, invalidates previous token and creates a new one.
	// Returns true if valid login, false otherwise.
	
	public function authenticatetoken($id, $tokenvalue, $emailconfirm = false) {
		$Tokens = new Set('Token');
		$Token = $Tokens->where('User_id=?', $id, 'i')->where('Token_value=?', $tokenvalue)->get();
		if (!$Token) return false;
		$_SESSION['User'] = new User($id);
		$_SESSION['User']->load(USER_FIELDS);
		if ($emailconfirm) {
			$_SESSION['User']->User_validated = 1;
			$_SESSION['User']->save();
		}
		$_SESSION['User']->removetoken($tokenvalue);
		Cookie::set('mmtoken', $_SESSION['User']->User_id.'_'.$_SESSION['User']->addtoken());
		$this->newcsrftoken();
		$_SESSION['cache'] = time();
		return true;
	}
	
	// Destroys cookies and session.
	// Invalidates current token.
	
	public function logout() {
		if ($this->loggedin() && Cookie::exists('mmtoken')) $_SESSION['User']->removetoken($this->cookietoken());
		Cookie::delete('mmtoken');
		$this->terminate();
		return true;
	}
	
	
	/* * * * * *
	 PERMISSIONS
	* * * * * */
	
	// Returns true if the current User is logged in.
	
	public function loggedin() {
		return (isset($_SESSION['User']) && Cookie::exists('mmcookie'));
	}
	
	// Returns the current User.
	
	public function getUser() {
		return isset($_SESSION['User']) ? $_SESSION['User'] : NULL;
	}
	
	
	/* * * * *
	 PROTECTED
	* * * * */
	
	protected function cookieid() {
		$value = Cookie::get('mmtoken');
		if (empty($value) || strpos($value, '_') === false) return 0;
		return strtok($value, '_');
	}
	
	protected function cookietoken() {
		$value = Cookie::get('mmtoken');
		if (empty($value) || strpos($value, '_') === false) return '';
		strtok($value, '_');
		return strtok('_');
	}
}

?>