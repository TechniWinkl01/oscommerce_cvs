<!-- shopping_cart //-->
          <tr>
            <td bgcolor="<?=BOX_HEADING_BACKGROUND_COLOR;?>" class="boxborder" nowrap><font face="<?=BOX_HEADING_FONT_FACE;?>" color="<?=BOX_HEADING_FONT_COLOR;?>" size="<?=BOX_HEADING_FONT_SIZE;?>">&nbsp;<a href="<?=tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL');?>" class="blacklink"><?=BOX_HEADING_SHOPPING_CART;?></a>&nbsp;</font></td>
          </tr>
          <tr>
            <td bgcolor="<?=BOX_CONTENT_BACKGROUND_COLOR;?>"><font face="<?=BOX_CONTENT_FONT_FACE;?>" color="<?=BOX_CONTENT_FONT_COLOR;?>" size="<?=BOX_CONTENT_FONT_SIZE;?>">
<?
  if (tep_session_is_registered("customer_id")) {
    $check_cart = tep_db_query("select customers_basket.products_id, customers_basket.customers_basket_quantity, products.products_model, customers_basket.final_price from customers_basket, products where customers_basket.customers_id = '" . $customer_id . "' and customers_basket.products_id = products.products_id");
    $total_cost = 0;
    if (tep_db_num_rows($check_cart)) {
      while ($check_cart_values = tep_db_fetch_array($check_cart)) {
        echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $check_cart_values['products_id'], 'NONSSL') . '">';
        if (tep_session_is_registered("new_products_id_in_cart") && $new_products_id_in_cart == $check_cart_values['products_id']) {
          echo '<font color="' . NEW_CART_ITEM_COLOR . '">';
        }
        echo $check_cart_values['customers_basket_quantity'] . 'x ' . $check_cart_values['products_model'];
        if (tep_session_is_registered("new_products_id_in_cart") && $new_products_id_in_cart == $check_cart_values['products_id']) {
          echo '</font>';
          tep_session_unregister('new_products_id_in_cart');
        }
        echo '</a><br>';
        $price = $check_cart_values['final_price'];
        $total_cost = $total_cost + ($check_cart_values['customers_basket_quantity'] * $price);
      }
    } else {
      $cart_empty = 1;
      echo BOX_SHOPPING_CART_EMPTY;
    }
  } else {
    if (tep_session_is_registered('nonsess_cart')) {
      $total_cost = 0;
      $product_in_cart = 0;
      $nonsess_cart_contents = explode('|', $nonsess_cart);
      for ($i=0;$i<sizeof($nonsess_cart_contents);$i++) {
        $product_info = explode(":", $nonsess_cart_contents[$i]);
        if (($product_info[0] != 0) && ($product_info[1] != 0)) {
          $product_in_cart = 1;
          $check_cart = tep_db_query("select products_model, products_price from products where products_id = '" . $product_info[0] . "'");
          $check_cart_values = tep_db_fetch_array($check_cart);
          $price = $check_cart_values['products_price'];
          echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_info[0], 'NONSSL') . '">';
          if (tep_session_is_registered("new_products_id_in_cart") && $new_products_id_in_cart == $product_info[0]) {
            echo '<font color="' . NEW_CART_ITEM_COLOR . '">';
          }
          echo $product_info[1] . 'x ' . $check_cart_values['products_model'];
          if (tep_session_is_registered("new_products_id_in_cart") && $new_products_id_in_cart == $product_info[0]) {
            echo '</font>';
            tep_session_unregister('new_products_id_in_cart');
          }
          echo '</a><br>';
          $check_special = tep_db_query("select specials_new_products_price from specials where products_id = '" . $product_info[0] . "'");
          if (tep_db_num_rows($check_special)) {
            $check_special_values = tep_db_fetch_array($check_special);
            $price = $check_special_values['specials_new_products_price'];
          }
          $total_cost = $total_cost + ($product_info[1] * $price);
        }
      }
    }
    if ($product_in_cart == 0) {
      tep_session_unregister('nonsess_cart');
    }
    if (!tep_session_is_registered('nonsess_cart')) {
      $cart_empty = 1;
      echo BOX_SHOPPING_CART_EMPTY;
    }
  } ?></font></td>
          </tr>
          <tr>
            <td bgcolor="<?=BOX_CONTENT_BACKGROUND_COLOR;?>"><?=tep_black_line();?></td>
          </tr>
<?
  if (!$cart_empty == 1) {
?>
          <tr>
            <td align="right" bgcolor="<?=BOX_CONTENT_BACKGROUND_COLOR;?>"><font face="<?=BOX_CONTENT_FONT_FACE;?>" color="<?=BOX_CONTENT_FONT_COLOR;?>" size="<?=BOX_CONTENT_FONT_SIZE;?>"><?=BOX_SHOPPING_CART_SUBTOTAL . ' ' . tep_currency_format($total_cost);?></font></td>
          </tr>
          <tr>
            <td align="right" bgcolor="<?=BOX_CONTENT_BACKGROUND_COLOR;?>"><font face="<?=BOX_CONTENT_FONT_FACE;?>" color="<?=BOX_CONTENT_FONT_COLOR;?>" size="<?=BOX_CONTENT_FONT_SIZE;?>"><a href="<?=tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL');?>"><?=BOX_SHOPPING_CART_VIEW_CONTENTS;?></a></font></td>
          </tr>
<?
  }
?>
<!-- shopping_cart_eof //-->
