<?php
/*
  $Id: backup.php,v 1.1 2004/08/15 18:15:04 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/
?>

<h1><?php echo HEADING_TITLE; ?></h1>

<div id="infoBox_bDefault" <?php if (!empty($action)) { echo 'style="display: none;"'; } ?>>
  <table border="0" width="100%" cellspacing="0" cellpadding="2" class="dataTable">
    <thead>
      <tr>
        <th><?php echo TABLE_HEADING_TITLE; ?></th>
        <th><?php echo TABLE_HEADING_FILE_DATE; ?></th>
        <th><?php echo TABLE_HEADING_FILE_SIZE; ?></th>
        <th><?php echo TABLE_HEADING_ACTION; ?></th>
      </tr>
    </thead>
    <tbody>
<?php
  if ($dir_ok === true) {
    $contents = array();
    $dir = dir(DIR_FS_BACKUP);
    while ($file = $dir->read()) {
      if (!is_dir(DIR_FS_BACKUP . $file)) {
        $contents[] = $file;
      }
    }
    $dir->close();

    rsort($contents);

    for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
      $entry = $contents[$i];

      if (!isset($buInfo) && (!isset($_GET['file']) || (isset($_GET['file']) && ($_GET['file'] == $entry)))) {
        $file_array['file'] = $entry;
        $file_array['date'] = date(PHP_DATE_TIME_FORMAT, filemtime(DIR_FS_BACKUP . $entry));
        $file_array['size'] = number_format(filesize(DIR_FS_BACKUP . $entry)) . ' bytes';
        switch (substr($entry, -3)) {
          case 'zip': $file_array['compression'] = 'ZIP'; break;
          case '.gz': $file_array['compression'] = 'GZIP'; break;
          default: $file_array['compression'] = TEXT_NO_EXTENSION; break;
        }

        $buInfo = new objectInfo($file_array);
      }

      if (isset($buInfo) && ($entry == $buInfo->file)) {
        echo '      <tr class="selected">' . "\n";
      } else {
        echo '      <tr onMouseOver="rowOverEffect(this);" onMouseOut="rowOutEffect(this);" onClick="document.location.href=\'' . tep_href_link(FILENAME_BACKUP, 'file=' . $entry) . '\';">' . "\n";
      }
?>
        <td><?php echo '<a href="' . tep_href_link(FILENAME_BACKUP, 'action=download&file=' . $entry) . '">' . tep_image('templates/' . $template . '/images/icons/16x16/save.png', ICON_FILE_DOWNLOAD, '16', '16') . '&nbsp;' . $entry . '</a>'; ?></td>
        <td><?php echo date(PHP_DATE_TIME_FORMAT, filemtime(DIR_FS_BACKUP . $entry)); ?></td>
        <td><?php echo number_format(filesize(DIR_FS_BACKUP . $entry)); ?> bytes</td>
        <td align="right">
<?php
      if (isset($buInfo) && ($entry == $buInfo->file)) {
        echo '<a href="#" onClick="toggleInfoBox(\'bRestore\');">' . tep_image('templates/' . $template . '/images/icons/16x16/tape.png', IMAGE_EDIT, '16', '16') . '</a>&nbsp;' .
             '<a href="#" onClick="toggleInfoBox(\'bDelete\');">' . tep_image('templates/' . $template . '/images/icons/16x16/trash.png', IMAGE_DELETE, '16', '16') . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_BACKUP, 'file=' . $entry . '&action=bRestore') . '">' . tep_image('templates/' . $template . '/images/icons/16x16/tape.png', IMAGE_EDIT, '16', '16') . '</a>&nbsp;' .
             '<a href="' . tep_href_link(FILENAME_BACKUP, 'file=' . $entry . '&action=bDelete') . '">' . tep_image('templates/' . $template . '/images/icons/16x16/trash.png', IMAGE_DELETE, '16', '16') . '</a>';
      }
?>
        </td>
      </tr>
<?php
    }
  }
?>
    </tbody>
  </table>

  <table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr>
      <td class="smallText"><?php echo TEXT_BACKUP_DIRECTORY . ' ' . DIR_FS_BACKUP; ?></td>
      <td class="smallText" align="right"><?php if (isset($dir)) { echo '<input type="button" value="' . IMAGE_BACKUP . '" class="infoBoxButton" onClick="toggleInfoBox(\'bBackup\');">&nbsp;<input type="button" value="' . IMAGE_RESTORE . '" class="infoBoxButton" onClick="toggleInfoBox(\'bRestoreLocal\');">'; } ?></td>
    </tr>
  </table>

<?php
  if (defined('DB_LAST_RESTORE')) {
?>

  <p><?php echo TEXT_LAST_RESTORATION . ' ' . DB_LAST_RESTORE . ' <a href="' . tep_href_link(FILENAME_BACKUP, 'action=forget') . '">' . TEXT_FORGET . '</a>'; ?></p>

<?php
  }
?>
</div>

<div id="infoBox_bBackup" <?php if ($action != 'bBackup') { echo 'style="display: none;"'; } ?>>
  <div class="infoBoxHeading"><?php echo tep_image('templates/' . $template . '/images/icons/16x16/new.png', IMAGE_INSERT, '16', '16') . ' ' . TEXT_INFO_HEADING_NEW_BACKUP; ?></div>
  <div class="infoBoxContent">
    <?php echo tep_draw_form('bBackup', FILENAME_BACKUP, 'action=backupnow'); ?>

    <p><?php echo TEXT_INFO_NEW_BACKUP; ?></p>

    <p>
<?php
  $compress_array = array(array('id' => 'no', 'text' => TEXT_INFO_USE_NO_COMPRESSION));

  if (file_exists(LOCAL_EXE_GZIP)) {
    $compress_array[] = array('id' => 'gzip', 'text' => TEXT_INFO_USE_GZIP);
  }

  if (file_exists(LOCAL_EXE_ZIP)) {
    $compress_array[] = array('id' => 'zip', 'text' => TEXT_INFO_USE_ZIP);
  }

  echo osc_draw_radio_field('compress', $compress_array, 'no', '', false, '<br>');
?>
    </p>

    <p>
<?php
  if ($dir_ok === true) {
    echo osc_draw_checkbox_field('download', array(array('id' => 'yes', 'text' => TEXT_INFO_DOWNLOAD_ONLY))) . '*<br><br>*' . TEXT_INFO_BEST_THROUGH_HTTPS;
  } else {
    echo osc_draw_radio_field('download', array(array('id' => 'yes', 'text' => TEXT_INFO_DOWNLOAD_ONLY)), true) . '*<br><br>*' . TEXT_INFO_BEST_THROUGH_HTTPS;
  }
?>
    </p>

    <p align="center"><?php echo '<input type="submit" value="' . IMAGE_BACKUP . '" class="operationButton"> <input type="button" value="' . IMAGE_CANCEL . '" onClick="toggleInfoBox(\'bDefault\');" class="operationButton">'; ?></p>

    </form>
  </div>
</div>

<div id="infoBox_bRestoreLocal" <?php if ($action != 'bRestoreLocal') { echo 'style="display: none;"'; } ?>>
  <div class="infoBoxHeading"><?php echo tep_image('templates/' . $template . '/images/icons/16x16/new.png', IMAGE_INSERT, '16', '16') . ' ' . TEXT_INFO_HEADING_RESTORE_LOCAL; ?></div>
  <div class="infoBoxContent">
    <?php echo tep_draw_form('bRestoreLocal', FILENAME_BACKUP, 'action=restorelocalnow', 'post', 'enctype="multipart/form-data"'); ?>

    <p><?php echo TEXT_INFO_RESTORE_LOCAL; ?></p>

    <p><?php echo osc_draw_file_field('sql_file'); ?></p>

    <p><?php echo TEXT_INFO_RESTORE_LOCAL_RAW_FILE; ?></p>

    <p align="center"><?php echo '<input type="submit" value="' . IMAGE_RESTORE . '" class="operationButton"> <input type="button" value="' . IMAGE_CANCEL . '" onClick="toggleInfoBox(\'bDefault\');" class="operationButton">'; ?></p>

    </form>
  </div>
</div>

<?php
  if (isset($buInfo)) {
?>

<div id="infoBox_bRestore" <?php if ($action != 'bRestore') { echo 'style="display: none;"'; } ?>>
  <div class="infoBoxHeading"><?php echo tep_image('templates/' . $template . '/images/icons/16x16/new.png', IMAGE_INSERT, '16', '16') . ' ' . $buInfo->file; ?></div>
  <div class="infoBoxContent">
    <p><?php echo sprintf(TEXT_INFO_RESTORE, DIR_FS_BACKUP . (($buInfo->compression != TEXT_NO_EXTENSION) ? substr($buInfo->file, 0, strrpos($buInfo->file, '.')) : $buInfo->file), ($buInfo->compression != TEXT_NO_EXTENSION) ? TEXT_INFO_UNPACK : ''); ?></p>

    <p align="center"><?php echo '<input type="button" value="' . IMAGE_RESTORE . '" class="operationButton" onClick="document.location.href=\'' . tep_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file . '&action=restorenow') . '\';"> <input type="button" value="' . IMAGE_CANCEL . '" onClick="toggleInfoBox(\'bDefault\');" class="operationButton">'; ?></p>
  </div>
</div>

<div id="infoBox_bDelete" <?php if ($action != 'bDelete') { echo 'style="display: none;"'; } ?>>
  <div class="infoBoxHeading"><?php echo tep_image('templates/' . $template . '/images/icons/16x16/trash.png', IMAGE_DELETE, '16', '16') . ' ' . $buInfo->file; ?></div>
  <div class="infoBoxContent">
    <p><?php echo TEXT_DELETE_INTRO; ?></p>
    <p><?php echo '<b>' . $buInfo->file . '</b>'; ?></p>

    <p align="center"><?php echo '<input type="button" value="' . IMAGE_DELETE . '" class="operationButton" onClick="document.location.href=\'' . tep_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file . '&action=deleteconfirm') . '\';"> <input type="button" value="' . IMAGE_CANCEL . '" onClick="toggleInfoBox(\'bDefault\');" class="operationButton">'; ?></p>
  </div>
</div>

<?php
  }
?>
