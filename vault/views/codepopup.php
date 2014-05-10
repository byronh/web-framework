<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo jquery().script('popup.js').script('tabby.js').script('base64.js'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title; ?></title>
<?php echo style('forms').style('popup'); ?>
</head>
<body id="popup">

<?php $this->loadview($Form->getheaderview())->loadview($Form->getbuttonview()); ?>

<textarea id="editcode" name="field0" rows="26" cols="123" wrap="off"><?php if ($html) echo htmlentities($source); else echo htmlentities(substr(substr($source, 7), 0, -4)); ?></textarea>

<?php $this->loadview($Form->getfooterview()); ?>

<div id="loading" style="text-align:center;"><h2>Saving...</h2><img src="/img/loadinggray.gif" /></div>

</body>
</html>