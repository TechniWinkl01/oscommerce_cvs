<?php
/*
  The Exchange Project Products<>Manufacturers Upgrade Script
  for Preview Release 1.x -> Preview Release 2.1

  Version: 1.0
  Last Update: 05/03/2001
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

  echo 'The Exchange Project - Products<>Manufacturers Update Script' . "\n\n";
  echo '(1/4) Added manufacturers_id to "products" table' . "\n";

  if (tep_db_query("alter table products add manufacturers_id int(5) null")) {
    echo ' ..done!' . "\n";
  } else {
    echo ' ..STOP! Could not alter the "products" table - check table permissions!' . "\n";
    exit();
  }

  echo '(2/4) Removing manufacturers_location from "manufacturers"' . "\n";

  if (tep_db_query("alter table manufacturers drop manufacturers_location")) {
    echo ' ..done!' . "\n";
  } else {
    echo ' ..STOP! Could not alter the "manufacturers" table - check table permissions!' . "\n";
    exit();
  }

  echo '(3/4) Updating manufacturers_id in "products" table';

  $manufacturers_query = tep_db_query("select products_id, manufacturers_id from products_to_manufacturers");
  while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
    tep_db_query("update products set manufacturers_id = '" . $manufacturers['manufacturers_id'] . "' where products_id = '" . $manufacturers['products_id'] . "'");
  }

  echo ' ..done!' . "\n";

  echo '(4/4) Removing "products_to_manufacturers" table';
  
  if (tep_db_query("drop table products_to_manufacturers")) {
    echo ' ..done!' . "\n";
  } else {
    echo ' ..STOP! Could not remove the "products_to_manufacturers" table - check table permissions!' . "\n";
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
