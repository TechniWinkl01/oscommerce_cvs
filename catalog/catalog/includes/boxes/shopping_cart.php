<!-- shopping_cart //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => '<a href="' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL') . '" class="blacklink">' . BOX_HEADING_SHOPPING_CART . '</a>'
                              );
  new infoBoxHeading($info_box_contents);

  $cart_contents_string = '';
  if ($cart->count_contents() > 0) {
    $models = $cart->get_products();
    for ($i=0; $i<sizeof($models); $i++) { // $models[$i]['id'] .. $models[$i]['model'] .. $models[$i]['quantity']
      $cart_contents_string .= '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $models[$i]['id'], 'NONSSL') . '">';
      if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $models[$i]['id'])) $cart_contents_string .=  '<font color="' . NEW_CART_ITEM_COLOR . '">';
      $cart_contents_string .= $models[$i]['quantity'] . 'x ' . $models[$i]['model'];
      if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $models[$i]['id'])) {
        $cart_contents_string .= '</font>';
        tep_session_unregister('new_products_id_in_cart');
      }
      $cart_contents_string .= '</a><br>';
    }
  } else {
    $cart_empty = 1;
    $cart_contents_string .= BOX_SHOPPING_CART_EMPTY;
  }

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $cart_contents_string
                              );
  $info_box_contents[] = array('align' => 'left',
                               'text' => tep_black_line()
                         );

  if ($cart_empty != 1) {
    $info_box_contents[] = array('align' => 'right',
                                 'text'  => BOX_SHOPPING_CART_SUBTOTAL . ' ' . tep_currency_format($cart->show_total())
                                );
    $info_box_contents[] = array('align' => 'right',
                                 'text'  => '<a href="' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL') . '">' . BOX_SHOPPING_CART_VIEW_CONTENTS . '</a>'
                                );
  }

  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- shopping_cart_eof //-->
