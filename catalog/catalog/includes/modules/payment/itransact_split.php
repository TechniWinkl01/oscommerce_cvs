<?
/*
  See README_catalog_itransact_split 
  iTransact Payment Module itransact_split.php
  Author: TriciaB (info@barestyle.com)
  File resides in: catalog/includes/modules/payment/
  This version is for TEP Preview Release 2.2 with mysql_catalog.sql version 1.116+.
*/

// random function for use in iTransact PGP signature.
    function create_rand ($len)  {
	$nps = "";
	mt_srand ((double) microtime() * 1000000);
	while (strlen($nps)<$len)     
	{ $c = chr(mt_rand (0,255));  
	if (eregi("^[0-9]$", $c)) $nps = $nps.$c; };
	return ($nps);
	}

  class itransact_split {
    var $code, $description, $enabled;

// class constructor
    function itransact_split() {
      $this->code = 'itransact_split';
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
      if ($this->enabled) {
          $checkout_form_action = MODULE_PAYMENT_ITRANSACT_SPLIT_CHECKOUT_FORM_ACTION;
        }
    }

/*
// this may be useful
    $products = $cart->get_products();
    for ($i=0; $i<sizeof($products); $i++) {
      $col_idx=0;
      $products_name = $products[$i]['name'];
      echo '          <tr>' . "\n";
      echo '            <td ' . $col_width[$col_idx++] . ' align="center" valign="top"><input type="checkbox" name="cart_delete[]" value="' . $products[$i]['id'] . '"></td>' . "\n";
      echo '            <td ' . $col_width[$col_idx++] . ' align="center" valign="top"><input type="text" name="cart_quantity[]" value="' . $products[$i]['quantity'] . '" maxlength="2" size="2"><input type="hidden" name="products_id[]" value="' . $products[$i]['id'] . '"></td>' . "\n";
      if (PRODUCT_LIST_MODEL) echo '            <td ' . $col_width[$col_idx++] . ' valign="top" class="main">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id'], 'NONSSL') . '">' . $products[$i]['model'] . '</a>&nbsp;</td>' . "\n";
      echo '            <td ' . $col_width[$col_idx++] . ' valign="top" class="main">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id'], 'NONSSL') . '"><b>' . $products_name . '</b></a>' . "\n";

      if (STOCK_CHECK) {
        echo check_stock($products[$i]['id'], $products[$i]['quantity']);
      }

//------display customer choosen option --------
      $attributes_exist = '0';
      if ($cart->contents[$products[$i]['id']]['attributes']) {
        $attributes_exist = '1';
        reset($cart->contents[$products[$i]['id']]['attributes']);
        while (list($option, $value) = each($cart->contents[$products[$i]['id']]['attributes'])) {
          $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $products[$i]['id'] . "' and pa.options_id = '" . $option . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $value . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
          $attributes_values = tep_db_fetch_array($attributes);
          echo "\n" . '<br><small><i>&nbsp;-&nbsp;' . $attributes_values['products_options_name'] . '&nbsp;:&nbsp;' . $attributes_values['products_options_values_name'] . '</i></small>';
          echo '<input type="hidden" name="id[' . $products[$i]['id'] . '][' . $option . ']" value="' . $value . '">';
        }
      }
//------display customer choosen option eof-----
      echo '</td>' . "\n";
      echo '            <td ' . $col_width[$col_idx++] . ' align="right" valign="top" class="main">&nbsp;<b>' . tep_currency_format($products[$i]['quantity'] * $products[$i]['price']) . '</b>&nbsp;';
//------display customer choosen option --------
      if ($attributes_exist == '1') {
        reset($cart->contents[$products[$i]['id']]['attributes']);
        while (list($option, $value) = each($cart->contents[$products[$i]['id']]['attributes'])) {
          $attributes = tep_db_query("select pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $products[$i]['id'] . "' and pa.options_id = '" . $option . "' and pa.options_values_id = '" . $value . "'");
          $attributes_values = tep_db_fetch_array($attributes);
          if ($attributes_values['options_values_price'] != '0') {
            echo "\n" . '<br><small><i>' . $attributes_values['price_prefix'] . tep_currency_format($products[$i]['quantity'] * $attributes_values['options_values_price']) . '</i></small>&nbsp;';
          } else {
            echo "\n" . '<br>&nbsp;';
          }
        }
      }
//------display customer choosen option eof-----
      echo '</td>' . "\n";
      echo '          </tr>' . "\n";
    }

*/


    function process_button() {
      global $HTTP_POST_VARS, $total_tax, $shipping_cost, $comments, $total_cost, $db_link;
      global $customer_id;		// from login.php - Necessary for tep_db_query below.
      global $products;			// from checkout_confirmation.php
      global $attributes_for_itransact;	// Added as a new array in checkout_confirmation.php
      //                                                                  //
      // NOTE: Please see the README regarding the global variables!      //
      //                                                                  //

      if ($this->enabled) {
	//                                                                //
	// Get customers_country.  Not available as a global variable.    //
	//                                                                //
// TriciaB - The TEP database structure for table "customers" was changed on 07/20/01.  The address info was moved to "address_book".  The other customer info remains here.  Two queries are needed to get the required info.
// (original - left here in case you want to use for earlier db versions)	$customer = tep_db_query("select customers_firstname, customers_lastname, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_zone_id, customers_country_id, customers_telephone, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
	$customer = tep_db_query("select customers_firstname, customers_lastname, customers_telephone, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
	$customer_values = tep_db_fetch_array($customer);
// use for earlier version	$customers_country = tep_get_countries($customer_values['customers_country_id']);

//	$address_book = tep_db_query("select entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_city, entry_postcode, entry_state, entry_zone_id, entry_country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $customer_id . "'");
	$address_book = tep_db_query("select * from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $customer_id . "'");
	$address_book_values = tep_db_fetch_array($address_book);
	$customers_country = tep_get_countries($address_book_values['entry_country_id']);

// TriciaB - Add entry to orders_itransact_auth with sig_rand, total and sesskey.  Used later for verifying authenticity of iTransact signature.
	$sig_rand = '';
	$sig_rand_query = tep_db_query("select sig_rand_begun, auth_id, status from orders_itransact_auth where sesskey_begun = '" . tep_session_id() . "' and orders_id is NULL and status = 'begun' order by auth_id DESC limit 1");
	$sig_rand_values = tep_db_fetch_array($sig_rand_query);
	$sig_rand = $sig_rand_values['sig_rand_begun'];
      if (!$sig_rand || $status == 'begun') {
	$sig_rand = create_rand(16);
	  tep_db_query("insert into orders_itransact_auth (customer_id, sig_rand_begun, gateway_id_begun, total_begun, sesskey_begun, status, datetime_begun) values ('" . $customer_id . "','" . $sig_rand . "', '" . MODULE_PAYMENT_ITRANSACT_SPLIT_GATEWAY_ID . "', '" . (number_format($total_cost + $total_tax + $shipping_cost, 2)) . "', '" . tep_session_id() . "', 'begun', now())");
	  $auth_id_query = tep_db_query("select auth_id from orders_itransact_auth where sesskey_begun = '" . tep_session_id() . "' and orders_id is NULL and status = 'begun' order by auth_id DESC limit 1");
	  $auth_id_values = tep_db_fetch_array($auth_id_query);
	  $auth_id = $auth_id_values['auth_id'];
      } else { 
	  tep_db_query("update orders_itransact_auth set customer_id = '" . $customer_id . "', total_begun = '" . (number_format($total_cost + $total_tax + $shipping_cost, 2)) . "', datetime_begun = now(), status = 'begun', gateway_id_begun = '" . MODULE_PAYMENT_ITRANSACT_SPLIT_GATEWAY_ID . "' where sig_rand_begun = '" . $sig_rand . "'");
	  $auth_id = $sig_rand_values['auth_id'];
	  }
?>

	<!-- Include passback variables -->
	<input type="hidden" name="<? echo tep_session_name(); ?>" value="<? echo tep_session_id(); ?>">
 	<input type="hidden" name="passback[]" value="prod">
	<input type="hidden" name="passback[]" value="sendto">
	<input type="hidden" name="passback[]" value="payment">
	<input type="hidden" name="passback[]" value="comments">
	<input type="hidden" name="passback[]" value="shipping_cost">
	<input type="hidden" name="passback[]" value="shipping_method">
	<input type="hidden" name="passback[]" value="vendor_id">
	<input type="hidden" name="passback[]" value="<? echo tep_session_name(); ?>">
	<input type="hidden" name="passback[]" value="sig_rand">
	<input type="hidden" name="passback[]" value="auth_id">
	<input type="hidden" name="sig_rand" value="<? echo $sig_rand; ?>">
	<input type="hidden" name="auth_id" value="<? echo $auth_id; ?>">

	<!-- Include lookups -->
	<input type="hidden" name="lookup[]" value="xid">
	<input type="hidden" name="lookup[]" value="authcode">
	<input type="hidden" name="lookup[]" value="avs_response">
	<input type="hidden" name="lookup[]" value="when">
	<input type="hidden" name="lookup[]" value="total">
	<input type="hidden" name="lookup[]" value="cc_last_four">
	<input type="hidden" name="lookup[]" value="test_mode">

	<!-- Include TEP variables for Split Form layout -->
	<input type="hidden" name="header_title" value="<? echo TITLE; ?>">
	<input type="hidden" name="header_title_my_account" value="<? echo HEADER_TITLE_MY_ACCOUNT; ?>">
	<input type="hidden" name="header_title_cart_contents" value="<? echo HEADER_TITLE_CART_CONTENTS; ?>">
	<input type="hidden" name="header_title_checkout" value="<? echo HEADER_TITLE_CHECKOUT; ?>">
	<input type="hidden" name="header_title_top" value="<? echo HEADER_TITLE_TOP; ?>">
	<input type="hidden" name="header_title_catalog" value="<? echo HEADER_TITLE_CATALOG; ?>">
	<input type="hidden" name="header_title_login" value="<? echo HEADER_TITLE_LOGIN; ?>">
<?
	if (MODULE_PAYMENT_ITRANSACT_SPLIT_CARDS <> 0) {
	   echo "	<input type=\"hidden\" name=\"header_accept_cards\" value=\"1\">" . "\n";
	}
	if (MODULE_PAYMENT_ITRANSACT_SPLIT_EFT <> 0) {
	   echo "	<input type=\"hidden\" name=\"header_accept_eft\" value=\"1\">" . "\n";
	}
?>

<?	// Format description, cost, and quantity for each item.  These are used in the email //
	// sent by iTransact, and are required to determine the transaction total.            //
	// This uses global $products.                                                        //
	echo "	<!-- Description, cost, quantity, attributes for each item-->" . "\n";
	   for ($i=0; $i<sizeof($products); $i++) {
		$item_num = $i; $item_num++;
		$products_name = $products[$i]['name'];
		$products_price = $products[$i]['price'];
		$products_quantity = $products[$i]['quantity'];
		$products_options_name = $attributes_values[$i]['products_options_name'];
	   	echo "	<input type=\"hidden\" name=\"item_" . $item_num . "_desc\" value=\"" . $products_name . "\">" . "\n";
	   	echo "	<input type=\"hidden\" name=\"item_" . $item_num . "_cost\" value=\"" . $products_price . "\">" . "\n";
	   	echo "	<input type=\"hidden\" name=\"item_" . $item_num . "_qty\" value=\"" . $products_quantity . "\">" . "\n";

	// Check for product attributes.  If they exist, format them for each item. as above. //
	if ($attributes_for_itransact) {
	   for ($num=0; $num<sizeof($attributes_for_itransact); $num++) {
		$item_num = $i; $item_num++;
		$attrib_name = $attributes_for_itransact['name'];
		$attrib_value = $attributes_for_itransact['value'];
		   if ($attrib_value[$i . $num]) {
		   	echo "	<input type=\"hidden\" name=\"item_" . $item_num . "_" . $attrib_name[$i . $num] . "\" value=\"" . $attrib_value[$i . $num] . "\">" . "\n";
			}
		}
     	}
   }

	if ($shipping_cost) {
	   echo "	\n";
	   echo "	<!-- Line Item for Shipping -->" . "\n";
	   echo "	<input type=\"hidden\" name=\"98_desc\" value=\"Shipping\">" . "\n";
	   echo "	<input type=\"hidden\" name=\"98_cost\" value=\"" . number_format($shipping_cost,2) . "\">" . "\n";
	   echo "	<input type=\"hidden\" name=\"98_qty\" value=\"1\">" . "\n";
	   }

	if ($total_tax) {
	   echo "	\n";
	   echo "	<!-- Line Item for Tax -->" . "\n";
	   echo "	<input type=\"hidden\" name=\"99_desc\" value=\"Tax\">" . "\n";
	   echo "	<input type=\"hidden\" name=\"99_cost\" value=\"" . number_format($total_tax,2) . "\">" . "\n";
	   echo "	<input type=\"hidden\" name=\"99_qty\" value=\"1\">" . "\n";
	   }

?>

	<!-- Required merchant and customer variables -->
	<input type="hidden" name="vendor_id" value="<? echo MODULE_PAYMENT_ITRANSACT_SPLIT_GATEWAY_ID; ?>">
	<input type="hidden" name="home_page" value="<? echo HTTP_SERVER; ?>">
	<input type="hidden" name="ret_addr" value="<? echo MODULE_PAYMENT_ITRANSACT_RETURN_ADDRESS; ?>">

<?	if (MODULE_PAYMENT_ITRANSACT_RETURN_MODE == 'post' ||
	    MODULE_PAYMENT_ITRANSACT_RETURN_MODE == 'redirect') {
	   echo "	<input type=\"hidden\" name=\"ret_mode\" value=\"" . MODULE_PAYMENT_ITRANSACT_RETURN_MODE . "\">" . "\n";
	   }
?>
<?	// This will be used for future versions.  Just leave as-is.  //
	if (MODULE_PAYMENT_ITRANSACT_ON_ERROR == "1") {
	   echo "	<input type=\"hidden\" name=\"post_back_on_error\" value=\"1\">" . "\n";
	   }
?>
	<input type="hidden" name="email_text" value="<? echo $comments ?>">
	<input type="hidden" name="first_name" value="<? echo $address_book_values['entry_firstname']; ?>">
	<input type="hidden" name="last_name" value="<? echo $address_book_values['entry_lastname']; ?>">
	<input type="hidden" name="address" value="<? echo $address_book_values['entry_street_address']; ?>">
	<input type="hidden" name="city" value="<? echo $address_book_values['entry_city']; ?>">
	<input type="hidden" name="zip" value="<? echo $address_book_values['entry_postcode']; ?>">
	<input type="hidden" name="country" value="<? echo tep_get_country_name($address_book_values['entry_country_id']); ?>">
	<input type="hidden" name="email" value="<? echo $customer_values['customers_email_address']; ?>">
	<input type="hidden" name="phone" value="<? echo $customer_values['customers_telephone']; ?>">
<? 
	// Include the state.  This is messy, but necessary because of how TEP handles states and zones. //
	$state = tep_get_zone_code($address_book_values['entry_country_id'], $address_book_values['entry_zone_id'],"");
	if (!$state) $state = $address_book_values['entry_state'];
	   echo "	<input type=\"hidden\" name=\"state\" value=\"" . $state . "\">" . "\n";
	   echo "	\n";

	// Create hidden inputs for card images on Split Form //
	if (MODULE_PAYMENT_ITRANSACT_SPLIT_VISAMC == '1') {
	   echo "	<input type=\"hidden\" name=\"header_visa_image\" value=\"1\">" . "\n";
	   echo "	<input type=\"hidden\" name=\"header_mc_image\" value=\"1\">" . "\n";
	   }
	if (MODULE_PAYMENT_ITRANSACT_SPLIT_AMEX == '1') {
	   echo "	<input type=\"hidden\" name=\"header_amex_image\" value=\"1\">" . "\n";
	   }
	if (MODULE_PAYMENT_ITRANSACT_SPLIT_DISC == '1') {
	   echo "	<input type=\"hidden\" name=\"header_disc_image\" value=\"1\">" . "\n";
	   }
	if (MODULE_PAYMENT_ITRANSACT_SPLIT_DINER == '1') {
	   echo "	<input type=\"hidden\" name=\"header_diner_image\" value=\"1\">" . "\n";
	   }
	   echo "	\n";
	}
        return false;
    }

// FOR FUTURE USE
// There are three possible responses from iTransact for a transaction.  This function will handle all three.
// However, there's no point right now to handle errors and dies.  These are handled only if iTransact's
// service is being used "transparently" which requires a secure server of your own.  Since this module
// is for the Split Form, a secure server isn't being used on my end, and all errors and dies will be handled
// by iTransact's secure server.
// 1.  Success - This will include the authcode, since it is included above as a Lookup variable.
// 2.  Error - This is basically a decline.  The error message is reported.
// 3.  Die - Something bad happened.  (internal error)

    function before_process() {
      global $payment, $authcode, $err, $die;

      if ( ($payment == $this->code) && (($die == "1") || ($err)) ) {
		if ($die == "1") {
		   Header('Location: ' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(MODULE_PAYMENT_ITRANSACT_SPLIT_TEXT_DIE_MESSAGE), 'SSL'));
		   }
		if ($err) {
		   Header('Location: ' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode($err), 'SSL'));
		   }
        tep_exit();
      }

    }

    function after_process() {
      global $HTTP_POST_VARS, $customer_id, $insert_id;
 // TriciaB - Update orders_itransact_auth.  First, check to see if the order is a dup.  This would happen if someone deliberately pressed the back button and reloaded the page, causing another transaction to go through iTransact.
      $find_dup = tep_db_query("select sig_rand_complete from orders_itransact_auth where sig_rand_complete = '" . $HTTP_POST_VARS['sig_rand'] . "' and sesskey_complete = '" . $HTTP_POST_VARS['PHPSESSID'] . "' and auth_id = '" . $HTTP_POST_VARS['auth_id'] . "' and status = 'complete'");
      if (tep_db_num_rows($find_dup) > 0) { 
      	    tep_db_query("insert into orders_itransact_auth (customer_id, status, orders_id, gateway_id_complete, authcode, datetime_itransact_timestamp, total_complete, cc_last_four, xid, test_mode, avs_response, signature, sig_rand_complete, sesskey_complete) values ('" . $customer_id . "', 'duplicate', '" . $insert_id . "', '" . $HTTP_POST_VARS['vendor_id'] . "', '" . $HTTP_POST_VARS['authcode'] . "', '" . $HTTP_POST_VARS['when'] . "','" . $HTTP_POST_VARS['total'] . "', '" . $HTTP_POST_VARS['cc_last_four'] . "', '" . $HTTP_POST_VARS['xid'] . "', '" . $HTTP_POST_VARS['test_mode'] . "', '" . $HTTP_POST_VARS['avs_response'] . "', '" . $HTTP_POST_VARS['signature'] . "', '" . $HTTP_POST_VARS['sig_rand'] . "', '" . tep_session_id() . "' )");
      }
      else {
	echo "<br>insert id: " . $insert_id;
      	    tep_db_query("update orders_itransact_auth set status = 'complete', orders_id = '" . $insert_id . "', gateway_id_complete = '" . $HTTP_POST_VARS['vendor_id'] . "', authcode = '" . $HTTP_POST_VARS['authcode'] . "', datetime_itransact_timestamp = '" . $HTTP_POST_VARS['when'] . "', total_complete = '" . $HTTP_POST_VARS['total'] . "', cc_last_four = '" . $HTTP_POST_VARS['cc_last_four'] . "', xid = '" . $HTTP_POST_VARS['xid'] . "', test_mode = '" . $HTTP_POST_VARS['test_mode'] . "', avs_response = '" . $HTTP_POST_VARS['avs_response'] . "', signature = '" . $HTTP_POST_VARS['signature'] . "', sesskey_complete = '" . $HTTP_POST_VARS['PHPSESSID'] . "', sig_rand_complete = '" . $HTTP_POST_VARS['sig_rand'] . "' where auth_id = '" . $HTTP_POST_VARS['auth_id'] . "' and sig_rand_begun = '" . $HTTP_POST_VARS['sig_rand'] . "'");
           }
	?>
<html>
<head>
<!meta http-equiv="refresh" content="<? echo MODULE_PAYMENT_ITRANSACT_SPLIT_CHECKOUT_PROCESS_DELAY_SECONDS; ?>; url=<? echo tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL'); ?>">
<title><? echo TITLE; ?></title>
<base href="<? echo (ENABLE_SSL ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?
   $ss_file = "https://secure.itransact.com/tep/checkout/" . MODULE_PAYMENT_ITRANSACT_SPLIT_GATEWAY_ID . "/stylesheet.css";
   if(file_exists($ss_file)) {
      echo "<link rel=\"stylesheet\" type=\"text/css\" href=" . $ss_file . ">";
   }
   else echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://secure.itransact.com/tep/stylesheet.css\">";
?>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<center>
&nbsp;
<br>
<!-- header //-->
<table width="65%" cellspacing="0" cellpadding="0">
<td width="100%" valign="top">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td width="100%" class="topBarTitle">&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
</table>
</td>
</table>
<!-- header_eof //-->

<!-- body //-->
<P>

<table border="0" width="65%" cellspacing="0" cellpadding="2">
      <tr>
        <td><img src="https://secure.itransact.com/tep/images/pixel_black.gif" border="0" alt="  " width="100%" height="1"></td>
      </tr>
      <tr class="payment-odd">
        <TD><? echo MODULE_PAYMENT_ITRANSACT_SPLIT_CHECKOUT_PROCESS_TEXT; ?></TD>
      </TR>
      <tr class="payment-even">
        <td align=center><BR><a href="<? echo tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL'); ?>">CLICK HERE</a></td>
      </tr>
      <tr>
        <td><img src="https://secure.itransact.com/tep/images/pixel_black.gif" border="0" alt="  " width="100%" height="1"></td>
      </tr>
</TABLE>
<P>
<!-- body_eof //-->

<? $include_file = DIR_WS_INCLUDES . 'application_bottom.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
</body>
</html>
<?
        tep_exit();
    }

    function output_error() {
      return false;
    }

    function check() {
      $check = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ITRANSACT_SPLIT_STATUS'");
      $check = tep_db_num_rows($check);

      return $check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enable iTransact module', 'MODULE_PAYMENT_ITRANSACT_SPLIT_STATUS', '1', 'Enter 1 to accept iTransact payments using the secure Split Form?', '1', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('iTransact Gateway ID', 'MODULE_PAYMENT_ITRANSACT_SPLIT_GATEWAY_ID', '', 'Five-digit iTransact Gateway ID', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Credit Cards', 'MODULE_PAYMENT_ITRANSACT_SPLIT_CARDS', '', 'Enter 1 if you are accepting credit card payments', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Checks/EFT', 'MODULE_PAYMENT_ITRANSACT_SPLIT_EFT', '', 'Enter 1 if you are accepting checks/EFT payments', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Visa & Mastercard', 'MODULE_PAYMENT_ITRANSACT_SPLIT_VISAMC', '', 'Enter 1 to display these images', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('American Express', 'MODULE_PAYMENT_ITRANSACT_SPLIT_AMEX', '', 'Enter 1 to display this image', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Discover', 'MODULE_PAYMENT_ITRANSACT_SPLIT_DISC', '', 'Enter 1 to display this image', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Diners Club', 'MODULE_PAYMENT_ITRANSACT_SPLIT_DINER', '', 'Enter 1 to display this image', '6', '0', now())");
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
      $keys = array('MODULE_PAYMENT_ITRANSACT_SPLIT_STATUS', 'MODULE_PAYMENT_ITRANSACT_SPLIT_GATEWAY_ID', 'MODULE_PAYMENT_ITRANSACT_SPLIT_CARDS', 'MODULE_PAYMENT_ITRANSACT_SPLIT_EFT', 'MODULE_PAYMENT_ITRANSACT_SPLIT_VISAMC', 'MODULE_PAYMENT_ITRANSACT_SPLIT_AMEX', 'MODULE_PAYMENT_ITRANSACT_SPLIT_DISC', 'MODULE_PAYMENT_ITRANSACT_SPLIT_DINER');       return $keys;
    }

  }
?>
