<?
  class usps {

// class constructor
    function usps() {
    }

// class methods
    function select() {
      $select_string = '<TR><TD>' . FONT_STYLE_MAIN . '&nbsp;' . SHIPPING_USPS_NAME . '</font></td>' .
                       '<td><select name="shipping_usps_prod">' .
                         '<option value="Parcel">' . SHIPPING_USPS_OPT_PP . '</option>' .
                         '<option value="Priority" SELECTED>' . SHIPPING_USPS_OPT_PM . '</option>' .
                         '<option value="Express">' . SHIPPING_USPS_OPT_EX . '</option>' .
                       '</select></td>' .
                       '<td align="right">&nbsp;<input type="checkbox" name="shipping_quote_usps" value="1" CHECKED></td></tr>' . "\n";

      return $select_string;
    }

    function quote() {
      global $HTTP_POST_VARS, $shipping_quote_usps, $shipping_quote_all, $address_values, $shipping_weight, $rate, $shipping_usps_cost, $shipping_usps_method, $shipping_num_boxes, $shipping_quoted;

      $prod = $HTTP_POST_VARS['shipping_usps_prod'];
      if ( ($shipping_quote_all == '1') || ($shipping_quote_usps) ) {
        $prod = 'Priority';
        include(DIR_WS_CLASSES . '_usps.php');
        $rate = new _USPS;
        $rate->SetServer(SHIPPING_USPS_SERVER);
        $rate->setUserName(SHIPPING_USPS_USERID);
        $rate->setPass(SHIPPING_USPS_PASSWORD);
        $rate->SetService($prod);
        $rate->setMachinable("False");
        $rate->SetOrigZip(STORE_ORIGIN_ZIP);
        $rate->SetDestZip($address_values['postcode']);
        $rate->setWeight($shipping_weight);
        $quote = $rate->getPrice();
        $shipping_usps_cost = SHIPPING_HANDLING + $quote;
        if ($prod != 'Parcel') {
          $shipping_usps_method = 'USPS ' . $prod . ' Mail';
        } else {
          $shipping_usps_method = 'USPS ' . $prod . ' Post';
        }
        $shipping_usps_method = $shipping_usps_method . ' ' . $shipping_num_boxes . ' X ' . $shipping_weight;
        if ($shipping_usps_cost == SHIPPING_HANDLING) {
          $shipping_usps_method = "USPS " . $quote;
        } else {
          $shipping_quoted = 'usps';
          $shipping_usps_cost = $shipping_usps_cost * $shipping_num_boxes;
        }
      }
    }

    function cheapest() {
      global $shipping_quote_usps, $shipping_quote_all, $shipping_count, $shipping_cheapest, $shipping_cheapest_cost, $shipping_usps_cost;

      if ( ($shipping_quote_all == '1') || ($shipping_quote_usps) ) {
        if ($shipping_count == 0) {
          $shipping_cheapest = 'usps';
          $shipping_cheapest_cost = $shipping_usps_cost;
        } else {
          if ($shipping_usps_cost < $shipping_cheapest_cost) {
            $shipping_cheapest = 'usps';
            $shipping_cheapest_cost = $shipping_usps_cost;
          }
        }
        $shipping_count++;
      }
    }

    function display() {
      global $shipping_quote_usps, $shipping_quote_all, $shipping_usps_cost, $shipping_usps_method, $shipping_cheapest;

      if ( ($shipping_quote_all == '1') || ($shipping_quote_usps) ) {
        $display_string = '<tr>' . "\n" .
                          '  <td>' . FONT_STYLE_MAIN . '&nbsp;' . SHIPPING_USPS_NAME . '</font></td>' . "\n" .
                          '  <td>' . FONT_STYLE_MAIN . $shipping_usps_method . '</font></td>' . "\n" .
                          '  <td align="right">' . FONT_STYLE_MAIN . tep_currency_format($shipping_usps_cost) . '</font></td>' . "\n" .
                          '  <td align="right" nowrap>&nbsp;<input type="radio" name="shipping_selected" value="usps"';
        if ($shipping_cheapest == 'usps') $display_string .= ' CHECKED';
        $display_string .= '>&nbsp;</td>' . "\n" .
                           '</tr>' . "\n" .
                           '<input type="hidden" name="shipping_usps_cost" value="' . $shipping_usps_cost . '">' .
                           '<input type="hidden" name="shipping_usps_method" value="' . $shipping_usps_method . '">' . "\n";
      }

      return $display_string;
    }

    function confirm() {
      global $HTTP_POST_VARS, $shipping_cost, $shipping_method;

      if ($HTTP_POST_VARS['shipping_selected'] == 'usps') {
        $shipping_cost = $HTTP_POST_VARS['shipping_usps_cost'];
        $shipping_method = $HTTP_POST_VARS['shipping_usps_method'];
      }
    }

    function check() {
      $check = tep_db_query("select configuration_value from configuration where configuration_key = 'SHIPPING_USPS_ENABLED'");
      $check = tep_db_num_rows($check) + 1;

      return $check;
    }

    function install() {
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enable USPS Shipping', 'SHIPPING_USPS_ENABLED', '1', 'Do you want to offer USPS shipping?', '7', '10', now())");
    }

    function remove() {
      tep_db_query("delete from configuration where configuration_key = 'SHIPPING_USPS_ENABLED'");
    }
  }
?>
