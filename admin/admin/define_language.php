<? include('includes/application_top.php'); ?>
<?
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
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body>
<!-- header //-->
<? $include_file = DIR_WS_INCLUDES . 'header.php';  include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_WS_INCLUDES . 'column_left.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td class="topBarTitle">&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</td>
            <td class="pageHeading" align="right">&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
          <tr class="subBar">
            <td class="subBar">&nbsp;<? echo SUB_BAR_TITLE; ?>&nbsp;</td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
<?
// Edit constants in selected file
  if ( ($HTTP_GET_VARS['directory']) && ($HTTP_GET_VARS['filename']) ) {
?>
          <tr>
            <td><form action="<? echo tep_href_link(FILENAME_DEFINE_LANGUAGE, 'directory=' . $HTTP_GET_VARS['directory'] . '&filename=' . $HTTP_GET_VARS['filename'] . '&action=save'); ?>" method="post"><table border="0" cellspacing="0" cellpadding="0">
<?
    $full_filename = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_LANGUAGES . urldecode($HTTP_GET_VARS['filename']);
    $file_array = file($full_filename);
    $file_contents = implode('', $file_array);
?>
              <tr>
                <td class="smallText">&nbsp;<b><? echo $HTTP_GET_VARS['filename']; ?></b><br>&nbsp;<textarea name="file_contents" wrap="off" cols="60" rows="15"><? echo $file_contents; ?></textarea>&nbsp;</td>
              </tr>
              <tr>
                <td class="smallText">&nbsp;</td>
              </tr>
              <tr>
                <td class="smallText" align="right"><br><? echo tep_image_submit(DIR_WS_IMAGES . 'button_save.gif', IMAGE_SAVE); ?>&nbsp;</td>
              </tr>
            </table></form></td>
          </tr>
<?
// List selection of files to edit
  } else {
    $filename = $HTTP_GET_VARS['directory'] . '.php';
?>
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="smallText">&nbsp;<a href="<? echo tep_href_link(FILENAME_DEFINE_LANGUAGE, 'directory=' . $HTTP_GET_VARS['directory'] . '&filename=' . $filename); ?>"><b><? echo $filename; ?></b></a>&nbsp;</td>
<?
    $dir = dir(DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_LANGUAGES . $HTTP_GET_VARS['directory']);
    $left = false;
    if ($dir) {
      while($entry = $dir->read()) {
        if (eregi('.php[34]?$', $entry)) {
          $filenames = '<a href="' . tep_href_link(FILENAME_DEFINE_LANGUAGE, 'directory=' . $HTTP_GET_VARS['directory'] . '&filename=' . urlencode($HTTP_GET_VARS['directory'] . '/' . $entry)) . '">' . $entry . '</a>';
?>              
                <td class="smallText">&nbsp;<? echo $filenames; ?>&nbsp;</td>
<?
          if (!$left) {
?>
              </tr>
              <tr>
<?
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
            <td><? echo tep_black_line(); ?></td>
          </tr>
<?
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
<? $include_file = DIR_WS_INCLUDES . 'footer.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<? $include_file = DIR_WS_INCLUDES . 'application_bottom.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
