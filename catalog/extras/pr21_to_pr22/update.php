<?php
/*
  $Id: update.php,v 1.4 2001/10/25 11:26:30 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  if (!$HTTP_POST_VARS['DB_SERVER']) {
?>
<html>
<head>
<title>The Exchange Project Preview Release 2.2 Database Update Script</title>
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
<b>The Exchange Project Preview Release 2.2 Database Update Script</b>
<p>
<form name="database" action="<?php echo basename($HTTP_SERVER_VARS['SCRIPT_FILENAME']); ?>" method="post">
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
<title>The Exchange Project Preview Release 2.2 Database Update Script</title>
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
    document.getElementById(where).innerText = what;
  } else if (document.all) {
    document.all[where].innerText = what;
  }
}
//--></script>
</head>
<body>
<p>
<b>The Exchange Project Preview Release 2.2 Database Update Script</b>
<p>
<span id="addressBook"><span id="addressBookMarker">-</span> Address Book</span><br>
<span id="banners"><span id="bannersMarker">-</span> Banners</span><br>
<span id="categories"><span id="categoriesMarker">-</span> Categories</span><br>
<span id="configuration"><span id="configurationMarker">-</span> Configuration</span><br>
<span id="currencies"><span id="currenciesMarker">-</span> Currencies</span><br>
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

  $entries_query = tep_db_query("select address_book_id, customers_id from address_book_to_customers order by customers_id, address_book_id DESC");
  $ab_id = '1'; // set new address_book_id
  $c_id = '-1'; // when customer_id does not equal $c_id, reset $ab_id
  while ($entries = tep_db_fetch_array($entries_query)) {
    if ($entries['customers_id'] != $c_id) {
      $ab_id = '1';
      $c_id = $entries['customers_id'];

      $customer_query = tep_db_query("select customers_gender, customers_firstname, customers_lastname, customers_street_address, customers_suburb, customers_postcode, customers_city, customers_state, customers_country_id, customers_zone_id from customers where customers_id = '" . $c_id . "'");
      $customer = tep_db_fetch_array($customer_query);
      tep_db_query("insert into address_book (customers_id, address_book_id, entry_gender, entry_company, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_country_id, entry_zone_id) values ('" . $c_id . "', '" . $ab_id . "', '" . $customer['customers_gender'] . "', '', '" . $customer['customers_firstname'] . "', '" . $customer['customers_lastname'] . "', '" . $customer['customers_street_address']. "', '" . $customer['customers_suburb']. "', '" . $customer['customers_postcode'] . "', '" . $customer['customers_city'] . "', '" . $customer['customers_state'] . "', '" . $customer['customers_country_id'] . "', '" . $customer['customers_zone_id'] . "')");
    }
    $ab_id++;

    $ab_query = tep_db_query("select entry_gender, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_country_id, entry_zone_id from address_book where address_book_id = '" . $entries['address_book_id'] . "'");
    $ab = tep_db_fetch_array($ab_query);

    tep_db_query("delete from address_book where address_book_id = '" . $entries['address_book_id'] . "' and customers_id = ''");
    tep_db_query("insert into address_book (customers_id, address_book_id, entry_gender, entry_company, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_country_id, entry_zone_id) values ('" . $c_id . "', '" . $ab_id . "', '" . $ab['entry_gender'] . "', '', '" . $ab['entry_firstname'] . "', '" . $ab['entry_lastname'] . "', '" . $ab['entry_street_address'] . "', '" . $ab['entry_suburb']. "', '" . $ab['entry_postcode']. "', '" . $ab['entry_city'] . "', '" . $ab['entry_state']. "', '" . $ab['entry_country_id'] . "', '" . $ab['entry_zone_id'] . "')");

//    tep_db_query("update address_book set customers_id = '" . $c_id . "', address_book_id = '" . $ab_id . "' where address_book_id = '" . $entries['address_book_id'] . "'");
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

  tep_db_query("create table banners ( banners_id int(5) not null auto_increment, banners_title varchar(64) not null, banners_url varchar(64) not null, banners_image varchar(64) not null, banners_group varchar(10) not null, banners_html_text text, expires_impressions int(7), expires_date datetime, date_scheduled datetime, date_added datetime not null, date_status_change datetime, status int(1) default '1', primary key (banners_id) )");
  tep_db_query("create table banners_history ( banners_history_id int(5) not null auto_increment, banners_id int(5) not null, banners_shown int(5) not null default '0', banners_clicked int(5) not null default '0', banners_history_date datetime not null, primary key (banners_history_id) )");
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
      tep_db_query("insert into categories_description (categories_id, language_id, categories_name) values ('" . $categories['categories_id'] . "', '" . $languages[$i]['id'] . "', '" . $categories['categories_name'] . "')");
    }
  }

  tep_db_query("alter table categories drop index IDX_CATEGORIES_NAME");
  tep_db_query("alter table categories drop categories_name");
  tep_db_query("alter table categories change parent_id parent_id int(5) not null default '0'");
  tep_db_query("alter table categories add date_added datetime after sort_order");
  tep_db_query("alter table categories add last_modified datetime after date_added");
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
  tep_db_query("alter table configuration add set_function varchar(32) after use_function");

  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Use MIME HTML when sending emails', 'EMAIL_USE_HTML', '0', '0 = If normal text mails are wanted. 1 = If you want to send the HTML version of the mail too.', '1', '4', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Company', 'ENTRY_COMPANY_LENGTH', '2', 'Minimum length of company name', '2', '6', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Manufacturers Select Size', 'MAX_MANUFACTURERS_LIST', '1', 'Used in manufacturers box; when this value is \'1\' the classic drop-down list will be used for the manufacturers box. Otherwise, a list-box with the specified number of rows will be displayed.', '3', '7', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('New Products Listing', 'MAX_DISPLAY_PRODUCTS_NEW', '10', 'Maximum number of new products to display in new products page', '3', '14', now())");

  tep_db_query("delete from configuration where configuration_group_id = '5'");
  tep_db_query("delete from configuration where configuration_group_id = '6'");

  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_PAYMENT_INSTALLED', 'cc.php;cod.php', 'List of payment module filenames separated by a semi-colon. This is automatically updated. No need to edit. (Example: cc.php;cod.php;paypal.php)', '6', '0', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_SHIPPING_INSTALLED', '', 'List of shipping module filenames separated by a semi-colon. This is automatically updated. No need to edit. (Example: ups.php;flat.php;item.php)', '6', '0', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Allow Cash On Delivery (COD)', 'MODULE_PAYMENT_COD_STATUS', '1', 'Do you want to accept COD (Cash On Delevery) payments?', '6', '0', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Allow Credit Card', 'MODULE_PAYMENT_CC_STATUS', '1', 'Do you want to accept credit card payments?', '6', '0', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Credit Card TP email address', 'MODULE_PAYMENT_CC_EMAIL', 'NONE', 'If this email address is not NONE then the middle digits of any stored cc numbers will be X-ed out and emailed with the order id.', '6', '0', now())");

  tep_db_query("delete from configuration where configuration_group_id = '7' and configuration_key != 'SHIPPING_BOX_WEIGHT' and configuration_key != 'SHIPPING_BOX_PADDING' and configuration_key != 'SHIPPING_HANDLING' and configuration_key != 'SHIPPING_MAX_WEIGHT' and configuration_key != 'STORE_ORIGIN_ZIP' and configuration_key != 'STORE_ORIGIN_COUNTRY'");

  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Check stock level', 'STOCK_CHECK', '1', 'Check to see if sufficent stock is available', '9', '1', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Subtract stock', 'STOCK_LIMITED', '1', 'Subtract product in stock by product orders', '9', '2', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Allow Checkout', 'STOCK_ALLOW_CHECKOUT', '1', 'Allow customer to checkout even if there is insufficient stock', '9', '3', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Mark product out of stock', 'STOCK_MARK_PRODUCT_OUT_OF_STOCK', '***', 'Display something on screen so customer can see which product has insufficient stock', '9', '4', now())");
  tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Stock Re-order level', 'STOCK_REORDER_LEVEL', '5', 'Define when stock needs to be re-ordered', '9', '5', now())");

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
?>

<script language="javascript"><!--
changeStyle('configuration', 'normal');
changeText('configurationMarker', '*');
changeText('statusText', 'Updating Configuration .. done!');

//changeStyle('statusText', 'bold');
//changeText('statusText', 'Update Complete!');
changeStyle('currencies', 'bold');
changeText('currenciesMarker', '?');
changeText('statusText', 'Updating Currencies');
//--></script>

<?php
  flush();

  tep_db_query("alter table currencies add value float");
  tep_db_query("alter table currencies add last_updated datetime");

  tep_db_query("update currencies set value = '1'");
?>

<script language="javascript"><!--
changeStyle('currencies', 'normal');
changeText('currenciesMarker', '*');
changeText('statusText', 'Updating Currencies .. done!');

changeStyle('statusText', 'bold');
changeText('statusText', 'Update Complete!');
//--></script>
