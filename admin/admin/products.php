<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_MANUFACTURERS; include(DIR_INCLUDES . 'include_once.php'); ?>
<?
  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'save') {
      if (EXPERT_MODE) {
        $update_query .= "manufacturers_id = '" . $HTTP_POST_VARS['manufacturers_id'] . "', manufacturers_name = '" . $HTTP_POST_VARS['manufacturers_name'] . "', manufacturers_image = '" . $HTTP_POST_VARS['manufacturers_image'] . "', manufacturers_location = '" . $HTTP_POST_VARS['manufacturers_location'] . "'";
        $new_manufacturers_id = $HTTP_POST_VARS['manufacturers_id'];
      } else {
        $update_query .= "manufacturers_name = '" . $HTTP_POST_VARS['manufacturers_name'] . "', manufacturers_image = '" . $HTTP_POST_VARS['manufacturers_image'] . "', manufacturers_location = '" . $HTTP_POST_VARS['manufacturers_location'] . "'";
        $new_manufacturers_id = $HTTP_POST_VARS['original_manufacturers_id'];
      }
      if (tep_db_query("update manufacturers set " . $update_query . " where manufacturers_id = '" . $HTTP_POST_VARS['original_manufacturers_id'] . "'")) {
        header('Location: ' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params('action', 'info') . 'info=' . $new_manufacturers_id, 'NONSSL'));
        tep_exit();
      } else {
        header('Location: ' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params('action', 'info') . 'error=SAVE', 'NONSSL'));
        tep_exit();
      }
    } elseif ($HTTP_GET_VARS['action'] == 'deleteconfirm') {
      if (tep_db_query("delete from manufacturers where manufacturers_id = '" . $HTTP_POST_VARS['manufacturers_id'] . "'")) {
        header('Location: ' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params('action', 'info'), 'NONSSL'));
        tep_exit();
      } else {
        header('Location: ' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params('action', 'info') . 'error=DELETE', 'NONSSL'));
        tep_exit();
      }
    } elseif ($HTTP_GET_VARS['action'] == 'insert') {
      if (tep_db_query("insert into manufacturers (manufacturers_name, manufacturers_image, manufacturers_location) values ('" . $HTTP_POST_VARS['manufacturers_name'] . "', '" . $HTTP_POST_VARS['manufacturers_image'] . "', '" . $HTTP_POST_VARS['manufacturers_location'] . "')")) {
        header('Location: ' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params('action', 'info'), 'NONSSL'));
        tep_exit();
      } else {
        header('Location: ' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params('action', 'info') . 'error=INSERT', 'NONSSL'));
        tep_exit();
      }
    }
  }

  class Product_Info {
    var $id, $name, $image, $location, $products_count;
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
  
  if (manufacturers_location = "" || manufacturers_location.length < 1) {
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_ID;?>&nbsp;</b></font></td>
                <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_MANUFACTURERS;?>&nbsp;</b></font></td>
                <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;Qty.&nbsp;</b></font></td>
                <td align="right" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;Price&nbsp;</b></font></td>
                <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_ACTION;?>&nbsp;</b></font></td>
              </tr>
              <tr>
                <td colspan="5"><?=tep_black_line();?></td>
              </tr>
<?
  $rows = 0;
  $products_query = tep_db_query("select products_id, products_name, products_quantity, products_image, products_price from products order by products_name");
  while ($products = tep_db_fetch_array($products_query)) {
    $rows++;

    if (((!$HTTP_GET_VARS['info']) || (@$HTTP_GET_VARS['info'] == $products['products_id'])) && (!$product_info)) {
      $product_info = new Product_Info();
      $product_info->id = $products['products_id'];
      $product_info->name = $products['products_name'];
      $product_info->image = $products['products_image'];
    }

    if ($products['products_id'] == $product_info->id) {
      echo '              <tr bgcolor="#b0c8df">' . "\n";
    } else {
      if (floor($rows/2) == ($rows/2)) {
        echo '              <tr bgcolor="#ffffff">' . "\n";
      } else {
        echo '              <tr bgcolor="#f4f7fd">' . "\n";
      }
    }
?>
                <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$products['products_id'];?>&nbsp;</font></td>
                <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$products['products_name'];?>&nbsp;</font></td>
                <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$products['products_quantity'];?>&nbsp;</font></td>
                <td align="right"nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$products['products_price'];?>&nbsp;</font></td>
<?
    if ($products['products_id'] == $product_info->id) {
?>
                <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=tep_image(DIR_IMAGES . 'icon_arrow_right.gif', 13, 13, 0, '');?>&nbsp;</font></td>
<?
    } else {
?>
                <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?='<a href="' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params('info', 'action') . 'info=' . $manufacturers['manufacturers_id'], 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'icon_info.gif', '13', '13', '0', IMAGE_ICON_INFO) . '</a>';?>&nbsp;</font></td>
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
              <form name="manufacturers" <?='action="' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params('action') . 'action=insert', 'NONSSL') . '"';?> method="post" onSubmit="return checkForm();">
                <td align="center"><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;</font></td>
                <td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="manufacturers_name">&nbsp;</font></td>
                <td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="manufacturers_quantity">&nbsp;</font></td>
                <td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="manufacturers_price">&nbsp;</font></td>
                <td align="center"><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=tep_image_submit(DIR_IMAGES . 'button_insert.gif', '66', '20', '0', IMAGE_INSERT);?>&nbsp;</font></td>
              </form></tr>
              <tr>
                <td colspan="5"><?=tep_black_line();?></td>
              </tr>
              <tr>
                <td colspan="5"><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_MANUFACTURERS;?> <?=$rows;?>&nbsp;</font></td>
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
                <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="#ffffff">&nbsp;<b><?=$manufacturer_info->name;?></b>&nbsp;</font></td>
              </tr>
              <tr bgcolor="#81a2b6">
                <td><?=tep_black_line();?></td>
              </tr>
<?
  if ($HTTP_GET_VARS['action'] == 'edit') {
?>
              <tr bgcolor="#b0c8df"><form name="manufacturers" <?='action="' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params('action') . 'action=save', 'NONSSL') . '"';?> method="post"><input type="hidden" name="original_manufacturers_id" value="<?=$manufacturer_info->id;?>">
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=TEXT_EDIT_INTRO;?><br>&nbsp;</font></td>
                  </tr>
<?
    if (EXPERT_MODE) {
?>
                  <tr>
                    <td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_EDIT_MANUFACTURERS_ID;?><br>&nbsp;<input type="text" name="manufacturers_id" value="<?=$manufacturer_info->id;?>" size="2"><br>&nbsp;</font></td>
                  </tr>
<?
    }
?>
                  <tr>
                    <td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_EDIT_MANUFACTURERS_NAME;?><br>&nbsp;<input type="text" name="manufacturers_name" value="<?=$manufacturer_info->name;?>"><br>&nbsp;<br>&nbsp;<?=TEXT_EDIT_MANUFACTURERS_IMAGE;?><br>&nbsp;<input type="text" name="manufacturers_image" value="<?=$manufacturer_info->image;?>"><br>&nbsp;<br>&nbsp;<?=TEXT_EDIT_MANUFACTURERS_LOCATION;?><br>&nbsp;<input type="text" name="manufacturers_location" size="2" value="<?=$manufacturer_info->location;?>"><br>&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td align="center"><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=tep_image_submit(DIR_IMAGES . 'button_save.gif', '66', '20', '0', IMAGE_SAVE);?> <?='<a href="' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params('action'), 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>';?></font></td>
                  </tr>
                </table></td>
              </form></tr>
<?
  } elseif ($HTTP_GET_VARS['action'] == 'delete') {
?>
              <tr bgcolor="#b0c8df"><form name="manufacturers" <?='action="' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params('action') . 'action=deleteconfirm', 'NONSSL') . '"';?> method="post"><input type="hidden" name="manufacturers_id" value="<?=$manufacturer_info->id;?>">
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=TEXT_DELETE_INTRO;?>&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<b><?=$manufacturer_info->name;?></b>&nbsp;</font></td>
                  </tr>
<?
    if ($manufacturer_info->products_count > 0) {
?>
                  <tr>
                    <td><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=sprintf(TEXT_DELETE_WARNING_PRODUCTS, $manufacturer_info->products_count);?>&nbsp;</font></td>
                  </tr>
<?
    }
?>
                  <tr>
                    <td align="center"><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=tep_image_submit(DIR_IMAGES . 'button_delete.gif', '66', '20', '0', IMAGE_DELETE);?> <?='<a href="' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params('action'), 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_cancel.gif', '66', '20', '0', IMAGE_CANCEL) . '</a>';?></font></td>
                  </tr>
                </table></td>
              </form></tr>
<?
  } else {
?>
              <tr bgcolor="#b0c8df">
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td align="center"><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?='<a href="' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params('action') . 'action=edit', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_edit.gif', '66', '20', '0', IMAGE_EDIT) . '</a>';?> <?='<a href="' . tep_href_link(FILENAME_MANUFACTURERS, tep_get_all_get_params('action') . 'action=delete', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_delete.gif', '66', '20', '0', IMAGE_DELETE) . '</a>';?></font></td>
                  </tr>
                  <tr>
                    <td><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_DATE_ADDED;?>&nbsp;<br>&nbsp;<?=TEXT_LAST_MODIFIED;?>&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<? $image_size = @getimagesize(DIR_SERVER_ROOT . DIR_CATALOG . $manufacturer_info->image); if ($image_size) { echo tep_image(DIR_CATALOG . $manufacturer_info->image, $image_size[0], $image_size[1], 0, $manufacturer_info->name); } else { echo TEXT_IMAGE_NONEXISTENT; } ?>&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_PRODUCTS;?> <?=$manufacturer_info->products_count;?>&nbsp;</font></td>
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