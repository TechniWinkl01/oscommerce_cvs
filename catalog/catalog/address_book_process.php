<? include('includes/application_top.php'); ?>
<?
  if ((@$HTTP_GET_VARS['action'] == 'remove') && (@$HTTP_GET_VARS['entry_id'])) {
    tep_db_query("delete from address_book where address_book_id = '" . $HTTP_GET_VARS['entry_id'] . "'");
    tep_db_query("delete from address_book_to_customers where address_book_id = '" . $HTTP_GET_VARS['entry_id'] . "'");
    header('Location: ' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'NONSSL'));
    tep_exit();
  }

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

  if ((@$process == 1) && (@$error == 0) && (@$HTTP_POST_VARS['action'] == 'update')) {
    $update_query = 'update address_book set ';
    if (ACCOUNT_GENDER) {
       $update_query = $update_query . "entry_gender = '" . $HTTP_POST_VARS['gender'] . "', ";
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
    $update_query = $update_query . "entry_country_id = '" . $HTTP_POST_VARS['country'] . "' where address_book_id = '" . $HTTP_POST_VARS['entry_id']. "'";
    tep_db_query($update_query);
    header('Location: ' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'NONSSL'));
    tep_exit();
  } elseif ((@$process == 1) && (@$error == 0)) {
    $gender = "";
    $suburb = "";
    $state = "";
    $zone_id = 0;
    if (ACCOUNT_GENDER) {
       $gender = $HTTP_POST_VARS['gender'];
    }
    if (ACCOUNT_SUBURB) {
       $suburb = $HTTP_POST_VARS['suburb'];
    }
    if (ACCOUNT_STATE) {
       $state = $HTTP_POST_VARS['state'];
       $zone_id = $HTTP_POST_VARS['zone_id'];
       if ($zone_id != 0) $state = '';
    }
    $update_query = "insert into address_book values ('', '" . $gender . "', '" . $HTTP_POST_VARS['firstname'] . "', '" . $HTTP_POST_VARS['lastname'] . "', '" . $HTTP_POST_VARS['street_address'] . "', '" . $suburb . "', '" . $HTTP_POST_VARS['postcode'] . "', '" . $HTTP_POST_VARS['city'] . "', '" . $state . "', '" . $HTTP_POST_VARS['country'] . "', '" . $zone_id . "')";
    tep_db_query($update_query);
    $insert_id = tep_db_insert_id();
    tep_db_query("insert into address_book_to_customers values ('', '" . $insert_id . "', '" . $customer_id . "')");

    if (@$HTTP_POST_VARS['origin']) {
      if (@$HTTP_POST_VARS['origin_connection'] == 'SSL') {
        $connection_type = 'SSL';
      } else {
        $connection_type = 'NONSSL';
      }
      header('Location: ' . tep_href_link($HTTP_POST_VARS['origin'], '', $connection_type));
      tep_exit();
    } else {
      header('Location: ' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'NONSSL'));
      tep_exit();
    }
  } else {
    if ((@$HTTP_GET_VARS['action'] == 'modify') && (@$HTTP_GET_VARS['entry_id'])) {
      $entry_query = 'select ';
      $state = '';
      $zone_id = 0;
      if (ACCOUNT_GENDER) {
         $entry_query = $entry_query . "entry_gender, ";
      }
      $entry_query = $entry_query . "entry_firstname, entry_lastname, entry_street_address, ";
      if (ACCOUNT_SUBURB) {
         $entry_query = $entry_query . "entry_suburb, ";
      }
      $entry_query = $entry_query . "entry_postcode, entry_city, ";
      if (ACCOUNT_STATE) {
         $entry_query = $entry_query . "entry_state, entry_zone_id, ";
      }
      $entry_query = $entry_query . "entry_country_id from address_book, address_book_to_customers where address_book_to_customers.customers_id = '" . $customer_id . "' and address_book_to_customers.address_book_id = address_book.address_book_id and address_book.address_book_id = '" . $HTTP_GET_VARS['entry_id'] . "'";
      $entry = tep_db_query($entry_query);
      $entry_values = tep_db_fetch_array($entry);
      if (ACCOUNT_GENDER) {
         $gender = $entry_values['entry_gender'];
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
    echo '            <td width="100%" class="topBarTitle" nowrap>&nbsp;' . TOP_BAR_TITLE_MODIFY_ENTRY . '&nbsp;</td>' . "\n";
  } else {
    echo '            <td width="100%" class="topBarTitle" nowrap>&nbsp;' . TOP_BAR_TITLE_ADD_ENTRY . '&nbsp;</td>' . "\n";
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
    echo '            <td class="pageHeading" nowrap>&nbsp;' . HEADING_TITLE_MODIFY_ENTRY . '&nbsp;</td>' . "\n";
    echo '            <td class="pageHeading" nowrap><br>' . tep_address_label($customer_id, $HTTP_GET_VARS['entry_id'], 1, '&nbsp;', '<br>') . '&nbsp;</td>' . "\n";
  } else {
    echo '            <td class="pageHeading" nowrap>&nbsp;' . HEADING_TITLE_ADD_ENTRY . '&nbsp;</td>' . "\n";
  }
  echo '            <td align="right" nowrap>&nbsp;' . tep_image(DIR_WS_IMAGES . 'table_background_address_book.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '&nbsp;</td>' . "\n";
  $rowspan = 5+ACCOUNT_GENDER;
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
            <td align="right" valign="middle" colspan="2" rowspan="<? echo $rowspan; ?>" class="accountCategory" nowrap><? echo CATEGORY_PERSONAL; ?></td>
          </tr>
<?
   if (ACCOUNT_GENDER) {
?>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_GENDER; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<?
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
    } ?></font></td>
          </tr>
<?
  }
?>
          <tr>
            <td colspan="2"><font face="Verdana, Arial" size="2">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_FIRST_NAME; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<?
    if (@$process == 1) {
      if (@$firstname_error == '1') {
        echo '<input type="text" name="firstname" maxlength="32" value="' . $HTTP_POST_VARS['firstname'] . '">&nbsp;' . ENTRY_FIRST_NAME_ERROR;
      } else {
        echo $HTTP_POST_VARS['firstname'] . '<input type="hidden" name="firstname" value="' . $HTTP_POST_VARS['firstname'] . '">';
      }
    } else {
      echo '<input type="text" name="firstname" value="' . @$firstname . '" maxlength="32">&nbsp;' . ENTRY_FIRST_NAME_TEXT;
    } ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_LAST_NAME; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<?
    if (@$process == 1) {
      if (@$lastname_error == '1') {
        echo '<input type="text" name="lastname" maxlength="32" value="' . $HTTP_POST_VARS['lastname'] . '">&nbsp;' . ENTRY_LAST_NAME_ERROR;
      } else {
        echo $HTTP_POST_VARS['lastname'] . '<input type="hidden" name="lastname" value="' . $HTTP_POST_VARS['lastname'] . '">';
      }
    } else {
      echo '<input type="text" name="lastname" value="' . @$lastname . '" maxlength="32">&nbsp;' . ENTRY_LAST_NAME_TEXT;
    }
    $rowspan = 6+ACCOUNT_STATE+ACCOUNT_SUBURB; 
    ?></font></td>
          </tr>
          <tr>
            <td colspan="2"><font face="Verdana, Arial" size="2">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="<? echo $rowspan; ?>" class="accountCategory" nowrap><? echo CATEGORY_ADDRESS; ?></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_STREET_ADDRESS; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<?
    if (@$process == 1) {
      if (@$street_address_error == '1') {
        echo '<input type="text" name="street_address" maxlength="64" value="' . $HTTP_POST_VARS['street_address'] . '">&nbsp;' . ENTRY_STREET_ADDRESS_ERROR;
      } else {
        echo $HTTP_POST_VARS['street_address'] . '<input type="hidden" name="street_address" value="' . $HTTP_POST_VARS['street_address'] . '">';
      }
    } else {
      echo '<input type="text" name="street_address" value="' . @$street_address . '" maxlength="64">&nbsp;' . ENTRY_STREET_ADDRESS_TEXT;
    } ?></font></td>
          </tr>
<?
  if (ACCOUNT_SUBURB) {
?>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_SUBURB; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<?
    if (@$process == 1) {
      $HTTP_POST_VARS['suburb'] . '<input type="hidden" name="suburb" value="' . $HTTP_POST_VARS['suburb'] . '">';
    } else {
      echo '<input type="text" name="suburb" value="' . @$suburb . '" maxlength="32">&nbsp;' . ENTRY_SUBURB_TEXT;
    } ?></font></td>
          </tr>
<?
  }
?>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_POST_CODE; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<?
    if (@$process == 1) {
      if (@$postcode_error == '1') {
        echo '<input type="text" name="postcode" maxlength="8" value="' . $HTTP_POST_VARS['postcode'] . '">&nbsp;' . ENTRY_POST_CODE_ERROR;
      } else {
        echo $HTTP_POST_VARS['postcode'] . '<input type="hidden" name="postcode" value="' . $HTTP_POST_VARS['postcode'] . '">';
      }
    } else {
      echo '<input type="text" name="postcode" value="' . @$postcode . '" maxlength="8">&nbsp;' . ENTRY_POST_CODE_TEXT;
    } ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_CITY; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<?
    if (@$process == 1) {
      if (@$city_error == '1') {
        echo '<input type="text" name="city" maxlength="32" value="' . $HTTP_POST_VARS['city'] . '">&nbsp;' . ENTRY_CITY_ERROR;
      } else {
        echo $HTTP_POST_VARS['city'] . '<input type="hidden" name="city" value="' . $HTTP_POST_VARS['city'] . '">';
      }
    } else {
      echo '<input type="text" name="city" value="' . @$city . '" maxlength="32">&nbsp;' . ENTRY_CITY_TEXT;
    } ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_COUNTRY; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<?
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
    } ?></font></td>
          </tr>
<?
   if (ACCOUNT_STATE) {
?>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;<? echo ENTRY_STATE; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>&nbsp;<?
    if (@$process == 1) {
      echo tep_get_zone_name($country, $zone_id, $state) . '<input type="hidden" name="zone_id" value="' . $HTTP_POST_VARS['zone_id'] . '">';
    } else {
      tep_get_zone_list("zone_id", $country, $zone_id, "onChange=\"resetStateText(this.form)\";");
      echo '&nbsp;' . ENTRY_STATE_TEXT;
    } ?></font></td>
          </tr>
          <tr>
            <td></td>
            <td nowrap><?php echo FONT_STYLE_FIELD_VALUE; ?>
            &nbsp;<input type="text" name="state" onChange="resetZoneSelected(this.form);" maxlength="32" value="<? echo $state; ?>">&nbsp;<? echo ENTRY_STATE_TEXT; ?></font></td>
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
      echo '        <td align="right" class="main" nowrap><br><input type="hidden" name="action" value="update"><input type="hidden" name="entry_id" value="' . $HTTP_GET_VARS['entry_id'] . '">' . tep_image_submit(DIR_WS_IMAGES . 'button_update.gif', IMAGE_UPDATE) . '&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'action=remove&entry_id=' . $HTTP_GET_VARS['entry_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . '</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>&nbsp;&nbsp;</td>' . "\n";
    } elseif ((@$HTTP_POST_VARS['action'] == 'update') && (@$HTTP_POST_VARS['entry_id'])) {
      echo '        <td align="right" class="main" nowrap><br><input type="hidden" name="action" value="update"><input type="hidden" name="entry_id" value="' . $HTTP_POST_VARS['entry_id'] . '">' . tep_image_submit(DIR_WS_IMAGES . 'button_update.gif', IMAGE_UPDATE) . '&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>&nbsp;&nbsp;</td>' . "\n";
    } else {
      echo '        <td align="right" class="main" nowrap><br><input type="hidden" name="action" value="process"><input type="hidden" name="origin_connection" value="' . @$HTTP_GET_VARS['connection'] . '">' . tep_image_submit(DIR_WS_IMAGES . 'button_insert.gif', IMAGE_INSERT) . '&nbsp;&nbsp;&nbsp;&nbsp;';
      if (@$HTTP_GET_VARS['origin']) {
        if (@$HTTP_GET_VARS['connection'] == 'SSL') {
          $connection_type = 'SSL';
        } else {
          $connection_type = 'NONSSL';
        }
        echo '<a href="' . tep_href_link($HTTP_GET_VARS['origin'], '', $connection_type) . '">';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'NONSSL') . '">';
      }
      echo tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>&nbsp;&nbsp;</td>' . "\n";
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
