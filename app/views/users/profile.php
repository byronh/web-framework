<div class="content694 clear">
	<h6>&nbsp;</h6>
	<div class="leftFloat clear">
		<p class="spaced">Basic Info</p>
		<table class="userProfileTable">
			<tr>
				<th>User Name:</th>
				<td><?php echo $User->User_name; ?></td>
			</tr>
			<tr>
				<th>Rank:</th>
				<td><?php $this->loadview('paragraph', array('text' => $User->Rank->Rank_name, 'color' => $User->Rank->Rank_color)); ?></td>
			</tr>
			<tr>
				<th>Date Joined:</th>
				<td><?php echo Date::day($User->User_joined); ?></td>
			</tr>
			<?php if ($User->User_homepage || $User->User_location): ?>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<?php if ($User->User_homepage): ?>
			<tr>
				<th>Home Page:</th>
				<td><?php echo $User->User_homepage; ?></td>
			</tr>
			<?php endif; ?>
			<?php if ($User->User_location): ?>
			<tr>
				<th>Location:</th>
				<td><?php echo $User->User_location; ?></td>
			</tr>
			<?php endif; ?>
			<?php endif; ?>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<th>Articles:</th>
				<td><?php echo $User->User_numarticles; ?><?php if ($User->User_numarticles > 0): ?> (<a href="/articles?user=<?php echo $User->User_id; ?>">View All</a>)<?php endif; ?></td>
			</tr>
			<tr>
				<th>Comments:</th>
				<td><?php echo $User->User_numcomments; ?></td>
			</tr>
		</table>
	</div>
	<div class="rightFloat clear">
		<?php if ($User->User_avatar): ?>
		<img class="avatar" src="/upload/avatar/<?php echo $User->User_avatar; ?>" alt="<?php echo $User->User_name; ?>'s Avatar" />
		<?php endif; ?>
	</div>
</div>

<?php if ($User->User_aboutme || $User->User_psn || $User->User_xbl || $User->User_nfc || $User->User_steam): ?>
<div class="content694 clear">
	<h6>&nbsp;</h6>
	<div class="leftFloat clear">
		<p class="spaced">Additional Details</p>
		<table class="userProfileTable">
			<?php if ($User->User_aboutme): ?>
			<tr>
				<th>About Me:</th>
				<td><?php echo nl2br($User->User_aboutme); ?></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<?php endif; ?>
			<?php if ($User->User_psn): ?>
			<tr>
				<th>PlayStation Network ID:</th>
				<td><?php echo $User->User_psn; ?></td>
			</tr>
			<?php endif; ?>
			<?php if ($User->User_xbl): ?>
			<tr>
				<th>XBox Gamertag:</th>
				<td><?php echo $User->User_xbl; ?></td>
			</tr>
			<?php endif; ?>
			<?php if ($User->User_nfc): ?>
			<tr>
				<th>Nintendo Friend Code:</th>
				<td><?php echo $User->User_nfc; ?></td>
			</tr>
			<?php endif; ?>
			<?php if ($User->User_steam): ?>
			<tr>
				<th>Steam ID:</th>
				<td><?php echo $User->User_steam; ?></td>
			</tr>
			<?php endif; ?>
		</table>
	</div>
</div>
<?php endif; ?>