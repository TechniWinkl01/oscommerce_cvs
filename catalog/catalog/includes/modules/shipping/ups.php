<?
  // define('SHIPPING_UPS_NAME', 'United Parcel Service');

  if ($action == 'select') {
?>
              <tr>
                <td>&nbsp<? echo SHIPPING_UPS_NAME; ?></td>
                <td align="right"><SELECT NAME="shipping_ups_prod">
                  <OPTION SELECTED VALUE="GND">UPS Ground</OPTION>
                  <OPTION VALUE="1DM">Next Day Air Early AM</OPTION>
                  <OPTION VALUE="1DA">Next Day Air</OPTION>
                  <OPTION VALUE="1DP">Next Day Air Saver</OPTION>
                  <OPTION VALUE="2DM">2nd Day Air Early AM</OPTION>
                  <OPTION VALUE="3DS">3 Day Select</OPTION>
                  <OPTION VALUE="STD">Canada Standard</OPTION>
                  <OPTION VALUE="XPR">Worldwide Express</OPTION>
                  <OPTION VALUE="XDM">Worldwide Express Plus</OPTION>
                  <OPTION VALUE="XPD">Worldwide Expedited</OPTION>
                  </SELECT><br>
                </td>
                <td>&nbsp<input type="checkbox"  name="shipping_quote_ups" value="1"></td>
             </tr>
<?
  } elseif ($action == 'quote') {
      if ($HTTP_POST_VARS['shipping_quote_ups'] == "1") {
        include('includes/ups.php');
        $rate = new Ups;
        $rate->upsProduct($HTTP_POST_VARS['shipping_ups_prod']);    // See upsProduct() function for codes
        $rate->origin(UPS_ORIGIN_ZIP, "US"); // Use ISO country codes!
        $rate->dest($address_values['postcode'], "US");      // Use ISO country codes!
        // $rate->dest($address_values['postcode'], $address_values['country']);      // Use ISO country codes!
        $rate->rate(UPS_PICKUP);        // See the rate() function for codes
        $rate->container(UPS_PACKAGE);    // See the container() function for codes
        $rate->weight($total_weight);
        $rate->rescom(UPS_RES);    // See the rescom() function for codes
        $shipping_ups_cost = SHIPPING_HANDLING + $rate->getQuote();
        $shipping_ups_method = "UPS " . $HTTP_POST_VARS['shipping_ups_prod'];
        echo "              <tr>\n";
        echo '                <td>&nbsp' . SHIPPING_UPS_NAME . "</td>\n";
        echo '                <td>' . $shipping_ups_method . "</td>\n";
        echo '                <td align="right">' . tep_currency_format($shipping_ups_cost) . "</td>\n";
        echo '                <td>&nbsp<input type="radio" name="shipping_selected" value="ups"';
        if ($shipping_quotes == 0) echo ' CHECKED';
        echo "></td>\n";
        echo "              </tr>\n";
        $shipping_quotes = $shipping_quotes+1;
        echo '              <input type="hidden" name="shipping_ups_cost" value=' . $shipping_ups_cost . ">\n";
        echo '              <input type="hidden" name="shipping_ups_method" value=' . $shipping_ups_method . ">\n";
      }
  } elseif ($action == 'confirm') {
      if ($HTTP_POST_VARS['shipping_selected'] == 'ups') {
        $shipping_cost = $HTTP_POST_VARS['shipping_ups_cost'];
        $shipping_method = $HTTP_POST_VARS['shipping_ups_method'];
      }
  }
?>

