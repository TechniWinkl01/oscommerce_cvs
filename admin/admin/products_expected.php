<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_PRODUCTS_EXPECTED; include(DIR_INCLUDES . 'include_once.php'); ?>
<?
  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'add_products') {
      $date_expected_formated = substr($HTTP_POST_VARS['date_expected'], -4) . substr($HTTP_POST_VARS['date_expected'], 3, 2) . substr($HTTP_POST_VARS['date_expected'], 0, 2);
      tep_db_query("insert into products_expected values ('', '" . $HTTP_POST_VARS['products_name'] . "', '" . $date_expected_formated . "')");
      header('Location: ' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, '', 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'update_products') {
      $date_expected_formated = substr($HTTP_POST_VARS['date_expected'], -4) . substr($HTTP_POST_VARS['date_expected'], 3, 2) . substr($HTTP_POST_VARS['date_expected'], 0, 2);
      tep_db_query("update products_expected set products_name = '" . $HTTP_POST_VARS['products_name'] . "', date_expected = '" . $date_expected_formated . "' where products_expected_id = '" . $HTTP_POST_VARS['products_id'] . "'");
      header('Location: ' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, '', 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'delete_products') {
      tep_db_query("delete from products_expected where products_expected_id = '" . $HTTP_GET_VARS['products_id'] . "'");
      header('Location: ' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, '', 'NONSSL')); tep_exit();
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
  var products_name = document.products_expected.products_name.value;
  var date_expected = document.products_expected.date_expected.value;
  
  if (products_name.length < 1) {
    error_message = error_message + "<?=JS_PRODUCTS_EXPECTED_NAME;?>";
    error = 1;
  }

  if (date_expected = "" || date_expected.length < 10) {
    error_message = error_message + "<?=JS_PRODUCTS_EXPECTED_DATE;?>";
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
  if (document.order_by.selected.options[document.order_by.selected.selectedIndex].value != "none") {
    location = "<?=FILENAME_PRODUCTS_EXPECTED;?>?order_by="+document.order_by.selected.options[document.order_by.selected.selectedIndex].value;
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
        <td width="100%"><br>
<?
    if ($HTTP_GET_VARS['order_by']) {
      $order_by = $HTTP_GET_VARS['order_by'];
    } else {
      $order_by = 'products_name';
    }
?>			
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap><form name="order_by"><select name="selected" onChange="go()"><option value="products_name"<? if ($order_by == 'products_name') { echo ' SELECTED'; } ?>>Products Name</option><option value="date_expected"<? if ($order_by == 'date_expected') { echo ' SELECTED'; } ?>>Date Expected</option></form></td>
          </tr>
        </table><br></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="3"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_PRODUCTS;?>&nbsp;</b></font></td>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_DATE_EXPECTED;?>&nbsp;</b></font></td>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_ACTION;?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td colspan="3"><?=tep_black_line();?></td>
          </tr>
<?
  $products = tep_db_query("select products_expected_id, products_name, date_expected from products_expected order by '" . $order_by . "'");
  while ($products_values = tep_db_fetch_array($products)) {
    $rows++;
    if (floor($rows/2) == ($rows/2)) {
      echo '          <tr bgcolor="#ffffff">' . "\n";
    } else {
      echo '          <tr bgcolor="#f4f7fd">' . "\n";
    }
    $date_expected_formated = substr($products_values['date_expected'], -2) . '/' . substr($products_values['date_expected'], -4, 2) . '/' . substr($products_values['date_expected'], 0, 4);
    if (($HTTP_GET_VARS['action'] == 'update') && ($products_values['products_expected_id'] == $HTTP_GET_VARS['products_id'])) {
      echo '<form name="products_expected" action="' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, 'action=update_products', 'NONSSL') . '" method="post" onSubmit="return checkForm();"><input type="hidden" name="products_id" value="' . $HTTP_GET_VARS['products_id'] . '">' . "\n";
?>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="products_name" size="30" value="<?=$products_values['products_name'];?>">&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="date_expected" size="10" value="<?=$date_expected_formated;?>" maxlength="10">&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=tep_image_submit(DIR_IMAGES . 'button_update_red.gif', '50', '14', '0', IMAGE_UPDATE);?>&nbsp;<?='<a href="' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, '', 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_cancel.gif', '50', '14', '0', IMAGE_CANCEL);?></a>&nbsp;</font></td>
</form>
          </tr>
<?
    } elseif (($HTTP_GET_VARS['action'] == 'delete') && ($products_values['products_expected_id'] == $HTTP_GET_VARS['products_id'])) {
?>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<b><?=$products_values['products_name'];?></b>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<b><?=$date_expected_formated;?></b>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?='<a href="' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, 'action=delete_products&products_id=' . $HTTP_GET_VARS['products_id'], 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_confirm_red.gif', '50', '14', '0', IMAGE_CONFIRM);?>&nbsp;<?='<a href="' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, '', 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_cancel.gif', '50', '14', '0', IMAGE_CANCEL);?></a>&nbsp;</font></td>
          </tr>
<?
    } else {
?>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$products_values['products_name'];?>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$date_expected_formated;?>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?='<a href="' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, 'action=update&products_id=' . $products_values['products_expected_id'], 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_modify.gif', '50', '14', '0', IMAGE_MODIFY);?></a>&nbsp;<?='<a href="' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, 'action=delete&products_id=' . $products_values['products_expected_id'], 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_delete.gif', '50', '14', '0', IMAGE_DELETE);?></a>&nbsp;</font></td>
          </tr>
<?
    }
  }
?>
          <tr>
            <td colspan="3"><?=tep_black_line();?></td>
          </tr>
<?
  if (!$HTTP_GET_VARS['products_id']) {
    if (floor($rows/2) == ($rows/2)) {
      echo '          <tr bgcolor="#f4f7fd">' . "\n";
    } else {
      echo '          <tr bgcolor="#ffffff">' . "\n";
    }
    echo '<form name="products_expected" action="' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, 'action=add_products', 'NONSSL') . '" method="post" onSubmit="return checkForm();">' . "\n";
?>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="products_name" size="30">&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<input type="text" name="date_expected" size="10" value="xx/xx/xxxx" maxlength="10">&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><b>&nbsp;<?=tep_image_submit(DIR_IMAGES . 'button_insert.gif', '50', '14', '0', IMAGE_INSERT);?>&nbsp;</font></td>
</form>
          </tr>
          <tr>
            <td colspan="3"><?=tep_black_line();?></td>
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