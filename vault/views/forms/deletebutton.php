
<button type="submit" <?php echo $cssclass; ?> name="submit" value="<?php echo (isset($valueoverride)) ? $valueoverride : $labeltext; ?>"<?php if ($confirmed) echo ' onclick="return confirm(\'Are you sure you wish to DELETE this item? This cannot be undone.\');"'; ?>><?php if ($imageurl): ?><img src="<?php echo $imageurl; ?>" alt="" /><?php endif; echo $labeltext; ?></button>
