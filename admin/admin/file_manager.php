<?php
/*
  $Id: file_manager.php,v 1.26 2002/01/11 23:34:46 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('current_path')) {
    $current_path = DIR_FS_DOCUMENT_ROOT;
    tep_session_register('current_path');
  }

  if (!is_dir($current_path)) {
    $current_path = DIR_FS_DOCUMENT_ROOT;
  }

  if (!ereg(DIR_FS_DOCUMENT_ROOT, $current_path)) {
    $current_path = DIR_FS_DOCUMENT_ROOT;
  }

  if ($HTTP_GET_VARS['goto']) {
    if (ereg('../', $HTTP_GET_VARS['goto'])) {
      $current_path = DIR_FS_DOCUMENT_ROOT;
    } elseif ($HTTP_GET_VARS['goto'] == '..') {
      $current_path = substr($current_path, 0, strrpos($current_path, '/'));
    } else {
      $current_path .= '/' . $HTTP_GET_VARS['goto'];
    }
    tep_redirect(tep_href_link(FILENAME_FILE_MANAGER));
  } elseif ($HTTP_GET_VARS['current_path']) {
    $new_path = urldecode($HTTP_GET_VARS['current_path']);
    if (strstr($new_path, '../')) {
      $current_path = DIR_FS_DOCUMENT_ROOT;
    } elseif (!ereg(DIR_FS_DOCUMENT_ROOT, $new_path)) {
      $current_path = DIR_FS_DOCUMENT_ROOT;
    } else {
      $current_path = $new_path;
    }
    tep_redirect(tep_href_link(FILENAME_FILE_MANAGER));
  }

  if ($HTTP_GET_VARS['action']) {
    switch ($HTTP_GET_VARS['action']) {
      case 'reset':
        tep_session_unregister('current_path');
        tep_redirect(tep_href_link(FILENAME_FILE_MANAGER));
        break;
      case 'deleteconfirm':
        tep_remove($current_path . '/' . $HTTP_GET_VARS['info']);
        if (!$tep_remove_error) tep_redirect(tep_href_link(FILENAME_FILE_MANAGER));
        break;
      case 'insert':
        if (mkdir($current_path . '/' . $HTTP_POST_VARS['folder_name'], 0777)) {
          tep_redirect(tep_href_link(FILENAME_FILE_MANAGER, 'info=' . urlencode($HTTP_POST_VARS['folder_name'])));
        }
        break;
      case 'save':
        if ($fp = fopen($current_path . '/' . $HTTP_POST_VARS['filename'], 'w+')) {
          fputs($fp, stripslashes($HTTP_POST_VARS['file_contents']));
          fclose($fp);
          tep_redirect(tep_href_link(FILENAME_FILE_MANAGER, 'info=' . urlencode($HTTP_POST_VARS['filename'])));
        }
        break;
      case 'processuploads':
        for ($i=1; $i<6; $i++) {
          if ($HTTP_POST_FILES['file_' . $i]) {
            $file = $HTTP_POST_FILES['file_' . $i]['tmp_name'];
            $file_name = $HTTP_POST_FILES['file_' . $i]['name'];
          } elseif ($HTTP_POST_VARS['file_' . $i]) {
            $file = $HTTP_POST_VARS['file_' . $i];
            $file_name = $HTTP_POST_VARS['file_' . $i . '_name'];
          } else {
            $file = ${'file_' . $i};
            $file_name = ${'file_' . $i . '_name'};
          }

          if ( ($file) && ($file != 'none') && (tep_is_uploaded_file($file)) ) {
            copy($file, $current_path . '/' . $file_name);
          }
        }

        tep_redirect(tep_href_link(FILENAME_FILE_MANAGER));
        break;
      case 'download':
        header('Content-type: application/x-octet-stream');
        header('Content-disposition: attachment; filename=' . urldecode($HTTP_GET_VARS['filename']));
        readfile($current_path . '/' . urldecode($HTTP_GET_VARS['filename']));
        exit;
        break;
      case 'upload':
      case 'new_folder':
      case 'new_file':
        $directory_writeable = true;
        if (!is_writeable($current_path)) {
          $directory_writeable = false;
          $errorStack->add(sprintf(ERROR_DIRECTORY_NOT_WRITEABLE, $current_path), 'error');
        }
        break;
      case 'edit':
        $file_writeable = true;
        if (!is_writeable($current_path . '/' . $HTTP_GET_VARS['info'])) {
          $file_writeable = false;
          $errorStack->add(sprintf(ERROR_FILE_NOT_WRITEABLE, $current_path . '/' . $HTTP_GET_VARS['info']), 'error');
        }
        break;
    }
  }

  $in_directory = substr(substr(DIR_FS_DOCUMENT_ROOT, strrpos(DIR_FS_DOCUMENT_ROOT, '/')), 1);
  $current_path_array = explode('/', $current_path);
  $document_root_array = explode('/', DIR_FS_DOCUMENT_ROOT);
  $goto_array = array(array('id' => DIR_FS_DOCUMENT_ROOT, 'text' => $in_directory));
  for ($i=0; $i<sizeof($current_path_array); $i++) {
    if ($current_path_array[$i] != $document_root_array[$i]) {
      $goto_array[] = array('id' => implode('/', tep_array_slice($current_path_array, 0, $i+1)), 'text' => $current_path_array[$i]);
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
          <tr><?php echo tep_draw_form('goto', FILENAME_FILE_MANAGER, '', 'get'); ?>
            <td class="pageHeading"><?php echo HEADING_TITLE . '<br><span class="smallText">' . $current_path . '</span>'; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', HEADING_IMAGE_HEIGHT); ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_pull_down_menu('current_path', $goto_array, '', 'onChange="this.form.submit();"'); ?></td>
          </form></tr>
        </table></td>
      </tr>
<?php
  if ( ($directory_writeable) && ($HTTP_GET_VARS['action'] == 'new_file') || ($HTTP_GET_VARS['action'] == 'edit') ) {
    if (!isset($file_writeable)) $file_writeable = true;
    $file_contents = '';
    if ($HTTP_GET_VARS['action'] == 'new_file') {
      $filename_input_field = tep_draw_input_field('filename');
    } elseif ($HTTP_GET_VARS['action'] == 'edit') {
      if ($file_array = file($current_path . '/' . $HTTP_GET_VARS['info'])) {
        $file_contents = htmlentities(implode('', $file_array));
      }
      $filename_input_field = $HTTP_GET_VARS['info'] . tep_draw_hidden_field('filename', $HTTP_GET_VARS['info']);
    }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('new_file', FILENAME_FILE_MANAGER, 'action=save'); ?>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_FILE_NAME; ?></td>
            <td class="main"><?php echo $filename_input_field; ?></td>
          </tr>
          <tr>
            <td class="main" valign="top"><?php echo TEXT_FILE_CONTENTS; ?></td>
            <td class="main"><?php echo tep_draw_textarea_field('file_contents', 'soft', '80', '20', $file_contents, (($file_writeable) ? '' : 'readonly')); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td align="right" class="main" colspan="2"><?php if ($file_writeable) echo tep_image_submit(DIR_WS_IMAGES . 'button_save.gif', IMAGE_SAVE) . '&nbsp;'; echo '<a href="' . tep_href_link(FILENAME_FILE_MANAGER, 'info=' . urlencode($HTTP_GET_VARS['info'])) . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
          </tr>
        </table></td>
      </form></tr>
<?php
  } else {
    $showuser = (function_exists('posix_getpwuid') ? true : false);
    $contents = array();
    $dir = dir($current_path);
    while ($file = $dir->read()) {
      if ( ($file != '.') && ($file != 'CVS') && ( ($file != '..') || ($current_path != DIR_FS_DOCUMENT_ROOT) ) ) {
        $file_size = number_format(filesize($current_path . '/' . $file)) . ' bytes';

        $permissions = tep_get_file_permissions(fileperms($current_path . '/' . $file));
        if ($showuser) {
          $user = posix_getpwuid(fileowner($current_path . '/' . $file));
          $group = posix_getgrgid($user['gid']);
        } else {
          $user = $group = array();
        }

        $contents[] = array('name' => $file,
                            'is_dir' => is_dir($current_path . '/' . $file),
                            'last_modified' => strftime(DATE_TIME_FORMAT, filemtime($current_path . '/' . $file)),
                            'size' => $file_size,
                            'permissions' => $permissions,
                            'user' => $user['name'],
                            'group' => $group['name']);
      }
    }

    function tep_cmp($a, $b) {
      return strcmp( ($a['is_dir'] ? 'D' : 'F') . $a['name'], ($b['is_dir'] ? 'D' : 'F') . $b['name']);
    }
    usort($contents, 'tep_cmp');
?>

      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2"><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="tableHeading"><?php echo TABLE_HEADING_FILENAME; ?></td>
                <td class="tableHeading" align="right"><?php echo TABLE_HEADING_SIZE; ?></td>
                <td class="tableHeading" align="center"><?php echo TABLE_HEADING_PERMISSIONS; ?></td>
                <td class="tableHeading"><?php echo TABLE_HEADING_USER; ?></td>
                <td class="tableHeading"><?php echo TABLE_HEADING_GROUP; ?></td>
                <td class="tableHeading" align="center"><?php echo TABLE_HEADING_LAST_MODIFIED; ?></td>
                <td class="tableHeading" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="7"><?php echo tep_draw_separator(); ?></td>
              </tr>
<?php
  for ($i=0; $i<sizeof($contents); $i++) {
    if (((!$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['info'] == $contents[$i]['name'])) && (!$fInfo) && ($HTTP_GET_VARS['action'] != 'upload') && ($HTTP_GET_VARS['action'] != 'new_folder') ) {
      $fInfo = new objectInfo($contents[$i]);
    }

    if ( (is_object($fInfo)) && ($contents[$i]['name'] == $fInfo->name) ) {
      echo '              <tr class="selectedRow">' . "\n";
    } else {
      echo '              <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_FILE_MANAGER, 'info=' . urlencode($contents[$i]['name'])) . '\'">' . "\n";
    }

    if ($contents[$i]['is_dir']) {
      if ($contents[$i]['name'] == '..') {
        $icon = tep_image(DIR_WS_ICONS . 'previous_level.gif', ICON_PREVIOUS_LEVEL);
      } else {
        $icon = ((is_object($fInfo)) && ($contents[$i]['name'] == $fInfo->name) ? tep_image(DIR_WS_ICONS . 'current_folder.gif', ICON_CURRENT_FOLDER) : tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER));
      }
      $link = tep_href_link(FILENAME_FILE_MANAGER, 'goto=' . $contents[$i]['name']);
    } else {
      $icon = tep_image(DIR_WS_ICONS . 'file_download.gif', ICON_FILE_DOWNLOAD);
      $link = tep_href_link(FILENAME_FILE_MANAGER, 'action=download&filename=' . urlencode($contents[$i]['name']));
    }
?>
                <td class="tableData"><?php echo '<a href="' . $link . '">' . $icon . '</a>&nbsp;' . $contents[$i]['name']; ?></td>
                <td class="tableData" align="right"><?php echo ($contents[$i]['is_dir'] ? '&nbsp;' : $contents[$i]['size']); ?></td>
                <td class="tableData" align="center"><tt><?php echo $contents[$i]['permissions']; ?></tt></td>
                <td class="tableData"><?php echo $contents[$i]['user']; ?></td>
                <td class="tableData"><?php echo $contents[$i]['group']; ?></td>
                <td class="tableData" align="center"><?php echo $contents[$i]['last_modified']; ?></td>
                <td class="tableData" align="right"><?php if ($contents[$i]['name'] != '..') echo '<a href="' . tep_href_link(FILENAME_FILE_MANAGER, 'info=' . urlencode($contents[$i]['name']) . '&action=delete') . '">' . tep_image(DIR_WS_ICONS . 'delete.gif', ICON_DELETE) . '</a>&nbsp;'; if (is_object($fInfo) && ($fInfo->name == $contents[$i]['name'])) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_FILE_MANAGER, 'info=' . urlencode($contents[$i]['name'])) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="7"><?php echo tep_draw_separator(); ?></td>
              </tr>
              <tr>
                <td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr valign="top">
                    <td class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_FILE_MANAGER, 'action=reset') . '">' . tep_image(DIR_WS_IMAGES . 'button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
                    <td class="smallText" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_FILE_MANAGER, 'info=' . urlencode($HTTP_GET_VARS['info']) . '&action=upload') . '">' . tep_image(DIR_WS_IMAGES . 'button_upload.gif', IMAGE_UPLOAD) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_FILE_MANAGER, 'info=' . urlencode($HTTP_GET_VARS['info']) . '&action=new_file') . '">' . tep_image(DIR_WS_IMAGES . 'button_new_file.gif', IMAGE_NEW_FILE) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_FILE_MANAGER, 'info=' . urlencode($HTTP_GET_VARS['info']) . '&action=new_folder') . '">' . tep_image(DIR_WS_IMAGES . 'button_new_folder.gif', IMAGE_NEW_FOLDER) . '</a>'; ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="25%" valign="top">
<?php
    $heading = array();
    $contents = array();
    switch ($HTTP_GET_VARS['action']) {
      case 'delete':
        $heading[] = array('text' => '<b>' . $fInfo->name . '</b>');

        $contents = array('form' => tep_draw_form('file', FILENAME_FILE_MANAGER, 'info=' . urlencode($fInfo->name) . '&action=deleteconfirm'));
        $contents[] = array('text' => TEXT_DELETE_INTRO);
        $contents[] = array('text' => '<br><b>' . $fInfo->name . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_FILE_MANAGER, 'info=' . urlencode($fInfo->name)) . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'new_folder':
        $heading[] = array('text' => '<b>' . TEXT_NEW_FOLDER . '</b>');

        $contents = array('form' => tep_draw_form('folder', FILENAME_FILE_MANAGER, 'action=insert'));
        $contents[] = array('text' => TEXT_NEW_FOLDER_INTRO);
        $contents[] = array('text' => '<br>' . TEXT_FILE_NAME . '<br>' . tep_draw_input_field('folder_name'));
        $contents[] = array('align' => 'center', 'text' => '<br>' . (($directory_writeable) ? tep_image_submit(DIR_WS_IMAGES . 'button_save.gif', IMAGE_SAVE) : '') . ' <a href="' . tep_href_link(FILENAME_FILE_MANAGER, 'info=' . urlencode($HTTP_GET_VARS['info'])) . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'upload':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_UPLOAD . '</b>');

        $contents = array('form' => tep_draw_form('file', FILENAME_FILE_MANAGER, 'action=processuploads', 'post', 'enctype="multipart/form-data"'));
        $contents[] = array('text' => TEXT_UPLOAD_INTRO);
        for ($i=1; $i<6; $i++) $file_upload .= tep_draw_file_field('file_' . $i) . '<br>';
        $contents[] = array('text' => '<br>' . $file_upload);
        $contents[] = array('align' => 'center', 'text' => '<br>' . (($directory_writeable) ? tep_image_submit(DIR_WS_IMAGES . 'button_upload.gif', IMAGE_UPLOAD) : '') . ' <a href="' . tep_href_link(FILENAME_FILE_MANAGER, 'info=' . urlencode($HTTP_GET_VARS['info'])) . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      default:
        if (is_object($fInfo)) {
          $heading[] = array('text' => '<b>' . $fInfo->name . '</b>');

          if (!$fInfo->is_dir) $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_FILE_MANAGER, 'info=' . urlencode($fInfo->name) . '&action=edit') . '">' . tep_image(DIR_WS_IMAGES . 'button_edit.gif', IMAGE_EDIT) . '</a>');
          $contents[] = array('text' => '<br>' . TEXT_FILE_NAME . ' <b>' . $fInfo->name . '</b>');
          if (!$fInfo->is_dir) $contents[] = array('text' => '<br>' . TEXT_FILE_SIZE . ' <b>' . $fInfo->size . '</b>');
          $contents[] = array('text' => '<br>' . TEXT_LAST_MODIFIED . ' ' . $fInfo->last_modified);
        }
    }

    $box = new box;
    echo $box->infoBox($heading, $contents);
?>
            </td>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
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
