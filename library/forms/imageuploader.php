<?php

$_FILES['uploaded'] = array();

class ImageUploader extends Field {
	
	/* * * *
	 PUBLIC
	* * * */
	
	// Constructor - overrides default properties if specified.
	
	public function __construct($label, array $settings = array()) {
		parent::__construct($label);
		$defaults = array(
			'required' => false,							// true/false
			'maxsize' => 1024,								// kb
			'minwidth' => 0,								// px
			'minheight' => 0,								// px
			'maxwidth' => 1000,								// px
			'maxheight' => 1000,							// px
			'uploadfolder' => new Folder(ROOT.DS.'public'),	// Folder object
			'makethumb' => false,							// true/false
			'thumbfolder' => new Folder(ROOT.DS.'public'),	// Folder object
			'thumbwidth' => 200,							// px
			'thumbheight' => 200,							// px
			'currentname' => '',							// filename of current image
			'currentlabel' => 'Image'						// string description
		);
		$this->settings = array_merge($defaults, $settings);
		if ($this->settings['required']) {
			$this->properties['rules'] = 'required';	
		}
		if (!empty($this->settings['currentname'])) {
			$this->settings['currentpath'] = $this->settings['uploadfolder']->getpath().DS.$this->settings['currentname'];
			$this->settings['currenturi'] = str_replace(ROOT.DS.'public', '', $this->settings['currentpath']);
		}
	}
	
	// Override. Returns true if the input in this field passes validation, false otherwise.
	
	public function validate() {
		$fieldname = $this->properties['fieldname'];
		if ($_FILES[$fieldname]['error']) {
			if ($this->settings['required']) {
				$this->properties['error'] = 'You must choose an image to upload.';
				return false;
			} else {
				return true;	
			}
		}
		$tempfile = $_FILES[$fieldname]['tmp_name'];
		if (!(@is_uploaded_file($tempfile))) {
			$this->properties['error'] = 'The attachment was not an HTTP upload.';
			return false;
		}
		$imagesize = @getimagesize($tempfile);
		if ($imagesize === false) {
			$this->properties['error'] = 'Only .gif, .jpg, and .png files are allowed.';
			return false;
		} elseif ($imagesize[0] < $this->settings['minwidth'] || $imagesize[1] < $this->settings['minheight'] || $imagesize[0] > $this->settings['maxwidth'] || $imagesize[1] > $this->settings['maxheight']) {
			if ($this->settings['minwidth'] == $this->settings['maxwidth'] && $this->settings['minheight'] == $this->settings['maxheight']) {
				$this->properties['error'] = 'Must be exactly '.$this->settings['minwidth'].'px wide and '.$this->settings['minheight'].'px tall.';
			} else {
				$this->properties['error'] = 'Must be within '.$this->settings['minwidth'].'-'.$this->settings['maxwidth'].'px wide and '.$this->settings['minheight'].'-'.$this->settings['maxheight'].'px tall.';
			}
			$this->properties['error'] .= ' Current dimensions: '.$imagesize[0].'x'.$imagesize[1].'px.';
			return false;
		}
		if (filesize($tempfile) > ($this->settings['maxsize']*1024)) {
			$this->properties['error'] = 'File size too large ('.round((filesize($tempfile)/1024),1).' kb). Limit: '.$this->settings['maxsize'].'.0 kb';
			return false;
		}
		$now = microtime(true); 
		while (file_exists($newfn = $now.strtolower(strrchr($_FILES[$fieldname]['name'], ".")))) {
			$now++;
		}
		if (!(@move_uploaded_file($_FILES[$fieldname]['tmp_name'], $this->settings['uploadfolder']->getpath().DS.$newfn))) {
			$this->properties['error'] = 'Failed to upload file.';
			return false;
		}
		if ($this->settings['makethumb']) {
			$imagefn = $this->settings['uploadfolder']->getpath().DS.$newfn; 
			$dimensions = getimagesize($imagefn);
			
			if ($dimensions[0] <= $this->settings['thumbwidth'] && $dimensions[1] <= $this->settings['thumbheight']) {
				copy($imagefn, $this->settings['thumbfolder']->getpath().DS.$newfn);
			} else {
				$gif = false;
				$jpg = false;
				$png = false;
				
				if ($this->checkgif($imagefn)) {
					$oldimage = imagecreatefromgif($imagefn);
					$gif = true;
				} elseif ($this->checkjpg($imagefn)) {
					$oldimage = imagecreatefromjpeg($imagefn);
					$jpg = true;
				} elseif ($this->checkpng($imagefn)) {
					$oldimage = imagecreatefrompng($imagefn);
					$png = true;
				}
				
				$ratio = ($dimensions[0] > $dimensions[1]) ? $this->settings['thumbwidth']/$dimensions[0] : $this->settings['thumbheight']/$dimensions[1];
				$thumbwidth = $dimensions[0] * $ratio;
				$thumbheight = $dimensions[1] * $ratio;
				$newimage = imagecreatetruecolor($thumbwidth, $thumbheight);
				imageantialias($newimage, true);
				$thumbfn = $this->settings['thumbfolder']->getpath().DS.$newfn;
				imagecopyresampled($newimage, $oldimage, 0, 0, 0, 0, $thumbwidth, $thumbheight, $dimensions[0], $dimensions[1]);
				
				if ($gif) imagegif($newimage, $thumbfn, 100);
				elseif ($jpg) imagejpeg($newimage, $thumbfn, 100);
				elseif ($png) imagepng($newimage, $thumbfn);
			}
			
			$_POST[$fieldname]['thumb'] = $this->settings['thumbfolder']->getpath().DS.$newfn;
			$_FILES['uploaded'][] = $this->settings['thumbfolder']->getpath().DS.$newfn;
		}
		
		if (!empty($this->settings['currentname'])) {
			if (file_exists($this->settings['currentpath'])) {
				unlink($this->settings['currentpath']);
			}
		}
		
		$_POST[$fieldname]['image'] = $this->settings['uploadfolder']->getpath().DS.$newfn;
		$_POST[$fieldname]['filename'] = $newfn;
		$_FILES['uploaded'][] = $this->settings['uploadfolder']->getpath().DS.$newfn;
		return true;
	}
	
	// Override. Returns a view to be used in a form.
	
	public function getformview() {
		return new View('forms/'.strtolower(get_class($this)), array_merge($this->properties, array('settings' => $this->settings)));
	}
	
	
	/* * * *
	 PRIVATE
	* * * */
	
	private $settings;
	
	private function checkgif($fn) {
		if ($img = @imagecreatefromgif($fn)) return true;
		return false;
	}
	private function checkjpg($fn) {
		if ($img = @imagecreatefromjpeg($fn)) return true;
		return false;
	}
	private function checkpng($fn) {
		if ($img = @imagecreatefrompng($fn)) return true;
		return false;
	}
	
}

?>