<?php if ($Paginator->totalpages > 1): ?>
<table class="tableform">
	<tr>
		<td class="basicPaginator"><?php $this->loadview($Paginator); ?></td>
	</tr>
</table>
<?php endif; ?>