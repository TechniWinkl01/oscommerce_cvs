<?php
/*
  $Id: tax_rates.php,v 1.19 2001/12/14 13:19:17 jan0815 Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'insert') {
      tep_db_query("insert into " . TABLE_TAX_RATES . " (tax_zone_id, tax_class_id, tax_rate, tax_description, date_added) values ('" . $HTTP_POST_VARS['tax_zone_id'] . "', '" . $HTTP_POST_VARS['tax_class_id'] . "', '" . $HTTP_POST_VARS['tax_rate'] . "', '" . $HTTP_POST_VARS['tax_description'] . "', now())");
      header('Location: ' . tep_href_link(FILENAME_TAX_RATES, '', 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'save') {
      tep_db_query("update " . TABLE_TAX_RATES . " set tax_zone_id = '" . $HTTP_POST_VARS['tax_zone_id'] . "', tax_class_id = '" . $HTTP_POST_VARS['tax_class_id'] . "', tax_priority = '" . $HTTP_POST_VARS['tax_priority'] . "', tax_rate = '" . $HTTP_POST_VARS['tax_rate'] . "', tax_description = '" . $HTTP_POST_VARS['tax_description'] . "', last_modified = now() where tax_rates_id = '" . $HTTP_POST_VARS['tax_rates_id'] . "'");
      header('Location: ' . tep_href_link(FILENAME_TAX_RATES, tep_get_all_get_params(array('action')), 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'deleteconfirm') {
      tep_db_query("delete from " . TABLE_TAX_RATES . " where tax_rates_id = '" . $HTTP_POST_VARS['tax_rates_id'] . "'");
      header('Location: ' . tep_href_link(FILENAME_TAX_RATES, tep_get_all_get_params(array('action', 'info')), 'NONSSL')); tep_exit();
    }
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
  if (($HTTP_GET_VARS['action'] == 'edit') || ($HTTP_GET_VARS['action'] == 'new')) {
  }
?>
</head>
<body onload="SetFocus();">
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
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="5"><?php echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="tableHeading">&nbsp;<?php echo TABLE_HEADING_TAX_RATE_PRIORITY; ?>&nbsp;</td>
                <td class="tableHeading">&nbsp;<?php echo TABLE_HEADING_TAX_CLASS_TITLE; ?>&nbsp;</td>
                <td class="tableHeading">&nbsp;<?php echo TABLE_HEADING_ZONE; ?>&nbsp;</td>
                <td class="tableHeading">&nbsp;<?php echo TABLE_HEADING_TAX_RATE; ?>&nbsp;</td>
                <td align="center" class="tableHeading">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="5"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
  $rows = 0;
  $rates_query_raw = "select r.tax_rates_id, z.geo_zone_id, z.geo_zone_name, tc.tax_class_title, tc.tax_class_id, r.tax_priority, r.tax_rate, r.tax_description, r.date_added, r.last_modified from " . TABLE_TAX_CLASS . " tc, " . TABLE_TAX_RATES . " r left join " . TABLE_GEO_ZONES . " z on r.tax_zone_id = z.geo_zone_id where r.tax_class_id = tc.tax_class_id";
  $rates_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $rates_query_raw, $rates_query_numrows);
  $rates_query = tep_db_query($rates_query_raw);
  while ($rates = tep_db_fetch_array($rates_query)) {
    $rows++;

    if (((!$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['info'] == $rates['tax_rates_id'])) && (!$trInfo) && (substr($HTTP_GET_VARS['action'], 0, 3) != 'new')) {
      $trInfo = new taxRateInfo($rates);
    }

    if ($rates['tax_rates_id'] == @$trInfo->id) {
      echo '                  <tr class="selectedRow">' . "\n";
    } else {
      echo '                  <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_TAX_RATES, tep_get_all_get_params(array('info', 'action')) . 'info=' . $rates['tax_rates_id'], 'NONSSL') . '\'">' . "\n";
    }
?>
                <td width="10%" class="smallText">&nbsp;<?php echo $rates['tax_priority']; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<?php echo $rates['tax_class_title']; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<?php echo $rates['geo_zone_name']; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<?php echo number_format($rates['tax_rate'], TAX_DECIMAL_PLACES); ?>%&nbsp;</td>
<?php
    if ($rates['tax_rates_id'] == @$trInfo->id) {
?>
                <td align="center" class="smallText">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); ?>&nbsp;</td>
<?php
    } else {
?>
                <td align="center" class="smallText">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_TAX_RATES, tep_get_all_get_params(array('info', 'action')) . 'info=' . $rates['tax_class_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; ?>&nbsp;</td>
<?php
    }
?>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="5"><?php echo tep_black_line(); ?></td>
              </tr>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td valign="top" class="smallText">&nbsp;<?php echo $rates_split->display_count($rates_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_TAX_RATES); ?>&nbsp;</td>
                    <td align="right" class="smallText">&nbsp;<?php echo TEXT_RESULT_PAGE; ?> <?php echo $rates_split->display_links($rates_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?>&nbsp;<?php if (!$HTTP_GET_VARS['action']) echo '<br><br>&nbsp;<a href="' . tep_href_link(FILENAME_TAX_RATES, tep_get_all_get_params(array('action', 'info')) . 'action=new', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_new_tax_rate.gif', IMAGE_NEW_TAX_RATE) . '</a>&nbsp;'; ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="25%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  $info_box_contents = array();
  if ($trInfo) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $trInfo->class_title . '</b>&nbsp;');
  if ((!$tcInfo) && ($HTTP_GET_VARS['action'] == 'new')) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . TEXT_INFO_HEADING_NEW_TAX_CLASS . '</b>&nbsp;');
?>
              <tr class="boxHeading">
                <td><?php new infoBoxHeading($info_box_contents); ?></td>
              </tr>
              <tr class="boxHeading">
                <td><?php echo tep_black_line(); ?></td>
              </tr>
<?php
  if ($HTTP_GET_VARS['action'] == 'new') {
    $form = '<form name="rates" action="' . tep_href_link(FILENAME_TAX_RATES, tep_get_all_get_params(array('action')) . 'action=insert', 'NONSSL') . '" method="post">'  ."\n";

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_EDIT_INTRO . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_CLASS_TITLE . '<br>' . tep_tax_classes_pull_down('name="tax_class_id" style="font-size:10px"') . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_ZONE_NAME . '<br>' . tep_geo_zones_pull_down('name="tax_zone_id" style="font-size:10px"') . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_TAX_RATE . '<br><input type="text" name="tax_rate"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_RATE_DESCRIPTION . '<br><input type="text" name="tax_description"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_WS_IMAGES . 'button_insert.gif', IMAGE_INSERT) . '&nbsp;<a href="' . tep_href_link(FILENAME_TAX_RATES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
  } elseif ($HTTP_GET_VARS['action'] == 'edit') {
    $form = '<form name="rates" action="' . tep_href_link(FILENAME_TAX_RATES, tep_get_all_get_params(array('action')) . 'action=save', 'NONSSL') . '" method="post"><input type="hidden" name="tax_rates_id" value="' . $trInfo->id . '">'  ."\n";

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_EDIT_INTRO . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_CLASS_TITLE . '<br>' . tep_tax_classes_pull_down('name="tax_class_id" style="font-size:10px"', $trInfo->class_id) . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_ZONE_NAME . '<br>' . tep_geo_zones_pull_down('name="tax_zone_id" style="font-size:10px"', $trInfo->zone_id) . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_TAX_RATE . '<br><input type="text" name="tax_rate" value="' . $trInfo->rate . '"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_RATE_DESCRIPTION . '<br><input type="text" name="tax_description" value="' . $trInfo->description . '"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_TAX_RATE_PRIORITY . '<br><input type="text" name="tax_priority" value="' . $trInfo->priority . '"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_WS_IMAGES . 'button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_TAX_RATES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
  } elseif ($HTTP_GET_VARS['action'] == 'delete') {
    $form = '<form name="rates" action="' . tep_href_link(FILENAME_TAX_RATES, tep_get_all_get_params(array('action')) . 'action=deleteconfirm', 'NONSSL') . '" method="post"><input type="hidden" name="tax_rates_id" value="' . $trInfo->id . '">'  ."\n";

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_DELETE_INTRO . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $trInfo->title . '</b><br>&nbsp;');
    $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_TAX_RATES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
  } else {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_TAX_RATES, tep_get_all_get_params(array('action')) . 'action=edit', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_TAX_RATES, tep_get_all_get_params(array('action')) . 'action=delete', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . '</a>');
    $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_INFO_DATE_ADDED . ' ' . tep_date_short($trInfo->date_added) . '<br>&nbsp;' . TEXT_INFO_LAST_MODIFIED . ' ' . tep_date_short($trInfo->last_modified));
    $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . TEXT_INFO_RATE_DESCRIPTION . '<br>' . $trInfo->description);
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