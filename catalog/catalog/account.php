<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_ACCOUNT; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE . '</a>'; ?>
<?
  if (!@tep_session_is_registered('customer_id')) {
    header('Location: ' . tep_href_link(FILENAME_LOGIN, 'origin=account', 'NONSSL'));
    tep_exit();
  }
?>
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
            <td bgcolor="<? echo TOP_BAR_BACKGROUND_COLOR;?>" width="100%" nowrap><font face="<? echo TOP_BAR_FONT_FACE;?>" size="<? echo TOP_BAR_FONT_SIZE;?>" color="<? echo TOP_BAR_FONT_COLOR;?>">&nbsp;<? echo TOP_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<? echo HEADING_FONT_FACE;?>" size="<? echo HEADING_FONT_SIZE;?>" color="<? echo HEADING_FONT_COLOR;?>">&nbsp;<? echo HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_IMAGES . 'table_background_account.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line();?></td>
      </tr>
      <tr>
        <td width="100%"><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="<? echo $rowspan;?>" nowrap><font face="<? echo CATEGORY_FONT_FACE;?>" size="<? echo CATEGORY_FONT_SIZE;?>" color="<? echo CATEGORY_FONT_COLOR;?>"><? echo CATEGORY_PERSONAL;?></font></td>
          </tr>
<?
   if (ACCOUNT_GENDER) {
?>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;<? echo ENTRY_GENDER;?>&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE;?>" size="<? echo VALUE_FONT_SIZE;?>" color="<? echo VALUE_FONT_COLOR;?>">&nbsp;<?
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
            <td colspan="2"><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;<? echo ENTRY_FIRST_NAME;?>&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE;?>" size="<? echo VALUE_FONT_SIZE;?>" color="<? echo VALUE_FONT_COLOR;?>">&nbsp;<? echo $account_values['customers_firstname'];?>&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;<? echo ENTRY_LAST_NAME;?>&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE;?>" size="<? echo VALUE_FONT_SIZE;?>" color="<? echo VALUE_FONT_COLOR;?>">&nbsp;<? echo $account_values['customers_lastname'];?>&nbsp;</font></td>
          </tr>
<?
   if (ACCOUNT_DOB) {
?>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;<? echo ENTRY_DATE_OF_BIRTH;?>&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE;?>" size="<? echo VALUE_FONT_SIZE;?>" color="<? echo VALUE_FONT_COLOR;?>">&nbsp;<?
  $dob_formatted = date(DATE_FORMAT_SHORT, mktime(0,0,0,substr($account_values['customers_dob'], 4, 2),substr($account_values['customers_dob'], -2),substr($account_values['customers_dob'], 0, 4)));
  echo strftime($dob_formatted); ?>&nbsp;</font></td>
          </tr>
<?
   }
?>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;<? echo ENTRY_EMAIL_ADDRESS;?>&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE;?>" size="<? echo VALUE_FONT_SIZE;?>" color="<? echo VALUE_FONT_COLOR;?>">&nbsp;<? echo $account_values['customers_email_address'];?>&nbsp;</font></td>
          </tr>
          <tr>
            <td colspan="2"><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="top" colspan="2" rowspan="3" nowrap><font face="<? echo CATEGORY_FONT_FACE;?>" size="<? echo CATEGORY_FONT_SIZE;?>" color="<? echo CATEGORY_FONT_COLOR;?>"><? echo CATEGORY_ADDRESS;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;&nbsp;</font></td>
            <td align="left" nowrap><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>"><? echo tep_address_label($customer_id, 0, 1, '&nbsp;', "<br>");?></font></td>
          </tr>
          <tr>
            <td colspan="2"><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="3" nowrap><font face="<? echo CATEGORY_FONT_FACE;?>" size="<? echo CATEGORY_FONT_SIZE;?>" color="<? echo CATEGORY_FONT_COLOR;?>"><? echo CATEGORY_CONTACT;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;<? echo ENTRY_TELEPHONE_NUMBER;?>&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE;?>" size="<? echo VALUE_FONT_SIZE;?>" color="<? echo VALUE_FONT_COLOR;?>">&nbsp;<? echo $account_values['customers_telephone'];?>&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;<? echo ENTRY_FAX_NUMBER;?>&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE;?>" size="<? echo VALUE_FONT_SIZE;?>" color="<? echo VALUE_FONT_COLOR;?>">&nbsp;<? echo $account_values['customers_fax'];?>&nbsp;</font></td>
          </tr>
          <tr>
            <td colspan="2"><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="3" nowrap><font face="<? echo CATEGORY_FONT_FACE;?>" size="<? echo CATEGORY_FONT_SIZE;?>" color="<? echo CATEGORY_FONT_COLOR;?>"><? echo CATEGORY_PASSWORD;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;<? echo ENTRY_PASSWORD;?>&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE;?>" size="<? echo VALUE_FONT_SIZE;?>" color="<? echo VALUE_FONT_COLOR;?>">&nbsp;<? echo PASSWORD_HIDDEN;?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><? echo tep_black_line();?></td>
      </tr>
      <tr>
        <td align="right"><br><font face="<? echo TEXT_FONT_FACE;?>" size="<? echo TEXT_FONT_SIZE;?>" color="<? echo TEXT_FONT_COLOR;?>"><a href="<? echo tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'NONSSL');?>"><? echo tep_image(DIR_IMAGES . 'button_edit_account.gif', '124', '24', '0', IMAGE_EDIT_ACCOUNT);?></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<? echo tep_href_link(FILENAME_ADDRESS_BOOK, '', 'NONSSL');?>"><? echo tep_image(DIR_IMAGES . 'button_address_book.gif', '130', '24', '0', IMAGE_ADDRESS_BOOK);?></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<? echo tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'NONSSL');?>"><? echo tep_image(DIR_IMAGES . 'button_history.gif', '80', '24', '0', IMAGE_HISTORY);?></a>&nbsp;&nbsp;</font></td>
      </tr>
    </table></td>
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
