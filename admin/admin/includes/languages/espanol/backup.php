<?php
/*
  $Id: backup.php,v 1.7 2001/11/21 12:46:18 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

define('TOP_BAR_TITLE', 'Copia de Seguridad');
define('HEADING_TITLE', 'Copia de Seguridad');

define('TABLE_HEADING_TITLE', 'T�tulo');
define('TABLE_HEADING_FILE_DATE', 'Fecha');
define('TABLE_HEADING_FILE_SIZE', 'Tama�o');
define('TABLE_HEADING_ACTION', 'Acci�n');

define('TEXT_INFO_HEADING_NEW_BACKUP', 'Nueva Copia De Seguridad');
define('TEXT_INFO_NEW_BACKUP', 'No interrumpa el proceso de copia, que puede durar unos minutos.');
define('TEXT_INFO_UNPACK', '<br><br>(despues de descomprimir el archivo)');
define('TEXT_INFO_RESTORE', 'No interrumpa el proceso de restauraci�n.<br><br>Cuanto mas grande sea la copia de seguridad, mas tardar� este proceso!<br><br>Si es posible, use el cliente de mysql.<br><br>Por ejemplo:<br><br><b>mysql -h' . DB_SERVER . ' -u' . DB_SERVER_USERNAME . ' -p ' . DB_DATABASE . ' < %s </b> %s');
define('TEXT_INFO_DATE', 'Fecha:');
define('TEXT_INFO_SIZE', 'Tama�o:');
define('TEXT_INFO_COMPRESSION', 'Compresi�n:');
?>