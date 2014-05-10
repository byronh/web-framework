<?php

if (!defined('ROOT')) die('Direct include not allowed.');

/* * * * * * *
 FILTER CONFIG
* * * * * * */

$elemnone = array();
$attrnone = array();

$elembasic = array('b','i','u','strong','em','del','sub','sup','a','img','code','pre','ul','ol','li','p','q','blockquote','acronym');
$attrbasic = array('a.href','img.src');

$elemintermediate = array('font','h1','h2','h3','h4','h5','h6','table','tr','th','td','hr');
$attrintermediate = array('font.face','font.size','font.color');

$elemadvanced = array('span','div');
$attradvanced = array('div.class','div.id','span.class','span.id');

?>