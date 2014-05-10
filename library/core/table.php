<?php

class Table {
	
	/* * * *
	 PUBLIC
	* * * */
	
	// Constructor - sets table name.
	
	public function __construct($tablename) {
		$this->Database = Factory::get('Database');
		$this->tablename = $tablename;
	}
	
	// Generates the SQL code required to completely reconstruct and repopulate this database table.
	// Returns result as string.
	
	public function backup() {
		$output = "/* - - - Table recovery for $this->tablename - - - */\n\n\n";
		$output .= "DROP TABLE IF EXISTS `$this->tablename`;\n\n";
		$output .= $this->showcreate().';';
		$output .= $this->showrepopulate();
		return $output."\n\n\n";
	}
	
	
	/* * * * *
	 PROTECTED
	* * * * */
	
	protected $Database;
	protected $tablename;
	
	protected function showcreate() {
		$result = $this->Database->fetch("SHOW CREATE TABLE $this->tablename");
		return $result[0]['Create Table'];
	}
	
	protected function showrepopulate() {
		$result = $this->Database->fetch("SELECT * FROM $this->tablename");
		$output = '';
		$count = count($result);
		if ($count) {
			$output = "\n\nINSERT INTO `$this->tablename` VALUES\n";
			for ($i=0; $i<$count; $i++) {
				$output .= "(";
				$numfields = count($result[$i]);
				for ($j=0; $j<$numfields; $j++) {
					if ($j>0) $output .= ',';
					$output .= $this->Database->escape(array_shift($result[$i]));
				}
				$output .= ")";
				$output .= ($i != $count-1) ? ",\n" : ';';
			}
		}
		return $output;
	}
	
	protected function __toString() {
		return $this->tablename;
	}
	
}

?>