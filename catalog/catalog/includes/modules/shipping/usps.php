<?
  $include_file = DIR_LANGUAGES . $language . '/modules/shipping/usps.php';include(DIR_INCLUDES . 'include_once.php');

  if ($action == 'select') {
?>
              <tr>
                <td><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo SHIPPING_USPS_NAME; ?></font></td>
                <td align="right"><SELECT NAME="shipping_usps_prod">
                  <OPTION VALUE="Parcel"><? echo SHIPPING_USPS_OPT_PP; ?></OPTION>
                  <OPTION SELECTED VALUE="Priority"><? echo SHIPPING_USPS_OPT_PM; ?></OPTION>
                  <OPTION VALUE="Express"><? echo SHIPPING_USPS_OPT_EX; ?></OPTION>
                  </SELECT><br>
                </td>
                <td align="right">&nbsp;<input type="checkbox"  name="shipping_quote_usps" value="1"
<?
  if ($shipping_count == 0) echo ' CHECKED';
  echo "></td>\n";
?>
             </tr>
<?
  } elseif ($action == 'quote') {
// Example
// $usps = new USPS;
// $usps-> setServer($server) {
// $usps-> setUserName($user) {
// $usps-> setPass($pass) {
// $usps->setDestZip($zip);
// $usps->setOrigZip($vendor_zip);
// $usps->setWeight($pounds, $ounces);
// $price = $usps->getPrice();
      if ($shipping_quote_usps == "1") {
        include('includes/usps.php');
        $rate = new USPS;
        $rate->SetServer(SHIPPING_USPS_SERVER);
        $rate->setUserName(SHIPPING_USPS_USERID);
        $rate->setPass(SHIPPING_USPS_PASSWORD);
        $rate->SetService($HTTP_POST_VARS['shipping_usps_prod']);
        $rate->setMachinable("False");
        $rate->SetOrigZip(STORE_ORIGIN_ZIP);
        $rate->SetDestZip($address_values['postcode']);
        $rate->setWeight($shipping_weight);
        $shipping_usps_quote = $rate->getPrice();
        $shipping_usps_cost = SHIPPING_HANDLING + $shipping_usps_quote;
        if ($HTTP_POST_VARS['shipping_usps_prod'] != 'Parcel') {
          $shipping_usps_method = 'USPS ' . $HTTP_POST_VARS['shipping_usps_prod'] . ' Mail';
        } else $shipping_usps_method = 'USPS ' . $HTTP_POST_VARS['shipping_usps_prod'] . ' Post';
        $shipping_usps_method = $shipping_usps_method . ' ' . $shipping_num_boxes . ' X ' . $shipping_weight;
        if ($shipping_usps_cost == SHIPPING_HANDLING) $shipping_usps_method = "USPS " . $shipping_usps_quote;
        else {
          $shipping_quoted = 'usps';
          $shipping_usps_cost = $shipping_usps_cost*$shipping_num_boxes;
        }
      }
  } elseif ($action == 'cheapest') {
    if ($shipping_quote_usps == "1") {
      if ($shipping_count == 0) {
        $shipping_cheapest = 'usps';
        $shipping_cheapest_cost = $shipping_usps_cost;
      } else {
        if ($shipping_usps_cost < $shipping_cheapest_cost) {
          $shipping_cheapest = 'usps';
          $shipping_cheapest_cost = $shipping_usps_cost;
        }
      }
    }
  } elseif ($action == 'display') {
      if ($shipping_quote_usps == "1") {
        echo "              <tr>\n";
        echo '                <td><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . SHIPPING_USPS_NAME . "</font></td>\n";
        echo '                <td><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">' . $shipping_usps_method . "</font></td>\n";
        echo '                <td align="right"><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">' . tep_currency_format($shipping_usps_cost) . "</font></td>\n";
        echo '                <td align="right" nowrap>&nbsp;<input type="radio" name="shipping_selected" value="usps"';
        if ($shipping_cheapest == 'usps') echo ' CHECKED';
        echo ">&nbsp;</td>\n";
        echo "              </tr>\n";
        echo '              <input type="hidden" name="shipping_usps_cost" value=' . $shipping_usps_cost . ">\n";
        echo '              <input type="hidden" name="shipping_usps_method" value="' . $shipping_usps_method . "\">\n";
      }
  } elseif ($action == 'confirm') {
      if ($HTTP_POST_VARS['shipping_selected'] == 'usps') {
        $shipping_cost = $HTTP_POST_VARS['shipping_usps_cost'];
        $shipping_method = $HTTP_POST_VARS['shipping_usps_method'];
      }
  } elseif ($action == 'check') {
    $check = tep_db_query("select configuration_value from configuration where configuration_key = 'SHIPPING_USPS_ENABLED'");
    $check = tep_db_num_rows($check) + 1;
  } elseif ($action == 'install') {
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Enable USPS Shipping', 'SHIPPING_USPS_ENABLED', '1', 'Do you want to offer USPS shipping?', '7', '10', now())");
  } elseif ($action == 'remove') {
    tep_db_query("DELETE FROM configuration WHERE configuration_key = 'SHIPPING_USPS_ENABLED'");
  }
  $shipping_count = $shipping_count+1;
?>
