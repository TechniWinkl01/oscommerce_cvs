<?
// define our webserver variables
  define('HTTP_SERVER', 'http://exchange');
  define('DIR_SERVER_ROOT', '/www'); // where your pages are located on the server.. needed to delete images.. (eg, /usr/local/apache/htdocs)
  define('DIR_LOGS', '/usr/local/apache/logs/');
  define('DIR_ADMIN', '/admin/');
  define('DIR_CATALOG', '/catalog/');
  define('DIR_CATALOG_IMAGES', DIR_CATALOG . 'images/');
  define('DIR_IMAGES', DIR_ADMIN . 'images/');
  define('DIR_INCLUDES', 'includes/'); // NOTE! this is not interpreted with www/url path, instead it is a system path (eg, /usr/local/apache/htdocs/admin/includes/)
  define('DIR_BOXES', DIR_INCLUDES . 'boxes/');
  define('DIR_FUNCTIONS', DIR_INCLUDES . 'functions/');
  define('DIR_MODULES', DIR_INCLUDES . 'modules/');
  define('DIR_LANGUAGES', DIR_INCLUDES . 'languages/');

  define('STORE_NAME', 'The Exchange Project');

  define('EXIT_AFTER_REDIRECT', 0); // if enabled, the parse time will not store its time after the header(location) redirect - used with tep_tep_exit();
  define('STORE_PAGE_PARSE_TIME', 1); // store the time it takes to parse the page
  define('STORE_PAGE_PARSE_TIME_LOG', DIR_LOGS . 'exchange/parse_time_log');

  define('STORE_PARSE_DATE_TIME_FORMAT', '%d/%m/%Y %H:%M:%S');
  if (STORE_PAGE_PARSE_TIME == '1') {
    $parse_start_time = microtime();
  }

// define how the session functions will be used
  $include_file = DIR_FUNCTIONS . 'sessions.php';  include(DIR_INCLUDES . 'include_once.php');

// lets start our session
  tep_session_start();

// define the filenames used in the project
  define('FILENAME_CATEGORIES', 'categories.php');
  define('FILENAME_CUSTOMERS', 'customers.php');
  define('FILENAME_DEFAULT', 'default.php');
  define('FILENAME_INDEXES', 'indexes.php');
  define('FILENAME_MANUFACTURERS', 'manufacturers.php');
  define('FILENAME_ORDERS', 'orders.php');
  define('FILENAME_PRODUCTS', 'products.php');
  define('FILENAME_PRODUCTS_EXPECTED', 'products_expected.php');
  define('FILENAME_REVIEWS', 'reviews.php');
  define('FILENAME_SPECIALS', 'specials.php');
  define('FILENAME_STATS_CUSTOMERS', 'stats_customers.php');
  define('FILENAME_STATS_PRODUCTS_PURCHASED', 'stats_products_purchased.php');
  define('FILENAME_STATS_PRODUCTS_VIEWED', 'stats_products_viewed.php');
  define('FILENAME_SUBCATEGORIES', 'subcategories.php');

// define our database connection
  define('DB_SERVER', $HTTP_ENV_VARS['HOSTNAME']);
  define('DB_SERVER_USERNAME', 'mysql');
  define('DB_SERVER_PASSWORD', '');
  define('DB_DATABASE', 'catalog');

// include the database functions
  $include_file = DIR_FUNCTIONS . 'database.php';  include(DIR_INCLUDES . 'include_once.php');

// make a connection to the database... now
  tep_db_connect() or die('Unable to connect to database server!');

// define our general functions used application-wide
  $include_file = DIR_FUNCTIONS . 'general.php'; include(DIR_INCLUDES . 'include_once.php');

// customization for the design layout
  define('TAX_VALUE', 16); // propducts tax
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
    }
  }
  tep_session_register('language');
  
  $include_file = DIR_LANGUAGES . $language . '.php'; include(DIR_INCLUDES . 'include_once.php');
?>
