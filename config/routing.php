<?php

if (!defined('ROOT')) die('Direct include not allowed.');

/* * * * * * * *
 ROUTING CONFIG
* * * * * * * */

$reserved = array('router', 'controller');

// Routes:

$route['_defaultcontroller'] = 'home';

$route['account'] = 'accounts';
$route['account/(:any)'] = 'accounts/$1';
$route['register'] = 'accounts/register';
$route['login'] = 'accounts/login';
$route['logout'] = 'accounts/logout';

$route['articles'] = 'content/index/';
$route['article/(:num)'] = 'content/view/$1';

$route['profile'] = 'users/profile';
$route['profile/(:num)'] = 'users/profile/$1';
$route['profile/edit'] = 'users/editprofile';
$route['profile/edit/(:num)'] = 'users/editprofile/$1';
$route['profile/(:any)'] = 'users/profile';

$route['contribute'] = 'content/contribute';

$route['terms'] = 'home/privacy';
$route['privacy'] = 'home/privacy';

?>