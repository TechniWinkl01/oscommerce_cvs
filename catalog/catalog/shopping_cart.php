<? include("includes/application_top.php"); ?>
<?
  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'add_product') {  // customer wants to add a product to their shopping cart
      $product_exists = 0; // product does not exist in catalog
      $product_check = tep_db_query("select products_id from products where products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
      if (@tep_db_num_rows($product_check)) {
        $product_exists = 1; // product exists in catalog
        if (tep_session_is_registered('customer_id')) { // customer is logged in
          $product_exists = tep_db_query("select products_id from customers_basket where products_id = '" . $HTTP_GET_VARS['products_id'] . "' and customers_id = '" . $customer_id . "'");
          if (@tep_db_num_rows($product_exists)) { // product already exists in their basket, so we'll increment the quantity
            tep_db_query("update customers_basket set customers_basket_quantity = customers_basket_quantity+1 where products_id = '" . $HTTP_GET_VARS['products_id'] . "' and customers_id = '" . $customer_id . "'");
          } else { // the product is not yet in their basket, so we'll add it with a quantity of 1
            $date_now = date('Ymd');
            tep_db_query("insert into customers_basket values ('', '" . $customer_id . "', '" . $HTTP_GET_VARS['products_id'] . "', '1', '" . $date_now . "')");
          }
          header('Location: ' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'));
          tep_exit();
        } else { // customer is not logged in
          if (tep_session_is_registered('nonsess_cart')) { // does the customer have a per session cart?
            $nonsess_cart_contents = explode('|', $nonsess_cart);
            for ($i=0;$i<sizeof($nonsess_cart_contents);$i++) { // lets see if the product exists in their per session cart
              $product_info = explode(':', $nonsess_cart_contents[$i]);
              if ($product_info[0] == $HTTP_GET_VARS['products_id']) {
                $product_info[1] = ($product_info[1] + 1); // the product exists, so we'll increment the quantity
                $nonsess_cart_contents_updated = '1'; // we set a flag to know if the product exists and the quantity has been updated
              }
              $nonsess_cart_contents[$i] = implode(':', $product_info);
            }
            $nonsess_cart = implode('|', $nonsess_cart_contents);
            if (($nonsess_cart_contents_updated != '1') && ($product_exists == '1')) { // when the flag is not set to true, we know the product didnt exist in their basket
              $nonsess_cart.='|' . $HTTP_GET_VARS['products_id'] . ':1'; // add the product to their basket
            }
          } else { // they do not yet have a per session basket..
            if ($product_exists == '1') { // add the product if it exists in the catalog
              $nonsess_cart = $HTTP_GET_VARS['products_id'] . ':1'; // lets add their first product to their basket
            }
          }
          tep_session_register('nonsess_cart'); // now the customer has a per session basket
          header('Location: ' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'));
          tep_exit();
        }
      } else {
        header('Location: ' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'));
        tep_exit();
      }
    } elseif ($HTTP_GET_VARS['action'] == 'remove_product') {  // customer wants to remove a product from their shopping cart
      if (tep_session_is_registered('customer_id')) {
        tep_db_query('delete from customers_basket where products_id = ' . $HTTP_GET_VARS['products_id'] . ' and customers_id = ' . $customer_id);
        header('Location: ' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'));
        tep_exit();
      } else {
        if (tep_session_is_registered('nonsess_cart')) {
          $nonsess_cart_contents = explode('|', $nonsess_cart);
          for ($i=0;$i<sizeof($nonsess_cart_contents);$i++) {
            $product_info = explode(':', $nonsess_cart_contents[$i]);
            if ($product_info[0] == $HTTP_GET_VARS['products_id']) {
              $product_info[0] = '0';
              $product_info[1] = '0';
            }
            $nonsess_cart_contents[$i] = implode(':', $product_info);
          }
          $nonsess_cart = implode('|', $nonsess_cart_contents);
        }
        tep_session_register('nonsess_cart');
        header('Location: ' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'));
        tep_exit();
      }
    } elseif ($HTTP_GET_VARS['action'] == 'update_quantity') {  // customer wants to update the product quantity in their shopping cart
      if (tep_session_is_registered('customer_id')) {
        if ($HTTP_POST_VARS['cart_quantity'] > 0) {
          tep_db_query("update customers_basket set customers_basket_quantity = '" . $HTTP_POST_VARS['cart_quantity'] . "' where products_id = '" . $HTTP_POST_VARS['products_id'] . "' and customers_id = '" . $customer_id . "'");
          header('Location: ' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'));
          tep_exit();
        } else {
          tep_db_query("delete from customers_basket where products_id = '" . $HTTP_POST_VARS['products_id'] . "' and customers_id = '" . $customer_id . "'");
          header('Location: ' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'));
          tep_exit();
        }
      } elseif (tep_session_is_registered('nonsess_cart')) {
        if ($HTTP_POST_VARS['cart_quantity'] > 0) {
          $nonsess_cart_contents = explode('|', $nonsess_cart);
          for ($i=0;$i<sizeof($nonsess_cart_contents);$i++) {
            $product_info = explode(':', $nonsess_cart_contents[$i]);
            if ($product_info[0] == $HTTP_POST_VARS['products_id']) {
              $product_info[1] = $HTTP_POST_VARS['cart_quantity'];
            }
            $nonsess_cart_contents[$i] = implode(':', $product_info);
          }
          $nonsess_cart = implode('|', $nonsess_cart_contents);
          tep_session_register('nonsess_cart');
          header('Location: ' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'));
          tep_exit();
        } else {
          $nonsess_cart_contents = explode('|', $nonsess_cart);
          for ($i=0;$i<sizeof($nonsess_cart_contents);$i++) {
            $product_info = explode(':', $nonsess_cart_contents[$i]);
            if ($product_info[0] == $HTTP_POST_VARS['products_id']) {
              $product_info[0] = '0';
              $product_info[1] = '0';
            }
            $nonsess_cart_contents[$i] = implode(':', $product_info);
          }
          $nonsess_cart = implode('|', $nonsess_cart_contents);
          $nonsess_cart = str_replace ('|0:0|', '|', $nonsess_cart);
          $nonsess_cart = str_replace ('0:0|', '', $nonsess_cart);
          $nonsess_cart = str_replace ('|0:0', '', $nonsess_cart);
          $nonsess_cart = str_replace ('0:0', '', $nonsess_cart);
          if ($nonsess_cart == '') {
            tep_session_unregister('nonsess_cart');
          } else {
            tep_session_register('nonsess_cart');
          }
          header('Location: ' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'));
          tep_exit();
        }
      }
    } elseif ($HTTP_GET_VARS['action'] == 'remove_all') {  // customer wants to remove all products from their shopping cart
      if (tep_session_is_registered('customer_id')) {
        tep_db_query("delete from customers_basket where customers_id = '" . $customer_id . "'");
      } elseif (tep_session_is_registered('nonsess_cart')) {
        tep_session_unregister('nonsess_cart');
      }
      header('Location: ' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'));
      tep_exit();
    }
  } else {
?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE . '</a>'; ?>
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
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_IMAGES . 'table_background_cart.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?
  $cart_empty = 1;
  if (tep_products_in_cart() == '1') {
    $cart_empty = 0;
?>
          <tr>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><?=TABLE_HEADING_QUANTITY;?></b>&nbsp;</font></td>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><?=TABLE_HEADING_PRODUCTS;?></b>&nbsp;</font></td>
            <td align="right" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><?=TABLE_HEADING_TOTAL;?></b>&nbsp;</font></td>
          </tr>
          <tr>
            <td colspan="3"><?=tep_black_line();?></td>
          </tr>
<?
    if (tep_session_is_registered('customer_id')) {
      $check_cart = tep_db_query("select customers_basket.customers_basket_quantity, manufacturers.manufacturers_name, manufacturers.manufacturers_location, products.products_id, products.products_name, products.products_price from customers_basket, manufacturers, products_to_manufacturers, products where customers_basket.customers_id = '" . $customer_id . "' and customers_basket.products_id = products.products_id and products.products_id = products_to_manufacturers.products_id and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id order by customers_basket.customers_basket_id");
      $total_cost = 0;
      while ($check_cart_values = tep_db_fetch_array($check_cart)) {
        $price = $check_cart_values['products_price'];
        $check_special = tep_db_query("select specials_new_products_price from specials where products_id = '" . $check_cart_values['products_id'] . "'");
        if (@tep_db_num_rows($check_special)) {
          $check_special_values = tep_db_fetch_array($check_special);
          $price = $check_special_values['specials_new_products_price'];
        }
        $products_name = tep_products_name($check_cart_values['manufacturers_location'], $check_cart_values['manufacturers_name'], $check_cart_values['products_name']);
        echo '          <tr>' . "\n";
        echo '            <td align="center" nowrap><font face="' , TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . $check_cart_values['customers_basket_quantity'] . '&nbsp;</font></td>' . "\n";
        echo '            <td nowrap><font face="' , TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $check_cart_values['products_id'], 'NONSSL') . '">' . $products_name . '</a>&nbsp;</font></td>' . "\n";
        echo '            <td align="right" nowrap><font face="' , TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;$' . number_format(($check_cart_values['customers_basket_quantity'] * $price),2) . '&nbsp;</font></td>' . "\n";
        echo '          </tr>' . "\n";
        $total_cost = $total_cost + ($check_cart_values['customers_basket_quantity'] * $price);
      }
    } elseif (tep_session_is_registered('nonsess_cart')) {
      $total_cost = 0;
      $product_in_cart = 0;
      $nonsess_cart_contents = explode('|', $nonsess_cart);
      $row_number = 1;
      for ($i=0;$i<sizeof($nonsess_cart_contents);$i++) {
        $product_info = explode(':', $nonsess_cart_contents[$i]);
        if (($product_info[0] != 0) && ($product_info[1] != 0)) {
          $product_in_cart = 1;
          $check_cart = tep_db_query("select manufacturers.manufacturers_name, manufacturers.manufacturers_location, products_name, products_price from manufacturers, products, products_to_manufacturers where products.products_id = '" . $product_info[0] . "' and products.products_id = products_to_manufacturers.products_id and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id");
          $check_cart_values = tep_db_fetch_array($check_cart);
          $price = $check_cart_values['products_price'];
          $check_special = tep_db_query("select specials_new_products_price from specials where products_id = '" . $product_info[0] . "'");
          if (@tep_db_num_rows($check_special)) {
            $check_special_values = tep_db_fetch_array($check_special);
            $price = $check_special_values['specials_new_products_price'];
          }
          $products_name = tep_products_name($check_cart_values['manufacturers_location'], $check_cart_values['manufacturers_name'], $check_cart_values['products_name']);
          echo '          <tr>' . "\n";
          echo '            <td align="center" nowrap><font face="' , TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . $product_info[1] . '&nbsp;</font></td>' . "\n";
          echo '            <td nowrap><font face="' , TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_info[0], 'NONSSL') . '">' . $products_name . '</a>&nbsp;</font></td>' . "\n";
          echo '            <td align="right" nowrap><font face="' , TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;$' . number_format(($product_info[1] * $price),2) . '&nbsp;</font></td>' . "\n";
          echo '          </tr>' . "\n";
          $total_cost = $total_cost + ($product_info[1] * $price);
        }
        $row_number++;
      }
      if ($product_in_cart == 0) {
        tep_session_unregister('nonsess_cart');
      }
      if (!tep_session_is_registered('nonsess_cart')) {
        $cart_empty = 1;
      }
    }
  } else {
    echo '          <tr>' . "\n";
    echo '            <td colspan="3" nowrap><font face="' , TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . TEXT_CART_EMPTY . '&nbsp;</font></td>' . "\n";
    echo '          </tr>' . "\n";
    echo '          <tr>' . "\n";
    echo '            <td colspan="3">' . tep_black_line() . '</td>' . "\n";
    echo '          </tr>' . "\n";
    echo '          <tr>' . "\n";
    echo '            <td colspan="3" align="right" nowrap><br><font face="' . TABLE_HEADING_FONT_FACE . '" size="' . TABLE_HEADING_FONT_SIZE . '" color="' . TABLE_HEADING_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_main_menu.gif', '112', '24', '0', IMAGE_MAIN_MENU) . '</a>&nbsp;&nbsp;</font></td>' . "\n";
    echo '          </tr>' . "\n";
  }

  if ($cart_empty != 1) {
?>
          <tr>
            <td colspan="3"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td colspan="3" align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0" align="right">
              <tr>
                <td align="right" width="100%" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<?=SUB_TITLE_SUB_TOTAL;?>&nbsp;</font></td>
                <td align="right" width="100%" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;$<?=number_format($total_cost, 2);?>&nbsp;</font></td>
              </tr>
              <tr>
                <td align="right" width="100%" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<?=SUB_TITLE_TAX;?>&nbsp;</font></td>
                <td align="right" width="100%" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;$<?=number_format(($total_cost * TAX_VALUE/100), 2);?>&nbsp;</font></td>
              </tr>
              <tr>
                <td align="right" width="100%" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><?=SUB_TITLE_TOTAL;?></b>&nbsp;</font></td>
                <td align="right" width="100%" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b>$<?=number_format((($total_cost * TAX_VALUE/100) + $total_cost), 2);?></b>&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="3"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td colspan="3" align="right" nowrap><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<a href="<?=tep_href_link(FILENAME_CHECKOUT, '', 'NONSSL');?>"><?=tep_image(DIR_IMAGES . 'button_checkout.gif', '91', '24', '0', IMAGE_CHECKOUT);?></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?=tep_href_link(FILENAME_SHOPPING_CART, 'action=remove_all', 'NONSSL');?>"><?=tep_image(DIR_IMAGES . 'button_remove_all.gif', '113', '24', '0', IMAGE_REMOVE_ALL);?></a>&nbsp;&nbsp;</font></td>
          </tr>
<?
  }
?>
        </table></td>
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
<?
  }
?>
<? $include_file = DIR_INCLUDES . 'application_bottom.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
