<?php
/*
  $Id: password_forgotten.php,v 1.32 2001/11/09 20:18:55 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PASSWORD_FORGOTTEN);

  if ($HTTP_GET_VARS['action'] == 'process') {
    $check_customer = tep_db_query("select customers_firstname, customers_lastname, customers_password, customers_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . $HTTP_POST_VARS['email_address'] . "'");
    if (tep_db_num_rows($check_customer)) {
      $check_customer_values = tep_db_fetch_array($check_customer);
      // Crypted password mods - create a new password, update the database and mail it to them
      $newpass = random_password(ENTRY_PASSWORD_MIN_LENGTH);
      $crpted_password = crypt_password($newpass);
      $sql = sprintf("UPDATE " . TABLE_CUSTOMERS . " SET customers_password = '%s' WHERE customers_id = %d", $crpted_password, $check_customer_values['customers_id']);
      tep_db_query($sql);
      
      tep_mail($check_customer_values['customers_firstname'] . " " . $check_customer_values['customers_lastname'], $HTTP_POST_VARS['email_address'], EMAIL_PASSWORD_REMINDER_SUBJECT, nl2br(sprintf(EMAIL_PASSWORD_REMINDER_BODY, $newpass)), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '');
      tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'NONSSL'));
    } else {
      tep_redirect(tep_href_link(FILENAME_PASSWORD_FORGOTTEN, 'email=nonexistent', 'NONSSL'));
    }
  } else {

  $location = ' : <a href="' . tep_href_link(FILENAME_LOGIN, '', 'NONSSL') . '" class="headerNavigation"> ' . NAVBAR_TITLE_1 . '</a> : <a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'NONSSL') . '" class="headerNavigation">' . NAVBAR_TITLE_2 . '</a>';
?>
<html>
<head>
<title><?php echo TITLE; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
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
            <td align="right">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'table_background_password_forgotten.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td width="100%"><form name="password_forgotten" method="post" action="<?php echo tep_href_link(FILENAME_PASSWORD_FORGOTTEN, 'action=process', 'NONSSL'); ?>"><br><table border="0" width="100%" cellspacing="0" cellpadding="3">
          <tr>
            <td align="right" class="main">&nbsp;<?php echo ENTRY_EMAIL_ADDRESS; ?>&nbsp;</td>
            <td class="main">&nbsp;<input type="text" name="email_address" maxlength="96" value="<?php if (($HTTP_COOKIE_VARS['email_address']) && ($HTTP_COOKIE_VARS['password'])) { echo $HTTP_COOKIE_VARS['email_address']; } ?>">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2"><br><?php echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td valign="top">&nbsp;&nbsp;<a href="<?php echo tep_href_link(FILENAME_LOGIN, '', 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
            <td align="right" valign="top"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?>&nbsp;&nbsp;</td>
          </tr>
<?php
  if ($HTTP_GET_VARS['email'] == 'nonexistent') {
    echo '          <tr>' . "\n";
    echo '            <td colspan="2" class="smallText">' .  TEXT_NO_EMAIL_ADDRESS_FOUND . '</td>' . "\n";
    echo '          </tr>' . "\n";
  }
?>
        </table></form></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?
  }

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
