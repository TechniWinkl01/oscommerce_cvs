<?php
/*
  $Id: configure.php,v 1.3 2001/12/30 03:42:35 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/

// define our webserver variables
// FS = Filesystem (physical)
// WS = Webserver (virtual)
  define('HTTP_SERVER', '');
  define('DIR_FS_DOCUMENT_ROOT', $DOCUMENT_ROOT); // where your pages are located on the server. if $DOCUMENT_ROOT doesnt suit you, replace with your local path. (eg, /usr/local/apache/htdocs)
  define('DIR_WS_ADMIN', '/admin/');
  define('DIR_WS_CATALOG', '/catalog/');
  define('DIR_FS_CATALOG', DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG);
  define('DIR_WS_IMAGES', DIR_WS_ADMIN . 'images/');
  define('DIR_WS_CATALOG_IMAGES', DIR_WS_CATALOG . 'images/');
  define('DIR_WS_INCLUDES', 'includes/');
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');
  define('DIR_WS_CATALOG_LANGUAGES', DIR_WS_CATALOG . 'includes/languages/');
  define('DIR_FS_CATALOG_LANGUAGES', DIR_FS_CATALOG . 'includes/languages/');
  define('DIR_FS_CATALOG_IMAGES', DIR_FS_CATALOG . 'images/');
  define('DIR_FS_PAYMENT_MODULES', DIR_FS_CATALOG . 'includes/modules/payment/');
  define('DIR_FS_SHIPPING_MODULES', DIR_FS_CATALOG . 'includes/modules/shipping/');
  define('DIR_FS_CACHE', '/tmp/'); // cache from the catalog
  define('DIR_FS_BACKUP', DIR_FS_DOCUMENT_ROOT . DIR_WS_ADMIN . 'backups/');

// define our database connection
  define('DB_SERVER', '');
  define('DB_SERVER_USERNAME', 'mysql');
  define('DB_SERVER_PASSWORD', '');
  define('DB_DATABASE', 'catalog');
  define('USE_PCONNECT', 1);
  define('STORE_SESSIONS', '');
?>
