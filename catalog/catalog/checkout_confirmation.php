<? include('includes/application_top.php')?>
<?
  if (!tep_session_is_registered('customer_id')) {
    header('Location: ' . tep_href_link(FILENAME_LOGIN, 'origin=' . FILENAME_SHOPPING_CART, 'NONSSL'));
    tep_exit();
  }

// Stock Check
  if (STOCK_CHECK) {
    $products = $cart->get_products();
    for ($i=0; $i<sizeof($products); $i++) {
      $products_name = $products[$i]['name'];
      $products_id = $products[$i]['id'];
      check_stock($products[$i]['id'], $products[$i]['quantity']);
    }
    if (STOCK_ALLOW_CHECKOUT) {
    } else {
      if ($any_out_of_stock) {
        // Out of Stock
        header('Location: ' . tep_href_link(FILENAME_SHOPPING_CART, 'origin=' . FILENAME_CHECKOUT_ADDRESS . '&connection=' . $connection, 'NONSSL'));
        tep_exit();
      }
    }
  }

  $include_file = DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_CONFIRMATION; include(DIR_WS_INCLUDES . 'include_once.php');
  $location = ' : <a href="' . tep_href_link(FILENAME_CHECKOUT_ADDRESS, '', 'SSL') . '" class="whitelink">' . NAVBAR_TITLE_1 . '</a> : ' . NAVBAR_TITLE_2;

// load shipping modules as objects
  include(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping;

// load payment modules as objects
  include(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment;
?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_WS_INCLUDES . 'header.php';  include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_WS_INCLUDES . 'column_left.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td width="100%" class="topBarTitle">&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right">&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'table_background_confirmation.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
    </table><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td align="center" class="tableHeading">&nbsp;<? echo TABLE_HEADING_QUANTITY; ?>&nbsp;</td>
            <td class="tableHeading">&nbsp;<? echo TABLE_HEADING_PRODUCTS; ?>&nbsp;</td>
            <td align="center" class="tableHeading">&nbsp;<? echo TABLE_HEADING_TAX; ?>&nbsp;</td>
            <td align="right" class="tableHeading">&nbsp;<? echo TABLE_HEADING_TOTAL; ?>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4"><? echo tep_black_line(); ?></td>
          </tr>
<?
  if ($HTTP_POST_VARS['sendto'] == '0') {
    $address = tep_db_query("select customers_firstname as firstname, customers_lastname as lastname, customers_street_address as street_address, customers_suburb as suburb, customers_postcode as postcode, customers_city as city, customers_zone_id as zone_id, customers_country_id as country_id, customers_state as state from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
  } else {
    $address = tep_db_query("select entry_firstname as firstname, entry_lastname as lastname, entry_street_address as street_address, entry_suburb as suburb, entry_postcode as postcode, entry_city as city, entry_zone_id as zone_id, entry_country_id as country_id, entry_state as state from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . $HTTP_POST_VARS['sendto'] . "'");
  }
  $address_values = tep_db_fetch_array($address);
  $total_cost = 0;
  $total_tax = 0;
  $total_weight = 0;
  $products = $cart->get_products();
  for ($i=0; $i<sizeof($products); $i++) {
    $products_name = $products[$i]['name'];
    $products_price = $products[$i]['price'];
    $total_products_price = ($products_price + $cart->attributes_price($products[$i]['id']));
    $products_tax = tep_get_tax_rate($address_values['zone_id'], $products[$i]['tax_class_id']);
    $products_weight = $products[$i]['weight'];

    echo '          <tr>' . "\n";
    echo '            <td align="center" valign="top" class="main">&nbsp;' . $products[$i]['quantity'] . '&nbsp;</td>' . "\n";
    echo '            <td valign="top" class="main"><b>&nbsp;' . $products_name . '&nbsp;</b>';

      if (STOCK_CHECK) {
      echo check_stock ($products[$i]['id'], $products[$i]['quantity']);
      }

    //------display customer choosen option --------
    $attributes_exist = '0';
    if ($products[$i]['attributes']) {
      $attributes_exist = '1';
      reset($products[$i]['attributes']);
      while (list($option, $value) = each($products[$i]['attributes'])) {
        $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $products[$i]['id'] . "' and pa.options_id = '" . $option . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $value . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
        $attributes_values = tep_db_fetch_array($attributes);
        echo '<br><small><i>&nbsp;-&nbsp;' . $attributes_values['products_options_name'] . '&nbsp;:&nbsp;' . $attributes_values['products_options_values_name'] . '</i></small>';
      }
    }
//------display customer choosen option eof-----
    echo '</td>' . "\n";
    echo '            <td align="center" valign="top" class="main">&nbsp;' . number_format($products_tax, TAX_DECIMAL_PLACES) . '%&nbsp;</td>' . "\n";
    echo '            <td align="right" valign="top" class="main">&nbsp;<b>' . tep_currency_format($products[$i]['quantity'] * $products_price) . '</b>&nbsp;';
//------display customer choosen option --------
    if ($attributes_exist == '1') {
      reset($products[$i]['attributes']);
      while (list($option, $value) = each($products[$i]['attributes'])) {
        $attributes = tep_db_query("select pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $products[$i]['id'] . "' and pa.options_id = '" . $option . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $value . "' and pa.options_values_id = poval.products_options_values_id");
        $attributes_values = tep_db_fetch_array($attributes);
        if ($attributes_values['options_values_price'] != '0') {
          echo '<br><small><i>' . $attributes_values['price_prefix'] . tep_currency_format($products[$i]['quantity'] * $attributes_values['options_values_price']) . '</i></small>&nbsp;';
        } else {
          echo '<br>&nbsp;';
        }
      }
    }
//------display customer choosen option eof-----
    echo '</td>' . "\n";
    echo '</tr>' . "\n";

    $total_weight += ($products[$i]['quantity'] * $products_weight);
    if (TAX_INCLUDE == true) {
      $total_tax += (($total_products_price * $products[$i]['quantity']) - (($total_products_price * $products[$i]['quantity']) / (($products_tax/100)+1)));
    } else {
      $total_tax += (($total_products_price * $products[$i]['quantity']) * $products_tax/100);
    }
    $total_cost += ($total_products_price * $products[$i]['quantity']);
  }

  $country = tep_get_countries($address_values['country_id']);
  $shipping_cost = 0.0;
  if (MODULE_SHIPPING_INSTALLED) {
    $shipping_modules->confirm();
  }
?>
          <tr>
            <td colspan="4"><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td colspan="4" align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0" align="right">
              <tr>
                <td align="right" width="100%" class="tableHeading">&nbsp;<? echo SUB_TITLE_SUB_TOTAL; ?>&nbsp;</td>
                <td align="right" width="100%" class="tableHeading">&nbsp;<? echo tep_currency_format($total_cost); ?>&nbsp;</td>
              </tr>
<?
  if ($total_tax > 0) {
?>
              <tr>
                <td align="right" width="100%" class="tableHeading">&nbsp;<? echo SUB_TITLE_TAX; ?>&nbsp;</td>
                <td align="right" width="100%" class="tableHeading">&nbsp;<? echo tep_currency_format($total_tax); ?>&nbsp;</td>
              </tr>

<?
  }
  if (MODULE_SHIPPING_INSTALLED) {
?>
              <tr>
                <td align="right" width="100%" class="tableHeading">&nbsp;<? echo $shipping_method . " " . SUB_TITLE_SHIPPING; ?>&nbsp;</td>
                <td align="right" width="100%" class="tableHeading">&nbsp;<? echo tep_currency_format($shipping_cost); ?>&nbsp;</td>
              </tr>
<?
  }
?>
              <tr>
                <td align="right" width="100%" class="tableHeading">&nbsp;<? echo SUB_TITLE_TOTAL; ?>&nbsp;</td>
                <td align="right" width="100%" class="tableHeading">&nbsp;<?
    if (TAX_INCLUDE == true) {
      echo tep_currency_format($total_cost + $shipping_cost);
    } else {
      echo tep_currency_format($total_cost + $total_tax + $shipping_cost);
    } ?>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="tableHeading">&nbsp;<? echo TABLE_HEADING_DELIVERY_ADDRESS; ?>&nbsp;</td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td class="main"><? echo tep_address_label($customer_id, $HTTP_POST_VARS['sendto'], 1, '&nbsp;', '<br>'); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?
   if (MODULE_PAYMENT_INSTALLED) {
?>
          <tr>
            <td class="tableHeading">&nbsp;<? echo TABLE_HEADING_PAYMENT_METHOD; ?>&nbsp;</td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
<?
// load the confirmation function from the payment modules
    $payment_modules->confirmation();
  }
  if (!$checkout_form_action) {
    $checkout_form_action = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
  }
  echo '<form name="checkout_confirmation" method="post" action="' . $checkout_form_action . '">';

  if ($comments) {
?>
          <tr>
            <td class="main">&nbsp;</td>
          </tr>
          <tr>
            <td class="main"><b>&nbsp;<? echo TABLE_HEADING_COMMENTS; ?>&nbsp;</b></td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td class="main"><? echo '&nbsp;' . nl2br(stripslashes($comments)); ?></td>
          </tr>
<?
  }
// Stock Options prompts user for sending when STOCK is available or send now !
  if (($any_out_of_stock) && (STOCK_ALLOW_CHECKOUT) && (MODULE_SHIPPING_INSTALLED)) {
?>
          <tr>
            <td class="main">&nbsp;</td>
          </tr>
          <tr>
            <td class="tableHeading">&nbsp;<? echo TEXT_STOCK_WARNING; ?></td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
          <tr class="payment-odd">
            <td class="main">&nbsp;<? echo TEXT_MULTIPLE_SHIPMENT; ?> <input type="radio" name="shiptype" value="Multiple Ship" checked>&nbsp;&nbsp;<? echo TEXT_UNIQUE_SHIPMENT; ?><input type="radio" name="shiptype" value="Single Ship"></td>
          </tr>
          <tr>
            <td class="infoBox"><br><? echo TEXT_STOCK_WARNING_DESC; ?></td>
          </tr>
          <tr>
            <td class="infoBox"><b><? echo TEXT_IMEDIATE_DELIVER; ?></b><br><br>
<?
    for ($i=0; $i<sizeof($products); $i++) {
      $products_name = $products[$i]['name'];
      $products_price = $products[$i]['price'];
      $products_id = $products[$i]['id'];
      $products_quantity = $products[$i]['quantity'];
      $out_of_stock = check_stock($products[$i]['id'], $products[$i]['quantity']);

      if ($out_of_stock) {
//  $qtd_to_ship = ($products_quantity  -= $qtd_stock);
        if ($qtd_stock < 0) $qtd_stock = 0;
        echo '<b>' . $qtd_stock . '</b> ' . TEXT_UNITS . ' <b>' . $products_name . '</b><br>';
      }
    }
?>
            </td>
          </tr>
<?
  }
?>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
<?
  echo '          <tr>' . "\n";
  echo '            <td align="right" class="main"><br>' .
                   '<input type="hidden" name="prod" value="' . $HTTP_POST_VARS['prod'] . '">' .
                   '<input type="hidden" name="sendto" value="' . $HTTP_POST_VARS['sendto'] . '">' .
                   '<input type="hidden" name="payment" value="' . $HTTP_POST_VARS['payment'] . '">' .
                   '<input type="hidden" name="comments" value="' . urlencode(stripslashes($comments)) . '">' .
                   '<input type="hidden" name="shipping_cost" value="' . $shipping_cost . '">' .
                   '<input type="hidden" name="shipping_method" value="' . $shipping_method . '">';

   // load the process_button function from the payment modules
  $payment_modules->process_button();

  if (!$checkout_form_submit) {
    echo tep_image_submit('button_confirm_order.gif', IMAGE_BUTTON_CONFIRM_ORDER) . '&nbsp;' . "\n";
  } else {
    echo $checkout_form_submit;
  }
  echo '            </td>' . "\n";
  echo '          </tr></form>' . "\n";
?>
        </table></td>
      </tr>
      <tr>
        <td align="right" class="smallText"><br><? echo '&nbsp;<font color="' . CHECKOUT_BAR_TEXT_COLOR . '">[ ' . CHECKOUT_BAR_DELIVERY_ADDRESS . ' | ' . CHECKOUT_BAR_PAYMENT_METHOD . ' | <font color="' . CHECKOUT_BAR_TEXT_COLOR_HIGHLIGHTED . '">' . CHECKOUT_BAR_CONFIRMATION . '</font> | ' . CHECKOUT_BAR_FINISHED . ' ]</font>&nbsp;'; ?></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<? $include_file = DIR_WS_INCLUDES . 'column_right.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- right_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<? $include_file = DIR_WS_INCLUDES . 'footer.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<? $include_file = DIR_WS_INCLUDES . 'application_bottom.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>

