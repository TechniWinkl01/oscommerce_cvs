#!/usr/bin/php -q
<?php
/* original code by Guy N. Hurst
   article available at http://www.zend.com/zend/tut/currency-exchange.php

   code modified by Harald Ponce de Leon
*/

  function querycurrencies($code = '') {
    $codeline=$flag=$err_num=$err_msg='';
    if ($code != '') {
      $codeline = "\r\nquotecurrency: $code";
    } else {
      $flag="all";
    }

    // connect to port 5011 with 5-second timeout
    $s = fsockopen('www.oanda.com', 5011, &$err_num, &$err_msg, 5);
    if (!$s) { // no connection; print or log msg
      echo "$err_msg ($err_num)<br>\n";
      $resp = "na";  // prevent breaking script
    } else { // send request; add extra blank line (\r\n)
      fputs($s,"fxp/1.1\r\nquery: currencies$codeline\r\n\r\n");
      $resp = fgets($s, 128);
      // check response for accepted request
      if (trim($resp) == "fxp/1.1 200 ok") { // skip past first blank line
        while ($resp != "\r\n") {
          $resp = fgets($s, 128);
        }
        // get first line of 'good' data
        $resp = fgets($s, 128);
        // read remaining lines if flag is set
        if ($flag == "all") {
          // read up to next blank line
          while ($resp2 != "\r\n") {
            // need to read lines separately into temp var for checking
            $resp2 = fgets($s, 128);
            // concatenate to the keeper
            $resp .= $resp2;
          }
        }
      } else {
        $resp = "na";
      }
    }
    fclose($s);

    return trim($resp);
  }

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
        print "$resp\n";
      } else {
        $resp = 'na';
      }
    }
    fclose($s);

    return trim($resp);
  }

// path to writable file
  $ratesfile = '/usr/local/apache/htdocs/devtep/catalog/catalog/includes/data/rates.php';

// get list of available currencies and store as array
  $currencies = split("\n", querycurrencies());
  $count = count($currencies); // store count for use in loop

// open file for writing
  $fp = fopen($ratesfile, "w");
// we are writing PHP code into it
  fputs($fp, '<?php' . "\n");
  fputs($fp, '$currency_rates = array(' . "\n");

// loop through all elements in your array of currencies
  for ($i=0; $i<$count; $i++) {
    // first three letters are the valid currency code
    $c = substr($currencies[$i], 0, 3);
    // get rate for this currency code with USD as base
    $new_rate = quotecurrency($c);
    // write code to file; pay attention to escapes
    fputs($fp, '\'' . $c . '\' => \'' . $new_rate . '\',' . "\n");
  }

// finishing touches
  fputs($fp, '\'DUMMY\', \'\'' . "\n");
  fputs($fp, ');' . "\n");
  fputs($fp, '?>' . "\n");
  fclose($fp);

  echo "$i currencies updated.\n";
?>
