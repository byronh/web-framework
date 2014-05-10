
<p>
	<label><?php echo $label; ?></label>
	<input type="text" name="<?php echo $fieldname; ?>" value="<?php echo $value; ?>"<?php echo $id.$maxlength; ?> />
	<span id="err<?php echo $fieldname; ?>" class="formerror"><?php echo $error; ?></span>
</p>