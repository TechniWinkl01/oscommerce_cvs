<? include('includes/application_top.php'); ?>
<?
  $payment_action = 'PM_BEFORE_PROCESS';
  include(DIR_MODULES . 'payment.php');

  if ($sendto == '0') {
    $delivery = tep_db_query("select customers_firstname as firstname, customers_lastname as lastname, customers_street_address as street_address, customers_suburb as suburb, customers_city as city, customers_postcode as postcode, customers_state as state, customers_zone_id as zone_id, customers_country_id as country_id from customers where customers_id = '" . $customer_id . "'");
  } else {
    $delivery = tep_db_query("select entry_firstname as firstname, entry_lastname as lastname, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from address_book where address_book_id = '" . $sendto . "'");
  }
  $delivery_values = tep_db_fetch_array($delivery);
  $delivery_country = tep_get_countries($delivery_values['country_id']);

  $customer = tep_db_query("select customers_firstname, customers_lastname, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_zone_id, customers_country_id, customers_telephone, customers_email_address from customers where customers_id = '" . $customer_id . "'");
  $customer_values = tep_db_fetch_array($customer);
  $customers_country = tep_get_countries($customer_values['customers_country_id']);

  $date_now = date('Ymd');

  $delivery_name = $delivery_values['firstname'] . ' ' . $delivery_values['lastname'];
  $customer_name = $customer_values['customers_firstname'] . ' ' . $customer_values['customers_lastname'];

  $cust_state = tep_get_zone_name($customer_values['customers_country_id'], $customer_values['customers_zone_id'], $customer_values['customers_state']);
  $cust_fmt_id = tep_get_address_format_id($customer_values['customers_country_id']);
  $del_state = tep_get_zone_name($delivery_values['country_id'], $delivery_values['zone_id'], $delivery_values['state']);
  $del_fmt_id = tep_get_address_format_id($delivery_values['country_id']);

  tep_db_query("insert into orders (customers_id, customers_name, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_country, customers_telephone, customers_email_address, customers_address_format_id, delivery_name, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, delivery_address_format_id, payment_method, cc_type, cc_owner, cc_number, cc_expires, date_purchased, shipping_cost, shipping_method, orders_status) values ('" . $customer_id . "', '" . $customer_name . "', '" . $customer_values['customers_street_address'] . "', '" . $customer_values['customers_suburb'] . "', '" . $customer_values['customers_city'] . "', '" . $customer_values['customers_postcode'] . "', '" . $cust_state . "', '" . $customers_country['countries_name'] . "', '" . $customer_values['customers_telephone'] . "', '" . $customer_values['customers_email_address'] . "', '" . $cust_fmt_id . "', '" . $delivery_name . "', '" . $delivery_values['street_address'] . "', '" . $delivery_values['suburb'] . "', '" . $delivery_values['city'] . "', '" . $delivery_values['postcode'] . "', '" . $del_state . "', '" . $delivery_country['countries_name'] . "', '" . $del_fmt_id . "', '" . $payment . "', '" . $cc_type . "', '" . $cc_owner . "', '" . $cc_number . "', '" . $cc_expires . "', now(), '" . $shipping_cost . "', '" . $shipping_method . "', 'Pending')");
  $insert_id = tep_db_insert_id();

  $products_ordered = ''; // initialized for the email confirmation
  $subtotal = 0; // initialized for the email confirmation
  $tax = 0;

  $products = $cart->get_products();
  for ($i=0; $i<sizeof($products); $i++) {
    $products_name = $products[$i]['name'];
    $products_price = $products[$i]['price'];
    $total_products_price = ($products_price + $cart->attributes_price($products[$i]['id']));
    $products_tax = tep_get_tax_rate($delivery_values['zone_id'], $products[$i]['tax_class_id']);
    $products_weight = $products[$i]['weight'];

    tep_db_query("insert into orders_products values ('', '" . $insert_id . "', '" . $products[$i]['id'] . "', '" . addslashes($products_name) . "', '" . $products_price . "', '" . $total_products_price . "', '" . $products_tax . "', '" . $products[$i]['quantity'] . "')");
//------insert customer choosen option to order--------
    $attributes_exist = '0';
    if ($products[$i]['attributes']) {
      $attributes_exist = '1';
      reset($products[$i]['attributes']);
      while (list($option, $value) = each($products[$i]['attributes'])) {
        $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from products_options popt, products_options_values poval, products_attributes pa where pa.products_id = '" . $products[$i]['id'] . "' and pa.options_id = '" . $option . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $value . "' and pa.options_values_id = poval.products_options_values_id");
        $attributes_values = tep_db_fetch_array($attributes);
        tep_db_query("insert into orders_products_attributes values ('', '" . $insert_id . "', '" . $products[$i]['id'] . "', '" . $attributes_values['products_options_name'] . "', '" . $attributes_values['products_options_values_name'] . "', '" . $attributes_values['options_values_price'] . "', '" . $attributes_values['price_prefix'] . "')");
      }
    }
//------insert customer choosen option eof ---- 
    $total_weight += ($products[$i]['quantity'] * $products_weight);
    $total_tax += (($total_products_price * $products[$i]['quantity']) * $products_tax/100);
    $total_cost += $total_products_price;

    $products_ordered .= $products[$i]['quantity'] . ' x ' . $products_name . ' = ' . tep_currency_format(($total_products_price * $products[$i]['quantity'])) . "\n";
  }

// lets start with the email confirmation function ;) ..right now its ugly, but its straight text - non html!
  $date_formatted = strftime(DATE_FORMAT_LONG, mktime(0,0,0,substr($date_now, 4, 2),substr($date_now, -2),substr($date_now, 0, 4)));

  $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PROCESS; include(DIR_INCLUDES . 'include_once.php');
  $message = EMAIL_ORDER;

  mail($customer_values['customers_email_address'], EMAIL_TEXT_SUBJECT, $message, 'From: ' . EMAIL_FROM);

// send emails to other people
  if (defined('SEND_EXTRA_ORDER_EMAILS_TO')) {
    mail(SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT, $message, 'From: ' . EMAIL_FROM);
  }

  $cart->reset(TRUE);

  $payment_action = 'PM_AFTER_PROCESS';
  include(DIR_MODULES . 'payment.php');
?>
<? $include_file = DIR_INCLUDES . 'application_bottom.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
