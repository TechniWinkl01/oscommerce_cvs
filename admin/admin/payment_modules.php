<? include('includes/application_top.php'); ?>
<?
  if ($HTTP_GET_VARS['install']) {
    include(DIR_FS_PAYMENT_MODULES . $install);
    $class = substr($install, 0, -4);
    $payment_module = new $class;
    $payment_module->install();
    header('Location: ' . tep_href_link(FILENAME_PAYMENT_MODULES, '', 'NONSSL')); tep_exit();
  } elseif ($HTTP_GET_VARS['remove']) {
    include(DIR_FS_PAYMENT_MODULES . $remove);
    $class = substr($remove, 0, -4);
    $payment_module = new $class;
    $payment_module->remove();
    header('Location: ' . tep_href_link(FILENAME_PAYMENT_MODULES, '', 'NONSSL')); tep_exit();
  }
?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_WS_INCLUDES . 'header.php';  include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_WS_INCLUDES . 'column_left.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
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
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_WS_CATALOG . 'images/pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', ''); ?>&nbsp;</td>
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
                <td align="right" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_STATUS; ?>&nbsp;</b></font></td>
              </tr>
              <tr>
                <td colspan="2"><? echo tep_black_line(); ?></td>
              </tr>
<?
  $installed_modules = '';
  $dir = dir(DIR_FS_PAYMENT_MODULES);
  if ($dir) {
    while($entry = $dir->read()) {
      if (eregi('.php[34]*$', $entry)) {
        $check = 0;
        include(DIR_FS_PAYMENT_MODULES . $entry);
        $class = substr($entry, 0, -4);
        $payment_module = new $class;
        $check = $payment_module->check();
        if ($check > 1) {
          $installed_modules .= ($installed_modules) ? ';' . $entry : $entry;
        }
        if ($check) {
?>
              <tr bgcolor="#d8e1eb" onmouseover="this.style.background='#cc9999'" onmouseout="this.style.background='#d8e1eb'">
                <td nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $entry; ?>&nbsp;</font></td>
                <td align="right" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;
<?
          if ($check != '1') {
            echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', '10', '10', '0', 'Active') . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_PAYMENT_MODULES, 'remove=' . $entry, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', '10', '10', '0', 'Set Inactive') . '</a>';
          } else {
            echo '<a href="' . tep_href_link(FILENAME_PAYMENT_MODULES, 'install=' . $entry, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', '10', '10', '0', 'Set Active') . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', '10', '10', '0', 'Inactive');
          }
?>&nbsp;</font></td>
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
      tep_db_query("update configuration set configuration_value = '" . $installed_modules . "' where configuration_key = 'PAYMENT_MODULES'");
    }
  } else {
    tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Installed Payment Modules', 'PAYMENT_MODULES', '" . $installed_modules . "', 'This is automatically updated. No need to edit.', '6', '0', now())");
  }
?>
              <tr>
                <td colspan="2"><? echo tep_black_line(); ?></td>
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
<? $include_file = DIR_WS_INCLUDES . 'footer.php';  include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<? $include_file = DIR_WS_INCLUDES . 'application_bottom.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
