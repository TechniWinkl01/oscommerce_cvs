<?php
/*
  $Id: orders.php,v 1.65 2002/01/21 21:57:25 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  switch ($HTTP_GET_VARS['action']) {
    case 'update_order':
      $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
      $status = tep_db_prepare_input($HTTP_POST_VARS['status']);
      $comments = tep_db_prepare_input($HTTP_POST_VARS['comments']);

      $order_finish = ($status == '3') ? ', orders_date_finished = now()' : '';
      tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . tep_db_input($status) . "', last_modified = now()" . $order_finish . " where orders_id = '" . tep_db_input($oID) . "'");
      tep_db_query("update " . TABLE_ORDERS . " set comments = '" . tep_db_input($comments) . "' where orders_id = '" . tep_db_input($oID) . "'");
      tep_redirect(tep_href_link(FILENAME_ORDERS, 'oID=' . $HTTP_GET_VARS['oID']));
      break;
    case 'delete_order':
      tep_db_query("delete from " . TABLE_ORDERS . " where orders_id = '" . $HTTP_GET_VARS['orders_id_delete'] . "'");
      tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . $HTTP_GET_VARS['orders_id_delete'] . "'");
      tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . $HTTP_GET_VARS['orders_id_delete'] . "'");
      tep_redirect(tep_href_link(FILENAME_ORDERS));
      break;
  }

  if ($HTTP_GET_VARS['oID']) {
    $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);

    $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . tep_db_input($oID) . "'");
    $order_exists = true;
    if (!tep_db_num_rows($orders_query)) {
      $order_exists = false;
      $errorStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
    }
  }

  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . $languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_status_array[] = array('id' => $orders_status['orders_status_id'],
                                   'text' => $orders_status['orders_status_name']);
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
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr><?php echo tep_draw_form('orders', FILENAME_ORDERS, '', 'get'); ?>
                <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('oID'); ?></td>
              </form></tr>
              <tr><?php echo tep_draw_form('status', FILENAME_ORDERS, '', 'get'); ?>
                <td class="smallText" align="right"><?php echo HEADING_TITLE_STATUS . ' ' . tep_draw_pull_down_menu('status', tep_array_merge(array(array('id' => '', 'text' => TEXT_ALL_ORDERS)), $orders_status_array), '', 'onChange="this.form.submit();"'); ?></td>
              </form></tr>            
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  if ( ($HTTP_GET_VARS['oID']) && ($order_exists) ) {
    $orders_query = tep_db_query("select customers_telephone, customers_email_address, payment_method, cc_type, cc_owner, cc_number, cc_expires, date_purchased, orders_status from " . TABLE_ORDERS . " where orders_id = '" . tep_db_input($oID) . "'");
    $orders = tep_db_fetch_array($orders_query);
    $sold_to_query = tep_db_query("select customers_name as name, customers_street_address as street_address, customers_suburb as suburb, customers_city as city, customers_postcode as postcode, customers_state as state, customers_country as country, customers_address_format_id as format_id from " . TABLE_ORDERS . " where orders_id = '" . tep_db_input($oID) . "'");
    $sold_to = tep_db_fetch_array($sold_to_query);
    $ship_to_query = tep_db_query("select delivery_name as name, delivery_street_address as street_address, delivery_suburb as suburb, delivery_city as city, delivery_postcode as postcode, delivery_state as state, delivery_country as country, delivery_address_format_id as format_id from " . TABLE_ORDERS . " where orders_id = '" . tep_db_input($oID) . "'");
    $ship_to = tep_db_fetch_array($ship_to_query);
?>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="2"><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td class="main" valign="top"><b><?php echo TABLE_HEADING_CUSTOMERS_INFO; ?></b></td>
            <td class="main" valign="top"><b><?php echo TABLE_HEADING_DELIVERY_INFO; ?></b></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><?php echo ENTRY_CUSTOMER; ?></td>
                <td class="main"><?php echo tep_address_format($sold_to['format_id'], $sold_to, 1, '&nbsp;', '<br>'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_TELEPHONE; ?></td>
                <td class="main"><?php echo $orders['customers_telephone']; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
                <td class="main"><?php echo '<a href="mailto:' . $orders['customers_email_address'] . '"><u>' . $orders['customers_email_address'] . '</u></a>'; ?></td>
              </tr>
            </table></td>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><?php echo ENTRY_DELIVERY_TO; ?></td>
                <td class="main"><?php echo tep_address_format($ship_to['format_id'], $ship_to, 1, '&nbsp;', '<br>'); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main" colspan="2"><b><?php echo TABLE_HEADING_PAYMENT_INFORMATION; ?></b></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td colspan="2"><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><?php echo ENTRY_PAYMENT_METHOD; ?></td>
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
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="tableHeading"><?php echo TABLE_HEADING_QUANTITY; ?></td>
                <td class="tableHeading"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
                <td class="tableHeading"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
                <td class="tableHeading" align="center"><?php echo TABLE_HEADING_TAX; ?></td>
                <td class="tableHeading" align="right"><?php echo TABLE_HEADING_TOTAL; ?></td>
              </tr>
              <tr>
                <td colspan="5"><?php echo tep_draw_separator(); ?></td>
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

      echo '          <tr>' . "\n" .
           '            <td class="main">' . $products['products_quantity'] . '</td>' . "\n" .
           '            <td class="main">' . $products['products_model'] . '</td>' . "\n" .
           '            <td class="main">' . $products['products_name'];

      $attributes_exist = false;
      $attributes_query = tep_db_query("select products_options, products_options_values from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . tep_db_input($oID) . "' and orders_products_id = '" . $products['orders_products_id'] . "'");
      if (tep_db_num_rows($attributes_query)) {
        $attributes_exist = true;
        while ($attributes = tep_db_fetch_array($attributes_query)) {
	  	    echo '<br><small><i>&nbsp;-&nbsp;' . $attributes['products_options'] . '&nbsp;:&nbsp;' . $attributes['products_options_values'] . '</i></small>';
        }
      }
      echo '</td>' . "\n" .
           '            <td class="main" align="center" valign="top">' . tep_display_tax_value($products['products_tax']) . '%</td>' . "\n" .
           '            <td class="main" align="right" valign="top"><b>' . tep_currency_format($products['products_quantity'] * $products['products_price']) . '</b>';

      if ($attributes_exist) {
        $attributes_query = tep_db_query("select options_values_price, price_prefix from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . tep_db_input($oID) . "' and orders_products_id = '" . $products['orders_products_id'] . "'");
        while ($attributes = tep_db_fetch_array($attributes_query)) {
          if ($attributes['options_values_price'] != '0') {
            echo '<br><small><i>' . $attributes_values['price_prefix'] . tep_currency_format($products['products_quantity'] * $attributes_values['options_values_price']) . '</i></small>';
          } else {
            echo '<br>&nbsp;';
          }
        }
      }
      echo '</td>' . "\n" .
           '          </tr>' . "\n";

      $cost = $products['products_quantity'] * $final_price;
      if (TAX_INCLUDE == 'true') {
        $total_tax += (($products['products_quantity'] * $final_price) - (($products['products_quantity'] * $final_price) / (($products['products_tax']/100)+1)));
      } else {
        $total_tax += ($cost * $products['products_tax']/100);
      }
      $total_cost += $cost;
    }
?>
              <tr>
                <td colspan="5"><?php echo tep_draw_separator(); ?></td>
              </tr>
              <tr>
                <td align="right" colspan="5"><table border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td align="right" class="main"><?php echo ENTRY_SUB_TOTAL; ?></td>
                    <td align="right" class="main"><?php echo tep_currency_format($total_cost); ?></td>
                  </tr>
                  <tr>
                    <td align="right" class="main"><?php echo ENTRY_TAX; ?></td>
                    <td align="right" class="main"><?php echo tep_currency_format($total_tax); ?></td>
                  </tr>
<?php
  if ($shipping != 0) {
?>
                  <tr>
                    <td align="right" class="main"><?php echo $shipping_method . ' ' . ENTRY_SHIPPING; ?></td>
                    <td align="right" class="main"><?php echo tep_currency_format($shipping); ?></td>
                  </tr>
<?php
  }
?>
                  <tr>
                    <td align="right" class="main"><b><?php echo ENTRY_TOTAL; ?></b></td>
                    <td align="right" class="main"><b><?php if (TAX_INCLUDE == 'true') { echo tep_currency_format($total_cost + $shipping); } else { echo tep_currency_format($total_cost + $total_tax + $shipping); } ?></b></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="2" class="main"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr><?php echo tep_draw_form('status', FILENAME_ORDERS, 'oID=' . $HTTP_GET_VARS['oID'] . '&action=update_order'); ?>
            <td colspan="2" class="main"><?php echo tep_draw_textarea_field('comments', 'soft', '60', '5', $info['comments']); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td colspan="2" class="main"><b><?php echo TABLE_HEADING_STATUS; ?></b></td>
           </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td colspan="2" class="main"><b><?php echo ENTRY_DATE_PURCHASED; ?></b> <?php echo tep_date_long($info['date_purchased']); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td colspan="2" class="main"><b><?php echo ENTRY_STATUS; ?></b> <?php echo tep_draw_pull_down_menu('status', $orders_status_array, $info['orders_status']) . ' ' . tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
          </tr></form>
<?php
    if (@$info_values['last_modified']) {
?>
          <tr>
            <td colspan="2" class="main"><br><b>&nbsp;<?php echo ENTRY_DATE_LAST_UPDATED; ?></b> <?php echo tep_date_long($info_values['last_modified']); ?>&nbsp;</td>
          </tr>
<?php
    }
?>
          <tr>
             <td colspan="2"><br><?php echo tep_black_line(); ?></td>
          </tr>
          <form action="<?php echo tep_href_link(FILENAME_ORDERS, '', 'NONSSL'); ?>" method="get" onsubmit="return confirm('<?php echo IMAGE_CONFIRM; ?>')">
          <tr>
            <td colspan="2" align="right"><input type="hidden" name="action" value="delete_order"><input type="hidden" name="orders_id_delete" value="<?php echo $HTTP_GET_VARS['orders_id'] ?>"><?php echo tep_image_submit('button_delete.gif', IMAGE_DELETE); ?>&nbsp;&nbsp;<a href="<?php echo tep_href_link(FILENAME_ORDERS, '', 'NONSSL'); ?>"><?php echo tep_image_button('button_back.gif', IMAGE_BACK); ?></a>&nbsp;&nbsp;</td>
          </tr>
          </form>
          <tr>
            <td colspan="2" class="main">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
<?php
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="5"><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td class="tableHeading"><?php echo TABLE_HEADING_CUSTOMERS; ?></td>
            <td class="tableHeading" align="right"><?php echo TABLE_HEADING_ORDER_TOTAL; ?></td>
            <td class="tableHeading" align="right"><?php echo TABLE_HEADING_PAYMENT_METHOD; ?></td>
            <td class="tableHeading" align="right"><?php echo TABLE_HEADING_DATE_PURCHASED; ?></td>
            <td class="tableHeading" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
          </tr>
          <tr>
            <td colspan="5"><?php echo tep_draw_separator(); ?></td>
          </tr>
<?php
    if ($HTTP_GET_VARS['cID']) {
      $cID = tep_db_prepare_input($HTTP_GET_VARS['cID']);
      $orders_query_raw = "select o.orders_id, o.customers_name, o.customers_id, o.payment_method, o.date_purchased, o.shipping_cost, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . tep_db_input($cID) . "' and o.orders_status = s.orders_status_id and s.language_id = '" . $languages_id . "' order by orders_id DESC";
    } elseif ($HTTP_GET_VARS['status']) {
      $status = tep_db_prepare_input($HTTP_GET_VARS['status']);
      $orders_query_raw = "select o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.shipping_cost, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . $languages_id . "' and s.orders_status_id = '" . tep_db_input($status) . "' order by o.orders_id DESC";
    } else {
      $orders_query_raw = "select o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.shipping_cost, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . $languages_id . "' order by o.orders_id DESC";
    }
    $orders_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $orders_query_raw, $orders_query_numrows);
    $orders_query = tep_db_query($orders_query_raw);
    while ($orders = tep_db_fetch_array($orders_query)) {
      $total = 0;
      $orders_products_query = tep_db_query("select products_price, final_price, products_quantity, products_tax from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . $orders['orders_id'] . "'");
      while ($orders_products = tep_db_fetch_array($orders_products_query)) {
        $subtotal = ($orders_products['final_price'] * $orders_products['products_quantity']);
        $tax = $subtotal * ($orders_products['products_tax']/100);
        $total =+ $subtotal;
        if (TAX_INCLUDE == 'true') $total += $tax;
      }
      $total = $total + $orders['shipping_cost'];
?>
          <tr class="tableRow" onmouseover="this.className='tableRowOver';this.style.cursor='hand'" onmouseout="this.className='tableRow'" onclick="document.location.href='<?php echo tep_href_link(FILENAME_ORDERS, 'oID=' . $orders['orders_id']); ?>'">
            <td class="tableData"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, 'oID=' . $orders['orders_id']) . '">' . $orders['customers_name'] . '</a>'; ?></td>
            <td class="tableData" align="right"><?php echo tep_currency_format($total); ?></td>
            <td class="tableData" align="right"><?php echo $orders['payment_method']; ?></td>
            <td class="tableData" align="right"><?php echo tep_datetime_short($orders['date_purchased']); ?></td>
            <td class="tableData" align="right"><?php echo $orders['orders_status_name']; ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="5"><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText"><?php echo $orders_split->display_count($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' '; echo $orders_split->display_links($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
              </tr>
            </table></td>
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
