<?php
/*
  $Id: specials.php,v 1.28 2002/01/09 11:46:44 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  switch ($HTTP_GET_VARS['action']) {
    case 'setflag':
      tep_set_specials_status($HTTP_GET_VARS['id'], $HTTP_GET_VARS['flag']);
      tep_redirect(tep_href_link(FILENAME_SPECIALS, '', 'NONSSL'));
      break;
    case 'insert':
// insert a product on special
      if (substr($HTTP_POST_VARS['specials_price'], -1) == '%') $HTTP_POST_VARS['specials_price'] = ($HTTP_POST_VARS['products_price'] - (($HTTP_POST_VARS['specials_price'] / 100) * $HTTP_POST_VARS['products_price']));

      $expires_date = '';
      if ($HTTP_POST_VARS['day'] && $HTTP_POST_VARS['month'] && $HTTP_POST_VARS['year']) {
        $expires_date = $HTTP_POST_VARS['year'];
        $expires_date .= (strlen($HTTP_POST_VARS['month']) == 1) ? '0' . $HTTP_POST_VARS['month'] : $HTTP_POST_VARS['month'];
        $expires_date .= (strlen($HTTP_POST_VARS['day']) == 1) ? '0' . $HTTP_POST_VARS['day'] : $HTTP_POST_VARS['day'];
      }

      tep_db_query("insert into " . TABLE_SPECIALS . " (products_id, specials_new_products_price, specials_date_added, expires_date, status) values ('" . $HTTP_POST_VARS['products_id'] . "', '" . $HTTP_POST_VARS['specials_price'] . "', now(), '" . $expires_date . "', '1')");
      tep_redirect(tep_href_link(FILENAME_SPECIALS, '', 'NONSSL'));
      break;
    case 'update':
// update a product on special
      if (substr($HTTP_POST_VARS['specials_price'], -1) == '%') $HTTP_POST_VARS['specials_price'] = ($HTTP_POST_VARS['products_price'] - (($HTTP_POST_VARS['specials_price'] / 100) * $HTTP_POST_VARS['products_price']));

      $expires_date = '';
      if ($HTTP_POST_VARS['day'] && $HTTP_POST_VARS['month'] && $HTTP_POST_VARS['year']) {
        $expires_date = $HTTP_POST_VARS['year'];
        $expires_date .= (strlen($HTTP_POST_VARS['month']) == 1) ? '0' . $HTTP_POST_VARS['month'] : $HTTP_POST_VARS['month'];
        $expires_date .= (strlen($HTTP_POST_VARS['day']) == 1) ? '0' . $HTTP_POST_VARS['day'] : $HTTP_POST_VARS['day'];
      }

      tep_db_query("update " . TABLE_SPECIALS . " set specials_new_products_price = '" . $HTTP_POST_VARS['specials_price'] . "', specials_last_modified = now(), expires_date = '" . $expires_date . "' where specials_id = '" . $HTTP_POST_VARS['specials_id'] . "'");
      tep_redirect(tep_href_link(FILENAME_SPECIALS, '', 'NONSSL'));
      break;
    case 'delete':
// delete a product on special
      tep_db_query("delete from " . TABLE_SPECIALS . " where specials_id = '" . $HTTP_POST_VARS['specials_id'] . "'");
      tep_redirect(tep_href_link(FILENAME_SPECIALS, '', 'NONSSL'));
      break;
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<?php
  if ( ($HTTP_GET_VARS['action'] == 'new') || ($HTTP_GET_VARS['action'] == 'edit') ) {
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/calendar.css">
<script language="JavaScript" src="includes/javascript/calendarcode.js"></script>
<?php
  }
?>
</head>
<body onload="SetFocus();">
<div id="popupcalendar" class="text"></div>
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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<?php echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
<?php
  if ( ($HTTP_GET_VARS['action'] == 'new') || ($HTTP_GET_VARS['action'] == 'edit') ) {
    $form_action = 'insert';
    if ( ($HTTP_GET_VARS['action'] == 'edit') && ($HTTP_GET_VARS['sID']) ) {
	  $form_action = 'update';

      $product_query = tep_db_query("select p.products_id, pd.products_name, p.products_price, s.specials_new_products_price, s.expires_date from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_id = s.products_id and s.specials_id = '" . $HTTP_GET_VARS['sID'] . "'");
      $product = tep_db_fetch_array($product_query);

      $sInfo = new specialPriceInfo($product);
    } else {
      $sInfo = new specialPriceInfo(array());

// create an array of products on special, which will be excluded from the pull down menu of products
// (when creating a new product on special)
      $specials_array = array();
      $specials_query = tep_db_query("select p.products_id from " . TABLE_PRODUCTS . " p, " . TABLE_SPECIALS . " s where s.products_id = p.products_id");
      while ($specials = tep_db_fetch_array($specials_query)) {
        $specials_array[] = $specials['products_id'];
      }
    }
?>
      <tr>
        <td><?php echo tep_black_line(); ?></td>
      </tr>
      <tr><form name="new_special" <?php echo 'action="' . tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params(array('action', 'info', 'sID')) . 'action=' . $form_action, 'NONSSL') . '"'; ?> method="post"><?php if ($form_action == 'update') echo tep_draw_hidden_field('specials_id', $HTTP_GET_VARS['sID']); ?>
        <td><br><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">&nbsp;<?php echo TEXT_SPECIALS_PRODUCT; ?>&nbsp;</td>
            <td class="main">&nbsp;<?php echo ($sInfo->products_name) ? $sInfo->products_name . ' <small>(' . tep_currency_format($sInfo->products_price) . ')</small>' : tep_draw_products_pull_down('products_id', 'style="font-size:10px"', $specials_array); echo tep_draw_hidden_field('products_price', $sInfo->products_price); ?>&nbsp;</td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo TEXT_SPECIALS_SPECIAL_PRICE; ?>&nbsp;</td>
            <td class="main">&nbsp;<?php echo tep_draw_input_field('specials_price', $sInfo->specials_price); ?>&nbsp;</td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo TEXT_SPECIALS_EXPIRES_DATE; ?><br>&nbsp;<span class="smallText">(dd/mm/yyyy)</span>&nbsp;</td>
            <td class="main">&nbsp;<?php echo tep_draw_input_field('day', $sInfo->expires_date_caljs_day, 'size="2" maxlength="2" class="cal-TextBox"') . tep_draw_input_field('month', $sInfo->expires_date_caljs_month, 'size="2" maxlength="2" class="cal-TextBox"') . tep_draw_input_field('year', $sInfo->expires_date_caljs_year, 'size="4" maxlength="4" class="cal-TextBox"'); ?><a class="so-BtnLink" href="javascript:calClick();return false;" onmouseover="calSwapImg('BTN_date', 'img_Date_OVER',true);" onmouseout="calSwapImg('BTN_date', 'img_Date_UP',true);" onclick="calSwapImg('BTN_date', 'img_Date_DOWN');showCalendar('new_special','dteWhen','BTN_date');return false;"><?php echo tep_image(DIR_WS_IMAGES . 'cal_date_up.gif', 'Calendar', '22', '17', 'align="absmiddle" name="BTN_date"'); ?></a>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><?php echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><br><?php echo TEXT_SPECIALS_PRICE_TIP; ?></td>
            <td class="main" align="right" valign="top"><br><?php echo (($form_action == 'insert') ? tep_image_submit(DIR_WS_IMAGES . 'button_insert.gif', IMAGE_INSERT) : tep_image_submit(DIR_WS_IMAGES . 'button_update.gif', IMAGE_UPDATE)). '&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_SPECIALS, '', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
          </tr>
        </table></td>
      </form></tr>
<?php
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2"><?php echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="tableHeading">&nbsp;<?php echo TABLE_HEADING_PRODUCTS; ?>&nbsp;</td>
                <td class="tableHeading" align="right">&nbsp;<?php echo TABLE_HEADING_PRODUCTS_PRICE; ?>&nbsp;</td>
                <td class="tableHeading" align="right">&nbsp;<?php echo TABLE_HEADING_STATUS; ?>&nbsp;</td>
                <td class="tableHeading" align="center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="4"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    $rows = 0;
    $specials_query_raw = "select p.products_id, pd.products_name, p.products_price, s.specials_id, s.specials_new_products_price, s.specials_date_added, s.specials_last_modified, s.expires_date, s.date_status_change, s.status from " . TABLE_PRODUCTS . " p, " . TABLE_SPECIALS . " s, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_id = s.products_id order by pd.products_name";
    $specials_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $specials_query_raw, $specials_query_numrows);
    $specials_query = tep_db_query($specials_query_raw);
    while ($specials = tep_db_fetch_array($specials_query)) {
      $rows++;

      if ( ((!$HTTP_GET_VARS['info']) || ($HTTP_GET_VARS['info'] == $specials['specials_id'])) && (!$sInfo) ) {
        $products_query = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . $specials['products_id'] . "'");
        $products = tep_db_fetch_array($products_query);

        $sInfo_array = tep_array_merge($specials, $products);
        $sInfo = new specialPriceInfo($sInfo_array);
      }

      if ($specials['specials_id'] == $sInfo->id) {
        echo '                  <tr class="selectedRow">' . "\n";
      } else {
        echo '                  <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params(array('info', 'action')) . 'info=' . $specials['specials_id'], 'NONSSL') . '\'">' . "\n";
      }
?>
                <td class="smallText">&nbsp;<?php echo $specials['products_name']; ?>&nbsp;</td>
                <td align="right" class="smallText">&nbsp;<span class="oldPrice"><?php echo tep_currency_format($specials['products_price']); ?></span> <span class="specialPrice"><?php echo tep_currency_format($specials['specials_new_products_price']); ?></span>&nbsp;</td>
                <td align="right" class="smallText">&nbsp;
<?php
      if ($specials['status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_SPECIALS, 'action=setflag&flag=0&id=' . $specials['specials_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_SPECIALS, 'action=setflag&flag=1&id=' . $specials['specials_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?>&nbsp;</td>
<?php
      if ($specials['specials_id'] == $sInfo->id) {
?>
                <td align="center" class="smallText">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); ?>&nbsp;</td>
<?php
      } else {
?>
                <td align="center" class="smallText">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params(array('info', 'action')) . 'info=' . $specials['specials_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; ?>&nbsp;</td>
<?php
      }
?>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="4"><?php echo tep_black_line(); ?></td>
              </tr>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellpadding="0"cellspacing="2">
                  <tr>
                    <td valign="top" class="smallText">&nbsp;<?php echo $specials_split->display_count($specials_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_SPECIALS); ?>&nbsp;</td>
                    <td align="right" class="smallText">&nbsp;<?php echo TEXT_RESULT_PAGE; ?> <?php echo $specials_split->display_links($specials_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?>&nbsp;<br><br>&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params(array('action', 'info')) . 'action=new', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_new_product.gif', IMAGE_NEW_PRODUCT) . '</a>'; ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="25%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
    $info_box_contents = array();
    if ($sInfo) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $sInfo->products_name . '</b>&nbsp;');
?>
              <tr class="boxHeading">
                <td><?php new infoBoxHeading($info_box_contents); ?></td>
              </tr>
              <tr class="boxHeading">
                <td><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    $info_box_contents = array();
    if ($sInfo) {
      if ($HTTP_GET_VARS['action'] == 'deleteconfirm') {
        $form = '<form name="specials_delete" action="' . tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params(array('action')) . 'action=delete', 'NONSSL') . '" method="post"><input type="hidden" name="specials_id" value="' . $sInfo->id . '">' . "\n";

        $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_DELETE_INTRO . '<br>&nbsp;');
        $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $sInfo->products_name . '</b>');
        $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . '&nbsp;<a href="' . tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
      } else { // default info box
        $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params(array('action', 'info')) . 'action=edit&sID=' . $sInfo->id, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_edit.gif', IMAGE_EDIT) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params(array('action')) . 'action=deleteconfirm', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . '</a>');
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_INFO_DATE_ADDED . ' ' . tep_date_short($sInfo->date_added) . '<br>&nbsp;' . TEXT_INFO_LAST_MODIFIED . ' ' . tep_date_short($sInfo->last_modified));
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . tep_info_image($sInfo->products_image, $sInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT));
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_INFO_NEW_PRICE . ' ' . tep_currency_format($sInfo->specials_price));
        $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_INFO_ORIGINAL_PRICE . ' ' . tep_currency_format($sInfo->products_price));
        $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_INFO_PERCENTAGE . ' ' . number_format($sInfo->percentage) . '%');

        if ($sInfo->expires_date) {
          $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . sprintf(TEXT_INFO_EXPIRES_AT, tep_date_short($sInfo->expires_date)));
        }

        if ($sInfo->date_status_change) {
          $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . sprintf(TEXT_INFO_STATUS_CHANGE, tep_date_short($sInfo->date_status_change)));
        }
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
<?php
  }
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>