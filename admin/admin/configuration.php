<?php
/*
  $Id: configuration.php,v 1.14 2001/09/19 10:14:23 mbs Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'save') {
      tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $HTTP_POST_VARS['configuration_value'] . "', last_modified = now() where configuration_id = '" . $HTTP_POST_VARS['configuration_id'] . "'");
      header('Location: ' . tep_href_link(FILENAME_CONFIGURATION, tep_get_all_get_params(array('action')) . '&gID=' . $HTTP_GET_VARS['gID'], 'NONSSL')); tep_exit();
    }
  }
?>
<html>
<head>
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
            <td class="pageHeading">&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right">&nbsp;<?php echo tep_image(DIR_WS_CATALOG . 'images/pixel_trans.gif', '', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
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
                <td class="tableHeading">&nbsp;<?php echo TABLE_HEADING_CONFIGURATION_TITLE; ?>&nbsp;</td>
                <td class="tableHeading">&nbsp;<?php echo TABLE_HEADING_CONFIGURATION_VALUE; ?>&nbsp;</td>
                <td class="tableHeading" align="center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
  $rows = 0;
  $configuration_query = tep_db_query("select configuration_id as cfgID, configuration_title as cfgTitle, configuration_value as cfgValue, use_function from " . TABLE_CONFIGURATION . " where configuration_group_id = '" . $HTTP_GET_VARS['gID'] . "' order by sort_order");
  while ($configuration = tep_db_fetch_array($configuration_query)) {
    $rows++;

    if (tep_not_null($configuration['use_function'])) {
      $cfgValue = $configuration['use_function']($configuration['cfgValue']);
    } else {
      $cfgValue = $configuration['cfgValue'];
    }

    if (((!$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['info'] == $configuration['cfgID'])) && (!$cfgInfo) && (substr($HTTP_GET_VARS['action'], 0, 3) != 'new')) {
      $cfg_extra_query = tep_db_query("select configuration_key as cfgKey, configuration_description as cfgDesc, date_added, last_modified, use_function, set_function from " . TABLE_CONFIGURATION . " where configuration_id = '" . $configuration['cfgID'] . "'");
      $cfg_extra = tep_db_fetch_array($cfg_extra_query);

      $cfgInfo_array = tep_array_merge($configuration, $cfg_extra);
      $cfgInfo = new configurationInfo($cfgInfo_array);
    }

    if ($configuration['cfgID'] == @$cfgInfo->id) {
      echo '                  <tr class="selectedRow">' . "\n";
    } else {
      echo '                  <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CONFIGURATION, tep_get_all_get_params(array('info', 'action')) . 'info=' . $configuration['cfgID'], 'NONSSL') . '\'">' . "\n";
    }
?>
                <td class="smallText">&nbsp;<?php echo $configuration['cfgTitle']; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<?php echo htmlspecialchars($cfgValue); ?>&nbsp;</td>
<?php
    if ($configuration['cfgID'] == @$cfgInfo->id) {
?>
                    <td align="center" class="smallText">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); ?>&nbsp;</td>
<?php
    } else {
?>
                    <td align="center" class="smallText">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_CONFIGURATION, tep_get_all_get_params(array('info', 'action')) . 'info=' . $configuration['cfgID'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; ?>&nbsp;</td>
<?php
    }
?>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="3"><?php echo tep_black_line(); ?></td>
              </tr>
            </table></td>
            <td width="25%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
  $info_box_contents = array();
  if ($cfgInfo) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $cfgInfo->title . '</b>&nbsp;');
  if ((!$peInfo) && ($HTTP_GET_VARS['action'] == 'new')) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . TEXT_INFO_HEADING_NEW_PRODUCT . '</b>&nbsp;');
?>
              <tr class="boxHeading">
                <td>
                  <?php new infoBoxHeading($info_box_contents); ?>
                </td>
              </tr>
              <tr class="boxHeading">
                <td><?php echo tep_black_line(); ?></td>
              </tr>
<?php
  if ($HTTP_GET_VARS['action'] == 'edit') {
    $form = '<form name="configuration" action="' . tep_href_link(FILENAME_CONFIGURATION, tep_get_all_get_params(array('action')) . 'action=save', 'NONSSL') . '" method="post"><input type="hidden" name="configuration_id" value="' . $cfgInfo->id . '">' . "\n";

    if (tep_not_null($cfgInfo->set_function)) {
      $set_function = $cfgInfo->set_function;
      $value_field = $set_function($cfgInfo->value);
    } else {
      $value_field = '<input type="text" name="configuration_value" value="' . $cfgInfo->value . '">';
    }

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_EDIT_INTRO . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => '<b>' . $cfgInfo->title . '</b><br>' . $cfgInfo->description . '<br>' . $value_field . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_WS_IMAGES . 'button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_CONFIGURATION, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
  } else {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CONFIGURATION, tep_get_all_get_params(array('action')) . 'action=edit', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_edit.gif', IMAGE_EDIT) . '</a>');
    $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . TEXT_INFO_DESCRIPTION . '<br>' . $cfgInfo->description);
    $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . tep_date_short($cfgInfo->date_added) . '<br>' . TEXT_INFO_LAST_MODIFIED . ' ' . tep_date_short($cfgInfo->last_modified));
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