<? include('includes/application_top.php'); ?>
<?
  if ($HTTP_GET_VARS['action'] == 'update') {
    $date_now = date('Ymd');
    if (ACCOUNT_DOB) {
       $dob_ordered = substr($HTTP_POST_VARS['dob'], -4) . substr($HTTP_POST_VARS['dob'], 3, 2) . substr($HTTP_POST_VARS['dob'], 0, 2);
    }
    $update_query = 'update ' . TABLE_CUSTOMERS . ' set ';
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
    tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = '" . $date_now . "' where customers_info_id = '" . $HTTP_POST_VARS['customers_id'] . "'");
    header('Location: ' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action')) . 'info=' . $HTTP_POST_VARS['customers_id'], 'NONSSL')); tep_exit();
  } elseif ($HTTP_GET_VARS['action'] == 'deleteconfirm') {
    tep_db_query("delete from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $HTTP_POST_VARS['customers_id'] . "'");
    tep_db_query("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . $HTTP_POST_VARS['customers_id'] . "'");
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_WS_INCLUDES . 'column_left.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td class="topBarTitle">&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
<?
  if ($HTTP_GET_VARS['action'] == 'edit') {
    $customers_query = tep_db_query("select customers_gender, customers_firstname, customers_lastname, customers_dob, customers_email_address, customers_street_address, customers_suburb, customers_postcode, customers_city, customers_state, customers_zone_id, customers_country_id, customers_telephone, customers_fax, customers_newsletter from " . TABLE_CUSTOMERS . " where customers_id = '" . $HTTP_GET_VARS['cID'] . "'");
    $customers = tep_db_fetch_array($customers_query);

    $gender = $customers['customers_gender'];
    $firstname = $customers['customers_firstname'];
    $lastname = $customers['customers_lastname'];
    $dob = substr($customers['customers_dob'], -2) . '/' . substr($customers['customers_dob'], 4, 2) . '/' . substr($customers['customers_dob'], 0, 4);
    $email_address = $customers['customers_email_address'];
    $street_address = $customers['customers_street_address'];
    $suburb = $customers['customers_suburb'];
    $postcode = $customers['customers_postcode'];
    $city = $customers['customers_city'];
    $state = $customers['customers_state'];
    $zone_id = $customers['customers_zone_id'];
    $country_id = $customers['customers_country_id'];
    $telephone = $customers['customers_telephone'];
    $fax = $customers['customers_fax'];
    $newsletter = $customers['customers_newsletter'];
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right">&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'table_background_account.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr><form name="customers" <? echo 'action="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action', 'cID')) . 'action=update', 'NONSSL') . '"'; ?> method="post" onSubmit="return check_form();"><input type="hidden" name="customers_id" value="<? echo $HTTP_GET_VARS['cID']; ?>">
        <td class="formAreaTitle"><br><?php echo CATEGORY_PERSONAL; ?></td>
      <tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="0" cellpadding="2">
<?
    if (ACCOUNT_GENDER) {
?>
          <tr>
            <td class="main">&nbsp;<? echo ENTRY_GENDER; ?></td>
            <td class="main">&nbsp;<input type="radio" name="gender" value="m"<?
      if (@$gender == 'm') {
        echo ' CHECKED';
      } ?>>&nbsp;&nbsp;<? echo MALE; ?>&nbsp;&nbsp;<input type="radio" name="gender" value="f"<?
      if (@$gender == 'f') {
        echo ' CHECKED';
      } ?>>&nbsp;&nbsp;<? echo FEMALE; ?>&nbsp;<? echo ENTRY_GENDER_TEXT; ?></td>
          </tr>
<?
    }
?>
          <tr>
            <td class="main">&nbsp;<? echo ENTRY_FIRST_NAME; ?></td>
            <td class="main">&nbsp;<? if ($action == 'delete') { echo $firstname; } else { echo '<input type="text" name="firstname" maxlength="32" value="' . @$firstname . '">&nbsp;' . ENTRY_FIRST_NAME_TEXT; } ?></td>
          </tr>
          <tr>
            <td class="main">&nbsp;<? echo ENTRY_LAST_NAME; ?></td>
            <td class="main">&nbsp;<? if ($action == 'delete') { echo $lastname; } else { echo '<input type="text" name="lastname" maxlength="32" value="' . @$lastname . '">&nbsp;' . ENTRY_LAST_NAME_TEXT; } ?></td>
          </tr>
<?
    if (ACCOUNT_DOB) {
?>
          <tr>
            <td class="main">&nbsp;<? echo ENTRY_DATE_OF_BIRTH; ?></td>
            <td class="main">&nbsp;<? if ($action == 'delete') { echo $dob; } else { echo '<input type="text" name="dob" maxlength="10" value="' . @$dob . '">&nbsp;' . ENTRY_DATE_OF_BIRTH_TEXT; } ?></td>
          </tr>
<?
    }
?>
          <tr>
            <td class="main">&nbsp;<? echo ENTRY_EMAIL_ADDRESS; ?></td>
            <td class="main">&nbsp;<? if ($action == 'delete') { echo $email_address; } else { echo '<input type="text" name="email_address" maxlength="96" value="' . @$email_address . '">&nbsp;' . ENTRY_EMAIL_ADDRESS_TEXT; } ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><br><?php echo CATEGORY_ADDRESS; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">&nbsp;<? echo ENTRY_STREET_ADDRESS; ?></td>
            <td class="main">&nbsp;<? if ($action == 'delete') { echo $street_address; } else { echo '<input type="text" name="street_address" maxlength="64" value="' . @$street_address . '">&nbsp;' . ENTRY_STREET_ADDRESS_TEXT; }?></td>
          </tr>
<?
    if (ACCOUNT_SUBURB) {
?>
          <tr>
            <td class="main">&nbsp;<? echo ENTRY_SUBURB; ?></td>
            <td class="main">&nbsp;<? if ($action == 'delete') { echo $suburb; } else { echo '<input type="text" name="suburb" maxlength="32" value="' . @$suburb . '">&nbsp;' . ENTRY_SUBURB_TEXT; } ?></td>
          </tr>
<?
    }
?>
          <tr>
            <td class="main">&nbsp;<? echo ENTRY_POST_CODE; ?></td>
            <td class="main">&nbsp;<? if ($action == 'delete') { echo $postcode; } else { echo '<input type="text" name="postcode" maxlength="8" value="' . @$postcode . '">&nbsp;' . ENTRY_POST_CODE_TEXT; } ?></td>
          </tr>
          <tr>
            <td class="main">&nbsp;<? echo ENTRY_CITY; ?></td>
            <td class="main">&nbsp;<? if ($action == 'delete') { echo $city; } else { echo '<input type="text" name="city" maxlength="32" value="' . @$city . '">&nbsp;' . ENTRY_CITY_TEXT; } ?></td>
          </tr>
          <tr>
            <td class="main">&nbsp;<? echo ENTRY_COUNTRY; ?></td>
            <td class="main">&nbsp;<? echo tep_countries_pull_down('name="countries_id" onChange="update_zone(this.form);"', $country_id); ?></td>
          </tr>
<?
    if (ACCOUNT_STATE) {
?>
          <tr>
            <td class="main">&nbsp;<? echo ENTRY_STATE; ?></td>
            <td class="main">&nbsp;<? echo tep_get_zone_list("zone_id", $country_id, $zone_id, "onChange=\"resetStateText(this.form)\";"); ?>&nbsp;<? echo ENTRY_STATE_TEXT; ?></td>
          </tr>
          <tr>
            <td class="main">&nbsp;</td>
            <td class="main">&nbsp;<? if ($action == 'delete') { echo $state; } else { echo '<input type="text" name="state" onChange="resetZoneSelected(this.form);" maxlength="32" value="' . @$state . '">&nbsp;' . ENTRY_STATE_TEXT; } ?></td>
          </tr>
<?
    }
?>
        </table></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><br><? echo CATEGORY_CONTACT; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellpadding="2" cellspacing="0">
          <tr>
            <td class="main">&nbsp;<? echo ENTRY_TELEPHONE_NUMBER; ?></td>
            <td class="main">&nbsp;<? if ($action == 'delete') { echo $telephone; } else { echo '<input type="text" name="telephone" maxlength="32" value="' . @$telephone . '">&nbsp;' . ENTRY_TELEPHONE_NUMBER_TEXT; } ?></td>
          </tr>
          <tr>
            <td class="main">&nbsp;<? echo ENTRY_FAX_NUMBER; ?></td>
            <td class="main">&nbsp;<? if ($action == 'delete') { echo $fax; } else { echo '<input type="text" name="fax" maxlength="32" value="' . @$fax . '">&nbsp;' . ENTRY_FAX_NUMBER_TEXT; } ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><br><? echo CATEGORY_OPTIONS; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellpadding="2" cellspacing="0">
          <tr>
            <td class="main">&nbsp;<? echo ENTRY_NEWSLETTER; ?></td>
            <td class="main">&nbsp;<? if ($action == 'delete') { echo $newsletter; } else { echo '<select name="newsletter">'; if (@$newsletter=="1") { echo '<option selected value="1">'; } else { echo '<option value="1">'; } ?><?php echo ENTRY_NEWSLETTER_YES; ?></option><?php if (@$newsletter=="0") { echo '<option selected value="0">'; } else { echo '<option value="0">'; } ?><? echo ENTRY_NEWSLETTER_NO; ?></option></select><? } ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="right" class="main"><br><? echo tep_image_submit(DIR_WS_IMAGES . 'button_update.gif', IMAGE_UPDATE); ?>&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action', 'cID')) . 'info=' . $HTTP_GET_VARS['cID'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </tr></form>
<?
  } else {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right"><br><form name="search" <? echo 'action="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(), 'NONSSL') . '"'; ?> method="get"><span class="smallText">&nbsp;<? echo HEADING_TITLE_SEARCH; ?>&nbsp;<input type="text" name="search" value="<? echo $HTTP_GET_VARS['search']; ?>" size="8">&nbsp;<? echo tep_image_submit(DIR_WS_IMAGES . 'button_search.gif', IMAGE_SEARCH); ?></form></td>
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
                <td class="tableHeading" align="center">&nbsp;<? echo TABLE_HEADING_ID; ?>&nbsp;</td>
                <td class="tableHeading">&nbsp;<? echo TABLE_HEADING_FIRSTNAME; ?>&nbsp;</td>
                <td class="tableHeading">&nbsp;<? echo TABLE_HEADING_LASTNAME; ?>&nbsp;</td>
                <td class="tableHeading" align="center">&nbsp;<? echo TABLE_HEADING_ACCOUNT_CREATED; ?>&nbsp;</td>
                <td class="tableHeading" align="center">&nbsp;<? echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="5"><? echo tep_black_line(); ?></td>
              </tr>
<?
    $search = (strlen($HTTP_GET_VARS['search']) > 0) ? '%' . $search . '%' : '%';
    $customers_query_raw = "select customers_id, customers_lastname, customers_firstname, customers_email_address, customers_country_id from " . TABLE_CUSTOMERS . " where customers_lastname like '" . $search . "' or  customers_firstname like '" . $search . "' order by customers_id DESC";
    $customers_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $customers_query_raw, $customers_query_numrows);
    $customers_query = tep_db_query($customers_query_raw);
    $rows = 0;
    while ($customers = tep_db_fetch_array($customers_query)) {
      $rows++;

      $info_query = tep_db_query("select customers_info_date_account_created as date_account_created, customers_info_date_account_last_modified as date_account_last_modified, customers_info_date_of_last_logon as date_last_logon, customers_info_number_of_logons as number_of_logons from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $customers['customers_id'] . "'");
      $info = tep_db_fetch_array($info_query);

      if (((!$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['info'] == $customers['customers_id'])) && (!$cuInfo)) {
        $country_query = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . $customers['customers_country_id'] . "'");
        $country = tep_db_fetch_array($country_query);

        $reviews_query = tep_db_query("select count(*) as number_of_reviews from " . TABLE_REVIEWS . " where customers_id = '" . $customers['customers_id'] . "'");
        $reviews = tep_db_fetch_array($reviews_query);

        $customer_info = tep_array_merge($country, $info, $reviews);

        $cuInfo_array = tep_array_merge($customers, $customer_info);
        $cuInfo = new customerInfo($cuInfo_array);
      }

      if ($customers['customers_id'] == @$cuInfo->id) {
?>
          <tr class="selectedRow" onmouseover="this.style.cursor='hand'" onclick="document.location.href='<? echo tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action', 'info', 'x', 'y')) . 'action=edit&cID=' . $cuInfo->id, 'NONSSL'); ?>'">
<?
      } else {
?>
          <tr class="tableRow" onmouseover="this.className='tableRowOver';this.style.cursor='hand'" onmouseout="this.className='tableRow'" onclick="document.location.href='<? echo tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('info', 'action', 'x', 'y')) . 'info=' . $customers['customers_id'], 'NONSSL'); ?>'">
<?
      }
?>
                <td class="smallText" align="center">&nbsp;<? echo $customers['customers_id']; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<? echo $customers['customers_firstname']; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<? echo $customers['customers_lastname']; ?>&nbsp;</td>
                <td class="smallText" align="center">&nbsp;<? echo tep_date_short($info['date_account_created']); ?>&nbsp;</td>
<?
      if ($customers['customers_id'] == @$cuInfo->id) {
?>
                <td class="smallText" align="center">&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); ?>&nbsp;</td>
<?
      } else {
?>
                <td class="smallText" align="center">&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('info', 'action', 'x', 'y')) . 'info=' . $customers['customers_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; ?>&nbsp;</td>
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
                    <td class="smallText">&nbsp;<? echo $customers_split->display_count($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?>&nbsp;<br>&nbsp;<? echo TEXT_RESULT_PAGE; ?> <? echo $customers_split->display_links($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>&nbsp;</td>
                    <td class="smallText" align="right"><? if ($HTTP_GET_VARS['search']) echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS, '', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_reset.gif', IMAGE_RESET) . '</a>'; ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="25%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $cuInfo->name . '</b>&nbsp;');
?>
              <tr class="boxHeading">
                <td><? new infoBoxHeading($info_box_contents); ?></td>
              </tr>
              <tr class="boxHeading">
                <td><? echo tep_black_line(); ?></td>
              </tr>
<?
  if ($HTTP_GET_VARS['action'] == 'confirm') {
    $form = '<form action="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action')) . 'action=deleteconfirm', 'NONSSL') . '" method="post"><input type="hidden" name="customers_id" value="' . $cuInfo->id . '">'  ."\n";

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_DELETE_INTRO . '<br>&nbsp;<b>' . $cuInfo->name . '</b><br>&nbsp;');
    $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . '&nbsp;<a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
  } else {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action', 'info', 'x', 'y')) . 'action=edit&cID=' . $cuInfo->id, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_edit.gif', IMAGE_EDIT) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action', 'info', 'x', 'y')) . 'action=confirm&info=' . $cuInfo->id, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . '</a>');
    $info_box_contents[] = array('align' => 'left', 'params' => 'nowrap class="infoBox"', 'text' => '<br>' . TEXT_DATE_ACCOUNT_CREATED . ' ' . tep_date_short($cuInfo->date_account_created) . '<br>' . TEXT_DATE_ACCOUNT_LAST_MODIFIED . ' ' . tep_date_short($cuInfo->date_account_last_modified) . '<br>');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_DATE_LAST_LOGON . ' '  . tep_date_short($cuInfo->date_last_logon) . '<br>' . TEXT_INFO_NUMBER_OF_LOGONS . ' ' . $cuInfo->number_of_logons . '<br>');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_COUNTRY . ' ' . $cuInfo->country . '<br>');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_NUMBER_OF_REVIEWS . ' ' . $cuInfo->number_of_reviews);
  }
?>
              <tr><? echo $form; ?>
                <td class="Box"><? new infoBox($info_box_contents); ?></td>
              <? if ($form) echo '</form>'; ?></tr>
              <tr>
                <td class="Box"><? echo tep_black_line(); ?></td>
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
