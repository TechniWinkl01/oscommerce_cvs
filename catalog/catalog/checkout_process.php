<? include('includes/application_top.php'); ?>
<?
  if ($HTTP_POST_VARS['sendto'] == '0') {
    $delivery = tep_db_query("select customers_firstname as firstname, customers_lastname as lastname, customers_street_address as street_address, customers_suburb as suburb, customers_city as city, customers_postcode as postcode, customers_state as state, customers_zone_id as zone_id, customers_country_id as country_id from customers where customers_id = '" . $customer_id . "'");
  } else {
    $delivery = tep_db_query("select entry_firstname as firstname, entry_lastname as lastname, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from address_book where address_book_id = '" . $HTTP_POST_VARS['sendto'] . "'");
  }
  $delivery_values = tep_db_fetch_array($delivery);
  $delivery_country = tep_get_countries($delivery_values['country_id']);

  $customer = tep_db_query("select customers_firstname, customers_lastname, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_zone_id, customers_country_id, customers_telephone, customers_email_address from customers where customers_id = '" . $customer_id . "'");
  $customer_values = tep_db_fetch_array($customer);
  $customers_country = tep_get_countries($customer_values['customers_country_id']);

  $date_now = date('Ymd');

  $delivery_name = $delivery_values['firstname'] . ' ' . $delivery_values['lastname'];
  $customer_name = $customer_values['customers_firstname'] . ' ' . $customer_values['customers_lastname'];
  $shipping_cost = $HTTP_POST_VARS['shipping'];
  $shipping_method = $HTTP_POST_VARS['shipping_method'];
  $cust_state = tep_get_zone_name($customer_values['customers_country_id'], $customer_values['customers_zone_id'], $customer_values['customers_state']);
  $del_state = tep_get_zone_name($delivery_values['country_id'], $delivery_values['zone_id'], $delivery_values['state']);

  tep_db_query("insert into orders values ('', '" . $customer_id . "', '" . $customer_name . "', '" . $customer_values['customers_street_address'] . "', '" . $customer_values['customers_suburb'] . "', '" . $customer_values['customers_city'] . "', '" . $customer_values['customers_postcode'] . "', '" . $cust_state . "', '" . $customers_country['countries_name'] . "', '" . $customer_values['customers_telephone'] . "', '" . $customer_values['customers_email_address'] . "', '" . $delivery_name . "', '" . $delivery_values['street_address'] . "', '" . $delivery_values['suburb'] . "', '" . $delivery_values['city'] . "', '" . $delivery_values['postcode'] . "', '" . $del_state . "', '" . $delivery_country['countries_name'] . "', '" . $HTTP_POST_VARS['payment'] . "', '" . $HTTP_POST_VARS['cc_type'] . "', '" . $HTTP_POST_VARS['cc_owner'] . "', '" . $HTTP_POST_VARS['cc_number'] . "', '" . $HTTP_POST_VARS['cc_expires'] . "', '" . $date_now . "', '" . $shipping_cost . "', '" . $shipping_method . "' , 'Pending', '')");
  $insert_id = tep_db_insert_id();  

  $cart = tep_db_query("select customers_basket.customers_basket_quantity, manufacturers.manufacturers_name, manufacturers.manufacturers_location, products.products_id, products.products_name, products.products_price, products.products_tax_class_id, customers_basket.final_price from customers_basket, manufacturers, products_to_manufacturers, products where customers_basket.customers_id = '" . $customer_id . "' and customers_basket.products_id = products.products_id and products.products_id = products_to_manufacturers.products_id and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id order by customers_basket.customers_basket_id");
  $products_ordered = ''; // initialized for the email confirmation
  $subtotal = 0; // initialized for the email confirmation
  $tax = 0;
  while ($cart_values = tep_db_fetch_array($cart)) {
    $price = $cart_values['products_price'];
	$final_price = $cart_values['final_price'];
    $check_special = tep_db_query("select specials_new_products_price from specials where products_id = '" . $cart_values[products_id] . "'");
    if (@tep_db_num_rows($check_special)) {
      $check_special_values = tep_db_fetch_array($check_special);
      $price = $check_special_values['specials_new_products_price'];
    }
	
    $products_name = tep_products_name($cart_values['manufacturers_location'], $cart_values['manufacturers_name'], $cart_values['products_name']);
    $taxrate = tep_get_tax_rate($delivery_values['zone_id'], $cart_values['products_tax_class_id']);
    tep_db_query("insert into orders_products values ('', '" . $insert_id . "', '" . $cart_values['products_id'] . "', '" . addslashes($products_name) . "', '" . $price . "', '" . $final_price . "', '" . $taxrate . "', '" . $cart_values['customers_basket_quantity'] . "')");
//------insert customer choosen option to order--------            
	$product_attributes_check = tep_db_query("select products_attributes_id from products_attributes where products_id = '" . $cart_values['products_id'] . "'");	  
	if (@tep_db_num_rows($product_attributes_check)) {
	$orders_id = tep_db_query("select orders_products_id from orders_products where orders_id = '" . $insert_id . "' and products_id = '" . $cart_values['products_id'] . "'");
		while ($orders_id_values = tep_db_fetch_array($orders_id)) {
		$orders_prod_attrib = tep_db_query("select products_options_name, products_options_values_name, options_values_price, price_prefix from customers_basket cb, products_attributes_to_basket pa2b, products_attributes pa, products_options po, products_options_values pov where cb.customers_basket_id = pa2b.customers_basket_id and cb.customers_id = '" . $customer_id . "' and pa2b.products_attributes_id = pa.products_attributes_id and pa.options_id = po.products_options_id and pa.options_values_id = pov.products_options_values_id and pa.products_id = '" . $cart_values['products_id'] . "'");
			while ($orders_prod_attrib_values = tep_db_fetch_array($orders_prod_attrib)) {
			tep_db_query("insert into orders_products_attributes values ('', '" . $orders_id_values['orders_products_id'] . "', '" . $orders_prod_attrib_values['products_options_name'] . "', '" . $orders_prod_attrib_values['products_options_values_name'] . "', '" . $orders_prod_attrib_values['options_values_price'] . "', '" . $orders_prod_attrib_values['price_prefix'] . "')");
			}
		}
	}
//------insert customer choosen option eof ---- 

    $cost = $cart_values['customers_basket_quantity'] * $final_price;
    $products_ordered .= $cart_values['customers_basket_quantity'] . ' x ' . $products_name . '= ' . tep_currency_format($cost) . "\n";
    $subtotal = $subtotal + $cost;
    $tax = $tax + ($cost * $taxrate/100);
  }

  tep_db_query("delete from customers_basket where customers_id = '" . $customer_id . "'");

// lets start with the email confirmation function ;) ..right now its ugly, but its straight text - non html!
  $date_formatted = strftime(DATE_FORMAT_LONG, mktime(0,0,0,substr($date_now, 4, 2),substr($date_now, -2),substr($date_now, 0, 4)));
  $total = $subtotal + $tax + $shipping_cost;

  $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PROCESS; include(DIR_INCLUDES . 'include_once.php');
  $message = EMAIL_ORDER;
  mail($customer_values['customers_email_address'], EMAIL_TEXT_SUBJECT, $message, 'From: ' . EMAIL_FROM);
  
// why a redirect? if the user pushes 'Refresh' on their browser, it wont process the products a second time..
  header('Location: ' . tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL'));
  tep_exit();
?>
<? $include_file = DIR_INCLUDES . 'application_bottom.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
