<?php
/*
  $Id: application_top.php,v 1.212 2002/02/02 16:32:07 clescuyer Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/

// Start the clock for the page parse time log
  define('PAGE_PARSE_START_TIME', microtime());

// Set the level of error reporting
  error_reporting(E_ALL & ~E_NOTICE);

// Disable use_trans_sid as tep_href_link() does this manually
  if (function_exists('ini_set')) {
    ini_set('session.use_trans_sid', 0);
  }

// Set the local configuration parameters - mainly for developers
  if (file_exists('includes/local/configure.php')) include('includes/local/configure.php');

// include server parameters
  require('includes/configure.php');

// Define the project version
  define('PROJECT_VERSION', 'Preview Release 2.2-CVS');

// define the filenames used in the project
  define('FILENAME_ACCOUNT', 'account.php');
  define('FILENAME_ACCOUNT_EDIT', 'account_edit.php');
  define('FILENAME_ACCOUNT_EDIT_PROCESS', 'account_edit_process.php');
  define('FILENAME_ACCOUNT_HISTORY', 'account_history.php');
  define('FILENAME_ACCOUNT_HISTORY_INFO', 'account_history_info.php');
  define('FILENAME_ADDRESS_BOOK', 'address_book.php');
  define('FILENAME_ADDRESS_BOOK_PROCESS', 'address_book_process.php');
  define('FILENAME_ADVANCED_SEARCH', 'advanced_search.php');
  define('FILENAME_ADVANCED_SEARCH_RESULT', 'advanced_search_result.php');
  define('FILENAME_ALSO_PURCHASED_PRODUCTS', 'also_purchased_products.php'); // This is the bottom of product_info.php (found in modules)
  define('FILENAME_CCVAL_FUNCTION', 'ccval.php');
  define('FILENAME_CHECKOUT_ADDRESS', 'checkout_address.php');
  define('FILENAME_CHECKOUT_CONFIRMATION', 'checkout_confirmation.php');
  define('FILENAME_CHECKOUT_PAYMENT', 'checkout_payment.php');
  define('FILENAME_CHECKOUT_PROCESS', 'checkout_process.php');
  define('FILENAME_CHECKOUT_SUCCESS', 'checkout_success.php');
  define('FILENAME_CONTACT_US', 'contact_us.php');
  define('FILENAME_CONDITIONS', 'conditions.php');
  define('FILENAME_CREATE_ACCOUNT', 'create_account.php');
  define('FILENAME_CREATE_ACCOUNT_PROCESS', 'create_account_process.php');
  define('FILENAME_CREATE_ACCOUNT_SUCCESS', 'create_account_success.php');
  define('FILENAME_DEFAULT', 'default.php');
  define('FILENAME_DOWNLOAD', 'download.php');
  define('FILENAME_INFO_SHOPPING_CART', 'info_shopping_cart.php');
  define('FILENAME_LOGIN', 'login.php');
  define('FILENAME_LOGOFF', 'logoff.php');
  define('FILENAME_NEW_PRODUCTS', 'new_products.php'); // This is the middle of default.php (found in modules)
  define('FILENAME_PASSWORD_CRYPT', 'password_funcs.php');
  define('FILENAME_PASSWORD_FORGOTTEN', 'password_forgotten.php');
  define('FILENAME_POPUP_IMAGE', 'popup_image.php');
  define('FILENAME_PRIVACY', 'privacy.php');
  define('FILENAME_PRODUCT_INFO', 'product_info.php');
  define('FILENAME_PRODUCT_LISTING', 'product_listing.php');
  define('FILENAME_PRODUCT_REVIEWS', 'product_reviews.php');
  define('FILENAME_PRODUCT_REVIEWS_INFO', 'product_reviews_info.php');
  define('FILENAME_PRODUCT_REVIEWS_WRITE', 'product_reviews_write.php');
  define('FILENAME_PRODUCTS_NEW', 'products_new.php');
  define('FILENAME_REDIRECT', 'redirect.php');
  define('FILENAME_REVIEWS', 'reviews.php');
  define('FILENAME_SHIPPING', 'shipping.php');
  define('FILENAME_SHOPPING_CART', 'shopping_cart.php');
  define('FILENAME_SPECIALS', 'specials.php');
  define('FILENAME_TELL_A_FRIEND', 'tell_a_friend.php');
  define('FILENAME_UPCOMING_PRODUCTS', 'upcoming_products.php'); // This is the bottom of default.php (found in modules)

// define the database table names used in the project
  define('TABLE_ADDRESS_BOOK', 'address_book');
  define('TABLE_ADDRESS_FORMAT', 'address_format');
  define('TABLE_BANNERS', 'banners');
  define('TABLE_BANNERS_HISTORY', 'banners_history');
  define('TABLE_CATEGORIES', 'categories');
  define('TABLE_CATEGORIES_DESCRIPTION', 'categories_description');
  define('TABLE_CONFIGURATION', 'configuration');
  define('TABLE_CONFIGURATION_GROUP', 'configuration_group');
  define('TABLE_COUNTER', 'counter');
  define('TABLE_COUNTER_HISTORY', 'counter_history');
  define('TABLE_COUNTRIES', 'countries');
  define('TABLE_CURRENCIES', 'currencies');
  define('TABLE_CUSTOMERS', 'customers');
  define('TABLE_CUSTOMERS_BASKET', 'customers_basket');
  define('TABLE_CUSTOMERS_BASKET_ATTRIBUTES', 'customers_basket_attributes');
  define('TABLE_CUSTOMERS_INFO', 'customers_info');
  define('TABLE_LANGUAGES', 'languages');
  define('TABLE_MANUFACTURERS', 'manufacturers');
  define('TABLE_MANUFACTURERS_INFO', 'manufacturers_info');
  define('TABLE_ORDERS', 'orders');
  define('TABLE_ORDERS_PRODUCTS', 'orders_products');
  define('TABLE_ORDERS_PRODUCTS_ATTRIBUTES', 'orders_products_attributes');
  define('TABLE_ORDERS_PRODUCTS_DOWNLOAD', 'orders_products_download');
  define('TABLE_ORDERS_STATUS', 'orders_status');
  define('TABLE_ORDERS_STATUS_HISTORY', 'orders_status_history');
  define('TABLE_PRODUCTS', 'products');
  define('TABLE_PRODUCTS_ATTRIBUTES', 'products_attributes');
  define('TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD', 'products_attributes_download');
  define('TABLE_PRODUCTS_DESCRIPTION', 'products_description');
  define('TABLE_PRODUCTS_OPTIONS', 'products_options');
  define('TABLE_PRODUCTS_OPTIONS_VALUES', 'products_options_values');
  define('TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS', 'products_options_values_to_products_options');
  define('TABLE_PRODUCTS_TO_CATEGORIES', 'products_to_categories');
  define('TABLE_REVIEWS', 'reviews');
  define('TABLE_REVIEWS_DESCRIPTION', 'reviews_description');
  define('TABLE_SESSIONS', 'sessions');
  define('TABLE_SPECIALS', 'specials');
  define('TABLE_TAX_CLASS', 'tax_class');
  define('TABLE_TAX_RATES', 'tax_rates');
  define('TABLE_GEO_ZONES', 'geo_zones');
  define('TABLE_ZONES_TO_GEO_ZONES', 'zones_to_geo_zones');
  define('TABLE_WHOS_ONLINE', 'whos_online');
  define('TABLE_ZONES', 'zones');

// customization for the design layout
  define('TAX_VALUE', 0); // propducts tax
  define('TAX_DECIMAL_PLACES', 0); // 16% - If this were 2 it would be 16.00%
  define('TAX_INCLUDE', false); // Show prices with tax (true) or without tax (false)
  define('BOX_WIDTH', 125); // how wide the boxes should be in pixels (default: 125)

// Control what fields of the customer table are used
  define('ACCOUNT_GENDER', 1);
  define('ACCOUNT_DOB', 1);
  define('ACCOUNT_COMPANY', 0);
  define('ACCOUNT_SUBURB', 1);
  define('ACCOUNT_STATE', 1);

// Downloadable products
  define('DOWNLOAD_ENABLED', 1);      // 0=disable, 1=enable
  define('DOWNLOAD_BY_REDIRECT', 0);  // 0=disable, 1=enable
  define('DOWNLOAD_MAX_DAYS', 14);    // Download available for n days, 0=no limit
  define('DOWNLOAD_MAX_COUNT', 5);    // Download possible n times, 0=no download possible
  

// Categories Box: recursive products count
  define('SHOW_COUNTS', 1); // show category count: 0=disable; 1=enable
  define('USE_RECURSIVE_COUNT', 1); // recursive count: 0=disable; 1=enable

// check to see if php implemented session management functions - if not, include php3/php4 compatible session class
  if (!function_exists('session_start')) {
    define('PHP_SESSION_NAME', 'sID');
    define('PHP_SESSION_SAVE_PATH', '/tmp');

    include(DIR_WS_CLASSES . 'sessions.php');
  }

// define how the session functions will be used
  require(DIR_WS_FUNCTIONS . 'sessions.php');

// include the database functions
  require(DIR_WS_FUNCTIONS . 'database.php');

// make a connection to the database... now
  tep_db_connect() or die('Unable to connect to database server!');

// set the application parameters (can be modified through the administration tool)
  $configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION . '');
  while ($configuration = tep_db_fetch_array($configuration_query)) {
    define($configuration['cfgKey'], $configuration['cfgValue']);
  }

// Get variables from $PATH_INFO
  if (SEARCH_ENGINE_FRIENDLY_URLS == 'true') {
    if (strlen($PATH_INFO) > 1) {
      $PHP_SELF = str_replace($PATH_INFO,'',$PHP_SELF);
      $vars = explode('/', substr($PATH_INFO, 1));
      while (list(, $var) = each($vars)) { 
        list(, $val) = each($vars); 
        $HTTP_GET_VARS[$var] = $val; 
        $GLOBALS[$var] = $val; 
      }
    }
  }

// include cache functions if enabled
  if (USE_CACHE == 'true') include(DIR_WS_FUNCTIONS . 'cache.php');

// include shopping cart class
  require(DIR_WS_CLASSES . 'shopping_cart.php');

// some code to solve compatibility issues
  require(DIR_WS_FUNCTIONS . 'compatibility.php');

// lets start our session
  if (function_exists('session_set_cookie_params')) {
    session_set_cookie_params(0, substr(DIR_WS_CATALOG, 0, -1));
  }

  tep_session_start();

// Create the cart & Fix the cart if necesary
  if ($cart) {
    if (!eregi('^4\.', phpversion()) || eregi('^4.0b2', phpversion())) {
      $broken_cart = $cart;
      $cart = new shoppingCart;
      $cart->unserialize($broken_cart);
    }
  } else {
    tep_session_register('cart');
    $cart = new shoppingCart;
  }

// include currencies class and create an instance
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

// define our localization functions
  require(DIR_WS_FUNCTIONS . 'localization.php');

// include the mail classes
  require(DIR_WS_CLASSES . 'mime.php');
  require(DIR_WS_CLASSES . 'email.php');
  
// language
  if ( (!$language) || ($HTTP_GET_VARS['language']) ) {
    if (!$language) {
      tep_session_register('language');
      tep_session_register('languages_id');
    }

    $language_code = ($HTTP_GET_VARS['language']) ? $HTTP_GET_VARS['language'] : DEFAULT_LANGUAGE;
    $languages = tep_get_languages($language_code);
    $language = $languages[0]['directory'];
    $languages_id = $languages[0]['id'];
  }

// include the language translations
  require(DIR_WS_LANGUAGES . $language . '.php');

// define our general functions used application-wide
  require(DIR_WS_FUNCTIONS . 'general.php');
  require(DIR_WS_FUNCTIONS . 'html_output.php');

// currency
  if ( (!$currency) || ($HTTP_GET_VARS['currency']) || ( (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') && (LANGUAGE_CURRENCY != $currency) ) ) {
    if (!$currency) tep_session_register('currency');

    if ($HTTP_GET_VARS['currency']) {
      $currency = tep_currency_exists($HTTP_GET_VARS['currency']);
      if (!$currency) $currency = (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;
    } else {
      $currency = (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;
    }
  }

// Shopping cart actions
  if ($HTTP_GET_VARS['action']) {
    if (DISPLAY_CART == 'true') {
      $goto =  FILENAME_SHOPPING_CART;
      $parameters = array('action', 'cPath', 'products_id');
    } else {
      $goto = basename($PHP_SELF);
      $parameters = array('action');
    }
    switch ($HTTP_GET_VARS['action']) {
      // customer wants to update the product quantity in their shopping cart
      case 'update_product' : for ($i=0; $i<sizeof($HTTP_POST_VARS['products_id']);$i++) {
                                if (tep_in_array($HTTP_POST_VARS['products_id'][$i], (is_array($HTTP_POST_VARS['cart_delete']) ? $HTTP_POST_VARS['cart_delete'] : array()))) {
                                  $cart->remove($HTTP_POST_VARS['products_id'][$i]);
                                } else { // if PHP3, make correction for lack of multidimensional array in PHP3
                                  if (ereg('^3\.', phpversion())) {
                                    reset($HTTP_POST_VARS);
                                    while (list($key, $value) = each($HTTP_POST_VARS)) {
                                      if (is_array($value)) {
                                        while (list($key2, $value2) = each($value)) {
                                          if (ereg ("(.*)\]\[(.*)", $key2, $var)) {
                                            $id2[$var[1]][$var[2]] = $value2;
                                          }
                                        }
                                      }
                                    }
                                    $attributes = ($id2[$HTTP_POST_VARS['products_id'][$i]]) ? $id2[$HTTP_POST_VARS['products_id'][$i]] : '';
                                  } else {
                                    $attributes = ($HTTP_POST_VARS['id'][$HTTP_POST_VARS['products_id'][$i]]) ? $HTTP_POST_VARS['id'][$HTTP_POST_VARS['products_id'][$i]] : '';
                                  }
                                  $cart->add_cart($HTTP_POST_VARS['products_id'][$i], $HTTP_POST_VARS['cart_quantity'][$i], $attributes);
                                }
                              }
                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters), 'NONSSL'));
                              break;
      // customer adds a product from the products page
      case 'add_product' :    if (ereg('^[0-9]+$', $HTTP_POST_VARS['products_id'])) {
                                $cart->add_cart($HTTP_POST_VARS['products_id'], $HTTP_POST_VARS['cart_quantity'], $HTTP_POST_VARS['id']);
                              }
                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters), 'NONSSL'));
                              break;
      // performed by the 'buy now' button in product listings and review page
      case 'buy_now' :        if (tep_has_product_attributes($HTTP_GET_VARS['products_id'])) {
                                tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_GET_VARS['products_id'], 'NONSSL'));
                              } else {
                                $cart->add_cart($HTTP_GET_VARS['products_id'], 1);
                                tep_redirect(tep_href_link($goto, tep_get_all_get_params(array('action')), 'NONSSL'));
                              }
                              break;
    }
  }

// include the who's online functions
  require(DIR_WS_FUNCTIONS . 'whos_online.php');
  tep_update_whos_online();

// Include the password crypto functions
  require(DIR_WS_FUNCTIONS . FILENAME_PASSWORD_CRYPT);

// Include validation functions (right now only email address)
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
  $cPath = $HTTP_GET_VARS['cPath'];
  if (strlen($cPath) > 0) {
    $cPath_array = explode('_', $cPath);
    if (sizeof($cPath_array) > 1) {
      $current_category_id = $cPath_array[(sizeof($cPath_array)-1)];
    } else {
      $current_category_id = $cPath_array[0];
    }
  } else {
    $current_category_id = 0;
  }

// set which precautions should be checked
  define('WARN_INSTALL_EXISTENCE', 'true');
  define('WARN_CONFIG_WRITEABLE', 'true');
  define('WARN_SESSION_DIRECTORY_NOT_WRITEABLE', 'true');

// check if the 'install' directory exists, and warn of its existence
  if (WARN_INSTALL_EXISTENCE == 'true') {
    if (is_dir(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/install')) {
      tep_output_warning(WARNING_INSTALL_DIRECTORY_EXISTS);
    }
  }

// check if the configure.php file is writeable
  if (WARN_CONFIG_WRITEABLE == 'true') {
    if ( (file_exists(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php')) && (is_writeable(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php')) ) {
      tep_output_warning(WARNING_CONFIG_FILE_WRITEABLE);
    }
  }

// check if the configure.php file is writeable
  if (WARN_SESSION_DIRECTORY_NOT_WRITEABLE == 'true') {
    if (STORE_SESSIONS == '') {
      if (!is_dir(tep_session_save_path())) {
        tep_output_warning(WARNING_SESSION_DIRECTORY_NON_EXISTENT);
      } elseif (!is_writeable(tep_session_save_path())) {
        tep_output_warning(WARNING_SESSION_DIRECTORY_NOT_WRITEABLE);
      }
    }
  }
?>
