<?php
/*
  $Id: define_language.php,v 1.5 2001/12/06 18:10:42 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// Save to file
  if ( ($HTTP_GET_VARS['action'] == 'save') && ($HTTP_GET_VARS['directory']) && ($HTTP_GET_VARS['filename']) ) {
    $full_filename = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_LANGUAGES . $HTTP_GET_VARS['filename'];
    if (file_exists($full_filename . '.bak')) {
      unlink($full_filename . '.bak');
    }
    rename($full_filename, $full_filename . '.bak');
    $new_file = fopen($full_filename, 'w');
    $file_contents = str_replace("\r\n", "\n", stripslashes($HTTP_POST_VARS['file_contents']));
    fwrite($new_file, $file_contents, strlen($file_contents));
    fclose($new_file);
    Header('Location: ' . tep_href_link(FILENAME_DEFINE_LANGUAGE, 'directory=' . $HTTP_GET_VARS['directory']));
    tep_exit();
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><?php echo tep_black_line(); ?></td>
          </tr>
          <tr class="subBar">
            <td class="subBar">&nbsp;<?php echo SUB_BAR_TITLE; ?>&nbsp;</td>
          </tr>
          <tr>
            <td><?php echo tep_black_line(); ?></td>
          </tr>
<?php
// Edit constants in selected file
  if ( ($HTTP_GET_VARS['directory']) && ($HTTP_GET_VARS['filename']) ) {
?>
          <tr>
            <td><form action="<?php echo tep_href_link(FILENAME_DEFINE_LANGUAGE, 'directory=' . $HTTP_GET_VARS['directory'] . '&filename=' . $HTTP_GET_VARS['filename'] . '&action=save'); ?>" method="post"><table border="0" cellspacing="0" cellpadding="0">
<?php
    $full_filename = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_LANGUAGES . urldecode($HTTP_GET_VARS['filename']);
    $file_array = file($full_filename);
    $file_contents = implode('', $file_array);
?>
              <tr>
                <td class="smallText">&nbsp;<b><?php echo $HTTP_GET_VARS['filename']; ?></b><br>&nbsp;<textarea name="file_contents" wrap="off" cols="60" rows="15"><?php echo $file_contents; ?></textarea>&nbsp;</td>
              </tr>
              <tr>
                <td class="smallText">&nbsp;</td>
              </tr>
              <tr>
                <td class="smallText" align="right"><br><?php echo tep_image_submit(DIR_WS_IMAGES . 'button_save.gif', IMAGE_SAVE); ?>&nbsp;</td>
              </tr>
            </table></form></td>
          </tr>
<?php
// List selection of files to edit
  } else {
    $filename = $HTTP_GET_VARS['directory'] . '.php';
?>
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="smallText">&nbsp;<a href="<?php echo tep_href_link(FILENAME_DEFINE_LANGUAGE, 'directory=' . $HTTP_GET_VARS['directory'] . '&filename=' . $filename); ?>"><b><?php echo $filename; ?></b></a>&nbsp;</td>
<?php
    $dir = dir(DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_LANGUAGES . $HTTP_GET_VARS['directory']);
    $left = false;
    if ($dir) {
      while($entry = $dir->read()) {
        if (eregi('.php[34]?$', $entry)) {
          $filenames = '<a href="' . tep_href_link(FILENAME_DEFINE_LANGUAGE, 'directory=' . $HTTP_GET_VARS['directory'] . '&filename=' . urlencode($HTTP_GET_VARS['directory'] . '/' . $entry)) . '">' . $entry . '</a>';
?>              
                <td class="smallText">&nbsp;<?php echo $filenames; ?>&nbsp;</td>
<?php
          if (!$left) {
?>
              </tr>
              <tr>
<?php
          }
          $left = !$left;
        }
      }
      $dir->close();
    }
?>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><?php echo tep_black_line(); ?></td>
          </tr>
<?php
  }
?>
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