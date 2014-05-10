<?php

$this->loadheader('Edit Profile', 'split');

$id = isset($this->args[0]) ? intval($this->args[0]) : $this->userid();
$User = new User($id);
$fields = 'User_name,User_validated,User_joined,Rank_id,Rank_name,Rank_color,User_avatar,User_homepage,User_location,User_aboutme,User_psn,User_xbl,User_nfc,User_steam';
if ($this->rank() >= 2 && $User->load($fields) && $User->User_validated == 1 && ($this->userid() == $User->User_id || ($this->rank() > $User->Rank->Rank_id && $this->rank() >= 5))) {
	
	$Form = new AjaxForm('left');
	$Form->add(new ImageUploader('Change Avatar', array(
		'maxsize' => 200,
		'maxwidth' => 2000,
		'maxheight' => 2000,
		'uploadfolder' => new Folder(ROOT.DS.'public'.DS.'upload'.DS.'avatar'),
		'makethumb' => true,
		'thumbfolder' => new Folder(ROOT.DS.'public'.DS.'upload'.DS.'avatar'),
		'thumbwidth' => 200,
		'thumbheight' => 200,
		'usecurrent' => true,
		'currentname' => $User->User_avatar,
		'currentlabel' => 'Avatar'
	)));
	$Form->add(new TextBox('Home Page', 'maxlength[255]'), $User->User_homepage);
	$Form->add(new TextBox('Location', 'maxlength[75]'), $User->User_location);
	$Form->add(new TextArea('About Me', 'maxlength[1000]', 8, 58), $User->User_aboutme);
	$Form->add(new TextBox('PlayStation Network ID', 'maxlength[16]'), $User->User_psn);
	$Form->add(new TextBox('XBox Gamertag', 'maxlength[20]'), $User->User_xbl);
	$Form->add(new TextBox('Nintendo Friend Code', 'maxlength[19]'), $User->User_nfc);
	$Form->add(new TextBox('Steam ID', 'maxlength[40]'), $User->User_steam);
	$Form->add(new SubmitButton('Save Changes', 'user', 'positive'));
	$Form->add(new CancelButton('/profile/'.$id));
	
	if ($Form->handle()) {
		$Filter = new Filter();
		$User = new User($id);
		$avatar = $Form->input(0);
		if (!empty($avatar)) {
			$User->User_avatar = $avatar['filename'];
		}
		$User->User_homepage = $Filter->purify($Form->input(1));
		$User->User_location = $Filter->purify($Form->input(2));
		$User->User_aboutme = $Filter->purify($Form->input(3));
		$User->User_psn = $Filter->purify($Form->input(4));
		$User->User_xbl = $Filter->purify($Form->input(5));
		$User->User_nfc = $Filter->purify($Form->input(6));
		$User->User_steam = $Filter->purify($Form->input(7));
		$User->save();
		goto('/profile/'.$id);
	}
	
	$this->loadview('title', array('title' => 'Edit Profile'));
	$this->loadview('content694', array('Views' => array($Form)));
	$this->loadview('users/sidebar');
} else {
	goto('/profile/'.$id);	
}

?>