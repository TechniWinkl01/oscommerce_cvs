<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_CONTACT_US; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_CONTACT_US, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE . '</a>'; ?>
<?
  if ($HTTP_GET_VARS['action'] == 'send') {
    mail(STORE_OWNER_EMAIL_ADDRESS, EMAIL_SUBJECT, $HTTP_POST_VARS['enquiry'], "From: " . $HTTP_POST_VARS['name'] . ' <' . $HTTP_POST_VARS['email'] . '>');
    Header('Location: ' . tep_href_link(FILENAME_CONTACT_US, 'action=success', 'NONSSL'));
    tep_exit();
  }
?>
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
            <td bgcolor="<? echo TOP_BAR_BACKGROUND_COLOR; ?>" width="100%" nowrap><font face="<? echo TOP_BAR_FONT_FACE; ?>" size="<? echo TOP_BAR_FONT_SIZE; ?>" color="<? echo TOP_BAR_FONT_COLOR; ?>">&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
<? 
  if ($HTTP_GET_VARS['action'] == 'success') {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td nowrap><? echo tep_image(DIR_IMAGES . 'table_background_man_on_board.gif', '175', '198', '0', HEADING_TITLE); ?></td>
            <td valign="top"><div align="center"><br><font face="<? echo HEADING_FONT_FACE; ?>" size="<? echo HEADING_FONT_SIZE; ?>" color="<? echo HEADING_FONT_COLOR; ?>"><? echo SUB_BAR_TITLE; ?>&nbsp;</font></div><br><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><? echo TEXT_SUCCESS; ?></font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><font face="<? echo ENTRY_FONT_FACE; ?>" size="<? echo ENTRY_FONT_SIZE; ?>" color="<? echo ENTRY_FONT_COLOR; ?>">&nbsp;</font></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td align="right" nowrap><br><a href="<? echo tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><? echo tep_image(DIR_IMAGES . 'button_main_menu.gif', '112', '24', '0', IMAGE_MAIN_MENU); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;</td>
      </tr>
<?
  } else {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<? echo HEADING_FONT_FACE; ?>" size="<? echo HEADING_FONT_SIZE; ?>" color="<? echo HEADING_FONT_COLOR; ?>">&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_IMAGES . 'table_background_contact_us.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr bgcolor="<? echo SUB_BAR_BACKGROUND_COLOR; ?>">
            <td nowrap><font face="<? echo SUB_BAR_FONT_FACE; ?>" size="<? echo SUB_BAR_FONT_SIZE; ?>" color="<? echo SUB_BAR_FONT_COLOR; ?>">&nbsp;<? echo SUB_BAR_TITLE; ?>&nbsp;</font></td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
        </td></table>
      </tr>
      <tr>
        <td><font face="<? echo ENTRY_FONT_FACE; ?>" size="<? echo ENTRY_FONT_SIZE; ?>" color="<? echo ENTRY_FONT_COLOR; ?>">&nbsp;</font></td>
      </tr>
      <tr>
        <td><form action="<? echo tep_href_link(FILENAME_CONTACT_US, 'action=send', 'NONSSL'); ?>" method="post"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="2"><font face="<? echo ENTRY_FONT_FACE; ?>" size="<? echo ENTRY_FONT_SIZE; ?>" color="<? echo ENTRY_FONT_COLOR; ?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><? echo ENTRY_NAME; ?></font></td>
            <td><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><input name="name" maxlength="32"></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><? echo ENTRY_EMAIL; ?></font></td>
            <td><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><input name="email" maxlength="96"></font></td>
          </tr>
          <tr>
            <td valign="top" align="right" nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><? echo ENTRY_ENQUIRY; ?></font></td>
            <td><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><textarea name="enquiry" wrap="soft" cols="40" rows="15"></textarea></font></td>
          </tr>
          <tr>
            <td><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;</font></td>
            <td><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><? echo tep_image_submit(DIR_IMAGES . 'button_process.gif', 78, 24, 0, IMAGE_SUBMIT); ?>&nbsp;&nbsp;</font></td>
          </tr>
        </table></form></td>
<?
  }
?>
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
<? $include_file = DIR_INCLUDES . 'application_bottom.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
