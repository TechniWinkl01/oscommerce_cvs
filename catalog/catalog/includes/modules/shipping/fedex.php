<?php
/*
  $Id: fedex.php,v 1.35 2002/08/29 02:00:54 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  class fedex {
    var $code, $title, $description, $icon, $enabled, $countries;

// class constructor
    function fedex() {
      global $address_values;

      $this->code = 'fedex';
      $this->title = MODULE_SHIPPING_FEDEX_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_FEDEX_TEXT_DESCRIPTION;
      $this->icon = DIR_WS_ICONS . 'shipping_fedex.gif';
      $this->enabled = MODULE_SHIPPING_FEDEX_STATUS;

      $this->countries = $this->country_list();
      $country_query = tep_db_query("select countries_iso_code_2 from " . TABLE_COUNTRIES . " where countries_id = '" . tep_db_input($address_values['country_id']) . "'");
      $country = tep_db_fetch_array($country_query);
      $this->dest_iso_code = $country['countries_iso_code_2'];
    }

// class methods
    function selection() {
      $selection_string = '<table border="0" cellspacing="0" cellpadding="0" width="100%">' . "\n" .
                          '  <tr>' . "\n" .
                          '    <td class="main">' . (($this->icon) ? tep_image($this->icon, $this->title) : '') . ' ' . MODULE_SHIPPING_FEDEX_TEXT_TITLE . '</td>' . "\n" .
                          '    <td align="right" class="main">' . tep_draw_checkbox_field('shipping_quote_fedex', '1', true) . '</td>' . "\n" .
                          '  </tr>' . "\n" .
                          '</table>' . "\n";

      return $selection_string;
    }

    function quote() {
      global $shipping_quoted, $address_values, $shipping_weight, $shipping_num_boxes, $shipping_fedex_cost, $shipping_fedex_method, $quote;

      if ( ($GLOBALS['shipping_quote_all'] == '1') || ($GLOBALS['shipping_quote_fedex'] == '1') ) {
        $shipping_quoted = 'fedex';
// only calculate if FedEx ships there.
        if (isset($this->countries[$this->dest_iso_code])) {
          include(DIR_WS_CLASSES . '_fedex.php');
          $rate = new _FedEx(STORE_ORIGIN_ZIP, STORE_ORIGIN_COUNTRY);
          $rate->SetDest($address_values['postcode'], $this->countries[$this->dest_iso_code]);
// fedex doesnt accept weights below one
          $rate->SetWeight($shipping_weight);
          $quote = $rate->GetQuote();
          if (!isset($quote['Error'])) {
            $shipping_fedex_cost = $shipping_num_boxes * (SHIPPING_HANDLING + $quote[0]['TotalCharges']);
// clean up the service text a little
            $shipping_fedex_method = $quote[0]['Service'] . ' (' . $shipping_num_boxes . ' x ' . ($shipping_weight < 1 ? 1 : $shipping_weight) . $rate->WeightUnit . ')';
          }
        } else {
          $quote['ErrorNbr'] = 1;
          $quote['Error'] = MODULE_SHIPPING_FEDEX_TEXT_NOTAVAILABLE;
        }
      }
    }

    function cheapest() {
      global $address_values, $quote, $shipping_count, $shipping_cheapest, $shipping_cheapest_cost, $shipping_fedex_cost;

      if ( ($GLOBALS['shipping_quote_all'] == '1') || ($GLOBALS['shipping_quote_fedex'] == '1') ) {
// only calculate if FedEx ships there.
        if ( (isset($this->countries[$this->dest_iso_code])) && (!$quote['ErrorNbr']) ) {
          if ($shipping_count == 0) {
            $shipping_cheapest = 'fedex';
            $shipping_cheapest_cost = $shipping_fedex_cost;
          }	else {
            if ($shipping_fedex_cost < $shipping_cheapest_cost) {
              $shipping_cheapest = 'fedex';
              $shipping_cheapest_cost = $shipping_fedex_cost;
            }
          }
        }
        $shipping_count++;
      }
    }

    function display() {
      global $HTTP_GET_VARS, $currencies, $quote, $shipping_fedex_method, $shipping_fedex_cost, $shipping_cheapest, $shipping_selected;

// set a global for the radio field (auto select cheapest shipping method)
      if (!$shipping_selected) $shipping_selected = $shipping_cheapest;

      $display_string = '';
      if ( ($GLOBALS['shipping_quote_all'] == '1') || ($GLOBALS['shipping_quote_fedex'] == '1') ) {
// check for errors
        if ($quote['ErrorNbr']) {
          $display_string .= '<table border="0" width="100%" cellspacing="0" cellpadding="0">' . "\n" .
                             '  <tr>' . "\n" .
                             '    <td class="main">' . (($this->icon) ? tep_image($this->icon, $this->title) : '') . ' ' . MODULE_SHIPPING_FEDEX_TEXT_TITLE . '</td>' . "\n" .
                             '    <td class="main"><font color="#ff0000">Error:</font> ' . $quote['Error'] . '</td>' . "\n" .
                             '  </tr>' . "\n" .
                             '</table>' . "\n";
        } else {
          $display_string .= '<table border="0" width="100%" cellspacing="0" cellpadding="0">' . "\n" .
                             '  <tr>' . "\n" .
                             '    <td class="main">' . (($this->icon) ? tep_image($this->icon, $this->title) : '') . ' ' . $shipping_fedex_method . '</td>' . "\n" .
                             '    <td align="right" class="main">' . $currencies->format($shipping_fedex_cost);
          if (tep_count_shipping_modules() > 1) {
            $display_string .= tep_draw_radio_field('shipping_selected', 'fedex') .
                               tep_draw_hidden_field('shipping_fedex_cost', $shipping_fedex_cost) .
                               tep_draw_hidden_field('shipping_fedex_method', $shipping_fedex_method) . '</td>' . "\n";
          } else {
            $display_string .= tep_draw_hidden_field('shipping_selected', 'fedex') .
                               tep_draw_hidden_field('shipping_fedex_cost', $shipping_fedex_cost) .
                               tep_draw_hidden_field('shipping_fedex_method', $shipping_fedex_method) . '</td>' . "\n";
          }
          $display_string .= '  </tr>' . "\n" .
                             '</table>' . "\n";
        }
      }

      return $display_string;
    }

    function confirm() {
      global $HTTP_POST_VARS, $shipping_cost, $shipping_method, $shipping_selected;

      if ($shipping_selected == 'fedex') {
        $shipping_cost = $HTTP_POST_VARS['shipping_fedex_cost'];
        $shipping_method = $HTTP_POST_VARS['shipping_fedex_method'];
      }
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_FEDEX_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enable FedEx Shipping', 'MODULE_SHIPPING_FEDEX_STATUS', '1', 'Do you want to offer Federal Express (FedEx) shipping?', '6', '10', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_FEDEX_STATUS'");
    }

    function keys() {
      return array('MODULE_SHIPPING_FEDEX_STATUS');
    }

    function country_list() {
      $list = array('AL' => 'Albania',
                    'DZ' => 'Algeria',
                    'AS' => 'American Samoa',
                    'AD' => 'Andorra',
                    'AO' => 'Angola',
                    'AI' => 'Anguilla',
                    'AG' => 'Antigua',
                    'AR' => 'Argentina',
                    'AM' => 'Armenia',
                    'AW' => 'Aruba',
                    'AU' => 'Australia',
                    'AT' => 'Austria',
                    'AZ' => 'Azerbaijan',
                    'BS' => 'Bahamas',
                    'BH' => 'Bahrain',
                    'BD' => 'Bangladesh',
                    'BB' => 'Barbados',
                    'BY' => 'Belarus',
                    'BE' => 'Belgium',
                    'BZ' => 'Belize',
                    'BJ' => 'Benin',
                    'BM' => 'Bermuda',
                    'BT' => 'Bhutan',
                    'BO' => 'Bolivia',
                    'BW' => 'Botswana',
                    'BR' => 'Brazil',
                    'VG' => 'British Virgin Islands',
                    'BN' => 'Brunei',
                    'BG' => 'Bulgaria',
                    'BF' => 'Burkina Faso',
                    'BI' => 'Burundi',
                    'KH' => 'Cambodia',
                    'CM' => 'Cameroon',
                    'CA' => 'Canada',
                    'CV' => 'Cape Verde',
                    'KY' => 'Cayman Islands',
                    'TD' => 'Chad',
                    'CL' => 'Chile',
                    'CN' => 'China',
                    'CO' => 'Colombia',
                    'CG' => 'Congo Brazzaville',
                    'ZR' => 'Congo Democratic Republic of',
                    'CK' => 'Cook Islands',
                    'CR' => 'Costa Rica',
                    'HR' => 'Croatia',
                    'CY' => 'Cyprus',
                    'CZ' => 'Czech Republic',
                    'DK' => 'Denmark',
                    'DJ' => 'Djibouti',
                    'DM' => 'Dominica',
                    'DO' => 'Dominican Republic',
                    'EC' => 'Ecuador',
                    'EG' => 'Egypt',
                    'SV' => 'El Savador',
                    'GQ' => 'Equatorial Guinea',
                    'ER' => 'Eritrea',
                    'EE' => 'Estonia',
                    'ET' => 'Ethiopia',
                    'FO' => 'Faroe Islands',
                    'FJ' => 'Fiji',
                    'FI' => 'Finland',
                    'FR' => 'France',
                    'GF' => 'French Guiana',
                    'PF' => 'French Polynesia',
                    'GA' => 'Gabon',
                    'GM' => 'Gambia',
                    'GE' => 'Georgia',
                    'DE' => 'Germany',
                    'GH' => 'Ghana',
                    'GI' => 'Gibraltar',
                    'GR' => 'Greece',
                    'GL' => 'Greenland',
                    'GD' => 'Grenada',
                    'GP' => 'Guadeloupe',
                    'GU' => 'Guam',
                    'GT' => 'Guatemala',
                    'GN' => 'Guinea',
                    'GY' => 'Guyana',
                    'HT' => 'Haiti',
                    'HN' => 'Honduras',
                    'HK' => 'Hong Kong',
                    'HU' => 'Hungary',
                    'IS' => 'Iceland',
                    'IN' => 'India',
                    'ID' => 'Indonesia',
                    'IE' => 'Ireland',
                    'IL' => 'Israel',
                    'IT' => 'Italy',
                    'CI' => 'Ivory Coast',
                    'JM' => 'Jamaica',
                    'JP' => 'Japan',
                    'JO' => 'Jordan',
                    'KZ' => 'Kazakhstan',
                    'KE' => 'Kenya',
                    'KW' => 'Kuwait',
                    'KG' => 'Kyrgyzstan',
                    'LA' => 'Laos',
                    'LV' => 'Latvia',
                    'LB' => 'Lebanon',
                    'LS' => 'Lesotho',
                    'LR' => 'Liberia',
                    'LI' => 'Liechtenstein',
                    'LT' => 'Lithuania',
                    'LU' => 'Luxembourg',
                    'MO' => 'Macau',
                    'MK' => 'Macedonia',
                    'MG' => 'Madagascar',
                    'MW' => 'Malawai',
                    'MY' => 'Malaysia',
                    'MV' => 'Maldives',
                    'ML' => 'Mali',
                    'MT' => 'Malta',
                    'MH' => 'Marschall Islands',
                    'MQ' => 'Martinique',
                    'MR' => 'Mauritania',
                    'MU' => 'Mauritius',
                    'MX' => 'Mexico',
                    'FM' => 'Micronesia',
                    'MD' => 'Moldova',
                    'MC' => 'Monaco',
                    'MN' => 'Mongolia',
                    'MS' => 'Montserrat',
                    'MA' => 'Morocco',
                    'MZ' => 'Mozambique',
                    'NA' => 'Namibia',
                    'NP' => 'Nepal',
                    'NL' => 'Netherlands',
                    'AN' => 'Netherlands Antilles',
                    'NC' => 'New Caledonia',
                    'NZ' => 'New Zealand',
                    'NI' => 'Nicaragua',
                    'NE' => 'Niger',
                    'NG' => 'Nigeria',
                    'NO' => 'Norway',
                    'OM' => 'Oman',
                    'PK' => 'Pakistan',
                    'PW' => 'Palau',
                    'PA' => 'Panama',
                    'PG' => 'Papua New Guinea',
                    'PY' => 'Paraguay',
                    'PE' => 'Peru',
                    'PH' => 'Philippines',
                    'PL' => 'Poland',
                    'PT' => 'Portugal',
                    'PR' => 'Puerto Rico',
                    'QA' => 'Qatar',
                    'RE' => 'Reunion',
                    'RO' => 'Romania',
                    'RU' => 'Russian Federation',
                    'RW' => 'Rwanda',
                    'MP' => 'Saipan',
                    'SM' => 'San Marino',
                    'SA' => 'Saudi Arabia',
                    'SN' => 'Senegal',
                    'SC' => 'Seychelles',
                    'SL' => 'Sierra Leone',
                    'SG' => 'Singapore',
                    'SK' => 'Slovak Republic',
                    'SI' => 'Slovenia',
                    'ZA' => 'South Africa',
                    'KR' => 'South Korea',
                    'ES' => 'Spain',
                    'LK' => 'Sri Lanka',
                    'KN' => 'St. Kitts/Nevis',
                    'LC' => 'St. Lucia',
                    'VC' => 'St. Vincent',
                    'SR' => 'Suriname',
                    'SZ' => 'Swaziland',
                    'SE' => 'Sweden',
                    'CH' => 'Switzerland',
                    'SY' => 'Syria',
                    'TW' => 'Taiwan',
                    'TZ' => 'Tanzania',
                    'TH' => 'Thailand',
                    'TG' => 'Togo',
                    'TT' => 'Trinidad/Tobago',
                    'TN' => 'Tunisia',
                    'TR' => 'Turkey',
                    'TM' => 'Turkmenistan',
                    'TC' => 'Turks & Caicos Islands',
                    'VI' => 'U.S. Virgin Islands',
                    'UG' => 'Uganda',
                    'UA' => 'Ukraine',
                    'AE' => 'United Arab Emirates',
                    'GB' => 'United Kingdom',
                    'UY' => 'Uruguay',
                    'US' => 'U.S.A.',
                    'UZ' => 'Uzbekistan',
                    'VU' => 'Vanuatu',
                    'VA' => 'Vatican City',
                    'VE' => 'Venezuela',
                    'VN' => 'Vietnam',
                    'WF' => 'Wallis & Futuna',
                    'YE' => 'Yemen',
                    'ZM' => 'Zambia',
                    'ZW' => 'Zimbabwe');

      return $list;
    }
  }
?>
