<?php
/*
  $Id: update.php,v 1.2 2001/10/25 06:50:40 hpdl Exp $

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

changeStyle('statusText', 'bold');
changeText('statusText', 'Update Complete!');
//--></script>
