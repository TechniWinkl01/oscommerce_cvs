<? include('includes/application_top.php')?>
<?
  if (!tep_session_is_registered('customer_id')) {
    if (getenv(HTTPS)) {
      $connection = 'secure';
    } else {
      $connection = 'normal';
    }
    header('Location: ' . tep_href_link(FILENAME_LOGIN, 'origin=' . FILENAME_CHECKOUT_CONFIRMATION . '&connection=' . $connection, 'NONSSL'));
    tep_exit();
  }
?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_CONFIRMATION; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_CHECKOUT, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_1 . '</a> : ' . NAVBAR_TITLE_2; ?>
<? 
   $cc_val = '1';  // Assume that the payment is Ok (for Non-cc payment types)
   if ($HTTP_POST_VARS['payment'] == 'cc') {
     $include_file = DIR_FUNCTIONS . 'ccval.php'; include(DIR_INCLUDES . 'include_once.php');
     $cc_val = OnlyNumericSolution($HTTP_POST_VARS['cc_number']);
     $cc_val = CCValidationSolution($cc_val);
   }
?>
<html>
<head>
<title><? echo TITLE;?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_INCLUDES . 'header.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<? echo BOX_WIDTH;?>" valign="top"><table border="0" width="<? echo BOX_WIDTH;?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_INCLUDES . 'column_left.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
            <td bgcolor="<? echo TOP_BAR_BACKGROUND_COLOR;?>" width="100%" nowrap><font face="<? echo TOP_BAR_FONT_FACE;?>" size="<? echo TOP_BAR_FONT_SIZE;?>" color="<? echo TOP_BAR_FONT_COLOR;?>">&nbsp;<? echo TOP_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<? echo HEADING_FONT_FACE;?>" size="<? echo HEADING_FONT_SIZE;?>" color="<? echo HEADING_FONT_COLOR;?>">&nbsp;<? echo HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_IMAGES . 'table_background_confirmation.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line();?></td>
      </tr>
    </table><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td align="center" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><? echo TABLE_HEADING_QUANTITY;?></b>&nbsp;</font></td>
            <td nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><? echo TABLE_HEADING_PRODUCTS;?></b>&nbsp;</font></td>
            <td align="center" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><? echo TABLE_HEADING_TAX;?></b>&nbsp;</font></td>
            <td align="right" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><? echo TABLE_HEADING_TOTAL;?></b>&nbsp;</font></td>
          </tr>
          <tr>
            <td colspan="4"><? echo tep_black_line();?></td>
          </tr>
<?
  $check_cart = tep_db_query("select customers_basket.customers_basket_quantity, manufacturers.manufacturers_name, manufacturers.manufacturers_location, products.products_id, products.products_name, products.products_price, products.products_weight, products.products_tax_class_id from customers_basket, manufacturers, products_to_manufacturers, products where customers_basket.customers_id = '" . $customer_id . "' and customers_basket.products_id = products.products_id and products.products_id = products_to_manufacturers.products_id and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id order by customers_basket.customers_basket_id");
  if ($HTTP_POST_VARS['sendto'] == '0') {
    $address = tep_db_query("select customers_firstname as firstname, customers_lastname as lastname, customers_street_address as street_address, customers_suburb as suburb, customers_postcode as postcode, customers_city as city, customers_zone_id as zone_id, customers_country_id as country_id, customers_state as state from customers where customers_id = '" . $customer_id . "'");
  } else {
    $address = tep_db_query("select entry_firstname as firstname, entry_lastname as lastname, entry_street_address as street_address, entry_suburb as suburb, entry_postcode as postcode, entry_city as city, entry_zone_id as zone_id, entry_country_id as country_id, entry_state as state from address_book where address_book_id = '" . $HTTP_POST_VARS['sendto'] . "'");
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
    echo '            <td align="center" valign="top" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . $products[$i]['quantity'] . '&nbsp;</font></td>' . "\n";
    echo '            <td valign="top" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '"><b>&nbsp;' . $products_name . '&nbsp;</b>';
//------display customer choosen option --------
    $attributes_exist = '0';
    if ($products[$i]['attributes']) {
      $attributes_exist = '1';
      reset($products[$i]['attributes']);
      while (list($option, $value) = each($products[$i]['attributes'])) {
        $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name from products_options popt, products_options_values poval, products_attributes pa where pa.products_id = '" . $products[$i]['id'] . "' and pa.options_id = '" . $option . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $value . "' and pa.options_values_id = poval.products_options_values_id");
        $attributes_values = tep_db_fetch_array($attributes);
        echo '<br><small><i>&nbsp;-&nbsp;' . $attributes_values['products_options_name'] . '&nbsp;:&nbsp;' . $attributes_values['products_options_values_name'] . '</i></small>';
      }
    }
//------display customer choosen option eof-----
    echo '</font></td>' . "\n";
    echo '            <td align="center" valign="top" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . $products_tax . '%&nbsp;</font></td>' . "\n";
    echo '            <td align="right" valign="top" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<b>' . tep_currency_format($products[$i]['quantity'] * $products_price) . '</b>&nbsp;';
//------display customer choosen option --------
    if ($attributes_exist == '1') {
      reset($products[$i]['attributes']);
      while (list($option, $value) = each($products[$i]['attributes'])) {
        $attributes = tep_db_query("select pa.options_values_price, pa.price_prefix from products_options popt, products_options_values poval, products_attributes pa where pa.products_id = '" . $products[$i]['id'] . "' and pa.options_id = '" . $option . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $value . "' and pa.options_values_id = poval.products_options_values_id");
        $attributes_values = tep_db_fetch_array($attributes);
        if ($attributes_values['options_values_price'] != '0') {
          echo '<br><small><i>' . $attributes_values['price_prefix'] . tep_currency_format($products[$i]['quantity'] * $attributes_values['options_values_price']) . '</i></small>&nbsp;';
        } else {
          echo '<br>&nbsp;';
        }
      }
    }
//------display customer choosen option eof-----
    echo '</font></td>' . "\n";
    echo '          </tr>' . "\n";

    $total_weight += ($products[$i]['quantity'] * $products_weight);
    $total_tax += ($total_products_price * $products_tax/100);
    $total_cost += $total_products_price;
  }

  $country = tep_get_countries($address_values['country_id']);
  $shipping_cost = 0.0;
  if (!SHIPPING_FREE) {
    if (SHIPPING_MODEL == SHIPPING_UPS) {
      include('includes/ups.php');
      $rate = new Ups;
      $rate->upsProduct($HTTP_POST_VARS['prod']);    // See upsProduct() function for codes
      // $rate->upsProduct(UPS_SPEED);    // See upsProduct() function for codes
      $rate->origin(UPS_ORIGIN_ZIP, "US"); // Use ISO country codes!
      $rate->dest($address_values['postcode'], "US");      // Use ISO country codes!
      // $rate->dest($address_values['postcode'], $address_values['country']);      // Use ISO country codes!
      $rate->rate(UPS_PICKUP);        // See the rate() function for codes
      $rate->container(UPS_PACKAGE);    // See the container() function for codes
      $rate->weight($total_weight);
      $rate->rescom(UPS_RES);    // See the rescom() function for codes
      $shipping_cost = $rate->getQuote();
      $shipping_method = "UPS " . $prod;
    }
  }

?>
          <tr>
            <td colspan="4"><? echo tep_black_line();?></td>
          </tr>
          <tr>
            <td colspan="4" align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0" align="right">
              <tr>
                <td align="right" width="100%" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<? echo SUB_TITLE_SUB_TOTAL;?>&nbsp;</font></td>
                <td align="right" width="100%" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<? echo tep_currency_format($cart->show_total());?>&nbsp;</font></td>
              </tr>
              <tr>
                <td align="right" width="100%" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<? echo SUB_TITLE_TAX;?>&nbsp;</font></td>
                <td align="right" width="100%" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<? echo tep_currency_format($total_tax);?>&nbsp;</font></td>
              </tr>
<?
  if (!SHIPPING_FREE) {
?>
              <tr>
                <td align="right" width="100%" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<? echo $shipping_method . " " . SUB_TITLE_SHIPPING;?>&nbsp;</font></td>
                <td align="right" width="100%" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<? echo tep_currency_format($shipping_cost);?>&nbsp;</font></td>
              </tr>
<?
  }
?>
              <tr>
                <td align="right" width="100%" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><? echo SUB_TITLE_TOTAL;?></b>&nbsp;</font></td>
                <td align="right" width="100%" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><? echo tep_currency_format($cart->show_total() + $total_tax + $shipping_cost);?></b>&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><? echo TABLE_HEADING_DELIVERY_ADDRESS;?></b>&nbsp;</font></td>
          </tr>
          <tr>
            <td><? echo tep_black_line();?></td>
          </tr>
          <tr>
            <td nowrap><font face="<? echo TEXT_FONT_FACE;?>" size="<? echo TEXT_FONT_SIZE;?>" color="<? echo TEXT_FONT_COLOR;?>"><? echo tep_address_label($customer_id, $HTTP_POST_VARS['sendto'], 1, '&nbsp;', '<br>');?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><? echo TABLE_HEADING_PAYMENT_METHOD;?></b>&nbsp;</font></td>
          </tr>
          <tr>
            <td><? echo tep_black_line();?></td>
          </tr>
<?
  switch($HTTP_POST_VARS['payment']) {
    case 'cod' : // Cash On Delivery
      echo '          <tr>' . "\n";
      echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . TEXT_CASH_ON_DELIVERY . '&nbsp;</font></td>' . "\n";
      echo '          </tr>' . "\n";
      break;
    case 'cc' : // Credit Card
      if ($cc_val == '1') {
        echo '          <tr>' . "\n";
        echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . TEXT_CREDIT_CARD . '&nbsp;</font></td>' . "\n";
        echo '          </tr>' . "\n";
        echo '          <tr>' . "\n";
        echo '            <td nowrap><br><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . TEXT_TYPE . '&nbsp;' . $CardName . '&nbsp;</font></td>' . "\n";
        echo '          </tr>' . "\n";
        echo '          <tr>' . "\n";
        echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . TEXT_OWNER . '&nbsp;' . $HTTP_POST_VARS['cc_owner'] . '&nbsp;</font></td>' . "\n";
        echo '          </tr>' . "\n";
        echo '          <tr>' . "\n";
        echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . TEXT_NUMBER . '&nbsp;' . $CardNumber . '&nbsp;</font></td>' . "\n";
        echo '          </tr>' . "\n";
        echo '          <tr>' . "\n";
        echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . TEXT_EXPIRES . '&nbsp;' . $HTTP_POST_VARS['cc_expires'] . '&nbsp;</font></td>' . "\n";
        echo '          </tr>' . "\n";
      } else {
        echo '          <tr>' . "\n";
        echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . TEXT_CREDIT_CARD . '&nbsp;</font></td>' . "\n";
        echo '          </tr>' . "\n";
        echo '          <tr>' . "\n";
        echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . TEXT_OWNER . '&nbsp;' . $HTTP_POST_VARS['cc_owner'] . '&nbsp;</font></td>' . "\n";
        echo '          </tr>' . "\n";
        echo '          <tr>' . "\n";
        echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . TEXT_EXPIRES . '&nbsp;' . $HTTP_POST_VARS['cc_expires'] . '&nbsp;</font></td>' . "\n";
        echo '          </tr>' . "\n";
        echo '          <tr>' . "\n";
        echo '            <td><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<font color="#FF0000"><b>' . TEXT_VAL . '</b></font><br>&nbsp;' . $cc_val . '&nbsp;</font></td>' . "\n";
        echo '          </tr>' . "\n";
      }
      break;
    case 'paypal' : // PayPal
      echo '          <tr>' . "\n";
      echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . EXT_PAYPAL . '&nbsp;</font></td>' . "\n";
      echo '          </tr>' . "\n";
      break;
  }
?>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line();?></td>
      </tr>
<?
  switch($HTTP_POST_VARS['payment']) {
    case 'cc' : // Credit Card
      if ($cc_val == '1') {
        echo '      <form name="checkout_confirmation" method="post" action="' . tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL') . '"><tr>' . "\n";
        echo '        <td align="right" nowrap>' .
                        '<input type="hidden" name="sendto" value="' . $HTTP_POST_VARS['sendto'] . '">' .
                        '<input type="hidden" name="payment" value="' . $HTTP_POST_VARS['payment'] . '">' .
                        '<input type="hidden" name="shipping" value="' . $shipping_cost . '">' .
                        '<input type="hidden" name="shipping_method" value="' . $shipping_method . '">' .
                        '<input type="hidden" name="cc_type" value="' . $CardName . '">' .
                        '<input type="hidden" name="cc_owner" value="' . $HTTP_POST_VARS['cc_owner'] . '">' .
                        '<input type="hidden" name="cc_number" value="' . $CardNumber . '">' .
                        '<input type="hidden" name="cc_expires" value="' . $HTTP_POST_VARS['cc_expires'] . '">' .
                        '<br><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . tep_image_submit(DIR_IMAGES . 'button_process.gif', '78', '24', '0', IMAGE_PROCESS) . '&nbsp;</font></td>' . "\n";
        echo '      </tr></form>' . "\n";
      } else {
        echo '      <form name="checkout_confirmation" method="post" action="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '"><tr>' . "\n";
        echo '        <td align="right" nowrap>' .
                        '<input type="hidden" name="sendto" value="' . $HTTP_POST_VARS['sendto'] . '">' .
                        '<input type="hidden" name="prod" value="' . $HTTP_POST_VARS['prod'] . '">' .
                        '<input type="hidden" name="cc_owner" value="' . $HTTP_POST_VARS['cc_owner'] . '">' .
                        '<input type="hidden" name="cc_expires" value="' . $HTTP_POST_VARS['cc_expires'] . '">' .
                        '<br><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . tep_image_submit(DIR_IMAGES . 'button_back.gif', '58', '24', '0', IMAGE_BACK) . '&nbsp;&nbsp;</font></td>' . "\n";
        echo '      </tr></form>' . "\n";
      }
      break;
    case 'cod' : // Cash On Delivery
      echo '      <form name="checkout_confirmation" method="post" action="' . tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL') . '"><tr>' . "\n";
      echo '        <td align="right" nowrap>' .
                      '<input type="hidden" name="sendto" value="' . $HTTP_POST_VARS['sendto'] . '">' .
                      '<input type="hidden" name="payment" value="' . $HTTP_POST_VARS['payment'] . '">' .
                      '<input type="hidden" name="shipping" value="' . $shipping_cost . '">' .
                      '<input type="hidden" name="shipping_method" value="' . $shipping_method . '">' .
                      '<br><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . tep_image_submit(DIR_IMAGES . 'button_process.gif', '78', '24', '0', IMAGE_PROCESS) . '&nbsp;</font></td>' . "\n";
      echo '      </tr></form>' . "\n";
      break;
    case 'paypal' : // PayPal
      $paypal_return = urlencode($HTTP_POST_VARS['payment'] . '|' . $HTTP_POST_VARS['sendto'] . '|' . $shipping_cost . '|' . $shipping_method);
      echo '      <form name="checkout_confirmation" method="post" action="https://secure.paypal.com/xclick/business=' . rawurlencode(PAYPAL_ID) . '&item_name=' . rawurlencode(STORE_NAME . ' ' . TEXT_PAYMENT) . '&amount=' . ($total_cost + $total_tax) . '&shipping=' . $shipping_cost . '&return=' . urlencode(tep_href_link(FILENAME_CHECKOUT_PROCESS, 'paypal_return=' . $paypal_return, 'SSL')) . '"><tr>' . "\n";
      echo '        <td align="right" nowrap><br><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . tep_image_submit(DIR_IMAGES . 'button_process.gif', '78', '24', '0', IMAGE_PROCESS) . '&nbsp;</font></td>' . "\n";
      echo '      </tr></form>' . "\n";
      break;
    }
 ?>
      <tr>
        <td align="right" nowrap><br><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>"><? echo '&nbsp;<font color="' . CHECKOUT_BAR_TEXT_COLOR . '">[ ' . CHECKOUT_BAR_CART_CONTENTS . ' | ' . CHECKOUT_BAR_DELIVERY_ADDRESS . ' | ' . CHECKOUT_BAR_PAYMENT_METHOD . ' | <font color="' . CHECKOUT_BAR_TEXT_COLOR_HIGHLIGHTED . '">' . CHECKOUT_BAR_CONFIRMATION . '</font> | ' . CHECKOUT_BAR_FINISHED . ' ]</font>&nbsp;'; ?></font></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<? echo BOX_WIDTH;?>" valign="top"><table border="0" width="<? echo BOX_WIDTH;?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<? $include_file = DIR_INCLUDES . 'column_right.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- right_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<? $include_file = DIR_INCLUDES . 'footer.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<? $include_file = DIR_INCLUDES . 'application_bottom.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
