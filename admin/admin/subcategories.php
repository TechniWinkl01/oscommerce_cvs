<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_SUBCATEGORIES; include(DIR_INCLUDES . 'include_once.php'); ?>
<?
  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'add_subcategory') {
      tep_db_query("insert into subcategories values ('', '" . $HTTP_POST_VARS['subcategories_name'] . "', '')");
      $subcategories_id = tep_db_insert_id();
      tep_db_query("insert into subcategories_to_category values ('', '" . $subcategories_id . "', '" . $HTTP_POST_VARS['category_top_id'] . "')");
      if (!empty($subcategories_image)) {
      	tep_db_query("update subcategories set subcategories_image = 'images/" . $subcategories_image_name . "' where subcategories_id = '" . $subcategories_id . "'");
      	$image_location = DIR_SERVER_ROOT . DIR_CATALOG_IMAGES . $subcategories_image_name;
        if (file_exists($image_location)) {
          @unlink($image_location);
        }
        copy($subcategories_image, $image_location);
      }
      header('Location: ' . tep_href_link(FILENAME_SUBCATEGORIES, '&order_by=' . $order_by . '&page=' . $page, 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'update_subcategory') {
      tep_db_query("update subcategories set subcategories_name = '" . $HTTP_POST_VARS['subcategories_name'] . "', subcategories_image = '" . $HTTP_POST_VARS['subcategories_image'] . "' where subcategories_id = '" . $HTTP_POST_VARS['subcategories_id'] . "'");
      tep_db_query("update subcategories_to_category set category_top_id = '" . $HTTP_POST_VARS['category_top_id'] . "' where subcategories_id = '" . $HTTP_POST_VARS['subcategories_id'] . "'");
      header('Location: ' . tep_href_link(FILENAME_SUBCATEGORIES, '&order_by=' . $order_by . '&page=' . $page, 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'delete_subcategory') {
      $products = tep_db_query("select products_id from products_to_subcategories where subcategories_id = '" . $HTTP_GET_VARS['subcategories_id'] . "'");
      while ($products_delete = tep_db_fetch_array($products)) {
        $subcategories = tep_db_query("select count(*) as count from products_to_subcategories where products_id = '" . $products_delete['products_id'] . "'");
        $subcategories_values = tep_db_fetch_array($subcategories);
        if ($subcategories_values['count'] > 1) { // product has more than one subcategory - only delete selected subcategory link
          tep_db_query("delete from products_to_subcategories where products_id = '" . $products_delete['products_id'] . "' and subcategories_id = '" . $HTTP_GET_VARS['subcategories_id'] . "'");
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
      $subcategories = tep_db_query("select subcategories_image from subcategories where subcategories_id = '" . $HTTP_GET_VARS['subcategories_id'] . "'");
      $subcategories_values = tep_db_fetch_array($subcategories);
      $subcategories_image = $subcategories_values['subcategories_image'];
      tep_db_query("delete from subcategories where subcategories_id = '" . $HTTP_GET_VARS['subcategories_id'] . "'"); // delete subcategory
      if (file_exists(DIR_SERVER_ROOT . DIR_CATALOG . $subcategories_image)) { // delete subcategories image
        if (!@unlink(DIR_SERVER_ROOT . DIR_CATALOG . $subcategories_image)) echo 'Can\'t delete this file:<br><br>' . DIR_SERVER_ROOT . DIR_CATALOG . $subcategories_image . '<br><br>';
      }
      tep_db_query("delete from subcategories_to_category where subcategories_top_id = '" . $HTTP_GET_VARS['subcategories_id'] . "'");
      header('Location: ' . tep_href_link(FILENAME_SUBCATEGORIES, '&order_by=' . $order_by . '&page=' . $page, 'NONSSL')); tep_exit();
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
  var subcategories_name = document.subcategories.subcategories_name.value;
  var subcategories_image = document.subcategories.subcategories_image.value;
  
  if (subcategories_name.length < 1) {
    error_message = error_message + "<?=JS_SUBCATEGORIES_NAME;?>";
    error = 1;
  }
  
  if (subcategories_image.length < 1) {
    error_message = error_message + "<?=JS_SUBCATEGORIES_IMAGE;?>";
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
    location = "<?=FILENAME_SUBCATEGORIES;?>?order_by="+document.order_by.selected.options[document.order_by.selected.selectedIndex].value;
  }
}

function remove_all() {
  if (confirm("<?=JS_SUBCATEGORIES_DELETE_CONFIRM;?>")) {
    document.location = "<?=FILENAME_SUBCATEGORIES;?>?action=delete_subcategory&subcategories_id=<?=$HTTP_GET_VARS['subcategories_id'];?>&order_by=<?=$order_by;?>";
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
    $subcategories = tep_db_query("select subcategories.subcategories_id, subcategories.subcategories_name, subcategories.subcategories_image, category_top.category_top_id, category_top.category_top_name from subcategories, subcategories_to_category, category_top where subcategories.subcategories_id = '" . $HTTP_GET_VARS['subcategories_id'] . "' and subcategories_to_category.subcategories_id = '" . $HTTP_GET_VARS['subcategories_id'] . "' and subcategories_to_category.category_top_id = category_top.category_top_id order by subcategories.subcategories_name");  
    $subcategories_values = tep_db_fetch_array($subcategories);
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=$subcategories_values['subcategories_name'];?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_CATALOG . $subcategories_values['subcategories_image'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', $subcategories_values['subcategories_name']);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="3"><?=tep_black_line();?></td>
          </tr>
<?
    $products = tep_db_query("select products.products_id, products.products_name, products.products_image from products, products_to_subcategories where products.products_id = products_to_subcategories.products_id and products_to_subcategories.subcategories_id = '" . $HTTP_GET_VARS['subcategories_id'] . "' order by products_name");
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
            <td align="right" colspan="3"><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><a href="javascript:remove_all();"><?=tep_image(DIR_IMAGES . 'button_delete.gif', '50', '14', '0', 'Delete');?></a>&nbsp;&nbsp;<?='<a href="' . tep_href_link(FILENAME_SUBCATEGORIES, '&order_by=' . $order_by . '&page=' . $page, 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_cancel.gif', '50', '14', '0', 'Cancel');?></a>&nbsp;&nbsp;</font></td>
          </tr>
<?
    } else {
?>
          <tr>
            <td colspan="3"><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><?=TEXT_OK_TO_DELETE;?></font></td>
          </tr>
          <tr>
            <td align="right" colspan="3"><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><?='<a href="' . tep_href_link(FILENAME_SUBCATEGORIES, 'action=delete_subcategory&subcategories_id=' . $HTTP_GET_VARS['subcategories_id'], 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_delete.gif', '50', '14', '0', IMAGE_DELETE);?></a>&nbsp;&nbsp;<?='<a href="' . tep_href_link(FILENAME_SUBCATEGORIES, '&order_by=' . $order_by . '&page=' . $page, 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_cancel.gif', '50', '14', '0', IMAGE_CANCEL);?></a>&nbsp;&nbsp;</font></td>
          </tr>
<?
    }
?>
        </table></td>
      </tr>
<?
  } else  {
?>
      <tr>
        <td width="100%">
<?
    if ($HTTP_GET_VARS['order_by']) {
      $order_by = $HTTP_GET_VARS['order_by'];
    } else {
      $order_by = 'subcategories_id';
    }
?>
          <table border="0" width="100%" cellspacing="0" cellpadding="0">
		  <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap><br><form name="order_by"><select name="selected" onChange="go()"><option value="subcategories_name"<? if ($order_by == 'subcategories_name') { echo ' SELECTED'; } ?>>Subcategories Name</option><option value="subcategories_id"<? if ($order_by == 'subcategories_id') { echo ' SELECTED'; } ?>>Subcategories ID</option></select>&nbsp;&nbsp;</form></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr><td colspan=5><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">
<?
$per_page = MAX_ROW_LISTS;
$subcategories = ("select subcategories_id, subcategories_name, subcategories_image  from subcategories order by '" . $order_by . "'");
if (!$page)
 {
   $page = 1;
 }
$prev_page = $page - 1;
$next_page = $page + 1;

$query = tep_db_query($subcategories);

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

$subcategories = $subcategories . " LIMIT $page_start, $per_page";

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
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_CATEGORIES;?>&nbsp;</b></font></td>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_ID;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_SUBCATEGORIES;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_IMAGE;?>&nbsp;</b></font></td>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_ACTION;?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td colspan="5"><?=tep_black_line();?></td>
          </tr>
<?
    $subcategories = tep_db_query("$subcategories");
    while ($subcategories_values = tep_db_fetch_array($subcategories)) {
      $rows++;
      if (floor($rows/2) == ($rows/2)) {
        echo '          <tr bgcolor="#ffffff">' . "\n";
      } else {
        echo '          <tr bgcolor="#f4f7fd">' . "\n";
      }
      if ($HTTP_GET_VARS['subcategories_id'] == $subcategories_values['subcategories_id']) {
        echo '<form name="subcategories" action="' . tep_href_link(FILENAME_SUBCATEGORIES, 'action=update_subcategory' . '&order_by=' . $order_by . '&page=' . $page, 'NONSSL') . '" method="post" onSubmit="return checkForm();">' . "\n";
?>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<select name="category_top_id"><?
        $categories = tep_db_query("select category_top_id, category_top_name from category_top order by category_top_id");
        while ($categories_values = tep_db_fetch_array($categories)) {
          $subcategories_categories = tep_db_query("select category_top.category_top_id, subcategories_to_category.subcategories_to_category_id from category_top, subcategories_to_category where subcategories_to_category.subcategories_id = '" . $subcategories_values['subcategories_id'] . "' and subcategories_to_category.category_top_id = category_top.category_top_id order by category_top.category_top_name");
          $subcategories_categories_values = tep_db_fetch_array($subcategories_categories);
          echo '<option name="' . $categories_values['category_top_name'] . '" value="' . $categories_values['category_top_id'] . '"';
          if ($subcategories_categories_values['category_top_id'] == $categories_values['category_top_id']) {
            echo ' SELECTED';
          }
          echo '>' . $categories_values['category_top_name'] . '</option>';
        } ?></select>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$subcategories_values['subcategories_id'];?><input type="hidden" name="subcategories_id" value="<?=$subcategories_values['subcategories_id'];?>"><input type="hidden" name="subcategories_to_category_id" value="<?=$subcategories_values['subcategories_to_category_id'];?>">&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="subcategories_name" value="<?=$subcategories_values['subcategories_name'];?>" size="20">&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="subcategories_image" value="<?=$subcategories_values['subcategories_image'];?>" size="20">&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=tep_image_submit(DIR_IMAGES . 'button_update_red.gif', '50', '14', '0', IMAGE_UPDATE);?>&nbsp;&nbsp;<?='<a href="' . tep_href_link(FILENAME_SUBCATEGORIES, '&order_by=' . $order_by . '&page=' . $page, 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_cancel.gif', '50', '14', '0', IMAGE_CANCEL);?></a>&nbsp;</font></td>
</form>
          </tr>
<?
      } else {
        tep_subcategories_categories($subcategories_values['subcategories_id']); // returns $subcategories_categories
?>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$subcategories_categories;?>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$subcategories_values['subcategories_id'];?>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$subcategories_values['subcategories_name'];?>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?
        if (file_exists(DIR_SERVER_ROOT . DIR_CATALOG . $subcategories_values['subcategories_image'])) {
          echo tep_image(DIR_IMAGES . 'dot_green.gif', '4', '4', '0', TEXT_IMAGE_EXISTS);
        } else {
          echo tep_image(DIR_IMAGES . 'dot_red.gif', '4', '4', '0', TEXT_IMAGE_DOES_NOT_EXIST);
        } ?>&nbsp;<a href="javascript:new_win('<?=DIR_CATALOG . $subcategories_values['subcategories_image'];?>')"><?=$subcategories_values['subcategories_image'];?></a>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?='<a href="' . tep_href_link(FILENAME_SUBCATEGORIES, 'action=update&subcategories_id=' . $subcategories_values['subcategories_id'] . '&order_by=' . $order_by . '&page=' . $page, 'NONSSL') , '">';?><?=tep_image(DIR_IMAGES . 'button_modify.gif', '50', '14', '0', IMAGE_MODIFY);?></a>&nbsp;&nbsp;<?='<a href="' . tep_href_link(FILENAME_SUBCATEGORIES, 'action=delete&subcategories_id=' . $subcategories_values['subcategories_id'] . '&order_by=' . $order_by . '&page=' . $page, 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_delete.gif', '50', '14', '0', IMAGE_DELETE);?></a>&nbsp;</font></td>
          </tr>
<?
  		$max_subcategories_id_query = tep_db_query("select max(subcategories_id) + 1 as next_id from subcategories");
		$max_subcategories_id_values = tep_db_fetch_array($max_subcategories_id_query);
		$next_id = $max_subcategories_id_values['next_id'];
      }
    }
?>
          <tr>
            <td colspan="5"><?=tep_black_line();?></td>
          </tr>
<?
    if (!$HTTP_GET_VARS['action'] == 'update') {
      if (floor($rows/2) == ($rows/2)) {
        echo '          <tr bgcolor="#f4f7fd">' . "\n";
      } else {
        echo '          <tr bgcolor="#ffffff">' . "\n";
      }
      echo '<form name="subcategories" action="' . tep_href_link(FILENAME_SUBCATEGORIES, 'action=add_subcategory' . '&order_by=' . $order_by . '&page=' . $page, 'NONSSL') . '" method="post" enctype="multipart/form-data" onSubmit="return checkForm();">' . "\n";
?>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<select name="category_top_id"><?
      $categories = tep_db_query("select category_top_id, category_top_name from category_top order by category_top_id");
      while ($categories_values = tep_db_fetch_array($categories)) {
        echo '<option name="' . $categories_values['category_top_name'] . '" value="' . $categories_values['category_top_id'] . '">' . $categories_values['category_top_name'] . '</option>';
      } ?></select>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$next_id;?>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="subcategories_name" size="20">&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="hidden" name="MAX_FILE_SIZE" value="200000"><input type="file" name="subcategories_image" size="20">&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><b>&nbsp;<?=tep_image_submit(DIR_IMAGES . 'button_insert.gif', '50', '14', '0', IMAGE_INSERT);?>&nbsp;</font></td>
</form>
          </tr>
          <tr>
            <td colspan="5"><?=tep_black_line();?></td>
          </tr>
<?
    }
?>
          <tr>
            <td align="right" colspan="5"><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=tep_image(DIR_IMAGES . 'dot_green.gif', '4', '4', '0', IMAGE_GREEN_DOT);?> <?=TEXT_IMAGE_EXISTS;?>&nbsp;&nbsp;&nbsp;<?=tep_image(DIR_IMAGES . 'dot_red.gif', '4', '4', '0', IMAGE_RED_DOT);?> <?=TEXT_IMAGE_DOES_NOT_EXIST;?>&nbsp;&nbsp;</font></td>
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