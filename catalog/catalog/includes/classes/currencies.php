<?php
/*
  $Id: currencies.php,v 1.8 2002/03/10 23:21:23 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

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
      $currencies_query = tep_db_query("select code, title, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value from " . TABLE_CURRENCIES);
      while ($currencies = tep_db_fetch_array($currencies_query)) {
	    $this->currencies[$currencies['code']] = array('title' => $currencies['title'],
                                                     'symbol_left' => $currencies['symbol_left'],
                                                     'symbol_right' => $currencies['symbol_right'],
                                                     'decimal_point' => $currencies['decimal_point'],
                                                     'thousands_point' => $currencies['thousands_point'],
                                                     'decimal_places' => $currencies['decimal_places'],
                                                     'value' => $currencies['value']
                                                    );
      }
    }

// class methods
    function format($number, $calculate_currency_value = true, $currency_type = '', $currency_value = '') {
      global $currency;

      if ($currency_type == '') {
        $currency_type = $currency;
      }

      if ($calculate_currency_value) {
        $rate = ($currency_value) ? $currency_value : $this->currencies[$currency_type]['value'];
        $format_string = $this->currencies[$currency_type]['symbol_left'] . number_format($number * $rate, $this->currencies[$currency_type]['decimal_places'], $this->currencies[$currency_type]['decimal_point'], $this->currencies[$currency_type]['thousands_point']) . $this->currencies[$currency_type]['symbol_right'];
// if the selected currency is in the european euro-conversion and the default currency is euro,
// the currency will displayed in the national currency and euro currency
        if ( (DEFAULT_CURRENCY == 'EUR') && ($currency_type == 'DEM' || $currency_type == 'BEF' || $currency_type == 'LUF' || $currency_type == 'ESP' || $currency_type == 'FRF' || $currency_type == 'IEP' || $currency_type == 'ITL' || $currency_type == 'NLG' || $currency_type == 'ATS' || $currency_type == 'PTE' || $currency_type == 'FIM' || $currency_type == 'GRD') ) {
          $format_string .= ' <small>[' . $this->format($number, true, 'EUR') . ']</small>';
        }
      } else {
        $format_string = $this->currencies[$currency_type]['symbol_left'] . number_format($number, $this->currencies[$currency_type]['decimal_places'], $this->currencies[$currency_type]['decimal_point'], $this->currencies[$current_type]['thousands_point']) . $this->currencies[$currency_type]['symbol_right'];
      }

      return $format_string;
    }

    function get_value($code) {
      return $this->currencies[$code]['value'];
    }

    function display_price($products_price, $tax_class_id) {
      global $customer_country_id, $customer_zone_id;

      if (!tep_session_is_registered('customer_id')) {
      	if (DISPLAY_PRICE_WITH_TAX == 'true') {
        $country_id = STORE_COUNTRY;
        $zone_id = STORE_ZONE;
        }
      } elseif (DISPLAY_PRICE_WITH_TAX == 'true') {
        $country_id = $customer_country_id;
        $zone_id = $customer_zone_id;
      } 
      $products_tax = tep_get_tax_rate($country_id, $zone_id, $tax_class_id);
      $products_price = ($products_price * (($products_tax/100)+1));

      $price = $this->format($products_price);

      return $price;
    }

  }
?>