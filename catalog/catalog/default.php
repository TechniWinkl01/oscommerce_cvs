<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_DEFAULT; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ''; ?>
<html>
<head>
<title><?=TITLE;?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
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
<?
  if ($HTTP_GET_VARS['category_id']) {
?>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
            <td bgcolor="<?=TOP_BAR_BACKGROUND_COLOR;?>" width="100%" nowrap><font face="<?=TOP_BAR_FONT_FACE;?>" size="<?=TOP_BAR_FONT_SIZE;?>" color="<?=TOP_BAR_FONT_COLOR;?>">&nbsp;<?=TOP_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
<?
    $top_category = tep_db_query("select category_top_name, category_image from category_top where category_top_id = '$HTTP_GET_VARS[category_id]'");
    $top_category_values = tep_db_fetch_array($top_category);
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<?=tep_image($top_category_values['category_image'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', $top_category_values['category_top_name']);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr bgcolor="<?=SUB_BAR_BACKGROUND_COLOR;?>">
            <td nowrap><font face="<?=SUB_BAR_FONT_FACE;?>" size="<?=SUB_BAR_FONT_SIZE;?>" color="<?=SUB_BAR_FONT_COLOR;?>">&nbsp;<?=SUB_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
          <tr>
            <td><?=tep_black_line();?></td>
          </tr>
<?
    if (($HTTP_GET_VARS['category_id']) && (!$HTTP_GET_VARS['index_id'])) {
      echo '          <tr>' . "\n";
      $categories = tep_db_query("select category_index.category_index_id, category_index.category_index_name from category_index, category_index_to_top where category_index_to_top.category_top_id = '$HTTP_GET_VARS[category_id]' and category_index_to_top.category_index_id = category_index.category_index_id");
      echo '            <td><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">';
      while ($categories_values = tep_db_fetch_array($categories)) {
        echo '&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, 'category_id=' . $HTTP_GET_VARS['category_id'] . '&index_id=' . $categories_values['category_index_id'], 'NONSSL') . '">' . $categories_values['category_index_name'] . '</a><br>';
      }
      echo '</font></td>' . "\n";
      echo '          </tr>' . "\n";
    } elseif (($HTTP_GET_VARS['category_id']) && ($HTTP_GET_VARS['index_id'])) {
?>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
<?
      $categories_count = tep_db_query("select count(*) as count from subcategories, subcategories_to_category where subcategories.subcategories_id = subcategories_to_category.subcategories_id and subcategories_to_category.category_top_id = '$HTTP_GET_VARS[category_id]'");
      $categories_count_values = tep_db_fetch_array($categories_count);

      $listby_query = tep_db_query("select sql_select from category_index where category_index_id = '$HTTP_GET_VARS[index_id]'");
      $listby_values = tep_db_fetch_array($listby_query);
      $listby = $listby_values['sql_select'];

      $subcategories = tep_db_query("select " . $listby . "." . $listby . "_id as id, " . $listby . "." . $listby . "_name as name, " . $listby . "." . $listby . "_image as image from " . $listby . ", " . $listby . "_to_category where " . $listby . "_to_category.category_top_id = '$HTTP_GET_VARS[category_id]' and " . $listby . "_to_category." . $listby . "_id = " . $listby . "." . $listby . "_id order by " . $listby . "." . $listby . "_name");
      $row = 0;
      while ($subcategories_values = tep_db_fetch_array($subcategories)) {
        $row++;
        $number_of_products = tep_db_query("select count(*) as total from products, products_to_" . $listby . " where products_status='1' and products.products_id = products_to_" . $listby . ".products_id and " . $listby . "_id = '" . $subcategories_values['id'] . "'");
        $number_of_products_values = tep_db_fetch_array($number_of_products);
        echo '                <td align="center"><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_LIST, 'category_id=' . $HTTP_GET_VARS['category_id'] . '&index_id=' . $HTTP_GET_VARS['index_id'] . '&subcategory_id=' . $subcategories_values['id'], 'NONSSL') . '">' . tep_image($subcategories_values['image'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT, '0', $subcategories_values['name']) . '</a>&nbsp;<br>&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_LIST, 'category_id=' . $HTTP_GET_VARS['category_id'] . '&index_id=' . $HTTP_GET_VARS['index_id'] . '&subcategory_id=' . $subcategories_values['id'], 'NONSSL') . '">' . $subcategories_values['name'] . '</a>&nbsp;(' . $number_of_products_values['total'] . ')&nbsp;</font></td>' . "\n";
        if ((($row / 3) == floor($row / 3)) && ($row != $categories_count_values['count'])) {
          echo '              </tr>' . "\n";
          echo '              <tr>' . "\n";
          echo '                <td>&nbsp;</td>' . "\n";
          echo '              </tr>' . "\n";
          echo '              <tr>' . "\n";
        }    
      }
?>
              </tr>
            </table></td>
          </tr>
<?
    }
    $np_category_id = $HTTP_GET_VARS['category_id']; $include_file = DIR_MODULES . 'new_products.php'; include(DIR_INCLUDES . 'include_once.php');
?>
        </table></td>
      </tr>
    </table></td>
<?
  } else {
?>
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
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_IMAGES . 'table_background_default.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr bgcolor="<?=SUB_BAR_BACKGROUND_COLOR;?>">
            <td nowrap><font face="<?=SUB_BAR_FONT_FACE;?>" size="<?=SUB_BAR_FONT_SIZE;?>" color="<?=SUB_BAR_FONT_COLOR;?>">&nbsp;<?=SUB_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
          <tr>
            <td><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><?=TEXT_MAIN;?></font></td>
          </tr>
<?
  $np_category_id = '0'; $include_file = DIR_MODULES . 'new_products.php'; include(DIR_INCLUDES . 'include_once.php');
  $include_file = DIR_MODULES . 'upcoming_products.php'; include(DIR_INCLUDES . 'include_once.php');
?>
        </table></td>
      </tr>
    </table></td>
<?
  }
?>
<!-- body_text_eof //-->
    <td width="<?=BOX_WIDTH;?>" valign="top"><table border="0" width="<?=BOX_WIDTH;?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<? $include_file = DIR_INCLUDES . 'column_right.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- right_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
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