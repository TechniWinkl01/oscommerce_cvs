<?
  if (file_exists('includes/local/configure.php')) {
    include('includes/local/configure.php');
    if ((!defined(CONFIGURE_STATUS_COMPLETED)) && (CONFIGURE_STATUS_COMPLETED != '1')) { // File not read properly
       die('File configure.php was not found or was improperly formatted, contact webmaster of this domain.<br>The configuration file in catalog/includes/local/configure.php was not properly formatted.<br>&nbsp;<br>Please add the following to that file:<br>&nbsp;<br>define(\'CONFIGURE_STATUS_COMPLETED\', \'1\');');
    }
  }

// for internal use until final v1.0 version is ready
  define('PROJECT_VERSION', 'Preview Release 2.1');

// define our webserver variables
  define('HTTP_SERVER', 'http://exchange');
  define('HTTPS_SERVER', 'https://exchange');
  define('ENABLE_SSL', 1); // ssl server enable(1)/disable(0)
  define('DIR_SERVER_ROOT', '/usr/local/apache/');
  define('DIR_LOGS', DIR_SERVER_ROOT . 'logs/');
  define('DIR_CATALOG', '/catalog/');
  define('DIR_IMAGES', '/catalog/images/'); // from webserver
  define('DIR_IMAGES_PHYSICAL', DIR_SERVER_ROOT); // 'images/' is hardcoded in the database .. all these paths will later fixed for a better structure..!!
  define('DIR_INCLUDES', 'includes/');
  define('DIR_BOXES', DIR_INCLUDES . 'boxes/');
  define('DIR_FUNCTIONS', DIR_INCLUDES . 'functions/');
  define('DIR_CLASSES', DIR_INCLUDES . 'classes/');
  define('DIR_MODULES', DIR_INCLUDES . 'modules/');
  define('DIR_PAYMENT_MODULES', DIR_MODULES . 'payment/');
  define('DIR_LANGUAGES', DIR_INCLUDES . 'languages/');

// who to send order confirmation emails to.. there is always one being sent to the customer, so there
// is no need to add their address to the following constant..
// use comma's to separate email addresses (as in the example)
//  define('SEND_EXTRA_ORDER_EMAILS_TO', 'root <root@localhost>, root <root@localhost>');

  define('EXIT_AFTER_REDIRECT', 1); // if enabled, the parse time will not store its time after the header(location) redirect - used with tep_exit();
  define('STORE_PAGE_PARSE_TIME', 1); // store the time it takes to parse a page
  define('STORE_PAGE_PARSE_TIME_LOG', DIR_LOGS . 'exchange/parse_time_log');

  define('STORE_PARSE_DATE_TIME_FORMAT', '%d/%m/%Y %H:%M:%S');
  if (STORE_PAGE_PARSE_TIME == '1') {
    $parse_start_time = microtime();
  }
  define('STORE_DB_TRANSACTIONS', 0);

// enable this under PHP3
  define('REPAIR_BROKEN_CART', 0);

// define the filenames used in the project
  define('FILENAME_NEW_PRODUCTS', 'new_products.php'); // This is the middle of default.php (found in modules)
  define('FILENAME_UPCOMING_PRODUCTS', 'upcoming_products.php'); // This is the bottom of default.php (found in modules)
  define('FILENAME_ALSO_PURCHASED_PRODUCTS', 'also_purchased_products.php'); // This is the bottom of product_info.php (found in modules)
  define('FILENAME_ACCOUNT', 'account.php');
  define('FILENAME_ACCOUNT_EDIT', 'account_edit.php');
  define('FILENAME_ACCOUNT_EDIT_PROCESS', 'account_edit_process.php');
  define('FILENAME_ACCOUNT_HISTORY', 'account_history.php');
  define('FILENAME_ACCOUNT_HISTORY_INFO', 'account_history_info.php');
  define('FILENAME_ADDRESS_BOOK', 'address_book.php');
  define('FILENAME_ADDRESS_BOOK_PROCESS', 'address_book_process.php');
  define('FILENAME_ADVANCED_SEARCH', 'advanced_search.php');
  define('FILENAME_ADVANCED_SEARCH_RESULT', 'advanced_search_result.php');
  define('FILENAME_CHECKOUT', 'checkout.php');
  define('FILENAME_CHECKOUT_ADDRESS', 'checkout_address.php');
  define('FILENAME_CHECKOUT_CONFIRMATION', 'checkout_confirmation.php');
  define('FILENAME_CHECKOUT_PAYMENT', 'checkout_payment.php');
  define('FILENAME_CHECKOUT_PROCESS', 'checkout_process.php');
  define('FILENAME_CHECKOUT_SUCCESS', 'checkout_success.php');
  define('FILENAME_CONTACT_US', 'contact_us.php');
  define('FILENAME_CREATE_ACCOUNT', 'create_account.php');
  define('FILENAME_CREATE_ACCOUNT_PROCESS', 'create_account_process.php');
  define('FILENAME_CREATE_ACCOUNT_SUCCESS', 'create_account_success.php');
  define('FILENAME_DEFAULT', 'default.php');
  define('FILENAME_INFO_SHOPPING_CART', 'info_shopping_cart.php');
  define('FILENAME_LOGIN', 'login.php');
  define('FILENAME_LOGOFF', 'logoff.php');
  define('FILENAME_PASSWORD_FORGOTTEN', 'password_forgotten.php');
  define('FILENAME_PRODUCT_INFO', 'product_info.php');
  define('FILENAME_PRODUCT_REVIEWS', 'product_reviews.php');
  define('FILENAME_PRODUCT_REVIEWS_INFO', 'product_reviews_info.php');
  define('FILENAME_PRODUCT_REVIEWS_WRITE', 'product_reviews_write.php');
  define('FILENAME_REVIEWS', 'reviews.php');
  define('FILENAME_SHOPPING_CART', 'shopping_cart.php');
  define('FILENAME_SPECIALS', 'specials.php');
  define('FILENAME_PASSWORD_CRYPT', 'password_funcs.php');

// define our database connection
  define('DB_SERVER', 'exchange');
  define('DB_SERVER_USERNAME', 'mysql');
  define('DB_SERVER_PASSWORD', '');
  define('DB_DATABASE', 'catalog');
  define('USE_PCONNECT', 1);

// customization for the design layout
  define('CART_DISPLAY', 1); // Enable to view the shopping cart after adding a product
  define('TAX_VALUE', 16); // propducts tax
  define('TAX_DECIMAL_PLACES', 0); // 16% - If this were 2 it would be 16.00%
  define('BOX_WIDTH', 125); // how wide the boxes should be in pixels (default: 125)

  define('HEADER_BACKGROUND_COLOR', '#AABBDD');
  define('HEADER_NAVIGATION_BAR_BACKGROUND_COLOR', '#000000');
  define('HEADER_NAVIGATION_BAR_BACKGROUND_ERROR_COLOR', '#FF0000');
  define('HEADER_NAVIGATION_BAR_BACKGROUND_INFO_COLOR', '#00FF00');
  define('HEADER_NAVIGATION_BAR_FONT_FACE', 'Tahoma, Verdana, Arial');
  define('HEADER_NAVIGATION_BAR_FONT_COLOR', '#FFFFFF');
  define('HEADER_NAVIGATION_BAR_FONT_ERROR_COLOR', '#FFFFFF');
  define('HEADER_NAVIGATION_BAR_FONT_INFO_COLOR', '#000000');
  define('HEADER_NAVIGATION_BAR_FONT_SIZE', '2');

  define('FOOTER_BAR_BACKGROUND_COLOR', '#000000');
  define('FOOTER_BAR_FONT_FACE', 'Tahoma, Verdana, Arial');
  define('FOOTER_BAR_FONT_COLOR', '#FFFFFF');
  define('FOOTER_BAR_FONT_SIZE', '2');

  define('BOX_HEADING_BACKGROUND_COLOR', '#AABBDD');
  define('BOX_HEADING_FONT_FACE', 'Tahoma, Verdana, Arial');
  define('BOX_HEADING_FONT_COLOR', '#000000');
  define('BOX_HEADING_FONT_SIZE', '2');
  define('BOX_CONTENT_BACKGROUND_COLOR', '#FFFFFF');
  define('BOX_CONTENT_HIGHLIGHT_COLOR', '#FFFF33');     // use in best_sellers.php
  define('BOX_CONTENT_FONT_FACE', 'Verdana, Arial');
  define('BOX_CONTENT_FONT_COLOR', '#000000');
  define('BOX_CONTENT_FONT_SIZE', '1');

  define('TOP_BAR_BACKGROUND_COLOR', '#AABBDD');
  define('TOP_BAR_FONT_FACE', 'Tahoma, Verdana, Arial');
  define('TOP_BAR_FONT_COLOR', '#000000');
  define('TOP_BAR_FONT_SIZE', '2');

  define('HEADING_FONT_FACE', 'Verdana, Arial');
  define('HEADING_FONT_SIZE', '4');
  define('HEADING_FONT_COLOR', '#000000');

  define('SUB_BAR_BACKGROUND_COLOR', '#f4f7fd');
  define('SUB_BAR_FONT_FACE', 'Verdana, Arial');
  define('SUB_BAR_FONT_SIZE', '1');
  define('SUB_BAR_FONT_COLOR', '#000000');

  define('TEXT_FONT_FACE', 'Verdana, Arial');
  define('TEXT_FONT_SIZE', '2');
  define('TEXT_FONT_COLOR', '#000000');

  define('TABLE_HEADING_FONT_FACE', 'Verdana, Arial');
  define('TABLE_HEADING_FONT_SIZE', '2');
  define('TABLE_HEADING_FONT_COLOR', '#000000');

  define('TABLE_ROW_BACKGROUND_COLOR', '#ffffff');
  define('TABLE_ALT_BACKGROUND_COLOR', '#f4f7fd');

  define('SMALL_TEXT_FONT_FACE', 'Verdana, Arial');
  define('SMALL_TEXT_FONT_SIZE', '1');
  define('SMALL_TEXT_FONT_COLOR', '#000000');

  define('SPECIALS_PRICE_COLOR', '#FF0000'); // font color for the new price of products on special
  define('NEW_CART_ITEM_COLOR', '#FF0000'); 

  define('CHECKOUT_BAR_TEXT_COLOR', '#AABBDD');
  define('CHECKOUT_BAR_TEXT_COLOR_HIGHLIGHTED', '#000000');

  define('CATEGORY_FONT_FACE', 'Verdana, Arial');
  define('CATEGORY_FONT_SIZE', 2);
  define('CATEGORY_FONT_COLOR', '#AABBDD');

  define('ENTRY_FONT_FACE', 'Verdana, Arial');
  define('ENTRY_FONT_SIZE', 2);
  define('ENTRY_FONT_COLOR', '#000000');
  define('VALUE_FONT_FACE', 'Verdana, Arial');
  define('VALUE_FONT_SIZE', 2);
  define('VALUE_FONT_COLOR', '#000000');

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
  define('DISPLAY_MANUFACTURERS_BOX', 1); // Manufacturers Box: 0=disable; 1=enable
  define('DISPLAY_EMPTY_MANUFACTURERS', 1); // Display Manufacturers with no products: 0=disable; 1=enable

// Rollover Effect
  define('USE_ROLLOVER_EFFECT', 1); // Rollover Effect: 0=disable; 1=enable

// Categories Box: recursive products count
  define('SHOW_COUNTS', 1); // show category count: 0=disable; 1=enable
  define('USE_RECURSIVE_COUNT', 1); // recursive count: 0=disable; 1=enable

// include the database functions
  $include_file = DIR_FUNCTIONS . 'database.php';  include(DIR_INCLUDES . 'include_once.php');

// make a connection to the database... now
  tep_db_connect() or die('Unable to connect to database server!');

// include shopping cart class
  $include_file = DIR_CLASSES . 'shopping_cart.php'; include(DIR_INCLUDES . 'include_once.php');

// some code to solve compatibility issues
  $include_file = DIR_FUNCTIONS . 'compatibility.php'; include(DIR_INCLUDES . 'include_once.php');

// check to see if php implemented session management functions - if not, include php3/php4 compatible session class
  if (!function_exists('session_start')) {
    $include_file = DIR_CLASSES . 'sessions.php'; include(DIR_INCLUDES . 'include_once.php');
  }

// define how the session functions will be used
  $include_file = DIR_FUNCTIONS . 'sessions.php';  include(DIR_INCLUDES . 'include_once.php');

// lets start our session
  if (!SID && $HTTP_GET_VARS[tep_session_name()]) 
    tep_session_id( $HTTP_GET_VARS[tep_session_name()] );
  tep_session_start();
  if (function_exists('session_set_cookie_params')) {
    session_set_cookie_params(0, DIR_CATALOG);
  }

// Fix the cart if necesary
  if (REPAIR_BROKEN_CART && is_object($cart) ) {
    $broken_cart = $cart;
    $cart = new shoppingCart;
    $cart->unserialize($broken_cart);
  } else {
    if (!$cart) {
      tep_session_register('cart');
      $cart = new shoppingCart;
    }
  }

// set the application parameters (can be modified through the administration tool)
  $configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from configuration');
  while ($configuration = tep_db_fetch_array($configuration_query)) {
    define($configuration['cfgKey'], $configuration['cfgValue']);
  }

// languages - this should be removed when the proper functions are implemented!
  if (@!$language) {
    tep_session_register('language');
    $language = 'english';
  }
  if ($HTTP_GET_VARS['language']) {
    $language = 'english';
    if ($HTTP_GET_VARS['language'] == 'english') {
      $language = 'english';
    } elseif ($HTTP_GET_VARS['language'] == 'german') {
      $language = 'german';
    } elseif ($HTTP_GET_VARS['language'] == 'espanol') {
      $language = 'espanol';
    }
  }

// include the currency rates, and the language translations
  $include_file = DIR_INCLUDES . 'data/rates.php'; include(DIR_INCLUDES . 'include_once.php');
  $include_file = DIR_LANGUAGES . $language . '.php'; include(DIR_INCLUDES . 'include_once.php');

// define our general functions used application-wide
  $include_file = DIR_FUNCTIONS . 'general.php'; include(DIR_INCLUDES . 'include_once.php');

// Include the password crypto functions
 $include_file = DIR_FUNCTIONS . FILENAME_PASSWORD_CRYPT; include(DIR_INCLUDES . 'include_once.php'); 

// Include validation functions (right now only email address)
 $include_file = DIR_FUNCTIONS . 'validations.php'; include(DIR_INCLUDES . 'include_once.php'); 

// split-page-results
  $include_file = DIR_CLASSES . 'split_page_results.php'; include(DIR_INCLUDES . 'include_once.php');

// infobox
  $include_file = DIR_CLASSES . 'boxes.php'; include(DIR_INCLUDES . 'include_once.php');

// Shopping cart actions
  if ($HTTP_GET_VARS['action']) {
    $goto = (CART_DISPLAY) ? FILENAME_SHOPPING_CART : basename($PHP_SELF);
    if ($HTTP_GET_VARS['action'] == 'remove_product') {
      // customer wants to remove a product from their shopping cart
      $cart->remove($HTTP_GET_VARS['products_id']);
      header('Location: ' . tep_href_link($goto, tep_get_all_get_params(array('action')), 'NONSSL')); 
      tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'add_update_product') {
      // customer wants to update the product quantity in their shopping cart
      if ((is_array($HTTP_POST_VARS['cart_quantity'])) && (is_array($HTTP_POST_VARS['products_id']))) {
        for ($i=0; $i<sizeof($HTTP_POST_VARS['products_id']);$i++) {
          $attributes = ($HTTP_POST_VARS['id'][$HTTP_POST_VARS['products_id'][$i]]) ? $HTTP_POST_VARS['id'][$HTTP_POST_VARS['products_id'][$i]] : '';
          $cart->add_cart($HTTP_POST_VARS['products_id'][$i], $HTTP_POST_VARS['cart_quantity'][$i], $attributes);
        }
      } else {
        if (ereg('^[0-9]+$', $HTTP_POST_VARS['products_id'])) {
          $cart->add_cart($HTTP_POST_VARS['products_id'], $HTTP_POST_VARS['cart_quantity'], $HTTP_POST_VARS['id']);
        }
      }
      header('Location: ' . tep_href_link($goto, tep_get_all_get_params(array('action')), 'NONSSL'));
      tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'remove_all') {
      // customer wants to remove all products from their shopping cart
      $cart->reset(TRUE);
      header('Location: ' . tep_href_link($goto, '', 'NONSSL')); 
      tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'add_a_quickie') {
      // customer wants to add a quickie to the cart (called from a box)
      $quickie_query = tep_db_query("select products_id from products where products_model = '" . $HTTP_POST_VARS['quickie'] . "'");
      if (tep_db_num_rows($quickie_query) == 0) {
        $quickie_query = tep_db_query("select products_id from products where products_model LIKE '" . $HTTP_POST_VARS['quickie'] . "%'");
      }
      if (tep_db_num_rows($quickie_query) == 0 ||tep_db_num_rows($quickie_query) > 1) {
        Header( 'Location: ' . tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, 'keywords=' . $HTTP_POST_VARS['quickie'], 'NONSSL'));
        tep_exit();
      }
      $quickie_values = tep_db_fetch_array($quickie_query);
      $cart->add_cart($quickie_values['products_id'], 1, '');
      header('Location: ' . tep_href_link($goto, tep_get_all_get_params(array('action')), 'NONSSL')); 
      tep_exit();
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

// calculate selected currency
  if (!tep_session_is_registered('currency')) {
    $currency = CURRENCY_VALUE;
    tep_session_register('currency');
  }

  if (@$HTTP_GET_VARS['currency']) {
    $currency = $HTTP_GET_VARS['currency'];
  }
?>
