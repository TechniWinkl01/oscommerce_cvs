<?
  function quotecurrency($code, $base = 'USD') {
    $err_num = $err_msg='';
    $s = fsockopen('www.oanda.com', 5011, &$err_num, &$err_msg, 3);
    if (!$s) {
      echo "$err_msg ($err_num)<br>\n";
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
    }
    fclose($s);

    return trim($resp);
  }
?>
