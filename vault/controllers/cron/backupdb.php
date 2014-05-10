<?php

// Create backup

$Database = Factory::get('Database');
$Folder = new Folder(ROOT.DS.'backup'.DS.'database');

$filename = DB_NAME.'.'.time().'.sql';
$data = "/* Backup date: ".Date::long(time())." */\n\n".$Database->backup();

$Folder->createfile($filename, $data);

// Delete old backups

foreach ($Folder->getfilesbyextension('sql') as $File) {
	$parts = explode('.', $File);
	$time = $parts[1];
	if ($time <= time() - (DB_DELETE_OLD * CRON_DAY)) {
		$File->delete();
	}
}

?>