<?php
/*
  $Id: orders_status.php,v 1.3 2004/11/20 02:16:50 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/
?>

<h1><?php echo HEADING_TITLE; ?></h1>

<div id="infoBox_osDefault" <?php if (!empty($action)) { echo 'style="display: none;"'; } ?>>
  <table border="0" width="100%" cellspacing="0" cellpadding="2" class="dataTable">
    <thead>
      <tr>
        <th><?php echo TABLE_HEADING_ORDERS_STATUS; ?></th>
        <th><?php echo TABLE_HEADING_ACTION; ?></th>
      </tr>
    </thead>
    <tbody>
<?php
  $Qstatuses = $osC_Database->query('select orders_status_id, orders_status_name from :table_orders_status where language_id = :language_id order by orders_status_name');
  $Qstatuses->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
  $Qstatuses->bindInt(':language_id', $osC_Session->value('languages_id'));
  $Qstatuses->setBatchLimit($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS);
  $Qstatuses->execute();

  while ($Qstatuses->next()) {
    if (!isset($osInfo) && (!isset($_GET['osID']) || (isset($_GET['osID']) && ($_GET['osID'] == $Qstatuses->valueInt('orders_status_id'))))) {
      $osInfo = new objectInfo($Qstatuses->toArray());
    }

    if (isset($osInfo) && ($Qstatuses->valueInt('orders_status_id') == $osInfo->orders_status_id)) {
      echo '      <tr class="selected">' . "\n";
    } else {
      echo '      <tr onMouseOver="rowOverEffect(this);" onMouseOut="rowOutEffect(this);" onClick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS_STATUS, 'page=' . $_GET['page'] . '&osID=' . $Qstatuses->valueInt('orders_status_id')) . '\';">' . "\n";
    }

    if (DEFAULT_ORDERS_STATUS_ID == $Qstatuses->valueInt('orders_status_id')) {
      echo '        <td><b>' . $Qstatuses->value('orders_status_name') . ' (' . TEXT_DEFAULT . ')</b></td>' . "\n";
    } else {
      echo '        <td>' . $Qstatuses->value('orders_status_name') . '</td>' . "\n";
    }
?>
        <td align="right">
<?php
    if (isset($osInfo) && ($Qstatuses->valueInt('orders_status_id') == $osInfo->orders_status_id)) {
      echo '<a href="#" onClick="toggleInfoBox(\'osEdit\');">' . tep_image('templates/' . $template . '/images/icons/16x16/configure.png', IMAGE_EDIT, '16', '16') . '</a>&nbsp;' .
           '<a href="#" onClick="toggleInfoBox(\'osDelete\');">' . tep_image('templates/' . $template . '/images/icons/16x16/trash.png', IMAGE_DELETE, '16', '16') . '</a>';
    } else {
      echo '<a href="' . tep_href_link(FILENAME_ORDERS_STATUS, 'page=' . $_GET['page'] . '&osID=' . $Qstatuses->valueInt('orders_status_id') . '&action=osEdit') . '">' . tep_image('templates/' . $template . '/images/icons/16x16/configure.png', IMAGE_EDIT, '16', '16') . '</a>&nbsp;' .
           '<a href="' . tep_href_link(FILENAME_ORDERS_STATUS, 'page=' . $_GET['page'] . '&osID=' . $Qstatuses->valueInt('orders_status_id') . '&action=osDelete') . '">' . tep_image('templates/' . $template . '/images/icons/16x16/trash.png', IMAGE_DELETE, '16', '16') . '</a>';
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
      <td class="smallText"><?php echo $Qstatuses->displayBatchLinksTotal(TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS); ?></td>
      <td class="smallText" align="right"><?php echo $Qstatuses->displayBatchLinksPullDown(); ?></td>
    </tr>
  </table>

  <p align="right"><?php echo '<input type="button" value="' . IMAGE_INSERT . '" onClick="toggleInfoBox(\'osNew\');" class="infoBoxButton">'; ?></p>
</div>

<div id="infoBox_osNew" <?php if ($action != 'osNew') { echo 'style="display: none;"'; } ?>>
  <div class="infoBoxHeading"><?php echo tep_image('templates/' . $template . '/images/icons/16x16/new.png', IMAGE_INSERT, '16', '16') . ' ' . TEXT_INFO_HEADING_NEW_ORDERS_STATUS; ?></div>
  <div class="infoBoxContent">
    <?php echo tep_draw_form('osNew', FILENAME_ORDERS_STATUS, 'action=save'); ?>

    <p><?php echo TEXT_INFO_INSERT_INTRO; ?></p>

    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td class="smallText" width="40%"><?php echo '<b>' . TEXT_INFO_ORDERS_STATUS_NAME . '</b>'; ?></td>
        <td class="smallText" width="60%">
<?php
  foreach ($osC_Language->getAll() as $language) {
    echo tep_image('../includes/languages/' . $language['directory'] . '/images/' . $language['image'], $language['name']) . '&nbsp;' . osc_draw_input_field('orders_status_name[' . $language['id'] . ']') . '<br>';
  }
?>
        </td>
      </tr>
      <tr>
        <td class="smallText" width="40%"><?php echo '<b>' . TEXT_SET_DEFAULT . '</b>'; ?></td>
        <td class="smallText" width="60%"><?php echo osc_draw_checkbox_field('default'); ?></td>
      </tr>
    </table>

    <p align="center"><?php echo '<input type="submit" value="' . IMAGE_SAVE . '" class="operationButton"> <input type="button" value="' . IMAGE_CANCEL . '" onClick="toggleInfoBox(\'osDefault\');" class="operationButton">'; ?></p>

    </form>
  </div>
</div>

<?php
  if (isset($osInfo)) {
?>

<div id="infoBox_osEdit" <?php if ($action != 'osEdit') { echo 'style="display: none;"'; } ?>>
  <div class="infoBoxHeading"><?php echo tep_image('templates/' . $template . '/images/icons/16x16/configure.png', IMAGE_EDIT, '16', '16') . ' ' . $osInfo->orders_status_name; ?></div>
  <div class="infoBoxContent">
    <?php echo tep_draw_form('osEdit', FILENAME_ORDERS_STATUS, 'page=' . $_GET['page'] . '&osID=' . $osInfo->orders_status_id . '&action=save'); ?>

    <p><?php echo TEXT_INFO_EDIT_INTRO; ?></p>

    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td class="smallText" width="40%"><?php echo '<b>' . TEXT_INFO_ORDERS_STATUS_NAME . '</b>'; ?></td>
        <td class="smallText" width="60%">
<?php
    $Qsd = $osC_Database->query('select language_id, orders_status_name from :table_orders_status where orders_status_id = :orders_status_id');
    $Qsd->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
    $Qsd->bindInt(':orders_status_id', $osInfo->orders_status_id);
    $Qsd->execute();

    $status_name = array();
    while ($Qsd->next()) {
      $status_name[$Qsd->valueInt('language_id')] = $Qsd->value('orders_status_name');
    }

    foreach ($osC_Language->getAll() as $language) {
      echo tep_image('../includes/languages/' . $language['directory'] . '/images/' . $language['image'], $language['name']) . '&nbsp;' . osc_draw_input_field('orders_status_name[' . $language['id'] . ']', (isset($status_name[$language['id']]) ? $status_name[$language['id']] : '')) . '<br>';
    }
?>
        </td>
      </tr>
<?php
    if (DEFAULT_ORDERS_STATUS_ID != $osInfo->orders_status_id) {
?>
      <tr>
        <td class="smallText" width="40%"><?php echo '<b>' . TEXT_SET_DEFAULT . '</b>'; ?></td>
        <td class="smallText" width="60%"><?php echo osc_draw_checkbox_field('default'); ?></td>
      </tr>
<?php
    }
?>
    </table>

    <p align="center"><?php echo '<input type="submit" value="' . IMAGE_SAVE . '" class="operationButton"> <input type="button" value="' . IMAGE_CANCEL . '" onClick="toggleInfoBox(\'osDefault\');" class="operationButton">'; ?></p>

    </form>
  </div>
</div>

<div id="infoBox_osDelete" <?php if ($action != 'osDelete') { echo 'style="display: none;"'; } ?>>
  <div class="infoBoxHeading"><?php echo tep_image('templates/' . $template . '/images/icons/16x16/trash.png', IMAGE_DELETE, '16', '16') . ' ' . $osInfo->orders_status_name; ?></div>
  <div class="infoBoxContent">
<?php
    $Qorders = $osC_Database->query('select count(*) as total from :table_orders where orders_status = :orders_status');
    $Qorders->bindTable(':table_orders', TABLE_ORDERS);
    $Qorders->bindInt(':orders_status', $osInfo->orders_status_id);
    $Qorders->execute();

    $Qhistory = $osC_Database->query('select count(*) as total from :table_orders_status_history where orders_status_id = :orders_status_id group by orders_id');
    $Qhistory->bindTable(':table_orders_status_history', TABLE_ORDERS_STATUS_HISTORY);
    $Qhistory->bindInt(':orders_status_id', $osInfo->orders_status_id);
    $Qhistory->execute();

    if ( (DEFAULT_ORDERS_STATUS_ID == $osInfo->orders_status_id) || ($Qorders->valueInt('total') > 0) || ($Qhistory->valueInt('total') > 0) ) {
      if (DEFAULT_ORDERS_STATUS_ID == $osInfo->orders_status_id) {
        echo '    <p><b>' . TEXT_INFO_DELETE_PROHIBITED . '</b></p>' . "\n";
      }

      if ($Qorders->valueInt('total') > 0) {
        echo '    <p><b>' . sprintf(TEXT_INFO_DELETE_PROHIBITED_ORDERS, $Qorders->valueInt('total')) . '</b></p>' . "\n";
      }

      if ($Qhistory->valueInt('total') > 0) {
        echo '    <p><b>' . sprintf(TEXT_INFO_DELETE_PROHIBITED_HISTORY, $Qhistory->valueInt('total')) . '</b></p>' . "\n";
      }

      echo '    <p align="center"><input type="button" value="' . IMAGE_BACK . '" onClick="toggleInfoBox(\'osDefault\');" class="operationButton"></p>' . "\n";
    } else {
?>
    <p><?php echo TEXT_INFO_DELETE_INTRO; ?></p>
    <p><?php echo '<b>' . $osInfo->orders_status_name . '</b>'; ?></p>
    <p align="center"><?php echo '<input type="button" value="' . IMAGE_DELETE . '" onClick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS_STATUS, 'page=' . $_GET['page'] . '&osID=' . $osInfo->orders_status_id . '&action=deleteconfirm') . '\';" class="operationButton"> <input type="button" value="' . IMAGE_CANCEL . '" onClick="toggleInfoBox(\'osDefault\');" class="operationButton">'; ?></p>
<?php
    }
?>
  </div>
</div>

<?php
  }
?>
