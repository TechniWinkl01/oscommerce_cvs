<?php
/*
  $Id: address_book.php,v 1.40 2001/11/09 20:18:55 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADDRESS_BOOK);

  $location = ' : <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL') . '" class="headerNavigation">' . NAVBAR_TITLE_1 . '</a> : <a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'NONSSL') . '" class="headerNavigation">' . NAVBAR_TITLE_2 . '</a>';

// send to login when there is no Customer_id
  if (!@tep_session_is_registered('customer_id')) {
    tep_redirect(tep_href_link(FILENAME_LOGIN, 'origin=' . FILENAME_ADDRESS_BOOK, 'NONSSL'));
  }
?>
<html>
<head>
<title><?php echo TITLE; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td width="100%" class="topBarTitle">&nbsp;<?php echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<?php echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'table_background_address_book.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td align="center" class="tableHeading">&nbsp;<?php echo TABLE_HEADING_NUMBER; ?>&nbsp;</td>
            <td class="tableHeading">&nbsp;<?php echo TABLE_HEADING_NAME; ?>&nbsp;</td>
            <td align="center" class="tableHeading">&nbsp;<?php echo TABLE_HEADING_CITY_COUNTRY; ?>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3"><?php echo tep_black_line(); ?></td>
          </tr>
<?php
// get all address_book entries of this customer with an address_book_id > 1
  $address_book = tep_db_query("select address_book_id, entry_firstname, entry_lastname, entry_city, entry_country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $customer_id . "' and  address_book_id > 1 order by address_book_id");
  if (!@tep_db_num_rows($address_book)) {
?>
          <tr class="addressBook-odd">
            <td colspan="3" class="smallText">&nbsp;<?php echo TEXT_NO_ENTRIES_IN_ADDRESS_BOOK; ?>&nbsp;</td>
          </tr>
<?php
// We have more addresses! Let's build a list
  } else {
    $row = 0;
    while ($address_book_values = tep_db_fetch_array($address_book)) {
      $row++;
      $entry_country = tep_get_countries($address_book_values['entry_country_id']);
      if (($row / 2) == floor($row / 2)) {
        echo '          <tr class="addressBook-even">' . "\n";
      } else {
        echo '          <tr class="addressBook-odd">' . "\n";
      }
      echo '            <td align="center" class="smallText">&nbsp;0' . $row . '.&nbsp;</td>' . "\n";
      echo '            <td class="smallText">&nbsp;<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'action=modify&entry_id=' . $address_book_values['address_book_id'], 'NONSSL') . '">' . $address_book_values['entry_firstname'] . ' ' . $address_book_values['entry_lastname'] . '</a>&nbsp;</td>' . "\n";
      echo '            <td align="center" class="smallText">&nbsp;' . tep_address_summary($customer_id, $address_book_values['address_book_id']) . '&nbsp;</td>' . "\n";
      echo '          </tr>' . "\n";
    }
  }
?>
          <tr>
            <td colspan="3"><?php echo tep_black_line(); ?></td>
          </tr>
<?php
// Is the maximum number of addresses already used?
  if ($row < MAX_ADDRESS_BOOK_ENTRIES) {
?>
          <tr>
            <td colspan="3" class="smallText"><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td valign="top" class="smallText">&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?><br><br><?php echo sprintf(TEXT_MAXIMUM_ENTRIES, (MAX_ADDRESS_BOOK_ENTRIES - $row)); ?></td>
                <td align="right" valign="top" class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS,  'entry_id=' . ($row + 2), 'NONSSL') . '">' . tep_image_button('button_add_address.gif', IMAGE_BUTTON_ADD_ADDRESS) . '</a>'; ?>&nbsp;&nbsp;</td>
              </tr>
            </table></td>
          </tr>
<?php
  } else {
?>
          <tr>
            <td colspan="3" class="smallText"><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText">&nbsp;&nbsp;<?php echo sprintf(TEXT_MAXIMUM_ENTRIES_REACHED, MAX_ADDRESS_BOOK_ENTRIES); ?></td>
                <td align="right" class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?>&nbsp;&nbsp;</td>
              </tr>
            </table></td>
          </tr>
<?php
  }
?>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
        </table></td>
      </tr>
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