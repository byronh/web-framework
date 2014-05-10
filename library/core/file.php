<?php

class File {
	
	// Constructor - gets file if it exists, otherwise creates a new file and writes the given data.
	
	public function __construct($path, $data = '') {
		if (!file_exists($path)) {
			file_put_contents($path, $data);
		}
		$this->path = $path;
	}
	
	
	/* * * * * * * *
	 FILE OPERATIONS
	* * * * * * * */
	
	// Attempts to write the given data to this file (truncates the file first).
	// Returns the number of kilobytes written if successful, false otherwise.
	
	public function write($data) {
		$result = file_put_contents($this->path, $data);
		if ($result === false) return false;
		return round($result / 1024, 2);
	}
	
	// Copies this file to the target folder. Optionally adds an extension.
	// Returns true if successful, false if file already exists in the target destination.
	
	public function copyto(Folder $Folder, $extension = NULL) {
		$path = $Folder->getpath().DS.$this;
		if ($extension) $path .= '.'.$extension;
		if (file_exists($path)) return false;
		return copy($this->path, $path);
	}
	
	// Renames this file.
	// Returns true if successful, false if filename already taken.
	
	public function rename($newname) {
		$path = $this->getparent()->getpath().DS.$newname;
		if (file_exists($path)) return false;
		return rename($this->path, $path);
	}
	
	// Attempts to delete this file.
	// Returns true if successful, false otherwise.
	
	public function delete() {
		return unlink($this->path);
	}
	
	// Returns the source code (contents) of this file.
	// If $highlight is set to true, will return with PHP code coloring.
	
	public function source($highlight = false) {
		return $highlight ? highlight_file($this->path, true) : file_get_contents($this->path);
	}
	
	
	/* * * * * * *
	 FILE ANCESTRY
	* * * * * * */
	
	// Returns a Folder object representing this file's directory.
	
	public function getparent() {
		return new Folder(dirname($this->path));
	}
	
	// Returns true if the given folder name is an ancestor of this file, false otherwise.
	
	public function ancestor($foldername) {
		$parent = $this;
		while ($parent = $parent->getparent()) {
			if ((string)$parent == $foldername) return true;
		}
		return false;
	}
	
	
	/* * * * * * * *
	 FILE PROPERTIES
	* * * * * * * */
	
	public function getpath() {
		return $this->path;
	}
	
	public function getname() {
		if (!isset($this->info)) $this->info = pathinfo($this->path);
		return $this->info['filename'];
	}
	
	public function getextension() {
		if (!isset($this->info)) $this->info = pathinfo($this->path);
		return $this->info['extension'];
	}
	
	public function getsize() {
		return round(filesize($this->path) / 1024, 2).' kb';
	}
	
	public function getmodified() {
		return filemtime($this->path);
	}
	
	
	/* * * * *
	 PROTECTED
	* * * * */
	
	protected $path, $info;
	
	protected function __toString() {
		if (!isset($this->info)) $this->info = pathinfo($this->path);
		return $this->info['basename'];
	}
	
}

?>