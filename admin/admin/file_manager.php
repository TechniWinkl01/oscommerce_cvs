<?php
/*
  $Id: file_manager.php,v 1.15 2002/01/07 15:23:54 hpdl Exp $

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

  if (strlen($current_path) < strlen(DIR_FS_DOCUMENT_ROOT)) {
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
  }

  if ($HTTP_GET_VARS['action']) {
    switch ($HTTP_GET_VARS['action']) {
      case 'reset':
        tep_session_unregister('current_path');
        tep_redirect(tep_href_link(FILENAME_FILE_MANAGER));
        break;
      case 'deleteconfirm':
        if (is_dir($current_path . '/' . $HTTP_POST_VARS['file_name'])) {
          if (rmdir($current_path . '/' . $HTTP_POST_VARS['file_name']) <> 0) {
            tep_redirect(tep_href_link(FILENAME_FILE_MANAGER));
          }
        } else {
          if (unlink($current_path . '/' . $HTTP_POST_VARS['file_name'])) {
            tep_redirect(tep_href_link(FILENAME_FILE_MANAGER));
          }
        }
        break;
      case 'insert':
        if (mkdir($current_path . '/' . $HTTP_POST_VARS['file_name'], 0777)) {
          tep_redirect(tep_href_link(FILENAME_FILE_MANAGER));
        }
        break;
      case 'save':
        if ($fp = fopen($current_path . '/' . $HTTP_POST_VARS['filename'], 'w+')) {
          fputs($fp, stripslashes($HTTP_POST_VARS['contents']));
          fclose($fp);
          tep_redirect(tep_href_link(FILENAME_FILE_MANAGER));
        }
        break;
      case 'upload':
        if ( ($filename != 'none') && ($filename != '') ) {
          copy($filename, $current_path . '/' . $filename_name);
        }
        tep_redirect(tep_href_link(FILENAME_FILE_MANAGER));
        break;
      case 'download':
        header('Content-type: application/x-octet-stream');
        header('Content-disposition: attachment; filename=' . urldecode($HTTP_GET_VARS['filename']));
        readfile($current_path . '/' . urldecode($HTTP_GET_VARS['filename']));
        exit;
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
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE . '<br><span class="smallText">' . $current_path . '</span>'; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  if ( ($HTTP_GET_VARS['action'] == 'new_file') || ($HTTP_GET_VARS['action'] == 'edit') ) {
?>
      <tr>
        <td><form action="<?php echo tep_href_link(FILENAME_FILE_MANAGER, 'action=save'); ?>" method="post"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2"><?php echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td colspan="2" class="main">&nbsp;</td>
          </tr>
<?php
  $file_contents = '';
  if ($HTTP_GET_VARS['action'] == 'new_file') {
?>
          <tr>
            <td class="main"><?php echo TEXT_FILE_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('filename'); ?></td>
          </tr>
<?php
  } elseif ($HTTP_GET_VARS['action'] == 'edit') {
    if ($file_array = file($current_path . '/' . $HTTP_GET_VARS['filename'])) {
      $file_contents = implode('', $file_array);
    }
?>
          <tr>
            <td class="main"><?php echo TEXT_FILE_NAME; ?></td>
            <td class="main"><?php echo $HTTP_GET_VARS['filename']; ?><input type="hidden" name="filename" value="<?php echo $HTTP_GET_VARS['filename']; ?>"></td>
          </tr>
<?php
  }
?>
          <tr valign="top">
            <td class="main"><?php echo TEXT_FILE_CONTENTS; ?></td>
            <td class="main"><textarea rows="15" cols="60" name="contents"><?php echo $file_contents; ?></textarea></td>
          </tr>
          <tr>
            <td colspan="2" class="main">&nbsp;</td>
          </tr>
          <tr>
            <td class="main">&nbsp;</td>
            <td class="main"><?php echo tep_image_submit(DIR_WS_IMAGES . 'button_save.gif', IMAGE_SAVE); ?>&nbsp;&nbsp;<a href="<?php echo tep_href_link(FILENAME_FILE_MANAGER); ?>"><?php echo tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL); ?></a></td>
          </tr>
        </table></form></td>
      </tr>
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
                            'last_modified' => filemtime($current_path . '/' . $file),
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
                <td class="tableHeading" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="6"><?php echo tep_draw_separator(); ?></td>
              </tr>
<?php
  for ($i=0; $i<sizeof($contents); $i++) {
    if (((!$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['info'] == $contents[$i]['name'])) && (!$fInfo)) {
      $fInfo = new objectInfo($contents[$i]);
    }

    if ( (is_object($fInfo)) && ($contents[$i]['name'] == $fInfo->name) ) {
      echo '              <tr class="selectedRow">' . "\n";
    } else {
      echo '              <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_FILE_MANAGER, 'info=' . $contents[$i]['name']) . '\'">' . "\n";
    }

    if ($contents[$i]['is_dir']) {
      if ($contents[$i]['name'] == '..') {
        $icon = tep_image(DIR_WS_ICONS . 'previous_level.gif', ICON_PREVIOUS_LEVEL);
      } else {
        $icon = ((is_object($fInfo)) && ($contents[$i]['name'] == $fInfo->name) ? tep_image(DIR_WS_ICONS . 'current_folder.gif', ICON_CURRENT_FOLDER) : tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER));
      }
      $link = tep_href_link(FILENAME_FILE_MANAGER, 'goto=' . $contents[$i]['name']);
    } else {
      $icon = tep_image(DIR_WS_ICONS . 'file.gif', ICON_FILE);
      $link = tep_href_link(FILENAME_FILE_MANAGER, 'action=download&filename=' . urlencode($contents[$i]['name']));
    }
?>
                <td class="tableData"><?php echo '<a href="' . $link . '">' . $icon . '</a>&nbsp;' . $contents[$i]['name']; ?></td>
                <td class="tableData" align="right"><?php echo ($contents[$i]['is_dir'] ? '&nbsp;' : $contents[$i]['size']); ?></td>
                <td class="tableData" align="center"><tt><?php echo $contents[$i]['permissions']; ?></tt></td>
                <td class="tableData"><tt><?php echo $contents[$i]['user']; ?></tt></td>
                <td class="tableData"><tt><?php echo $contents[$i]['group']; ?></tt></td>
                <td class="tableData" align="right"><?php if ($contents[$i]['name'] != '..') echo '<a href="' . tep_href_link(FILENAME_FILE_MANAGER, 'info=' . $contents[$i]['name'] . '&action=delete') . '">' . tep_image(DIR_WS_ICONS . 'delete.gif', ICON_DELETE) . '</a>&nbsp;'; if (is_object($fInfo) && ($fInfo->name == $contents[$i]['name'])) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_FILE_MANAGER, 'info=' . $contents[$i]['name']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="6"><?php echo tep_draw_separator(); ?></td>
              </tr>
              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr valign="top">
                    <td class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_FILE_MANAGER, 'action=reset') . '">' . tep_image(DIR_WS_IMAGES . 'button_reset.gif', IMAGE_RESET) . '</a>'; ?><form action="<?php echo tep_href_link(FILENAME_FILE_MANAGER, 'action=upload'); ?>" method="post" enctype="multipart/form-data"><input type="file" size="10" name="filename"><?php echo tep_image_submit(DIR_WS_IMAGES . 'button_upload.gif', IMAGE_UPLOAD); ?></form></td>
                    <td align="right" class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_FILE_MANAGER, 'action=new_file') . '">' . tep_image(DIR_WS_IMAGES . 'button_new_file.gif', IMAGE_NEW_FILE) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_FILE_MANAGER, 'action=new_folder') . '">' . tep_image(DIR_WS_IMAGES . 'button_new_folder.gif', IMAGE_NEW_FOLDER) . '</a>'; ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="25%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
    $info_box_contents = array();
    if ($HTTP_GET_VARS['action'] == 'new_folder') {
      $info_box_contents[] = array('text' => '<b>' . TEXT_NEW_FOLDER . '</b>');
    } else {
      $info_box_contents[] = array('text' => '<b>' . $fInfo->name . '</b>');
    }
?>
              <tr class="boxHeading">
                <td><?php new infoBoxHeading($info_box_contents); ?></td>
              </tr>
              <tr class="boxHeading">
                <td><?php echo tep_draw_separator(); ?></td>
              </tr>
<?php
    switch ($HTTP_GET_VARS['action']) {
      case 'delete':
        $info_box_contents = array('form' => tep_draw_form('file', FILENAME_FILE_MANAGER, 'info=' . $fInfo->name . '&action=deleteconfirm'));
        $info_box_contents[] = array('text' => TEXT_DELETE_INTRO);
        $info_box_contents[] = array('text' => '<br><b>' . $fInfo->name . '</b>');
        $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_FILE_MANAGER, 'info=' . $fInfo->name) . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'new_folder':
        $info_box_contents = array('form' => tep_draw_form('file', FILENAME_FILE_MANAGER, 'info=' . $fInfo->name . '&action=insert'));
        $info_box_contents[] = array('text' => TEXT_NEW_FOLDER_INTRO);
        $info_box_contents[] = array('text' => '<br>' . TEXT_FILE_NAME . '<br>' . tep_draw_input_field('file_name'));
        $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_FILE_MANAGER, 'info=' . $fInfo->name) . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      default:
        $info_box_contents = array();
        if (is_object($fInfo)) {
          if (!$fInfo->is_dir) $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_FILE_MANAGER, 'info=' . $fInfo->name . '&action=edit') . '">' . tep_image(DIR_WS_IMAGES . 'button_edit.gif', IMAGE_EDIT) . '</a>');
          $info_box_contents[] = array('text' => '<br>' . TEXT_FILE_NAME . ' <b>' . $fInfo->name . '</b>');
          if (!$fInfo->is_dir) $info_box_contents[] = array('text' => '<br>' . TEXT_FILE_SIZE . ' <b>' . $fInfo->size . '</b>');
          $info_box_contents[] = array('text' => '<br>' . TEXT_LAST_MODIFIED . ' ' . strftime(DATE_FORMAT_SHORT, $fInfo->last_modified));
        }
    }
?>
              <tr>
                <td class="box"><?php new infoBox($info_box_contents); ?></td>
              </tr>
              <tr>
                <td class="box"><?php echo tep_draw_separator(); ?></td>
              </tr>
            </table></td>
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
