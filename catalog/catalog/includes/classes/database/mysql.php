<?php
/*
  $Id: mysql.php,v 1.3 2004/04/13 08:09:35 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  if (!function_exists('mysql_fetch_assoc')) {
    function mysql_fetch_assoc($resource) {
      return mysql_fetch_array($resource, MYSQL_ASSOC);
    }
  }

  class osC_Database_mysql extends osC_Database {
    var $sql_parse_string = 'addslashes',
        $sql_parse_string_with_connection_handler = false;

    function osC_Database_mysql($server, $username, $password) {
      $this->server = $server;
      $this->username = $username;
      $this->password = $password;

      if (function_exists('mysql_real_escape_string')) {
        $this->sql_parse_string = 'mysql_real_escape_string';
        $this->sql_parse_string_with_connection_handler = true;
      } elseif (function_exists('mysql_escape_string')) {
        $this->sql_parse_string = 'mysql_escape_string';
      }

      if ($this->is_connected === false) {
        $this->connect();
      }
    }

    function connect() {
      if (defined('USE_PCONNECT') && (USE_PCONNECT == 'true')) {
        $connect_function = 'mysql_pconnect';
      } else {
        $connect_function = 'mysql_connect';
      }

      if ($this->link = @$connect_function($this->server, $this->username, $this->password)) {
        $this->setConnected(true);

        return true;
      } else {
        $this->setError(mysql_error(), mysql_errno());

        return false;
      }
    }

    function disconnect() {
      if ($this->isConnected()) {
        if (@mysql_close($this->link)) {
          return true;
        } else {
          return false;
        }
      } else {
        return true;
      }
    }

    function selectDatabase($database) {
      if ($this->isConnected()) {
        if (@mysql_select_db($database, $this->link)) {
          return true;
        } else {
          $this->setError(mysql_error($this->link), mysql_errno($this->link));

          return false;
        }
      } else {
        return false;
      }
    }

    function simpleQuery($query, $debug = false) {
      global $messageStack, $osC_Services;

      if ($this->isConnected()) {
        $this->number_of_queries++;

        if ( ($debug === false) && ($this->debug === true) ) {
          $debug = true;
        }

        if (($debug === true) && (!isset($osC_Services) || !$osC_Service->isStarted('debug'))) {
          $debug = false;
        }

        if (isset($osC_Services) && $osC_Services->isStarted('debug')) {
          if (tep_not_null(SERVICE_DEBUG_EXECUTION_TIME_LOG) && (SERVICE_DEBUG_LOG_DB_QUERIES == 'True')) {
            @error_log('QUERY ' . $query . "\n", 3, SERVICE_DEBUG_EXECUTION_TIME_LOG);
          }
        }

        if ($debug === true) {
          $time_start = $this->getMicroTime();
        }

        $resource = @mysql_query($query, $this->link);

        if ($debug === true) {
          $time_end = $this->getMicroTime();

          $query_time = number_format($time_end - $time_start, 5);

          if ($this->debug === true) {
            $this->time_of_queries += $query_time;
          }

          echo '<div style="font-family: Verdana, Arial, sans-serif; font-size: 7px; font-weight: bold;">[<a href="#query' . $this->number_of_queries . '">#' . $this->number_of_queries . '</a>]</div>';

          $messageStack->add('debug', '<a name=\'query' . $this->number_of_queries . '\'></a>[#' . $this->number_of_queries . ' - ' . $query_time . 's] ' . $query, 'warning');
        }

        if ($resource !== false) {
          return $resource;
        } else {
          $this->setError(mysql_error($this->link), mysql_errno($this->link));

          return false;
        }
      } else {
        return false;
      }
    }

    function dataSeek($row_number, $resource) {
      return @mysql_data_seek($resource, $row_number);
    }

    function randomQuery($query) {
      $query .= ' order by rand() limit 1';

      return $this->simpleQuery($query);
    }

    function randomQueryMulti($query) {
      $resource = $this->simpleQuery($query);

      $num_rows = $this->numberOfRows($resource);

      if ($num_rows > 0) {
        $random_row = tep_rand(0, ($num_rows - 1));

        $this->dataSeek($random_row, $resource);

        return $resource;
      } else {
        return false;
      }
    }

    function next($resource) {
      return @mysql_fetch_assoc($resource);
    }

    function freeResult($resource) {
      if (@mysql_free_result($resource)) {
        return true;
      } else {
        $this->setError('Resource \'osC_Database->' . $resource . '\' could not be freed.');

        return false;
      }
    }

    function nextID() {
      if ($id = @mysql_insert_id($this->link)) {
        return $id;
      } else {
        $this->setError(mysql_error($this->link), mysql_errno($this->link));

        return false;
      }
    }

    function numberOfRows($resource) {
      return @mysql_num_rows($resource);
    }

    function affectedRows() {
      return mysql_affected_rows($this->link);
    }
  }
?>
