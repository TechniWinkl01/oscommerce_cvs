<?php
/*
  $Id: file_manager.php,v 1.11 2002/01/09 06:04:46 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Datei-Manager');

define('TABLE_HEADING_FILENAME', 'Name');
define('TABLE_HEADING_SIZE', 'Größe');
define('TABLE_HEADING_PERMISSIONS', 'Permissions');
define('TABLE_HEADING_USER', 'User');
define('TABLE_HEADING_GROUP', 'Group');
define('TABLE_HEADING_LAST_MODIFIED', 'Last Modified');
define('TABLE_HEADING_ACTION', 'Aktion');

define('TEXT_FILE_NAME', 'Dateiname:');
define('TEXT_FILE_SIZE', 'Größe:');
define('TEXT_FILE_CONTENTS', 'Inhalt:');
define('TEXT_LAST_MODIFIED', 'letzte Änderung:');
define('TEXT_NEW_FOLDER', 'Neues Verzeichnis');
define('TEXT_NEW_FOLDER_INTRO', 'Geben Sie den Namen für das neue Verzeichnis ein:');
define('TEXT_DELETE_INTRO', 'Sind Sie sicher, daß Sie diese Datei löschen möchten?');
define('TEXT_UPLOAD', 'Upload');

define('ERROR_DIRECTORY_NOT_WRITEABLE', 'Error: I can not write to this directory. Please set the right user permissions on: %s');
define('ERROR_FILE_NOT_WRITEABLE', 'Error: I can not write to this file. Please set the right user permissions on: %s');
define('ERROR_DIRECTORY_NOT_REMOVEABLE', 'Error: I can not remove this directory. Please set the right user permissions on: %s');
define('ERROR_FILE_NOT_REMOVEABLE', 'Error: I can not remove this file. Please set the right user permissions on: %s');
?>