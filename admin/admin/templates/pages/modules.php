<?php
/*
  $Id: modules.php,v 1.1 2004/07/22 23:29:31 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));

  $directory_array = array();
  if ($dir = @dir('../includes/modules/' . $module_type)) {
    while ($file = $dir->read()) {
      if (!is_dir('../includes/modules/' . $module_type . '/' . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          $directory_array[] = $file;
        }
      }
    }
    sort($directory_array);
    $dir->close();
  }
?>

<h1><?php echo HEADING_TITLE; ?></h1>

<div id="infoBox_mDefault" <?php if (!empty($action)) { echo 'style="display: none;"'; } ?>>
  <table border="0" width="100%" cellspacing="0" cellpadding="2" class="dataTable">
    <thead>
      <tr>
        <th><?php echo TABLE_HEADING_MODULES; ?></th>
        <th><?php echo TABLE_HEADING_SORT_ORDER; ?></th>
        <th><?php echo TABLE_HEADING_STATUS; ?></th>
        <th><?php echo TABLE_HEADING_ACTION; ?></th>
      </tr>
    </thead>
    <tbody>
<?php
  $installed_modules = array();
  foreach ($directory_array as $file) {
    include('../includes/languages/' . $osC_Session->value('language') . '/modules/' . $module_type . '/' . $file);
    include('../includes/modules/' . $module_type . '/' . $file);

    $class = substr($file, 0, strrpos($file, '.'));
    if (class_exists($class)) {
      $module = new $class;
      if ($module->check() > 0) {
        if (($module->sort_order > 0) && !isset($installed_modules[$module->sort_order])) {
          $installed_modules[$module->sort_order] = $file;
        } else {
          $installed_modules[] = $file;
        }
      }

      if (!isset($mInfo) && (!isset($_GET['module']) || (isset($_GET['module']) && ($_GET['module'] == $class)))) {
        $module_info = array('code' => $module->code,
                             'title' => $module->title,
                             'description' => $module->description,
                             'installed' => ($module->check() ? true : false),
                             'status' => $module->enabled);

        $module_keys = $module->keys();

        $keys_extra = array();
        foreach ($module_keys as $key) {
          $key_value_query = tep_db_query("select configuration_title, configuration_value, configuration_description, use_function, set_function from " . TABLE_CONFIGURATION . " where configuration_key = '" . $key . "'");
          $key_value = tep_db_fetch_array($key_value_query);

          $keys_extra[$key]['title'] = $key_value['configuration_title'];
          $keys_extra[$key]['value'] = $key_value['configuration_value'];
          $keys_extra[$key]['description'] = $key_value['configuration_description'];
          $keys_extra[$key]['use_function'] = $key_value['use_function'];
          $keys_extra[$key]['set_function'] = $key_value['set_function'];
        }

        $module_info['keys'] = $keys_extra;

        $mInfo = new objectInfo($module_info);
      }

      if (isset($mInfo) && ($class == $mInfo->code) ) {
        echo '      <tr class="selected">' . "\n";
      } else {
        echo '      <tr onMouseOver="rowOverEffect(this);" onMouseOut="rowOutEffect(this);" onClick="document.location.href=\'' . tep_href_link(FILENAME_MODULES, 'set=' . $set . '&module=' . $class) . '\';">' . "\n";
      }
?>
        <td><?php echo $module->title; ?></td>
        <td><?php if (isset($module->sort_order) && is_numeric($module->sort_order)) echo $module->sort_order; ?></td>
        <td align="center"><?php echo tep_image('templates/' . $template . '/images/icons/' . (($module->check() > 0) ? ($module->enabled ? 'checkbox_ticked.gif' : 'checkbox_crossed.gif') : 'checkbox.gif')); ?></td>
        <td align="right">
<?php
    if (isset($mInfo) && ($class == $mInfo->code)) {
      if ($mInfo->installed === true) {
        echo '<a href="#" onClick="toggleInfoBox(\'mUninstall\');">' . tep_image('templates/' . $template . '/images/icons/16x16/stop.png', IMAGE_MODULE_REMOVE, '16', '16') . '</a>&nbsp;' .
             '<a href="#" onClick="toggleInfoBox(\'mEdit\');">' . tep_image('templates/' . $template . '/images/icons/16x16/configure.png', IMAGE_EDIT, '16', '16') . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_MODULES, 'set=' . $set . '&module=' . $class . '&action=install') . '">' . tep_image('templates/' . $template . '/images/icons/16x16/play.png', IMAGE_MODULE_INSTALL, '16', '16') . '</a>&nbsp;' .
             tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '16', '16');
      }
    } else {
      if (($module->check() > 0) && $module->enabled === true) {
        echo '<a href="' . tep_href_link(FILENAME_MODULES, 'set=' . $set . '&module=' . $class . '&action=mUninstall') . '">' . tep_image('templates/' . $template . '/images/icons/16x16/stop.png', IMAGE_MODULE_REMOVE, '16', '16') . '</a>&nbsp;' .
             '<a href="' . tep_href_link(FILENAME_MODULES, 'set=' . $set . '&module=' . $class . '&action=mEdit') . '">' . tep_image('templates/' . $template . '/images/icons/16x16/configure.png', IMAGE_EDIT, '16', '16') . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_MODULES, 'set=' . $set . '&module=' . $class . '&action=install') . '">' . tep_image('templates/' . $template . '/images/icons/16x16/play.png', IMAGE_MODULE_INSTALL, '16', '16') . '</a>&nbsp;' .
             tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '16', '16');
      }
    }
?>
        </td>
      </tr>
<?php
    }
  }

  ksort($installed_modules);
  if (constant($module_key) != implode(';', $installed_modules)) {
    $Qupdate = $osC_Database->query('update :table_configuration set configuration_value = :configuration_value, last_modified = now() where configuration_key = :configuration_key');
    $Qupdate->bindTable(':table_configuration', TABLE_CONFIGURATION);
    $Qupdate->bindValue(':configuration_value', implode(';', $installed_modules));
    $Qupdate->bindValue(':configuration_key', $module_key);
    $Qupdate->execute();

    if ($Qupdate->affectedRows()) {
      osC_Cache::clear('configuration');
    }
  }
?>
    </tbody>
  </table>

  <p><?php echo TEXT_MODULE_DIRECTORY . ' ' . realpath(dirname(__FILE__) . '/../../../includes/modules/' . $module_type); ?></p>
</div>

<?php
  if (isset($mInfo)) {
?>

<div id="infoBox_mUninstall" <?php if ($action != 'mUninstall') { echo 'style="display: none;"'; } ?>>
  <div class="infoBoxHeading"><?php echo tep_image('templates/' . $template . '/images/icons/16x16/stop.png', IMAGE_MODULE_REMOVE, '16', '16') . ' ' . $mInfo->title; ?></div>
  <div class="infoBoxContent">
    <p><?php echo INFO_MODULE_UNINSTALL_INTRO; ?></p>
    <p align="center"><?php echo '<input type="button" value="' . IMAGE_MODULE_REMOVE . '" class="operationButton" onClick="document.location.href=\'' . tep_href_link(FILENAME_MODULES, 'set=' . $set . '&module=' . $mInfo->code . '&action=remove') . '\';"> <input type="button" value="' . IMAGE_CANCEL . '" class="operationButton" onClick="toggleInfoBox(\'mDefault\');">'; ?></p>
  </div>
</div>

<div id="infoBox_mEdit" <?php if ($action != 'mEdit') { echo 'style="display: none;"'; } ?>>
  <div class="infoBoxHeading"><?php echo tep_image('templates/' . $template . '/images/icons/16x16/configure.png', IMAGE_EDIT, '16', '16') . ' ' . $mInfo->title; ?></div>
  <div class="infoBoxContent">
    <?php echo tep_draw_form('mEdit', FILENAME_MODULES, 'set=' . $set . '&module=' . $mInfo->code . '&action=save'); ?>

<?php
    $keys = '';
    foreach ($mInfo->keys as $key => $value) {
      $keys .= '<b>' . $value['title'] . '</b><br>' . $value['description'] . '<br>';

      if ($value['set_function']) {
        eval('$keys .= ' . $value['set_function'] . "'" . $value['value'] . "', '" . $key . "');");
      } else {
        $keys .= osc_draw_input_field('configuration[' . $key . ']', $value['value']);
      }
      $keys .= '<br><br>';
    }
    $keys = substr($keys, 0, strrpos($keys, '<br><br>'));
?>
    <p><?php echo $keys; ?></p>

    <p align="center"><?php echo '<input type="submit" value="' . IMAGE_SAVE . '" class="operationButton"> <input type="button" value="' . IMAGE_CANCEL . '" onClick="toggleInfoBox(\'mDefault\');" class="operationButton">'; ?></p>

    </form>
  </div>
</div>

<?php
  }
?>
