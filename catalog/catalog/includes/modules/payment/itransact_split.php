<?php
/*
  $Id: itransact_split.php,v 1.18 2002/06/23 13:43:01 clescuyer Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

/*
  See README_catalog_itransact_split
  iTransact Payment Module itransact_split.php
  Author: TriciaB (info@barestyle.com)
  File resides in: catalog/includes/modules/payment/
  This version is for TEP Preview Release 2.2 with mysql_catalog.sql version 1.116+.
*/

  class itransact_split {
    var $code, $title, $description, $enabled;

// class constructor
    function itransact_split() {
      $this->code = 'itransact_split';
      $this->title = MODULE_PAYMENT_ITRANSACT_SPLIT_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_ITRANSACT_SPLIT_TEXT_DESCRIPTION;
      $this->enabled = MODULE_PAYMENT_ITRANSACT_SPLIT_STATUS;
    }

// class methods
    function javascript_validation() {
        return false;
      }

    function selection() {
        return false;
    }

    function confirmation() {
     global $checkout_form_action;

        $checkout_form_action = MODULE_PAYMENT_ITRANSACT_SPLIT_CHECKOUT_FORM_ACTION;
    }

    function process_button() {
      global $HTTP_POST_VARS, $total_tax, $shipping_cost, $total_cost, $customer_id, $products, $languages_id;

      $customer_query = tep_db_query("select customers_firstname, customers_lastname, customers_telephone, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
      $customer = tep_db_fetch_array($customer_query);

      $address_book_query = tep_db_query("select entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_city, entry_postcode, entry_state, entry_zone_id, entry_country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $customer_id . "'");
      $address_book = tep_db_fetch_array($address_book_query);
      $customers_country = tep_get_countries($address_book['entry_country_id']);

      $sig_rand_query = tep_db_query("select sig_rand_begun, auth_id, status from orders_itransact_auth where sesskey_begun = '" . tep_session_id() . "' and orders_id is NULL and status = 'begun' order by auth_id DESC limit 1");
      $sig_rand = tep_db_fetch_array($sig_rand_query);
      $sig_rand_begun = $sig_rand['sig_rand_begun'];

      if (!$sig_rand_begun || $status == 'begun') {
        $sig_rand_begun = tep_create_random_value(16, 'digits');
        tep_db_query("insert into orders_itransact_auth (customer_id, sig_rand_begun, gateway_id_begun, total_begun, sesskey_begun, status, datetime_begun) values ('" . $customer_id . "','" . $sig_rand_begun . "', '" . MODULE_PAYMENT_ITRANSACT_SPLIT_GATEWAY_ID . "', '" . (number_format($total_cost + $total_tax + $shipping_cost, 2)) . "', '" . tep_session_id() . "', 'begun', now())");
        $auth_id_query = tep_db_query("select auth_id from orders_itransact_auth where sesskey_begun = '" . tep_session_id() . "' and orders_id is NULL and status = 'begun' order by auth_id DESC limit 1");
        $auth_id = tep_db_fetch_array($auth_id_query);
        $auth_id = $auth_id['auth_id'];
      } else {
        tep_db_query("update orders_itransact_auth set customer_id = '" . $customer_id . "', total_begun = '" . (number_format($total_cost + $total_tax + $shipping_cost, 2)) . "', datetime_begun = now(), status = 'begun', gateway_id_begun = '" . MODULE_PAYMENT_ITRANSACT_SPLIT_GATEWAY_ID . "' where sig_rand_begun = '" . $sig_rand_begun . "'");
        $auth_id = $sig_rand['auth_id'];
      }

// setup passback variables
      $process_button_string = tep_draw_hidden_field(tep_session_name(), tep_session_id()) .
                               tep_draw_hidden_field('passback[]', 'prod') .
                               tep_draw_hidden_field('passback[]', 'vendor_id') .
                               tep_draw_hidden_field('passback[]', tep_session_name()) .
                               tep_draw_hidden_field('passback[]', 'sig_rand') .
                               tep_draw_hidden_field('passback[]', 'auth_id') .
                               tep_draw_hidden_field('sig_rand', $sig_rand_begun) .
                               tep_draw_hidden_field('auth_id', $auth_id);

// setup lookups
      $process_button_string .= tep_draw_hidden_field('lookup[]', 'xid') .
                                tep_draw_hidden_field('lookup[]', 'authcode') .
                                tep_draw_hidden_field('lookup[]', 'avs_response') .
                                tep_draw_hidden_field('lookup[]', 'when') .
                                tep_draw_hidden_field('lookup[]', 'total') .
                                tep_draw_hidden_field('lookup[]', 'cc_last_four') .
                                tep_draw_hidden_field('lookup[]', 'test_mode');

// setup tep variables for split form layout
      $process_button_string .= tep_draw_hidden_field('header_title', TITLE) .
                                tep_draw_hidden_field('header_title_my_account', HEADER_TITLE_MY_ACCOUNT) .
                                tep_draw_hidden_field('header_title_cart_contents', HEADER_TITLE_CART_CONTENTS) .
                                tep_draw_hidden_field('header_title_checkout', HEADER_TITLE_CHECKOUT) .
                                tep_draw_hidden_field('header_title_top', HEADER_TITLE_TOP) .
                                tep_draw_hidden_field('header_title_catalog', HEADER_TITLE_CATALOG) .
                                tep_draw_hidden_field('header_title_login', HEADER_TITLE_LOGIN) .
                                tep_draw_hidden_field('header_accept_cards', MODULE_PAYMENT_ITRANSACT_SPLIT_CARDS) .
                                tep_draw_hidden_field('header_accept_eft', MODULE_PAYMENT_ITRANSACT_SPLIT_EFT);

/*
  Format description, cost, and quantity for each item.  These are used in the email
  sent by iTransact, and are required to determine the transaction total.
  This uses global $products.
*/
      for ($i=0; $i<sizeof($products); $i++) {
        $item_num = $i;
        $item_num++;
        $products_name = $products[$i]['name'];
        $products_price = $products[$i]['price'];
        $products_quantity = $products[$i]['quantity'];
        $products_options_name = $attributes_values[$i]['products_options_name'];

        $process_button_string .= tep_draw_hidden_field('item_' . $item_num . '_desc', $products_name) .
                                  tep_draw_hidden_field('item_' . $item_num . '_cost', $products_price) .
                                  tep_draw_hidden_field('item_' . $item_num . '_qty', $products_quantity);

// Check for product attributes.  If they exist, format them for each item. as above.
        if ($products[$i]['attributes']) {
          reset($products[$i]['attributes']);
          $num = 0;
          while (list($option, $value) = each($products[$i]['attributes'])) {
            $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $products[$i]['id'] . "' and pa.options_id = '" . $option . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $value . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
            $attributes_values = tep_db_fetch_array($attributes);
	        $attributes_for_itransact['name'][$i . $num] .= $attributes_values['products_options_name'];
	        $attributes_for_itransact['value'][$i . $num] .= $attributes_values['products_options_values_name'];
            $num++;
          }
        }

        if ($attributes_for_itransact) {
          for ($num=0; $num<sizeof($attributes_for_itransact); $num++) {
            $item_num = $i;
            $item_num++;
            $attrib_name = $attributes_for_itransact['name'];
            $attrib_value = $attributes_for_itransact['value'];

            if ($attrib_value[$i . $num]) {
              $process_button_string .= tep_draw_hidden_field('item_' . $item_num . '_' . $attrib_name[$i . $num], $attrib_value[$i . $num]);
            }
          }
        }
      }

      if ($shipping_cost) {
        $process_button_string .= tep_draw_hidden_field('98_desc', 'Shipping') .
                                  tep_draw_hidden_field('98_cost', number_format($shipping_cost,2)) .
                                  tep_draw_hidden_field('98_qty', '1');
      }

      if ($total_tax) {
        $process_button_string .= tep_draw_hidden_field('99_desc', 'Tax') .
                                  tep_draw_hidden_field('99_cost', number_format($total_tax,2)) .
                                  tep_draw_hidden_field('99_qty', '1');
      }

// setup merchant and customer variables
      $process_button_string .= tep_draw_hidden_field('vendor_id', MODULE_PAYMENT_ITRANSACT_SPLIT_GATEWAY_ID) .
                                tep_draw_hidden_field('home_page', HTTP_SERVER) .
                                tep_draw_hidden_field('ret_addr', MODULE_PAYMENT_ITRANSACT_RETURN_ADDRESS);

      if ( (MODULE_PAYMENT_ITRANSACT_RETURN_MODE == 'post') || (MODULE_PAYMENT_ITRANSACT_RETURN_MODE == 'redirect') ) {
        $process_button_string .= tep_draw_hidden_field('ret_mode', MODULE_PAYMENT_ITRANSACT_RETURN_MODE);
      }

// This will be used for future versions.
      if (MODULE_PAYMENT_ITRANSACT_ON_ERROR == '1') {
        $process_button_string .= tep_draw_hidden_field('post_back_on_error', '1');
      }

      $process_button_string .= tep_draw_hidden_field('email_text', $comments) .
                                tep_draw_hidden_field('first_name', $address_book['entry_firstname']) .
                                tep_draw_hidden_field('last_name', $address_book['entry_lastname']) .
                                tep_draw_hidden_field('address', $address_book['entry_street_address']) .
                                tep_draw_hidden_field('city', $address_book['entry_city']) .
                                tep_draw_hidden_field('zip', $address_book['entry_postcode']) .
                                tep_draw_hidden_field('country', $customers_country['countries_name']) .
                                tep_draw_hidden_field('email', $customer['customers_email_address']) .
                                tep_draw_hidden_field('phone', $customer['customers_telephone']);

// Include the state.
      $state = tep_get_zone_code($address_book['entry_country_id'], $address_book['entry_zone_id'], '');
      if (!$state) $state = $address_book['entry_state'];
      $process_button_string .= tep_draw_hidden_field('state', $state);

// Create hidden inputs for card images on Split Form
      $process_button_string .= tep_draw_hidden_field('header_visa_image', MODULE_PAYMENT_ITRANSACT_SPLIT_VISAMC) .
                                tep_draw_hidden_field('header_mc_image', MODULE_PAYMENT_ITRANSACT_SPLIT_VISAMC) .
                                tep_draw_hidden_field('header_amex_image', MODULE_PAYMENT_ITRANSACT_SPLIT_AMEX) .
                                tep_draw_hidden_field('header_disc_image', MODULE_PAYMENT_ITRANSACT_SPLIT_DISC) .
                                tep_draw_hidden_field('header_diner_image', MODULE_PAYMENT_ITRANSACT_SPLIT_DINER);

      return $process_button_string;
    }

/*
  FOR FUTURE USE
  There are three possible responses from iTransact for a transaction.  This function will handle all three.
  However, there's no point right now to handle errors and dies.  These are handled only if iTransact's
  service is being used "transparently" which requires a secure server of your own.  Since this module
  is for the Split Form, a secure server isn't being used on my end, and all errors and dies will be handled
  by iTransact's secure server.
  1.  Success - This will include the authcode, since it is included above as a Lookup variable.
  2.  Error - This is basically a decline.  The error message is reported.
  3.  Die - Something bad happened.  (internal error)
*/
    function before_process() {
      global $payment;

      if ( ($payment == $this->code) && (($HTTP_POST_VARS['die'] == '1') || ($HTTP_POST_VARS['err'])) ) {
        if ($HTTP_POST_VARS['die'] == '1') {
          tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(MODULE_PAYMENT_ITRANSACT_SPLIT_TEXT_DIE_MESSAGE), 'SSL', true, false));
        }
        if ($HTTP_POST_VARS['err']) {
          tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode($HTTP_POST_VARS['err']), 'SSL', true, false));
        }
      }
    }

    function after_process() {
      global $HTTP_POST_VARS, $customer_id, $insert_id;

// Update orders_itransact_auth.  First, check to see if the order is a dup.  This would happen if someone deliberately pressed the back button and reloaded the page, causing another transaction to go through iTransact.
      $find_dup_query = tep_db_query("select sig_rand_complete from orders_itransact_auth where sig_rand_complete = '" . $HTTP_POST_VARS['sig_rand'] . "' and sesskey_complete = '" . tep_session_id() . "' and auth_id = '" . $HTTP_POST_VARS['auth_id'] . "' and status = 'complete'");
      if (tep_db_num_rows($find_dup_query) > 0) {
        tep_db_query("insert into orders_itransact_auth (customer_id, status, orders_id, gateway_id_complete, authcode, datetime_itransact_timestamp, total_complete, cc_last_four, xid, test_mode, avs_response, signature, sig_rand_complete, sesskey_complete) values ('" . $customer_id . "', 'duplicate', '" . $insert_id . "', '" . $HTTP_POST_VARS['vendor_id'] . "', '" . $HTTP_POST_VARS['authcode'] . "', '" . $HTTP_POST_VARS['when'] . "','" . $HTTP_POST_VARS['total'] . "', '" . $HTTP_POST_VARS['cc_last_four'] . "', '" . $HTTP_POST_VARS['xid'] . "', '" . $HTTP_POST_VARS['test_mode'] . "', '" . $HTTP_POST_VARS['avs_response'] . "', '" . $HTTP_POST_VARS['signature'] . "', '" . $HTTP_POST_VARS['sig_rand'] . "', '" . tep_session_id() . "' )");
      } else {
        tep_db_query("update orders_itransact_auth set status = 'complete', orders_id = '" . $insert_id . "', gateway_id_complete = '" . $HTTP_POST_VARS['vendor_id'] . "', authcode = '" . $HTTP_POST_VARS['authcode'] . "', datetime_itransact_timestamp = '" . $HTTP_POST_VARS['when'] . "', total_complete = '" . $HTTP_POST_VARS['total'] . "', cc_last_four = '" . $HTTP_POST_VARS['cc_last_four'] . "', xid = '" . $HTTP_POST_VARS['xid'] . "', test_mode = '" . $HTTP_POST_VARS['test_mode'] . "', avs_response = '" . $HTTP_POST_VARS['avs_response'] . "', signature = '" . $HTTP_POST_VARS['signature'] . "', sesskey_complete = '" . tep_session_id() . "', sig_rand_complete = '" . $HTTP_POST_VARS['sig_rand'] . "' where auth_id = '" . $HTTP_POST_VARS['auth_id'] . "' and sig_rand_begun = '" . $HTTP_POST_VARS['sig_rand'] . "'");
      }
    }

    function pre_confirmation_check() {
      return false;
    }

    function output_error() {
      return false;
    }

    function check() {
      if (!isset($this->check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ITRANSACT_SPLIT_STATUS'");
        $this->check = tep_db_num_rows($check_query);
      }
      return $this->check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enable iTransact module', 'MODULE_PAYMENT_ITRANSACT_SPLIT_STATUS', '1', 'Enter 1 to accept iTransact payments using the secure Split Form?', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('iTransact Gateway ID', 'MODULE_PAYMENT_ITRANSACT_SPLIT_GATEWAY_ID', '', 'Five-digit iTransact Gateway ID', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Credit Cards', 'MODULE_PAYMENT_ITRANSACT_SPLIT_CARDS', '0', 'Enter 1 if you are accepting credit card payments', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Checks/EFT', 'MODULE_PAYMENT_ITRANSACT_SPLIT_EFT', '0', 'Enter 1 if you are accepting checks/EFT payments', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Visa & Mastercard', 'MODULE_PAYMENT_ITRANSACT_SPLIT_VISAMC', '0', 'Enter 1 to display these images', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('American Express', 'MODULE_PAYMENT_ITRANSACT_SPLIT_AMEX', '0', 'Enter 1 to display this image', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Discover', 'MODULE_PAYMENT_ITRANSACT_SPLIT_DISC', '0', 'Enter 1 to display this image', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Diners Club', 'MODULE_PAYMENT_ITRANSACT_SPLIT_DINER', '0', 'Enter 1 to display this image', '6', '0', now())");
//      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Use Split Form?', 'MODULE_PAYMENT_ITRANSACT_SPLIT_ON', '1', 'Enter 1 if using Split Form.  This is used if you do NOT have your own secure server.', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ITRANSACT_SPLIT_STATUS'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ITRANSACT_SPLIT_GATEWAY_ID'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ITRANSACT_SPLIT_CARDS'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ITRANSACT_SPLIT_EFT'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ITRANSACT_SPLIT_VISAMC'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ITRANSACT_SPLIT_AMEX'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ITRANSACT_SPLIT_DISC'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ITRANSACT_SPLIT_DINER'");
//      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ITRANSACT_SPLIT_ON'");
    }

    function keys() {
      $keys = array('MODULE_PAYMENT_ITRANSACT_SPLIT_STATUS', 'MODULE_PAYMENT_ITRANSACT_SPLIT_GATEWAY_ID', 'MODULE_PAYMENT_ITRANSACT_SPLIT_CARDS', 'MODULE_PAYMENT_ITRANSACT_SPLIT_EFT', 'MODULE_PAYMENT_ITRANSACT_SPLIT_VISAMC', 'MODULE_PAYMENT_ITRANSACT_SPLIT_AMEX', 'MODULE_PAYMENT_ITRANSACT_SPLIT_DISC', 'MODULE_PAYMENT_ITRANSACT_SPLIT_DINER');
      return $keys;
    }

  }
?>
