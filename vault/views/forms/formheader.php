<form id="mainform" name="mainform" enctype="multipart/form-data" action="<?php echo $action; ?>" method="post">
<div class="mainForm<?php echo $style; ?>">
<?php foreach ($Hiddens as $Hidden) $this->loadview($Hidden); ?>
