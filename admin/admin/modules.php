<?php
/*
  $Id: modules.php,v 1.34 2002/01/20 16:06:22 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  switch ($HTTP_GET_VARS['set']) {
    case 'payment':
      $module_type = 'payment';
      $module_directory = DIR_FS_PAYMENT_MODULES;
      $module_key = 'MODULE_PAYMENT_INSTALLED';
      define('HEADING_TITLE', HEADING_TITLE_MODULES_PAYMENT);
      break;
    case 'shipping':
      $module_type = 'shipping';
      $module_directory = DIR_FS_SHIPPING_MODULES;
      $module_key = 'MODULE_SHIPPING_INSTALLED';
      define('HEADING_TITLE', HEADING_TITLE_MODULES_SHIPPING);
      break;
  }

  switch ($HTTP_GET_VARS['action']) {
    case 'save':
      while (list($key, $value) = each($HTTP_POST_VARS['configuration'])) {
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $value . "' where configuration_key = '" . $key . "'");
      }
      tep_redirect(tep_href_link(FILENAME_MODULES, 'set=' . $HTTP_GET_VARS['set'] . '&module=' . $HTTP_GET_VARS['module']));
      break;
    case 'install':
    case 'remove':
      $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
      $class = basename($HTTP_GET_VARS['module']);
      if (file_exists($module_directory . $class . $file_extension)) {
        include($module_directory . $class . $file_extension);
        $module = new $class;
        if ($HTTP_GET_VARS['action'] == 'install') {
          $module->install();
        } elseif ($HTTP_GET_VARS['action'] == 'remove') {
          $module->remove();
        }
      }
      tep_redirect(tep_href_link(FILENAME_MODULES, 'set=' . $HTTP_GET_VARS['set'] . '&module=' . $class));
      break;
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2"><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="tableHeading"><?php echo TABLE_HEADING_MODULES; ?></td>
                <td class="tableHeading" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="tableHeading" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3"><?php echo tep_draw_separator(); ?></td>
              </tr>
<?php
  $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
  $directory_array = array();
  if ($dir = @dir($module_directory)) {
    while ($file = $dir->read()) {
      if (!is_dir($module_directory . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          $directory_array[] = $file;
        }
      }
    }
    sort($directory_array);
    $dir->close();
  }

  $installed_modules = '';
  for ($i=0; $i<sizeof($directory_array); $i++) {
    $file = $directory_array[$i];

    include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/' . $module_type . '/' . $file);
    include($module_directory . $file);

    $class = substr($file, 0, strrpos($file, '.'));
    if (tep_class_exists($class)) {
      $module = new $class;
      if ($module->check() == '1') {
        $installed_modules .= (tep_not_null($installed_modules)) ? ';' . $file : $file;
      }

      if (((!$HTTP_GET_VARS['module']) || (@$HTTP_GET_VARS['module'] == $class)) && (!$mInfo)) {
        $module_info = array('code' => $module->code,
                             'title' => $module->title,
                             'description' => $module->description,
                             'status' => $module->check());
        $mInfo_array = tep_array_merge($module_info, $module->keys());
        $mInfo = new moduleInfo($mInfo_array);
      }

      if ( (is_object($mInfo)) && ($class == $mInfo->code) ) {
        if ($module->check() == '1') {
          echo '              <tr class="selectedRow" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_MODULES, 'set=' . $HTTP_GET_VARS['set'] . '&module=' . $class . '&action=edit') . '\'">' . "\n";
        } else {
          echo '              <tr class="selectedRow">' . "\n";
        }
      } else {
        echo '              <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_MODULES, 'set=' . $HTTP_GET_VARS['set'] . '&module=' . $class) . '\'">' . "\n";
      }
?>
                <td class="tableData"><?php echo $module->title; ?></td>
                <td class="tableData" align="right"><?php if ($module->check() == '1') { echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;<a href="' . tep_href_link(FILENAME_MODULES, 'set=' . $HTTP_GET_VARS['set'] . '&module=' . $class . '&action=remove') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>'; } else { echo '<a href="' . tep_href_link(FILENAME_MODULES, 'set=' . $HTTP_GET_VARS['set'] . '&module=' . $class . '&action=install') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10); } ?></td>
                <td class="tableData" align="right"><?php if ( (is_object($mInfo)) && ($class == $mInfo->code) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_MODULES, 'set=' . $HTTP_GET_VARS['set'] . '&module=' . $class) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?
    }
  }

  $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = '" . $module_key . "'");
  if (tep_db_num_rows($check_query)) {
    $check = tep_db_fetch_array($check_query);
    if ($check['configuration_value'] != $installed_modules) {
      tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $installed_modules . "', last_modified = now() where configuration_key = '" . $module_key . "'");
    }
  } else {
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Installed Modules', '" . $module_key . "', '" . $installed_modules . "', 'This is automatically updated. No need to edit.', '6', '0', now())");
  }
?>
              <tr>
                <td colspan="3"><?php echo tep_draw_separator(); ?></td>
              </tr>
              <tr>
                <td colspan="3" class="smallText"><?php echo TEXT_MODULE_DIRECTORY . ' ' . $module_directory; ?></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  switch ($HTTP_GET_VARS['action']) {
    case 'edit':
      $keys = '';
      reset($mInfo->keys);
      while (list($key, $value) = each($mInfo->keys)) {
        $keys .= '<b>' . $value['title'] . '</b><br>' . $value['description'] . '<br>';

        if ($value['set_function']) {
          eval('$keys .= ' . $value['set_function'] . "'" . $value['value'] . "', '" . $key . "');");
        } else {
          $keys .= tep_draw_input_field('configuration[' . $key . ']', $value['value']);
        }
        $keys .= '<br><br>';
      }
      $keys = substr($keys, 0, strrpos($keys, '<br><br>'));

      $heading[] = array('text' => '<b>' . $mInfo->title . '</b>');

      $contents = array('form' => tep_draw_form('modules', FILENAME_MODULES, 'set=' . $HTTP_GET_VARS['set'] . '&module=' . $HTTP_GET_VARS['module'] . '&action=save'));
      $contents[] = array('text' => $keys);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_MODULES, 'set=' . $HTTP_GET_VARS['set'] . '&module=' . $HTTP_GET_VARS['module']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      $heading[] = array('text' => '<b>' . $mInfo->title . '</b>');

      if ($mInfo->status == '1') {
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
        }
        $keys = substr($keys, 0, strrpos($keys, '<br><br>'));

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_MODULES, 'set=' . $HTTP_GET_VARS['set'] . '&module=' . $HTTP_GET_VARS['module'] . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a>');
        $contents[] = array('text' => '<br>' . $mInfo->description);
        $contents[] = array('text' => '<br>' . $keys);
      } else {
        $contents[] = array('text' => $mInfo->description);
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
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
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>