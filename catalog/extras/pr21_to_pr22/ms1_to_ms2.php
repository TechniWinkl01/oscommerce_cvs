<?php
/*
  $Id: ms1_to_ms2.php,v 1.2 2003/06/17 02:50:32 thomasamoulton Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  if (!$HTTP_POST_VARS['DB_SERVER']) {
?>
<html>
<head>
<title>osCommerce Preview Release 2.2 Database Update Script</title>
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
<b>osCommerce Release 2.2 MS1 to MS2 Database Update Script</b>
<p>This script can be copied to any web directory to upgrade a MS1 database
to a MS2 database. By MS1 and MS2 I mean the state of the database the DAY
that the MS release was made, not *any* MS1 like CVS tree.

So if you upgraded to MS1 and stayed there you can use this script.
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
</form>
</body>
</html>
<?php
    exit;
  }

  function osc_db_connect() {
    global $db_link, $HTTP_POST_VARS;

    $db_link = mysql_connect($HTTP_POST_VARS['DB_SERVER'], $HTTP_POST_VARS['DB_SERVER_USERNAME'], $HTTP_POST_VARS['DB_SERVER_PASSWORD']);

    if ($db_link) mysql_select_db($HTTP_POST_VARS['DB_DATABASE']);

    return $db_link;
  }

  function osc_db_error ($query, $errno, $error) { 
    die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
  }

  function osc_db_query($db_query) {
    global $db_link;

    $result = mysql_query($db_query, $db_link) or osc_db_error($db_query, mysql_errno(), mysql_error());

    return $result;
  }

  function osc_db_fetch_array($db_query) {
    $result = mysql_fetch_array($db_query);

    return $result;
  }

  function osc_db_num_rows($db_query) {
    return mysql_num_rows($db_query);
  }

// Sets timeout for the current script.
// Cant be used in safe mode.
  function osc_set_time_limit($limit) {
    if (!get_cfg_var('safe_mode')) {
      set_time_limit($limit);
    }
  }

  osc_db_connect() or die('Unable to connect to database server!');
?>
<p><span class="pageHeading">osCommerce</span><br><font color="#9a9a9a">Open Source E-Commerce Solutions</font></p>

<p class="pageTitle">Upgrade</p>

<p><b>Step 1: Database Upgrade</b></p>

<?php
  function osc_db_update_configuration_key($key, $new_key) {

    $sql = "update configuration set configuration_key = '" . $new_key . "' where configuration_key = '" . $key . "'";
    osc_db_query($sql);
  }

  function osc_db_update_configuration_title($key, $new_title) {

    $sql = "update configuration set configuration_title = '" . $new_title . "' where configuration_key = '" . $key . "'";
    osc_db_query($sql);
    $db_error = mysql_error();
    if ($db_error != false) die($db_error);
  }

  function osc_db_update_configuration_description($key, $new_description) {

    $sql = "update configuration set configuration_description = '" . $new_description . "' where configuration_key = '" . $key . "'";
    osc_db_query($sql);
    $db_error = mysql_error();
    if ($db_error != false) die($db_error);
  }

  function osc_db_update_configuration_use_null($key) {

    $sql = "update configuration set use_function = NULL where configuration_key = '" . $key . "'";
    osc_db_query($sql);
    $db_error = mysql_error();
    if ($db_error != false) die($db_error);
  }

  osc_set_time_limit(0);

// send data to the browser, so the flushing works with IE
  for ($i=0; $i<300; $i++) print(' ');
  print ("\n");
?>

<p><span id="addressBook"><span id="addressBookMarker">-</span> Address Book</span><br>
<span id="banners"><span id="bannersMarker">-</span> Banners</span><br>
<span id="categories"><span id="categoriesMarker">-</span> Categories</span><br>
<span id="configuration"><span id="configurationMarker">-</span> Configuration</span><br>
<span id="currencies"><span id="currenciesMarker">-</span> Currencies</span><br>
<span id="countries"><span id="countriesMarker">-</span> Countries</span><br>
<span id="customers"><span id="customersMarker">-</span> Customers</span><br>
<span id="languages"><span id="languagesMarker">-</span> Languages</span><br>
<span id="zones"><span id="zonesMarker">-</span> Zones</span><br>
<span id="manufacturers"><span id="manufacturersMarker">-</span> Manufacturers</span><br>
<span id="newsletters"><span id="newslettersMarker">-</span> Newsletters</span><br>
<span id="orders"><span id="ordersMarker">-</span> Orders</span><br>
<span id="products"><span id="productsMarker">-</span> Products</span><br>
<span id="reviews"><span id="reviewsMarker">-</span> Reviews</span><br>
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

  osc_db_query("ALTER TABLE address_book CHANGE COLUMN entry_country_id entry_country_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE address_book CHANGE COLUMN entry_zone_id entry_zone_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE address_book CHANGE COLUMN customers_id customers_id int(11) NOT NULL default '0'");

  /* Now convert the address_book_id to unique entries, now most are =1 */
  osc_db_query("alter table address_book add temp_id int(11) not NULL default '0' FIRST");
  $ab_query = osc_db_query("select customers_id, address_book_id from address_book order by address_book_id");
  $ab_id = 1;
  while ($ab = osc_db_fetch_array($ab_query)) {
    osc_db_query("update customers set customers_default_address_id = '" . $ab_id . "' where customers_id = '" . $ab['customers_id'] . "'");
    osc_db_query("update address_book set temp_id = '" . $ab_id . "' where customers_id = '" . $ab['customers_id'] . "' and address_book_id = '" . $ab['address_book_id'] . "'");
    $ab_id++;
  }

  osc_db_query("ALTER TABLE address_book DROP PRIMARY KEY");
  osc_db_query("ALTER TABLE address_book DROP COLUMN address_book_id");
  osc_db_query("ALTER TABLE address_book ADD PRIMARY KEY (temp_id)");
  osc_db_query("ALTER TABLE address_book CHANGE COLUMN temp_id address_book_id int(11) NOT NULL auto_increment");
  osc_db_query("ALTER TABLE address_book ADD INDEX idx_address_book_customers_id (customers_id)");

  osc_db_query("ALTER TABLE address_format CHANGE COLUMN address_format_id address_format_id int(11) NOT NULL auto_increment");
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

  osc_db_query("ALTER TABLE banners CHANGE COLUMN banners_id banners_id int(11) NOT NULL auto_increment");
  osc_db_query("ALTER TABLE banners_history CHANGE COLUMN banners_id banners_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE banners_history CHANGE COLUMN banners_history_id banners_history_id int(11) NOT NULL auto_increment");

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

  osc_db_query("ALTER TABLE categories CHANGE COLUMN parent_id parent_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE categories CHANGE COLUMN categories_id categories_id int(11) NOT NULL auto_increment");
  osc_db_query("ALTER TABLE categories_description CHANGE COLUMN language_id language_id int(11) NOT NULL default '1'");
  osc_db_query("ALTER TABLE categories_description CHANGE COLUMN categories_id categories_id int(11) NOT NULL default '0'");

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

  osc_db_query("ALTER TABLE configuration CHANGE COLUMN configuration_id configuration_id int(11) NOT NULL auto_increment");
  osc_db_query("ALTER TABLE configuration CHANGE COLUMN configuration_group_id configuration_group_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE configuration_group CHANGE COLUMN configuration_group_id configuration_group_id int(11) NOT NULL auto_increment");

  osc_db_update_configuration_key('download_by_redirect', 'DOWNLOAD_BY_REDIRECT');
  osc_db_update_configuration_key('download_enabled', 'DOWNLOAD_ENABLED');
  osc_db_update_configuration_key('download_max_count', 'DOWNLOAD_MAX_COUNT');
  osc_db_update_configuration_key('download_max_days', 'DOWNLOAD_MAX_DAYS');

  osc_db_update_configuration_use_null('EMAIL_FROM');

  osc_db_update_configuration_key('ENTRY_COMPANY_LENGTH', 'ENTRY_COMPANY_MIN_LENGTH');

  osc_db_update_configuration_use_null('ENTRY_FIRST_NAME_MIN_LENGTH');
  osc_db_update_configuration_use_null('ENTRY_LAST_NAME_MIN_LENGTH');

  osc_db_update_configuration_use_null('ENTRY_STATE_MIN_LENGTH');
  osc_db_update_configuration_use_null('HEADING_IMAGE_HEIGHT');
  osc_db_update_configuration_use_null('HEADING_IMAGE_WIDTH');
  osc_db_update_configuration_use_null('IMAGE_REQUIRED');
  osc_db_update_configuration_use_null('PRODUCT_LIST_BUY_NOW');
  osc_db_update_configuration_use_null('PRODUCT_LIST_MODEL');
  osc_db_update_configuration_use_null('PRODUCT_LIST_WEIGHT');
  osc_db_update_configuration_use_null('SHIPPING_BOX_WEIGHT');
  osc_db_update_configuration_use_null('SMALL_IMAGE_HEIGHT');
  osc_db_update_configuration_use_null('STORE_NAME');
  osc_db_update_configuration_use_null('STORE_OWNER');
  osc_db_update_configuration_use_null('STORE_OWNER_EMAIL_ADDRESS');
  osc_db_update_configuration_use_null('SUBCATEGORY_IMAGE_HEIGHT');

  osc_db_query("update configuration set use_function = 'tep_cfg_get_zone_name' where configuration_key = 'STORE_ZONE'");

  osc_db_update_configuration_key('STORE_ORIGIN_ZIP', 'SHIPPING_ORIGIN_ZIP');
  osc_db_update_configuration_use_null('SHIPPING_ORIGIN_ZIP');

  osc_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Country of Origin', 'SHIPPING_ORIGIN_COUNTRY', '223', 'Select the country of origin to be used in shipping quotes.', '7', '1', 'tep_get_country_name', 'tep_cfg_pull_down_country_list(', now())");

  $country_query = osc_db_query("select configuration_value as name from configuration where configuration_key = 'STORE_ORIGIN_COUNTRY'");
  if (osc_db_num_rows($country_query) > 0) {
    $country = osc_db_fetch_array($country_query);
    if ($country['name'] != '') {
      $new_country_query = osc_db_query("select countries_id from countries where countries_iso_code_2 = '" . $country['name'] . "'");
      $new_country = osc_db_fetch_array($new_country_query);
      if ($new_country['countries_iso_code_2'] != NULL) {
        osc_db_query("update configuration set configuration_value = " . $new_country['countries_iso_code_2'] . " where configuration_key = 'SHIPPING_ORIGIN_COUNTRY'");
      }
    }
  }

  osc_db_query("delete from configuration where configuration_key = 'STORE_ORIGIN_COUNTRY'");

  osc_db_query("insert into configuration_group values ('15', 'Sessions', 'Session options', '15', '1')");

  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Session Directory', 'SESSION_WRITE_DIRECTORY', '/tmp', 'If sessions are file based, store them in this directory.', '15', '1', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Force Cookie Use', 'SESSION_FORCE_COOKIE_USE', 'False', 'Force the use of sessions when cookies are only enabled.', '15', '2', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Check SSL Session ID', 'SESSION_CHECK_SSL_SESSION_ID', 'False', 'Validate the SSL_SESSION_ID on every secure HTTPS page request.', '15', '3', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Check User Agent', 'SESSION_CHECK_USER_AGENT', 'False', 'Validate the clients browser user agent on every page request.', '15', '4', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Check IP Address', 'SESSION_CHECK_IP_ADDRESS', 'False', 'Validate the clients IP address on every page request.', '15', '5', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Prevent Spider Sessions', 'SESSION_BLOCK_SPIDERS', 'False', 'Prevent known spiders from starting a session.', '15', '6', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
  osc_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Recreate Session', 'SESSION_RECREATE', 'False', 'Recreate the session to generate a new session ID when the customer logs on or creates an account (PHP >=4.1 needed).', '15', '7', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");

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

  osc_db_query("ALTER TABLE currencies CHANGE COLUMN currencies_id currencies_id int(11) NOT NULL auto_increment");

?>

<script language="javascript"><!--
changeStyle('currencies', 'normal');
changeText('currenciesMarker', '*');
changeText('statusText', 'Updating Currencies .. done!');

changeStyle('countries', 'bold');
changeText('countriesMarker', '?');
changeText('statusText', 'Updating Countries');
//--></script>

<?php
  flush();

  osc_db_query("ALTER TABLE countries CHANGE COLUMN countries_id countries_id int(11) NOT NULL auto_increment");
  osc_db_query("ALTER TABLE countries CHANGE COLUMN address_format_id address_format_id int(11) NOT NULL default '0'");

?>

<script language="javascript"><!--
changeStyle('countries', 'normal');
changeText('countriesMarker', '*');
changeText('statusText', 'Updating Currencies .. done!');

changeStyle('customers', 'bold');
changeText('customersMarker', '?');
changeText('statusText', 'Updating Customers');
//--></script>

<?php
  flush();

  osc_db_query("ALTER TABLE customers CHANGE COLUMN customers_default_address_id customers_default_address_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE customers CHANGE COLUMN customers_id customers_id int(11) NOT NULL auto_increment");
  osc_db_query("ALTER TABLE customers_basket CHANGE COLUMN customers_basket_id customers_basket_id int(11) NOT NULL auto_increment");
  osc_db_query("ALTER TABLE customers_basket CHANGE COLUMN customers_id customers_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE customers_basket_attributes CHANGE COLUMN customers_basket_attributes_id customers_basket_attributes_id int(11) NOT NULL auto_increment");
  osc_db_query("ALTER TABLE customers_basket_attributes CHANGE COLUMN products_options_id products_options_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE customers_basket_attributes CHANGE COLUMN products_options_value_id products_options_value_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE customers_basket_attributes CHANGE COLUMN customers_id customers_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE customers_info CHANGE COLUMN customers_info_id customers_info_id int(11) NOT NULL default '0'");
?>

<script language="javascript"><!--
changeStyle('customers', 'normal');
changeText('customersMarker', '*');
changeText('statusText', 'Updating Customers .. done!');

changeStyle('languages', 'bold');
changeText('languagesMarker', '?');
changeText('statusText', 'Updating Languages');
//--></script>

<?php
  flush();

  osc_db_query("ALTER TABLE languages CHANGE COLUMN languages_id languages_id int(11) NOT NULL auto_increment");
?>

<script language="javascript"><!--
changeStyle('languages', 'normal');
changeText('languagesMarker', '*');
changeText('statusText', 'Updating Languages .. done!');

changeStyle('zones', 'bold');
changeText('zonesMarker', '?');
changeText('statusText', 'Updating Zones');
//--></script>

<?php
  flush();

  osc_db_query("ALTER TABLE geo_zones CHANGE COLUMN geo_zone_id geo_zone_id int(11) NOT NULL auto_increment");
  osc_db_query("ALTER TABLE zones CHANGE COLUMN zone_country_id zone_country_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE zones CHANGE COLUMN zone_id zone_id int(11) NOT NULL auto_increment");
  osc_db_query("ALTER TABLE zones_to_geo_zones CHANGE COLUMN zone_country_id zone_country_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE zones_to_geo_zones CHANGE COLUMN association_id association_id int(11) NOT NULL auto_increment");
  osc_db_query("ALTER TABLE zones_to_geo_zones CHANGE COLUMN zone_id zone_id int(11) default NULL");
  osc_db_query("ALTER TABLE zones_to_geo_zones CHANGE COLUMN geo_zone_id geo_zone_id int(11) default NULL");
?>

<script language="javascript"><!--
changeStyle('zones', 'normal');
changeText('zonesMarker', '*');
changeText('statusText', 'Updating Zones .. done!');

changeStyle('manufacturers', 'bold');
changeText('manufacturersMarker', '?');
changeText('statusText', 'Updating Manufacturers');
//--></script>

<?php
  flush();

  osc_db_query("ALTER TABLE manufacturers CHANGE COLUMN manufacturers_id manufacturers_id int(11) NOT NULL auto_increment");
  osc_db_query("ALTER TABLE manufacturers_info CHANGE COLUMN manufacturers_id manufacturers_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE manufacturers_info CHANGE COLUMN languages_id languages_id int(11) NOT NULL default '0'");
?>

<script language="javascript"><!--
changeStyle('manufacturers', 'normal');
changeText('manufacturersMarker', '*');
changeText('statusText', 'Updating Manufacturers .. done!');

changeStyle('newsletters', 'bold');
changeText('newslettersMarker', '?');
changeText('statusText', 'Updating Newsletters');
//--></script>

<?php
  flush();

  osc_db_query("ALTER TABLE newsletters CHANGE COLUMN newsletters_id newsletters_id int(11) NOT NULL auto_increment");
?>

<script language="javascript"><!--
changeStyle('newsletters', 'normal');
changeText('newslettersMarker', '*');
changeText('statusText', 'Updating Newsletters .. done!');

changeStyle('orders', 'bold');
changeText('ordersMarker', '?');
changeText('statusText', 'Updating Orders');
//--></script>

<?php
  flush();

  osc_db_query("ALTER TABLE orders CHANGE COLUMN customers_id customers_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE orders CHANGE COLUMN orders_id orders_id int(11) NOT NULL auto_increment");
  osc_db_query("ALTER TABLE orders CHANGE COLUMN currency_value currency_value decimal(14,6) default NULL");
  osc_db_query("ALTER TABLE orders_products CHANGE COLUMN orders_products_id orders_products_id int(11) NOT NULL auto_increment");
  osc_db_query("ALTER TABLE orders_products CHANGE COLUMN products_id products_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE orders_products CHANGE COLUMN orders_id orders_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE orders_products CHANGE COLUMN products_tax products_tax decimal(7,4) NOT NULL default '0.0000'");
  osc_db_query("ALTER TABLE orders_products_attributes CHANGE COLUMN orders_products_id orders_products_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE orders_products_attributes CHANGE COLUMN orders_products_attributes_id orders_products_attributes_id int(11) NOT NULL auto_increment");
  osc_db_query("ALTER TABLE orders_products_attributes CHANGE COLUMN orders_id orders_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE orders_products_download CHANGE COLUMN orders_products_id orders_products_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE orders_products_download CHANGE COLUMN orders_products_download_id orders_products_download_id int(11) NOT NULL auto_increment");
  osc_db_query("ALTER TABLE orders_products_download CHANGE COLUMN orders_id orders_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE orders_status CHANGE COLUMN language_id language_id int(11) NOT NULL default '1'");
  osc_db_query("ALTER TABLE orders_status CHANGE COLUMN orders_status_id orders_status_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE orders_status_history CHANGE COLUMN orders_id orders_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE orders_status_history CHANGE COLUMN orders_status_history_id orders_status_history_id int(11) NOT NULL auto_increment");
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

  osc_db_query("ALTER TABLE products CHANGE COLUMN manufacturers_id manufacturers_id int(11) default NULL");
  osc_db_query("ALTER TABLE products CHANGE COLUMN products_tax_class_id products_tax_class_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE products CHANGE COLUMN products_id products_id int(11) NOT NULL auto_increment");
  osc_db_query("ALTER TABLE products CHANGE COLUMN products_weight products_weight decimal(5,2) NOT NULL default '0.00'");
  osc_db_query("ALTER TABLE products_attributes CHANGE COLUMN options_values_id options_values_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE products_attributes CHANGE COLUMN products_id products_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE products_attributes CHANGE COLUMN options_id options_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE products_attributes CHANGE COLUMN products_attributes_id products_attributes_id int(11) NOT NULL auto_increment");
  osc_db_query("ALTER TABLE products_attributes_download CHANGE COLUMN products_attributes_id products_attributes_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE products_description CHANGE COLUMN products_id products_id int(11) NOT NULL auto_increment");
  osc_db_query("ALTER TABLE products_description CHANGE COLUMN language_id language_id int(11) NOT NULL default '1'");
  osc_db_query("ALTER TABLE products_notifications CHANGE COLUMN customers_id customers_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE products_notifications CHANGE COLUMN products_id products_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE products_options CHANGE COLUMN language_id language_id int(11) NOT NULL default '1'");
  osc_db_query("ALTER TABLE products_options CHANGE COLUMN products_options_id products_options_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE products_options_values CHANGE COLUMN products_options_values_id products_options_values_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE products_options_values CHANGE COLUMN language_id language_id int(11) NOT NULL default '1'");
  osc_db_query("ALTER TABLE products_options_values_to_products_options CHANGE COLUMN products_options_values_to_products_options_id products_options_values_to_products_options_id int(11) NOT NULL auto_increment");
  osc_db_query("ALTER TABLE products_options_values_to_products_options CHANGE COLUMN products_options_values_id products_options_values_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE products_options_values_to_products_options CHANGE COLUMN products_options_id products_options_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE products_to_categories CHANGE COLUMN products_id products_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE products_to_categories CHANGE COLUMN categories_id categories_id int(11) NOT NULL default '0'");
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

  osc_db_query("ALTER TABLE reviews CHANGE COLUMN reviews_id reviews_id int(11) NOT NULL auto_increment");
  osc_db_query("ALTER TABLE reviews CHANGE COLUMN customers_id customers_id int(11) default NULL");
  osc_db_query("ALTER TABLE reviews CHANGE COLUMN products_id products_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE reviews_description CHANGE COLUMN reviews_id reviews_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE reviews_description CHANGE COLUMN languages_id languages_id int(11) NOT NULL default '0'");
?>

<script language="javascript"><!--
changeStyle('reviews', 'normal');
changeText('reviewsMarker', '*');
changeText('statusText', 'Updating Reviews .. done!');

changeStyle('specials', 'bold');
changeText('specialsMarker', '?');
changeText('statusText', 'Updating Specials');
//--></script>

<?php
  flush();

  osc_db_query("ALTER TABLE specials CHANGE COLUMN products_id products_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE specials CHANGE COLUMN specials_id specials_id int(11) NOT NULL auto_increment");
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

  osc_db_query("ALTER TABLE tax_class CHANGE COLUMN tax_class_id tax_class_id int(11) NOT NULL auto_increment");
  osc_db_query("ALTER TABLE tax_rates CHANGE COLUMN tax_rate tax_rate decimal(7,4) NOT NULL default '0.0000'");
  osc_db_query("ALTER TABLE tax_rates CHANGE COLUMN tax_rates_id tax_rates_id int(11) NOT NULL auto_increment");
  osc_db_query("ALTER TABLE tax_rates CHANGE COLUMN tax_zone_id tax_zone_id int(11) NOT NULL default '0'");
  osc_db_query("ALTER TABLE tax_rates CHANGE COLUMN tax_class_id tax_class_id int(11) NOT NULL default '0'");
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

  osc_db_query("ALTER TABLE whos_online CHANGE COLUMN customer_id customer_id int(11) default NULL");
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
