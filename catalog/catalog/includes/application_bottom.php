<?php
/*
  $Id: application_bottom.php,v 1.16 2004/02/16 07:05:23 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

// close session (store variables)
  $osC_Session->close();

  $messageStack->add('debug', 'Number of queries: ' . $osC_Database->numberOfQueries() . ' [' . $osC_Database->timeOfQueries() . 's]', 'warning');

  if ( (STORE_PAGE_PARSE_TIME == 'true') || (DISPLAY_PAGE_PARSE_TIME == 'true') ) {
    $time_start = explode(' ', PAGE_PARSE_START_TIME);
    $time_end = explode(' ', microtime());
    $parse_time = number_format(($time_end[1] + $time_end[0] - ($time_start[1] + $time_start[0])), 3);

    if (STORE_PAGE_PARSE_TIME == 'true') {
      error_log(strftime(STORE_PARSE_DATE_TIME_FORMAT) . ' - ' . getenv('REQUEST_URI') . ' (' . $parse_time . 's)' . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }

    if (DISPLAY_PAGE_PARSE_TIME == 'true') {
      $messageStack->add('debug', 'Parse Time: ' . $parse_time . 's', 'warning');
    }
  }

  if ($messageStack->size('debug') > 0) {
    echo $messageStack->output('debug');
  }

  if ( (GZIP_COMPRESSION == 'true') && ($ext_zlib_loaded == true) && ($ini_zlib_output_compression < 1) ) {
    if ( (PHP_VERSION < '4.0.4') && (PHP_VERSION >= '4') ) {
      tep_gzip_output(GZIP_LEVEL);
    }
  }
?>
