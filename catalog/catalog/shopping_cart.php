<? include("includes/application_top.php"); ?>
<?
  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'remove_product') {  // customer wants to remove a product from their shopping cart
//----------------------------------------------------------------------------------------
// @@@@   @@@@@  @      @@@@@  @@@@@  @@@@@       @@@   @   @  @@@@@
// @   @  @      @      @        @    @          @   @  @@  @  @
// @   @  @@@@   @      @@@@     @    @@@@       @   @  @ @ @  @@@@
// @   @  @      @      @        @    @          @   @  @  @@  @
// @@@@   @@@@@  @@@@@  @@@@@    @    @@@@@       @@@   @   @  @@@@@
//----------------------------------------------------------------------------------------
      if (tep_session_is_registered('customer_id')) {
//------insert customer choosen option --------
		$basket_id = tep_db_query('select customers_basket_id from customers_basket where products_id = ' . $HTTP_GET_VARS['products_id'] . ' and customers_id = ' . $customer_id);
		$basket_id = tep_db_fetch_array($basket_id);
		tep_db_query('delete from products_attributes_to_basket where customers_basket_id = ' . $basket_id['customers_basket_id']);
//------insert customer choosen option eof-----		
        tep_db_query('delete from customers_basket where products_id = ' . $HTTP_GET_VARS['products_id'] . ' and customers_id = ' . $customer_id);
      } else {
        if (tep_session_is_registered('nonsess_cart')) {
          $nonsess_cart_contents = explode('|', $nonsess_cart);
          for ($i=0;$i<sizeof($nonsess_cart_contents);$i++) {
            $product_info = explode(':', $nonsess_cart_contents[$i]);
            if ($product_info[0] == $HTTP_GET_VARS['products_id']) {
              array_splice($nonsess_cart_contents, $i, 1);
              break;
            }
          }
          $nonsess_cart = implode('|', $nonsess_cart_contents);
          if ($nonsess_cart == '') {
            tep_session_unregister('nonsess_cart');
          } else {
            tep_session_register('nonsess_cart');
          }
        }
      }
      header('Location: ' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'));
      tep_exit();
    } elseif ($HTTP_GET_VARS['action'] == 'add_update_product') {  // customer wants to update the product quantity in their shopping cart
//----------------------------------------------------------------------------------------
//   @    @@@@   @@@@       @  @   @  @@@@   @@@@     @    @@@@@  @@@@@  
//  @ @   @   @  @   @     @   @   @  @   @  @   @   @ @     @    @      
// @@@@@  @   @  @   @    @    @   @  @@@@   @   @  @@@@@    @    @@@@   
// @   @  @   @  @   @   @     @   @  @      @   @  @   @    @    @      
// @   @  @@@@   @@@@   @       @@@   @      @@@@   @   @    @    @@@@@  
//----------------------------------------------------------------------------------------
      // lets retrieve all $HTTP_POST_VARS keys and values..
      if (sizeof($HTTP_POST_VARS) > 0) {
        $keys = array_keys($HTTP_POST_VARS);
        $values = array_values($HTTP_POST_VARS);
  
        $new_quantity = '';
        $old_quantity = '';
        $products_id_to_change = '';
        for ($i=0;$i<sizeof($keys);$i++) {
          if ($keys[$i] == 'new_cart_quantity')
            $new_quantity = $values[$i];
          if ($keys[$i] == 'old_cart_quantity')
            $old_quantity = $values[$i];
          if ($keys[$i] == 'products_id')
            $products_id_to_change = $values[$i];
        }
      }
      else {
        // need to check get vars
        $products_id_to_change[0] = $HTTP_GET_VARS['products_id'];
        $old_quantity[0] = -1;
        $new_quantity[0] = 1;
      }


      if (tep_session_is_registered('customer_id')) { // customer is logged in
        for ($i=0;$i<sizeof($products_id_to_change);$i++) {
          // Skip if quantity is not changed
          if ($new_quantity[$i] == $old_quantity[$i])
            continue;
  
          // Skip if product does not exist
          $product_check = tep_db_query("select products_id from products where products_id = '" . $products_id_to_change[$i] . "'");
          if (!(@tep_db_num_rows($product_check)))
            continue;

          $product_exists_in_cart = tep_db_query("select products_id from customers_basket where products_id = '" . $products_id_to_change[$i] . "' and customers_id = '" . $customer_id . "'");
          if (@tep_db_num_rows($product_exists_in_cart)) {
            if ($new_quantity[$i] > 0) {
              if ($old_quantity[$i] >= 0) {
                tep_db_query("update customers_basket set customers_basket_quantity = '" . $new_quantity[$i] . "' where products_id = '" . $products_id_to_change[$i] . "' and customers_id = '" . $customer_id . "'");
              } else {
                tep_db_query("update customers_basket set customers_basket_quantity = customers_basket_quantity + '" . $new_quantity[$i] . "' where products_id = '" . $products_id_to_change[$i] . "' and customers_id = '" . $customer_id . "'");
              }
            } else {
//------insert customer choosen option --------
			  $basket_id = tep_db_query('select customers_basket_id from customers_basket where products_id = ' . $products_id_to_change[$i] . ' and customers_id = ' . $customer_id);
			  $basket_id = tep_db_fetch_array($basket_id);
			  tep_db_query('delete from products_attributes_to_basket where customers_basket_id = ' . $basket_id['customers_basket_id']);
//------insert customer choosen option eof-----
              tep_db_query("delete from customers_basket where products_id = '" . $products_id_to_change[$i] . "' and customers_id = '" . $customer_id . "'");
            }
          } else { // the product is not yet in their basket, so we'll add it
            if ($new_quantity[$i] > 0) {
              $date_now = date('Ymd');
              tep_db_query("insert into customers_basket values ('', '" . $customer_id . "', '" . $products_id_to_change[$i] . "', '" . $new_quantity[$i] . "', '', '" . $date_now . "')");
              $new_products_id_in_cart = $products_id_to_change[$i];
              tep_session_register('new_products_id_in_cart');
//------insert customer choosen option --------            
		$product_attributes_check = tep_db_query("select products_attributes_id from products_attributes where products_id = '" . $products_id_to_change[$i] . "'"); 
		if (tep_db_num_rows($product_attributes_check)) {
			$basket_id = tep_db_query("select customers_basket_id from customers_basket where customers_id = '" . $customer_id . "' and products_id = '" . $products_id_to_change[$i] . "'");
			$basket_id_values = tep_db_fetch_array($basket_id);
			$options = tep_db_query("select distinct options_id from products_attributes where products_id = '" . $products_id_to_change[$i] . "' order by products_id");
			$options_name_id = '';
			$value_id = '';
			while ($options_values = tep_db_fetch_array($options)) {
    		$option_name_id = $options_values['options_id'];
			$value_id = $HTTP_POST_VARS[$option_name_id];
			$attributes_id = tep_db_query("select products_attributes_id from products_attributes where products_id = '" . $products_id_to_change[$i] . "' and options_id = '" . $option_name_id . "' and options_values_id = '" . $value_id . "'");
			$attributes_id_values = tep_db_fetch_array($attributes_id);
			tep_db_query("insert into products_attributes_to_basket values ('', '" . $basket_id_values['customers_basket_id'] . "', '" . $attributes_id_values['products_attributes_id'] . "')");
			}
		}	  
//------insert customer choosen option eof ---- 	
            }
          }
        }
      } else { // customer is not logged in
        if (tep_session_is_registered('nonsess_cart')) { // does the customer have a per session cart?
          $nonsess_cart_contents = explode('|', $nonsess_cart);
          $nonsess_cart_contents_updated = '0'; // initialize flag 
          
          for ($i=0;$i<sizeof($products_id_to_change);$i++) {
            // Skip if quantity is not changed
            if ($new_quantity[$i] == $old_quantity[$i])
              continue;
  
            // Skip if product does not exist
            $product_check = tep_db_query("select products_id from products where products_id = '" . $products_id_to_change[$i] . "'");
            if (!(@tep_db_num_rows($product_check)))
              continue;
              
            for ($j=0;$j<sizeof($nonsess_cart_contents);$j++) { // lets see if the product exists in their per session cart
              $product_info = explode(':', $nonsess_cart_contents[$j]);
              if ($product_info[0] == $products_id_to_change[$i]) {
                if ($new_quantity[$i] > 0) {
                  if ($old_quantity[$i] >= 0) {
                    $product_info[1] = $new_quantity[$i]; // the product exists, so we'll update the quantity
                  } else {
                    $product_info[1] += $new_quantity[$i]; // the product exists, so we'll update the quantity
                  }
                  $nonsess_cart_contents[$j] = implode(':', $product_info);
                } else {
                  array_splice($nonsess_cart_contents, $j, 1);
                }
                $nonsess_cart_contents_updated = '1'; // we set a flag to know if the product exists and the quantity has been updated
                break;
              }
            }
            if ($nonsess_cart_contents_updated == '1') {
              $nonsess_cart = implode('|', $nonsess_cart_contents);
            } else {
              $nonsess_cart .= '|' . $products_id_to_change[$i] . ':' . $new_quantity[$i]; // add the product to their basket
              $new_products_id_in_cart = $products_id_to_change[$i];
              tep_session_register('new_products_id_in_cart');
            }
          }
        } else { // they do not yet have a per session basket
          $nonsess_cart = '';
          for ($i=0;$i<sizeof($products_id_to_change);$i++) {
            // Skip if quantity is not changed
            if ($new_quantity[$i] == $old_quantity[$i])
              continue;
  
            // Skip if product does not exist
            $product_check = tep_db_query("select products_id from products where products_id = '" . $products_id_to_change[$i] . "'");
            if (!(@tep_db_num_rows($product_check)))
              continue;

            // lets add their first product to their basket
            if ($nonsess_cart != '')
              $nonsess_cart .= '|';
              
            $nonsess_cart .= $products_id_to_change[$i] . ':' . $new_quantity[$i]; 
            $new_products_id_in_cart = $products_id_to_change[$i];
            tep_session_register('new_products_id_in_cart');
          }
        }
        if ($nonsess_cart == '') {
          tep_session_unregister('nonsess_cart');
        } else {
          tep_session_register('nonsess_cart');
        }
      }
      header('Location: ' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'));
      tep_exit();
      
    } elseif ($HTTP_GET_VARS['action'] == 'remove_all') {  // customer wants to remove all products from their shopping cart
//----------------------------------------------------------------------------------------
// @@@@   @@@@@  @      @@@@@  @@@@@  @@@@@        @    @      @    
// @   @  @      @      @        @    @           @ @   @      @
// @   @  @@@@   @      @@@@     @    @@@@       @@@@@  @      @   
// @   @  @      @      @        @    @          @   @  @      @
// @@@@   @@@@@  @@@@@  @@@@@    @    @@@@@      @   @  @@@@@  @@@@@
//----------------------------------------------------------------------------------------
      if (tep_session_is_registered('customer_id')) {
//------insert customer choosen option --------
		$basket_id = tep_db_query('select customers_basket_id from customers_basket where customers_id = ' . $customer_id);
		while ($basket_id_all = tep_db_fetch_array($basket_id)) {;
		tep_db_query('delete from products_attributes_to_basket where customers_basket_id = ' . $basket_id_all['customers_basket_id']);
		}
//------insert customer choosen option eof-----		
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

  //------------------------------------------------
  // Set up column widths and colspan
  //------------------------------------------------
  if (PRODUCT_LIST_MODEL) {
    $col_width = array ('width="7%"', 'width="8%"', 'width="12%"', 'width="61%"', 'width="12%"' );
    $colspan = 5;
  } 
  else {
    $col_width = array ('width="7%"', 'width="8%"', 'width="73%"', 'width="12%"' );
    $colspan = 4;
  }

?>
<form name="cart_quantity" method="post" action="<?=tep_href_link(FILENAME_SHOPPING_CART, 'action=add_update_product', 'NONSSL');?>">

          <tr>
            <td <? $col_idx=0; echo $col_width[$col_idx++];?>></td>
            <td <?=$col_width[$col_idx++];?> align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><?=TABLE_HEADING_QUANTITY;?></b>&nbsp;</font></td>

<?
  if (PRODUCT_LIST_MODEL) {
?>
            <td <?=$col_width[$col_idx++];?> nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><?=TABLE_HEADING_MODEL;?></b>&nbsp;</font></td>
<?
  }
?>

            <td <?=$col_width[$col_idx++];?> nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><?=TABLE_HEADING_PRODUCTS;?></b>&nbsp;</font></td>
            <td <?=$col_width[$col_idx++];?> align="right" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><?=TABLE_HEADING_TOTAL;?></b>&nbsp;</font></td>
          </tr>
          <tr>
            <td colspan="<?=$colspan;?>"><?=tep_black_line();?></td>
          </tr>
<?
    if (tep_session_is_registered('customer_id')) {
      $check_cart = tep_db_query("select customers_basket.customers_basket_id, customers_basket.customers_id, customers_basket.customers_basket_quantity, manufacturers.manufacturers_id, manufacturers.manufacturers_name, manufacturers.manufacturers_location, products.products_id, products.products_name, products.products_model, products.products_price from customers_basket, manufacturers, products_to_manufacturers, products where customers_basket.customers_id = '" . $customer_id . "' and customers_basket.products_id = products.products_id and products.products_id = products_to_manufacturers.products_id and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id order by customers_basket.customers_basket_id");
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

        echo '            <td '; $col_idx=0; echo $col_width[$col_idx++]; echo ' align="center"><a href="' . tep_href_link(FILENAME_SHOPPING_CART, 'action=remove_product&products_id=' . $check_cart_values['products_id'], 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_small_delete.gif', '50', '14', '0', 'Remove ' . $products_name . ' from Shopping Cart.') . '</a></td>' . "\n";
        echo '            <td '; echo $col_width[$col_idx++]; echo ' align="center" nowrap><input type="text" name="new_cart_quantity[]" value="' . $check_cart_values['customers_basket_quantity'] . '" maxlength="2" size="2"><input type="hidden" name="old_cart_quantity[]" value="' . $check_cart_values['customers_basket_quantity'] . '"><input type="hidden" name="products_id[]" value="' . $check_cart_values['products_id'] . '"></td>' . "\n";

        if (PRODUCT_LIST_MODEL) {
          echo '            <td ' . $col_width[$col_idx++] . ' nowrap><font face="' , TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $check_cart_values['products_id'], 'NONSSL') . '">' . $check_cart_values['products_model'] . '</a>&nbsp;</font></td>' . "\n";
        }

        echo '            <td ' . $col_width[$col_idx++] . ' nowrap><font face="' , TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $check_cart_values['products_id'], 'NONSSL') . '"><b>' . $products_name . '</b></a>' . "\n";
//------insert customer choosen option --------
		$attributes_exist = '';
		$attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name from products_options popt, products_options_values poval, products_attributes pa, products_attributes_to_basket pa2b, customers_basket cb where cb.customers_id = '" . $check_cart_values['customers_id'] . "' and pa.products_id = '" . $check_cart_values['products_id'] . "' and pa2b.customers_basket_id = cb.customers_basket_id and pa2b.products_attributes_id = pa.products_attributes_id and pa.options_id = popt.products_options_id and pa.options_values_id = poval.products_options_values_id");
        if (tep_db_num_rows($attributes)) {
		$attributes_exist = '1';
		while ($attributes_values = tep_db_fetch_array($attributes)) {
		echo "\n" . '<br>&nbsp;-&nbsp;' . $attributes_values['products_options_name'] . '&nbsp;:&nbsp;' . $attributes_values['products_options_values_name'];
		}
		}
//------insert customer choosen option eof-----
		echo '</font></td>' . "\n";
		echo '            <td ' . $col_width[$col_idx++] . ' align="right" valign="top" nowrap><font face="' , TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . tep_currency_format($check_cart_values['customers_basket_quantity'] * $price) . '&nbsp;';
//------insert customer choosen option --------
		if ($attributes_exist == '1') {
        $attributes = tep_db_query("select pa.options_values_price, pa.price_prefix from products_options popt, products_options_values poval, products_attributes pa, products_attributes_to_basket pa2b, customers_basket cb where cb.customers_id = '" . $check_cart_values['customers_id'] . "' and pa.products_id = '" . $check_cart_values['products_id'] . "' and pa2b.customers_basket_id = cb.customers_basket_id and pa2b.products_attributes_id = pa.products_attributes_id and pa.options_id = popt.products_options_id and pa.options_values_id = poval.products_options_values_id");
		$final_price=$price;
		while ($attributes_values = tep_db_fetch_array($attributes)) {
			  if ($attributes_values['options_values_price'] != '0') {
			  	if ($attributes_values['price_prefix'] =='+') {
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
		// echo '<tr><td colspan="' . ($colspan-1) . '" align="right"><font face="' , TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '"><b>' . SUB_TITLE_FINAL . '</b></font></td>';
		// echo '<td align="right"><font face="' , TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '"><b>' . tep_currency_format($final_price*$check_cart_values['customers_basket_quantity']) . '&nbsp;</b></font></td>';
		tep_db_query("update customers_basket set final_price = '" . $final_price . "' where customers_basket_id = '" . $check_cart_values['customers_basket_id'] . "'");
		} else {
		tep_db_query("update customers_basket set final_price = '" . $price . "' where customers_basket_id = '" . $check_cart_values['customers_basket_id'] . "'");
		$final_price = $price;
		}
//------insert customer choosen option eof-----
        $total_cost = $total_cost + ($check_cart_values['customers_basket_quantity'] * $final_price);
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
          $check_cart = tep_db_query("select manufacturers.manufacturers_id,  manufacturers.manufacturers_name, manufacturers.manufacturers_location, products.products_name, products.products_model, products.products_price from manufacturers, products, products_to_manufacturers where products.products_id = '" . $product_info[0] . "' and products.products_id = products_to_manufacturers.products_id and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id");
          $check_cart_values = tep_db_fetch_array($check_cart);
          $price = $check_cart_values['products_price'];
          $check_special = tep_db_query("select specials_new_products_price from specials where products_id = '" . $product_info[0] . "'");
          if (@tep_db_num_rows($check_special)) {
            $check_special_values = tep_db_fetch_array($check_special);
            $price = $check_special_values['specials_new_products_price'];
          }
          $products_name = tep_products_name($check_cart_values['manufacturers_location'], $check_cart_values['manufacturers_name'], $check_cart_values['products_name']);
          
          echo '          <tr>' . "\n";
          echo '            <td '; $col_idx=0; echo $col_width[$col_idx++]; echo ' align="center"><a href="' . tep_href_link(FILENAME_SHOPPING_CART, 'action=remove_product&products_id=' . $product_info[0], 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_small_delete.gif', '50', '14', '0', 'Remove ' . $products_name . ' from Shopping Cart.') . '</a></td>' . "\n";
          echo '            <td ' . $col_width[$col_idx++] . ' align="center" nowrap><input type="text" name="new_cart_quantity[]" value="' . $product_info[1] . '" maxlength="2" size="2"><input type="hidden" name="old_cart_quantity[]" value="' . $product_info[1] . '"><input type="hidden" name="products_id[]" value="' . $product_info[0] . '"></td>' . "\n";

          if (PRODUCT_LIST_MODEL) {
            echo '            <td '; echo $col_width[$col_idx++]; echo ' nowrap><font face="' , TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_info[0], 'NONSSL') . '">' . $check_cart_values['products_model'] . '</a>&nbsp;</font></td>' . "\n";
          }

          echo '            <td ' . $col_width[$col_idx++] . ' nowrap><font face="' , TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_info[0], 'NONSSL') . '">' . $products_name . '</a>&nbsp;</font></td>' . "\n";
          echo '            <td ' . $col_width[$col_idx++] . ' align="right" nowrap><font face="' , TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . tep_currency_format($product_info[1] * $price) . '&nbsp;</font></td>' . "\n";
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
    echo '            <td colspan="' . $colspan . '" nowrap><font face="' , TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . TEXT_CART_EMPTY . '&nbsp;</font></td>' . "\n";
    echo '          </tr>' . "\n";
    echo '          <tr>' . "\n";
    echo '            <td colspan="' . $colspan . '">' . tep_black_line() . '</td>' . "\n";
    echo '          </tr>' . "\n";
    echo '          <tr>' . "\n";
    echo '            <td colspan="' . $colspan . '" align="right" nowrap><br><font face="' . TABLE_HEADING_FONT_FACE . '" size="' . TABLE_HEADING_FONT_SIZE . '" color="' . TABLE_HEADING_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_main_menu.gif', '112', '24', '0', IMAGE_MAIN_MENU) . '</a>&nbsp;&nbsp;</font></td>' . "\n";
    echo '          </tr>' . "\n";
  }

  if ($cart_empty != 1) {
?>
          <tr>
            <td colspan="<?=$colspan;?>"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td colspan="<?=$colspan;?>" align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0" align="right">
              <tr>
                <td align="right" width="100%" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<?=SUB_TITLE_SUB_TOTAL;?>&nbsp;</font></td>
                <td align="right" width="100%" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<?=tep_currency_format($total_cost);?>&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="<?=$colspan;?>"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td colspan="<?=$colspan;?>" align="right" nowrap><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=tep_image_submit(DIR_IMAGES . 'button_update_cart.gif', '116', '24', '0', IMAGE_UPDATE_CART);?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?=tep_href_link(FILENAME_CHECKOUT, '', 'NONSSL');?>"><?=tep_image(DIR_IMAGES . 'button_checkout.gif', '91', '24', '0', IMAGE_CHECKOUT);?></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?=tep_href_link(FILENAME_SHOPPING_CART, 'action=remove_all', 'NONSSL');?>"><?=tep_image(DIR_IMAGES . 'button_remove_all.gif', '113', '24', '0', IMAGE_REMOVE_ALL);?></a>&nbsp;&nbsp;</font></td>
          </tr>
</form>
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
