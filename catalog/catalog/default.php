<? include('includes/application_top.php'); ?>
<?
// remember the following cPath references come from application_top.php
  $category_depth = 'top';
  if ($cPath) {
    $categories_products_query = tep_db_query("select count(*) as total from products_to_categories where categories_id = '" . $current_category_id . "'");
    $cateqories_products = tep_db_fetch_array($categories_products_query);
    if ($cateqories_products['total'] > 0) {
      $category_depth = 'products'; // display products
    } else {
      $category_parent_query = tep_db_query("select count(*) as total from categories where parent_id = '" . $current_category_id . "'");
      $category_parent = tep_db_fetch_array($category_parent_query);
      if ($category_parent['total'] > 0) {
        $category_depth = 'nested'; // navigate through the categories
      } else {
        $category_depth = 'products'; // category has no products, but display the 'no products' message
      }
    }
  }
?>
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
  if ($category_depth == 'nested') {
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
    $category_query = tep_db_query("select categories_name, categories_image from categories where categories_id = '" . $current_category_id . "'");
    $category = tep_db_fetch_array($category_query);
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<?=tep_image($category['categories_image'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', $category['categories_name']);?>&nbsp;</td>
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
    $new_products_category_id = $current_category_id; $include_file = DIR_MODULES . FILENAME_NEW_PRODUCTS; include(DIR_INCLUDES . 'include_once.php');
?>
        </table></td>
      </tr>
    </table></td>
<?
  } elseif ($category_depth == 'products') {
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
    $listing = tep_db_query("select p.products_id, p.products_name, p.products_model, m.manufacturers_name, m.manufacturers_location, p.products_price
                            from products p, manufacturers m, products_to_manufacturers p2m, products_to_categories p2c
                            where p.products_status = '1'
                            and p.products_id = p2m.products_id
                            and p2m.manufacturers_id = m.manufacturers_id
                            and p.products_id = p2c.products_id
                            and p2c.categories_id = " . $current_category_id . "
                            order by p.products_name");
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
        echo '<td nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&products_id=' . $listing_values['products_id'], 'NONSSL') . '">';
        $products_name = tep_products_name($listing_values['manufacturers_location'], $listing_values['manufacturers_name'], $listing_values['products_name']);
        echo $products_name . '</a>&nbsp;</font></td>' . "\n";
        $check_special = tep_db_query("select specials.specials_new_products_price from specials where products_id = '" . $listing_values['products_id'] . "'");
        if (tep_db_num_rows($check_special)) {
          $check_special_values = tep_db_fetch_array($check_special);
          $new_price = $check_special_values['specials_new_products_price'];
        }
        echo '            <td align="right" nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;';
        if ($new_price) {
          echo '<s>' .  tep_currency_format($listing_values['products_price']) . '</s>&nbsp;&nbsp;<font color="' . SPECIALS_PRICE_COLOR . '">' . tep_currency_format($new_price) . '</font>';
          unset($new_price);
        } else {
          echo tep_currency_format($listing_values['products_price']);
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
<?
  } else { // default page
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
  $new_products_category_id = '0'; $include_file = DIR_MODULES . FILENAME_NEW_PRODUCTS; include(DIR_INCLUDES . 'include_once.php');
  $include_file = DIR_MODULES . FILENAME_UPCOMING_PRODUCTS; include(DIR_INCLUDES . 'include_once.php');
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
