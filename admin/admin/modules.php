<?
  require('includes/application_top.php');

  switch ($HTTP_GET_VARS['set']) {
    case 'payment'  : $module_directory = DIR_FS_PAYMENT_MODULES;
                      $module_key = 'MODULE_PAYMENT_INSTALLED';
                      $heading_title = HEADING_TITLE_MODULES_PAYMENT;
                      break;
    case 'shipping' : $module_directory = DIR_FS_SHIPPING_MODULES;
                      $module_key = 'MODULE_SHIPPING_INSTALLED';
                      $heading_title = HEADING_TITLE_MODULES_SHIPPING;
                      break;
  }

  if ($HTTP_GET_VARS['action']) {
    switch ($HTTP_GET_VARS['action']) {
      case 'save' :    while (list($key, $value) = each($HTTP_POST_VARS['configuration'])) {
                         tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $value . "' where configuration_key = '" . $key . "'");
                       }
                       header('Location: ' . tep_href_link(FILENAME_MODULES, 'set=' . $HTTP_GET_VARS['set'] . '&info=' . $HTTP_GET_VARS['info'], 'NONSSL')); tep_exit();
                       break;
      case 'install' : include($module_directory . $HTTP_GET_VARS['module']);
                       $class = substr($HTTP_GET_VARS['module'], 0, strrpos($HTTP_GET_VARS['module'], '.'));
                       $module = new $class;
                       $module->install();
                       header('Location: ' . tep_href_link(FILENAME_MODULES, 'set=' . $HTTP_GET_VARS['set'] . '&info=' . substr($HTTP_GET_VARS['module'], 0, strrpos($HTTP_GET_VARS['module'], '.')), 'NONSSL')); tep_exit();
                       break;
      case 'remove' :  include($module_directory . $HTTP_GET_VARS['module']);
                       $class = substr($HTTP_GET_VARS['module'], 0, strrpos($HTTP_GET_VARS['module'], '.'));
                       $module = new $class;
                       $module->remove();
                       header('Location: ' . tep_href_link(FILENAME_MODULES, 'set=' . $HTTP_GET_VARS['set'] . '&info=' . substr($HTTP_GET_VARS['module'], 0, strrpos($HTTP_GET_VARS['module'], '.')), 'NONSSL')); tep_exit();
                       break;
    }
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
            <td nowrap><font face="<? echo HEADING_FONT_FACE; ?>" size="<? echo HEADING_FONT_SIZE; ?>" color="<? echo HEADING_FONT_COLOR; ?>">&nbsp;<? echo $heading_title; ?>&nbsp;</font></td>
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
                <td align="right" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_ACTION; ?>&nbsp;</b></font></td>
              </tr>
              <tr>
                <td colspan="3"><? echo tep_black_line(); ?></td>
              </tr>
<?
  $installed_modules = '';
  $dir = opendir($module_directory);

  if ($dir) {
    while ($file = readdir($dir)) {
      if (!is_dir($file)) {
        $directory_array[] = $file;
      }
    }
    sort($directory_array);

    for ($files=0; $files<sizeof($directory_array); $files++) {
      $entry = $directory_array[$files];

      $check = 0;
      include($module_directory . $entry);
      $class = substr($entry, 0, strrpos($entry, '.'));
      if (tep_class_exists($class)) {
        $module = new $class;
        $check = $module->check();
        if ($check == '1') {
          $installed_modules .= ($installed_modules) ? ';' . $entry : $entry;
        }

        if (((!$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['info'] == $class)) && (!$mInfo)) {
          $module_info = array('code' => $module->code);
          $mInfo_array = tep_array_merge($module_info, $module->keys());
          $mInfo = new moduleInfo($mInfo_array);
        }

        if ($mInfo && ($class == $mInfo->code) ) {
          echo '              <tr bgcolor="#b0c8df">' . "\n";
        } else {
          echo '              <tr bgcolor="#d8e1eb" onmouseover="this.style.background=\'#cc9999\';this.style.cursor=\'hand\'" onmouseout="this.style.background=\'#d8e1eb\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_MODULES, tep_get_all_get_params(array('info', 'action')) . 'info=' . $class, 'NONSSL') . '\'">' . "\n";
        }
?>
                <td nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $entry; ?>&nbsp;</font></td>
                <td align="right" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;
<?
        if ($module->check() == '1') {
          echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', '10', '10', '0', 'Active') . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_MODULES, tep_get_all_get_params(array('action', 'module')) . 'action=remove&module=' . $entry, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', '10', '10', '0', 'Set Inactive') . '</a>';
        } else {
          echo '<a href="' . tep_href_link(FILENAME_MODULES, tep_get_all_get_params(array('action', 'module')) . 'action=install&module=' . $entry, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', '10', '10', '0', 'Set Active') . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', '10', '10', '0', 'Inactive');
        }
?>&nbsp;</font></td>
<?
        if ($mInfo && ($class == $mInfo->code)) {
?>
                    <td align="right" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', 13, 13, 0, ''); ?>&nbsp;</font></td>
<?
        } else {
?>
                    <td align="right" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_MODULES, tep_get_all_get_params(array('info', 'action')) . 'info=' . $class, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', '13', '13', '0', IMAGE_ICON_INFO) . '</a>'; ?>&nbsp;</font></td>
<?
        }
?>

                <td></td>
              </tr>
<?
      }
    }
  }
  closedir($dir);

  $check = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = '" . $module_key . "'");
  if (tep_db_num_rows($check)) {
    list($check) = tep_db_fetch_array($check);
    if ($check <> $installed_modules) {
      tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $installed_modules . "' where configuration_key = '" . $module_key . "'");
    }
  } else {
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Installed Modules', '" . $module_key . "', '" . $installed_modules . "', 'This is automatically updated. No need to edit.', '6', '0', now())");
  }
?>
              <tr>
                <td colspan="3"><? echo tep_black_line(); ?></td>
              </tr>
              <tr>
                <td colspan="3"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">Module Directory: <? echo $module_directory; ?></font></td>
              </tr>
            </table></td>
            <td width="25%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?
  $info_box_contents = array();
  if ($mInfo) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $mInfo->code . '</b>&nbsp;');
?>
              <tr bgcolor="#81a2b6">
                <td>
                  <? new infoBoxHeading($info_box_contents); ?>
                </td>
              </tr>
              <tr bgcolor="#81a2b6">
                <td><? echo tep_black_line(); ?></td>
              </tr>
<?
  if ($HTTP_GET_VARS['action'] == 'edit') {
    $keys = '';
    reset($mInfo->keys);
    while (list($key, $value) = each($mInfo->keys)) {
      $keys .= '<b>' . $value['title'] . '</b><br>' . $value['description'] . '<br><input type="text" name="configuration[' . $key . ']" value="' . $value['value'] . '"><br><br>';
    }

    $form = '<form name="module" action="' . tep_href_link(FILENAME_MODULES, tep_get_all_get_params(array('action')) . 'action=save&info=' . $HTTP_GET_VARS['info'], 'NONSSL') . '" method="post">' . "\n";

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => $keys);
    $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_WS_IMAGES . 'button_update.gif', '66', '20', '0', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_MODULES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>');
  } else {
    $field_set = 0;
    $keys = '';
    reset($mInfo->keys);
    while (list(, $value) = each($mInfo->keys)) {
      $keys .= '<b>' . $value['title'] . '</b><br>' . $value['value'] . '<br><br>';
      if ( ($value['title'] != '') && ($value['value'] != '')) $field_set = 1;
    }
    $keys = substr($keys, 0, strrpos($keys, '<br><br>'));
    $info_box_contents = array();
    if ($field_set == '1') {
      $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_MODULES, tep_get_all_get_params(array('action')) . 'action=edit', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_edit.gif', '66', '20', '0', IMAGE_EDIT) . '</a>');
      $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . $keys);
    }
  }
?>
              <tr bgcolor="#b0c8df"><? echo $form; ?>
                <td>
                  <? new infoBox($info_box_contents); ?>
                </td>
              <? if ($form) echo '</form>'; ?></tr>
              <tr bgcolor="#b0c8df">
                <td><? echo tep_black_line(); ?></td>
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
