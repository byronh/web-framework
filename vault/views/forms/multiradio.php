<p class="spaced"><label><?php echo $label; ?></label> <span id="err<?php echo $fieldname; ?>" class="formerror"><?php echo $error ? $error :  '&nbsp;'; ?></span></p>
<?php foreach ($choices as $choicevalue => $choicelabel): ?>
<p>
	<label>&nbsp;</label>
	<input type="radio"<?php if (Request::post($fieldname) == $choicevalue || ($default == $choicevalue && Request::server('REQUEST_METHOD') != 'POST')) echo ' checked="checked"' ?> value="<?php echo $choicevalue; ?>" name="<?php echo $fieldname; ?>"<?php echo $id; ?> /> <?php echo $choicelabel; ?>
</p>
<?php endforeach; ?>
<p>&nbsp;</p>
