<?php
/*
  $Id: file_manager.php,v 1.1 2001/10/21 16:07:30 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('current_path')) {
    $current_path = DIR_FS_DOCUMENT_ROOT;
    tep_session_register('current_path');
  }

  if ($HTTP_GET_VARS['goto']) {
    if ($HTTP_GET_VARS['goto'] == '..') {
      $current_path = substr($current_path, 0, strrpos($current_path, '/'));
    } else {
      $current_path .= '/' . $HTTP_GET_VARS['goto'];
    }
    tep_redirect(tep_href_link(FILENAME_FILE_MANAGER));
  }

  if ($HTTP_GET_VARS['action']) {
    switch ($HTTP_GET_VARS['action']) {
      case 'deleteconfirm':
        // Folder
        if (is_dir($current_path . '/' . $HTTP_POST_VARS['file_name'])) {
          if (rmdir($current_path . '/' . $HTTP_POST_VARS['file_name']) <> 0) {
            tep_redirect(tep_href_link(FILENAME_FILE_MANAGER));
          }
        // File
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
    }
  }

  $directory_array = array();
  $dir = opendir($current_path);
  $file_count = 0;
  while ($dir && ($file = readdir($dir)) ) {
    if ( ($file <> '.') && ($file <> 'CVS') && ($file <> '..' || $current_path <> DIR_FS_DOCUMENT_ROOT) ) {
      $file_count ++;
      $directory_array[$file_count] = array('name' => $file, 
                                            'is_dir' => is_dir($current_path . '/' . $file),
                                            'last_modified' => filemtime($current_path . '/' . $file)
                                           );
    }
  }
  
  function cmp($a, $b) {
    return strcmp( ($a['is_dir']?'D':'F') . $a['name'], ($b['is_dir']?'D':'F') . $b['name']);
  }
  uasort($directory_array, "cmp");
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
            <td class="topBarTitle">&nbsp;<?php echo TOP_BAR_TITLE . $current_path; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<?php echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'table_background_button.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
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
                <td class="tableHeading">&nbsp;</td>
                <td class="tableHeading" width="100%">&nbsp;<?php echo TABLE_HEADING_FILENAME; ?>&nbsp;</td>
                <td class="tableHeading">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3"><?php echo tep_black_line(); ?></td>
              </tr>
<?
  reset($directory_array);
  while (list($key,$file) = each($directory_array)) {
    if ( ($key == $HTTP_GET_VARS['info']) || (!$HTTP_GET_VARS['info'] && !$fmInfo) ) {
      $file['key'] = $key;
      $fmInfo = new fileManagerInfo($file);
?>
              <tr class="selectedRow">
<?
    } else {
?>
              <tr class="tableRow" onmouseover="this.className='tableRowOver';this.style.cursor='hand'" onmouseout="this.className='tableRow'" onclick="document.location.href='<? echo tep_href_link(FILENAME_FILE_MANAGER, 'info=' . $key); ?>'">
<?
    }
    if ($file['is_dir']) {
      $icon = ($key == $HTTP_GET_VARS['info'] ? 'icon_current_folder.gif' : 'icon_folder.gif');
?>          
                <td class="main"><?php echo tep_image(DIR_WS_IMAGES . $icon); ?></td>
                <td class="main"><a href="<? echo tep_href_link(FILENAME_FILE_MANAGER, 'goto=' . $file['name']); ?>"><?php echo $file['name']; ?></a></td>
<?
    } else {
?>          
                <td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'icon_file.gif'); ?></td>
                <td class="main"><?php echo $file['name']; ?></td>
<?
    }
    if ($fmInfo->key == $key) {
?>
                <td align="center" class="main"><?php echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); ?>&nbsp;</td>
<?php
    } else {
?>
                <td align="center" class="main"><a href="<? echo tep_href_link(FILENAME_FILE_MANAGER, 'info=' . $key); ?>"><? echo tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO); ?></a>&nbsp;</td>
<?php
    }
?>
              </tr>
<?
  }
?>          
              <tr>
                <td colspan="3"><?php echo tep_black_line(); ?></td>
              </tr>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td align="right" class="smallText"><a href="<?php echo tep_href_link(FILENAME_FILE_MANAGER, 'action=new_folder'); ?>"><?php echo tep_image(DIR_WS_IMAGES . 'button_new_folder.gif', IMAGE_NEW_FOLDER); ?></a>&nbsp;&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="25%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
    $info_box_contents = array();
    if ($HTTP_GET_VARS['action'] == 'new_folder') {
      $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . TEXT_NEW_FOLDER . '</b>');
    } else {
      $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $fmInfo->name . '</b>');
    }
?>
              <tr class="boxHeading">
                <td><?php new infoBoxHeading($info_box_contents); ?></td>
              </tr>
              <tr class="boxHeading">
                <td><?php echo tep_black_line(); ?></td>
              </tr>
<?php
/* here we display the appropiate info box on the right of the main table */
    switch ($HTTP_GET_VARS['action']) {
/* delete file */
      case 'delete':
        $form = '<form action="' . tep_href_link(FILENAME_FILE_MANAGER, 'action=deleteconfirm') . '" method="post"><input type="hidden" name="file_name" value="' . $fmInfo->name . '">';

        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left', 'text' => TEXT_DELETE_INTRO);
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;<b>' . $fmInfo->name . '</b>');
        $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_FILE_MANAGER, 'info=' . $fmInfo->key) . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');

        break;
/* new folder box contents */
      case 'new_folder':
        $form = '<form action="' . tep_href_link(FILENAME_FILE_MANAGER, 'action=insert') . '" method="post">';

        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left', 'text' => TEXT_NEW_FOLDER_INTRO . '<br>');
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . TEXT_FILE_NAME . '<br><input type="text" name="file_name"><br>');
        $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_FILE_MANAGER) . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');

        break;
/* display default info boxes */
      default:
        if ($fmInfo) { // category info box contents
          $info_box_contents = array();
          if ($fmInfo->name <> '..') $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_FILE_MANAGER, 'action=delete&info=' . $fmInfo->key ) . '">' . tep_image(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . '</a>');
          $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_FILE_NAME . ' <b>' . $fmInfo->name . '</b>');
          $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_LAST_MODIFIED . ' ' . strftime(DATE_FORMAT_SHORT, $fmInfo->last_modified));
        }
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
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>