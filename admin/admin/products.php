<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_PRODUCTS; include(DIR_INCLUDES . 'include_once.php'); ?>
<?
  if ($HTTP_GET_VARS['action']) {
    if (($HTTP_GET_VARS['action'] == 'add_product') && ($HTTP_POST_VARS['insert'] == '1')) {
      $date_now = date('Ymd');
      tep_db_query("insert into products values ('', '" . $HTTP_POST_VARS['products_name'] . "', '" . $HTTP_POST_VARS['products_description'] . "', '" . $HTTP_POST_VARS['products_quantity'] . "', '" . $HTTP_POST_VARS['products_model'] . "', '', '" . $HTTP_POST_VARS['products_url'] . "', '" . $HTTP_POST_VARS['products_price'] . "', '" . $date_now . "', '0', '" . $HTTP_POST_VARS['products_weight'] . "')");
      $products_id = tep_db_insert_id();
      if (!empty($products_image)) {
      	tep_db_query("update products set products_image = 'images/" . $products_image_name . "' where products_id = '" . $products_id . "'");
      	$image_location = DIR_SERVER_ROOT . DIR_CATALOG_IMAGES . $products_image_name;
        if (file_exists($image_location)) {
          @unlink($image_location);
        }
        copy($products_image, $image_location);
      }
      tep_db_query("insert into products_to_subcategories values ('', '" . $products_id . "', '" . $HTTP_POST_VARS['subcategories_id'] . "')");
      tep_db_query("insert into products_to_manufacturers values ('', '" . $products_id . "', '" . $HTTP_POST_VARS['manufacturers_id'] . "')");
      header('Location: ' . tep_href_link(FILENAME_PRODUCTS, '', 'NONSSL')); tep_exit();
    } elseif (($HTTP_GET_VARS["action"] == "add_product") && ($HTTP_POST_VARS['update'] == '1')) {
      tep_db_query("update products set products_name = '" . $HTTP_POST_VARS['products_name'] . "', products_description = '" . $HTTP_POST_VARS['products_description'] . "', products_quantity = '" . $HTTP_POST_VARS['products_quantity'] . "', products_model = '" . $HTTP_POST_VARS['products_model'] . "', products_image = '" . $HTTP_POST_VARS['products_image'] . "', products_url = '" . $HTTP_POST_VARS['products_url'] . "', products_price = '" . $HTTP_POST_VARS['products_price'] . "', products_weight = '" . $HTTP_POST_VARS['products_weight'] . "' where products_id = '" . $HTTP_POST_VARS['products_id'] . "'");
/* manufacturers */
      $manufacturers_id = $HTTP_POST_VARS['manufacturers_id'];
      $number_manufacturers = sizeof($manufacturers_id);
      for ($i=0;$i<$number_manufacturers;$i++) {
        $manufacturer_info = explode('_', $manufacturers_id[$i]); // products_to_manufacturers_id . "_" . manufacturers_id
        $check_link = tep_db_query("select products_id, manufacturers_id from products_to_manufacturers where products_to_manufacturers_id = '" . $manufacturer_info[0] . "'");
        $check_link_values = tep_db_fetch_array($check_link);
        if ($check_link_values['manufacturers_id'] == $manufacturer_info[1]) {
          $manufacturers_id_match = '1'; // manufacturers match.. no updated needed
        } else {
          $manufacturers_id_match = '0'; // manufactuers mismatch.. update or remove?
        }
        if ($manufacturers_id_match == '0') {
          if ($manufacturer_info[1] == 'none') { // the manufacturer has been updated to be deleted..
            tep_db_query("delete from products_to_manufacturers where products_to_manufacturers_id = '" . $manufacturer_info[0] . "'");
          } else { // the manufacturer has been updated to another manufacturer
            tep_db_query("update products_to_manufacturers set manufacturers_id = '" . $manufacturer_info[1] . "' where products_to_manufacturers_id = '" . $manufacturer_info[0] . "'");
          }
        }
        if (($manufacturer_info[0]) && (!$manufacturer_info[1])) { // insert a new manufacturer
          tep_db_query("insert into products_to_manufacturers values ('', '" . $HTTP_POST_VARS['products_id'] . "', '" . $manufacturer_info[0] . "')");
        }
      }
/* subcategories */
      $subcategories_id = $HTTP_POST_VARS['subcategories_id'];
      $number_subcategories = sizeof($subcategories_id);
      for ($i=0;$i<$number_subcategories;$i++) {
        $subcategory_info = explode('_', $subcategories_id[$i]); // products_to_subcategories_id . "_" . subcategories_id
        $check_link = tep_db_query("select products_id, subcategories_id from products_to_subcategories where products_to_subcategories_id = '" . $subcategory_info[0] . "'");
        $check_link_values = tep_db_fetch_array($check_link);
        if ($check_link_values['subcategories_id'] == $subcategory_info[1]) {
          $subcategories_id_match = '1'; // subcategories match.. no updated needed
        } else {
          $subcategories_id_match = '0'; // subcategories mismatch.. update or remove?
        }
        if ($subcategories_id_match == '0') {
          if ($subcategory_info[1] == 'none') { // the subcategory has been updated to be deleted..
            tep_db_query("delete from products_to_subcategories where products_to_subcategories_id = '" . $subcategory_info[0] . "'");
          } else { // the subcategory has been updated to another subcategory
            tep_db_query("update products_to_subcategories set subcategories_id = '" . $subcategory_info[1] . "' where products_to_subcategories_id = '" . $subcategory_info[0] . "'");
          }
        }
        if (($subcategory_info[0]) && (!$subcategory_info[1])) { // insert a new subcategory
          tep_db_query("insert into products_to_subcategories values ('', '" . $HTTP_POST_VARS['products_id'] . "', '" . $subcategory_info[0] . "')");
        }
      }
      header('Location: ' . tep_href_link(FILENAME_PRODUCTS, '', 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'delete_product') {
      $products = tep_db_query("select products_image from products where products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
      $products_values = tep_db_fetch_array($products);
      $products_image = $products_values['products_image'];
      tep_db_query("delete from products where products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
      tep_db_query("delete from products_to_subcategories where products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
      tep_db_query("delete from products_to_manufacturers where products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
      tep_db_query("delete from customers_basket where products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
      $reviews = tep_db_query("select reviews_id from reviews_extra where products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
      while (@$reviews_delete = tep_db_fetch_array($reviews)) { // delete all products reviews
        tep_db_query("delete from reviews where reviews_id = '" . $reviews_delete['reviews_id'] . "'");
      }
      tep_db_query("delete from reviews_extra where products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
      if (file_exists(DIR_SERVER_ROOT . DIR_CATALOG . $products_image)) { // delete products image
        if (!@unlink(DIR_SERVER_ROOT . DIR_CATALOG . $products_image)) echo 'Can\'t delete this file:<br><br>' . DIR_SERVER_ROOT . DIR_CATALOG . $products_image . '<br><br>';
      }
      header('Location: ' . tep_href_link(FILENAME_PRODUCTS, '', 'NONSSL')); tep_exit();
    }
  }
?>
<html>
<head>
<title><?=TITLE;?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript"><!--
function checkForm(form) {
  if (form == "2") {
    var error_message = "<?=JS_ERROR;?>";
    var error = 0;
    var products_description = document.products.products_description.value;
    var products_price = document.products.products_price.value;
    var products_weight = document.products.products_weight.value;
    var products_quantity = document.products.products_quantity.value;
    var products_model = document.products.products_model.value;
    var products_image = document.products.products_image.value;

    if (products_description.length < 1) {
      error_message = error_message + "<?=JS_PRODUCTS_DESCRIPTION;?>";
      error = 1;
    }

    if (products_price.length < 1) {
      error_message = error_message + "<?=JS_PRODUCTS_PRICE;?>";
      error = 1;
    }

    if (products_weight.length < 1) {
      error_message = error_message + "<?=JS_PRODUCTS_WEIGHT;?>";
      error = 1;
    }

    if (products_quantity.length < 1) {
      error_message = error_message + "<?=JS_PRODUCTS_QUANTITY;?>";
      error = 1;
    }

    if (products_model.length < 1) {
      error_message = error_message + "<?=JS_PRODUCTS_MODEL;?>";
      error = 1;
    }

    if (products_image.length < 1) {
      error_message = error_message + "<?=JS_PRODUCTS_IMAGE;?>";
      error = 1;
    }

    if (error == 1) {
      alert(error_message);
      return false;
    } else {
      return true;
    }
  } else if (form == "1") {
    var error_message = "<?=JS_ERROR;?>";
    var error = 0;
    var products_name = document.products.products_name.value;

    if (products_name.length < 1) {
      error_message = error_message + "<?=JS_PRODUCTS_NAME;?>";
      error = 1;
    }

    if (error == 1) {
      alert(error_message);
      return false;
    } else {
      return true;
    }
  }
}

function new_win(image) {
  window.open(image,"image","height=80,width=120,toolbar=no,statusbar=no,scrollbars=no").focus();
}

function go() {
  if (document.order_by.selected.options[document.order_by.selected.selectedIndex].value != "none") {
    location = "<?=FILENAME_PRODUCTS;?>?order_by="+document.order_by.selected.options[document.order_by.selected.selectedIndex].value;
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
            <td bgcolor="<?=TOP_BAR_BACKGROUND_COLOR;?>" width="100%" nowrap><font face="<?=TOP_BAR_FONT_FACE;?>" size="<?=TOP_BAR_FONT_SIZE;?>" color="<?=TOP_BAR_FONT_COLOR;?>">&nbsp;<?=TOP_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
<?
  if ($HTTP_GET_VARS['action'] == 'delete') {
    $products = tep_db_query("select products_id, products_name, products_description, products_quantity, products_model, products_image, products_url, products_price, products_date_added from products where products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
    $products_values = tep_db_fetch_array($products);
    $manufacturers = tep_db_query("select manufacturers.manufacturers_name, manufacturers.manufacturers_location, manufacturers.manufacturers_image from manufacturers, products_to_manufacturers where products_to_manufacturers.products_id = '" . $HTTP_GET_VARS['products_id'] . "' and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id");
    $products_manufacturers = '';
    $products_manufacturers_images = '';
    if (tep_db_num_rows($manufacturers) > 1) {
      while ($manufacturers_values = tep_db_fetch_array($manufacturers)) {
        $products_manufacturers .= $manufacturers_values['manufacturers_name'] . ' / ';
        $products_manufacturers_images .= tep_image(DIR_CATALOG . $manufacturers_values['manufacturers_image'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', $manufacturers_values['manufacturers_name']) . '&nbsp;';
        if ($manufacturers_values['manufacturers_location'] == '1') {
          $manufacturers_location = '1';
        } else {
          if ($manufacturers_location == '1') {
            $manufacturers_location = '1';
          } else {
            $manufacturers_location = '0';
          }
        }
      }
      $products_manufacturers = substr($products_manufacturers, 0, -3); // remove last ' / '
    } else {
      $manufacturers_values = tep_db_fetch_array($manufacturers);
      $products_manufacturers = $manufacturers_values['manufacturers_name'];
      $products_manufacturers_images = tep_image(DIR_CATALOG . $manufacturers_values['manufacturers_image'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', $manufacturers_values['manufacturers_name']) . '&nbsp;';
      $manufacturers_location = $manufacturers_values['manufacturers_location'];
    }
    if ($manufacturers_location == '0') {
      $products_name = $products_manufacturers . ' ' . $products_values['products_name'];
    } else {
      $products_name = $products_values['products_name'] . ' (' . $products_manufacturers . ')';
    }
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=$products_name . ' @ $' . $products_values['products_price'];?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<?=$products_manufacturers_images;?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><?=tep_image(DIR_CATALOG . $products_values['products_image'], '100', '80', '0" align="right" hspace="5" vspace="5"', $products_name);?><?=$products_values['products_description'];?></font></td>
      </tr>
<?
    $reviews = tep_db_query("select count(*) as count from reviews_extra where products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
    $reviews_values = tep_db_fetch_array($reviews);
?>
      <tr>
        <td nowrap><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><?=TEXT_CURRENT_REVIEWS;?> <?=$reviews_values['count'];?>&nbsp;</font></td>
      </tr>
<?
    if ($products_values['products_url']) {
?>
      <tr>
        <td nowrap><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><?=sprintf(TEXT_MORE_INFORMATION, $products_values['products_url']);?>&nbsp;</font></td>
      </tr>
<?
    }
    $raw_date_added = $products_values['products_date_added'];
    $date_added = date('l, jS F, Y', mktime(0,0,0,substr($raw_date_added, 4, 2),substr($raw_date_added, -2),substr($raw_date_added, 0, 4)));
?>
      <tr>
        <td align="center" nowrap><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=sprintf(TEXT_DATE_ADDED, $date_added);?></font></td>
      </tr>
      <tr>
        <td width="100%"><br><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td align="right" nowrap><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?='<a href="' . tep_href_link(FILENAME_PRODUCTS, 'action=delete_product&products_id=' . $HTTP_GET_VARS['products_id'], 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_delete.gif', '50', '14', '0', IMAGE_DELETE);?></a>&nbsp;&nbsp;<?='<a href="' . tep_href_link(FILENAME_PRODUCTS, '', 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_cancel.gif', '50', '14', '0', IMAGE_CANCEL);?></a>&nbsp;</font></td>
      </tr>
<?
  }  elseif ((($HTTP_GET_VARS['action'] == 'add_product') && (!$HTTP_POST_VARS['insert'])) || ($HTTP_GET_VARS['action'] == 'update')) {
    $update = 0;
    if ($HTTP_GET_VARS['action'] == 'update') {
      $update = 1;
      $products = tep_db_query("select products_id, products_name, products_description, products_quantity, products_model, products_image, products_url, products_price, products_weight from products where products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
      $products_values = tep_db_fetch_array($products);
    }
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?
    if ($update == 0) {
?>
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=$HTTP_POST_VARS['products_name'];?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_IMAGES . 'pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', '');?>&nbsp;</td>
          </tr>
<?
    } else {
?>
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=$products_values['products_name'];?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_CATALOG . $products_values['products_image'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', $products_values['products_name']);?>&nbsp;</td>
          </tr>
<?
    }
?>
        </table></td>
      </tr>
      <tr><form name="products" <?='action="' . tep_href_link(FILENAME_PRODUCTS, 'action=add_product', 'NONSSL') . '"';?> enctype="multipart/form-data" method="post" onSubmit="return checkForm('2');">
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="2"><?=tep_black_line();?></td>
          </tr>
<?
    if ($update == 0) {
?>
          <tr>
            <td valign="top" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=ENTRY_MANUFACTURERS;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<select name="manufacturers_id"><?
    $manufacturers = tep_db_query("select manufacturers.manufacturers_id, manufacturers.manufacturers_name, category_top.category_top_name from manufacturers, manufacturers_to_category, category_top where manufacturers.manufacturers_id = manufacturers_to_category.manufacturers_id and manufacturers_to_category.category_top_id = category_top.category_top_id order by manufacturers_name");
    while ($manufacturers_values = tep_db_fetch_array($manufacturers)) {
      echo '<option name="' . $manufacturers_values['manufacturers_name'] . '" value="' . $manufacturers_values['manufacturers_id'] . '">' . $manufacturers_values['manufacturers_name'] . ' (' . $manufacturers_values['category_top_name'] . ')</option>';
    } ?></select>&nbsp;<br>&nbsp;<?=ENTRY_MANUFACTURERS_TEXT;?></font></td>
          </tr>
<?
    } else {
?>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=ENTRY_PRODUCTS_NAME;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="products_name" value="<?=$products_values['products_name'];?>">&nbsp;</font></td>
          </tr>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=ENTRY_MANUFACTURERS;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?
    $products_manufacturers = tep_db_query("select manufacturers.manufacturers_id, products_to_manufacturers.products_to_manufacturers_id from manufacturers, products_to_manufacturers where products_to_manufacturers.products_id = '" . $products_values['products_id'] . "' and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id order by manufacturers.manufacturers_name");
    $manufacturers = tep_db_query("select manufacturers.manufacturers_id, manufacturers.manufacturers_name, category_top.category_top_name from manufacturers, manufacturers_to_category, category_top where manufacturers.manufacturers_id = manufacturers_to_category.manufacturers_id and manufacturers_to_category.category_top_id = category_top.category_top_id order by manufacturers_name");
    while ($products_manufacturers_values = tep_db_fetch_array($products_manufacturers)) {
      echo '<select name="manufacturers_id[]"><option name="none" value="' . $products_manufacturers_values['products_to_manufacturers_id'] . '_none"></option>';
      tep_db_data_seek($manufacturers, 0);
      while ($manufacturers_values = tep_db_fetch_array($manufacturers)) {
        echo '<option name="' . $manufacturers_values['manufacturers_name'] . '" value="' . $products_manufacturers_values['products_to_manufacturers_id'] . '_' . $manufacturers_values['manufacturers_id'] . '"';
        if ($products_manufacturers_values['manufacturers_id'] == $manufacturers_values['manufacturers_id']) {
          echo ' SELECTED';
        }
        echo '>' . $manufacturers_values['manufacturers_name'] . ' (' . $manufacturers_values['category_top_name'] . ')</option>';
      }
      echo '</select>&nbsp;&nbsp;';
    } ?><select name="manufacturers_id[]"><option name="none" value="none"></option><?
    tep_db_data_seek($manufacturers, 0);
    while ($manufacturers_values = tep_db_fetch_array($manufacturers)) {
      echo '<option name="' . $manufacturers_values['manufacturers_name'] . '" value="' . $manufacturers_values['manufacturers_id'] . '">' . $manufacturers_values['manufacturers_name'] . ' (' . $manufacturers_values['category_top_name'] . ')</option>';
    }
    ?></select></font></td>
          </tr>
<?
    }
    if ($update == 0) {
?>
          <tr>
            <td valign="top" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=ENTRY_SUBCATEGORIES;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<select name="subcategories_id"><?
    $subcategories = tep_db_query("select subcategories.subcategories_id, subcategories.subcategories_name, category_top.category_top_name from subcategories, subcategories_to_category, category_top where subcategories.subcategories_id = subcategories_to_category.subcategories_id and subcategories_to_category.category_top_id = category_top.category_top_id order by subcategories_name");
    while ($subcategories_values = tep_db_fetch_array($subcategories)) {
      echo '<option name="' . $subcategories_values['subcategories_name'] . '" value="' . $subcategories_values['subcategories_id'] . '"';
      if ($HTTP_POST_VARS['subcategories_id'] == $subcategories_values['subcategories_id']) {
        echo ' SELECTED';
      }
      echo '>' . $subcategories_values['subcategories_name'] . ' (' . $subcategories_values['category_top_name'] . ')</option>';
    } ?></select>&nbsp;<br>&nbsp;<?=ENTRY_SUBCATEGORIES_TEXT;?></font></td>
          </tr>
<?
    } else {
?>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=ENTRY_SUBCATEGORIES;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?
    $products_subcategories = tep_db_query("select subcategories.subcategories_id, products_to_subcategories.products_to_subcategories_id from subcategories, products_to_subcategories where products_to_subcategories.products_id = '" . $products_values['products_id'] . "' and products_to_subcategories.subcategories_id = subcategories.subcategories_id order by subcategories.subcategories_name");
    $subcategories = tep_db_query("select subcategories.subcategories_id, subcategories.subcategories_name, category_top.category_top_name from subcategories, subcategories_to_category, category_top where subcategories.subcategories_id = subcategories_to_category.subcategories_id and subcategories_to_category.category_top_id = category_top.category_top_id order by subcategories_name");
    while ($products_subcategories_values = tep_db_fetch_array($products_subcategories)) {
      echo '<select name="subcategories_id[]"><option name="none" value="' . $products_subcategories_values['products_to_subcategories_id'] . '_none"></option>';
      tep_db_data_seek($subcategories, 0);
      while ($subcategories_values = tep_db_fetch_array($subcategories)) {
        echo '<option name="' . $subcategories_values['subcategories_name'] . '" value="' . $products_subcategories_values['products_to_subcategories_id'] . '_' . $subcategories_values['subcategories_id'] . '"';
        if ($products_subcategories_values['subcategories_id'] == $subcategories_values['subcategories_id']) {
          echo ' SELECTED';
        }
        echo '>' . $subcategories_values['subcategories_name'] . ' (' . $subcategories_values['category_top_name'] . ')</option>';
      }
      echo '</select>&nbsp;&nbsp;';
    } ?><select name="subcategories_id[]"><option name="none" value="none"></option><?
    tep_db_data_seek($subcategories, 0);
    while ($subcategories_values = tep_db_fetch_array($subcategories)) {
      echo '<option name="' . $subcategories_values['subcategories_name'] . '" value="' . $subcategories_values['subcategories_id'] . '">' . $subcategories_values['subcategories_name'] . ' (' . $subcategories_values['category_top_name'] . ')</option>';
    } ?></select></font></td>
          </tr>
<?
    }
    if ($update == 0) {
?>
          <tr>
            <td valign="top" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=ENTRY_DESCRIPTION;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<textarea name="products_description" wrap="off" cols="60" rows="15"></textarea>&nbsp;</font></td>
          </tr>
<?
    } else {
?>
          <tr>
            <td valign="top" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=ENTRY_DESCRIPTION;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<textarea name="products_description" wrap="off" cols="60" rows="15"><?=$products_values['products_description'];?></textarea>&nbsp;</font></td>
          </tr>
<?
    }
    if ($update == 0) {
?>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=ENTRY_PRICE;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="products_price" value="" size="10">&nbsp;<?=ENTRY_PRICE_TEXT;?></font></td>
          </tr>
<?
    } else {
?>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=ENTRY_PRICE;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="products_price" value="<?=$products_values['products_price'];?>" size="10">&nbsp;<?=ENTRY_PRICE_TEXT;?></font></td>
          </tr>
<?
    }
    if ($update == 0) {
?>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=ENTRY_WEIGHT;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="products_weight" value="" size="10">&nbsp;<?=ENTRY_WEIGHT_TEXT;?></font></td>
          </tr>
<?
    } else {
?>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=ENTRY_WEIGHT;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="products_weight" value="<?=$products_values['products_weight'];?>" size="10">&nbsp;<?=ENTRY_WEIGHT_TEXT;?></font></td>
          </tr>
<?
    }
    if ($update == 0) {
?>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=ENTRY_QUANTITY;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="products_quantity" value="" size="3">&nbsp;</font></td>
          </tr>
<?
    } else {
?>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=ENTRY_QUANTITY;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="products_quantity" value="<?=$products_values['products_quantity'];?>" size="3">&nbsp;</font></td>
          </tr>
<?
    }
    if ($update == 0) {
?>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=ENTRY_MODEL;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="products_model" value="" size="10">&nbsp;</font></td>
          </tr>
<?
    } else {
?>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=ENTRY_MODEL;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="products_model" value="<?=$products_values['products_model'];?>" size="10">&nbsp;</font></td>
          </tr>
<?
    }
    if ($update == 0) {
?>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=ENTRY_IMAGE;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="file" name="products_image" size="20">&nbsp;</font></td>
          </tr>
<?
    } else {
?>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=ENTRY_IMAGE;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="products_image" value="<?=$products_values['products_image'];?>" size="20">&nbsp;</font></td>
          </tr>
<?
    }
    if ($update == 0) {
?>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=ENTRY_URL;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="products_url" value="" size="20">&nbsp;<?=ENTRY_URL_TEXT;?></font></td>
          </tr>
<?
    } else {
?>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=ENTRY_URL;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="products_url" value="<?=$products_values['products_url'];?>" size="20">&nbsp;<?=ENTRY_URL_TEXT;?></font></td>
          </tr>
<?
    }
?>
          <tr>
            <td colspan="2"><?=tep_black_line();?></td>
          </tr>
<?
    if ($update == 0) {
?>
          <tr>
            <td align="right" colspan="5" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><input type="hidden" name="products_name" value="<?=$HTTP_POST_VARS['products_name'];?>"><input type="hidden" name="insert" value="1"><?=tep_image_submit(DIR_IMAGES . 'button_insert.gif', '50', '14', '0', IMAGE_INSERT);?>&nbsp;&nbsp;<?='<a href="' . tep_href_link(FILENAME_PRODUCTS, '', 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_cancel.gif', '50', '14', '0', IMAGE_CANCEL);?></a>&nbsp;&nbsp;</font></td>
          </tr>
<?
    } else {
?>
          <tr>
            <td align="right" colspan="5" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><input type="hidden" name="products_id" value="<?=$HTTP_GET_VARS['products_id'];?>"><input type="hidden" name="update" value="1"><?=tep_image_submit(DIR_IMAGES . 'button_update.gif', '50', '14', '0', IMAGE_UPDATE);?>&nbsp;&nbsp;<?='<a href="' . tep_href_link(FILENAME_PRODUCTS, '', 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_cancel.gif', '50', '14', '0', IMAGE_CANCEL);?></a>&nbsp;&nbsp;</font></td>
          </tr>
<?
    }
?>
        </table></td>
      </tr></form>
<?
  } else {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?
    if ($HTTP_GET_VARS['order_by']) {
      $order_by = $HTTP_GET_VARS['order_by'];
    } else {
      $order_by = 'products_name';
    }
?>
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap><br><form name="order_by"><select name="selected" onChange="go()"><option value="products_name"<? if ($order_by == 'products_name') { echo ' SELECTED'; } ?>>Products Name</option><option value="products_id"<? if ($order_by == 'products_id') { echo ' SELECTED'; } ?>>Products ID</option></select>&nbsp;&nbsp;</form></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="4"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_SUBCATEGORIES;?>&nbsp;</b></font></td>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_ID;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_PRODUCTS;?>&nbsp;</b></font></td>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_ACTION;?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td colspan="4"><?=tep_black_line();?></td>
          </tr>
<?
    $products = tep_db_query("select products_id, products_name, products_image from products order by '" . $order_by . "'");
    while ($products_values = tep_db_fetch_array($products)) {
      tep_products_subcategories($products_values['products_id']); // returns $products_subcategories
      $rows++;
      if (floor($rows/2) == ($rows/2)) {
        echo '          <tr bgcolor="#ffffff">' . "\n";
      } else {
        echo '          <tr bgcolor="#f4f7fd">' . "\n";
      }
?>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$products_subcategories;?>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$products_values['products_id'];?>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?
    if (file_exists(DIR_SERVER_ROOT . DIR_CATALOG . $products_values['products_image'])) {
      echo tep_image(DIR_IMAGES . 'dot_green.gif', '4', '4', '0', TEXT_IMAGE_EXISTS);
    } else {
      echo tep_image(DIR_IMAGES . 'dot_red.gif', '4', '4', '0', TEXT_IMAGE_DOES_NOT_EXIST);
    } ?>&nbsp;<a href="javascript:new_win('<?=DIR_CATALOG . $products_values['products_image'];?>')"><?=$products_values['products_name'];?></a>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?='<a href="' . tep_href_link(FILENAME_PRODUCTS, 'action=update&products_id=' . $products_values['products_id'], 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_modify.gif', '50', '14', '0', IMAGE_MODIFY);?></a>&nbsp;<?='<a href="' . tep_href_link(FILENAME_PRODUCTS, 'action=delete&products_id=' . $products_values['products_id'], 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_delete.gif', '50', '14', '0', IMAGE_DELETE);?></a>&nbsp;</font></td>
          </tr>
<?
      $ids[] = $products_values['products_id'];
      rsort($ids);
      $next_id = ($ids[0] + 1);
    }
?>
          <tr>
            <td colspan="4"><?=tep_black_line();?></td>
          </tr>
<?
    if (floor($rows/2) == ($rows/2)) {
      echo '          <tr bgcolor="#f4f7fd">' . "\n";
    } else {
      echo '          <tr bgcolor="#ffffff">' . "\n";
    }
    echo '<form name="products" action="' . tep_href_link(FILENAME_PRODUCTS, 'action=add_product', 'NONSSL') . '" method="post" onSubmit="return checkForm(\'1\');">' . "\n";
?>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<select name="subcategories_id"><?
    $subcategories = tep_db_query("select subcategories.subcategories_id, subcategories.subcategories_name, category_top.category_top_name from subcategories, subcategories_to_category, category_top where subcategories.subcategories_id = subcategories_to_category.subcategories_id and subcategories_to_category.category_top_id = category_top.category_top_id order by subcategories_name");
    while ($subcategories_values = tep_db_fetch_array($subcategories)) {
      echo '<option name="' . $subcategories_values['subcategories_name'] . '" value="' . $subcategories_values['subcategories_id'] . '">' . $subcategories_values['subcategories_name'] . ' (' . $subcategories_values['category_top_name'] . ')</option>';
    } ?></select>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$next_id;?>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="products_name" size="20">&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><b>&nbsp;<?=tep_image_submit(DIR_IMAGES . 'button_insert.gif', '50', '14', '0', IMAGE_INSERT);?>&nbsp;</font></td>
</form>
          </tr>
          <tr>
            <td colspan="4"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td align="right" colspan="4" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=tep_image(DIR_IMAGES . 'dot_green.gif', '4', '4', '0', IMAGE_GREEN_DOT);?> <?=TEXT_IMAGE_EXISTS;?>&nbsp;&nbsp;&nbsp;<?=tep_image(DIR_IMAGES . 'dot_red.gif', '4', '4', '0', IMAGE_RED_DOT);?> <?=TEXT_IMAGE_DOES_NOT_EXIST;?>&nbsp;&nbsp;</font></td>
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
