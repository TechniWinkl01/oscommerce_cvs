<?php
/*
  $Id: account_edit_process.php,v 1.60 2002/05/23 01:02:16 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_ACCOUNT_EDIT));
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  if (!$HTTP_POST_VARS['action']) {
    tep_redirect(tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'));
  }

  $error = false; // reset error flag

  if (ACCOUNT_GENDER == 'true') {
    if (($HTTP_POST_VARS['gender'] == 'm') || ($HTTP_POST_VARS['gender'] == 'f')) {
      $entry_gender_error = false;
    } else {
      $error = true;
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

  if (ACCOUNT_DOB == 'true') {
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

  if (ACCOUNT_STATE == 'true') {
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

  $check_email = tep_db_query("select customers_email_address from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input(tep_db_prepare_input($HTTP_POST_VARS['email_address'])) . "' and customers_id <> '" . $customer_id . "'");
  if (tep_db_num_rows($check_email)) {
    $error = true;
    $entry_email_address_exists = true;
  } else {
    $entry_email_address_exists = false;
  }

  if ($error) {
    $processed = true;

    include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_EDIT_PROCESS);

    $location = ' &raquo; <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '" class="headerNavigation">' . NAVBAR_TITLE_1 . '</a> &raquo; <a href="' . tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL') . '" class="headerNavigation">' . NAVBAR_TITLE_2 . '</a>';
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<?php require('includes/form_check.js.php'); ?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><?php echo tep_draw_form('account_edit', tep_href_link(FILENAME_ACCOUNT_EDIT_PROCESS, '', 'SSL'), 'post', 'onSubmit="return check_form();"') . tep_draw_hidden_field('action', 'process'); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_account.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><?php include(DIR_WS_MODULES . 'account_details.php'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
            <td class="main" align="right"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
          </tr>
        </table></td>
      </tr>
    </table></form></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php include(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php include(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php
  } else {
    $customers_firstname = tep_db_prepare_input($HTTP_POST_VARS['firstname']);
    $customers_lastname = tep_db_prepare_input($HTTP_POST_VARS['lastname']);
    $customers_email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
    $customers_gender = tep_db_prepare_input($HTTP_POST_VARS['gender']);
    $customers_dob = tep_db_prepare_input($HTTP_POST_VARS['dob']);
    $customers_telephone = tep_db_prepare_input($HTTP_POST_VARS['telephone']);
    $customers_fax = tep_db_prepare_input($HTTP_POST_VARS['fax']);
    $customers_newsletter = tep_db_prepare_input($HTTP_POST_VARS['newsletter']);
    $customers_password = tep_db_prepare_input($HTTP_POST_VARS['password']);
    $entry_street_address = tep_db_prepare_input($HTTP_POST_VARS['street_address']);
    $entry_gender = tep_db_prepare_input($HTTP_POST_VARS['gender']);
    $entry_firstname = tep_db_prepare_input($HTTP_POST_VARS['firstname']);
    $entry_lastname = tep_db_prepare_input($HTTP_POST_VARS['lastname']);
    $entry_company = tep_db_prepare_input($HTTP_POST_VARS['company']);
    $entry_suburb = tep_db_prepare_input($HTTP_POST_VARS['suburb']);
    $entry_postcode = tep_db_prepare_input($HTTP_POST_VARS['postcode']);
    $entry_city = tep_db_prepare_input($HTTP_POST_VARS['city']);
    $entry_zone_id = tep_db_prepare_input($HTTP_POST_VARS['zone_id']);
    $entry_state = tep_db_prepare_input($HTTP_POST_VARS['state']);
    $entry_country_id = tep_db_prepare_input($HTTP_POST_VARS['country']);

    $sql_data_array = array('customers_firstname' => $customers_firstname,
                            'customers_lastname' => $customers_lastname,
                            'customers_email_address' => $customers_email_address,
                            'customers_telephone' => $customers_telephone,
                            'customers_fax' => $customers_fax,
                            'customers_newsletter' => $customers_newsletter,
                            'customers_password' => crypt_password($customers_password));

    if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $customers_gender;
    if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($customers_dob);

    tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . $customer_id . "'");

    $sql_data_array = array('entry_street_address' => $entry_street_address,
                            'entry_firstname' => $entry_firstname,
                            'entry_lastname' => $entry_lastname,
                            'entry_postcode' => $entry_postcode,
                            'entry_city' => $entry_city,
                            'entry_country_id' => $entry_country_id);

    if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $entry_gender;
    if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $entry_company;
    if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $entry_suburb;
    if (ACCOUNT_STATE == 'true') {
      if ($entry_zone_id > 0) {
        $sql_data_array['entry_zone_id'] = $entry_zone_id;
        $sql_data_array['entry_state'] = '';
      } else {
        $sql_data_array['entry_zone_id'] = '0';
        $sql_data_array['entry_state'] = $entry_state;
      }
    }

    tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "customers_id = '" . $customer_id . "' and address_book_id = '" . $customer_default_address_id . "'");

    tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . $customer_id . "'");

    $customer_first_name = $HTTP_POST_VARS['firstname'];
    $customer_country_id = $HTTP_POST_VARS['country'];
    if ($HTTP_POST_VARS['zone_id'] > 0) {
      $customer_zone_id = $HTTP_POST_VARS['zone_id'];
    } else {
      $customer_zone_id = '0';
    }

    tep_redirect(tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  }

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>