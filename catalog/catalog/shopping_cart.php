<? include("includes/application_top.php"); ?>
<?
  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'remove_product') {
// customer wants to remove a product from their shopping cart
      $cart->remove($HTTP_GET_VARS['products_id']);
      header('Location: ' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'add_update_product') {
// customer wants to update the product quantity in their shopping cart
      if ((is_array($HTTP_POST_VARS['cart_quantity'])) && (is_array($HTTP_POST_VARS['products_id']))) {
        for ($i=0; $i<sizeof($HTTP_POST_VARS['products_id']);$i++) {
          $attributes = ($HTTP_POST_VARS['id'][$HTTP_POST_VARS['products_id'][$i]]) ? $HTTP_POST_VARS['id'][$HTTP_POST_VARS['products_id'][$i]] : '';
          $cart->add_cart($HTTP_POST_VARS['products_id'][$i], $HTTP_POST_VARS['cart_quantity'][$i], $attributes);
        }
      } else {
        if (ereg('^[0-9]+$', $HTTP_POST_VARS['products_id'])) {
          $cart->add_cart($HTTP_POST_VARS['products_id'], $HTTP_POST_VARS['cart_quantity'], $HTTP_POST_VARS['id']);
        }
      }
      header('Location: ' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL')); tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'remove_all') {
// customer wants to remove all products from their shopping cart
      $cart->reset();
      header('Location: ' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL')); tep_exit();
    }
  }
?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE . '</a>'; ?>
<html>
<head>
<title><? echo TITLE;?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_INCLUDES . 'header.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<? echo BOX_WIDTH;?>" valign="top"><table border="0" width="<? echo BOX_WIDTH;?>" cellspacing="0" cellpadding="0">
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
            <td bgcolor="<? echo TOP_BAR_BACKGROUND_COLOR;?>" width="100%" nowrap><font face="<? echo TOP_BAR_FONT_FACE;?>" size="<? echo TOP_BAR_FONT_SIZE;?>" color="<? echo TOP_BAR_FONT_COLOR;?>">&nbsp;<? echo TOP_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<? echo HEADING_FONT_FACE;?>" size="<? echo HEADING_FONT_SIZE;?>" color="<? echo HEADING_FONT_COLOR;?>">&nbsp;<? echo HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_IMAGES . 'table_background_cart.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line();?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?
  $cart_empty = ($cart->count_contents() > 0) ? 0 : 1;

  if (!$cart_empty) {
  //------------------------------------------------
  // Set up column widths and colspan
  //------------------------------------------------
    if (PRODUCT_LIST_MODEL) {
      $col_width = array ('width="7%"', 'width="8%"', 'width="12%"', 'width="61%"', 'width="12%"' );
      $colspan = 5;
    } else {
      $col_width = array ('width="7%"', 'width="8%"', 'width="73%"', 'width="12%"' );
      $colspan = 4;
    }
?>
<form name="cart_quantity" method="post" action="<? echo tep_href_link(FILENAME_SHOPPING_CART, 'action=add_update_product', 'NONSSL');?>">

          <tr>
            <td <? $col_idx=0; echo $col_width[$col_idx++];?>></td>
            <td <? echo $col_width[$col_idx++];?> align="center" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><? echo TABLE_HEADING_QUANTITY;?></b>&nbsp;</font></td>

<?
    if (PRODUCT_LIST_MODEL) {
?>
            <td <? echo $col_width[$col_idx++];?> nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><? echo TABLE_HEADING_MODEL;?></b>&nbsp;</font></td>
<?
    }
?>

            <td <? echo $col_width[$col_idx++];?> nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><? echo TABLE_HEADING_PRODUCTS;?></b>&nbsp;</font></td>
            <td <? echo $col_width[$col_idx++];?> align="right" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><? echo TABLE_HEADING_TOTAL;?></b>&nbsp;</font></td>
          </tr>
          <tr>
            <td colspan="<? echo $colspan;?>"><? echo tep_black_line();?></td>
          </tr>
<?
    $products = $cart->get_products();
    for ($i=0; $i<sizeof($products); $i++) {
      $col_idx=0;
      $products_name = $products[$i]['name'];
      echo '          <tr>' . "\n";
      echo '            <td ' . $col_width[$col_idx++] . ' align="center" valign="top"><a href="' . tep_href_link(FILENAME_SHOPPING_CART, 'action=remove_product&products_id=' . $products[$i]['id'], 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_small_delete.gif', '50', '14', '0', 'Remove ' . $products_name . ' from Shopping Cart.') . '</a></td>' . "\n";
      echo '            <td ' . $col_width[$col_idx++] . ' align="center" valign="top" nowrap><input type="text" name="cart_quantity[]" value="' . $products[$i]['quantity'] . '" maxlength="2" size="2"><input type="hidden" name="products_id[]" value="' . $products[$i]['id'] . '"></td>' . "\n";
      if (PRODUCT_LIST_MODEL) echo '            <td ' . $col_width[$col_idx++] . ' valign="top" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id'], 'NONSSL') . '">' . $products[$i]['model'] . '</a>&nbsp;</font></td>' . "\n";
      echo '            <td ' . $col_width[$col_idx++] . ' valign="top" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id'], 'NONSSL') . '"><b>' . $products_name . '</b></a>' . "\n";

//------display customer choosen option --------
      $attributes_exist = '0';
      if ($cart->contents[$products[$i]['id']]['attributes']) {
        $attributes_exist = '1';
        reset($cart->contents[$products[$i]['id']]['attributes']);
        while (list($option, $value) = each($cart->contents[$products[$i]['id']]['attributes'])) {
          $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name from products_options popt, products_options_values poval, products_attributes pa where pa.products_id = '" . $products[$i]['id'] . "' and pa.options_id = '" . $option . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $value . "' and pa.options_values_id = poval.products_options_values_id");
          $attributes_values = tep_db_fetch_array($attributes);
          echo "\n" . '<br><small><i>&nbsp;-&nbsp;' . $attributes_values['products_options_name'] . '&nbsp;:&nbsp;' . $attributes_values['products_options_values_name'] . '</i></small>';
          echo '<input type="hidden" name="id[' . $products[$i]['id'] . '][' . $option . ']" value="' . $value . '">';
        }
      }
//------display customer choosen option eof-----
      echo '</font></td>' . "\n";
      echo '            <td ' . $col_width[$col_idx++] . ' align="right" valign="top" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<b>' . tep_currency_format($products[$i]['quantity'] * $products[$i]['price']) . '</b>&nbsp;';
//------display customer choosen option --------
      if ($attributes_exist == '1') {
        reset($cart->contents[$products[$i]['id']]['attributes']);
        while (list($option, $value) = each($cart->contents[$products[$i]['id']]['attributes'])) {
          $attributes = tep_db_query("select pa.options_values_price, pa.price_prefix from products_options popt, products_options_values poval, products_attributes pa where pa.products_id = '" . $products[$i]['id'] . "' and pa.options_id = '" . $option . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $value . "' and pa.options_values_id = poval.products_options_values_id");
          $attributes_values = tep_db_fetch_array($attributes);
          if ($attributes_values['options_values_price'] != '0') {
            echo "\n" . '<br><small><i>' . $attributes_values['price_prefix'] . tep_currency_format($products[$i]['quantity'] * $attributes_values['options_values_price']) . '</i></small>&nbsp;';
          } else {
            echo "\n" . '<br>&nbsp;';
          }
        }
      }
//------display customer choosen option eof-----
      echo '</font></td>' . "\n";
      echo '          </tr>' . "\n";
    }
?>
          <tr>
            <td colspan="<? echo $colspan;?>"><? echo tep_black_line();?></td>
          </tr>
          <tr>
            <td colspan="<? echo $colspan;?>" align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0" align="right">
              <tr>
                <td align="right" width="100%" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<? echo SUB_TITLE_SUB_TOTAL;?>&nbsp;</font></td>
                <td align="right" width="100%" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<? echo tep_currency_format($cart->show_total());?>&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="<? echo $colspan;?>"><? echo tep_black_line();?></td>
          </tr>
          <tr>
            <td colspan="<? echo $colspan;?>" align="right" nowrap><br><font face="<? echo TEXT_FONT_FACE;?>" size="<? echo TEXT_FONT_SIZE;?>" color="<? echo TEXT_FONT_COLOR;?>">&nbsp;<? echo tep_image_submit(DIR_IMAGES . 'button_update_cart.gif', '116', '24', '0', IMAGE_UPDATE_CART);?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<? echo tep_href_link(FILENAME_CHECKOUT, '', 'NONSSL');?>"><? echo tep_image(DIR_IMAGES . 'button_checkout.gif', '91', '24', '0', IMAGE_CHECKOUT);?></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<? echo tep_href_link(FILENAME_SHOPPING_CART, 'action=remove_all', 'NONSSL');?>"><? echo tep_image(DIR_IMAGES . 'button_remove_all.gif', '113', '24', '0', IMAGE_REMOVE_ALL);?></a>&nbsp;&nbsp;</font></td>
          </tr>
</form>
<?
  } else {
    echo '          <tr>' . "\n";
    echo '            <td colspan="' . $colspan . '" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . TEXT_CART_EMPTY . '&nbsp;</font></td>' . "\n";
    echo '          </tr>' . "\n";
    echo '          <tr>' . "\n";
    echo '            <td colspan="' . $colspan . '">' . tep_black_line() . '</td>' . "\n";
    echo '          </tr>' . "\n";
    echo '          <tr>' . "\n";
    echo '            <td colspan="' . $colspan . '" align="right" nowrap><br><font face="' . TABLE_HEADING_FONT_FACE . '" size="' . TABLE_HEADING_FONT_SIZE . '" color="' . TABLE_HEADING_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_main_menu.gif', '112', '24', '0', IMAGE_MAIN_MENU) . '</a>&nbsp;&nbsp;</font></td>' . "\n";
    echo '          </tr>' . "\n";
  }
?>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<? echo BOX_WIDTH;?>" valign="top"><table border="0" width="<? echo BOX_WIDTH;?>" cellspacing="0" cellpadding="0">
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
