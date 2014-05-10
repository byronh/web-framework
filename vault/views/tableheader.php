<table class="table<?php if (isset($style)) echo $style; else echo 'form'; ?>">
<?php if (isset($headings) && count($headings)): ?>
<tr>
<?php foreach($headings as $heading): ?>
<th><p><?php echo $heading; ?></p></th>
<?php endforeach; ?>
</tr>
<?php endif; ?>