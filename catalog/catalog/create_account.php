<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE . '</a>'; ?>
<html>
<head>
<title><?=TITLE;?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript"><!--
function check_form() {
  var error = 0;
  var error_message = "<?=JS_ERROR;?>";

  var first_name = document.create_account.firstname.value;
  var last_name = document.create_account.lastname.value;
<?
  if (ACCOUNT_DOB) {
     echo 'var dob = document.create_account.dob.value;';
  }
?>
  var email_address = document.create_account.email_address.value;  
  var street_address = document.create_account.street_address.value;
  var postcode = document.create_account.postcode.value;
  var city = document.create_account.city.value;
  var telephone = document.create_account.telephone.value;
  var password = document.create_account.password.value;
  var confirmation = document.create_account.confirmation.value;

<?
  if (ACCOUNT_GENDER) {
?>
  if (document.create_account.gender[0].checked || document.create_account.gender[1].checked) {
  } else {
    error_message = error_message + "<?=JS_GENDER;?>";
    error = 1;
  }
<?
  }
?>
  
  if (first_name = "" || first_name.length < <?=ENTRY_FIRST_NAME_MIN_LENGTH;?>) {
    error_message = error_message + "<?=JS_FIRST_NAME;?>";
    error = 1;
  }

  if (last_name = "" || last_name.length < <?=ENTRY_LAST_NAME_MIN_LENGTH;?>) {
    error_message = error_message + "<?=JS_LAST_NAME;?>";
    error = 1;
  }

<?
  if (ACCOUNT_DOB) {
?>
  if (dob = "" || dob.length < <?=ENTRY_DOB_MIN_LENGTH;?>) {
    error_message = error_message + "<?=JS_DOB;?>";
    error = 1;
  }
<?
  }
?>

  if (email_address = "" || email_address.length < <?=ENTRY_EMAIL_ADDRESS_MIN_LENGTH;?>) {
    error_message = error_message + "<?=JS_EMAIL_ADDRESS;?>";
    error = 1;
  }

  if (street_address = "" || street_address.length < <?=ENTRY_STREET_ADDRESS_MIN_LENGTH;?>) {
    error_message = error_message + "<?=JS_ADDRESS;?>";
    error = 1;
  }

  if (postcode = "" || postcode.length < <?=ENTRY_POSTCODE_MIN_LENGTH;?>) {
    error_message = error_message + "<?=JS_POST_CODE;?>";
    error = 1;
  }

  if (city = "" || city.length < <?=ENTRY_CITY_MIN_LENGTH;?>) {
    error_message = error_message + "<?=JS_CITY;?>";
    error = 1;
  }

  if (telephone = "" || telephone.length < <?=ENTRY_TELEPHONE_MIN_LENGTH;?>) {
    error_message = error_message + "<?=JS_TELEPHONE;?>";
    error = 1;
  }

  if ((password != confirmation) || (password = "" || password.length < <?=ENTRY_PASSWORD_MIN_LENGTH;?>)) {
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
    <td width="100%" valign="top"><form name="create_account" method="post" action="<?=tep_href_link(FILENAME_CREATE_ACCOUNT_PROCESS, '', 'NONSSL');?>" onSubmit="return check_form();"><input type="hidden" name="action" value="process"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
            <td bgcolor="<?=TOP_BAR_BACKGROUND_COLOR;?>" width="100%" nowrap><font face="<?=TOP_BAR_FONT_FACE;?>" size="<?=TOP_BAR_FONT_SIZE;?>" color="<?=TOP_BAR_FONT_COLOR;?>">&nbsp;<?=TOP_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
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
  if ($HTTP_GET_VARS['origin']) {
?>
      <tr>
        <td nowrap><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_ORIGIN_LOGIN;?>&nbsp;</font></td>
      </tr>
<?
  }
  $rowspan = 5+ACCOUNT_GENDER+ACCOUNT+DOB;
?>
      <tr>
        <td width="100%"><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="<?=$rowspan;?>" nowrap><font face="<?=CATEGORY_FONT_FACE;?>" size="<?=CATEGORY_FONT_SIZE;?>" color="<?=CATEGORY_FONT_COLOR;?>"><?=CATEGORY_PERSONAL;?></font></td>
          </tr>
<?
   if (ACCOUNT_GENDER) {
?>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;<?=ENTRY_GENDER;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_SIZE;?>">&nbsp;<input type="radio" name="gender" value="m">&nbsp;<?=MALE;?>&nbsp;&nbsp;<input type="radio" name="gender" value="f">&nbsp;&nbsp;<?=FEMALE;?>&nbsp;<?=ENTRY_GENDER_TEXT;?></font></td>
          </tr>
<?
   }
?>
          <tr>
            <td colspan="2" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;<?=ENTRY_FIRST_NAME;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_SIZE;?>">&nbsp;<input type="text" name="firstname" maxlength="32">&nbsp;<?=ENTRY_FIRST_NAME_TEXT;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;<?=ENTRY_LAST_NAME;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_SIZE;?>">&nbsp;<input type="text" name="lastname" maxlength="32">&nbsp;<?=ENTRY_LAST_NAME_TEXT;?></font></td>
          </tr>
<?
   if (ACCOUNT_DOB) {
?>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;<?=ENTRY_DATE_OF_BIRTH;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_SIZE;?>">&nbsp;<input type="text" name="dob" value="<?=DOB_FORMAT_STRING;?>" maxlength="10">&nbsp;<?=ENTRY_DATE_OF_BIRTH_TEXT;?></font></td>
          </tr>
<?
   }
   $rowspan = 5+ACCOUNT_SUBURB+ACCOUNT_STATE;
?>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;<?=ENTRY_EMAIL_ADDRESS;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_SIZE;?>">&nbsp;<input type="text" name="email_address" maxlength="96">&nbsp;<?=ENTRY_EMAIL_ADDRESS_TEXT;?></font></td>
          </tr>
          <tr>
            <td colspan="2" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="<?=$rowspan;?>" nowrap><font face="<?=CATEGORY_FONT_FACE;?>" size="<?=CATEGORY_FONT_SIZE;?>" color="<?=CATEGORY_FONT_COLOR;?>"><?=CATEGORY_ADDRESS;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;<?=ENTRY_STREET_ADDRESS;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_SIZE;?>">&nbsp;<input type="text" name="street_address" maxlength="64">&nbsp;<?=ENTRY_STREET_ADDRESS_TEXT;?></font></td>
          </tr>
<?
  if (ACCOUNT_SUBURB) {
?>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;<?=ENTRY_SUBURB;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_SIZE;?>">&nbsp;<input type="text" name="suburb" maxlength="32">&nbsp;<?=ENTRY_SUBURB_TEXT;?></font></td>
          </tr>
<?
   }
?>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;<?=ENTRY_POST_CODE;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_SIZE;?>">&nbsp;<input type="text" name="postcode" maxlength="8">&nbsp;<?=ENTRY_POST_CODE_TEXT;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;<?=ENTRY_CITY;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_SIZE;?>">&nbsp;<input type="text" name="city" maxlength="32">&nbsp;<?=ENTRY_CITY_TEXT;?></font></td>
          </tr>
<?
  if (ACCOUNT_STATE) {
?>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;<?=ENTRY_STATE;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_SIZE;?>">&nbsp;<input type="text" name="state" maxlength="32">&nbsp;<?=ENTRY_STATE_TEXT;?></font></td>
          </tr>
<?
   }
?>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;<?=ENTRY_COUNTRY;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_SIZE;?>">&nbsp;<select name="country"><option value="0"><?=PLEASE_SELECT;?></option>
<?
    $countries = tep_get_countries();
    for ($i=0; $i < sizeof($countries); $i++) {
      echo '<option value="' . $countries[$i]['countries_id'] . '">' . $countries[$i]['countries_name'] . '</option>';
    }
?></select>&nbsp;<?=ENTRY_COUNTRY_TEXT;?></font></td>
          </tr>
          <tr>
            <td colspan="2" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="3" nowrap><font face="<?=CATEGORY_FONT_FACE;?>" size="<?=CATEGORY_FONT_SIZE;?>" color="<?=CATEGORY_FONT_COLOR;?>"><?=CATEGORY_CONTACT;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;<?=ENTRY_TELEPHONE_NUMBER;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_SIZE;?>">&nbsp;<input type="text" name="telephone" maxlength="32">&nbsp;<?=ENTRY_TELEPHONE_NUMBER_TEXT;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;<?=ENTRY_FAX_NUMBER;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_SIZE;?>">&nbsp;<input type="text" name="fax" maxlength="32">&nbsp;<?=ENTRY_FAX_NUMBER_TEXT;?></font></td>
          </tr>
          <tr>
            <td colspan="2" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" valign="middle" colspan="2" rowspan="3" nowrap><font face="<?=CATEGORY_FONT_FACE;?>" size="<?=CATEGORY_FONT_SIZE;?>" color="<?=CATEGORY_FONT_COLOR;?>"><?=CATEGORY_PASSWORD;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;<?=ENTRY_PASSWORD;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_SIZE;?>">&nbsp;<input type="password" name="password" maxlength="12">&nbsp;<?=ENTRY_PASSWORD_TEXT;?></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<?=ENTRY_FONT_FACE;?>" size="<?=ENTRY_FONT_SIZE;?>" color="<?=ENTRY_FONT_SIZE;?>">&nbsp;<?=ENTRY_PASSWORD_CONFIRMATION;?>&nbsp;</font></td>
            <td nowrap><font face="<?=VALUE_FONT_FACE;?>" size="<?=VALUE_FONT_SIZE;?>" color="<?=VALUE_FONT_SIZE;?>">&nbsp;<input type="password" name="confirmation" maxlength="12">&nbsp;<?=ENTRY_PASSWORD_CONFIRMATION_TEXT;?></font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td align="right" nowrap><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><?=tep_image_submit(DIR_IMAGES . 'button_done.gif', '53', '24', '0', IMAGE_DONE);?>&nbsp;&nbsp;</font></td>
      </tr>
    </table><? if ($HTTP_GET_VARS['origin']) { echo '<input type="hidden" name="origin" value="' . $HTTP_GET_VARS['origin'] . '">'; } ?><? if ($HTTP_GET_VARS['connection']) { echo '<input type="hidden" name="connection" value="' . $HTTP_GET_VARS['connection'] . '">'; } ?></form></td>
<!-- body_text_eof //-->
    <td width="<?=BOX_WIDTH;?>" valign="top"><table border="0" width="<?=BOX_WIDTH;?>" cellspacing="0" cellpadding="0">
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
<? $include_file = DIR_INCLUDES . 'application_bottom.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
