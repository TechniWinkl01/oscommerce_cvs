<? include('includes/application_top.php'); ?>
<?
  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'setflag') {
      switch ($HTTP_GET_VARS['flag']) {
        case '0' : tep_set_banner_status($HTTP_GET_VARS['id'], '0');
                   header('Location: ' . tep_href_link(FILENAME_BANNERS_MANAGER, '', 'NONSSL')); tep_exit();
                   break;
        case '1' : tep_set_banner_status($HTTP_GET_VARS['id'], '1');
                   header('Location: ' . tep_href_link(FILENAME_BANNERS_MANAGER, '', 'NONSSL')); tep_exit();
                   break;
        default :  header('Location: ' . tep_href_link(FILENAME_BANNERS_MANAGER, '', 'NONSSL')); tep_exit();
                   break;
      }
    } elseif ($HTTP_GET_VARS['action'] == 'update') {
      $banners_id = $HTTP_POST_VARS['banners_id'];
      $banners_title = $HTTP_POST_VARS['banners_title'];
      $banners_url = $HTTP_POST_VARS['banners_url'];
      $banners_group = (strlen($HTTP_POST_VARS['new_banners_group']) > 0) ? $HTTP_POST_VARS['new_banners_group'] : $HTTP_POST_VARS['banners_group'];
      $banners_image_target = $HTTP_POST_VARS['banners_image_target'];

      $html_text = '';
      $db_image_location = '';
      if ($HTTP_POST_VARS['html_text']) {
        $html_text = $HTTP_POST_VARS['html_text'];
      } else {
        $image_location = '';
        if ($banners_image != 'none') {
          $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES;
          if ($banners_image_target != '') {
            $image_location .= $banners_image_target;
          }
          copy($banners_image, $image_location . $banners_image_name);
        } elseif ($banners_image_local != '') {
          $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $HTTP_POST_VARS['banners_image_local'];
        }

        $db_image_location = ($HTTP_POST_VARS['banners_image_local'] != '') ? $HTTP_POST_VARS['banners_image_local'] : $banners_image_target . $banners_image_name;
      }

      tep_db_query("update " . TABLE_BANNERS . " set banners_title = '" . $banners_title . "', banners_url = '" . $banners_url . "', banners_image = '" . $db_image_location . "', banners_group = '" . $banners_group . "', banners_html_text = '" . $html_text . "' where banners_id = '" . $banners_id . "'");

      if ($HTTP_POST_VARS['day'] && $HTTP_POST_VARS['month'] && $HTTP_POST_VARS['year']) {
        $expires_date = $HTTP_POST_VARS['year'];
        $expires_date .= (strlen($HTTP_POST_VARS['month']) == 1) ? '0' . $HTTP_POST_VARS['month'] : $HTTP_POST_VARS['month'];
        $expires_date .= (strlen($HTTP_POST_VARS['day']) == 1) ? '0' . $HTTP_POST_VARS['day'] : $HTTP_POST_VARS['day'];

        tep_db_query("update " . TABLE_BANNERS . " set expires_date = '" . $expires_date . "', expires_impressions = null where banners_id = '" . $banners_id . "'");
      } elseif ($HTTP_POST_VARS['impressions']) {
        tep_db_query("update " . TABLE_BANNERS . " set expires_impressions = '" . $HTTP_POST_VARS['impressions'] . "', expires_date = null where banners_id = '" . $banners_id . "'");
      }

      if ($HTTP_POST_VARS['sday'] && $HTTP_POST_VARS['smonth'] && $HTTP_POST_VARS['syear']) {
        $date_scheduled = $HTTP_POST_VARS['syear'];
        $date_scheduled .= (strlen($HTTP_POST_VARS['smonth']) == 1) ? '0' . $HTTP_POST_VARS['smonth'] : $HTTP_POST_VARS['smonth'];
        $date_scheduled .= (strlen($HTTP_POST_VARS['sday']) == 1) ? '0' . $HTTP_POST_VARS['sday'] : $HTTP_POST_VARS['sday'];

        tep_db_query("update " . TABLE_BANNERS . " set status = '0', date_scheduled = '" . $date_scheduled . "' where banners_id = '" . $banners_id . "'");
      }

      header('Location: ' . tep_href_link(FILENAME_BANNERS_MANAGER, '', 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'insert') {
      $banners_title = $HTTP_POST_VARS['banners_title'];
      $banners_url = $HTTP_POST_VARS['banners_url'];
      $banners_group = (strlen($HTTP_POST_VARS['new_banners_group']) > 0) ? $HTTP_POST_VARS['new_banners_group'] : $HTTP_POST_VARS['banners_group'];
      $banners_image_target = $HTTP_POST_VARS['banners_image_target'];

      $html_text = '';
      $db_image_location = '';
      if ($HTTP_POST_VARS['html_text']) {
        $html_text = $HTTP_POST_VARS['html_text'];
      } else {
        $image_location = '';
        if ($banners_image != 'none') {
          $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES;
          if ($banners_image_target != '') {
            $image_location .= $banners_image_target;
          }
          copy($banners_image, $image_location . $banners_image_name);
        } elseif ($banners_image_local != '') {
          $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $HTTP_POST_VARS['banners_image_local'];
        }

        $db_image_location = ($HTTP_POST_VARS['banners_image_local'] != '') ? $HTTP_POST_VARS['banners_image_local'] : $banners_image_target . $banners_image_name;
      }

      tep_db_query("insert into " . TABLE_BANNERS . " (banners_title, banners_url, banners_image, banners_group, banners_html_text, date_added, status) values ('" . $banners_title . "', '" . $banners_url . "', '" . $db_image_location . "', '" . $banners_group . "', '" . $html_text . "', now(), '1')");
      $banners_id = tep_db_insert_id();

      if ($HTTP_POST_VARS['day'] && $HTTP_POST_VARS['month'] && $HTTP_POST_VARS['year']) {
        $expires_date = $HTTP_POST_VARS['year'];
        $expires_date .= (strlen($HTTP_POST_VARS['month']) == 1) ? '0' . $HTTP_POST_VARS['month'] : $HTTP_POST_VARS['month'];
        $expires_date .= (strlen($HTTP_POST_VARS['day']) == 1) ? '0' . $HTTP_POST_VARS['day'] : $HTTP_POST_VARS['day'];

        tep_db_query("update " . TABLE_BANNERS . " set expires_date = '" . $expires_date . "' where banners_id = '" . $banners_id . "'");
      } elseif ($HTTP_POST_VARS['impressions']) {
        tep_db_query("update " . TABLE_BANNERS . " set expires_impressions = '" . $HTTP_POST_VARS['impressions'] . "' where banners_id = '" . $banners_id . "'");
      }

      if ($HTTP_POST_VARS['sday'] && $HTTP_POST_VARS['smonth'] && $HTTP_POST_VARS['syear']) {
        $date_scheduled = $HTTP_POST_VARS['syear'];
        $date_scheduled .= (strlen($HTTP_POST_VARS['smonth']) == 1) ? '0' . $HTTP_POST_VARS['smonth'] : $HTTP_POST_VARS['smonth'];
        $date_scheduled .= (strlen($HTTP_POST_VARS['sday']) == 1) ? '0' . $HTTP_POST_VARS['sday'] : $HTTP_POST_VARS['sday'];

        tep_db_query("update " . TABLE_BANNERS . " set status = '0', date_scheduled = '" . $date_scheduled . "' where banners_id = '" . $banners_id . "'");
      }

      header('Location: ' . tep_href_link(FILENAME_BANNERS_MANAGER, '', 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'deleteconfirm') {
      tep_db_query("delete from " . TABLE_BANNERS . " where banners_id = '" . $HTTP_GET_VARS['bID'] . "'");
      tep_db_query("delete from " . TABLE_BANNERS_HISTORY . " where banners_id = '" . $HTTP_GET_VARS['bID'] . "'");

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
<?php
  if ($HTTP_GET_VARS['action'] == 'new') {
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/calendar.css">
<script language="JavaScript" src="includes/javascript/calendarcode.js"></script>
<?php
  }
?>
</head>
<body>
<div id="popupcalendar" class="text"></div>
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
<?
  if ($HTTP_GET_VARS['action'] == 'new') {
    $form_action = 'insert';
    if ($HTTP_GET_VARS['bID']) {
	  $form_action = 'update';

      $banner_query = tep_db_query("select banners_title, banners_url, banners_image, banners_group, banners_html_text, status, date_scheduled, expires_date, expires_impressions, date_status_change from " . TABLE_BANNERS . " where banners_id = '" . $HTTP_GET_VARS['bID'] . "'");
      $banner = tep_db_fetch_array($banner_query);

      $bInfo = new bannerInfo($banner);
    } elseif ($HTTP_POST_VARS) {
      $bInfo = new bannerInfo($HTTP_POST_VARS);
    } else {
      $bInfo = new bannerInfo(array());
    }
?>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr><form name="new_banner" enctype="multipart/form-data" <? echo 'action="' . tep_href_link(FILENAME_BANNERS_MANAGER, tep_get_all_get_params(array('action', 'pinfo', 'info')) . 'action=' . $form_action, 'NONSSL') . '"'; ?> method="post"><? if ($form_action == 'update') echo '<input type="hidden" name="banners_id" value="' . $HTTP_GET_VARS['bID'] . '">'; ?>
        <td><br><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">&nbsp;<? echo TEXT_BANNERS_TITLE; ?>&nbsp;</td>
            <td class="main">&nbsp;<input type="text" name="banners_title" value="<? echo $bInfo->title; ?>">&nbsp;</td>
          </tr>
  	      <tr>
            <td class="main">&nbsp;<? echo TEXT_BANNERS_URL; ?>&nbsp;</td>
            <td class="main">&nbsp;<input type="text" name="banners_url" value="<? echo $bInfo->url; ?>">&nbsp;</td>
          </tr>
    	    <tr>
            <td class="main" valign="top">&nbsp;<? echo TEXT_BANNERS_GROUP; ?>&nbsp;</td>
            <td class="main">
<?
    echo '&nbsp;<select name="banners_group">';
    $groups_query = tep_db_query("select distinct banners_group from " . TABLE_BANNERS . " order by banners_group");
    while ($groups = tep_db_fetch_array($groups_query)) {
      echo '<option value="' . $groups['banners_group'] . '"';
	  if ($groups['banners_group'] == $bInfo->group) echo ' SELECTED';
	  echo '>' . $groups['banners_group'] . '</option>';
    }
    echo '</select>' . TEXT_BANNERS_NEW_GROUP;
?><br>&nbsp;<input type="text" name="new_banners_group">&nbsp;</td>
          </tr>
	        <tr>
            <td class="main" valign="top">&nbsp;<? echo TEXT_BANNERS_IMAGE; ?>&nbsp;</td>
            <td class="main">&nbsp;<input type="file" name="banners_image">&nbsp;<? echo TEXT_BANNERS_IMAGE_LOCAL; ?><br>&nbsp;<? echo DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES; ?><input type="text" name="banners_image_local" value="<? echo $bInfo->image; ?>">&nbsp;</td>
          </tr>
          <tr>
            <td class="main">&nbsp;<? echo TEXT_BANNERS_IMAGE_TARGET; ?>&nbsp;</td>
            <td class="main">&nbsp;<? echo DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES; ?><input type="text" name="banners_image_target">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top" class="main">&nbsp;<? echo TEXT_BANNERS_HTML_TEXT; ?>&nbsp;</td>
            <td class="main">&nbsp;<textarea name="html_text" cols="60" rows="5"><? echo $bInfo->html_text; ?></textarea></td>
          </tr>
          <tr>
            <td class="main">&nbsp;<? echo TEXT_BANNERS_EXPIRES_ON; ?><br>&nbsp;<span class="smallText">(dd/mm/yyyy)</span>&nbsp;</td>
            <td class="main">&nbsp;<input class="cal-TextBox" size="2" maxlength="2" type="text" name="day" value="<? echo $bInfo->expires_date_caljs_day; ?>"><input class="cal-TextBox" size="2" maxlength="2" type="text" name="month" value="<? echo $bInfo->expires_date_caljs_month; ?>"><input class="cal-TextBox" size="4" maxlength="4" type="text" name="year" value="<? echo $bInfo->expires_date_caljs_year; ?>"><a class="so-BtnLink" href="javascript:calClick();return false;" onmouseover="calSwapImg('BTN_date', 'img_Date_OVER',true);" onmouseout="calSwapImg('BTN_date', 'img_Date_UP',true);" onclick="calSwapImg('BTN_date', 'img_Date_DOWN');showCalendar('new_banner','dteWhen','BTN_date');return false;"><img align="absmiddle" border="0" name="BTN_date" src="<?php echo DIR_WS_IMAGES; ?>cal_date_up.gif" width="22" height="17"></a><? echo TEXT_BANNERS_OR_AT; ?>&nbsp;<br>&nbsp;<input type="text" name="impressions" maxlength="7" size="7" value="<? echo $bInfo->expires_impressions; ?>"> <? echo TEXT_BANNERS_IMPRESSIONS; ?></td>
          </tr>
          <tr>
            <td class="main">&nbsp;<? echo TEXT_BANNERS_SCHEDULED_AT; ?><br>&nbsp;<span class="smallText">(dd/mm/yyyy)</span>&nbsp;</td>
            <td class="main">&nbsp;<input class="cal-TextBox" size="2" maxlength="2" type="text" name="sday" value="<? echo $bInfo->scheduled_date_caljs_day; ?>"><input class="cal-TextBox" size="2" maxlength="2" type="text" name="smonth" value="<? echo $bInfo->scheduled_date_caljs_month; ?>"><input class="cal-TextBox" size="4" maxlength="4" type="text" name="syear" value="<? echo $bInfo->scheduled_date_caljs_year; ?>"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><br><? echo TEXT_BANNERS_BANNER_NOTE; ?><br><? echo TEXT_BANNERS_INSERT_NOTE; ?><br><? echo TEXT_BANNERS_EXPIRCY_NOTE; ?><br><? echo TEXT_BANNERS_SCHEDULE_NOTE; ?></td>
            <td class="main" align="right" valign="top"><br><? echo (($form_action == 'insert') ? tep_image_submit(DIR_WS_IMAGES . 'button_insert.gif', IMAGE_INSERT) : tep_image_submit(DIR_WS_IMAGES . 'button_update.gif', IMAGE_UPDATE)). '&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_BANNERS_MANAGER, '', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
          </tr>
        </table></td>
      </form></tr>
<?
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2"><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="tableHeading">&nbsp;<? echo TABLE_HEADING_BANNERS; ?>&nbsp;</td>
                <td class="tableHeading" align="right">&nbsp;<? echo TABLE_HEADING_GROUPS; ?>&nbsp;</td>
                <td class="tableHeading" align="right">&nbsp;<? echo TABLE_HEADING_STATISTICS; ?>&nbsp;</td>
                <td class="tableHeading" align="right">&nbsp;<? echo TABLE_HEADING_STATUS; ?>&nbsp;</td>
                <td class="tableHeading" align="center">&nbsp;<? echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="5"><? echo tep_black_line(); ?></td>
              </tr>
<?
    $banners_query_raw = "select banners_id, banners_title, banners_image, banners_group, status, expires_date, expires_impressions, date_status_change, date_scheduled, date_added from " . TABLE_BANNERS . " order by banners_title, banners_group";
    $banners_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $banners_query_raw, $banners_query_numrows);
    $banners_query = tep_db_query($banners_query_raw);
    while ($banners = tep_db_fetch_array($banners_query)) {
      $info_query = tep_db_query("select sum(banners_shown) as banners_shown, sum(banners_clicked) as banners_clicked from " . TABLE_BANNERS_HISTORY . " where banners_id = '" . $banners['banners_id'] . "'");
      $info = tep_db_fetch_array($info_query);

      if (((!$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['info'] == $banners['banners_id'])) && (!$bInfo) && (substr($HTTP_GET_VARS['action'], 0, 3) != 'new')) {
        $bInfo_array = tep_array_merge($banners, $info);
        $bInfo = new bannerInfo($bInfo_array);
      }

      $banners_shown = ($info['banners_shown'] != '') ? $info['banners_shown'] : '0';
      $banners_clicked = ($info['banners_clicked'] != '') ? $info['banners_clicked'] : '0';

      if ($banners['banners_id'] == @$bInfo->id) {
        echo '                  <tr class="selectedRow">' . "\n";
      } else {
        echo '                  <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_BANNERS_MANAGER, tep_get_all_get_params(array('info', 'action')) . 'info=' . $banners['banners_id'], 'NONSSL') . '\'">' . "\n";
      }
?>
                <td class="tableData">&nbsp;<? echo '<a href="javascript:popupImageWindow(\'' . FILENAME_POPUP_IMAGE . '?image=' . DIR_WS_CATALOG_IMAGES . $banners['banners_image'] . '&alt=' . urlencode($banners['banners_title']) . '\')" class="blacklink">' . $banners['banners_title'] . '</a>'; ?>&nbsp;</td>
                <td class="tableData" align="right">&nbsp;<? echo $banners['banners_group']; ?>&nbsp;</td>
                <td class="tableData" align="right">&nbsp;<? echo $banners_shown . ' / ' . $banners_clicked; ?>&nbsp;</td>
                <td class="tableData" align="right">
<?
      if ($banners['status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', 'Active', 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_BANNERS_MANAGER, 'action=setflag&flag=0&id=' . $banners['banners_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', 'Set Inactive', 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_BANNERS_MANAGER, 'action=setflag&flag=1&id=' . $banners['banners_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', 'Set Active', 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', 'Inactive', 10, 10);
      }
?>&nbsp;</td>
<?
      if ($banners['banners_id'] == @$bInfo->id) {
?>
                    <td align="center" class="smallText">&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); ?>&nbsp;</td>
<?
    } else {
?>
                    <td align="center" class="smallText">&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_BANNERS_MANAGER, tep_get_all_get_params(array('info', 'action')) . 'info=' . $banners['banners_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; ?>&nbsp;</td>
<?
    }
?>
              </tr>
<?
    }
?>
              <tr>
                <td class="main" colspan="5"><? echo tep_black_line(); ?></td>
              </tr>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText">&nbsp;<? echo $banners_split->display_count($banners_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_BANNERS); ?>&nbsp;</td>
                    <td class="smallText" align="right">&nbsp;<? echo TEXT_RESULT_PAGE; ?> <? echo $banners_split->display_links($banners_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?>&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="right" colspan="2"><? echo '<a href="' . tep_href_link(FILENAME_BANNERS_MANAGER, 'action=new', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_new_banner.gif', IMAGE_NEW_BANNER) . '</a>'; ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="25%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?
    $info_box_contents = array();
    if ($bInfo) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $bInfo->title . '</b>&nbsp;');
?>
              <tr class="boxHeading">
                <td><? new infoBoxHeading($info_box_contents); ?></td>
              </tr>
              <tr class="boxHeading">
                <td><? echo tep_black_line(); ?></td>
              </tr>
<?
  if ($HTTP_GET_VARS['action'] == 'delete') {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_DELETE_INTRO . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $bInfo->title . '</b><br>&nbsp;');
    $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_BANNERS_MANAGER, 'action=deleteconfirm&bID=' . $HTTP_GET_VARS['bID']) . '">' . tep_image(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_BANNERS_MANAGER, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
  } else {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_BANNERS_MANAGER, tep_get_all_get_params(array('action', 'bID')) . 'action=new&bID=' . $bInfo->id, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_BANNERS_MANAGER, tep_get_all_get_params(array('action', 'bID')) . 'action=delete&bID=' . $bInfo->id, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . '</a>');
      $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_BANNERS_DATE_ADDED . ' ' . tep_date_short($bInfo->date_added));
    $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_banner_graph_infoBox($bInfo->id, '3'));
    $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . tep_image(DIR_WS_IMAGES . 'graph_hbar_blue.gif', 'Blue', '5', '5') . ' Banner Views<br>&nbsp;' . tep_image(DIR_WS_IMAGES . 'graph_hbar_red.gif', 'Red', '5', '5') . ' Banner Clicks');

    if ($bInfo->date_scheduled) {
      $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . sprintf(TEXT_BANNERS_SCHEDULED_AT_DATE, tep_date_short($bInfo->date_scheduled)));
    }

    if ($bInfo->expires_date) {
      $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . sprintf(TEXT_BANNERS_EXPIRES_AT_DATE, tep_date_short($bInfo->expires_date)));
    } elseif ($bInfo->expires_impressions) {
      $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . sprintf(TEXT_BANNERS_EXPIRES_AT_IMPRESSIONS, $bInfo->expires_impressions));
    }

    if ($bInfo->date_status_change) {
      $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . sprintf(TEXT_BANNERS_STATUS_CHANGE, tep_date_short($bInfo->date_status_change)));
    }
  }
?>
              <tr><? echo $form; ?>
                <td class="box"><? new infoBox($info_box_contents); ?></td>
              <? if ($form) echo '</form>'; ?></tr>
              <tr>
                <td class="box"><? echo tep_black_line(); ?></td>
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
<? $include_file = DIR_WS_INCLUDES . 'footer.php';  include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<? $include_file = DIR_WS_INCLUDES . 'application_bottom.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
