<?php
/*
  $Id: database.php,v 1.11 2001/11/17 00:37:43 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  function tep_db_connect() {
    global $db_link;
    
    if (USE_PCONNECT == true) {
      @$db_link = mysql_pconnect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
    } else {
      @$db_link = mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
    }

    if ($db_link) @mysql_select_db(DB_DATABASE);
    return $db_link;
  }

  function tep_db_close() {
    global $db_link;

    $result = mysql_close($db_link);
    
    return $result;
  }

  function tep_db_error ($query, $errno, $error) { 
    die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
  }

  function tep_db_query($db_query) {
    global $db_link;

    if (STORE_DB_TRANSACTIONS == 'true') {
       error_log("QUERY " . $db_query . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }

    $result = mysql_query($db_query, $db_link) or tep_db_error($db_query, mysql_errno(), mysql_error());

    if (STORE_DB_TRANSACTIONS == 'true') {
       $result_error = mysql_error();
       error_log("RESULT " . $result . " " . $result_error . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }

    return $result;
  }

  function tep_db_fetch_array($db_query) {

    $result = mysql_fetch_array($db_query);

    return $result;
  }

  function tep_db_num_rows($db_query) {

    $result = mysql_num_rows($db_query);

    return $result;
  }

  function tep_db_data_seek($db_query, $row_number) {

    $result = mysql_data_seek($db_query, $row_number);

    return $result;
  }

  function tep_db_insert_id() {

    $result = mysql_insert_id();

    return $result;
  }

  function tep_db_free_result($db_query) {

    $result = mysql_free_result($db_query);

    return $result;
  }

  function tep_db_fetch_fields($db_query) {
    $result = mysql_fetch_field($db_query);

    return $result;
  }
?>
