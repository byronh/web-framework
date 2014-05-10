<?php

class Folder {
	
	// Constructor - gets folder if it exists, creates a new folder otherwise.
	
	public function __construct($path) {
		if (!file_exists($path)) mkdir($path, 0755, true);
		$this->path = $path;
	}
	
	
	/* * * * * * * * *
	 FOLDER OPERATIONS
	* * * * * * * * */
	
	// Attempts to deletes this folder and its immediate children files. Must not contain any subfolders.
	// If $recursive set to true, will also recursively delete all subfolders and their contents.
	// Returns true if successful, false otherwise.
	
	public function delete($recursive = false) {
		if ($recursive == true) {
			foreach ($this->getfolders() as $Folder) {
				$Folder->delete(true);
			}
		} elseif (count($this->getfolders())) return false;
		$this->deleteallfiles();
		return rmdir($this->path);
	}
	
	
	// Copies this folder and all its contents to the target folder.
	// Optionally add extension to all copied files, such as 'bak'.
	
	public function copyto(Folder $Folder, $extension = NULL) {
		$destination = $Folder;
		foreach ($this->getfolders() as $Folder) {
			$Folder->copyto($destination->createfolder($Folder), $extension);
		}
		foreach ($this->getfiles() as $File) {
			$File->copyto($destination, $extension);
		}
		
	}
	
	// Archives this folder and all its contents into a zip file in the target folder.
	
	public function zipto(Folder $Folder) {
		$destination = $Folder->path.DS.$this.'.zip';
		$zip = new ZipArchive();
		if ($zip->open($destination, ZIPARCHIVE::CREATE) === true) {
			$source = $this->path;
			$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
			foreach ($files as $file) {
				$file = realpath($file);
				if (is_dir($file) === true) {
					$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
				} else if (is_file($file) === true) {
					$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
				}
			}
		}
		return $zip->close();
	}
	
	
	/* * * * * * * * * *
	 SUBFOLDER OPERATIONS
	* * * * * * * * * */	
	
	// Returns a Folder object if subfolder exists in this folder with the given name, false otherwise.
	// If $recursive set to true, will also recursively search all subfolders and return the first result.
	
	public function getfolder($foldername, $recursive = false) {
		if ($recursive == true) {
			foreach ($this->getfolders() as $Folder) {
				if ($Folder = $Folder->getfolder($foldername, true)) return $Folder;
			}
		}
		$path = $this->path.DS.$foldername;
		if (!file_exists($path)) return false;
		return new Folder($path);
	}
	
	// Returns array of Folder objects that are subdirectories of this folder.
	
	public function getfolders($recursive = false) {
		$Folders = array();
		if ($recursive == true) {
			foreach ($this->getfolders() as $Folder) {
				$Folders = array_merge($Folders, $Folder->getfolders(true));
			}
		}
		$glob = glob($this->path.DS.'*');
		if (!empty($glob)) {
			foreach (array_filter($glob, 'is_dir') as $folderpath) {
				$Folders[] = new Folder($folderpath);
			}
		}
		return $Folders;
	}
	
	// Attempts to create a new subfolder.
	// Returns the newly created subfolder if successful, false otherwise.
	
	public function createfolder($foldername) {
		$path = $this->path.DS.$foldername;
		if (file_exists($path)) return false;
		return new Folder($path);
	}
	
	// Attempts to delete the subfolder with the given name and its immediate children files. Must not contain any subfolders.
	// If $recursive set to true, will also recursively delete all subfolders and their contents.
	// Returns true if successful, false otherwise.
	
	public function deletefolder($foldername, $recursive = false) {
		$path = $this->path.DS.$foldername;
		if (!file_exists($path) || !is_dir($path)) return false;
		$Folder = new Folder($path);
		return $Folder->delete($recursive);
	}
	
	
	/* * * * * * * *
	 FILE OPERATIONS
	* * * * * * * */
	
	// Returns a File object if file exists in this folder with the given filename, false otherwise.
	// If $recursive set to true, will also recursively search all subfolders and return the first result.
	
	public function getfile($filename, $recursive = false) {
		if ($recursive == true) {
			foreach ($this->getfolders() as $Folder) {
				if ($File = $Folder->getfile($filename, true)) return $File;
			}
		}
		$path = $this->path.DS.$filename;
		if (!file_exists($path)) return false;
		return new File($path);
	}
	
	// Returns an array containing all the files in this folder.
	// If $recursive set to true, will also include all files in all subfolders.
	
	public function getfiles($recursive = false) {
		return $this->getfilesbyextension('*', $recursive);
	}
	
	// Returns an array containing all the files in this folder with the given extension.
	// If $recursive set to true, will also include all files in all subfolders.
	
	public function getfilesbyextension($extension, $recursive = false) {
		$Files = array();
		if ($recursive == true) {
			foreach ($this->getfolders() as $Folder) {
				$Files = array_merge($Files, $Folder->getfilesbyextension($extension, true));
			}
		}
		$glob = glob($this->path.DS.'*.'.$extension);	
		if (!empty($glob)) {
			sort($glob);
			$glob = array_reverse($glob);
			foreach ($glob as $filepath) {
				$Files[] = new File($filepath);
			}
		}
		return $Files;
	}
	
	// Returns an array containing the paginated files in this folder with the given extension.
	
	public function paginatefiles($extension, $perpage = 10, $recursive = false) {
		$Files = $this->getfilesbyextension($extension, $recursive);
		$page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
		$totalresults = count($Files);
		$totalpages = ceil($totalresults / $perpage);
		if ($page > $totalpages) $page = $totalpages;
		$result = array_slice($Files, ($page - 1) * $perpage, $perpage);
		$this->Paginator = new Paginator(array(
			'currentpage' => $page,
			'totalpages' => $totalpages,
			'firstresult' => ($page > 0) ? ($page-1) * $perpage+1 : 0,
			'lastresult' => (($last = $page * $perpage) > $totalresults) ? $totalresults : $last,
			'totalresults' => $totalresults
		));
		return $result;
	}
		
	// Attempts to create a new file.
	// Returns the newly created file if successful, false otherwise.
	
	public function createfile($filename, $data = '') {
		$path = $this->path.DS.$filename;
		if (file_exists($path)) return false;
		return new File($path, $data);
	}
	
	// Attempts to deletes a file in this directory.
	// Returns true if successful, false otherwise.
	
	public function deletefile($filename) {
		$path = $this->path.DS.$filename;
		if (!file_exists($path)) return false;
		$File = new File($path);
		return $File->delete();
	}
	
	// Deletes all files in this directory.
	
	public function deleteallfiles() {
		foreach ($this->getfilesbyextension('*') as $File) {
			$File->delete();
		}
		return true;
	}
	
	
	/* * * * * * * *
	 FOLDER ANCESTRY
	* * * * * * * */
	
	// Returns a Folder object representing the parent folder of this folder.
	// Returns false if already at the top directory.
	
	public function getparent() {
		if ($this->path == ROOT) return false;
		return new Folder(dirname($this->path));
	}
	
	// Returns true if the given folder name is an ancestor of this folder, false otherwise.
	
	public function ancestor($foldername) {
		$parent = $this;
		while ($parent = $parent->getparent()) {
			if ($parent->name == $foldername) return true;
		}
		return false;
	}
	
	
	/* * * * * * * * *
	 FOLDER PROPERTIES
	* * * * * * * * */
	
	public function getpath() {
		return $this->path;
	}
		
	
	/* * * * *
	 PROTECTED
	* * * * */
	
	protected $path;
	
	protected function __toString() {
		return basename($this->path);
	}
	
}

?>