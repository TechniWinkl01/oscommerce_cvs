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
    <td width="100%" valign="top"><form name="checkout_address" method="post" action="<? echo tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'); ?>"><table border="0" width="100%" cellspacing="0" cellpadding="0">
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
            <td align="right">&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'table_background_delivery.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?
   if (MODULE_SHIPPING_INSTALLED) {
?>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="tableHeading">&nbsp;<? echo TABLE_HEADING_SHIPPING_INFO; ?>&nbsp;</td>
                <td align="right" class="tableHeading">&nbsp;<? echo TABLE_HEADING_SHIPPING_QUOTE; ?>&nbsp;</td>
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
                <td class="tableHeading">&nbsp;<? echo TABLE_HEADING_MY_ADDRESS; ?>&nbsp;</td>
                <td align="right" class="tableHeading">&nbsp;<? echo TABLE_HEADING_DELIVER_TO; ?>&nbsp;</td>
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
                    <td class="main"><? echo tep_address_label($customer_id, 1, 1, '&nbsp;', '<br>'); ?>&nbsp;</td>
                  </tr>
                </table></td>
                <td align="right" valign="middle" class="main">&nbsp;<input type="radio" name="sendto" value="1" CHECKED>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="tableHeading">&nbsp;<? echo TABLE_HEADING_ADDRESS_BOOK; ?>&nbsp;</td>
                <td align="right" class="tableHeading">&nbsp;<? echo TABLE_HEADING_DELIVER_TO; ?>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
<?
  $address_book = tep_db_query("select address_book_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $customer_id . "' and address_book_id > 1 order by address_book_id");
  $row = 1;
  if (!tep_db_num_rows($address_book)) {
?>
          <tr>
            <td class="smallText">&nbsp;<? echo TEXT_ADDRESS_BOOK_NO_ENTRIES; ?>&nbsp;</td>
          </tr>
<?
  } else {
?>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?
    $boln = '<td class="smallText">&nbsp;';
    $eoln = '&nbsp;</td>' . "\n";
    while ($address_book_values = tep_db_fetch_array($address_book)) {
      $row++;
      echo '              <tr>' . "\n";
      echo '                <td class="smallText">&nbsp;0' . $row . '.&nbsp;</td>' . "\n";
      echo tep_address_label($customer_id, $address_book_values['address_book_id'], 1, $boln, $eoln);
      echo '                <td align="right" class="smallText">&nbsp;<input type="radio" name="sendto" value="' . $address_book_values['address_book_id'] . '">&nbsp;</td>' . "\n";
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
            <td class="main"><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
<?
  if ($row < MAX_ADDRESS_BOOK_ENTRIES) {
    echo '                <td class="main">&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'origin=' . FILENAME_CHECKOUT_ADDRESS . '&connection=' . $connection . '&entry_id=' . ($row + 1), 'NONSSL') . '">' . tep_image_button('button_add_address.gif', IMAGE_BUTTON_ADD_ADDRESS) . '</a>&nbsp;</td>' . "\n";
  } else {
    echo '                <td valign="top" class="smallText">&nbsp;' . TEXT_MAXIMUM_ENTRIES_REACHED . '&nbsp;</td>' . "\n";
  }
?>
                <td align="right" class="main">&nbsp;<? echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?>&nbsp;&nbsp;</td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="right" class="smallText"><br>&nbsp;<font color="<? echo CHECKOUT_BAR_TEXT_COLOR; ?>">[ <font color="<? echo CHECKOUT_BAR_TEXT_COLOR_HIGHLIGHTED; ?>"><? echo CHECKOUT_BAR_DELIVERY_ADDRESS; ?></font> | <? echo CHECKOUT_BAR_PAYMENT_METHOD; ?> | <? echo CHECKOUT_BAR_CONFIRMATION; ?> | <? echo CHECKOUT_BAR_FINISHED; ?> ]</font>&nbsp;</td>
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
