<? include('includes/application_top.php'); ?>
<?
  if (!tep_session_is_registered('customer_id')) {
    if (getenv(HTTPS)) {
      $connection = 'secure';
    } else {
      $connection = 'normal';
    }
    header('Location: ' . tep_href_link(FILENAME_LOGIN, 'origin=checkout_address&connection=' . $connection, 'NONSSL'));
    tep_exit();
  }
?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_ADDRESS; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_CHECKOUT, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_1 . '</a> : ' . NAVBAR_TITLE_2; ?>
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
    <td width="100%" valign="top"><form name="checkout_address" method="post" action="<? echo tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL');?>"><table border="0" width="100%" cellspacing="0" cellpadding="0">
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
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_IMAGES . 'table_background_delivery.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line();?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
<?
   if (!SHIPPING_FREE) {
     if (SHIPPING_MODEL == SHIPPING_UPS) {
?>
                <td nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><? echo TABLE_HEADING_SHIPPING_INFO;?></b>&nbsp;</font><br><br></td>
              </tr>
              <tr>
                <td><SELECT NAME="prod">
                  <OPTION SELECTED VALUE="GND">UPS Ground</OPTION>
                  <OPTION VALUE="1DM">Next Day Air Early AM</OPTION>
                  <OPTION VALUE="1DA">Next Day Air</OPTION>
                  <OPTION VALUE="1DP">Next Day Air Saver</OPTION>
                  <OPTION VALUE="2DM">2nd Day Air Early AM</OPTION>
                  <OPTION VALUE="3DS">3 Day Select</OPTION>
                  <OPTION VALUE="STD">Canada Standard</OPTION>
                  <OPTION VALUE="XPR">Worldwide Express</OPTION>
                  <OPTION VALUE="XDM">Worldwide Express Plus</OPTION>
                  <OPTION VALUE="XPD">Worldwide Expedited</OPTION></SELECT><br></td>
            </tr>
            </table></td>
          </tr>
          <tr>
            <td><? echo tep_black_line();?></td>
          </tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
<?
      }
    }
?>
                <td nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><? echo TABLE_HEADING_MY_ADDRESS;?></b>&nbsp;</font></td>
                <td align="right" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><? echo TABLE_HEADING_DELIVER_TO;?></b>&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><? echo tep_black_line();?></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td nowrap><font face="<? echo TEXT_FONT_FACE;?>" size="<? echo TEXT_FONT_SIZE;?>" color="<? echo TEXT_FONT_COLOR;?>"><? echo tep_address_label($customer_id, 0, 1, '&nbsp;', '<br>');?>&nbsp;</font></td>
                  </tr>
                </table></td>
                <td align="right" valign="middle"><font face="<? echo TEXT_FONT_FACE;?>" size="<? echo TEXT_FONT_SIZE;?>" color="<? echo TEXT_FONT_COLOR;?>">&nbsp;<input type="radio" name="sendto" value="0" CHECKED>&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><? echo TABLE_HEADING_ADDRESS_BOOK;?></b>&nbsp;</font></td>
                <td align="right" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><? echo TABLE_HEADING_DELIVER_TO;?></b>&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><? echo tep_black_line();?></td>
          </tr>
<?
  $address_book = tep_db_query("select address_book_id from address_book_to_customers where customers_id = '" . $customer_id . "' order by address_book_id");
  if (!@tep_db_num_rows($address_book)) {
?>
          <tr>
            <td nowrap><font face="<? echo SMALL_TEXT_FONT_FACE;?>" size="<? echo SMALL_TEXT_FONT_SIZE;?>" color="<? echo SMALL_TEXT_FONT_COLOR;?>">&nbsp;<? echo TEXT_ADDRESS_BOOK_NO_ENTRIES;?>&nbsp;</font></td>
          </tr>
<?
  } else {
?>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?
    $row = 0;
    $boln = '<td nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;';
    $eoln = '&nbsp;</font></td>' . "\n";
    while ($address_book_values = tep_db_fetch_array($address_book)) {
      $row++;
      echo '              <tr>' . "\n";
      echo '                <td nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;0' . $row . '.&nbsp;</font></td>' . "\n";
      echo tep_address_label($customer_id, $address_book_values['address_book_id'], 1, $boln, $eoln);
      echo '                <td nowrap align="right"><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;<input type="radio" name="sendto" value="' . $address_book_values['address_book_id'] . '">&nbsp;</font></td>' . "\n";
      echo '              </tr>' . "\n";
    }
?>
            </table></td>
          </tr>
<?
  }
?>
          <tr>
            <td><? echo tep_black_line();?></td>
          </tr>
          <tr>
            <td><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
<?
  if ($row < 5) {
    if (getenv(HTTPS)) {
      $connection = 'secure';
    } else {
      $connection = 'normal';
    }
    echo '                <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'origin=checkout_address&connection=' . $connection, 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_add_entry.gif', '113', '24', '0', IMAGE_ADD_ENTRY) . '</a>&nbsp;</font></td>' . "\n";
  } else {
    echo '                <td valign="top" nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;' . TEXT_MAXIMUM_ENTRIES_REACHED . '&nbsp;</font></td>' . "\n";
  }
?>
                <td align="right" nowrap><font face="<? echo TEXT_FONT_FACE;?>" size="<? echo TEXT_FONT_SIZE;?>" color="<? echo TEXT_FONT_COLOR;?>">&nbsp;<? echo tep_image_submit(DIR_IMAGES . 'button_next.gif', '50', '24', '0', IMAGE_NEXT);?>&nbsp;&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="right"><br><font face="<? echo SMALL_TEXT_FONT_FACE;?>" size="<? echo SMALL_TEXT_FONT_SIZE;?>" color="<? echo SMALL_TEXT_FONT_COLOR;?>">&nbsp;<font color="<? echo CHECKOUT_BAR_TEXT_COLOR;?>">[ <? echo CHECKOUT_BAR_CART_CONTENTS;?> | <font color="<? echo CHECKOUT_BAR_TEXT_COLOR_HIGHLIGHTED;?>"><? echo CHECKOUT_BAR_DELIVERY_ADDRESS;?></font> | <? echo CHECKOUT_BAR_PAYMENT_METHOD;?> | <? echo CHECKOUT_BAR_CONFIRMATION;?> | <? echo CHECKOUT_BAR_FINISHED;?> ]</font>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
    </table></form></td>
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
