<?php
/*
  $Id: update.php,v 1.30 2002/01/23 16:23:57 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  if (!$HTTP_POST_VARS['DB_SERVER']) {
?>
<html>
<head>
<title>osCommerce 2.2 Database Update Script</title>
<style type=text/css><!--
  TD, P, BODY {
    font-family: Verdana, Arial, sans-serif;
    font-size: 14px;
    color: #000000;
  }
//--></style>
</head>
<body>
<p>
<b>osCommerce 2.2 Database Update Script</b>
<p>
<form name="database" action="<?php echo basename($PHP_SELF); ?>" method="post">
<table border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td colspan="2"><b>Database Server Information</b></td>
  </tr>
  <tr>
    <td>Server:</td>
    <td><input type="text" name="DB_SERVER"> <small>(eg, 192.168.0.1)</small></td>
  </tr>
  <tr>
    <td>Username:</td>
    <td><input type="text" name="DB_SERVER_USERNAME"> <small>(eg, root)</small></td>
  </tr>
  <tr>
    <td>Password:</td>
    <td><input type="text" name="DB_SERVER_PASSWORD"> <small>(eg, bee)</small></td>
  </tr>
  <tr>
    <td>Database:</td>
    <td><input type="text" name="DB_DATABASE"> <small>(eg, catalog)</small></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="Submit"></td>
  </tr>
</table>
<p>
Note: The user must have create and drop privileges!
</form>
</body>
</html>
<?php
    exit;
  }

  function tep_db_connect() {
    global $db_link, $HTTP_POST_VARS;

    $db_link = mysql_connect($HTTP_POST_VARS['DB_SERVER'], $HTTP_POST_VARS['DB_SERVER_USERNAME'], $HTTP_POST_VARS['DB_SERVER_PASSWORD']);

    if ($db_link) mysql_select_db($HTTP_POST_VARS['DB_DATABASE']);

    return $db_link;
  }

  function tep_db_error ($query, $errno, $error) { 
    die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
  }

  function tep_db_query($db_query) {
    global $db_link;

    $result = mysql_query($db_query, $db_link) or tep_db_error($db_query, mysql_errno(), mysql_error());

    return $result;
  }

  function tep_db_fetch_array($db_query) {
    $result = mysql_fetch_array($db_query);

    return $result;
  }

  function tep_db_num_rows($db_query) {
    $result = mysql_num_rows($db_query);

    return $result;
  }

  function tep_db_insert_id() {
    $result = mysql_insert_id();

    return $result;
  }

  function tep_get_languages() {
    $languages_query = tep_db_query("select languages_id, name, code, image, directory from languages order by sort_order");
    while ($languages = tep_db_fetch_array($languages_query)) {
      $languages_array[] = array('id' => $languages['languages_id'],
                                 'name' => $languages['name'],
                                 'code' => $languages['code'],
                                 'image' => $languages['image'],
                                 'directory' => $languages['directory']
                                );
    }

    return $languages_array;
  }

  tep_db_connect() or die('Unable to connect to database server!');

  $languages = tep_get_languages();

// send data to the browser, so the flushing works with IE
  for ($i=0; $i<300; $i++) print(' ');
  print ("\n");
?>
<html>
<head>
<title>osCommerce 2.2 Database Update Script</title>
<style type=text/css><!--
  TD, P, BODY {
    font-family: Verdana, Arial, sans-serif;
    font-size: 14px;
    color: #000000;
  }
//--></style>
<script language="JavaScript"><!--
function changeStyle(what, how) {
  if (document.getElementById) {
    document.getElementById(what).style.fontWeight = how;
  } else if (document.all) {
    document.all[what].style.fontWeight = how;
  }
}

function changeText(where, what) {
  if (document.getElementById) {
    document.getElementById(where).innerHTML = what;
  } else if (document.all) {
    document.all[where].innerHTML = what;
  }
}
//--></script>
</head>
<body>
<p>
<b>osCommerce 2.2 Database Update Script</b>
<p>
<span id="addressBook"><span id="addressBookMarker">-</span> Address Book</span><br>
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
<span id="whosOnline"><span id="whosOnlineMarker">-</span> Whos Online</span><br>
<p>
Status: <span id="statusText">Preparing</span>
</body>
</html>

<?php flush(); ?>

<script language="javascript"><!--
changeStyle('addressBook', 'bold');
changeText('addressBookMarker', '?');
changeText('statusText', 'Updating Address Book');
//--></script>

<?php
  flush();

  tep_db_query("alter table address_book change address_book_id address_book_id int(5) not null default '1'");
  tep_db_query("alter table address_book add customers_id int(5) not null default '0' first");
  tep_db_query("alter table address_book add entry_company varchar(32) after entry_gender");
  tep_db_query("alter table address_book drop primary key");

  $customer_query = tep_db_query("select customers_id, customers_gender, customers_firstname, customers_lastname, customers_street_address, customers_suburb, customers_postcode, customers_city, customers_state, customers_country_id, customers_zone_id from customers");
  while ($customer = tep_db_fetch_array($customer_query)) {
    tep_db_query("insert into address_book (customers_id, address_book_id, entry_gender, entry_company, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_country_id, entry_zone_id) values ('" . $customer['customers_id'] . "', '1', '" . $customer['customers_gender'] . "', '', '" . addslashes($customer['customers_firstname']) . "', '" . addslashes($customer['customers_lastname']) . "', '" . addslashes($customer['customers_street_address']) . "', '" . addslashes($customer['customers_suburb']) . "', '" . addslashes($customer['customers_postcode']) . "', '" . addslashes($customer['customers_city']) . "', '" . addslashes($customer['customers_state']) . "', '" . $customer['customers_country_id'] . "', '" . $customer['customers_zone_id'] . "')");
  }

  $entries_query = tep_db_query("select address_book_id, customers_id from address_book_to_customers order by customers_id, address_book_id DESC");
  $ab_id = '1'; // set new address_book_id
  $c_id = '-1'; // when customer_id does not equal $c_id, reset $ab_id
  while ($entries = tep_db_fetch_array($entries_query)) {
    if ($entries['customers_id'] != $c_id) {
      $ab_id = '1';
      $c_id = $entries['customers_id'];
    }
    $ab_id++;

    $ab_query = tep_db_query("select entry_gender, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_country_id, entry_zone_id from address_book where address_book_id = '" . $entries['address_book_id'] . "'");
    $ab = tep_db_fetch_array($ab_query);

    tep_db_query("delete from address_book where address_book_id = '" . $entries['address_book_id'] . "' and customers_id = ''");
    tep_db_query("insert into address_book (customers_id, address_book_id, entry_gender, entry_company, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_country_id, entry_zone_id) values ('" . $c_id . "', '" . $ab_id . "', '" . $ab['entry_gender'] . "', '', '" . addslashes($ab['entry_firstname']) . "', '" . addslashes($ab['entry_lastname']) . "', '" . addslashes($ab['entry_street_address']) . "', '" . addslashes($ab['entry_suburb']) . "', '" . addslashes($ab['entry_postcode']) . "', '" . addslashes($ab['entry_city']) . "', '" . addslashes($ab['entry_state']) . "', '" . $ab['entry_country_id'] . "', '" . $ab['entry_zone_id'] . "')");
  }

  tep_db_query("alter table address_book add primary key (address_book_id, customers_id)");
  tep_db_query("drop table address_book_to_customers");
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

  tep_db_query("create table banners ( banners_id int(5) not null auto_increment, banners_title varchar(64) not null, banners_url varchar(64) not null, banners_image varchar(64) not null, banners_group varchar(10) not null, banners_html_text text, expires_impressions int(7) default '0', expires_date datetime default null, date_scheduled datetime default null, date_added datetime not null, date_status_change datetime default null, status int(1) default '1', primary key (banners_id) )");
  tep_db_query("create table banners_history ( banners_history_id int(5) not null auto_increment, banners_id int(5) not null, banners_shown int(5) not null default '0', banners_clicked int(5) not null default '0', banners_history_date datetime not null, primary key (banners_history_id) )");
  tep_db_query("insert into banners values (1, 'osCommerce', 'http://www.oscommerce.com', 'banners/oscommerce.gif', '468x50', '', 0, null, null, now(), null, 1)");

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

  tep_db_query("create table categories_description ( categories_id int(5) default '0' not null, language_id int(5) default '1' not null, categories_name varchar(32) not null, primary key (categories_id, language_id), key idx_categories_name (categories_name) )");

  $categories_query = tep_db_query("select categories_id, categories_name from categories order by categories_id");
  while ($categories = tep_db_fetch_array($categories_query)) {
    for ($i=0; $i<sizeof($languages); $i++) {
      tep_db_query("insert into categories_description (categories_id, language_id, categories_name) values ('" . $categories['categories_id'] . "', '" . $languages[$i]['id'] . "', '" . addslashes($categories['categories_name']) . "')");
    }
  }

  tep_db_query("alter table categories drop index IDX_CATEGORIES_NAME");
  tep_db_query("alter table categories drop categories_name");
  tep_db_query("alter table categories change parent_id parent_id int(5) not null default '0'");
  tep_db_query("alter table categories add date_added datetime after sort_order");
  tep_db_query("alter table categories add last_modified datetime after date_added");
  tep_db_query("alter table categories add status int(1) default '1' after date_added");
  tep_db_query("alter table categories add index idx_categories_parent_id (parent_id)");
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

  tep_db_query("alter table configuration change last_modified last_modified datetime");
  tep_db_query("alter table configuration change date_added date_added datetime not null");
  tep_db_query("alter table configuration modify use_function varchar(255)");
  tep_db_query("alter table configuration add set_function varchar(255) after use_function");

  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use MIME HTML when sending emails', 'EMAIL_USE_HTML', 'false', 'Send Emails in HTML format', '1', '5', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");

  tep_db_query("update configuration set set_function = 'tep_cfg_pull_down_country_list(' where configuration_key = 'STORE_COUNTRY'");
  tep_db_query("update configuration set configuration_value = 'desc', configuration_description = 'This is the sort order used in the expected products box.', set_function = 'tep_cfg_select_option(array(\'asc\', \'desc\'), ' where configuration_key = 'EXPECTED_PRODUCTS_SORT'");
  tep_db_query("update configuration set configuration_value = 'date_expected', configuration_description = 'The column to sort by in the expected products box.', set_function = 'tep_cfg_select_option(array(\'products_name\', \'date_expected\'), ' where configuration_key = 'EXPECTED_PRODUCTS_FIELD'");

  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Switch To Default Currency', 'USE_DEFAULT_LANGUAGE_CURRENCY', 'false', 'Automatically switch to the language\'s currency when it is changed', '1', '9', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Send Extra Order Emails To', 'SEND_EXTRA_ORDER_EMAILS_TO', '', 'Send extra order emails to the following email addresses, in this format: Name 1 &lt;email@address1&gt;, Name 2 &lt;email@address2&gt;', '1', '10', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use Search-Engine Safe URLs', 'SEARCH_ENGINE_FRIENDLY_URLS', 'false', 'Use search-engine safe urls for all site links', '1', '11', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Send out Emails', 'SEND_EMAILS', 'true', 'Send out Emails', '1', '12', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Cart After Adding Product', 'DISPLAY_CART', 'true', 'Display the shopping cart after adding a product (or return back to their origin)', '1', '13', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Allow Guest To Tell A Friend', 'ALLOW_GUEST_TO_TELL_A_FRIEND', 'false', 'Allow guests to tell a friend about a product', '1', '14', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Verfiy Email Addresses Through DNS', 'ENTRY_EMAIL_ADDRESS_CHECK', 'false', 'Verfiy Email address through a DNS server', '1', '15', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Empty Manufacturers', 'DISPLAY_EMPTY_MANUFACTURERS', 'true', 'Display manufacturers with no products', '1', '16', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Default Search Operator', 'ADVANCED_SEARCH_DEFAULT_OPERATOR', 'and', 'Default search operators', '1', '17', 'tep_cfg_select_option(array(\'and\', \'or\'), ', now())");

  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Company', 'ENTRY_COMPANY_LENGTH', '2', 'Minimum length of company name', '2', '6', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Best Sellers', 'MIN_DISPLAY_BESTSELLERS', '1', 'Minimum number of best sellers to display', '2', '15', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Also Purchased', 'MIN_DISPLAY_ALSO_PURCHASED', '1', 'Minimum number of products to display in the \'This Customer Also Purchased\' box', '2', '16', now())");

  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Manufacturers Select Size', 'MAX_MANUFACTURERS_LIST', '1', 'Used in manufacturers box; when this value is \'1\' the classic drop-down list will be used for the manufacturers box. Otherwise, a list-box with the specified number of rows will be displayed.', '3', '7', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('New Products Listing', 'MAX_DISPLAY_PRODUCTS_NEW', '10', 'Maximum number of new products to display in new products page', '3', '14', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Best Sellers', 'MAX_DISPLAY_BESTSELLERS', '10', 'Maximum number of best sellers to display', '3', '15', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Also Purchased', 'MAX_DISPLAY_ALSO_PURCHASED', '5', 'Maximum number of products to display in the \'This Customer Also Purchased\' box', '3', '16', now())");

  tep_db_query("delete from configuration where configuration_group_id = '5'");
  tep_db_query("delete from configuration where configuration_group_id = '6'");

  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_PAYMENT_INSTALLED', 'cc.php;cod.php', 'List of payment module filenames separated by a semi-colon. This is automatically updated. No need to edit. (Example: cc.php;cod.php;paypal.php)', '6', '0', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_SHIPPING_INSTALLED', '', 'List of shipping module filenames separated by a semi-colon. This is automatically updated. No need to edit. (Example: ups.php;flat.php;item.php)', '6', '0', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Allow Cash On Delivery (COD)', 'MODULE_PAYMENT_COD_STATUS', '1', 'Do you want to accept COD (Cash On Delevery) payments?', '6', '0', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Allow Credit Card', 'MODULE_PAYMENT_CC_STATUS', '1', 'Do you want to accept credit card payments?', '6', '0', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Credit Card TP email address', 'MODULE_PAYMENT_CC_EMAIL', 'NONE', 'If this email address is not NONE then the middle digits of any stored cc numbers will be X-ed out and emailed with the order id.', '6', '0', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Default Currency', 'DEFAULT_CURRENCY', 'USD', 'Default Currency', '6', '0', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Default Language', 'DEFAULT_LANGUAGE', 'en', 'Default Language', '6', '0', now())");

  tep_db_query("delete from configuration where configuration_group_id = '7' and configuration_key != 'SHIPPING_BOX_WEIGHT' and configuration_key != 'SHIPPING_BOX_PADDING' and configuration_key != 'SHIPPING_HANDLING' and configuration_key != 'SHIPPING_MAX_WEIGHT' and configuration_key != 'STORE_ORIGIN_ZIP' and configuration_key != 'STORE_ORIGIN_COUNTRY'");

  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Check stock level', 'STOCK_CHECK', '1', 'Check to see if sufficent stock is available', '9', '1', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Subtract stock', 'STOCK_LIMITED', '1', 'Subtract product in stock by product orders', '9', '2', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Allow Checkout', 'STOCK_ALLOW_CHECKOUT', '1', 'Allow customer to checkout even if there is insufficient stock', '9', '3', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Mark product out of stock', 'STOCK_MARK_PRODUCT_OUT_OF_STOCK', '***', 'Display something on screen so customer can see which product has insufficient stock', '9', '4', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Stock Re-order level', 'STOCK_REORDER_LEVEL', '5', 'Define when stock needs to be re-ordered', '9', '5', now())");

  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Store Page Parse Time', 'STORE_PAGE_PARSE_TIME', 'false', 'Store the time it takes to parse a page', '10', '1', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Log Destination', 'STORE_PAGE_PARSE_TIME_LOG', '/var/log/www/tep/page_parse_time.log', 'Directory and filename of the page parse time log', '10', '2', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Log Date Format', 'STORE_PARSE_DATE_TIME_FORMAT', '%d/%m/%Y %H:%M:%S', 'The date format', '10', '3', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display The Page Parse Time', 'DISPLAY_PAGE_PARSE_TIME', 'true', 'Display the page parse time (store page parse time must be enabled)', '10', '4', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Store Database Queries', 'STORE_DB_TRANSACTIONS', 'false', 'Store the database queries in the page parse time log (PHP4 only)', '10', '5', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");

  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use Cache', 'USE_CACHE', 'false', 'Use caching features', '11', '1', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Cache Directory', 'DIR_FS_CACHE', '/tmp/', 'The directory where the cached files are saved', '11', '2', now())");

  tep_db_query("delete from configuration_group");

  tep_db_query("alter table configuration_group add visible int(1) default '1'");

  tep_db_query("insert into configuration_group VALUES ('1', 'My Store', 'General information about my store', '1', '1')");
  tep_db_query("insert into configuration_group VALUES ('2', 'Minimum Values', 'The minimum values for functions / data', '2', '1')");
  tep_db_query("insert into configuration_group VALUES ('3', 'Maximum Values', 'The maximum values for functions / data', '3', '1')");
  tep_db_query("insert into configuration_group VALUES ('4', 'Images', 'Image parameters', '4', '1')");
  tep_db_query("insert into configuration_group VALUES ('6', 'Module Options', 'Hidden from configuration', '6', '0')");
  tep_db_query("insert into configuration_group VALUES ('7', 'Shipping/Packaging', 'Shipping options available at my store', '7', '1')");
  tep_db_query("insert into configuration_group VALUES ('8', 'Product Listing', 'Product Listing    configuration options', '8', '1')");
  tep_db_query("insert into configuration_group VALUES ('9', 'Stock', 'Stock configuration options', '9', '1')");
  tep_db_query("insert into configuration_group values ('10', 'Logging', 'Logging configuration options', '10', '1')");
  tep_db_query("insert into configuration_group values ('11', 'Cache', 'Caching configuration options', '11', '1')");
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

  tep_db_query("alter table currencies add value float(13,8)");
  tep_db_query("alter table currencies add last_updated datetime");

  tep_db_query("update currencies set value = '1'");
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

  tep_db_query("alter table customers drop customers_street_address");
  tep_db_query("alter table customers drop customers_suburb");
  tep_db_query("alter table customers drop customers_postcode");
  tep_db_query("alter table customers drop customers_city");
  tep_db_query("alter table customers drop customers_state");
  tep_db_query("alter table customers drop customers_zone_id");
  tep_db_query("alter table customers drop customers_country_id");
  tep_db_query("alter table customers change customers_dob customers_dob datetime not null default '0000-00-00 00:00:00'");
  tep_db_query("alter table customers add customers_newsletter char(1)");
  tep_db_query("alter table customers add customers_default_address_id int(5) not null default '1' after customers_email_address");

  tep_db_query("alter table customers_basket change products_id products_id tinytext not null");
  tep_db_query("alter table customers_basket change customers_basket_date_added customers_basket_date_added varchar(8)");

  tep_db_query("alter table customers_basket_attributes change products_id products_id tinytext not null");

  tep_db_query("alter table customers_info change customers_info_date_account_created customers_info_date_account_created datetime");
  tep_db_query("alter table customers_info change customers_info_date_of_last_logon customers_info_date_of_last_logon datetime");
  tep_db_query("alter table customers_info change customers_info_date_account_last_modified customers_info_date_account_last_modified datetime");
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
  $categories_query = tep_db_query("select categories_id, categories_image from categories where left(categories_image, 7) = 'images/'");
  while ($categories = tep_db_fetch_array($categories_query)) {
    tep_db_query("update categories set categories_image = substring('" . $categories['categories_image'] . "', 8) where categories_id = '" . $categories['categories_id'] . "'");
  }

// manufacturers
  $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_image from manufacturers where left(manufacturers_image, 7) = 'images/'");
  while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
    tep_db_query("update manufacturers set manufacturers_image = substring('" . $manufacturers['manufacturers_image'] . "', 8) where manufacturers_id = '" . $manufacturers['manufacturers_id'] . "'");
  }

// products
  $products_query = tep_db_query("select products_id, products_image from products where left(products_image, 7) = 'images/'");
  while ($products = tep_db_fetch_array($products_query)) {
    tep_db_query("update products set products_image = substring('" . $products['products_image'] . "', 8) where products_id = '" . $products['products_id'] . "'");
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

  tep_db_query("update languages set image = 'icon.gif'");
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

  tep_db_query("alter table manufacturers add date_added datetime null after manufacturers_image, add last_modified datetime null after date_added");
  tep_db_query("create table manufacturers_info (manufacturers_id int(5) not null, languages_id int(5) not null, manufacturers_url varchar(255) not null, url_clicked int(5) not null default '0', date_last_click datetime, primary key (manufacturers_id, languages_id))");
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

  tep_db_query("alter table orders change date_purchased date_purchased datetime");
  tep_db_query("alter table orders change last_modified last_modified datetime");
  tep_db_query("alter table orders change orders_date_finished orders_date_finished datetime");
  tep_db_query("alter table orders_products add column products_model varchar(12)");

  tep_db_query("create table orders_status ( orders_status_id int(5) default '0' not null, language_id int(5) default '1' not null, orders_status_name varchar(32) not null, primary key (orders_status_id, language_id), key idx_orders_status_name (orders_status_name))");

  for ($i=0; $i<sizeof($languages); $i++) {
    tep_db_query("insert into orders_status values ('1', '" . $languages[$i]['id'] . "', 'Pending')");
    tep_db_query("insert into orders_status values ('2', '" . $languages[$i]['id'] . "', 'Processing')");
    tep_db_query("insert into orders_status values ('3', '" . $languages[$i]['id'] . "', 'Delivered')");
  }

  tep_db_query("update orders set orders_status = '1' where orders_status = 'Pending'");
  tep_db_query("update orders set orders_status = '2' where orders_status = 'Processing'");
  tep_db_query("update orders set orders_status = '3' where orders_status = 'Delivered'");

  $status = array();
  $orders_status_query = tep_db_query("select distinct orders_status from orders where orders_status not in ('1', '2', '3')");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $status[] = array('text' => $orders_status['orders_status']);
  }

  $orders_status_id = 4;
  for ($i=0; $i<sizeof($status); $i++) {
    for ($j=0; $j<sizeof($languages); $j++) {
      tep_db_query("insert into orders_status values ('" . $orders_status_id . "', '" . $languages[$j]['id'] . "', '" . $status[$i]['text'] . "')");
    }
    tep_db_query("update orders set orders_status = '" . $orders_status_id . "' where orders_status = '" . $status[$i]['text'] . "'");
    $orders_status_id++;
  }

  tep_db_query("alter table orders change orders_status orders_status int(5) not null");
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

  tep_db_query("create table products_description ( products_id int(5) not null auto_increment, language_id int(5) not null default '1', products_name varchar(64) not null default '',  products_description text, products_url varchar(255), products_viewed int(5) default '0', primary key (products_id, language_id), key products_name (products_name))");

  $products_query = tep_db_query("select products_id, products_name, products_description, products_url, products_viewed from products order by products_id");
  while ($products = tep_db_fetch_array($products_query)) {
    for ($i=0; $i<sizeof($languages); $i++) {
      tep_db_query("insert into products_description (products_id, language_id, products_name, products_description, products_url, products_viewed) values ('" . $products['products_id'] . "', '" . $languages[$i]['id'] . "', '" . addslashes($products['products_name']) . "', '" . addslashes($products['products_description']) . "', '" . addslashes($products['products_url']) . "', '" . $products['products_viewed'] . "')");
    }
  }

  tep_db_query("alter table products change products_date_added products_date_added datetime");

  tep_db_query("alter table products drop index products_name");

  tep_db_query("alter table products drop products_url");
  tep_db_query("alter table products drop products_name");
  tep_db_query("alter table products drop products_description");
  tep_db_query("alter table products drop products_viewed");

  tep_db_query("alter table products add products_date_available datetime");
  tep_db_query("alter table products add products_last_modified datetime");

  tep_db_query("drop table products_expected");

  tep_db_query("alter table products_options change products_options_id products_options_id int(5) not null default '0'");
  tep_db_query("alter table products_options add language_id int(5) not null default '1' after products_options_id");
  tep_db_query("alter table products_options drop primary key");
  tep_db_query("alter table products_options add primary key (products_options_id, language_id)");

  $products_query = tep_db_query("select products_options_id, language_id, products_options_name from products_options order by products_options_id");
  while ($products = tep_db_fetch_array($products_query)) {
    for ($i=0; $i<sizeof($languages); $i++) {
      tep_db_query("replace into products_options (products_options_id, language_id, products_options_name) values ('" . $products['products_options_id'] . "', '" . $languages[$i]['id'] . "', '" . addslashes($products['products_options_name']) . "')");
    }
  }

  tep_db_query("alter table products_options_values change products_options_values_id products_options_values_id int(5) not null default '0'");
  tep_db_query("alter table products_options_values add language_id int(5) not null default '1' after products_options_values_id");
  tep_db_query("alter table products_options_values drop primary key");
  tep_db_query("alter table products_options_values add primary key (products_options_values_id, language_id)");

  $products_query = tep_db_query("select products_options_values_id, language_id, products_options_values_name from products_options_values order by products_options_values_id");
  while ($products = tep_db_fetch_array($products_query)) {
    for ($i=0; $i<sizeof($languages); $i++) {
      tep_db_query("replace into products_options_values (products_options_values_id, language_id, products_options_values_name) values ('" . $products['products_options_values_id'] . "', '" . $languages[$i]['id'] . "', '" . addslashes($products['products_options_values_name']) . "')");
    }
  }

  tep_db_query("alter table products_to_categories change products_id products_id int(5) not null");
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

  tep_db_query("create table reviews_description ( reviews_id int(5) not null, languages_id int(5) not null, reviews_text text not null, primary key (reviews_id, languages_id))");

  tep_db_query("alter table reviews add products_id int(5) not null default '0' after reviews_id");
  tep_db_query("alter table reviews add customers_id int(5) after products_id");
  tep_db_query("alter table reviews add customers_name varchar(64) not null default '' after customers_id");
  tep_db_query("alter table reviews add date_added datetime after reviews_rating");
  tep_db_query("alter table reviews add last_modified datetime after date_added");
  tep_db_query("alter table reviews add reviews_read int(5) not null default '0'");

  $reviews_query = tep_db_query("select r.reviews_id, re.products_id, re.customers_id, r.reviews_rating, re.date_added, re.reviews_read, r.reviews_text from reviews r, reviews_extra re where r.reviews_id = re.reviews_id order by r.reviews_id");
  while ($reviews = tep_db_fetch_array($reviews_query)) {
    $customer_query = tep_db_query("select customers_firstname, customers_lastname from customers where customers_id = '" . $reviews['customers_id'] . "'");
    if (tep_db_num_rows($customer_query)) {
      $customer = tep_db_fetch_array($customer_query);
      $customers_name = $customer['customers_firstname'] . ' ' . $customer['customers_lastname'];
    } else {
      $customers_name = '';
    }

    tep_db_query("update reviews set products_id = '" . $reviews['products_id'] . "', customers_id = '" . $reviews['customers_id'] . "', customers_name = '" . addslashes($customers_name) . "', date_added = '" . $reviews['date_added'] . "', last_modified = '', reviews_read = '" . $reviews['reviews_read'] . "' where reviews_id = '" . $reviews['reviews_id'] . "'");
    tep_db_query("insert into reviews_description (reviews_id, languages_id, reviews_text) values ('" . $reviews['reviews_id'] . "', '" . $languages[0]['id'] . "', '" . addslashes($reviews['reviews_text']) . "')");
  }

  tep_db_query("alter table reviews drop reviews_text");

  tep_db_query("drop table reviews_extra");
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

  tep_db_query("create table sessions (sesskey varchar(32) not null, expiry int(11) unsigned not null, value text not null, primary key (sesskey))");
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

  tep_db_query("alter table specials change specials_date_added specials_date_added datetime");

  tep_db_query("alter table specials add specials_last_modified datetime");
  tep_db_query("alter table specials add expires_date datetime");
  tep_db_query("alter table specials add date_status_change datetime");
  tep_db_query("alter table specials add status int(1) default '1'");
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

  tep_db_query("alter table tax_class change date_added date_added datetime not null");
  tep_db_query("alter table tax_class change last_modified last_modified datetime");

  tep_db_query("alter table tax_rates change date_added date_added datetime not null");
  tep_db_query("alter table tax_rates change last_modified last_modified datetime");

  tep_db_query("alter table tax_rates add tax_priority int(5) default '1' after tax_class_id");

  tep_db_query("create table geo_zones (geo_zone_id int(5) not null auto_increment, geo_zone_name varchar(32) not null, geo_zone_description varchar(255) not null, last_modified datetime, date_added datetime not null, primary key (geo_zone_id))");
  tep_db_query("create table zones_to_geo_zones (association_id int(5) not null auto_increment, zone_country_id int(5) not null, zone_id int(5), geo_zone_id int(5), last_modified datetime, date_added datetime not null, primary key (association_id))");

  tep_db_query("alter table zones change zone_code zone_code varchar(32) not null");
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

  tep_db_query("create table whos_online (customer_id int(5),  full_name varchar(64) not null, session_id varchar(128) not null, ip_address varchar(15) not null, time_entry varchar(14) not null, time_last_click varchar(14) not null, last_page_url varchar(64) not null)");
?>

<script language="javascript"><!--
changeStyle('whosOnline', 'normal');
changeText('whosOnlineMarker', '*');
changeText('statusText', 'Updating Whos Online .. done!');

changeStyle('statusText', 'bold');
changeText('statusText', 'Update Complete!');
//--></script>
