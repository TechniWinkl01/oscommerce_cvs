<? include('includes/application_top.php'); ?>
<?
  if (!@$HTTP_POST_VARS['action']) {
    header('Location: ' . tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'NONSSL'));
    tep_exit();
  }

  $error = 0; // reset error flag

  if (($HTTP_POST_VARS['gender'] == 'm') || ($HTTP_POST_VARS['gender'] == 'f')) {
    $gender_error = 0;
  } else {
    $gender_error = 1;
    $error = 1;
  }

  if (strlen(trim($HTTP_POST_VARS['firstname'])) < ENTRY_FIRST_NAME_MIN_LENGTH) {
    $firstname_error = 1;
    $error = 1;
  } else {
    $firstname_error = 0;
  }

  if (strlen(trim($HTTP_POST_VARS['lastname'])) < ENTRY_LAST_NAME_MIN_LENGTH) {
    $lastname_error = 1;
    $error = 1;
  } else {
    $lasttname_error = 0;
  }

  if (checkdate(substr($HTTP_POST_VARS['dob'], 3, 2), substr($HTTP_POST_VARS['dob'], 0, 2),substr($HTTP_POST_VARS['dob'], -4))) {
    $dob_error = 0;
  } else {
    $dob_error = 1;
    $error = 1;
  }

  if (strlen(trim($HTTP_POST_VARS['email_address'])) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
    $email_address_error = 1;
    $error = 1;
  } else {
    $email_address_error = 0;
  }

  if (strlen(trim($HTTP_POST_VARS['street_address'])) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
    $street_address_error = 1;
    $error = 1;
  } else {
    $street_address_error = 0;
  }

  if (strlen(trim($HTTP_POST_VARS['postcode'])) < ENTRY_POSTCODE_MIN_LENGTH) {
    $postcode_error = 1;
    $error = 1;
  } else {
    $postcode_error = 0;
  }

  if (strlen(trim($HTTP_POST_VARS['city'])) < ENTRY_CITY_MIN_LENGTH) {
    $city_error = 1;
    $error = 1;
  } else {
    $city_error = 0;
  }

  if (strlen(trim($HTTP_POST_VARS['country'])) < ENTRY_COUNTRY_MIN_LENGTH) {
    $country_error = 1;
    $error = 1;
  } else {
    $country_error = 0;
  }

  if (strlen(trim($HTTP_POST_VARS['telephone'])) < ENTRY_TELEPHONE_MIN_LENGTH) {
    $telephone_error = 1;
    $error = 1;
  } else {
    $telephone_error = 0;
  }

  if (strlen(trim($HTTP_POST_VARS['password'])) < ENTRY_PASSWORD_MIN_LENGTH) {
    $password_error = 1;
    $error = 1;
  } else {
    $password_error = 0;
  }

  if ($error == 1) {
?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_EDIT_PROCESS; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_1 . '</a> : <a href="' . tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_2 . '</a>'; ?>
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
    <td width="100%" valign="top"><form name="account_edit" method="post" action="<?=tep_href_link(FILENAME_ACCOUNT_EDIT_PROCESS, '', 'NONSSL');?>"><input type="hidden" name="action" value="process"><table border="0" width="100%" cellspacing="0" cellpadding="0">
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
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_IMAGES . 'table_background_account.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td width="100%"><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="7" nowrap><font face="<?=CATEGORY_FONT_FACE;?>" size="<?=CATEGORY_FONT_SIZE;?>" color="<?=CATEGORY_FONT_COLOR;?>"><?=CATEGORY_PERSONAL;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_GENDER;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<?
    if ($gender_error == 1) {
      echo '<input type="radio" name="gender" value="m">&nbsp;' . MALE . '&nbsp;<input type="radio" name="gender" value="m">&nbsp;' . FEMALE . '&nbsp;' . ENTRY_GENDER_ERROR;
    } else {
      if ($HTTP_POST_VARS['gender'] == 'm') {
        echo MALE . '<input type="hidden" name="gender" value="m">';
      } elseif ($HTTP_POST_VARS['gender'] == 'f') {
        echo FEMALE . '<input type="hidden" name="gender" value="f">';
      }
    } ?></font></td>
          </tr>
          <tr>
            <td colspan="2"><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_FIRST_NAME;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<?
    if ($firstname_error == 1) {            
      echo '<input type="text" name="firstname" maxlength="32" value="' . $HTTP_POST_VARS['firstname'] . '">&nbsp;' . ENTRY_FIRST_NAME_ERROR;
    } else {
      echo $HTTP_POST_VARS['firstname'] . '<input type="hidden" name="firstname" value="' . $HTTP_POST_VARS['firstname'] . '">';
    } ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_LAST_NAME;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<?
    if ($lastname_error == 1) {
      echo '<input type="text" name="lastname" maxlength="32" value="' . $HTTP_POST_VARS['lastname'] . '">&nbsp;' . ENTRY_LAST_NAME_ERROR;
    } else {
      echo $HTTP_POST_VARS['lastname'] . '<input type="hidden" name="lastname" value="' . $HTTP_POST_VARS['lastname'] . '">';
    } ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_DATE_OF_BIRTH;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<?
    if ($dob_error == 1) {
      echo '<input type="text" name="dob" value="' . $HTTP_POST_VARS['dob'] . '"maxlength="10">&nbsp;' . ENTRY_DATE_OF_BIRTH_ERROR;
    } else {
      echo $HTTP_POST_VARS['dob'] . '<input type="hidden" name="dob" value="' . $HTTP_POST_VARS['dob'] . '">';
    } ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_EMAIL_ADDRESS;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<?
    if ($email_address_error == 1) {
      echo '<input type="text" name="email_address" maxlength="96" value="' . $HTTP_POST_VARS['email_address'] . '">&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR;
    } elseif ($email_exists == 1) {
      echo '<input type="text" name="email_address" maxlength="96" value="' . $HTTP_POST_VARS['email_address'] . '">&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR_EXISTS;
    } else {
      echo $HTTP_POST_VARS['email_address'] . '<input type="hidden" name="email_address" value="' . $HTTP_POST_VARS['email_address'] . '">';
    } ?></font></td>
          </tr>
          <tr>
            <td colspan="2"><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="7" nowrap><font face="<?=CATEGORY_FONT_FACE;?>" size="<?=CATEGORY_FONT_SIZE;?>" color="<?=CATEGORY_FONT_COLOR;?>"><?=CATEGORY_ADDRESS;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_STREET_ADDRESS;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<?
    if ($street_address_error == 1) {
      echo '<input type="text" name="street_address" maxlength="64" value="' . $HTTP_POST_VARS['street_address'] . '">&nbsp;' . ENTRY_STREET_ADDRESS_ERROR;
    } else {
      echo $HTTP_POST_VARS['street_address'] . '<input type="hidden" name="street_address" value="' . $HTTP_POST_VARS['street_address'] . '">';
    } ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_SUBURB;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<?
    echo $HTTP_POST_VARS['suburb'] . '<input type="hidden" name="suburb" value="' . $HTTP_POST_VARS['suburb'] . '">&nbsp;' . ENTRY_SUBURB_ERROR; ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_POST_CODE;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<?
    if ($postcode_error == 1) {
      echo '<input type="text" name="postcode" maxlength="8" value="' . $HTTP_POST_VARS['postcode'] . '">&nbsp;' . ENTRY_POST_CODE_ERROR;
    } else {
      echo $HTTP_POST_VARS['postcode'] . '<input type="hidden" name="postcode" value="' . $HTTP_POST_VARS['postcode'] . '">';
    } ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_CITY;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<?
    if ($city_error == 1) {
      echo '<input type="text" name="city" maxlength="32" value="' . $HTTP_POST_VARS['city'] . '">&nbsp;' . ENTRY_CITY_ERROR;
    } else {
      echo $HTTP_POST_VARS['city'] . '<input type="hidden" name="city" value ="' . $HTTP_POST_VARS['city'] . '">';
    } ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_STATE;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<?
    echo $HTTP_POST_VARS['state'] . '<input type="hidden" name="state" value="' . $HTTP_POST_VARS['state'] . '">&nbsp;' . ENTRY_STATE_ERROR; ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_COUNTRY;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<?
    if ($country_error == 1) {
      echo '<input type="text" name="country" maxlength="32" value="' . $HTTP_POST_VARS['country'] . '">&nbsp;' . ENTRY_COUNTRY_ERROR;
    } else {
      echo $HTTP_POST_VARS['country'] . '<input type="hidden" name="country" value="' . $HTTP_POST_VARS['country'] . '">';
    } ?></font></td>
          </tr>
          <tr>
            <td colspan="2"><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="3" nowrap><font face="<?=CATEGORY_FONT_FACE;?>" size="<?=CATEGORY_FONT_SIZE;?>" color="<?=CATEGORY_FONT_COLOR;?>"><?=CATEGORY_CONTACT;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_TELEPHONE_NUMBER;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<?
    if ($telephone_error == 1) {
      echo '<input type="text" name="telephone" maxlength="32" value="' . $HTTP_POST_VARS['telephone'] . '">&nbsp;' . ENTRY_TELEPHONE_NUMBER_ERROR;
    } else {
      echo $HTTP_POST_VARS['telephone'] . '<input type="hidden" name="telephone" value="' . $HTTP_POST_VARS['telephone'] . '">';
    } ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_FAX_NUMBER;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<?
    echo $HTTP_POST_VARS['fax'] . '<input type="hidden" name="fax" value="' . $HTTP_POST_VARS['fax'] . '">&nbsp;' . ENTRY_FAX_NUMBER_ERROR; ?></font></td>
          </tr>
          <tr>
            <td colspan="2"><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="3" nowrap><font face="<?=CATEGORY_FONT_FACE;?>" size="<?=CATEGORY_FONT_SIZE;?>" color="<?=CATEGORY_FONT_COLOR;?>"><?=CATEGORY_PASSWORD;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_PASSWORD;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<?
    if ($password_error == 1) {
      echo '<input type="password" name="password" maxlength="12" value="' . $HTTP_POST_VARS['password'] . '">&nbsp;' . ENTRY_PASSWORD_ERROR;
    } else {
      echo PASSWORD_HIDDEN . '<input type="hidden" name="password" value="' . $HTTP_POST_VARS['password'] . '">';
    } ?></font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td align="right"><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><?=tep_image_submit(DIR_IMAGES . 'button_update.gif', '78', '24', '0', IMAGE_UPDATE);?>&nbsp;&nbsp;</font></td>
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
<?
  } else {
    $date_now = date('Ymd');
    $dob_ordered = substr($HTTP_POST_VARS['dob'], -4) . substr($HTTP_POST_VARS['dob'], 3, 2) . substr($HTTP_POST_VARS['dob'], 0, 2);

    tep_db_query("update customers set customers_gender = '" . $HTTP_POST_VARS['gender'] . "', customers_firstname = '" . $HTTP_POST_VARS['firstname'] . "', customers_lastname = '" . $HTTP_POST_VARS['lastname'] . "', customers_dob = '" . $dob_ordered . "', customers_email_address = '" . $HTTP_POST_VARS['email_address'] . "', customers_street_address = '" . $HTTP_POST_VARS['street_address'] . "', customers_suburb = '" . $HTTP_POST_VARS['suburb'] . "', customers_postcode = '" . $HTTP_POST_VARS['postcode'] . "', customers_city = '" . $HTTP_POST_VARS['city'] . "', customers_state = '" . $HTTP_POST_VARS['state'] . "', customers_country = '" . $HTTP_POST_VARS['country'] . "', customers_telephone = '" . $HTTP_POST_VARS['telephone'] . "', customers_fax = '" . $HTTP_POST_VARS['fax'] . "', customers_password = '" . $HTTP_POST_VARS['password'] . "' where customers_id = '" . $customer_id . "'");
    tep_db_query("update customers_info set customers_info_date_account_last_modified = '" . $date_now . "' where customers_info_id = '" . $customer_id . "'");

    header('Location: ' . tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL'));
    tep_exit();
  }
?>
<? $include_file = DIR_INCLUDES . 'application_bottom.php'; include(DIR_INCLUDES . 'include_once.php'); ?>