<?php
/*
  $Id: checkout_address.php,v 1.66 2002/01/15 19:55:35 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    tep_redirect(tep_href_link(FILENAME_LOGIN, 'origin=' . FILENAME_CHECKOUT_ADDRESS, 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_ADDRESS);

  $location = ' &raquo; <a href="' . tep_href_link(FILENAME_CHECKOUT_ADDRESS, '', 'SSL') . '" class="headerNavigation">' . NAVBAR_TITLE_1 . '</a> &raquo; ' . NAVBAR_TITLE_2;

  include(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping;
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
    <td width="100%" valign="top"><form name="checkout_address" method="post" action="<?php echo tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'); ?>"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_delivery.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
   if (MODULE_SHIPPING_INSTALLED) {
?>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="tableHeading"><?php echo TABLE_HEADING_SHIPPING_INFO; ?></td>
                <td align="right" class="tableHeading"><?php echo TABLE_HEADING_SHIPPING_QUOTE; ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td><?php echo $shipping_modules->selection(); ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="tableHeading"><?php echo TABLE_HEADING_MY_ADDRESS; ?></td>
                <td align="right" class="tableHeading"><?php echo TABLE_HEADING_DELIVER_TO; ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="main"><?php echo tep_address_label($customer_id, 1, 1, ' ', '<br>'); ?></td>
                  </tr>
                </table></td>
                <td align="right" valign="middle" class="main"><input type="radio" name="sendto" value="1"<?php if ($sendto == '1') echo ' checked'; ?>></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="tableHeading"><?php echo TABLE_HEADING_ADDRESS_BOOK; ?></td>
                <td align="right" class="tableHeading"><?php echo TABLE_HEADING_DELIVER_TO; ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator(); ?></td>
          </tr>
<?php
  $address_book = tep_db_query("select address_book_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $customer_id . "' and address_book_id > 1 order by address_book_id");
  $row = 1;
  if (!tep_db_num_rows($address_book)) {
?>
          <tr>
            <td class="smallText"><?php echo TEXT_ADDRESS_BOOK_NO_ENTRIES; ?></td>
          </tr>
<?php
  } else {
?>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
    while ($address_book_values = tep_db_fetch_array($address_book)) {
      $row++;
      echo '              <tr class="shippingOptions-' . ($row / 2 == floor($row / 2) ? 'odd' : 'even') . '">' . "\n";
      echo '                <td align="right" valign="top" class="smallText">' . number_format($row - 1) . '.</td>' . "\n";
      echo '                <td class="smallText">' . tep_address_label($customer_id, $address_book_values['address_book_id'], true) . '</td>' . "\n";
      echo '                <td align="right" class="smallText"><input type="radio" name="sendto" value="' . $address_book_values['address_book_id'] . '"' . ($address_book_values['address_book_id'] == $sendto ? ' checked' : '') . '></td>' . "\n";
      echo '              </tr>' . "\n";
    }
?>
            </table></td>
          </tr>
<?php
  }
?>
          <tr>
            <td class="main"><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
<?php
  if ($row < MAX_ADDRESS_BOOK_ENTRIES) {
    echo '                <td class="main"><a href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'origin=' . FILENAME_CHECKOUT_ADDRESS . '&entry_id=' . ($row + 1), 'SSL') . '">' . tep_image_button('button_add_address.gif', IMAGE_BUTTON_ADD_ADDRESS) . '</a></td>' . "\n";
  } else {
    echo '                <td valign="top" class="smallText">' . TEXT_MAXIMUM_ENTRIES_REACHED . '</td>' . "\n";
  }
?>
                <td align="right" class="main"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="right" class="checkoutBar"><br>[ <span class="checkoutBarHighlighted"><?php echo CHECKOUT_BAR_DELIVERY_ADDRESS; ?></span> | <?php echo CHECKOUT_BAR_PAYMENT_METHOD; ?> | <?php echo CHECKOUT_BAR_CONFIRMATION; ?> | <?php echo CHECKOUT_BAR_FINISHED; ?> ]</td>
          </tr>
        </table></td>
      </tr>
    </table></form></td>
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
