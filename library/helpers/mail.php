<?php

/* * * * * *
 MAIL HELPER
* * * * * */

require(ROOT.DS.'config'.DS.'mail.php');

function sendmail($email, $subject, $altbody, $htmlbody) {
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->Host = SITE_MAIL_HOST;
	$mail->SMTPAuth = true;
	$mail->Port = 26;
	$mail->Username = SITE_EMAIL;
	$mail->Password = SITE_EMAIL_PASS;
	$mail->SetFrom(SITE_EMAIL, SITE_NAME.' Admin');
	$mail->Subject = SITE_NAME.' '.$subject;	
	$mail->AltBody = $altbody; 
	$mail->MsgHTML($htmlbody);
	$mail->AddAddress($email);
	if(!$mail->Send()) {
		return false;
	}
	return true;
}

?>