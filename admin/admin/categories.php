<?
/*
TODO
   + retrieve total count of products in current category (through child categories)
   + error handling improvement
   + code optimizations
   + products status code alterations
   + update tep_get_all_get_params function, to accept an array of exclusions (and not $exclude1 -> $exclude4)
   + more options when deleting products or categories
   + more expert options
*/
?>
<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_CATEGORIES; include(DIR_INCLUDES . 'include_once.php'); ?>
<?
  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'save') {
      if (EXPERT_MODE) {
        $update_query .= "categories_id = '" . $HTTP_POST_VARS['categories_id'] . "', categories_name = '" . $HTTP_POST_VARS['categories_name'] . "', categories_image = '" . $HTTP_POST_VARS['categories_image'] . "', parent_id = '" . $HTTP_POST_VARS['parent_id'] . "', sort_order = '" . $HTTP_POST_VARS['sort_order'] . "'";
        $new_categories_id = $HTTP_POST_VARS['categories_id'];
      } else {
        $update_query .= "categories_name = '" . $HTTP_POST_VARS['categories_name'] . "', categories_image = '" . $HTTP_POST_VARS['categories_image'] . "', sort_order = '" . $HTTP_POST_VARS['sort_order'] . "'";
        $new_categories_id = $HTTP_POST_VARS['original_categories_id'];
      }
      if (tep_db_query("update categories set " . $update_query . " where categories_id = '" . $HTTP_POST_VARS['original_categories_id'] . "'")) {
        header('Location: ' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action', 'info') . 'info=' . $new_categories_id, 'NONSSL'));
        tep_exit();
      } else {
        header('Location: ' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action', 'info') . 'error=SAVE', 'NONSSL'));
        tep_exit();
      }
    } elseif ($HTTP_GET_VARS['action'] == 'deleteconfirm') {
      if ($HTTP_POST_VARS['categories_id']) {
        if (tep_db_query("delete from categories where categories_id = '" . $HTTP_POST_VARS['categories_id'] . "'")) {
          header('Location: ' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action', 'info'), 'NONSSL'));
          tep_exit();
        } else {
          header('Location: ' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action', 'info') . 'error=DELETE', 'NONSSL'));
          tep_exit();
        }
      } elseif ($HTTP_POST_VARS['products_id']) {
        tep_db_query("update products set products_status = '0' where products_id = '" . $HTTP_POST_VARS['products_id'] . "'");
        header('Location: ' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action', 'info'), 'NONSSL'));
        tep_exit();
      }
    } elseif ($HTTP_GET_VARS['action'] == 'moveconfirm') {
      if ($HTTP_POST_VARS['categories_id']) {
        if (tep_db_query("update categories set parent_id = '" . $HTTP_POST_VARS['move_to_category_id'] . "' where categories_id = '" . $HTTP_POST_VARS['categories_id'] . "'")) {
          header('Location: ' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action', 'info'), 'NONSSL'));
          tep_exit();
        } else {
          header('Location: ' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action', 'info') . 'error=MOVE', 'NONSSL'));
          tep_exit();
        }
      } elseif ($HTTP_POST_VARS['products_id']) {
        tep_db_query("update products_to_categories set categories_id = '" . $HTTP_POST_VARS['move_to_category_id'] . "' where products_id = '" . $HTTP_POST_VARS['products_id'] . "'");
        header('Location: ' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action', 'info'), 'NONSSL'));
        tep_exit();
      }
    } elseif ($HTTP_GET_VARS['action'] == 'insert_category') {
      if (tep_db_query("insert into categories (categories_name, categories_image, parent_id, sort_order) values ('" . $HTTP_POST_VARS['categories_name'] . "', '" . $HTTP_POST_VARS['categories_image'] . "', '" . $current_category_id . "', '" . $HTTP_POST_VARS['sort_order'] . "')")) {
        header('Location: ' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action', 'info'), 'NONSSL'));
        tep_exit();
      } else {
        header('Location: ' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action', 'info') . 'error=INSERT', 'NONSSL'));
        tep_exit();
      }
    } elseif ($HTTP_GET_VARS['action'] == 'insert_product') {
      if (tep_db_query("insert into products (products_name, products_description, products_quantity, products_model, products_image, products_url, products_price, products_date_added, products_weight, products_status) values ('" . $HTTP_POST_VARS['products_name'] . "', '" . $HTTP_POST_VARS['products_description'] . "', '" . $HTTP_POST_VARS['products_quantity'] . "', '" . $HTTP_POST_VARS['products_model'] . "', '" . $HTTP_POST_VARS['products_image'] . "', '" . $HTTP_POST_VARS['products_url'] . "', '" . $HTTP_POST_VARS['products_price'] . "', '" . $HTTP_POST_VARS['products_date_added'] . "', '" . $HTTP_POST_VARS['products_weight'] . "', '1')")) {
        $new_products_id = tep_db_insert_id();
        tep_db_query("insert into products_to_categories (products_id, categories_id) values ('" . $new_products_id . "', '" . $current_category_id . "')");
        if ($HTTP_POST_VARS['manufacturers_id']) tep_db_query("insert into products_to_manufacturers (products_id, manufacturers_id) values ('" . $new_products_id . "', '" . $HTTP_POST_VARS['manufacturers_id'] . "')");
        header('Location: ' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action', 'info'), 'NONSSL'));
        tep_exit();
      } else {
        header('Location: ' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action', 'info') . 'error=INSERT', 'NONSSL'));
        tep_exit();
      }
    } elseif ($HTTP_GET_VARS['action'] == 'update_product') {
      tep_db_query("update products set products_name = '" . $HTTP_POST_VARS['products_name'] . "', products_description = '" . $HTTP_POST_VARS['products_description'] . "', products_quantity = '" . $HTTP_POST_VARS['products_quantity'] . "', products_model = '" . $HTTP_POST_VARS['products_model'] . "', products_image = '" . $HTTP_POST_VARS['products_image'] . "', products_url = '" . $HTTP_POST_VARS['products_url'] . "', products_price = '" . $HTTP_POST_VARS['products_price'] . "', products_weight = '" . $HTTP_POST_VARS['products_weight'] . "' where products_id = '" . $HTTP_GET_VARS['pID'] . "'");
      if ($HTTP_POST_VARS['manufacturers_id']) {
        $check_manufacturer_query = tep_db_query("select count(*) as total from products_to_manufacturers where products_id = '" . $HTTP_GET_VARS['pID'] . "'");
        $check_manufacturer = tep_db_fetch_array($check_manufacturer_query);
        if ($check_manufacturer['total'] > 0) {
          tep_db_query("update products_to_manufacturers set manufacturers_id = '" . $HTTP_POST_VARS['manufacturers_id'] . "' where products_id = '" . $HTTP_GET_VARS['pID'] . "'");
        } else {
          tep_db_query("insert into products_to_manufacturers (products_id, manufacturers_id) values ('" . $HTTP_GET_VARS['pID'] . "', '" . $HTTP_POST_VARS['manufacturers_id'] . "')");
        }
      } else {
        tep_db_query("delete from products_to_manufacturers where products_id = '" . $HTTP_GET_VARS['pID'] . "'");
      }
      header('Location: ' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action', 'pID'), 'NONSSL'));
      tep_exit();
    }
  }

  class Category_Info {
    var $id, $name, $image, $sort_order, $parent_id, $childs_count, $products_count;
  }

  class Product_Info {
    var $id, $name, $image, $description, $quantity, $model, $url, $price, $date_added, $weight, $manufacturer, $manufacturers_id, $manufacturers_image;
  }
?>
<html>
<head>
<title><?=TITLE;?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript"><!--
function checkForm() {
  var error_message = "<?=JS_ERROR;?>";
  var error = 0;
  var categories_name = document.categories.categories_name.value;
  var sort_order = document.categories.sort_order.value;
  var categories_image = document.categories.categories_image.value;
  
  if (categories_name.length < 1) {
    error_message = error_message + "<?=JS_CATEGORIES_NAME;?>";
    error = 1;
  }
  
  if (sort_order = "" || sort_order.length < 1) {
    error_message = error_message + "<?=JS_SORT_ORDER;?>";
    error = 1;
  }
  
  if (categories_image.length < 1) {
    error_message = error_message + "<?=JS_CATEGORIES_IMAGE;?>";
    error = 1;
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_INCLUDES . 'header.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<?=BOX_WIDTH;?>" valign="top"><table border="0" width="<?=BOX_WIDTH;?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_INCLUDES . 'column_left.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
            <td bgcolor="<?=TOP_BAR_BACKGROUND_COLOR;?>" width="100%" nowrap><font face="<?=TOP_BAR_FONT_FACE;?>" size="<?=TOP_BAR_FONT_SIZE;?>" color="<?=TOP_BAR_FONT_COLOR;?>">&nbsp;<?='<a href="' . tep_href_link(FILENAME_CATEGORIES, '', 'NONSSL') . '" class="blacklink">' . TOP_BAR_TITLE . '</a>';?>
<?
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
      $categories_query = tep_db_query("select categories_name from categories where categories_id = '" . $cPath_array[$i] . "'");
      $categories = tep_db_fetch_array($categories_query);
      $parent_categories_name = $categories['categories_name'];
      echo ' -> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath_new, 'NONSSL') . '" class="blacklink">' . $parent_categories_name . '</a>';
    }
  }
?>
            &nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
<?
  if ($HTTP_GET_VARS['action'] == 'new_product') {
    if ($HTTP_GET_VARS['pID']) {
      $product_query = tep_db_query("select products_name, products_description, products_quantity, products_model, products_image, products_url, products_price, products_weight, products_date_added from products where products_id = '" . $HTTP_GET_VARS['pID'] . "'");
      $product = tep_db_fetch_array($product_query);
      $manufacturer_query = tep_db_query("select manufacturers_id from products_to_manufacturers where products_id = '" . $HTTP_GET_VARS['pID'] . "'");
      $manufacturer = tep_db_fetch_array($manufacturer_query);

      $pInfo->name = $product['products_name'];
      $pInfo->description = stripslashes($product['products_description']);
      $pInfo->quantity = $product['products_quantity'];
      $pInfo->model = $product['products_model'];
      $pInfo->image = $product['products_image'];
      $pInfo->url = $product['products_url'];
      $pInfo->price = $product['products_price'];
      $pInfo->weight = $product['products_weight'];
      $pInfo->date_added = $product['products_date_added'];
      $pInfo->manufacturers_id = $manufacturer['manufacturers_id'];
    } elseif ($HTTP_POST_VARS) {
      $pInfo->name = $HTTP_POST_VARS['products_name'];
      $pInfo->description = stripslashes($HTTP_POST_VARS['products_description']);
      $pInfo->quantity = $HTTP_POST_VARS['products_quantity'];
      $pInfo->model = $HTTP_POST_VARS['products_model'];
      $pInfo->image = $HTTP_POST_VARS['products_image'];
      $pInfo->url = $HTTP_POST_VARS['products_url'];
      $pInfo->price = $HTTP_POST_VARS['products_price'];
      $pInfo->weight = $HTTP_POST_VARS['products_weight'];
      $pInfo->date_added = $HTTP_POST_VARS['products_date_added'];
      $pInfo->manufacturers_id = $HTTP_POST_VARS['manufacturers_id'];
    }

    $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from manufacturers order by manufacturers_name");

    if ($parent_categories_name == '') $parent_categories_name = 'Top Level Categories';
?>
      <tr><form name="new_product" <?='action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action', 'info') . 'action=new_product_preview', 'NONSSL') . '"';?> method="post">
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=sprintf(TEXT_NEW_PRODUCT, $parent_categories_name);?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_CATALOG . 'images/pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', '');?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td><br><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_PRODUCTS_MANUFACTURER;?>&nbsp;</font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<select name="manufacturers_id"><option value=""></option><? while ($manufacturers = tep_db_fetch_array($manufacturers_query)) { echo '<option value="' . $manufacturers['manufacturers_id'] . '"'; if ($pInfo->manufacturers_id == $manufacturers['manufacturers_id']) echo ' SELECTED'; echo '>' . $manufacturers['manufacturers_name'] . '</option>'; } ?></select>&nbsp;</font></td>
          </tr>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_PRODUCTS_NAME;?>&nbsp;</font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="products_name" value="<?=$pInfo->name;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td colspan="2"><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td nowrap valign="top"><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_PRODUCTS_DESCRIPTION;?>&nbsp;</font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<textarea name="products_description" cols="50" rows="10"><?=$pInfo->description;?></textarea>&nbsp;</font></td>
          </tr>
          <tr>
            <td colspan="2"><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_PRODUCTS_QUANTITY;?>&nbsp;</font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="products_quantity" value="<?=$pInfo->quantity;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_PRODUCTS_MODEL;?>&nbsp;</font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="products_model" value="<?=$pInfo->model;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_PRODUCTS_IMAGE;?>&nbsp;</font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="products_image" value="<? if ($pInfo->image) { echo $pInfo->image; } else { echo 'images/'; } ;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_PRODUCTS_URL;?>&nbsp;</font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="products_url" value="<?=$pInfo->url;?>">&nbsp;<?=TEXT_PRODUCTS_URL_WITHOUT_HTTP;?>&nbsp;</font></td>
          </tr>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_PRODUCTS_PRICE;?>&nbsp;</font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="products_price" value="<?=$pInfo->price;?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_PRODUCTS_WEIGHT;?>&nbsp;</font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="products_weight" value="<?=$pInfo->weight;?>">&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td nowrap align="right"><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="hidden" name="products_date_added" value="<? if ($pInfo->date_added) { echo $pInfo->date_added; } else { echo date('Ymd'); } ?>"><?=tep_image_submit(DIR_IMAGES . 'button_preview.gif', '66', '20', '0', IMAGE_PREVIEW);?>&nbsp;<?='<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action', 'pID', 'info') . 'info=' . $HTTP_GET_VARS['pID'], 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>';?>&nbsp;</font></td>
      </tr>
<?
  } elseif ($HTTP_GET_VARS['action'] == 'new_product_preview') {
    $pInfo = new Product_Info();

    if ($HTTP_POST_VARS) {
      $manufacturer_query = tep_db_query("select manufacturers_name, manufacturers_image from manufacturers where manufacturers_id = '" . $HTTP_POST_VARS['manufacturers_id'] . "'");
      $manufacturer = tep_db_fetch_array($manufacturer_query);

      $pInfo->name = tep_products_name('', $HTTP_POST_VARS['manufacturers_id'], $HTTP_POST_VARS['products_name']);
      $pInfo->description = stripslashes($HTTP_POST_VARS['products_description']);
      $pInfo->image = DIR_CATALOG . $HTTP_POST_VARS['products_image'];
      $pInfo->price = tep_currency_format($HTTP_POST_VARS['products_price']);
      $pInfo->date_added = tep_date_long($HTTP_POST_VARS['products_date_added']);
      $pInfo->url = $HTTP_POST_VARS['products_url'];
      $pInfo->manufacturer = $manufacturer['manufacturers_name'];
      $pInfo->manufacturers_image = $manufacturer['manufacturers_image'];
    } else {
      $product_query = tep_db_query("select products_name, products_description, products_quantity, products_model, products_image, products_url, products_price, products_weight, products_date_added from products where products_id = '" . $HTTP_GET_VARS['pID'] . "'");
      $product = tep_db_fetch_array($product_query);
      $manufacturer_query = tep_db_query("select m.manufacturers_name, m.manufacturers_image from manufacturers m, products_to_manufacturers p2m where p2m.products_id = '" . $HTTP_GET_VARS['pID'] . "' and p2m.manufacturers_id = m.manufacturers_id");
      $manufacturer = tep_db_fetch_array($manufacturer_query);

      $pInfo->name = $product['products_name'];
      $pInfo->description = stripslashes($product['products_description']);
      $pInfo->image = DIR_CATALOG . $product['products_image'];
      $pInfo->price = tep_currency_format($product['products_price']);
      $pInfo->date_added = tep_date_long($product['products_date_added']);
      $pInfo->url = $product['products_url'];
      $pInfo->manufacturer = $manufacturer['manufacturers_name'];
      $pInfo->manufacturers_image = $manufacturer['manufacturers_image'];
    }

    $form_action = 'insert_product';
    if ($HTTP_GET_VARS['pID']) $form_action = 'update_product';
?>
      <tr><form name="<?=$form_action;?>" <?='action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action') . 'action=' . $form_action, 'NONSSL') . '"';?> method="post">
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=$pInfo->name . ' @ ' . $pInfo->price;?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_CATALOG . $pInfo->manufacturers_image, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', $pInfo->manufacturer);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td wrap><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><?=tep_image($pInfo->image, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, '0' . '" align="right" hspace="5" vspace="5', $pInfo->name);?><?=$pInfo->description;?></font></td>
      </tr>
<?
    if ($pInfo->url) {
?>
      <tr>
        <td nowrap><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><?=sprintf(TEXT_PRODUCT_MORE_INFORMATION, $pInfo->url);?></font></td>
      </tr>
<?
    }
?>
      <tr>
        <td align="center" nowrap><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=sprintf(TEXT_PRODUCT_DATE_ADDED, $pInfo->date_added);?></font></td>
      </tr>
      <tr>
        <td><br><?=tep_black_line();?></td>
      </tr>
<?
    if ($HTTP_GET_VARS['read'] == 'only') {
?>
      <tr>
        <td align="right" nowrap><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?='<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action', 'pID', 'read', 'info') . 'info=' . $HTTP_GET_VARS['pID'], 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_back.gif', '66', '20', '0', IMAGE_BACK) . '</a>';?>&nbsp;</font></td>
      </tr>
<?
    } else {
?>
      <tr>
        <td align="right" nowrap><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">
<?
/* Re-Post all POST'ed variables */
      foreach($HTTP_POST_VARS as $key => $value) echo '<input type="hidden" name="' . $key . '" value="' . htmlspecialchars($value) . '">';
?>
        <?='<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action') . 'action=new_product', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_back.gif', '66', '20', '0', IMAGE_BACK) . '</a>';?>&nbsp;<? if ($HTTP_GET_VARS['pID']) { echo tep_image_submit(DIR_IMAGES . 'button_update.gif', '66', '20', '0', IMAGE_UPDATE); } else { echo tep_image_submit(DIR_IMAGES . 'button_insert.gif', '66', '20', '0', IMAGE_INSERT); } ?>&nbsp;<?='<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action', 'pID') . 'info=' . $HTTP_GET_VARS['pID'], 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>';?>&nbsp;</font></td>
      </tr>
<?
    }
?>
      </form>
<?
  } else {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_IMAGES . 'pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', '');?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10%" align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_ID;?>&nbsp;</b></font></td>
                <td width="80%" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_CATEGORIES_PRODUCTS;?>&nbsp;</b></font></td>
                <td width="10%" align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_ACTION;?>&nbsp;</b></font></td>
              </tr>
              <tr>
                <td colspan="3"><?=tep_black_line();?></td>
              </tr>
<?
    $categories_count = 0;
    $rows = 0;
    $categories_query = tep_db_query("select categories_id, categories_name, categories_image, parent_id, sort_order from categories where parent_id = '" . $current_category_id . "' order by sort_order, categories_name");
    while ($categories = tep_db_fetch_array($categories_query)) {
      $categories_count++;
      $rows++;

      if ( ((!$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['info'] == $categories['categories_id'])) && (!$cInfo) && (!$pInfo) && (substr($HTTP_GET_VARS['action'], 0, 4) != 'new_') ) {
// count category childs
        $category_childs_query = tep_db_query("select count(*) as total from categories where parent_id = '" . $categories['categories_id'] . "'");
        $category_childs = tep_db_fetch_array($category_childs_query);
// count category proucts (direct links)
        $category_products_query = tep_db_query("select count(*) as total from products_to_categories where categories_id = '" . $categories['categories_id'] . "'");
        $category_products = tep_db_fetch_array($category_products_query);

// new category info instance
        $cInfo = new Category_Info();
        $cInfo->id = $categories['categories_id'];
        $cInfo->name = $categories['categories_name'];
        $cInfo->image = $categories['categories_image'];
        $cInfo->parent_id = $categories['parent_id'];
        $cInfo->sort_order = $categories['sort_order'];
        $cInfo->childs_count = $category_childs['total'];
        $cInfo->products_count = $category_products['total'];
      }

      if ($categories['categories_id'] == $cInfo->id) {
        echo '              <tr bgcolor="#b0c8df" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id']), 'NONSSL') . '\'">' . "\n";
      } else {
        echo '              <tr bgcolor="#d8e1eb" onmouseover="this.style.background=\'#cc9999\';this.style.cursor=\'hand\'" onmouseout="this.style.background=\'#d8e1eb\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('info', 'action') . 'info=' . $categories['categories_id'], 'NONSSL') . '\'">' . "\n";
      }
?>
                <td width="10%" align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$categories['categories_id'];?>&nbsp;</font></td>
                <td width="80%" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<b><?='<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id']), 'NONSSL') . '" class="blacklink"><u>' . $categories['categories_name'] . '</u></a>';?></b>&nbsp;</font></td>
<?
      if ($categories['categories_id'] == $cInfo->id) {
?>
                <td width="10%" align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=tep_image(DIR_IMAGES . 'icon_arrow_right.gif', 13, 13, 0, '');?>&nbsp;</font></td>
<?
      } else {
?>
                <td width="10%" align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?='<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('info', 'action') . 'info=' . $categories['categories_id'], 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'icon_info.gif', '13', '13', '0', IMAGE_ICON_INFO) . '</a>';?>&nbsp;</font></td>
<?
      }
?>
              </tr>
<?
    }

    $products_count = 0;
//  $rows = 0; // this shouldnt be reset
    $products_query = tep_db_query("select p.products_id, p.products_quantity, p.products_image, p.products_price, p.products_date_added from products p, products_to_categories p2c where p.products_id = p2c.products_id and p2c.categories_id = '" . $current_category_id . "' order by products_name");
    while ($products = tep_db_fetch_array($products_query)) {
      $products_count++;
      $rows++;

      if ( ((!$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['info'] == $products['products_id'])) && (!$pInfo) && (!$cInfo) && (substr($HTTP_GET_VARS['action'], 0, 4) != 'new_') ) {
// new product info instance
        $pInfo = new Product_Info();
        $pInfo->id = $products['products_id'];
        $pInfo->name = tep_products_name($products['products_id']);
        $pInfo->quantity = $products['products_quantity'];
        $pInfo->image = $products['products_image'];
        $pInfo->price = tep_currency_format($products['products_price']);
        $pInfo->date_added = tep_date_short($products['products_date_added']);
      }

      if ($products['products_id'] == $pInfo->id) {
        echo '              <tr bgcolor="#b0c8df" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action', 'pID') . 'pID=' . $products['products_id'] . '&action=new_product_preview&read=only', 'NONSSL') . '\'">' . "\n";
      } else {
        echo '              <tr bgcolor="#e9e9e9" onmouseover="this.style.background=\'#cc9999\';this.style.cursor=\'hand\'" onmouseout="this.style.background=\'#e9e9e9\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('info', 'action') . 'info=' . $products['products_id'], 'NONSSL') . '\'">' . "\n";
      }
?>
                <td width="10%" align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$products['products_id'];?>&nbsp;</font></td>
                <td width="80%" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?='<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action', 'pID') . 'pID=' . $products['products_id'] . '&action=new_product_preview&read=only', 'NONSSL') . '" class="blacklink"><u>' . tep_products_name($products['products_id']) . '</u></a>';?>&nbsp;</font></td>
<?
      if ($products['products_id'] == $pInfo->id) {
?>
                <td width="10%" align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=tep_image(DIR_IMAGES . 'icon_arrow_right.gif', 13, 13, 0, '');?>&nbsp;</font></td>
<?
      } else {
?>
                <td width="10%" align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?='<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('info', 'action') . 'info=' . $products['products_id'], 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'icon_info.gif', '13', '13', '0', IMAGE_ICON_INFO) . '</a>';?>&nbsp;</font></td>
<?
      }
?>
              </tr>
<?
    }

    if ($rows > 0) {
?>
              <tr>
                <td colspan="3"><?=tep_black_line();?></td>
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
                    <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_CATEGORIES;?> <?=$categories_count;?>&nbsp;<br>&nbsp;Products: <?=$products_count;?>&nbsp;</font></td>
                    <td align="right" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<? if ($cPath) echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, $cPath_back, 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_back.gif', '66', '20', '0', IMAGE_BACK) . '</a>&nbsp;';?><?='<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action', 'info') . 'action=new_category', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_new_category.gif', '103', '20', '0', IMAGE_NEW_CATEGORY) . '</a>';?>&nbsp;<?='<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action', 'info') . 'action=new_product', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_new_product.gif', '103', '20', '0', IMAGE_NEW_PRODUCT) . '</a>';?>&nbsp;</font></td>
                  </tr>
                </table></td>
              </tr>
<?
    if ($HTTP_GET_VARS['error']) {
?>
              <tr>
                <td colspan="3"><?=tep_black_line();?></td>
              </tr>
              <tr>
                <td colspan="3"><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="#ff0000">&nbsp;<?=ERROR_ACTION;?>&nbsp;</font></td>
              </tr>
<?
    }
?>
            </table></td>
            <td width="25%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr bgcolor="#81a2b6">
                <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="#ffffff">&nbsp;<b><? if ($cInfo && !$pInfo) echo $cInfo->name;if ($pInfo && !$cInfo) echo $pInfo->name;?></b>&nbsp;</font></td>
              </tr>
              <tr bgcolor="#81a2b6">
                <td><?=tep_black_line();?></td>
              </tr>
<?
/*
  here we display the info box on the right of the main table
*/

    if ($HTTP_GET_VARS['action'] == 'edit_category') {
?>
              <tr bgcolor="#b0c8df"><form name="categories" <?='action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action') . 'action=save', 'NONSSL') . '"';?> method="post"><input type="hidden" name="original_categories_id" value="<?=$cInfo->id;?>">
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=TEXT_EDIT_INTRO;?><br>&nbsp;</font></td>
                  </tr>
<?
      if (EXPERT_MODE) {
?>
                  <tr>
                    <td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_EDIT_CATEGORIES_ID;?><br>&nbsp;<input type="text" name="categories_id" value="<?=$cInfo->id;?>" size="2"><br>&nbsp;</font></td>
                  </tr>
<?
      }
?>
                  <tr>
                    <td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_EDIT_CATEGORIES_NAME;?><br>&nbsp;<input type="text" name="categories_name" value="<?=$cInfo->name;?>"><br>&nbsp;<br>&nbsp;<?=TEXT_EDIT_CATEGORIES_IMAGE;?><br>&nbsp;<input type="text" name="categories_image" value="<?=$cInfo->image;?>"><br>&nbsp;<br>&nbsp;<?=TEXT_EDIT_SORT_ORDER;?><br>&nbsp;<input type="text" name="sort_order" size="2" value="<?=$cInfo->sort_order;?>"><br>&nbsp;</font></td>
                  </tr>
<?
      if (EXPERT_MODE) {
?>
                  <tr>
                    <td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_EDIT_PARENT_ID;?><br>&nbsp;<input type="text" name="parent_id" value="<?=$cInfo->parent_id;?>"><br>&nbsp;</font></td>
                  </tr>
<?
      }
?>
                  <tr>
                    <td align="center"><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=tep_image_submit(DIR_IMAGES . 'button_save.gif', '66', '20', '0', IMAGE_SAVE);?> <?='<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action'), 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>';?></font></td>
                  </tr>
                </table></td>
              </form></tr>
<?
    } elseif ($HTTP_GET_VARS['action'] == 'delete_category') {
?>
              <tr bgcolor="#b0c8df"><form name="categories" <?='action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action') . 'action=deleteconfirm', 'NONSSL') . '"';?> method="post"><input type="hidden" name="categories_id" value="<?=$cInfo->id;?>">
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=TEXT_DELETE_INTRO;?>&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<b><?=$cInfo->name;?></b>&nbsp;</font></td>
                  </tr>
<?
      if ($cInfo->childs_count > 0) {
?>
                  <tr>
                    <td><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count);?>&nbsp;</font></td>
                  </tr>
<?
      }
?>
<?
      if ($cInfo->products_count > 0) {
?>
                  <tr>
                    <td><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count);?>&nbsp;</font></td>
                  </tr>
<?
      }
?>
                  <tr>
                    <td align="center"><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=tep_image_submit(DIR_IMAGES . 'button_delete.gif', '66', '20', '0', IMAGE_DELETE);?> <?='<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action'), 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>';?></font></td>
                  </tr>
                </table></td>
              </form></tr>
<?
    } elseif ($HTTP_GET_VARS['action'] == 'delete_product') {
?>
              <tr bgcolor="#b0c8df"><form name="products" <?='action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action') . 'action=deleteconfirm', 'NONSSL') . '"';?> method="post"><input type="hidden" name="products_id" value="<?=$pInfo->id;?>">
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=TEXT_DELETE_PRODUCTS_INTRO;?>&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<b><?=$pInfo->name;?></b>&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td align="center"><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=tep_image_submit(DIR_IMAGES . 'button_delete.gif', '66', '20', '0', IMAGE_DELETE);?> <?='<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action'), 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>';?></font></td>
                  </tr>
                </table></td>
              </form></tr>
<?
    } elseif ($HTTP_GET_VARS['action'] == 'move_category') {
?>
              <tr bgcolor="#b0c8df"><form name="categories" <?='action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action') . 'action=moveconfirm', 'NONSSL') . '"';?> method="post"><input type="hidden" name="categories_id" value="<?=$cInfo->id;?>">
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=sprintf(TEXT_MOVE_INTRO, $cInfo->name);?>&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=sprintf(TEXT_MOVE, $cInfo->name);?><br>&nbsp;<select name="move_to_category_id" style="font-size:10px">
<?
      $categories_all_query = tep_db_query("select categories_id, categories_name, parent_id from categories order by categories_name");
      while ($categories_all = tep_db_fetch_array($categories_all_query)) {
        if ($cInfo->id != $categories_all['categories_id']) {
          $categories_parent_query = tep_db_query("select categories_name from categories where categories_id = '" . $categories_all['parent_id'] . "'");
          $categories_parent = tep_db_fetch_array($categories_parent_query);
          echo '<option value="' . $categories_all['categories_id'] . '">' . $categories_all['categories_name'] . ' (' . $categories_parent['categories_name'] . ')</option>';
        }
      }
?>
                    </select><? if (!EXPERT_MODE) echo '<br>&nbsp;<br>' . TEXT_MOVE_NOTE; ?>&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td align="center"><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=tep_image_submit(DIR_IMAGES . 'button_move.gif', '66', '20', '0', IMAGE_MOVE);?> <?='<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action'), 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>';?></font></td>
                  </tr>
                </table></td>
              </form></tr>
<?
    } elseif ($HTTP_GET_VARS['action'] == 'move_product') {
?>
              <tr bgcolor="#b0c8df"><form name="products" <?='action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action') . 'action=moveconfirm', 'NONSSL') . '"';?> method="post"><input type="hidden" name="products_id" value="<?=$pInfo->id;?>">
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=sprintf(TEXT_MOVE_PRODUCTS_INTRO, $pInfo->name);?>&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=sprintf(TEXT_MOVE, $pInfo->name);?><br>&nbsp;<select name="move_to_category_id" style="font-size:10px">
<?
      $categories_all_query = tep_db_query("select categories_id, categories_name, parent_id from categories order by categories_name");
      while ($categories_all = tep_db_fetch_array($categories_all_query)) {
        if ($cInfo->id != $categories_all['categories_id']) {
          $categories_parent_query = tep_db_query("select categories_name from categories where categories_id = '" . $categories_all['parent_id'] . "'");
          $categories_parent = tep_db_fetch_array($categories_parent_query);
          echo '<option value="' . $categories_all['categories_id'] . '">' . $categories_all['categories_name'] . ' (' . $categories_parent['categories_name'] . ')</option>';
        }
      }
?>
                    </select><? if (!EXPERT_MODE) echo '<br>&nbsp;<br>' . TEXT_MOVE_NOTE; ?>&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td align="center"><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=tep_image_submit(DIR_IMAGES . 'button_move.gif', '66', '20', '0', IMAGE_MOVE);?> <?='<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action'), 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>';?></font></td>
                  </tr>
                </table></td>
              </form></tr>
<?
    } elseif ($HTTP_GET_VARS['action'] == 'new_category') {
?>
              <tr bgcolor="#b0c8df"><form name="insert_category" <?='action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action') . 'action=insert_category', 'NONSSL') . '"';?> method="post">
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=TEXT_NEW_CATEGORY_INTRO;?><br>&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_CATEGORIES_NAME;?><br>&nbsp;<input type="text" name="categories_name"><br>&nbsp;<br>&nbsp;<?=TEXT_CATEGORIES_IMAGE;?><br>&nbsp;<input type="text" name="categories_image"><br>&nbsp;<br>&nbsp;<?=TEXT_SORT_ORDER;?><br>&nbsp;<input type="text" name="sort_order" size="2"><br>&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td align="center"><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=tep_image_submit(DIR_IMAGES . 'button_save.gif', '66', '20', '0', IMAGE_SAVE);?> <?='<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action'), 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>';?></font></td>
                  </tr>
                </table></td>
              </form></tr>
<?
    } else {
?>
              <tr bgcolor="#b0c8df">
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?
      if ($rows > 0) {
        if ($cInfo) {
?>
                  <tr>
                    <td align="center"><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?='<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action') . 'action=edit_category', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_edit.gif', '66', '20', '0', IMAGE_EDIT) . '</a>';?> <?='<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action') . 'action=delete_category', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_delete.gif', '66', '20', '0', IMAGE_DELETE) . '</a>';?> <?='<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action') . 'action=move_category', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_move.gif', '66', '20', '0', IMAGE_MOVE) . '</a>';?></font></td>
                  </tr>
                  <tr>
                    <td><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_DATE_ADDED;?>&nbsp;<br>&nbsp;<?=TEXT_LAST_MODIFIED;?>&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<? $image_size = @getimagesize(DIR_SERVER_ROOT . DIR_CATALOG . $cInfo->image); if ($image_size) { echo tep_image(DIR_CATALOG . $cInfo->image, $image_size[0], $image_size[1], 0, $cInfo->name); } else { echo TEXT_IMAGE_NONEXISTENT; } ?>&nbsp;<br>&nbsp;<?=$cInfo->image;?>&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_SUBCATEGORIES;?> <?=$cInfo->childs_count;?><br>&nbsp;<?=TEXT_PRODUCTS;?> <?=$cInfo->products_count;?>&nbsp;</font></td>
                  </tr>
<?
        } elseif ($pInfo) {
?>
                  <tr>
                    <td align="center"><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?='<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action') . 'action=new_product&pID=' . $pInfo->id, 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_edit.gif', '66', '20', '0', IMAGE_EDIT) . '</a>';?> <?='<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action') . 'action=delete_product', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_delete.gif', '66', '20', '0', IMAGE_DELETE) . '</a>';?> <?='<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action') . 'action=move_product', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_move.gif', '66', '20', '0', IMAGE_MOVE) . '</a>';?></font></td>
                  </tr>
                  <tr>
                    <td><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_DATE_ADDED;?>&nbsp;<?=$pInfo->date_added;?><br>&nbsp;<?=TEXT_LAST_MODIFIED;?>&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<? $image_size = @getimagesize(DIR_SERVER_ROOT . DIR_CATALOG . $pInfo->image); if ($image_size) { echo tep_image(DIR_CATALOG . $pInfo->image, $image_size[0], $image_size[1], 0, $pInfo->name); } else { echo TEXT_IMAGE_NONEXISTENT; } ?>&nbsp;<br>&nbsp;<?=$pInfo->image;?>&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_PRODUCTS_PRICE_INFO;?> <?=$pInfo->price;?><br>&nbsp;<?=TEXT_PRODUCTS_QUANTITY_INFO;?> <?=$pInfo->quantity;?>&nbsp;</font></td>
                  </tr>
<?
        }
      } else {
?>
                  <tr>
                    <td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=sprintf(TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS, $parent_categories_name);?></font></td>
                  </tr>
<?
      }
?>
                </table></td>
              </tr>
<?
    }
?>
              <tr bgcolor="#b0c8df">
                <td><?=tep_black_line();?></td>
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
<? $include_file = DIR_INCLUDES . 'footer.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<? include('includes/application_bottom.php'); ?>