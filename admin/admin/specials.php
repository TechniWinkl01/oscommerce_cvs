<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_SPECIALS; include(DIR_INCLUDES . 'include_once.php'); ?>
<?
  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'add_specials') {
      $date_now = date('Ymd');
      tep_db_query("insert into specials values ('', '" . $HTTP_POST_VARS['products_id'] . "', '" . $HTTP_POST_VARS['products_price'] . "', '" . $date_now . "')");
      header('Location: ' . tep_href_link(FILENAME_SPECIALS, '', 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'update_specials') {
      $date_updated = substr($HTTP_POST_VARS['date_added'], -4) . substr($HTTP_POST_VARS['date_added'], 3, 2) . substr($HTTP_POST_VARS['date_added'], 0, 2);
      tep_db_query("update specials set specials_new_products_price = '" . $HTTP_POST_VARS['products_price'] . "', specials_date_added = '" . $date_updated . "' where specials_id = '" . $HTTP_POST_VARS['specials_id'] . "'");
      header('Location: ' . tep_href_link(FILENAME_SPECIALS, '', 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'delete_specials') {
      tep_db_query("delete from specials where specials_id = '" . $HTTP_GET_VARS['specials_id'] . "'");
      header('Location: ' . tep_href_link(FILENAME_SPECIALS, '', 'NONSSL')); tep_exit();
    }
  }
?>
<html>
<head>
<title><?=TITLE;?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript"><!--
function checkForm(form) {
  var error_message = "<?=JS_ERROR;?>";
  var error = 0;
  var products_price = document.specials.products_price.value;

  if (products_price.length < 1) {
    error_message = error_message + "<?=JS_SPECIALS_PRODUCTS_PRICE;?>";
    error = 1;
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}

function go() {
  if (document.specials.selected.options[document.specials.selected.selectedIndex].value != "none") {
    location = "<?=FILENAME_SPECIALS;?>?products_id="+document.specials.selected.options[document.specials.selected.selectedIndex].value;
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
          <tr>
            <td colspan="5"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_SUBCATEGORIES;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_PRODUCTS;?>&nbsp;</b></font></td>
            <td align="right" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_PRODUCTS_PRICE;?>&nbsp;</b></font></td>
            <td align="right" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_DATE_ADDED;?>&nbsp;</b></font></td>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_ACTION;?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td colspan="5"><?=tep_black_line();?></td>
          </tr>
<?
  $specials = tep_db_query("select products.products_id, products.products_name, products.products_price, specials.specials_id, specials.specials_new_products_price, specials.specials_date_added from products, specials where products.products_id = specials.products_id order by specials.specials_date_added DESC");
  while ($specials_values = tep_db_fetch_array($specials)) {
    tep_products_subcategories($specials_values['products_id']); // returns $products_subcategories
    tep_products_name($specials_values['products_id']); // returns $products_name
    $rows++;
    if (floor($rows/2) == ($rows/2)) {
      echo '          <tr bgcolor="#ffffff">' . "\n";
    } else {
      echo '          <tr bgcolor="#f4f7fd">' . "\n";
    }
    $date_added_formated = substr($specials_values['specials_date_added'], -2) . '/' . substr($specials_values['specials_date_added'], -4, 2) . '/' . substr($specials_values['specials_date_added'], 0, 4);
    if (($HTTP_GET_VARS['action'] == 'update') && ($specials_values['specials_id'] == $HTTP_GET_VARS['specials_id'])) {
      echo '<form name="specials" action="' . tep_href_link(FILENAME_SPECIALS, 'action=update_specials', 'NONSSL') . '" method="post" onSubmit="return checkForm();"><input type="hidden" name="specials_id" value="' . $HTTP_GET_VARS['specials_id'] . '">' . "\n";
?>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$products_subcategories;?>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$products_name;?>&nbsp;</font></td>
            <td align="right" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<s>$<?=$specials_values['products_price'];?></s> <input type="text" name="products_price" value="<?=$specials_values['specials_new_products_price'];?>" size="10">&nbsp;</font></td>
            <td align="right" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="date_added" value="<?=$date_added_formated;?>" size="10" maxlength="10">&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=tep_image_submit(DIR_IMAGES . 'button_update_red.gif', '50', '14', '0', IMAGE_UPDATE);?>&nbsp;<?='<a href="' . tep_href_link(FILENAME_SPECIALS, '', 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_cancel.gif', '50', '14', '0', IMAGE_CANCEL);?></a>&nbsp;</font></td>
</form>
          </tr>
<?
    } elseif (($HTTP_GET_VARS['action'] == 'delete') && ($specials_values['specials_id'] == $HTTP_GET_VARS['specials_id'])) {
?>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<b><?=$products_subcategories;?></b>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<b><?=$products_name;?></b>&nbsp;</font></td>
            <td align="right" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<b><s>$<?=$specials_values['products_price'];?></s> <font color="<?=SPECIALS_PRICE_COLOR;?>">$<?=$specials_values['specials_new_products_price'];?></font></b>&nbsp;</font></td>
            <td align="right" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<b><?=$date_added_formated;?></b>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?='<a href="' . tep_href_link(FILENAME_SPECIALS, 'action=delete_specials&specials_id=' . $HTTP_GET_VARS['specials_id'], 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_confirm_red.gif', '50', '14', '0', IMAGE_CONFIRM);?></a>&nbsp;<?='<a href="' . tep_href_link(FILENAME_SPECIALS, '', 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_cancel.gif', '50', '14', '0', IMAGE_CANCEL);?></a>&nbsp;</font></td>
          </tr>
<?
    } else {
?>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$products_subcategories;?>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$products_name;?>&nbsp;</font></td>
            <td align="right" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<s>$<?=$specials_values['products_price'];?></s> <font color="<?=SPECIALS_PRICE_COLOR;?>">$<?=$specials_values['specials_new_products_price'];?></font>&nbsp;</font></td>
            <td align="right" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$date_added_formated;?>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?='<a href="' . tep_href_link(FILENAME_SPECIALS, 'action=update&specials_id=' . $specials_values['specials_id'], 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_modify.gif', '50', '14', '0', IMAGE_MODIFY);?></a>&nbsp;<?='<a href="' . tep_href_link(FILENAME_SPECIALS, 'action=delete&specials_id=' . $specials_values['specials_id'], 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_delete.gif', '50', '14', '0', IMAGE_DELETE);?></a>&nbsp;</font></td>
          </tr>
<?
    }
  }
?>
          <tr>
            <td colspan="5"><?=tep_black_line();?></td>
          </tr>
<?
  if (!$HTTP_GET_VARS['action']) {
    if (floor($rows/2) == ($rows/2)) {
      echo '          <tr bgcolor="#f4f7fd">' . "\n";
    } else {
      echo '          <tr bgcolor="#ffffff">' . "\n";
    }
    echo '<form name="specials" action="' . tep_href_link(FILENAME_SPECIALS, 'action=add_specials', 'NONSSL') . '" method="post" onSubmit="return checkForm();"><input type="hidden" name="products_id" value="' . $HTTP_GET_VARS['products_id'] . '">' . "\n";
    if ($HTTP_GET_VARS['products_id']) {
      tep_products_subcategories($HTTP_GET_VARS['products_id']); // returns $products_subcategories
    } else {
      $products_subcategories = '';
    }
?>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$products_subcategories;?>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?
    if ($HTTP_GET_VARS['products_id']) {
      $products = tep_db_query("select products_name, products_price from products where products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
      $products_values = tep_db_fetch_array($products);
      tep_products_name($HTTP_GET_VARS['products_id']); // returns $products_name
      $products_price = $products_values['products_price'];
      echo $products_name;
    } else {
      $products_price = '';
      echo '<select name="selected" onChange="go()"><option value="">-- Please Select A Product --</option>';
      $products = tep_db_query("select products_id, products_name from products order by products_name");
      while ($products_values = tep_db_fetch_array($products)) {
        tep_products_name($products_values['products_id']); // returns $products_name
        echo '<option value="' . $products_values['products_id'] . '">' . $products_name . '</option>';
      }
      echo '</select>';
    } ?>&nbsp;</font></td>
            <td align="right" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<? if ($HTTP_GET_VARS['products_id']) { echo '<input type="text" name="products_price" size="10" value="' . $products_price . '">&nbsp;'; } ?></font></td>
            <td align="right" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=date('d/m/Y');?>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><b>&nbsp;<? if ($HTTP_GET_VARS['products_id']) { echo tep_image_submit(DIR_IMAGES . 'button_insert.gif', '50', '14', '0', IMAGE_INSERT) . '&nbsp;<a href="' . tep_href_link(FILENAME_SPECIALS, '', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_cancel.gif', '50', '14', '0', IMAGE_CANCEL) . '</a>&nbsp;'; } ?></font></td>
</form>
          </tr>
          <tr>
            <td colspan="5"><?=tep_black_line();?></td>
          </tr>
<?
  }
?>
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
<? $include_file = DIR_INCLUDES . 'application_bottom.php'; include(DIR_INCLUDES . 'include_once.php'); ?>