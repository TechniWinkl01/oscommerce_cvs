<? include('includes/application_top.php'); ?>
<?
    if (getenv(HTTPS)) {
      $connection = 'SSL';
    } else {
      $connection = 'NONSSL';
    } 
  if ($cart->count_contents() == 0) {
    header('Location: ' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'));
    tep_exit();
  }
  if (!tep_session_is_registered('customer_id')) {
    header('Location: ' . tep_href_link(FILENAME_LOGIN, 'origin=' . FILENAME_CHECKOUT_PAYMENT . '&connection=' . $connection, 'NONSSL'));
    tep_exit();
  }
  $sendto = $HTTP_POST_VARS['sendto'];
  if ($sendto == '') {
    $sendto = '0';
    $shipping_quote_all = '1';
  }
  if ($sendto == '0') {
    $address = tep_db_query("select customers_postcode as postcode, customers_country_id as country_id from customers where customers_id = '" . $customer_id . "'");
  } else {
    $address = tep_db_query("select entry_postcode as postcode, entry_country_id as country_id from address_book where address_book_id = '" . $sendto . "'");
  }
  $address_values = tep_db_fetch_array($address);
  $total_weight = $cart->show_weight();
  $total_count = $cart->count_contents();
  $action = 'quote'; 
  include(DIR_MODULES . 'shipping.php');
  if ($shipping_quoted == '' && SHIPPING_MODULES != '') { // Null if no quotes selected
    header('Location: ' . tep_href_link(FILENAME_CHECKOUT_ADDRESS, '' , $connection));
    tep_exit();
  }
?> 
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PAYMENT; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_CHECKOUT_ADDRESS, '', 'SSL') . '" class="whitelink">' . NAVBAR_TITLE_1 . '</a> : ' . NAVBAR_TITLE_2; ?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript"><!--
function check_form() {
  var error = 0;
  var error_message = "<? echo JS_ERROR; ?>";
  var payment_value = null;

  if (document.payment.payment.length) {
    for (var i = 0; i < document.payment.payment.length; i++)
      if (document.payment.payment[i].checked)
        payment_value = document.payment.payment[i].value;
  } else if (document.payment.payment.checked) {
    payment_value = document.payment.payment.value;
  }
<?
// payment validation
  $payment_action = 'PM_VALIDATION';
  include(DIR_MODULES . 'payment.php');
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
<? $include_file = DIR_INCLUDES . 'header.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_INCLUDES . 'column_left.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><form name="payment" method="post" action="<? echo tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'); ?>" onsubmit="return check_form();"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
            <td bgcolor="<? echo TOP_BAR_BACKGROUND_COLOR; ?>" width="100%" nowrap><font face="<? echo TOP_BAR_FONT_FACE; ?>" size="<? echo TOP_BAR_FONT_SIZE; ?>" color="<? echo TOP_BAR_FONT_COLOR; ?>">&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<? echo HEADING_FONT_FACE; ?>" size="<? echo HEADING_FONT_SIZE; ?>" color="<? echo HEADING_FONT_COLOR; ?>">&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_IMAGES . 'table_background_payment.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>">&nbsp;<b><? echo TABLE_HEADING_METHODS; ?></b>&nbsp;</font></td>
                <td align="right" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>">&nbsp;<b><? echo TABLE_HEADING_SELECTION; ?></b>&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?
// list payment options
  $payment_action = 'PM_SELECTION';
  include(DIR_MODULES . 'payment.php');
?>
            </table></td>
          </tr>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td nowrap colspan="2"><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>">&nbsp;<b><? echo TABLE_HEADING_DELIVERY_ADDRESS; ?></b>&nbsp;</font></td>
          </tr>
          <tr>
            <td colspan="2"><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><? echo tep_address_label($customer_id, $sendto, 1, '&nbsp;', '<br>'); ?>&nbsp;</font></td>
            <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><a href="<? echo tep_href_link(FILENAME_CHECKOUT_ADDRESS, '', $connection) . '">' . CHANGE_DELIVERY_ADDRESS; ?></a>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
<?
   if (SHIPPING_MODULES != '') {
?>
          <tr>          
            <td><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td nowrap colspan=2><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>">&nbsp;<b><? echo TABLE_HEADING_SHIPPING_INFO; ?></b>&nbsp;</font></td>
                <td nowrap colspan=2 align="right"><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>">&nbsp;<b><? echo TABLE_HEADING_SHIPPING_QUOTE; ?></b>&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?
      $action = 'cheapest'; 
      include(DIR_MODULES . 'shipping.php');
      $action = 'display'; 
      include(DIR_MODULES . 'shipping.php');
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
            <td nowrap colspan="2"><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_COMMENTS; ?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td colspan="2"><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
	   <td colspan="2"><textarea name="comments" rows=5 cols=60></textarea></td>
          </tr>
          <tr>
            <td colspan="2"><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td align="right" nowrap><br><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo tep_image_submit(DIR_IMAGES . 'button_next.gif', IMAGE_NEXT); ?>&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" nowrap><br><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<font color="<? echo CHECKOUT_BAR_TEXT_COLOR; ?>">[ <? echo CHECKOUT_BAR_CART_CONTENTS; ?> | <? echo CHECKOUT_BAR_DELIVERY_ADDRESS; ?> | <font color="<? echo CHECKOUT_BAR_TEXT_COLOR_HIGHLIGHTED; ?>"><? echo CHECKOUT_BAR_PAYMENT_METHOD; ?></font> | <? echo CHECKOUT_BAR_CONFIRMATION; ?> | <? echo CHECKOUT_BAR_FINISHED; ?> ]</font>&nbsp;</font></td>
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
