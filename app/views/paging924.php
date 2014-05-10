<?php 
$left = new View('leftfloat', array('View' => $Paginator->results()));
$right = new View('rightfloat', array('View' => $Paginator->pages()));
$this->loadview('content924', array('Views' => array($left, $right)));
?>