<?php
/*
  $Id: credit_cards.php,v 1.1 2004/05/12 19:31:25 mevans Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'setflag':
        if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) {
          if (isset($HTTP_GET_VARS['ccID'])) {
            tep_set_credit_card_status($HTTP_GET_VARS['ccID'], $HTTP_GET_VARS['flag']);
          }

        osC_Cache::clear('credit-cards');

        }

        tep_redirect(tep_href_link(FILENAME_CREDIT_CARDS, 'ccID=' . $HTTP_GET_VARS['ccID']));
        break;
      case 'insert':
      case 'save':
        if (isset($HTTP_GET_VARS['ccID'])) $credit_card_id = tep_db_prepare_input($HTTP_GET_VARS['ccID']);

        $credit_card_name = tep_db_prepare_input($HTTP_POST_VARS['credit_card_name']);
        $credit_card_code = tep_db_prepare_input($HTTP_POST_VARS['credit_card_code']);
        $sort_order = tep_db_prepare_input($HTTP_POST_VARS['sort_order']);

        $error = false;

        if (!tep_not_null($credit_card_name)) {
          $messageStack->add_session(ERROR_CREDIT_CARD_NAME, 'error');
          $error = true;
        }

        if (!tep_not_null($credit_card_code)) {
          $messageStack->add_session(ERROR_CREDIT_CARD_CODE, 'error');
          $error = true;
        }

        if ($error == false) {
          $sql_data_array = array('credit_card_name' => $credit_card_name,
                                  'credit_card_code' => $credit_card_code,
                                  'sort_order' => $sort_order);

          if ($action == 'insert') {
            tep_db_perform(TABLE_CREDIT_CARDS, $sql_data_array);
            $credit_card_id = tep_db_insert_id();
          } elseif ($action == 'save') {
            tep_db_perform(TABLE_CREDIT_CARDS, $sql_data_array, 'update', "credit_card_id = '" . (int)$credit_card_id . "'");
          }

          osC_Cache::clear('credit-cards');

          tep_redirect(tep_href_link(FILENAME_CREDIT_CARDS, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . 'ccID=' . $credit_card_id));
        } else {
          tep_redirect(tep_href_link(FILENAME_CREDIT_CARDS, 'page=' . $HTTP_GET_VARS['page']));
        }

        break;
      case 'deleteconfirm':
        $credit_card_id = tep_db_prepare_input($HTTP_GET_VARS['ccID']);

        tep_db_query("delete from " . TABLE_CREDIT_CARDS . " where credit_card_id = '" . (int)$credit_card_id . "'");

        osC_Cache::clear('credit-cards');

        tep_redirect(tep_href_link(FILENAME_CREDIT_CARDS, 'page=' . $HTTP_GET_VARS['page']));
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
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CREDIT_CARD_CODE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CREDIT_CARD_NAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_SORT_ORDER; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CREDIT_CARD_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $credit_card_query_raw = "select credit_card_id, credit_card_name, credit_card_code, credit_card_status, sort_order from " . TABLE_CREDIT_CARDS . " order by sort_order";
  $credit_card_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $credit_card_query_raw, $credit_card_query_numrows);
  $credit_card_query = tep_db_query($credit_card_query_raw);
  while ($credit_card = tep_db_fetch_array($credit_card_query)) {
    if ((!isset($HTTP_GET_VARS['ccID']) || (isset($HTTP_GET_VARS['ccID']) && ($HTTP_GET_VARS['ccID'] == $credit_card['credit_card_id']))) && !isset($ccInfo) && (substr($action, 0, 3) != 'new')) {

      $ccInfo = new objectInfo($credit_card);
    }

    if (isset($ccInfo) && is_object($ccInfo) && ($credit_card['credit_card_id'] == $ccInfo->credit_card_id)) {
      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CREDIT_CARDS, 'page=' . $HTTP_GET_VARS['page'] . '&ccID=' . $credit_card['credit_card_id'] . '&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CREDIT_CARDS, 'page=' . $HTTP_GET_VARS['page'] . '&ccID=' . $credit_card['credit_card_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $credit_card['credit_card_code']; ?></td>
                <td class="dataTableContent"><?php echo $credit_card['credit_card_name']; ?></td>
                <td class="dataTableContent"><?php echo $credit_card['sort_order']; ?></td>
                <td class="dataTableContent" align="center">
<?php
      if ($credit_card['credit_card_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CREDIT_CARDS, 'action=setflag&flag=0&ccID=' . $credit_card['credit_card_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_CREDIT_CARDS, 'action=setflag&flag=1&ccID=' . $credit_card['credit_card_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?></td>
                <td class="dataTableContent" align="right"><?php if (isset($ccInfo) && is_object($ccInfo) && ($credit_card['credit_card_id'] == $ccInfo->credit_card_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_CREDIT_CARDS, 'page=' . $HTTP_GET_VARS['page'] . '&ccID=' . $manufacturers['manufacturers_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $credit_card_split->display_count($credit_card_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_CREDIT_CARDS); ?></td>
                    <td class="smallText" align="right"><?php echo $credit_card_split->display_links($credit_card_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
<?php
  if (empty($action)) {
?>
              <tr>
                <td align="right" colspan="5" class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_CREDIT_CARDS, 'page=' . $HTTP_GET_VARS['page'] . '&ccID=' . $ccInfo->credit_card_id . '&action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
              </tr>
<?php
  }
?>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_NEW_CREDIT_CARD . '</b>');

      $contents = array('form' => tep_draw_form('credit_card', FILENAME_CREDIT_CARDS, 'page=' . $HTTP_GET_VARS['page'] . '&ccID=' . $ccInfo->credit_card_id . '&action=insert'));
      $contents[] = array('text' => TEXT_EDIT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_CREDIT_CARD_NAME . '<br>' . tep_draw_input_field('credit_card_name'));
      $contents[] = array('text' => '<br>' . TEXT_CREDIT_CARD_CODE . '<br>' . tep_draw_input_field('credit_card_code'));
      $contents[] = array('text' => '<br>' . TEXT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order'));

      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_CREDIT_CARDS, 'page=' . $HTTP_GET_VARS['page'] . '&ccID=' . $ccInfo->credit_card_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_EDIT_CREDIT_CARD . '</b>');

      $contents = array('form' => tep_draw_form('credit_card', FILENAME_CREDIT_CARDS, 'page=' . $HTTP_GET_VARS['page'] . '&ccID=' . $ccInfo->credit_card_id . '&action=save'));
      $contents[] = array('text' => TEXT_EDIT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_CREDIT_CARD_NAME . '<br>' . tep_draw_input_field('credit_card_name', $ccInfo->credit_card_name));
      $contents[] = array('text' => '<br>' . TEXT_CREDIT_CARD_CODE . '<br>' . tep_draw_input_field('credit_card_code', $ccInfo->credit_card_code));
      $contents[] = array('text' => '<br>' . TEXT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', $ccInfo->sort_order));

      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_CREDIT_CARDS, 'page=' . $HTTP_GET_VARS['page'] . '&ccID=' . $ccInfo->credit_card_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_DELETE_CREDIT_CARD . '</b>');

      $contents = array('form' => tep_draw_form('credit_card', FILENAME_CREDIT_CARDS, 'page=' . $HTTP_GET_VARS['page'] . '&ccID=' . $ccInfo->credit_card_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $ccInfo->credit_card_name . '</b>');

      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CREDIT_CARDS, 'page=' . $HTTP_GET_VARS['page'] . '&ccID=' . $ccInfo->credit_card_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($ccInfo) && is_object($ccInfo)) {
        $heading[] = array('text' => '<b>' . $ccInfo->credit_card_name . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CREDIT_CARDS, 'page=' . $HTTP_GET_VARS['page'] . '&ccID=' . $ccInfo->credit_card_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CREDIT_CARDS, 'page=' . $HTTP_GET_VARS['page'] . '&ccID=' . $ccInfo->credit_card_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_CREDIT_CARD_CODE . ' ' . $ccInfo->credit_card_code);
        $contents[] = array('text' => '<br>' . TEXT_SORT_ORDER . ' ' . $ccInfo->sort_order);
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
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
