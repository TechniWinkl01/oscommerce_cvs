<?php
/*
  $Id: modules.php,v 1.31 2001/12/28 13:51:57 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  switch ($HTTP_GET_VARS['set']) {
    case 'payment'  : $module_type = 'payment';
                      $module_directory = DIR_FS_PAYMENT_MODULES;
                      $module_key = 'MODULE_PAYMENT_INSTALLED';
                      $heading_title = HEADING_TITLE_MODULES_PAYMENT;
                      break;
    case 'shipping' : $module_type = 'shipping';
                      $module_directory = DIR_FS_SHIPPING_MODULES;
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
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body>
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td class="topBarTitle">&nbsp;<?php echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<?php echo $heading_title; ?>&nbsp;</td>
            <td align="right">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2"><?php echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="tableHeading">&nbsp;<?php echo TABLE_HEADING_CONFIGURATION_TITLE; ?>&nbsp;</td>
                <td class="tableHeading" align="right">&nbsp;<?php echo TABLE_HEADING_STATUS; ?>&nbsp;</td>
                <td class="tableHeading" align="right">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
  $installed_modules = '';
  $dir = opendir($module_directory);
  $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
  if ($dir) {
    while ($file = readdir($dir)) {
      if (!is_dir($module_directory . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          $directory_array[] = $file;
        }
      }
    }
    sort($directory_array);

    for ($files=0; $files<sizeof($directory_array); $files++) {
      $entry = $directory_array[$files];

      $check = 0;
      include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/' . $module_type . '/' . $entry);
      include($module_directory . $entry);
      $class = substr($entry, 0, strrpos($entry, '.'));
      if (tep_class_exists($class)) {
        $module = new $class;
        $check = $module->check();
        if ($check == '1') {
          $installed_modules .= ($installed_modules) ? ';' . $entry : $entry;
        }

        if (((!$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['info'] == $class)) && (!$mInfo)) {
          $module_info = array('code' => $module->code, 'title' => $module->title, 'description' => $module->description, 'status' => $module->check());
          $mInfo_array = tep_array_merge($module_info, $module->keys());
          $mInfo = new moduleInfo($mInfo_array);
        }

        if ($mInfo && ($class == $mInfo->code) ) {
          echo '              <tr class="selectedRow">' . "\n";
        } else {
          echo '              <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_MODULES, tep_get_all_get_params(array('info', 'action')) . 'info=' . $class, 'NONSSL') . '\'">' . "\n";
        }
?>
                <td class="smallText">&nbsp;<?php echo $entry; ?>&nbsp;</td>
                <td align="right" class="smallText">&nbsp;
<?php
        if ($module->check() == '1') {
          echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_MODULES, tep_get_all_get_params(array('action', 'module')) . 'action=remove&module=' . $entry, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
        } else {
          echo '<a href="' . tep_href_link(FILENAME_MODULES, tep_get_all_get_params(array('action', 'module')) . 'action=install&module=' . $entry, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
        }
?>&nbsp;</td>
<?php
        if ($mInfo && ($class == $mInfo->code)) {
?>
                <td align="right" class="smallText">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); ?>&nbsp;</td>
<?php
        } else {
?>
                <td align="right" class="smallText">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_MODULES, tep_get_all_get_params(array('info', 'action')) . 'info=' . $class, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; ?>&nbsp;</td>
<?php
        }
?>

              </tr>
<?
      }
    }
  }
  closedir($dir);

  $check = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = '" . $module_key . "'");
  if (tep_db_num_rows($check)) {
    $check_values = tep_db_fetch_array($check);
    if ($check_values['configuration_value'] <> $installed_modules) {
      tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $installed_modules . "' where configuration_key = '" . $module_key . "'");
    }
  } else {
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Installed Modules', '" . $module_key . "', '" . $installed_modules . "', 'This is automatically updated. No need to edit.', '6', '0', now())");
  }
?>
              <tr>
                <td colspan="3"><?php echo tep_black_line(); ?></td>
              </tr>
              <tr>
                <td colspan="3" class="smallText">Module Directory: <?php echo $module_directory; ?></td>
              </tr>
            </table></td>
            <td width="25%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
  $info_box_contents = array();
  if ($mInfo) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $mInfo->title . '</b>&nbsp;');
?>
              <tr class="boxHeading">
                <td><?php new infoBoxHeading($info_box_contents); ?></td>
              </tr>
              <tr class="boxHeading">
                <td><?php echo tep_black_line(); ?></td>
              </tr>
<?php
  if ($HTTP_GET_VARS['action'] == 'edit') {
    $keys = '';
    reset($mInfo->keys);
    while (list($key, $value) = each($mInfo->keys)) {
      $keys .= '<b>' . $value['title'] . '</b><br>' . $value['description'] . '<br>';
      if ($value['set_function']) {
        eval('$keys .= ' . $value['set_function'] . "'" . $key . "', '" . $value['value'] . "');");
      } else {
        $keys .= '<input type="text" name="configuration[' . $key . ']" value="' . $value['value'] . '">';
      }
      $keys .= '<br><br>';
    }

    $form = '<form name="module" action="' . tep_href_link(FILENAME_MODULES, tep_get_all_get_params(array('action')) . 'action=save&info=' . $HTTP_GET_VARS['info'], 'NONSSL') . '" method="post">' . "\n";

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => $keys);
    $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_WS_IMAGES . 'button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_MODULES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
  } else {
    $info_box_contents = array();
    if ($mInfo->status == '1') {
      $field_set = 0;
      $keys = '';
      reset($mInfo->keys);
      while (list(, $value) = each($mInfo->keys)) {
        $keys .= '<b>' . $value['title'] . '</b><br>';
        if ($value['use_function']) {
          $use_function = $value['use_function'];
          $keys .= $use_function($value['value']);
        } else {
          $keys .= $value['value'];
        }
        $keys .= '<br><br>';
        if ( ($value['title'] != '') && ($value['value'] != '')) $field_set = 1;
      }
      $keys = substr($keys, 0, strrpos($keys, '<br><br>'));
      if ($field_set == '1') {
        $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_MODULES, tep_get_all_get_params(array('action')) . 'action=edit', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_edit.gif', IMAGE_EDIT) . '</a>');
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . $mInfo->description);
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . $keys);
      }
    } else {
      $info_box_contents[] = array('align' => 'left', 'text' => $mInfo->description);
    }
  }
?>
              <tr><?php echo $form; ?>
                <td class="box"><?php new infoBox($info_box_contents); ?></td>
              <?php if ($form) echo '</form>'; ?></tr>
              <tr>
                <td class="box"><?php echo tep_black_line(); ?></td>
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
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>