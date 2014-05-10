<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo jquery().script('global.js').script('popup.js'); ?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title; ?></title>
<?php
echo style('global');
echo style('admin');
echo style('forms');
echo style('popup');
?>

</head>
<body>

<?php foreach ($Views as $View) $this->loadview($View); ?>


</body>
</html>