<?php
/*
  $Id: currencies.php,v 1.18 2003/12/18 23:52:14 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class osC_Currencies {
    var $currencies;

// class constructor
    function osC_Currencies() {
      $this->currencies = array();
      $currencies_query = tep_db_query("select code, title, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value from " . TABLE_CURRENCIES);
      while ($currencies = tep_db_fetch_array($currencies_query)) {
        $this->currencies[$currencies['code']] = array('title' => $currencies['title'],
                                                       'symbol_left' => $currencies['symbol_left'],
                                                       'symbol_right' => $currencies['symbol_right'],
                                                       'decimal_point' => $currencies['decimal_point'],
                                                       'thousands_point' => $currencies['thousands_point'],
                                                       'decimal_places' => $currencies['decimal_places'],
                                                       'value' => $currencies['value']);
      }
    }

// class methods
    function format($number, $currency_code = '', $currency_value = '') {
      global $osC_Session;

      if (empty($currency_code) || ($this->exists($currency_code) == false)) {
        $currency_code = $osC_Session->value('currency');
      }

      if (empty($currency_value) || (is_numeric($currency_value) == false)) {
        $currency_value = $this->currencies[$currency_code]['value'];
      }

      return $this->currencies[$currency_code]['symbol_left'] . number_format(tep_round($number * $currency_value, $this->currencies[$currency_code]['decimal_places']), $this->currencies[$currency_code]['decimal_places'], $this->currencies[$currency_code]['decimal_point'], $this->currencies[$currency_code]['thousands_point']) . $this->currencies[$currency_code]['symbol_right'];
    }

    function displayPrice($price, $tax_class_id, $quantity = 1) {
      global $osC_Tax;

      $price = tep_round($price, $this->currencies[DEFAULT_CURRENCY]['decimal_places']);

      if ( (DISPLAY_PRICE_WITH_TAX == 'true') && ($tax_class_id > 0) ) {
        $price += tep_round($price * ($osC_Tax->getTaxRate($tax_class_id) / 100), $this->currencies[DEFAULT_CURRENCY]['decimal_places']);
      }

      return $this->format($price * $quantity);
    }

    function exists($code) {
      if (isset($this->currencies[$code])) {
        return true;
      }

      return false;
    }

    function decimalPlaces($code) {
      if ($this->exists($code)) {
        return $this->currencies[$code]['decimal_places'];
      }

      return false;
    }

    function value($code) {
      if ($this->exists($code)) {
        return $this->currencies[$code]['value'];
      }

      return false;
    }
  }
?>
