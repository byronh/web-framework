<?php

if (!defined('ROOT')) die('Direct include not allowed.');

/* * * * * * * * *
 CRON JOB CONFIG
* * * * * * * * */

// Defines the length of one day in seconds.

define('CRON_DAY', 60 * 60 * 24);

// Extension to add to backed-up files.

define('CRON_BACKUP_EXT', 'bak');

// Delete backups older than X days:

define('DB_DELETE_OLD', 7);
define('APP_DELETE_OLD', 7);

?>