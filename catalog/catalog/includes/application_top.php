<?
// define our webserver variables
  define('HTTP_SERVER', 'http://exchange');
  define('HTTPS_SERVER', 'https://exchange');
  define('ENABLE_SSL', 1); // ssl server enable(1)/disable(0)
  define('DIR_SERVER_ROOT', '/usr/local/apache/');
  define('DIR_CATALOG', '/catalog/');
  define('DIR_IMAGES', '/catalog/images/');
  define('DIR_INCLUDES', 'includes/');
  define('DIR_BOXES', DIR_INCLUDES . 'boxes/');
  define('DIR_FUNCTIONS', DIR_INCLUDES . 'functions/');
  define('DIR_MODULES', DIR_INCLUDES . 'modules/');
  define('DIR_LANGUAGES', DIR_INCLUDES . 'languages/');

  define('STORE_NAME', 'The Exchange Project');
  define('STORE_OWNER', 'Harald Ponce de Leon');
  define('STORE_OWNER_EMAIL_ADDRESS', 'hpdl@theexchangeproject.org');
  define('EMAIL_FROM', 'Harald Ponce de Leon <hpdl@theexchangeproject.org>');

  define('EXIT_AFTER_REDIRECT', 0); // if enabled, the parse time will not store its time after the header(location) redirect - used with tep_exit();
  define('STORE_PAGE_PARSE_TIME', 1);
  define('STORE_PAGE_PARSE_TIME_LOG', DIR_SERVER_ROOT . 'logs/exchange/parse_time_log');

  define('STORE_PARSE_DATE_TIME_FORMAT', '%d/%m/%Y %H:%M:%S');
  if (STORE_PAGE_PARSE_TIME == '1') {
    $parse_start_time = microtime();
  }

// define how the session functions will be used
  $include_file = DIR_FUNCTIONS . 'sessions.php';  include(DIR_INCLUDES . 'include_once.php');

// lets start our session
  tep_session_start();

// define the filenames used in the project
  define('FILENAME_ACCOUNT', 'account.php');
  define('FILENAME_ACCOUNT_EDIT', 'account_edit.php');
  define('FILENAME_ACCOUNT_EDIT_PROCESS', 'account_edit_process.php');
  define('FILENAME_ACCOUNT_HISTORY', 'account_history.php');
  define('FILENAME_ACCOUNT_HISTORY_INFO', 'account_history_info.php');
  define('FILENAME_ADDRESS_BOOK', 'address_book.php');
  define('FILENAME_ADDRESS_BOOK_PROCESS', 'address_book_process.php');
  define('FILENAME_CHECKOUT', 'checkout.php');
  define('FILENAME_CHECKOUT_ADDRESS', 'checkout_address.php');
  define('FILENAME_CHECKOUT_CONFIRMATION', 'checkout_confirmation.php');
  define('FILENAME_CHECKOUT_PAYMENT', 'checkout_payment.php');
  define('FILENAME_CHECKOUT_PROCESS', 'checkout_process.php');
  define('FILENAME_CHECKOUT_SUCCESS', 'checkout_success.php');
  define('FILENAME_CREATE_ACCOUNT', 'create_account.php');
  define('FILENAME_CREATE_ACCOUNT_PROCESS', 'create_account_process.php');
  define('FILENAME_CREATE_ACCOUNT_SUCCESS', 'create_account_success.php');
  define('FILENAME_DEFAULT', 'default.php');
  define('FILENAME_INFO_SHOPPING_CART', 'info_shopping_cart.php');
  define('FILENAME_LOGIN', 'login.php');
  define('FILENAME_LOGOFF', 'logoff.php');
  define('FILENAME_PASSWORD_FORGOTTEN', 'password_forgotten.php');
  define('FILENAME_PRODUCT_INFO', 'product_info.php');
  define('FILENAME_PRODUCT_LIST', 'product_list.php');
  define('FILENAME_PRODUCT_REVIEWS', 'product_reviews.php');
  define('FILENAME_PRODUCT_REVIEWS_INFO', 'product_reviews_info.php');
  define('FILENAME_PRODUCT_REVIEWS_WRITE', 'product_reviews_write.php');
  define('FILENAME_REVIEWS', 'reviews.php');
  define('FILENAME_SEARCH', 'search.php');
  define('FILENAME_SHOPPING_CART', 'shopping_cart.php');
  define('FILENAME_SPECIALS', 'specials.php');

// define our database connection
  define('DB_SERVER', 'exchange');
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
  define('IMAGE_REQUIRED', 1); // should product images be necessary
  define('TAX_VALUE', 16); // propducts tax
  define('BOX_WIDTH', 125); // how wide the boxes should be in pixels (default: 125)
  define('MAX_ADDRESS_BOOK_ENTRIES', 5);
  define('MAX_DISPLAY_SEARCH_RESULTS', 30);
  define('MAX_DISPLAY_SPECIAL_PRODUCTS', 9);
  define('MAX_DISPLAY_NEW_PRODUCTS', 9); // used when user has chosen a category, how many new products are shown
  define('MAX_DISPLAY_UPCOMING_PRODUCTS', 10); // how many upcoming products should be displayed on the main page
  define('MAX_DISPLAY_NEW_REVIEWS', 6);
  define('MAX_RANDOM_SELECT_REVIEWS', 10); // how many records to select from to choose one random product review (default: 10)
  define('MAX_RANDOM_SELECT_NEW', 10); // how many records to select from to choose one random new product to display (default: 10)
  define('MAX_RANDOM_SELECT_SPECIALS', 10); // how many records to select from to choose one random product special to display (default: 10)
  define('SMALL_IMAGE_WIDTH', 100); // the width in pixels of small images (default: 100);
  define('SMALL_IMAGE_HEIGHT', 80); // the height in pixels of small images (default: 80);
  define('HEADING_IMAGE_WIDTH', 85);
  define('HEADING_IMAGE_HEIGHT', 60);
  define('SUBCATEGORY_IMAGE_WIDTH', 100);
  define('SUBCATEGORY_IMAGE_HEIGHT', 57);

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
  define('TABLE_HEADING_FONT_SIZE', '2');
  define('TABLE_HEADING_FONT_COLOR', '#000000');

  define('SMALL_TEXT_FONT_FACE', 'Verdana, Arial');
  define('SMALL_TEXT_FONT_SIZE', '1');
  define('SMALL_TEXT_FONT_COLOR', '#000000');

  define('SPECIALS_PRICE_COLOR', '#FF0000'); // font color for the new price of products on special

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

  define('ADDRESS_BOOK_FIRST_NAME_MIN_LENGTH', 3);
  define('ADDRESS_BOOK_LAST_NAME_MIN_LENGTH', 3);
  define('ADDRESS_BOOK_STREET_ADDRESS_MIN_LENGTH', 5);
  define('ADDRESS_BOOK_POST_CODE_MIN_LENGTH', 4);
  define('ADDRESS_BOOK_CITY_MIN_LENGTH', 4);
  define('ADDRESS_BOOK_COUNTRY_MIN_LENGTH', 3);

  define('CC_OWNER_MIN_LENGTH', 3);
  define('CC_NUMBER_MIN_LENGTH', 10);
  define('CC_EXPIRY_MIN_LENGTH', 4);

  define('REVIEW_TEXT_MIN_LENGTH', 50);

// Shipping Options
  define('SHIPPING_FREE', 1);
  define('SHIPPING_MODEL', 0);
  define('SHIPPING_NONE', 0); // Shipping Models
  define('SHIPPING_UPS', 1);

// Vars for UPS Shipping Model (Only really useful for USA Stores)
  define('UPS_SPEED', "GND");
  define('UPS_ORIGIN_ZIP', "34685");
  define('UPS_PICKUP', "CC");
  define('UPS_PACKAGE', "CP");
  define('UPS_RES', "RES");

// Prodict listing control
  define('PRODUCT_LIST_MODEL', 0); // Make true to display Model # before Product name

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
