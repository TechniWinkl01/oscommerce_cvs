<?
  if (file_exists('includes/local/configure.php')) {
    include('includes/local/configure.php');
    if (!CONFIGURE_STATUS_COMPLETED) { // File not read properly
       die('File configure.php was not found or was improperly formatted, contact webmaster of this domain.<br>The configuration file in catalog/includes/local/configure.php was not properly formatted.');
    }
  }
// expert mode?
  define('EXPERT_MODE', '0'); // enable if you know what your doing with the database structure

// define our webserver variables
  define('HTTP_SERVER', 'http://exchange');
  define('DIR_SERVER_ROOT', '/www'); // where your pages are located on the server.. needed to delete images.. (eg, /usr/local/apache/htdocs)
  define('DIR_LOGS', '/usr/local/apache/logs/');
  define('DIR_ADMIN', '/admin/');
  define('DIR_CATALOG', '/catalog/');
  define('DIR_CATALOG_IMAGES', DIR_CATALOG . 'images/');
  define('DIR_PAYMENT_MODULES', DIR_SERVER_ROOT . DIR_CATALOG . 'includes/modules/payment/');
  define('DIR_SHIPPING_MODULES', DIR_SERVER_ROOT . DIR_CATALOG . 'includes/modules/shipping/');
  define('DIR_IMAGES', DIR_ADMIN . 'images/');
  define('DIR_INCLUDES', 'includes/'); // NOTE! this is not interpreted with www/url path, instead it is a system path (eg, /usr/local/apache/htdocs/admin/includes/)
  define('DIR_BOXES', DIR_INCLUDES . 'boxes/');
  define('DIR_FUNCTIONS', DIR_INCLUDES . 'functions/');
  define('DIR_CLASSES', DIR_INCLUDES . 'classes/');
  define('DIR_MODULES', DIR_INCLUDES . 'modules/');
  define('DIR_LANGUAGES', DIR_INCLUDES . 'languages/');

  define('STORE_NAME', 'The Exchange Project');
  define('STORE_COUNTRY', 81); // Germany is 81, USA is 223

  define('EXIT_AFTER_REDIRECT', 0); // if enabled, the parse time will not store its time after the header(location) redirect - used with tep_tep_exit();
  define('STORE_PAGE_PARSE_TIME', 1); // store the time it takes to parse the page
  define('STORE_PAGE_PARSE_TIME_LOG', DIR_LOGS . 'exchange/parse_time_log');

  define('STORE_PARSE_DATE_TIME_FORMAT', '%d/%m/%Y %H:%M:%S');
  if (STORE_PAGE_PARSE_TIME == '1') {
    $parse_start_time = microtime();
  }
  define('STORE_DB_TRANSACTIONS', 0);

// define the filenames used in the project
  define('FILENAME_CATEGORIES', 'categories.php');
  define('FILENAME_CONFIGURATION', 'configuration.php');
  define('FILENAME_PAYMENT_MODULES', 'payment_modules.php');
  define('FILENAME_SHIPPING_MODULES', 'shipping_modules.php');
  define('FILENAME_CUSTOMERS', 'customers.php');
  define('FILENAME_DEFAULT', 'default.php');
  define('FILENAME_MANUFACTURERS', 'manufacturers.php');
  define('FILENAME_ORDERS', 'orders.php');
  define('FILENAME_PRODUCTS_ATTRIBUTES', 'products_attributes.php');
  define('FILENAME_PRODUCTS_EXPECTED', 'products_expected.php');
  define('FILENAME_REVIEWS', 'reviews.php');
  define('FILENAME_SPECIALS', 'specials.php');
  define('FILENAME_STATS_CUSTOMERS', 'stats_customers.php');
  define('FILENAME_STATS_PRODUCTS_PURCHASED', 'stats_products_purchased.php');
  define('FILENAME_STATS_PRODUCTS_VIEWED', 'stats_products_viewed.php');
  define('FILENAME_TAX_CLASSES', 'tax_classes.php');
  define('FILENAME_TAX_RATES', 'tax_rates.php');
  define('FILENAME_ZONES', 'zones.php');

// define our database connection
  define('DB_SERVER', $HTTP_ENV_VARS['HOSTNAME']);
  define('DB_SERVER_USERNAME', 'mysql');
  define('DB_SERVER_PASSWORD', '');
  define('DB_DATABASE', 'catalog');
  define('USE_PCONNECT', 1);

// customization for the design layout
  define('MAX_DISPLAY_SEARCH_RESULTS', 20); // how many products to list
  define('MAX_DISPLAY_PAGE_LINKS', 5); // how many page numbers to link for page-sets
  define('IMAGE_REQUIRED', 1); // require product images? 1 = yes
  define('TAX_VALUE', 16); // propducts tax
  define('TAX_DECIMAL_PLACES', 0); // Display format for tax rate
  define('BOX_WIDTH', 125); // how wide the boxes should be in pixels (default: 125)
  define('SMALL_IMAGE_WIDTH', 100); // the width in pixels of small images (default: 100);
  define('SMALL_IMAGE_HEIGHT', 80); // the height in pixels of small images (default: 80);
  define('HEADING_IMAGE_WIDTH', 85);
  define('HEADING_IMAGE_HEIGHT', 60);

  define('HEADER_BACKGROUND_COLOR', '#AABBDD');
  define('HEADER_NAVIGATION_BAR_BACKGROUND_COLOR', '#000000');
  define('HEADER_NAVIGATION_BAR_FONT_FACE', 'Tahoma, Verdana, Arial');
  define('HEADER_NAVIGATION_BAR_FONT_COLOR', '#FFFFFF');
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
  define('TABLE_HEADING_FONT_SIZE', '1');
  define('TABLE_HEADING_FONT_COLOR', '#000000');

  define('SMALL_TEXT_FONT_FACE', 'Verdana, Arial');
  define('SMALL_TEXT_FONT_SIZE', '1');
  define('SMALL_TEXT_FONT_COLOR', '#000000');

  define('SPECIALS_PRICE_COLOR', '#FF0000'); // font color for the new price of products on special

  define('CATEGORY_FONT_FACE', 'Verdana, Arial');
  define('CATEGORY_FONT_SIZE', 2);
  define('CATEGORY_FONT_COLOR', '#AABBDD');

  define('ENTRY_FONT_FACE', 'Verdana, Arial');
  define('ENTRY_FONT_SIZE', 2);
  define('ENTRY_FONT_COLOR', '#000000');
  define('VALUE_FONT_FACE', 'Verdana, Arial');
  define('VALUE_FONT_SIZE', 2);
  define('VALUE_FONT_COLOR', '#000000');

// font styles
  define('FONT_STYLE_GENERAL', '<font face="Verdana, Arial" size="2">');
  define('FONT_STYLE_INFO_BOX_HEADING', '<font face="Verdana, Arial" size="1" color="#ffffff">');
  define('FONT_STYLE_INFO_BOX_BODY', '<font face="Verdana, Arial" size="1">');
  define('FONT_STYLE_NAVIGATION_BOX_HEADING', '<font face="Tahoma, Verdana, Arial" size="2">');

// Shipping Options
  define('SHIPPING_FREE', 1);

// minimum length of text field values accepted
  define('ENTRY_FIRST_NAME_MIN_LENGTH', 3);
  define('ENTRY_LAST_NAME_MIN_LENGTH', 3);
  define('ENTRY_DOB_MIN_LENGTH', 10);
  define('ENTRY_EMAIL_ADDRESS_MIN_LENGTH', 6);
  define('ENTRY_STREET_ADDRESS_MIN_LENGTH', 5);
  define('ENTRY_POSTCODE_MIN_LENGTH', 4);
  define('ENTRY_CITY_MIN_LENGTH', 4);
  define('ENTRY_TELEPHONE_MIN_LENGTH', 3);
  define('ENTRY_PASSWORD_MIN_LENGTH', 5);

// Control what fields of the customer table are used
  define('ACCOUNT_GENDER', 1);
  define('ACCOUNT_DOB', 1);
  define('ACCOUNT_SUBURB', 1);
  define('ACCOUNT_STATE', 1);

// check to see if php implemented session management functions - if not, include php3/php4 compatible session class
  if (!function_exists('session_start')) {
    $include_file = DIR_CLASSES . 'sessions.php'; include(DIR_INCLUDES . 'include_once.php');
  }

// define how the session functions will be used
  $include_file = DIR_FUNCTIONS . 'sessions.php';  include(DIR_INCLUDES . 'include_once.php');

// lets start our session
  tep_session_start();

// languages - this should be removed when the proper functions are implemented!
  if (@!$language) {
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
  tep_session_register('language');

  $include_file = DIR_LANGUAGES . $language . '.php'; include(DIR_INCLUDES . 'include_once.php');
  $include_file = DIR_LANGUAGES . $language . '/' . basename($PHP_SELF); include(DIR_INCLUDES . 'include_once.php');

// include the database functions
  $include_file = DIR_FUNCTIONS . 'database.php';  include(DIR_INCLUDES . 'include_once.php');

// make a connection to the database... now
  tep_db_connect() or die('Unable to connect to database server!');

// define our general functions used application-wide
  $include_file = DIR_FUNCTIONS . 'general.php'; include(DIR_INCLUDES . 'include_once.php');

// setup our boxes
  $include_file = DIR_CLASSES . 'boxes.php'; include(DIR_INCLUDES . 'include_once.php');

// split-page-results
  $include_file = DIR_CLASSES . 'split_page_results.php'; include(DIR_INCLUDES . 'include_once.php');

// entry/item info classes
  $include_file = DIR_CLASSES . 'category_info.php'; include(DIR_INCLUDES . 'include_once.php');
  $include_file = DIR_CLASSES . 'configuration_info.php'; include(DIR_INCLUDES . 'include_once.php');
  $include_file = DIR_CLASSES . 'customer_info.php'; include(DIR_INCLUDES . 'include_once.php');
  $include_file = DIR_CLASSES . 'manufacturer_info.php'; include(DIR_INCLUDES . 'include_once.php');
  $include_file = DIR_CLASSES . 'product_expected_info.php'; include(DIR_INCLUDES . 'include_once.php');
  $include_file = DIR_CLASSES . 'product_info.php'; include(DIR_INCLUDES . 'include_once.php');
  $include_file = DIR_CLASSES . 'review_info.php'; include(DIR_INCLUDES . 'include_once.php');
  $include_file = DIR_CLASSES . 'special_price_info.php'; include(DIR_INCLUDES . 'include_once.php');
  $include_file = DIR_CLASSES . 'tax_class_info.php'; include(DIR_INCLUDES . 'include_once.php');
  $include_file = DIR_CLASSES . 'tax_rate_info.php'; include(DIR_INCLUDES . 'include_once.php');
  $include_file = DIR_CLASSES . 'zones_info.php'; include(DIR_INCLUDES . 'include_once.php');

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
?>
