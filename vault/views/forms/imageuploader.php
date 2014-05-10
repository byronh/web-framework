<?php if (!empty($settings['currentname']) && file_exists($settings['currentpath'])): ?>
<p class="spaced"><label>Current <?php echo $settings['currentlabel']; ?>:</label><img src="<?php echo $settings['currenturi']; ?>" alt="<?php echo $settings['currentlabel']; ?>" /></p>
<?php endif; ?>
<p>
	<label><?php echo $label; ?></label>
	<input type="file" name="<?php echo $fieldname; ?>"<?php echo $id; ?> />
</p>
<p>
	<label>&nbsp;</label>
	<span id="err<?php echo $fieldname; ?>" class="formerror">&nbsp;<?php echo $error; ?></span>
</p>
<p>&nbsp;</p>