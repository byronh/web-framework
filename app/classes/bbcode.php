<?php

class BBCode {
	
	// Replaces BBCode tags with HTML.
	
	public function replace($content, $userid) {
		$search = array(
			'/\[b\](.*?)\[\/b\]/is',
			'/\[i\](.*?)\[\/i\]/is',
			'/\[u\](.*?)\[\/u\]/is',
			'/\[h\](.*?)\[\/h\]/is',
			'/\[img\](.*?)\[\/img\]/is',
			'/\[caption\](.*?)\[\/caption\]/is',
		);
		$replace = array(
			'<strong>$1</strong>',
			'<em>$1</em>',
			'<u>$1</u>',
			'<h2>$1</h2>',
			'<div class="articleImage"><img src="/upload/user/'.$userid.'/full/$1" alt="" /></div>',
			'<div class="articleCaption"><p>$1</p></div>'
		);
		return preg_replace($search, $replace, $content);
	}
	
}

?>