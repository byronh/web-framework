<?php

$this->loadheader('Articles', 'split');

$Links = new Set('Link');
$Links->select('Tag_id,Tag_name')->sortasc('Tag_name');
$Stories = new Set('Story');
$Stories->addchild($Links)->sortdesc('Story_published')->where('Story_type=2');

$search = Request::get('search');
$tag = Request::get('tag');
$user = Request::get('user');
if ($search) $Stories->where('Story_title LIKE ? OR Story_id IN (SELECT Story_id FROM Link JOIN Tag ON Tag.Tag_id = Link.Tag_id WHERE Tag_name LIKE ?)', '%'.$search.'%', 's');
if ($tag) $Stories->where('Story_id IN (SELECT Story_id FROM Link WHERE Tag_id=?)', $tag, 'i');
if ($user) $Stories->where('Story.User_id=?', $user, 'i');

$Stories->paginate(10)->load('Story_title,Story_published,Story_image,Story_tagline,User_id,User_name');

$Tags = new Set('Tag');
$Tags->where('(SELECT COUNT(*) FROM Link JOIN Story ON Story.Story_id = Link.Story_id WHERE Link.Tag_id=Tag.Tag_id AND Story.Story_type=2)>0')->sortasc('Tag_name')->load('Tag_name');

$Users = new Set('User');
$Users->where('(SELECT COUNT(*) FROM Story WHERE Story.Story_type=2 AND Story.User_id=User.User_id)>0')->sortasc('User_name')->load('User_name');

$this->loadview('content/index', array(
	'Stories' => $Stories,
	'Paginator' => $Stories->Paginator,
	'Tags' => $Tags,
	'Users' => $Users
));

?>