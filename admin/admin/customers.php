<?php
/*
  $Id: customers.php,v 1.66 2002/03/17 00:46:10 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ($HTTP_GET_VARS['action']) {
    switch ($HTTP_GET_VARS['action']) {
      case 'update':
        $customers_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
        $customers_firstname = tep_db_prepare_input($HTTP_POST_VARS['customers_firstname']);
        $customers_lastname = tep_db_prepare_input($HTTP_POST_VARS['customers_lastname']);
        $customers_email_address = tep_db_prepare_input($HTTP_POST_VARS['customers_email_address']);
        $customers_telephone = tep_db_prepare_input($HTTP_POST_VARS['customers_telephone']);
        $customers_fax = tep_db_prepare_input($HTTP_POST_VARS['customers_fax']);
        $customers_newsletter = tep_db_prepare_input($HTTP_POST_VARS['customers_newsletter']);

        $customers_gender = tep_db_prepare_input($HTTP_POST_VARS['customers_gender']);
        $customers_dob = tep_db_prepare_input($HTTP_POST_VARS['customers_dob']);

        $sql_data_array = array('customers_firstname' => $customers_firstname,
                                'customers_lastname' => $customers_lastname,
                                'customers_email_address' => $customers_email_address,
                                'customers_telephone' => $customers_telephone,
                                'customers_fax' => $customers_fax,
                                'customers_newsletter' => $customers_newsletter);

        if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $customers_gender;
        if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($customers_dob);

        tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . tep_db_input($customers_id) . "'");

        tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . tep_db_input($customers_id) . "'");

        $default_address_id = tep_db_prepare_input($HTTP_POST_VARS['default_address_id']);
        $entry_street_address = tep_db_prepare_input($HTTP_POST_VARS['entry_street_address']);
        $entry_suburb = tep_db_prepare_input($HTTP_POST_VARS['entry_suburb']);
        $entry_postcode = tep_db_prepare_input($HTTP_POST_VARS['entry_postcode']);
        $entry_city = tep_db_prepare_input($HTTP_POST_VARS['entry_city']);
        $entry_country_id = tep_db_prepare_input($HTTP_POST_VARS['entry_country_id']);

        $entry_company = tep_db_prepare_input($HTTP_POST_VARS['entry_company']);
        $entry_state = tep_db_prepare_input($HTTP_POST_VARS['entry_state']);
        $entry_zone_id = tep_db_prepare_input($HTTP_POST_VARS['entry_zone_id']);
        if ($entry_zone_id > 0) $entry_state = '';

        $sql_data_array = array('entry_street_address' => $entry_street_address,
                                'entry_postcode' => $entry_postcode,
                                'entry_city' => $entry_city,
                                'entry_country_id' => $entry_country_id);

        if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $entry_company;
        if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $entry_suburb;
        if (ACCOUNT_STATE == 'true') {
          $sql_data_array['entry_state'] = $entry_state;
          $sql_data_array['entry_zone_id'] = $entry_zone_id;
        }

        tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "customers_id = '" . tep_db_input($customers_id) . "' and address_book_id = '" . tep_db_input($default_address_id) . "'");

        tep_redirect(tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $customers_id));
        break;
      case 'deleteconfirm':
        $customers_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);

        if ($HTTP_POST_VARS['delete_reviews'] == 'on') {
          $reviews_query = tep_db_query("select reviews_id from " . TABLE_REVIEWS . " where customers_id = '" . tep_db_input($customers_id) . "'");
          while ($reviews = tep_db_fetch_array($reviews_query)) {
            tep_db_query("delete from " . TABLE_REVIEWS_DESCRIPTION . " where reviews_id = '" . $reviews['reviews_id'] . "'");
          }
          tep_db_query("delete from " . TABLE_REVIEWS . " where customers_id = '" . tep_db_input($customers_id) . "'");
        } else {
          tep_db_query("update " . TABLE_REVIEWS . " set customers_id = null where customers_id = '" . tep_db_input($customers_id) . "'");
        }

        tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . tep_db_input($customers_id) . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . tep_db_input($customers_id) . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . tep_db_input($customers_id) . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . tep_db_input($customers_id) . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . tep_db_input($customers_id) . "'");
        tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where customer_id = '" . tep_db_input($customers_id) . "'");

        tep_redirect(tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')))); 
        break;
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<?php
  if ($HTTP_GET_VARS['action'] == 'edit') {
?>
<script language="javascript"><!--
document.write('<style type="text/css"><!--');
document.write('.collapse { position: absolute; visibility: hidden; }');
document.write('.expand { position: relative; visibility: visible; }');
document.write('//--></style>');

function show(ement) {
  ement.style.position = 'relative';
  ement.style.visibility = 'visible';
}

function hide(ement) {
  ement.style.position = 'absolute';
  ement.style.visibility = 'hidden';
}

function resetStateText(theForm) {
  theForm.entry_state.value = '';

  if (theForm.entry_zone_id.options.length > 1) {
    theForm.entry_state.value = '<?php echo JS_STATE_SELECT; ?>';

    show(theForm.entry_zone_id);
    hide(theForm.entry_state);
  } else {
    hide(theForm.entry_zone_id);
    show(theForm.entry_state);
  }
}

function resetZoneSelected(theForm) {
  if (theForm.entry_state.value != '') {
    theForm.entry_zone_id.selectedIndex = '0';
    if (theForm.entry_zone_id.options.length > 1) {
      theForm.entry_state.value = '<?php echo JS_STATE_SELECT; ?>';
    }
  }
}

function update_zone(theForm) {
  var NumState = theForm.entry_zone_id.options.length;
  var SelectedCountry = '';

  while(NumState > 0) {
    NumState--;
    theForm.entry_zone_id.options[NumState] = null;
  }

  SelectedCountry = theForm.entry_country_id.options[theForm.entry_country_id.selectedIndex].value;

<?php echo tep_js_zone_list('SelectedCountry', 'theForm', 'entry_zone_id'); ?>

  resetStateText(theForm);
}

function check_form() {
  var error = 0;
  var error_message = "<?php echo JS_ERROR; ?>";

  var frm = document.customers;
  var erfrm = document.error_messages;
  var radioGroup = false;

  for (var i=0; i<frm.length; i++) {
    if (frm.elements[i].type == 'text') {
      if (eval(erfrm[frm.elements[i].name+'_error'])) {
        if (frm.elements[i].value == '' || frm.elements[i].length < erfrm[frm.elements[i].name+'_min'].value) {
          error_message = error_message + erfrm[frm.elements[i].name+'_error'].value + "\n";
          error = 1;
        }
      }
    } else if (frm.elements[i].type == 'radio') {
      if ( (eval(erfrm[frm.elements[i].name+'_error'])) && (!radioGroup) ) {
        if (!radioGroup) radioGroup = frm.elements[i].name;
        var radio = 'frm.'+frm.elements[i].name;
        if (!eval(radio+'.length')) {
        } else {
          var control = false;
          for (var j=0; j<eval(radio+'.length'); j++) {
            if (eval(radio+'['+j+'].checked')) {
              control = true;
            }
          }
          if (!control) {
            error_message = error_message + erfrm[frm.elements[i].name+'_error'].value + "\n";
            error = 1;
          }
        }
      }
    } else if (frm.elements[i].type == 'select-one') {
      if (eval(erfrm[frm.elements[i].name+'_error'])) {
        if (frm.elements[i].value == 0) {
          error_message = error_message + erfrm[frm.elements[i].name+'_error'].value + "\n";
          error = 1;
        }
      }
    }
  }

<?php if (ACCOUNT_STATE == 'true') { ?>
  if (document.customers.entry_zone_id.options.length <= 1) {
    if (document.customers.entry_state.value == "" || document.customers.entry_state.length < <?php echo ENTRY_STATE_MIN_LENGTH; ?> ) {
       error_message = error_message + "<?php echo JS_STATE; ?>";
       error = 1;
    }
  } else {
    document.customers.entry_state.value = '';
    if (document.customers.entry_zone_id.selectedIndex == 0) {
       error_message = error_message + "<?php echo JS_ZONE; ?>";
       error = 1;
    }
  }
<?php } ?>

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>
<?php
  }
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if ($HTTP_GET_VARS['action'] == 'edit') {
    $customers_query = tep_db_query("select c.customers_gender, c.customers_firstname, c.customers_lastname, c.customers_dob, c.customers_email_address, a.entry_company, a.entry_street_address, a.entry_suburb, a.entry_postcode, a.entry_city, a.entry_state, a.entry_zone_id, a.entry_country_id, c.customers_telephone, c.customers_fax, c.customers_newsletter, c.customers_default_address_id from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_default_address_id = a.address_book_id where a.customers_id = c.customers_id and c.customers_id = '" . $HTTP_GET_VARS['cID'] . "'");
    $customers = tep_db_fetch_array($customers_query);
    $cInfo = new objectInfo($customers);

    $newsletter_array = array(array('id' => '1', 'text' => ENTRY_NEWSLETTER_YES),
                              array('id' => '0', 'text' => ENTRY_NEWSLETTER_NO));
?>
<form name="error_messages">
<?php if (ACCOUNT_GENDER == 'true') echo '<input type="hidden" name="customers_gender_error" value="' . JS_GENDER . '">'; ?>
<input type="hidden" name="customers_firstname_min" value="<?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>">
<input type="hidden" name="customers_firstname_error" value="<?php echo JS_FIRST_NAME; ?>">
<input type="hidden" name="customers_lastname_min" value="<?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>">
<input type="hidden" name="customers_lastname_error" value="<?php echo JS_LAST_NAME; ?>">
<input type="hidden" name="customers_dob_min" value="<?php echo ENTRY_DOB_MIN_LENGTH; ?>">
<input type="hidden" name="customers_dob_error" value="<?php echo JS_DOB; ?>">
<input type="hidden" name="customers_email_address_min" value="<?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>">
<input type="hidden" name="customers_email_address_error" value="<?php echo JS_EMAIL_ADDRESS; ?>">
<input type="hidden" name="entry_street_address_min" value="<?php echo ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>">
<input type="hidden" name="entry_street_address_error" value="<?php echo JS_ADDRESS; ?>">
<input type="hidden" name="entry_postcode_min" value="<?php echo ENTRY_POSTCODE_MIN_LENGTH; ?>">
<input type="hidden" name="entry_postcode_error" value="<?php echo JS_POST_CODE; ?>">
<input type="hidden" name="entry_city_min" value="<?php echo ENTRY_CITY_MIN_LENGTH; ?>">
<input type="hidden" name="entry_city_error" value="<?php echo JS_CITY; ?>">
<input type="hidden" name="entry_country_id_error" value="<?php echo JS_COUNTRY; ?>">
<input type="hidden" name="customers_telephone_min" value="<?php echo ENTRY_TELEPHONE_MIN_LENGTH; ?>">
<input type="hidden" name="customers_telephone_error" value="<?php echo JS_TELEPHONE; ?>">
</form>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('customers', FILENAME_CUSTOMERS, tep_get_all_get_params(array('action')) . 'action=update', 'post', 'onSubmit="return check_form();"') . tep_draw_hidden_field('default_address_id', $cInfo->customers_default_address_id); ?>
        <td class="formAreaTitle"><?php echo CATEGORY_PERSONAL; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
<?php
    if (ACCOUNT_GENDER == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_GENDER; ?></td>
            <td class="main"><?php echo tep_draw_radio_field('customers_gender', 'm', false, $cInfo->customers_gender) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('customers_gender', 'f', false, $cInfo->customers_gender) . '&nbsp;&nbsp;' . FEMALE; ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td class="main"><?php echo ENTRY_FIRST_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('customers_firstname', $cInfo->customers_firstname, 'maxlength="32"', true); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_LAST_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('customers_lastname', $cInfo->customers_lastname, 'maxlength="32"', true); ?></td>
          </tr>
<?php
    if (ACCOUNT_DOB == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_DATE_OF_BIRTH; ?></td>
            <td class="main"><?php echo tep_draw_input_field('customers_dob', tep_date_short($cInfo->customers_dob), 'maxlength="10"', true); ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
            <td class="main"><?php echo tep_draw_input_field('customers_email_address', $cInfo->customers_email_address, 'maxlength="96"', true); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
    if (ACCOUNT_COMPANY == 'true') {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_COMPANY; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_COMPANY; ?></td>
            <td class="main"><?php echo tep_draw_input_field('entry_company', $cInfo->entry_company, 'maxlength="32"'); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
    }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_ADDRESS; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_STREET_ADDRESS; ?></td>
            <td class="main"><?php echo tep_draw_input_field('entry_street_address', $cInfo->entry_street_address, 'maxlength="64"', true); ?></td>
          </tr>
<?php
    if (ACCOUNT_SUBURB == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_SUBURB; ?></td>
            <td class="main"><?php echo tep_draw_input_field('entry_suburb', $cInfo->entry_suburb, 'maxlength="32"'); ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td class="main"><?php echo ENTRY_POST_CODE; ?></td>
            <td class="main"><?php echo tep_draw_input_field('entry_postcode', $cInfo->entry_postcode, 'maxlength="8"', true); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CITY; ?></td>
            <td class="main"><?php echo tep_draw_input_field('entry_city', $cInfo->entry_city, 'maxlength="32"', true); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_COUNTRY; ?></td>
            <td class="main"><?php echo tep_draw_pull_down_menu('entry_country_id', tep_get_countries(), $cInfo->entry_country_id, 'onChange="update_zone(this.form);"'); ?></td>
          </tr>
<?php
    if (ACCOUNT_STATE == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_STATE; ?></td>
            <td class="main"><div><?php echo tep_draw_pull_down_menu('entry_zone_id', tep_prepare_country_zones_pull_down($cInfo->entry_country_id), $cInfo->entry_zone_id, 'onChange="resetStateText(this.form);" class="expand"') . '</div><div>' . tep_draw_input_field('entry_state', $cInfo->entry_state, 'maxlength="32" onChange="resetZoneSelected(this.form);" class="collapse"'); ?></div></td>
          </tr>
<?php
    }
?>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_CONTACT; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_TELEPHONE_NUMBER; ?></td>
            <td class="main"><?php echo tep_draw_input_field('customers_telephone', $cInfo->customers_telephone, 'maxlength="32"', true); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_FAX_NUMBER; ?></td>
            <td class="main"><?php echo tep_draw_input_field('customers_fax', $cInfo->customers_fax, 'maxlength="32"'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_OPTIONS; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_NEWSLETTER; ?></td>
            <td class="main"><?php echo tep_draw_pull_down_menu('customers_newsletter', $newsletter_array, $cInfo->customers_newsletter); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action'))) .'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </tr></form>
<?php
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr><?php echo tep_draw_form('search', FILENAME_CUSTOMERS, '', 'get'); ?>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search'); ?></td>
          </form></tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LASTNAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FIRSTNAME; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACCOUNT_CREATED; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $search = '';
    if ( ($HTTP_GET_VARS['search']) && (tep_not_null($HTTP_GET_VARS['search'])) ) {
      $keywords = tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['search']));
      $search = "where c.customers_lastname like '%" . $keywords . "%' or c.customers_firstname like '%" . $keywords . "%' or c.customers_email_address like '%" . $keywords . "'";
    }
    $customers_query_raw = "select c.customers_id, c.customers_lastname, c.customers_firstname, c.customers_email_address, a.entry_country_id from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id " . $search . " order by c.customers_lastname";
    $customers_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $customers_query_raw, $customers_query_numrows);
    $customers_query = tep_db_query($customers_query_raw);
    while ($customers = tep_db_fetch_array($customers_query)) {
      $info_query = tep_db_query("select customers_info_date_account_created as date_account_created, customers_info_date_account_last_modified as date_account_last_modified, customers_info_date_of_last_logon as date_last_logon, customers_info_number_of_logons as number_of_logons from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $customers['customers_id'] . "'");
      $info = tep_db_fetch_array($info_query);

      if (((!$HTTP_GET_VARS['cID']) || (@$HTTP_GET_VARS['cID'] == $customers['customers_id'])) && (!$cInfo)) {
        $country_query = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . $customers['entry_country_id'] . "'");
        $country = tep_db_fetch_array($country_query);

        $reviews_query = tep_db_query("select count(*) as number_of_reviews from " . TABLE_REVIEWS . " where customers_id = '" . $customers['customers_id'] . "'");
        $reviews = tep_db_fetch_array($reviews_query);

        $customer_info = tep_array_merge($country, $info, $reviews);

        $cInfo_array = tep_array_merge($customers, $customer_info);
        $cInfo = new objectInfo($cInfo_array);
      }

      if ( (is_object($cInfo)) && ($customers['customers_id'] == $cInfo->customers_id) ) {
        echo '          <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '          <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID')) . 'cID=' . $customers['customers_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo $customers['customers_lastname']; ?></td>
                <td class="dataTableContent"><?php echo $customers['customers_firstname']; ?></td>
                <td class="dataTableContent" align="right"><?php echo tep_date_short($info['date_account_created']); ?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($cInfo)) && ($customers['customers_id'] == $cInfo->customers_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID')) . 'cID=' . $customers['customers_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td valign="top" class="smallText"><?php echo $customers_split->display_count($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
                    <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' '; echo $customers_split->display_links($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>
                  </tr>
<?php
    if (tep_not_null($HTTP_GET_VARS['search'])) {
?>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS) . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
                  </tr>
<?php
    }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  switch ($HTTP_GET_VARS['action']) {
    case 'confirm':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CUSTOMER . '</b>');

      $contents = array('form' => tep_draw_form('customers', FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO . '<br><br><b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');
      if ($cInfo->number_of_reviews > 0) $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_reviews', 'on', true) . ' ' . sprintf(TEXT_DELETE_REVIEWS, $cInfo->number_of_reviews));
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=confirm') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS, 'cID=' . $cInfo->customers_id) . '">' . tep_image_button('button_orders.gif', IMAGE_ORDERS) . '</a> <a href="' . tep_href_link(FILENAME_MAIL, 'selected_box=tools&customer=' . $cInfo->customers_email_address) . '">' . tep_image_button('button_email.gif', IMAGE_EMAIL) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_DATE_ACCOUNT_CREATED . ' ' . tep_date_short($cInfo->date_account_created));
        $contents[] = array('text' => '<br>' . TEXT_DATE_ACCOUNT_LAST_MODIFIED . ' ' . tep_date_short($cInfo->date_account_last_modified));
        $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_LAST_LOGON . ' '  . tep_date_short($cInfo->date_last_logon));
        $contents[] = array('text' => '<br>' . TEXT_INFO_NUMBER_OF_LOGONS . ' ' . $cInfo->number_of_logons);
        $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY . ' ' . $cInfo->countries_name);
        $contents[] = array('text' => '<br>' . TEXT_INFO_NUMBER_OF_REVIEWS . ' ' . $cInfo->number_of_reviews);
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
