<? include('includes/application_top.php'); ?>
<?
/* addres_book_process.php

   Default action: add an entry to the address book of the customer
   Other actions:

   process:      Check for errors
   update:       Check for errors and enter the altered data in the
                 address_book table
   modify:       modify an existing entry in the address book, needs
                 the GET-Var entry_id with the address_book_id to modify
   remove:       remove the entry identified with the GET_Var entry_id
                 from the address_book table

*/

// send to login when there is no Customer_id
  if (!@tep_session_is_registered('customer_id')) {
    tep_redirect(tep_href_link(FILENAME_LOGIN, 'origin=' . FILENAME_ADDRESS_BOOK_PROCESS, 'NONSSL'));
  }

// are we asked to remove an entry?
  if ((@$HTTP_GET_VARS['action'] == 'remove') && (@$HTTP_GET_VARS['entry_id'])) {
    // delete the entry from the address_book
    tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . $HTTP_GET_VARS['entry_id'] . "' and customers_id = '" . $customer_id . "'");
    // make sure the list of ids is correct
    tep_db_query("update " . TABLE_ADDRESS_BOOK . " set address_book_id = address_book_id -1 where address_book_id > " . $HTTP_GET_VARS['entry_id']  . " and customers_id = '" . $customer_id . "'");
    // adjust the default_address_id when necessary
//    if ($HTTP_GET_VARS['entry_id'] < $customer_default_address_id) {
//      tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = customers_default_address_id - 1 where customers_id = '" . $customer_id . "'")};
//    }
    tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'NONSSL'));
  }
//post-entry error checking when updating or modifying an entry
  $process = 0;
  if ((@$HTTP_POST_VARS['action'] == 'process') || (@$HTTP_POST_VARS['action'] == 'update')) {
    $process = 1;
    $error = 0;
    if (ACCOUNT_GENDER) {
      if ((@$HTTP_POST_VARS['gender'] == 'm') || (@$HTTP_POST_VARS['gender'] == 'f')) {
        $gender_error = 0;
      } else {
        $gender_error = 1;
        $error = 1;
      }
    }

    if (ACCOUNT_COMPANY) {
      if (@strlen(trim($HTTP_POST_VARS['company'])) < ENTRY_COMPANY_MIN_LENGTH) {
        $company_error = 1;
        $error = 1;
      } else {
        $company_error = 0;
      }
    }

    if (@strlen(trim($HTTP_POST_VARS['firstname'])) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $firstname_error = 1;
      $error = 1;
    } else {
      $firstname_error = 0;
    }

    if (@strlen(trim($HTTP_POST_VARS['lastname'])) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $lastname_error = 1;
      $error = 1;
    } else {
      $lasttname_error = 0;
    }

    if (@strlen(trim($HTTP_POST_VARS['street_address'])) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
      $street_address_error = 1;
      $error = 1;
    } else {
      $street_address_error = 0;
    }

    if (@strlen(trim($HTTP_POST_VARS['postcode'])) < ENTRY_POSTCODE_MIN_LENGTH) {
      $postcode_error = 1;
      $error = 1;
    } else {
      $postcode_error = 0;
    }

    if (@strlen(trim($HTTP_POST_VARS['city'])) < ENTRY_CITY_MIN_LENGTH) {
      $city_error = 1;
      $error = 1;
    } else {
      $city_error = 0;
    }

    if (@$HTTP_POST_VARS['country'] == "0") {
      $country_error = 1;
      $error = 1;
    } else {
      $country_error = 0;
    }
  }
//update when no errors occured
  if ((@$process == 1) && (@$error == 0) && (@$HTTP_POST_VARS['action'] == 'update')) {
    $update_query = 'update ' . TABLE_ADDRESS_BOOK . ' set ';
    if (ACCOUNT_GENDER) {
       $update_query = $update_query . "entry_gender = '" . $HTTP_POST_VARS['gender'] . "', ";
    }
    if (ACCOUNT_COMPANY) {
       $update_query = $update_query . "entry_company = '" . $HTTP_POST_VARS['company'] . "', ";
    }
    $update_query = $update_query . "entry_firstname = '" . $HTTP_POST_VARS['firstname'] . "', entry_lastname = '" . $HTTP_POST_VARS['lastname'] . "', entry_street_address = '" . $HTTP_POST_VARS['street_address'] . "', ";
    if (ACCOUNT_SUBURB) {
       $update_query = $update_query . "entry_suburb = '" . $HTTP_POST_VARS['suburb'] . "', ";
    }
    $update_query = $update_query . "entry_postcode = '" . $HTTP_POST_VARS['postcode'] . "', entry_city = '" . $HTTP_POST_VARS['city'] . "', ";
    if (ACCOUNT_STATE) {
       if ($HTTP_POST_VARS['zone_id'] > 0) {
         $update_query = $update_query . "entry_zone_id = '" . $HTTP_POST_VARS['zone_id'] . "', entry_state = '', ";
       } else {
         $update_query = $update_query . "entry_zone_id = '0', entry_state = '" . $HTTP_POST_VARS['state']. "', ";
       }
    }
    $update_query = $update_query . "entry_country_id = '" . $HTTP_POST_VARS['country'] . "' where address_book_id = '" . $HTTP_POST_VARS['entry_id']. "' and customers_id ='" . $customer_id . "'";
    tep_db_query($update_query);
// go back ot the address book page
    tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'NONSSL'));
  } elseif ((@$process == 1) && (@$error == 0)) {
    $company = "";
    $gender = "";
    $suburb = "";
    $state = "";
    $zone_id = 0;
    if (ACCOUNT_GENDER) {
       $gender = $HTTP_POST_VARS['gender'];
    }
    if (ACCOUNT_COMPANY) {
       $company = $HTTP_POST_VARS['company'];
    }
    if (ACCOUNT_SUBURB) {
       $suburb = $HTTP_POST_VARS['suburb'];
    }
    if (ACCOUNT_STATE) {
       $state = $HTTP_POST_VARS['state'];
       $zone_id = $HTTP_POST_VARS['zone_id'];
       if ($zone_id != 0) $state = '';
    }
// insert the new entry
    $update_query = "insert into " . TABLE_ADDRESS_BOOK . " values ('" . $customer_id . "', '" . $HTTP_POST_VARS['entry_id'] . "', '" . $gender . "', '" . $HTTP_POST_VARS['company'] . "', '" . $HTTP_POST_VARS['firstname'] . "', '" . $HTTP_POST_VARS['lastname'] . "', '" . $HTTP_POST_VARS['street_address'] . "', '" . $suburb . "', '" . $HTTP_POST_VARS['postcode'] . "', '" . $HTTP_POST_VARS['city'] . "', '" . $state . "', '" . $HTTP_POST_VARS['country'] . "', '" . $zone_id . "')";
    tep_db_query($update_query);
// Go back to where we came from
    if (@$HTTP_POST_VARS['origin']) {
      $connection = ($HTTP_POST_VARS['origin_connection']) ? $HTTP_POST_VARS['origin_connection'] : 'NONSSL';
      tep_redirect(tep_href_link($HTTP_POST_VARS['origin'], '', $connection));
    } else {
      tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'NONSSL'));
    }
  } else {
    if ((@$HTTP_GET_VARS['action'] == 'modify') && (@$HTTP_GET_VARS['entry_id'])) {
      $entry_query = 'select ';
      $state = '';
      $zone_id = 0;
      if (ACCOUNT_GENDER) {
         $entry_query = $entry_query . "entry_gender, ";
      }
      if (ACCOUNT_COMPANY) {
         $entry_query = $entry_query . "entry_company, ";
      }
      $entry_query = $entry_query . "entry_firstname, entry_lastname, entry_street_address, ";
      if (ACCOUNT_SUBURB) {
         $entry_query = $entry_query . "entry_suburb, ";
      }
      $entry_query = $entry_query . "entry_postcode, entry_city, ";
      if (ACCOUNT_STATE) {
         $entry_query = $entry_query . "entry_state, entry_zone_id, ";
      }
      $entry_query = $entry_query . "entry_country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $customer_id . "' and address_book_id = '" . $HTTP_GET_VARS['entry_id'] . "'";
      $entry = tep_db_query($entry_query);
      $entry_values = tep_db_fetch_array($entry);
      if (ACCOUNT_GENDER) {
         $gender = $entry_values['entry_gender'];
      }
      if (ACCOUNT_COMPANY) {
         $company = $entry_values['entry_company'];
      }
      $firstname = $entry_values['entry_firstname'];
      $lastname = $entry_values['entry_lastname'];
      $street_address = $entry_values['entry_street_address'];
      if (ACCOUNT_SUBURB) {
         $suburb = $entry_values['entry_suburb'];
      }
      $postcode = $entry_values['entry_postcode'];
      $city = $entry_values['entry_city'];
      if (ACCOUNT_STATE) {
         $state = $entry_values['entry_state'];
         $zone_id = $entry_values['entry_zone_id'];
      }
      $country = $entry_values['entry_country_id'];
    }
?>
<? $include_file = DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADDRESS_BOOK_PROCESS; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<?
  $location = ' : <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_1 . '</a> : <a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_2 . '</a>';
  if ((($HTTP_GET_VARS['action'] == 'modify') && ($HTTP_GET_VARS['entry_id'])) || (($HTTP_POST_VARS['action'] == 'update') && ($HTTP_POST_VARS['entry_id']))) {
    $location .= ' : <a href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'action=modify&entry_id=' . $HTTP_GET_VARS['entry_id'], 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_MODIFY_ENTRY . '</a>';
  } else {
    $location .= ' : <a href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_ADD_ENTRY . '</a>';
  }
?>
<html>
<head>
<title><? echo TITLE; ?></title>
<base href="<? echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
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

  var firstname = document.add_entry.firstname.value;
  var lastname = document.add_entry.lastname.value;
  var street_address = document.add_entry.street_address.value;
  var postcode = document.add_entry.postcode.value;
  var city = document.add_entry.city.value;

<?
 if (ACCOUNT_GENDER) {
?>
  if (document.add_entry.gender[0].checked || document.add_entry.gender[1].checked) {
  } else {
    error_message = error_message + "<? echo JS_GENDER; ?>";
    error = 1;
  }
<?
 }
?>
  if (firstname == "" || firstname.length < <? echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "<? echo JS_FIRST_NAME; ?>";
    error = 1;
  }

  if (lastname == "" || lastname.length < <? echo ENTRY_LAST_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "<? echo JS_LAST_NAME; ?>";
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
  if (document.add_entry.zone_id.options.length <= 1) {
    if (document.add_entry.state.value == "" || document.add_entry.state.length < <? echo ENTRY_STATE_MIN_LENGTH; ?> ) {
       error_message = error_message + "<? echo JS_STATE; ?>";
       error = 1;
    }
  } else {
    document.add_entry.state.value = '';
    if (document.add_entry.zone_id.selectedIndex == 0) {
       error_message = error_message + "<? echo JS_ZONE; ?>";
       error = 1;
    }
  }
<?
  }
?>

  if (document.add_entry.country.value == 0) {
    error_message = error_message + "<? echo JS_COUNTRY; ?>";
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
    <td width="100%" valign="top"><form name="add_entry" method="post" action="<? echo tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, '', 'NONSSL'); ?>" onSubmit="return check_form();"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
<?
  if ((($HTTP_GET_VARS['action'] == 'modify') && ($HTTP_GET_VARS['entry_id'])) || (($HTTP_POST_VARS['action'] == 'update') && ($HTTP_POST_VARS['entry_id']))) {
    echo '            <td width="100%" class="topBarTitle">&nbsp;' . TOP_BAR_TITLE_MODIFY_ENTRY . '&nbsp;</td>' . "\n";
  } else {
    echo '            <td width="100%" class="topBarTitle">&nbsp;' . TOP_BAR_TITLE_ADD_ENTRY . '&nbsp;</td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
<?
  if ((($HTTP_GET_VARS['action'] == 'modify') && ($HTTP_GET_VARS['entry_id'])) || (($HTTP_POST_VARS['action'] == 'update') && ($HTTP_POST_VARS['entry_id']))) {
    echo '            <td class="pageHeading">&nbsp;' . HEADING_TITLE_MODIFY_ENTRY . '&nbsp;</td>' . "\n";
    echo '            <td class="pageHeading"><br>' . tep_address_label($customer_id, $HTTP_GET_VARS['entry_id'], 1, '&nbsp;', '<br>') . '&nbsp;</td>' . "\n";
  } else {
    echo '            <td class="pageHeading">&nbsp;' . HEADING_TITLE_ADD_ENTRY . '&nbsp;</td>' . "\n";
  }
  echo '            <td align="right">&nbsp;' . tep_image(DIR_WS_IMAGES . 'table_background_address_book.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '&nbsp;</td>' . "\n";
  $rowspan = 5+ACCOUNT_GENDER+ACCOUNT_COMPANY*2;
?>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td width="100%"><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td align="right" rowspan="<? echo $rowspan; ?>" class="accountCategory"><? echo CATEGORY_PERSONAL; ?></td>
          </tr>
<?
   if (ACCOUNT_GENDER) {
?>
          <tr>
            <td align="right" class="fieldKey">&nbsp;<? echo ENTRY_GENDER; ?>&nbsp;</td>
            <td class="fieldValue">&nbsp;<?
    if (@$process == 1) {
      if (@$gender_error == '1') {
        echo '<input type="radio" name="gender" value="m">&nbsp;' . MALE . '&nbsp;<input type="radio" name="gender" value="f">&nbsp;' . FEMALE . '&nbsp;' . ENTRY_GENDER_ERROR;
      } else {
        if ($HTTP_POST_VARS['gender'] == 'm') {
          echo MALE . '<input type="hidden" name="gender" value="m">';
        } elseif ($HTTP_POST_VARS['gender'] == 'f') {
          echo FEMALE . '<input type="hidden" name="gender" value="f">';
        }
      }
    } else {
      echo '<input type="radio" name="gender" value="m"';
      if (@$gender == 'm') {
        echo ' CHECKED';
      }
      echo '>&nbsp;' . MALE . '&nbsp;<input type="radio" name="gender" value="f"';
      if (@$gender == 'f') {
        echo ' CHECKED';
      }
      echo '>&nbsp;' . FEMALE . '&nbsp;' . ENTRY_GENDER_TEXT;
    } ?></td>
          </tr>
<?
  }
   if (ACCOUNT_COMPANY) {
?>

          <tr>
            <td colspan="2" class="fieldKey">&nbsp;</td>
          </tr>
          <tr>
            <td align="right" class="fieldKey">&nbsp;<? echo ENTRY_COMPANY; ?>&nbsp;</td>
            <td class="fieldValue">&nbsp;<?
    if (@$process == 1) {
      if (@$company_error == '1') {
        echo '<input type="text" name="company" maxlength="32" value="' . $HTTP_POST_VARS['company'] . '">&nbsp;' . ENTRY_FIRST_NAME_ERROR;
      } else {
        echo $HTTP_POST_VARS['company'] . '<input type="hidden" name="company" value="' . $HTTP_POST_VARS['company'] . '">';
      }
    } else {
      echo '<input type="text" name="company" value="' . @$company . '" maxlength="32">&nbsp;' . ENTRY_COMPANY_TEXT;
    } ?></td>
      </tr>
<?
  }
?>
          <tr>
            <td colspan="2" class="fieldKey">&nbsp;</td>
          </tr>
          <tr>
            <td align="right" class="fieldKey">&nbsp;<? echo ENTRY_FIRST_NAME; ?>&nbsp;</td>
            <td class="fieldValue">&nbsp;<?
    if (@$process == 1) {
      if (@$firstname_error == '1') {
        echo '<input type="text" name="firstname" maxlength="32" value="' . $HTTP_POST_VARS['firstname'] . '">&nbsp;' . ENTRY_FIRST_NAME_ERROR;
      } else {
        echo $HTTP_POST_VARS['firstname'] . '<input type="hidden" name="firstname" value="' . $HTTP_POST_VARS['firstname'] . '">';
      }
    } else {
      echo '<input type="text" name="firstname" value="' . @$firstname . '" maxlength="32">&nbsp;' . ENTRY_FIRST_NAME_TEXT;
    } ?></td>
          </tr>
          <tr>
            <td align="right" class="fieldKey">&nbsp;<? echo ENTRY_LAST_NAME; ?>&nbsp;</td>
            <td class="fieldValue">&nbsp;<?
    if (@$process == 1) {
      if (@$lastname_error == '1') {
        echo '<input type="text" name="lastname" maxlength="32" value="' . $HTTP_POST_VARS['lastname'] . '">&nbsp;' . ENTRY_LAST_NAME_ERROR;
      } else {
        echo $HTTP_POST_VARS['lastname'] . '<input type="hidden" name="lastname" value="' . $HTTP_POST_VARS['lastname'] . '">';
      }
    } else {
      echo '<input type="text" name="lastname" value="' . @$lastname . '" maxlength="32">&nbsp;' . ENTRY_LAST_NAME_TEXT;
    }
    $rowspan = 5+ACCOUNT_STATE*2+ACCOUNT_SUBURB;
    ?></td>
          </tr>
          <tr>
            <td colspan="2" class="fieldKey">&nbsp;</td>
          </tr>
          <tr>
            <td align="right" rowspan="<? echo $rowspan; ?>" class="accountCategory"><? echo CATEGORY_ADDRESS; ?></td>
          </tr>
          <tr>
            <td align="right" class="fieldKey">&nbsp;<? echo ENTRY_STREET_ADDRESS; ?>&nbsp;</td>
            <td class="fieldValue">&nbsp;<?
    if (@$process == 1) {
      if (@$street_address_error == '1') {
        echo '<input type="text" name="street_address" maxlength="64" value="' . $HTTP_POST_VARS['street_address'] . '">&nbsp;' . ENTRY_STREET_ADDRESS_ERROR;
      } else {
        echo $HTTP_POST_VARS['street_address'] . '<input type="hidden" name="street_address" value="' . $HTTP_POST_VARS['street_address'] . '">';
      }
    } else {
      echo '<input type="text" name="street_address" value="' . @$street_address . '" maxlength="64">&nbsp;' . ENTRY_STREET_ADDRESS_TEXT;
    } ?></td>
          </tr>
<?
  if (ACCOUNT_SUBURB) {
?>
          <tr>
            <td align="right" class="fieldKey">&nbsp;<? echo ENTRY_SUBURB; ?>&nbsp;</td>
            <td class="fieldValue">&nbsp;<?
    if (@$process == 1) {
      $HTTP_POST_VARS['suburb'] . '<input type="hidden" name="suburb" value="' . $HTTP_POST_VARS['suburb'] . '">';
    } else {
      echo '<input type="text" name="suburb" value="' . @$suburb . '" maxlength="32">&nbsp;' . ENTRY_SUBURB_TEXT;
    } ?></td>
          </tr>
<?
  }
?>
          <tr>
            <td align="right" class="fieldKey">&nbsp;<? echo ENTRY_POST_CODE; ?>&nbsp;</td>
            <td class="fieldValue">&nbsp;<?
    if (@$process == 1) {
      if (@$postcode_error == '1') {
        echo '<input type="text" name="postcode" maxlength="8" value="' . $HTTP_POST_VARS['postcode'] . '">&nbsp;' . ENTRY_POST_CODE_ERROR;
      } else {
        echo $HTTP_POST_VARS['postcode'] . '<input type="hidden" name="postcode" value="' . $HTTP_POST_VARS['postcode'] . '">';
      }
    } else {
      echo '<input type="text" name="postcode" value="' . @$postcode . '" maxlength="8">&nbsp;' . ENTRY_POST_CODE_TEXT;
    } ?></td>
          </tr>
          <tr>
            <td align="right" class="fieldKey">&nbsp;<? echo ENTRY_CITY; ?>&nbsp;</td>
            <td class="fieldValue">&nbsp;<?
    if (@$process == 1) {
      if (@$city_error == '1') {
        echo '<input type="text" name="city" maxlength="32" value="' . $HTTP_POST_VARS['city'] . '">&nbsp;' . ENTRY_CITY_ERROR;
      } else {
        echo $HTTP_POST_VARS['city'] . '<input type="hidden" name="city" value="' . $HTTP_POST_VARS['city'] . '">';
      }
    } else {
      echo '<input type="text" name="city" value="' . @$city . '" maxlength="32">&nbsp;' . ENTRY_CITY_TEXT;
    } ?></td>
          </tr>
          <tr>
            <td align="right" class="fieldKey">&nbsp;<? echo ENTRY_COUNTRY; ?>&nbsp;</td>
            <td class="fieldValue">&nbsp;<?
    if (@$process == 1) {
      if (@$country_error == '1') {
        tep_get_country_list("country", STORE_COUNTRY, (ACCOUNT_STATE)?"onChange=\"update_zone(this.form);\"":"");
        echo '&nbsp;' . ENTRY_COUNTRY_ERROR;
      } else {
        $entry_country = tep_get_countries($HTTP_POST_VARS['country']);
        echo $entry_country['countries_name'] . '<input type="hidden" name="country" value="' . $HTTP_POST_VARS['country'] . '">';
      }
    } else {
      if ($country == "") $country = STORE_COUNTRY;
      tep_get_country_list("country", $country, (ACCOUNT_STATE)?"onChange=\"update_zone(this.form);\"":"");
      echo '&nbsp;' . ENTRY_COUNTRY_TEXT;
    } ?></td>
          </tr>
<?
   if (ACCOUNT_STATE) {
?>
          <tr>
            <td align="right" class="fieldKey">&nbsp;<? echo ENTRY_STATE; ?>&nbsp;</td>
            <td class="fieldValue">&nbsp;<?
    if (@$process == 1) {
      echo tep_get_zone_name($country, $zone_id, $state) . '<input type="hidden" name="zone_id" value="' . $HTTP_POST_VARS['zone_id'] . '">';
    } else {
      tep_get_zone_list("zone_id", $country, $zone_id, "onChange=\"resetStateText(this.form)\";");
      echo '&nbsp;' . ENTRY_STATE_TEXT;
    } ?></td>
          </tr>
          <tr>
            <td align="right" class="fieldKey">&nbsp;</td>
            <td class="fieldValue">&nbsp;<input type="text" name="state" onChange="resetZoneSelected(this.form);" maxlength="32" value="<? echo $state; ?>">&nbsp;<? echo ENTRY_STATE_TEXT; ?></td>
          </tr>
<?
   }
?>
        </table></td>
      </tr>
      <tr>
        <td><br><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
<?
    if ((@$HTTP_GET_VARS['action'] == 'modify') && (@$HTTP_GET_VARS['entry_id'])) {
?>
        <td><table border="0" width="100%" cellspacing="2" cellpadding="0">
          <tr>
            <td class="main">&nbsp;&nbsp;<input type="hidden" name="action" value="update"><input type="hidden" name="entry_id" value="<? echo $HTTP_GET_VARS['entry_id']; ?>"><a href="<? echo tep_href_link(FILENAME_ADDRESS_BOOK, '', 'NONSSL'); ?>"><? echo tep_image_button('button_back.gif', IMAGE_BUTTON_BACK); ?></a></td>
            <td class="main" align="right"><a href="<? echo tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'action=remove&entry_id=' . $HTTP_GET_VARS['entry_id'], 'NONSSL'); ?>"><? echo tep_image_button('button_delete.gif', IMAGE_BUTTON_DELETE); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;<? echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
          </tr>
        </table></td>
<?
    } elseif ((@$HTTP_POST_VARS['action'] == 'update') && (@$HTTP_POST_VARS['entry_id'])) {
?>
        <td><table border="0" width="100%" cellspacing="2" cellpadding="0">
          <tr>
            <td class="main">&nbsp;&nbsp;<input type="hidden" name="action" value="update"><input type="hidden" name="entry_id" value="<? echo $HTTP_POST_VARS['entry_id']; ?>"><a href="<? echo tep_href_link(FILENAME_ADDRESS_BOOK); ?>"><? echo tep_image_button('button_back.gif', IMAGE_BUTTON_BACK); ?></a></td>
            <td class="main" align="right"><? echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
          </tr>
        </table></td>
<?
    } else {
?>
        <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">&nbsp;&nbsp;<a href="<? echo tep_href_link((($HTTP_GET_VARS['origin']) ? $HTTP_GET_VARS['origin'] : FILENAME_ADDRESS_BOOK), '', (($HTTP_GET_VARS['connection'] == 'SSL') ? 'SSL' : 'NONSSL')); ?>"><? echo tep_image_button('button_back.gif', IMAGE_BUTTON_BACK); ?></a></td>
            <td align="right" class="main"><br><input type="hidden" name="entry_id" value="<? echo $HTTP_GET_VARS['entry_id'] ?>"><input type="hidden" name="action" value="process"><input type="hidden" name="origin_connection" value="<? echo @$HTTP_GET_VARS['connection']; ?>"><? echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?>&nbsp;&nbsp;</td>
          </tr>
        </table></td>
<?
    }
?>
      </tr>
    </table><? if ($HTTP_GET_VARS['origin']) { echo '<input type="hidden" name="origin" value="' . $HTTP_GET_VARS['origin'] . '">'; } ?></form></td>
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
