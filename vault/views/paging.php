<?php 
$this->loadview('leftfloat', array('View' => $Paginator->results()));
$this->loadview('rightfloat', array('View' => $Paginator->pages()));
?>