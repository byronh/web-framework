
<p>
	<label><?php echo $label; ?></label>
	<textarea name="<?php echo $fieldname; ?>" rows="<?php echo $rows; ?>" cols="<?php echo $cols; ?>"<?php echo $id; ?>><?php echo $value; ?></textarea>
</p>
<p>
	<label>&nbsp;</label>
	<span id="err<?php echo $fieldname; ?>" class="formerror">&nbsp;<?php echo $error; ?></span>
</p>
<p>&nbsp;</p>