<? include('includes/application_top.php'); ?>
<?
  if (!tep_session_is_registered('customer_id')) {
    header('Location: ' . tep_href_link(FILENAME_LOGIN, 'origin=checkout', 'NONSSL'));
    tep_exit();
  }
?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_CHECKOUT; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_CHECKOUT, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_1 . '</a> : ' . NAVBAR_TITLE_2; ?>
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
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_IMAGES . 'table_background_checkout.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE);?>&nbsp;</td>
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
      $check_cart = tep_db_query("select customers_basket.customers_basket_quantity, manufacturers.manufacturers_name, manufacturers.manufacturers_location, products.products_id, products.products_name, products.products_price, products.products_tax_class_id from customers_basket, manufacturers, products_to_manufacturers, products where customers_basket.customers_id = '" . $customer_id . "' and customers_basket.products_id = products.products_id and products.products_id = products_to_manufacturers.products_id and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id order by customers_basket.customers_basket_id");
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
        echo '            <td nowrap><font face="' , TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '"><b>&nbsp;' . $products_name . '&nbsp;</b>' . "\n";
//------insert customer choosen option --------
		$attributes_exist = '';
		$attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name from products_options popt, products_options_values poval, products_attributes pa, products_attributes_to_basket pa2b, customers_basket cb where cb.customers_id = '" . $customer_id . "' and pa.products_id = '" . $check_cart_values['products_id'] . "' and pa2b.customers_basket_id = cb.customers_basket_id and pa2b.products_attributes_id = pa.products_attributes_id and pa.options_id = popt.products_options_id and pa.options_values_id = poval.products_options_values_id");
        if (tep_db_num_rows($attributes)) {
		$attributes_exist = '1';
		while ($attributes_values = tep_db_fetch_array($attributes)) {
		echo "\n" . '<br>&nbsp;-&nbsp;' . $attributes_values['products_options_name'] . '&nbsp:&nbsp' . $attributes_values['products_options_values_name'];
		}
		}
//------insert customer choosen option eof-----		
		echo '</font></td>' . "\n";
        echo '            <td align="right" nowrap valign="top"><font face="' , TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . tep_currency_format($check_cart_values['customers_basket_quantity'] * $price) . '&nbsp;';
//------insert customer choosen option --------
		if ($attributes_exist == '1') {
        $attributes = tep_db_query("select pa.options_values_price, pa.price_prefix from products_options popt, products_options_values poval, products_attributes pa, products_attributes_to_basket pa2b, customers_basket cb where cb.customers_id = '" . $customer_id . "' and pa.products_id = '" . $check_cart_values['products_id'] . "' and pa2b.customers_basket_id = cb.customers_basket_id and pa2b.products_attributes_id = pa.products_attributes_id and pa.options_id = popt.products_options_id and pa.options_values_id = poval.products_options_values_id");
		$final_price=$price;
		while ($attributes_values = tep_db_fetch_array($attributes)) {
			  if ($attributes_values['options_values_price'] != '0') {
			  	if ($attributes_values['price_prefix'] == '+') {
			  	$final_price=$final_price+$attributes_values['options_values_price'];
				} else {
				$final_price=$final_price-$attributes_values['options_values_price'];
				}
			  echo "\n" . '<br>' . $attributes_values['price_prefix'] . tep_currency_format($check_cart_values['customers_basket_quantity'] * $attributes_values['options_values_price']) . '&nbsp;';
			  } else {
			  echo "\n" . '<br>&nbsp;';
			  }
		}
		}		
//------insert customer choosen option eof-----
		echo '</font></td>' . "\n";
        echo '          </tr>' . "\n";
//------insert customer choosen option --------
		if ($attributes_exist=='1') {
		// echo '<tr><td colspan="2" align="right"><font face="' , TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '"><b>' . SUB_TITLE_FINAL . '</b></font></td>';
		// echo '<td align="right"><font face="' , TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '"><b>' . tep_currency_format($final_price) . '&nbsp;</b></font></td>';
		} else {
		$final_price = $price;
		}
//------insert customer choosen option eof-----
        $cost = $check_cart_values['customers_basket_quantity'] * $final_price;
        $total_cost = $total_cost + $cost;
      }
    } elseif (tep_session_is_registered('nonsess_cart')) {
      $total_cost = 0;
      $product_in_cart = 0;
      $nonsess_cart_contents = explode('|', $nonsess_cart);
      for ($i=0;$i<sizeof($nonsess_cart_contents);$i++) {
        $product_info = explode(':', $nonsess_cart_contents[$i]);
        if (($product_info[0] != 0) && ($product_info[1] != 0)) {
          $product_in_cart = 1;
          $check_cart = tep_db_query("select manufacturers.manufacturers_name, manufacturers.manufacturers_location, products_name, products_price, products_tax_class_id from manufacturers, products, products_to_manufacturers where products.products_id = '" . $product_info[0] . "' and products.products_id = products_to_manufacturers.products_id and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id");
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
          echo '            <td nowrap><font face="' , TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . $products_name . '&nbsp;</font></td>' . "\n";
          echo '            <td align="right" nowrap><font face="' , TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . tep_currency_format($product_info[1] * $price) . '&nbsp;</font></td>' . "\n";
          echo '          </tr>' . "\n";
          $total_cost = $total_cost + ($product_info[1] * $price);
        }
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
                <td align="right" width="100%" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<?=tep_currency_format($total_cost);?>&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="3"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td colspan="3" align="right"><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=NO_SHIPPING_OR_TAX_TEXT;?>&nbsp;</font></td>
          </tr>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
<!--                <td><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_CURRENT_CONNECT_STATUS_SSL;?>&nbsp;<br>&nbsp;<a href="<?=tep_href_link(FILENAME_CHECKOUT, '', 'SSL');?>"><?=tep_image(DIR_IMAGES . 'button_unsecure_server.gif', '177', '24', '0', IMAGE_UNSECURE_SERVER);?></a>&nbsp;</font></td> //-->
                <td align="right"><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<a href="<?=tep_href_link(FILENAME_CHECKOUT_ADDRESS, '', 'SSL');?>"><?=tep_image(DIR_IMAGES . 'button_next.gif', '50', '24', '0', IMAGE_NEXT);?></a>&nbsp;&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="3" align="right"><br><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<font color="<?=CHECKOUT_BAR_TEXT_COLOR;?>">[ <font color="<?=CHECKOUT_BAR_TEXT_COLOR_HIGHLIGHTED;?>"><?=CHECKOUT_BAR_CART_CONTENTS;?></font> | <?=CHECKOUT_BAR_DELIVERY_ADDRESS;?> | <?=CHECKOUT_BAR_PAYMENT_METHOD;?> | <?=CHECKOUT_BAR_CONFIRMATION;?> | <?=CHECKOUT_BAR_FINISHED;?> ]</font>&nbsp;&nbsp;</font></td>
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
<? $include_file = DIR_INCLUDES . 'application_bottom.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
