<? include('includes/application_top.php'); ?>
<?
  if ($HTTP_GET_VARS['install']) {
    $payment_action = 'PM_INSTALL';
    include(DIR_PAYMENT_MODULES . $install);
    header('Location: ' . tep_href_link(FILENAME_PAYMENT_MODULES, '', 'NONSSL')); 
    tep_exit();
  } elseif ($HTTP_GET_VARS['remove']) {
    $payment_action = 'PM_REMOVE';
    include(DIR_PAYMENT_MODULES . $remove);
    header('Location: ' . tep_href_link(FILENAME_PAYMENT_MODULES, '', 'NONSSL')); 
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
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<? echo HEADING_FONT_FACE; ?>" size="<? echo HEADING_FONT_SIZE; ?>" color="<? echo HEADING_FONT_COLOR; ?>">&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_CATALOG . 'images/pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', ''); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2"><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_CONFIGURATION_TITLE; ?>&nbsp;</b></font></td>
                <td align="center" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_CONFIGURATION_VALUE; ?>&nbsp;</b></font></td>
                <td align="center" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_ACTION; ?>&nbsp;</b></font></td>
              </tr>
              <tr>
                <td colspan="3"><? echo tep_black_line(); ?></td>
              </tr>
<?
  $installed_modules = '';
  $dir = dir(DIR_PAYMENT_MODULES);
  if ($dir) {
    while($entry=$dir->read()) {
      if (eregi('.php[34]*$', $entry)) {
        $check = 0;
        $payment_action = 'PM_CHECK';
        include(DIR_PAYMENT_MODULES . $entry);
        if ($check > 1) {
          $installed_modules .= ($installed_modules)?';' . $entry:$entry;
        }
        if ($check) {
?>
              <tr bgcolor="#d8e1eb" onmouseover="this.style.background='#cc9999'" onmouseout="this.style.background='#d8e1eb'">
                <td nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $entry; ?>&nbsp;</font></td>
                <td align="center" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo ($check == 1)?'No':'Yes'; ?>&nbsp;</font></td>
                <td align="center" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<a href="<? echo ($check == 1)?tep_href_link(FILENAME_PAYMENT_MODULES, 'install=' . $entry, 'NONSSL'):tep_href_link(FILENAME_PAYMENT_MODULES, 'remove=' . $entry, 'NONSSL'); ?>"><? echo tep_image(DIR_IMAGES . 'icon_info.gif', '13', '13', '0', IMAGE_INSTALL_REMOVE); ?></a>&nbsp;</font></td>
              </tr>
<?
        }
      }
    }
    $dir->close();
  }
  
  $check = tep_db_query("select configuration_value from configuration where configuration_key = 'PAYMENT_MODULES'");
  if (tep_db_num_rows($check) > 0) {
    list($check) = tep_db_fetch_array($check);
    if ($check <> $installed_modules) {
      tep_db_query("UPDATE configuration set configuration_value = '$installed_modules' where configuration_key = 'PAYMENT_MODULES'");
    }
  } else {
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Payment Modules', 'PAYMENT_MODULES', '$installed_modules', 'This is automatically updated. No need to edit.', '6', '0', now())");
  }
?>
              <tr>
                <td colspan="3"><? echo tep_black_line(); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
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
