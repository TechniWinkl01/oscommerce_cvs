<?php
/*
  $Id: weight_classes.php,v 1.1 2004/04/15 16:06:39 mevans Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
      case 'save':
        if (isset($HTTP_GET_VARS['wcID'])) $weight_class_id = tep_db_prepare_input($HTTP_GET_VARS['wcID']);

        $classes_title_array = $HTTP_POST_VARS['weight_class_title'];
        $classes_key_array = $HTTP_POST_VARS['weight_class_key'];
        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $language_id = $languages[$i]['id'];

          $sql_data_array = array('weight_class_title' => tep_db_prepare_input($classes_title_array[$language_id]),
                                  'weight_class_key' => tep_db_prepare_input($classes_key_array[$language_id]));

          if ($action == 'insert') {
            if (empty($weight_class_id)) {
              $next_id_query = tep_db_query("select max(weight_class_id) as weight_class_id from " . TABLE_WEIGHT_CLASS);
              $next_id = tep_db_fetch_array($next_id_query);
              $weight_class_id = $next_id['weight_class_id'] + 1;
            }

            $insert_sql_data = array('weight_class_id' => $weight_class_id,
                                     'language_id' => $language_id);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            tep_db_perform(TABLE_WEIGHT_CLASS, $sql_data_array);
          } elseif ($action == 'save') {
            tep_db_perform(TABLE_WEIGHT_CLASS, $sql_data_array, 'update', "weight_class_id = '" . (int)$weight_class_id . "' and language_id = '" . (int)$language_id . "'");

            $weight_class_rules_array = $HTTP_POST_VARS['weight_class_rules'];

            foreach ($weight_class_rules_array as $weight_class_to_id => $weight_class_rule) {
              $sql_data_array = array('weight_class_rule' => tep_db_prepare_input($weight_class_rule));
              tep_db_perform(TABLE_WEIGHT_CLASS_RULES, $sql_data_array, 'update', "weight_class_from_id = '" . (int)$weight_class_id . "' and weight_class_to_id = '" . $weight_class_to_id . "'");
            }

          }
        }

        if ($action == 'insert') {
          $classes_query = tep_db_query("select weight_class_id, language_id from " . TABLE_WEIGHT_CLASS . " where weight_class_id != '" . (int)$weight_class_id . "' and language_id = '" . (int)$languages_id . "'");
          while ($classes = tep_db_fetch_array($classes_query)) {
            $sql_data_array = array('weight_class_from_id' => tep_db_prepare_input($weight_class_id),
                                    'weight_class_to_id' => tep_db_prepare_input($classes['weight_class_id']),
                                    'weight_class_rule' => 1);
            tep_db_perform(TABLE_WEIGHT_CLASS_RULES, $sql_data_array);

            $sql_data_array = array('weight_class_from_id' => tep_db_prepare_input($classes['weight_class_id']),
                                    'weight_class_to_id' => tep_db_prepare_input($weight_class_id),
                                    'weight_class_rule' => 1);
            tep_db_perform(TABLE_WEIGHT_CLASS_RULES, $sql_data_array);
           }
        }

        if (isset($HTTP_POST_VARS['default']) && ($HTTP_POST_VARS['default'] == 'on')) {
          tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($weight_class_id) . "' where configuration_key = 'SHIPPING_WEIGHT_UNIT'");
        }

        osC_Cache::clear('weight-classes');
        osC_Cache::clear('weight-rules');

        tep_redirect(tep_href_link(FILENAME_WEIGHT_CLASSES, 'page=' . $HTTP_GET_VARS['page'] . '&wcID=' . $weight_class_id));
        break;
      case 'deleteconfirm':
        $wcID = tep_db_prepare_input($HTTP_GET_VARS['wcID']);

        tep_db_query("delete from " . TABLE_WEIGHT_CLASS . " where weight_class_id = '" . tep_db_input($wcID) . "'");
        tep_db_query("delete from " . TABLE_WEIGHT_CLASS_RULES . " where weight_class_from_id = '" . tep_db_input($wcID) . "' or weight_class_to_id = '" . tep_db_input($wcID) . "'");

        osC_Cache::clear('weight-classes');
        osC_Cache::clear('weight-rules');

        tep_redirect(tep_href_link(FILENAME_WEIGHT_CLASSES, 'page=' . $HTTP_GET_VARS['page']));
        break;
      case 'delete':
        $wcID = tep_db_prepare_input($HTTP_GET_VARS['wcID']);

        $remove_status = true;
        if ($wcID == SHIPPING_WEIGHT_UNIT) {
          $remove_status = false;
          $messageStack->add(ERROR_REMOVE_DEFAULT_WEIGHT_CLASS, 'error');
        }
        break;
      case 'update_rules':
        $wcID = tep_db_prepare_input($HTTP_GET_VARS['wcID']);


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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_WEIGHT_CLASSES_TITLE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_WEIGHT_CLASSES_UNIT; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $classes_query_raw = "select weight_class_id, weight_class_key, weight_class_title, language_id from " . TABLE_WEIGHT_CLASS . " where language_id = '" . (int)$languages_id . "' order by weight_class_title";
  $classes_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $classes_query_raw, $classes_query_numrows);
  $classes_query = tep_db_query($classes_query_raw);
  while ($classes = tep_db_fetch_array($classes_query)) {
    if ((!isset($HTTP_GET_VARS['wcID']) || (isset($HTTP_GET_VARS['wcID']) && ($HTTP_GET_VARS['wcID'] == $classes['weight_class_id']))) && !isset($wcInfo) && (substr($action, 0, 3) != 'new')) {
      $wcInfo = new objectInfo($classes);
    }

    if (isset($wcInfo) && is_object($wcInfo) && ($classes['weight_class_id'] == $wcInfo->weight_class_id)) {
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_WEIGHT_CLASSES, 'page=' . $HTTP_GET_VARS['page'] . '&wcID=' . $wcInfo->weight_class_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_WEIGHT_CLASSES, 'page=' . $HTTP_GET_VARS['page'] . '&wcID=' . $classes['weight_class_id']) . '\'">' . "\n";
    }

    if (SHIPPING_WEIGHT_UNIT == $classes['weight_class_id']) {
      echo '                <td class="dataTableContent"><b>' . $classes['weight_class_title'] . ' (' . TEXT_DEFAULT . ')</b></td>' . "\n";
    } else {
      echo '                <td class="dataTableContent">' . $classes['weight_class_title'] . '</td>' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $classes['weight_class_key']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($wcInfo) && is_object($wcInfo) && ($classes['weight_class_id'] == $wcInfo->weight_class_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_WEIGHT_CLASSES, 'page=' . $HTTP_GET_VARS['page'] . '&oID=' . $classes['weight_class_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $classes_split->display_count($classes_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_WEIGHT_CLASSES); ?></td>
                    <td class="smallText" align="right"><?php echo $classes_split->display_links($classes_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr>
                    <td colspan="3" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_WEIGHT_CLASSES, 'page=' . $HTTP_GET_VARS['page'] . '&action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
                  </tr>
<?php
  }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_WEIGHT_CLASS . '</b>');

      $contents = array('form' => tep_draw_form('status', FILENAME_WEIGHT_CLASSES, 'page=' . $HTTP_GET_VARS['page'] . '&action=insert'));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);

      $classes_inputs_string = '';
      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $classes_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('weight_class_title[' . $languages[$i]['id'] . ']') . tep_draw_input_field('weight_class_key[' . $languages[$i]['id'] . ']', $wcInfo->weight_class_key, 'size="4"');
      }

      $contents[] = array('text' => '<br>' . TEXT_INFO_WEIGHT_CLASS_TITLE . $classes_inputs_string);
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_insert.gif', IMAGE_INSERT) . ' <a href="' . tep_href_link(FILENAME_WEIGHT_CLASSES, 'page=' . $HTTP_GET_VARS['page']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_WEIGHT_CLASS . '</b>');

      $contents = array('form' => tep_draw_form('edit', FILENAME_WEIGHT_CLASSES, 'page=' . $HTTP_GET_VARS['page'] . '&wcID=' . $wcInfo->weight_class_id  . '&action=save'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO . '</b>');

      $classes_inputs_string = '';
      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $classes_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('weight_class_title[' . $languages[$i]['id'] . ']', tep_get_weight_class_title($wcInfo->weight_class_id, $languages[$i]['id'])) . tep_draw_input_field('weight_class_key[' . $languages[$i]['id'] . ']', tep_get_weight_class_key($wcInfo->weight_class_id, $languages[$i]['id']), 'size="4"');
      }

      $contents[] = array('text' => '<br><b>' . TEXT_INFO_WEIGHT_CLASS_TITLE . '</b>' . $classes_inputs_string);

      $contents[] = array('text' => '<br><b>' . TEXT_INFO_HEADING_EDIT_WEIGHT_RULES . '</b>');
      $contents[] = array('form' => tep_draw_form('edit_rules', FILENAME_WEIGHT_CLASSES, 'page=' . $HTTP_GET_VARS['page'] . '&wcID=' . $wcInfo->weight_class_id  . '&action=update_rules'));

      $weight_rules_string = '<table border="0" cellspacing="0" cellpadding="2">';
      $rules_query = tep_db_query("select r.weight_class_to_id, r.weight_class_rule, c.weight_class_title from " . TABLE_WEIGHT_CLASS_RULES . " r, " . TABLE_WEIGHT_CLASS . " c where r.weight_class_from_id = '" . $wcInfo->weight_class_id . "' and r.weight_class_to_id != '" . $wcInfo->weight_class_id . "' and r.weight_class_to_id = c.weight_class_id and c.language_id = '" . (int)$languages_id . "' order by c.weight_class_title");
      while ($rules = tep_db_fetch_array($rules_query)) {
        $weight_rules_string .= '<tr><td class="dataTableContent">' . $rules['weight_class_title'] . ':</td><td class="dataTableContent">' . tep_draw_input_field('weight_class_rules[' . $rules['weight_class_to_id'] . ']', $rules['weight_class_rule'], 'size="10"') . '</td></tr>';
      }

      $weight_rules_string .= '</table>';

      $contents[] = array('text' => $weight_rules_string);

      if (SHIPPING_WEIGHT_UNIT != $wcInfo->weight_class_id) $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default') . ' <b>' . TEXT_SET_DEFAULT . '</b>');

      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_WEIGHT_CLASSES, 'page=' . $HTTP_GET_VARS['page'] . '&wcID=' . $wcInfo->weight_class_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_WEIGHT_CLASS . '</b>');

      $contents = array('form' => tep_draw_form('status', FILENAME_WEIGHT_CLASSES, 'page=' . $HTTP_GET_VARS['page'] . '&wcID=' . $wcInfo->weight_class_id  . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $wcInfo->weight_class_title . '</b>');
      if ($remove_status) $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_WEIGHT_CLASSES, 'page=' . $HTTP_GET_VARS['page'] . '&wcID=' . $wcInfo->weight_class_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($wcInfo) && is_object($wcInfo)) {
        $heading[] = array('text' => '<b>' . $wcInfo->weight_class_title . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_WEIGHT_CLASSES, 'page=' . $HTTP_GET_VARS['page'] . '&wcID=' . $wcInfo->weight_class_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_WEIGHT_CLASSES, 'page=' . $HTTP_GET_VARS['page'] . '&wcID=' . $wcInfo->weight_class_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');

        $classes_inputs_string = '';
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $classes_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_get_weight_class_title($wcInfo->weight_class_id, $languages[$i]['id']);
        }

        $contents[] = array('text' => $classes_inputs_string);
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
