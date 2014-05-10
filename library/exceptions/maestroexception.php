<?php

// Base class for all exceptions in the framework.

class MaestroException extends Exception {
	
	// Produces nice-looking exception info.
	
	public function __toString() {
		$output = '<strong>Uncaught '.get_class($this).'</strong>: <em>'.$this->getMessage().'</em> ';
		$output .= 'in <code>'.$this->getFile().'</code> : line <strong>'.$this->getLine().'</strong>.<br /><br />';
		$output .= "<strong>Stack trace</strong>:<pre>\n".$this->getTraceAsString().'</pre>';
		return $output;
	}
	
}

?>