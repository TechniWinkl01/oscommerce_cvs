<?php
/*
  $Id: weight_classes.php,v 1.1 2004/07/22 23:31:58 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/
?>

<h1><?php echo HEADING_TITLE; ?></h1>

<div id="infoBox_wcDefault" <?php if (!empty($action)) { echo 'style="display: none;"'; } ?>>
  <table border="0" width="100%" cellspacing="0" cellpadding="2" class="dataTable">
    <thead>
      <tr>
        <th><?php echo TABLE_HEADING_WEIGHT_CLASSES_TITLE; ?></th>
        <th><?php echo TABLE_HEADING_WEIGHT_CLASSES_UNIT; ?></th>
        <th><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
      </tr>
    </thead>
    <tbody>
<?php
  $Qclasses = $osC_Database->query('select weight_class_id, weight_class_key, weight_class_title from :table_weight_classes where language_id = :language_id order by weight_class_title');
  $Qclasses->bindTable(':table_weight_classes', TABLE_WEIGHT_CLASS);
  $Qclasses->bindInt(':language_id', $osC_Session->value('languages_id'));
  $Qclasses->setBatchLimit($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS);
  $Qclasses->execute();

  while ($Qclasses->next()) {
    if (!isset($wcInfo) && (!isset($_GET['wcID']) || (isset($_GET['wcID']) && ($_GET['wcID'] == $Qclasses->valueInt('weight_class_id'))))) {
      $wcInfo = new objectInfo($Qclasses->toArray());
    }

    if (isset($wcInfo) && ($Qclasses->valueInt('weight_class_id') == $wcInfo->weight_class_id)) {
      echo '      <tr class="selected">' . "\n";
    } else {
      echo '      <tr onMouseOver="rowOverEffect(this);" onMouseOut="rowOutEffect(this);" onClick="document.location.href=\'' . tep_href_link(FILENAME_WEIGHT_CLASSES, 'page=' . $_GET['page'] . '&wcID=' . $Qclasses->valueInt('weight_class_id')) . '\';">' . "\n";
    }

    if (SHIPPING_WEIGHT_UNIT == $Qclasses->valueInt('weight_class_id')) {
      echo '        <td><b>' . $Qclasses->value('weight_class_title') . ' (' . TEXT_DEFAULT . ')</b></td>' . "\n";
    } else {
      echo '        <td>' . $Qclasses->value('weight_class_title') . '</td>' . "\n";
    }
?>
        <td><?php echo $Qclasses->value('weight_class_key'); ?></td>
        <td align="right">
<?php
    if (isset($wcInfo) && ($Qclasses->valueInt('weight_class_id') == $wcInfo->weight_class_id)) {
      echo '<a href="#" onClick="toggleInfoBox(\'wcEdit\');">' . tep_image('templates/' . $template . '/images/icons/16x16/configure.png', IMAGE_EDIT, '16', '16') . '</a>&nbsp;' .
           '<a href="#" onClick="toggleInfoBox(\'wcDelete\');">' . tep_image('templates/' . $template . '/images/icons/16x16/trash.png', IMAGE_DELETE, '16', '16') . '</a>';
    } else {
      echo '<a href="' . tep_href_link(FILENAME_WEIGHT_CLASSES, 'page=' . $_GET['page'] . '&wcID=' . $Qclasses->valueInt('weight_class_id') . '&action=wcEdit') . '">' . tep_image('templates/' . $template . '/images/icons/16x16/configure.png', IMAGE_EDIT, '16', '16') . '</a>&nbsp;' .
           '<a href="' . tep_href_link(FILENAME_WEIGHT_CLASSES, 'page=' . $_GET['page'] . '&wcID=' . $Qclasses->valueInt('weight_class_id') . '&action=wcDelete') . '">' . tep_image('templates/' . $template . '/images/icons/16x16/trash.png', IMAGE_DELETE, '16', '16') . '</a>';
    }
?>
        </td>
      </tr>
<?php
  }
?>
    </tbody>
  </table>

  <table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr>
      <td class="smallText"><?php echo $Qclasses->displayBatchLinksTotal(TEXT_DISPLAY_NUMBER_OF_WEIGHT_CLASSES); ?></td>
      <td class="smallText" align="right"><?php echo $Qclasses->displayBatchLinksPullDown(); ?></td>
    </tr>
  </table>

  <p align="right"><?php echo '<input type="button" value="' . IMAGE_INSERT . '" onClick="toggleInfoBox(\'wcNew\');" class="infoBoxButton">'; ?></p>
</div>

<div id="infoBox_wcNew" <?php if ($action != 'wcNew') { echo 'style="display: none;"'; } ?>>
  <div class="infoBoxHeading"><?php echo tep_image('templates/' . $template . '/images/icons/16x16/new.png', IMAGE_INSERT, '16', '16') . ' ' . TEXT_INFO_HEADING_NEW_WEIGHT_CLASS; ?></div>
  <div class="infoBoxContent">
    <?php echo tep_draw_form('wcNew', FILENAME_WEIGHT_CLASSES, 'action=save'); ?>

    <p><?php echo TEXT_INFO_INSERT_INTRO; ?></p>

    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td class="smallText" width="40%"><?php echo '<b>' . TEXT_INFO_WEIGHT_CLASS_TITLE . '</b>'; ?></td>
        <td class="smallText" width="60%">
<?php
  $languages = tep_get_languages();
  foreach ($languages as $l_entry) {
    echo tep_image(DIR_WS_CATALOG_LANGUAGES . $l_entry['directory'] . '/images/' . $l_entry['image'], $l_entry['name']) . '&nbsp;' . osc_draw_input_field('weight_class_title[' . $l_entry['id'] . ']') . osc_draw_input_field('weight_class_key[' . $l_entry['id'] . ']', '', 'size="4"') . '<br>';
  }
?>
        </td>
      </tr>
      <tr>
        <td class="smallText" width="40%"><?php echo '<b>' . TEXT_INFO_HEADING_EDIT_WEIGHT_RULES . '</b>'; ?></td>
        <td class="smallText" width="60%">
          <table border="0" cellspacing="0" cellpadding="2">
<?php
    $Qrules = $osC_Database->query('select weight_class_id, weight_class_title from :table_weight_classes where language_id = :language_id order by weight_class_title');
    $Qrules->bindTable(':table_weight_classes', TABLE_WEIGHT_CLASS);
    $Qrules->bindInt(':language_id', $osC_Session->value('languages_id'));
    $Qrules->execute();

    while ($Qrules->next()) {
      echo '            <tr>' . "\n" .
           '              <td class="dataTableContent">' . $Qrules->value('weight_class_title') . ':</td>' . "\n" .
           '              <td class="dataTableContent">' . osc_draw_input_field('weight_class_rules[' . $Qrules->valueInt('weight_class_id') . ']') . '</td>' . "\n" .
           '            </tr>' . "\n";
    }
?>
          </table>
        </td>
      </tr>
      <tr>
        <td class="smallText" width="40%"><?php echo '<b>' . TEXT_SET_DEFAULT . '</b>'; ?></td>
        <td class="smallText" width="60%"><?php echo osc_draw_checkbox_field('default'); ?></td>
      </tr>
    </table>

    <p align="center"><?php echo '<input type="submit" value="' . IMAGE_SAVE . '" class="operationButton"> <input type="button" value="' . IMAGE_CANCEL . '" onClick="toggleInfoBox(\'wcDefault\');" class="operationButton">'; ?></p>

    </form>
  </div>
</div>

<?php
  if (isset($wcInfo)) {
?>

<div id="infoBox_wcEdit" <?php if ($action != 'wcEdit') { echo 'style="display: none;"'; } ?>>
  <div class="infoBoxHeading"><?php echo tep_image('templates/' . $template . '/images/icons/16x16/configure.png', IMAGE_EDIT, '16', '16') . ' ' . $wcInfo->weight_class_title; ?></div>
  <div class="infoBoxContent">
    <?php echo tep_draw_form('wcEdit', FILENAME_WEIGHT_CLASSES, 'page=' . $_GET['page'] . '&wcID=' . $wcInfo->weight_class_id . '&action=save'); ?>

    <p><?php echo TEXT_INFO_EDIT_INTRO; ?></p>

    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td class="smallText" width="40%"><?php echo '<b>' . TEXT_INFO_WEIGHT_CLASS_TITLE . '</b>'; ?></td>
        <td class="smallText" width="60%">
<?php
    $Qwc = $osC_Database->query('select language_id, weight_class_key, weight_class_title from :table_weight_classes where weight_class_id = :weight_class_id');
    $Qwc->bindTable(':table_weight_classes', TABLE_WEIGHT_CLASS);
    $Qwc->bindInt(':weight_class_id', $wcInfo->weight_class_id);
    $Qwc->execute();

    $class_name = array();
    while ($Qwc->next()) {
      $class_name[$Qwc->valueInt('language_id')] = array('key' => $Qwc->value('weight_class_key'),
                                                         'title' => $Qwc->value('weight_class_title'));
    }

    $languages = tep_get_languages();
    foreach ($languages as $l_entry) {
      echo tep_image(DIR_WS_CATALOG_LANGUAGES . $l_entry['directory'] . '/images/' . $l_entry['image'], $l_entry['name']) . '&nbsp;' . osc_draw_input_field('weight_class_title[' . $l_entry['id'] . ']', $class_name[$l_entry['id']]['title']) . osc_draw_input_field('weight_class_key[' . $l_entry['id'] . ']', $class_name[$l_entry['id']]['key'], 'size="4"') . '<br>';
    }
?>
        </td>
      </tr>
      <tr>
        <td class="smallText" width="40%"><?php echo '<b>' . TEXT_INFO_HEADING_EDIT_WEIGHT_RULES . '</b>'; ?></td>
        <td class="smallText" width="60%">
          <table border="0" cellspacing="0" cellpadding="2">
<?php
    $Qrules = $osC_Database->query('select r.weight_class_to_id, r.weight_class_rule, c.weight_class_title from :table_weight_classes_rules r, :table_weight_classes c where r.weight_class_from_id = :weight_class_from_id and r.weight_class_to_id != :weight_class_to_id and r.weight_class_to_id = c.weight_class_id and c.language_id = :language_id order by c.weight_class_title');
    $Qrules->bindTable(':table_weight_classes_rules', TABLE_WEIGHT_CLASS_RULES);
    $Qrules->bindTable(':table_weight_classes', TABLE_WEIGHT_CLASS);
    $Qrules->bindInt(':weight_class_from_id', $wcInfo->weight_class_id);
    $Qrules->bindInt(':weight_class_to_id', $wcInfo->weight_class_id);
    $Qrules->bindInt(':language_id', $osC_Session->value('languages_id'));
    $Qrules->execute();

    while ($Qrules->next()) {
      echo '            <tr>' . "\n" .
           '              <td class="dataTableContent">' . $Qrules->value('weight_class_title') . ':</td>' . "\n" .
           '              <td class="dataTableContent">' . osc_draw_input_field('weight_class_rules[' . $Qrules->valueInt('weight_class_to_id') . ']', $Qrules->value('weight_class_rule')) . '</td>' . "\n" .
           '            </tr>' . "\n";
    }
?>
          </table>
        </td>
      </tr>
<?php
    if (SHIPPING_WEIGHT_UNIT != $wcInfo->weight_class_id) {
?>
      <tr>
        <td class="smallText" width="40%"><?php echo '<b>' . TEXT_SET_DEFAULT . '</b>'; ?></td>
        <td class="smallText" width="60%"><?php echo osc_draw_checkbox_field('default'); ?></td>
      </tr>
<?php
    }
?>
    </table>

    <p align="center"><?php echo '<input type="submit" value="' . IMAGE_SAVE . '" class="operationButton"> <input type="button" value="' . IMAGE_CANCEL . '" onClick="toggleInfoBox(\'wcDefault\');" class="operationButton">'; ?></p>

    </form>
  </div>
</div>

<div id="infoBox_wcDelete" <?php if ($action != 'wcDelete') { echo 'style="display: none;"'; } ?>>
  <div class="infoBoxHeading"><?php echo tep_image('templates/' . $template . '/images/icons/16x16/trash.png', IMAGE_DELETE, '16', '16') . ' ' . $wcInfo->weight_class_title; ?></div>
  <div class="infoBoxContent">
<?php
    $Qcheck = $osC_Database->query('select count(*) as total from :table_products where products_weight_class = :products_weight_class');
    $Qcheck->bindTable(':table_products', TABLE_PRODUCTS);
    $Qcheck->bindInt(':products_weight_class', $wcInfo->weight_class_id);
    $Qcheck->execute();

    if ( (SHIPPING_WEIGHT_UNIT == $wcInfo->weight_class_id) || ($Qcheck->valueInt('total') > 0) ) {
      if (SHIPPING_WEIGHT_UNIT == $wcInfo->weight_class_id) {
        echo '<p><b>' . TEXT_INFO_DELETE_PROHIBITED . '</b></p>' . "\n";
      }

      if ($Qcheck->valueInt('total') > 0) {
        echo '    <p><b>' . sprintf(TEXT_INFO_DELETE_PROHIBITED_PRODUCTS, $Qcheck->valueInt('total')) . '</b></p>' . "\n";
      }

      echo '    <p align="center"><input type="button" value="' . IMAGE_BACK . '" onClick="toggleInfoBox(\'wcDefault\');" class="operationButton"></p>' . "\n";
    } else {
?>
    <p><?php echo TEXT_INFO_DELETE_INTRO; ?></p>
    <p><b><?php echo $wcInfo->weight_class_title; ?></b></p>
    <p align="center"><?php echo '<input type="button" value="' . IMAGE_DELETE . '" class="operationButton" onClick="document.location.href=\'' . tep_href_link(FILENAME_WEIGHT_CLASSES, 'page=' . $_GET['page'] . '&wcID=' . $wcInfo->weight_class_id . '&action=deleteconfirm') . '\';"> <input type="button" value="' . IMAGE_CANCEL . '" onClick="toggleInfoBox(\'wcDefault\');" class="operationButton">'; ?></p>
<?php
    }
?>
  </div>
</div>

<?php
  }
?>
