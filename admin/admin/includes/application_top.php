<?php
/*
  $Id: application_top.php,v 1.172 2004/10/28 12:25:12 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

// start the timer for the page parse time log
  define('PAGE_PARSE_START_TIME', microtime());

// set the local configuration parameters - mainly for developers
  if (file_exists('includes/local/configure.php')) include('includes/local/configure.php');

// include server parameters
  require('includes/configure.php');

// set the level of error reporting
  error_reporting(E_ALL & ~E_NOTICE);

// Define the project version
  define('PROJECT_VERSION', 'osCommerce 2.2-MS3-CVS');

// set the type of request (secure or not)
  $request_type = (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on')) ? 'SSL' : 'NONSSL';

// set php_self in the local scope
  if (!isset($PHP_SELF)) $PHP_SELF = (isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']);

// Used in the "Backup Manager" to compress backups
  define('LOCAL_EXE_GZIP', '/usr/bin/gzip');
  define('LOCAL_EXE_GUNZIP', '/usr/bin/gunzip');
  define('LOCAL_EXE_ZIP', '/usr/local/bin/zip');
  define('LOCAL_EXE_UNZIP', '/usr/local/bin/unzip');

// compatibility work-around logic for PHP4
  require('includes/functions/compatibility.php');

// include the list of project filenames
  require('includes/filenames.php');

// include the list of project database tables
  require('../includes/database_tables.php');

// initialize the cache class
  require('../includes/classes/cache.php');
  $osC_Cache = new osC_Cache;

// include the database functions
  require('../includes/functions/database.php');

// include the database class
  require('../includes/classes/database.php');

  $osC_Database = osC_Database::connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
  $osC_Database->selectDatabase(DB_DATABASE);

// set application wide parameters
  $Qcfg = $osC_Database->query('select configuration_key as cfgKey, configuration_value as cfgValue from :table_configuration');
  $Qcfg->bindRaw(':table_configuration', TABLE_CONFIGURATION);
  $Qcfg->setCache('configuration');
  $Qcfg->execute();

  while ($Qcfg->next()) {
    define($Qcfg->value('cfgKey'), $Qcfg->value('cfgValue'));
  }

  $Qcfg->freeResult();

// define our general functions used application-wide
  require('includes/functions/general.php');
  require('includes/functions/html_output.php');

// initialize the logger class
  require('includes/classes/logger.php');

// include session class
  if (PHP_VERSION < 4.1) {
    include('../includes/classes/session_compatible.php');
  } else {
    include('../includes/classes/session.php');
  }
  $osC_Session = new osC_Session;
  $osC_Session->setName('osCAdminID');
  $osC_Session->setSavePath(DIR_FS_WORK);

// define how the session functions will be used
  require('includes/functions/sessions.php');

// lets start our session
  $osC_Session->start();

// set the language
  if (($osC_Session->exists('language') == false) || isset($_GET['language'])) {
    include('../includes/classes/language.php');
    $lng = new language;

    if (isset($_GET['language']) && tep_not_null($_GET['language'])) {
      $lng->set_language($_GET['language']);
    } else {
      $lng->get_browser_language();
    }

    $osC_Session->set('language', $lng->language['directory']);
    $osC_Session->set('languages_id', $lng->language['id']);
  }

  require('includes/languages/' . $osC_Session->value('language') . '.php');

  $current_page = basename($PHP_SELF);
  if (file_exists('includes/languages/' . $osC_Session->value('language') . '/' . $current_page)) {
    include('includes/languages/' . $osC_Session->value('language') . '/' . $current_page);
  }

  header('Content-Type: text/html; charset=' . CHARSET);

  setlocale(LC_TIME, LANGUAGE_LOCALE);

// define our localization functions
  require('includes/functions/localization.php');

// Include validation functions (right now only email address)
  require('includes/functions/validations.php');

// setup our boxes; PENDING REMOVAL (dependencies: messageStack class)
  require('includes/classes/table_block.php');

// initialize the message stack for output messages
  require('includes/classes/message_stack.php');
  $messageStack = new messageStack;

// split-page-results
  require('includes/classes/split_page_results.php');

// entry/item info classes
  require('includes/classes/object_info.php');

// email classes
  require('includes/classes/mime.php');
  require('includes/classes/email.php');

// file uploading class
  require('includes/classes/upload.php');

// check if a default currency is set
  if (!defined('DEFAULT_CURRENCY')) {
    $messageStack->add(ERROR_NO_DEFAULT_CURRENCY_DEFINED, 'error');
  }

// check if a default language is set
  if (!defined('DEFAULT_LANGUAGE')) {
    $messageStack->add(ERROR_NO_DEFAULT_LANGUAGE_DEFINED, 'error');
  }

  if (function_exists('ini_get') && ((bool)ini_get('file_uploads') == false) ) {
    $messageStack->add(WARNING_FILE_UPLOADS_DISABLED, 'warning');
  }
?>
