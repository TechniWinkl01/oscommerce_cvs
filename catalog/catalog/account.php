<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_ACCOUNT; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE . '</a>'; ?>
<?
  if (!@tep_session_is_registered('customer_id')) {
    header('Location: ' . tep_href_link(FILENAME_LOGIN, 'origin=' . FILENAME_ACCOUNT, 'NONSSL'));
    tep_exit();
  }
?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_INCLUDES . 'header.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_INCLUDES . 'column_left.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
<?
  $account_query = 'select ';
  if (ACCOUNT_GENDER) {
    $account_query = $account_query . 'customers_gender, ';
  }
  $account_query = $account_query . 'customers_firstname, customers_lastname, ';
  if (ACCOUNT_DOB) {
    $account_query = $account_query . 'customers_dob, ';
  }
  $account_query = $account_query . 'customers_email_address, customers_street_address, ';
  if (ACCOUNT_SUBURB) {
    $account_query = $account_query . 'customers_suburb, ';
  }
  $account_query = $account_query . 'customers_postcode, customers_city, ';
  if (ACCOUNT_STATE) {
    $account_query = $account_query . 'customers_zone_id, customers_state, ';
  }
  $account_query = $account_query . "customers_country_id, customers_telephone, customers_fax from customers where customers_id = '" . $customer_id . "'";
  $account = tep_db_query($account_query);
  $account_values = tep_db_fetch_array($account);

  $customers_country = tep_get_countries($account_values['customers_country_id']);
  $rowspan = 5+ACCOUNT_GENDER+ACCOUNT_DOB;
?>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
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
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_IMAGES . 'table_background_account.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td width="100%"><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="<? echo $rowspan; ?>" nowrap><?php echo FONT_STYLE_ACCOUNT_CATEGORY; ?><? echo CATEGORY_PERSONAL; ?></font></td>
          </tr>
<?
   if (ACCOUNT_GENDER) {
?>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_GENDER; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<?
  if ($account_values['customers_gender'] == 'm') {
    echo MALE;
  } else {
    echo FEMALE;
  } ?>&nbsp;</font></td>
          </tr>
<?
   }
?>
          <tr>
            <td colspan="2"><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_FIRST_NAME; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<? echo $account_values['customers_firstname']; ?>&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_LAST_NAME; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<? echo $account_values['customers_lastname']; ?>&nbsp;</font></td>
          </tr>
<?
   if (ACCOUNT_DOB) {
?>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_DATE_OF_BIRTH; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<? echo date(DATE_FORMAT, mktime(0, 0, 0, substr($account_values['customers_dob'], 4, 2), substr($account_values['customers_dob'], 6, 2), substr($account_values['customers_dob'], 0, 4))); ?>&nbsp;</font></td>
          </tr>
<?
   }
?>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_EMAIL_ADDRESS; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<? echo $account_values['customers_email_address']; ?>&nbsp;</font></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="top" colspan="2" rowspan="3" nowrap><?php echo FONT_STYLE_ACCOUNT_CATEGORY; ?><? echo CATEGORY_ADDRESS; ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;&nbsp;</font></td>
            <td align="left" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?><? echo tep_address_label($customer_id, 0, 1, '&nbsp;', "<br>"); ?></font></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="3" nowrap><?php echo FONT_STYLE_ACCOUNT_CATEGORY; ?><? echo CATEGORY_CONTACT; ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_TELEPHONE_NUMBER; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<? echo $account_values['customers_telephone']; ?>&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_FAX_NUMBER; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<? echo $account_values['customers_fax']; ?>&nbsp;</font></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="3" nowrap><?php echo FONT_STYLE_ACCOUNT_CATEGORY; ?><? echo CATEGORY_PASSWORD; ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_PASSWORD; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<? echo PASSWORD_HIDDEN; ?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td align="right"><br><?php echo FONT_STYLE_MAIN; ?><a href="<? echo tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'NONSSL'); ?>"><? echo tep_image(DIR_IMAGES . 'button_edit_account.gif', IMAGE_EDIT_ACCOUNT); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<? echo tep_href_link(FILENAME_ADDRESS_BOOK, '', 'NONSSL'); ?>"><? echo tep_image(DIR_IMAGES . 'button_address_book.gif', IMAGE_ADDRESS_BOOK); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<? echo tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'NONSSL'); ?>"><? echo tep_image(DIR_IMAGES . 'button_history.gif', IMAGE_HISTORY); ?></a>&nbsp;&nbsp;</font></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
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
