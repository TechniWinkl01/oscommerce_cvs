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
  $cust_fmt_id = tep_get_address_format_id($customer_values['customers_country_id']);
  $del_state = tep_get_zone_name($delivery_values['country_id'], $delivery_values['zone_id'], $delivery_values['state']);
  $del_fmt_id = tep_get_address_format_id($delivery_values['country_id']);

  tep_db_query("insert into orders values ('', '" . $customer_id . "', '" . $customer_name . "', '" . $customer_values['customers_street_address'] . "', '" . $customer_values['customers_suburb'] . "', '" . $customer_values['customers_city'] . "', '" . $customer_values['customers_postcode'] . "', '" . $cust_state . "', '" . $customers_country['countries_name'] . "', '" . $customer_values['customers_telephone'] . "', '" . $customer_values['customers_email_address'] . "', '" . $cust_fmt_id . "', '" . $delivery_name . "', '" . $delivery_values['street_address'] . "', '" . $delivery_values['suburb'] . "', '" . $delivery_values['city'] . "', '" . $delivery_values['postcode'] . "', '" . $del_state . "', '" . $delivery_country['countries_name'] . "', '" . $del_fmt_id . "', '" . $HTTP_POST_VARS['payment'] . "', '" . $HTTP_POST_VARS['cc_type'] . "', '" . $HTTP_POST_VARS['cc_owner'] . "', '" . $HTTP_POST_VARS['cc_number'] . "', '" . $HTTP_POST_VARS['cc_expires'] . "', '" . $date_now . "', '" . $shipping_cost . "', '" . $shipping_method . "', 'Pending', '')");
  $insert_id = tep_db_insert_id();  

  $products_ordered = ''; // initialized for the email confirmation
  $subtotal = 0; // initialized for the email confirmation
  $tax = 0;

  $products = $cart->get_products();
  for ($i=0; $i<sizeof($products); $i++) {
    $products_id = $products[$i]['id'];
    $products_name = $products[$i]['name'];
    $price = $products[$i]['price'];
	$final_price = $products[$i]['final_price'];
    $taxrate = tep_get_tax_rate($delivery_values['zone_id'], $products[$i]['tax_class_id']);

    tep_db_query("insert into orders_products values ('', '" . $insert_id . "', '" . $products_id . "', '" . addslashes($products_name) . "', '" . $price . "', '" . $final_price . "', '" . $taxrate . "', '" . $products[$i]['quantity'] . "')");
//------insert customer choosen option to order--------
    if ($cart->contents[$products[$i]['id']]['attributes']) {
      reset($cart->contents[$products[$i]['id']]['attributes']);
      while (list($option, $value) = each($cart->contents[$products[$i]['id']]['attributes'])) {
        $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from products_options popt, products_options_values poval, products_attributes pa where pa.products_id = '" . $products[$i]['id'] . "' and pa.options_id = '" . $option . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $value . "' and pa.options_values_id = poval.products_options_values_id");
        $attributes_values = tep_db_fetch_array($attributes);

        tep_db_query("insert into orders_products_attributes values ('', '" . $insert_id . "', '" . $products_id . "', '" . $attributes_values['products_options_name'] . "', '" . $attributes_values['products_options_values_name'] . "', '" . $attributes_values['options_values_price'] . "', '" . $attributes_values['price_prefix'] . "')");
      }
    }
//------insert customer choosen option eof ---- 

    $products_ordered .= $products[$i]['quantity'] . 'x ' . $products_name . '= ' . tep_currency_format($products[$i]['final_price']) . "\n";
    $tax += ($final_price * $taxrate/100);
     $subtotal += $final_price;
  }

  $cart->reset();

// lets start with the email confirmation function ;) ..right now its ugly, but its straight text - non html!
  $date_formatted = strftime(DATE_FORMAT_LONG, mktime(0,0,0,substr($date_now, 4, 2),substr($date_now, -2),substr($date_now, 0, 4)));
  $total = $subtotal + $tax + $shipping_cost;

  $paypal_total = round(($subtotal + $tax)*100)/100;
  $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PROCESS; include(DIR_INCLUDES . 'include_once.php');
  $message = EMAIL_ORDER;
  mail($customer_values['customers_email_address'], EMAIL_TEXT_SUBJECT, $message, 'From: ' . EMAIL_FROM);

// why a redirect? if the user pushes 'Refresh' on their browser, it wont process the products a second time..
	switch($HTTP_POST_VARS['payment']) {
		case 'cc' : // Credit Card
		case 'cod' : // Cash On Delivery
			header('Location: ' . tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL')); tep_exit();
			break;
		case 'paypal' : // PayPal
			header("Location: https://secure.paypal.com/xclick/business=" . rawurlencode(PAYPAL_ID) . "&item_name=" . rawurlencode(STORE_NAME . " " . TEXT_PAYMENT) . "&item_number=" . rawurlencode($insert_id) . "&amount=" . $paypal_total . "&shipping=" . $shipping_cost . "&return=" . urlencode(tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL')));
			break;
	}
?>
<? $include_file = DIR_INCLUDES . 'application_bottom.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
