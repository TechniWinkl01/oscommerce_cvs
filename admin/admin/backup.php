<?php
/*
  $Id: backup.php,v 1.40 2002/01/11 02:20:56 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ($HTTP_GET_VARS['action']) {
    switch ($HTTP_GET_VARS['action']) {
      case 'forget':
        tep_db_query("delete from configuration where configuration_key = 'DB_LAST_RESTORE'");
        tep_redirect(tep_href_link(FILENAME_BACKUP));
        break;
      case 'backupnow':
        tep_set_time_limit(0);
        $schema = '# osCommerce, Open Source E-Commerce Solutions' . "\n" .
                  '# http://www.oscommerce.com' . "\n" .
                  '#' . "\n" .
                  '# Database Backup For ' . STORE_NAME . "\n" . 
                  '# Copyright (c) ' . date('Y') . ' ' . STORE_OWNER . "\n" .
                  '#' . "\n" .
                  '# Database: ' . DB_DATABASE . "\n" .
                  '# Database Server: ' . DB_SERVER . "\n" . 
                  '#' . "\n" .
                  '# Backup Date: ' . date(PHP_DATE_TIME_FORMAT) . "\n\n";
        $tables_query = tep_db_query('show tables');
        while ($tables = tep_db_fetch_array($tables_query)) {
          list(,$table) = each($tables);
          $schema .= 'drop table if exists ' . $table . ';' . "\n" .
                     'create table ' . $table . ' (' . "\n";
          $table_list = array();
          $fields_query = tep_db_query("show fields from " . $table);
          while ($fields = tep_db_fetch_array($fields_query)) {
            $table_list[] = $fields['Field'];
            $schema .= '  ' . $fields['Field'] . ' ' . $fields['Type'];
            if (strlen($fields['Default']) > 0) $schema .= ' default \'' . $fields['Default'] . '\'';
            if ($fields['Null'] != 'YES') $schema .= ' not null';
            if (isset($fields['Extra'])) $schema .= ' ' . $fields['Extra'];
            $schema .= ',' . "\n";
          }
          $schema = ereg_replace(",\n$", '', $schema);

          // Add the keys
          $index = array();
          $keys_query = tep_db_query("show keys from " . $table);
          while ($keys = tep_db_fetch_array($keys_query)) {
            $kname = $keys['Key_name'];
            if (!isset($index[$kname])) {
              $index[$kname] = array('unique' => !$keys['Non_unique'],
                                     'columns' => array());
            }
            $index[$kname]['columns'][] = $keys['Column_name'];
          }
          while (list($kname, $info) = each($index)) {
            $schema .= ',' . "\n";
            $columns = implode($info['columns'], ', ');
            if ($kname == 'PRIMARY') {
              $schema .= '  PRIMARY KEY (' . $columns . ')';
            } elseif ($info['unique']) {
              $schema .= '  UNIQUE ' . $kname . ' (' . $columns . ')';
            } else {
              $schema .= '  KEY ' . $kname . ' (' . $columns . ')';
            }
          }
          $schema .= "\n" . ');' . "\n\n";

          // Dump the data
          $rows_query = tep_db_query("select " . implode(',', $table_list) . " from " . $table);
          while ($rows = tep_db_fetch_array($rows_query)) {
            $schema_insert = 'insert into ' . $table . ' (' . implode(', ', $table_list) . ') values (';
            reset($table_list);
            while (list(,$i) = each($table_list)) {
              if (!isset($rows[$i])) {
                $schema_insert .= 'NULL, ';
              } elseif ($rows[$i] != '') {
                $row = addslashes($rows[$i]);
                $row = ereg_replace("\n#", "\n".'\#', $row);
                $schema_insert .= '\'' . $row . '\', ';
              } else {
                $schema_insert .= '\'\', ';
              }
            }
            $schema_insert = ereg_replace(', $', '', $schema_insert) . ');' . "\n";
            $schema .= $schema_insert;
          }
          $schema .= "\n";
        }

        if ($HTTP_POST_VARS['download'] == 'yes') {
          $backup_file = 'db_' . DB_DATABASE . '-' . date('YmdHis') . '.sql';
          switch ($HTTP_POST_VARS['compress']) {
            case 'no':
              header('Content-type: application/x-octet-stream');
              header('Content-disposition: attachment; filename=' . $backup_file);
              echo $schema;
              exit;
              break;
            case 'gzip':
              if ($fp = fopen(DIR_FS_BACKUP . $backup_file, 'w')) {
                fputs($fp, $schema);
                fclose($fp);
                exec(LOCAL_EXE_GZIP . ' ' . DIR_FS_BACKUP . $backup_file);
                $backup_file .= '.gz';
              }
              if ($fp = fopen(DIR_FS_BACKUP . $backup_file, 'rb')) {
                $buffer = fread($fp, filesize(DIR_FS_BACKUP . $backup_file));
                fclose($fp);
                unlink(DIR_FS_BACKUP . $backup_file);
                header('Content-type: application/x-octet-stream');
                header('Content-disposition: attachment; filename=' . $backup_file);
                echo $buffer;
                exit;
              }
              break;
            case 'zip':
              if ($fp = fopen(DIR_FS_BACKUP . $backup_file, 'w')) {
                fputs($fp, $schema);
                fclose($fp);
                exec(LOCAL_EXE_ZIP . ' -j ' . DIR_FS_BACKUP . $backup_file . '.zip ' . DIR_FS_BACKUP . $backup_file);
                unlink(DIR_FS_BACKUP . $backup_file);
                $backup_file .= '.zip';
              }
              if ($fp = fopen(DIR_FS_BACKUP . $backup_file, 'rb')) {
                $buffer = fread($fp, filesize(DIR_FS_BACKUP . $backup_file));
                fclose($fp);
                unlink(DIR_FS_BACKUP . $backup_file);
                header('Content-type: application/x-octet-stream');
                header('Content-disposition: attachment; filename=' . $backup_file);
                echo $buffer;
                exit;
              }
          }
        } else {
          $backup_file = DIR_FS_BACKUP . 'db_' . DB_DATABASE . '-' . date('YmdHis') . '.sql';
          if ($fp = fopen($backup_file, 'w')) {
            fputs($fp, $schema);
            fclose($fp);
            switch ($HTTP_POST_VARS['compress']) {
              case 'gzip':
                exec(LOCAL_EXE_GZIP . ' ' . $backup_file);
                break;
              case 'zip':
                exec(LOCAL_EXE_ZIP . ' -j ' . $backup_file . '.zip ' . $backup_file);
                unlink($backup_file);
            }
          }
          tep_redirect(tep_href_link(FILENAME_BACKUP));
        }
        break;
      case 'restorenow':
      case 'restorelocalnow':
        tep_set_time_limit(0);

        if ($HTTP_GET_VARS['action'] == 'restorenow') {
          $read_from = $HTTP_GET_VARS['file'];
          if (file_exists(DIR_FS_BACKUP . $HTTP_GET_VARS['file'])) {
            $restore_file = DIR_FS_BACKUP . $HTTP_GET_VARS['file'];
            $extension = substr($HTTP_GET_VARS['file'], -3);
            if ( ($extension == 'sql') || ($extension == '.gz') || ($extension == 'zip') ) {
              switch ($extension) {
                case 'sql':
                  $restore_from = $restore_file;
                  $remove_raw = false;
                  break;
                case '.gz':
                  $restore_from = substr($restore_file, 0, -3);
                  exec(LOCAL_EXE_GUNZIP . ' ' . $restore_file . ' -c > ' . $restore_from);
                  $remove_raw = true;
                  break;
                case 'zip':
                  $restore_from = substr($restore_file, 0, -4);
                  exec(LOCAL_EXE_UNZIP . ' ' . $restore_file . ' -d ' . DIR_FS_BACKUP);
                  $remove_raw = true;
              }

              if ( ($restore_from) && (file_exists($restore_from)) && (filesize($restore_from) > 15000) ) {
                $fd = fopen($restore_from, 'rb');
                $restore_query = fread($fd, filesize($restore_from));
                fclose($fd);
              }
            }
          }
        } elseif ($HTTP_GET_VARS['action'] == 'restorelocalnow') {
          if ($HTTP_POST_FILES['sql_file']) {
            $uploaded_file = $HTTP_POST_FILES['sql_file']['tmp_name'];
            $read_from = basename($HTTP_POST_FILES['sql_file']['name']);
          } elseif ($HTTP_POST_VARS['sql_file']) {
            $uploaded_file = $HTTP_POST_VARS['sql_file'];
            $read_from = basename($HTTP_POST_VARS['sql_file_name']);
          } else {
            $uploaded_file = $sql_file;
            $read_from = basename($sql_file_name);
          }
          if ($uploaded_file != 'none') {
            if (tep_is_uploaded_file($uploaded_file)) {
              $restore_query = fread(fopen($uploaded_file, 'r'), filesize($uploaded_file));
            }
          }
        }

        if ($restore_query) {
          $sql_array = array();
          $sql_length = strlen($restore_query);
          $pos = strpos($restore_query, ';');
          for ($i=$pos; $i<$sql_length; $i++) {
            if ($restore_query[0] == '#') {
              $restore_query = ltrim(substr($restore_query, strpos($restore_query, "\n")));
              $sql_length = strlen($restore_query);
              $i = strpos($restore_query, ';')-1;
              continue;
            }
            if ($restore_query[($i+1)] == "\n") {
              for ($j=($i+2); $j<$sql_length; $j++) {
                if (trim($restore_query[$j]) != '') {
                  $next = substr($restore_query, $j, 6);
                  if ($next[0] == '#') {
// find out where the break position is so we can remove this line (#comment line)
                    for ($k=$j; $k<$sql_length; $k++) {
                      if ($restore_query[$k] == "\n") break;
                    }
                    $query = substr($restore_query, 0, $i+1);
                    $restore_query = substr($restore_query, $k);
// join the query before the comment appeared, with the rest of the dump
                    $restore_query = $query . $restore_query;
                    $sql_length = strlen($restore_query);
                    $i = strpos($restore_query, ';')-1;
                    continue 2;
                  }
                  break;
                }
              }
              if ($next == '') { // get the last insert query
                $next = 'insert';
              }
              if ( (eregi('create', $next)) || (eregi('insert', $next)) || (eregi('drop t', $next)) ) {
                $next = '';
                $sql_array[] = substr($restore_query, 0, $i);
                $restore_query = ltrim(substr($restore_query, $i+1));
                $sql_length = strlen($restore_query);
                $i = strpos($restore_query, ';')-1;
              }
            }
          }

          $tables_query = tep_db_query('show tables');
          while ($tables = tep_db_fetch_array($tables_query)) {
            list(,$table) = each($tables);
            tep_db_query("drop table " . $table);
          }

          for ($i=0; $i<sizeof($sql_array); $i++) {
            tep_db_query($sql_array[$i]);
          }

          tep_db_query("delete from configuration where configuration_key = 'DB_LAST_RESTORE'");
          tep_db_query("insert into configuration values ('', 'Last Database Restore', 'DB_LAST_RESTORE', '" . $read_from . "', 'Last database restore file', '6', '', '', now(), '', '')");

          if ($remove_raw) {
            unlink($restore_from);
          }
        }
        tep_redirect(tep_href_link(FILENAME_BACKUP));
        break;
      case 'download':
        $extension = substr($HTTP_GET_VARS['file'], -3);
        if ( ($extension == 'zip') || ($extension == '.gz') || ($extension == 'sql') ) {
          if ($fp = fopen(DIR_FS_BACKUP . $HTTP_GET_VARS['file'], 'rb')) {
            $buffer = fread($fp, filesize(DIR_FS_BACKUP . $HTTP_GET_VARS['file']));
            fclose($fp);
            header('Content-type: application/x-octet-stream');
            header('Content-disposition: attachment; filename=' . $HTTP_GET_VARS['file']);
            echo $buffer;
            exit;
          }
        }
    }
  }

// check if the backup directory exists
  if (is_dir(DIR_FS_BACKUP)) {
    if (!is_writeable(DIR_FS_BACKUP)) $errorStack->add(ERROR_BACKUP_DIRECTORY_NOT_WRITEABLE, 'error');
  } else {
    $errorStack->add(ERROR_BACKUP_DIRECTORY_DOES_NOT_EXIST, 'error');
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
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
                <td class="tableHeading"><?php echo TABLE_HEADING_TITLE; ?></td>
                <td align="center" class="tableHeading"><?php echo TABLE_HEADING_FILE_DATE; ?></td>
                <td align="right" class="tableHeading"><?php echo TABLE_HEADING_FILE_SIZE; ?></td>
                <td align="right" class="tableHeading"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="4"><?php echo tep_draw_separator(); ?></td>
              </tr>
<?php
  $dir = @dir(DIR_FS_BACKUP);

  if ($dir) {
    $contents = array();
    while ($file = $dir->read()) {
      if (!is_dir(DIR_FS_BACKUP . $file)) {
        $contents[] = $file;
      }
    }
    sort($contents);

    for ($files=0; $files<sizeof($contents); $files++) {
      $entry = $contents[$files];

      $check = 0;

      if (((!$HTTP_GET_VARS['file']) || ($HTTP_GET_VARS['file'] == $entry)) && (!$buInfo) && ($HTTP_GET_VARS['action'] != 'backup') && ($HTTP_GET_VARS['action'] != 'restorelocal')) {
        $file_array['file'] = $entry;
        $file_array['date'] = date(PHP_DATE_TIME_FORMAT, filemtime(DIR_FS_BACKUP . $entry));
        $file_array['size'] = number_format(filesize(DIR_FS_BACKUP . $entry)) . ' bytes';
        switch (substr($entry, -3)) {
          case 'zip': $file_array['compression'] = 'ZIP'; break;
          case '.gz': $file_array['compression'] = 'GZIP'; break;
          default: $file_array['compression'] = TEXT_NO_EXTENSION;
        }

        $buInfo = new objectInfo($file_array);
      }

      if (is_object($buInfo) && ($entry == $buInfo->file)) {
        echo '              <tr class="selectedRow">' . "\n";
      } else {
        echo '              <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_BACKUP, tep_get_all_get_params(array('file', 'action')) . 'file=' . $entry, 'NONSSL') . '\'">' . "\n";
      }
?>
                <td class="tableData"><?php echo '<a href="' . tep_href_link(FILENAME_BACKUP, 'action=download&file=' . $entry) . '">' . tep_image(DIR_WS_ICONS . 'file_download.gif', ICON_FILE_DOWNLOAD) . '</a>&nbsp;' . $entry; ?></td>
                <td align="center" class="tableData"><?php echo date(PHP_DATE_TIME_FORMAT, filemtime(DIR_FS_BACKUP . $entry)); ?></td>
                <td align="right" class="tableData"><?php echo number_format(filesize(DIR_FS_BACKUP . $entry)); ?> bytes</td>
                <td align="right" class="tableData"><?php if ( (is_object($buInfo)) && ($entry == $buInfo->file) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_BACKUP, tep_get_all_get_params(array('file', 'action')) . 'file=' . $entry) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?
    }
    $dir->close();
  }
?>
              <tr>
                <td colspan="4"><?php echo tep_draw_separator(); ?></td>
              </tr>
              <tr>
                <td class="smallText" colspan="3"><?php echo TEXT_BACKUP_DIRECTORY . ' ' . DIR_FS_BACKUP; ?></td>
                <td align="right" class="smallText"><?php if ($HTTP_GET_VARS['action'] != 'backup') echo '<a href="' . tep_href_link(FILENAME_BACKUP, 'action=backup') . '">' . tep_image(DIR_WS_IMAGES . 'button_backup.gif', IMAGE_BACKUP) . '</a>'; if ($HTTP_GET_VARS['action'] != 'restorelocal') echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_BACKUP, 'action=restorelocal') . '">' . tep_image(DIR_WS_IMAGES . 'button_restore.gif', IMAGE_RESTORE) . '</a>'; ?></td>
              </tr>
<?php
  if (defined('DB_LAST_RESTORE')) {
?>
              <tr>
                <td class="smallText" colspan="4"><?php echo TEXT_LAST_RESTORATION . ' ' . DB_LAST_RESTORE . ' <a href="' . tep_href_link(FILENAME_BACKUP, 'action=forget') . '">' . TEXT_FORGET . '</a>'; ?></td>
              </tr>
<?php
  }
?>
            </table></td>
            <td width="25%" valign="top">
<?php
  $heading = array();
  $contents = array();
  switch ($HTTP_GET_VARS['action']) {
    case 'backup':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_BACKUP . '</b>');

      $contents = array('form' => tep_draw_form('backup', FILENAME_BACKUP, 'action=backupnow'));
      $contents[] = array('text' => TEXT_INFO_NEW_BACKUP);

      if ($errorStack->size > 0) {
        $contents[] = array('text' => '<br>' . tep_draw_radio_field('compress', 'no', true) . ' ' . TEXT_INFO_USE_NO_COMPRESSION);
        $contents[] = array('text' => '<br>' . tep_draw_radio_field('download', 'yes', true) . ' ' . TEXT_INFO_DOWNLOAD_ONLY . '*<br><br>*' . TEXT_INFO_BEST_THROUGH_HTTPS);
      } else {
        $contents[] = array('text' => '<br>' . tep_draw_radio_field('compress', 'gzip', true) . ' ' . TEXT_INFO_USE_GZIP);
        $contents[] = array('text' => tep_draw_radio_field('compress', 'zip') . ' ' . TEXT_INFO_USE_ZIP);
        $contents[] = array('text' => tep_draw_radio_field('compress', 'no') . ' ' . TEXT_INFO_USE_NO_COMPRESSION);
        $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('download', 'yes') . ' ' . TEXT_INFO_DOWNLOAD_ONLY . '*<br><br>*' . TEXT_INFO_BEST_THROUGH_HTTPS);
      }

      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_backup.gif', IMAGE_BACKUP) . '&nbsp;<a href="' . tep_href_link(FILENAME_BACKUP, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'restore':
      $heading[] = array('text' => '<b>' . $buInfo->date . '</b>');

      $contents[] = array('text' => tep_break_string(sprintf(TEXT_INFO_RESTORE, DIR_FS_BACKUP . (($buInfo->compression != TEXT_NO_EXTENSION) ? substr($buInfo->file, 0, strrpos($buInfo->file, '.')) : $buInfo->file), ($buInfo->compression != TEXT_NO_EXTENSION) ? TEXT_INFO_UNPACK : ''), 35, ' '));
      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_BACKUP, tep_get_all_get_params(array('action', 'file')) . 'action=restorenow&file=' . $buInfo->file, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_restore.gif', IMAGE_RESTORE) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_BACKUP, tep_get_all_get_params(array('action', 'file')) . 'file=' . $buInfo->file, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'restorelocal':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_RESTORE_LOCAL . '</b>');

      $contents = array('form' => tep_draw_form('restore', FILENAME_BACKUP, 'action=restorelocalnow', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_INFO_RESTORE_LOCAL . '<br><br>' . TEXT_INFO_BEST_THROUGH_HTTPS);
      $contents[] = array('text' => '<br>' . tep_draw_file_field('sql_file'));
      $contents[] = array('text' => TEXT_INFO_RESTORE_LOCAL_RAW_FILE);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_restore.gif', IMAGE_restore) . '&nbsp;<a href="' . tep_href_link(FILENAME_BACKUP, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($buInfo)) {
        $heading[] = array('text' => '<b>' . $buInfo->date . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_BACKUP, tep_get_all_get_params(array('action', 'file')) . 'action=restore&file=' . $buInfo->file, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_restore.gif', IMAGE_RESTORE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_DATE . ' ' . $buInfo->date);
        $contents[] = array('text' => TEXT_INFO_SIZE . ' ' . $buInfo->size);
        $contents[] = array('text' => '<br>' . TEXT_INFO_COMPRESSION . ' ' . $buInfo->compression);
      }
  }

  $box = new box;
  echo $box->infoBox($heading, $contents);
?>
            </td>
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
