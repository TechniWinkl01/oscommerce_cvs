<?php
/*
  $Id: currencies.php,v 1.30 2002/01/11 04:54:04 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ($HTTP_GET_VARS['action']) {
    switch ($HTTP_GET_VARS['action']) {
      case 'insert':
      case 'save':
        $currency_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
        $title = tep_db_prepare_input($HTTP_POST_VARS['title']);
        $code = tep_db_prepare_input($HTTP_POST_VARS['code']);
        $symbol_left = tep_db_prepare_input($HTTP_POST_VARS['symbol_left']);
        $symbol_right = tep_db_prepare_input($HTTP_POST_VARS['symbol_right']);
        $decimal_point = tep_db_prepare_input($HTTP_POST_VARS['decimal_point']);
        $thousands_point = tep_db_prepare_input($HTTP_POST_VARS['thousands_point']);
        $decimal_places = tep_db_prepare_input($HTTP_POST_VARS['decimal_places']);
        $value = tep_db_prepare_input($HTTP_POST_VARS['value']);

        $sql_data_array = array('title' => $title,
                                'code' => $code,
                                'symbol_left' => $symbol_left,
                                'symbol_right' => $symbol_right,
                                'decimal_point' => $decimal_point,
                                'thousands_point' => $thousands_point,
                                'decimal_places' => $decimal_places,
                                'value' => $value);

        if ($HTTP_GET_VARS['action'] == 'insert') {
          tep_db_perform(TABLE_CURRENCIES, $sql_data_array);
          $currency_id = tep_db_insert_id();
        } elseif ($HTTP_GET_VARS['action'] == 'save') {
          tep_db_perform(TABLE_CURRENCIES, $sql_data_array, 'update', "currencies_id = '" . tep_db_input($currency_id) . "'");
        }

        if ($HTTP_POST_VARS['default'] == 'on') {
          tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($code) . "' where configuration_key = 'DEFAULT_CURRENCY'");
        }
        tep_redirect(tep_href_link(FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $currency_id));
        break;
      case 'deleteconfirm':
        $currencies_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);

        $currency_query = tep_db_query("select c.currencies_id from currencies c, configuration cfg where cfg.configuration_key = 'DEFAULT_CURRENCY' and cfg.configuration_value = c.code");
        $currency = tep_db_fetch_array($currency_query);
        if ($currency['currencies_id'] == $currencies_id) {
          tep_db_query("update configuration set configuration_value = '' where configuration_key = 'DEFAULT_CURRENCY'");
        }

        tep_db_query("delete from " . TABLE_CURRENCIES . " where currencies_id = '" . tep_db_input($currencies_id) . "'");

        tep_redirect(tep_href_link(FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page']));
        break;
      case 'update':
        $currencies_query = tep_db_query("select currencies_id, code from " . TABLE_CURRENCIES);
        while ($currencies = tep_db_fetch_array($currencies_query)) {
          $quote_function = 'quote_' . CURRENCY_SERVER_PRIMARY . '_currency';
          $rate = $quote_function($currencies['code']);
          if ( (!$rate) && (CURRENCY_SERVER_BACKUP != '') ) {
            $quote_function = 'quote_' . CURRENCY_SERVER_BACKUP . '_currency';
            $rate = $quote_function($currencies['code']);
          }
          if ($rate) {
            tep_db_query("update " . TABLE_CURRENCIES . " set value = '" . $rate . "', last_updated = now() where currencies_id = '" . $currencies['currencies_id'] . "'");
          }
        }
        tep_redirect(tep_href_link(FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $HTTP_GET_VARS['cID']));
        break;
      case 'delete':
        $currencies_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);

        $currency_query = tep_db_query("select code from currencies where currencies_id = '" . $currencies_id . "'");
        $currency = tep_db_fetch_array($currency_query);

        $remove_currency = true;
        if ($currency['code'] == DEFAULT_CURRENCY) {
          $remove_currency = false;
          $errorStack->add(ERROR_REMOVE_DEFAULT_CURRENCY, 'error');
        }
        break;
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
                <td class="tableHeading"><?php echo TABLE_HEADING_CURRENCY_NAME; ?></td>
                <td class="tableHeading"><?php echo TABLE_HEADING_CURRENCY_CODES; ?></td>
                <td class="tableHeading" align="right"><?php echo TABLE_HEADING_CURRENCY_VALUE; ?></td>
                <td class="tableHeading" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="4"><?php echo tep_draw_separator(); ?></td>
              </tr>
<?php
  $currencies_query_raw = "select currencies_id, title, code, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, last_updated, value from " . TABLE_CURRENCIES . " order by title";
  $currencies_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $currencies_query_raw, $currencies_query_numrows);
  $currencies_query = tep_db_query($currencies_query_raw);

  while ($currencies = tep_db_fetch_array($currencies_query)) {
    if (((!$HTTP_GET_VARS['cID']) || (@$HTTP_GET_VARS['cID'] == $currencies['currencies_id'])) && (!$cInfo) && (substr($HTTP_GET_VARS['action'], 0, 3) != 'new')) {
      $cInfo = new objectInfo($currencies);
    }

    if ( (is_object($cInfo)) && ($currencies['currencies_id'] == $cInfo->currencies_id) ) {
      echo '                  <tr class="selectedRow">' . "\n";
    } else {
      echo '                  <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $currencies['currencies_id']) . '\'">' . "\n";
    }

    if (DEFAULT_CURRENCY == $currencies['code']) {
      echo '                <td class="tableData"><b>' . $currencies['title'] . ' (' . TEXT_DEFAULT . ')</b></td>' . "\n";
    } else {
      echo '                <td class="tableData">' . $currencies['title'] . '</td>' . "\n";
    }
?>
                <td class="tableData"><?php echo $currencies['code']; ?></td>
                <td class="tableData" align="right"><?php echo number_format($currencies['value'], 8); ?></td>
                <td class="tableData" align="right"><?php if ( (is_object($cInfo)) && ($currencies['currencies_id'] == $cInfo->currencies_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $currencies['currencies_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="4"><?php echo tep_draw_separator(); ?></td>
              </tr>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td valign="top" class="smallText"><?php echo $currencies_split->display_count($currencies_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_CURRENCIES); ?></td>
                    <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' '; echo $currencies_split->display_links($currencies_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>
<?php
  if (!$HTTP_GET_VARS['action']) {
?>
                  <tr>
                    <td><?php if (CURRENCY_SERVER_PRIMARY) { echo '<a href="' . tep_href_link(FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->currencies_id . '&action=update') . '">' . tep_image(DIR_WS_IMAGES . 'button_update_currencies.gif', IMAGE_UPDATE_CURRENCIES) . '</a>'; } ?></td>
                    <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->currencies_id . '&action=new') . '">' . tep_image(DIR_WS_IMAGES . 'button_new_currency.gif', IMAGE_NEW_CURRENCY) . '</a>'; ?></td>
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
  if (is_object($cInfo)) $info_box_contents[] = array('text' => '<b>' . $cInfo->title . '</b>');
  if ((!$cInfo) && ($HTTP_GET_VARS['action'] == 'new')) $info_box_contents[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_CURRENCY . '</b>');
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
      $info_box_contents = array('form' => tep_draw_form('currencies', FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->currencies_id . '&action=insert'));
      $info_box_contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
      $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_TITLE . '<br>' . tep_draw_input_field('title'));
      $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_CODE . '<br>' . tep_draw_input_field('code'));
      $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_SYMBOL_LEFT . '<br>' . tep_draw_input_field('symbol_left'));
      $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_SYMBOL_RIGHT . '<br>' . tep_draw_input_field('symbol_right'));
      $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_DECIMAL_POINT . '<br>' . tep_draw_input_field('decimal_point'));
      $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_THOUSANDS_POINT . '<br>' . tep_draw_input_field('thousands_point'));
      $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_DECIMAL_PLACES . '<br>' . tep_draw_input_field('decimal_places'));
      $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_VALUE . '<br>' . tep_draw_input_field('value'));
      $info_box_contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default') . ' ' . TEXT_INFO_SET_AS_DEFAULT);
      $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_insert.gif', IMAGE_INSERT) . ' <a href="' . tep_href_link(FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $HTTP_GET_VARS['cID']) . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
      $info_box_contents = array('form' => tep_draw_form('currencies', FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->currencies_id . '&action=save'));
      $info_box_contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_TITLE . '<br>' . tep_draw_input_field('title', $cInfo->title));
      $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_CODE . '<br>' . tep_draw_input_field('code', $cInfo->code));
      $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_SYMBOL_LEFT . '<br>' . tep_draw_input_field('symbol_left', $cInfo->symbol_left));
      $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_SYMBOL_RIGHT . '<br>' . tep_draw_input_field('symbol_right', $cInfo->symbol_right));
      $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_DECIMAL_POINT . '<br>' . tep_draw_input_field('decimal_point', $cInfo->decimal_point));
      $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_THOUSANDS_POINT . '<br>' . tep_draw_input_field('thousands_point', $cInfo->thousands_point));
      $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_DECIMAL_PLACES . '<br>' . tep_draw_input_field('decimal_places', $cInfo->decimal_places));
      $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_VALUE . '<br>' . tep_draw_input_field('value', $cInfo->value));
      if (DEFAULT_CURRENCY != $cInfo->code) $info_box_contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default') . ' ' . TEXT_INFO_SET_AS_DEFAULT);
      $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->currencies_id) . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $info_box_contents = array();
      $info_box_contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $info_box_contents[] = array('text' => '<br><b>' . $cInfo->title . '</b>');
      $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . (($remove_currency) ? '<a href="' . tep_href_link(FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->currencies_id . '&action=deleteconfirm') . '">' . tep_image(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . '</a>' : '') . ' <a href="' . tep_href_link(FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->currencies_id) . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      $info_box_contents = array();
      if (is_object($cInfo)) {
        $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->currencies_id . '&action=edit') . '">' . tep_image(DIR_WS_IMAGES . 'button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->currencies_id . '&action=delete') . '">' . tep_image(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . '</a>');
        $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_TITLE . ' ' . $cInfo->title);
        $info_box_contents[] = array('text' => TEXT_INFO_CURRENCY_CODE . ' ' . $cInfo->code);
        $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_SYMBOL_LEFT . ' ' . $cInfo->symbol_left);
        $info_box_contents[] = array('text' => TEXT_INFO_CURRENCY_SYMBOL_RIGHT . ' ' . $cInfo->symbol_right);
        $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_DECIMAL_POINT . ' ' . $cInfo->decimal_point);
        $info_box_contents[] = array('text' => TEXT_INFO_CURRENCY_THOUSANDS_POINT . ' ' . $cInfo->thousands_point);
        $info_box_contents[] = array('text' => TEXT_INFO_CURRENCY_DECIMAL_PLACES . ' ' . $cInfo->decimal_places);
        $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_LAST_UPDATED . ' ' . tep_date_short($cInfo->last_updated));
        $info_box_contents[] = array('text' => TEXT_INFO_CURRENCY_VALUE . ' ' . number_format($cInfo->value, 8));
        $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_CURRENCY_EXAMPLE . '<br>' . tep_currency_format(30) . ' = ' . tep_currency_format('30', true, $cInfo->code));
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
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>