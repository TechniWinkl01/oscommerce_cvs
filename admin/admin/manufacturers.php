<?php
/*
  $Id: manufacturers.php,v 1.38 2001/12/14 13:19:17 jan0815 Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'save') {
      tep_db_query("update " . TABLE_MANUFACTURERS . " set manufacturers_name = '" . $HTTP_POST_VARS['manufacturers_name'] . "', last_modified = now() where manufacturers_id = '" . $HTTP_POST_VARS['manufacturers_id'] . "'");
      if ( ($manufacturers_image != 'none') && ($manufacturers_image != '') ) {
        tep_db_query("update " . TABLE_MANUFACTURERS . " set manufacturers_image = '" . $manufacturers_image_name . "' where manufacturers_id = '" . $HTTP_POST_VARS['manufacturers_id'] . "'");
        $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $manufacturers_image_name;
        if (file_exists($image_location)) @unlink($image_location);
        copy($manufacturers_image, $image_location);
      }
      $languages = tep_get_languages();
      for ($i=0; $i<sizeof($languages); $i++) {
        $manufacturers_url_array = $HTTP_POST_VARS['manufacturers_url'];
        $language_id = $languages[$i]['id'];
        $manufacturers_url = $manufacturers_url_array[$language_id];
        tep_db_query("update " . TABLE_MANUFACTURERS_INFO . " set manufacturers_url = '" . $manufacturers_url . "' where manufacturers_id = '" . $HTTP_POST_VARS['manufacturers_id'] . "' and languages_id = '" . $languages[$i]['id'] . "'");
      }
      header('Location: ' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('action', 'info')) . 'info=' . $HTTP_POST_VARS['manufacturers_id'], 'NONSSL'));
      tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'deleteconfirm') {
      tep_db_query("delete from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . $HTTP_POST_VARS['manufacturers_id'] . "'");
      tep_db_query("delete from " . TABLE_MANUFACTURERS_INFO . " where manufacturers_id = '" . $HTTP_POST_VARS['manufacturers_id'] . "'");

      if ($HTTP_POST_VARS['delete_products'] == 'on') {
// retreive all products from the manufacturer
        $products_query = tep_db_query("select products_id, products_image from " . TABLE_PRODUCTS . " where manufacturers_id = '" . $HTTP_POST_VARS['manufacturers_id'] . "'");
        while ($products = tep_db_fetch_array($products_query)) {
          tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $products['products_id'] . "'");
          tep_db_query("delete from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $products['products_id'] . "'");
          tep_db_query("delete from " . TABLE_SPECIALS . " where products_id = '" . $products['products_id'] . "'");

// delete product reviews
          $reviews_query = tep_db_query("select reviews_id from " . TABLE_REVIEWS . " where products_id = '" . $products['products_id'] . "'");
          while ($reviews = tep_db_fetch_array($reviews_query)) {
            tep_db_query("delete from " . TABLE_REVIEWS_DESCRIPTION . " where reviews_id = '" . $reviews['reviews_id'] . "'");
          }
          tep_db_query("delete from " . TABLE_REVIEWS . " where products_id = '" . $products['products_id'] . "'");

// delete product image
          if (file_exists(DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $products['products_image'])) {
            @unlink(DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $products['products_image']);
          }
        }
        tep_db_query("delete from " . TABLE_PRODUCTS . " where manufacturers_id = '" . $HTTP_POST_VARS['manufacturers_id'] . "'");
      } else {
        tep_db_query("update " . TABLE_PRODUCTS . " set manufacturers_id = '' where manufacturers_id = '" . $HTTP_POST_VARS['manufacturers_id'] . "'");
      }

      header('Location: ' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('action', 'info')), 'NONSSL'));
      tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'insert') {
      $error = 0;
      if (tep_db_query("insert into " . TABLE_MANUFACTURERS . " (manufacturers_name, date_added) values ('" . $HTTP_POST_VARS['manufacturers_name'] . "', now())")) {
        $manufacturers_id = tep_db_insert_id();
        if ( ($manufacturers_image != 'none') && ($manufacturers_image != '') ) {
          if (tep_db_query("update " . TABLE_MANUFACTURERS . " set manufacturers_image = '" . $manufacturers_image_name . "' where manufacturers_id = '" . $manufacturers_id . "'")) {
            $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $manufacturers_image_name;
            if (file_exists($image_location)) @unlink($image_location);
            copy($manufacturers_image, $image_location);
          } else {
            $error = 1;
          }
        }
   
        $languages = tep_get_languages();
        for ($i=0; $i<sizeof($languages); $i++) {
          $manufacturers_url_array = $HTTP_POST_VARS['manufacturers_url'];
          $language_id = $languages[$i]['id'];
          $manufacturers_url = $manufacturers_url_array[$language_id];
          tep_db_query("insert into " . TABLE_MANUFACTURERS_INFO . " (manufacturers_id, languages_id, manufacturers_url, url_clicked, date_last_click) values ('" . $manufacturers_id . "', '" . $languages[$i]['id'] . "', '" . $manufacturers_url . "', '0', '')");
        }
      } else {
        $error = 1;
      }
      if ($error == 0) {
        header('Location: ' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('action', 'info')) . 'info=' . $manufacturers_id, 'NONSSL'));
        tep_exit();
      } else {
        header('Location: ' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('action', 'info')) . 'error=INSERT', 'NONSSL'));
        tep_exit();
      }
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
                <td class="tableHeading" align="center">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
                <td class="tableHeading">&nbsp;<?php echo TABLE_HEADING_MANUFACTURERS; ?>&nbsp;</td>
                <td class="tableHeading" align="center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
  $rows = 0;
  $manufacturers_query_raw = "select manufacturers_id, manufacturers_name, manufacturers_image, date_added, last_modified from " . TABLE_MANUFACTURERS . " order by manufacturers_name";
  $manufacturers_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $manufacturers_query_raw, $manufacturers_query_numrows);
  $manufacturers_query = tep_db_query($manufacturers_query_raw);
  while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
    $rows++;

    if (((!$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['info'] == $manufacturers['manufacturers_id'])) && (!$mInfo) && ($HTTP_GET_VARS['action'] != 'new')) {
      $manufacturer_products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where manufacturers_id = '" . $manufacturers['manufacturers_id'] . "'");
      $manufacturer_products = tep_db_fetch_array($manufacturer_products_query);

      $mInfo_array = tep_array_merge($manufacturers, $manufacturer_products);
      $mInfo = new manufacturerInfo($mInfo_array);
    }

    if ($manufacturers['manufacturers_id'] == @$mInfo->id) {
      echo '              <tr class="selectedRow">' . "\n";
    } else {
      echo '              <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('info', 'action')) . 'info=' . $manufacturers['manufacturers_id'], 'NONSSL') . '\'">' . "\n";
    }
?>
                <td align="center" class="smallText">&nbsp;<?php echo $manufacturers['manufacturers_id']; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<?php echo $manufacturers['manufacturers_name']; ?>&nbsp;</td>
<?php
    if ($manufacturers['manufacturers_id'] == @$mInfo->id) {
?>
                <td align="center" class="smallText">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); ?>&nbsp;</td>
<?php
    } else {
?>
                <td align="center" class="smallText">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('info', 'action')) . 'info=' . $manufacturers['manufacturers_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; ?>&nbsp;</td>
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
                    <td class="smallText">&nbsp;<?php echo $manufacturers_split->display_count($manufacturers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS); ?>&nbsp;</td>
                    <td align="right" class="smallText">&nbsp;<?php echo TEXT_RESULT_PAGE; ?> <?php echo $manufacturers_split->display_links($manufacturers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="right" colspan="3" class="smallText"><a href="<?php echo tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('action')) . 'action=new', 'NONSSL'); ?>"><?php echo tep_image(DIR_WS_IMAGES . 'button_insert.gif', IMAGE_INSERT); ?></a>&nbsp;</td>
              </tr>
<?php
  if ($HTTP_GET_VARS['error']) {
?>
              <tr>
                <td colspan="3" class="smallText">&nbsp;<?php echo ERROR_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  }
?>
            </table></td>
            <td width="25%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . @$mInfo->name . '</b>&nbsp;');
?>
              <tr class="boxHeading">
                <td><?php new infoBoxHeading($info_box_contents); ?></td>
              </tr>
              <tr class="boxHeading">
                <td><?php echo tep_black_line(); ?></td>
              </tr>
<?php
  $info_box_contents = array();
  if ($mInfo || ($HTTP_GET_VARS['action'] == 'new') ) {
    if ($HTTP_GET_VARS['action'] == 'new') {
      $form = '<form name="manufacturers" enctype="multipart/form-data" action="' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('action')) . 'action=insert', 'NONSSL') . '" method="post">' . "\n";

      $info_box_contents[] = array('align' => 'left', 'text' => TEXT_NEW_INTRO . '<br>&nbsp;');
      $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_EDIT_MANUFACTURERS_NAME . '<br>&nbsp;<input type="text" name="manufacturers_name"><br>&nbsp;<br>&nbsp;' . TEXT_EDIT_MANUFACTURERS_IMAGE . '<br>&nbsp;<input type="file" name="manufacturers_image" size="20" style="font-size:10px">');

      $languages = tep_get_languages();
      for ($i=0; $i<sizeof($languages); $i++) {
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_EDIT_MANUFACTURERS_URL . ' (' . $languages[$i]['name'] . ')<br>&nbsp;<input type="text" name="manufacturers_url[' . $languages[$i]['id'] . ']"><br>');
      }

      $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_WS_IMAGES . 'button_save.gif', IMAGE_SAVE) . '<a href="' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
    } elseif ($HTTP_GET_VARS['action'] == 'edit') {
      $form = '<form name="manufacturers" enctype="multipart/form-data" action="' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('action')) . 'action=save', 'NONSSL') . '" method="post"><input type="hidden" name="manufacturers_id" value="' . $mInfo->id . '">'  ."\n";

      $info_box_contents[] = array('align' => 'left', 'text' => TEXT_EDIT_INTRO . '<br>&nbsp;');
      $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_EDIT_MANUFACTURERS_NAME . '<br>&nbsp;<input type="text" name="manufacturers_name" value="' . $mInfo->name . '"><br>&nbsp;<br>&nbsp;' . TEXT_EDIT_MANUFACTURERS_IMAGE . '<br>&nbsp;<input type="file" name="manufacturers_image" size="20" style="font-size:10px"><br>' . $mInfo->image);

      $languages = tep_get_languages();
      for ($i=0; $i<sizeof($languages); $i++) {
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_EDIT_MANUFACTURERS_URL . ' (' . $languages[$i]['name'] . ')<br>&nbsp;<input type="text" name="manufacturers_url[' . $languages[$i]['id'] . ']" value="' . tep_get_manufacturer_url($mInfo->id, $languages[$i]['id']) . '"><br>');
      }

      $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_WS_IMAGES . 'button_save.gif', IMAGE_SAVE) . '<a href="' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
    } elseif ($HTTP_GET_VARS['action'] == 'delete') {
      $form = '<form name="manufacturers" action="' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('action')) . 'action=deleteconfirm', 'NONSSL') . '" method="post"><input type="hidden" name="manufacturers_id" value="' . $mInfo->id . '">' . "\n";

      $info_box_contents[] = array('align' => 'left', 'text' => TEXT_DELETE_INTRO . '<br>&nbsp;');
      $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $mInfo->name . '</b>');
      if ($mInfo->products_count > 0) {
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . tep_draw_checkbox_field('delete_products') . ' ' . TEXT_DELETE_PRODUCTS);
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $mInfo->products_count));
      }
      $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
    } else {
      $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('action')) . 'action=edit', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params(array('action')) . 'action=delete', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . '</a>');
      $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_DATE_ADDED . ' ' . tep_date_short($mInfo->added) . '&nbsp;<br>&nbsp;' . TEXT_LAST_MODIFIED . ' ' . tep_date_short($mInfo->modified) . '&nbsp;');
      $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . tep_info_image($mInfo->image, $mInfo->name));
      $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_PRODUCTS . ' ' . $mInfo->products_count);
    }
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