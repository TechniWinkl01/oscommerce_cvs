<?php
/*
  $Id: cache.php,v 1.16 2002/01/11 02:20:56 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'reset') {
      tep_reset_cache_block($HTTP_GET_VARS['block']);
    }
    tep_redirect(tep_href_link(FILENAME_CACHE));
  }

// check if the cache directory exists
  if (is_dir(DIR_FS_CACHE)) {
    if (!is_writeable(DIR_FS_CACHE)) $errorStack->add(ERROR_CACHE_DIRECTORY_NOT_WRITEABLE, 'error');
  } else {
    $errorStack->add(ERROR_CACHE_DIRECTORY_DOES_NOT_EXIST, 'error');
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
                <td class="tableHeading"><?php echo TABLE_HEADING_CACHE; ?></td>
                <td class="tableHeading" align="right"><?php echo TABLE_HEADING_DATE_CREATED; ?></td>
                <td class="tableHeading" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3"><?php echo tep_draw_separator(); ?></td>
              </tr>
<?php
  if ($errorStack->size < 1) {
    $languages = tep_get_languages();
    for ($i=0; $i<sizeof($languages); $i++) {
      if ($languages[$i]['code'] == DEFAULT_LANGUAGE) {
        $language = $languages[$i]['directory'];
      }
    }
    for ($i=0; $i<sizeof($cache_blocks); $i++) {
      $cached_file = ereg_replace('-language', '-' . $language, $cache_blocks[$i]['file']);
      if (file_exists(DIR_FS_CACHE . $cached_file)) {
        $cache_mtime = strftime(DATE_TIME_FORMAT, filemtime(DIR_FS_CACHE . $cached_file));
      } else {
        $cache_mtime = TEXT_FILE_DOES_NOT_EXIST;
        $dir = dir(DIR_FS_CACHE);
        while ($cache_file = $dir->read()) {
          $cached_file = ereg_replace('-language', '-' . $language, $cache_blocks[$i]['file']);
          if (ereg('^' . $cached_file, $cache_file)) {
            $cache_mtime = strftime(DATE_TIME_FORMAT, filemtime(DIR_FS_CACHE . $cache_file));
            break;
          }
        }
        $dir->close();
      }
?>
              <tr bgcolor="#d8e1eb" onmouseover="this.style.background='#cc9999'" onmouseout="this.style.background='#d8e1eb'">
                <td class="tableData"><?php echo $cache_blocks[$i]['title']; ?></td>
                <td class="tableData" align="right"><?php echo $cache_mtime; ?></td>
                <td class="tableData" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_CACHE, 'action=reset&block=' . $cache_blocks[$i]['code'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_reset.gif', 'Reset', 13, 13) . '</a>'; ?>&nbsp;</td>
              </tr>
<?php
    }
  }
?>
              <tr>
                <td colspan="3"><?php echo tep_draw_separator(); ?></td>
              </tr>
              <tr>
                <td class="smallText" colspan="3"><?php echo TEXT_CACHE_DIRECTORY . ' ' . DIR_FS_CACHE; ?></td>
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