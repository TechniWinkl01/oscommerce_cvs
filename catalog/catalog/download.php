<?php
/*
  $Id: download.php,v 1.6 2002/06/04 15:46:05 clescuyer Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

// Check download.php was called with proper GET parameters
  if (!is_numeric($HTTP_GET_VARS['order']) ||
      !is_numeric($HTTP_GET_VARS['id'])) die;
     
  include('includes/application_top.php');
  
// Check that order_id, customer_id and filename match
  $downloads_query_raw = "SELECT DATE_FORMAT(date_purchased, '%Y-%m-%d') as date_purchased_day, opd.download_maxdays, opd.download_count, opd.download_maxdays, opd.orders_products_filename
                          FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " opd
                          WHERE customers_id = '" . $customer_id . "' 
                           AND o.orders_id = '" . $HTTP_GET_VARS['order'] . "'
                           AND op.orders_id = '" . $HTTP_GET_VARS['order'] . "'
                           AND opd.orders_products_id=op.orders_products_id
                           AND opd.orders_products_download_id = '" . $HTTP_GET_VARS['id'] . "'
                           AND opd.orders_products_filename<>''";
  $downloads_query = tep_db_query($downloads_query_raw);
// Die if no record in database
  if (tep_db_num_rows($downloads_query) == 0) die;
  $downloads_values = tep_db_fetch_array($downloads_query);
// MySQL 3.22 does not have INTERVAL
	list($dt_year, $dt_month, $dt_day) = explode('-', $downloads_values['date_purchased_day']);
 	$download_timestamp = mktime(23, 59, 59, $dt_month, $dt_day + $downloads_values['download_maxdays'], $dt_year);
  	  
// Die if time expired (maxdays = 0 means no time limit)
  if (($downloads_values['download_maxdays'] != 0) && ($download_timestamp <= time())) die;
// Die if remaining count is <=0
  if ($downloads_values['download_count'] <= 0) die;
// Die if file is not there
  if (!file_exists(DIR_FS_DOWNLOAD . $downloads_values['orders_products_filename'])) die;
  
// Now decrement counter
  $downloads_query_raw = "UPDATE " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " SET download_count=download_count-1
                          WHERE orders_products_download_id = " . $HTTP_GET_VARS['id'];
  $downloads_query = tep_db_query($downloads_query_raw);

// Returns a random name, 16 to 20 characters long
// There are more than 10^28 combinations
// The directory is "hidden", i.e. starts with '.'
function tep_random_name()
{
  $letters = 'abcdefghijklmnopqrstuvwxyz';
  srand((double) microtime() * 1000000);
  $dirname = '.';
  $length = floor(rand(16,20));
  for ($i = 1; $i <= $length; $i++) {
   $q = floor(rand(1,26));
   $dirname .= $letters[$q];
  }
  return $dirname;
}

// Unlinks all subdirectories and files in $dir
// Works only on one subdir level, will not recurse
function tep_unlink_temp_dir($dir)
{
  $h1 = opendir($dir);
  while ($subdir = readdir($h1)) {
// Ignore non directories
    if (!is_dir($dir . $subdir)) continue;
// Ignore . and .. and CVS
    if ($subdir == '.' || $subdir == '..' || $subdir == 'CVS') continue;
// Loop and unlink files in subdirectory
    $h2 = opendir($dir . $subdir);
    while ($file = readdir($h2)) {
      if ($file == '.' || $file == '..') continue;
      @unlink($dir . $subdir . '/' . $file);
    }
    closedir($h2); 
    @rmdir($dir . $subdir);
  }
  closedir($h1);
}


// Now send the file with header() magic
  header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
  header("Last-Modified: " . gmdate("D,d M Y H:i:s") . " GMT");
  header("Cache-Control: no-cache, must-revalidate");
  header("Pragma: no-cache");
  header("Content-Type: Application/octet-stream");
  header("Content-disposition: attachment; filename=" . $downloads_values['orders_products_filename']);

  if (DOWNLOAD_BY_REDIRECT == 'true') {

// This will work only on Unix/Linux hosts
    tep_unlink_temp_dir(DIR_FS_DOWNLOAD_PUBLIC);
    $tempdir = tep_random_name();
    umask(0000);
    mkdir(DIR_FS_DOWNLOAD_PUBLIC . $tempdir, 0777);
    symlink(DIR_FS_DOWNLOAD . $downloads_values['orders_products_filename'], DIR_FS_DOWNLOAD_PUBLIC . $tempdir . '/' . $downloads_values['orders_products_filename']);
    tep_redirect(DIR_WS_DOWNLOAD_PUBLIC . $tempdir . '/' . $downloads_values['orders_products_filename']);

  } else {
// This will work on all systems, but will need considerable resources
// We could also loop with fread($fp, 4096) to save memory
    readfile(DIR_FS_DOWNLOAD . $downloads_values['orders_products_filename']);
  }

?>
