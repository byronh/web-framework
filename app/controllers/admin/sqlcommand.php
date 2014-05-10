<?php

$this->loadheader('Admin');

if ($this->rank() >= 6) {
	
	$Form = new Form('left');
	$Form->add(new TextArea('', 'optional', 10, 112));
	$Form->add(new SubmitButton('Execute', 'cog_go', 'positive'));
	$Form->add(new CancelButton('/admin'));
	
	$this->loadview('admin/title', array('title' => 'SQL Command Line'));
	$this->loadview($Form);
	
	if ($Form->handle()) {
		$sql = $Form->input(0);
		$array = explode(' ', $sql);
		if (!empty($array)) {
			include_once(ROOT.DS.'config'.DS.'database.php');
			$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS);
			mysql_select_db(DB_NAME, $conn);
			if (strtoupper($array[0]) == 'SELECT') {
				$result = mysql_query($sql);
				ob_start();
				printResult($result, $sql);
				$output = ob_get_contents();
				ob_end_clean();
				$this->loadview('text', array('text' => $output));
			} else {
				mysql_query($sql);	
			}
		}
	}
	
}

function printResult($result, $query) {
	?><table class="tableform"><tr><?php
	if (!$result) {
		?><th>Result not valid.</th><?php
	} else {
		
		if (strpos($query, "SELECT ") !== FALSE) {
			$i = 0;
			while ($i < mysql_num_fields($result)) {
				$meta = mysql_fetch_field($result, $i);
				?><th style="white-space:nowrap;"><?php echo $meta->name; ?></th><?php
				$i++;
			}
			?></tr><?php
			if (mysql_num_rows($result) == 0) { 
				?><tr><td colspan="<?php echo mysql_num_fields($result); ?>">
				<strong><center>no result</center></strong>
				</td></tr><?php
			} else {
				while ($row=mysql_fetch_assoc($result)) {
					?><tr style="white-space:nowrap;"><?php
					foreach($row as $key=>$value) { ?><td><?php echo $value; ?></td><?php }
					?></tr><?php
				}
			}
		}
	}
	?></table><?php
}

?>