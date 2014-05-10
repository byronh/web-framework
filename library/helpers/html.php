<?php

/* * * * * *
 HTML HELPER
* * * * * */

function anchor($href, $text = NULL, $rel = '') {
	if (!$text) $text = $href;
	if ($rel != '') $rel = ' rel="'.$rel.'"';
	return '<a href="'.$href.'"'.$rel.'>'.$text.'</a>';
}

function heading($text, $headingnum) {
	return "<h$headingnum>$text</h$headingnum>";
}

function paragraph($text) {
	return '<p>'.$text.'<p>';
}

function image($src) {
	return '<img src="'.$src.'" alt="" />';
}

function icon($src) {
	return '<img src="/icon/'.$src.'.png" alt="" />';
}

function jquery() {
	return '<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>';
}

function script($src) {	
	return '<script src="/script/'.$src.'" type="text/javascript"></script>';
}

function style($src) {
	return '<link href="/style/'.$src.'.css" rel="stylesheet" type="text/css" />';
}

function redirect($address, $delay = 0) {
	return '<meta http-equiv="Refresh" content="'.$delay.';url='.$address.'" />';
}

function addhttp($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}

?>