<?php
/*
  $Id: checkout_confirmation.php,v 1.117 2002/06/16 14:32:37 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot(array('mode' => 'NONSSL', 'page' => FILENAME_SHOPPING_CART));
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// Check if there is something in the cart
  if ($cart->count_contents() == 0) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'));
  }

// Stock Check
  $any_out_of_stock = 0;
  if (STOCK_CHECK == 'true') {
    $products = $cart->get_products();
    for ($i=0; $i<sizeof($products); $i++) {
      if (tep_check_stock($products[$i]['id'], $products[$i]['quantity'])) {
        $any_out_of_stock = 1;
      }
    }
    // Out of Stock
    if ( (STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock) ) {
      tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
    }
  }

// Register checkout variables
  if ($HTTP_POST_VARS['comments']) {
    $comments = stripslashes($HTTP_POST_VARS['comments']);
  }
  if ($HTTP_POST_VARS['payment']) {
    $payment = stripslashes($HTTP_POST_VARS['payment']);
  }
  if ($HTTP_POST_VARS['shipping_selected']) {
    $shipping_selected = stripslashes($HTTP_POST_VARS['shipping_selected']);
  }
  if (!tep_session_is_registered('comments')) {
    tep_session_register('comments');
  }
  if (!tep_session_is_registered('payment')) {
    tep_session_register('payment');
  }
  if (!tep_session_is_registered('shipping_selected')) {
    tep_session_register('shipping_selected');
  }
  if (!tep_session_is_registered('shipping_cost')) {
    tep_session_register('shipping_cost');
  }
  if (!tep_session_is_registered('shipping_method')) {
    tep_session_register('shipping_method');
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_CONFIRMATION);
  $location = ' &raquo; <a href="' . tep_href_link(FILENAME_CHECKOUT_ADDRESS, '', 'SSL') . '" class="headerNavigation">' . NAVBAR_TITLE_1 . '</a> &raquo; ' . NAVBAR_TITLE_2;

// load shipping modules as objects
  require(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping;

// load payment modules as objects
  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment;

  if (MODULE_PAYMENT_INSTALLED) {
    $payment_modules->pre_confirmation_check();
  }

  if (MODULE_SHIPPING_INSTALLED) {
    $shipping_modules->confirm();
  }

  require(DIR_WS_CLASSES . 'order_total.php');
  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;
  $order_total_modules = new order_total;
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_confirmation.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
    </table><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td>
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="tableHeading" colspan="2"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
            <td class="tableHeading" align="right"><?php echo TABLE_HEADING_TAX; ?></td>
            <td class="tableHeading" align="right"><?php echo TABLE_HEADING_TOTAL; ?></td>
          </tr>
          <tr>
            <td colspan="4"><?php echo tep_draw_separator(); ?></td>
          </tr>
<?php
  for ($i=0; $i<sizeof($order->products); $i++) {
      echo '          <tr>' . "\n" .
           '            <td class="main" valign="top" align="right" width="30">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
           '            <td class="main" valign="top">' . $order->products[$i]['name'];

      if (STOCK_CHECK == 'true') {
        echo tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty']);
      }

      if (sizeof($order->products[$i]['attributes']) > 0) {
        for ($j=0; $j<sizeof($order->products[$i]['attributes']); $j++) {
          echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'];
          if ($order->products[$i]['attributes'][$j]['price'] != '0') echo ' (' . $order->products[$i]['attributes'][$j]['prefix'] . $currencies->format($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty']) . ')';
          echo '</i></small></nobr>';
        }
      }

      echo '</td>' . "\n" .
           '            <td class="main" align="right" valign="top">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";
      echo '            <td class="main" align="right" valign="top">' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . '</td>' . "\n";
      echo '          </tr>' . "\n";
  }
?>
          <tr>
            <td colspan="4"><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td colspan="4" align="right"><table border="0" cellspacing="0" cellpadding="1">
<?php
  if (MODULE_ORDER_TOTAL_INSTALLED) {
    $order_total_modules->process();
    echo $order_total_modules->output();
  }
?>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="tableHeading"><?php echo TABLE_HEADING_DELIVERY_ADDRESS; ?></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
   if (MODULE_PAYMENT_INSTALLED) {
?>
          <tr>
            <td class="tableHeading"><br><?php echo TABLE_HEADING_PAYMENT_METHOD; ?></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td><?php echo $payment_modules->confirmation(); ?></td>
          </tr>
<?php
  }

  if (!$checkout_form_action) {
    $checkout_form_action = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
  }

  echo '<form name="checkout_confirmation" method="post" action="' . $checkout_form_action . '">';

  if ($order->info['comments']) {
?>
          <tr>
            <td class="main"><br><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo nl2br($order->info['comments']); ?></td>
          </tr>
<?php
  }
// Stock Options prompts user for sending when STOCK is available or send now !
  if ( ($any_out_of_stock) && (STOCK_ALLOW_CHECKOUT == 'true') ) {
?>
          <tr>
            <td class="tableHeading"><br><?php echo TEXT_STOCK_WARNING; ?></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr class="payment-odd">
            <td class="main"><?php echo TEXT_MULTIPLE_SHIPMENT; ?> <input type="radio" name="shiptype" value="Multiple Ship" checked><?php echo TEXT_UNIQUE_SHIPMENT; ?><input type="radio" name="shiptype" value="Single Ship"></td>
          </tr>
          <tr>
            <td class="infoBox"><br><?php echo TEXT_STOCK_WARNING_DESC; ?></td>
          </tr>
          <tr>
            <td class="infoBox"><b><?php echo TEXT_IMEDIATE_DELIVER; ?></b><br><br>
<?php
    for ($i=0; $i<sizeof($products); $i++) {
      if (tep_check_stock($products[$i]['id'], $products[$i]['quantity'])) {
        echo '<b>' . tep_get_products_stock($products[$i]['id']) . '</b> ' . TEXT_UNITS . ' <b>' . $products[$i]['name'] . '</b><br>';
      }
    }
?>
            </td>
          </tr>
<?php
  }
?>
          <tr>
            <td align="right" class="main"><br>
<?php
  echo tep_draw_hidden_field('prod', $HTTP_POST_VARS['prod']) .
       $payment_modules->process_button();

  if (!$checkout_form_submit) {
    echo tep_image_submit('button_confirm_order.gif', IMAGE_BUTTON_CONFIRM_ORDER);
  } else {
    echo $checkout_form_submit;
  }
?></td>
          </tr></form>
        </table></td>
      </tr>
      <tr>
        <td align="right" class="checkoutBar"><br>[ <?php echo CHECKOUT_BAR_DELIVERY_ADDRESS; ?> | <?php echo CHECKOUT_BAR_PAYMENT_METHOD; ?> | <span class="checkoutBarHighlighted"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></span> | <?php echo CHECKOUT_BAR_FINISHED; ?> ]</td>
      </tr>
    </table></td>
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