<?php
/*
  $Id: services.php,v 1.1 2004/04/13 08:19:16 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  $installed = explode(';', MODULE_SERVICES_INSTALLED);

  $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));

  if (tep_not_null($action)) {
    switch ($action) {
      case 'save':
        foreach ($HTTP_POST_VARS['configuration'] as $key => $value) {
          tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $value . "' where configuration_key = '" . $key . "'");
        }

        osC_Cache::clear('configuration');

        tep_redirect(tep_href_link(FILENAME_SERVICES, 'service=' . $HTTP_GET_VARS['service']));
        break;
      case 'remove':
        $service = tep_db_prepare_input($HTTP_GET_VARS['service']);

        if (($key = array_search($service, $installed)) !== false) {
          include(DIR_FS_CATALOG_MODULES . '/services/' . $service . $file_extension);
          $class = 'osC_Services_' . $service;
          $module = new $class;
          $module->remove();

          unset($installed[$key]);

          tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . implode(';', $installed) . "' where configuration_key = 'MODULE_SERVICES_INSTALLED'");

          osC_Cache::clear('configuration');
        }

        tep_redirect(tep_href_link(FILENAME_SERVICES, 'service=' . $HTTP_GET_VARS['service']));
        break;
      case 'install':
        $service = tep_db_prepare_input($HTTP_GET_VARS['service']);

        if (array_search($service, $installed) === false) {
          include(DIR_FS_CATALOG_MODULES . '/services/' . $service . $file_extension);
          $class = 'osC_Services_' . $service;
          $module = new $class;
          $module->install();

          if (isset($module->depends)) {
            if (is_string($module->depends) && (($key = array_search($module->depends, $installed)) !== false)) {
              if (isset($installed[$key+1])) {
                array_splice($installed, $key+1, 0, $service);
              } else {
                $installed[] = $service;
              }
            } elseif (is_array($module->depends)) {
              foreach ($module->depends as $depends_module) {
                if (($key = array_search($depends_module, $installed)) !== false) {
                  if (!isset($array_position) || ($key > $array_position)) {
                    $array_position = $key;
                  }
                }
              }

              if (isset($array_position)) {
                array_splice($installed, $array_position+1, 0, $service);
              } else {
                $installed[] = $service;
              }
            }
          } elseif (isset($module->preceeds)) {
            if (is_string($module->preceeds)) {
              if ((($key = array_search($module->preceeds, $installed)) !== false)) {
                array_splice($installed, $key, 0, $service);
              } else {
                $installed[] = $service;
              }
            } elseif (is_array($module->preceeds)) {
              foreach ($module->preceeds as $preceeds_module) {
                if (($key = array_search($preceeds_module, $installed)) !== false) {
                  if (!isset($array_position) || ($key < $array_position)) {
                    $array_position = $key;
                  }
                }
              }

              if (isset($array_position)) {
                array_splice($installed, $array_position, 0, $service);
              } else {
                $installed[] = $service;
              }
            }
          } else {
            $installed[] = $service;
          }

          tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . implode(';', $installed) . "' where configuration_key = 'MODULE_SERVICES_INSTALLED'");

          osC_Cache::clear('configuration');
        }

        tep_redirect(tep_href_link(FILENAME_SERVICES, 'service=' . $HTTP_GET_VARS['service']));
        break;
    }
  }

  $directory_array = array();
  if ($dir = @dir(DIR_FS_CATALOG_MODULES . '/services/')) {
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
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
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
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_SERVICES; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  foreach ($directory_array as $service_module) {
    include(DIR_FS_CATALOG_MODULES . '/services/' . $service_module);
    $class_code = substr($service_module, 0, strrpos($service_module, '.'));
    $class = 'osC_Services_' . $class_code;
    $module = new $class;

    if ((!isset($HTTP_GET_VARS['service']) || (isset($HTTP_GET_VARS['service']) && ($HTTP_GET_VARS['service'] == $class_code))) && !isset($sInfo)) {
      $module_info = array('code' => $class_code,
                           'title' => $module->title,
                           'description' => $module->description,
                           'status' => in_array($class_code, $installed),
                           'uninstallable' => $module->uninstallable,
                           'preceeds' => $module->preceeds);

      $module_keys = $module->keys();
      $keys_extra = array();

      if (is_array($module_keys) && (sizeof($module_keys) > 0)) {
        foreach ($module_keys as $key) {
          $key_value_query = tep_db_query("select configuration_title, configuration_value, configuration_description, use_function, set_function from " . TABLE_CONFIGURATION . " where configuration_key = '" . $key . "'");
          $key_value = tep_db_fetch_array($key_value_query);

          $keys_extra[$key]['title'] = $key_value['configuration_title'];
          $keys_extra[$key]['value'] = $key_value['configuration_value'];
          $keys_extra[$key]['description'] = $key_value['configuration_description'];
          $keys_extra[$key]['use_function'] = $key_value['use_function'];
          $keys_extra[$key]['set_function'] = $key_value['set_function'];
        }
      }

      $module_info['keys'] = $keys_extra;

      $sInfo = new objectInfo($module_info);
    }

    if (isset($sInfo) && is_object($sInfo) && ($class_code == $sInfo->code) ) {
      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
    }
?>
                <td class="dataTableContent" onclick="document.location.href='<?php echo tep_href_link(FILENAME_SERVICES, 'service=' . $class_code); ?>'"><?php echo (isset($module->title) ? $module->title : $class_code); ?></td>
                <td class="dataTableContent" align="center">
<?php
      if (in_array($class_code, $installed)) {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;' . ($module->uninstallable ? tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) : tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', 10, 10));
      } else {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?>
                </td>
                <td class="dataTableContent" align="right"><?php if (isset($sInfo) && is_object($sInfo) && ($class_code == $sInfo->code)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_SERVICES, 'service=' . $class_code) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="3" class="smallText"><?php echo TEXT_MODULE_DIRECTORY . ' ' . DIR_FS_CATALOG_MODULES . 'services/'; ?></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'edit':
      $keys = '';
      foreach ($sInfo->keys as $key => $value) {
        $keys .= '<b>' . $value['title'] . '</b><br>' . $value['description'] . '<br>';

        if ($value['set_function']) {
          eval('$keys .= ' . $value['set_function'] . "'" . $value['value'] . "', '" . $key . "');");
        } else {
          $keys .= tep_draw_input_field('configuration[' . $key . ']', $value['value']);
        }
        $keys .= '<br><br>';
      }
      $keys = substr($keys, 0, strrpos($keys, '<br><br>'));

      $heading[] = array('text' => '<b>' . $sInfo->title . '</b>');

      $contents = array('form' => tep_draw_form('modules', FILENAME_SERVICES, '&service=' . $HTTP_GET_VARS['service'] . '&action=save'));
      $contents[] = array('text' => $keys);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_SERVICES, 'service=' . $HTTP_GET_VARS['service']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($sInfo) && is_object($sInfo)) {
        $heading[] = array('text' => '<b>' . $sInfo->title . '</b>');

        $keys = '';
        if ($sInfo->uninstallable || (sizeof($sInfo->keys > 0))) {
          if ($sInfo->status) {
            $contents[] = array('align' => 'center', 'text' => ($sInfo->uninstallable ? '<a href="' . tep_href_link(FILENAME_SERVICES, 'service=' . $sInfo->code . '&action=remove') . '">' . tep_image_button('button_module_remove.gif', IMAGE_MODULE_REMOVE) . '</a>' : '') . ((sizeof($sInfo->keys) > 0) ? ' <a href="' . tep_href_link(FILENAME_SERVICES, 'service=' . $sInfo->code . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a>' : ''));

            if (sizeof($sInfo->keys) > 0) {
              $keys = '<br>';

              foreach ($sInfo->keys as $value) {
                $keys .= '<b>' . $value['title'] . '</b><br>';
                if ($value['use_function']) {
                  $use_function = $value['use_function'];
                  if (ereg('->', $use_function)) {
                    $class_method = explode('->', $use_function);
                    if (!is_object(${$class_method[0]})) {
                      include('includes/classes/' . $class_method[0] . '.php');
                      ${$class_method[0]} = new $class_method[0]();
                    }
                    $keys .= tep_call_function($class_method[1], $value['value'], ${$class_method[0]});
                  } else {
                    $keys .= tep_call_function($use_function, $value['value']);
                  }
                } else {
                  $keys .= $value['value'];
                }
                $keys .= '<br><br>';
              }
              $keys = substr($keys, 0, strrpos($keys, '<br><br>'));
            }
          } else {
            $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_SERVICES, 'service=' . $sInfo->code . '&action=install') . '">' . tep_image_button('button_module_install.gif', IMAGE_MODULE_INSTALL) . '</a>');
          }
        }

        $contents[] = array('text' => $sInfo->description);

        if (!empty($sInfo->preceeds)) {
          $preceeds_string = '<u>Preceeds</u><br>';

          if (is_string($sInfo->preceeds)) {
            $preceeds_string .= $sInfo->preceeds;
          } else {
            foreach ($sInfo->preceeds as $preceeds) {
              $preceeds_string .= $preceeds . '<br>';
            }
          }

          $contents[] = array('text' => $preceeds_string);
        }

        $contents[] = array('text' => $keys);
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
