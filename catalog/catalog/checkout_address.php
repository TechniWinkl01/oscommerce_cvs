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
    <td width="100%" valign="top"><form name="checkout_address" method="post" action="<?=tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL');?>"><table border="0" width="100%" cellspacing="0" cellpadding="0">
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
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_IMAGES . 'table_background_delivery.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?=tep_black_line();?></td>
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
                <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><?=TABLE_HEADING_SHIPPING_INFO;?></b>&nbsp;</font><br><br></td>
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
            <td><?=tep_black_line();?></td>
          </tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
<?
      }
    }
?>
                <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><?=TABLE_HEADING_MY_ADDRESS;?></b>&nbsp;</font></td>
                <td align="right" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><?=TABLE_HEADING_DELIVER_TO;?></b>&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><?=tep_black_line();?></td>
          </tr>
<?
  $address = tep_db_query("select customers_firstname, customers_lastname, customers_street_address, customers_suburb, customers_postcode, customers_city, customers_state, customers_country_id, customers_zone_id from customers where customers_id = '" . $customer_id . "'");
  $address_values = tep_db_fetch_array($address);

  $customers_country = tep_get_countries($address_values['customers_country_id']);
?>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$address_values['customers_firstname'] . ' ' . $address_values['customers_lastname'];?>&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$address_values['customers_street_address'];?>&nbsp;</font></td>
                  </tr>
<?
  if ($address_values['customers_suburb'] != '') {
?>
                  <tr>
                    <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$address_values['customers_suburb'];?>&nbsp;</font></td>
                  </tr>
<?
  }
?>
                  <tr>
                    <td><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$address_values['customers_city'] . ', ' . $address_values['customers_postcode'];?>&nbsp;</font></td>
                  </tr>
                  <tr>
<?
  $state_country = '';
  if ($address_values['customers_zone_id'] != 0) {
     $state_country = tep_get_zone_name($address_values['customers_country_id'], $address_values['customers_zone_id'], $address_values['customers_state']) . ', ';
  } else {
    if ($address_values['customers_state'] != '') {
     $state_country = $address_values['customers_state'] . ', ';
    }
  }
  $state_country = $state_country . $customers_country['countries_name'];
?>
                    <td><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$state_country;?>&nbsp;</font></td>
                  </tr>
                </table></td>
                <td align="right" valign="middle"><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="radio" name="sendto" value="0" CHECKED>&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><?=TABLE_HEADING_ADDRESS_BOOK;?></b>&nbsp;</font></td>
                <td align="right" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><?=TABLE_HEADING_DELIVER_TO;?></b>&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><?=tep_black_line();?></td>
          </tr>
<?
  $address_book = tep_db_query("select address_book.address_book_id, address_book.entry_firstname, address_book.entry_lastname, address_book.entry_street_address, address_book.entry_suburb, address_book.entry_postcode, address_book.entry_city, address_book.entry_state, address_book.entry_zone_id, address_book.entry_country_id from address_book, address_book_to_customers where address_book_to_customers.customers_id = '" . $customer_id . "' and address_book_to_customers.address_book_id = address_book.address_book_id order by address_book.address_book_id");
  if (!@tep_db_num_rows($address_book)) {
?>
          <tr>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_ADDRESS_BOOK_NO_ENTRIES;?>&nbsp;</font></td>
          </tr>
<?
  } else {
?>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?
    $row = 0;
    while ($address_book_values = tep_db_fetch_array($address_book)) {
      $row++;
      $entry_country = tep_get_countries($address_book_values['entry_country_id']);
      echo '              <tr>' . "\n";
      echo '                <td nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;0' . $row . '.&nbsp;</font></td>' . "\n";
      echo '                <td nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;' . $address_book_values['entry_firstname'] . ' ' . $address_book_values['entry_lastname'] . '&nbsp;</font></td>' . "\n";
      echo '                <td nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;' . $address_book_values['entry_street_address'] . '&nbsp;</font></td>' . "\n";
      echo '                <td nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;' . $address_book_values['entry_suburb'] . '&nbsp;</font></td>' . "\n";
      echo '                <td nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;' . $address_book_values['entry_city'] . ', ' . $address_book_values['entry_postcode'] . '&nbsp;</font></td>' . "\n";
      echo '                <td nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;';
      if ($address_book_values['entry_zone_id'] != 0 || $address_book_values['entry_state'] != '') {
        echo tep_get_zone_name($address_book_values['entry_country_id'], $address_book_values['entry_zone_id'], $address_book_values['entry_state']) . ", ";
      }
      echo $entry_country['countries_name'] . '&nbsp;</font></td>' . "\n";
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
            <td><?=tep_black_line();?></td>
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
                <td align="right" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=tep_image_submit(DIR_IMAGES . 'button_next.gif', '50', '24', '0', IMAGE_NEXT);?>&nbsp;&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="right"><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<font color="<?=CHECKOUT_BAR_TEXT_COLOR;?>">[ <?=CHECKOUT_BAR_CART_CONTENTS;?> | <font color="<?=CHECKOUT_BAR_TEXT_COLOR_HIGHLIGHTED;?>"><?=CHECKOUT_BAR_DELIVERY_ADDRESS;?></font> | <?=CHECKOUT_BAR_PAYMENT_METHOD;?> | <?=CHECKOUT_BAR_CONFIRMATION;?> | <?=CHECKOUT_BAR_FINISHED;?> ]</font>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
    </table></form></td>
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
