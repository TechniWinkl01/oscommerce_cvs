<?php
/*
  $Id: localization.php,v 1.8 2001/10/12 14:25:05 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  function quotecurrency($code, $base = DEFAULT_CURRENCY) {
    $err_num = $err_msg='';
    $s = fsockopen('www.oanda.com', 5011, $err_num, $err_msg, 5);
    if (!$s) {
      $resp = 'na';  // prevent breaking script
    } else {
      fputs($s, "fxp/1.1\r\nbasecurrency: $code\r\nquotecurrency: $base\r\n\r\n");
      $resp = fgets($s, 128);
      if (trim($resp) == "fxp/1.1 200 ok") {
        while ($resp != "\r\n") {
          $resp = fgets($s, 128);
        }
        if (!$resp = fgets($s, 128)) { // timeout? then skip
          $resp = 'na';
        }
      } else {
        $resp = 'na';
      }
      fclose($s);
    }

    return trim($resp);
  }
?>
