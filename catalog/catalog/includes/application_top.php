<?php
/*
  $Id: application_top.php,v 1.283 2003/12/18 23:52:14 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// start the timer for the page parse time log
  define('PAGE_PARSE_START_TIME', microtime());

// set the level of error reporting
  error_reporting(E_ALL & ~E_NOTICE);

// Set the local configuration parameters - mainly for developers
  if (file_exists('includes/local/configure.php')) include('includes/local/configure.php');

// include server parameters
  require('includes/configure.php');

  if (strlen(DB_SERVER) < 1) {
    if (is_dir('install')) {
      header('Location: install/index.php');
    }
  }

// define the project version
  define('PROJECT_VERSION', 'osCommerce 2.2-MS3-CVS');

// set the type of request (secure or not)
  $request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';

  if ($request_type == 'NONSSL') {
    define('DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG);
  } else {
    define('DIR_WS_CATALOG', DIR_WS_HTTPS_CATALOG);
  }

// set the cookie domain
  $cookie_domain = (($request_type == 'NONSSL') ? HTTP_COOKIE_DOMAIN : HTTPS_COOKIE_DOMAIN);
  $cookie_path = (($request_type == 'NONSSL') ? HTTP_COOKIE_PATH : HTTPS_COOKIE_PATH);

// compatibility work-around logic for PHP4
  require(DIR_WS_FUNCTIONS . 'compatibility.php');

// set php_self in the local scope
  if (!isset($PHP_SELF)) $PHP_SELF = $_SERVER['PHP_SELF'];

// include the list of project filenames
  require(DIR_WS_INCLUDES . 'filenames.php');

// include the list of project database tables
  require(DIR_WS_INCLUDES . 'database_tables.php');

// customization for the design layout
  define('BOX_WIDTH', 125); // how wide the boxes should be in pixels (default: 125)

// include the database functions
  require(DIR_WS_FUNCTIONS . 'database.php');

// make a connection to the database... now
  tep_db_connect() or die('Unable to connect to database server!');

// set the application parameters
  $configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
  while ($configuration = tep_db_fetch_array($configuration_query)) {
    define($configuration['cfgKey'], $configuration['cfgValue']);
  }

// if gzip_compression is enabled, start to buffer the output
  if ( (GZIP_COMPRESSION == 'true') && ($ext_zlib_loaded = extension_loaded('zlib')) && (PHP_VERSION >= 4) ) {
    if (($ini_zlib_output_compression = (int)ini_get('zlib.output_compression')) < 1) {
      if (PHP_VERSION >= '4.0.4') {
        ob_start('ob_gzhandler');
      } else {
        include(DIR_WS_FUNCTIONS . 'gzip_compression.php');
        ob_start();
        ob_implicit_flush();
      }
    } else {
      ini_set('zlib.output_compression_level', GZIP_LEVEL);
    }
  }

// set the HTTP GET parameters manually if search_engine_friendly_urls is enabled
  if (SEARCH_ENGINE_FRIENDLY_URLS == 'true') {
    if (strlen(getenv('PATH_INFO')) > 1) {
      $GET_array = array();
      $PHP_SELF = str_replace(getenv('PATH_INFO'), '', $PHP_SELF);
      $vars = explode('/', substr(getenv('PATH_INFO'), 1));
      for ($i=0, $n=sizeof($vars); $i<$n; $i++) {
        if (strpos($vars[$i], '[]')) {
          $GET_array[substr($vars[$i], 0, -2)][] = $vars[$i+1];
        } else {
          $_GET[$vars[$i]] = $vars[$i+1];
        }
        $i++;
      }

      if (sizeof($GET_array) > 0) {
        while (list($key, $value) = each($GET_array)) {
          $_GET[$key] = $value;
        }
      }
    }
  }

// define general functions used application-wide
  require(DIR_WS_FUNCTIONS . 'general.php');
  require(DIR_WS_FUNCTIONS . 'html_output.php');

// include cache functions if enabled
  if (USE_CACHE == 'true') include(DIR_WS_FUNCTIONS . 'cache.php');

// include shopping cart class
  require(DIR_WS_CLASSES . 'shopping_cart.php');

// include customer class
  require(DIR_WS_CLASSES . 'customer.php');

// include session class
  if (PHP_VERSION < 4.1) {
    include(DIR_WS_CLASSES . 'session_compatible.php');
  } else {
    include(DIR_WS_CLASSES . 'session.php');
  }
  $osC_Session = new osC_Session;

// include navigation history class
  require(DIR_WS_CLASSES . 'navigation_history.php');

// start the session
  if (SESSION_FORCE_COOKIE_USE == 'True') {
    tep_setcookie('cookie_test', 'please_accept_for_session', time()+60*60*24*30, $cookie_path, $cookie_domain);

    if (isset($_COOKIE['cookie_test'])) {
      $osC_Session->start();
    }
  } elseif (SESSION_BLOCK_SPIDERS == 'True') {
    $user_agent = strtolower(getenv('HTTP_USER_AGENT'));
    $spider_flag = false;

    if (tep_not_null($user_agent)) {
      $spiders = file(DIR_WS_INCLUDES . 'spiders.txt');

      for ($i=0, $n=sizeof($spiders); $i<$n; $i++) {
        if (tep_not_null($spiders[$i])) {
          if (is_integer(strpos($user_agent, trim($spiders[$i])))) {
            $spider_flag = true;
            break;
          }
        }
      }
    }

    if ($spider_flag == false) {
      $osC_Session->start();
    }
  } else {
    $osC_Session->start();
  }

// set SID once, even if empty
  $SID = (defined('SID') ? SID : '');

// verify the ssl_session_id if the feature is enabled
  if ( ($request_type == 'SSL') && (SESSION_CHECK_SSL_SESSION_ID == 'True') && (ENABLE_SSL == true) && ($osC_Session->is_started == true) ) {
    $ssl_session_id = getenv('SSL_SESSION_ID');

    if ($osC_Session->exists('SESSION_SSL_ID') == false) {
      $osC_Session->set('SESSION_SSL_ID', $ssl_session_id);
    }

    if ($osC_Session->value('SESSION_SSL_ID') != $ssl_session_id) {
      $osC_Session->destroy();

      tep_redirect(tep_href_link(FILENAME_SSL_CHECK));
    }
  }

// verify the browser user agent if the feature is enabled
  if (SESSION_CHECK_USER_AGENT == 'True') {
    $http_user_agent = getenv('HTTP_USER_AGENT');

    if ($osC_Session->exists('SESSION_USER_AGENT') == false) {
      $osC_Session->set('SESSION_USER_AGENT', $http_user_agent);
    }

    if ($osC_Session->value('SESSION_USER_AGENT') != $http_user_agent) {
      $osC_Session->destroy();

      tep_redirect(tep_href_link(FILENAME_LOGIN));
    }
  }

// verify the IP address if the feature is enabled
  if (SESSION_CHECK_IP_ADDRESS == 'True') {
    $ip_address = tep_get_ip_address();

    if ($osC_Session->exists('SESSION_IP_ADDRESS') == false) {
      $osC_Session->set('SESSION_IP_ADDRESS', $ip_address);
    }

    if ($osC_Session->value('SESSION_IP_ADDRESS') != $ip_address) {
      $osC_Session->destroy();

      tep_redirect(tep_href_link(FILENAME_LOGIN));
    }
  }

// create an instance of the shopping cart
  if ($osC_Session->exists('cart')) {
    $cart =& $osC_Session->value('cart');
  } else {
    $cart = new shoppingCart;
    $osC_Session->set('cart', $cart);
  }

// create an instance of the customer class
  if ($osC_Session->exists('osC_Customer')) {
    $osC_Customer =& $osC_Session->value('osC_Customer');
  } else {
    $osC_Customer = new osC_Customer;
    $osC_Session->set('osC_Customer', $osC_Customer);
  }

// include currencies class and create an instance
  require(DIR_WS_CLASSES . 'currencies.php');
  $osC_Currencies = new osC_Currencies();

// include the mail classes
  require(DIR_WS_CLASSES . 'mime.php');
  require(DIR_WS_CLASSES . 'email.php');

// set the language
  if (($osC_Session->exists('language') == false) || isset($_GET['language'])) {
    include(DIR_WS_CLASSES . 'language.php');
    $lng = new language;

    if (isset($_GET['language']) && tep_not_null($_GET['language'])) {
      $lng->set_language($_GET['language']);
    } else {
      $lng->get_browser_language();
    }

    $osC_Session->set('language', $lng->language['directory']);
    $osC_Session->set('languages_id', $lng->language['id']);
  }

// include the language translations
  require(DIR_WS_LANGUAGES . $osC_Session->value('language') . '.php');

// currency
  if (($osC_Session->exists('currency') == false) || isset($_GET['currency']) || ( (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') && (LANGUAGE_CURRENCY != $osC_Session->value('currency')) ) ) {
    if (isset($_GET['currency']) && $osC_Currencies->exists($_GET['currency'])) {
      $currency = $_GET['currency'];
    } else {
      $currency = (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;
    }

    $osC_Session->set('currency', $currency);
  }

// tax class
  require(DIR_WS_CLASSES . 'tax.php');
  $osC_Tax = new osC_Tax;

// navigation history
  if ($osC_Session->exists('navigation')) {
    $navigation =& $osC_Session->value('navigation');
  } else {
    $navigation = new navigationHistory;
    $osC_Session->set('navigation', $navigation);
  }
  $navigation->add_current_page();

// Shopping cart actions
  if (isset($_GET['action'])) {
// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled
    if ($osC_Session->is_started == false) {
      tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));
    }

    if (DISPLAY_CART == 'true') {
      $goto =  FILENAME_SHOPPING_CART;
      $parameters = array('action', 'cPath', 'products_id', 'pid');
    } else {
      $goto = basename($PHP_SELF);
      if ($_GET['action'] == 'buy_now') {
        $parameters = array('action', 'pid', 'products_id');
      } else {
        $parameters = array('action', 'pid');
      }
    }

    switch ($_GET['action']) {
      // customer wants to update the product quantity in their shopping cart
      case 'update_product' : for ($i=0, $n=sizeof($_POST['products_id']); $i<$n; $i++) {
                                if (isset($_POST['cart_delete']) && is_array($_POST['cart_delete']) && in_array($_POST['products_id'][$i], $_POST['cart_delete'])) {
                                  $cart->remove($_POST['products_id'][$i]);
                                } else {
                                  $attributes = (isset($_POST['id']) && isset($_POST['id'][$_POST['products_id'][$i]])) ? $_POST['id'][$_POST['products_id'][$i]] : '';
                                  $cart->add_cart($_POST['products_id'][$i], $_POST['cart_quantity'][$i], $attributes, false);
                                }
                              }

                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                              break;
      // customer adds a product from the products page
      case 'add_product' :    if (isset($_POST['products_id']) && is_numeric($_POST['products_id'])) {
                                if (isset($_POST['id']) && is_array($_POST['id'])) {
                                  $cart->add_cart($_POST['products_id'], $cart->get_quantity(tep_get_uprid($_POST['products_id'], $_POST['id']))+1, $_POST['id']);
                                } else {
                                  $cart->add_cart($_POST['products_id'], $cart->get_quantity($_POST['products_id'])+1);
                                }
                              }

                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                              break;
      // performed by the 'buy now' button in product listings and review page
      case 'buy_now' :        if (isset($_GET['products_id'])) {
                                if (tep_has_product_attributes($_GET['products_id'])) {
                                  tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $_GET['products_id']));
                                } else {
                                  $cart->add_cart($_GET['products_id'], $cart->get_quantity($_GET['products_id'])+1);
                                }
                              }

                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                              break;
      case 'notify' :         if ($osC_Customer->isLoggedOn()) {
                                if (isset($_GET['products_id'])) {
                                  $notify = $_GET['products_id'];
                                } elseif (isset($_GET['notify'])) {
                                  $notify = $_GET['notify'];
                                } elseif (isset($_POST['notify'])) {
                                  $notify = $_POST['notify'];
                                } else {
                                  tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'notify'))));
                                }

                                if (!is_array($notify)) $notify = array($notify);
                                for ($i=0, $n=sizeof($notify); $i<$n; $i++) {
                                  $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$notify[$i] . "' and customers_id = '" . (int)$osC_Customer->id . "'");
                                  $check = tep_db_fetch_array($check_query);
                                  if ($check['count'] < 1) {
                                    tep_db_query("insert into " . TABLE_PRODUCTS_NOTIFICATIONS . " (products_id, customers_id, date_added) values ('" . (int)$notify[$i] . "', '" . (int)$osC_Customer->id . "', now())");
                                  }
                                }

                                tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'notify'))));
                              } else {
                                $navigation->set_snapshot();

                                tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
                              }
                              break;
      case 'notify_remove' :  if ($osC_Customer->isLoggedOn() && isset($_GET['products_id'])) {
                                $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$_GET['products_id'] . "' and customers_id = '" . (int)$osC_Customer->id . "'");
                                $check = tep_db_fetch_array($check_query);
                                if ($check['count'] > 0) {
                                  tep_db_query("delete from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$_GET['products_id'] . "' and customers_id = '" . (int)$osC_Customer->id . "'");
                                }

                                tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action'))));
                              } else {
                                $navigation->set_snapshot();

                                tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
                              }
                              break;
      case 'cust_order' :     if ($osC_Customer->isLoggedOn() && isset($_GET['pid'])) {
                                if (tep_has_product_attributes($_GET['pid'])) {
                                  tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $_GET['pid']));
                                } else {
                                  $cart->add_cart($_GET['pid'], $cart->get_quantity($_GET['pid'])+1);
                                }
                              }

                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                              break;
    }
  }

// include the who's online functions
  require(DIR_WS_FUNCTIONS . 'whos_online.php');
  tep_update_whos_online();

// include the password crypto functions
  require(DIR_WS_FUNCTIONS . 'password_funcs.php');

// include validation functions (right now only email address)
  require(DIR_WS_FUNCTIONS . 'validations.php');

// split-page-results
  require(DIR_WS_CLASSES . 'split_page_results.php');

// infobox
  require(DIR_WS_CLASSES . 'boxes.php');

// auto activate and expire banners
  require(DIR_WS_FUNCTIONS . 'banner.php');
  tep_activate_banners();
  tep_expire_banners();

// auto expire special products
  require(DIR_WS_FUNCTIONS . 'specials.php');
  tep_expire_specials();

// calculate category path
  if (isset($_GET['cPath'])) {
    $cPath = $_GET['cPath'];
  } elseif (isset($_GET['products_id']) && !isset($_GET['manufacturers_id'])) {
    $cPath = tep_get_product_path($_GET['products_id']);
  } else {
    $cPath = '';
  }

  if (tep_not_null($cPath)) {
    $cPath_array = tep_parse_category_path($cPath);
    $cPath = implode('_', $cPath_array);

    $current_category_id = end($cPath_array);
  } else {
    $current_category_id = 0;
  }

// include the breadcrumb class and start the breadcrumb trail
  require(DIR_WS_CLASSES . 'breadcrumb.php');
  $breadcrumb = new breadcrumb;

  $breadcrumb->add(HEADER_TITLE_TOP, HTTP_SERVER);
  $breadcrumb->add(HEADER_TITLE_CATALOG, tep_href_link(FILENAME_DEFAULT));

// add category names or the manufacturer name to the breadcrumb trail
  if (isset($cPath_array)) {
    for ($i=0, $n=sizeof($cPath_array); $i<$n; $i++) {
      $categories_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$cPath_array[$i] . "' and language_id = '" . (int)$osC_Session->value('languages_id') . "'");
      if (tep_db_num_rows($categories_query) > 0) {
        $categories = tep_db_fetch_array($categories_query);
        $breadcrumb->add($categories['categories_name'], tep_href_link(FILENAME_DEFAULT, 'cPath=' . implode('_', array_slice($cPath_array, 0, ($i+1)))));
      } else {
        break;
      }
    }
  } elseif (isset($_GET['manufacturers_id'])) {
    $manufacturers_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'");
    if (tep_db_num_rows($manufacturers_query)) {
      $manufacturers = tep_db_fetch_array($manufacturers_query);
      $breadcrumb->add($manufacturers['manufacturers_name'], tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $_GET['manufacturers_id']));
    }
  }

// add the products model to the breadcrumb trail
  if (isset($_GET['products_id'])) {
    $model_query = tep_db_query("select products_model from " . TABLE_PRODUCTS . " where products_id = '" . (int)$_GET['products_id'] . "'");
    if (tep_db_num_rows($model_query)) {
      $model = tep_db_fetch_array($model_query);
      $breadcrumb->add($model['products_model'], tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $cPath . '&products_id=' . $_GET['products_id']));
    }
  }

// initialize the message stack for output messages
  require(DIR_WS_CLASSES . 'message_stack.php');
  $messageStack = new messageStack;

// set which precautions should be checked
  define('WARN_INSTALL_EXISTENCE', 'true');
  define('WARN_CONFIG_WRITEABLE', 'true');
  define('WARN_SESSION_DIRECTORY_NOT_WRITEABLE', 'true');
  define('WARN_SESSION_AUTO_START', 'true');
  define('WARN_DOWNLOAD_DIRECTORY_NOT_READABLE', 'true');
?>
