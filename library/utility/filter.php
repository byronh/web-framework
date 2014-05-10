<?php

require(ROOT.DS.'library'.DS.'plugins'.DS.'HTMLPurifier.standalone.php');

class Filter {
	
	/* * * *
	 PUBLIC
	* * * */
	
	// Constructor - loads config.
	// Allowed tag levels - none, basic, intermediate, advanced
	
	public function __construct($level = 'none') {
		$this->level = $level;
		$this->initconfig();
		$this->Filter = new HTMLPurifier($this->Config);
	}
	
	// Purifies user input using HTMLPurifier (http://htmlpurifier.org).
	// Note that this alters the user input a lot so you should save a filtered and unfiltered version in the database
	// separately, and show an escaped version of the unfiltered data when allowing the user to edit their data.
	
	public function purify($html) {
		if (is_array($html)) {
			$htmlitems = array();
			foreach ($html as $item) {
				$htmlitems[] = $this->Filter->purify($item);
			}
			return $htmlitems;
		} else {
			return $this->Filter->purify($html);
		}
	}
	
	// Escapes user input so that it is suitable for displaying in a textarea or textbox.
	
	public function escape($html) {
		return str_replace(array("'", '"', '<', '>'), array("&#39;", "&quot;", '&lt;', '&gt;'), $html);
	}
	
	
	/* * * * *
	 PROTECTED
	* * * * */
	
	protected $Filter, $Config;
	protected $level, $elements, $attributes;
	
	protected function initconfig() {
		
		$this->Config = HTMLPurifier_Config::createDefault();
		$this->set('Core.Encoding', 'UTF-8');
		$this->set('HTML.Doctype', 'XHTML 1.0 Transitional');
		if ($this->level != 'none') {
			$this->set('AutoFormat.Linkify', true);
			//$this->set('AutoFormat.AutoParagraph', true);
		}
		
		require(ROOT.DS.'config'.DS.'filter.php');
		
		$this->elements = $elemnone;
		$this->attributes = $attrnone;
		
		if ($this->level == 'basic' || $this->level == 'intermediate' || $this->level == 'advanced') {
			$this->elements = array_merge($this->elements, $elembasic);
			$this->attributes = array_merge($this->attributes, $attrbasic);
			if ($this->level == 'intermediate' || $this->level == 'advanced') {
				$this->elements = array_merge($this->elements, $elemintermediate);
				$this->attributes = array_merge($this->attributes, $attrintermediate);
				if ($this->level == 'advanced') {
					$this->elements = array_merge($this->elements, $elemadvanced);
					$this->attributes = array_merge($this->attributes, $attradvanced);
				}
			}
		}
		
		$this->set('HTML.AllowedElements', implode(',', $this->elements));
		$this->set('HTML.AllowedAttributes', implode(',', $this->attributes));
	}
	
	protected function set($directive, $params) {
		$this->Config->set($directive, $params);
	}
	
}

?>