<? include('includes/application_top.php'); ?>
<?
  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'setflag') {
      switch ($HTTP_GET_VARS['flag']) {
        case '0' : tep_db_query("update banners set status = '0' where banners_id = '" . $HTTP_GET_VARS['id'] . "'");
                   header('Location: ' . tep_href_link(FILENAME_BANNERS_MANAGER, '', 'NONSSL')); tep_exit();
                   break;
        case '1' : tep_db_query("update banners set status = '1' where banners_id = '" . $HTTP_GET_VARS['id'] . "'");
                   header('Location: ' . tep_href_link(FILENAME_BANNERS_MANAGER, '', 'NONSSL')); tep_exit();
                   break;
        default :  header('Location: ' . tep_href_link(FILENAME_BANNERS_MANAGER, '', 'NONSSL')); tep_exit();
                   break;
      }
    } elseif ($HTTP_GET_VARS['action'] == 'insert') {
      $banners_title = $HTTP_POST_VARS['banners_title'];
      $banners_url = $HTTP_POST_VARS['banners_url'];
      $banners_group = (strlen($HTTP_POST_VARS['new_banners_group']) > 0) ? $HTTP_POST_VARS['new_banners_group'] : $HTTP_POST_VARS['banners_group'];
      $banners_image_target = $HTTP_POST_VARS['banners_image_target'];

      $image_location = '';
      if ($banners_image != 'none') {
        $image_location = (substr(DIR_WS_CATALOG_IMAGES, 0, 1) == '/') ? DIR_FS_DOCUMENT_ROOT . substr(DIR_WS_CATALOG_IMAGES, 1) : DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES;
        if ($HTTP_POST_VARS['banners_image_target'] != '') {
          $image_location .= $HTTP_POST_VARS['banners_image_target'];
        }
        copy($banners_image, $image_location . $banners_image_name);
      } elseif ($banners_image_local != '') {
        $image_location = (substr(DIR_WS_CATALOG_IMAGES, 0, 1) == '/') ? DIR_FS_DOCUMENT_ROOT . substr(DIR_WS_CATALOG_IMAGES, 1) . $HTTP_POST_VARS['banners_image_local'] : DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $HTTP_POST_VARS['banners_image_local'];
      }

      $db_image_location = ($HTTP_POST_VARS['banners_image_local'] != '') ? 'images/' . $HTTP_POST_VARS['banners_image_local'] : 'images/' . $banners_image_target . $banners_image_name;
      tep_db_query("insert into banners values ('', '" . $banners_title . "', '" . $banners_url . "', '" . $db_image_location . "', '" . $banners_group . "', now(), '1')");

      header('Location: ' . tep_href_link(FILENAME_BANNERS_MANAGER, '', 'NONSSL')); tep_exit();
    }
  }
?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript"><!--
function popupImageWindow(url) {
  window.open(url,'popupImageWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_WS_INCLUDES . 'column_left.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
            <td bgcolor="<? echo TOP_BAR_BACKGROUND_COLOR; ?>" width="100%" nowrap><font face="<? echo TOP_BAR_FONT_FACE; ?>" size="<? echo TOP_BAR_FONT_SIZE; ?>" color="<? echo TOP_BAR_FONT_COLOR; ?>">&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<? echo HEADING_FONT_FACE; ?>" size="<? echo HEADING_FONT_SIZE; ?>" color="<? echo HEADING_FONT_COLOR; ?>">&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_WS_CATALOG . 'images/pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', ''); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
<?
  if ($HTTP_GET_VARS['action'] == 'new') {
?>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr><form name="new_banner" enctype="multipart/form-data" <? echo 'action="' . tep_href_link(FILENAME_BANNERS_MANAGER, tep_get_all_get_params(array('action', 'pinfo', 'info')) . 'action=insert', 'NONSSL') . '"'; ?> method="post">
        <td><br><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo TEXT_BANNERS_TITLE; ?>&nbsp;</font></td>
            <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<input type="text" name="banners_title">&nbsp;</font></td>
          </tr>
	      <tr>
            <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo TEXT_BANNERS_URL; ?>&nbsp;</font></td>
            <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<input type="text" name="banners_url">&nbsp;</font></td>
          </tr>
    	  <tr>
            <td valign="top" nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo TEXT_BANNERS_GROUP; ?>&nbsp;</font></td>
            <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">
<?
    echo '&nbsp;<select name="banners_group">';
    $groups_query = tep_db_query("select distinct banners_group from banners order by banners_group");
    while ($groups = tep_db_fetch_array($groups_query)) {
      echo '<option value="' . $groups['banners_group'] . '">' . $groups['banners_group'] . '</option>';
    }
    echo '</select>' . TEXT_BANNERS_NEW_GROUP;
?><br>&nbsp;<input type="text" name="new_banners_group">&nbsp;</font></td>
          </tr>
	      <tr>
            <td valign="top" nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo TEXT_BANNERS_IMAGE; ?>&nbsp;</font></td>
            <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<input type="file" name="banners_image"><? echo TEXT_BANNERS_IMAGE_LOCAL; ?><br>&nbsp;<? echo DIR_FS_DOCUMENT_ROOT; if (substr(DIR_WS_CATALOG_IMAGES, 0, 1) == '/') { echo substr(DIR_WS_CATALOG_IMAGES, 1); } else { echo DIR_WS_CATALOG_IMAGES; } ?><input type="text" name="banners_image_local">&nbsp;</font></td>
          </tr>
	      <tr>
            <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo TEXT_BANNERS_IMAGE_TARGET; ?>&nbsp;</font></td>
            <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo DIR_FS_DOCUMENT_ROOT; if (substr(DIR_WS_CATALOG_IMAGES, 0, 1) == '/') { echo substr(DIR_WS_CATALOG_IMAGES, 1); } else { echo DIR_WS_CATALOG_IMAGES; } ?><input type="text" name="banners_image_target">&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><br><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><? echo TEXT_BANNERS_INSERT_NOTE; ?></font></td>
            <td align="right" valign="top"><br><? echo tep_image_submit(DIR_WS_IMAGES . 'button_insert.gif', '66', '20', '0', IMAGE_INSERT) . '&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_BANNERS_MANAGER, '', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>'; ?></td>
          </tr>
        </table></td>
      <form></tr>
<?
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="4"><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_BANNERS; ?>&nbsp;</b></font></td>
            <td align="right" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_GROUPS; ?>&nbsp;</b></font></td>
            <td align="right" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_STATISTICS; ?>&nbsp;</b></font></td>
            <td align="right" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_STATUS; ?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td colspan="4"><? echo tep_black_line(); ?></td>
          </tr>
<?
    if ($HTTP_GET_VARS['page'] > 1) $rows = $HTTP_GET_VARS['page'] * 20 - 20;
    $banners_query_raw = "select banners_id, banners_title, banners_image, banners_group, status from banners order by banners_title, banners_group";
    $banners_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $banners_query_raw, $banners_query_numrows);
    $banners_query = tep_db_query($banners_query_raw);
    while ($banners = tep_db_fetch_array($banners_query)) {
      $info_query = tep_db_query("select sum(banners_shown) as banners_shown, sum(banners_clicked) as banners_clicked from banners_history where banners_id = '" . $banners['banners_id'] . "'");
      $info = tep_db_fetch_array($info_query);

     $banners_shown = ($info['banners_shown'] != '') ? $info['banners_shown'] : '0';
     $banners_clicked = ($info['banners_clicked'] != '') ? $info['banners_clicked'] : '0';
?>
          <tr bgcolor="#d8e1eb" onmouseover="this.style.background='#cc9999'" onmouseout="this.style.background='#d8e1eb'">
            <td nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo '<a href="javascript:popupImageWindow(\'' . FILENAME_POPUP_IMAGE . '?image=' . DIR_WS_CATALOG . $banners['banners_image'] . '&alt=' . urlencode($banners['banners_title']) . '\')" class="blacklink">' . $banners['banners_title'] . '</a>'; ?>&nbsp;</font></td>
            <td align="right" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $banners['banners_group']; ?>&nbsp;</font></td>
            <td align="right" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $banners_shown . ' / ' . $banners_clicked; ?>&nbsp;</font></td>
            <td align="right" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;
<?
      if ($banners['status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', '10', '10', '0', 'Active') . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_BANNERS_MANAGER, 'action=setflag&flag=0&id=' . $banners['banners_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', '10', '10', '0', 'Set Inactive') . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_BANNERS_MANAGER, 'action=setflag&flag=1&id=' . $banners['banners_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', '10', '10', '0', 'Set Active') . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', '10', '10', '0', 'Inactive');
      }
?>&nbsp;</font></td>
          </tr>
<?
    }
?>
          <tr>
            <td colspan="4"><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $banners_split->display_count($banners_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_BANNERS); ?>&nbsp;</font></td>
                <td align="right" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo TEXT_RESULT_PAGE; ?> <? echo $banners_split->display_links($banners_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?>&nbsp;</font></td>
              </tr>
              <tr>
                <td align="right" colspan="2"><? echo '<a href="' . tep_href_link(FILENAME_BANNERS_MANAGER, 'action=new', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_new_banner.gif', '91', '20', '0', IMAGE_NEW_BANNER) . '</a>'; ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?
  }
?>
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