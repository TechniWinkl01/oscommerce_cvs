<?php
/*
  $Id: configure.php,v 1.1 2001/05/23 11:24:39 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  define('HTTP_SERVER', 'http://localhost');
  define('HTTPS_SERVER', 'https://localhost');
  define('ENABLE_SSL', 0); // ssl server enable(1)/disable(0)

  define('DIR_FS_DOCUMENT_ROOT', 'C:/Program Files/Apache Group/Apache/htdocs/');
  define('DIR_FS_LOGS', 'C:/Program Files/Apache Group/Apache/logs/');

  define('DB_SERVER', 'localhost');
  define('DB_SERVER_USERNAME', 'root');
  define('DB_SERVER_PASSWORD', '');

  define('CONFIGURE_STATUS_COMPLETED', 1);
?>