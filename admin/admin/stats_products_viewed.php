<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_STATS_PRODUCTS_VIEWED; include(DIR_INCLUDES . 'include_once.php'); ?>
<html>
<head>
<title><?=TITLE;?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript"><!--
function go() {
  if (document.order_by.selected.options[document.order_by.selected.selectedIndex].value != "none") {
    location = "<?=FILENAME_STATS_PRODUCTS_VIEWED;?>?limit="+document.order_by.selected.options[document.order_by.selected.selectedIndex].value;
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
<?
  if ($HTTP_GET_VARS['limit']) {
    $limit = $HTTP_GET_VARS['limit'];
  } else {
    $limit = '10';
  }
?>
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap><br><form name="order_by"><select name="selected" onChange="go()"><option value="10"<? if ($limit == '10') { echo ' SELECTED'; } ?>>10</option><option value="20"<? if ($limit == '20') { echo ' SELECTED'; } ?>>20</option></select>&nbsp;&nbsp;</form></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="4"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_NUMBER;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_PRODUCTS;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_SUBCATEGORIES;?>&nbsp;</b></font></td>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_VIEWED;?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td colspan="4"><?=tep_black_line();?></td>
          </tr>
<?
  $products = tep_db_query("select products_id, products_name, products_viewed from products order by products_viewed DESC limit " . $limit);
  while ($products_values = tep_db_fetch_array($products)) {
    $products_subcategories = '';
    $subcategories = tep_db_query("select subcategories.subcategories_name from subcategories, products_to_subcategories where products_to_subcategories.products_id = '" . $products_values['products_id'] . "' and products_to_subcategories.subcategories_id = subcategories.subcategories_id order by subcategories.subcategories_name");
    while ($subcategories_values = tep_db_fetch_array($subcategories)) {
      $products_subcategories .= $subcategories_values['subcategories_name'] . ' / ';
    }
    $products_subcategories = substr($products_subcategories, 0, -3); // remove the last ' / '
    $products_manufacturers = '';
    $manufacturers = tep_db_query("select manufacturers.manufacturers_name, manufacturers.manufacturers_location from manufacturers, products_to_manufacturers where products_to_manufacturers.products_id = '" . $products_values['products_id'] . "' and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id order by manufacturers.manufacturers_name");
    while ($manufacturers_values = tep_db_fetch_array($manufacturers)) {
      $products_manufacturers .= $manufacturers_values['manufacturers_name'] . ' / ';
      $location = $manufacturers_values['manufacturers_location'];
    }
    $products_manufacturers = substr($products_manufacturers, 0, -3); // remove the last ' / '

    if ($location == '0') {
      $products_name = $products_manufacturers . ' ' . $products_values['products_name'];
    } else {
      $products_name = $products_values['products_name'] . ' (' . $products_manufacturers . ')';
    }

    $rows++;
    if (floor($rows/2) == ($rows/2)) {
      echo '          <tr bgcolor="#ffffff">' . "\n";
    } else {
      echo '          <tr bgcolor="#f4f7fd">' . "\n";
    }
    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
?>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$rows;?>.&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$products_name;?>&nbsp;</font></td>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$products_subcategories;?>&nbsp;</font></td>
            <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$products_values['products_viewed'];?>&nbsp;</font></td>
          </tr>
<?
  }
?>
          <tr>
            <td colspan="4"><?=tep_black_line();?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<? $include_file = DIR_INCLUDES . 'footer.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<? $include_file = DIR_INCLUDES . 'application_bottom.php'; include(DIR_INCLUDES . 'include_once.php'); ?>