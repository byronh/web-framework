
<div class="buttons">
<p class="submit">
<?php

foreach ($Buttons as $Button) {
	$this->loadview($Button->getformview());
}

?>
</p>
</div>
