<? include('includes/application_top.php'); ?>
<?
  $cache_blocks = array(array('title' => TEXT_CACHE_CATEGORIES, 'code' => 'categories', 'file' => 'categories_box.cache', 'multiple' => true),
                        array('title' => TEXT_CACHE_MANUFACTURERS, 'code' => 'manufacturers', 'file' => 'manufacturers_box.cache', 'multiple' => true),
                        array('title' => TEXT_CACHE_ALSO_PURCHASED, 'code' => 'also_purchased', 'file' => 'also_purchased.cache', 'multiple' => true)
                       );

  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'reset') {
      for ($i=0; $i<sizeof($cache_blocks); $i++) {
        if ($HTTP_GET_VARS['block'] == $cache_blocks[$i]['code']) {
          if ($cache_blocks[$i]['multiple']) {
            if ($dir = @opendir(DIR_FS_CACHE)) {
              while ($cache_file = readdir($dir)) {
                if (ereg($cache_blocks[$i]['file'], $cache_file)) {
                  @unlink(DIR_FS_CACHE . $cache_file);
                }
              }
              closedir($dir);
            }
          } else {
            @unlink(DIR_FS_CACHE . $cache_blocks[$i]['file']);
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
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
            <td class="pageHeading" align="right">&nbsp;<? echo tep_image(DIR_WS_CATALOG . 'images/pixel_trans.gif', '', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2"><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="tableHeading">&nbsp;<? echo TABLE_HEADING_CACHE; ?>&nbsp;</td>
                <td class="tableHeading" align="right">&nbsp;<? echo TABLE_HEADING_DATE_CREATED; ?>&nbsp;</td>
                <td class="tableHeading" align="right">&nbsp;<? echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3"><? echo tep_black_line(); ?></td>
              </tr>
<?
  for ($i=0; $i<sizeof($cache_blocks); $i++) {
?>
              <tr bgcolor="#d8e1eb" onmouseover="this.style.background='#cc9999'" onmouseout="this.style.background='#d8e1eb'">
                <td class="tableData">&nbsp;<? echo $cache_blocks[$i]['title']; ?>&nbsp;</td>
                <td class="tableData" align="right">&nbsp;<? if (file_exists(DIR_FS_CACHE . $cache_blocks[$i]['file'])) { echo strftime(DATE_TIME_FORMAT, filemtime(DIR_FS_CACHE . $cache_blocks[$i]['file'])); } else { echo TEXT_FILE_DOES_NOT_EXIST; } ?>&nbsp;</td>
                <td class="tableData" align="right"><? echo '<a href="' . tep_href_link(FILENAME_CACHE, 'action=reset&block=' . $cache_blocks[$i]['code'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_reset.gif', 'Reset', 13, 13) . '</a>'; ?>&nbsp;</td>
              </tr>
<?
  }
?>
              <tr>
                <td class="main" colspan="3"><? echo tep_black_line(); ?></td>
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
<? $include_file = DIR_WS_INCLUDES . 'footer.php';  include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<? $include_file = DIR_WS_INCLUDES . 'application_bottom.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
