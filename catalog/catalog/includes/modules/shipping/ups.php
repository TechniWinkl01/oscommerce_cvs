<?
  /* $Id: ups.php,v 1.17 2001/02/14 20:43:01 hpdl Exp $ */
  if ($action != 'install' && $action != 'remove' && $action != 'check') { // Only use language for catalog
    $include_file = DIR_LANGUAGES . $language . '/modules/shipping/ups.php';include(DIR_INCLUDES . 'include_once.php');
  }

  if ($action == 'select') {
?>
              <tr>
                <td><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo SHIPPING_UPS_NAME; ?></font></td>
                <td align="right"><SELECT NAME="shipping_ups_prod">
                  <OPTION SELECTED VALUE="GND"><? echo SHIPPING_UPS_OPT_GND; ?></OPTION>
                  <OPTION VALUE="1DM"><? echo SHIPPING_UPS_OPT_1DM; ?></OPTION>
                  <OPTION VALUE="1DA"><? echo SHIPPING_UPS_OPT_1DA; ?></OPTION>
                  <OPTION VALUE="1DP"><? echo SHIPPING_UPS_OPT_1DP; ?></OPTION>
                  <OPTION VALUE="2DM"><? echo SHIPPING_UPS_OPT_2DM; ?></OPTION>
                  <OPTION VALUE="3DS"><? echo SHIPPING_UPS_OPT_3DS; ?></OPTION>
                  <OPTION VALUE="STD"><? echo SHIPPING_UPS_OPT_STD; ?></OPTION>
                  <OPTION VALUE="XPR"><? echo SHIPPING_UPS_OPT_XPR; ?></OPTION>
                  <OPTION VALUE="XDM"><? echo SHIPPING_UPS_OPT_XDM; ?></OPTION>
                  <OPTION VALUE="XPD"><? echo SHIPPING_UPS_OPT_XPD; ?></OPTION>
                  </SELECT><br>
                </td>
                <td align="right">&nbsp;<input type="checkbox"  name="shipping_quote_ups" value="1"
<?
  if ($shipping_count == 0) echo ' CHECKED';
  echo "></td>\n";
?>
             </tr>
<?
  } elseif ($action == 'quote') {
      if ($shipping_quote_ups == "1") {
        include(DIR_CLASSES . 'ups.php');
        $rate = new Ups;
        $rate->upsProduct($HTTP_POST_VARS['shipping_ups_prod']);    // See upsProduct() function for codes
        $rate->origin(STORE_ORIGIN_ZIP, STORE_ORIGIN_COUNTRY); // Use ISO country codes!
        $rate->dest($address_values['postcode'], STORE_ORIGIN_COUNTRY);      // Use ISO country codes!
        // $rate->dest($address_values['postcode'], $address_values['country']);      // Use ISO country codes!
        $rate->rate(SHIPPING_UPS_PICKUP);        // See the rate() function for codes
        $rate->container(SHIPPING_UPS_PACKAGE);    // See the container() function for codes
        $rate->weight($shipping_weight);
        $rate->rescom(SHIPPING_UPS_RES);    // See the rescom() function for codes
        $shipping_ups_quote = $rate->getQuote();
        $shipping_ups_cost = SHIPPING_HANDLING + $shipping_ups_quote;
        $shipping_ups_method = "UPS " . $HTTP_POST_VARS['shipping_ups_prod'] . ' ' . $shipping_num_boxes . ' X ' . $shipping_weight;
        if ($shipping_ups_cost == SHIPPING_HANDLING) $shipping_ups_method = "UPS " . $shipping_ups_quote;
        else {
          $shipping_quoted = 'ups';
          $shipping_ups_cost = $shipping_ups_cost*$shipping_num_boxes;
        }
      }
  } elseif ($action == 'cheapest') {
    if ($shipping_quote_ups == "1") {
      if ($shipping_count == 0) {
        $shipping_cheapest = 'ups';
        $shipping_cheapest_cost = $shipping_ups_cost;
      } else {
        if ($shipping_ups_cost < $shipping_cheapest_cost) {
          $shipping_cheapest = 'ups';
          $shipping_cheapest_cost = $shipping_ups_cost;
        }
      }
    }
  } elseif ($action == 'display') {
      if ($shipping_quote_ups == "1") {
        echo "              <tr>\n";
        echo '                <td><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . SHIPPING_UPS_NAME . "</font></td>\n";
        echo '                <td><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">' . $shipping_ups_method . "</font></td>\n";
        echo '                <td align="right"><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">' . tep_currency_format($shipping_ups_cost) . "</font></td>\n";
        echo '                <td align="right" nowrap>&nbsp;<input type="radio" name="shipping_selected" value="ups"';
        if ($shipping_cheapest == 'ups') echo ' CHECKED';
        echo ">&nbsp;</td>\n";
        echo "              </tr>\n";
        echo '              <input type="hidden" name="shipping_ups_cost" value=' . $shipping_ups_cost . ">\n";
        echo '              <input type="hidden" name="shipping_ups_method" value="' . $shipping_ups_method . "\">\n";
      }
  } elseif ($action == 'confirm') {
      if ($HTTP_POST_VARS['shipping_selected'] == 'ups') {
        $shipping_cost = $HTTP_POST_VARS['shipping_ups_cost'];
        $shipping_method = $HTTP_POST_VARS['shipping_ups_method'];
      }
  } elseif ($action == 'check') {
    $check = tep_db_query("select configuration_value from configuration where configuration_key = 'SHIPPING_UPS_ENABLED'");
    $check = tep_db_num_rows($check) + 1;
  } elseif ($action == 'install') {
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Enable UPS Shipping', 'SHIPPING_UPS_ENABLED', '1', 'Do you want to offer UPS shipping?', '7', '9', now())");
  } elseif ($action == 'remove') {
    tep_db_query("DELETE FROM configuration WHERE configuration_key = 'SHIPPING_UPS_ENABLED'");
  }
  $shipping_count = $shipping_count+1;
?>
