<?php
/*
  $Id: configure.php,v 1.8 2002/04/03 23:25:41 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

// define our webserver variables
// FS = Filesystem (physical)
// WS = Webserver (virtual)
  define('HTTP_SERVER', ''); // eg, http://localhost - should not be NULL for productive servers
  define('HTTPS_SERVER', ''); // eg, https://localhost - should not be NULL for productive servers
  define('HTTP_CATALOG_SERVER', '');
  define('HTTPS_CATALOG_SERVER', '');
  define('ENABLE_SSL', 'false'); // secure webserver for administration tool
  define('ENABLE_SSL_CATALOG', 'false'); // secure webserver for catalog module
  define('DIR_FS_DOCUMENT_ROOT', $DOCUMENT_ROOT); // where your pages are located on the server. if $DOCUMENT_ROOT doesnt suit you, replace with your local path. (eg, /usr/local/apache/htdocs)
  define('DIR_WS_ADMIN', '/admin/');
  define('DIR_WS_CATALOG', '/catalog/');
  define('DIR_FS_CATALOG', DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG);
  define('DIR_WS_IMAGES', DIR_WS_ADMIN . 'images/');
  define('DIR_WS_ICONS', DIR_WS_ADMIN . 'images/icons/');
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
  define('DIR_FS_ORDER_TOTAL_MODULES', DIR_FS_CATALOG . 'includes/modules/order_total/');
  define('DIR_FS_CACHE', '/tmp/'); // cache from the catalog
  define('DIR_FS_BACKUP', DIR_FS_DOCUMENT_ROOT . DIR_WS_ADMIN . 'backups/');

// define our database connection
  define('DB_SERVER', '');
  define('DB_SERVER_USERNAME', 'mysql');
  define('DB_SERVER_PASSWORD', '');
  define('DB_DATABASE', 'catalog');
  define('USE_PCONNECT', 'false');
  define('STORE_SESSIONS', '');
?>
