<?php
/*
  The Exchange Project - Community Made Shopping!

  $Id: whos_online.php,v 1.1 2001/03/15 21:52:25 hpdl Exp $
*/

  function tep_update_whos_online() {
    global $customer_id;

    if (isset($customer_id)) {
      $wo_customer_id = $customer_id;

      $customer_query = tep_db_query("select customers_firstname, customers_lastname from customers where customers_id = '" . $customer_id . "'");
      $customer = tep_db_fetch_array($customer_query);

      $wo_full_name = $customer['customers_firstname'] . ' ' . $customer['customers_lastname'];
    } else {
      $wo_customer_id = '';
      $wo_full_name = 'Guest';
    }

    $wo_session_id = tep_session_id();
    $wo_ip_address = getenv('REMOTE_ADDR');
    $wo_last_page_url = getenv('REQUEST_URI');

    $current_time = time();
    $xx_mins_ago = ($current_time - 900);

// remove entries that have expired
    tep_db_query("delete from whos_online where time_last_click < '" . $xx_mins_ago . "'");

    $stored_customer_query = tep_db_query("select count(*) as count from whos_online where session_id = '" . $wo_session_id . "'");
    $stored_customer = tep_db_fetch_array($stored_customer_query);

    if ($stored_customer['count'] > 0) {
      tep_db_query("update whos_online set customer_id = '" . $wo_customer_id . "', full_name = '" . $wo_full_name . "', ip_address = '" . $wo_ip_address . "', time_last_click = '" . $current_time . "', last_page_url = '" . $wo_last_page_url . "' where session_id = '" . $wo_session_id . "'");
    } else {
      tep_db_query("insert into whos_online (customer_id, full_name, session_id, ip_address, time_entry, time_last_click, last_page_url) values ('" . $wo_customer_id . "', '" . $wo_full_name . "', '" . $wo_session_id . "', '" . $wo_ip_address . "', '" . $current_time . "', '" . $current_time . "', '" . $wo_last_page_url . "')");
    }
  }
?>