<?php
/*
  $Id: currencies.php,v 1.1 2001/09/04 19:23:00 dwatkins Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

//// Auxiliary class used by the currencies class
  class currencies_info {
    var $symbol_left, $decimal_point, $thousands_point, $decimal_places, $symbol_right;

    function currencies_info($row) {
      $this->symbol_left = $row['symbol_left'];
      $this->decimal_point = $row['decimal_point'];
      $this->thousands_point = $row['thousands_point'];
      $this->decimal_places = $row['decimal_places'];
      $this->symbol_right = $row['symbol_right'];
    }

  }

//// Class to handle currencies
// The constructor loads the data from the table
// The only method is used to display currencies
// TABLES: currencies
  class currencies {
    var $currencies;

// class constructor
    function currencies() {
      $this->currencies = array();
      $currencies_query = tep_db_query("select code, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places from " . TABLE_CURRENCIES);
      while ($currencies_values = tep_db_fetch_array($currencies_query)) {
	    $this->currencies[$currencies_values['code']] = new currencies_info($currencies_values);
      }
    }

// class methods
    function format($number, $calculate_currency_value = true, $currency_type = '', $currency_value = '') {
      global $currency_rates, $currency, $currencies;

      if ($currency_type == '') {
        $currency_type = $currency;
      }

      if ($calculate_currency_value == true) {
        if (strlen($currency_type) == 3) {
          $rate = $currency_rates[$currency_type]; // read from catalog/includes/data/rates.php - the value is in /catalog/includes/languages/<language>.php
        } else {
          $rate = 1;
        }

        if ($currency_value != '') {
          $rate = $currency_value;
        }

        $current = $this->currencies[$currency_type];
        $number2currency = $current->symbol_left . number_format($number * $rate, $current->decimal_places, $current->decimal_point, $current->thousands_point) . $current->symbol_right;
// If the selected currency is in the european euro-conversion and the default currency is euro, the currency will displayed in the national and euro currency.
        if ( (DEFAULT_CURRENCY=='EUR') && ($currency=='DEM' || $currency=='BEF' || $currency=='LUF' || $currency=='ESP' || $currency=='FRF' || $currency=='IEP' || $currency=='ITL' || $currency=='NLG' || $currency=='ATS' || $currency=='PTE' || $currency=='FIM' || $currency=='GRD') ) {
          $euro = $this->currencies['EUR'];
          $number2currency .= ' [' . $euro->symbol_left . number_format($number * $currency_rates['EUR'], $euro->decimal_places, $euro->decimal_point, $euro->thousands_point) . $euro->symbol_right . ']';
        }
      } else {
        $current = $this->currencies[$currency_type];
	    $number2currency = $current->symbol_left . number_format($number, $current->decimal_places, $current->decimal_point, $current->thousands_point) . $current->symbol_right;
      }

      return $number2currency;
    }

  }
?>