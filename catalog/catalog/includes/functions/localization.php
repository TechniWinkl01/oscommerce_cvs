<?php
/*
  $Id: localization.php,v 1.2 2001/06/04 16:29:49 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

////
// If no parameter is passed, this function returns all languages and required information such as id, name, language path, etc
// If the language code is given as a parameter, it returns the same information just for that one language
// TABLES: languages
  function tep_get_languages($language = '') {
    if ($language != '') {
      $languages_query = tep_db_query("select languages_id, name, code, image, directory from " . TABLE_LANGUAGES . " where code = '" . $language . "'");
    } else {
      $languages_query = tep_db_query("select languages_id, name, code, image, directory from " . TABLE_LANGUAGES . " order by sort_order");
    }
    while ($languages = tep_db_fetch_array($languages_query)) {
      $languages_array[] = array('id' => $languages['languages_id'],
                                 'name' => $languages['name'],
                                 'code' => $languages['code'],
                                 'image' => $languages['image'],
                                 'directory' => $languages['directory']
                                );
    }

    return $languages_array;
  }

////
// Format a number to the selected currency
// Note: $currency_rates is taken from includes/data/rates.php
//       $currency is in the session variable
// TABLES: currencies
  function tep_currency_format($number, $calculate_currency_value = true, $currency_type = '', $currency_value = '') {
    global $currency_rates, $currency;

    if ($currency_type == '') {
      $currency_type = $currency;
    }

    $currencies_query = tep_db_query("select symbol_left, symbol_right, decimal_point, thousands_point, decimal_places from " . TABLE_CURRENCIES . " where code = '" . $currency_type . "'");
    $currencies = tep_db_fetch_array($currencies_query);

    if ($calculate_currency_value == true) {
      if (strlen($currency_type) == 3) {
        $rate = $currency_rates[$currency_type]; // read from catalog/includes/data/rates.php - the value is in /catalog/includes/languages/<language>.php
      } else {
        $rate = 1;
      }

      if ($currency_value != '') {
        $rate = $currency_value;
      }

// If the selected currency is in the european euro-conversion and the default currency is euro, the currency will displayed in the national and euro currency.
      if (DEFAULT_CURRENCY=='EUR') {
        if ($currency=='DEM' || $currency=='BEF' || $currency=='LUF' || $currency=='ESP' || $currency=='FRF' || $currency=='IEP' || $currency=='ITL' || $currency=='NLG' || $currency=='ATS' || $currency=='PTE' || $currency=='FIM' || $currency=='GRD') {
          $number2currency = $currencies['symbol_left'] . number_format(($number * $rate), $currencies['decimal_places'], $currencies['decimal_point'], $currencies['thousands_point']) . $currencies['symbol_right'] . ' [€ ' . number_format($number * $currency_rates['EUR'],2) . ']';
        } else {
          $number2currency = $currencies['symbol_left'] . number_format(($number * $rate), $currencies['decimal_places'], $currencies['decimal_point'], $currencies['thousands_point']) . $currencies['symbol_right'];
        }
      } else {
        $number2currency = $currencies['symbol_left'] . number_format($number * $rate, $currencies['decimal_places'], $currencies['decimal_point'], $currencies['thousands_point']) . $currencies['symbol_right'];
      }
    } else {
      $number2currency = $currencies['symbol_left'] . number_format($number, $currencies['decimal_places'], $currencies['decimal_point'], $currencies['thousands_point']) . $currencies['symbol_right'];
    }

    return $number2currency;
  }

////
// Checks to see if the currency code exists as a currency
// TABLES: currencies
  function tep_currency_exists($code) {
    $currency_code = tep_db_query("select currencies_id from " . TABLE_CURRENCIES . " where code = '" . $code . "'");
    if (tep_db_num_rows($currency_code)) {
      return $code;
    } else {
      return false;
    }
  }
?>