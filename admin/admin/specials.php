<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_SPECIALS; include(DIR_INCLUDES . 'include_once.php'); ?>
<?
  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'insert') {
      tep_db_query("insert into specials values ('', '" . $HTTP_POST_VARS['products_id'] . "', '" . $HTTP_POST_VARS['specials_new_products_price'] . "', '" . date('Ymd') . "')");
      header('Location: ' . tep_href_link(FILENAME_SPECIALS, '', 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'save') {
      tep_db_query("update specials set specials_new_products_price = '" . $HTTP_POST_VARS['specials_price'] . "' where specials_id = '" . $HTTP_POST_VARS['specials_id'] . "'");
      header('Location: ' . tep_href_link(FILENAME_SPECIALS, '', 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'deleteconfirm') {
      tep_db_query("delete from specials where specials_id = '" . $HTTP_POST_VARS['specials_id'] . "'");
      header('Location: ' . tep_href_link(FILENAME_SPECIALS, '', 'NONSSL')); tep_exit();
    }
  }

  class Special_Price_Info {
    var $id, $products_id, $products_price, $products_image, $specials_price, $date_added;
  }
?>
<html>
<head>
<title><?=TITLE;?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_INCLUDES . 'header.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<?=BOX_WIDTH;?>" valign="top"><table border="0" width="<?=BOX_WIDTH;?>" cellspacing="0" cellpadding="0">
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
            <td bgcolor="<?=TOP_BAR_BACKGROUND_COLOR;?>" width="100%" nowrap><font face="<?=TOP_BAR_FONT_FACE;?>" size="<?=TOP_BAR_FONT_SIZE;?>" color="<?=TOP_BAR_FONT_COLOR;?>">&nbsp;<?=TOP_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_IMAGES . 'pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', '');?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_PRODUCTS;?>&nbsp;</b></font></td>
                <td align="right" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_PRODUCTS_PRICE;?>&nbsp;</b></font></td>
                <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_DATE_ADDED;?>&nbsp;</b></font></td>
                <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_ACTION;?>&nbsp;</b></font></td>
              </tr>
              <tr>
                <td colspan="4"><?=tep_black_line();?></td>
              </tr>
<?
  $rows = 0;
  $specials_query_raw = "select p.products_id, p.products_price, s.specials_id, s.specials_new_products_price, s.specials_date_added from products p, specials s where p.products_id = s.products_id order by s.specials_date_added DESC";
  $specials_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $specials_query_raw, $specials_query_numrows);
  $specials_query = tep_db_query($specials_query_raw);
  while ($specials = tep_db_fetch_array($specials_query)) {
    $rows++;

    if (((!$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['info'] == $specials['specials_id'])) && (!$sInfo) && (substr($HTTP_GET_VARS['action'], 0, 3) != 'new')) {
      $products_query = tep_db_query("select products_image from products where products_id = '" . $specials['products_id'] . "'");
      $products = tep_db_fetch_array($products_query);

      $sInfo = new Special_Price_Info();
      $sInfo_array = tep_array_merge($specials, $products);
      tep_set_special_price_info($sInfo_array);
    }

    if ($specials['specials_id'] == $sInfo->id) {
      echo '                  <tr bgcolor="#b0c8df">' . "\n";
    } else {
      echo '                  <tr bgcolor="#d8e1eb" onmouseover="this.style.background=\'#cc9999\';this.style.cursor=\'hand\'" onmouseout="this.style.background=\'#d8e1eb\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params('info', 'action') . 'info=' . $specials['specials_id'], 'NONSSL') . '\'">' . "\n";
    }
?>
                <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=tep_products_name($specials['products_id']);?>&nbsp;</font></td>
                <td align="right" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<s><?=tep_currency_format($specials['products_price']);?></s> <font color="<?=SPECIALS_PRICE_COLOR;?>"><?=tep_currency_format($specials['specials_new_products_price']);?></font>&nbsp;</font></td>
                <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=tep_date_short($specials['specials_date_added']);?>&nbsp;</font></td>
<?
    if ($specials['specials_id'] == $sInfo->id) {
?>
                <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=tep_image(DIR_IMAGES . 'icon_arrow_right.gif', 13, 13, 0, '');?>&nbsp;</font></td>
<?
    } else {
?>
                <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?='<a href="' . tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params('info', 'action') . 'info=' . $specials['specials_id'], 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'icon_info.gif', '13', '13', '0', IMAGE_ICON_INFO) . '</a>';?>&nbsp;</font></td>
<?
    }
?>
              </tr>
<?
  }
?>
              <tr>
                <td colspan="4"><?=tep_black_line();?></td>
              </tr>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellpadding="0"cellspacing="2">
                  <tr>
                    <td valign="top" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$specials_split->display_count($specials_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_SPECIALS);?>&nbsp;</font></td>
                    <td align="right" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_RESULT_PAGE;?> <?=$specials_split->display_links($specials_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']);?>&nbsp;<? if (!$HTTP_GET_VARS['action']) echo '<br><br>&nbsp;<a href="' . tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params('action', 'info') . 'action=new', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_new_product.gif', '103', '20', '0', IMAGE_NEW_PRODUCT) . '</a>&nbsp;';?></font></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="25%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . tep_products_name($sInfo->products_id) . '</b>&nbsp;');
?>
              <tr bgcolor="#81a2b6">
                <td>
                  <? new infoBoxHeading($info_box_contents); ?>
                </td>
              </tr>
              <tr bgcolor="#81a2b6">
                <td><?=tep_black_line();?></td>
              </tr>
<?
  if ($HTTP_GET_VARS['action'] == 'edit') {
    $form = '<form name="specials_edit" action="' . tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params('action') . 'action=save', 'NONSSL') . '" method="post"><input type="hidden" name="specials_id" value="' . $sInfo->id . '">'  ."\n";

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_EDIT_INTRO . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_INFO_EDIT_SPECIALS_PRICE . '<br>&nbsp;<input type="text" name="specials_price" value="' . $sInfo->specials_price . '" size="8"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_IMAGES . 'button_save.gif', '66', '20', '0', IMAGE_SAVE) . '&nbsp;<a href="' . tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params('action'), 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>');
  } elseif ($HTTP_GET_VARS['action'] == 'delete') {
    $form = '<form name="specials_delete" action="' . tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params('action') . 'action=deleteconfirm', 'NONSSL') . '" method="post"><input type="hidden" name="specials_id" value="' . $sInfo->id . '">' . "\n";

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_DELETE_INTRO . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $sInfo->products_name . '</b>');
    $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_IMAGES . 'button_delete.gif', '66', '20', '0', IMAGE_DELETE) . '&nbsp;<a href="' . tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params('action'), 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>');
  } elseif ($HTTP_GET_VARS['action'] == 'new') {
    if (!$HTTP_POST_VARS['products_id']) {
// we have to choose a product first, so we know the original price
      $form = '<form name="specials_new" action="' . tep_href_link(FILENAME_SPECIALS, 'action=new', 'NONSSL') . '" method="post">'  ."\n";

      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_SELECT_PRODUCT_INTRO . '<br>&nbsp;');
      $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . tep_products_pull_down('name="products_id" style="font-size:10px"'));
      $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_IMAGES . 'button_select.gif', '66', '20', '0', IMAGE_SELECT) . '&nbsp;<a href="' . tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params('action'), 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>');
    } else {
// product has been chosen, its time to specify the new price
      $product_query = tep_db_query("select products_price from products where products_id = '" . $HTTP_POST_VARS['products_id'] . "'");
      $product = tep_db_fetch_array($product_query);

      $sInfo = new Special_Price_Info();
      $sInfo_array = tep_array_merge($HTTP_POST_VARS, $product);
      tep_set_special_price_info($sInfo_array);

      $form = '<form name="specials_new" action="' . tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params('action') . 'action=new_preview', 'NONSSL') . '" method="post"><input type="hidden" name="products_id" value="' . $sInfo->products_id . '">'  ."\n";

      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_INSERT_INTRO . '<br>&nbsp;');
      $info_box_contents[] = array('align' => 'left', 'text' => '<b>' . tep_products_name($sInfo->products_id) . '</b><br>' . TEXT_INFO_ORIGINAL_PRICE . ' ' . tep_currency_format($sInfo->products_price) . '<br>&nbsp;');
      $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_INFO_EDIT_SPECIALS_PRICE . '<br>&nbsp;<input type="text" name="specials_new_products_price" size="8"><br>' . TEXT_INFO_SPECIAL_PRICE_TIP . '<br>&nbsp;');
      if (!EXPERT_MODE) $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_NEW_PRICE_NOTE . '<br>&nbsp;');
      $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_IMAGES . 'button_preview.gif', '66', '20', '0', IMAGE_PREVIEW) . '&nbsp;<a href="' . tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params('action'), 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>');
    }
  } elseif ($HTTP_GET_VARS['action'] == 'new_preview') {
    $product_query = tep_db_query("select products_price, products_image from products where products_id = '" . $HTTP_POST_VARS['products_id'] . "'");
    $product = tep_db_fetch_array($product_query);

    if (substr($HTTP_POST_VARS['specials_new_products_price'], -1) == '%') $HTTP_POST_VARS['specials_new_products_price'] = ($product['products_price'] - (($HTTP_POST_VARS['specials_new_products_price'] / 100) * $product['products_price']));

    $sInfo = new Special_Price_Info();
    $sInfo_array = tep_array_merge($HTTP_POST_VARS, $product);
    tep_set_special_price_info($sInfo_array);

    $form = '<form name="specials_new" action="' . tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params('action') . 'action=insert', 'NONSSL') . '" method="post"><input type="hidden" name="products_id" value="' . $sInfo->products_id . '"><input type="hidden" name="specials_new_products_price" value="' . $sInfo->specials_price . '">'  ."\n";

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_DATE_ADDED . ' ' . tep_date_short(date('Ymd')));
    $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . tep_info_image($sInfo->products_image, tep_products_name($sInfo->products_id)));
    $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_INFO_NEW_PRICE . ' ' . tep_currency_format($sInfo->specials_price));
    $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_INFO_ORIGINAL_PRICE . ' ' . tep_currency_format($sInfo->products_price));
    $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_INFO_PERCENTAGE . ' ' . number_format($sInfo->percentage, 2) . '%<br>&nbsp;<br>');
    $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_IMAGES . 'button_insert.gif', '66', '20', '0', IMAGE_INSERT) . '&nbsp;<a href="' . tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params('action'), 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>');
  } else { // default info box
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params('action') . 'action=edit', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_edit.gif', '66', '20', '0', IMAGE_EDIT) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params('action') . 'action=delete', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_delete.gif', '66', '20', '0', IMAGE_DELETE) . '</a>');
    $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_DATE_ADDED . ' ' . tep_date_short($sInfo->date_added) . '<br>&nbsp;' . TEXT_LAST_MODIFIED);
    $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . tep_info_image($sInfo->products_image, tep_products_name($sInfo->products_id)));
    $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_INFO_NEW_PRICE . ' ' . tep_currency_format($sInfo->specials_price));
    $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_INFO_ORIGINAL_PRICE . ' ' . tep_currency_format($sInfo->products_price));
    $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_INFO_PERCENTAGE . ' ' . number_format($sInfo->percentage, 2) . '%');
  }
?>
              <tr bgcolor="#b0c8df"><?=$form;?>
                <td>
                  <? new infoBox($info_box_contents); ?>
                </td>
              <? if ($form) echo '</form>';?></tr>
              <tr bgcolor="#b0c8df">
                <td><?=tep_black_line();?></td>
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