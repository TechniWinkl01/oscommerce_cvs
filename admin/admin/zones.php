<?php
/*
  $Id: zones.php,v 1.16 2002/01/05 05:29:22 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'insert') {
      tep_db_query("insert into " . TABLE_ZONES . " (zone_country_id, zone_code, zone_name) values ('" . $HTTP_POST_VARS['zone_country_id'] . "', '" . $HTTP_POST_VARS['zone_code'] . "', '" . $HTTP_POST_VARS['zone_name'] . "')");
      header('Location: ' . tep_href_link(FILENAME_ZONES, '', 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'save') {
      tep_db_query("update " . TABLE_ZONES . " set zone_country_id = '" . $HTTP_POST_VARS['zone_country_id'] . "', zone_code = '" . $HTTP_POST_VARS['zone_code'] . "', zone_name = '" . $HTTP_POST_VARS['zone_name'] . "' where zone_id = '" . $HTTP_POST_VARS['zone_id'] . "'");
      header('Location: ' . tep_href_link(FILENAME_ZONES, tep_get_all_get_params(array('action')), 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'deleteconfirm') {
      tep_db_query("delete from " . TABLE_ZONES . " where zone_id = '" . $HTTP_POST_VARS['zone_id'] . "'");
      header('Location: ' . tep_href_link(FILENAME_ZONES, tep_get_all_get_params(array('action', 'info')), 'NONSSL')); tep_exit();
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
            <td colspan="2"><?php echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="tableHeading">&nbsp;<?php echo TABLE_HEADING_COUNTRY_NAME; ?>&nbsp;</td>
                <td class="tableHeading">&nbsp;<?php echo TABLE_HEADING_ZONE_NAME; ?>&nbsp;</td>
                <td class="tableHeading" align="center">&nbsp;<?php echo TABLE_HEADING_ZONE_CODE; ?>&nbsp;</td>
                <td class="tableHeading" align="center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="4"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
  $rows = 0;
  $zones_query_raw = "select z.zone_id, c.countries_name, z.zone_name, z.zone_code, z.zone_country_id from " . TABLE_ZONES . " z, " . TABLE_COUNTRIES . " c where z.zone_country_id = c.countries_id order by c.countries_name, z.zone_name";
  $zones_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $zones_query_raw, $zones_query_numrows);
  $zones_query = tep_db_query($zones_query_raw);
  while ($zones = tep_db_fetch_array($zones_query)) {
    $rows++;

    if (((!$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['info'] == $zones['zone_id'])) && (!$zInfo) && (substr($HTTP_GET_VARS['action'], 0, 3) != 'new')) {
      $zInfo = new zonesInfo($zones);
    }

    if ($zones['zone_id'] == @$zInfo->id) {
      echo '                  <tr class="selectedRow">' . "\n";
    } else {
      echo '                  <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ZONES, tep_get_all_get_params(array('info', 'action')) . 'info=' . $zones['zone_id'], 'NONSSL') . '\'">' . "\n";
    }
?>
                <td class="smallText">&nbsp;<?php echo $zones['countries_name']; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<?php echo $zones['zone_name']; ?>&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<?php echo $zones['zone_code']; ?>&nbsp;</td>
<?php
    if ($zones['zone_id'] == @$zInfo->id) {
?>
                <td align="center" class="smallText">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); ?>&nbsp;</td>
<?php
    } else {
?>
                <td align="center" class="smallText">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_ZONES, tep_get_all_get_params(array('info', 'action')) . 'info=' . $zones['zone_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; ?>&nbsp;</td>
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
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td valign="top" class="smallText">&nbsp;<?php echo $zones_split->display_count($zones_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ZONES); ?>&nbsp;</td>
                    <td align="right" class="smallText">&nbsp;<?php echo TEXT_RESULT_PAGE; ?> <?php echo $zones_split->display_links($zones_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?>&nbsp;<?php if (!$HTTP_GET_VARS['action']) echo '<br><br>&nbsp;<a href="' . tep_href_link(FILENAME_ZONES, tep_get_all_get_params(array('action', 'info')) . 'action=new', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_new_zone.gif', IMAGE_NEW_ZONE) . '</a>&nbsp;'; ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="25%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
  $info_box_contents = array();
  if ($zInfo) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $zInfo->name . '</b>&nbsp;');
  if ((!$zInfo) && ($HTTP_GET_VARS['action'] == 'new')) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . TEXT_INFO_HEADING_NEW_ZONE . '</b>&nbsp;');
?>
              <tr class="boxHeading">
                <td><?php new infoBoxHeading($info_box_contents); ?></td>
              </tr>
              <tr class="boxHeading">
                <td><?php echo tep_black_line(); ?></td>
              </tr>
<?php
  if ($HTTP_GET_VARS['action'] == 'new') {
    $form = '<form name="zones" action="' . tep_href_link(FILENAME_ZONES, tep_get_all_get_params(array('action')) . 'action=insert', 'NONSSL') . '" method="post">'  ."\n";

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_INSERT_INTRO . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_ZONES_NAME . '<br><input type="text" name="zone_name"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_ZONES_CODE . '<br><input type="text" name="zone_code"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_COUNTRY_NAME . '<br>' . tep_draw_pull_down_menu('zone_country_id', tep_get_countries()) . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_WS_IMAGES . 'button_insert.gif', IMAGE_INSERT) . '&nbsp;<a href="' . tep_href_link(FILENAME_ZONES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
  } elseif ($HTTP_GET_VARS['action'] == 'edit') {
    $form = '<form name="zones" action="' . tep_href_link(FILENAME_ZONES, tep_get_all_get_params(array('action')) . 'action=save', 'NONSSL') . '" method="post"><input type="hidden" name="zone_id" value="' . $zInfo->id . '">'  ."\n";

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_EDIT_INTRO . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_ZONES_NAME . '<br><input type="text" name="zone_name" value="' . $zInfo->name . '"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_ZONES_CODE . '<br><input type="text" name="zone_code" value="' . $zInfo->code . '"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_COUNTRY_NAME . '<br>' . tep_draw_pull_down_menu('zone_country_id', tep_get_countries(), $zInfo->country_id) . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_WS_IMAGES . 'button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_ZONES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
  } elseif ($HTTP_GET_VARS['action'] == 'delete') {
    $form = '<form name="zones" action="' . tep_href_link(FILENAME_ZONES, tep_get_all_get_params(array('action')) . 'action=deleteconfirm', 'NONSSL') . '" method="post"><input type="hidden" name="zone_id" value="' . $zInfo->id . '">'  ."\n";

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_DELETE_INTRO . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $zInfo->name . ' (' . $zInfo->code . ')</b><br>&nbsp;');
    $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_ZONES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
  } else {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ZONES, tep_get_all_get_params(array('action')) . 'action=edit', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_ZONES, tep_get_all_get_params(array('action')) . 'action=delete', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . '</a>');
    $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_INFO_ZONES_NAME . '<br>&nbsp;' . $zInfo->name . ' (' . $zInfo->code . ')');
    $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_INFO_COUNTRY_NAME . '<br>&nbsp;' . $zInfo->country);
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