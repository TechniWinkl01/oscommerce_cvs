<?php
/*
  $Id: cache.php,v 1.2 2001/08/09 19:59:58 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

////
//! Write out serialized data.
//  write_cache uses serialize() to store $var in $filename.
//  $var      -  The variable to be written out.
//  $filename -  The name of the file to write to.
  function write_cache(&$var, $filename) {
    $filename = CACHE_DIR . $filename;
    $success = false;
// try to open the file
    if ($fp = fopen($filename, 'w')) {
// write serialized data
      fputs($fp, serialize($var));
      fclose($fp);
      $success = true;
    }

    return $success;
  }

////
//! Read in seralized data.
//  read_cache reads the serialized data in $filename and
//  fills $var using unserialize().
//  $var      -  The variable to be filled.
//  $filename -  The name of the file to read.
  function read_cache(&$var, $filename){
    $filename = CACHE_DIR . $filename;
    $success = false;
// try to open file
    if ($fp = @fopen($filename, 'r')) {
// read in serialized data
      $szdata = fread($fp, filesize($filename));
      fclose($fp);
// unserialze the data
      $var = unserialize($szdata);
      $success = true;
    }

    return $success;
  }

////
//! Get data from the cache or the database.
//  get_db_cache checks the cache for cached SQL data in $filename
//  or retreives it from the database is the cache is not present.
//  $SQL      -  The SQL query to exectue if needed.
//  $filename -  The name of the cache file.
//  $var      -  The variable to be filled.
//  $refresh  -  Optional.  If true, do not read from the cache.
  function get_db_cache($SQL, &$var, $filename, $refresh = false){
    $var = array();

// check for the refresh flag and try to the data
    if ($refresh || !read_cache($var, $filename)) {
// Didn' get cache so go to the database.
//      $conn = mysql_connect("localhost", "apachecon", "apachecon");
      $res = tep_db_query($SQL);
//      if ($err = mysql_error()) trigger_error($err, E_USER_ERROR);
// loop through the results and add them to an array
      while ($rec = tep_db_fetch_array($res)) {
        $var[] = $rec;
      }
// write the data to the file
      write_cache($var, $filename);
    }
  }

////
//! Cache the categories box
// Cache the categories box
  function tep_cache_categories_box($refresh = false) {
    global $HTTP_GET_VARS, $foo, $languages_id, $id, $categories_string;

    if ($refresh || !read_cache($cache_output, 'categories_box.cache' . $HTTP_GET_VARS['cPath'])) {
      ob_start();
      include(DIR_WS_BOXES . 'categories.php');
      $cache_output = ob_get_contents();
      ob_end_clean();
      write_cache($cache_output, 'categories_box.cache' . $HTTP_GET_VARS['cPath']);
    }

    return $cache_output;
  }

////
//! Cache the manufacturers box
// Cache the manufacturers box
  function tep_cache_manufacturers_box($refresh = false) {
    global $HTTP_GET_VARS;

    if ($refresh || !read_cache($cache_output, 'manufacturers_box.cache' . $HTTP_GET_VARS['manufacturers_id'])) {
      ob_start();
      include(DIR_WS_BOXES . 'manufacturers.php');
      $cache_output = ob_get_contents();
      ob_end_clean();
      write_cache($cache_output, 'manufacturers_box.cache' . $HTTP_GET_VARS['manufacturers_id']);
    }

    return $cache_output;
  }
?>
