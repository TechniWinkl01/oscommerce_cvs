<?php
/*
  $Id: application_top.php,v 1.146 2001/06/11 22:40:31 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

// Set your local configuration parameters.
  if (file_exists('includes/local/configure.php')) {
    include('includes/local/configure.php');
  }

// Define the project version
// * for internal use until a complete v1.0 version of this project is ready
  define('PROJECT_VERSION', 'Preview Release 2.2-CVS');

// Define the webserver and path parameters
// * DIR_FS_* = Filesystem directories (local/physical)
// * DIR_WS_* = Webserver directories (virtual/URL)
  define('HTTP_SERVER', ''); // eg, http://localhost - should not be NULL for productive servers
  define('HTTPS_SERVER', ''); // eg, https://localhost - should not be NULL for productive servers
  define('ENABLE_SSL', false); // secure webserver for checkout procedure?
  define('DIR_FS_LOGS', '/usr/local/apache/logs/tep/'); // logging directory
  define('DIR_WS_CATALOG', '/catalog/'); // absolute path required
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_INCLUDES', 'includes/'); // If "URL fopen wrappers" are enabled in PHP (which they are in the default configuration), this can be a URL instead of a local pathname
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_PAYMENT_MODULES', DIR_WS_MODULES . 'payment/');
  define('DIR_WS_SHIPPING_MODULES', DIR_WS_MODULES . 'shipping/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');

// default localization values
  define('DEFAULT_LANGUAGE', 'en'); // codes are in the "languages" database table
  define('DEFAULT_CURRENCY', 'USD'); // codes are in the "currencies" database table (and catalog/includes/data/rates.php)
  define('USE_DEFAULT_LANGUAGE_CURRENCY', false); // when the language is changed, use its default currency instead of the applications default currency

// Send order confirmation emails ALSO to these email addresses (separated by a comma)
//  define('SEND_EXTRA_ORDER_EMAILS_TO', 'root <root@localhost>, root <root@localhost>');

  define('EXIT_AFTER_REDIRECT', true); // if enabled, the parse time will not store its time after the header(location) redirect - used with tep_exit();

  define('STORE_PAGE_PARSE_TIME', false); // store the time it takes to parse a page (in the logfile)
  define('STORE_PAGE_PARSE_TIME_LOG', DIR_FS_LOGS . 'parse_time_log'); // filename of the log
  define('STORE_PARSE_DATE_TIME_FORMAT', '%d/%m/%Y %H:%M:%S'); // format of the time entries
  define('DISPLAY_PAGE_PARSE_TIME', true); // display how long it takes to parse a page (STORE_PAGE_PARSE_TIME must be enabled)

  if (STORE_PAGE_PARSE_TIME == true) {
    define('PAGE_PARSE_START_TIME', microtime());
  }
  define('STORE_DB_TRANSACTIONS', false); // log database queries

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
  define('FILENAME_EMAILPRODUCT', 'emailproduct.php');
  define('FILENAME_INFO_SHOPPING_CART', 'info_shopping_cart.php');
  define('FILENAME_LOGIN', 'login.php');
  define('FILENAME_LOGIN_CREATE', 'login_create.php');
  define('FILENAME_LOGOFF', 'logoff.php');
  define('FILENAME_NEW_PRODUCTS', 'new_products.php'); // This is the middle of default.php (found in modules)
  define('FILENAME_PASSWORD_CRYPT', 'password_funcs.php');
  define('FILENAME_PASSWORD_FORGOTTEN', 'password_forgotten.php');
  define('FILENAME_POPUP_IMAGE', 'popup_image.php');
  define('FILENAME_PRIVACY', 'privacy.php');
  define('FILENAME_PRODUCT_INFO', 'product_info.php');
  define('FILENAME_PRODUCT_REVIEWS', 'product_reviews.php');
  define('FILENAME_PRODUCT_REVIEWS_INFO', 'product_reviews_info.php');
  define('FILENAME_PRODUCT_REVIEWS_WRITE', 'product_reviews_write.php');
  define('FILENAME_REDIRECT', 'redirect.php');
  define('FILENAME_REVIEWS', 'reviews.php');
  define('FILENAME_SHOPPING_CART', 'shopping_cart.php');
  define('FILENAME_SPECIALS', 'specials.php');
  define('FILENAME_SHIPPING', 'shipping.php');
  define('FILENAME_UPCOMING_PRODUCTS', 'upcoming_products.php'); // This is the bottom of default.php (found in modules)
  define('FILENAME_PRODUCT_LISTING', 'product_listing.php');

// define the database table names used in the project
  define('TABLE_ADDRESS_BOOK', 'address_book');
  define('TABLE_ADDRESS_BOOK_TO_CUSTOMERS', 'address_book_to_customers');
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
  define('TABLE_PRODUCTS', 'products');
  define('TABLE_PRODUCTS_ATTRIBUTES', 'products_attributes');
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
  define('TABLE_WHOS_ONLINE', 'whos_online');
  define('TABLE_ZONES', 'zones');

// define our database connection
  define('DB_SERVER', ''); // eg, localhost - should not be NULL for productive servers
  define('DB_SERVER_USERNAME', 'mysql');
  define('DB_SERVER_PASSWORD', '');
  define('DB_DATABASE', 'catalog');
  define('USE_PCONNECT', true); // use persisstent connections?
  define('STORE_SESSIONS', ''); // leave empty '' for default handler or set to 'mysql'

// customization for the design layout
  define('CART_DISPLAY', true); // Enable to view the shopping cart after adding a product
  define('TAX_VALUE', 0); // propducts tax
  define('TAX_DECIMAL_PLACES', 0); // 16% - If this were 2 it would be 16.00%
  define('TAX_INCLUDE', false); // Show prices with tax (true) or without tax (false)
  define('BOX_WIDTH', 125); // how wide the boxes should be in pixels (default: 125)
  define('EMAILPRODUCT_GUEST', false); // Can guests use the tell a friend email form?

  define('SPECIALS_PRICE_COLOR', '#FF0000'); // font color for the new price of products on special

  define('CHECKOUT_BAR_TEXT_COLOR', '#AABBDD');
  define('CHECKOUT_BAR_TEXT_COLOR_HIGHLIGHTED', '#000000');

// Stock Check & Update
  define('STOCK_CHECK', 1);
  define('STOCK_LIMITED', 1); // Subtract product in stock by product orders
  define('STOCK_ALLOW_CHECKOUT', 1); 

// set to "1" if extended email check function should be used
// If you're testing locally and your webserver has no possibility to query
// a dns server you should set this to "0" !
  define('ENTRY_EMAIL_ADDRESS_CHECK', 0);

// Control what fields of the customer table are used
  define('ACCOUNT_GENDER', 1);
  define('ACCOUNT_DOB', 1);
  define('ACCOUNT_SUBURB', 1);
  define('ACCOUNT_STATE', 1);

// Advanced Search controls
  define('ADVANCED_SEARCH_DEFAULT_OPERATOR', 'and'); // default boolean search operator: or/and
  define('ADVANCED_SEARCH_DISPLAY_TIPS', 1); // Display Advanced Search Tips at the bottom of the page: 0=disable; 1=enable

// Bestsellers Min/Max Controls
  define('MIN_DISPLAY_BESTSELLERS', 1);    // Min no. of bestsellers to display
  define('MAX_DISPLAY_BESTSELLERS', 10);   // Max no. of bestsellers to display

// Min/Max Controls for also_purchased_products.php : 'Customers who bought this product also purchased' module
  define('MIN_DISPLAY_ALSO_PURCHASED', 1);   // Min no. of products in purchased list to qualify
  define('MAX_DISPLAY_ALSO_PURCHASED', 5);   // Max no. of products to display

// Prev/Next Navigation Bar location
  define('PREV_NEXT_BAR_LOCATION', 2) ;    // 1 - top, 2 - bottom, 3 - both

// Manufacturers box
  define('DISPLAY_EMPTY_MANUFACTURERS', 1); // Display Manufacturers with no products: 0=disable; 1=enable

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

// set up cache functionality - only for PHP4
  define('CACHE_ON', false); // Default: false - Turn caching on/off
  define('CACHE_DIR', '/tmp'); // Default: /tmp - Default cache directory
  define('CACHE_DEBUG', false); // Default: false - Turn debugging on/off
  define('CACHE_GC', .10); // Default: .10 - Probability of garbage collection
  define('CACHE_USE_STORAGE_HASH', 0);	/* Default: 1 - Use storage hashing.  This will increase peformance if you are caching many pages. */ 
  define('CACHE_STORAGE_CREATED', 0);	/* Default: 0 - This is a peformance tweak.  If you set this to 1, phpCache will not check if storage structures have been created.  Don't change this unles you are *SURE* the cache storage has been created. */
  define('CACHE_MAX_STORAGE_HASH', 23);	/* Don't touch this unless you know what you're doing */
  define('CACHE_STORAGE_PERM', 0700);	/* Default: 0700 - Default permissions for storage directories. */
  define('CACHE_MAX_FILENAME_LEN', 200);	/* How long the cache storage filename can be before it will md5() the entire thing */

  if (CACHE_ON) {
    include(DIR_WS_CLASSES . 'cache.php');
    $cache = new phpCache;
  }

// include the database functions
  require(DIR_WS_FUNCTIONS . 'database.php');

// make a connection to the database... now
  tep_db_connect() or die('Unable to connect to database server!');

// set the application parameters (can be modified through the administration tool)
  $configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION . '');
  while ($configuration = tep_db_fetch_array($configuration_query)) {
    define($configuration['cfgKey'], $configuration['cfgValue']);
  }

// include shopping cart class
  require(DIR_WS_CLASSES . 'shopping_cart.php');

// some code to solve compatibility issues
  require(DIR_WS_FUNCTIONS . 'compatibility.php');

// lets start our session
  if ($HTTP_POST_VARS[tep_session_name()]) {
    tep_session_id($HTTP_POST_VARS[tep_session_name()]);
  }
  if ( (getenv('HTTPS')) && ($HTTP_GET_VARS[tep_session_name()]) ) {
    tep_session_id($HTTP_GET_VARS[tep_session_name()]);
  }
  if (function_exists('session_set_cookie_params')) {
    session_set_cookie_params(0, DIR_WS_CATALOG);
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

// include the currency rates
  require(DIR_WS_INCLUDES . 'data/rates.php');

// define our localization functions
  require(DIR_WS_FUNCTIONS . 'localization.php');

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
  require(DIR_WS_FUNCTIONS . 'banner.php');

// currency
  if ( (!$currency) || ($HTTP_GET_VARS['currency']) || ( (USE_DEFAULT_LANGUAGE_CURRENCY) && (LANGUAGE_CURRENCY != $currency) ) ) {
    if (!$currency) tep_session_register('currency');

    if ($HTTP_GET_VARS['currency']) {
      $currency = tep_currency_exists($HTTP_GET_VARS['currency']);
      if (!$currency) $currency = (USE_DEFAULT_LANGUAGE_CURRENCY) ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;
    } else {
      $currency = (USE_DEFAULT_LANGUAGE_CURRENCY) ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;
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

// Shopping cart actions
  if ($HTTP_GET_VARS['action']) {
    $goto = (CART_DISPLAY == true) ? FILENAME_SHOPPING_CART : basename($PHP_SELF);
    $parameters = (CART_DISPLAY == true) ? array('action', 'cPath', 'products_id') : array('action');
    if ($HTTP_GET_VARS['action'] == 'add_update_product') {
      // customer wants to update the product quantity in their shopping cart
      if ((is_array($HTTP_POST_VARS['cart_quantity'])) && (is_array($HTTP_POST_VARS['products_id']))) {
        for ($i=0; $i<sizeof($HTTP_POST_VARS['products_id']);$i++) {
          if ( tep_in_array($HTTP_POST_VARS['products_id'][$i], ( is_array($HTTP_POST_VARS['cart_delete']) ? $HTTP_POST_VARS['cart_delete'] : array() ) ) ) {
            $cart->remove($HTTP_POST_VARS['products_id'][$i]);
          } else {
            $attributes = ($HTTP_POST_VARS['id'][$HTTP_POST_VARS['products_id'][$i]]) ? $HTTP_POST_VARS['id'][$HTTP_POST_VARS['products_id'][$i]] : '';
            $cart->add_cart($HTTP_POST_VARS['products_id'][$i], $HTTP_POST_VARS['cart_quantity'][$i], $attributes);
          }
        }
      } else {
        if (ereg('^[0-9]+$', $HTTP_POST_VARS['products_id'])) {
          $cart->add_cart($HTTP_POST_VARS['products_id'], $HTTP_POST_VARS['cart_quantity'], $HTTP_POST_VARS['id']);
        }
      }
      header('Location: ' . tep_href_link($goto, tep_get_all_get_params($parameters), 'NONSSL'));
      tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'add_a_quickie') {
      // customer wants to add a quickie to the cart (called from a box)
      $quickie_query = tep_db_query("select products_id from " . TABLE_PRODUCTS . " where products_model = '" . $HTTP_POST_VARS['quickie'] . "'");
      if (!tep_db_num_rows($quickie_query)) {
        $quickie_query = tep_db_query("select products_id from " . TABLE_PRODUCTS . " where products_model LIKE '%" . $HTTP_POST_VARS['quickie'] . "%'");
      }
      if (tep_db_num_rows($quickie_query) != 1) {
        header( 'Location: ' . tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, 'keywords=' . $HTTP_POST_VARS['quickie'], 'NONSSL'));
        tep_exit();
      }
      $quickie = tep_db_fetch_array($quickie_query);
      if (tep_has_product_attributes($quickie['products_id'])) {
        header('Location: ' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $quickie['products_id'], 'NONSSL'));
        tep_exit();
      } else {
        $cart->add_cart($quickie['products_id'], 1);
        header('Location: ' . tep_href_link($goto, tep_get_all_get_params(array('action')), 'NONSSL'));
        tep_exit();
      }
    }
  }

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
// mail class
  require(DIR_WS_CLASSES . 'mail.php');

// smtp class
  require(DIR_WS_CLASSES . 'smtp.php');
?>
