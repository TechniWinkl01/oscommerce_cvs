<?php
/*
  $Id: upgrade_3.php,v 1.15 2002/04/03 23:23:18 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<p><span class="pageHeading">osCommerce</span><br><font color="#9a9a9a">Open Source E-Commerce Solutions</font></p>

<p class="pageTitle">Upgrade</p>

<p><b>Step 1: Database Upgrade</b></p>

<?php
  $db = array();
  $db['DB_SERVER'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER']));
  $db['DB_SERVER_USERNAME'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER_USERNAME']));
  $db['DB_SERVER_PASSWORD'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER_PASSWORD']));
  $db['DB_DATABASE'] = trim(stripslashes($HTTP_POST_VARS['DB_DATABASE']));

  osc_db_connect($db['DB_SERVER'], $db['DB_SERVER_USERNAME'], $db['DB_SERVER_PASSWORD']);
  osc_db_select_db($db['DB_DATABASE']);

  function osc_get_languages() {
    $languages_query = osc_db_query("select languages_id, name, code, image, directory from languages order by sort_order");
    while ($languages = osc_db_fetch_array($languages_query)) {
      $languages_array[] = array('id' => $languages['languages_id'],
                                 'name' => $languages['name'],
                                 'code' => $languages['code'],
                                 'image' => $languages['image'],
                                 'directory' => $languages['directory']
                                );
    }

    return $languages_array;
  }

  set_time_limit(0);

  $languages = osc_get_languages();

// send data to the browser, so the flushing works with IE
  for ($i=0; $i<300; $i++) print(' ');
  print ("\n");
?>

<p><span id="addressBook"><span id="addressBookMarker">-</span> Address Book</span><br>
<span id="banners"><span id="bannersMarker">-</span> Banners</span><br>
<span id="categories"><span id="categoriesMarker">-</span> Categories</span><br>
<span id="configuration"><span id="configurationMarker">-</span> Configuration</span><br>
<span id="currencies"><span id="currenciesMarker">-</span> Currencies</span><br>
<span id="customers"><span id="customersMarker">-</span> Customers</span><br>
<span id="images"><span id="imagesMarker">-</span> Images</span><br>
<span id="languages"><span id="languagesMarker">-</span> Languages</span><br>
<span id="manufacturers"><span id="manufacturersMarker">-</span> Manufacturers</span><br>
<span id="orders"><span id="ordersMarker">-</span> Orders</span><br>
<span id="products"><span id="productsMarker">-</span> Products</span><br>
<span id="reviews"><span id="reviewsMarker">-</span> Reviews</span><br>
<span id="sessions"><span id="sessionsMarker">-</span> Sessions</span><br>
<span id="specials"><span id="specialsMarker">-</span> Specials</span><br>
<span id="taxes"><span id="taxesMarker">-</span> Taxes</span><br>
<span id="whosOnline"><span id="whosOnlineMarker">-</span> Whos Online</span></p>

<p>Status: <span id="statusText">Preparing</span></p>

<?php flush(); ?>

<script language="javascript"><!--
changeStyle('addressBook', 'bold');
changeText('addressBookMarker', '?');
changeText('statusText', 'Updating Address Book');
//--></script>

<?php
  flush();

  osc_db_query("alter table address_book change address_book_id address_book_id int(5) not null default '1'");
  osc_db_query("alter table address_book add customers_id int(5) not null default '0' first");
  osc_db_query("alter table address_book add entry_company varchar(32) after entry_gender");
  osc_db_query("alter table address_book drop primary key");

  $customer_query = osc_db_query("select customers_id, customers_gender, customers_firstname, customers_lastname, customers_street_address, customers_suburb, customers_postcode, customers_city, customers_state, customers_country_id, customers_zone_id from customers");
  while ($customer = osc_db_fetch_array($customer_query)) {
    osc_db_query("insert into address_book (customers_id, address_book_id, entry_gender, entry_company, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_country_id, entry_zone_id) values ('" . $customer['customers_id'] . "', '1', '" . $customer['customers_gender'] . "', '', '" . addslashes($customer['customers_firstname']) . "', '" . addslashes($customer['customers_lastname']) . "', '" . addslashes($customer['customers_street_address']) . "', '" . addslashes($customer['customers_suburb']) . "', '" . addslashes($customer['customers_postcode']) . "', '" . addslashes($customer['customers_city']) . "', '" . addslashes($customer['customers_state']) . "', '" . $customer['customers_country_id'] . "', '" . $customer['customers_zone_id'] . "')");
  }

  $entries_query = osc_db_query("select address_book_id, customers_id from address_book_to_customers order by customers_id, address_book_id DESC");
  $ab_id = '1'; // set new address_book_id
  $c_id = '-1'; // when customer_id does not equal $c_id, reset $ab_id
  while ($entries = osc_db_fetch_array($entries_query)) {
    if ($entries['customers_id'] != $c_id) {
      $ab_id = '1';
      $c_id = $entries['customers_id'];
    }
    $ab_id++;

    $ab_query = osc_db_query("select entry_gender, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_country_id, entry_zone_id from address_book where address_book_id = '" . $entries['address_book_id'] . "'");
    $ab = osc_db_fetch_array($ab_query);

    osc_db_query("delete from address_book where address_book_id = '" . $entries['address_book_id'] . "' and customers_id = ''");
    osc_db_query("insert into address_book (customers_id, address_book_id, entry_gender, entry_company, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_country_id, entry_zone_id) values ('" . $c_id . "', '" . $ab_id . "', '" . $ab['entry_gender'] . "', '', '" . addslashes($ab['entry_firstname']) . "', '" . addslashes($ab['entry_lastname']) . "', '" . addslashes($ab['entry_street_address']) . "', '" . addslashes($ab['entry_suburb']) . "', '" . addslashes($ab['entry_postcode']) . "', '" . addslashes($ab['entry_city']) . "', '" . addslashes($ab['entry_state']) . "', '" . $ab['entry_country_id'] . "', '" . $ab['entry_zone_id'] . "')");
  }

  osc_db_query("alter table address_book add primary key (address_book_id, customers_id)");
  osc_db_query("drop table address_book_to_customers");
?>
<script language="javascript"><!--
changeStyle('addressBook', 'normal');
changeText('addressBookMarker', '*');
changeText('statusText', 'Updating Address Book .. done!');

changeStyle('banners', 'bold');
changeText('bannersMarker', '?');
changeText('statusText', 'Updating Banners');
//--></script>

<?php
  flush();

  osc_db_query("create table banners ( banners_id int(5) not null auto_increment, banners_title varchar(64) not null, banners_url varchar(64) not null, banners_image varchar(64) not null, banners_group varchar(10) not null, banners_html_text text, expires_impressions int(7) default '0', expires_date datetime default null, date_scheduled datetime default null, date_added datetime not null, date_status_change datetime default null, status int(1) default '1', primary key (banners_id) )");
  osc_db_query("create table banners_history ( banners_history_id int(5) not null auto_increment, banners_id int(5) not null, banners_shown int(5) not null default '0', banners_clicked int(5) not null default '0', banners_history_date datetime not null, primary key (banners_history_id) )");
  osc_db_query("insert into banners values (1, 'osCommerce', 'http://www.oscommerce.com', 'banners/oscommerce.gif', '468x50', '', 0, null, null, now(), null, 1)");

?>
<script language="javascript"><!--
changeStyle('banners', 'normal');
changeText('bannersMarker', '*');
changeText('statusText', 'Updating Banners .. done!');

changeStyle('categories', 'bold');
changeText('categoriesMarker', '?');
changeText('statusText', 'Updating Categories');
//--></script>

<?php
  flush();

  osc_db_query("create table categories_description ( categories_id int(5) default '0' not null, language_id int(5) default '1' not null, categories_name varchar(32) not null, primary key (categories_id, language_id), key idx_categories_name (categories_name) )");

  $categories_query = osc_db_query("select categories_id, categories_name from categories order by categories_id");
  while ($categories = osc_db_fetch_array($categories_query)) {
    for ($i=0; $i<sizeof($languages); $i++) {
      osc_db_query("insert into categories_description (categories_id, language_id, categories_name) values ('" . $categories['categories_id'] . "', '" . $languages[$i]['id'] . "', '" . addslashes($categories['categories_name']) . "')");
    }
  }

  osc_db_query("alter table categories drop index IDX_CATEGORIES_NAME");
  osc_db_query("alter table categories drop categories_name");
  osc_db_query("alter table categories change parent_id parent_id int(5) not null default '0'");
  osc_db_query("alter table categories add date_added datetime after sort_order");
  osc_db_query("alter table categories add last_modified datetime after date_added");
  osc_db_query("alter table categories add status int(1) default '1' after date_added");
  osc_db_query("alter table categories add index idx_categories_parent_id (parent_id)");
?>
<script language="javascript"><!--
changeStyle('categories', 'normal');
changeText('categoriesMarker', '*');
changeText('statusText', 'Updating Categories .. done!');

changeStyle('configuration', 'bold');
changeText('configurationMarker', '?');
changeText('statusText', 'Updating Configuration');
//--></script>

<?php
  flush();

  osc_db_query("alter table configuration change last_modified last_modified datetime");
  osc_db_query("alter table configuration change date_added date_added datetime not null");
  osc_db_query("alter table configuration modify use_function varchar(255)");
  osc_db_query("alter table configuration add set_function varchar(255) after use_function");

  osc_db_query("update configuration set set_function = 'tep_cfg_pull_down_country_list(' where configuration_key = 'STORE_COUNTRY'");
  osc_db_query("update configuration set configuration_value = 'desc', configuration_description = 'This is the sort order used in the expected products box.', set_function = 'tep_cfg_select_option(array(\'asc\', \'desc\'), ' where configuration_key = 'EXPECTED_PRODUCTS_SORT'");
  osc_db_query("update configuration set configuration_value = 'date_expected', configuration_description = 'The column to sort by in the expected products box.', set_function = 'tep_cfg_select_option(array(\'products_name\', \'date_expected\'), ' where configuration_key = 'EXPECTED_PRODUCTS_FIELD'");

  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Zone', 'STORE_ZONE', '88', 'The zone my store is located in', '1', '7', 'tep_get_zone_name', 'tep_cfg_pull_down_zone_list(', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Switch To Default Currency', 'USE_DEFAULT_LANGUAGE_CURRENCY', 'false', 'Automatically switch to the language\'s currency when it is changed', '1', '10', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Send Extra Order E-Mails To', 'SEND_EXTRA_ORDER_EMAILS_TO', '', 'Send extra order e-mails to the following e-mail addresses, in this format: Name 1 &lt;email@address1&gt;, Name 2 &lt;email@address2&gt;', '1', '11', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use Search-Engine Safe URLs', 'SEARCH_ENGINE_FRIENDLY_URLS', 'false', 'Use search-engine safe urls for all site links', '1', '12', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Cart After Adding Product', 'DISPLAY_CART', 'true', 'Display the shopping cart after adding a product (or return back to their origin)', '1', '14', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Allow Guest To Tell A Friend', 'ALLOW_GUEST_TO_TELL_A_FRIEND', 'false', 'Allow guests to tell a friend about a product', '1', '15', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Default Search Operator', 'ADVANCED_SEARCH_DEFAULT_OPERATOR', 'and', 'Default search operators', '1', '17', 'tep_cfg_select_option(array(\'and\', \'or\'), ', now())");

  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Company', 'ENTRY_COMPANY_LENGTH', '2', 'Minimum length of company name', '2', '6', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Best Sellers', 'MIN_DISPLAY_BESTSELLERS', '1', 'Minimum number of best sellers to display', '2', '15', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Also Purchased', 'MIN_DISPLAY_ALSO_PURCHASED', '1', 'Minimum number of products to display in the \'This Customer Also Purchased\' box', '2', '16', now())");

  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Manufacturers Select Size', 'MAX_MANUFACTURERS_LIST', '1', 'Used in manufacturers box; when this value is \'1\' the classic drop-down list will be used for the manufacturers box. Otherwise, a list-box with the specified number of rows will be displayed.', '3', '7', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('New Products Listing', 'MAX_DISPLAY_PRODUCTS_NEW', '10', 'Maximum number of new products to display in new products page', '3', '14', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Best Sellers', 'MAX_DISPLAY_BESTSELLERS', '10', 'Maximum number of best sellers to display', '3', '15', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Also Purchased', 'MAX_DISPLAY_ALSO_PURCHASED', '5', 'Maximum number of products to display in the \'This Customer Also Purchased\' box', '3', '16', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Customer Order History Box', 'MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX', '6', 'Maximum number of products to display in the customer order history box', '3', '17', now())");

  osc_db_query("delete from configuration where configuration_group_id = '5'");
  osc_db_query("delete from configuration where configuration_group_id = '6'");

  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_PAYMENT_INSTALLED', 'cc.php;cod.php', 'List of payment module filenames separated by a semi-colon. This is automatically updated. No need to edit. (Example: cc.php;cod.php;paypal.php)', '6', '0', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_SHIPPING_INSTALLED', '', 'List of shipping module filenames separated by a semi-colon. This is automatically updated. No need to edit. (Example: ups.php;flat.php;item.php)', '6', '0', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_ORDER_TOTAL_INSTALLED', 'ot_subtotal.php;ot_tax.php;ot_shipping.php;ot_total.php', 'List of order_total module filenames separated by a semi-colon. This is automatically updated. No need to edit. (Example: ot_subtotal.php;ot_tax.php;ot_shipping.php;ot_total.php)', '6', '0', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Allow Cash On Delivery (COD)', 'MODULE_PAYMENT_COD_STATUS', '1', 'Do you want to accept COD (Cash On Delevery) payments?', '6', '0', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Allow Credit Card', 'MODULE_PAYMENT_CC_STATUS', '1', 'Do you want to accept credit card payments?', '6', '0', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Credit Card TP email address', 'MODULE_PAYMENT_CC_EMAIL', 'NONE', 'If this email address is not NONE then the middle digits of any stored cc numbers will be X-ed out and emailed with the order id.', '6', '0', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Default Currency', 'DEFAULT_CURRENCY', 'USD', 'Default Currency', '6', '0', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Default Language', 'DEFAULT_LANGUAGE', 'en', 'Default Language', '6', '0', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Default Order Status For New Orders', 'DEFAULT_ORDERS_STATUS_ID', '1', 'When a new order is created, this order status will be assigned to it.', '6', '0', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display Shipping', 'MODULE_ORDER_TOTAL_SHIPPING_STATUS', 'true', 'Do you want to display the order shipping cost?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER', '3', 'Sort order of display.', '6', '2', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Allow Free Shipping', 'MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING', 'false', 'Do you want to allow free shipping?', '6', '3', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, date_added) VALUES ('Free Shipping For Orders Over', 'MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER', '50', 'Provide free shipping for orders over the set amount.', '6', '4', 'tep_currency_format', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Provide Free Shipping For Orders Made', 'MODULE_ORDER_TOTAL_SHIPPING_DESTINATION', 'national', 'Provide free shipping for orders sent to the set destination.', '6', '5', 'tep_cfg_select_option(array(\'national\', \'international\', \'both\'), ', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display Sub-Total', 'MODULE_ORDER_TOTAL_SUBTOTAL_STATUS', 'true', 'Do you want to display the order sub-total cost?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_ORDER_TOTAL_SUBTOTAL_SORT_ORDER', '1', 'Sort order of display.', '6', '2', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display Tax', 'MODULE_ORDER_TOTAL_TAX_STATUS', 'true', 'Do you want to display the order tax value?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_ORDER_TOTAL_TAX_SORT_ORDER', '2', 'Sort order of display.', '6', '2', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display Total', 'MODULE_ORDER_TOTAL_TOTAL_STATUS', 'true', 'Do you want to display the total order value?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER', '4', 'Sort order of display.', '6', '2', now())");

  osc_db_query("delete from configuration where configuration_group_id = '7' and configuration_key != 'SHIPPING_BOX_WEIGHT' and configuration_key != 'SHIPPING_BOX_PADDING' and configuration_key != 'SHIPPING_HANDLING' and configuration_key != 'SHIPPING_MAX_WEIGHT' and configuration_key != 'STORE_ORIGIN_ZIP' and configuration_key != 'STORE_ORIGIN_COUNTRY'");
  osc_db_query("update configuration set sort_order = '5' where sort_order = '2'");
  osc_db_query("update configuration set configuration_group_id = '7', sort_order = '1' where configuration_key = 'STORE_ORIGIN_ZIP'");
  osc_db_query("update configuration set configuration_group_id = '7', sort_order = '2' where configuration_key = 'STORE_ORIGIN_COUNTRY'");

  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Check stock level', 'STOCK_CHECK', '1', 'Check to see if sufficent stock is available', '9', '1', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Subtract stock', 'STOCK_LIMITED', '1', 'Subtract product in stock by product orders', '9', '2', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Allow Checkout', 'STOCK_ALLOW_CHECKOUT', '1', 'Allow customer to checkout even if there is insufficient stock', '9', '3', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Mark product out of stock', 'STOCK_MARK_PRODUCT_OUT_OF_STOCK', '***', 'Display something on screen so customer can see which product has insufficient stock', '9', '4', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Stock Re-order level', 'STOCK_REORDER_LEVEL', '5', 'Define when stock needs to be re-ordered', '9', '5', now())");

  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Store Page Parse Time', 'STORE_PAGE_PARSE_TIME', 'false', 'Store the time it takes to parse a page', '10', '1', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Log Destination', 'STORE_PAGE_PARSE_TIME_LOG', '/var/log/www/tep/page_parse_time.log', 'Directory and filename of the page parse time log', '10', '2', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Log Date Format', 'STORE_PARSE_DATE_TIME_FORMAT', '%d/%m/%Y %H:%M:%S', 'The date format', '10', '3', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display The Page Parse Time', 'DISPLAY_PAGE_PARSE_TIME', 'true', 'Display the page parse time (store page parse time must be enabled)', '10', '4', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Store Database Queries', 'STORE_DB_TRANSACTIONS', 'false', 'Store the database queries in the page parse time log (PHP4 only)', '10', '5', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");

  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use Cache', 'USE_CACHE', 'false', 'Use caching features', '11', '1', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Cache Directory', 'DIR_FS_CACHE', '/tmp/', 'The directory where the cached files are saved', '11', '2', now())");

  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('E-Mail Transport Method', 'EMAIL_TRANSPORT', 'sendmail', 'Defines if this server uses a local connection to sendmail or uses an SMTP connection via TCP/IP. Servers running ong Windows or MacOS should change this setting to SMTP.', '12', '1', 'tep_cfg_select_option(array(\'sendmail\', \'smtp\'), ', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('E-Mail Linefeeds', 'EMAIL_LINEFEED', 'LF', 'Defines the character sequence used to separate mail headers. When using sendmail use LF, when using smtp use CRLF.', '12', '2', 'tep_cfg_select_option(array(\'LF\', \'CRLF\'), ', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use MIME HTML When Sending E-Mails', 'EMAIL_USE_HTML', 'false', 'Send e-mails in HTML format', '12', '3', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Verfiy E-Mail Addresses Through DNS', 'ENTRY_EMAIL_ADDRESS_CHECK', 'false', 'Verfiy e-mail address through a DNS server', '12', '4', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Send E-Mails', 'SEND_EMAILS', 'true', 'Send out e-mails', '12', '5', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");

  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('enable download', 'download_enabled', 'false', 'enable the products download functions.', '13', '1', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('download by redirect', 'download_by_redirect', 'false', 'use browser redirection for download. disable on non-unix systems.', '13', '2', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('expiry delay (days)' ,'download_max_days', '7', 'set number of days before the download link expires. 0 means no limit.', '13', '3', '', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('maximum number of downloads' ,'download_max_count', '5', 'set the maximum number of downloads. 0 means no download authorized.', '13', '4', '', now())");

  osc_db_query("delete from configuration_group");

  osc_db_query("alter table configuration_group add visible int(1) default '1'");

  osc_db_query("insert into configuration_group values ('1', 'My Store', 'General information about my store', '1', '1')");
  osc_db_query("insert into configuration_group values ('2', 'Minimum Values', 'The minimum values for functions / data', '2', '1')");
  osc_db_query("insert into configuration_group values ('3', 'Maximum Values', 'The maximum values for functions / data', '3', '1')");
  osc_db_query("insert into configuration_group values ('4', 'Images', 'Image parameters', '4', '1')");
  osc_db_query("insert into configuration_group values ('6', 'Module Options', 'Hidden from configuration', '6', '0')");
  osc_db_query("insert into configuration_group values ('7', 'Shipping/Packaging', 'Shipping options available at my store', '7', '1')");
  osc_db_query("insert into configuration_group values ('8', 'Product Listing', 'Product Listing    configuration options', '8', '1')");
  osc_db_query("insert into configuration_group values ('9', 'Stock', 'Stock configuration options', '9', '1')");
  osc_db_query("insert into configuration_group values ('10', 'Logging', 'Logging configuration options', '10', '1')");
  osc_db_query("insert into configuration_group values ('11', 'Cache', 'Caching configuration options', '11', '1')");
  osc_db_query("insert into configuration_group values ('12', 'E-Mail Options', 'General setting for E-Mail transport and HTML E-Mails', '12', '1')");
  osc_db_query("insert into configuration_group values ('13', 'Download', 'Downloadable products options', '13', '1')");

  osc_db_query("insert into products_attributes values (26, 22, 5, 10, '0.00', '+')");
  osc_db_query("insert into products_attributes values (27, 22, 5, 13, '0.00', '+')");

  osc_db_query("insert into products_attributes_download values (26, 'unreal.zip', 7, 3)");
  osc_db_query("insert into products_options values (5, 1, 'Version')");
  osc_db_query("insert into products_options values (5, 2, 'Version')");
  osc_db_query("insert into products_options values (5, 3, 'Versión')");

  osc_db_query("insert into products_options_values values (10, 1, 'Download: Windows - English')");
  osc_db_query("insert into products_options_values values (10, 2, 'Download: Windows - Englisch')");
  osc_db_query("insert into products_options_values values (10, 3, 'Download: Windows - Inglese')");
  osc_db_query("insert into products_options_values values (13, 1, 'Box: Windows - English')");
  osc_db_query("insert into products_options_values values (13, 2, 'Box: Windows - Englisch')");
  osc_db_query("insert into products_options_values values (13, 3, 'Box: Windows - Inglese')");

  osc_db_query("insert into products_options_values_to_products_options values (10, 5, 10)");
  osc_db_query("insert into products_options_values_to_products_options values (13, 5, 13)");
?>

<script language="javascript"><!--
changeStyle('configuration', 'normal');
changeText('configurationMarker', '*');
changeText('statusText', 'Updating Configuration .. done!');

changeStyle('currencies', 'bold');
changeText('currenciesMarker', '?');
changeText('statusText', 'Updating Currencies');
//--></script>

<?php
  flush();

  osc_db_query("alter table currencies add value float(13,8)");
  osc_db_query("alter table currencies add last_updated datetime");

  osc_db_query("update currencies set value = '1'");
?>

<script language="javascript"><!--
changeStyle('currencies', 'normal');
changeText('currenciesMarker', '*');
changeText('statusText', 'Updating Currencies .. done!');

changeStyle('customers', 'bold');
changeText('customersMarker', '?');
changeText('statusText', 'Updating Customers');
//--></script>

<?php
  flush();

  osc_db_query("alter table customers drop customers_street_address");
  osc_db_query("alter table customers drop customers_suburb");
  osc_db_query("alter table customers drop customers_postcode");
  osc_db_query("alter table customers drop customers_city");
  osc_db_query("alter table customers drop customers_state");
  osc_db_query("alter table customers drop customers_zone_id");
  osc_db_query("alter table customers drop customers_country_id");
  osc_db_query("alter table customers change customers_dob customers_dob datetime not null default '0000-00-00 00:00:00'");
  osc_db_query("alter table customers add customers_newsletter char(1)");
  osc_db_query("alter table customers add customers_default_address_id int(5) not null default '1' after customers_email_address");

  osc_db_query("alter table customers_basket change products_id products_id tinytext not null");
  osc_db_query("alter table customers_basket change customers_basket_date_added customers_basket_date_added varchar(8)");

  osc_db_query("alter table customers_basket_attributes change products_id products_id tinytext not null");

  osc_db_query("alter table customers_info change customers_info_date_account_created customers_info_date_account_created datetime");
  osc_db_query("alter table customers_info change customers_info_date_of_last_logon customers_info_date_of_last_logon datetime");
  osc_db_query("alter table customers_info change customers_info_date_account_last_modified customers_info_date_account_last_modified datetime");
  osc_db_query("alter table customers_info add global_product_notifications int(1) default '0'");

  osc_db_query("create table newsletters ( newsletters_id int(5) not null auto_increment, title varchar(255) not null, content text not null, module varchar(255) not null, date_added datetime not null, date_sent datetime, status int(1), locked int(1) default '0', primary key (newsletters_id))");
?>

<script language="javascript"><!--
changeStyle('customers', 'normal');
changeText('customersMarker', '*');
changeText('statusText', 'Updating Customers .. done!');

changeStyle('images', 'bold');
changeText('imagesMarker', '?');
changeText('statusText', 'Updating Images');
//--></script>

<?php
  flush();

// categories
  $categories_query = osc_db_query("select categories_id, categories_image from categories where left(categories_image, 7) = 'images/'");
  while ($categories = osc_db_fetch_array($categories_query)) {
    osc_db_query("update categories set categories_image = substring('" . $categories['categories_image'] . "', 8) where categories_id = '" . $categories['categories_id'] . "'");
  }

// manufacturers
  $manufacturers_query = osc_db_query("select manufacturers_id, manufacturers_image from manufacturers where left(manufacturers_image, 7) = 'images/'");
  while ($manufacturers = osc_db_fetch_array($manufacturers_query)) {
    osc_db_query("update manufacturers set manufacturers_image = substring('" . $manufacturers['manufacturers_image'] . "', 8) where manufacturers_id = '" . $manufacturers['manufacturers_id'] . "'");
  }

// products
  $products_query = osc_db_query("select products_id, products_image from products where left(products_image, 7) = 'images/'");
  while ($products = osc_db_fetch_array($products_query)) {
    osc_db_query("update products set products_image = substring('" . $products['products_image'] . "', 8) where products_id = '" . $products['products_id'] . "'");
  }
?>

<script language="javascript"><!--
changeStyle('images', 'normal');
changeText('imagesMarker', '*');
changeText('statusText', 'Updating Images .. done!');

changeStyle('languages', 'bold');
changeText('languagesMarker', '?');
changeText('statusText', 'Updating Languages');
//--></script>

<?php
  flush();

  osc_db_query("update languages set image = 'icon.gif'");
?>

<script language="javascript"><!--
changeStyle('languages', 'normal');
changeText('languagesMarker', '*');
changeText('statusText', 'Updating Languages .. done!');

changeStyle('manufacturers', 'bold');
changeText('manufacturersMarker', '?');
changeText('statusText', 'Updating Manufacturers');
//--></script>

<?php
  flush();

  osc_db_query("alter table manufacturers add date_added datetime null after manufacturers_image, add last_modified datetime null after date_added");
  osc_db_query("create table manufacturers_info (manufacturers_id int(5) not null, languages_id int(5) not null, manufacturers_url varchar(255) not null, url_clicked int(5) not null default '0', date_last_click datetime, primary key (manufacturers_id, languages_id))");
?>

<script language="javascript"><!--
changeStyle('manufacturers', 'normal');
changeText('manufacturersMarker', '*');
changeText('statusText', 'Updating Manufacturers .. done!');

changeStyle('orders', 'bold');
changeText('ordersMarker', '?');
changeText('statusText', 'Updating Orders');
//--></script>

<?php
  flush();

  osc_db_query("alter table orders change date_purchased date_purchased datetime");
  osc_db_query("alter table orders change last_modified last_modified datetime");
  osc_db_query("alter table orders change orders_date_finished orders_date_finished datetime");
  osc_db_query("alter table orders_products add column products_model varchar(12)");

  osc_db_query("create table orders_status ( orders_status_id int(5) default '0' not null, language_id int(5) default '1' not null, orders_status_name varchar(32) not null, primary key (orders_status_id, language_id), key idx_orders_status_name (orders_status_name))");

  for ($i=0; $i<sizeof($languages); $i++) {
    osc_db_query("insert into orders_status values ('1', '" . $languages[$i]['id'] . "', 'Pending')");
    osc_db_query("insert into orders_status values ('2', '" . $languages[$i]['id'] . "', 'Processing')");
    osc_db_query("insert into orders_status values ('3', '" . $languages[$i]['id'] . "', 'Delivered')");
  }

  osc_db_query("update orders set orders_status = '1' where orders_status = 'Pending'");
  osc_db_query("update orders set orders_status = '2' where orders_status = 'Processing'");
  osc_db_query("update orders set orders_status = '3' where orders_status = 'Delivered'");

  $status = array();
  $orders_status_query = osc_db_query("select distinct orders_status from orders where orders_status not in ('1', '2', '3')");
  while ($orders_status = osc_db_fetch_array($orders_status_query)) {
    $status[] = array('text' => $orders_status['orders_status']);
  }

  $orders_status_id = 4;
  for ($i=0; $i<sizeof($status); $i++) {
    for ($j=0; $j<sizeof($languages); $j++) {
      osc_db_query("insert into orders_status values ('" . $orders_status_id . "', '" . $languages[$j]['id'] . "', '" . $status[$i]['text'] . "')");
    }
    osc_db_query("update orders set orders_status = '" . $orders_status_id . "' where orders_status = '" . $status[$i]['text'] . "'");
    $orders_status_id++;
  }

  osc_db_query("alter table orders change orders_status orders_status int(5) not null");

  osc_db_query("create table orders_status_history ( orders_status_history_id int(5) not null auto_increment, orders_id int(5) not null, new_value int(5) not null, old_value int(5), date_added datetime not null, customer_notified int(1) default '0', primary key (orders_status_history_id))");

  $orders_products_query = osc_db_query("select op.orders_products_id, opa.orders_products_attributes_id, op.products_id from orders_products op, orders_products_attributes opa where op.orders_id = opa.orders_id");
  while ($orders_products = osc_db_fetch_array($orders_products_query)) {
    osc_db_query("update orders_products_attributes set orders_products_id = '" . $orders_products['orders_products_id'] . "' where orders_products_attributes_id = '" . $orders_products['orders_products_attributes_id'] . "' and orders_products_id = '" . $orders_products['products_id'] . "'");
  }

  osc_db_query("create table orders_products_download ( orders_products_download_id int(5) not null auto_increment, orders_id int(5) not null default '0', orders_products_id int(5) not null default '0', orders_products_filename varchar(255) not null, download_maxdays int(2) not null default '0', download_count int(2) not null default '0', primary key (orders_products_download_id))");
?>

<script language="javascript"><!--
changeStyle('orders', 'normal');
changeText('ordersMarker', '*');
changeText('statusText', 'Updating Orders .. done!');

changeStyle('products', 'bold');
changeText('productsMarker', '?');
changeText('statusText', 'Updating Products');
//--></script>

<?php
  flush();

  osc_db_query("create table products_description ( products_id int(5) not null auto_increment, language_id int(5) not null default '1', products_name varchar(64) not null default '',  products_description text, products_url varchar(255), products_viewed int(5) default '0', primary key (products_id, language_id), key products_name (products_name))");

  $products_query = osc_db_query("select products_id, products_name, products_description, products_url, products_viewed from products order by products_id");
  while ($products = osc_db_fetch_array($products_query)) {
    for ($i=0; $i<sizeof($languages); $i++) {
      osc_db_query("insert into products_description (products_id, language_id, products_name, products_description, products_url, products_viewed) values ('" . $products['products_id'] . "', '" . $languages[$i]['id'] . "', '" . addslashes($products['products_name']) . "', '" . addslashes($products['products_description']) . "', '" . addslashes($products['products_url']) . "', '" . $products['products_viewed'] . "')");
    }
  }

  osc_db_query("alter table products change products_date_added products_date_added datetime");

  osc_db_query("alter table products drop index products_name");

  osc_db_query("alter table products drop products_url");
  osc_db_query("alter table products drop products_name");
  osc_db_query("alter table products drop products_description");
  osc_db_query("alter table products drop products_viewed");

  osc_db_query("alter table products add products_date_available datetime");
  osc_db_query("alter table products add products_last_modified datetime");

  osc_db_query("drop table products_expected");

  osc_db_query("alter table products_options change products_options_id products_options_id int(5) not null default '0'");
  osc_db_query("alter table products_options add language_id int(5) not null default '1' after products_options_id");
  osc_db_query("alter table products_options drop primary key");
  osc_db_query("alter table products_options add primary key (products_options_id, language_id)");

  $products_query = osc_db_query("select products_options_id, language_id, products_options_name from products_options order by products_options_id");
  while ($products = osc_db_fetch_array($products_query)) {
    for ($i=0; $i<sizeof($languages); $i++) {
      osc_db_query("replace into products_options (products_options_id, language_id, products_options_name) values ('" . $products['products_options_id'] . "', '" . $languages[$i]['id'] . "', '" . addslashes($products['products_options_name']) . "')");
    }
  }

  osc_db_query("alter table products_options_values change products_options_values_id products_options_values_id int(5) not null default '0'");
  osc_db_query("alter table products_options_values add language_id int(5) not null default '1' after products_options_values_id");
  osc_db_query("alter table products_options_values drop primary key");
  osc_db_query("alter table products_options_values add primary key (products_options_values_id, language_id)");

  $products_query = osc_db_query("select products_options_values_id, language_id, products_options_values_name from products_options_values order by products_options_values_id");
  while ($products = osc_db_fetch_array($products_query)) {
    for ($i=0; $i<sizeof($languages); $i++) {
      osc_db_query("replace into products_options_values (products_options_values_id, language_id, products_options_values_name) values ('" . $products['products_options_values_id'] . "', '" . $languages[$i]['id'] . "', '" . addslashes($products['products_options_values_name']) . "')");
    }
  }

  osc_db_query("alter table products_to_categories change products_id products_id int(5) not null");

  osc_db_query("create table products_attributes_download ( products_attributes_id int(5) not null, products_attributes_filename varchar(255) not null, products_attributes_maxdays int(2) default '0', products_attributes_maxcount int(2) default '0', primary key (products_attributes_id))");

  osc_db_query("create table products_notifications ( products_id int(5) not null, customers_id int(5) not null, date_added datetime not null, primary key (products_id, customers_id))");
?>

<script language="javascript"><!--
changeStyle('products', 'normal');
changeText('productsMarker', '*');
changeText('statusText', 'Updating Products .. done!');

changeStyle('reviews', 'bold');
changeText('reviewsMarker', '?');
changeText('statusText', 'Updating Reviews');
//--></script>

<?php
  flush();

  osc_db_query("create table reviews_description ( reviews_id int(5) not null, languages_id int(5) not null, reviews_text text not null, primary key (reviews_id, languages_id))");

  osc_db_query("alter table reviews add products_id int(5) not null default '0' after reviews_id");
  osc_db_query("alter table reviews add customers_id int(5) after products_id");
  osc_db_query("alter table reviews add customers_name varchar(64) not null default '' after customers_id");
  osc_db_query("alter table reviews add date_added datetime after reviews_rating");
  osc_db_query("alter table reviews add last_modified datetime after date_added");
  osc_db_query("alter table reviews add reviews_read int(5) not null default '0'");

  $reviews_query = osc_db_query("select r.reviews_id, re.products_id, re.customers_id, r.reviews_rating, re.date_added, re.reviews_read, r.reviews_text from reviews r, reviews_extra re where r.reviews_id = re.reviews_id order by r.reviews_id");
  while ($reviews = osc_db_fetch_array($reviews_query)) {
    $customer_query = osc_db_query("select customers_firstname, customers_lastname from customers where customers_id = '" . $reviews['customers_id'] . "'");
    if (osc_db_num_rows($customer_query)) {
      $customer = osc_db_fetch_array($customer_query);
      $customers_name = $customer['customers_firstname'] . ' ' . $customer['customers_lastname'];
    } else {
      $customers_name = '';
    }

    osc_db_query("update reviews set products_id = '" . $reviews['products_id'] . "', customers_id = '" . $reviews['customers_id'] . "', customers_name = '" . addslashes($customers_name) . "', date_added = '" . $reviews['date_added'] . "', last_modified = '', reviews_read = '" . $reviews['reviews_read'] . "' where reviews_id = '" . $reviews['reviews_id'] . "'");
    osc_db_query("insert into reviews_description (reviews_id, languages_id, reviews_text) values ('" . $reviews['reviews_id'] . "', '" . $languages[0]['id'] . "', '" . addslashes($reviews['reviews_text']) . "')");
  }

  osc_db_query("alter table reviews drop reviews_text");

  osc_db_query("drop table reviews_extra");
?>

<script language="javascript"><!--
changeStyle('reviews', 'normal');
changeText('reviewsMarker', '*');
changeText('statusText', 'Updating Reviews .. done!');

changeStyle('sessions', 'bold');
changeText('sessionsMarker', '?');
changeText('statusText', 'Updating Sessions');
//--></script>

<?php
  flush();

  osc_db_query("create table sessions (sesskey varchar(32) not null, expiry int(11) unsigned not null, value text not null, primary key (sesskey))");
?>

<script language="javascript"><!--
changeStyle('sessions', 'normal');
changeText('sessionsMarker', '*');
changeText('statusText', 'Updating Sessions .. done!');

changeStyle('specials', 'bold');
changeText('specialsMarker', '?');
changeText('statusText', 'Updating Specials');
//--></script>

<?php
  flush();

  osc_db_query("alter table specials change specials_date_added specials_date_added datetime");

  osc_db_query("alter table specials add specials_last_modified datetime");
  osc_db_query("alter table specials add expires_date datetime");
  osc_db_query("alter table specials add date_status_change datetime");
  osc_db_query("alter table specials add status int(1) default '1'");
?>

<script language="javascript"><!--
changeStyle('specials', 'normal');
changeText('specialsMarker', '*');
changeText('statusText', 'Updating Specials .. done!');

changeStyle('taxes', 'bold');
changeText('taxesMarker', '?');
changeText('statusText', 'Updating Taxes');
//--></script>

<?php
  flush();

  osc_db_query("alter table tax_class change date_added date_added datetime not null");
  osc_db_query("alter table tax_class change last_modified last_modified datetime");

  osc_db_query("alter table tax_rates change date_added date_added datetime not null");
  osc_db_query("alter table tax_rates change last_modified last_modified datetime");

  osc_db_query("alter table tax_rates add tax_priority int(5) default '1' after tax_class_id");

  osc_db_query("create table geo_zones (geo_zone_id int(5) not null auto_increment, geo_zone_name varchar(32) not null, geo_zone_description varchar(255) not null, last_modified datetime, date_added datetime not null, primary key (geo_zone_id))");
  osc_db_query("create table zones_to_geo_zones (association_id int(5) not null auto_increment, zone_country_id int(5) not null, zone_id int(5), geo_zone_id int(5), last_modified datetime, date_added datetime not null, primary key (association_id))");

  osc_db_query("alter table zones change zone_code zone_code varchar(32) not null");

  osc_db_query("INSERT INTO geo_zones (geo_zone_id,geo_zone_name,geo_zone_description,last_modified,date_added) SELECT tr.tax_zone_id,zone_name,zone_name,NULL,now() from tax_rates tr,zones z,countries c WHERE tr.tax_zone_id=z.zone_id AND c.countries_id=z.zone_country_id GROUP BY tr.tax_zone_id");

  osc_db_query("INSERT INTO zones_to_geo_zones (zone_country_id,zone_id,geo_zone_id,date_added) SELECT z.zone_country_id, z.zone_id,tr.tax_zone_id,now() FROM tax_rates tr, zones z WHERE z.zone_id=tr.tax_zone_id GROUP BY tr.tax_zone_id");
?>

<script language="javascript"><!--
changeStyle('taxes', 'normal');
changeText('taxesMarker', '*');
changeText('statusText', 'Updating Taxes .. done!');

changeStyle('whosOnline', 'bold');
changeText('whosOnlineMarker', '?');
changeText('statusText', 'Updating Whos Online');
//--></script>

<?php
  flush();

  osc_db_query("create table whos_online (customer_id int(5),  full_name varchar(64) not null, session_id varchar(128) not null, ip_address varchar(15) not null, time_entry varchar(14) not null, time_last_click varchar(14) not null, last_page_url varchar(64) not null)");
?>

<script language="javascript"><!--
changeStyle('whosOnline', 'normal');
changeText('whosOnlineMarker', '*');
changeText('statusText', 'Updating Whos Online .. done!');

changeStyle('statusText', 'bold');
changeText('statusText', 'Update Complete!');
//--></script>

<?php flush(); ?>

<p>The database upgrade procedure was successful!</p>
