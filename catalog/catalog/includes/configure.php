<?php
/*
  $Id: configure.php,v 1.6 2001/09/01 00:23:33 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

// Define the webserver and path parameters
// * DIR_FS_* = Filesystem directories (local/physical)
// * DIR_WS_* = Webserver directories (virtual/URL)
  define('HTTP_SERVER', ''); // eg, http://localhost - should not be NULL for productive servers
  define('HTTPS_SERVER', ''); // eg, https://localhost - should not be NULL for productive servers
  define('ENABLE_SSL', false); // secure webserver for checkout procedure?
  define('DIR_FS_LOGS', '/usr/local/apache/logs/tep/'); // logging directory
  define('DIR_WS_CATALOG', '/catalog/'); // absolute path required
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_INCLUDES', 'includes/'); // If "URL fopen wrappers" are enabled in PHP (which they are in the default configuration), this can be a URL instead of a local pathname
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_PAYMENT_MODULES', DIR_WS_MODULES . 'payment/');
  define('DIR_WS_SHIPPING_MODULES', DIR_WS_MODULES . 'shipping/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');

// define our database connection
  define('DB_SERVER', ''); // eg, localhost - should not be NULL for productive servers
  define('DB_SERVER_USERNAME', 'mysql');
  define('DB_SERVER_PASSWORD', '');
  define('DB_DATABASE', 'catalog');
  define('USE_PCONNECT', true); // use persisstent connections?
  define('STORE_SESSIONS', ''); // leave empty '' for default handler or set to 'mysql'

// default localization values
  define('DEFAULT_LANGUAGE', 'en'); // codes are in the "languages" database table
  define('DEFAULT_CURRENCY', 'USD'); // codes are in the "currencies" database table (and catalog/includes/data/rates.php)
  define('USE_DEFAULT_LANGUAGE_CURRENCY', false); // when the language is changed, use its default currency instead of the applications default currency

// Send order confirmation emails ALSO to these email addresses (separated by a comma)
//  define('SEND_EXTRA_ORDER_EMAILS_TO', 'root <root@localhost>, someone else <someone@else.com>');

// Use Search-engine Friendly URL's (only works in Apache)
  define('SEARCH_ENGINE_FRIENDLY_URLS', false);

// set up cache functionality - only for PHP4
  define('CACHE_ON', false); // Default: false - Turn caching on/off
  define('DIR_FS_CACHE', '/tmp/'); // Default: /tmp/ - Default cache directory
?>
