<?php
/*
  $Id: checkout_process.php,v 1.90 2002/02/03 00:57:14 clescuyer Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  include('includes/application_top.php');

  include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PROCESS);

// load payment modules as objects
  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment;

// load the before_process function from the payment modules
  $payment_modules->before_process();

// check for essential variables (payment module could lose them)
  if (!tep_session_is_registered('customer_id')) {
    tep_redirect(tep_href_link(FILENAME_LOGIN, 'origin=' . FILENAME_CHECKOUT_PAYMENT, 'SSL'));
  }
  if (!tep_session_is_registered('sendto')) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
  }

// select the delivery address
  $delivery = tep_db_query("select entry_firstname as firstname, entry_lastname as lastname, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $customer_id . "' and address_book_id = '" . $sendto . "'");
  $delivery_values = tep_db_fetch_array($delivery);
// select the customer with the default address
  $customer = tep_db_query("select c.customers_firstname, c.customers_lastname, a.entry_street_address as customers_street_address, a.entry_suburb as customers_suburb, a.entry_city as customers_city, a.entry_postcode as customers_postcode, a.entry_state as customers_state, a.entry_zone_id as customers_zone_id, a.entry_country_id as customers_country_id, c.customers_telephone, c.customers_email_address from " . TABLE_CUSTOMERS . " c, " . TABLE_ADDRESS_BOOK . " a where c.customers_id = '" . $customer_id . "' and a.customers_id = '" . $customer_id . "' and a.address_book_id = c.customers_default_address_id");
  $customer_values = tep_db_fetch_array($customer);

// Prepare values to insert in the database
  while (list($key) = each($delivery_values)) $delivery_values[$key] = addslashes($delivery_values[$key]);
  while (list($key) = each($customer_values)) $customer_values[$key] = addslashes($customer_values[$key]);

// create order
  $delivery_country = tep_get_countries($delivery_values['country_id']);
  $customers_country = tep_get_countries($customer_values['customers_country_id']);
  $delivery_name = $delivery_values['firstname'] . ' ' . $delivery_values['lastname'];
  $customer_name = $customer_values['customers_firstname'] . ' ' . $customer_values['customers_lastname'];
  $cust_state = tep_get_zone_name($customer_values['customers_country_id'], $customer_values['customers_zone_id'], $customer_values['customers_state']);
  $cust_fmt_id = tep_get_address_format_id($customer_values['customers_country_id']);
  $del_state = tep_get_zone_name($delivery_values['country_id'], $delivery_values['zone_id'], $delivery_values['state']);
  $del_fmt_id = tep_get_address_format_id($delivery_values['country_id']);

  tep_db_query("insert into " . TABLE_ORDERS . " (customers_id, customers_name, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_country, customers_telephone, customers_email_address, customers_address_format_id, delivery_name, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, delivery_address_format_id, payment_method, cc_type, cc_owner, cc_number, cc_expires, date_purchased, shipping_cost, shipping_method, orders_status, comments, currency, currency_value) values ('" . $customer_id . "', '" . $customer_name . "', '" . $customer_values['customers_street_address'] . "', '" . $customer_values['customers_suburb'] . "', '" . $customer_values['customers_city'] . "', '" . $customer_values['customers_postcode'] . "', '" . $cust_state . "', '" . $customers_country['countries_name'] . "', '" . $customer_values['customers_telephone'] . "', '" . $customer_values['customers_email_address'] . "', '" . $cust_fmt_id . "', '" . $delivery_name . "', '" . $delivery_values['street_address'] . "', '" . $delivery_values['suburb'] . "', '" . $delivery_values['city'] . "', '" . $delivery_values['postcode'] . "', '" . $del_state . "', '" . $delivery_country['countries_name'] . "', '" . $del_fmt_id . "', '" . $payment . "', '" . $GLOBALS['cc_type'] . "', '" . $GLOBALS['cc_owner'] . "', '" . $GLOBALS['cc_number'] . "', '" . $GLOBALS['cc_expires'] . "', now(), '" . $GLOBALS['shipping_cost'] . "', '" . $GLOBALS['shipping_method'] . "', '" . DEFAULT_ORDERS_STATUS_ID . "', '" . addslashes($comments) . "', '" . $currency . "', '" . $currencies->get_value($currency) . "')");
  $insert_id = tep_db_insert_id();

  $customer_notification = (SEND_EMAILS == 'true') ? '1' : '0';
  tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, new_value, date_added, customer_notified) values ('" . $insert_id . "', '" . DEFAULT_ORDERS_STATUS_ID . "', now(), '" . $customer_notification . "')");

// initialized for the email confirmation
  $products_ordered = '';
  $subtotal = 0;
  $total_tax = 0;

  $products = $cart->get_products();
  for ($i=0; $i<sizeof($products); $i++) {
    $products_model = $products[$i]['model'];
    $products_name = $products[$i]['name'];
    $products_price = $products[$i]['price'];
    $total_products_price = ($products_price + $cart->attributes_price($products[$i]['id']));
    $products_tax = tep_get_tax_rate($delivery_values['country_id'], $delivery_values['zone_id'], $products[$i]['tax_class_id']);    
    $products_weight = $products[$i]['weight'];
    $products_attributes = $products[$i]['attributes'];
// Will work with only one option for downloadable products
// otherwise, we have to build the query dynamically with a loop
    list ($options_id, $options_values_id) = each($products_attributes);

    // Stock Update - Joao Correia
    if (STOCK_LIMITED == 'true') {
      if (DOWNLOAD_ENABLED == 'true') {
        $stock_query_raw = "SELECT products_quantity, pad.products_attributes_filename 
                            FROM " . TABLE_PRODUCTS . " p
                            LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                             ON p.products_id=pa.products_id
                            LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                             ON pa.products_attributes_id=pad.products_attributes_id
                            WHERE p.products_id = '" . tep_get_prid($products[$i]['id']) . "' 
                             AND pa.options_id = '" . $options_id . "'
                             AND pa.options_values_id = '" . $options_values_id . "'";
        $stock_query = tep_db_query($stock_query_raw);
      } else {
        $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($products[$i]['id']) . "'");
      }
      $stock_values = tep_db_fetch_array($stock_query);
// do not decrement quantities if products_attributes_filename exists
      if ((DOWNLOAD_ENABLED == 'false') || (!$stock_values['products_attributes_filename']!= '')) {
        $stock_left = $stock_values['products_quantity'] - $products[$i]['quantity'];
  
        tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . tep_get_prid($products[$i]['id']) . "'");
        if ($stock_left < 1) {
          tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . tep_get_prid($products[$i]['id']) . "'");
        }
      }
    }

    tep_db_query("insert into " . TABLE_ORDERS_PRODUCTS . " (orders_id, products_id, products_model, products_name, products_price, final_price, products_tax, products_quantity) values ('" . $insert_id . "', '" . tep_get_prid($products[$i]['id'])  . "', '" . addslashes($products_model) . "', '" . addslashes($products_name) . "', '" . $products_price . "', '"  . $total_products_price . "', '" . $products_tax . "', '" . $products[$i]['quantity']   . "')");
    $order_products_id = tep_db_insert_id();

//------insert customer choosen option to order--------
    $attributes_exist = '0';
    $products_ordered_attributes = '';
    if ($products[$i]['attributes']) {
      $attributes_exist = '1';
      reset($products[$i]['attributes']);
      while (list($option, $value) = each($products[$i]['attributes'])) {
        if (DOWNLOAD_ENABLED == 'true') {
          $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, "  . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $products[$i]['id'] . "' and pa.options_id = '" . $option . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $value . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "' and pa.products_attributes_id=pad.products_attributes_id");
        } else {
          $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $products[$i]['id'] . "' and pa.options_id = '" . $option . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $value . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
        }
        $attributes_values = tep_db_fetch_array($attributes);
        tep_db_query("insert into " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " (orders_id, orders_products_id, products_options, products_options_values, options_values_price, price_prefix) values ('" . $insert_id . "', '" . $order_products_id . "', '" . $attributes_values['products_options_name'] . "', '" . $attributes_values['products_options_values_name'] . "', '" . $attributes_values['options_values_price'] . "', '" . $attributes_values['price_prefix']  . "')");
        if (DOWNLOAD_ENABLED == 'true') {
          tep_db_query("insert into " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " (orders_id, orders_products_id, orders_products_filename, download_maxdays, download_count) values ('" . $insert_id . "', '" . $order_products_id . "', '" . $attributes_values['products_attributes_filename'] . "', '" . $attributes_values['products_attributes_maxdays'] . "', '" . $attributes_values['products_attributes_maxcount']  . "')");
        }
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

// lets start with the email confirmation
  $email_order = STORE_NAME . "\n" . 
                 EMAIL_SEPARATOR . "\n" . 
                 EMAIL_TEXT_ORDER_NUMBER . ' ' . $insert_id . "\n" . 
                 EMAIL_TEXT_INVOICE_URL . ' ' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false) . "\n" . 
                 EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";
  if ($comments) {
    $email_order .= $comments . "\n\n";
  }
  $email_order .= EMAIL_TEXT_PRODUCTS . "\n" . 
                  EMAIL_SEPARATOR . "\n" . 
                  $products_ordered . 
                  EMAIL_SEPARATOR . "\n" . 
                  EMAIL_TEXT_SUBTOTAL . ' ' . $currencies->format($cart->show_total()) . "\n" . 
                  EMAIL_TEXT_TAX . $currencies->format($total_tax) . "\n";
  if ($GLOBALS['shipping_cost'] > 0) {
    $email_order .= EMAIL_TEXT_SHIPPING . ' ' . $currencies->format($GLOBALS['shipping_cost']) . ' ' . TEXT_EMAIL_VIA . ' ' . $GLOBALS['shipping_method'] . "\n";
  }
  if (TAX_INCLUDE == true) {
    $email_order .= EMAIL_TEXT_TOTAL . ' ' . $currencies->format($cart->show_total() + $GLOBALS['shipping_cost']) . "\n\n";
  } else {
    $email_order .= EMAIL_TEXT_TOTAL . ' ' . $currencies->format($cart->show_total() + $total_tax + $GLOBALS['shipping_cost']) . "\n\n";
  }
  $email_order .= EMAIL_TEXT_DELIVERY_ADDRESS . "\n" . 
                  EMAIL_SEPARATOR . "\n" .
                  tep_address_label($customer_id, $sendto, 0, '', "\n") . "\n\n";
  if (is_object($$payment)) {
    $email_order .= EMAIL_TEXT_PAYMENT_METHOD . "\n" . 
                    EMAIL_SEPARATOR . "\n";
    $email_order .= $$payment->title . "\n\n";
    if ($$payment->email_footer) { 
      $email_order .= $$payment->email_footer . "\n\n";
    }
  }
  tep_mail($customer_name, $customer_values['customers_email_address'], EMAIL_TEXT_SUBJECT, nl2br($email_order), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '');

// send emails to other people
  if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
    tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT, nl2br($email_order), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '');
  }

// load the after_process function from the payment modules
  $payment_modules->after_process();

  $cart->reset(TRUE);

// unregister session variables used during checkout
  tep_session_unregister('sendto');
  tep_session_unregister('comments');
  tep_session_unregister('payment');

  tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL'));

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>