<?php

$id = isset($this->args[0]) ? intval($this->args[0]) : $this->userid();
if (!$id) {
	$this->error('loginrequired');
	return;	
}
$User = new User($id);
$fields = 'User_name,User_validated,User_joined,Rank_name,Rank_color,User_avatar,User_homepage,User_location,User_aboutme,User_psn,User_xbl,User_nfc,User_steam';
$fields .= ',(SELECT COUNT(*) FROM Story WHERE User_id='.$id.' AND Story_type=2) AS User_numarticles';
$fields .= ',(SELECT COUNT(*) FROM Comment WHERE User_id='.$id.') AS User_numcomments';
if ($User->load($fields) && $User->User_validated == 1) {
	$this->loadheader('Profile: '.$User->User_name, 'split');
	$this->loadview('title', array('title' => $User->User_id == $this->userid() ? 'My Profile' : $User->User_name.'\'s Profile'));
	$this->loadview('users/profile', array('User' => $User));
	$this->loadview('users/sidebar');
} else {
	$this->error('itemnotfound', array('item' => 'User'));	
}

?>