<?php
/*
  $Id: configuration.php,v 1.29 2002/01/09 10:38:46 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ($HTTP_GET_VARS['action']) {
    switch ($HTTP_GET_VARS['action']) {
      case 'save':
        $configuration_value = tep_db_prepare_input($HTTP_POST_VARS['configuration_value']);
        $cfgID = tep_db_prepare_input($HTTP_GET_VARS['cfgID']);

        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($configuration_value) . "', last_modified = now() where configuration_id = '" . tep_db_input($cfgID) . "'");
        tep_redirect(tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cfgID=' . $cfgID));
    }
  }

  $cfg_group_query = tep_db_query("select configuration_group_title from configuration_group where configuration_group_id = '" . $HTTP_GET_VARS['gID'] . "'");
  $cfg_group = tep_db_fetch_array($cfg_group_query);
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
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo $cfg_group['configuration_group_title']; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2"><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="tableHeading"><?php echo TABLE_HEADING_CONFIGURATION_TITLE; ?></td>
                <td class="tableHeading"><?php echo TABLE_HEADING_CONFIGURATION_VALUE; ?></td>
                <td class="tableHeading" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3"><?php echo tep_draw_separator(); ?></td>
              </tr>
<?php
  $configuration_query = tep_db_query("select configuration_id, configuration_title, configuration_value, use_function from " . TABLE_CONFIGURATION . " where configuration_group_id = '" . $HTTP_GET_VARS['gID'] . "' order by sort_order");
  while ($configuration = tep_db_fetch_array($configuration_query)) {
    if (tep_not_null($configuration['use_function'])) {
      $cfgValue = $configuration['use_function']($configuration['configuration_value']);
    } else {
      $cfgValue = $configuration['configuration_value'];
    }

    if (((!$HTTP_GET_VARS['cfgID']) || (@$HTTP_GET_VARS['cfgID'] == $configuration['configuration_id'])) && (!$cfgInfo) && (substr($HTTP_GET_VARS['action'], 0, 3) != 'new')) {
      $cfg_extra_query = tep_db_query("select configuration_key, configuration_description, date_added, last_modified, use_function, set_function from " . TABLE_CONFIGURATION . " where configuration_id = '" . $configuration['configuration_id'] . "'");
      $cfg_extra = tep_db_fetch_array($cfg_extra_query);

      $cfgInfo_array = tep_array_merge($configuration, $cfg_extra);
      $cfgInfo = new objectInfo($cfgInfo_array);
    }

    if ( (is_object($cfgInfo)) && ($configuration['configuration_id'] == $cfgInfo->configuration_id) ) {
      echo '                  <tr class="selectedRow">' . "\n";
    } else {
      echo '                  <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cfgID=' . $configuration['configuration_id']) . '\'">' . "\n";
    }
?>
                <td class="tableData"><?php echo $configuration['configuration_title']; ?></td>
                <td class="tableData"><?php echo htmlspecialchars($cfgValue); ?></td>
                <td align="right" class="tableData"><?php if ( (is_object($cfgInfo)) && ($configuration['configuration_id'] == $cfgInfo->configuration_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cfgID=' . $configuration['configuration_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="3"><?php echo tep_draw_separator(); ?></td>
              </tr>
            </table></td>
            <td width="25%" valign="top">
<?php
  $heading = array();
  $contents = array();
  switch ($HTTP_GET_VARS['action']) {
    case 'edit':
      $heading[] = array('text' => '<b>' . $cfgInfo->configuration_title . '</b>');

      if ($cfgInfo->set_function) {
        eval('$value_field = ' . $cfgInfo->set_function . "'" . $cfgInfo->configuration_value . "');");
      } else {
        $value_field = tep_draw_input_field('configuration_value', $cfgInfo->configuration_value);
      }

      $contents = array('form' => tep_draw_form('configuration', FILENAME_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cfgID=' . $cfgInfo->configuration_id . '&action=save'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $contents[] = array('text' => '<br><b>' . $cfgInfo->configuration_title . '</b><br>' . $cfgInfo->configuration_description . '<br>' . $value_field);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cfgID=' . $cfgInfo->configuration_id) . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($cfgInfo)) {
        $heading[] = array('text' => '<b>' . $cfgInfo->configuration_title . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cfgID=' . $cfgInfo->configuration_id . '&action=edit') . '">' . tep_image(DIR_WS_IMAGES . 'button_edit.gif', IMAGE_EDIT) . '</a>');
        $contents[] = array('text' => '<br>' . $cfgInfo->configuration_description);
        $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . tep_date_short($cfgInfo->date_added));
        if (tep_not_null($cfgInfo->last_modified)) $contents[] = array('text' => TEXT_INFO_LAST_MODIFIED . ' ' . tep_date_short($cfgInfo->last_modified));
      }
  }

  $box = new box;
  echo $box->infoBox($heading, $contents);
?>
            </td>
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