<? include('includes/application_top.php'); ?>
<?
  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'insert') {
      tep_db_query("insert into products_expected values ('', '" . $HTTP_POST_VARS['products_name'] . "', '" . $HTTP_POST_VARS['date_expected'] . "')");
      header('Location: ' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, '', 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'save') {
      tep_db_query("update products_expected set products_name = '" . $HTTP_POST_VARS['products_name'] . "', date_expected = '" . $HTTP_POST_VARS['date_expected'] . "' where products_expected_id = '" . $HTTP_POST_VARS['products_expected_id'] . "'");
      header('Location: ' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, '', 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'deleteconfirm') {
      tep_db_query("delete from products_expected where products_expected_id = '" . $HTTP_POST_VARS['products_expected_id'] . "'");
      header('Location: ' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, '', 'NONSSL')); tep_exit();
    }
  }
?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body onload="SetFocus();" marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_CATALOG . 'images/pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', ''); ?>&nbsp;</td>
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
                <td nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_PRODUCTS; ?>&nbsp;</b></font></td>
                <td align="center" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_DATE_EXPECTED; ?>&nbsp;</b></font></td>
                <td align="center" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_ACTION; ?>&nbsp;</b></font></td>
              </tr>
              <tr>
                <td colspan="3"><? echo tep_black_line(); ?></td>
              </tr>
<?
  $rows = 0;
  $products_query_raw = "select products_expected_id, products_name, date_expected from products_expected order by date_expected DESC";
  $products_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
  $products_query = tep_db_query($products_query_raw);
  while ($products = tep_db_fetch_array($products_query)) {
    $rows++;

    if (((!$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['info'] == $products['products_expected_id'])) && (!$peInfo) && (substr($HTTP_GET_VARS['action'], 0, 3) != 'new')) {
      $peInfo = new productExpectedInfo($products);
    }

    if ($products['products_expected_id'] == @$peInfo->id) {
      echo '                  <tr bgcolor="#b0c8df">' . "\n";
    } else {
      echo '                  <tr bgcolor="#d8e1eb" onmouseover="this.style.background=\'#cc9999\';this.style.cursor=\'hand\'" onmouseout="this.style.background=\'#d8e1eb\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, tep_get_all_get_params(array('info', 'action')) . 'info=' . $products['products_expected_id'], 'NONSSL') . '\'">' . "\n";
    }
?>
                <td nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $products['products_name']; ?>&nbsp;</font></td>
                <td align="center" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo tep_date_short($products['date_expected']); ?>&nbsp;</font></td>
<?
    if ($products['products_expected_id'] == @$peInfo->id) {
?>
                    <td align="center" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo tep_image(DIR_IMAGES . 'icon_arrow_right.gif', 13, 13, 0, ''); ?>&nbsp;</font></td>
<?
    } else {
?>
                    <td align="center" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, tep_get_all_get_params(array('info', 'action')) . 'info=' . $products['products_expected_id'], 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'icon_info.gif', '13', '13', '0', IMAGE_ICON_INFO) . '</a>'; ?>&nbsp;</font></td>
<?
    }
?>
              </tr>
<?
  }
?>
              <tr>
                <td colspan="3"><? echo tep_black_line(); ?></td>
              </tr>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td valign="top" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS_EXPECTED); ?>&nbsp;</font></td>
                    <td align="right" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo TEXT_RESULT_PAGE; ?> <? echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?>&nbsp;<? if (!$HTTP_GET_VARS['action']) echo '<br><br>&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, tep_get_all_get_params(array('action', 'info')) . 'action=new', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_new_product.gif', '103', '20', '0', IMAGE_NEW_PRODUCT) . '</a>&nbsp;'; ?></font></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="25%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?
  $info_box_contents = array();
  if ($peInfo) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $peInfo->products_name . '</b>&nbsp;');
  if ((!$peInfo) && ($HTTP_GET_VARS['action'] == 'new')) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . TEXT_INFO_HEADING_NEW_PRODUCT . '</b>&nbsp;');

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
  if ($HTTP_GET_VARS['action'] == 'new') {
      $form = '<form name="specials_new" action="' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, tep_get_all_get_params(array('action')) . 'action=insert', 'NONSSL') . '" method="post">'  ."\n";

      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_INSERT_INTRO . '<br>&nbsp;');
      $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_INFO_PRODUCTS_NAME . '<br>&nbsp;<input type="text" name="products_name"><br>&nbsp;<br>' . TEXT_INFO_DATE_EXPECTED . '<br>&nbsp;<input type="text" name="date_expected"><br>&nbsp;');
      $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_DATE_NOTE . '<br>&nbsp;');
      $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_IMAGES . 'button_save.gif', '66', '20', '0', IMAGE_SAVE) . '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>');
  } elseif ($HTTP_GET_VARS['action'] == 'edit') {
    $form = '<form name="products_expected" action="' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, tep_get_all_get_params(array('action')) . 'action=save', 'NONSSL') . '" method="post"><input type="hidden" name="products_expected_id" value="' . $peInfo->id . '">'  ."\n";

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_EDIT_INTRO . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_PRODUCTS_NAME . '<br><input type="text" name="products_name" value="' . $peInfo->products_name . '"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_DATE_EXPECTED . '<br><input type="text" name="date_expected" value="' . $peInfo->date_expected . '"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_DATE_NOTE . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_IMAGES . 'button_update.gif', '66', '20', '0', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>');
  } elseif ($HTTP_GET_VARS['action'] == 'delete') {
    $form = '<form name="products_expected" action="' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, tep_get_all_get_params(array('action')) . 'action=deleteconfirm', 'NONSSL') . '" method="post"><input type="hidden" name="products_expected_id" value="' . $peInfo->id . '">'  ."\n";

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_DELETE_INTRO . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $peInfo->products_name . '</b><br>&nbsp;');
    $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_IMAGES . 'button_delete.gif', '66', '20', '0', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>');
  } else {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, tep_get_all_get_params(array('action')) . 'action=edit', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_edit.gif', '66', '20', '0', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, tep_get_all_get_params(array('action')) . 'action=delete', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_delete.gif', '66', '20', '0', IMAGE_DELETE) . '</a>');
    $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_INFO_DATE_EXPECTED . ' ' . tep_date_short($peInfo->date_expected));
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