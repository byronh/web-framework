<?php

if ($this->rank() >= 4) {
	
	$RSS = new RSS(ROOT.DS.'public'.DS.'rss.xml');
	$RSS->generate(10);
	
	goto('/admin');
}

?>