<? include('includes/application_top.php'); ?>
<?
  if (ENABLE_SSL) {
    $connection = 'SSL';
  } else {
    $connection = 'NONSSL';
  }
  if (!tep_session_is_registered('customer_id')) {
    header('Location: ' . tep_href_link(FILENAME_LOGIN, 'origin=' . FILENAME_CHECKOUT_PAYMENT . '&connection=' . $connection, 'NONSSL'));
    tep_exit();
  }
  if ($cart->count_contents() == 0) {
    header('Location: ' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'));
    tep_exit();
  }

// Stock Check !
   if (STOCK_CHECK) {

    $products = $cart->get_products();
    for ($i=0; $i<sizeof($products); $i++) {
    $products_name = $products[$i]['name'];
    $products_id = $products[$i]['id'];
    check_stock ($products[$i]['id'], $products[$i]['quantity']);
                     }

       if (STOCK_ALLOW_CHECKOUT) {

       } else {

  if ($any_out_of_stock) {
  // Out of Stock
  header('Location: ' . tep_href_link(FILENAME_SHOPPING_CART, 'origin=' . FILENAME_CHECKOUT_ADDRESS . '&connection=' . $connection, 'NONSSL'));
  exit;
          }
      } // Stock Allow Checkout

  } // Stock Check IF
// Stock Check




  $sendto = $HTTP_POST_VARS['sendto'];
  if ($sendto == '') {
    $sendto = '0';
  }
  if (@!tep_in_array('shipping_quote_all', $HTTP_POST_VARS)) {
    $shipping_quote_all = '1';
  }
  if ($sendto == '0') {
    $address = tep_db_query("select customers_postcode as postcode, customers_country_id as country_id from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
  } else {
    $address = tep_db_query("select entry_postcode as postcode, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . $sendto . "'");
  }
  $address_values = tep_db_fetch_array($address);
  $total_weight = $cart->show_weight();
  $total_count = $cart->count_contents();

// load shipping modules as objects
  include(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping;
  $shipping_modules->quote();

  if ( ($shipping_quoted == '') && (MODULE_SHIPPING_INSTALLED) ) { // Null if no quotes selected
    header('Location: ' . tep_href_link(FILENAME_CHECKOUT_ADDRESS, '' , $connection)); tep_exit();
  }

  $include_file = DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PAYMENT; include(DIR_WS_INCLUDES . 'include_once.php');
  $location = ' : <a href="' . tep_href_link(FILENAME_CHECKOUT_ADDRESS, '', 'SSL') . '" class="whitelink">' . NAVBAR_TITLE_1 . '</a> : ' . NAVBAR_TITLE_2;

// load payment modules as objects
  include(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment;
?>
<html>
<head>
<title><? echo TITLE; ?></title>
<base href="<? echo (ENABLE_SSL ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function check_form() {
  var error = 0;
  var error_message = "<? echo JS_ERROR; ?>";
  var payment_value = null;

<?
// load the javascript_validation function from the payment modules
  if (MODULE_PAYMENT_INSTALLED) {
?>
  if (document.payment.payment.length) {
    for (var i = 0; i < document.payment.payment.length; i++)
      if (document.payment.payment[i].checked)
        payment_value = document.payment.payment[i].value;
  } else if (document.payment.payment.checked) {
    payment_value = document.payment.payment.value;
  }
<?
    $payment_modules->javascript_validation();
  }
?>
  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>
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
    <td width="100%" valign="top"><form name="payment" method="post" action="<? echo tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'); ?>" onsubmit="return check_form();"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td width="100%" class="topBarTitle">&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right">&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'table_background_payment.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?
   if (MODULE_PAYMENT_INSTALLED) {
?>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="tableHeading">&nbsp;<? echo TABLE_HEADING_METHODS; ?>&nbsp;</td>
                <td align="right" class="tableHeading">&nbsp;<? echo TABLE_HEADING_SELECTION; ?>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?
// load the selection function from the payment modules
  $payment_modules->selection();
?>
            </table></td>
          </tr>
<?
  }
?>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="tableHeading">&nbsp;<? echo TABLE_HEADING_DELIVERY_ADDRESS; ?>&nbsp;</td>
              </tr>
              <tr>
                <td><? echo tep_black_line(); ?></td>
              </tr>
              <tr>
                <td class="main"><? echo tep_address_label($customer_id, $sendto, 1, '&nbsp;', '<br>'); ?>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
<?
   if (MODULE_SHIPPING_INSTALLED) {
?>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2" class="tableHeading">&nbsp;<? echo TABLE_HEADING_SHIPPING_INFO; ?>&nbsp;</td>
                <td colspan=2 align="right" class="tableHeading">&nbsp;<? echo TABLE_HEADING_SHIPPING_QUOTE; ?>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?
    $shipping_modules->cheapest();
    $shipping_modules->display();
?>
            </table></td>
          </tr>
<?
  }
?>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
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
            <td><textarea name="comments" rows=5 cols=60></textarea></td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main">&nbsp;&nbsp;<a href="<? echo tep_href_link(FILENAME_CHECKOUT_ADDRESS, '', $connection); ?>"><? echo tep_image_button('button_shipping_options.gif', IMAGE_BUTTON_SHIPPING_OPTIONS); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<? echo tep_href_link(FILENAME_CHECKOUT_ADDRESS, '', $connection); ?>"><? echo tep_image_button('button_change_address.gif', IMAGE_BUTTON_CHANGE_ADDRESS); ?></a></td>
                <td class="main" align="right"><? echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?>&nbsp;&nbsp;</td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="right" class="smallText"><br>&nbsp;<font color="<? echo CHECKOUT_BAR_TEXT_COLOR; ?>">[ <? echo CHECKOUT_BAR_DELIVERY_ADDRESS; ?> | <font color="<? echo CHECKOUT_BAR_TEXT_COLOR_HIGHLIGHTED; ?>"><? echo CHECKOUT_BAR_PAYMENT_METHOD; ?></font> | <? echo CHECKOUT_BAR_CONFIRMATION; ?> | <? echo CHECKOUT_BAR_FINISHED; ?> ]</font>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
    </table><input type="hidden" name="sendto" value="<? echo $sendto; ?>">
    </form></td>
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