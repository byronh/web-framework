<?php

class Paginator implements Viewable {
	
	/* * * *
	 PUBLIC
	* * * */
	
	public $currentpage;
	public $totalpages;
	
	// Constructor - fills attributes
	
	public function __construct($pageinfo = array()) {
		foreach ($pageinfo as $key => $value) $this->$key = $value;
		require(ROOT.DS.'config'.DS.'pagination.php');
	}
	
	// Returns the view object for this Paginator.
	
	public function getview() {
		return new View('paging', array('Paginator' => $this));
	}
	
	// Hides the results info from the view.
	
	public function hideresults() {
		$this->hide = true;	
	}
	
	// Sets the text to be used in the results statement.
	
	public function settype($type) {
		$this->type = $type;	
	}
	
	// Returns a view containing results info.
	
	public function results($type = NULL) {
		if ($this->hide === true) {
			return new View('empty');	
		} else {
			if ($type != NULL) {
				$this->type = $type;	
			}
			return new View('text', array('text' => 'Displaying '.$this->type.' '.$this->firstresult.'-'.$this->lastresult.' of '.$this->totalresults.' total'));
		}
	}
	
	// Returns a view containing the page navigation controls.
	
	public function pages() {
		$output = '';
		if ($this->totalpages > 1) {
			$url = '?page=';
			$output .= 'Page: ';
			if (($this->totalpages > PAGINATION_ADJACENT) && ($this->currentpage > PAGINATION_ADJACENT + 1))
				$output .= ' '.anchor($url.'1', '&laquo; First', 'start');
			if ($this->currentpage > 1)
				$output .= ' '.anchor($url.($this->currentpage - 1), '&lt; Prev', 'prev');
			for ($i = $this->currentpage - PAGINATION_ADJACENT; $i <= $this->currentpage + PAGINATION_ADJACENT; $i++) {
				if (($i > 0) && ($i <= $this->totalpages)) {
					if ($i == $this->currentpage) $output .= ' <strong>'.$i.'</strong>';
					else $output .= ' '.anchor($url.$i, $i);
				}
			}
			if ($this->currentpage < $this->totalpages)
				$output .= ' '.anchor($url.($this->currentpage + 1), 'Next &gt;', 'next');
			if (($this->totalpages > PAGINATION_ADJACENT) && ($this->currentpage < $this->totalpages - PAGINATION_ADJACENT))
				$output .= ' '.anchor($url.$this->totalpages, 'Last &raquo;');
		}
		return new View('text', array('text' => $output));
	}
	
	
	/* * * * *
	 PROTECTED
	* * * * */
	
	protected $firstresult, $lastresult, $totalresults, $hide = false, $type = 'results';
	
}

?>