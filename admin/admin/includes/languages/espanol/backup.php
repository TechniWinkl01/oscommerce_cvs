<?php
/*
  $Id: backup.php,v 1.6 2001/11/21 09:04:08 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

define('TOP_BAR_TITLE', 'Copia de Seguridad');
define('HEADING_TITLE', 'Copia de Seguridad');

define('TABLE_HEADING_TITLE', 'Título');
define('TABLE_HEADING_FILE_DATE', 'Fecha');
define('TABLE_HEADING_FILE_SIZE', 'Tamaño');
define('TABLE_HEADING_ACTION', 'Acción');

define('TEXT_INFO_HEADING_NEW_BACKUP', 'Nueva Copia De Seguridad');
define('TEXT_INFO_NEW_BACKUP', 'No interrumpa el proceso de copia, que puede durar unos minutos.');
define('TEXT_INFO_UNPACK', '<br><br>(after unpacking the file from the archive)');
define('TEXT_INFO_RESTORE', 'Do not interrupt the restoration process.<br><br>The larger the backup, the longer this process takes!<br><br>If possible, use the mysql client.<br><br>For example:<br><br><b>mysql -h' . DB_SERVER . ' -u' . DB_SERVER_USERNAME . ' -p ' . DB_DATABASE . ' < %s </b> %s');
define('TEXT_INFO_DATE', 'Fecha:');
define('TEXT_INFO_SIZE', 'Tamaño:');
define('TEXT_INFO_COMPRESSION', 'Compresión:');
?>