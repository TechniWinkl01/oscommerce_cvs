<?php
/*
  $Id: checkout_payment.php,v 1.95 2002/06/16 14:40:36 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// Check if there is something in the cart
  if ($cart->count_contents() == 0) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'));
  }

// Stock Check
  if ( (STOCK_CHECK == 'true') && (STOCK_ALLOW_CHECKOUT != 'true') ) {
    $products = $cart->get_products();
    $any_out_of_stock = 0;
    for ($i=0; $i<sizeof($products); $i++) {
      if (tep_check_stock($products[$i]['id'], $products[$i]['quantity'])) {
        $any_out_of_stock = 1;
      }
    }
    if ($any_out_of_stock) {
      tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
    }
  }

// Register needed checkout variables
  if (!tep_session_is_registered('sendto')) {
    $sendto = $customer_default_address_id;
    tep_session_register('sendto');
  }
  if ($HTTP_POST_VARS['sendto']) {
    $sendto = $HTTP_POST_VARS['sendto'];
  }

  if ($HTTP_POST_VARS['shipping_quote_all'] == '0') {
    $shipping_quote_all = '0';
  } else {
    $shipping_quote_all = '1';
  }

  $address = tep_db_query("select entry_postcode as postcode, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $customer_id . "' and address_book_id = '" . $sendto . "'");
  $address_values = tep_db_fetch_array($address);

  $total_weight = $cart->show_weight();
  $total_count = $cart->count_contents();

// load shipping modules as objects
  require(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping;
  $shipping_modules->quote();
  if ( ($shipping_quoted == '') && (MODULE_SHIPPING_INSTALLED) ) { // Null if no quotes selected
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_ADDRESS, '' , 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PAYMENT);
  $location = ' &raquo; <a href="' . tep_href_link(FILENAME_CHECKOUT_ADDRESS, '', 'SSL') . '" class="headerNavigation">' . NAVBAR_TITLE_1 . '</a> &raquo; ' . NAVBAR_TITLE_2;

// load payment modules as objects
  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment;
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function check_form() {
  var error = 0;
  var error_message = "<?php echo JS_ERROR; ?>";
  var payment_value = null;
<?php
// load the javascript_validation function from the payment modules
  if (MODULE_PAYMENT_INSTALLED) {
?>
  if (document.payment.payment.length) {
    for (var i = 0; i < document.payment.payment.length; i++)
      if (document.payment.payment[i].checked)
        payment_value = document.payment.payment[i].value;
  } else if (document.payment.payment.checked) {
    payment_value = document.payment.payment.value;
  } else if (document.payment.payment.value) {
    payment_value = document.payment.payment.value;
  }
<?php
    echo $payment_modules->javascript_validation();
  }

  if (tep_count_payment_modules() > 1) {
?>
  if (payment_value == null) {
    error_message = error_message + "<?php echo JS_ERROR_NO_PAYMENT_MODULE_SELECTED; ?>";
    error = 1;
  }
<?php
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
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><form name="payment" method="post" action="<?php echo tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'); ?>" onsubmit="return check_form();"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_payment.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  if ($HTTP_GET_VARS['payment_error']) {
?>
      <tr>
        <td class="main"><?php echo $payment_modules->output_error(); ?></td>
      </tr>
<?php
  }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
   if (MODULE_PAYMENT_INSTALLED) {
?>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="tableHeading"><?php echo TABLE_HEADING_METHODS; ?></td>
                <td class="tableHeading" align="right"><?php if (tep_count_payment_modules() > 1) echo TABLE_HEADING_SELECTION; ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td><?php echo $payment_modules->selection(); ?></td>
          </tr>
<?php
  }
?>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="tableHeading"><?php echo TABLE_HEADING_DELIVERY_ADDRESS; ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator(); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo tep_address_label($customer_id, $sendto, 1, ' ', '<br>'); ?></td>
              </tr>
            </table></td>
          </tr>
<?php
  if (MODULE_SHIPPING_INSTALLED) {
    $shipping_modules->cheapest();
?>
          <tr>
            <td><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="tableHeading"><?php echo TABLE_HEADING_SHIPPING_INFO; ?></td>
                <td class="tableHeading" align="right" class="tableHeading"><?php if (tep_count_shipping_modules() > 1) echo TABLE_HEADING_SHIPPING_QUOTE; ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td><?php echo $shipping_modules->display(); ?></td>
          </tr>
<?php
  }
?>
          <tr>
            <td class="main"><br><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_textarea_field('comments', 'virtual', '60', '5'); ?></td>
          </tr>
          <tr>
            <td class="main"><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><a href="<?php echo tep_href_link(FILENAME_CHECKOUT_ADDRESS, '', 'SSL'); ?>"><?php echo tep_image_button('button_shipping_options.gif', IMAGE_BUTTON_SHIPPING_OPTIONS); ?></a></td>
                <td class="main" align="center"><a href="<?php echo tep_href_link(FILENAME_CHECKOUT_ADDRESS, '', 'SSL'); ?>"><?php echo tep_image_button('button_change_address.gif', IMAGE_BUTTON_CHANGE_ADDRESS); ?></a></td>
                <td class="main" align="right"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="right" class="checkoutBar"><br>[ <?php echo CHECKOUT_BAR_DELIVERY_ADDRESS; ?> | <span class="checkoutBarHighlighted"><?php echo CHECKOUT_BAR_PAYMENT_METHOD; ?></span> | <?php echo CHECKOUT_BAR_CONFIRMATION; ?> | <?php echo CHECKOUT_BAR_FINISHED; ?> ]</td>
          </tr>
        </table></td>
      </tr>
    </table>
    </form></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>