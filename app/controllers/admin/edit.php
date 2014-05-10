<?php

$path = Request::get('path');
if ($path) $path = base64_decode($path);

if ($this->rank() >= 6 && $path && file_exists($path)) {
	$File = new File($path);
	if ($File->ancestor('app')) {
		$Form = new Form();
		$Form->add(new SubmitButton('Save Version', 'script_save', 'positive save', 'version'));
		$Form->add(new LinkButton('Close', 'cross', 'negative close'));
		
		$Version = make('Set', 'Version')->where('Version_versionpath=?', $path)->get('Version_path,Version_live');
		$actualfile = new File($Version->Version_path);
		$html = $actualfile->ancestor('views');
		
		if ($Form->handle()) {
			$code = base64_decode(Request::post('field0'), true);
			if ($code === false) throw new FilesystemException('Invalid character encoding.');
			$content = $html ? $code : "<?php\n\n".$code."\n\n?>";
			$File->write($content);
			if ($Version->Version_live) {
				$actualfile->write($content);
			}
			$Version->Version_modified = time();
			$Version->User_id = $this->userid();
			$Version->save();
		}
		
		$this->loadview('codepopup', array(
			'title' => 'Editing source: '.$File,
			'Form' => $Form,
			'source' => $File->source(),
			'html' => $html,
		));
		$this->loadview('refreshparent');
	}
}

?>