<?php
/*
  $Id: languages.php,v 1.23 2002/01/10 14:03:55 jan0815 Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'insert') {
      tep_db_query("insert into " . TABLE_LANGUAGES . " (name, code, image, directory, sort_order) values ('" . $HTTP_POST_VARS['name'] . "', '" . $HTTP_POST_VARS['code'] . "', '" . $HTTP_POST_VARS['image'] . "', '" . $HTTP_POST_VARS['directory'] . "', '" . $HTTP_POST_VARS['sort_order'] . "')");
      $insert_id = tep_db_insert_id();
      // Create additional categories_description records
      $categories = tep_db_query("select c.categories_id, cd.categories_name from " . TABLE_CATEGORIES . " c left join " . TABLE_CATEGORIES_DESCRIPTION . " cd on c.categories_id = cd.categories_id where cd.language_id = '" . $languages_id . "'");
      while ($categories_values = tep_db_fetch_array($categories)) {
        tep_db_query("insert into " . TABLE_CATEGORIES_DESCRIPTION . " (categories_id, language_id, categories_name) values ('" . $categories_values['categories_id'] . "', '" . $insert_id . "', '" . addslashes($categories_values['categories_name']) . "')");
      }
      // Create additional products_description records
      $products = tep_db_query("select p.products_id, pd.products_name, pd.products_description, pd.products_url from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id where pd.language_id = '" . $languages_id . "'");
      while ($products_values = tep_db_fetch_array($products)) {
        tep_db_query("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (products_id, language_id, products_name, products_description, products_url) values ('" . $products_values['products_id'] . "', '" . $insert_id . "', '" . addslashes($products_values['products_name']) . "', '" . addslashes($products_values['products_description']) . "', '" . addslashes($products_values['products_url']) . "')");
      }
      // Create additional products_options records
      $products_options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "'");
      while ($products_options_values = tep_db_fetch_array($products_options)) {
        tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS . " (products_options_id, language_id, products_options_name) values ('" . $products_options_values['products_options_id'] . "', '" . $insert_id . "', '" . addslashes($products_options_values['products_options_name']) . "')");
      }
      // Create additional products_options_values records
      $products_options_values = tep_db_query("select products_options_values_id, products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id = '" . $languages_id . "'");
      while ($products_options_values_values = tep_db_fetch_array($products_options_values)) {
        tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " (products_options_values_id, language_id, products_options_values_name) values ('" . $products_options_values_values['products_options_values_id'] . "', '" . $insert_id . "', '" . addslashes($products_options_values_values['products_options_values_name']) . "')");
      }
      // Create additional manufacturers_info records
      $manufacturers = tep_db_query("select m.manufacturers_id, mi.manufacturers_url from " . TABLE_MANUFACTURERS . " m left join " . TABLE_MANUFACTURERS_INFO . " mi on m.manufacturers_id = mi.manufacturers_id where mi.languages_id = '" . $languages_id . "'");
      while ($manufacturers_values = tep_db_fetch_array($manufacturers)) {
        tep_db_query("insert into " . TABLE_MANUFACTURERS_INFO . " (manufacturers_id, languages_id, manufacturers_url) values ('" . $manufacturers_values['manufacturers_id'] . "', '" . $insert_id . "', '" . addslashes($manufacturers_values['manufacturers_url']) . "')");
      }
      // Create additional orders_status records
      $orders_status = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . $languages_id . "'");
      while ($orders_status_values = tep_db_fetch_array($orders_status)) {
        tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . $orders_status_values['orders_status_id'] . "', '" . $insert_id . "', '" . addslashes($orders_status_values['orders_status_name']) . "')");
      }
      if ($HTTP_POST_VARS['default'] == 'on') {
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $HTTP_POST_VARS['code'] . "' where configuration_key = 'DEFAULT_LANGUAGE'");
      }
      header('Location: ' . tep_href_link(FILENAME_LANGUAGES, '', 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'save') {
      tep_db_query("update " . TABLE_LANGUAGES . " set name = '" . $HTTP_POST_VARS['name'] . "', code = '" . $HTTP_POST_VARS['code'] . "', image = '" . $HTTP_POST_VARS['image'] . "', directory = '" . $HTTP_POST_VARS['directory'] . "', sort_order = '" . $HTTP_POST_VARS['sort_order'] . "' where languages_id = '" . $HTTP_POST_VARS['languages_id'] . "'");
      if ($HTTP_POST_VARS['default'] == 'on') {
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $HTTP_POST_VARS['code'] . "' where configuration_key = 'DEFAULT_LANGUAGE'");
      }
      header('Location: ' . tep_href_link(FILENAME_LANGUAGES, tep_get_all_get_params(array('action')), 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'deleteconfirm') {
      tep_db_query("delete from " . TABLE_CATEGORIES_DESCRIPTION . " where language_id = '" . $HTTP_POST_VARS['languages_id'] . "'");
      tep_db_query("delete from " . TABLE_PRODUCTS_DESCRIPTION . " where language_id = '" . $HTTP_POST_VARS['languages_id'] . "'");
      tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $HTTP_POST_VARS['languages_id'] . "'");
      tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id = '" . $HTTP_POST_VARS['languages_id'] . "'");
      tep_db_query("delete from " . TABLE_MANUFACTURERS_INFO . " where languages_id = '" . $HTTP_POST_VARS['languages_id'] . "'");
      tep_db_query("delete from " . TABLE_ORDERS_STATUS . " where language_id = '" . $HTTP_POST_VARS['languages_id'] . "'");
      tep_db_query("delete from " . TABLE_LANGUAGES . " where languages_id = '" . $HTTP_POST_VARS['languages_id'] . "'");
      header('Location: ' . tep_href_link(FILENAME_LANGUAGES, tep_get_all_get_params(array('action', 'info')), 'NONSSL')); tep_exit();
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
                <td class="tableHeading">&nbsp;<?php echo TABLE_HEADING_LANGUAGE_NAME; ?>&nbsp;</td>
                <td class="tableHeading">&nbsp;<?php echo TABLE_HEADING_LANGUAGE_CODE; ?>&nbsp;</td>
                <td class="tableHeading" align="center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
  $languages_query_raw = "select languages_id, name, code, image, directory, sort_order from " . TABLE_LANGUAGES . " order by sort_order";
  $languages_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $languages_query_raw, $languages_query_numrows);
  $languages_query = tep_db_query($languages_query_raw);

  $rows = 0;
  while ($languages = tep_db_fetch_array($languages_query)) {
    $rows++;

    if (((!$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['info'] == $languages['languages_id'])) && (!$lInfo) && (substr($HTTP_GET_VARS['action'], 0, 3) != 'new')) {
      $lInfo = new languagesInfo($languages);
    }

    if ($languages['languages_id'] == @$lInfo->id) {
      echo '                  <tr class="selectedRow">' . "\n";
    } else {
      echo '                  <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_LANGUAGES, tep_get_all_get_params(array('info', 'action')) . 'info=' . $languages['languages_id'], 'NONSSL') . '\'">' . "\n";
    }

    if (DEFAULT_LANGUAGE == $languages['code']) {
      echo '                <td class="smallText">&nbsp;<b>' . $languages['name'] . ' (' . TEXT_DEFAULT . ')</b>&nbsp;</td>' . "\n";
    } else {
      echo '                <td class="smallText">&nbsp;' . $languages['name'] . '&nbsp;</td>' . "\n";
    }
?>
                <td class="smallText">&nbsp;<?php echo $languages['code']; ?>&nbsp;</td>
<?php
    if ($languages['languages_id'] == @$lInfo->id) {
?>
                    <td align="center" class="smallText">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); ?>&nbsp;</td>
<?php
    } else {
?>
                    <td align="center" class="smallText">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_LANGUAGES, tep_get_all_get_params(array('info', 'action')) . 'info=' . $languages['languages_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; ?>&nbsp;</td>
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
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td valign="top" class="smallText">&nbsp;<?php echo $languages_split->display_count($languages_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_LANGUAGES); ?>&nbsp;</td>
                    <td align="right" class="smallText">&nbsp;<?php echo TEXT_RESULT_PAGE; ?> <?php echo $languages_split->display_links($languages_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?>&nbsp;<?php if (!$HTTP_GET_VARS['action']) echo '<br><br>&nbsp;<a href="' . tep_href_link(FILENAME_LANGUAGES, tep_get_all_get_params(array('action', 'info')) . 'action=new', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_new_language.gif', IMAGE_NEW_LANGUAGE) . '</a>&nbsp;'; ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="25%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
  $info_box_contents = array();
  if ($lInfo) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $lInfo->name . '</b>&nbsp;');
  if ((!$lInfo) && ($HTTP_GET_VARS['action'] == 'new')) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . TEXT_INFO_HEADING_NEW_LANGUAGE . '</b>&nbsp;');

?>
              <tr class="boxHeading">
                <td><?php new infoBoxHeading($info_box_contents); ?></td>
              </tr>
              <tr class="boxHeading">
                <td><?php echo tep_black_line(); ?></td>
              </tr>
<?php
  $direction_options = array( array('id'   => '', 
                                    'text' => TEXT_INFO_LANGUAGE_DIRECTION_DEFAULT), 
                              array('id'   => 'ltr', 
                                    'text' => TEXT_INFO_LANGUAGE_DIRECTION_LEFT_TO_RIGHT),
                              array('id'   => 'rtl', 
                                    'text' => TEXT_INFO_LANGUAGE_DIRECTION_RIGHT_TO_LEFT)
                            );
  if ($HTTP_GET_VARS['action'] == 'new') {
    $form = '<form name="languages" action="' . tep_href_link(FILENAME_LANGUAGES, tep_get_all_get_params(array('action')) . 'action=insert', 'NONSSL') . '" method="post">'  ."\n";

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_INSERT_INTRO . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_LANGUAGE_NAME . '<br><input type="text" name="name"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_LANGUAGE_CODE . '<br><input type="text" name="code"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_LANGUAGE_IMAGE . '<br><input type="text" name="image" value="icon.gif"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_LANGUAGE_DIRECTORY . '<br><input type="text" name="directory"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_LANGUAGE_SORT_ORDER . '<br><input type="text" name="sort_order"><br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => '<input type="checkbox" name="default"> ' . TEXT_SET_DEFAULT . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_WS_IMAGES . 'button_insert.gif', IMAGE_INSERT) . '&nbsp;<a href="' . tep_href_link(FILENAME_LANGUAGES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
  } elseif ($HTTP_GET_VARS['action'] == 'edit') {
    $form = '<form name="languages" action="' . tep_href_link(FILENAME_LANGUAGES, tep_get_all_get_params(array('action')) . 'action=save', 'NONSSL') . '" method="post"><input type="hidden" name="languages_id" value="' . $lInfo->id . '">'  ."\n";

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_EDIT_INTRO . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_LANGUAGE_NAME . '<br><input type="text" name="name" value="' . $lInfo->name . '">');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_LANGUAGE_CODE . '<br><input type="text" name="code" value="' . $lInfo->code . '">');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_LANGUAGE_IMAGE . '<br><input type="text" name="image" value="' . $lInfo->image . '">');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_LANGUAGE_DIRECTORY . '<br><input type="text" name="directory" value="' . $lInfo->directory . '">');
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_LANGUAGE_SORT_ORDER . '<br><input type="text" name="sort_order" value="' . $lInfo->sort_order . '">');
    if (DEFAULT_LANGUAGE != $lInfo->code) $info_box_contents[] = array('align' => 'left', 'text' => '<input type="checkbox" name="default"> ' . TEXT_SET_DEFAULT);
    $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_LANGUAGES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
  } elseif ($HTTP_GET_VARS['action'] == 'delete') {
    $form = '<form name="languages" action="' . tep_href_link(FILENAME_LANGUAGES, tep_get_all_get_params(array('action')) . 'action=deleteconfirm', 'NONSSL') . '" method="post"><input type="hidden" name="languages_id" value="' . $lInfo->id . '">'  ."\n";

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_DELETE_INTRO . '<br>&nbsp;');
    $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $lInfo->name . '</b><br>&nbsp;');
    $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . '&nbsp;<a href="' . tep_href_link(FILENAME_LANGUAGES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
  } else {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_LANGUAGES, tep_get_all_get_params(array('action')) . 'action=edit', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_LANGUAGES, tep_get_all_get_params(array('action')) . 'action=delete', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . '</a>');
    $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_DEFINE_LANGUAGE, 'lngdir=' . $lInfo->directory, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_define.gif', IMAGE_DEFINE) . '</a>');
    $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_INFO_LANGUAGE_NAME . '&nbsp;' . $lInfo->name);
    $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_INFO_LANGUAGE_CODE . '&nbsp;' . $lInfo->code);
    $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $lInfo->directory . '/images/' . $lInfo->image, $lInfo->name) . '<br>' . DIR_WS_CATALOG_LANGUAGES . $lInfo->directory .'/images/' . '<b>' . $lInfo->image . '</b>');
    $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_INFO_LANGUAGE_DIRECTORY . '<br>&nbsp;' . DIR_WS_CATALOG_LANGUAGES . '<b>' . $lInfo->directory . '</b>');
    $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_INFO_LANGUAGE_SORT_ORDER . '&nbsp;' . $lInfo->sort_order);
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
