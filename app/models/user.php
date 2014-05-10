<?php

class User extends Model {
	
	public static $parents = array('Rank');
	public static $children = array('Story', 'Token', 'Version', 'Todo');
	
	
	/* * * * * * * * *
	 TOKEN MANAGEMENT
	* * * * * * * * */
	
	// Creates a new token and associates it with this User.
	
	public function addtoken() {
		$Token = new Token;
		$Token->User_id = $this->User_id;
		$value = $Token->generate();
		$Token->save();
		return $value;
	}
	
	// Disassociates and deletes the given token from this User.
	
	public function removetoken($value) {
		$Tokens = new Set('Token');
		$Tokens->where('User_id=?', $this->User_id, 'i')->where('Token_value=?', $value, 's')->delete();
	}
	
	// Deletes all tokens associated with this User.
	
	public function removealltokens() {
		$Tokens = new Set('Token');
		$Tokens->where('User_id=?', $this->User_id, 'i')->delete();
	}
	
}

?>