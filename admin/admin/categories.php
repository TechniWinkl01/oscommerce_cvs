<?php
/*
  $Id: categories.php,v 1.69 2001/09/09 17:17:59 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ($HTTP_GET_VARS['action']) {
    switch ($HTTP_GET_VARS['action']) {
// update category
      case 'save':            tep_db_query("update " . TABLE_CATEGORIES . " set sort_order = '" . $HTTP_POST_VARS['sort_order'] . "', parent_id = '" . $HTTP_POST_VARS['parent_id'] . "', last_modified = now() where categories_id = '" . $HTTP_POST_VARS['categories_id'] . "'");

                              $languages = tep_get_languages();
                              for ($i=0; $i<sizeof($languages); $i++) {
                                $categories_name_array = $HTTP_POST_VARS['categories_name'];
                                $language_id = $languages[$i]['id'];
                                $categories_name = $categories_name_array[$language_id];
                                tep_db_query("update " . TABLE_CATEGORIES_DESCRIPTION . " set categories_name = '" . $categories_name . "' where categories_id = '" . $HTTP_POST_VARS['categories_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");
                              }

                              if ( ($categories_image != 'none') && ($categories_image != '') ) {
                                tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = 'images/" . $categories_image_name . "' where categories_id = '" . $HTTP_POST_VARS['categories_id'] . "'");
                                $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $categories_image_name;
                                if (file_exists($image_location)) @unlink($image_location);
                                copy($categories_image, $image_location);
                              }

                              tep_redirect(tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pinfo', 'info')) . 'info=' . $HTTP_POST_VARS['categories_id'], 'NONSSL'));
                              break;
// delete category
      case 'deleteconfirm':   if ($HTTP_POST_VARS['categories_id']) {
                                tep_db_query("delete from " . TABLE_CATEGORIES . " where categories_id = '" . $HTTP_POST_VARS['categories_id'] . "'");
                                tep_db_query("delete from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $HTTP_POST_VARS['categories_id'] . "'");
                              } elseif ($HTTP_POST_VARS['products_id']) {
                                $products_categories_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $HTTP_POST_VARS['products_id'] . "'");
                                $products_categories = tep_db_fetch_array($products_categories_query);

                                if ($products_categories['total'] > 1) {
                                  tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $HTTP_POST_VARS['products_id'] . "' and categories_id = '" . $current_category_id . "'");
                                } else {
                                  $products_image = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . $HTTP_POST_VARS['products_id'] . "'");
                                  $products_image = tep_db_fetch_array($products_image);
                                  if (file_exists(DIR_FS_CATALOG . $products_image['products_image'])) {
                                    @unlink(DIR_FS_CATALOG . $products_image['products_image']);
                                  }
                                  tep_db_query("delete from " . TABLE_SPECIALS . " where products_id = '" . $HTTP_POST_VARS['products_id'] . "'");
                                  tep_db_query("delete from " . TABLE_PRODUCTS . " where products_id = '" . $HTTP_POST_VARS['products_id'] . "'");
                                  tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $HTTP_POST_VARS['products_id'] . "'");
                                  tep_db_query("delete from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $HTTP_POST_VARS['products_id'] . "'");
                                }
                              }

                              tep_redirect(tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pinfo', 'info')), 'NONSSL'));
                              break;
// move category
      case 'moveconfirm':     if ($HTTP_POST_VARS['categories_id']) {
                                tep_db_query("update " . TABLE_CATEGORIES . " set parent_id = '" . $HTTP_POST_VARS['move_to_category_id'] . "', last_modified = now() where categories_id = '" . $HTTP_POST_VARS['categories_id'] . "'");
                              } elseif ($HTTP_POST_VARS['products_id']) {
                                tep_db_query("update " . TABLE_PRODUCTS_TO_CATEGORIES . " set categories_id = '" . $HTTP_POST_VARS['move_to_category_id'] . "' where products_id = '" . $HTTP_POST_VARS['products_id'] . "' and categories_id = '" . $current_category_id . "'");
                              }

                              tep_redirect(tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pinfo', 'info')), 'NONSSL'));
                              break;
// insert category
      case 'insert_category': tep_db_query("insert into " . TABLE_CATEGORIES . " (parent_id, sort_order, date_added) values ('" . $current_category_id . "', '" . $HTTP_POST_VARS['sort_order'] . "', now())");
                              $categories_id = tep_db_insert_id();

                              $languages = tep_get_languages();
                              for ($i=0; $i<sizeof($languages); $i++) {
                                $categories_name_array = $HTTP_POST_VARS['categories_name'];
                                $language_id = $languages[$i]['id'];
                                $categories_name = $categories_name_array[$language_id];
                                tep_db_query("insert into " . TABLE_CATEGORIES_DESCRIPTION . " (categories_id, language_id, categories_name) values ('" . $categories_id . "', '" . $languages[$i]['id'] . "', '" . $categories_name . "')");
                              }

                              if (!($categories_image == 'none' || $categories_image == '')) {
                                tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = 'images/" . $categories_image_name . "' where categories_id = '" . $categories_id . "'");
                                $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $categories_image_name;
                                if (file_exists($image_location)) @unlink($image_location);
                                copy($categories_image, $image_location);
                              }

                              tep_redirect(tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pinfo', 'info')), 'NONSSL'));
                              break;
// insert product
      case 'insert_product':  $products_date_available = $HTTP_POST_VARS['year'];
                              $products_date_available .= (strlen($HTTP_POST_VARS['month']) == 1) ? '0' . $HTTP_POST_VARS['month'] : $HTTP_POST_VARS['month'];
                              $products_date_available .= (strlen($HTTP_POST_VARS['day']) == 1) ? '0' . $HTTP_POST_VARS['day'] : $HTTP_POST_VARS['day'];

                              $products_date_available = (date('Y-m-d H:i:s') < $products_date_available) ? $products_date_available : '';

                              tep_db_query("insert into " . TABLE_PRODUCTS . " (products_quantity, products_model, products_image, products_price, products_date_added, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id) values ('" . $HTTP_POST_VARS['products_quantity'] . "', '" . $HTTP_POST_VARS['products_model'] . "', '" . $HTTP_POST_VARS['products_image'] . "', '" . $HTTP_POST_VARS['products_price'] . "', now(), '" . $products_date_available . "', '" . $HTTP_POST_VARS['products_weight'] . "', '" . $HTTP_POST_VARS['products_status'] . "', '" . $HTTP_POST_VARS['products_tax_class_id'] . "', '" . $HTTP_POST_VARS['manufacturers_id'] . "')");
                              $new_products_id = tep_db_insert_id();
                              tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . $new_products_id . "', '" . $current_category_id . "')");

                              $languages = tep_get_languages();
                              for ($i=0; $i<sizeof($languages); $i++) {
                                $language_id = $languages[$i]['id'];
                                tep_db_query("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (products_id, language_id, products_name, products_description, products_url) values ('" . $new_products_id . "', '" . $language_id . "' , '".$HTTP_POST_VARS['products_name'][$language_id]."' , '".$HTTP_POST_VARS['products_description'][$language_id]."' , '".$HTTP_POST_VARS['products_url'][$language_id]."')");
                              }

                              tep_redirect(tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pinfo', 'info')), 'NONSSL'));
                              break;
// update product
      case 'update_product':  $products_date_available = $HTTP_POST_VARS['year'];
                              $products_date_available .= (strlen($HTTP_POST_VARS['month']) == 1) ? '0' . $HTTP_POST_VARS['month'] : $HTTP_POST_VARS['month'];
                              $products_date_available .= (strlen($HTTP_POST_VARS['day']) == 1) ? '0' . $HTTP_POST_VARS['day'] : $HTTP_POST_VARS['day'];

                              $products_date_available = (date('Y-m-d H:i:s') < $products_date_available) ? $products_date_available : '';

                              tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $HTTP_POST_VARS['products_quantity'] . "', products_model = '" . $HTTP_POST_VARS['products_model'] . "', products_image = '" . $HTTP_POST_VARS['products_image'] . "', products_price = '" . $HTTP_POST_VARS['products_price'] . "', products_last_modified = now(), products_date_available = '" . $products_date_available . "', products_weight = '" . $HTTP_POST_VARS['products_weight'] . "', products_tax_class_id = '" . $HTTP_POST_VARS['products_tax_class_id'] . "', products_status = '" . $HTTP_POST_VARS['products_status'] . "', manufacturers_id = '" . $HTTP_POST_VARS['manufacturers_id'] . "' where products_id = '" . $HTTP_GET_VARS['pID'] . "'");

                              $languages = tep_get_languages();
                              for ($i=0; $i<sizeof($languages); $i++) {
                                $language_id = $languages[$i]['id'];
                                $products_name = $HTTP_POST_VARS['products_name'][$language_id];
                                $products_description = $HTTP_POST_VARS['products_description'][$language_id];
                                $products_url = $HTTP_POST_VARS['products_url'][$language_id];
                                tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_name = '" . $products_name . "', products_description = '" . $products_description . "', products_url = '" . $products_url . "' where products_id = '" . $HTTP_GET_VARS['pID'] . "' and language_id = '". $language_id . "'");
                              }

                              tep_redirect(tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pID')) . 'pinfo=' . $HTTP_GET_VARS['pID'], 'NONSSL'));
                              break;
// copy product to another cateogry
      case 'copy_to_confirm': tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . $HTTP_POST_VARS['products_id'] . "', '" . $HTTP_POST_VARS['categories_id'] . "')");
                              tep_redirect(tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')), 'NONSSL'));
                              break;
    }
  }
?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<?
  if ($HTTP_GET_VARS['action'] == 'new_product') {
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/calendar.css">
<script language="JavaScript" src="includes/javascript/calendarcode.js"></script>
<?
  }
?>
</head>
<body onload="SetFocus();">
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
            <td class="topBarTitle">&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, '', 'NONSSL') . '" class="blacklink">' . TOP_BAR_TITLE . '</a>'; ?>
<?
// output a navigation path of categories entered
  if ($cPath) {
    if (!ereg('_', $cPath)) $cPath_array = array($cPath);
    $cPath_new = '';
    for($i=0;$i<sizeof($cPath_array);$i++) {
      if ($cPath_new == '') {
        $cPath_new .= $cPath_array[$i];
      } else {
        $cPath_new .= '_' . $cPath_array[$i];
      }
      if ($i != (sizeof($cPath_array)-1)) $cPath_back = $cPath_new; // for a back button function (search for it below)
      $parent_categories_name = tep_get_category_name($cPath_array[$i], $languages_id);
      echo ' -> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath_new, 'NONSSL') . '" class="blacklink">' . $parent_categories_name . '</a>';
    }
  }
?>
            &nbsp;</td>
          </tr>
        </table></td>
      </tr>
<?
  if ($HTTP_GET_VARS['action'] == 'new_product') {
    if ($HTTP_GET_VARS['pID']) {
      $product_query = tep_db_query("select pd.products_name, pd.products_description, pd.products_url, p.products_id, p.products_quantity, p.products_model, p.products_image, p.products_price, p.products_weight, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_tax_class_id, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . $HTTP_GET_VARS['pID'] . "' and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "'");
      $product = tep_db_fetch_array($product_query);

      $pInfo = new productInfo($product);
    } elseif ($HTTP_POST_VARS) {
/* not in use at the moment! this should be used when the user presses 'BACK' on the products preview page.. */
      $pInfo = new productInfo($HTTP_POST_VARS);
    } else {
      $pInfo = new productInfo(array());
    }

    $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
    $tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_id");

    if ($parent_categories_name == '') $parent_categories_name = 'Top Level Categories';
?>
      <tr><form name="new_product" enctype="multipart/form-data" <? echo 'action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pinfo', 'info')) . 'action=new_product_preview', 'NONSSL') . '"'; ?> method="post">
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="pageHeading">&nbsp;<? echo sprintf(TEXT_NEW_PRODUCT, $parent_categories_name); ?>&nbsp;</td>
            <td align="right">&nbsp;<? echo tep_image(DIR_WS_CATALOG . 'images/pixel_trans.gif', '', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><br><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">&nbsp;<? echo TEXT_PRODUCTS_STATUS; ?>&nbsp;</td>
            <td class="main">&nbsp;<input type="radio" name="products_status" value="1" 
<?
	if (@$pInfo->status == '1' && $product['products_status'] == '1') {  
	  echo ' CHECKED';
	} ?>>&nbsp;<? echo TEXT_PRODUCT_AVAILABLE; ?>&nbsp;<input type="radio" name="products_status" value="0"
<?
	if (@$pInfo->status == '0' && $product['products_status'] == '0') {  
	  echo ' CHECKED';
	} ?>>&nbsp;<? echo TEXT_PRODUCT_NOT_AVAILABLE; ?>&nbsp;
	          </td>
          </tr>
          <tr>
            <td class="main">&nbsp;<? echo TEXT_PRODUCTS_DATE_AVAILABLE; ?><br>&nbsp;<span class="smallText">(dd/mm/yyyy)</span>&nbsp;</td>
            <td class="main">&nbsp;<input class="cal-TextBox" size="2" maxlength="2" type="text" name="day" value="<?php echo $pInfo->date_available_caljs_day; ?>"><input class="cal-TextBox" size="2" maxlength="2" type="text" name="month" value="<?php echo $pInfo->date_available_caljs_month; ?>"><input class="cal-TextBox" size="4" maxlength="4" type="text" name="year" value="<? echo $pInfo->date_available_caljs_year; ?>"><a class="so-BtnLink" href="javascript:calClick();return false;" onmouseover="calSwapImg('BTN_date', 'img_Date_OVER',true);" onmouseout="calSwapImg('BTN_date', 'img_Date_UP',true);" onclick="calSwapImg('BTN_date', 'img_Date_DOWN');showCalendar('new_product','dteWhen','BTN_date');return false;"><img align="absmiddle" border="0" name="BTN_date" src="<?php echo DIR_WS_IMAGES; ?>cal_date_up.gif" width="22" height="17"></a>&nbsp;</td>
          </tr>
          <tr>
            <td class="main">&nbsp;<? echo TEXT_PRODUCTS_MANUFACTURER; ?>&nbsp;</td>
            <td class="main">&nbsp;<select name="manufacturers_id"><option value=""></option><? while ($manufacturers = tep_db_fetch_array($manufacturers_query)) { echo '<option value="' . $manufacturers['manufacturers_id'] . '"'; if (@$pInfo->manufacturers_id == $manufacturers['manufacturers_id']) echo ' SELECTED'; echo '>' . $manufacturers['manufacturers_name'] . '</option>'; } ?></select>&nbsp;</td>
          </tr>
<?
    $languages = tep_get_languages();
    for ($i=0; $i<sizeof($languages); $i++) {
?>
          <tr>
            <td class="main">&nbsp;<? echo TEXT_PRODUCTS_NAME . ' (' . $languages[$i]['name'] . ')'; ?>&nbsp;</td>
            <td class="main">&nbsp;<input type="text" name="products_name[<? echo $languages[$i]['id']; ?>]" value="<? echo tep_get_products_name($pInfo->id, $languages[$i]['id']); ?>">&nbsp;</td>
          </tr>
<?
    }
?>
          <tr>
            <td class="main" colspan="2">&nbsp;</td>
          </tr>
<?
    for ($i=0; $i<sizeof($languages); $i++) {
?>
          <tr>
            <td class="main">&nbsp;<? echo TEXT_PRODUCTS_DESCRIPTION . ' (' . $languages[$i]['name'] . ')'; ?>&nbsp;</td>
            <td class="main">&nbsp;<textarea name="products_description[<? echo $languages[$i]['id']; ?>]" cols="50" rows="10"><? echo tep_get_products_description($pInfo->id, $languages[$i]['id']); ?></textarea>&nbsp;</td>
          </tr>
<?
    }
?>
          <tr>
            <td class="main" colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td class="main">&nbsp;<? echo TEXT_PRODUCTS_QUANTITY; ?>&nbsp;</td>
            <td class="main">&nbsp;<input type="text" name="products_quantity" value="<? echo @$pInfo->quantity; ?>">&nbsp;</td>
          </tr>
          <tr>
            <td class="main">&nbsp;<? echo TEXT_PRODUCTS_MODEL; ?>&nbsp;</td>
            <td class="main">&nbsp;<input type="text" name="products_model" value="<? echo @$pInfo->model; ?>">&nbsp;</td>
          </tr>
          <tr>
            <td class="main">&nbsp;<? echo TEXT_PRODUCTS_IMAGE; ?>&nbsp;</td>
            <td class="main">&nbsp;<input type="file" name="products_image" size="20">&nbsp;<br>&nbsp;<? echo @$pInfo->image; ?><input type="hidden" name="products_previous_image" value="<? echo @$pInfo->image; ?>"></td>
          </tr>
<?
    for ($i=0; $i<sizeof($languages); $i++) {
?>
          <tr>
            <td class="main">&nbsp;<? echo TEXT_PRODUCTS_URL . ' (' . $languages[$i]['name'] . ')'; ?>&nbsp;</td>
            <td class="main">&nbsp;<input type="text" name="products_url[<? echo $languages[$i]['id']; ?>]" value="<? echo tep_get_products_url($pInfo->id, $languages[$i]['id']); ?>">&nbsp;<span class="smallText"><? echo TEXT_PRODUCTS_URL_WITHOUT_HTTP; ?></span></td>
          </tr>
<?
    }
?>
          <tr>
            <td class="main">&nbsp;<? echo TEXT_PRODUCTS_PRICE; ?>&nbsp;</td>
            <td class="main">&nbsp;<input type="text" name="products_price" value="<? echo @$pInfo->price; ?>">&nbsp;</td>
          </tr>
          <tr>
            <td class="main">&nbsp;<? echo TEXT_PRODUCTS_TAX_CLASS; ?>&nbsp;</td>
            <td class="main">&nbsp;<select name="products_tax_class_id"><option value="0">None Selected</option><? while ($tax_class = tep_db_fetch_array($tax_class_query)) { echo '<option value="' . $tax_class['tax_class_id'] . '"'; if (@$pInfo->tax_class == $tax_class['tax_class_id']) echo ' SELECTED'; echo '>' . $tax_class['tax_class_title'] . '</option>'; } ?></select>&nbsp;</td>
          </tr>
          <tr>
            <td class="main">&nbsp;<? echo TEXT_PRODUCTS_WEIGHT; ?>&nbsp;</td>
            <td class="main">&nbsp;<input type="text" name="products_weight" value="<? echo @$pInfo->weight; ?>">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td class="main" align="right"><br>&nbsp;<input type="hidden" name="products_date_added" value="<? if (@$pInfo->date_added) { echo $pInfo->date_added; } else { echo date('Y-m-d'); } ?>"><? echo tep_image_submit(DIR_WS_IMAGES . 'button_preview.gif', IMAGE_PREVIEW); ?>&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pID', 'pinfo', 'info')) . 'pinfo=' . $HTTP_GET_VARS['pID'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?>&nbsp;</td>
      </form></tr>
<?
  } elseif ($HTTP_GET_VARS['action'] == 'new_product_preview') {
    if ($HTTP_POST_VARS) {
      $manufacturer_query = tep_db_query("select manufacturers_name, manufacturers_image from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . $HTTP_POST_VARS['manufacturers_id'] . "'");
      $manufacturer = tep_db_fetch_array($manufacturer_query);

      $pInfo_array = tep_array_merge((array)$HTTP_POST_VARS, (array)$manufacturer);
      $pInfo = new productInfo($pInfo_array);

      // Copy image only if modified
      if ($products_image && ($products_image != 'none')) {
        $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $products_image_name;
        if (file_exists($image_location)) @unlink($image_location);
        copy($products_image, $image_location);
        $products_image_name = 'images/' . $products_image_name;
      } else {
        $products_image_name = $products_previous_image;
      }

    } else {
      $product_query = tep_db_query("select p.products_id, pd.language_id, pd.products_name, pd.products_description, pd.products_url, p.products_quantity, p.products_model, p.products_image, p.products_price, p.products_weight, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.manufacturers_id, m.manufacturers_name, m.manufacturers_image from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m where p.manufacturers_id = m.manufacturers_id and p.products_id = pd.products_id and pd.products_id = '" . $HTTP_GET_VARS['pID'] . "'");
      $product = tep_db_fetch_array($product_query);

      $pInfo = new productInfo($product);
      $products_image_name = $pInfo->image;
    }

    $form_action = 'insert_product';
    if ($HTTP_GET_VARS['pID']) $form_action = 'update_product';
?>
      <form name="<? echo $form_action; ?>" enctype="multipart/form-data" <? echo 'action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=' . $form_action, 'NONSSL') . '"'; ?> method="post">
<?
    if ($HTTP_GET_VARS['read'] == 'only') {
      $languages = tep_get_languages();
      for ($i=0; $i<sizeof($languages); $i++) {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="pageHeading">&nbsp;<? echo tep_get_products_name($pInfo->id, $languages[$i]['id']) . ' (' . $languages[$i]['name'] . ')<br>&nbsp;@ ' . tep_currency_format($pInfo->price); ?>&nbsp;</td>
            <td align="right"><? echo tep_image(DIR_WS_CATALOG . $pInfo->manufacturers_image, $pInfo->manufacturer, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td class="main"><br>
<?
        echo tep_image(DIR_WS_CATALOG . $products_image_name, tep_get_products_name($pInfo->id, $languages[$i]['id']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"') .
             ' (' . $languages[$i]['name'] . ')<br>' . tep_get_products_description($pInfo->id, $languages[$i]['id']) . '<br><br>';
?>      </td>
      </tr>
<?
        if ($pInfo->url) {
?>
      <tr>
        <td class="main"><br><? echo sprintf(TEXT_PRODUCT_MORE_INFORMATION, tep_get_products_url($pInfo->id, $languages[$i]['id'])) . ' (' . $languages[$i]['name'] . ')<br>'; ?></td>
      </tr>
<?
        }

        if ($pInfo->date_available > date('Y-m-d H:i:s')) {
?>
      <tr>
        <td align="center" class="smallText"><br><? echo sprintf(TEXT_PRODUCT_DATE_AVAILABLE, tep_date_long($pInfo->date_available)); ?></td>
      </tr>
<?
        } else {
?>
      <tr>
        <td align="center" class="smallText"><br><? echo sprintf(TEXT_PRODUCT_DATE_ADDED, tep_date_long($pInfo->date_added)); ?></td>
      </tr>
<?
        }
?>
      <tr>
        <td><br><? echo tep_black_line(); ?></td>
      </tr>
<?
      }
    } else {
      $languages = tep_get_languages();
      for ($i=0; $i<sizeof($languages); $i++) {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="pageHeading">&nbsp;<? echo $products_name[$languages[$i]['id']] . ' (' . $languages[$i]['name'] . ')<br>@ ' . tep_currency_format($pInfo->price); ?>&nbsp;</td>
            <td align="right"><? echo tep_image(DIR_WS_CATALOG . $pInfo->manufacturers_image, $pInfo->manufacturer, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td class="main"><br><? echo tep_image(DIR_WS_CATALOG . $products_image_name, $products_name[$languages[$i]['id']], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"') . ' (' . $languages[$i]['name'] . ')<br>' . $products_description[$languages[$i]['id']]; ?></td>
      </tr>
<?
        if ($pInfo->url) {
?>
      <tr>
        <td class="main"><br><? echo sprintf(TEXT_PRODUCT_MORE_INFORMATION, $products_url[$languages[$i]['id']]) . ' (' . $languages[$i]['name'] . ')'; ?></td>
      </tr>
<?
        }

        if ($pInfo->date_available > date('Y-m-d H:i:s')) {
?>
      <tr>
        <td align="center" class="smallText"><br><? echo sprintf(TEXT_PRODUCT_DATE_AVAILABLE, tep_date_long($pInfo->date_available)); ?></td>
      </tr>
<?
        } else {
?>
      <tr>
        <td align="center" class="smallText"><br><? echo sprintf(TEXT_PRODUCT_DATE_ADDED, tep_date_long($pInfo->date_added)); ?></td>
      </tr>
<?
        }
?>
      <tr>
        <td><br><? echo tep_black_line(); ?></td>
      </tr>
<?
      }
    }

    if ($HTTP_GET_VARS['read'] == 'only') {
      if ($HTTP_GET_VARS['origin']) {
        $pos_params = strpos($HTTP_GET_VARS['origin'], '?', 0);
        if ($pos_params != false) {
          $back_url = substr($HTTP_GET_VARS['origin'], 0, $pos_params);
          $back_url_params = substr($HTTP_GET_VARS['origin'], $pos_params + 1);
        } else {
          $back_url = $HTTP_GET_VARS['origin'];
          $back_url_params = '';
        }
      } else {
        $back_url = FILENAME_CATEGORIES;
        $back_url_params = tep_get_all_get_params(array('action', 'pID', 'read', 'pinfo', 'info')) . 'pinfo=' . $HTTP_GET_VARS['pID'];
      }
?>
      <tr>
        <td align="right" class="smallText"><br><? echo '<a href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_back.gif', IMAGE_BACK) . '</a>'; ?>&nbsp;</td>
      </tr>
<?
    } else {
?>
      <tr>
        <td align="right" class="smallText"><br>
<?
/* Re-Post all POST'ed variables */
      reset($HTTP_POST_VARS);
      while (list($key, $value) = each($HTTP_POST_VARS)) echo '<input type="hidden" name="' . $key . '" value="' . htmlspecialchars(stripslashes($value)) . '">';
      $languages = tep_get_languages();
      for ($i=0; $i<sizeof($languages); $i++) {
        echo '<input type="hidden" name="products_name[' . $languages[$i]['id'] . ']" value="' . htmlspecialchars(stripslashes($products_name[$languages[$i]['id']])) . '">' .
             '<input type="hidden" name="products_description[' . $languages[$i]['id'] . ']" value="' . htmlspecialchars(stripslashes($products_description[$languages[$i]['id']])) . '">' .
             '<input type="hidden" name="products_url[' . $languages[$i]['id'] . ']" value="' . htmlspecialchars(stripslashes($products_url[$languages[$i]['id']])) . '">';
      }
      echo '<input type="hidden" name="products_image" value="' . htmlspecialchars(stripslashes($products_image_name)) . '">';

      echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=new_product', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_back.gif', IMAGE_BACK) . '</a>&nbsp;';
      if ($HTTP_GET_VARS['pID']) {
        echo tep_image_submit(DIR_WS_IMAGES . 'button_update.gif', IMAGE_UPDATE);
      } else {
        echo tep_image_submit(DIR_WS_IMAGES . 'button_insert.gif', IMAGE_INSERT);
      }
      echo '&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pID')) . 'info=' . $HTTP_GET_VARS['pID'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>';
?>&nbsp;</td>
      </form></tr>
<?
    }
  } else {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right">&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2"><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="tableHeading" align="center">&nbsp;<? echo TABLE_HEADING_ID; ?>&nbsp;</td>
                <td class="tableHeading">&nbsp;<? echo TABLE_HEADING_CATEGORIES_PRODUCTS; ?>&nbsp;</td>
                <td class="tableHeading" align="center">&nbsp;<? echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3"><? echo tep_black_line(); ?></td>
              </tr>
<?
    $categories_count = 0;
    $rows = 0;
    $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . $current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by c.sort_order, cd.categories_name");
    while ($categories = tep_db_fetch_array($categories_query)) {
      $categories_count++;
      $rows++;

      if ( ((!$HTTP_GET_VARS['pinfo'] && !$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['info'] == $categories['categories_id'])) && (!$cInfo) && (!$pInfo) && (substr($HTTP_GET_VARS['action'], 0, 4) != 'new_') ) {
// count category childs
        $category_childs = array('childs_count' => tep_childs_in_category_count($categories['categories_id']));
// count category proucts
        $category_products = array('products_count' => tep_products_in_category_count($categories['categories_id']));

        $cInfo_array = tep_array_merge($categories, $category_childs, $category_products);
        $cInfo = new categoryInfo($cInfo_array);
      }

      if ($categories['categories_id'] == @$cInfo->id) {
        echo '              <tr class="selectedRow" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id']), 'NONSSL') . '\'">' . "\n";
      } else {
        echo '              <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('pinfo', 'info', 'action')) . 'info=' . $categories['categories_id'], 'NONSSL') . '\'">' . "\n";
      }
?>
                <td class="smallText" align="center">&nbsp;<? echo $categories['categories_id']; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<b><? echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id']), 'NONSSL') . '" class="blacklink"><u>' . $categories['categories_name'] . '</u></a>'; ?></b>&nbsp;</td>
<?
      if ($categories['categories_id'] == @$cInfo->id) {
?>
                <td width="10%" align="center" class="smallText">&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); ?>&nbsp;</td>
<?
      } else {
?>
                <td width="10%" align="center" class="smallText">&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('pinfo', 'info', 'action')) . 'info=' . $categories['categories_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; ?>&nbsp;</td>
<?
      }
?>
              </tr>
<?
    }

    $products_count = 0;
//  $rows = 0; // this shouldnt be reset
    $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . $current_category_id . "' order by pd.products_name");
    while ($products = tep_db_fetch_array($products_query)) {
      $products_count++;
      $rows++;

      if ( ((!$HTTP_GET_VARS['pinfo'] && !$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['pinfo'] == $products['products_id'])) && (!$pInfo) && (!$cInfo) && (substr($HTTP_GET_VARS['action'], 0, 4) != 'new_') ) {
// find out the rating average from customer reviews
        $reviews_query = tep_db_query("select (avg(reviews_rating) / 5 * 100) as average_rating from " . TABLE_REVIEWS . " where products_id = '" . $products['products_id'] . "'");
        if ($reviews_query != '') $reviews = tep_db_fetch_array($reviews_query);
        $pInfo_array = tep_array_merge($products, $reviews);
        $pInfo = new productInfo($pInfo_array);
      }

      if ($products['products_id'] == @$pInfo->id) {
        echo '              <tr class="selectedRow" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pID')) . 'pID=' . $products['products_id'] . '&action=new_product_preview&read=only', 'NONSSL') . '\'">' . "\n";
      } else {
        echo '              <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('pinfo', 'info', 'action')) . 'pinfo=' . $products['products_id'], 'NONSSL') . '\'">' . "\n";
      }
?>
                <td class="smallText" align="center">&nbsp;<? echo $products['products_id']; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pID', 'info', 'pinfo')) . 'pID=' . $products['products_id'] . '&action=new_product_preview&read=only', 'NONSSL') . '" class="blacklink"><u>' . $products['products_name'] . '</u></a>'; ?>&nbsp;</td>
<?
      if ($products['products_id'] == @$pInfo->id) {
?>
                <td width="10%" align="center" class="smallText">&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); ?>&nbsp;</td>
<?
      } else {
?>
                <td width="10%" align="center" class="smallText">&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('pinfo', 'info', 'action')) . 'pinfo=' . $products['products_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; ?>&nbsp;</td>
<?
      }
?>
              </tr>
<?
    }

    if ($rows > 0) {
?>
              <tr>
                <td colspan="3"><? echo tep_black_line(); ?></td>
              </tr>
<?
    }
    if (!$cPath_back) {
      $cPath_back = '';
    } else {
      $cPath_back = 'cPath=' . $cPath_back;
    }
?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText">&nbsp;<? echo TEXT_CATEGORIES; ?> <? echo $categories_count; ?>&nbsp;<br>&nbsp;<? echo TEXT_PRODUCTS; ?>&nbsp;<? echo $products_count; ?>&nbsp;</td>
                    <td align="right" class="smallText">&nbsp;<? if ($cPath) echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, $cPath_back, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_back.gif', IMAGE_BACK) . '</a>&nbsp;'; ?><? echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pinfo', 'info')) . 'action=new_category', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_new_category.gif', IMAGE_NEW_CATEGORY) . '</a>'; ?>&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pinfo', 'info')) . 'action=new_product', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_new_product.gif', IMAGE_NEW_PRODUCT) . '</a>'; ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
<?
    if ($HTTP_GET_VARS['error']) {
?>
              <tr>
                <td colspan="3"><? echo tep_black_line(); ?></td>
              </tr>
              <tr>
                <td colspan="3" class="smallText">&nbsp;<? echo ERROR_ACTION; ?>&nbsp;</td>
              </tr>
<?
    }
?>
            </table></td>
            <td width="25%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?
    $info_box_contents = array();
    if ($cInfo && !$pInfo) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . $cInfo->name . '</b>');
    if ($pInfo && !$cInfo) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . tep_get_products_name($pInfo->id, $languages_id) . '</b>');
    if (!$pInfo && !$cInfo) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;<b>' . EMPTY_CATEGORY . '</b>');
?>
              <tr class="boxHeading">
                <td><? new infoBoxHeading($info_box_contents); ?></td>
              </tr>
              <tr class="boxHeading">
                <td><? echo tep_black_line(); ?></td>
              </tr>
<?
/* here we display the appropiate info box on the right of the main table */
    switch ($HTTP_GET_VARS['action']) {
/* edit category box contents */
      case 'edit_category':
        $form = '<form name="categories" enctype="multipart/form-data" action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=save', 'NONSSL') . '" method="post"><input type="hidden" name="categories_id" value="' . $cInfo->id . '">' . "\n";

        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left', 'text' => TEXT_EDIT_INTRO . '<br>');

        $languages = tep_get_languages();
        for ($i=0; $i<sizeof($languages); $i++) {
          $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . TEXT_EDIT_CATEGORIES_NAME . ' (' . $languages[$i]['name'] . ')<br><input type="text" name="categories_name[' . $languages[$i]['id'] . ']" value="' . tep_get_category_name($cInfo->id, $languages[$i]['id']) . '"><br>');
        }

        $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . TEXT_EDIT_CATEGORIES_IMAGE . '<br><input type="file" name="categories_image" size="20" style="font-size:10px"><br>' . $cInfo->image . '<br><br>' . TEXT_EDIT_SORT_ORDER . '<br><input type="text" name="sort_order" size="2" value="' . $cInfo->sort_order . '"><br>');
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . TEXT_EDIT_PARENT_ID . '<br><input type="text" name="parent_id" value="' . $cInfo->parent_id . '"><br>');
        $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');

        break;
/* delete category box contents */
      case 'delete_category':
        $form = '<form name="categories" action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=deleteconfirm', 'NONSSL') . '" method="post"><input type="hidden" name="categories_id" value="' . $cInfo->id . '">' . "\n";

        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left', 'text' => TEXT_DELETE_CATEGORY_INTRO);
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;<b>' . $cInfo->name . '</b>');
        if ($cInfo->childs_count > 0) $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count));
        if ($cInfo->products_count > 0) $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count));
        if ( ($cInfo->products_count > 0) || ($cInfo->childs_count > 0) ) {
          $button = '';
        } else {
          $button = tep_image_submit(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE);
        }
        $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . $button . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');

        break;
/* delete product box contents */
      case 'delete_product':
        $form = '<form name="products" action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=deleteconfirm', 'NONSSL') . '" method="post"><input type="hidden" name="products_id" value="' . $pInfo->id . '">' . "\n";

        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left', 'text' => TEXT_DELETE_PRODUCT_INTRO);
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;<b>' . $pInfo->name . '</b>');
        $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');

        break;
/* move category box contents */
      case 'move_category':
        $form = '<form name="categories" action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=moveconfirm', 'NONSSL') . '" method="post"><input type="hidden" name="categories_id" value="' . $cInfo->id . '">' . "\n";

        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left', 'text' => sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->name));
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . sprintf(TEXT_MOVE, $cInfo->name) . '<br>&nbsp;' . tep_categories_pull_down('name="move_to_category_id" style="font-size:10px"', $cInfo->id));
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;<br>' . TEXT_MOVE_NOTE);
        $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');

        break;
/* move product box contents */
      case 'move_product':
        $products_categories_array = tep_products_categories_array($pInfo->id, true);

        $form = '<form name="products" action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=moveconfirm', 'NONSSL') . '" method="post"><input type="hidden" name="products_id" value="' . $pInfo->id . '">' . "\n";

        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left', 'text' => sprintf(TEXT_MOVE_PRODUCTS_INTRO, $pInfo->name));
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . sprintf(TEXT_MOVE, $pInfo->name) . '<br>&nbsp;' . tep_categories_pull_down('name="move_to_category_id" style="font-size:10px"', $products_categories_array));
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;<br>' . TEXT_MOVE_NOTE);
        $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');

        break;
/* new category box contents */
      case 'new_category':
        $form = '<form name="insert_category" enctype="multipart/form-data" action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=insert_category', 'NONSSL') . '" method="post">' . "\n";

        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left', 'text' => TEXT_NEW_CATEGORY_INTRO . '<br>');

        $languages = tep_get_languages();
        for ($i=0; $i<sizeof($languages); $i++) {
          $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . TEXT_CATEGORIES_NAME . ' (' . $languages[$i]['name'] . ')<br><input type="text" name="categories_name[' . $languages[$i]['id'] . ']"><br>');
        }

        $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . TEXT_CATEGORIES_IMAGE . '<br><input type="file" name="categories_image" size="20" style="font-size:10px"><br>');
        $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . TEXT_SORT_ORDER . '<br><input type="text" name="sort_order" size="2"><br>');
        $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');

        break;
/* display copy_to info box */
      case 'copy_to':
        $products_categories_array = tep_products_categories_array($pInfo->id, true);

        $form = '<form name="copy_to" action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=copy_to_confirm', 'NONSSL') . '" method="post"><input type="hidden" name="products_id" value="' . $pInfo->id . '">' . "\n";

        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left', 'text' => TEXT_INFO_COPY_TO_INTRO . '<br>&nbsp;');
        $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_INFO_CURRENT_CATEGORIES . '<br>&nbsp;' . tep_products_categories_info_box($pInfo->id));
        $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_CATEGORIES . '<br>&nbsp;' . tep_categories_pull_down('name="categories_id" style="font-size:10px"', $products_categories_array) . '<br>&nbsp;');
        $info_box_contents[] = array('align' => 'center', 'text' => tep_image_submit(DIR_WS_IMAGES . 'button_copy.gif', IMAGE_COPY) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');

        break;
/* display default info boxes */
      default:
        if ($rows > 0) {
          if ($cInfo) { // category info box contents
            $info_box_contents = array();
            $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=edit_category', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=delete_category', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=move_category', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_move.gif', IMAGE_MOVE) . '</a>');
            $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_DATE_ADDED . ' ' . tep_date_short($cInfo->date_added) . '<br>&nbsp;' . TEXT_LAST_MODIFIED . ' ' . tep_date_short($cInfo->last_modified));
            $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . tep_info_image($cInfo->image, $cInfo->name) . '<br>&nbsp;' . $cInfo->image);
            $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_SUBCATEGORIES . ' ' . $cInfo->childs_count . '<br>&nbsp;' . TEXT_PRODUCTS . ' ' . $cInfo->products_count);
          } elseif ($pInfo) { // product info box contents
            $info_box_contents = array();
            $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pinfo', 'info')) . 'action=new_product&pID=' . $pInfo->id, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=delete_product', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=move_product', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_move.gif', IMAGE_MOVE) . '</a>');
            $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_DATE_ADDED . ' ' . tep_date_short($pInfo->date_added));
            $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_LAST_MODIFIED . ' ' . tep_date_short($pInfo->last_modified));
            if (date('Y-m-d H:i:s') < $pInfo->date_available) $info_box_contents[] = array('align' => 'left', 'text' => '&nbsp;' . TEXT_DATE_AVAILABLE . ' ' . tep_date_short($pInfo->date_available) . '<br>');
            $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_PRODUCTS_STATUS . ' ' . $pInfo->status);
            $info_box_contents[] = array('align' => 'left', 'text' => '<br>' . tep_info_image($pInfo->image, $pInfo->name) . '<br>&nbsp;' . $pInfo->image);
            $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_PRODUCTS_PRICE_INFO . ' ' . tep_currency_format($pInfo->price) . '<br>&nbsp;' . TEXT_PRODUCTS_QUANTITY_INFO . ' ' . $pInfo->quantity);
            $info_box_contents[] = array('align' => 'left', 'text' => '<br>&nbsp;' . TEXT_PRODUCTS_AVERAGE_RATING . ' ' . number_format($pInfo->average_rating, 2) . '%');
            $info_box_contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=copy_to', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_copy_to.gif', IMAGE_COPY_TO) . '</a>');
          }
        } else { // create-category-or-product box contents
          $info_box_contents = array();
          $info_box_contents[] = array('align' => 'left', 'text' => sprintf(TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS, $parent_categories_name));
        }
    } // end switch
// display box contents by creating an instance of "infoBox" (down a couple of lines)
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
<?
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