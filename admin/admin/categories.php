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
      if (tep_db_query("delete from categories where categories_id = '" . $HTTP_POST_VARS['categories_id'] . "'")) {
        header('Location: ' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action', 'info'), 'NONSSL'));
        tep_exit();
      } else {
        header('Location: ' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action', 'info') . 'error=DELETE', 'NONSSL'));
        tep_exit();
      }
    } elseif ($HTTP_GET_VARS['action'] == 'moveconfirm') {
      if (tep_db_query("update categories set parent_id = '" . $HTTP_POST_VARS['move_to_category_id'] . "' where categories_id = '" . $HTTP_POST_VARS['categories_id'] . "'")) {
        header('Location: ' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action', 'info'), 'NONSSL'));
        tep_exit();
      } else {
        header('Location: ' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action', 'info') . 'error=MOVE', 'NONSSL'));
        tep_exit();
      }
    } elseif ($HTTP_GET_VARS['action'] == 'insert') {
      if (tep_db_query("insert into categories (categories_name, categories_image, parent_id, sort_order) values ('" . $HTTP_POST_VARS['categories_name'] . "', '" . $HTTP_POST_VARS['categories_image'] . "', '" . $current_category_id . "', '" . $HTTP_POST_VARS['sort_order'] . "')")) {
        header('Location: ' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action', 'info'), 'NONSSL'));
        tep_exit();
      } else {
        header('Location: ' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action', 'info') . 'error=INSERT', 'NONSSL'));
        tep_exit();
      }
    }
  }

  class Category_Info {
    var $id, $name, $image, $sort_order, $parent_id, $childs_count, $products_count;
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
      $categories_query = tep_db_query("select categories_name from categories where categories_id = '" . $cPath_array[$i] . "'");
      $categories = tep_db_fetch_array($categories_query);
      echo ' -> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath_new, 'NONSSL') . '" class="blacklink">' . $categories['categories_name'] . '</a>';
    }
  }
?>
            &nbsp;</font></td>
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
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
  $rows = 0;
  $categories_query = tep_db_query("select categories_id, categories_name, categories_image, parent_id, sort_order from categories where parent_id = '" . $current_category_id . "' order by sort_order, categories_name");
  while ($categories = tep_db_fetch_array($categories_query)) {
    $rows++;

// check to see if category has a child
    $category_childs_query = tep_db_query("select count(*) as total from categories where parent_id = '" . $categories['categories_id'] . "'");
    $category_childs = tep_db_fetch_array($category_childs_query);

    if (((!$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['info'] == $categories['categories_id'])) && (!$category_info)) {
      $category_products_query = tep_db_query("select count(*) as total from products_to_categories where categories_id = '" . $categories['categories_id'] . "'");
      $category_products = tep_db_fetch_array($category_products_query);

      $category_info = new Category_Info();
      $category_info->id = $categories['categories_id'];
      $category_info->name = $categories['categories_name'];
      $category_info->image = $categories['categories_image'];
      $category_info->parent_id = $categories['parent_id'];
      $category_info->sort_order = $categories['sort_order'];
      $category_info->childs_count = $category_childs['total'];
      $category_info->products_count = $category_products['total'];
    }

    if ($categories['categories_id'] == $category_info->id) {
      echo '              <tr bgcolor="#b0c8df">' . "\n";
    } else {
      if (floor($rows/2) == ($rows/2)) {
        echo '              <tr bgcolor="#ffffff">' . "\n";
      } else {
        echo '              <tr bgcolor="#f4f7fd">' . "\n";
      }
    }
?>
                <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$categories['categories_id'];?>&nbsp;</font></td>

<?
    if ($category_childs['total'] > 0) {
?>
                <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?='<a href="' . tep_href_link('categories.php', tep_get_path($categories['categories_id']), 'NONSSL') . '"><u>' . $categories['categories_name'] . '</u></a>';?>&nbsp;</font></td>
<?
    } else {
?>
                <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$categories['categories_name'];?>&nbsp;</font></td>
<?
    }
?>
                <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$categories['sort_order'];?>&nbsp;</font></td>
                <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$categories['categories_image'];?>&nbsp;</font></td>
<?
    if ($categories['categories_id'] == $category_info->id) {
?>
                <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=tep_image(DIR_IMAGES . 'icon_arrow_right.gif', 13, 13, 0, '');?>&nbsp;</font></td>
<?
    } else {
?>
                <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?='<a href="' . tep_href_link('categories.php', tep_get_all_get_params('info', 'action') . 'info=' . $categories['categories_id'], 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'icon_info.gif', '13', '13', '0', IMAGE_ICON_INFO) . '</a>';?>&nbsp;</font></td>
<?
    }
?>
              </tr>
<?
  }
?>
              <tr>
                <td colspan="5"><?=tep_black_line();?></td>
              </tr>
<?
  if (floor($rows/2) == ($rows/2)) {
    echo '              <tr bgcolor="#f4f7fd">' . "\n";
  } else {
    echo '              <tr bgcolor="#ffffff">' . "\n";
  }
?>
              <form name="categories" <?='action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action') . 'action=insert', 'NONSSL') . '"';?> method="post" onSubmit="return checkForm();">
                <td align="center"><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;</font></td>
                <td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="categories_name">&nbsp;</font></td>
                <td align="center"><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="sort_order" size="2">&nbsp;</font></td>
                <td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="categories_image">&nbsp;</font></td>
                <td align="center"><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=tep_image_submit(DIR_IMAGES . 'button_insert.gif', '66', '20', '0', IMAGE_INSERT);?>&nbsp;</font></td>
              </form></tr>
              <tr>
                <td colspan="5"><?=tep_black_line();?></td>
              </tr>
              <tr>
                <td colspan="5"><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_CATEGORIES;?> <?=$rows;?>&nbsp;</font></td>
              </tr>
<?
  if ($HTTP_GET_VARS['error']) {
?>
              <tr>
                <td colspan="5"><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="#ff0000">&nbsp;<?=ERROR_ACTION;?>&nbsp;</font></td>
              </tr>
<?
  }
?>
            </table></td>
            <td width="25%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr bgcolor="#81a2b6">
                <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="#ffffff">&nbsp;<b><?=$category_info->name;?></b>&nbsp;</font></td>
              </tr>
              <tr bgcolor="#81a2b6">
                <td><?=tep_black_line();?></td>
              </tr>
<?
  if ($HTTP_GET_VARS['action'] == 'edit') {
?>
              <tr bgcolor="#b0c8df"><form name="categories" <?='action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action') . 'action=save', 'NONSSL') . '"';?> method="post"><input type="hidden" name="original_categories_id" value="<?=$category_info->id;?>">
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=TEXT_EDIT_INTRO;?><br>&nbsp;</font></td>
                  </tr>
<?
    if (EXPERT_MODE) {
?>
                  <tr>
                    <td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_EDIT_CATEGORIES_ID;?><br>&nbsp;<input type="text" name="categories_id" value="<?=$category_info->id;?>" size="2"><br>&nbsp;</font></td>
                  </tr>
<?
    }
?>
                  <tr>
                    <td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_EDIT_CATEGORIES_NAME;?><br>&nbsp;<input type="text" name="categories_name" value="<?=$category_info->name;?>"><br>&nbsp;<br>&nbsp;<?=TEXT_EDIT_CATEGORIES_IMAGE;?><br>&nbsp;<input type="text" name="categories_image" value="<?=$category_info->image;?>"><br>&nbsp;<br>&nbsp;<?=TEXT_EDIT_SORT_ORDER;?><br>&nbsp;<input type="text" name="sort_order" size="2" value="<?=$category_info->sort_order;?>"><br>&nbsp;</font></td>
                  </tr>
<?
    if (EXPERT_MODE) {
?>
                  <tr>
                    <td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_EDIT_PARENT_ID;?><br>&nbsp;<input type="text" name="parent_id" value="<?=$category_info->parent_id;?>"><br>&nbsp;</font></td>
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
  } elseif ($HTTP_GET_VARS['action'] == 'delete') {
?>
              <tr bgcolor="#b0c8df"><form name="categories" <?='action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action') . 'action=deleteconfirm', 'NONSSL') . '"';?> method="post"><input type="hidden" name="categories_id" value="<?=$category_info->id;?>">
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=TEXT_DELETE_INTRO;?>&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<b><?=$category_info->name;?></b>&nbsp;</font></td>
                  </tr>
<?
    if ($category_info->childs_count > 0) {
?>
                  <tr>
                    <td><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=sprintf(TEXT_DELETE_WARNING_CHILDS, $category_info->childs_count);?>&nbsp;</font></td>
                  </tr>
<?
    }
?>
<?
    if ($category_info->products_count > 0) {
?>
                  <tr>
                    <td><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=sprintf(TEXT_DELETE_WARNING_PRODUCTS, $category_info->products_count);?>&nbsp;</font></td>
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
  } elseif ($HTTP_GET_VARS['action'] == 'move') {
?>
              <tr bgcolor="#b0c8df"><form name="categories" <?='action="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action') . 'action=moveconfirm', 'NONSSL') . '"';?> method="post"><input type="hidden" name="categories_id" value="<?=$category_info->id;?>">
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=TEXT_MOVE_INTRO;?>&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=sprintf(TEXT_MOVE, $category_info->name);?><br>&nbsp;<select name="move_to_category_id" style="font-size:10px">
<?
    $categories_all_query = tep_db_query("select categories_id, categories_name, parent_id from categories order by categories_name");
    while ($categories_all = tep_db_fetch_array($categories_all_query)) {
      if ($category_info->id != $categories_all['categories_id']) {
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
  } else {
?>
              <tr bgcolor="#b0c8df">
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td align="center"><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?='<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action') . 'action=edit', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_edit.gif', '66', '20', '0', IMAGE_EDIT) . '</a>';?> <?='<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action') . 'action=delete', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_delete.gif', '66', '20', '0', IMAGE_DELETE) . '</a>';?> <?='<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params('action') . 'action=move', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_move.gif', '66', '20', '0', IMAGE_MOVE) . '</a>';?></font></td>
                  </tr>
                  <tr>
                    <td><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_DATE_ADDED;?>&nbsp;<br>&nbsp;<?=TEXT_LAST_MODIFIED;?>&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<? $image_size = @getimagesize(DIR_SERVER_ROOT . DIR_CATALOG . $category_info->image); if ($image_size) { echo tep_image(DIR_CATALOG . $category_info->image, $image_size[0], $image_size[1], 0, $category_info->name); } else { echo TEXT_IMAGE_NONEXISTENT; } ?>&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_SUBCATEGORIES;?> <?=$category_info->childs_count;?><br>&nbsp;<?=TEXT_PRODUCTS;?> <?=$category_info->products_count;?>&nbsp;</font></td>
                  </tr>
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