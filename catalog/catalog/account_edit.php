<? include('includes/application_top.php'); ?>
<? $include_file = DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_EDIT; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_1 . '</a> : <a href="' . tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_2 . '</a>'; ?>
<?
  if (!@tep_session_is_registered('customer_id')) {
    header('Location: ' . tep_href_link(FILENAME_LOGIN, 'origin=' . FILENAME_ACCOUNT_EDIT, 'NONSSL'));
    tep_exit();
  }
?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function resetStateText(theForm) {
  theForm.state.value = '';
  if (theForm.zone_id.options.length > 1) {
    theForm.state.value = '<? echo JS_STATE_SELECT; ?>';
  }
}

function resetZoneSelected(theForm) {
  if (theForm.zone_id.options.length > 1) {
    theForm.state.value = '<? echo JS_STATE_SELECT; ?>';
  }
}

function update_zone(theForm) {
   
  var NumState = theForm.zone_id.options.length;
  
  while(NumState > 0) {
    NumState--;
    theForm.zone_id.options[NumState] = null;
  }         

  var SelectedCountry = "";

  SelectedCountry = theForm.country.options[theForm.country.selectedIndex].value;
            
<? tep_js_zone_list("SelectedCountry", "theForm"); ?>
  resetStateText(theForm);
}
function check_form() {
  var error = 0;
  var error_message = "<? echo JS_ERROR; ?>";

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
    error_message = error_message + "<? echo JS_GENDER; ?>";
    error = 1;
  }
<?
  }
?>
 
  if (first_name == "" || first_name.length < <? echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "<? echo JS_FIRST_NAME; ?>";
    error = 1;
  }

  if (last_name == "" || last_name.length < <? echo ENTRY_LAST_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "<? echo JS_LAST_NAME; ?>";
    error = 1;
  }

<?
   if (ACCOUNT_DOB) {
?>
  if (dob == "" || dob.length < <? echo ENTRY_DOB_MIN_LENGTH; ?>) {
    error_message = error_message + "<? echo JS_DOB; ?>";
    error = 1;
  }
<?
  }
?>
 
  if (email_address == "" || email_address.length < <? echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>) {
    error_message = error_message + "<? echo JS_EMAIL_ADDRESS; ?>";
    error = 1;
  }

  if (street_address == "" || street_address.length < <? echo ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>) {
    error_message = error_message + "<? echo JS_ADDRESS; ?>";
    error = 1;
  }

  if (postcode == "" || postcode.length < <? echo ENTRY_POSTCODE_MIN_LENGTH; ?>) {
    error_message = error_message + "<? echo JS_POST_CODE; ?>";
    error = 1;
  }

  if (city == "" || city.length < <? echo ENTRY_CITY_MIN_LENGTH; ?>) {
    error_message = error_message + "<? echo JS_CITY; ?>";
    error = 1;
  }

<?
  if (ACCOUNT_STATE) {
?>
  if (document.account_edit.zone_id.options.length <= 1) {
    if (document.account_edit.state.value == "" || document.account_edit.state.value.length < <? echo ENTRY_STATE_MIN_LENGTH; ?> ) {
       error_message = error_message + "<? echo JS_STATE; ?>";
       error = 1;
    }
  } else {
    document.account_edit.state.value = '';
    if (document.account_edit.zone_id.selectedIndex == 0) {
       error_message = error_message + "<? echo JS_ZONE; ?>";
       error = 1;
    }
  }
<?
  }
?>

  if (document.account_edit.country.value == 0) {
    error_message = error_message + "<? echo JS_COUNTRY; ?>";
    error = 1;
  }

  if (telephone == "" || telephone.length < <? echo ENTRY_TELEPHONE_MIN_LENGTH; ?>) {
    error_message = error_message + "<? echo JS_TELEPHONE; ?>";
    error = 1;
  }

  if ((password != confirmation) || (password == "" || password.length < <? echo ENTRY_PASSWORD_MIN_LENGTH; ?>)) {
    error_message = error_message + "<? echo JS_PASSWORD; ?>";
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
    $account_query = $account_query . 'customers_state, customers_zone_id, ';
  }
  $account_query = $account_query . "customers_country_id, customers_telephone, customers_fax, customers_newsletter from customers where customers_id = '" . $customer_id . "'";
  $account = tep_db_query($account_query);
  $account_values = tep_db_fetch_array($account);
  $rowspan=5+ACCOUNT_GENDER+ACCOUNT_DOB;
?>
    <td width="100%" valign="top"><form name="account_edit" method="post" action="<? echo tep_href_link(FILENAME_ACCOUNT_EDIT_PROCESS, '', 'NONSSL'); ?>" onSubmit="return check_form();"><input type="hidden" name="action" value="process"><table border="0" width="100%" cellspacing="0" cellpadding="0">
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
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'table_background_account.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td width="100%"><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="<? echo $rowspan; ?>" class="accountCategory" nowrap><? echo CATEGORY_PERSONAL; ?></td>
          </tr>
<?
  if (ACCOUNT_GENDER) {
?>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_GENDER; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;&nbsp;<input type="radio" name="gender" value="m"<?
  if ($account_values['customers_gender'] == 'm') {
    echo ' CHECKED';
  } ?>>&nbsp;&nbsp;<? echo MALE; ?>&nbsp;&nbsp;<input type="radio" name="gender" value="f"<?
  if ($account_values['customers_gender'] == 'f') {
    echo ' CHECKED';
  } ?>>&nbsp;&nbsp;<? echo FEMALE; ?>&nbsp;<? echo ENTRY_GENDER_TEXT; ?></font></td>
          </tr>
<?
   }
?>
          <tr>
            <td colspan="2"><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_FIRST_NAME; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<input type="text" name="firstname" maxlength="32" value="<? echo $account_values['customers_firstname']; ?>">&nbsp;<? echo ENTRY_FIRST_NAME_TEXT; ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_LAST_NAME; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<input type="text" name="lastname" maxlength="32" value="<? echo $account_values['customers_lastname']; ?>">&nbsp;<? echo ENTRY_LAST_NAME_TEXT; ?></font></td>
          </tr>
<?
  if (ACCOUNT_DOB) {
?>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_DATE_OF_BIRTH; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<input type="text" name="dob" value="<? echo substr($account_values['customers_dob'], -2) . '/' . substr($account_values['customers_dob'], 4, 2) . '/' . substr($account_values['customers_dob'], 0, 4); ?>" maxlength="10">&nbsp;<? echo ENTRY_DATE_OF_BIRTH_TEXT; ?></font></td>
          </tr>
<?
   }
   $rowspan=5+ACCOUNT_SUBURB+ACCOUNT_STATE+ACCOUNT_STATE;
?>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_EMAIL_ADDRESS; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<input type="text" name="email_address" maxlength="96" value="<? echo $account_values['customers_email_address']; ?>">&nbsp;<? echo ENTRY_EMAIL_ADDRESS_TEXT; ?></font></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="<? echo $rowspan; ?>" class="accountCategory" nowrap><? echo CATEGORY_ADDRESS; ?></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_STREET_ADDRESS; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<input type="text" name="street_address" maxlength="64" value="<? echo $account_values['customers_street_address']; ?>">&nbsp;<? echo ENTRY_STREET_ADDRESS_TEXT; ?></font></td>
          </tr>
<?
  if (ACCOUNT_SUBURB) {
?>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_SUBURB; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<input type="text" name="suburb" maxlength="32" value="<? echo $account_values['customers_suburb']; ?>">&nbsp;<? echo ENTRY_SUBURB_TEXT; ?></font></td>
          </tr>
<?
   }
?>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_POST_CODE; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<input type="text" name="postcode" maxlength="8" value="<? echo $account_values['customers_postcode']; ?>">&nbsp;<? echo ENTRY_POST_CODE_TEXT; ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_CITY; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<input type="text" name="city" maxlength="32" value="<? echo $account_values['customers_city']; ?>">&nbsp;<? echo ENTRY_CITY_TEXT; ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_COUNTRY; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<?tep_get_country_list("country", $account_values['customers_country_id'], (ACCOUNT_STATE)?"onChange=\"update_zone(this.form);\"":""); ?>&nbsp;<? echo ENTRY_COUNTRY_TEXT; ?></font></td>
          </tr>
<?
  if (ACCOUNT_STATE) {
?>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_STATE; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<?tep_get_zone_list("zone_id", $account_values['customers_country_id'], $account_values['customers_zone_id'], "onChange=\"resetStateText(this.form)\";"); ?>&nbsp;<? echo ENTRY_STATE_TEXT; ?></font></td>
          </tr>
          <tr>
            <td></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>
            &nbsp;<input type="text" name="state" onChange="resetZoneSelected(this.form);" maxlength="32" value="<? echo $account_values['customers_state']; ?>">&nbsp;<? echo ENTRY_STATE_TEXT; ?></font></td>
          </tr>
<?
  }
?>
          <tr>
            <td colspan="2"><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="3" class="accountCategory" nowrap><? echo CATEGORY_CONTACT; ?></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_TELEPHONE_NUMBER; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<input type="text" name="telephone" maxlength="32" value="<? echo $account_values['customers_telephone']; ?>">&nbsp;<? echo ENTRY_TELEPHONE_NUMBER_TEXT; ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_FAX_NUMBER; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<input type="text" name="fax" maxlength="32" value="<? echo $account_values['customers_fax']; ?>">&nbsp;<? echo ENTRY_FAX_NUMBER_TEXT; ?></font></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="2" class="accountCategory" nowrap><? echo CATEGORY_OPTIONS; ?></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_NEWSLETTER; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<select name="newsletter"><?php if ($account_values['customers_newsletter']=="1") { echo '<option selected value="1">'; } else { echo '<option value="1">'; } ?><?php echo ENTRY_NEWSLETTER_YES; ?></option><?php if ($account_values['customers_newsletter']=="0") { echo '<option selected value="0">'; } else { echo '<option value="0">'; } ?><? echo ENTRY_NEWSLETTER_NO; ?></option></select>&nbsp;<? echo ENTRY_NEWSLETTER_TEXT; ?></font></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" class="accountCategory" rowspan="3"><? echo CATEGORY_PASSWORD; ?></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_PASSWORD; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<input type="password" name="password" maxlength="12" value="">&nbsp;<? echo ENTRY_PASSWORD_TEXT; ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_PASSWORD_CONFIRMATION; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<input type="password" name="confirmation" maxlength="12" value="">&nbsp;<? echo ENTRY_PASSWORD_CONFIRMATION_TEXT; ?></font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><br><? echo tep_image_submit(DIR_WS_IMAGES . 'button_update.gif', IMAGE_UPDATE); ?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<? echo tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL'); ?>"><? echo tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL); ?></a>&nbsp;&nbsp;</td>
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
