<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_EDIT; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_1 . '</a> : <a href="' . tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_2 . '</a>'; ?>
<html>
<head>
<title><?=TITLE;?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript"><!--
function check_form() {
  var error = 0;
  var error_message = "<?=JS_ERROR;?>";

  var first_name = document.account_edit.firstname.value;
  var last_name = document.account_edit.lastname.value;
<?
   if (ACCOUNT_DOB) {
?>
  var dob = document.account_edit.dob.value;
<?
  }
?>
  var email_address = document.account_edit.email_address.value;  
  var street_address = document.account_edit.street_address.value;
  var postcode = document.account_edit.postcode.value;
  var city = document.account_edit.city.value;
  var telephone = document.account_edit.telephone.value;
  var password = document.account_edit.password.value;
  var confirmation = document.account_edit.confirmation.value;

<?
   if (ACCOUNT_GENDER) {
?>
  if (document.account_edit.gender[0].checked || document.account_edit.gender[1].checked) {
  } else {
    error_message = error_message + "<?=JS_GENDER;?>";
    error = 1;
  }
<?
  }
?>
 
  if (first_name = "" || first_name.length < <?=ENTRY_FIRST_NAME_MIN_LENGTH;?>) {
    error_message = error_message + "<?=JS_FIRST_NAME;?>";
    error = 1;
  }

  if (last_name = "" || last_name.length < <?=ENTRY_LAST_NAME_MIN_LENGTH;?>) {
    error_message = error_message + "<?=JS_LAST_NAME;?>";
    error = 1;
  }

<?
   if (ACCOUNT_DOB) {
?>
  if (dob = "" || dob.length < <?=ENTRY_DOB_MIN_LENGTH;?>) {
    error_message = error_message + "<?=JS_DOB;?>";
    error = 1;
  }
<?
  }
?>
 
  if (email_address = "" || email_address.length < <?=ENTRY_EMAIL_ADDRESS_MIN_LENGTH;?>) {
    error_message = error_message + "<?=JS_EMAIL_ADDRESS;?>";
    error = 1;
  }

  if (street_address = "" || street_address.length < <?=ENTRY_STREET_ADDRESS_MIN_LENGTH;?>) {
    error_message = error_message + "<?=JS_ADDRESS;?>";
    error = 1;
  }

  if (postcode = "" || postcode.length < <?=ENTRY_POSTCODE_MIN_LENGTH;?>) {
    error_message = error_message + "<?=JS_POST_CODE;?>";
    error = 1;
  }

  if (city = "" || city.length < <?=ENTRY_CITY_MIN_LENGTH;?>) {
    error_message = error_message + "<?=JS_CITY;?>";
    error = 1;
  }

  if (telephone = "" || telephone.length < <?=ENTRY_TELEPHONE_MIN_LENGTH;?>) {
    error_message = error_message + "<?=JS_TELEPHONE;?>";
    error = 1;
  }

  if ((password != confirmation) || (password = "" || password.length < <?=ENTRY_PASSWORD_MIN_LENGTH;?>)) {
    error_message = error_message + "<?=JS_PASSWORD;?>";
    error = 1;
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>
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
    $account_query = $account_query . 'customers_state, ';
  }
  $account_query = $account_query . "customers_country_id, customers_telephone, customers_fax, customers_password from customers where customers_id = '" . $customer_id . "'";
  $account = tep_db_query($account_query);
  $account_values = tep_db_fetch_array($account);
  $rowspan=5+ACCOUNT_GENDER+ACCOUNT_DOB;
?>
    <td width="100%" valign="top"><form name="account_edit" method="post" action="<?=tep_href_link(FILENAME_ACCOUNT_EDIT_PROCESS, '', 'NONSSL');?>" onSubmit="return check_form();"><input type="hidden" name="action" value="process"><table border="0" width="100%" cellspacing="0" cellpadding="0">
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
            <td align="right" valign="middle" colspan="2" rowspan="<?=$rowspan;?>" nowrap><font face="<?=CATEGORY_FONT_FACE;?>" size="<?=CATEGORY_FONT_SIZE;?>" color="<?=CATEGORY_FONT_COLOR;?>"><?=CATEGORY_PERSONAL;?></font></td>
          </tr>
<?
  if (ACCOUNT_GENDER) {
?>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_GENDER;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;&nbsp;<input type="radio" name="gender" value="m"<?
  if ($account_values['customers_gender'] == 'm') {
    echo ' CHECKED';
  } ?>>&nbsp;&nbsp;<?=MALE;?>&nbsp;&nbsp;<input type="radio" name="gender" value="f"<?
  if ($account_values['customers_gender'] == 'f') {
    echo ' CHECKED';
  } ?>>&nbsp;&nbsp;<?=FEMALE;?>&nbsp;<?=ENTRY_GENDER_TEXT;?></font></td>
          </tr>
<?
   }
?>
          <tr>
            <td colspan="2"><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_FIRST_NAME;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<input type="text" name="firstname" maxlength="32" value="<?=$account_values['customers_firstname'];?>">&nbsp;<?=ENTRY_FIRST_NAME_TEXT;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_LAST_NAME;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<input type="text" name="lastname" maxlength="32" value="<?=$account_values['customers_lastname'];?>">&nbsp;<?=ENTRY_LAST_NAME_TEXT;?></font></td>
          </tr>
<?
  if (ACCOUNT_DOB) {
?>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_DATE_OF_BIRTH;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<input type="text" name="dob" value="<?=substr($account_values['customers_dob'], -2) . '/' . substr($account_values['customers_dob'], 4, 2) . '/' . substr($account_values['customers_dob'], 0, 4);?>" maxlength="10">&nbsp;<?=ENTRY_DATE_OF_BIRTH_TEXT;?></font></td>
          </tr>
<?
   }
   $rowspan=5+ACCOUNT_SUBURB+ACCOUNT_STATE;
?>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_EMAIL_ADDRESS;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<input type="text" name="email_address" maxlength="96" value="<?=$account_values['customers_email_address'];?>">&nbsp;<?=ENTRY_EMAIL_ADDRESS_TEXT;?></font></td>
          </tr>
          <tr>
            <td colspan="2"><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="<?=$rowspan;?>" nowrap><font face="<?=CATEGORY_FONT_FACE;?>" size="<?=CATEGORY_FONT_SIZE;?>" color="<?=CATEGORY_FONT_COLOR;?>"><?=CATEGORY_ADDRESS;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_STREET_ADDRESS;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<input type="text" name="street_address" maxlength="64" value="<?=$account_values['customers_street_address'];?>">&nbsp;<?=ENTRY_STREET_ADDRESS_TEXT;?></font></td>
          </tr>
<?
  if (ACCOUNT_SUBURB) {
?>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_SUBURB;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<input type="text" name="suburb" maxlength="32" value="<?=$account_values['customers_suburb'];?>">&nbsp;<?=ENTRY_SUBURB_TEXT;?></font></td>
          </tr>
<?
   }
?>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_POST_CODE;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<input type="text" name="postcode" maxlength="8" value="<?=$account_values['customers_postcode'];?>">&nbsp;<?=ENTRY_POST_CODE_TEXT;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_CITY;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<input type="text" name="city" maxlength="32" value="<?=$account_values['customers_city'];?>">&nbsp;<?=ENTRY_CITY_TEXT;?></font></td>
          </tr>
<?
  if (ACCOUNT_STATE) {
?>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_STATE;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<input type="text" name="state" maxlength="32" value="<?=$account_values['customers_state'];?>">&nbsp;<?=ENTRY_STATE_TEXT;?></font></td>
          </tr>
<?
  }
?>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_COUNTRY;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<select name="country"><option value="0"><?=PLEASE_SELECT;?></option>
<?
    $countries = tep_get_countries();
    for ($i=0; $i < sizeof($countries); $i++) {
      echo '<option value="' . $countries[$i]['countries_id'] . '"';
      if ($countries[$i]['countries_id'] == $account_values['customers_country_id']) echo ' SELECTED';
      echo '>' . $countries[$i]['countries_name'] . '</option>';
  }
?></select>&nbsp;<?=ENTRY_COUNTRY_TEXT;?></font></td>
          </tr>
          <tr>
            <td colspan="2"><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="3" nowrap><font face="<?=CATEGORY_FONT_FACE;?>" size="<?=CATEGORY_FONT_SIZE;?>" color="<?=CATEGORY_FONT_COLOR;?>"><?=CATEGORY_CONTACT;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_TELEPHONE_NUMBER;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<input type="text" name="telephone" maxlength="32" value="<?=$account_values['customers_telephone'];?>">&nbsp;<?=ENTRY_TELEPHONE_NUMBER_TEXT;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_FAX_NUMBER;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<input type="text" name="fax" maxlength="32" value="<?=$account_values['customers_fax'];?>">&nbsp;<?=ENTRY_FAX_NUMBER_TEXT;?></font></td>
          </tr>
          <tr>
            <td colspan="2"><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="3"><font face="<?=CATEGORY_FONT_FACE;?>" size="<?=CATEGORY_FONT_SIZE;?>" color="<?=CATEGORY_FONT_COLOR;?>"><?=CATEGORY_PASSWORD;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_PASSWORD;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<input type="password" name="password" maxlength="12" value="<?=$account_values['customers_password'];?>">&nbsp;<?=ENTRY_PASSWORD_TEXT;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;<?=ENTRY_PASSWORD_CONFIRMATION;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;<input type="password" name="confirmation" maxlength="12" value="<?=$account_values['customers_password'];?>">&nbsp;<?=ENTRY_PASSWORD_CONFIRMATION_TEXT;?></font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td align="right"><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><?=tep_image_submit(DIR_IMAGES . 'button_update.gif', '78', '24', '0', IMAGE_UPDATE);?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?=tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL');?>"><?=tep_image(DIR_IMAGES . 'button_cancel.gif', '72', '24', '0', IMAGE_CANCEL);?></a>&nbsp;&nbsp;</font></td>
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
