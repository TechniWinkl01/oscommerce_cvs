<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_PASSWORD_FORGOTTEN; include(DIR_INCLUDES . 'include_once.php'); ?>
<?
  if ($HTTP_GET_VARS['action'] == 'process') {
    $check_customer = tep_db_query("select customers_password, customers_id from customers where customers_email_address = '" . $HTTP_POST_VARS['email_address'] . "'");
    if (tep_db_num_rows($check_customer)) {
      $check_customer_values = tep_db_fetch_array($check_customer);
      // Crypted password mods - create a new password, update the database and mail it to them
      $newpass = random_password(ENTRY_PASSWORD_MIN_LENGTH);
      $crpted_password = crypt_password($newpass);
      $sql = sprintf("UPDATE customers SET customers_password = '%s' WHERE customers_id = %d", $crpted_password, $check_customer_values['customers_id']);
      tep_db_query($sql);
      
      mail($HTTP_POST_VARS['email_address'], EMAIL_PASSWORD_REMINDER_SUBJECT, sprintf(EMAIL_PASSWORD_REMINDER_BODY, $newpass), 'From: ' . EMAIL_FROM);
      header('Location: ' . tep_href_link(FILENAME_LOGIN, '', 'NONSSL'));
      tep_exit();
    } else {
      header('Location: ' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, 'email=nonexistent', 'NONSSL'));
      tep_exit();
    }
  } else {
?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_LOGIN, '', 'NONSSL') . '" class="whitelink"> ' . NAVBAR_TITLE_1 . '</a> : <a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_2 . '</a>'; ?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_INCLUDES . 'header.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
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
            <td bgcolor="<? echo TOP_BAR_BACKGROUND_COLOR; ?>" width="100%" nowrap><?php echo FONT_STYLE_TOP_BAR; ?>&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><?php echo FONT_STYLE_HEADING; ?>&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_IMAGES . 'table_background_password_forgotten.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td width="100%"><form name="password_forgotten" method="post" action="<? echo tep_href_link(FILENAME_PASSWORD_FORGOTTEN, 'action=process', 'NONSSL'); ?>"><br><table border="0" width="100%" cellspacing="0" cellpadding="3">
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_MAIN; ?>&nbsp;<? echo ENTRY_EMAIL_ADDRESS; ?>&nbsp;</font></td>
            <td nowrap><?php echo FONT_STYLE_MAIN; ?>&nbsp;<input type="text" name="email_address" maxlength="96" value="<? if (($HTTP_COOKIE_VARS['email_address']) && ($HTTP_COOKIE_VARS['password'])) { echo $HTTP_COOKIE_VARS['email_address']; } ?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td colspan="2"><br><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td align="right" valign="top" colspan="2" nowrap><? echo tep_image_submit(DIR_IMAGES . 'button_email_me.gif', IMAGE_EMAIL_ME); ?>&nbsp;&nbsp;</td>
          </tr>
<?
  if ($HTTP_GET_VARS['email'] == 'nonexistent') {
    echo '          <tr>' . "\n";
    echo '            <td colspan="2"><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">' . TEXT_NO_EMAIL_ADDRESS_FOUND . '</font></td>' . "\n";
    echo '          </tr>' . "\n";
  }
?>
        </table></form></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
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
  }
?>
<? $include_file = DIR_INCLUDES . 'application_bottom.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
