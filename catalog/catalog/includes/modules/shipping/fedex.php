<?php
  class fedex {
    var $fedex_countries, $fedex_countries_nbr;

// class constructor
    function fedex() {
// only these three are needed since FedEx only ships to them
// convert TEP country id to ISO 3166 id
      $this->fedex_countries = array(38 => 'CA', 138 => 'MX', 223 => 'US');
      $this->fedex_countries_nbr = array(38, 138, 223);
    }

// class methods
    function select() {
      $select_string = '<TR><TD>' . FONT_STYLE_MAIN . '&nbsp;' . htmlentities(SHIPPING_FEDEX_NAME) . '</font></td>' .
                       '<td>&nbsp;</td>' .
                       '<td align="right">&nbsp;<input type="checkbox" name="shipping_quote_fedex" value="1" CHECKED></td></tr>' . "\n";

      return $select_string;
    }

    function quote() {
      global $shipping_quote_fedex, $shipping_quote_all, $shipping_quoted, $address_values, $shipping_weight, $shipping_num_boxes, $shipping_fedex_cost, $shipping_fedex_method, $quote;

      if ( ($shipping_quote_all == '1') || ($shipping_quote_fedex) ) {
        $shipping_quoted = 'fedex';
// only calculate if FedEx ships there.
        if (in_array($address_values['country_id'], $this->fedex_countries_nbr)) {
          include(DIR_WS_CLASSES . '_fedex.php');
          $rate = new _FedEx(STORE_ORIGIN_ZIP, STORE_ORIGIN_COUNTRY);
          $rate->SetDest($address_values['postcode'], $this->fedex_countries[$address_values['country_id']]);
          $rate->SetWeight($shipping_weight);
          $quote = $rate->GetQuote();
          $shipping_fedex_cost = $shipping_num_boxes * (SHIPPING_HANDLING + $quote['TotalCharges']);
// clean up the service text a little
          $shipping_fedex_method = str_replace(' Package', '', $quote['Service']);
          $shipping_fedex_method = str_replace(' FedEx', '', $shipping_fedex_method);
          $shipping_fedex_method .= ' ' . $shipping_num_boxes . ' x ' . $shipping_weight;
        } else {
          $quote['ErrorNbr'] = 1;
          $quote['Error'] = SHIPPING_FEDEX_NOTAVAILABLE;
        }
      }
    }

    function cheapest() {
      global $shipping_quote_fedex, $shipping_quote_all, $address_values, $quote, $shipping_count, $shipping_cheapest, $shipping_cheapest_cost, $shipping_fedex_cost;

      if ( ($shipping_quote_all == '1') || ($shipping_quote_fedex) ) {
// only calculate if FedEx ships there.
        if ( (in_array($address_values['country_id'], $this->fedex_countries_nbr)) && (!$quote['ErrorNbr']) ) {
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
      global $shipping_quote_fedex, $shipping_quote_all, $quote, $shipping_fedex_method, $shipping_fedex_cost, $shipping_cheapest;

      $display_string = '';
      if ( ($shipping_quote_all == '1') || ($shipping_quote_fedex) ) {
// check for errors
        if ($quote['ErrorNbr']) {
          $display_string .= '<tr>' . "\n" .
                             '  <td>' . FONT_STYLE_MAIN . '&nbsp;' . htmlentities(SHIPPING_FEDEX_NAME) . '</font></td>' . "\n" .
                             '  <td>' . FONT_STYLE_MAIN . '<font color="#ff0000">Error:</font> ' . htmlentities($quote['Error']) . '</font></td>' . "\n" .
                             '  <td align="right">' . FONT_STYLE_MAIN . '&nbsp;</font></td>' . "\n" .
                             '  <td align="right" nowrap>&nbsp;</td>' . "\n" .
                             '</tr>' . "\n";
        } else {
          $display_string .= '<tr>' . "\n" .
                             '  <td>' . FONT_STYLE_MAIN . '&nbsp;' . htmlentities(SHIPPING_FEDEX_NAME) . '</font></td>' . "\n" .
                             '  <td>' . FONT_STYLE_MAIN . $shipping_fedex_method . '</font></td>' . "\n" .
                             '  <td align="right">' . FONT_STYLE_MAIN . tep_currency_format($shipping_fedex_cost) . '</font></td>' . "\n" .
                             '  <td align="right" nowrap>&nbsp;<input type="radio" name="shipping_selected" value="fedex"';
          if ($shipping_cheapest == 'fedex') {
            $display_string .= ' CHECKED';
          }
          $display_string .= '>&nbsp;</td>' . "\n" .
                             '</tr>' . "\n" .
                             '<input type="hidden" name="shipping_fedex_cost" value="' . $shipping_fedex_cost . '">' .
                             '<input type="hidden" name="shipping_fedex_method" value="' . $shipping_fedex_method . '">' . "\n";
        }
      }

      echo $display_string;
    }

    function confirm() {
      global $HTTP_POST_VARS, $shipping_cost, $shipping_method;

      if ($HTTP_POST_VARS['shipping_selected'] == 'fedex') {
        $shipping_cost = $HTTP_POST_VARS['shipping_fedex_cost'];
        $shipping_method = $HTTP_POST_VARS['shipping_fedex_method'];
      }
    }

    function check() {
      $check = tep_db_query("select configuration_value from configuration where configuration_key = 'SHIPPING_FEDEX_ENABLED'");
      $check = tep_db_num_rows($check) + 1;

      return $check;
    }

    function install() {
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enable FedEx Shipping', 'SHIPPING_FEDEX_ENABLED', '1', 'Do you want to offer Federal Express (FedEx) shipping?', '7', '10', now())");
    }

    function remove() {
      tep_db_query("delete from configuration where configuration_key = 'SHIPPING_FEDEX_ENABLED'");
    }
  }
?>
