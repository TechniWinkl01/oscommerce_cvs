<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_CATEGORIES; include(DIR_INCLUDES . 'include_once.php'); ?>
<?
  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'add_category') {
      tep_db_query("insert into category_top values ('', '" . $HTTP_POST_VARS['category_top_name'] . "', '" . $HTTP_POST_VARS['sort_order'] . "', '')");
      $category_top_id = tep_db_insert_id();
      if (!empty($category_image)) {
      	tep_db_query("update category_top set category_image = 'images/" . $category_image_name . "' where category_top_id = '" . $category_top_id . "'");
      	$image_location = DIR_SERVER_ROOT . DIR_CATALOG_IMAGES . $category_image_name;
        if (file_exists($image_location)) {
          @unlink($image_location);
        }
        copy($category_image, $image_location);
      }
      tep_db_query("insert into category_index values ('', 'Products', 'subcategories')");
      $category_index_id = tep_db_insert_id();
      tep_db_query("insert into category_index_to_top values ('', '" . $category_top_id . "', '" . $category_index_id . "', 1)");
      tep_db_query("insert into category_index values ('', 'Manufacturers', 'manufacturers')");
      $category_index_id = tep_db_insert_id();
      tep_db_query("insert into category_index_to_top values ('', '" . $category_top_id . "', '" . $category_index_id . "', 2)");
      header('Location: ' . tep_href_link(FILENAME_CATEGORIES, '', 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'update_category') {
      tep_db_query("update category_top set category_top_name = '" . $HTTP_POST_VARS['category_top_name'] . "', sort_order = '" . $HTTP_POST_VARS['sort_order'] . "', category_image = '" . $HTTP_POST_VARS['category_image'] . "' where category_top_id = '" . $HTTP_POST_VARS['category_id'] . "'");
      header('Location: ' . tep_href_link(FILENAME_CATEGORIES, '', 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'delete_category') {
      $category_subcategories = tep_db_query("select subcategories_id from subcategories_to_category where category_top_id = '" . $HTTP_GET_VARS['category_id'] . "'");
      while ($category_subcategories_values = tep_db_fetch_array($category_subcategories)) {
        $products = tep_db_query("select products_id from products_to_subcategories where subcategories_id = '" . $category_subcategories_values['subcategories_id'] . "'");
        while ($products_delete = tep_db_fetch_array($products)) {
          $subcategories = tep_db_query("select count(*) as count from products_to_subcategories where products_id = '" . $products_delete['products_id'] . "'");
          $subcategories_values = tep_db_fetch_array($subcategories);
          if ($subcategories_values['count'] > 1) { // product has more than one subcategory - only delete selected subcategory link
            tep_db_query("delete from products_to_subcategories where products_id = '" . $products_delete['products_id'] . "' and subcategories_id = '" . $category_subcategories_values['subcategories_id'] . "'");
          } else { // product has only one subcategory.. delete all related data with this products_id
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
            if (file_exists(DIR_SERVER_ROOT . DIR_CATALOG . $products_image)) { // delete products image
              if (!@unlink(DIR_SERVER_ROOT . DIR_CATALOG . $products_image)) echo 'Can\'t delete this file:<br><br>' . DIR_SERVER_ROOT . DIR_CATALOG . $products_image . '<br><br>';
            }
          }
        }
        $subcategories = tep_db_query("select subcategories_image from subcategories where subcategories_id = '" . $category_subcategories_values['subcategories_id'] . "'");
        $subcategories_values = tep_db_fetch_array($subcategories);
        $subcategories_image = $subcategories_values['subcategories_image'];
        tep_db_query("delete from subcategories where subcategories_id = '" . $category_subcategories_values['subcategories_id'] . "'"); // delete subcategory
        if (file_exists(DIR_SERVER_ROOT . DIR_CATALOG . $subcategories_image)) { // delete subcategories image
          if (!@unlink(DIR_SERVER_ROOT . DIR_CATALOG . $subcategories_image)) echo 'Can\'t delete this file:<br><br>' . DIR_SERVER_ROOT . DIR_CATALOG . $subcategories_image . '<br><br>';
        }
      }
      $category_top = tep_db_query("select category_top.category_top_name, category_top.category_image from category_top where category_top_id = '" . $HTTP_GET_VARS['category_id'] . "'");
      $category_top_values = tep_db_fetch_array($category_top);
      if (file_exists(DIR_SERVER_ROOT . DIR_CATALOG . $category_top_values['category_image'])) {
        if (!@unlink(DIR_SERVER_ROOT . DIR_CATALOG . $category_top_values['category_image'])) echo 'Can\'t delete this file:<br><br>' . DIR_SERVER_ROOT . DIR_CATALOG . $category_top_values['category_image'] . '<br><br>';
      }
      tep_db_query("delete from category_top where category_top_id = '" . $HTTP_GET_VARS['category_id'] . "'");
      $indexes = tep_db_query("select category_index_id from category_index_to_top where category_top_id = '" . $HTTP_GET_VARS['category_id'] . "'");
      while ($indexes_values = tep_db_fetch_array($indexes)) {
        tep_db_query("delete from category_index where category_index_id = '" . $indexes_values['category_index_id'] . "'");
      }
      tep_db_query("delete from category_index_to_top where category_top_id = '" . $HTTP_GET_VARS['category_id'] . "'");
      tep_db_query("delete from manufacturers_to_category where category_top_id = '" . $HTTP_GET_VARS['category_id'] . "'");
      tep_db_query("delete from subcategories_to_category where category_top_id = '" . $HTTP_GET_VARS['category_id'] . "'");
      if (!@header('Location: ' . tep_href_link(FILENAME_CATEGORIES, '', 'NONSSL'))) echo 'Can\'t redirect -> <a href="' . tep_href_link(FILENAME_CATEGORIES, '', 'NONSSL') . '"><u>continue</u></a><br><br>'; tep_exit();
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
  var categories_name = document.categories.category_top_name.value;
  var sort_order = document.categories.sort_order.value;
  var categories_image = document.categories.category_image.value;
  
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

function new_win(image) {
  window.open(image,"image","height=80,width=120,toolbar=no,statusbar=no,scrollbars=no").focus();
}

function go() {
  if (document.order_by.selected.options[document.order_by.selected.selectedIndex].value != "none") {
    location = "<?=FILENAME_CATEGORIES;?>?order_by="+document.order_by.selected.options[document.order_by.selected.selectedIndex].value;
  }
}

function remove_all() {
  if (confirm("<?=JS_CATEGORY_DELETE_CONFIRM;?>")) {
    document.location = "<?=FILENAME_CATEGORIES;?>?action=delete_category&category_id=<?=$HTTP_GET_VARS['category_id'];?>";
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
  if ($HTTP_GET_VARS['action'] == 'delete') { // delete category
    $category_top = tep_db_query("select category_top.category_top_name, category_top.category_image from category_top where category_top_id = '" . $HTTP_GET_VARS['category_id'] . "'");
    $category_top_values = tep_db_fetch_array($category_top);
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=$category_top_values['category_top_name'];?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_CATALOG . $category_top_values['category_image'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', $category_top_values['category_top_name']);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="3"><?=tep_black_line();?></td>
          </tr>
<?
    $products = tep_db_query("select distinct products.products_id, products.products_name, products.products_image from products, products_to_subcategories, subcategories_to_category where subcategories_to_category.category_top_id = '" . $HTTP_GET_VARS['category_id'] . "' and subcategories_to_category.subcategories_id = products_to_subcategories.subcategories_id and products_to_subcategories.products_id = products.products_id order by products_name");
    if (tep_db_num_rows($products)) {
?>
          <tr>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_PRODUCTS_ID;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_PRODUCTS_NAME;?>&nbsp;</b></font></td>
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
            <td align="right" colspan="3"><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><a href="javascript:remove_all();"><?=tep_image(DIR_IMAGES . 'button_delete.gif', '50', '14', '0', ' delete ');?></a>&nbsp;&nbsp;<?='<a href="' . tep_href_link(FILENAME_CATEGORIES, '', 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_cancel.gif', '50', '14', '0', ' cancel ');?></a>&nbsp;&nbsp;</font></td>
          </tr>
<?
    } else {
?>
          <tr>
            <td colspan="3"><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><?=TEXT_OK_TO_DELETE;?></font></td>
          </tr>
          <tr>
            <td align="right" colspan="3"><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><?='<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=delete_category&category_id=' . $HTTP_GET_VARS['category_id'], 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_delete.gif', '50', '14', '0', 'Delete');?></a>&nbsp;&nbsp;&nbsp;<?='<a href="' . tep_href_link(FILENAME_CATEGORIES, '', 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_cancel.gif', '50', '14', '0', 'Cancel');?></a>&nbsp;&nbsp;</font></td>
          </tr>
<?
    }
?>
        </table></td>
      </tr>
<?
  } else { // modify or insert category
?>
      <tr>
        <td width="100%">
<?
    if ($HTTP_GET_VARS['order_by']) {
      $order_by = $HTTP_GET_VARS['order_by'];
    } else {
      $order_by = 'category_top_id';
    }
?>		
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap><br><form name="order_by"><select name="selected" onChange="go()"><option value="category_top_name"<? if ($order_by == 'category_top_name') { echo ' SELECTED'; } ?>>Category Name</option><option value="category_top_id"<? if ($order_by == 'category_top_id') { echo ' SELECTED'; } ?>>Category ID</option><option value="sort_order"<? if ($order_by == 'sort_order') { echo ' SELECTED'; } ?>>Category Sort</option></select>&nbsp;&nbsp;</form></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr><td colspan=5><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">
<?
$per_page = MAX_ROW_LISTS;
$categories = ("select category_top_id, category_top_name, sort_order, category_image from category_top order by '" . $order_by . "'");
if (!$page)
 {
   $page = 1;
 }
$prev_page = $page - 1;
$next_page = $page + 1;

$query = tep_db_query($categories);

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

	 $categories = $categories . " LIMIT $page_start, $per_page";
	 
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
            <td colspan="5"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_ID;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_CATEGORIES;?>&nbsp;</b></font></td>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_SORT_ORDER;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_IMAGE;?>&nbsp;</b></font></td>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_ACTION;?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td colspan="5"><?=tep_black_line();?></td>
          </tr>
<?
    $categories = tep_db_query("$categories");
    $rows = 0;
    while ($categories_values = tep_db_fetch_array($categories)) {
      $rows++;
      if (floor($rows/2) == ($rows/2)) {
        echo '          <tr bgcolor="#ffffff">' . "\n";
      } else {
        echo '          <tr bgcolor="#f4f7fd">' . "\n";
      }
      if (($HTTP_GET_VARS['action'] == 'update') && ($HTTP_GET_VARS['category_id'] == $categories_values['category_top_id'])) {
        echo '<form name="categories" action="' . tep_href_link(FILENAME_CATEGORIES, 'action=update_category', 'NONSSL') . '" method="post" onSubmit="return checkForm();">' . "\n";
?>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$categories_values["category_top_id"];?><input type="hidden" name="category_id" value="<?=$categories_values["category_top_id"];?>">&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="category_top_name" value="<?=$categories_values["category_top_name"];?>" size="20">&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="sort_order" value="<?=$categories_values["sort_order"];?>" size="2">&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="category_image" value="<?=$categories_values["category_image"];?>" size="20">&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=tep_image_submit(DIR_IMAGES . 'button_update_red.gif', '50', '14', '0', 'Update');?>&nbsp;<?='<a href="' . tep_href_link(FILENAME_CATEGORIES, '', 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_cancel.gif', '50', '14', '0', 'Cancel');?></a>&nbsp;</b></font></td>
</form>
          </tr>
<?
      } else {
?>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$categories_values['category_top_id'];?>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$categories_values['category_top_name'];?>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$categories_values['sort_order'];?>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?
        if (file_exists(DIR_SERVER_ROOT . DIR_CATALOG . $categories_values['category_image'])) {
          echo tep_image(DIR_IMAGES . 'dot_green.gif', '4', '4', '0', TEXT_IMAGE_EXISTS);
        } else {
          echo tep_image(DIR_IMAGES . 'dot_red.gif', '4', '4', '0', TEXT_IMAGE_DOES_NOT_EXIST);
        } ?>&nbsp;<a href="javascript:new_win('<?=DIR_CATALOG . $categories_values['category_image'];?>')"><?=$categories_values['category_image'];?></a>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?='<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=update&category_id=' . $categories_values['category_top_id'], 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_modify.gif', '50', '14', '0', IMAGE_MODIFY);?></a>&nbsp;&nbsp;<?='<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=delete&category_id=' . $categories_values['category_top_id'], 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_delete.gif', '50', '14', '0', IMAGE_DELETE);?></a>&nbsp;</font></td>
          </tr>
<?
      }
	  $max_category_query = tep_db_query("select max(category_top_id) + 1 as next_id from category_top");
	  $max_category_id_values = tep_db_fetch_array($max_category_query);
	  $next_id = $max_category_id_values['next_id'];
    }
?>
          <tr>
            <td colspan="5"><?=tep_black_line();?></td>
          </tr>
<?
    if (!$HTTP_GET_VARS['action'] == 'update') {
      $rows++;
      if (floor($rows/2) == ($rows/2)) {
        echo '          <tr bgcolor="#ffffff">';
      } else {
        echo '          <tr bgcolor="#f4f7fd">';
      }
      echo '<form name="categories" action="' . tep_href_link(FILENAME_CATEGORIES, 'action=add_category', 'NONSSL') . '" method="post" enctype="multipart/form-data" onSubmit="return checkForm();">' . "\n";
?>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$next_id;?>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="category_top_name" size="20">&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="sort_order" size="2">&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="hidden" name="MAX_FILE_SIZE" value="200000"><input type="file" name="category_image" size="20">&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><b>&nbsp;<?=tep_image_submit(DIR_IMAGES . 'button_insert.gif', '50', '14', '0', IMAGE_INSERT);?>&nbsp;</b></font></td>
</form>
          </tr>
          <tr>
            <td colspan="5"><?=tep_black_line();?></td>
          </tr>
<?
    }
?>
          <tr>
            <td align="right" colspan="5" nowrpa><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=tep_image(DIR_IMAGES . 'dot_green.gif', '4', '4', '0', IMAGE_GREEN_DOT);?>&nbsp;<?=TEXT_IMAGE_EXISTS;?>&nbsp;&nbsp;&nbsp;<?=tep_image(DIR_IMAGES . 'dot_red.gif', '4', '4', '0', IMAGE_RED_DOT);?>&nbsp;<?=TEXT_IMAGE_DOES_NOT_EXIST;?>&nbsp;&nbsp;</font></td>
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