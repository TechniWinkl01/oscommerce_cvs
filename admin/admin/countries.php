<?php
/*
  $Id: countries.php,v 1.17 2002/01/05 05:50:31 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ($HTTP_GET_VARS['action']) {
    switch ($HTTP_GET_VARS['action']) {
      case 'insert':
        $countries_name = tep_db_prepare_input($HTTP_POST_VARS['countries_name']);
        $countries_iso_code_2 = tep_db_prepare_input($HTTP_POST_VARS['countries_iso_code_2']);
        $countries_iso_code_3 = tep_db_prepare_input($HTTP_POST_VARS['countries_iso_code_3']);
        $address_format_id = tep_db_prepare_input($HTTP_POST_VARS['address_format_id']);

        tep_db_query("insert into " . TABLE_COUNTRIES . " (countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) values ('" . tep_db_input($countries_name) . "', '" . tep_db_input($countries_iso_code_2) . "', '" . tep_db_input($countries_iso_code_3) . "', '" . tep_db_input($address_format_id) . "')");
        tep_redirect(tep_href_link(FILENAME_COUNTRIES));
        break;
      case 'save':
        $countries_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
        $countries_name = tep_db_prepare_input($HTTP_POST_VARS['countries_name']);
        $countries_iso_code_2 = tep_db_prepare_input($HTTP_POST_VARS['countries_iso_code_2']);
        $countries_iso_code_3 = tep_db_prepare_input($HTTP_POST_VARS['countries_iso_code_3']);
        $address_format_id = tep_db_prepare_input($HTTP_POST_VARS['address_format_id']);

        tep_db_query("update " . TABLE_COUNTRIES . " set countries_name = '" . tep_db_input($countries_name) . "', countries_iso_code_2 = '" . tep_db_input($countries_iso_code_2) . "', countries_iso_code_3 = '" . tep_db_input($countries_iso_code_3) . "', address_format_id = '" . tep_db_input($address_format_id) . "' where countries_id = '" . tep_db_input($countries_id) . "'");
        tep_redirect(tep_href_link(FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $countries_id));
        break;
      case 'deleteconfirm':
        $countries_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);

        tep_db_query("delete from " . TABLE_COUNTRIES . " where countries_id = '" . tep_db_input($countries_id) . "'");
        tep_redirect(tep_href_link(FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page']));
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
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
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
                <td class="tableHeading"><?php echo TABLE_HEADING_COUNTRY_NAME; ?></td>
                <td class="tableHeading"><?php echo TABLE_HEADING_COUNTRY_CODES; ?></td>
                <td class="tableHeading" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3"><?php echo tep_draw_separator(); ?></td>
              </tr>
<?php
  $countries_query_raw = "select countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id from " . TABLE_COUNTRIES . " order by countries_name";
  $countries_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $countries_query_raw, $countries_query_numrows);
  $countries_query = tep_db_query($countries_query_raw);
  while ($countries = tep_db_fetch_array($countries_query)) {
    if (((!$HTTP_GET_VARS['cID']) || (@$HTTP_GET_VARS['cID'] == $countries['countries_id'])) && (!$cInfo) && (substr($HTTP_GET_VARS['action'], 0, 3) != 'new')) {
      $cInfo = new objectInfo($countries);
    }

    if ( (is_object($cInfo)) && ($countries['countries_id'] == $cInfo->countries_id) ) {
      echo '                  <tr class="selectedRow">' . "\n";
    } else {
      echo '                  <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $countries['countries_id']) . '\'">' . "\n";
    }
?>
                <td class="tableData"><?php echo $countries['countries_name']; ?></td>
                <td class="tableData"><?php echo $countries['countries_iso_code_2'] . ' / ' . $countries['countries_iso_code_3']; ?></td>
                <td class="tableData" align="right"><?php if ( (is_object($cInfo)) && ($countries['countries_id'] == $cInfo->countries_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $countries['countries_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="3"><?php echo tep_draw_separator(); ?></td>
              </tr>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td valign="top" class="smallText"><?php echo $countries_split->display_count($countries_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_COUNTRIES); ?></td>
                    <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' '; echo $countries_split->display_links($countries_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>
<?php
  if (!$HTTP_GET_VARS['action']) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page'] . '&action=new') . '">' . tep_image(DIR_WS_IMAGES . 'button_new_country.gif', IMAGE_NEW_COUNTRY) . '</a>'; ?></td>
                  </tr>
<?php
  }
?>
                </table></td>
              </tr>
            </table></td>
            <td width="25%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
  $info_box_contents = array();
  if (is_object($cInfo)) $info_box_contents[] = array('text' => '<b>' . $cInfo->countries_name . '</b>');
  if ((!$cInfo) && ($HTTP_GET_VARS['action'] == 'new')) $info_box_contents[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_COUNTRY . '</b>');
?>
              <tr class="boxHeading">
                <td><?php new infoBoxHeading($info_box_contents); ?></td>
              </tr>
              <tr class="boxHeading">
                <td><?php echo tep_draw_separator(); ?></td>
              </tr>
<?php
  switch ($HTTP_GET_VARS['action']) {
    case 'new':
      $info_box_contents = array('form' => tep_draw_form('countries', FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page'] . '&action=insert'));
      $info_box_contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
      $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_NAME . '<br>' . tep_draw_input_field('countries_name'));
      $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE_2 . '<br>' . tep_draw_input_field('countries_iso_code_2'));
      $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE_3 . '<br>' . tep_draw_input_field('countries_iso_code_3'));
      $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_ADDRESS_FORMAT . '<br>' . tep_draw_input_field('address_format_id'));
      $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_insert.gif', IMAGE_INSERT) . '&nbsp;<a href="' . tep_href_link(FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page']) . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
      $info_box_contents = array('form' => tep_draw_form('countries', FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->countries_id . '&action=save'));
      $info_box_contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_NAME . '<br>' . tep_draw_input_field('countries_name', $cInfo->countries_name));
      $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE_2 . '<br>' . tep_draw_input_field('countries_iso_code_2', $cInfo->countries_iso_code_2));
      $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE_3 . '<br>' . tep_draw_input_field('countries_iso_code_3', $cInfo->countries_iso_code_3));
      $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_ADDRESS_FORMAT . '<br>' . tep_draw_input_field('address_format_id', $cInfo->address_format_id));
      $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->countries_id) . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $info_box_contents = array('form' => tep_draw_form('countries', FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->countries_id . '&action=deleteconfirm'));
      $info_box_contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $info_box_contents[] = array('text' => '<br><b>' . $cInfo->countries_name . '</b>');
      $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->countries_id) . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      $info_box_contents = array();
      if (is_object($cInfo)) {
        $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->countries_id . '&action=edit') . '">' . tep_image(DIR_WS_IMAGES . 'button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->countries_id . '&action=delete') . '">' . tep_image(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . '</a>');
        $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_NAME . '<br>' . $cInfo->countries_name);
        $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE_2 . ' ' . $cInfo->countries_iso_code_2);
        $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE_3 . ' ' . $cInfo->countries_iso_code_3);
        $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_ADDRESS_FORMAT . ' ' . $cInfo->address_format_id);
      }
  }
?>
              <tr>
                <td class="box"><?php new infoBox($info_box_contents); ?></td>
              </tr>
              <tr>
                <td class="box"><?php echo tep_draw_separator(); ?></td>
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