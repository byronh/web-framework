<?php

// Re-build app directories and files.

$Folder = new Folder(ROOT.DS.'app');
$Folder->copyto(new Folder(ROOT.DS.'backup'.DS.'app'.DS.time()), CRON_BACKUP_EXT);

// Delete old backups

$backup = new Folder(ROOT.DS.'backup'.DS.'app');
foreach ($backup->getfolders() as $Folder) {
	if ((string)$Folder <= time() - (APP_DELETE_OLD * CRON_DAY)) {
		$Folder->delete(true);
	}
}

?>