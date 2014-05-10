<h1>Admin Functions</h1>

<div class="clear">
<?php foreach ($Ranks as $Rank): ?>
	<div class="adminColumn clear">
		<p class="cpg"><span style="color:<?php echo $Rank->Rank_color; ?>;"><?php echo $Rank->Rank_name; ?></span></p>
		<?php
		foreach ($Rank->AdminFunctions as $Func)
			$this->loadview('admin/function', array(
				'Function' => $Func,
				'image' => true,
				'rank' => $userrank
			));
		?>
	</div>
<?php endforeach; ?>
</div>
<br />
<hr />
