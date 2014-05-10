
<p>
	<label><?php echo $label; ?></label>
	<select name="<?php echo $fieldname; ?>"<?php echo $id; ?>>
	<?php foreach ($choices as $choicevalue => $choicelabel): ?><option value="<?php echo $choicevalue; ?>"<?php if (Request::post($fieldname) == $choicevalue || ($default == $choicevalue && Request::server('REQUEST_METHOD') != 'POST')) echo ' selected="selected"' ?>><?php echo $choicelabel; ?><?php endforeach; ?>
	
	</select>
	<span id="err<?php echo $fieldname; ?>" class="formerror"><?php echo $error; ?></span>
</p>
