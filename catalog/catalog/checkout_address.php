<? include('includes/application_top.php'); ?>
<?
  if (getenv(HTTPS)) {
    $connection = 'SSL';
  } else {
    $connection = 'NONSSL';
  }
  if (!tep_session_is_registered('customer_id')) {
    header('Location: ' . tep_href_link(FILENAME_LOGIN, 'origin=' . FILENAME_CHECKOUT_ADDRESS . '&connection=' . $connection, 'NONSSL'));
    tep_exit();
  }
?>
<? $include_file = DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_ADDRESS; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_CHECKOUT_ADDRESS, '', 'SSL') . '" class="whitelink">' . NAVBAR_TITLE_1 . '</a> : ' . NAVBAR_TITLE_2; ?>
<?php
  include(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping;
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
    <td width="100%" valign="top"><form name="checkout_address" method="post" action="<? echo tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'); ?>"><table border="0" width="100%" cellspacing="0" cellpadding="0">
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
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'table_background_delivery.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?
   if (SHIPPING_MODULES != '') {
?>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?>&nbsp;<b><? echo TABLE_HEADING_SHIPPING_INFO; ?></b>&nbsp;</font></td>
                <td nowrap align="right"><?php echo FONT_STYLE_TABLE_HEADING; ?>&nbsp;<b><? echo TABLE_HEADING_SHIPPING_QUOTE; ?></b>&nbsp;</font></td>
              </tr>
            </table></td>
            <tr>
              <td><? echo tep_black_line(); ?></td>
            </tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?
  $shipping_modules->select();
?>
            </table></td>
          </tr>
<?
    }
?>
            <td><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?>&nbsp;<b><? echo TABLE_HEADING_MY_ADDRESS; ?></b>&nbsp;</font></td>
                <td align="right" nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?>&nbsp;<b><? echo TABLE_HEADING_DELIVER_TO; ?></b>&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td nowrap><?php echo FONT_STYLE_MAIN; ?><? echo tep_address_label($customer_id, 0, 1, '&nbsp;', '<br>'); ?>&nbsp;</font></td>
                  </tr>
                </table></td>
                <td align="right" valign="middle"><?php echo FONT_STYLE_MAIN; ?>&nbsp;<input type="radio" name="sendto" value="0" CHECKED>&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?>&nbsp;<b><? echo TABLE_HEADING_ADDRESS_BOOK; ?></b>&nbsp;</font></td>
                <td align="right" nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?>&nbsp;<b><? echo TABLE_HEADING_DELIVER_TO; ?></b>&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
<?
  $address_book = tep_db_query("select address_book_id from address_book_to_customers where customers_id = '" . $customer_id . "' order by address_book_id");
  if (!@tep_db_num_rows($address_book)) {
?>
          <tr>
            <td nowrap><?php echo FONT_STYLE_SMALL_TEXT; ?>&nbsp;<? echo TEXT_ADDRESS_BOOK_NO_ENTRIES; ?>&nbsp;</font></td>
          </tr>
<?
  } else {
?>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?
    $row = 0;
    $boln = '<td nowrap>' . FONT_STYLE_SMALL_TEXT . '&nbsp;';
    $eoln = '&nbsp;</font></td>' . "\n";
    while ($address_book_values = tep_db_fetch_array($address_book)) {
      $row++;
      echo '              <tr>' . "\n";
      echo '                <td nowrap>' . FONT_STYLE_SMALL_TEXT . '&nbsp;0' . $row . '.&nbsp;</font></td>' . "\n";
      echo tep_address_label($customer_id, $address_book_values['address_book_id'], 1, $boln, $eoln);
      echo '                <td nowrap align="right">' . FONT_STYLE_SMALL_TEXT . '&nbsp;<input type="radio" name="sendto" value="' . $address_book_values['address_book_id'] . '">&nbsp;</font></td>' . "\n";
      echo '              </tr>' . "\n";
    }
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
            <td><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
<?
  if ($row < 5) {
    echo '                <td nowrap>' . FONT_STYLE_MAIN . '&nbsp;<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'origin=' . FILENAME_CHECKOUT_ADDRESS . '&connection=' . $connection, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_add_entry.gif', IMAGE_ADD_ENTRY) . '</a>&nbsp;</font></td>' . "\n";
  } else {
    echo '                <td valign="top" nowrap>' . FONT_STYLE_SMALL_TEXT . '&nbsp;' . TEXT_MAXIMUM_ENTRIES_REACHED . '&nbsp;</font></td>' . "\n";
  }
?>
                <td align="right" nowrap><?php echo FONT_STYLE_MAIN; ?>&nbsp;<? echo tep_image_submit(DIR_WS_IMAGES . 'button_next.gif', IMAGE_NEXT); ?>&nbsp;&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="right"><br><?php echo FONT_STYLE_SMALL_TEXT; ?>&nbsp;<font color="<? echo CHECKOUT_BAR_TEXT_COLOR; ?>">[ <font color="<? echo CHECKOUT_BAR_TEXT_COLOR_HIGHLIGHTED; ?>"><? echo CHECKOUT_BAR_DELIVERY_ADDRESS; ?></font> | <? echo CHECKOUT_BAR_PAYMENT_METHOD; ?> | <? echo CHECKOUT_BAR_CONFIRMATION; ?> | <? echo CHECKOUT_BAR_FINISHED; ?> ]</font>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
    </table></form></td>
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
