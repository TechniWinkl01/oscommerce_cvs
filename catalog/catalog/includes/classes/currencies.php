<?php
/*
  $Id: currencies.php,v 1.19 2004/02/16 07:08:16 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  class osC_Currencies {
    var $currencies;

// class constructor
    function osC_Currencies() {
      global $osC_Database;

      $this->currencies = array();

      $Qcurrencies = $osC_Database->query('select code, title, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value from :table_currencies');
      $Qcurrencies->bindRaw(':table_currencies', TABLE_CURRENCIES);
      $Qcurrencies->execute();

      while ($Qcurrencies->next()) {
        $this->currencies[$Qcurrencies->value('code')] = array('title' => $Qcurrencies->value('title'),
                                                               'symbol_left' => $Qcurrencies->value('symbol_left'),
                                                               'symbol_right' => $Qcurrencies->value('symbol_right'),
                                                               'decimal_point' => $Qcurrencies->value('decimal_point'),
                                                               'thousands_point' => $Qcurrencies->value('thousands_point'),
                                                               'decimal_places' => $Qcurrencies->valueInt('decimal_places'),
                                                               'value' => $Qcurrencies->valueDecimal('value'));
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
