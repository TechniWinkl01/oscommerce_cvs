<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_PRODUCT_LIST; include(DIR_INCLUDES . 'include_once.php'); ?>
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
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_IMAGES . 'table_background_list.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
<?
   if (PRODUCT_LIST_MODEL) {
     echo '<td nowrap><font face="' . TABLE_HEADING_FONT_FACE . '" size="' . TABLE_HEADING_FONT_SIZE .'" color="' . TABLE_HEADING_FONT_COLOR . '"><b>&nbsp;' . TABLE_HEADING_MODEL . '&nbsp;</b></font></td>';
   }
?>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_PRODUCTS;?>&nbsp;</b></font></td>
            <td align="right" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_PRICE;?>&nbsp;</b></font></td>
          </tr>
          <tr>
<?
   if (PRODUCT_LIST_MODEL) {
            echo '<td colspan="3">' . tep_black_line() . '</td>';
   } else {
            echo '<td colspan="2">' . tep_black_line() . '</td>';
   }
   echo '</tr>';
  $listby_query = tep_db_query("select sql_select from category_index where category_index_id = '" . $HTTP_GET_VARS['index_id'] . "'");
  $listby_values = tep_db_fetch_array($listby_query);
  $listby = $listby_values['sql_select'];

// we need to look for the sort location of manufacturers_name<>products_name - this method is not the best but it works for now ;)
  $sort_location = tep_db_query("select manufacturers.manufacturers_location from products, manufacturers, products_to_manufacturers, products_to_subcategories where products_to_" . $listby . "." . $listby . "_id = '" . $HTTP_GET_VARS['subcategory_id'] . "' and products_to_subcategories.products_id = products.products_id and products.products_id = products_to_manufacturers.products_id and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id order by manufacturers.manufacturers_name, products.products_name");
  $sort_location_values = tep_db_fetch_array($sort_location);
  if ($sort_location_values['manufacturers_location'] == '0') { // the location of manufacturers name is to the left of the products name
    $listing = tep_db_query("select products.products_id, products.products_name, products.products_model, manufacturers.manufacturers_name, manufacturers.manufacturers_location, products.products_price from products, manufacturers, products_to_manufacturers, products_to_subcategories where products.products_status='1' and products_to_" . $listby . "." . $listby . "_id = '" . $HTTP_GET_VARS['subcategory_id'] . "' and products_to_subcategories.products_id = products.products_id and products.products_id = products_to_manufacturers.products_id and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id order by manufacturers.manufacturers_name, products.products_name");
  } else { // its to the right..
    $listing = tep_db_query("select products.products_id, products.products_name, products.products_model, manufacturers.manufacturers_name, manufacturers.manufacturers_location, products.products_price from products, manufacturers, products_to_manufacturers, products_to_subcategories where products.products_status='1' and products_to_" . $listby . "." . $listby . "_id = '" . $HTTP_GET_VARS['subcategory_id'] . "' and products_to_subcategories.products_id = products.products_id and products.products_id = products_to_manufacturers.products_id and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id order by products.products_name, manufacturers.manufacturers_name");
  }
  tep_db_free_result($sort_location); // lets free the result from memory..
  $number_of_products = '0';
  if (tep_db_num_rows($listing)) {
    while ($listing_values = tep_db_fetch_array($listing)) {
      $number_of_products++;
      if (($number_of_products / 2) == floor($number_of_products / 2)) {
        echo '          <tr bgcolor="#ffffff">' . "\n";
      } else {
        echo '          <tr bgcolor="#f4f7fd">' . "\n";
      }
      if (PRODUCT_LIST_MODEL) {
         echo '            <td nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">' . $listing_values['products_model'] . '&nbsp;</font></td>';
       }
      echo '<td nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'category_id=' . $HTTP_GET_VARS['category_id'] . '&index_id=' . $HTTP_GET_VARS['index_id'] . '&subcategory_id=' . $HTTP_GET_VARS['subcategory_id'] . '&products_id=' . $listing_values['products_id'], 'NONSSL') . '">';
      $products_name = tep_products_name($listing_values['manufacturers_location'], $listing_values['manufacturers_name'], $listing_values['products_name']);
      echo $products_name . '</a>&nbsp;</font></td>' . "\n";
      $check_special = tep_db_query("select specials.specials_new_products_price from specials where products_id = '" . $listing_values['products_id'] . "'");
      if (tep_db_num_rows($check_special)) {
        $check_special_values = tep_db_fetch_array($check_special);
        $new_price = $check_special_values['specials_new_products_price'];
      }
      echo '            <td align="right" nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;';
      if ($new_price) {
        echo '<s>$' .  $listing_values['products_price'] . '</s>&nbsp;&nbsp;<font color="' . SPECIALS_PRICE_COLOR . '">$' . $new_price . '</font>';
        unset($new_price);
      } else {
        echo '$' . $listing_values['products_price'];
      }
      echo '&nbsp;</font></td>' . "\n";
      echo '          </tr>' . "\n";
    }
  } else {
?>
          <tr bgcolor="#f4f7fd">
            <td colspan="2" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_NO_PRODUCTS;?>&nbsp;</font></td>
          </tr>
<?
  }
?>
        </td></table>
      </tr>
      <tr>
        <td><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td align="right" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_NUMBER_OF_PRODUCTS . $number_of_products;?>&nbsp;&nbsp;</font></td>
      </tr>
    </table></td>
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
