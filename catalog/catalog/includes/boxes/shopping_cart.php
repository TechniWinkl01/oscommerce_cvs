<!-- shopping_cart //-->
          <tr>
            <td bgcolor="<?=BOX_HEADING_BACKGROUND_COLOR;?>" class="boxborder" nowrap><font face="<?=BOX_HEADING_FONT_FACE;?>" color="<?=BOX_HEADING_FONT_COLOR;?>" size="<?=BOX_HEADING_FONT_SIZE;?>">&nbsp;<a href="<?=tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL');?>" class="blacklink"><?=BOX_HEADING_SHOPPING_CART;?></a>&nbsp;</font></td>
          </tr>
          <tr>
            <td bgcolor="<?=BOX_CONTENT_BACKGROUND_COLOR;?>"><font face="<?=BOX_CONTENT_FONT_FACE;?>" color="<?=BOX_CONTENT_FONT_COLOR;?>" size="<?=BOX_CONTENT_FONT_SIZE;?>">
<?
  if ($cart->count_contents() > 0) {
    $models = $cart->get_products();
    for ($i=0; $i<sizeof($models); $i++) { // $models[$i]['id'] .. $models[$i]['model'] .. $models[$i]['quantity']
      echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $models[$i]['id'], 'NONSSL') . '">';
      if (tep_session_is_registered('new_products_id_in_cart') && $new_products_id_in_cart == $models[$i]['id']) {
        echo '<font color="' . NEW_CART_ITEM_COLOR . '">';
      }
      echo $models[$i]['quantity'] . 'x ' . $models[$i]['model'];
      if (tep_session_is_registered('new_products_id_in_cart') && $new_products_id_in_cart == $models[$i]['id']) {
        echo '</font>';
        tep_session_unregister('new_products_id_in_cart');
      }
      echo '</a><br>';
    }
  } else {
    $cart_empty = 1;
    echo BOX_SHOPPING_CART_EMPTY;
  }
?></font></td>
          </tr>
          <tr>
            <td bgcolor="<?=BOX_CONTENT_BACKGROUND_COLOR;?>"><?=tep_black_line();?></td>
          </tr>
<?
  if ($cart_empty != 1) {
?>
          <tr>
            <td align="right" bgcolor="<?=BOX_CONTENT_BACKGROUND_COLOR;?>"><font face="<?=BOX_CONTENT_FONT_FACE;?>" color="<?=BOX_CONTENT_FONT_COLOR;?>" size="<?=BOX_CONTENT_FONT_SIZE;?>"><?=BOX_SHOPPING_CART_SUBTOTAL . ' ' . tep_currency_format($cart->show_total());?></font></td>
          </tr>
          <tr>
            <td align="right" bgcolor="<?=BOX_CONTENT_BACKGROUND_COLOR;?>"><font face="<?=BOX_CONTENT_FONT_FACE;?>" color="<?=BOX_CONTENT_FONT_COLOR;?>" size="<?=BOX_CONTENT_FONT_SIZE;?>"><a href="<?=tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL');?>"><?=BOX_SHOPPING_CART_VIEW_CONTENTS;?></a></font></td>
          </tr>
<?
  }
?>
<!-- shopping_cart_eof //-->
