<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_MANUFACTURERS; include(DIR_INCLUDES . 'include_once.php'); ?>
<?
  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'add_manufacturer') {
      tep_db_query("insert into manufacturers values ('', '" . $HTTP_POST_VARS['manufacturers_name'] . "', '" . $HTTP_POST_VARS['manufacturers_location'] . "', '')");
      $manufacturers_id = tep_db_insert_id();
      if (!empty($manufacturers_image)) {
      	tep_db_query("update manufacturers set manufacturers_image = 'images/" . $manufacturers_image_name . "' where manufacturers_id = '" . $manufacturers_id . "'");
      	$image_location = DIR_SERVER_ROOT . DIR_CATALOG_IMAGES . $manufacturers_image_name;
        if (file_exists($image_location)) {
          @unlink($image_location);
        }
        copy($manufacturers_image, $image_location);
      }
      tep_db_query("insert into manufacturers_to_category values ('', '" . $manufacturers_id . "', '" . $HTTP_POST_VARS['category_top_id'] . "')");
      header('Location: ' . tep_href_link(FILENAME_MANUFACTURERS, '&order_by=' . $order_by . '&page=' . $page, 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'update_manufacturer') {
      tep_db_query("update manufacturers set manufacturers_name = '" . $HTTP_POST_VARS['manufacturers_name'] . "', manufacturers_location = '" . $HTTP_POST_VARS['manufacturers_location'] . "', manufacturers_image = '" . $HTTP_POST_VARS['manufacturers_image'] . "' where manufacturers_id = '" . $HTTP_POST_VARS['manufacturers_id'] . "'");
      tep_db_query("update manufacturers_to_category set category_top_id = '" . $HTTP_POST_VARS['category_top_id'] . "' where manufacturers_id = '" . $HTTP_POST_VARS['manufacturers_id'] . "'");
      header('Location: ' . tep_href_link(FILENAME_MANUFACTURERS, '&order_by=' . $order_by . '&page=' . $page, 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'delete_manufacturer') {
      $products = tep_db_query("select products_id from products_to_manufacturers where manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id'] . "'");
      while ($products_delete = tep_db_fetch_array($products)) {
        $manufacturers = tep_db_query("select count(*) as count from products_to_manufacturers where products_id = '" . $products_delete['products_id'] . "'");
        $manufacturers_values = tep_db_fetch_array($manufacturers);
        if ($manufacturers_values['count'] > 1) { // product has more than one manufacturer - only delete selected manufacturer link
          tep_db_query("delete from products_to_manufacturers where products_id = '" . $products_delete['products_id'] . "' and manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id'] . "'");
        } else { // product has only one manufacturer.. delete all related data with this products_id
          $products_image = tep_db_query("select products_image from products where products_id = '" . $products_delete['products_id'] . "'");
          $products_image_values = tep_db_fetch_array($products_image);
          $products_image = $products_image_values['products_image'];
          tep_db_query("delete from products where products_id = '" . $products_delete['products_id'] . "'"); // delete the product
          tep_db_query("delete from customers_basket where products_id = '" . $products_delete['products_id'] . "'"); // delete the product from customers basket
          $reviews = tep_db_query("select reviews_id from reviews_extra where products_id = '" . $products_delete['products_id'] . "'");
          while (@$reviews_delete = tep_db_fetch_array($reviews)) { // delete all products reviews
            tep_db_query("delete from reviews where reviews_id = '" . $reviews_delete['reviews_id'] . "'");
          }
          tep_db_query("delete from reviews_extra where products_id = '" . $products_delete['products_id'] . "'");
          tep_db_query("delete from specials where products_id = '" . $products_delete['products_id'] . "'"); // delete products specials
          tep_db_query("delete from products_to_subcategories where products_id = '" . $products_delete['products_id'] . "'"); // delete links
          tep_db_query("delete from products_to_manufacturers where products_id = '" . $products_delete['products_id'] . "'"); // delete links
          if (file_exists(DIR_SERVER_ROOT . DIR_CATALOG . $products_image)) { // delete products image
            if (!@unlink(DIR_SERVER_ROOT . DIR_CATALOG . $products_image)) echo 'Can\'t delete this file:<br><br>' . DIR_SERVER_ROOT . DIR_CATALOG . $products_image . '<br><br>';
          }
        }
      }
      $manufacturers = tep_db_query("select manufacturers_image from manufacturers where manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id'] . "'");
      $manufacturers_values = tep_db_fetch_array($manufacturers);
      $manufacturers_image = $manufacturers_values['manufacturers_image'];
      tep_db_query("delete from manufacturers where manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id'] . "'"); // delete manufacturer
      if (file_exists(DIR_SERVER_ROOT . DIR_CATALOG . $manufacturers_image)) { // delete manufacturers image
        if (!@unlink(DIR_SERVER_ROOT . DIR_CATALOG . $manufacturers_image)) echo 'Can\'t delete this file:<br><br>' . DIR_SERVER_ROOT . DIR_CATALOG . $manufacturers_image . '<br><br>';
      }
      tep_db_query("delete from manufacturers_to_category where manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id'] . "'");
      if (!@header('Location: ' . tep_href_link(FILENAME_MANUFACTURERS, '&order_by=' . $order_by . '&page' . $page, 'NONSSL'))) echo 'Can\'t redirect -> <a href="' . tep_href_link(FILENAME_MANUFACTURERS, '', 'NONSSL') . '"><u>continue</u></a><br><br>'; tep_exit();
    }
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
  var manufacturers_name = document.manufacturers.manufacturers_name.value;
  var manufacturers_location = document.manufacturers.manufacturers_location.value;
  var manufacturers_image = document.manufacturers.manufacturers_image.value;
  
  if (manufacturers_name.length < 1) {
    error_message = error_message + "<?=JS_MANUFACTURERS_NAME;?>";
    error = 1;
  }
  
  if (manufacturers_location.length < 1) {
    error_message = error_message + "<?=JS_MANUFACTURERS_LOCATION;?>";
    error = 1;
  }

  if (manufacturers_image.length < 1) {
    error_message = error_message + "<?=JS_MANUFACTURERS_IMAGE;?>";
    error = 1;
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}

function new_win(image) {
  window.open(image,"image","height=80,width=120,toolbar=no,statusbar=no,scrollbars=no").focus();
}

function go() {
  if (document.order_by.selected.options[document.order_by.selected.selectedIndex].value != "none") {
    location = "<?=FILENAME_MANUFACTURERS;?>?order_by="+document.order_by.selected.options[document.order_by.selected.selectedIndex].value;
  }
}

function remove_all() {
  if (confirm("<?=JS_MANUFACTURERS_DELETE_CONFIRM;?>")) {
    document.location = "<?=FILENAME_MANUFACTURERS;?>?action=delete_manufacturer&manufacturers_id=<?=$HTTP_GET_VARS['manufacturers_id'];?>&order_by=<?=$order_by;?>";
  } else {
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
    $manufacturers = tep_db_query("select manufacturers.manufacturers_id, manufacturers.manufacturers_name, manufacturers.manufacturers_location, manufacturers.manufacturers_image, category_top.category_top_id, category_top.category_top_name from manufacturers, manufacturers_to_category, category_top where manufacturers.manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id'] . "' and manufacturers_to_category.manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id'] . "' and manufacturers_to_category.category_top_id = category_top.category_top_id order by manufacturers.manufacturers_name");  
    $manufacturers_values = tep_db_fetch_array($manufacturers);
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=$manufacturers_values['manufacturers_name'];?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_CATALOG . $manufacturers_values['manufacturers_image'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', $manufacturers_values['manufacturers_name']);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="3"><?=tep_black_line();?></td>
          </tr>
<?
    $products = tep_db_query("select products.products_id, products.products_name, products.products_image from products, products_to_manufacturers where products.products_id = products_to_manufacturers.products_id and products_to_manufacturers.manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id'] . "' order by products_name");
    if (tep_db_num_rows($products)) {
?>
          <tr>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_PRODUCTS_ID;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_PRODUCTS;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_SUBCATEGORIES;?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td colspan="3"><?=tep_black_line();?></td>
          </tr>
<?
      while ($products_values = tep_db_fetch_array($products)) {
        tep_products_subcategories($products_values['products_id']); // returns $products_subcategories
        $rows++;
        if (floor($rows/2) == ($rows/2)) {
          echo '          <tr bgcolor="#ffffff">' . "\n";
        } else {
          echo '          <tr bgcolor="#f4f7fd">' . "\n";
        }
?>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$products_values['products_id'];?>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$products_values['products_name'];?>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$products_subcategories;?>&nbsp;</font></td>
          </tr>
<?
      }
?>
          <tr>
            <td colspan="3"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td colspan="3"><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><?=TEXT_WARNING_OF_DELETE;?></font></td>
          </tr>
          <tr>
            <td align="right" colspan="3"><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><a href="javascript:remove_all();"><?=tep_image(DIR_IMAGES . 'button_delete.gif', '50', '14', '0', IMAGE_DELETE);?></a>&nbsp;&nbsp;<?='<a href="' . tep_href_link(FILENAME_MANUFACTURERS, '&order_by=' . $order_by . '&page=' . $page, 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_cancel.gif', '50', '14', '0', IMAGE_CANCEL);?></a>&nbsp;&nbsp;</font></td>
          </tr>
<?
    } else {
?>
          <tr>
            <td colspan="3"><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><?=TEXT_OK_TO_DELETE;?></font></td>
          </tr>
          <tr>
            <td align="right" colspan="3"><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><?='<a href="' . tep_href_link(FILENAME_MANUFACTURERS, 'action=delete_manufacturer&manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&order_by=' . $order_by, 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_delete.gif', '50', '14', '0', IMAGE_DELETE);?></a>&nbsp;&nbsp;<?='<a href="' . tep_href_link(FILENAME_MANUFACTURERS, '&order_by=' . $order_by . '&page=' . $page, 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_cancel.gif', '50', '14', '0', IMAGE_CANCEL);?></a>&nbsp;&nbsp;</font></td>
          </tr>
<?
    }
?>
        </table></td>
      </tr>
<?
  } else {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?
    if ($HTTP_GET_VARS['order_by']) {
      $order_by = $HTTP_GET_VARS['order_by'];
    } else {
      $order_by = 'manufacturers_id';
    }
?>
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap><br><form name="order_by"><select name="selected" onChange="go()"><option value="manufacturers_name"<? if ($order_by == 'manufacturers_name') { echo ' SELECTED'; } ?>>Manufacturers Name</option><option value="manufacturers_id"<? if ($order_by == 'manufacturers_id') { echo ' SELECTED'; } ?>>Manufacturers ID</option></select>&nbsp;&nbsp;</form></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr><td colspan=6><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">
<?
$per_page = MAX_ROW_LISTS;
$manufacturers = ("select manufacturers_id, manufacturers_name, manufacturers_location, manufacturers_image from manufacturers order by '" . $order_by . "'");
if (!$page)
 {
   $page = 1;
 }
$prev_page = $page - 1;
$next_page = $page + 1;

$query = tep_db_query($manufacturers);

$page_start = ($per_page * $page) - $per_page;
$num_rows = tep_db_num_rows($query);

if ($num_rows <= $per_page) {
   $num_pages = 1;
} else if (($num_rows % $per_page) == 0) {
   $num_pages = ($num_rows / $per_page);
} else {
   $num_pages = ($num_rows / $per_page) + 1;
}
$num_pages = (int) $num_pages;

if (($page > $num_pages) || ($page < 0)) {
   error("You have specified an invalid page number");
}

$manufacturers = $manufacturers . " LIMIT $page_start, $per_page";

// Previous
if ($prev_page)  {
   echo "<a href=\"$PHP_SELF?page=$prev_page&order_by=$order_by\"><< </a> | ";
}

for ($i = 1; $i <= $num_pages; $i++) {
   if ($i != $page) {
      echo " <a href=\"$PHP_SELF?page=$i&order_by=$order_by\">$i</a> | ";
   } else {
      echo " <b><font color=red>$i<font color=black></b> |";
   }
}

// Next
if ($page != $num_pages) {
   echo " <a href=\"$PHP_SELF?page=$next_page&order_by=$order_by\"> >></a>";
}
echo '</td></tr>';

?>
          <tr>
            <td colspan="6"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_CATEGORIES;?>&nbsp;</b></font></td>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_ID;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_MANUFACTURERS;?>&nbsp;</b></font></td>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_LOCATION;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_IMAGE;?>&nbsp;</b></font></td>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_ACTION;?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td colspan="6"><?=tep_black_line();?></td>
          </tr>
<?
    $manufacturers = tep_db_query("$manufacturers");
    $rows = 0;
    while ($manufacturers_values = tep_db_fetch_array($manufacturers)) {
      $rows++;
      if (floor($rows/2) == ($rows/2)) {
        echo '          <tr bgcolor="#ffffff">' . "\n";
      } else {
        echo '          <tr bgcolor="#f4f7fd">' . "\n";
      }
      if ($HTTP_GET_VARS['manufacturers_id'] == $manufacturers_values['manufacturers_id']) {
        echo '<form name="manufacturers" action="' . tep_href_link(FILENAME_MANUFACTURERS, 'action=update_manufacturer' . '&order_by=' . $order_by . '&page=' . $page, 'NONSSL') . '" method="post" onSubmit="return checkForm();">' . "\n";
?>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<select name="category_top_id"><?
        $categories = tep_db_query("select category_top_id, category_top_name from category_top order by category_top_id");
        while ($categories_values = tep_db_fetch_array($categories)) {
          $manufacturers_categories = tep_db_query("select category_top.category_top_id, manufacturers_to_category.manufacturers_to_category_id from category_top, manufacturers_to_category where manufacturers_to_category.manufacturers_id = '" . $manufacturers_values['manufacturers_id'] . "' and manufacturers_to_category.category_top_id = category_top.category_top_id order by category_top.category_top_name");
          $manufacturers_categories_values = tep_db_fetch_array($manufacturers_categories);
          echo '<option name="' . $categories_values['category_top_name'] . '" value="' . $categories_values['category_top_id'] . '"';
          if ($manufacturers_categories_values['category_top_id'] == $categories_values['category_top_id']) {
            echo ' SELECTED';
          }
          echo '>' . $categories_values['category_top_name'] . '</option>';
        } ?></select>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$manufacturers_values['manufacturers_id'];?><input type="hidden" name="manufacturers_id" value="<?=$manufacturers_values['manufacturers_id'];?>">&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="manufacturers_name" value="<?=$manufacturers_values['manufacturers_name'];?>" size="20">&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="manufacturers_location" value="<?=$manufacturers_values['manufacturers_location'];?>" size="2" maxlength="1">&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="manufacturers_image" value="<?=$manufacturers_values['manufacturers_image'];?>" size="20">&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><b>&nbsp;<?=tep_image_submit(DIR_IMAGES . 'button_update_red.gif', '50', '14', '0', IMAGE_UPDATE);?>&nbsp;&nbsp;<?='<a href="' . tep_href_link(FILENAME_MANUFACTURERS, '&order_by=' . $order_by . '&page=' . $page, 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_cancel.gif', '50', '14', '0', IMAGE_CANCEL);?></a>&nbsp;</font></td>
</form>
          </tr>
<?
      } else {
        tep_manufacturers_categories($manufacturers_values['manufacturers_id']); // returns $manufacturers_categories
?>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$manufacturers_categories;?>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$manufacturers_values['manufacturers_id'];?>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$manufacturers_values['manufacturers_name'];?>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$manufacturers_values['manufacturers_location'];?>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?
        if (file_exists(DIR_SERVER_ROOT . DIR_CATALOG . $manufacturers_values['manufacturers_image'])) {
          echo '<img src="images/dot_green.gif" width="4" height="4" border="0" alt=" Image Exists ">';
        } else {
          echo '<img src="images/dot_red.gif" width="4" height="4" border="0" alt=" Image Non-Existant ">';
        } ?>&nbsp;<a href="javascript:new_win('<?=DIR_CATALOG . $manufacturers_values['manufacturers_image'];?>')"><?=$manufacturers_values['manufacturers_image'];?></a>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?='<a href="' . tep_href_link(FILENAME_MANUFACTURERS, 'action=update&manufacturers_id=' . $manufacturers_values['manufacturers_id'] . '&order_by=' . $order_by . '&page=' . $page, 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_modify.gif', '50', '14', '0', IMAGE_MODIFY);?></a>&nbsp;&nbsp;<?='<a href="' . tep_href_link(FILENAME_MANUFACTURERS, 'action=delete&manufacturers_id=' . $manufacturers_values['manufacturers_id'] . '&order_by=' . $order_by . '&page=' . $page, 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_delete.gif', '50', '14', '0', IMAGE_DELETE);?></a>&nbsp;</font></td>
          </tr>
<?
  		$max_manufacturers_id_query = tep_db_query("select max(manufacturers_id) + 1 as next_id from manufacturers");
		$max_manufacturers_id_values = tep_db_fetch_array($max_manufacturers_id_query);
		$next_id = $max_manufacturers_id_values['next_id'];
      }
    }
?>
          <tr>
            <td colspan="6"><?=tep_black_line();?></td>
          </tr>
<?
    if (!$HTTP_GET_VARS['action'] == 'update') {
      if (floor($rows/2) == ($rows/2)) {
        echo '          <tr bgcolor="#f4f7fd">' . "\n";
      } else {
        echo '          <tr bgcolor="#ffffff">' . "\n";
      }
      echo '<form name="manufacturers" action="' . tep_href_link(FILENAME_MANUFACTURERS, 'action=add_manufacturer' . '&order_by=' . $order_by . '&page=' . $page, 'NONSSL') . '" method="post" enctype="multipart/form-data" onSubmit="return checkForm();">' . "\n";
?>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<select name="category_top_id"><?
      $categories = tep_db_query("select category_top_id, category_top_name from category_top order by category_top_id");
      while ($categories_values = tep_db_fetch_array($categories)) {
        echo '<option name="' . $categories_values['category_top_name'] . '" value="' . $categories_values['category_top_id'] . '">' . $categories_values['category_top_name'] . '</option>';
      } ?></select>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$next_id;?>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="manufacturers_name" size="20">&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="manufacturers_location" size="2" maxlength="1">&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="hidden" name="MAX_FILE_SIZE" value="200000"><input type="file" name="manufacturers_image" size="20">&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><b>&nbsp;<?=tep_image_submit(DIR_IMAGES . 'button_insert.gif', '50', '14', '0', IMAGE_INSERT);?>&nbsp;</font></td>
</form>
          </tr>
          <tr>
            <td colspan="6"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td align="right" colspan="6"><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=tep_image(DIR_IMAGES . 'dot_green.gif', '4', '4', '0', IMAGE_GREEN_DOT);?> <?=TEXT_IMAGE_EXISTS;?>&nbsp;&nbsp;&nbsp;<?=tep_image(DIR_IMAGES . 'dot_red.gif', '4', '4', '0', IMAGE_RED_DOT);?> <?=TEXT_IMAGE_DOES_NOT_EXIST;?>&nbsp;&nbsp;</font></td>
          </tr>
<?
    }
?>
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