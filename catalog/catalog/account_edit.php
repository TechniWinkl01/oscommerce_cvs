<?php
/*
  $Id: account_edit.php,v 1.37 2001/06/13 20:12:15 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_EDIT);

  $location = ' : <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_1 . '</a> : <a href="' . tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_2 . '</a>';

  if (!tep_session_is_registered('customer_id')) {
    header('Location: ' . tep_href_link(FILENAME_LOGIN, 'origin=' . FILENAME_ACCOUNT_EDIT, 'NONSSL')); tep_exit();
  }
?>
<html>
<head>
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function resetStateText(theForm) {
  theForm.state.value = '';
  if (theForm.zone_id.options.length > 1) {
    theForm.state.value = "<?php echo JS_STATE_SELECT; ?>";
  }
}

function resetZoneSelected(theForm) {
  if (theForm.zone_id.options.length > 1) {
    theForm.state.value = "<?php echo JS_STATE_SELECT; ?>";
  }
}

function update_zone(theForm) {
  var NumState = theForm.zone_id.options.length;
  var SelectedCountry = '';

  while(NumState > 0) {
    NumState--;
    theForm.zone_id.options[NumState] = null;
  }         

  SelectedCountry = theForm.country.options[theForm.country.selectedIndex].value;
            
<?php tep_js_zone_list('SelectedCountry', 'theForm'); ?>

  resetStateText(theForm);
}

function check_form() {
  var error = 0;
  var error_message = "<?php echo JS_ERROR; ?>";

  var first_name = document.account_edit.firstname.value;
  var last_name = document.account_edit.lastname.value;
<?php
   if (ACCOUNT_DOB) echo '  var dob = document.account_edit.dob.value;' . "\n";
?>
  var email_address = document.account_edit.email_address.value;  
  var street_address = document.account_edit.street_address.value;
  var postcode = document.account_edit.postcode.value;
  var city = document.account_edit.city.value;
  var telephone = document.account_edit.telephone.value;
  var password = document.account_edit.password.value;
  var confirmation = document.account_edit.confirmation.value;

<?php
   if (ACCOUNT_GENDER) {
?>
  if (document.account_edit.gender[0].checked || document.account_edit.gender[1].checked) {
  } else {
    error_message = error_message + "<?php echo JS_GENDER; ?>";
    error = 1;
  }
<?
  }
?>
 
  if (first_name == '' || first_name.length < <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_FIRST_NAME; ?>";
    error = 1;
  }

  if (last_name == '' || last_name.length < <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_LAST_NAME; ?>";
    error = 1;
  }

<?
   if (ACCOUNT_DOB) {
?>
  if (dob == '' || dob.length < <?php echo ENTRY_DOB_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_DOB; ?>";
    error = 1;
  }
<?
  }
?>
 
  if (email_address == '' || email_address.length < <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_EMAIL_ADDRESS; ?>";
    error = 1;
  }

  if (street_address == '' || street_address.length < <?php echo ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_ADDRESS; ?>";
    error = 1;
  }

  if (postcode == '' || postcode.length < <?php echo ENTRY_POSTCODE_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_POST_CODE; ?>";
    error = 1;
  }

  if (city == '' || city.length < <?php echo ENTRY_CITY_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_CITY; ?>";
    error = 1;
  }

<?php
  if (ACCOUNT_STATE) {
?>
  if (document.account_edit.zone_id.options.length <= 1) {
    if (document.account_edit.state.value == '' || document.account_edit.state.value.length < <?php echo ENTRY_STATE_MIN_LENGTH; ?> ) {
       error_message = error_message + "<?php echo JS_STATE; ?>";
       error = 1;
    }
  } else {
    document.account_edit.state.value = '';
    if (document.account_edit.zone_id.selectedIndex == 0) {
       error_message = error_message + "<?php echo JS_ZONE; ?>";
       error = 1;
    }
  }
<?
  }
?>

  if (document.account_edit.country.value == 0) {
    error_message = error_message + "<?php echo JS_COUNTRY; ?>";
    error = 1;
  }

  if (telephone == '' || telephone.length < <?php echo ENTRY_TELEPHONE_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_TELEPHONE; ?>";
    error = 1;
  }

  if ((password != confirmation) || (password == '' || password.length < <?php echo ENTRY_PASSWORD_MIN_LENGTH; ?>)) {
    error_message = error_message + "<?php echo JS_PASSWORD; ?>";
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
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php
  require(DIR_WS_INCLUDES . 'column_left.php');
?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><form name="account_edit" method="post" action="<? echo tep_href_link(FILENAME_ACCOUNT_EDIT_PROCESS, '', 'NONSSL'); ?>" onSubmit="return check_form();"><input type="hidden" name="action" value="process"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td width="100%" class="topBarTitle">&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right">&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'table_background_account.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><br>
<?php
  $account_query = tep_db_query("select customers_gender, customers_firstname, customers_lastname, customers_dob, customers_email_address, customers_street_address, customers_suburb, customers_postcode, customers_city, customers_state, customers_zone_id, customers_country_id, customers_telephone, customers_fax, customers_newsletter from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
  $account = tep_db_fetch_array($account_query);

  require(DIR_WS_MODULES . 'account_details.php');
?>
        </td>
      </tr>
      <tr>
        <td class="main"><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
            <td align="right" class="main"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
          </tr>
        </table></td>
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
