<?php
/*
  $Id: address_book.php,v 1.55 2003/02/13 01:58:23 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADDRESS_BOOK);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
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
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_address_book.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="tableHeading" align="center"><?php echo TABLE_HEADING_NUMBER; ?></td>
            <td class="tableHeading"><?php echo TABLE_HEADING_NAME; ?></td>
            <td class="tableHeading" align="right"><?php echo TABLE_HEADING_LOCATION; ?></td>
          </tr>
          <tr>
            <td colspan="3"><?php echo tep_draw_separator(); ?></td>
          </tr>
<?php
  $address_book_query = tep_db_query("select address_book_id, entry_firstname, entry_lastname from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $customer_id . "' and address_book_id > 1 order by address_book_id");
  if (!tep_db_num_rows($address_book_query)) {
?>
          <tr class="addressBook-odd">
            <td colspan="3" class="smallText"><?php echo TEXT_NO_ENTRIES_IN_ADDRESS_BOOK; ?></td>
          </tr>
<?php
  } else {
    $row = 0;
    while ($address_book = tep_db_fetch_array($address_book_query)) {
      $row++;
       if (($row / 2) == floor($row / 2)) {
        echo '          <tr class="addressBook-even">' . "\n";
      } else {
        echo '          <tr class="addressBook-odd">' . "\n";
      }
      echo '            <td class="smallText" align="center">' . tep_row_number_format($row) . '.</td>' . "\n" .
           '            <td class="smallText"><a href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'action=modify&entry_id=' . $address_book['address_book_id'], 'SSL') . '">' . $address_book['entry_firstname'] . ' ' . $address_book['entry_lastname'] . '</a></td>' . "\n" .
           '            <td class="smallText" align="right">' . tep_address_summary($customer_id, $address_book['address_book_id']) . '</td>' . "\n" .
           '          </tr>' . "\n";
    }
  }
?>
          <tr>
            <td colspan="3"><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
<?php
  if ($row < MAX_ADDRESS_BOOK_ENTRIES) {
?>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a><br><br>' . sprintf(TEXT_MAXIMUM_ENTRIES, MAX_ADDRESS_BOOK_ENTRIES); ?></td>
                <td class="smallText" align="right" valign="top"><?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS,  'entry_id=' . ($row + 2), 'SSL') . '">' . tep_image_button('button_add_address.gif', IMAGE_BUTTON_ADD_ADDRESS) . '</a>'; ?></td>
              </tr>
            </table></td>
          </tr>
<?php
  } else {
?>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText"><?php echo sprintf(TEXT_MAXIMUM_ENTRIES_REACHED, MAX_ADDRESS_BOOK_ENTRIES); ?></td>
                <td class="smallText" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
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
