<?php
/*
  $Id: backup.php,v 1.17 2001/11/20 22:44:20 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ($HTTP_GET_VARS['action'] == 'backupnow') {
    tep_set_time_limit(180);
    $tables_query = tep_db_query('show tables');
    while ($tables = tep_db_fetch_array($tables_query)) {
      $table = $tables[0];
      $schema .= 'drop table if exists ' . $table . ';' . "\n" .
                 'create table ' . $table . ' (' . "\n";
      $table_list = array();
      $fields_query = tep_db_query("show fields from " . $table);
      while ($fields = tep_db_fetch_array($fields_query)) {
        $table_list[] = $fields['Field'];

        $schema .= '  ' . $fields['Field'] . ' ' . $fields['Type'];
        if (strlen($fields['Default']) > 0) {
          $schema .= ' default \'' . $fields['Default'] . '\'';
        }
        if ($fields['Null'] != 'YES') {
          $schema .= ' not null';
        }
        if (isset($fields['Extra'])) {
          $schema .= ' ' . $fields['Extra'];
        }
        $schema .= ',' . "\n";
      }
      $schema = ereg_replace(",\n$", '', $schema);
      // Add the keys
      $index = array();
      $keys_query = tep_db_query("show keys from " . $table);
      while ($keys = tep_db_fetch_array($keys_query)) {
        $kname = $keys['Key_name'];
        if(!isset($index[$kname])) {
          $index[$kname] = array('unique' => !$keys['Non_unique'],
                                 'columns' => array()
                                );
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
        $schema_insert = 'insert into ' . $table . ' (' . implode(',', $table_list) . ') values (';
        $num_fields = sizeof($table_list);
        for ($i=0; $i<$num_fields; $i++) {
          if (!isset($rows[$i])) {
            $schema_insert .= ' NULL,';
          } elseif ($rows[$i] != '') {
            $schema_insert .= ' \'' . addslashes($rows[$i]) . '\',';
          } else {
            $schema_insert .= ' \'\',';
          }
        }
        $schema_insert = ereg_replace(',$', '', $schema_insert);
        $schema_insert .= ');' . "\n";
        $schema .= trim($schema_insert);
      }
      $schema .= "\n";
    }

    $backup_file = DIR_FS_BACKUP . 'db_' . DB_DATABASE . '-' . date('YmdHis') . '.sql';
    if ($fp = fopen($backup_file, 'w')) {
      fputs($fp, $schema);
      fclose($fp);
      switch ($HTTP_POST_VARS['compress']) {
        case 'gzip': exec(LOCAL_EXE_GZIP . ' ' . $backup_file);
                     break;
        case 'zip':  exec(LOCAL_EXE_ZIP . ' -j ' . $backup_file . '.zip ' . $backup_file);
                     unlink($backup_file);
                     break;
      }
    } else {
      echo 'Could not write to <b>' . $backup_file . '</b>. Please check the directory permissions.';
    }
    header('Location: ' . tep_href_link('backup.php')); exit;
  } elseif ($HTTP_GET_VARS['action'] == 'download') {
    $extension = substr($HTTP_GET_VARS['backup'], -3);
    if ( ($extension == 'zip') || ($extension == '.gz') || ($extension == 'sql') ) {
      if ($fp = fopen(DIR_FS_BACKUP . $HTTP_GET_VARS['backup'], 'rb')) {
        $buffer = fread($fp, filesize(DIR_FS_BACKUP . $HTTP_GET_VARS['backup']));
        header('Content-type: application/x-octet-stream');
        header('Content-disposition: attachment; filename=' . $HTTP_GET_VARS['backup']);
        echo $buffer;
        exit;
      }
    }
  }
?>
<html>
<head>
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<?php echo HEADING_TITLE; ?>&nbsp;</td>
            <td class="pageHeading" align="right">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
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
                <td class="tableHeading">&nbsp;<?php echo TABLE_HEADING_TITLE; ?>&nbsp;</td>
                <td align="center" class="tableHeading">&nbsp;<?php echo TABLE_HEADING_FILE_DATE; ?>&nbsp;</td>
                <td align="right" class="tableHeading">&nbsp;<?php echo TABLE_HEADING_FILE_SIZE; ?>&nbsp;</td>
                <td align="right" class="tableHeading">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="4"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
  $dir = opendir(DIR_FS_BACKUP);

  if ($dir) {
    $directory_array = array();
    while ($file = readdir($dir)) {
      if (!is_dir(DIR_FS_BACKUP . $file)) {
        $directory_array[] = $file;
      }
    }
    sort($directory_array);

    for ($files=0; $files<sizeof($directory_array); $files++) {
      $entry = $directory_array[$files];

      $check = 0;

      if (((!$HTTP_GET_VARS['info']) || ($HTTP_GET_VARS['info'] == $entry)) && (!$buInfo) && (!$HTTP_GET_VARS['action'])) {
        $buInfo = new backupInfo(array('entry' => $entry));
      }

      if (is_object($buInfo) && ($entry == $buInfo->file)) {
        echo '              <tr class="selectedRow">' . "\n";
      } else {
        echo '              <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_BACKUP, tep_get_all_get_params(array('info', 'action')) . 'info=' . $entry, 'NONSSL') . '\'">' . "\n";
      }
?>
                <td class="smallText">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_BACKUP, 'action=download&backup=' . $entry) . '">' . $entry . '</a>'; ?>&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<?php echo date(PHP_DATE_TIME_FORMAT, filemtime(DIR_FS_BACKUP . $entry)); ?>&nbsp;</td>
                <td align="right" class="smallText">&nbsp;<?php echo number_format(filesize(DIR_FS_BACKUP . $entry)); ?> bytes&nbsp;</td>
<?php
      if (is_object(buInfo) && ($entry == $buInfo->file)) {
?>
                <td align="right" class="smallText">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); ?>&nbsp;</td>
<?php
      } else {
?>
                <td align="right" class="smallText">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_BACKUP, tep_get_all_get_params(array('info', 'action')) . 'info=' . $entry, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; ?>&nbsp;</td>
<?php
      }
?>

              </tr>
<?
    }
  }
  closedir($dir);
?>
              <tr>
                <td colspan="4"><?php echo tep_black_line(); ?></td>
              </tr>
              <tr>
                <td class="smallText" colspan="3">Backup Directory: <?php echo DIR_FS_BACKUP; ?></td>
                <td align="right" class="smallText"><?php echo (($HTTP_GET_VARS['action'] == 'backup') ? '&nbsp;' : '<a href="' . tep_href_link('backup.php', 'action=backup') . '">' . tep_image(DIR_WS_IMAGES . 'button_backup.gif', IMAGE_BACKUP) . '</a>'); ?></td>
              </tr>
            </table></td>
            <td width="25%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
  $info_box_contents = array();
  if ($buInfo) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $buInfo->date . '</b>&nbsp;');
  if ((!$buInfo) && ($HTTP_GET_VARS['action'] == 'backup')) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . TEXT_INFO_HEADING_NEW_BACKUP . '</b>&nbsp;');
?>
              <tr class="boxHeading">
                <td><?php new infoBoxHeading($info_box_contents); ?></td>
              </tr>
              <tr class="boxHeading">
                <td><?php echo tep_black_line(); ?></td>
              </tr>
<?php
  if ($HTTP_GET_VARS['action'] == 'backup') {
    $form = tep_draw_form('backup', FILENAME_BACKUP, 'action=backupnow');

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_NEW_BACKUP);
    $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . tep_draw_radio_field('compress', 'gzip', true) . ' Use GZIP');
    $info_box_contents[] = array('align' => 'left', 'text' => tep_draw_radio_field('compress', 'zip') . ' Use ZIP');
    $info_box_contents[] = array('align' => 'left', 'text' => tep_draw_radio_field('compress', 'no') . ' No Compression (Pure SQL)');
    $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_backup.gif', IMAGE_BACKUP) . '&nbsp;<a href="' . tep_href_link(FILENAME_BACKUP, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
  } else {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_DATE . ' ' . $buInfo->date);
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_SIZE . ' ' . $buInfo->size);
    $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . TEXT_INFO_COMPRESSION . ' ' . $buInfo->compression);
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
