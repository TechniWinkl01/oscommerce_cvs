<?php
/*
  $Id: checkout_process.php,v 1.97 2002/04/03 23:10:33 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  include('includes/application_top.php');

// check for essential variables (payment module could lose them)
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_PAYMENT));
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
  if (!tep_session_is_registered('sendto')) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
  }

  include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PROCESS);

// load payment modules as objects
  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment;

// load the before_process function from the payment modules
  $payment_modules->before_process();

  require(DIR_WS_CLASSES . 'order.php');
  require(DIR_WS_CLASSES . 'order_total.php');
  $order = new order;
  $order_total_modules = new order_total;

  $order_total_modules->process();

  tep_db_query("insert into " . TABLE_ORDERS . " (customers_id, customers_name, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_country, customers_telephone, customers_email_address, customers_address_format_id, delivery_name, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, delivery_address_format_id, payment_method, cc_type, cc_owner, cc_number, cc_expires, date_purchased, shipping_cost, shipping_method, orders_status, comments, currency, currency_value) values ('" . $customer_id . "', '" . $order->customer['name'] . "', '" . $order->customer['street_address'] . "', '" . $order->customer['suburb'] . "', '" . $order->customer['city'] . "', '" . $order->customer['postcode'] . "', '" . $order->customer['state'] . "', '" . $order->customer['country'] . "', '" . $order->customer['telephone'] . "', '" . $order->customer['email_address'] . "', '" . $order->customer['format_id'] . "', '" . $order->delivery['name'] . "', '" . $order->delivery['street_address'] . "', '" . $order->delivery['suburb'] . "', '" . $order->delivery['city'] . "', '" . $order->delivery['postcode'] . "', '" . $order->delivery['state'] . "', '" . $order->delivery['country'] . "', '" . $order->delivery['format_id'] . "', '" . $order->info['payment_method'] . "', '" . $order->info['cc_type'] . "', '" . $order->info['cc_owner'] . "', '" . $order->info['cc_number'] . "', '" . $order->info['cc_expires'] . "', now(), '" . $order->info['shipping_cost'] . "', '" . $order->info['shipping_method'] . "', '" . DEFAULT_ORDERS_STATUS_ID . "', '" . addslashes($order->info['comments']) . "', '" . $order->info['currency'] . "', '" . $order->info['currency_value'] . "')");
  $insert_id = tep_db_insert_id();

  $customer_notification = (SEND_EMAILS == 'true') ? '1' : '0';
  tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, new_value, date_added, customer_notified) values ('" . $insert_id . "', '" . DEFAULT_ORDERS_STATUS_ID . "', now(), '" . $customer_notification . "')");

// initialized for the email confirmation
  $products_ordered = '';
  $subtotal = 0;
  $total_tax = 0;

  for ($i=0; $i<sizeof($order->products); $i++) {
// Stock Update - Joao Correia
    if (STOCK_LIMITED == 'true') {
      if (DOWNLOAD_ENABLED == 'true') {
        $stock_query_raw = "SELECT products_quantity, pad.products_attributes_filename 
                            FROM " . TABLE_PRODUCTS . " p
                            LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                             ON p.products_id=pa.products_id
                            LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                             ON pa.products_attributes_id=pad.products_attributes_id
                            WHERE p.products_id = '" . tep_get_prid($order->products[$i]['id']) . "'";
// Will work with only one option for downloadable products
// otherwise, we have to build the query dynamically with a loop
        $products_attributes = $order->products[$i]['attributes'];
        if (is_array($products_attributes)) {
          list ($options_id, $options_values_id) = each($products_attributes);
          $stock_query_raw .= " AND pa.options_id = '" . $options_id . "' AND pa.options_values_id = '" . $options_values_id . "'";
        }
        $stock_query = tep_db_query($stock_query_raw);
      } else {
        $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
      }
      $stock_values = tep_db_fetch_array($stock_query);
// do not decrement quantities if products_attributes_filename exists
      if ((DOWNLOAD_ENABLED == 'false') || (!$stock_values['products_attributes_filename']!= '')) {
        $stock_left = $stock_values['products_quantity'] - $order->products[$i]['qty'];

        tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
        if ($stock_left < 1) {
          tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
        }
      }
    }

    tep_db_query("insert into " . TABLE_ORDERS_PRODUCTS . " (orders_id, products_id, products_model, products_name, products_price, final_price, products_tax, products_quantity) values ('" . $insert_id . "', '" . tep_get_prid($order->products[$i]['id'])  . "', '" . addslashes($order->products[$i]['model']) . "', '" . addslashes($order->products[$i]['name']) . "', '" . $order->products[$i]['price'] . "', '"  . $order->products[$i]['final_price'] . "', '" . $order->products[$i]['tax'] . "', '" . $order->products[$i]['qty']   . "')");
    $order_products_id = tep_db_insert_id();

//------insert customer choosen option to order--------
    $attributes_exist = '0';
    $products_ordered_attributes = '';
    if ($order->products[$i]['attributes']) {
      $attributes_exist = '1';
      for ($j=0; $j<sizeof($order->products[$i]['attributes']); $j++) {
        if (DOWNLOAD_ENABLED == 'true') {
          $attributes_query = "select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename 
                               from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa 
                               left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                                on pa.products_attributes_id=pad.products_attributes_id
                               where pa.products_id = '" . $order->products[$i]['id'] . "' 
                                and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' 
                                and pa.options_id = popt.products_options_id 
                                and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' 
                                and pa.options_values_id = poval.products_options_values_id 
                                and popt.language_id = '" . $languages_id . "' 
                                and poval.language_id = '" . $languages_id . "'";
          $attributes = tep_db_query($attributes_query);
        } else {
          $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
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
    $total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
    $total_tax += ((($total_products_price * $products[$i]['quantity']) * (($products_tax/100)+1)) - ($total_products_price * $products[$i]['quantity']));
    $total_cost += $total_products_price;

    $products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty']) . $products_ordered_attributes . "\n";
  }

// lets start with the email confirmation
  $email_order = STORE_NAME . "\n" . 
                 EMAIL_SEPARATOR . "\n" . 
                 EMAIL_TEXT_ORDER_NUMBER . ' ' . $insert_id . "\n" . 
                 EMAIL_TEXT_INVOICE_URL . ' ' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false) . "\n" . 
                 EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";
  if ($order->info['comments']) {
    $email_order .= $order->info['comments'] . "\n\n";
  }
  $email_order .= EMAIL_TEXT_PRODUCTS . "\n" . 
                  EMAIL_SEPARATOR . "\n" . 
                  $products_ordered . 
                  EMAIL_SEPARATOR . "\n";
  $email_order .= EMAIL_TEXT_SUBTOTAL . ' ' . $currencies->format($order->info['subtotal']) . "\n";
  $email_order .= EMAIL_TEXT_TAX . ' ' . $currencies->format($order->info['tax']) . "\n";
  if (tep_not_null($order->info['shipping_method'])) {
    $email_order .= EMAIL_TEXT_SHIPPING . ' ' . $currencies->format($order->info['shipping_cost']) . ' ' . TEXT_EMAIL_VIA . ' ' . $order->info['shipping_method'] . "\n";
  }
  $email_order .= EMAIL_TEXT_TOTAL . ' ' . $currencies->format($order->info['total']) . "\n\n";
  $email_order .= EMAIL_TEXT_DELIVERY_ADDRESS . "\n" . 
                  EMAIL_SEPARATOR . "\n" .
                  tep_address_label($customer_id, $sendto, 0, '', "\n") . "\n\n";
  if (is_object($$payment)) {
    $email_order .= EMAIL_TEXT_PAYMENT_METHOD . "\n" . 
                    EMAIL_SEPARATOR . "\n";
    $payment_class = $$payment;
    $email_order .= $payment_class->title . "\n\n";
    if ($payment_class->email_footer) { 
      $email_order .= $payment_class->email_footer . "\n\n";
    }
  }
  tep_mail($order->customer['name'], $order->customer['email_address'], EMAIL_TEXT_SUBJECT, nl2br($email_order), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '');

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
