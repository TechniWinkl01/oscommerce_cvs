<?php
/*
  $Id: categories.php,v 1.93 2001/12/30 03:17:27 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ($HTTP_GET_VARS['action']) {
    switch ($HTTP_GET_VARS['action']) {
      case 'setflag': // update the products status
        if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) {
          tep_set_product_status($HTTP_GET_VARS['id'], $HTTP_GET_VARS['flag']);

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
          }
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $HTTP_GET_VARS['cPath']));
        break;
      case 'insert_category':
      case 'update_category':
        $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);
        $sort_order = tep_db_prepare_input($HTTP_POST_VARS['sort_order']);

        $sql_data_array = array('sort_order' => $sort_order);

        if ($HTTP_GET_VARS['action'] == 'insert_category') {
          $insert_sql_data = array('parent_id' => $current_category_id,
                                   'date_added' => 'now()');
          $sql_data_array = tep_array_merge($sql_data_array, $insert_sql_data);
          tep_db_perform(TABLE_CATEGORIES, $sql_data_array);
          $categories_id = tep_db_insert_id();
        } elseif ($HTTP_GET_VARS['action'] == 'update_category') {
          $update_sql_data = array('last_modified' => 'now()');
          $sql_data_array = tep_array_merge($sql_data_array, $update_sql_data);
          tep_db_perform(TABLE_CATEGORIES, $sql_data_array, 'update', 'categories_id = \'' . $categories_id . '\'');
        }

        $languages = tep_get_languages();
        for ($i=0; $i<sizeof($languages); $i++) {
          $categories_name_array = $HTTP_POST_VARS['categories_name'];
          $language_id = $languages[$i]['id'];
          $sql_data_array = array('categories_name' => $categories_name_array[$language_id]);
          if ($HTTP_GET_VARS['action'] == 'insert_category') {
            $insert_sql_data = array('categories_id' => $categories_id,
                                     'language_id' => $languages[$i]['id']);
            $sql_data_array = tep_array_merge($sql_data_array, $insert_sql_data);
            tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array);
          } elseif ($HTTP_GET_VARS['action'] == 'update_category') {
            tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', 'categories_id = \'' . $categories_id . '\' and language_id = \'' . $languages[$i]['id'] . '\'');
          }
        }

        if ( ($categories_image != 'none') && ($categories_image != '') ) {
          tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '" . $categories_image_name . "' where categories_id = '" . tep_db_input($categories_id) . "'");
          $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $categories_image_name;
          copy($categories_image, $image_location);
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));
        break;
      case 'delete_category_confirm':
        if ($HTTP_POST_VARS['categories_id']) {
          $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);

          $categories = tep_get_category_tree($categories_id, '', '0', '', true);
          $products = array();
          $products_delete = array();

          for ($i=0; $i<sizeof($categories); $i++) {
            $product_ids_query = tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . $categories[$i]['id'] . "'");
            while ($product_ids = tep_db_fetch_array($product_ids_query)) {
              $products[$product_ids['products_id']]['categories'][] = $categories[$i]['id'];
            }
          }

          reset($products);
          while (list($key, $value) = each($products)) {
            $category_ids = '';
            for ($i=0; $i<sizeof($value['categories']); $i++) {
              $category_ids .= '\'' . $value['categories'][$i] . '\', ';
            }
            $category_ids = substr($category_ids, 0, -2);

            $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $key . "' and categories_id not in (" . $category_ids . ")");
            $check = tep_db_fetch_array($check_query);
            if ($check['total'] < '1') {
              $products_delete[$key] = $key;
            }
          }

          for ($i=0; $i<sizeof($categories); $i++) {
            tep_remove_category($categories[$i]['id']);
          }

          reset($products_delete);
          while (list($key) = each($products_delete)) {
            tep_remove_product($key);
          }
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
        break;
      case 'delete_product_confirm':
        if ( ($HTTP_POST_VARS['products_id']) && (is_array($HTTP_POST_VARS['product_categories'])) ) {
          $product_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
          $product_categories = $HTTP_POST_VARS['product_categories'];

          for ($i=0; $i<sizeof($product_categories); $i++) {
            tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . tep_db_input($product_id) . "' and categories_id = '" . tep_db_input($product_categories[$i]) . "'");
          }

          $product_categories_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . tep_db_input($product_id) . "'");
          $product_categories = tep_db_fetch_array($product_categories_query);

          if ($product_categories['total'] == '0') {
            tep_remove_product($product_id);
          }
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
        break;
      case 'move_category_confirm':
        if ( ($HTTP_POST_VARS['categories_id']) && ($HTTP_POST_VARS['categories_id'] != $HTTP_POST_VARS['move_to_category_id']) ) {
          $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);
          $new_parent_id = tep_db_prepare_input($HTTP_POST_VARS['move_to_category_id']);
          tep_db_query("update " . TABLE_CATEGORIES . " set parent_id = '" . tep_db_input($new_parent_id) . "', last_modified = now() where categories_id = '" . tep_db_input($categories_id) . "'");

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
          }
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&cID=' . $categories_id));
        break;
      case 'move_product_confirm':
        if ( ($HTTP_POST_VARS['products_id']) && ($HTTP_POST_VARS['products_id'] != $HTTP_POST_VARS['move_to_category_id']) ) {
          $products_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
          $new_parent_id = tep_db_prepare_input($HTTP_POST_VARS['move_to_category_id']);

          $duplicate_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . tep_db_input($products_id) . "' and categories_id = '" . tep_db_input($new_parent_id) . "'");
          $duplicate_check = tep_db_fetch_array($duplicate_check_query);
          if ($duplicate_check['total'] < 1) tep_db_query("update " . TABLE_PRODUCTS_TO_CATEGORIES . " set categories_id = '" . tep_db_input($new_parent_id) . "' where products_id = '" . tep_db_input($products_id) . "' and categories_id = '" . $current_category_id . "'");

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
          }
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&pID=' . $products_id));
        break;
      case 'insert_product':
      case 'update_product':
        $products_date_available = $HTTP_POST_VARS['year'];
        $products_date_available .= '-';
        $products_date_available .= (strlen($HTTP_POST_VARS['month']) == 1) ? '0' . $HTTP_POST_VARS['month'] : $HTTP_POST_VARS['month'];
        $products_date_available .= '-';
        $products_date_available .= (strlen($HTTP_POST_VARS['day']) == 1) ? '0' . $HTTP_POST_VARS['day'] : $HTTP_POST_VARS['day'];

        $products_date_available = (date('Y-m-d') < $products_date_available) ? $products_date_available : '';

        $sql_data_array = array('products_quantity' => $HTTP_POST_VARS['products_quantity'],
                                'products_model' => $HTTP_POST_VARS['products_model'],
                                'products_image' => $HTTP_POST_VARS['products_image'],
                                'products_price' => $HTTP_POST_VARS['products_price'],
                                'products_date_available' => $products_date_available,
                                'products_weight' => $HTTP_POST_VARS['products_weight'],
                                'products_status' => $HTTP_POST_VARS['products_status'],
                                'products_tax_class_id' => $HTTP_POST_VARS['products_tax_class_id'],
                                'manufacturers_id' => $HTTP_POST_VARS['manufacturers_id']);

        if ($HTTP_GET_VARS['action'] == 'insert_product') {
          $insert_sql_data = array('products_date_added' => 'now()');
          $sql_data_array = tep_array_merge($sql_data_array, $insert_sql_data);
          tep_db_perform(TABLE_PRODUCTS, $sql_data_array);
          $new_products_id = tep_db_insert_id();
          tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . $new_products_id . "', '" . $current_category_id . "')");
        } elseif ($HTTP_GET_VARS['action'] == 'update_product') {
          $update_sql_data = array('products_last_modified' => 'now()');
          $sql_data_array = tep_array_merge($sql_data_array, $update_sql_data);
          tep_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', 'products_id = \'' . $HTTP_GET_VARS['pID'] . '\'');
        }

        $languages = tep_get_languages();
        for ($i=0; $i<sizeof($languages); $i++) {
          $language_id = $languages[$i]['id'];

          $sql_data_array = array('products_name' => $HTTP_POST_VARS['products_name'][$language_id],
                                  'products_description' => $HTTP_POST_VARS['products_description'][$language_id],
                                  'products_url' => $HTTP_POST_VARS['products_url'][$language_id]);

          if ($HTTP_GET_VARS['action'] == 'insert_product') {
            $insert_sql_data = array('products_id' => $new_products_id,
                                     'language_id' => $language_id);
            $sql_data_array = tep_array_merge($sql_data_array, $insert_sql_data);
            tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array);
          } elseif ($HTTP_GET_VARS['action'] == 'update_product') {
            tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', 'products_id = \'' . $HTTP_GET_VARS['pID'] . '\' and language_id = \'' . $language_id . '\'');
          }
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pID')) . 'pinfo=' . $HTTP_GET_VARS['pID']));
        break;
      case 'copy_to_confirm':
        if ( (tep_not_null($HTTP_POST_VARS['products_id'])) && (tep_not_null($HTTP_POST_VARS['categories_id'])) && ($HTTP_POST_VARS['categories_id'] != $current_category_id) ) {
          $products_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
          $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);

          $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . tep_db_input($products_id) . "' and categories_id = '" . tep_db_input($categories_id) . "'");
          $check = tep_db_fetch_array($check_query);
          if ($check['total'] < '1') {
            tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . tep_db_input($products_id) . "', '" . tep_db_input($categories_id) . "')");
          }

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
          }
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $categories_id . '&pID=' . $products_id));
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
<?php
  if ($HTTP_GET_VARS['action'] == 'new_product') {
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/calendar.css">
<script language="JavaScript" src="includes/javascript/calendarcode.js"></script>
<?php
  }
?>
</head>
<body onload="SetFocus();">
<div id="popupcalendar" class="text"></div>
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
<?php
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
      <tr><form name="new_product" enctype="multipart/form-data" <?php echo 'action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pinfo', 'info')) . 'action=new_product_preview', 'NONSSL') . '"'; ?> method="post">
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="pageHeading">&nbsp;<?php echo sprintf(TEXT_NEW_PRODUCT, $parent_categories_name); ?>&nbsp;</td>
            <td align="right">&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><br><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">&nbsp;<?php echo TEXT_PRODUCTS_STATUS; ?>&nbsp;</td>
            <td class="main">&nbsp;<input type="radio" name="products_status" value="1"<?php
	if (@$pInfo->status == '1' && $product['products_status'] == '1') {  
	  echo ' CHECKED';
	} ?>>&nbsp;<?php echo TEXT_PRODUCT_AVAILABLE; ?>&nbsp;<input type="radio" name="products_status" value="0"<?php
        if (@$pInfo->status == '0' && $product['products_status'] == '0') {  
	  echo ' CHECKED';
	} ?>>&nbsp;<?php echo TEXT_PRODUCT_NOT_AVAILABLE; ?>&nbsp;</td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo TEXT_PRODUCTS_DATE_AVAILABLE; ?><br>&nbsp;<span class="smallText">(dd/mm/yyyy)</span>&nbsp;</td>
            <td class="main">&nbsp;<input class="cal-TextBox" size="2" maxlength="2" type="text" name="day" value="<?php echo $pInfo->date_available_caljs_day; ?>"><input class="cal-TextBox" size="2" maxlength="2" type="text" name="month" value="<?php echo $pInfo->date_available_caljs_month; ?>"><input class="cal-TextBox" size="4" maxlength="4" type="text" name="year" value="<?php echo $pInfo->date_available_caljs_year; ?>"><a class="so-BtnLink" href="javascript:calClick();return false;" onmouseover="calSwapImg('BTN_date', 'img_Date_OVER',true);" onmouseout="calSwapImg('BTN_date', 'img_Date_UP',true);" onclick="calSwapImg('BTN_date', 'img_Date_DOWN');showCalendar('new_product','dteWhen','BTN_date');return false;"><img align="absmiddle" border="0" name="BTN_date" src="<?php echo DIR_WS_IMAGES; ?>cal_date_up.gif" width="22" height="17"></a>&nbsp;</td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo TEXT_PRODUCTS_MANUFACTURER; ?>&nbsp;</td>
            <td class="main">&nbsp;<select name="manufacturers_id"><option value=""></option><?php while ($manufacturers = tep_db_fetch_array($manufacturers_query)) { echo '<option value="' . $manufacturers['manufacturers_id'] . '"'; if (@$pInfo->manufacturers_id == $manufacturers['manufacturers_id']) echo ' SELECTED'; echo '>' . $manufacturers['manufacturers_name'] . '</option>'; } ?></select>&nbsp;</td>
          </tr>
<?php
    $languages = tep_get_languages();
    for ($i=0; $i<sizeof($languages); $i++) {
?>
          <tr>
            <td class="main">&nbsp;<?php echo TEXT_PRODUCTS_NAME . ' (' . $languages[$i]['name'] . ')'; ?>&nbsp;</td>
            <td class="main">&nbsp;<input type="text" name="products_name[<?php echo $languages[$i]['id']; ?>]" value="<?php echo tep_get_products_name($pInfo->id, $languages[$i]['id']); ?>">&nbsp;</td>
          </tr>
<?php
    }
?>
          <tr>
            <td class="main" colspan="2">&nbsp;</td>
          </tr>
<?php
    for ($i=0; $i<sizeof($languages); $i++) {
?>
          <tr>
            <td class="main">&nbsp;<?php echo TEXT_PRODUCTS_DESCRIPTION . ' (' . $languages[$i]['name'] . ')'; ?>&nbsp;</td>
            <td class="main">&nbsp;<textarea name="products_description[<?php echo $languages[$i]['id']; ?>]" cols="50" rows="10"><?php echo tep_get_products_description($pInfo->id, $languages[$i]['id']); ?></textarea>&nbsp;</td>
          </tr>
<?php
    }
?>
          <tr>
            <td class="main" colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo TEXT_PRODUCTS_QUANTITY; ?>&nbsp;</td>
            <td class="main">&nbsp;<input type="text" name="products_quantity" value="<?php echo @$pInfo->quantity; ?>">&nbsp;</td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo TEXT_PRODUCTS_MODEL; ?>&nbsp;</td>
            <td class="main">&nbsp;<input type="text" name="products_model" value="<?php echo @$pInfo->model; ?>">&nbsp;</td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo TEXT_PRODUCTS_IMAGE; ?>&nbsp;</td>
            <td class="main">&nbsp;<input type="file" name="products_image" size="20">&nbsp;<br>&nbsp;<?php echo @$pInfo->image; ?><input type="hidden" name="products_previous_image" value="<?php echo @$pInfo->image; ?>"></td>
          </tr>
<?php
    for ($i=0; $i<sizeof($languages); $i++) {
?>
          <tr>
            <td class="main">&nbsp;<?php echo TEXT_PRODUCTS_URL . ' (' . $languages[$i]['name'] . ')'; ?>&nbsp;</td>
            <td class="main">&nbsp;<input type="text" name="products_url[<?php echo $languages[$i]['id']; ?>]" value="<?php echo tep_get_products_url($pInfo->id, $languages[$i]['id']); ?>">&nbsp;<span class="smallText"><?php echo TEXT_PRODUCTS_URL_WITHOUT_HTTP; ?></span></td>
          </tr>
<?php
    }
?>
          <tr>
            <td class="main">&nbsp;<?php echo TEXT_PRODUCTS_PRICE; ?>&nbsp;</td>
            <td class="main">&nbsp;<input type="text" name="products_price" value="<?php echo @$pInfo->price; ?>">&nbsp;</td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo TEXT_PRODUCTS_TAX_CLASS; ?>&nbsp;</td>
            <td class="main">&nbsp;<select name="products_tax_class_id"><option value="0">None Selected</option><?php while ($tax_class = tep_db_fetch_array($tax_class_query)) { echo '<option value="' . $tax_class['tax_class_id'] . '"'; if (@$pInfo->tax_class == $tax_class['tax_class_id']) echo ' SELECTED'; echo '>' . $tax_class['tax_class_title'] . '</option>'; } ?></select>&nbsp;</td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo TEXT_PRODUCTS_WEIGHT; ?>&nbsp;</td>
            <td class="main">&nbsp;<input type="text" name="products_weight" value="<?php echo @$pInfo->weight; ?>">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><?php echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td class="main" align="right"><br>&nbsp;<input type="hidden" name="products_date_added" value="<?php if (@$pInfo->date_added) { echo $pInfo->date_added; } else { echo date('Y-m-d'); } ?>"><?php echo tep_image_submit(DIR_WS_IMAGES . 'button_preview.gif', IMAGE_PREVIEW); ?>&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pID', 'pinfo', 'info')) . 'pinfo=' . $HTTP_GET_VARS['pID'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?>&nbsp;</td>
      </form></tr>
<?php
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
      } else {
        $products_image_name = $products_previous_image;
      }

    } else {
      $product_query = tep_db_query("select p.products_id, pd.language_id, pd.products_name, pd.products_description, pd.products_url, p.products_quantity, p.products_model, p.products_image, p.products_price, p.products_weight, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.manufacturers_id, m.manufacturers_name, m.manufacturers_image from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id where p.products_id = pd.products_id and pd.products_id = '" . $HTTP_GET_VARS['pID'] . "'");
      $product = tep_db_fetch_array($product_query);

      $pInfo = new productInfo($product);
      $products_image_name = $pInfo->image;
    }

    $form_action = 'insert_product';
    if ($HTTP_GET_VARS['pID']) $form_action = 'update_product';
?>
      <form name="<?php echo $form_action; ?>" enctype="multipart/form-data" <?php echo 'action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=' . $form_action, 'NONSSL') . '"'; ?> method="post">
<?php
    if ($HTTP_GET_VARS['read'] == 'only') {
      $languages = tep_get_languages();
      for ($i=0; $i<sizeof($languages); $i++) {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="pageHeading">&nbsp;<?php echo tep_get_products_name($pInfo->id, $languages[$i]['id']) . ' (' . $languages[$i]['name'] . ')<br>&nbsp;@ ' . tep_currency_format($pInfo->price); ?>&nbsp;</td>
            <td align="right"><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->manufacturers_image, $pInfo->manufacturer, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td class="main"><br>
<?php
        echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_name, tep_get_products_name($pInfo->id, $languages[$i]['id']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"') .
             ' (' . $languages[$i]['name'] . ')<br>' . tep_get_products_description($pInfo->id, $languages[$i]['id']) . '<br><br>';
?></td>
      </tr>
<?php
        if ($pInfo->url) {
?>
      <tr>
        <td class="main"><br><?php echo sprintf(TEXT_PRODUCT_MORE_INFORMATION, tep_get_products_url($pInfo->id, $languages[$i]['id'])) . ' (' . $languages[$i]['name'] . ')<br>'; ?></td>
      </tr>
<?php
        }

        if ($pInfo->date_available > date('Y-m-d')) {
?>
      <tr>
        <td align="center" class="smallText"><br><?php echo sprintf(TEXT_PRODUCT_DATE_AVAILABLE, tep_date_long($pInfo->date_available)); ?></td>
      </tr>
<?php
        } else {
?>
      <tr>
        <td align="center" class="smallText"><br><?php echo sprintf(TEXT_PRODUCT_DATE_ADDED, tep_date_long($pInfo->date_added)); ?></td>
      </tr>
<?php
        }
?>
      <tr>
        <td><br><?php echo tep_black_line(); ?></td>
      </tr>
<?php
      }
    } else {
      $languages = tep_get_languages();
      for ($i=0; $i<sizeof($languages); $i++) {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="pageHeading">&nbsp;<?php echo $products_name[$languages[$i]['id']] . ' (' . $languages[$i]['name'] . ')<br>@ ' . tep_currency_format($pInfo->price); ?>&nbsp;</td>
            <td align="right"><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->manufacturers_image, $pInfo->manufacturer, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td class="main"><br><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_name, $products_name[$languages[$i]['id']], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"') . ' (' . $languages[$i]['name'] . ')<br>' . $products_description[$languages[$i]['id']]; ?></td>
      </tr>
<?php
        if ($pInfo->url) {
?>
      <tr>
        <td class="main"><br><?php echo sprintf(TEXT_PRODUCT_MORE_INFORMATION, $products_url[$languages[$i]['id']]) . ' (' . $languages[$i]['name'] . ')'; ?></td>
      </tr>
<?php
        }

        if ($pInfo->date_available > date('Y-m-d')) {
?>
      <tr>
        <td align="center" class="smallText"><br><?php echo sprintf(TEXT_PRODUCT_DATE_AVAILABLE, tep_date_long($pInfo->date_available)); ?></td>
      </tr>
<?php
        } else {
?>
      <tr>
        <td align="center" class="smallText"><br><?php echo sprintf(TEXT_PRODUCT_DATE_ADDED, tep_date_long($pInfo->date_added)); ?></td>
      </tr>
<?php
        }
?>
      <tr>
        <td><br><?php echo tep_black_line(); ?></td>
      </tr>
<?php
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
        <td align="right" class="smallText"><br><?php echo '<a href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_back.gif', IMAGE_BACK) . '</a>'; ?>&nbsp;</td>
      </tr>
<?php
    } else {
?>
      <tr>
        <td align="right" class="smallText"><br>
<?php
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
<?php
    }
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr><?php echo tep_draw_form('search', FILENAME_CATEGORIES, '', 'get'); ?>
                <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . tep_draw_input_field('search', $HTTP_GET_VARS['search']); ?></td>
              </form></tr>
              <tr><?php echo tep_draw_form('goto', FILENAME_CATEGORIES, '', 'get'); ?>
                <td class="smallText" align="right"><?php echo tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"'); ?></td>
              </form></tr>            
            </table></td>
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
                <td class="tableHeading"><?php echo TABLE_HEADING_CATEGORIES_PRODUCTS; ?></td>
                <td class="tableHeading" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="tableHeading" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3"><?php echo tep_draw_separator(); ?></td>
              </tr>
<?php
    $categories_count = 0;
    $rows = 0;
    if ($HTTP_GET_VARS['search']) {
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' and cd.categories_name like '%" . $HTTP_GET_VARS['search'] . "%' order by c.sort_order, cd.categories_name");
    } else {
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . $current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by c.sort_order, cd.categories_name");
    }
    while ($categories = tep_db_fetch_array($categories_query)) {
      $categories_count++;
      $rows++;

      if ( ((!$HTTP_GET_VARS['cID']) && (!$HTTP_GET_VARS['pID']) || (@$HTTP_GET_VARS['cID'] == $categories['categories_id'])) && (!$cInfo) && (substr($HTTP_GET_VARS['action'], 0, 4) != 'new_') ) {
        $category_childs = array('childs_count' => tep_childs_in_category_count($categories['categories_id']));
        $category_products = array('products_count' => tep_products_in_category_count($categories['categories_id']));

        $cInfo_array = tep_array_merge($categories, $category_childs, $category_products);
        $cInfo = new objectInfo($cInfo_array);
      }

      if ( (is_object($cInfo)) && ($categories['categories_id'] == $cInfo->categories_id) ) {
        echo '              <tr class="selectedRow" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '\'">' . "\n";
      } else {
        echo '              <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '\'">' . "\n";
      }
?>
                <td class="tableData"><b><?php echo $categories['categories_name']; ?></b></td>
                <td class="tableData" align="center"><?php echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', 10, 10); ?></td>
                <td class="tableData" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '">' . tep_image(DIR_WS_IMAGES . 'icon_folder.gif', 'Enter') . '</a>&nbsp;'; if ( (is_object($cInfo)) && ($categories['categories_id'] == $cInfo->categories_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cID=' . $categories['categories_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }

    $products_count = 0;
    if ($HTTP_GET_VARS['search']) {
  	  $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_id = p2c.products_id and pd.products_name like '%" . $HTTP_GET_VARS['search'] . "%' order by pd.products_name");
    } else {
      $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . $current_category_id . "' order by pd.products_name");
    }
    while ($products = tep_db_fetch_array($products_query)) {
      $products_count++;
      $rows++;

      if ( ((!$HTTP_GET_VARS['pID']) && (!$HTTP_GET_VARS['cID']) || (@$HTTP_GET_VARS['pID'] == $products['products_id'])) && (!$pInfo) && (!$cInfo) && (substr($HTTP_GET_VARS['action'], 0, 4) != 'new_') ) {
// find out the rating average from customer reviews
        $reviews_query = tep_db_query("select (avg(reviews_rating) / 5 * 100) as average_rating from " . TABLE_REVIEWS . " where products_id = '" . $products['products_id'] . "'");
        $reviews = tep_db_fetch_array($reviews_query);
        $pInfo_array = tep_array_merge($products, $reviews);
        $pInfo = new productInfo($pInfo_array);
      }

      if ( (is_object($pInfo)) && ($products['products_id'] == $pInfo->id) ) {
        echo '              <tr class="selectedRow" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action', 'pID')) . 'pID=' . $products['products_id'] . '&action=new_product_preview&read=only') . '\'">' . "\n";
      } else {
        echo '              <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '\'">' . "\n";
      }
?>
                <td class="tableData"><?php echo $products['products_name']; ?></td>
                <td class="tableData" align="center">
<?php
      if ($products['products_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=0&id=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=1&id=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?></td>
                <td class="tableData" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview&read=only') . '">' . tep_image(DIR_WS_IMAGES . 'icon_preview.gif', 'Preview') . '</a>&nbsp;'; if ( (is_object($pInfo)) && ($products['products_id'] == $pInfo->id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }

    if ($rows > 0) {
?>
              <tr>
                <td colspan="3"><?php echo tep_draw_separator(); ?></td>
              </tr>
<?php
    }

    $cPath_back = ($cPath_back) ? 'cPath=' . $cPath_back : '';

?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText"><?php echo TEXT_CATEGORIES . '&nbsp;' . $categories_count . '<br>' . TEXT_PRODUCTS . '&nbsp;' . $products_count; ?></td>
                    <td align="right" class="smallText"><?php if ($cPath) echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, $cPath_back) . '">' . tep_image(DIR_WS_IMAGES . 'button_back.gif', IMAGE_BACK) . '</a>&nbsp;'; if (!$HTTP_GET_VARS['search']) echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_category') . '">' . tep_image(DIR_WS_IMAGES . 'button_new_category.gif', IMAGE_NEW_CATEGORY) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_product') . '">' . tep_image(DIR_WS_IMAGES . 'button_new_product.gif', IMAGE_NEW_PRODUCT) . '</a>'; ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="25%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
    $info_box_contents = array();
    if ( (is_object($cInfo)) && (!$pInfo) ) $info_box_contents[] = array('text' => '<b>' . $cInfo->categories_name . '</b>');
    if ( (is_object($pInfo)) && (!$cInfo) ) $info_box_contents[] = array('text' => '<b>' . tep_get_products_name($pInfo->id, $languages_id) . '</b>');
    if ( (!$pInfo) && (!$cInfo) ) $info_box_contents[] = array('text' => '<b>' . EMPTY_CATEGORY . '</b>');
?>
              <tr class="boxHeading">
                <td><?php new infoBoxHeading($info_box_contents); ?></td>
              </tr>
              <tr class="boxHeading">
                <td><?php echo tep_draw_separator(); ?></td>
              </tr>
<?php
    switch ($HTTP_GET_VARS['action']) {
      case 'new_category':
        $info_box_contents = array('form' => tep_draw_form('newcategory', FILENAME_CATEGORIES, 'action=insert_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"'));
        $info_box_contents[] = array('text' => TEXT_NEW_CATEGORY_INTRO);

        $category_inputs_string = '';
        $languages = tep_get_languages();
        for ($i=0; $i<sizeof($languages); $i++) {
          $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']');
        }

        $info_box_contents[] = array('text' => '<br>' . TEXT_CATEGORIES_NAME . $category_inputs_string);
        $info_box_contents[] = array('text' => '<br>' . TEXT_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
        $info_box_contents[] = array('text' => '<br>' . TEXT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', '', 'size="2"'));
        $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'edit_category':
        $info_box_contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=update_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"') . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
        $info_box_contents[] = array('text' => TEXT_EDIT_INTRO);

        $category_inputs_string = '';
        $languages = tep_get_languages();
        for ($i=0; $i<sizeof($languages); $i++) {
          $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', tep_get_category_name($cInfo->categories_id, $languages[$i]['id']));
        }

        $info_box_contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_NAME . $category_inputs_string);
        $info_box_contents[] = array('text' => '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $cInfo->categories_image, $cInfo->categories_name) . '<br>' . DIR_WS_CATALOG_IMAGES . '<br><b>' . $cInfo->categories_image . '</b>');
        $info_box_contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
        $info_box_contents[] = array('text' => '<br>' . TEXT_EDIT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', $cInfo->sort_order, 'size="2"'));
        $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'delete_category':
        $info_box_contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=delete_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
        $info_box_contents[] = array('text' => TEXT_DELETE_CATEGORY_INTRO);
        $info_box_contents[] = array('text' => '<br><b>' . $cInfo->categories_name . '</b>');
        if ($cInfo->childs_count > 0) $info_box_contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count));
        if ($cInfo->products_count > 0) $info_box_contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count));
        $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'move_category':
        $info_box_contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=move_category_confirm') . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
        $info_box_contents[] = array('text' => sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->categories_name));
        $info_box_contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $cInfo->categories_name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree('0', '', $cInfo->categories_id), $current_category_id));
        $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'delete_product':
        $info_box_contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=delete_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->id));
        $info_box_contents[] = array('text' => TEXT_DELETE_PRODUCT_INTRO);
        $info_box_contents[] = array('text' => '<br><b>' . $pInfo->name . '</b>');

        $product_categories_string = '';
        $product_categories = tep_generate_category_path($pInfo->id, 'product');
        for ($i=0; $i<sizeof($product_categories); $i++) {
          $category_path = '';
          for ($j=0; $j<sizeof($product_categories[$i]); $j++) {
            $category_path .= $product_categories[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
          }
          $category_path = substr($category_path, 0, -16);
          $product_categories_string .= tep_draw_checkbox_field('product_categories[]', $product_categories[$i][sizeof($product_categories[$i])-1]['id'], true) . '&nbsp;' . $category_path . '<br>';
        }
        $product_categories_string = substr($product_categories_string, 0, -4);

        $info_box_contents[] = array('text' => '<br>' . $product_categories_string);
        $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->id) . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'move_product':
        $info_box_contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=move_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->id));
        $info_box_contents[] = array('text' => sprintf(TEXT_MOVE_PRODUCTS_INTRO, $pInfo->name));
        $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . tep_output_generated_category_path($pInfo->id) . '</b>');
        $info_box_contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $pInfo->name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id));
        $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->id) . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'copy_to':
        $info_box_contents = array('form' => tep_draw_form('copy_to', FILENAME_CATEGORIES, 'action=copy_to_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->id));
        $info_box_contents[] = array('text' => TEXT_INFO_COPY_TO_INTRO);
        $info_box_contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . tep_output_generated_category_path($pInfo->id) . '</b>');
        $info_box_contents[] = array('text' => TEXT_CATEGORIES . '<br>' . tep_draw_pull_down_menu('categories_id', tep_get_category_tree(), $current_category_id));
        $info_box_contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit(DIR_WS_IMAGES . 'button_copy.gif', IMAGE_COPY) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->id) . '">' . tep_image(DIR_WS_IMAGES . 'button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      default:
        $info_box_contents = array();
        if ($rows > 0) {
          if (is_object($cInfo)) { // category info box contents
            $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=edit_category') . '">' . tep_image(DIR_WS_IMAGES . 'button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=delete_category') . '">' . tep_image(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=move_category') . '">' . tep_image(DIR_WS_IMAGES . 'button_move.gif', IMAGE_MOVE) . '</a>');
            $info_box_contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($cInfo->date_added) . '<br>' . TEXT_LAST_MODIFIED . ' ' . tep_date_short($cInfo->last_modified));
            $info_box_contents[] = array('text' => '<br>' . tep_info_image($cInfo->categories_image, $cInfo->categories_name) . '<br>' . $cInfo->categories_image);
            $info_box_contents[] = array('text' => '<br>' . TEXT_SUBCATEGORIES . ' ' . $cInfo->childs_count . '<br>' . TEXT_PRODUCTS . ' ' . $cInfo->products_count);
          } elseif (is_object($pInfo)) { // product info box contents
            $info_box_contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->id . '&action=new_product') . '">' . tep_image(DIR_WS_IMAGES . 'button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->id . '&action=delete_product') . '">' . tep_image(DIR_WS_IMAGES . 'button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->id . '&action=move_product') . '">' . tep_image(DIR_WS_IMAGES . 'button_move.gif', IMAGE_MOVE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->id . '&action=copy_to') . '">' . tep_image(DIR_WS_IMAGES . 'button_copy_to.gif', IMAGE_COPY_TO) . '</a>');
            $info_box_contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($pInfo->date_added) . '<br>' . TEXT_LAST_MODIFIED . ' ' . tep_date_short($pInfo->last_modified));
            if (date('Y-m-d') < $pInfo->date_available) $info_box_contents[] = array('text' => TEXT_DATE_AVAILABLE . ' ' . tep_date_short($pInfo->date_available));
            $info_box_contents[] = array('text' => '<br>' . tep_info_image($pInfo->image, $pInfo->name) . '<br>' . $pInfo->image);
            $info_box_contents[] = array('text' => '<br>' . TEXT_PRODUCTS_PRICE_INFO . ' ' . tep_currency_format($pInfo->price) . '<br>' . TEXT_PRODUCTS_QUANTITY_INFO . ' ' . $pInfo->quantity);
            $info_box_contents[] = array('text' => '<br>' . TEXT_PRODUCTS_AVERAGE_RATING . ' ' . number_format($pInfo->average_rating, 2) . '%');
          }
        } else { // create category/product info
          $info_box_contents[] = array('text' => sprintf(TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS, $parent_categories_name));
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
<?php
  }
?>
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
