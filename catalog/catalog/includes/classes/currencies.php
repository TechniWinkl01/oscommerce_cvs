<?php
/*
  $Id: currencies.php,v 1.3 2001/09/14 21:12:44 dwatkins Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

////
// Class to handle currencies
// TABLES: currencies
  class currencies {
    var $currencies;

// class constructor
    function currencies() {
      $this->currencies = array();
      $currencies_query = tep_db_query("select code, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places from " . TABLE_CURRENCIES);
      while ($currencies = tep_db_fetch_array($currencies_query)) {
	    $this->currencies[$currencies['code']] = array('symbol_left' => $currencies['symbol_left'],
                                                       'symbol_right' => $currencies['symbol_right'],
                                                       'decimal_point' => $currencies['decimal_point'],
                                                       'thousands_point' => $currencies['thousands_point'],
                                                       'decimal_places' => $currencies['decimal_places']
                                                      );
      }
    }

// class methods
    function format($number, $calculate_currency_value = true, $currency_type = '', $currency_value = '') {
      global $currency_rates, $currency;

      if ($currency_type == '') {
        $currency_type = $currency;
      }

      if ($calculate_currency_value) {
        if ($currency_value) {
          $rate = $currency_value;
        } else {
// read from catalog/includes/data/rates.php
          $rate = ($currency_rates[$currency_type]) ? $currency_rates[$currency_type] : '1';
        }

        $format_string = $this->currencies[$currency_type]['symbol_left'] . number_format($number * $rate, $this->currencies[$currency_type]['decimal_places'], $this->currencies[$currency_type]['decimal_point'], $this->currencies[$current_type]['thousands_point']) . $this->currencies[$currency_type]['symbol_right'];
// if the selected currency is in the european euro-conversion and the default currency is euro,
// the currency will displayed in the national currency and euro currency
        if ( (DEFAULT_CURRENCY == 'EUR') && ($currency == 'DEM' || $currency == 'BEF' || $currency == 'LUF' || $currency == 'ESP' || $currency == 'FRF' || $currency == 'IEP' || $currency == 'ITL' || $currency == 'NLG' || $currency == 'ATS' || $currency == 'PTE' || $currency == 'FIM' || $currency == 'GRD') ) {
          $format_string .= ' <small>[' . $this->currencies[DEFAULT_CURRENCY]['symbol_left'] . number_format($number * $currency_rates[DEFAULT_CURRENCY], $this->currencies[DEFAULT_CURRENCY]['decimal_places'], $this->currencies[DEFAULT_CURRENCY]['decimal_point'], $this->currencies[DEFAULT_CURRENCY]['thousands_point']) . $this->currencies[DEFAULT_CURRENCY]['symbol_right'] . ']</small>';
        }
      } else {
        $format_string = $this->currencies[$currency_type]['symbol_left'] . number_format($number, $this->currencies[$currency_type]['decimal_places'], $this->currencies[$currency_type]['decimal_point'], $this->currencies[$current_type]['thousands_point']) . $this->currencies[$currency_type]['symbol_right'];
      }

      return $format_string;
    }

  }
?>