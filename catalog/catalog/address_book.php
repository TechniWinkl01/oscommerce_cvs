<? include('includes/application_top.php'); ?>
<? $include_file = DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADDRESS_BOOK; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_1 . '</a> : <a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_2 . '</a>'; ?>
<?
  if (!@tep_session_is_registered('customer_id')) {
    header('Location: ' . tep_href_link(FILENAME_LOGIN, 'origin=' . FILENAME_ADDRESS_BOOK, 'NONSSL'));
    tep_exit();
  }
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td width="100%" class="topBarTitle" nowrap>&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading" nowrap>&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'table_background_address_book.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td align="center" nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?><b>&nbsp;<? echo TABLE_HEADING_NUMBER; ?>&nbsp;</b></font></td>
            <td nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?><b>&nbsp;<? echo TABLE_HEADING_NAME; ?>&nbsp;</b></font></td>
            <td align="center" nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?><b>&nbsp;<? echo TABLE_HEADING_CITY_COUNTRY; ?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td colspan="3"><? echo tep_black_line(); ?></td>
          </tr>
<?
  $address_book = tep_db_query("select address_book.address_book_id, address_book.entry_firstname, address_book.entry_lastname, address_book.entry_city, address_book.entry_country_id from address_book, address_book_to_customers where address_book_to_customers.customers_id = '" . $customer_id . "' and address_book_to_customers.address_book_id = address_book.address_book_id order by address_book.address_book_id");
  if (!tep_db_num_rows($address_book)) {
?>
          <tr bgcolor="<? echo TABLE_ALT_BACKGROUND_COLOR; ?>">
            <td colspan="3" class="smallText" nowrap>&nbsp;<? echo TEXT_NO_ENTRIES_IN_ADDRESS_BOOK; ?>&nbsp;</td>
          </tr>
<?
  } else {
    $row = 0;
    while ($address_book_values = tep_db_fetch_array($address_book)) {
      $row++;
      $entry_country = tep_get_countries($address_book_values['entry_country_id']);
      if (($row / 2) == floor($row / 2)) {
        echo '          <tr bgcolor="' . TABLE_ROW_BACKGROUND_COLOR . '">' . "\n";
      } else {
        echo '          <tr bgcolor="' . TABLE_ALT_BACKGROUND_COLOR . '">' . "\n";
      }
      echo '            <td align="center" class="smallText" nowrap>&nbsp;0' . $row . '.&nbsp;</td>' . "\n";
      echo '            <td class="smallText" nowrap>&nbsp;<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'action=modify&entry_id=' . $address_book_values['address_book_id'], 'NONSSL') . '">' . $address_book_values['entry_firstname'] . ' ' . $address_book_values['entry_lastname'] . '</a>&nbsp;</td>' . "\n";
      echo '            <td align="center" class="smallText" nowrap>&nbsp;' . tep_address_summary($customer_id, $address_book_values['address_book_id']) . '&nbsp;</td>' . "\n";
      echo '          </tr>' . "\n";
    }
  }
?>
          <tr>
            <td colspan="3"><? echo tep_black_line(); ?></td>
          </tr>
<?
  if ($row < MAX_ADDRESS_BOOK_ENTRIES) {
?>
          <tr>
            <td colspan="2" class="smallText" nowrap>&nbsp;<? echo TEXT_MAXIMUM_ENTRIES; ?>&nbsp;</td>
            <td align="right" class="smallText" nowrap><br>&nbsp;<a href="<? echo tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, '', 'NONSSL'); ?>"><? echo tep_image(DIR_WS_IMAGES . 'button_add_entry.gif', IMAGE_ADD_ENTRY); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<? echo tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL'); ?>"><? echo tep_image(DIR_WS_IMAGES . 'button_back.gif', IMAGE_BACK); ?></a>&nbsp;&nbsp;</td>
          </tr>
<?
  } else {
?>
          <tr>
            <td colspan="2" class="smallText" nowrap>&nbsp;<? echo TEXT_MAXIMUM_ENTRIES_REACHED; ?>&nbsp;</td>
            <td align="right" class="smallText" nowrap><br>&nbsp;<a href="<? echo tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL'); ?>"><? echo tep_image(DIR_WS_IMAGES . 'button_back.gif', IMAGE_BACK); ?></a>&nbsp;&nbsp;</td>
          </tr>
<?
  }
?>
        </table></td>
      </tr>
    </table></td>
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