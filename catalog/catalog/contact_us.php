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
            <td bgcolor="<? echo TOP_BAR_BACKGROUND_COLOR; ?>" width="100%" nowrap><?php echo FONT_STYLE_TOP_BAR; ?>&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
<? 
  if ($HTTP_GET_VARS['action'] == 'success') {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td nowrap><? echo tep_image(DIR_IMAGES . 'table_background_man_on_board.gif', HEADING_TITLE); ?></td>
            <td valign="top"><div align="center"><br><?php echo FONT_STYLE_HEADING; ?><? echo SUB_BAR_TITLE; ?>&nbsp;</font></div><br><?php echo FONT_STYLE_MAIN; ?><? echo TEXT_SUCCESS; ?></font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;</font></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td align="right" nowrap><br><a href="<? echo tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><? echo tep_image(DIR_IMAGES . 'button_main_menu.gif', IMAGE_MAIN_MENU); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;</td>
      </tr>
<?
  } else {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><?php echo FONT_STYLE_HEADING; ?>&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_IMAGES . 'table_background_contact_us.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr bgcolor="<? echo SUB_BAR_BACKGROUND_COLOR; ?>">
            <td nowrap><?php echo FONT_STYLE_SUB_BAR; ?>&nbsp;<? echo SUB_BAR_TITLE; ?>&nbsp;</font></td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
        </td></table>
      </tr>
      <tr>
        <td><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;</font></td>
      </tr>
      <tr>
        <td><form action="<? echo tep_href_link(FILENAME_CONTACT_US, 'action=send', 'NONSSL'); ?>" method="post"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="2"><?php echo FONT_STYLE_FIELD_ENTRY; ?>&nbsp;</font></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_MAIN; ?><? echo ENTRY_NAME; ?></font></td>
            <td><?php echo FONT_STYLE_MAIN; ?><input name="name" maxlength="32"></font></td>
          </tr>
          <tr>
            <td align="right" nowrap><?php echo FONT_STYLE_MAIN; ?><? echo ENTRY_EMAIL; ?></font></td>
            <td><?php echo FONT_STYLE_MAIN; ?><input name="email" maxlength="96"></font></td>
          </tr>
          <tr>
            <td valign="top" align="right" nowrap><?php echo FONT_STYLE_MAIN; ?><? echo ENTRY_ENQUIRY; ?></font></td>
            <td><?php echo FONT_STYLE_MAIN; ?><textarea name="enquiry" wrap="soft" cols="40" rows="15"></textarea></font></td>
          </tr>
          <tr>
            <td><?php echo FONT_STYLE_MAIN; ?>&nbsp;</font></td>
            <td><?php echo FONT_STYLE_MAIN; ?><? echo tep_image_submit(DIR_IMAGES . 'button_process.gif', IMAGE_SUBMIT); ?>&nbsp;&nbsp;</font></td>
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
