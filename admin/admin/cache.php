<?php
/*
  $Id: cache.php,v 1.6 2001/11/29 12:17:26 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $cache_blocks = array(array('title' => TEXT_CACHE_CATEGORIES, 'code' => 'categories', 'file' => 'categories_box-language.cache', 'multiple' => true),
                        array('title' => TEXT_CACHE_MANUFACTURERS, 'code' => 'manufacturers', 'file' => 'manufacturers_box-language.cache', 'multiple' => true),
                        array('title' => TEXT_CACHE_ALSO_PURCHASED, 'code' => 'also_purchased', 'file' => 'also_purchased-language.cache', 'multiple' => true)
                       );

  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'reset') {
      for ($i=0; $i<sizeof($cache_blocks); $i++) {
        if ($HTTP_GET_VARS['block'] == $cache_blocks[$i]['code']) {
          if ($cache_blocks[$i]['multiple']) {
            if ($dir = @opendir(DIR_FS_CACHE)) {
              while ($cache_file = readdir($dir)) {
                $cached_file = $cache_blocks[$i]['file'];
                $languages = tep_get_languages();
                for ($j=0; $j<sizeof($languages); $j++) {
                  $cached_file_unlink = ereg_replace('-language', '-' . $languages[$j]['directory'], $cached_file);
                  if (ereg('^' . $cached_file_unlink, $cache_file)) {
                    @unlink(DIR_FS_CACHE . $cache_file);
                  }
                }
              }
              closedir($dir);
            }
          } else {
            $cached_file = $cache_blocks[$i]['file'];
            $languages = tep_get_languages();
            for ($i=0; $i<sizeof($languages); $i++) {
              $cached_file = ereg_replace('-language', '-' . $languages[$i]['directory'], $cached_file);
              @unlink(DIR_FS_CACHE . $cached_file);
            }
          }
          break;
        }
      }
      header('Location: ' . tep_href_link(FILENAME_CACHE, '', 'NONSSL')); tep_exit();
    }
    header('Location: ' . tep_href_link(FILENAME_CACHE, '', 'NONSSL')); tep_exit();
  }
?>
<html>
<head>
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
            <td class="pageHeading" align="right">&nbsp;<?php echo tep_image(DIR_WS_CATALOG . 'images/pixel_trans.gif', '', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
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
                <td class="tableHeading">&nbsp;<?php echo TABLE_HEADING_CACHE; ?>&nbsp;</td>
                <td class="tableHeading" align="right">&nbsp;<?php echo TABLE_HEADING_DATE_CREATED; ?>&nbsp;</td>
                <td class="tableHeading" align="right">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
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
      if ($dir = @opendir(DIR_FS_CACHE)) {
        while ($cache_file = readdir($dir)) {
          $cached_file = ereg_replace('-language', '-' . $language, $cache_blocks[$i]['file']);
          if (ereg('^' . $cached_file, $cache_file)) {
            $cache_mtime = strftime(DATE_TIME_FORMAT, filemtime(DIR_FS_CACHE . $cache_file));
            break;
          }
        }
        closedir($dir);
      }
    }
?>
              <tr bgcolor="#d8e1eb" onmouseover="this.style.background='#cc9999'" onmouseout="this.style.background='#d8e1eb'">
                <td class="tableData">&nbsp;<?php echo $cache_blocks[$i]['title']; ?>&nbsp;</td>
                <td class="tableData" align="right">&nbsp;<?php echo $cache_mtime; ?>&nbsp;</td>
                <td class="tableData" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_CACHE, 'action=reset&block=' . $cache_blocks[$i]['code'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_reset.gif', 'Reset', 13, 13) . '</a>'; ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td class="main" colspan="3"><?php echo tep_black_line(); ?></td>
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