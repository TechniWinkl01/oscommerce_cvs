<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_INDEXES; include(DIR_INCLUDES . 'include_once.php'); ?>
<?
  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'add_index') {
      tep_db_query("insert into category_index values ('', '" . $HTTP_POST_VARS['category_index_name'] . "', '" . $HTTP_POST_VARS['sql_select'] . "')");
      $category_index_id = tep_db_insert_id();
      tep_db_query("insert into category_index_to_top values ('', '" . $HTTP_POST_VARS['category_top_id'] . "', '" . $category_index_id . "', '" . $HTTP_POST_VARS['sort_order'] . "')");
      header('Location: ' . tep_href_link(FILENAME_INDEXES, '', 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'update_index') {
      tep_db_query("update category_index set category_index_name = '" . $HTTP_POST_VARS['category_index_name'] . "', sql_select = '" . $HTTP_POST_VARS['sql_select'] . "' where category_index_id = '" . $HTTP_POST_VARS['category_index_id'] . "'");
      tep_db_query("update category_index_to_top set category_top_id = '" . $HTTP_POST_VARS['category_top_id'] . "', sort_order = '" . $HTTP_POST_VARS['sort_order'] . "' where category_index_to_top_id = '" . $HTTP_POST_VARS['category_index_to_top_id'] . "'");
      header('Location: ' . tep_href_link(FILENAME_INDEXES, '', 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'delete_index') {
      tep_db_query("delete from category_index where category_index_id = '" . $HTTP_GET_VARS['index_id'] . "'");
      tep_db_query("delete from category_index_to_top where category_index_id = '" . $HTTP_GET_VARS['index_id'] . "'");
      header('Location: ' . tep_href_link(FILENAME_INDEXES, '', 'NONSSL')); tep_exit();
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
  var category_index_name = document.category_index.category_index_name.value;
  var sql_select = document.category_index.sql_select.value;
  var sort_order = document.category_index.sort_order.value;
  
  if (category_index_name.length < 1) {
    error_message = error_message + "<?=JS_INDEX_NAME;?>";
    error = 1;
  }
  
  if (sort_order.length < 1) {
    error_message = error_message + "<?=JS_INDEX_SORT_ORDER;?>";
    error = 1;
  }

  if (sql_select.length < 1) {
    error_message = error_message + "<?=JS_INDEX_SQL_SELECT;?>";
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
            <td bgcolor="<?=TOP_BAR_BACKGROUND_COLOR;?>" width="100%" nowrap><font face="<?=TOP_BAR_FONT_FACE;?>" size="<?=TOP_BAR_FONT_SIZE;?>" color="<?=TOP_BAR_FONT_COLOR;?>">&nbsp;<?=TOP_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_IMAGES . 'pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', '');?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr><td colspan=6><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">
<?
$per_page = MAX_ROW_LISTS;
$indexes = ("select category_top.category_top_name, category_index.category_index_id, category_index.category_index_name, category_index.sql_select, category_index_to_top.sort_order, category_index_to_top.category_index_to_top_id from category_index, category_index_to_top, category_top where category_index.category_index_id = category_index_to_top.category_index_id and category_index_to_top.category_top_id = category_top.category_top_id order by category_top.category_top_id, category_index_to_top.sort_order");
if (!$page)
 {
   $page = 1;
 }
$prev_page = $page - 1;
$next_page = $page + 1;

$query = tep_db_query($indexes);

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

	 $indexes = $indexes . " LIMIT $page_start, $per_page";

// Previous
if ($prev_page)  {
   echo "<a href=\"$PHP_SELF?page=$prev_page\"><< </a> | ";
}

for ($i = 1; $i <= $num_pages; $i++) {
   if ($i != $page) {
      echo " <a href=\"$PHP_SELF?page=$i\">$i</a> | ";
   } else {
      echo " <b><font color=red>$i<font color=black></b> |";
   }
}

// Next
if ($page != $num_pages) {
   echo " <a href=\"$PHP_SELF?page=$next_page\"> >></a>";
}
echo '</td></tr>';

?>
            <td colspan="6"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_CATEGORY;?>&nbsp;</b></font></td>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_ID;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_INDEX;?>&nbsp;</b></font></td>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_SORT_ORDER;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_SQL_SELECT;?>&nbsp;</b></font></td>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_ACTION;?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td colspan="6"><?=tep_black_line();?></td>
          </tr>
<?
  $indexes = tep_db_query("$indexes");
  while ($indexes_values = tep_db_fetch_array($indexes)) {
    $rows++;
    if (floor($rows/2) == ($rows/2)) {
      echo '          <tr bgcolor="#ffffff">' . "\n";
    } else {
      echo '          <tr bgcolor="#f4f7fd">' . "\n";
    }
    if (($HTTP_GET_VARS['action'] == 'update') && ($HTTP_GET_VARS['index_id'] == $indexes_values['category_index_id'])) {
      echo '<form name="category_index" action="' . tep_href_link(FILENAME_INDEXES, 'action=update_index', 'NONSSL') . '" method="post" onSubmit="return checkForm();">';
?>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<select name="category_top_id"><?
      $categories = tep_db_query("select category_top_id, category_top_name from category_top order by category_top_id");
      while($categories_values = tep_db_fetch_array($categories)) {
        if ($indexes_values['category_top_name'] == $categories_values['category_top_name']) {
          echo '<option name="' . $categories_values['category_top_name'] . '" value="' . $categories_values['category_top_id'] . '" SELECTED>' . $categories_values['category_top_name'] . '</option>';
        } else {
          echo '<option name="' . $categories_values['category_top_name'] . '" value="' . $categories_values['category_top_id'] . '">' . $categories_values['category_top_name'] . '</option>';
        }
      } ?></select>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$indexes_values['category_index_id'];?><input type="hidden" name="category_index_id" value="<?=$indexes_values['category_index_id'];?>"><input type="hidden" name="category_index_to_top_id" value="<?=$indexes_values['category_index_to_top_id'];?>">&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="category_index_name" value="<?=$indexes_values['category_index_name'];?>" size="20">&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="sort_order" value="<?=$indexes_values['sort_order'];?>" size="2">&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="sql_select" value="<?=$indexes_values['sql_select'];?>" size="20">&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=tep_image_submit(DIR_IMAGES . 'button_update_red.gif', '50', '14', '0', IMAGE_UPDATE);?>&nbsp;<?='<a href="' . tep_href_link(FILENAME_INDEXES, '', 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_cancel.gif', '50', '14', '0', IMAGE_CANCEL);?></a>&nbsp;</font></td>
<?
      echo '</form>' . "\n";
?>
          </tr>
<?
    } elseif (($HTTP_GET_VARS['action'] == 'delete') && ($HTTP_GET_VARS['index_id'] == $indexes_values['category_index_id'])) {
?>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<b><?=$indexes_values['category_top_name'];?></b>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<b><?=$indexes_values['category_index_id'];?></b>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<b><?=$indexes_values['category_index_name'];?></b>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<b><?=$indexes_values['sort_order'];?></b>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<b><?=$indexes_values['sql_select'];?></b>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<b><?='<a href="' . tep_href_link(FILENAME_INDEXES, 'action=delete_index&index_id=' . $HTTP_GET_VARS['index_id'], 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_confirm_red.gif', '50', '14', '0', IMAGE_CONFIRM);?></a>&nbsp;&nbsp;<?='<a href="' . tep_href_link(FILENAME_INDEXES, '', 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_cancel.gif', '50', '14', '0', IMAGE_CANCEL);?></a>&nbsp;</b></font></td>
          </tr>
<?
    } else {
?>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$indexes_values["category_top_name"];?>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$indexes_values["category_index_id"];?>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$indexes_values["category_index_name"];?>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$indexes_values["sort_order"];?>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$indexes_values["sql_select"];?>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?='<a href="' . tep_href_link(FILENAME_INDEXES, 'action=update&index_id=' . $indexes_values['category_index_id'], 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_modify.gif', '50', '14', '0', IMAGE_MODIFY);?></a>&nbsp;&nbsp;<?='<a href="' . tep_href_link(FILENAME_INDEXES, 'action=delete&index_id=' . $indexes_values['category_index_id'], 'NONSSL') , '">';?><?=tep_image(DIR_IMAGES . 'button_delete.gif', '50', '14', '0', IMAGE_DELETE);?></a>&nbsp;</font></td>
          </tr>
<?
    }
    $max_indexes_id_query = tep_db_query("select max(category_index_id) + 1 as next_id from category_index");
	$max_indexes_id_values = tep_db_fetch_array($max_indexes_id_query);
	$next_id = $max_indexes_id_values['next_id'];
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
    echo '<form name="category_index" action="' . tep_href_link(FILENAME_INDEXES, 'action=add_index', 'NONSSL') . '" method="post" onSubmit="return checkForm();">';
?>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<select name="category_top_id"><?
    $categories = tep_db_query("select category_top_id, category_top_name from category_top order by category_top_id");
    while ($categories_values = tep_db_fetch_array($categories)) {
      echo '<option name="' . $categories_values['category_top_name'] . '" value="' . $categories_values['category_top_id'] . '">' . $categories_values['category_top_name'] . '</option>';
    } ?></select>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$next_id;?>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="category_index_name" size="20">&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="sort_order" size="2">&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="sql_select" size="20">&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=tep_image_submit(DIR_IMAGES . 'button_insert.gif', '50', '14', '0', IMAGE_INSERT);?>&nbsp;</font></td>
</form>
          </tr>
          <tr>
            <td colspan="6"><?=tep_black_line();?></td>
          </tr>
<?
  }
?>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->

<!-- footer //-->
<? $include_file = DIR_INCLUDES . 'footer.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<? include('includes/application_bottom.php'); ?>