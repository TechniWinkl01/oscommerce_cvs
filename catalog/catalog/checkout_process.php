<?php
/*
  $Id: checkout_process.php,v 1.80 2001/12/28 15:55:43 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  include('includes/application_top.php');

  include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PROCESS);

// load payment modules as objects
  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment;

// load the before_process function from the payment modules
  $payment_modules->before_process();

// select the delivery address
  $delivery = tep_db_query("select entry_firstname as firstname, entry_lastname as lastname, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $customer_id . "' and address_book_id = '" . $sendto . "'");
  $delivery_values = tep_db_fetch_array($delivery);
  $delivery_country = tep_get_countries($delivery_values['country_id']);
// select the customer with the default address
  $customer = tep_db_query("select c.customers_firstname, c.customers_lastname, a.entry_street_address as customers_street_address, a.entry_suburb as customers_suburb, a.entry_city as customers_city, a.entry_postcode as customers_postcode, a.entry_state as customers_state, a.entry_zone_id as customers_zone_id, a.entry_country_id as customers_country_id, c.customers_telephone, c.customers_email_address from " . TABLE_CUSTOMERS . " c, " . TABLE_ADDRESS_BOOK . " a where c.customers_id = '" . $customer_id . "' and a.customers_id = '" . $customer_id . "' and a.address_book_id = 1");
  $customer_values = tep_db_fetch_array($customer);
  $customers_country = tep_get_countries($customer_values['customers_country_id']);

  $date_now = date('Ymd');

// Ugly fix, will be addressed properly later on
  while (list($key) = each($delivery_values)) $delivery_values[$key] = addslashes($delivery_values[$key]);
  while (list($key) = each($customer_values)) $customer_values[$key] = addslashes($customer_values[$key]);

  $delivery_name = $delivery_values['firstname'] . ' ' . $delivery_values['lastname'];
  $customer_name = $customer_values['customers_firstname'] . ' ' . $customer_values['customers_lastname'];

  $cust_state = tep_get_zone_name($customer_values['customers_country_id'], $customer_values['customers_zone_id'], $customer_values['customers_state']);
  $cust_fmt_id = tep_get_address_format_id($customer_values['customers_country_id']);
  $del_state = tep_get_zone_name($delivery_values['country_id'], $delivery_values['zone_id'], $delivery_values['state']);
  $del_fmt_id = tep_get_address_format_id($delivery_values['country_id']);

  tep_db_query("insert into " . TABLE_ORDERS . " (customers_id, customers_name, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_country, customers_telephone, customers_email_address, customers_address_format_id, delivery_name, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, delivery_address_format_id, payment_method, cc_type, cc_owner, cc_number, cc_expires, date_purchased, shipping_cost, shipping_method, orders_status, comments, currency, currency_value) values ('" . $customer_id . "', '" . $customer_name . "', '" . $customer_values['customers_street_address'] . "', '" . $customer_values['customers_suburb'] . "', '" . $customer_values['customers_city'] . "', '" . $customer_values['customers_postcode'] . "', '" . $cust_state . "', '" . $customers_country['countries_name'] . "', '" . $customer_values['customers_telephone'] . "', '" . $customer_values['customers_email_address'] . "', '" . $cust_fmt_id . "', '" . $delivery_name . "', '" . $delivery_values['street_address'] . "', '" . $delivery_values['suburb'] . "', '" . $delivery_values['city'] . "', '" . $delivery_values['postcode'] . "', '" . $del_state . "', '" . $delivery_country['countries_name'] . "', '" . $del_fmt_id . "', '" . $GLOBALS['payment'] . "', '" . $GLOBALS['cc_type'] . "', '" . $GLOBALS['cc_owner'] . "', '" . $GLOBALS['cc_number'] . "', '" . $GLOBALS['cc_expires'] . "', now(), '" . $GLOBALS['shipping_cost'] . "', '" . $GLOBALS['shipping_method'] . "', '1', '" . addslashes($comments) . "', '" . $currency . "', '" . $currencies->get_value($currency) . "')");
  $insert_id = tep_db_insert_id();

  $products_ordered = ''; // initialized for the email confirmation
  $subtotal = 0; // initialized for the email confirmation
  $tax = 0;

  $products = $cart->get_products();
  for ($i=0; $i<sizeof($products); $i++) {
    $products_model = $products[$i]['model'];
    $products_name = $products[$i]['name'];
    $products_price = $products[$i]['price'];
    $total_products_price = ($products_price + $cart->attributes_price($products[$i]['id']));
    $products_tax = tep_get_tax_rate($delivery_values['country_id'], $delivery_values['zone_id'], $products[$i]['tax_class_id']);    
    $products_weight = $products[$i]['weight'];

    // Stock Update - Joao Correia
    if (STOCK_LIMITED =='true') {
      $qtd_stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id like '" . $products[$i]['id'] . "'");
      $stock = tep_db_fetch_array($qtd_stock_query);

      $qtd_stock = $stock['products_quantity'];
      $qtd_buy =  ($products[$i]['quantity']);
      $qtd_left = ($qtd_stock -= $qtd_buy);

      tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $qtd_left ."' where products_id like '" . $products[$i]['id'] . "'");

      if ($qtd_left == '0') {
        tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id like '" . $products[$i]['id'] . "'");
      }
    }
    // Stock Update !

    tep_db_query("insert into " . TABLE_ORDERS_PRODUCTS . " (orders_id, products_id, products_model, products_name, products_price, final_price, products_tax, products_quantity) values ('" . $insert_id . "', '" . tep_get_prid($products[$i]['id'])  . "', '" . addslashes($products_model) . "', '" . addslashes($products_name) . "', '" . $products_price . "', '"  . $total_products_price . "', '" . $products_tax . "', '" . $products[$i]['quantity']   . "')");
    $order_products_id = tep_db_insert_id();

//------insert customer choosen option to order--------
    $attributes_exist = '0';
    $products_ordered_attributes = '';
    if ($products[$i]['attributes']) {
      $attributes_exist = '1';
      reset($products[$i]['attributes']);
      while (list($option, $value) = each($products[$i]['attributes'])) {
        $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $products[$i]['id'] . "' and pa.options_id = '" . $option . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $value . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
        $attributes_values = tep_db_fetch_array($attributes);
        tep_db_query("insert into " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " (orders_id, orders_products_id, products_options, products_options_values, options_values_price, price_prefix) values ('" . $insert_id . "', '" . $order_products_id . "', '" . $attributes_values['products_options_name'] . "', '" . $attributes_values['products_options_values_name'] . "', '" . $attributes_values['options_values_price'] . "', '" . $attributes_values['price_prefix']  . "')");
        $products_ordered_attributes .= "\n\t" . $attributes_values['products_options_name'] . ' ' . $attributes_values['products_options_values_name'];
      }
    }
//------insert customer choosen option eof ----
    $total_weight += ($products[$i]['quantity'] * $products_weight);
    if (TAX_INCLUDE == true) {
      $total_tax += (($total_products_price * $products[$i]['quantity']) - (($total_products_price * $products[$i]['quantity']) / (($products_tax/100)+1)));
    } else {
      $total_tax += (($total_products_price * $products[$i]['quantity']) * $products_tax/100);
    }
    $total_cost += $total_products_price;

    $products_ordered .= $products[$i]['quantity'] . ' x ' . $products_name . ' (' . $products[$i]['model'] . ') = ' . $currencies->format($total_products_price * $products[$i]['quantity']) . $products_ordered_attributes . "\n";
  }

// lets start with the email confirmation function ;) ..right now its ugly, but its straight text - non html!
  $date_formatted = strftime(DATE_FORMAT_LONG, mktime(0,0,0,substr($date_now, 4, 2),substr($date_now, -2),substr($date_now, 0, 4)));

  $email_order = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $insert_id . "\n" . EMAIL_TEXT_INVOICE_URL . " " . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false) . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . $date_formatted . "\n\n";
  if ($comments) {
    $email_order .= $comments . "\n\n";
  }
  $email_order .= EMAIL_TEXT_PRODUCTS . "\n" . EMAIL_SEPARATOR . "\n" . $products_ordered . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_SUBTOTAL . ' ' . $currencies->format($cart->show_total()) . "\n" . EMAIL_TEXT_TAX . $currencies->format($total_tax) . "\n";
  if ($GLOBALS['shipping_cost'] > 0) {
    $email_order .= EMAIL_TEXT_SHIPPING . ' ' . $currencies->format($GLOBALS['shipping_cost']) . ' ' . TEXT_EMAIL_VIA . ' ' . $GLOBALS['shipping_method'] . "\n";
  }
  $email_order .= EMAIL_TEXT_TOTAL . ' ';
  if (TAX_INCLUDE == true) {
    $email_order .= $currencies->format($cart->show_total() + $GLOBALS['shipping_cost']);
  } else {
    $email_order .= $currencies->format($cart->show_total() + $total_tax + $GLOBALS['shipping_cost']);
  }
  $email_order .= "\n\n" . EMAIL_TEXT_DELIVERY_ADDRESS . "\n" . EMAIL_SEPARATOR . "\n";
  $email_order .= tep_address_label($customer_id, $sendto, 0, '', "\n") . "\n\n";
  if (is_object($GLOBALS[$GLOBALS['payment']])) {
    $email_order .= EMAIL_TEXT_PAYMENT_METHOD . "\n" . EMAIL_SEPARATOR . "\n";
    $email_order .= $GLOBALS[$GLOBALS['payment']]->title . "\n\n";
    if ($GLOBALS[$GLOBALS['payment']]->email_footer) { 
      $email_order .= $GLOBALS[$GLOBALS['payment']]->email_footer . "\n\n";
    }
  }
  tep_mail($customer_name, $customer_values['customers_email_address'], EMAIL_TEXT_SUBJECT, nl2br($email_order), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '');

// send emails to other people
  if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
    tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT, nl2br($email_order), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '');
  }

  $cart->reset(TRUE);
  tep_session_unregister('sendto');

// load the after_process function from the payment modules
  $payment_modules->after_process();
  tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL'));

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
