<? include('includes/application_top.php'); ?>
<?
  if ($HTTP_POST_VARS['sendto'] == '0') {
    $delivery = tep_db_query("select customers_firstname as firstname, customers_lastname as lastname, customers_street_address as street_address, customers_suburb as suburb, customers_city as city, customers_postcode as postcode, customers_state as state, customers_country_id as country from customers where customers_id = '" . $customer_id . "'");
  } else {
    $delivery = tep_db_query("select entry_firstname as firstname, entry_lastname as lastname, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_country_id as country from address_book where address_book_id = '" . $HTTP_POST_VARS['sendto'] . "'");
  }
  $delivery_values = tep_db_fetch_array($delivery);
  $delivery_country = tep_get_countries($delivery_values['country']);

  $customer = tep_db_query("select customers_firstname, customers_lastname, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_country_id, customers_telephone, customers_email_address from customers where customers_id = '" . $customer_id . "'");
  $customer_values = tep_db_fetch_array($customer);
  $customers_country = tep_get_countries($customer_values['customers_country_id']);

  $date_now = date('Ymd');

  $delivery_name = $delivery_values['firstname'] . ' ' . $delivery_values['lastname'];
  $customer_name = $customer_values['customers_firstname'] . ' ' . $customer_values['customers_lastname'];
  $shipping_cost = $HTTP_POST_VARS['shipping'];
  $shipping_method = $HTTP_POST_VARS['shipping_method'];

  tep_db_query("insert into orders values ('', '" . $customer_id . "', '" . $customer_name . "', '" . $customer_values['customers_street_address'] . "', '" . $customer_values['customers_suburb'] . "', '" . $customer_values['customers_city'] . "', '" . $customer_values['customers_postcode'] . "', '" . $customer_values['customers_state'] . "', '" . $customers_country['countries_name'] . "', '" . $customer_values['customers_telephone'] . "', '" . $customer_values['customers_email_address'] . "', '" . $delivery_name . "', '" . $delivery_values['street_address'] . "', '" . $delivery_values['suburb'] . "', '" . $delivery_values['city'] . "', '" . $delivery_values['postcode'] . "', '" . $delivery_values['state'] . "', '" . $delivery_country['countries_name'] . "', '" . $HTTP_POST_VARS['payment'] . "', '" . $HTTP_POST_VARS['cc_type'] . "', '" . $HTTP_POST_VARS['cc_owner'] . "', '" . $HTTP_POST_VARS['cc_number'] . "', '" . $HTTP_POST_VARS['cc_expires'] . "', '" . $date_now . "', '" . TAX_VALUE . "', '" . $shipping_cost . "', '" . $shipping_method . "' , 'Pending', '')");
  $insert_id = tep_db_insert_id();  

  $cart = tep_db_query("select customers_basket.customers_basket_quantity, manufacturers.manufacturers_name, manufacturers.manufacturers_location, products.products_id, products.products_name, products.products_price from customers_basket, manufacturers, products_to_manufacturers, products where customers_basket.customers_id = '" . $customer_id . "' and customers_basket.products_id = products.products_id and products.products_id = products_to_manufacturers.products_id and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id order by customers_basket.customers_basket_id");
  $products_ordered = ''; // initialized for the email confirmation
  $subtotal = ''; // initialized for the email confirmation
  while ($cart_values = tep_db_fetch_array($cart)) {
    $price = $cart_values['products_price'];
    $check_special = tep_db_query("select specials_new_products_price from specials where products_id = '" . $cart_values[products_id] . "'");
    if (@tep_db_num_rows($check_special)) {
      $check_special_values = tep_db_fetch_array($check_special);
      $price = $check_special_values['specials_new_products_price'];
    }
    $products_name = tep_products_name($cart_values['manufacturers_location'], $cart_values['manufacturers_name'], $cart_values['products_name']);
    tep_db_query("insert into orders_products values ('', '" . $insert_id . "', '" . $cart_values['products_id'] . "', '" . addslashes($products_name) . "', '" . $price . "', '" . $cart_values['customers_basket_quantity'] . "')");

    $products_ordered.= $cart_values['customers_basket_quantity'] . ' x ' . $products_name . '= ' . tep_currency_format($price * $cart_values['customers_basket_quantity']) . "\n";
    $subtotal = $subtotal + ($cart_values['customers_basket_quantity'] * $price);
  }

  tep_db_query("delete from customers_basket where customers_id = '" . $customer_id . "'");

// lets start with the email confirmation function ;) ..right now its ugly, but its straight text - non html!
  $date_formatted = strftime(DATE_FORMAT_LONG, mktime(0,0,0,substr($date_now, 4, 2),substr($date_now, -2),substr($date_now, 0, 4)));
  $tax = $subtotal * TAX_VALUE/100;
  $total = number_format(($subtotal + $tax + $shipping_cost), 2);
  $tax = number_format($tax, 2);
  $subtotal = number_format($subtotal, 2);

  $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PROCESS; include(DIR_INCLUDES . 'include_once.php');
  $message = EMAIL_ORDER;
  mail($customer_values['customers_email_address'], EMAIL_TEXT_SUBJECT, $message, 'From: ' . EMAIL_FROM);
  
// why a redirect? if the user pushes 'Refresh' on their browser, it wont process the products a second time..
  header('Location: ' . tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL'));
  tep_exit();
?>
<? $include_file = DIR_INCLUDES . 'application_bottom.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
