<? include('includes/application_top.php'); ?>
<?
  // $Id: login_create.php,v 1.9 2001/04/28 12:11:12 hpdl Exp $
  if ($HTTP_GET_VARS['action'] == 'process') {
    $check_customer = tep_db_query("select customers_id, customers_password from customers where customers_email_address = '" . $HTTP_POST_VARS['email_address'] . "'");
    if (tep_db_num_rows($check_customer)) {
      $check_customer_values = tep_db_fetch_array($check_customer);
      // Check that password is good
      $pass_ok = validate_password($HTTP_POST_VARS['password'], $check_customer_values['customers_password']);
      if ($pass_ok != true) {
	  if (@$HTTP_POST_VARS['origin']) {
            if (@$HTTP_POST_VARS['products_id']) {
              header('Location: ' . tep_href_link(FILENAME_LOGIN_CREATE, 'login=fail&origin=' . $HTTP_POST_VARS['origin'] . '&products_id=' . $HTTP_POST_VARS['products_id'], 'NONSSL'));
              tep_exit();
            } elseif (@$HTTP_POST_VARS['order_id']) {
              header('Location: ' . tep_href_link(FILENAME_LOGIN_CREATE, 'login=fail&origin=' . $HTTP_POST_VARS['origin'] . '&order_id=' . $HTTP_POST_VARS['order_id'], 'NONSSL'));
              tep_exit();
            } else {
              header('Location: ' . tep_href_link(FILENAME_LOGIN_CREATE, 'login=fail&origin=' . $HTTP_POST_VARS['origin'], 'NONSSL'));
              tep_exit();
            }
	  } else {
            header('Location: ' . tep_href_link(FILENAME_LOGIN_CREATE, 'login=fail', 'NONSSL'));
            tep_exit();
	  }
	  tep_exit();
      }
 
      $customer_id = $check_customer_values['customers_id'];
      tep_session_register('customer_id');

      if ($HTTP_POST_VARS['setcookie'] == '1') {
        setcookie('email_address', $HTTP_POST_VARS['email_address'], time()+2592000);
        setcookie('password', $HTTP_POST_VARS['password'], time()+2592000);
      } else {
        setcookie('email_address', '');
        setcookie('password', '');
      }

      $date_now = date('Ymd');
      tep_db_query("update customers_info set customers_info_date_of_last_logon = '" . $date_now . "', customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . $customer_id . "'");

// restore cart contents
      $cart->restore_contents();

      if (@$HTTP_POST_VARS['origin']) {
        if (@$HTTP_POST_VARS['products_id']) {
          header('Location: ' . tep_href_link($HTTP_POST_VARS['origin'], 'products_id=' . $HTTP_POST_VARS['products_id'], 'NONSSL'));
          tep_exit();
        } elseif (@$HTTP_POST_VARS['order_id']) {
          header('Location: ' . tep_href_link($HTTP_POST_VARS['origin'], 'order_id=' . $HTTP_POST_VARS['order_id'], 'NONSSL'));
          tep_exit();
        } else {
          if (@$HTTP_POST_VARS['connection'] == 'secure') {
            $connection_type = 'SSL';
          } else {
            $connection_type = 'NONSSL';
          }
          header('Location: ' . tep_href_link($HTTP_POST_VARS['origin'], '', $connection_type));
          tep_exit();
        }
      } else {
        header('Location: ' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'));
        tep_exit();
      }
    } else {
      if (@$HTTP_POST_VARS['origin']) {
        if (@$HTTP_POST_VARS['products_id']) {
          header('Location: ' . tep_href_link(FILENAME_LOGIN_CREATE, 'login=fail&origin=' . $HTTP_POST_VARS['origin'] . '&products_id=' . $HTTP_POST_VARS['products_id'], 'NONSSL'));
          tep_exit();
        } elseif (@$HTTP_POST_VARS['order_id']) {
          header('Location: ' . tep_href_link(FILENAME_LOGIN_CREATE, 'login=fail&origin=' . $HTTP_POST_VARS['origin'] . '&order_id=' . $HTTP_POST_VARS['order_id'], 'NONSSL'));
          tep_exit();
        } else {
          header('Location: ' . tep_href_link(FILENAME_LOGIN_CREATE, 'login=fail&origin=' . $HTTP_POST_VARS['origin'], 'NONSSL'));
          tep_exit();
        }
      } else {
        header('Location: ' . tep_href_link(FILENAME_LOGIN_CREATE, 'login=fail', 'NONSSL'));
        tep_exit();
      }
    }
  } else {
?>
<? $include_file = DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN_CREATE; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_LOGIN_CREATE, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE . '</a>'; ?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function session_win() {
  window.open("<? echo FILENAME_INFO_SHOPPING_CART; ?>","info_shopping_cart","height=460,width=430,toolbar=no,statusbar=no,scrollbars=yes").focus();
}
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

  var first_name = document.login_create.firstname.value;
  var last_name = document.login_create.lastname.value;
<?
  if (ACCOUNT_DOB) {
     echo 'var dob = document.login_create.dob.value;' . "\n";
  }
  if (ACCOUNT_STATE) {
?>
  if (document.login_create.zone_id.options.length > 1) {
    var zone_id = document.login_create.zone_id.options[document.login_create.zone_id.selectedIndex].value;
  }
<?
  }
?>
  var country = document.login_create.country.options[document.login_create.country.selectedIndex].value;
  var email_address = document.login_create.email_address.value;  
  var street_address = document.login_create.street_address.value;
  var postcode = document.login_create.postcode.value;
  var city = document.login_create.city.value;
  var telephone = document.login_create.telephone.value;
  var password = document.login_create.password.value;
  var confirmation = document.login_create.confirmation.value;

<?
  if (ACCOUNT_GENDER) {
?>
  if (document.login_create.gender[0].checked || document.login_create.gender[1].checked) {
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

<?
  if (ACCOUNT_STATE) {
?>
  if (document.login_create.zone_id.options.length <= 1) {
    if (document.login_create.state.value == "" || document.login_create.state.value.length < <? echo ENTRY_STATE_MIN_LENGTH; ?> ) {
       error_message = error_message + "<? echo JS_STATE; ?>";
       error = 1;
    }
  } else {
    document.login_create.state.value = '';
    if (document.login_create.zone_id.selectedIndex == 0) {
       error_message = error_message + "<? echo JS_ZONE; ?>";
       error = 1;
    }
  }
<?
  }
?>

  if (postcode == "" || postcode.length < <? echo ENTRY_POSTCODE_MIN_LENGTH; ?>) {
    error_message = error_message + "<? echo JS_POST_CODE; ?>";
    error = 1;
  }

  if (city == "" || city.length < <? echo ENTRY_CITY_MIN_LENGTH; ?>) {
    error_message = error_message + "<? echo JS_CITY; ?>";
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
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'table_background_login.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><form name="login" method="post" action="<? echo tep_href_link(FILENAME_LOGIN_CREATE, 'action=process', 'NONSSL'); ?>"><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td align="right" class="main" nowrap>&nbsp;<? echo ENTRY_EMAIL_ADDRESS; ?>&nbsp;</td>
            <td class="main" nowrap>&nbsp;<input type="text" name="email_address" maxlength="96" value="<? if (($HTTP_COOKIE_VARS['email_address']) && ($HTTP_COOKIE_VARS['password'])) { echo $HTTP_COOKIE_VARS['email_address']; } ?>">&nbsp;</td>
          </tr>
          <tr>
            <td align="right" class="main" nowrap>&nbsp;<? echo ENTRY_PASSWORD; ?>&nbsp;</td>
            <td class="main" nowrap>&nbsp;<input type="password" name="password" maxlength="12" value="<? if (($HTTP_COOKIE_VARS['email_address']) && ($HTTP_COOKIE_VARS['password'])) { echo $HTTP_COOKIE_VARS['password']; } ?>">&nbsp;</td>
          </tr>
        </table>
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="2"><br><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td valign="top" class="smallText" nowrap>&nbsp;<label for="setcookie"><input type="checkbox" name="setcookie" value="1" id="setcookie" <? if (($HTTP_COOKIE_VARS['email_address']) && ($HTTP_COOKIE_VARS['password'])) { echo 'CHECKED'; } ?>>&nbsp;<? echo TEXT_COOKIE; ?></label>&nbsp;</td>
            <td align="right" valign="top" nowrap><? echo tep_image_submit(DIR_WS_IMAGES . 'button_log_in.gif', IMAGE_LOGIN); ?>&nbsp;</td>
          </tr>
          <tr>
            <td align="right" colspan="2" class="smallText" nowrap>&nbsp;<a href="<? echo tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'NONSSL'); ?>"><? echo TEXT_PASSWORD_FORGOTTEN; ?></a>&nbsp;</td>
          </tr>
<?
   $origin = '';
   if ($HTTP_GET_VARS['products_id']) {
     $origin = 'products_id=' . $HTTP_GET_VARS['products_id'];
   }
   if ($HTTP_GET_VARS['order_id']) {
     $origin = 'order_id=' . $HTTP_GET_VARS['order_id'];
   }
   if ($HTTP_GET_VARS['origin']) {
     if ($origin != '') {
       $origin = $origin . '?';
     }
     $origin = $origin . 'origin=' . $HTTP_GET_VARS['origin'];
   }
   if ($HTTP_GET_VARS['connection']) {
     if ($origin != '') {
       $origin = $origin . '?';
     }
     $origin = $origin . 'connection=' . $HTTP_GET_VARS['connection'];
   }

<?
  if (sizeof($cart->contents) > 0) {
?>
          <tr>
            <td colspan="2" class="smallText"><br><? echo TEXT_VISITORS_CART; ?></td>
          </tr>
<?
  }
  if ($HTTP_GET_VARS['login'] == 'fail') {
?>
          <tr>
            <td colspan="2" class="smallText" nowrap><? echo TEXT_LOGIN_ERROR; ?></td>
          </tr>
<?
  }
?>
        </table><? if ($HTTP_GET_VARS['origin']) { echo '<input type="hidden" name="origin" value="' . $HTTP_GET_VARS['origin'] . '">'; } ?><? if ($HTTP_GET_VARS['connection']) { echo '<input type="hidden" name="connection" value="' . $HTTP_GET_VARS['connection'] . '">'; } ?><? if ($HTTP_GET_VARS['products_id']) { echo '<input type="hidden" name="products_id" value="' . $HTTP_GET_VARS['products_id'] . '">'; } ?><? if ($HTTP_GET_VARS['order_id']) { echo '<input type="hidden" name="order_id" value="' . $HTTP_GET_VARS['order_id'] . '">'; } ?></form></td>
      </tr>
    </table>
<!-- Middle //-->
    <form name="login_create" method="post" action="<? echo tep_href_link(FILENAME_CREATE_ACCOUNT_PROCESS, '', 'NONSSL'); ?>" onSubmit="return check_form();"><input type="hidden" name="action" value="process"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading" nowrap>&nbsp;<? echo HEADING2_TITLE; ?>&nbsp;</td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'table_background_account.gif', HEADING2_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
<?
  $rowspan = 5+ACCOUNT_GENDER+ACCOUNT_DOB;
?>
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
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<input type="radio" name="gender" value="m">&nbsp;<? echo MALE; ?>&nbsp;&nbsp;<input type="radio" name="gender" value="f">&nbsp;&nbsp;<? echo FEMALE; ?>&nbsp;<? echo ENTRY_GENDER_TEXT; ?></font></td>
          </tr>
<?
   }
?>
          <tr>
            <td colspan="2" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_FIRST_NAME; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<input type="text" name="firstname" maxlength="32">&nbsp;<? echo ENTRY_FIRST_NAME_TEXT; ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_LAST_NAME; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<input type="text" name="lastname" maxlength="32">&nbsp;<? echo ENTRY_LAST_NAME_TEXT; ?></font></td>
          </tr>
<?
   if (ACCOUNT_DOB) {
?>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_DATE_OF_BIRTH; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<input type="text" name="dob" value="<? echo DOB_FORMAT_STRING; ?>" maxlength="10">&nbsp;<? echo ENTRY_DATE_OF_BIRTH_TEXT; ?></font></td>
          </tr>
<?
   }
   $rowspan = 5+ACCOUNT_SUBURB+ACCOUNT_STATE+ACCOUNT_STATE;
?>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_EMAIL_ADDRESS; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<input type="text" name="email_address" maxlength="96">&nbsp;<? echo ENTRY_EMAIL_ADDRESS_TEXT; ?></font></td>
          </tr>
          <tr>
            <td colspan="2" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="<? echo $rowspan; ?>" class="accountCategory" nowrap><? echo CATEGORY_ADDRESS; ?></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_STREET_ADDRESS; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<input type="text" name="street_address" maxlength="64">&nbsp;<? echo ENTRY_STREET_ADDRESS_TEXT; ?></font></td>
          </tr>
<?
  if (ACCOUNT_SUBURB) {
?>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_SUBURB; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<input type="text" name="suburb" maxlength="32">&nbsp;<? echo ENTRY_SUBURB_TEXT; ?></font></td>
          </tr>
<?
   }
?>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_CITY; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<input type="text" name="city" maxlength="32">&nbsp;<? echo ENTRY_CITY_TEXT; ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_COUNTRY; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>
            &nbsp;<?tep_get_country_list("country", STORE_COUNTRY, (ACCOUNT_STATE)?"onChange=\"update_zone(this.form);\"":""); ?>&nbsp;<? echo ENTRY_COUNTRY_TEXT; ?></font></td>
          </tr>
<?
  if (ACCOUNT_STATE) {
?>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_STATE; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>
            &nbsp;<?tep_get_zone_list("zone_id", STORE_COUNTRY, "", "onChange=\"resetStateText(this.form)\";"); ?></select>&nbsp;<? echo ENTRY_STATE_TEXT; ?></font></td>
          </tr>
          <tr>
            <td></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>
            &nbsp;<input type="text" name="state" onChange="resetZoneSelected(this.form);" maxlength="32">&nbsp;<? echo ENTRY_STATE_TEXT; ?></font></td>
          </tr>
<?
   }
?>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_POST_CODE; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<input type="text" name="postcode" maxlength="8">&nbsp;<? echo ENTRY_POST_CODE_TEXT; ?></font></td>
          </tr>
          <tr>
            <td colspan="2" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="3" class="accountCategory" nowrap><? echo CATEGORY_CONTACT; ?></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_TELEPHONE_NUMBER; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<input type="text" name="telephone" maxlength="32">&nbsp;<? echo ENTRY_TELEPHONE_NUMBER_TEXT; ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_FAX_NUMBER; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<input type="text" name="fax" maxlength="32">&nbsp;<? echo ENTRY_FAX_NUMBER_TEXT; ?></font></td>
          </tr>
          <tr>
            <td colspan="2" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="2" class="accountCategory" nowrap><? echo CATEGORY_OPTIONS; ?></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_NEWSLETTER; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<select name="newsletter"><option value="1"><?php echo ENTRY_NEWSLETTER_YES; ?></option><option selected value="0"><? echo ENTRY_NEWSLETTER_NO; ?></option></font></td>
          </tr>
          <tr>
            <td colspan="2" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="3" class="accountCategory" nowrap><? echo CATEGORY_PASSWORD; ?></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_PASSWORD; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<input type="password" name="password" maxlength="12">&nbsp;<? echo ENTRY_PASSWORD_TEXT; ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_PASSWORD_CONFIRMATION; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<input type="password" name="confirmation" maxlength="12">&nbsp;<? echo ENTRY_PASSWORD_CONFIRMATION_TEXT; ?></font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td align="right" class="main" nowrap><br><? echo tep_image_submit(DIR_WS_IMAGES . 'button_done.gif', IMAGE_DONE); ?>&nbsp;&nbsp;</td>
      </tr>
    </table><? if ($HTTP_GET_VARS['origin']) { echo '<input type="hidden" name="origin" value="' . $HTTP_GET_VARS['origin'] . '">'; } ?><? if ($HTTP_GET_VARS['connection']) { echo '<input type="hidden" name="connection" value="' . $HTTP_GET_VARS['connection'] . '">'; } ?></form></td>
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
<?
  }
?>
<? $include_file = DIR_WS_INCLUDES . 'application_bottom.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
