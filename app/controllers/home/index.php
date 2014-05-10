<?php

$this->loadheader();

$Features = new Set('Feature');
$Features->sortdesc('Story_published')->where('Feature_active=1')->limit(3)->load('Feature_image,Feature_thumb,Story_id,Story_title,Story_published,Story_tagline,User_name');

$Links = new Set('Link');
$Links->select('Tag_id,Tag_name')->sortasc('Tag_name');
$Stories = new Set('Story');
$Stories->addchild($Links)->where('Story_type=2 AND Story_id NOT IN (SELECT Story_id FROM Feature WHERE Feature_active=1)')
		->sortdesc('Story_published')->limit(10)->load('Story_type,Story_title,Story_published,Story_image,Story_tagline,User_id,User_name');

$this->loadview('home/index', array(
	'Features' => $Features,
	'Stories' => $Stories
));

?>