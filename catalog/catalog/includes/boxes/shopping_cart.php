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
    $cart_contents_string = '<table border="0" width="100%" cellspacing="0" cellpadding="0">';
    $products = $cart->get_products(); // $products[$i]['id'] .. $products[$i]['name'] .. $products[$i]['quantity']
    for ($i=0; $i<sizeof($products); $i++) {
      $cart_contents_string .= '<tr><td align="right" valign="top" class="infoBox">';

      if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
        $cart_contents_string .= '<span class="newItemInCart">'; // highlight product quantity
      } else {
        $cart_contents_string .= '<span class="infoBox">';
      }

      $cart_contents_string .= $products[$i]['quantity'] . 'x</span></td><td valign="top" class="infoBox"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id'], 'NONSSL') . '">';

      if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
        $cart_contents_string .= '<span class="newItemInCart">'; // highlight product name
      } else {
        $cart_contents_string .= '<span class="infoBox">';
      }

      $cart_contents_string .= $products[$i]['name'] . '</span></a></td></tr>';

      if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
        tep_session_unregister('new_products_id_in_cart');
      }
    }
    $cart_contents_string .= '</table>';
  } else {
    $cart_empty = 1;
    $cart_contents_string .= BOX_SHOPPING_CART_EMPTY;
  }

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $cart_contents_string
                              );

  if (!$cart_empty) {
    $info_box_contents[] = array('align' => 'left',
                                 'text' => tep_black_line()
                           );
    $info_box_contents[] = array('align' => 'right',
                                 'text'  => BOX_SHOPPING_CART_SUBTOTAL . ' ' . $currencies->format($cart->show_total())
                                );
  }

  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- shopping_cart_eof //-->
