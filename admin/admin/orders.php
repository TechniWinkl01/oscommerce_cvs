<?php
/*
  $Id: orders.php,v 1.88 2002/03/17 17:46:56 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . $languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }

  switch ($HTTP_GET_VARS['action']) {
    case 'update_order':
      $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
      $status = tep_db_prepare_input($HTTP_POST_VARS['status']);
      $comments = tep_db_prepare_input($HTTP_POST_VARS['comments']);

      $order_updated = false;
      $check_status_query = tep_db_query("select customers_name, customers_email_address, orders_status, date_purchased from " . TABLE_ORDERS . " where orders_id = '" . tep_db_input($oID) . "'");
      $check_status = tep_db_fetch_array($check_status_query);
      if ($check_status['orders_status'] != $status) {
        tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . tep_db_input($status) . "', last_modified = now() where orders_id = '" . tep_db_input($oID) . "'");

        $customer_notified = '0';
        if ($HTTP_POST_VARS['notify'] == 'on') {
          $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL') . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n" . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);
          tep_mail($check_status['customers_name'], $check_status['customers_email_address'], EMAIL_TEXT_SUBJECT, nl2br($email), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '');
          $customer_notified = '1';
        }

        tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, new_value, old_value, date_added, customer_notified) values ('" . tep_db_input($oID) . "', '" . tep_db_input($status) . "', '" . $check_status['orders_status'] . "', now(), '" . $customer_notified . "')");

        $order_updated = true;
      }

      if (tep_not_null($comments)) {
        tep_db_query("update " . TABLE_ORDERS . " set comments = '" . tep_db_input($comments) . "' where orders_id = '" . tep_db_input($oID) . "'");
        $order_updated = true;
      }

      if ($order_updated) {
       $messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
      }

      tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=edit'));
      break;
    case 'deleteconfirm':
      $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);

      tep_remove_order($oID, $HTTP_POST_VARS['restock']);

      tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action'))));
      break;
  }

  if ( ($HTTP_GET_VARS['action'] == 'edit') && ($HTTP_GET_VARS['oID']) ) {
    $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);

    $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . tep_db_input($oID) . "'");
    $order_exists = true;
    if (!tep_db_num_rows($orders_query)) {
      $order_exists = false;
      $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if ( ($HTTP_GET_VARS['action'] == 'edit') && ($order_exists) ) {
    $orders_query = tep_db_query("select customers_telephone, customers_email_address, payment_method, cc_type, cc_owner, cc_number, cc_expires, date_purchased, orders_status from " . TABLE_ORDERS . " where orders_id = '" . tep_db_input($oID) . "'");
    $orders = tep_db_fetch_array($orders_query);
    $sold_to_query = tep_db_query("select customers_name as name, customers_street_address as street_address, customers_suburb as suburb, customers_city as city, customers_postcode as postcode, customers_state as state, customers_country as country, customers_address_format_id as format_id from " . TABLE_ORDERS . " where orders_id = '" . tep_db_input($oID) . "'");
    $sold_to = tep_db_fetch_array($sold_to_query);
    $ship_to_query = tep_db_query("select delivery_name as name, delivery_street_address as street_address, delivery_suburb as suburb, delivery_city as city, delivery_postcode as postcode, delivery_state as state, delivery_country as country, delivery_address_format_id as format_id from " . TABLE_ORDERS . " where orders_id = '" . tep_db_input($oID) . "'");
    $ship_to = tep_db_fetch_array($ship_to_query);
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="pageHeading" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="2"><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b><?php echo ENTRY_CUSTOMER; ?></b></td>
                <td class="main"><?php echo tep_address_format($sold_to['format_id'], $sold_to, 1, '&nbsp;', '<br>'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo ENTRY_TELEPHONE; ?></b></td>
                <td class="main"><?php echo $orders['customers_telephone']; ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td>
                <td class="main"><?php echo '<a href="mailto:' . $orders['customers_email_address'] . '"><u>' . $orders['customers_email_address'] . '</u></a>'; ?></td>
              </tr>
            </table></td>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b><?php echo ENTRY_DELIVERY_TO; ?></b></td>
                <td class="main"><?php echo tep_address_format($ship_to['format_id'], $ship_to, 1, '&nbsp;', '<br>'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
            <td class="main"><?php echo $orders['payment_method']; ?></td>
          </tr>
<?php
    if ( ($orders['cc_type']) || ($orders['cc_owner']) || ($orders['cc_number']) ) {
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_TYPE; ?></td>
            <td class="main"><?php echo $orders['cc_type']; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_OWNER; ?></td>
            <td class="main"><?php echo $orders['cc_owner']; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_NUMBER; ?></td>
            <td class="main"><?php echo $orders['cc_number']; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_EXPIRES; ?></td>
            <td class="main"><?php echo $orders['cc_expires']; ?></td>
          </tr>
<?php
    }
?>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_QUANTITY; ?></td>
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_INCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></td>
          </tr>
<?php
    $info_query = tep_db_query("select date_purchased, orders_status, last_modified, shipping_cost, shipping_method,comments from " . TABLE_ORDERS . " where orders_id = '" . tep_db_input($oID) . "'");
    $info = tep_db_fetch_array($info_query);
    $shipping = $info['shipping_cost'];
    $shipping_method = $info['shipping_method'];
    $products_query = tep_db_query("select orders_products_id, products_id, products_model, products_name, products_price, products_quantity, final_price, products_tax from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . tep_db_input($oID) . "'");
    $total_cost = 0;
    $total_tax = 0;
    while ($products = tep_db_fetch_array($products_query)) {
      $final_price = $products['final_price'];

      echo '      <tr class="dataTableRow">' . "\n" .
           '        <td class="dataTableContent" valign="top">' . $products['products_quantity'] . '</td>' . "\n" .
           '        <td class="dataTableContent" valign="top">' . $products['products_name'];

      $attributes_exist = false;
      $attributes_query = tep_db_query("select products_options, products_options_values, options_values_price, price_prefix from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . tep_db_input($oID) . "' and orders_products_id = '" . $products['orders_products_id'] . "'");
      if (tep_db_num_rows($attributes_query)) {
        $attributes_exist = true;
        while ($attributes = tep_db_fetch_array($attributes_query)) {
	  	    echo '<br><small><i>&nbsp;-&nbsp;' . $attributes['products_options'] . ':&nbsp;' . $attributes['products_options_values'];
            if ($attributes['options_values_price'] != '0') echo '&nbsp;(' . $attributes['price_prefix'] . tep_currency_format($attributes['options_values_price']) . ')';
            echo '</i></small>';
        }
      }
      echo '</td>' . "\n" .
           '        <td class="dataTableContent" valign="top">' . $products['products_model'] . '</td>' . "\n" .
           '        <td class="dataTableContent" align="right" valign="top">' . tep_display_tax_value($products['products_tax']) . '%</td>' . "\n" .
           '        <td class="dataTableContent" align="right" valign="top">' . tep_currency_format($products['final_price']) . '</td>' . "\n" .
           '        <td class="dataTableContent" align="right" valign="top">' . tep_currency_format(($products['final_price'] * $products['products_tax']/100) + $products['final_price']) . '</td>' . "\n" .
           '        <td class="dataTableContent" align="right" valign="top"><b>' . tep_currency_format($products['final_price'] * $products['products_quantity']) . '</b></td>' . "\n" .
           '        <td class="dataTableContent" align="right" valign="top"><b>' . tep_currency_format((($products['final_price'] * $products['products_quantity']) * $products['products_tax']/100) + ($products['final_price'] * $products['products_quantity'])) . '</b></td>' . "\n" .
           '      </tr>' . "\n";

      $cost = $products['final_price'] * $products['products_quantity'];

      $total_tax += $cost * $products['products_tax']/100;
      $total_cost += $cost;
    }
?>
          <tr>
            <td align="right" colspan="8"><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td align="right" class="smallText"><?php echo ENTRY_SUB_TOTAL; ?></td>
                <td align="right" class="smallText"><?php echo tep_currency_format($total_cost); ?></td>
              </tr>
              <tr>
                <td align="right" class="smallText"><?php echo ENTRY_TAX; ?></td>
                <td align="right" class="smallText"><?php echo tep_currency_format($total_tax); ?></td>
              </tr>
<?php
  if ($shipping != 0) {
?>
              <tr>
                <td align="right" class="smallText"><?php echo $shipping_method . ' ' . ENTRY_SHIPPING; ?></td>
                <td align="right" class="smallText"><?php echo tep_currency_format($shipping); ?></td>
              </tr>
<?php
  }
?>
              <tr>
                <td align="right" class="main"><b><?php echo ENTRY_TOTAL; ?></b></td>
                <td align="right" class="main"><b><?php echo tep_currency_format($total_cost + $total_tax + $shipping); ?></b></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('status', FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=update_order'); ?>
        <td class="main"><?php echo tep_draw_textarea_field('comments', 'soft', '60', '5', $info['comments']); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo ENTRY_STATUS; ?></b> <?php echo tep_draw_pull_down_menu('status', $orders_statuses, $info['orders_status']); ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo ENTRY_NOTIFY_CUSTOMER; ?></b> <?php echo tep_draw_checkbox_field('notify', '', true); ?></td>
              </tr>
            </table></td>
            <td valign="top"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
          </tr>
        </table></td>
      </form></tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><table border="1" cellspacing="0" cellpadding="5">
          <tr>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_NEW_VALUE; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_OLD_VALUE; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_DATE_ADDED; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></b></td>
          </tr>
<?php
    $orders_history_query = tep_db_query("select new_value, old_value, date_added, customer_notified from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . tep_db_input($oID) . "' order by orders_status_history_id desc");
    if (tep_db_num_rows($orders_history_query)) {
      while ($orders_history = tep_db_fetch_array($orders_history_query)) {
        echo '          <tr>' . "\n" .
             '            <td class="smallText">' . $orders_status_array[$orders_history['new_value']] . '</td>' . "\n" .
             '            <td class="smallText">' . (tep_not_null($orders_history['old_value']) ? $orders_status_array[$orders_history['old_value']] : '&nbsp;') . '</td>' . "\n" .
             '            <td class="smallText" align="center">' . tep_datetime_short($orders_history['date_added']) . '</td>' . "\n" .
             '            <td class="smallText" align="center">';
        if ($orders_history['customer_notified'] == '1') {
          echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK);
        } else {
          echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS);
        }
        echo '          </tr>' . "\n";
      }
    } else {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" colspan="4">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
             '          </tr>' . "\n";
    }
?>
        </table></td>
      </tr>
      <tr>
        <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
<?php
  } else {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr><?php echo tep_draw_form('orders', FILENAME_ORDERS, '', 'get'); ?>
                <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('oID', '', 'size="12"') . tep_draw_hidden_field('action', 'edit'); ?></td>
              </form></tr>
              <tr><?php echo tep_draw_form('status', FILENAME_ORDERS, '', 'get'); ?>
                <td class="smallText" align="right"><?php echo HEADING_TITLE_STATUS . ' ' . tep_draw_pull_down_menu('status', tep_array_merge(array(array('id' => '', 'text' => TEXT_ALL_ORDERS)), $orders_statuses), '', 'onChange="this.form.submit();"'); ?></td>
              </form></tr>            
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMERS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ORDER_TOTAL; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_DATE_PURCHASED; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    if ($HTTP_GET_VARS['cID']) {
      $cID = tep_db_prepare_input($HTTP_GET_VARS['cID']);
      $orders_query_raw = "select o.orders_id, o.customers_name, o.customers_id, o.payment_method, o.date_purchased, o.last_modified, o.shipping_cost, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . tep_db_input($cID) . "' and o.orders_status = s.orders_status_id and s.language_id = '" . $languages_id . "' order by orders_id DESC";
    } elseif ($HTTP_GET_VARS['status']) {
      $status = tep_db_prepare_input($HTTP_GET_VARS['status']);
      $orders_query_raw = "select o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.shipping_cost, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . $languages_id . "' and s.orders_status_id = '" . tep_db_input($status) . "' order by o.orders_id DESC";
    } else {
      $orders_query_raw = "select o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.shipping_cost, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . $languages_id . "' order by o.orders_id DESC";
    }
    $orders_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $orders_query_raw, $orders_query_numrows);
    $orders_query = tep_db_query($orders_query_raw);
    while ($orders = tep_db_fetch_array($orders_query)) {
      $total = 0;
      $orders_products_query = tep_db_query("select products_price, final_price, products_quantity, products_tax from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . $orders['orders_id'] . "'");
      while ($orders_products = tep_db_fetch_array($orders_products_query)) {
        $subtotal = ($orders_products['final_price'] * $orders_products['products_quantity']);
        $tax = $subtotal * ($orders_products['products_tax']/100);
        $total += $subtotal + $tax;
      }
      $total += $orders['shipping_cost'];

      if (((!$HTTP_GET_VARS['oID']) || ($HTTP_GET_VARS['oID'] == $orders['orders_id'])) && (!$oInfo)) {
        $oInfo = new objectInfo($orders);
      }

      if ( (is_object($oInfo)) && ($orders['orders_id'] == $oInfo->orders_id) ) {
        echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID')) . 'oID=' . $orders['orders_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders['orders_id'] . '&action=edit') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $orders['customers_name']; ?></td>
                <td class="dataTableContent" align="right"><?php echo tep_currency_format($total); ?></td>
                <td class="dataTableContent" align="center"><?php echo tep_datetime_short($orders['date_purchased']); ?></td>
                <td class="dataTableContent" align="right"><?php echo $orders['orders_status_name']; ?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($oInfo)) && ($orders['orders_id'] == $oInfo->orders_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID')) . 'oID=' . $orders['orders_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $orders_split->display_count($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                    <td class="smallText" align="right"><?php echo $orders_split->display_links($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'oID', 'action'))); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  switch ($HTTP_GET_VARS['action']) {
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_ORDER . '</b>');

      $contents = array('form' => tep_draw_form('orders', FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO . '<br><br><b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('restock') . ' ' . TEXT_INFO_RESTOCK_PRODUCT_QUANTITY);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($oInfo)) {
        $heading[] = array('text' => '<b>[' . $oInfo->orders_id . ']&nbsp;&nbsp;' . tep_datetime_short($oInfo->date_purchased) . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_DATE_ORDER_CREATED . ' ' . tep_date_short($oInfo->date_purchased));
        if (tep_not_null($oInfo->last_modified)) $contents[] = array('text' => TEXT_DATE_ORDER_LAST_MODIFIED . ' ' . tep_date_short($oInfo->last_modified));
        $contents[] = array('text' => '<br>' . TEXT_INFO_PAYMENT_METHOD . ' '  . $oInfo->payment_method);
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>