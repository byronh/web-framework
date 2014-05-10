
<p>
	<label><?php echo $label; ?></label>
	<input type="checkbox" name="<?php echo $fieldname; ?>"<?php if (Request::post($fieldname)) echo ' checked="checked"'; ?>/>
</p>
