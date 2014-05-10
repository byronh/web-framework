<?php

/* * * * * *
 cURL HELPER
* * * * * */

// Fetches the output from the given url using cURL.
// Returns result as a string.

function curlfetch($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	curl_close($ch); 
	return $output;
}

// Fetches the output from the given url with request method POST using cURL.
// Returns result as a string.

function curlpost($url, array $data = array()) {
	$fields = array();
	$post = '';
	
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, true);
	
	foreach ($data as $key => $value)
		$fields[] = $key.'='.$value;
	
	curl_setopt($ch, CURLOPT_POSTFIELDS, implode('&', $fields));
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}

?>