<? include('includes/application_top.php'); ?>
<?
  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'save') {
      if (EXPERT_MODE) {
        $update_query .= "manufacturers_id = '" . $HTTP_POST_VARS['manufacturers_id'] . "', manufacturers_name = '" . $HTTP_POST_VARS['manufacturers_name'] . "', manufacturers_image = '" . $HTTP_POST_VARS['manufacturers_image'] . "', manufacturers_location = '" . $HTTP_POST_VARS['manufacturers_location'] . "'";
        $new_manufacturers_id = $HTTP_POST_VARS['manufacturers_id'];
      } else {
        $update_query .= "manufacturers_name = '" . $HTTP_POST_VARS['manufacturers_name'] . "', manufacturers_image = '" . $HTTP_POST_VARS['manufacturers_image'] . "', manufacturers_location = '" . $HTTP_POST_VARS['manufacturers_location'] . "'";
        $new_manufacturers_id = $HTTP_POST_VARS['original_manufacturers_id'];
      }
      if (tep_db_query("update manufacturers set " . $update_query . " where manufacturers_id = '" . $HTTP_POST_VARS['original_manufacturers_id'] . "'")) {
        header('Location: ' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('action', 'info')) . 'info=' . $new_manufacturers_id, 'NONSSL'));
        tep_exit();
      } else {
        header('Location: ' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('action', 'info')) . 'error=SAVE', 'NONSSL'));
        tep_exit();
      }
    } elseif ($HTTP_GET_VARS['action'] == 'deleteconfirm') {
      if (tep_db_query("delete from manufacturers where manufacturers_id = '" . $HTTP_POST_VARS['manufacturers_id'] . "'")) {
        header('Location: ' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('action', 'info')), 'NONSSL'));
        tep_exit();
      } else {
        header('Location: ' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('action', 'info')) . 'error=DELETE', 'NONSSL'));
        tep_exit();
      }
    } elseif ($HTTP_GET_VARS['action'] == 'insert') {
      if (tep_db_query("insert into manufacturers (manufacturers_name, manufacturers_image, manufacturers_location) values ('" . $HTTP_POST_VARS['manufacturers_name'] . "', '" . $HTTP_POST_VARS['manufacturers_image'] . "', '" . $HTTP_POST_VARS['manufacturers_location'] . "')")) {
        $manufacturers_id = tep_db_insert_id();
        header('Location: ' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('action', 'info')) . 'info=' . $manufacturers_id, 'NONSSL'));
        tep_exit();
      } else {
        header('Location: ' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('action', 'info')) . 'error=INSERT', 'NONSSL'));
        tep_exit();
      }
    }
  }
?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript"><!--
function checkForm() {
  var error_message = "<? echo JS_ERROR; ?>";
  var error = 0;
  var manufacturers_name = document.manufacturers.manufacturers_name.value;
  var manufacturers_location = document.manufacturers.manufacturers_location.value;
  var manufacturers_image = document.manufacturers.manufacturers_image.value;
  
  if (manufacturers_name.length < 1) {
    error_message = error_message + "<? echo JS_MANUFACTURERS_NAME; ?>";
    error = 1;
  }
  
  if (manufacturers_location = "" || manufacturers_location.length < 1) {
    error_message = error_message + "<? echo JS_MANUFACTURERS_LOCATION; ?>";
    error = 1;
  }
  
  if (manufacturers_image.length < 1) {
    error_message = error_message + "<? echo JS_MANUFACTURERS_IMAGE; ?>";
    error = 1;
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_INCLUDES . 'header.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_INCLUDES . 'column_left.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
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
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_IMAGES . 'pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', ''); ?>&nbsp;</td>
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
                <td align="center" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_ID; ?>&nbsp;</b></font></td>
                <td nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_MANUFACTURERS; ?>&nbsp;</b></font></td>
                <td align="center" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_LOCATION; ?>&nbsp;</b></font></td>
                <td nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_IMAGE; ?>&nbsp;</b></font></td>
                <td align="center" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_ACTION; ?>&nbsp;</b></font></td>
              </tr>
              <tr>
                <td colspan="5"><? echo tep_black_line(); ?></td>
              </tr>
<?
  $rows = 0;
  $manufacturers_query_raw = "select manufacturers_id, manufacturers_name, manufacturers_image, manufacturers_location from manufacturers order by manufacturers_name";
  $manufacturers_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $manufacturers_query_raw, $manufacturers_query_numrows);
  $manufacturers_query = tep_db_query($manufacturers_query_raw);
  while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
    $rows++;

    if (((!$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['info'] == $manufacturers['manufacturers_id'])) && (!$mInfo)) {
      $manufacturer_products_query = tep_db_query("select count(*) as total from products_to_manufacturers where manufacturers_id = '" . $manufacturers['manufacturers_id'] . "'");
      $manufacturer_products = tep_db_fetch_array($manufacturer_products_query);

      $mInfo_array = tep_array_merge($manufacturers, $manufacturer_products);
      $mInfo = new manufacturerInfo($mInfo_array);
    }

    if ($manufacturers['manufacturers_id'] == @$mInfo->id) {
      echo '              <tr bgcolor="#b0c8df">' . "\n";
    } else {
      echo '              <tr bgcolor="#d8e1eb" onmouseover="this.style.background=\'#cc9999\';this.style.cursor=\'hand\'" onmouseout="this.style.background=\'#d8e1eb\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('info', 'action')) . 'info=' . $manufacturers['manufacturers_id'], 'NONSSL') . '\'">' . "\n";
    }
?>
                <td align="center" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $manufacturers['manufacturers_id']; ?>&nbsp;</font></td>
                <td nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $manufacturers['manufacturers_name']; ?>&nbsp;</font></td>
                <td align="center" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $manufacturers['manufacturers_location']; ?>&nbsp;</font></td>
                <td nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $manufacturers['manufacturers_image']; ?>&nbsp;</font></td>
<?
    if ($manufacturers['manufacturers_id'] == @$mInfo->id) {
?>
                <td align="center" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo tep_image(DIR_IMAGES . 'icon_arrow_right.gif', 13, 13, 0, ''); ?>&nbsp;</font></td>
<?
    } else {
?>
                <td align="center" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('info', 'action')) . 'info=' . $manufacturers['manufacturers_id'], 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'icon_info.gif', '13', '13', '0', IMAGE_ICON_INFO) . '</a>'; ?>&nbsp;</font></td>
<?
    }
?>
              </tr>
<?
  }
?>
              <tr>
                <td colspan="5"><? echo tep_black_line(); ?></td>
              </tr>
<?
  if (!$HTTP_GET_VARS['action']) {
?>
              <tr><form name="manufacturers" <? echo 'action="' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('action')) . 'action=insert', 'NONSSL') . '"'; ?> method="post" onSubmit="return checkForm();">
                <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;</font></td>
                <td><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<input type="text" name="manufacturers_name">&nbsp;</font></td>
                <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<input type="text" name="manufacturers_location" size="2">&nbsp;</font></td>
                <td><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<input type="text" name="manufacturers_image">&nbsp;</font></td>
                <td align="center"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo tep_image_submit(DIR_IMAGES . 'button_insert.gif', '66', '20', '0', IMAGE_INSERT); ?>&nbsp;</font></td>
              </form></tr>
              <tr>
                <td colspan="5"><? echo tep_black_line(); ?></td>
              </tr>
<?
  }
?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $manufacturers_split->display_count($manufacturers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS); ?>&nbsp;</font></td>
                    <td align="right" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo TEXT_RESULT_PAGE; ?> <? echo $manufacturers_split->display_links($manufacturers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?>&nbsp;</font></td>
                  </tr>
                </table></td>
              </tr>
<?
  if ($HTTP_GET_VARS['error']) {
?>
              <tr>
                <td colspan="5"><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="#ff0000">&nbsp;<? echo ERROR_ACTION; ?>&nbsp;</font></td>
              </tr>
<?
  }
?>
            </table></td>
            <td width="25%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $mInfo->name . '</b>&nbsp;');
?>
              <tr bgcolor="#81a2b6">
                <td>
                  <? new infoBoxHeading($info_box_contents); ?>
                </td>
              </tr>
              <tr bgcolor="#81a2b6">
                <td><? echo tep_black_line(); ?></td>
              </tr>
<?
  if ($HTTP_GET_VARS['action'] == 'edit') {
    $form = '<form name="manufacturers" action="' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('action')) . 'action=save', 'NONSSL') . '" method="post"><input type="hidden" name="original_manufacturers_id" value="' . $mInfo->id . '">'  ."\n";

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_EDIT_INTRO . '<br>&nbsp;');
    if (EXPERT_MODE) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_EDIT_MANUFACTURERS_ID . '<br>&nbsp;<input type="text" name="manufacturers_id" value="' . $mInfo->id . '" size="2"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_EDIT_MANUFACTURERS_NAME . '<br>&nbsp;<input type="text" name="manufacturers_name" value="' . $mInfo->name . '"><br>&nbsp;<br>&nbsp;' . TEXT_EDIT_MANUFACTURERS_IMAGE . '<br>&nbsp;<input type="text" name="manufacturers_image" value="' . $mInfo->image . '"><br>&nbsp;<br>&nbsp;' . TEXT_EDIT_MANUFACTURERS_LOCATION . '<br>&nbsp;<input type="text" name="manufacturers_location" size="2" value="' . $mInfo->location . '"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_IMAGES . 'button_save.gif', '66', '20', '0', IMAGE_SAVE) . '<a href="' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>');
  } elseif ($HTTP_GET_VARS['action'] == 'delete') {
    $form = '<form name="manufacturers" action="' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('action')) . 'action=deleteconfirm', 'NONSSL') . '" method="post"><input type="hidden" name="manufacturers_id" value="' . $mInfo->id . '">' . "\n";

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_DELETE_INTRO . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $mInfo->name . '</b>');
    if ($mInfo->products_count > 0) $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $mInfo->products_count));
    $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_IMAGES . 'button_delete.gif', '66', '20', '0', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>');
  } else {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('action')) . 'action=edit', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_edit.gif', '66', '20', '0', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('action')) . 'action=delete', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_delete.gif', '66', '20', '0', IMAGE_DELETE) . '</a>');
    $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_DATE_ADDED . '&nbsp;<br>&nbsp;' . TEXT_LAST_MODIFIED);
    $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . tep_info_image($mInfo->image, $mInfo->name));
    $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_PRODUCTS . ' ' . $mInfo->products_count);
  }
?>
              <tr bgcolor="#b0c8df"><? echo $form; ?>
                <td>
                  <? new infoBox($info_box_contents); ?>
                </td>
              <? if ($form) echo '</form>'; ?></tr>
              <tr bgcolor="#b0c8df">
                <td><? echo tep_black_line(); ?></td>
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
<? $include_file = DIR_INCLUDES . 'footer.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<? $include_file = DIR_INCLUDES . 'application_bottom.php'; include(DIR_INCLUDES . 'include_once.php'); ?>