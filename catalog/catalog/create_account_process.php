<?php
/*
  $Id: create_account_process.php,v 1.46 2001/06/20 14:36:05 kwiltner Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!@$HTTP_POST_VARS['action']) {
    header('Location: ' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'NONSSL'));
    tep_exit();
  }

  $error = 0; // reset error flag

  if (ACCOUNT_GENDER) {
    if (($HTTP_POST_VARS['gender'] == 'm') || ($HTTP_POST_VARS['gender'] == 'f')) {
      $entry_gender_error = false;
    } else {
      $error = 1;
      $entry_gender_error = true;
    }
  }

  if (strlen(trim($HTTP_POST_VARS['firstname'])) < ENTRY_FIRST_NAME_MIN_LENGTH) {
    $error = true;
    $entry_firstname_error = true;
  } else {
    $entry_firstname_error = false;
  }

  if (strlen(trim($HTTP_POST_VARS['lastname'])) < ENTRY_LAST_NAME_MIN_LENGTH) {
    $error = true;
    $entry_lastname_error = true;
  } else {
    $entry_lastname_error = false;
  }

  if (ACCOUNT_DOB) {
    if (checkdate(substr(tep_date_raw($HTTP_POST_VARS['dob']), 4, 2), substr(tep_date_raw($HTTP_POST_VARS['dob']), 6, 2), substr(tep_date_raw($HTTP_POST_VARS['dob']), 0, 4))) {
      $entry_date_of_birth_error = false;
    } else {
      $error = true;
      $entry_date_of_birth_error = true;
    }
  }

  if (strlen(trim($HTTP_POST_VARS['email_address'])) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
    $error = true;
    $entry_email_address_error = true;
  } else {
    $entry_email_address_error = false;
  }

  if (!(tep_validate_email(trim($HTTP_POST_VARS['email_address'])))) {
    $error = true;
    $entry_email_address_check_error = true;
  } else {
    $entry_email_address_check_error = false;
  }

  if (strlen(trim($HTTP_POST_VARS['street_address'])) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
    $error = true;
    $entry_street_address_error = true;
  } else {
    $entry_street_address_error = false;
  }

  if (strlen(trim($HTTP_POST_VARS['postcode'])) < ENTRY_POSTCODE_MIN_LENGTH) {
    $error = true;
    $entry_post_code_error = true;
  } else {
    $entry_post_code_error = false;
  }

  if (strlen(trim($HTTP_POST_VARS['city'])) < ENTRY_CITY_MIN_LENGTH) {
    $error = true;
    $entry_city_error = true;
  } else {
    $entry_city_error = false;
  }

  if (ACCOUNT_STATE) {
    $zone_id = $HTTP_POST_VARS['zone_id'];
    if ($zone_id > 0) {
      $state = '';
    } else {
      $state = trim($HTTP_POST_VARS['state']);
    }
  }

  if ($HTTP_POST_VARS['country'] == '0') {
    $error = true;
    $entry_country_error = true;
  } else {
    $entry_country_error = false;
  }

  if (strlen(trim($HTTP_POST_VARS['telephone'])) < ENTRY_TELEPHONE_MIN_LENGTH) {
    $error = true;
    $entry_telephone_error = true;
  } else {
    $entry_telephone_error = false;
  }

  $passlen = strlen(trim($HTTP_POST_VARS['password']));
  if ($passlen < ENTRY_PASSWORD_MIN_LENGTH) {
    $error = true;
    $entry_password_error = true;
  } else {
    $entry_password_error = false;
  }

  if (trim($HTTP_POST_VARS['password']) != trim($HTTP_POST_VARS['confirmation'])) {
    $error = true;
    $entry_password_error = true;
  }

  $check_email = tep_db_query("select customers_email_address from customers where customers_email_address = '" . $HTTP_POST_VARS['email_address'] . "' and customers_id <> '" . $customer_id . "'");
  if (tep_db_num_rows($check_email)) {
    $error = true;
    $entry_email_address_exists = true;
  } else {
    $entry_email_address_exists = false;
  }

  if ($error == 1) {
    $processed = true;

    require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT_PROCESS);

    $location = ' : <a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_1 . '</a> : ' . NAVBAR_TITLE_2;
?>
<html>
<head>
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<?php
  require('includes/form_check.js.php');
?>
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
    <td width="100%" valign="top"><form name="account_edit" method="post" <?php echo 'action="' . tep_href_link(FILENAME_CREATE_ACCOUNT_PROCESS, '', 'NONSSL') . '"'; ?> onSubmit="return check_form();"><input type="hidden" name="action" value="process"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td width="100%" class="topBarTitle">&nbsp;<?php echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<?php echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'table_background_account.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><br>
<?php
  require(DIR_WS_MODULES . 'account_details.php');
?>
        </td>
      </tr>
      <tr>
        <td><br><?php echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><br><?php echo tep_image_submit('button_continue.gif', IMAGE_CONTINUE); ?>&nbsp;&nbsp;</td>
      </tr>
    </table><?php if ($HTTP_POST_VARS['origin']) { echo '<input type="hidden" name="origin" value="' . $HTTP_POST_VARS['origin'] . '">'; } ?></form></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php
  require(DIR_WS_INCLUDES . 'column_right.php');
?>
<!-- right_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php
  require(DIR_WS_INCLUDES . 'footer.php');
?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php
  } else {
    require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT_PROCESS);
    $date_now = date('Ymd');
    $dob_ordered = substr($HTTP_POST_VARS['dob'], -4) . substr($HTTP_POST_VARS['dob'], 0, 2) . substr($HTTP_POST_VARS['dob'], 2, 2);
// Crypted passwords mods
    $crypted_password = crypt_password($HTTP_POST_VARS['password']);
    tep_db_query("insert into " . TABLE_CUSTOMERS . " values ('', '" . $HTTP_POST_VARS['gender'] . "', '" . $HTTP_POST_VARS['firstname'] . "', '" . $HTTP_POST_VARS['lastname'] . "', '" . tep_date_raw($HTTP_POST_VARS['dob']) . "', '" . $HTTP_POST_VARS['email_address'] . "', '" . $HTTP_POST_VARS['street_address'] . "', '" . $HTTP_POST_VARS['suburb'] . "', '" . $HTTP_POST_VARS['postcode'] . "', '" . $HTTP_POST_VARS['city'] . "', '" . $state . "', '" . $HTTP_POST_VARS['telephone'] . "', '" . $HTTP_POST_VARS['fax'] . "', '" . $crypted_password . "', '" . $HTTP_POST_VARS['country'] . "', '" . $zone_id . "', '" . $HTTP_POST_VARS['newsletter'] . "')");
    $insert_id = tep_db_insert_id();
    tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " values ('" . $insert_id . "', '', '0', '" . $date_now . "', '')");

    $customer_id = $insert_id;
    $customer_first_name = $HTTP_POST_VARS['firstname'];
    tep_session_register('customer_id');
    tep_session_register('customer_first_name');


// restore cart contents
    $cart->restore_contents();

    // build the message content
    $firstname = $HTTP_POST_VARS['firstname'];
    $lastname = $HTTP_POST_VARS['lastname'];
    $email_address = $HTTP_POST_VARS['email_address'];

    if (ACCOUNT_GENDER) {
       if ($HTTP_POST_VARS['gender'] == 'm') {
         $email_text = EMAIL_GREET_MR;
       } else {
         $email_text = EMAIL_GREET_MS;
       }
    } else {
      $email_text = EMAIL_GREET_NONE;
    }

    $email_text .= EMAIL_WELCOME . EMAIL_TEXT . EMAIL_CONTACT . EMAIL_WARNING;
    tep_mail($firstname, $lastname, $email_address, EMAIL_SUBJECT, $email_text, '', EMAIL_FROM, '');
    
    if ($HTTP_POST_VARS['origin']) {
      if (@$HTTP_POST_VARS['connection'] == 'SSL') {
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

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
