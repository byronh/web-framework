<?php

class Version extends Model {
	
	public static $parents = array('User');
	public static $children = array();
	
	public function create($path, $content, $userid, $comment = '') {
		$time = time();
		$versionpath = implode('_', array_filter(explode('/', str_replace(ROOT.DS.'app', '', $path)))).'.'.$time.'.ver';
		$Folder = new Folder(ROOT.DS.'app'.DS.'revision');
		if (!$content) $content = "<?php\n\n\n\n?>";
		$Folder->createfile($versionpath, $content);
		
		$Version = make('Set', 'Version')->where('Version_path=?', $path)->sortdesc('Version_number')->get('Version_number');
		$this->Version_number = $Version ? $Version->Version_number + 1 : 1;
		$this->User_id = $userid;
		$this->Version_path = $path;
		$this->Version_versionpath = ROOT.DS.'app'.DS.'revision'.DS.$versionpath;
		$this->Version_modified = $time;
		$this->Version_created = $time;
		$this->Version_comment = $comment;
		
		parent::save();
	}
	
}

?>