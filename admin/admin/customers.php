<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_CUSTOMERS; include(DIR_INCLUDES . 'include_once.php'); ?>
<?
  if (($HTTP_GET_VARS['action'] == 'add_customers') && ($HTTP_POST_VARS['insert'] == '1')) {
    $date_now = date('Ymd');
    $dob_ordered = "00000000";
    if (ACCOUNT_DOB) {
       $dob_ordered = substr($HTTP_POST_VARS['dob'], -4) . substr($HTTP_POST_VARS['dob'], 3, 2) . substr($HTTP_POST_VARS['dob'], 0, 2);
    }
    $gender = "M";
    if (ACCOUNT_GENDER) {
       $gender = $HTTP_POST_VARS['gender'];
    }
    $suburb = "";
    if (ACCOUNT_SUBURB) {
       $suburb = $HTTP_POST_VARS['suburb'];
    }
    $state = "";
    if (ACCOUNT_STATE) {
       $HTTP_POST_VARS['state'];
    }
    tep_db_query("insert into customers values ('', '" . $gender . "', '" . $HTTP_POST_VARS['firstname'] . "', '" . $HTTP_POST_VARS['lastname'] . "', '" . $dob_ordered . "', '" . $HTTP_POST_VARS['email_address'] . "', '" . $HTTP_POST_VARS['street_address'] . "', '" . $suburb . "', '" . $HTTP_POST_VARS['postcode'] . "', '" . $HTTP_POST_VARS['city'] . "', '" . $state . "', '" . $HTTP_POST_VARS['telephone'] . "', '" . $HTTP_POST_VARS['fax'] . "', '" . $HTTP_POST_VARS['password'] . "', '" . $HTTP_POST_VARS['country'] . "')");
    $insert_id = tep_db_insert_id();
    tep_db_query("insert into customers_info values ('" . $insert_id . "', '', '0', '" . $date_now . "', '')");
    header('Location: ' . tep_href_link(FILENAME_CUSTOMERS, '&order_by=' . $order_by . '&page=' . $page, 'NONSSL')); tep_exit();
  } elseif ($HTTP_GET_VARS['action'] == 'update_customers') {
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
       $update_query = $update_query . "customers_state = '" . $HTTP_POST_VARS['state'] . "', ";
    }
    $update_query = $update_query . "customers_telephone = '" . $HTTP_POST_VARS['telephone'] . "', customers_fax = '" . $HTTP_POST_VARS['fax'] . "', customers_password = '" . $HTTP_POST_VARS['password'] . "', customers_country_id = '" . $HTTP_POST_VARS['country'] . "' where customers_id = '" . $HTTP_POST_VARS['customers_id'] . "'";
    tep_db_query($update_query);
    tep_db_query("update customers_info set customers_info_date_account_last_modified = '" . $date_now . "' where customers_info_id = '" . $HTTP_POST_VARS['customers_id'] . "'");
    header('Location: ' . tep_href_link(FILENAME_CUSTOMERS, '&order_by=' . $order_by . '&page=' . $page, 'NONSSL')); tep_exit();
  } elseif ($HTTP_GET_VARS['action'] == 'delete_customers') {
    tep_db_query("delete from customers where customers_id = '" . $HTTP_POST_VARS['customers_id'] . "'");
    tep_db_query("delete from customers_info where customers_info_id = '" . $HTTP_POST_VARS['customers_id'] . "'");
    tep_db_query("delete from customers_basket where customers_id = '" . $HTTP_POST_VARS['customers_id'] . "'");
    $reviews = tep_db_query("select reviews_id from reviews_extra where customers_id = '" . $HTTP_POST_VARS['customers_id'] . "'");
    while ($reviews_values = tep_db_fetch_array($reviews)) {
      tep_db_query("delete from reviews where reviews_id = '" . $reviews_values['reviews_id'] . "'");
    }
    tep_db_query("delete from reviews_extra where customers_id = '" . $HTTP_POST_VARS['customers_id'] . "'");
    $address_book = tep_db_query("select address_book_id from address_book_to_customers where customers_id = '" . $HTTP_POST_VARS['customers_id'] . "'");
    while ($address_book_values = tep_db_fetch_array($address_book)) {
      tep_db_query("delete from address_book where address_book_id = '" . $address_book_values['address_book_id'] . "'");
    }
    tep_db_query("delete from address_book_to_customers where customers_id = '" . $HTTP_POST_VARS['customers_id'] . "'");
    header('Location: ' . tep_href_link(FILENAME_CUSTOMERS, '&order_by=' . $order_by . '&page=' . $page, 'NONSSL')); tep_exit();
  }
?>
<html>
<head>
<title><?=TITLE;?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript"><!--
<?
  if ((($HTTP_GET_VARS['action'] == 'add_customers') && (!$HTTP_POST_VARS['insert'])) || ($HTTP_GET_VARS['action'] == 'update')) {
?>
function check_form() {
  var error = 0;
  var error_message = "<?=JS_ERROR;?>";

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
  var country = document.customers.country.value;
  var telephone = document.customers.telephone.value;
  var password = document.customers.password.value;
  var confirmation = document.customers.confirmation.value;

<?
   if (ACCOUNT_GENDER) {
?>
  if (document.customers.gender[0].checked || document.customers.gender[1].checked) {
  } else {
    error_message = error_message + "<?=JS_GENDER;?>";
    error = 1;
  }
<?
   }
?>
  
  if (firstname = "" || firstname.length < 3) {
    error_message = error_message + "<?=JS_FIRST_NAME;?>";
    error = 1;
  }

  if (lastname = "" || lastname.length < 3) {
    error_message = error_message + "<?=JS_LAST_NAME;?>";
    error = 1;
  }

<?
   if (ACCOUNT_DOB) {
?>
  if (dob = "" || dob.length < 10) {
    error_message = error_message + "<?=JS_DOB;?>";
    error = 1;
  }
<?
   }
?>

  if (email_address = "" || email_address.length < 6) {
    error_message = error_message + "<?=JS_EMAIL_ADDRESS;?>";
    error = 1;
  }

  if (street_address = "" || street_address.length < 5) {
    error_message = error_message + "<?=JS_ADDRESS;?>";
    error = 1;
  }

  if (postcode = "" || postcode.length < 4) {
    error_message = error_message + "<?=JS_POST_CODE;?>";
    error = 1;
  }

  if (city = "" || city.length < 4) {
    error_message = error_message + "<?=JS_CITY;?>";
    error = 1;
  }

  if (country == 0) {
    error_message = error_message + "<?=JS_COUNTRY;?>";
    error = 1;
  }

  if (telephone = "" || telephone.length < 5) {
    error_message = error_message + "<?=JS_TELEPHONE;?>";
    error = 1;
  }

  if ((password != confirmation) || (password = "" || password.length < 5)) {
    error_message = error_message + "<?=JS_PASSWORD;?>";
    error = 1;
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
<?
  } else {
?>
function checkForm() {
  var error_message = "<?=JS_ERROR;?>";
  var error = 0;
  var customers_firstname = document.customers.customers_firstname.value;
  var customers_lastname = document.customers.customers_lastname.value;
  var customers_country = document.customers.customers_country.value;
  
  if (customers_firstname.length < 1) {
    error_message = error_message + "<?=JS_FIRST_NAME;?>";
    error = 1;
  }
  
  if (customers_lastname.length < 1) {
    error_message = error_message + "<?=JS_LAST_NAME;?>";
    error = 1;
  }
  
  if (customers_country == 0) {
    error_message = error_message + "<?=JS_COUNTRY;?>";
    error = 1;
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
<?
  }
?>

function go() {
  if (document.order_by.selected.options[document.order_by.selected.selectedIndex].value != "none") {
    location = "<?=FILENAME_CUSTOMERS;?>?order_by="+document.order_by.selected.options[document.order_by.selected.selectedIndex].value;
  }
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_INCLUDES . 'header.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<?=BOX_WIDTH;?>" valign="top"><table border="0" width="<?=BOX_WIDTH;?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_INCLUDES . 'column_left.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
            <td bgcolor="<?=TOP_BAR_BACKGROUND_COLOR;?>" width="100%" nowrap><font face="<?=TOP_BAR_FONT_FACE;?>" size="<?=TOP_BAR_FONT_SIZE;?>" color="<?=TOP_BAR_FONT_COLOR;?>">&nbsp;<?=TOP_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
<?
  if ((($HTTP_GET_VARS['action'] == 'add_customers') && (!$HTTP_POST_VARS['insert'])) || ($HTTP_GET_VARS['action'] == 'update') || ($HTTP_GET_VARS['action'] == 'delete')) {
    if ($HTTP_GET_VARS['action'] == 'add_customers') {
      $action = 'add';
    } elseif ($HTTP_GET_VARS['action'] == 'update') {
      $action = 'update';
    } elseif ($HTTP_GET_VARS['action'] == 'delete') {
      $action = 'delete';
    }
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_IMAGES . 'table_background_account.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?=tep_black_line();?></td>
      </tr>
<?
    if ($action == 'add') {
      echo '      <tr><form name="customers" method="post" action="' . tep_href_link(FILENAME_CUSTOMERS, 'action=add_customers' . '&order_by=' . $order_by . '&page=' . $page, 'NONSSL') . '" onSubmit="return check_form();"><input type="hidden" name="insert" value="1">' . "\n";
    } elseif ($action == 'update') {
      echo '      <tr><form name="customers" method="post" action="' . tep_href_link(FILENAME_CUSTOMERS, 'action=update_customers' . '&order_by=' . $order_by . '&page=' . $page, 'NONSSL') . '" onSubmit="return check_form();"><input type="hidden" name="customers_id" value="' . $HTTP_GET_VARS['customers_id'] . '">' . "\n";
    } else {
      echo '      <tr><form name="customers" method="post" action="' . tep_href_link(FILENAME_CUSTOMERS, 'action=delete_customers' . '&order_by=' . $order_by, 'NONSSL') . '"><input type="hidden" name="customers_id" value="' . $HTTP_GET_VARS['customers_id'] . '">' . "\n";
    }

    if (($action == 'update') || ($action == 'delete')) {
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
         $cust_query = $cust_query . "customers_state, ";
      }
      $cust_query = $cust_query . "customers_country_id, customers_telephone, customers_fax, customers_password from customers where customers_id = '" . $HTTP_GET_VARS['customers_id'] . "'";

      $customers = tep_db_query($cust_query);
      $customers_values = tep_db_fetch_array($customers);

      if (ACCOUNT_GENDER) {
         $gender = $customers_values['customers_gender'];
      }
      $firstname = $customers_values['customers_firstname'];
      $lastname = $customers_values['customers_lastname'];
      if (ACCOUNT_DOB) {
         $dob = substr($customers_values['customers_dob'], -2) . '/' . substr($customers_values['customers_dob'], 4, 2) . '/' . substr($customers_values['customers_dob'], 0, 4);
      }
      $email_address = $customers_values['customers_email_address'];
      $street_address = $customers_values['customers_street_address'];
      if (ACCOUNT_SUBURB) {
         $suburb = $customers_values['customers_suburb'];
      }
      $postcode = $customers_values['customers_postcode'];
      $city = $customers_values['customers_city'];
      if (ACCOUNT_STATE) {
         $state = $customers_values['customers_state'];
      }
      $country = $customers_values['customers_country_id'];
      $telephone = $customers_values['customers_telephone'];
      $fax = $customers_values['customers_fax'];
      $password = $customers_values['customers_password'];
    } else {
      $firstname = $HTTP_POST_VARS['customers_firstname'];
      $lastname = $HTTP_POST_VARS['customers_lastname'];
      if (ACCOUNT_DOB) {
         $dob = DOB_FORMAT_STRING;
      }
      $country = $HTTP_POST_VARS['customers_country'];
      $password = '';
    }
?>
        <td width="100%"><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="7" nowrap><font face="<?=CATEGORY_FONT_FACE;?>" size="<?=CATEGORY_FONT_SIZE;?>" color="<?=CATEGORY_FONT_COLOR;?>"><?=CATEGORY_PERSONAL;?></font></td>
          </tr>
<?
   if (ACCOUNT_GENDER) {
    if ($action == 'delete') {
?>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;&nbsp;<?=ENTRY_GENDER;?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;&nbsp;<?
      if (@$gender == 'm') {
        echo MALE;
      } else {
        echo FEMALE;
      } ?></font></td>
          </tr>
<?
    } else {
?>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;&nbsp;<?=ENTRY_GENDER;?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;&nbsp;<input type="radio" name="gender" value="m"<?
      if (@$gender == 'm') {
        echo ' CHECKED';
      } ?>>&nbsp;&nbsp;<?=MALE;?>&nbsp;&nbsp;<input type="radio" name="gender" value="f"<?
      if (@$gender == 'f') {
        echo ' CHECKED';
      } ?>>&nbsp;&nbsp;<?=FEMALE;?>&nbsp;<?=ENTRY_GENDER_TEXT;?></font></td>
          </tr>
<?
    }
   }
?>
          <tr>
            <td colspan="2"><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;&nbsp;<?=ENTRY_FIRST_NAME;?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;&nbsp;<? if ($action == 'delete') { echo $firstname; } else { echo '<input type="text" name="firstname" maxlength="32" value="' . @$firstname . '">&nbsp;' . ENTRY_FIRST_NAME_TEXT; } ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;&nbsp;<?=ENTRY_LAST_NAME;?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;&nbsp;<? if ($action == 'delete') { echo $lastname; } else { echo '<input type="text" name="lastname" maxlength="32" value="' . @$lastname . '">&nbsp;' . ENTRY_LAST_NAME_TEXT; } ?></font></td>
          </tr>
<?
   if (ACCOUNT_DOB) {
?>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;&nbsp;<?=ENTRY_DATE_OF_BIRTH;?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;&nbsp;<? if ($action == 'delete') { echo $dob; } else { echo '<input type="text" name="dob" maxlength="10" value="' . @$dob . '">&nbsp;' . ENTRY_DATE_OF_BIRTH_TEXT; } ?></font></td>
          </tr>
<?
   }
?>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;&nbsp;<?=ENTRY_EMAIL_ADDRESS;?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;&nbsp;<? if ($action == 'delete') { echo $email_address; } else { echo '<input type="text" name="email_address" maxlength="96" value="' . @$email_address . '">&nbsp;' . ENTRY_EMAIL_ADDRESS_TEXT; } ?></font></td>
          </tr>
          <tr>
            <td colspan="2"><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="7" nowrap><font face="<?=CATEGORY_FONT_FACE;?>" size="<?=CATEGORY_FONT_SIZE;?>" color="<?=CATEGORY_FONT_COLOR;?>"><?=CATEGORY_ADDRESS;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;&nbsp;<?=ENTRY_STREET_ADDRESS;?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;&nbsp;<? if ($action == 'delete') { echo $street_address; } else { echo '<input type="text" name="street_address" maxlength="64" value="' . @$street_address . '">&nbsp;' . ENTRY_STREET_ADDRESS_TEXT; }?></font></td>
          </tr>
<?
   if (ACCOUNT_SUBURB) {
?>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;&nbsp;<?=ENTRY_SUBURB;?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;&nbsp;<? if ($action == 'delete') { echo $suburb; } else { echo '<input type="text" name="suburb" maxlength="32" value="' . @$suburb . '">&nbsp;' . ENTRY_SUBURB_TEXT; } ?></font></td>
          </tr>
<?
   }
?>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;&nbsp;<?=ENTRY_POST_CODE;?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;&nbsp;<? if ($action == 'delete') { echo $postcode; } else { echo '<input type="text" name="postcode" maxlength="8" value="' . @$postcode . '">&nbsp;' . ENTRY_POST_CODE_TEXT; } ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;&nbsp;<?=ENTRY_CITY;?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;&nbsp;<? if ($action == 'delete') { echo $city; } else { echo '<input type="text" name="city" maxlength="32" value="' . @$city . '">&nbsp;' . ENTRY_CITY_TEXT; } ?></font></td>
          </tr>
<?
   if (ACCOUNT_STATE) {
?>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;&nbsp;<?=ENTRY_STATE;?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;&nbsp;<? if ($action == 'delete') { echo $state; } else { echo '<input type="text" name="state" maxlength="32" value="' . @$state . '">&nbsp;' . ENTRY_STATE_TEXT; } ?></font></td>
          </tr>
<?
   }
?>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;<?=ENTRY_COUNTRY;?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_SIZE;?>">&nbsp;
<?			
	$countries = tep_db_query("select countries_id, countries_name from countries order by countries_name");
	if ($action == 'delete') { 
	while ($countries_values = tep_db_fetch_array($countries)) {
	   if ($countries_values['countries_id'] == $country) {
	   echo ($countries_values['countries_name']); 
	   }
	   }
	}	else {
	echo '<select name="country"><option value="0">' . PLEASE_SELECT . '</option>';
	while ($countries_values = tep_db_fetch_array($countries)) {
      echo '<option value="' . $countries_values['countries_id'] . '"';
      if ($countries_values['countries_id'] == $country) echo ' SELECTED';
      echo '>' . $countries_values['countries_name'] . '</option>';
    }
	echo '</select>&nbsp;' . ENTRY_COUNTRY_TEXT;
	}
	echo '</font></td>';
?>	
          </tr>
          <tr>
            <td colspan="2"><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="3" nowrap><font face="<?=CATEGORY_FONT_FACE;?>" size="<?=CATEGORY_FONT_SIZE;?>" color="<?=CATEGORY_FONT_COLOR;?>"><?=CATEGORY_CONTACT;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;&nbsp;<?=ENTRY_TELEPHONE_NUMBER;?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;&nbsp;<? if ($action == 'delete') { echo $telephone; } else { echo '<input type="text" name="telephone" maxlength="32" value="' . @$telephone . '">&nbsp;' . ENTRY_TELEPHONE_NUMBER_TEXT; } ?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;&nbsp;<?=ENTRY_FAX_NUMBER;?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;&nbsp;<? if ($action == 'delete') { echo $fax; } else { echo '<input type="text" name="fax" maxlength="32" value="' . @$fax . '">&nbsp;' . ENTRY_FAX_NUMBER_TEXT; } ?></font></td>
          </tr>
          <tr>
            <td colspan="2"><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="3" nowrap><font face="<?=CATEGORY_FONT_FACE;?>" size="<?=CATEGORY_FONT_SIZE;?>" color="<?=CATEGORY_FONT_COLOR;?>"><?=CATEGORY_PASSWORD;?></font></td>
          </tr>
<?
    if ($action == 'delete') {
?>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;&nbsp;<?=ENTRY_PASSWORD;?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;&nbsp;<?=PASSWORD_HIDDEN;?></font></td>
          </tr>
<?
    } else {
?>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;&nbsp;<?=ENTRY_PASSWORD;?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;&nbsp;<input type="password" name="password" maxlength="12" value="<?=@$password;?>">&nbsp;<?=ENTRY_PASSWORD_TEXT;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_COLOR;?>">&nbsp;&nbsp;<?=ENTRY_PASSWORD_CONFIRMATION;?>&nbsp;&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_COLOR;?>">&nbsp;&nbsp;<input type="password" name="confirmation" maxlength="12" value="<?=@$password;?>">&nbsp;<?=ENTRY_PASSWORD_CONFIRMATION_TEXT;?></font></td>
          </tr>
<?
    }
?>
        </table></td>
      </tr>
      <tr>
        <td><br><?=tep_black_line();?></td>
      </tr>
      <tr>
<?
    if ($action == 'add') {
      echo '        <td align="right"><br><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">' . tep_image_submit(DIR_IMAGES . 'button_insert.gif', '50', '14', '0', IMAGE_INSERT) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CUSTOMERS, '&order_by=' . $order_by . '&page=' . $page, 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_cancel.gif', '50', '14', '0', IMAGE_CANCEL) . '</a>&nbsp;&nbsp;</font></td>' . "\n";
    } elseif ($action == 'update') {
      echo '        <td align="right"><br><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">' . tep_image_submit(DIR_IMAGES . 'button_update.gif', '50', '14', '0', IMAGE_UPDATE) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CUSTOMERS, '&order_by=' . $order_by . '&page=' . $page, 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_cancel.gif', '50', '14', '0', IMAGE_CANCEL) . '</a>&nbsp;&nbsp;</font></td>' . "\n";
    } elseif ($action == 'delete') {
      echo '        <td align="right"><br><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">' . tep_image_submit(DIR_IMAGES . 'button_delete.gif', '50', '14', '0', IMAGE_DELETE) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CUSTOMERS, '&order_by=' . $order_by . '&page=' . $page, 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_cancel.gif', '50', '14', '0', IMAGE_CANCEL) . '</a>&nbsp;&nbsp;</font></td>' . "\n";
    }
?>
      </tr></form>
<?
  } else {
    if ($HTTP_GET_VARS['order_by']) {
      $order_by = $HTTP_GET_VARS['order_by'];
    } else {
      $order_by = 'customers_id';
    }
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap><br><form name="order_by"><select name="selected" onChange="go()"><option value="customers_lastname"<? if ($order_by == 'customers_lastname') { echo ' SELECTED'; } ?>>Customers Lastname</option><option value="customers_id"<? if ($order_by == 'customers_id') { echo ' SELECTED'; } ?>>Customers ID</option><option value="customers_country"<? if ($order_by == 'customers_country') { echo ' SELECTED'; } ?>>Customers Country</option></select></form></td>
          </tr>
        </table></td>
      </tr>
	  <tr>
	    <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
		  	<td nowrap align="right"><form name="quick_find" method="post" action="customers.php?"><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=CUSTOMER_SEARCH_TEXT;?>&nbsp;</font>
		   <input type="text" name="query" size="13" maxlength="30"<? if ($HTTP_POST_VARS["query"]) { echo ' value="' . $HTTP_POST_VARS["query"] . '"'; } ?>>&nbsp;
		   <?=tep_image_submit(DIR_CATALOG_IMAGES . 'button_quick_find.gif', '16', '17', '0', BOX_HEADING_SEARCH);?></form></td>
          </tr>
		  <tr>
		   <td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">
<?
$per_page = MAX_DISPLAY_SEARCH_RESULTS;
  $row = 0;
    $search_keywords = explode(' ', trim($HTTP_POST_VARS['query']));
     $customers = "select customers.customers_id, customers.customers_lastname, customers.customers_firstname, customers.customers_city, countries.countries_name, customers.customers_email_address from countries, customers where customers.customers_country_id = countries.countries_id and ";
     for ($i=0; ($i<count($search_keywords)-1); $i++ ) {
       $customers .= "customers_email_address like '%" .
	   $search_keywords[$i] . "%' or customers_firstname like '%" .
	   $search_keywords[$i] . "%' or customers_lastname like '%" .
	   $search_keywords[$i] . "%' and ";
     }
     $customers .= "(customers_email_address like '%" . $search_keywords[$i] . "%' or customers_firstname like '%" . $search_keywords[$i] . "%' or customers_lastname like '%" . $search_keywords[$i] . "%') order by '" . $order_by . "'";
	 if (!$page)
 	 {
   	 $page = 1;
 	 }
	 $prev_page = $page - 1;
	 $next_page = $page + 1;
	 $query = tep_db_query($customers);
	 $page_start = ($per_page * $page) - $per_page;
	 $num_rows = tep_db_num_rows($query);
	 if ($num_rows <= $per_page) {
   	 $num_pages = 1;
	 } else if (($num_rows % $per_page) == 0) {
   	 $num_pages = ($num_rows / $per_page);
	 } else {
   	 $num_pages = ($num_rows / $per_page) + 1;
	 }
	 $num_pages = (int) $num_pages;

	 if (($page > $num_pages) || ($page < 0)) {
   	 error("You have specified an invalid page number");
	 }

	 $customers = $customers . " LIMIT $page_start, $per_page";
	 $query = tep_db_query($customers);

	 while ($result = tep_db_fetch_array($query)) {
     echo $result[email];
     echo "";
	 }

// Previous
   if ($prev_page)  {
   echo "<a href=\"$PHP_SELF?page=$prev_page&order_by=$order_by\"><<</a> | ";
   }

   for ($i = 1; $i <= $num_pages; $i++) {
   if ($i != $page) {
      echo " <a href=\"$PHP_SELF?page=$i&order_by=$order_by\">$i</a> | ";
   	  } else {
      echo " <b><font color=red>$i<font color=black></b> |";
   	  }
	  }

// Next
   if ($page != $num_pages) {
   echo " <a href=\"$PHP_SELF?page=$next_page&order_by=$order_by\">>></a> ";
   }
   echo '</td></tr></table></td></tr>';
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="5"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_ID;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_FIRSTNAME;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_LASTNAME;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_COUNTRY;?>&nbsp;</b></font></td>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_ACTION;?>&nbsp;</b></font></td>
          </tr>
		  <tr>
            <td colspan="5"><?=tep_black_line();?></td>
          </tr>
<?
  		  $customers = tep_db_query($customers);
    	  while ($customers_values = tep_db_fetch_array($customers)) {
      	  $rows++;
      if (floor($rows/2) == ($rows/2)) {
        echo '          <tr bgcolor="#ffffff">' . "\n";
      } else {
        echo '          <tr bgcolor="#f4f7fd">' . "\n";
      }
?>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$customers_values['customers_id'];?>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$customers_values['customers_firstname'];?>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$customers_values['customers_lastname'];?>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$customers_values['countries_name'];?>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?='<a href="' . tep_href_link(FILENAME_CUSTOMERS, 'action=update&customers_id=' . $customers_values['customers_id'] . '&order_by=' . $order_by . '&page=' . $page, 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_modify.gif', '50', '14', '0', IMAGE_MODIFY);?></a>&nbsp;&nbsp;<?='<a href="' . tep_href_link(FILENAME_CUSTOMERS, 'action=delete&customers_id=' . $customers_values['customers_id'] . '&order_by=' . $order_by . '&page=' . $page, 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_delete.gif', '50', '14', '0', IMAGE_DELETE);?></a>&nbsp;</font></td>
          </tr>
<?
  		  $max_customers_id_query = tep_db_query("select max(customers_id) + 1 as next_id from customers");
		  $max_customers_id_values = tep_db_fetch_array($max_customers_id_query);
		  $next_id = $max_customers_id_values['next_id'];
    }
?>
          <tr>
            <td colspan="5"><?=tep_black_line();?></td>
          </tr>
<?
    if (floor($rows/2) == ($rows/2)) {
      echo '          <tr bgcolor="#f4f7fd">' . "\n";
    } else {
      echo '          <tr bgcolor="#ffffff">' . "\n";
    }
    echo '<form name="customers" action="' . tep_href_link(FILENAME_CUSTOMERS, 'action=add_customers' . '&order_by=' . $order_by . '&page=' . $page, 'NONSSL') . '" method="post" onSubmit="return checkForm();">' . "\n";
?>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$next_id;?>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="customers_firstname" size="20">&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="customers_lastname" size="20">&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_SIZE;?>">&nbsp;&nbsp;<select name="customers_country"><option value="0"><?=PLEASE_SELECT;?></option>
<?
    $countries = tep_db_query("select countries_id, countries_name from countries order by countries_name");
    while ($countries_values = tep_db_fetch_array($countries)) {
      echo '<option value="' . $countries_values['countries_id'] . '"';
      if ($countries_values['countries_id'] == STORE_COUNTRY) echo ' SELECTED';
      echo '>' . $countries_values['countries_name'] . '</option>';
    }
?>
  	  </select></font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><b>&nbsp;<?=tep_image_submit(DIR_IMAGES . 'button_insert.gif', '50', '14', '0', IMAGE_INSERT);?>&nbsp;</font></td>
</form>
          </tr>
          <tr>
            <td colspan="5"><?=tep_black_line();?></td>
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
<? $include_file = DIR_INCLUDES . 'footer.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<? $include_file = DIR_INCLUDES . 'application_bottom.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
