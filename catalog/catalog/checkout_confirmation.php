<? include('includes/application_top.php')?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_CONFIRMATION; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_CHECKOUT, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_1 . '</a> : ' . NAVBAR_TITLE_2; ?>
<html>
<head>
<title><?=TITLE;?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_INCLUDES . 'header.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<?=BOX_WIDTH;?>" valign="top"><table border="0" width="<?=BOX_WIDTH;?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_INCLUDES . 'column_left.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><form name="checkout_confirmation" method="post" action="<?=tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');?>"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
            <td bgcolor="<?=TOP_BAR_BACKGROUND_COLOR;?>" width="100%" nowrap><font face="<?=TOP_BAR_FONT_FACE;?>" size="<?=TOP_BAR_FONT_SIZE;?>" color="<?=TOP_BAR_FONT_COLOR;?>">&nbsp;<?=TOP_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_IMAGES . 'table_background_confirmation.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><?=TABLE_HEADING_QUANTITY;?></b>&nbsp;</font></td>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><?=TABLE_HEADING_PRODUCTS;?></b>&nbsp;</font></td>
            <td align="right" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><?=TABLE_HEADING_TOTAL;?></b>&nbsp;</font></td>
          </tr>
          <tr>
            <td colspan="3"><?=tep_black_line();?></td>
          </tr>
<?
  $check_cart = tep_db_query("select customers_basket.customers_basket_quantity, manufacturers.manufacturers_name, manufacturers.manufacturers_location, products.products_id, products.products_name, products.products_price, products.products_weight from customers_basket, manufacturers, products_to_manufacturers, products where customers_basket.customers_id = '" . $customer_id . "' and customers_basket.products_id = products.products_id and products.products_id = products_to_manufacturers.products_id and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id order by customers_basket.customers_basket_id");
  $total_cost = 0;
  $total_weight = 0;
  while ($check_cart_values = tep_db_fetch_array($check_cart)) {
    $price = $check_cart_values['products_price'];
    $check_weight = $check_cart_values['products_weight'];
    $total_weight = $total_weight + ($check_cart_values['customers_basket_quantity'] * $check_weight);
    $check_special = tep_db_query("select specials_new_products_price from specials where products_id = '" . $check_cart_values['products_id'] . "'");
    if (tep_db_num_rows($check_special)) {
      $check_special_values = tep_db_fetch_array($check_special);
      $price = $check_special_values['specials_new_products_price'];
    }
    $products_name = tep_products_name($check_cart_values['manufacturers_location'], $check_cart_values['manufacturers_name'], $check_cart_values['products_name']);
    echo '          <tr>' . "\n";
    echo '            <td align="center" nowrap><font face="' , TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . $check_cart_values['customers_basket_quantity'] . '&nbsp;</font></td>' . "\n";
    echo '            <td nowrap><font face="' , TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . $products_name . '&nbsp;</font></td>' . "\n";
    echo '            <td align="right" nowrap><font face="' , TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;$' . number_format(($check_cart_values['customers_basket_quantity'] * $price),2) . '&nbsp;</font></td>' . "\n";
    echo '          </tr>' . "\n";
    $total_cost = $total_cost + ($check_cart_values['customers_basket_quantity'] * $price);
  }

  if ($HTTP_POST_VARS['sendto'] == '0') {
    $address = tep_db_query("select customers_firstname as firstname, customers_lastname as lastname, customers_street_address as street_address, customers_suburb as suburb, customers_postcode as postcode, customers_city as city, customers_state as state, customers_country_id as country from customers where customers_id = '" . $customer_id . "'");
  } else {
    $address = tep_db_query("select entry_firstname as firstname, entry_lastname as lastname, entry_street_address as street_address, entry_suburb as suburb, entry_postcode as postcode, entry_city as city, entry_state as state, entry_country_id as country from address_book where address_book_id = '" . $HTTP_POST_VARS['sendto'] . "'");
  }
  $address_values = tep_db_fetch_array($address);
  $country = tep_get_countries($address_values['country']);
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
      $rate->weight("$total_weight");
      $rate->rescom(UPS_RES);    // See the rescom() function for codes
      $shipping_cost = $rate->getQuote();
      $shipping_method = "UPS " . $prod;
    }
  }

?>
          <tr>
            <td colspan="3"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td colspan="3" align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0" align="right">
              <tr>
                <td align="right" width="100%" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<?=SUB_TITLE_SUB_TOTAL;?>&nbsp;</font></td>
                <td align="right" width="100%" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;$<?=number_format($total_cost, 2);?>&nbsp;</font></td>
              </tr>
              <tr>
                <td align="right" width="100%" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<?=SUB_TITLE_TAX;?>&nbsp;</font></td>
                <td align="right" width="100%" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;$<?=number_format(($total_cost * TAX_VALUE/100), 2);?>&nbsp;</font></td>
              </tr>
<?
  if (!SHIPPING_FREE) {
?>
              <tr>
                <td align="right" width="100%" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<?=$shipping_method . " " . SUB_TITLE_SHIPPING;?>&nbsp;</font></td>
                <td align="right" width="100%" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;$<?=number_format($shipping_cost, 2);?>&nbsp;</font></td>
              </tr>
<?
  }
?>
              <tr>
                <td align="right" width="100%" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><?=SUB_TITLE_TOTAL;?></b>&nbsp;</font></td>
                <td align="right" width="100%" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b>$<?=number_format((($total_cost * TAX_VALUE/100) + $total_cost + $shipping_cost), 2);?></b>&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><?=TABLE_HEADING_DELIVERY_ADDRESS;?></b>&nbsp;</font></td>
          </tr>
          <tr>
            <td><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$address_values['firstname'] . ' ' . $address_values['lastname'];?>&nbsp;</font></td>
          </tr>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$address_values['street_address'];?>&nbsp;</font></td>
          </tr>
<?
  if ($address_values['suburb'] != '') {
    echo '          <tr>' . "\n";
    echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . $address_values['suburb'] . '&nbsp;</font></td>' . "\n";
    echo '          </tr>' . "\n";
  }
?>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$address_values['city'] . ', ' . $address_values['postcode'];?>&nbsp;</font></td>
          </tr>
          <tr>
<?
  if ($address_values['state'] != '') {
    echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . $address_values['state'] . ', ' . $country['countries_name'] . '&nbsp;</font></td>' . "\n";
  } else {
    echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . $country['countries_name'] . '&nbsp;</font></td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><?=TABLE_HEADING_PAYMENT_METHOD;?></b>&nbsp;</font></td>
          </tr>
          <tr>
            <td><?=tep_black_line();?></td>
          </tr>
<?
  if ($HTTP_POST_VARS['payment'] == 'cod') {
    echo '          <tr>' . "\n";
    echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . TEXT_CASH_ON_DELIVERY . '&nbsp;</font></td>' . "\n";
    echo '          </tr>' . "\n";
  } elseif ($HTTP_POST_VARS['payment'] == 'cc') {
?>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_CREDIT_CARD;?>&nbsp;</font></td>
          </tr>
          <tr>
            <td nowrap><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_TYPE;?>&nbsp;<?=$HTTP_POST_VARS['cc_type'];?>&nbsp;</font></td>
          </tr>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_OWNER;?>&nbsp;<?=$HTTP_POST_VARS['cc_owner'];?>&nbsp;</font></td>
          </tr>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_NUMBER;?>&nbsp;<?=$HTTP_POST_VARS['cc_number'];?>&nbsp;</font></td>
          </tr>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_EXPIRES;?>&nbsp;<?=$HTTP_POST_VARS['cc_expires'];?>&nbsp;</font></td>
          </tr>
<?
  }
?>
        </table></td>
      </tr>
      <tr>
        <td><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td align="right" nowrap><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=tep_image_submit(DIR_IMAGES . 'button_process.gif', '78', '24', '0', IMAGE_PROCESS);?>&nbsp;</font></td>
      </tr>
      <tr>
        <td align="right" nowrap><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<font color="<?=CHECKOUT_BAR_TEXT_COLOR;?>">[ <?=CHECKOUT_BAR_CART_CONTENTS;?> | <?=CHECKOUT_BAR_DELIVERY_ADDRESS;?> | <?=CHECKOUT_BAR_PAYMENT_METHOD;?> | <font color="<?=CHECKOUT_BAR_TEXT_COLOR_HIGHLIGHTED;?>"><?=CHECKOUT_BAR_CONFIRMATION;?></font> | <?=CHECKOUT_BAR_FINISHED;?> ]</font>&nbsp;</font></td>
      </tr>
    </table>
    <input type="hidden" name="sendto" value="<?=$HTTP_POST_VARS['sendto'];?>">
    <input type="hidden" name="payment" value="<?=$HTTP_POST_VARS['payment'];?>">
    <input type="hidden" name="shipping" value="<?=$shipping_cost;?>">
    <input type="hidden" name="shipping_method" value="<?=$shipping_method;?>">
<?
  if ($HTTP_POST_VARS['payment'] == 'cc') {
?>
    <input type="hidden" name="cc_type" value="<?=$HTTP_POST_VARS['cc_type'];?>">
    <input type="hidden" name="cc_owner" value="<?=$HTTP_POST_VARS['cc_owner'];?>">
    <input type="hidden" name="cc_number" value="<?=$HTTP_POST_VARS['cc_number'];?>">
    <input type="hidden" name="cc_expires" value="<?=$HTTP_POST_VARS['cc_expires'];?>">
<?
  }
?>
    </form></td>
<!-- body_text_eof //-->
    <td width="<?=BOX_WIDTH;?>" valign="top"><table border="0" width="<?=BOX_WIDTH;?>" cellspacing="0" cellpadding="0">
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
