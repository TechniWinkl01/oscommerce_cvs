<?php
/*
  $Id: database.php,v 1.13 2001/12/30 09:20:16 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/

  function tep_db_connect($server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE, $link = 'db_link') {
    global $$link;

    if (USE_PCONNECT) {
      $$link = mysql_pconnect($server, $username, $password);
    } else {
      $$link = mysql_connect($server, $username, $password);
    }

    if ($$link) mysql_select_db($database);

    return $$link;
  }

  function tep_db_close($link = 'db_link') {
    global $$link;

    return mysql_close($$link);
  }

  function tep_db_error($query, $errno, $error) { 
    die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
  }

  function tep_db_query($query, $link = 'db_link') {
    global $$link;

    if (STORE_DB_TRANSACTIONS == 'true') {
      error_log('QUERY ' . $query . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }

    $result = mysql_query($query, $$link) or tep_db_error($query, mysql_errno(), mysql_error());

    if (STORE_DB_TRANSACTIONS == 'true') {
      error_log('RESULT ' . $result . ' ' . mysql_error() . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }

    return $result;
  }

  function tep_db_perform($table, $data, $action = 'insert', $parameters = '', $link = 'db_link') {
    reset($data);
    if ($action == 'insert') {
      $query = 'insert into ' . $table . ' (';
      while (list($columns, ) = each($data)) {
        $query .= $columns . ', ';
      }
      $query = substr($query, 0, -2) . ') values (';
      reset($data);
      while (list(, $value) = each($data)) {
        if (is_string($value)) {
          if ($value == 'now()') {
            $query .= 'now(), ';
          } elseif ($value == 'null') {
            $query .= 'null, ';
          } else {
            $query .= '\'' . tep_db_input($value) . '\', ';
          }
        } else {
          $query .= $value . ', ';
        }
      }
      $query = substr($query, 0, -2) . ')';
    } elseif ($action == 'update') {
      $query = 'update ' . $table . ' set ';
      while (list($columns, $value) = each($data)) {
        if (is_string($value)) {
          if ($value == 'now()') {
            $query .= $columns . ' = now(), ';
          } elseif ($value == 'null') {
            $query .= $columns .= ' = null, ';
          } else {
            $query .= $columns . ' = \'' . tep_db_input($value) . '\', ';
          }
        } else {
          $query .= $columns . ' = ' . $value . ', ';
        }
      }
      $query = substr($query, 0, -2) . ' where ' . $parameters;
    }

    return tep_db_query($query, $link);
  }

  function tep_db_fetch_array($db_query) {
    return mysql_fetch_array($db_query, MYSQL_ASSOC);
  }

  function tep_db_result($result, $row, $field = '') {
    return mysql_result($result, $row, $field);
  }

  function tep_db_num_rows($db_query) {
    return mysql_num_rows($db_query);
  }

  function tep_db_data_seek($db_query, $row_number) {
    return mysql_data_seek($db_query, $row_number);
  }

  function tep_db_insert_id() {
    return mysql_insert_id();
  }

  function tep_db_free_result($db_query) {
    return mysql_free_result($db_query);
  }

  function tep_db_fetch_fields($db_query) {
    return mysql_fetch_field($db_query);
  }

  function tep_db_output($string) {
    return stripslashes($string);
  }

  function tep_db_input($string) {
    return addslashes($string);
  }

  function tep_db_prepare_input($string) {
    return trim(stripslashes($string));
  }
?>
