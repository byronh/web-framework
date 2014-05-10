<p><?php if (isset($image)): ?><img src="/icon/<?php echo $Function->AdminFunction_image; ?>.png" alt="" /><?php endif; ?>

<?php if ($rank >= $Function->Rank_id) { ?>
<a href="<?php echo $Function->AdminFunction_link; ?>"<?php if ($Function->AdminFunction_type == 1) echo ' onclick="return confirm(\'Are you sure you wish to perform this action?\');"'; elseif ($Function->AdminFunction_type == 2) echo ' target="_blank"'; elseif ($Function->AdminFunction_type == 3) echo ' class="popup"'; ?>><?php echo $Function->AdminFunction_label; ?></a></p>
<?php } else { echo $Function->AdminFunction_label.'</p>'; } ?>