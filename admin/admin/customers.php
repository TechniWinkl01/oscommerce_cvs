<? include('includes/application_top.php'); ?>
<?
  if ($HTTP_GET_VARS['action'] == 'update') {
    $date_now = date('Ymd');
    if (ACCOUNT_DOB) {
       $dob_ordered = substr($HTTP_POST_VARS['dob'], -4) . substr($HTTP_POST_VARS['dob'], 3, 2) . substr($HTTP_POST_VARS['dob'], 0, 2);
    }
    $update_query = 'update customers set ';
    if (ACCOUNT_GENDER) {
       $update_query = $update_query . "customers_gender = '" . $HTTP_POST_VARS['gender'] . "', ";
    }
     $update_query = $update_query . "customers_firstname = '" . $HTTP_POST_VARS['firstname'] . "', customers_lastname = '" . $HTTP_POST_VARS['lastname'] . "', ";
    if (ACCOUNT_DOB) {
       $update_query = $update_query . "customers_dob = '" . $dob_ordered . "', ";
    }
    $update_query = $update_query . "customers_email_address = '" . $HTTP_POST_VARS['email_address'] . "', customers_street_address = '" . $HTTP_POST_VARS['street_address'] . "', ";
    if (ACCOUNT_SUBURB) {
       $update_query = $update_query . "customers_suburb = '" . $HTTP_POST_VARS['suburb'] . "', ";
    }
    $update_query = $update_query . "customers_postcode = '" . $HTTP_POST_VARS['postcode'] . "', customers_city = '" . $HTTP_POST_VARS['city'] . "', ";
    if (ACCOUNT_STATE) {
       $state = $HTTP_POST_VARS['state'];
       $zone_id = $HTTP_POST_VARS['zone_id'];
       if ($zone_id > 0) $state = '';
       $update_query = $update_query . "customers_state = '" . $state . "', ";
       $update_query = $update_query . "customers_zone_id = '" . $zone_id . "', ";
    }
    $update_query .= "customers_telephone = '" . $HTTP_POST_VARS['telephone'] . "', customers_fax = '" . $HTTP_POST_VARS['fax'] . "', customers_newsletter = '" . $HTTP_POST_VARS['newsletter'] . "', customers_country_id = '" . $HTTP_POST_VARS['countries_id'] . "' where customers_id = '" . $HTTP_POST_VARS['customers_id'] . "'";
    tep_db_query($update_query);
    tep_db_query("update customers_info set customers_info_date_account_last_modified = '" . $date_now . "' where customers_info_id = '" . $HTTP_POST_VARS['customers_id'] . "'");
    header('Location: ' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action')) . 'info=' . $HTTP_POST_VARS['customers_id'], 'NONSSL')); tep_exit();
  } elseif ($HTTP_GET_VARS['action'] == 'deleteconfirm') {
    tep_db_query("delete from customers_info where customers_info_id = '" . $HTTP_POST_VARS['customers_id'] . "'");
    tep_db_query("delete from customers where customers_id = '" . $HTTP_POST_VARS['customers_id'] . "'");
    header('Location: ' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action','info')), 'NONSSL')); 
    tep_exit();
  }
?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<?
  if ($HTTP_GET_VARS['action'] == 'edit') {
?>
<script language="javascript"><!--
function resetStateText(theForm) {
  theForm.state.value = '';
  if (theForm.zone_id.options.length > 1) {
    theForm.state.value = '<? echo JS_STATE_SELECT; ?>';
  }
}

function resetZoneSelected(theForm) {
  if (theForm.state.value != '') {
    theForm.zone_id.selectedIndex = '0';
    if (theForm.zone_id.options.length > 1) {
      theForm.state.value = '<? echo JS_STATE_SELECT; ?>';
    }
  }
}

function update_zone(theForm) {
   
  var NumState = theForm.zone_id.options.length;
  
  while(NumState > 0) {
    NumState--;
    theForm.zone_id.options[NumState] = null;
  }         

  var SelectedCountry = "";

  SelectedCountry = theForm.countries_id.options[theForm.countries_id.selectedIndex].value;

<? tep_js_zone_list("SelectedCountry", "theForm"); ?>
  resetStateText(theForm);
}
function check_form() {
  var error = 0;
  var error_message = "<? echo JS_ERROR; ?>";

  var firstname = document.customers.firstname.value;
  var lastname = document.customers.lastname.value;
<?
   if (ACCOUNT_DOB) {
?>
  var dob = document.customers.dob.value;
<?
   }
?>
  var email_address = document.customers.email_address.value;  
  var street_address = document.customers.street_address.value;
  var postcode = document.customers.postcode.value;
  var city = document.customers.city.value;
  var telephone = document.customers.telephone.value;

<?
   if (ACCOUNT_GENDER) {
?>
  if (document.customers.gender[0].checked || document.customers.gender[1].checked) {
  } else {
    error_message = error_message + "<? echo JS_GENDER; ?>";
    error = 1;
  }
<?
   }
?>
  
  if (firstname = "" || firstname.length < 3) {
    error_message = error_message + "<? echo JS_FIRST_NAME; ?>";
    error = 1;
  }

  if (lastname = "" || lastname.length < 3) {
    error_message = error_message + "<? echo JS_LAST_NAME; ?>";
    error = 1;
  }

<?
   if (ACCOUNT_DOB) {
?>
  if (dob = "" || dob.length < 10) {
    error_message = error_message + "<? echo JS_DOB; ?>";
    error = 1;
  }
<?
   }
?>

  if (email_address = "" || email_address.length < 6) {
    error_message = error_message + "<? echo JS_EMAIL_ADDRESS; ?>";
    error = 1;
  }

  if (street_address = "" || street_address.length < 5) {
    error_message = error_message + "<? echo JS_ADDRESS; ?>";
    error = 1;
  }

  if (postcode = "" || postcode.length < 4) {
    error_message = error_message + "<? echo JS_POST_CODE; ?>";
    error = 1;
  }

  if (city = "" || city.length < 4) {
    error_message = error_message + "<? echo JS_CITY; ?>";
    error = 1;
  }

<?
  if (ACCOUNT_STATE) {
?>
  if (document.customers.zone_id.options.length == 0) {
    if (document.customers.state.value == "" || document.customers.state.length < 4 ) {
       error_message = error_message + "<? echo JS_STATE; ?>";
       error = 1;
    }
  } else {
    document.customers.state.value = '';
    if (document.customers.zone_id.selectedIndex == 0) {
       error_message = error_message + "<? echo JS_ZONE; ?>";
       error = 1;
    }
  }
<?
  }
?>

  if (document.customers.countries_id.value == 0) {
    error_message = error_message + "<? echo JS_COUNTRY; ?>";
    error = 1;
  }

  if (telephone = "" || telephone.length < 5) {
    error_message = error_message + "<? echo JS_TELEPHONE; ?>";
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
<?
  }
?>
</head>
<body onload="SetFocus();" marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
            <td bgcolor="<? echo TOP_BAR_BACKGROUND_COLOR; ?>" width="100%" nowrap><font face="<? echo TOP_BAR_FONT_FACE; ?>" size="<? echo TOP_BAR_FONT_SIZE; ?>" color="<? echo TOP_BAR_FONT_COLOR; ?>">&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
<?
  if ($HTTP_GET_VARS['action'] == 'edit') {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<? echo HEADING_FONT_FACE; ?>" size="<? echo HEADING_FONT_SIZE; ?>" color="<? echo HEADING_FONT_COLOR; ?>">&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'table_background_account.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr><form name="customers" <? echo 'action="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action', 'cID')) . 'action=update', 'NONSSL') . '"'; ?> method="post" onSubmit="return check_form();"><input type="hidden" name="customers_id" value="<? echo $HTTP_GET_VARS['cID']; ?>">
<?
    $cust_query = "select ";
    if (ACCOUNT_GENDER) {
       $cust_query = $cust_query . "customers_gender, ";
    }
    $cust_query = $cust_query . "customers_firstname, customers_lastname, ";
    if (ACCOUNT_DOB) {
       $cust_query = $cust_query . "customers_dob, ";
    }
    $cust_query = $cust_query . "customers_email_address, customers_street_address, ";
    if (ACCOUNT_SUBURB) {
       $cust_query = $cust_query . "customers_suburb, ";
    }
    $cust_query = $cust_query . "customers_postcode, customers_city, ";
    if (ACCOUNT_STATE) {
       $cust_query = $cust_query . "customers_state, customers_zone_id, ";
    }
    $cust_query = $cust_query . "customers_country_id, customers_telephone, customers_fax, customers_newsletter from customers where customers_id = '" . $HTTP_GET_VARS['cID'] . "'";
    $customers_query = tep_db_query($cust_query);
    $customers = tep_db_fetch_array($customers_query);
    $rowspan=5+ACCOUNT_GENDER+ACCOUNT_DOB;

    if (ACCOUNT_GENDER) {
     $gender = $customers['customers_gender'];
    }
    $firstname = $customers['customers_firstname'];
    $lastname = $customers['customers_lastname'];
    if (ACCOUNT_DOB) {
       $dob = substr($customers['customers_dob'], -2) . '/' . substr($customers['customers_dob'], 4, 2) . '/' . substr($customers['customers_dob'], 0, 4);
    }
    $email_address = $customers['customers_email_address'];
    $street_address = $customers['customers_street_address'];
    if (ACCOUNT_SUBURB) {
       $suburb = $customers['customers_suburb'];
    }
    $postcode = $customers['customers_postcode'];
    $city = $customers['customers_city'];
    if (ACCOUNT_STATE) {
       $state = $customers['customers_state'];
       $zone_id = $customers['customers_zone_id'];
    }
    $country_id = $customers['customers_country_id'];
    $telephone = $customers['customers_telephone'];
    $fax = $customers['customers_fax'];
    $newsletter = $customers['customers_newsletter'];
?>
        <td width="100%"><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="<? echo $rowspan; ?>" nowrap><font face="<? echo CATEGORY_FONT_FACE; ?>" size="<? echo CATEGORY_FONT_SIZE; ?>" color="<? echo CATEGORY_FONT_COLOR; ?>"><? echo CATEGORY_PERSONAL; ?></font></td>
          </tr>
<?
    if (ACCOUNT_GENDER) {
?>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE; ?>" size="<? echo ENTRY_FONT_SIZE; ?>" color="<? echo ENTRY_FONT_COLOR; ?>">&nbsp;&nbsp;<? echo ENTRY_GENDER; ?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE; ?>" size="<? echo VALUE_FONT_SIZE; ?>" color="<? echo VALUE_FONT_COLOR; ?>">&nbsp;&nbsp;<input type="radio" name="gender" value="m"<?
      if (@$gender == 'm') {
        echo ' CHECKED';
      } ?>>&nbsp;&nbsp;<? echo MALE; ?>&nbsp;&nbsp;<input type="radio" name="gender" value="f"<?
      if (@$gender == 'f') {
        echo ' CHECKED';
      } ?>>&nbsp;&nbsp;<? echo FEMALE; ?>&nbsp;<? echo ENTRY_GENDER_TEXT; ?></font></td>
          </tr>
<?
    }
?>
          <tr>
            <td colspan="2"><font face="<? echo ENTRY_FONT_FACE; ?>" size="<? echo ENTRY_FONT_SIZE; ?>" color="<? echo ENTRY_FONT_COLOR; ?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE; ?>" size="<? echo ENTRY_FONT_SIZE; ?>" color="<? echo ENTRY_FONT_COLOR; ?>">&nbsp;&nbsp;<? echo ENTRY_FIRST_NAME; ?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE; ?>" size="<? echo VALUE_FONT_SIZE; ?>" color="<? echo VALUE_FONT_COLOR; ?>">&nbsp;&nbsp;<? if ($action == 'delete') { echo $firstname; } else { echo '<input type="text" name="firstname" maxlength="32" value="' . @$firstname . '">&nbsp;' . ENTRY_FIRST_NAME_TEXT; } ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE; ?>" size="<? echo ENTRY_FONT_SIZE; ?>" color="<? echo ENTRY_FONT_COLOR; ?>">&nbsp;&nbsp;<? echo ENTRY_LAST_NAME; ?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE; ?>" size="<? echo VALUE_FONT_SIZE; ?>" color="<? echo VALUE_FONT_COLOR; ?>">&nbsp;&nbsp;<? if ($action == 'delete') { echo $lastname; } else { echo '<input type="text" name="lastname" maxlength="32" value="' . @$lastname . '">&nbsp;' . ENTRY_LAST_NAME_TEXT; } ?></font></td>
          </tr>
<?
    if (ACCOUNT_DOB) {
?>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE; ?>" size="<? echo ENTRY_FONT_SIZE; ?>" color="<? echo ENTRY_FONT_COLOR; ?>">&nbsp;&nbsp;<? echo ENTRY_DATE_OF_BIRTH; ?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE; ?>" size="<? echo VALUE_FONT_SIZE; ?>" color="<? echo VALUE_FONT_COLOR; ?>">&nbsp;&nbsp;<? if ($action == 'delete') { echo $dob; } else { echo '<input type="text" name="dob" maxlength="10" value="' . @$dob . '">&nbsp;' . ENTRY_DATE_OF_BIRTH_TEXT; } ?></font></td>
          </tr>
<?
    }
    $rowspan=5+ACCOUNT_SUBURB+ACCOUNT_STATE+ACCOUNT_STATE;
?>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE; ?>" size="<? echo ENTRY_FONT_SIZE; ?>" color="<? echo ENTRY_FONT_COLOR; ?>">&nbsp;&nbsp;<? echo ENTRY_EMAIL_ADDRESS; ?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE; ?>" size="<? echo VALUE_FONT_SIZE; ?>" color="<? echo VALUE_FONT_COLOR; ?>">&nbsp;&nbsp;<? if ($action == 'delete') { echo $email_address; } else { echo '<input type="text" name="email_address" maxlength="96" value="' . @$email_address . '">&nbsp;' . ENTRY_EMAIL_ADDRESS_TEXT; } ?></font></td>
          </tr>
          <tr>
            <td colspan="2"><font face="<? echo ENTRY_FONT_FACE; ?>" size="<? echo ENTRY_FONT_SIZE; ?>" color="<? echo ENTRY_FONT_COLOR; ?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="<? echo $rowspan; ?>" nowrap><font face="<? echo CATEGORY_FONT_FACE; ?>" size="<? echo CATEGORY_FONT_SIZE; ?>" color="<? echo CATEGORY_FONT_COLOR; ?>"><? echo CATEGORY_ADDRESS; ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE; ?>" size="<? echo ENTRY_FONT_SIZE; ?>" color="<? echo ENTRY_FONT_COLOR; ?>">&nbsp;&nbsp;<? echo ENTRY_STREET_ADDRESS; ?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE; ?>" size="<? echo VALUE_FONT_SIZE; ?>" color="<? echo VALUE_FONT_COLOR; ?>">&nbsp;&nbsp;<? if ($action == 'delete') { echo $street_address; } else { echo '<input type="text" name="street_address" maxlength="64" value="' . @$street_address . '">&nbsp;' . ENTRY_STREET_ADDRESS_TEXT; }?></font></td>
          </tr>
<?
    if (ACCOUNT_SUBURB) {
?>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE; ?>" size="<? echo ENTRY_FONT_SIZE; ?>" color="<? echo ENTRY_FONT_COLOR; ?>">&nbsp;&nbsp;<? echo ENTRY_SUBURB; ?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE; ?>" size="<? echo VALUE_FONT_SIZE; ?>" color="<? echo VALUE_FONT_COLOR; ?>">&nbsp;&nbsp;<? if ($action == 'delete') { echo $suburb; } else { echo '<input type="text" name="suburb" maxlength="32" value="' . @$suburb . '">&nbsp;' . ENTRY_SUBURB_TEXT; } ?></font></td>
          </tr>
<?
    }
?>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE; ?>" size="<? echo ENTRY_FONT_SIZE; ?>" color="<? echo ENTRY_FONT_COLOR; ?>">&nbsp;&nbsp;<? echo ENTRY_POST_CODE; ?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE; ?>" size="<? echo VALUE_FONT_SIZE; ?>" color="<? echo VALUE_FONT_COLOR; ?>">&nbsp;&nbsp;<? if ($action == 'delete') { echo $postcode; } else { echo '<input type="text" name="postcode" maxlength="8" value="' . @$postcode . '">&nbsp;' . ENTRY_POST_CODE_TEXT; } ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE; ?>" size="<? echo ENTRY_FONT_SIZE; ?>" color="<? echo ENTRY_FONT_COLOR; ?>">&nbsp;&nbsp;<? echo ENTRY_CITY; ?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE; ?>" size="<? echo VALUE_FONT_SIZE; ?>" color="<? echo VALUE_FONT_COLOR; ?>">&nbsp;&nbsp;<? if ($action == 'delete') { echo $city; } else { echo '<input type="text" name="city" maxlength="32" value="' . @$city . '">&nbsp;' . ENTRY_CITY_TEXT; } ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE; ?>" size="<? echo ENTRY_FONT_SIZE; ?>" color="<? echo ENTRY_FONT_SIZE; ?>">&nbsp;<? echo ENTRY_COUNTRY; ?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE; ?>" size="<? echo VALUE_FONT_SIZE; ?>" color="<? echo VALUE_FONT_SIZE; ?>">&nbsp;&nbsp;<? echo tep_countries_pull_down('name="countries_id" onChange="update_zone(this.form);"', $country_id); ?></font></td>
          </tr>
<?
    if (ACCOUNT_STATE) {
?>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE; ?>" size="<? echo ENTRY_FONT_SIZE; ?>" color="<? echo ENTRY_FONT_COLOR; ?>">&nbsp;&nbsp;<? echo ENTRY_STATE; ?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE; ?>" size="<? echo VALUE_FONT_SIZE; ?>" color="<? echo VALUE_FONT_COLOR; ?>">&nbsp;&nbsp;<? echo tep_get_zone_list("zone_id", $country_id, $zone_id, "onChange=\"resetStateText(this.form)\";"); ?>&nbsp;<? echo ENTRY_STATE_TEXT; ?></font></td>
          </tr>
          <tr>
            <td></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE; ?>" size="<? echo VALUE_FONT_SIZE; ?>" color="<? echo VALUE_FONT_COLOR; ?>">&nbsp;&nbsp;<? if ($action == 'delete') { echo $state; } else { echo '<input type="text" name="state" onChange="resetZoneSelected(this.form);" maxlength="32" value="' . @$state . '">&nbsp;' . ENTRY_STATE_TEXT; } ?></font></td>
          </tr>
<?
    }
?>
          <tr>
            <td colspan="2"><font face="<? echo ENTRY_FONT_FACE; ?>" size="<? echo ENTRY_FONT_SIZE; ?>" color="<? echo ENTRY_FONT_COLOR; ?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="3" nowrap><font face="<? echo CATEGORY_FONT_FACE; ?>" size="<? echo CATEGORY_FONT_SIZE; ?>" color="<? echo CATEGORY_FONT_COLOR; ?>"><? echo CATEGORY_CONTACT; ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE; ?>" size="<? echo ENTRY_FONT_SIZE; ?>" color="<? echo ENTRY_FONT_COLOR; ?>">&nbsp;&nbsp;<? echo ENTRY_TELEPHONE_NUMBER; ?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE; ?>" size="<? echo VALUE_FONT_SIZE; ?>" color="<? echo VALUE_FONT_COLOR; ?>">&nbsp;&nbsp;<? if ($action == 'delete') { echo $telephone; } else { echo '<input type="text" name="telephone" maxlength="32" value="' . @$telephone . '">&nbsp;' . ENTRY_TELEPHONE_NUMBER_TEXT; } ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE; ?>" size="<? echo ENTRY_FONT_SIZE; ?>" color="<? echo ENTRY_FONT_COLOR; ?>">&nbsp;&nbsp;<? echo ENTRY_FAX_NUMBER; ?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE; ?>" size="<? echo VALUE_FONT_SIZE; ?>" color="<? echo VALUE_FONT_COLOR; ?>">&nbsp;&nbsp;<? if ($action == 'delete') { echo $fax; } else { echo '<input type="text" name="fax" maxlength="32" value="' . @$fax . '">&nbsp;' . ENTRY_FAX_NUMBER_TEXT; } ?></font></td>
          </tr>
          <tr>
            <td colspan="2"><font face="<? echo ENTRY_FONT_FACE; ?>" size="<? echo ENTRY_FONT_SIZE; ?>" color="<? echo ENTRY_FONT_COLOR; ?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="2" nowrap><font face="<? echo CATEGORY_FONT_FACE; ?>" size="<? echo CATEGORY_FONT_SIZE; ?>" color="<? echo CATEGORY_FONT_COLOR; ?>"><? echo CATEGORY_OPTIONS; ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE; ?>" size="<? echo ENTRY_FONT_SIZE; ?>" color="<? echo ENTRY_FONT_COLOR; ?>">&nbsp;&nbsp;<? echo ENTRY_NEWSLETTER; ?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE; ?>" size="<? echo VALUE_FONT_SIZE; ?>" color="<? echo VALUE_FONT_COLOR; ?>">&nbsp;&nbsp;<? if ($action == 'delete') { echo $newsletter; } else { echo '<select name="newsletter">'; if (@$newsletter=="1") { echo '<option selected value="1">'; } else { echo '<option value="1">'; } ?><?php echo ENTRY_NEWSLETTER_YES; ?></option><?php if (@$newsletter=="0") { echo '<option selected value="0">'; } else { echo '<option value="0">'; } ?><? echo ENTRY_NEWSLETTER_NO; ?></option></select><? } ?></font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td align="right"><br><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><? echo tep_image_submit(DIR_WS_IMAGES . 'button_update.gif', '66', '20', '0', IMAGE_UPDATE); ?>&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action', 'cID')) . 'info=' . $HTTP_GET_VARS['cID'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>'; ?>&nbsp;</font></td>
      </tr></form>
<?
  } else {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<? echo HEADING_FONT_FACE; ?>" size="<? echo HEADING_FONT_SIZE; ?>" color="<? echo HEADING_FONT_COLOR; ?>">&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</font></td>
            <td align="right" nowrap><br><form name="search" <? echo 'action="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(), 'NONSSL') . '"'; ?> method="get"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo HEADING_TITLE_SEARCH; ?>&nbsp;<input type="text" name="search" value="<? echo $HTTP_GET_VARS['search']; ?>" size="8">&nbsp;<? echo tep_image_submit(DIR_WS_IMAGES . 'button_search.gif', '66', '20', '0', IMAGE_SEARCH); ?>&nbsp;</font></form></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2"><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td align="center" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_ID; ?>&nbsp;</b></font></td>
                <td nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_FIRSTNAME; ?>&nbsp;</b></font></td>
                <td nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_LASTNAME; ?>&nbsp;</b></font></td>
                <td align="center" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_ACCOUNT_CREATED; ?>&nbsp;</b></font></td>
                <td align="center" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_ACTION; ?>&nbsp;</b></font></td>
              </tr>
              <tr>
                <td colspan="5"><? echo tep_black_line(); ?></td>
              </tr>
<?
    $search = (strlen($HTTP_GET_VARS['search']) > 0) ? '%' . $search . '%' : '%';
    $customers_query_raw = "select customers_id, customers_lastname, customers_firstname, customers_email_address, customers_country_id from customers where customers_lastname like '" . $search . "' or  customers_firstname like '" . $search . "' order by customers_id DESC";
    $customers_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $customers_query_raw, $customers_query_numrows);
    $customers_query = tep_db_query($customers_query_raw);
    $rows = 0;
    while ($customers = tep_db_fetch_array($customers_query)) {
      $rows++;

      $info_query = tep_db_query("select customers_info_date_account_created as date_account_created, customers_info_date_account_last_modified as date_account_last_modified, customers_info_date_of_last_logon as date_last_logon, customers_info_number_of_logons as number_of_logons from customers_info where customers_info_id = '" . $customers['customers_id'] . "'");
      $info = tep_db_fetch_array($info_query);

      if (((!$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['info'] == $customers['customers_id'])) && (!$cuInfo)) {
        $country_query = tep_db_query("select countries_name from countries where countries_id = '" . $customers['customers_country_id'] . "'");
        $country = tep_db_fetch_array($country_query);

        $reviews_query = tep_db_query("select count(*) as number_of_reviews from reviews_extra where customers_id = '" . $customers['customers_id'] . "'");
        $reviews = tep_db_fetch_array($reviews_query);

        $customer_info = tep_array_merge($country, $info, $reviews);

        $cuInfo_array = tep_array_merge($customers, $customer_info);
        $cuInfo = new customerInfo($cuInfo_array);
      }

      if ($customers['customers_id'] == @$cuInfo->id) {
        echo '              <tr bgcolor="#b0c8df" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action', 'info', 'x', 'y')) . 'action=edit&cID=' . $cuInfo->id, 'NONSSL') . '\'">' . "\n";
      } else {
        echo '              <tr bgcolor="#d8e1eb" onmouseover="this.style.background=\'#cc9999\';this.style.cursor=\'hand\'" onmouseout="this.style.background=\'#d8e1eb\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('info', 'action', 'x', 'y')) . 'info=' . $customers['customers_id'], 'NONSSL') . '\'">' . "\n";
      }
?>
                <td align="center" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $customers['customers_id']; ?>&nbsp;</font></td>
                <td nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $customers['customers_firstname']; ?>&nbsp;</font></td>
                <td nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $customers['customers_lastname']; ?>&nbsp;</font></td>
                <td align="center" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo tep_date_short($info['date_account_created']); ?>&nbsp;</font></td>
<?
      if ($customers['customers_id'] == @$cInfo->id) {
?>
                <td align="center" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', 13, 13, 0, ''); ?>&nbsp;</font></td>
<?
      } else {
?>
                <td align="center" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('info', 'action', 'x', 'y')) . 'info=' . $customers['customers_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', '13', '13', '0', IMAGE_ICON_INFO) . '</a>'; ?>&nbsp;</font></td>
<?
      }
?>
              </tr>
<?
    }
?>
              <tr>
                <td colspan="5"><? echo tep_black_line(); ?></td>
              </tr>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $customers_split->display_count($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?>&nbsp;<br>&nbsp;<? echo TEXT_RESULT_PAGE; ?> <? echo $customers_split->display_links($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>&nbsp;</font></td>
                    <td align="right" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? if ($HTTP_GET_VARS['search']) echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS, '', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_reset.gif', '66', '20', '0', IMAGE_RESET) . '</a>'; ?>&nbsp;</font></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="25%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $cuInfo->name . '</b>&nbsp;');
?>
              <tr bgcolor="#81a2b6">
                <td>
                  <? new infoBoxHeading($info_box_contents); ?>
                </td>
              </tr>
              <tr bgcolor="#81a2b6">
                <td><? echo tep_black_line(); ?></td>
              </tr>
<?
  if ($HTTP_GET_VARS['action'] == 'confirm') {
    $form = '<form action="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action')) . 'action=deleteconfirm', 'NONSSL') . '" method="post"><input type="hidden" name="customers_id" value="' . $cuInfo->id . '">'  ."\n";

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_DELETE_INTRO . '<br>&nbsp;<b>' . $cuInfo->name . '</b><br>&nbsp;');
    $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_WS_IMAGES . 'button_delete.gif', '66', '20', '0', IMAGE_DELETE) . '&nbsp;<a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>');
  } else {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action', 'info', 'x', 'y')) . 'action=edit&cID=' . $cuInfo->id, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_edit.gif', '66', '20', '0', IMAGE_EDIT) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action', 'info', 'x', 'y')) . 'action=confirm&info=' . $cuInfo->id, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_delete.gif', '66', '20', '0', IMAGE_DELETE) . '</a>');
    $info_box_contents[] = array('align' => 'left', 'params' => 'nowrap', 'text' => '<br>&nbsp;' . TEXT_DATE_ACCOUNT_CREATED . ' ' . tep_date_short($cuInfo->date_account_created) . '<br>&nbsp;' . TEXT_DATE_ACCOUNT_LAST_MODIFIED . ' ' . tep_date_short($cuInfo->date_account_last_modified) . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'params' => 'nowrap', 'text' => '&nbsp;' . TEXT_INFO_DATE_LAST_LOGON . ' '  . tep_date_short($cuInfo->date_last_logon) . '<br>&nbsp;' . TEXT_INFO_NUMBER_OF_LOGONS . ' ' . $cuInfo->number_of_logons . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_COUNTRY . ' ' . $cuInfo->country . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_NUMBER_OF_REVIEWS . ' ' . $cuInfo->number_of_reviews);
  }
?>
              <tr bgcolor="#b0c8df"><? echo $form; ?>
                <td>
                  <? new infoBox($info_box_contents); ?>
                </td>
              <? if ($form) echo '</form>'; ?></tr>
              <tr bgcolor="#b0c8df">
                <td><? echo tep_black_line(); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?
  }
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<? $include_file = DIR_WS_INCLUDES . 'footer.php';  include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<? $include_file = DIR_WS_INCLUDES . 'application_bottom.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
