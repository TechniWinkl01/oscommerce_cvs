<? include('includes/application_top.php')?>
<?
  if (!tep_session_is_registered('customer_id')) {
    header('Location: ' . tep_href_link(FILENAME_LOGIN, 'origin=' . FILENAME_SHOPPING_CART, 'NONSSL'));
    tep_exit();
  }
?>
<?php
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
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
            <td bgcolor="<? echo TOP_BAR_BACKGROUND_COLOR; ?>" width="100%" nowrap><?php echo FONT_STYLE_TOP_BAR; ?>&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><?php echo FONT_STYLE_HEADING; ?>&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'table_background_confirmation.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
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
            <td align="center" nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?>&nbsp;<b><? echo TABLE_HEADING_QUANTITY; ?></b>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?>&nbsp;<b><? echo TABLE_HEADING_PRODUCTS; ?></b>&nbsp;</font></td>
            <td align="center" nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?>&nbsp;<b><? echo TABLE_HEADING_TAX; ?></b>&nbsp;</font></td>
            <td align="right" nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?>&nbsp;<b><? echo TABLE_HEADING_TOTAL; ?></b>&nbsp;</font></td>
          </tr>
          <tr>
            <td colspan="4"><? echo tep_black_line(); ?></td>
          </tr>
<?
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
    echo '            <td align="center" valign="top" nowrap>' . FONT_STYLE_MAIN . '&nbsp;' . $products[$i]['quantity'] . '&nbsp;</font></td>' . "\n";
    echo '            <td valign="top" nowrap>' . FONT_STYLE_MAIN . '<b>&nbsp;' . $products_name . '&nbsp;</b>';
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
    echo '            <td align="center" valign="top" nowrap>' . FONT_STYLE_MAIN . '&nbsp;' . number_format($products_tax, TAX_DECIMAL_PLACES) . '%&nbsp;</font></td>' . "\n";
    echo '            <td align="right" valign="top" nowrap>' . FONT_STYLE_MAIN . '&nbsp;<b>' . tep_currency_format($products[$i]['quantity'] * $products_price) . '</b>&nbsp;';
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
    $total_tax += (($total_products_price * $products[$i]['quantity']) * $products_tax/100);
    $total_cost += ($total_products_price * $products[$i]['quantity']);
  }

  $country = tep_get_countries($address_values['country_id']);
  $shipping_cost = 0.0;
  if (SHIPPING_MODULES != '') {
    $shipping_modules->confirm();
  }
?>
          <tr>
            <td colspan="4"><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td colspan="4" align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0" align="right">
              <tr>
                <td align="right" width="100%" nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?>&nbsp;<? echo SUB_TITLE_SUB_TOTAL; ?>&nbsp;</font></td>
                <td align="right" width="100%" nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?>&nbsp;<? echo tep_currency_format($total_cost); ?>&nbsp;</font></td>
              </tr>
<?
  if ($total_tax > 0) {
?>
              <tr>
                <td align="right" width="100%" nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?>&nbsp;<? echo SUB_TITLE_TAX; ?>&nbsp;</font></td>
                <td align="right" width="100%" nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?>&nbsp;<? echo tep_currency_format($total_tax); ?>&nbsp;</font></td>
              </tr>
<?
  }
  if (SHIPPING_MODULES != '') {
?>
              <tr>
                <td align="right" width="100%" nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?>&nbsp;<? echo $shipping_method . " " . SUB_TITLE_SHIPPING; ?>&nbsp;</font></td>
                <td align="right" width="100%" nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?>&nbsp;<? echo tep_currency_format($shipping_cost); ?>&nbsp;</font></td>
              </tr>
<?
  }
?>
              <tr>
                <td align="right" width="100%" nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?>&nbsp;<b><? echo SUB_TITLE_TOTAL; ?></b>&nbsp;</font></td>
                <td align="right" width="100%" nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?>&nbsp;<b><? echo tep_currency_format($total_cost + $total_tax + $shipping_cost); ?></b>&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?>&nbsp;<b><? echo TABLE_HEADING_DELIVERY_ADDRESS; ?></b>&nbsp;</font></td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td nowrap><?php echo FONT_STYLE_MAIN; ?><? echo tep_address_label($customer_id, $HTTP_POST_VARS['sendto'], 1, '&nbsp;', '<br>'); ?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?>&nbsp;<b><? echo TABLE_HEADING_PAYMENT_METHOD; ?></b>&nbsp;</font></td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
<?
// load the confirmation function from the payment modules
  $payment_modules->confirmation();

  if ($comments) {
?>
        <tr>
            <td><?php echo FONT_STYLE_MAIN; ?>&nbsp;</font></td>
        </tr>
        <tr>
          <td nowrap colspan="2"><?php echo FONT_STYLE_MAIN; ?><b>&nbsp;<? echo TABLE_HEADING_COMMENTS; ?>&nbsp;</b></font></td>
        </tr>
        <tr>
          <td colspan="2"><? echo tep_black_line(); ?></td>
        </tr>
        <tr>
          <td colspan="2"><?php echo FONT_STYLE_MAIN; ?><? echo '&nbsp;' . nl2br(stripslashes($comments)); ?></font></td>
        </tr>
<?
  }
?>
        <tr>
          <td colspan="2"><? echo tep_black_line(); ?></td>
        </tr>
<?
  if (!$checkout_form_action) {
    $checkout_form_action = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
  }
  echo '          <form name="checkout_confirmation" method="post" action="' . $checkout_form_action . '"><tr>' . "\n";
  echo '            <td align="right" nowrap>' .
                   '<input type="hidden" name="prod" value="' . $HTTP_POST_VARS['prod'] . '">' .
                   '<input type="hidden" name="sendto" value="' . $HTTP_POST_VARS['sendto'] . '">' .
                   '<input type="hidden" name="payment" value="' . $HTTP_POST_VARS['payment'] . '">' .
                   '<input type="hidden" name="comments" value="' . urlencode(stripslashes($comments)) . '">' .
                   '<input type="hidden" name="shipping_cost" value="' . $shipping_cost . '">' .
                   '<input type="hidden" name="shipping_method" value="' . $shipping_method . '">';

// load the process_button function from the payment modules
  $payment_modules->process_button();

  if (!$checkout_form_submit) {
    echo tep_image_submit(DIR_WS_IMAGES . 'button_process.gif', IMAGE_PROCESS) . '&nbsp;' . "\n";
  } else {
    echo $checkout_form_submit;
  }
  echo '            </td>' . "\n";
  echo '          </tr></form>' . "\n";
?>
        </table></td>
      </tr>
      <tr>
        <td align="right" nowrap><br><?php echo FONT_STYLE_SMALL_TEXT; ?><? echo '&nbsp;<font color="' . CHECKOUT_BAR_TEXT_COLOR . '">[ ' . CHECKOUT_BAR_DELIVERY_ADDRESS . ' | ' . CHECKOUT_BAR_PAYMENT_METHOD . ' | <font color="' . CHECKOUT_BAR_TEXT_COLOR_HIGHLIGHTED . '">' . CHECKOUT_BAR_CONFIRMATION . '</font> | ' . CHECKOUT_BAR_FINISHED . ' ]</font>&nbsp;'; ?></font></td>
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
