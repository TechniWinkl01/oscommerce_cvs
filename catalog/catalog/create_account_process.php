<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT_PROCESS; include(DIR_INCLUDES . 'include_once.php'); ?>
<?
  if (!@$HTTP_POST_VARS['action']) {
    header('Location: ' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'NONSSL'));
    tep_exit();
  }

  $error = 0; // reset error flag to false

 if (ACCOUNT_GENDER) {
  if (($HTTP_POST_VARS['gender'] == 'm') || ($HTTP_POST_VARS['gender'] == 'f')) {
    $gender_error = 0;
  } else {
    $gender_error = 1;
    $error = 1;
  }
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

 if (ACCOUNT_DOB) {
  if (checkdate(substr($HTTP_POST_VARS['dob'], 3, 2), substr($HTTP_POST_VARS['dob'], 0, 2),substr($HTTP_POST_VARS['dob'], -4))) {
    $dob_error = 0;
  } else {
    $dob_error = 1;
    $error = 1;
  }
 }

  if (strlen(trim($HTTP_POST_VARS['email_address'])) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
    $email_address_error = 1;
    $error = 1;
  } else {
    $email_address_error = 0;
  }

  if (ENTRY_EMAIL_ADDRESS_CHECK == 1) {
    if (!(tep_validate_email(trim($HTTP_POST_VARS['email_address'])))) {
      $email_address_check_error = 1;
      $error = 1;
    } else {
      $email_address_check_error = 0;
    }
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

  if(ACCOUNT_STATE) {
    $zone_id = $HTTP_POST_VARS['zone_id'];
    if ($zone_id > 0) $state = "";
    else $state = trim($HTTP_POST_VARS['state']);
  }

  if ($HTTP_POST_VARS['country'] == '0') {
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

  $check_email = tep_db_query('select customers_email_address from customers where customers_email_address = "' . $HTTP_POST_VARS['email_address'] . '"');
  if (@tep_db_num_rows($check_email)) {
    $email_exists = 1;
    $error = 1;
  } else {
    $email_exists = 0;
  }

  if ($error == 1) {
?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_1 . '</a> : ' . NAVBAR_TITLE_2; ?>
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
    <td width="100%" valign="top"><form name="create_account" method="post" action="<? echo tep_href_link(FILENAME_CREATE_ACCOUNT_PROCESS, '', 'NONSSL');?>"><input type="hidden" name="action" value="process"><table border="0" width="100%" cellspacing="0" cellpadding="0">
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
            <td align="right" valign="middle" colspan="2" rowspan="7" nowrap><font face="<? echo CATEGORY_FONT_FACE;?>" size="<? echo CATEGORY_FONT_SIZE;?>" color="<? echo CATEGORY_FONT_COLOR;?>"><? echo CATEGORY_PERSONAL;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;<? echo ENTRY_GENDER;?>&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE;?>" size="<? echo VALUE_FONT_SIZE;?>" color="<? echo VALUE_FONT_COLOR;?>">&nbsp;
<?
   if (ACCOUNT_GENDER) {
    if ($gender_error == 1) {
      echo '<input type="radio" name="gender" value="m">&nbsp;' . MALE . '&nbsp;<input type="radio" name="gender" value="m">&nbsp;' . FEMALE . '&nbsp;' . ENTRY_GENDER_ERROR;
    } else {
      if ($HTTP_POST_VARS['gender'] == 'm') {
        echo MALE . '<input type="hidden" name="gender" value="m">';
      } elseif ($HTTP_POST_VARS['gender'] == 'f') {
        echo FEMALE . '<input type="hidden" name="gender" value="f">';
      }
    }
   } else {
     echo '<input type="hidden" name="gender" value="m">';
   }
?></font></td>
          </tr>
          <tr>
            <td colspan="2"><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;<? echo ENTRY_FIRST_NAME;?>&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE;?>" size="<? echo VALUE_FONT_SIZE;?>" color="<? echo VALUE_FONT_COLOR;?>">&nbsp;<?
    if ($firstname_error == 1) {            
      echo '<input type="text" name="firstname" maxlength="32" value="' . $HTTP_POST_VARS['firstname'] . '">&nbsp;' . ENTRY_FIRST_NAME_ERROR;
    } else {
      echo $HTTP_POST_VARS['firstname'] . '<input type="hidden" name="firstname" value="' . $HTTP_POST_VARS['firstname'] . '">';
    } ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;<? echo ENTRY_LAST_NAME;?>&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE;?>" size="<? echo VALUE_FONT_SIZE;?>" color="<? echo VALUE_FONT_COLOR;?>">&nbsp;<?
    if ($lastname_error == 1) {
      echo '<input type="text" name="lastname" maxlength="32" value="' . $HTTP_POST_VARS['lastname'] . '">&nbsp;' . ENTRY_LAST_NAME_ERROR;
    } else {
      echo $HTTP_POST_VARS['lastname'] . '<input type="hidden" name="lastname" value="' . $HTTP_POST_VARS['lastname'] . '">';
    } ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;<? echo ENTRY_DATE_OF_BIRTH;?>&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE;?>" size="<? echo VALUE_FONT_SIZE;?>" color="<? echo VALUE_FONT_COLOR;?>">&nbsp;<?
   if (ACCOUNT_DOB) {
    if ($dob_error == 1) {
      echo '<input type="text" name="dob" value="' . $HTTP_POST_VARS['dob'] . '"maxlength="10">&nbsp;' . ENTRY_DATE_OF_BIRTH_ERROR;
    } else {
      echo $HTTP_POST_VARS['dob'] . '<input type="hidden" name="dob" value="' . $HTTP_POST_VARS['dob'] . '">';
    }
   } else {
     echo '<input type="hidden" name="dob" value="00000000">';
   }
?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;<? echo ENTRY_EMAIL_ADDRESS;?>&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE;?>" size="<? echo VALUE_FONT_SIZE;?>" color="<? echo VALUE_FONT_COLOR;?>">&nbsp;<?
    if ($email_address_error == 1) {
      echo '<input type="text" name="email_address" maxlength="96" value="' . $HTTP_POST_VARS['email_address'] . '">&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR;
    }  elseif ($email_address_check_error == 1) {
      echo '<input type="text" name="email_address" maxlength="96" value="' . $HTTP_POST_VARS['email_address'] . '">&nbsp;' . ENTRY_EMAIL_ADDRESS_CHECK_ERROR;
    }  elseif ($email_exists == 1) {
      echo '<input type="text" name="email_address" maxlength="96" value="' . $HTTP_POST_VARS['email_address'] . '">&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR_EXISTS;
    } else {
      echo $HTTP_POST_VARS['email_address'] . '<input type="hidden" name="email_address" value="' . $HTTP_POST_VARS['email_address'] . '">';
    } ?></font></td>
          </tr>
          <tr>
            <td colspan="2"><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="7"><font face="<? echo CATEGORY_FONT_FACE;?>" size="<? echo CATEGORY_FONT_SIZE;?>" color="<? echo CATEGORY_FONT_COLOR;?>"><? echo CATEGORY_ADDRESS;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;<? echo ENTRY_STREET_ADDRESS;?>&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE;?>" size="<? echo VALUE_FONT_SIZE;?>" color="<? echo VALUE_FONT_COLOR;?>">&nbsp;<?
    if ($street_address_error == 1) {
      echo '<input type="text" name="street_address" maxlength="64" value="' . $HTTP_POST_VARS['street_address'] . '">&nbsp;' . ENTRY_STREET_ADDRESS_ERROR;
    } else {
      echo $HTTP_POST_VARS['street_address'] . '<input type="hidden" name="street_address" value="' . $HTTP_POST_VARS['street_address'] . '">';
    } ?></font></td>
          </tr>
<?
   if (ACCOUNT_SUBURB) {
?>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;<? echo ENTRY_SUBURB;?>&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE;?>" size="<? echo VALUE_FONT_SIZE;?>" color="<? echo VALUE_FONT_COLOR;?>">&nbsp;<?
    echo $HTTP_POST_VARS['suburb'] . '<input type="hidden" name="suburb" value="' . $HTTP_POST_VARS['suburb'] . '">&nbsp;' . ENTRY_SUBURB_ERROR; ?></font></td>
          </tr>
<?
   } else {
     echo '<input type="hidden" name="suburb" value="">';
   }
?>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;<? echo ENTRY_POST_CODE;?>&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE;?>" size="<? echo VALUE_FONT_SIZE;?>" color="<? echo VALUE_FONT_COLOR;?>">&nbsp;<?
    if ($postcode_error == 1) {
      echo '<input type="text" name="postcode" maxlength="8" value="' . $HTTP_POST_VARS['postcode'] . '">&nbsp;' . ENTRY_POST_CODE_ERROR;
    } else {
      echo $HTTP_POST_VARS['postcode'] . '<input type="hidden" name="postcode" value="' . $HTTP_POST_VARS['postcode'] . '">';
    } ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;<? echo ENTRY_CITY;?>&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE;?>" size="<? echo VALUE_FONT_SIZE;?>" color="<? echo VALUE_FONT_COLOR;?>">&nbsp;<?
    if ($city_error == 1) {
      echo '<input type="text" name="city" maxlength="32" value="' . $HTTP_POST_VARS['city'] . '">&nbsp;' . ENTRY_CITY_ERROR;
    } else {
      echo $HTTP_POST_VARS['city'] . '<input type="hidden" name="city" value ="' . $HTTP_POST_VARS['city'] . '">';
    } ?></font></td>
          </tr>
<?
   if (ACCOUNT_STATE) {
?>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;<? echo ENTRY_STATE;?>&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE;?>" size="<? echo VALUE_FONT_SIZE;?>" color="<? echo VALUE_FONT_COLOR;?>">&nbsp;<?
    echo $state . '<input type="hidden" name="state" value="' . $state . '">&nbsp;' . ENTRY_STATE_ERROR; ?></font></td>
          </tr>
<?
   } else {
     echo '<input type="hidden" name="state" value="">';
   }
?>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;<? echo ENTRY_COUNTRY;?>&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE;?>" size="<? echo VALUE_FONT_SIZE;?>" color="<? echo VALUE_FONT_COLOR;?>">&nbsp;<?
    if ($country_error == 1) {
      echo '<select name="country"><option value="0">' . PLEASE_SELECT . '</option>';
      $countries = tep_get_countries();
      for ($i=0; $i < sizeof($countries); $i++) {
        echo '<option value="' . $countries[$i]['countries_id'] . '"';
        if ($countries[$i]['countries_id'] == STORE_COUNTRY) echo ' SELECTED';
        echo '>' . $countries[$i]['countries_name'] . '</option>';
      }
      echo '</select>&nbsp;' . ENTRY_COUNTRY_ERROR;
    } else {
      $country = tep_get_countries($HTTP_POST_VARS['country']);
      echo $country['countries_name'] . '<input type="hidden" name="country" value="' . $HTTP_POST_VARS['country'] . '">';
    } ?></font></td>
          </tr>
          <tr>
            <td colspan="2"><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="3"><font face="<? echo CATEGORY_FONT_FACE;?>" size="<? echo CATEGORY_FONT_SIZE;?>" color="<? echo CATEGORY_FONT_COLOR;?>"><? echo CATEGORY_CONTACT;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;<? echo ENTRY_TELEPHONE_NUMBER;?>&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE;?>" size="<? echo VALUE_FONT_SIZE;?>" color="<? echo VALUE_FONT_COLOR;?>">&nbsp;<?
    if ($telephone_error == 1) {
      echo '<input type="text" name="telephone" maxlength="32" value="' . $HTTP_POST_VARS['telephone'] . '">&nbsp;' . ENTRY_TELEPHONE_NUMBER_ERROR;
    } else {
      echo $HTTP_POST_VARS['telephone'] . '<input type="hidden" name="telephone" value="' . $HTTP_POST_VARS['telephone'] . '">';
    } ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;<? echo ENTRY_FAX_NUMBER;?>&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE;?>" size="<? echo VALUE_FONT_SIZE;?>" color="<? echo VALUE_FONT_COLOR;?>">&nbsp;<?
    echo $HTTP_POST_VARS['fax'] . '<input type="hidden" name="fax" value="' . $HTTP_POST_VARS['fax'] . '">&nbsp;' . ENTRY_FAX_NUMBER_ERROR; ?></font></td>
          </tr>
          <tr>
            <td colspan="2"><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="3"><font face="<? echo CATEGORY_FONT_FACE;?>" size="<? echo CATEGORY_FONT_SIZE;?>" color="<? echo CATEGORY_FONT_COLOR;?>"><? echo CATEGORY_PASSWORD;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<? echo ENTRY_FONT_FACE;?>" size="<? echo ENTRY_FONT_SIZE;?>" color="<? echo ENTRY_FONT_COLOR;?>">&nbsp;<? echo ENTRY_PASSWORD;?>&nbsp;</font></td>
            <td nowrap><font face="<? echo VALUE_FONT_FACE;?>" size="<? echo VALUE_FONT_SIZE;?>" color="<? echo VALUE_FONT_COLOR;?>">&nbsp;<?
    if ($password_error == 1) {
      echo '<input type="password" name="password" maxlength="12" value="' . $HTTP_POST_VARS['password'] . '">&nbsp;' . ENTRY_PASSWORD_ERROR;
    } else {
      echo PASSWORD_HIDDEN . '<input type="hidden" name="password" value="' . $HTTP_POST_VARS['password'] . '">';
    } ?></font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><? echo tep_black_line();?></td>
      </tr>
      <tr>
        <td align="right"><br><font face="<? echo TEXT_FONT_FACE;?>" size="<? echo TEXT_FONT_SIZE;?>" color="<? echo TEXT_FONT_COLOR;?>"><? echo tep_image_submit(DIR_IMAGES . 'button_done.gif', '53', '24', '0', IMAGE_DONE);?>&nbsp;&nbsp;</font></td>
      </tr>
    </table><? if ($HTTP_POST_VARS['origin']) { echo '<input type="hidden" name="origin" value="' . $HTTP_POST_VARS['origin'] . '">'; } ?></form></td>
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
<?
  } else {
    $date_now = date('Ymd');
    $dob_ordered = substr($HTTP_POST_VARS['dob'], -4) . substr($HTTP_POST_VARS['dob'], 3, 2) . substr($HTTP_POST_VARS['dob'], 0, 2);
// Crypted passwords mods
    $crypted_password = crypt_password($HTTP_POST_VARS['password']);
    tep_db_query("insert into customers values ('', '" . $HTTP_POST_VARS['gender'] . "', '" . $HTTP_POST_VARS['firstname'] . "', '" . $HTTP_POST_VARS['lastname'] . "', '" . $dob_ordered . "', '" . $HTTP_POST_VARS['email_address'] . "', '" . $HTTP_POST_VARS['street_address'] . "', '" . $HTTP_POST_VARS['suburb'] . "', '" . $HTTP_POST_VARS['postcode'] . "', '" . $HTTP_POST_VARS['city'] . "', '" . $state . "', '" . $HTTP_POST_VARS['telephone'] . "', '" . $HTTP_POST_VARS['fax'] . "', '" . $crypted_password . "', '" . $HTTP_POST_VARS['country'] . "', '" . $zone_id . "')");
    $insert_id = tep_db_insert_id();
    tep_db_query("insert into customers_info values ('" . $insert_id . "', '', '0', '" . $date_now . "', '')");

    $customer_id = $insert_id;
    tep_session_register('customer_id');

    if (tep_session_is_registered('nonsess_cart')) { //transfer session cart to account cart
      $nonsess_cart_contents = explode('|', $nonsess_cart);
      for ($i=0;$i<sizeof($nonsess_cart_contents);$i++) {
        $product_info = explode(':', $nonsess_cart_contents[$i]);
        if (($product_info[0] != 0) && ($product_info[1] != 0)) {
          $product_in_cart = 1;
          $check_cart = tep_db_query("select customers_basket_quantity from customers_basket where customers_id = '" . $customer_id . "' and products_id = '" . $product_info[0] . "'");
          if (tep_db_num_rows($check_cart)) {
            $check_cart_values = tep_db_fetch_array($check_cart);
            tep_db_query("update customers_basket set customers_basket_quantity = customers_basket_quantity+" . $product_info[1] . " where customers_id = '" . $customer_id . "' and products_id = '" . $product_info[0] . "'");
          } else {
            tep_db_query("insert into customers_basket values ('', '" . $customer_id . "', '" . $product_info[0] . "', '" . $product_info[1] . "', '" . $date_now . "')");


          }
        }
      }
      tep_session_unregister('nonsess_cart');
    }

    if (ACCOUNT_GENDER) {
       if ($HTTP_POST_VARS['gender'] == 'm') {
         $gender = MALE_ADDRESS;
       } else {
         $gender = FEMALE_ADDRESS;
       }
    } else {
      $gender = $firstname;
    }

    $message = sprintf(EMAIL_WELCOME, $gender, $HTTP_POST_VARS['lastname']);
    mail($HTTP_POST_VARS['email_address'], EMAIL_WELCOME_SUBJECT, $message, "From: " . EMAIL_FROM);
    
    if ($HTTP_POST_VARS['origin']) {
      if (@$HTTP_POST_VARS['connection'] == 'secure') {
        $connection_type = 'SSL';
      } else {
        $connection_type = 'NONSSL';
      }
      header('Location: ' . tep_href_link($HTTP_POST_VARS['origin'], '', $connection_type));
      tep_exit();
    } else {
      header('Location: ' . tep_href_link(FILENAME_CREATE_ACCOUNT_SUCCESS, '', 'NONSSL'));
      tep_exit();
    }
  }
?>
<? $include_file = DIR_INCLUDES . 'application_bottom.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
