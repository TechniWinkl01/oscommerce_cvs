<?
/*
  The Exchange Project Category Structure Upgrade Script
  for Preview Release 1.x -> Preview Release 2.x

  Version: 1.0
  Last Update: 25/09/2000
*/

// define our database connection
  define('DB_SERVER', 'exchange');
  define('DB_SERVER_USERNAME', 'root'); // user must have CREATE permissions!
  define('DB_SERVER_PASSWORD', '');
  define('DB_DATABASE', 'catalog'); // double check!

  define('STORE_PAGE_PARSE_TIME', 0); // safe to enable
  define('STORE_DB_TRANSACTIONS', 0); // safe to enable
  define('STORE_PAGE_PARSE_TIME_LOG', '/usr/local/apache/logs/exchange/upgrade_parse_time_log');

  define('STORE_PARSE_DATE_TIME_FORMAT', '%d/%m/%Y %H:%M:%S');
  if (STORE_PAGE_PARSE_TIME == '1') {
    $parse_start_time = microtime();
  }

// include the database functions (taken from project source)
  include('/usr/local/apache/htdocs/catalog/includes/functions/database.php');

// make a connection to the database
  tep_db_connect() or die('Unable to connect to database server!');

  echo 'The Exchange Project - Category Structure Update Script' . "\n\n";
  echo '(1/3) Creating "categories" and "products_to_categories" tables';

  if (tep_db_query("create table categories (
               categories_id int(5) DEFAULT '0' NOT NULL auto_increment,
               categories_name varchar(32) DEFAULT '' NOT NULL,
               categories_image varchar(64),
               parent_id int(5),
               sort_order int(3),
               PRIMARY KEY (categories_id),
               KEY IDX_CATEGORIES_NAME (categories_name))")) {
    $query1 = '1';
  } else {
    $query1 = '0';
  }

  if (tep_db_query("create table products_to_categories (
                    products_id int(5) DEFAULT '0' NOT NULL auto_increment,
                    categories_id int(5) DEFAULT '0' NOT NULL,
                    PRIMARY KEY (products_id,categories_id))")) {
    $query2 = '1';
  } else {
    $query2 = '0';
  }

  if (($query1 == '1') && ($query2 == '1')) {
    echo ' ..done!' . "\n";
  } else {
    echo ' ..STOP!! There has been an error creating the categories and products_to_categories table!' . "\n\n" . 'Please check the mysql user permissions!' . "\n";
    exit();
  }

  echo '(2/3) Inserting top level categories';

  $count = 0;
  $category_top_query = tep_db_query("select category_top_id, category_top_name, sort_order, category_image from category_top");
  while ($category_top = tep_db_fetch_array($category_top_query)) {
    tep_db_query("insert into categories
                 (categories_id, categories_name, categories_image, parent_id, sort_order) values
                 ('" . $category_top['category_top_id'] . "',
                 '" . $category_top['category_top_name'] . "',
                 '" . $category_top['category_image'] . "',
                 '0',
                 '" . $category_top['sort_order'] . "')");
  }
  $count++;

  if ($count > 0) {
    echo ' ..done! ' . $count . ' records inserted.' . "\n";
  } else {
    echo ' ..STOP!! No records have been inserted! No top level categories found!?' . "\n\n" . 'This script cannot help you any further if you have modified your database structure.' . "\n";
    exit();
  }

  echo '(3/3) Inserting subcategories and products';

  $count = 0;
  $subcategories_query = tep_db_query("select s.subcategories_id, s.subcategories_name, s.subcategories_image, s2c.category_top_id
                                      from subcategories s, subcategories_to_category s2c
                                      where s.subcategories_id = s2c.subcategories_id");
  while ($subcategories = tep_db_fetch_array($subcategories_query)) {
    tep_db_query("insert into categories
                 (categories_id, categories_name, categories_image, parent_id, sort_order) values
                 ('',
                 '" . $subcategories['subcategories_name'] . "',
                 '" . $subcategories['subcategories_image'] . "',
                 '" . $subcategories['category_top_id'] . "',
                 '0')");
    $count++;

    $new_category_id = tep_db_insert_id();

    $products_query = tep_db_query("select products_id from products_to_subcategories where subcategories_id = '" . $subcategories['subcategories_id'] . "'");
    while ($products = tep_db_fetch_array($products_query)) {
      tep_db_query("insert into products_to_categories (products_id, categories_id) values ('" . $products['products_id'] . "', '" . $new_category_id . "')");
      $count++;
    }
  }

  if ($count > 0) {
    echo ' ..done! ' . $count . ' records inserted.' . "\n";
  } else {
    echo ' ..STOP!! No records have been inserted! No subcategories found!?' . "\n\n" . 'This script cannot help you any further if you have modified your database structure.' . "\n";
    exit();
  }

  echo "\n" . 'Finished!' . "\n";

  if (STORE_PAGE_PARSE_TIME == '1') {
    $parse_end_time = microtime();
    $time_start = explode(' ', $parse_start_time);
    $time_end = explode(' ', $parse_end_time);
    $parse_time = number_format(($time_end[1] + $time_end[0] - ($time_start[1] + $time_start[0])), 3);
    error_log(strftime(STORE_PARSE_DATE_TIME_FORMAT) . ' - ' . ' (' . $parse_time . 'ms)' . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
  }
?>
