<?php
/*
  $Id: default.php,v 1.36 2002/01/05 10:53:08 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $limits_array = array('5', '10', '20', '30');

  $limits = array();
  for ($i=0; $i<sizeof($limits_array); $i++) {
    $limits[] = array('id' => $limits_array[$i], 'text' => $limits_array[$i]);
  }

  if (!$HTTP_GET_VARS['limit']) {
    $limit = 10;
  } else {
    $limit = tep_db_prepare_input($HTTP_GET_VARS['limit']);
  }

  if (!tep_in_array($limit, $limits_array)) $limit = 10;
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
          <tr><?php echo tep_draw_form('limit', FILENAME_DEFAULT, '', 'get'); ?>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', HEADING_IMAGE_HEIGHT); ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_pull_down_menu('limit', $limits, '', 'onChange="this.form.submit();"'); ?></td>
          </form></tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td width="50%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td colspan="2"><?php echo tep_draw_separator(); ?></td>
              </tr>
              <tr class="subBar">
                <td colspan="2" class="subBar"><?php echo TABLE_HEADING_NEW_CUSTOMERS; ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator(); ?></td>
              </tr>
<?php
  $customers_query = tep_db_query("select c.customers_id, c.customers_firstname, c.customers_lastname, i.customers_info_date_account_created from " . TABLE_CUSTOMERS . " c, " . TABLE_CUSTOMERS_INFO . " i where c.customers_id = i.customers_info_id order by c.customers_id DESC limit " . tep_db_input($limit));
  $rows = 0;
  while ($customers = tep_db_fetch_array($customers_query)) {
    $rows++;
    echo '              <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CUSTOMERS, 'search=' . addslashes($customers['customers_lastname']) . '&origin=' . FILENAME_DEFAULT) . '\'">' . "\n" .
         '                <td class="smallText"><a href="' . tep_href_link(FILENAME_CUSTOMERS, 'search=' . $customers['customers_lastname'] . '&origin=' . FILENAME_DEFAULT) . '" class="blacklink">'. $customers['customers_firstname'] . ' ' . $customers['customers_lastname'] . '</a></td>' . "\n" .
         '                <td align="right" class="smallText">' . tep_datetime_short($customers['customers_info_date_account_created']) . '</td>' . "\n" .
         '              </tr>' . "\n";
  }

  if ($rows < $limit) {
    for ($i=$rows; $i<$limit; $i++) {
      echo '              <tr class="tableRow">' . "\n" .
           '                <td class="smallText" colspan="2">&nbsp;</td>' . "\n" .
           '              </tr>' . "\n";
    }
  }
?>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator(); ?></td>
              </tr>
            </table></td>
            <td width="50%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td colspan="3"><?php echo tep_draw_separator(); ?></td>
              </tr>
<?php
  $orders_status_query = tep_db_query("select orders_status_name from orders_status where orders_status_id = '1' and language_id = '" . $languages_id . "'");
  $orders_status = tep_db_fetch_array($orders_status_query);
  $orders_pending_query = tep_db_query("select count(*) as count from orders where orders_status = '1'");
  $orders_pending = tep_db_fetch_array($orders_pending_query);
?>
              <tr class="subBar">
                <td colspan="3" class="subBar"><?php echo TABLE_HEADING_LAST_ORDERS . ' (' . $orders_status['orders_status_name'] . ': ' . $orders_pending['count'] . ')'; ?></td>
              </tr>
              <tr>
                <td colspan="3"><?php echo tep_draw_separator(); ?></td>
              </tr>
<?php
  $orders_query = tep_db_query("select orders_id, customers_name, date_purchased, shipping_cost from " . TABLE_ORDERS . " order by orders_id DESC limit " . tep_db_input($limit));
  $rows = 0;
  while ($orders = tep_db_fetch_array($orders_query)) {
    $rows++;
    $total_cost = 0;
    $orders_products_query = tep_db_query("select final_price, products_quantity, products_tax from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . $orders['orders_id'] . "'");
    while ($orders_products = tep_db_fetch_array($orders_products_query)) {
      if (TAX_INCLUDE == true) {
        $total_cost += $orders_products['final_price'] * $orders_products['products_quantity'];
      } else {
        $total_cost += ($orders_products['final_price'] + ($orders_products['final_price'] * ($orders_products['products_tax']/100))) * $orders_products['products_quantity'];
      }
    }
    $total_cost += $orders['shipping_cost'];

    echo '              <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS, 'orders_id=' . $orders['orders_id']) . '\'">' . "\n" .
         '                <td class="smallText"><a href="' . tep_href_link(FILENAME_ORDERS, 'orders_id=' . $orders['orders_id']) . '" class="blacklink">' . $orders['customers_name'] . '</a></td>' . "\n" .
         '                <td class="smallText">' . tep_currency_format($total_cost) . '</td>' . "\n" .
         '                <td align="right" class="smallText">' . tep_datetime_short($orders['date_purchased']) . '</td>' . "\n" .
         '              </tr>' . "\n";
  }

  if ($rows < $limit) {
    for ($i=$rows; $i<$limit; $i++) {
      echo '              <tr class="tableRow">' . "\n" .
           '                <td class="smallText" colspan="3">&nbsp;</td>' . "\n" .
           '              </tr>' . "\n";
    }
  }
?>
              <tr>
                <td colspan="3"><?php echo tep_draw_separator(); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td width="50%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td colspan="2"><?php echo tep_draw_separator(); ?></td>
              </tr>
              <tr class="subBar">
                <td colspan="2" class="subBar"><?php echo TABLE_HEADING_NEW_PRODUCTS; ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator(); ?></td>
              </tr>
<?php
  $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_date_added from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '$languages_id' order by p.products_id DESC limit " . tep_db_input($limit));
  $rows = 0;
  while ($products = tep_db_fetch_array($products_query)) {
    $rows++;
    echo '              <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'action=new_product_preview&read=only&pID=' . $products['products_id'] . '&origin=' . FILENAME_DEFAULT) . '\'">' . "\n" .
         '                <td class="smallText"><a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=new_product_preview&read=only&pID=' . $products['products_id'] . '&origin=' . FILENAME_DEFAULT) . '" class="blacklink">' . $products['products_name'] . '</a></td>' . "\n" .
         '                <td align="right" class="smallText">' . tep_datetime_short($products['products_date_added']) . '</td>' . "\n" .
         '              </tr>' . "\n";
  }

  if ($rows < $limit) {
    for ($i=$rows; $i<$limit; $i++) {
      echo '              <tr class="tableRow">' . "\n" .
           '                <td class="smallText" colspan="2">&nbsp;</td>' . "\n" .
           '              </tr>' . "\n";
    }
  }
?>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator(); ?></td>
              </tr>
            </table></td>
            <td width="50%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td colspan="2"><?php echo tep_draw_separator(); ?></td>
              </tr>
              <tr class="subBar">
                <td colspan="2" class="subBar"><?php echo TABLE_HEADING_NEW_REVIEWS; ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator(); ?></td>
              </tr>
<?php
  $reviews_query = tep_db_query("select r.reviews_id, pd.products_name, r.date_added from " . TABLE_REVIEWS . " r, " . TABLE_PRODUCTS_DESCRIPTION . " pd where r.products_id = pd.products_id and pd.language_id='$languages_id' order by reviews_id DESC limit " . tep_db_input($limit));
  $rows = 0;
  while ($reviews = tep_db_fetch_array($reviews_query)) {
    $rows++;
    echo '              <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_REVIEWS, 'action=preview&rID=' . $reviews['reviews_id'] . '&origin=' . FILENAME_DEFAULT) . '\'">' . "\n" .
         '                <td class="smallText"><a href="' . tep_href_link(FILENAME_REVIEWS, 'action=preview&rID=' . $reviews['reviews_id'] . '&origin=' . FILENAME_DEFAULT) . '" class="blacklink">' . $reviews['products_name'] . '</a></td>' . "\n" .
         '                <td align="right" class="smallText">' . tep_datetime_short($reviews['date_added']) . '</td>' . "\n" .
         '              </tr>' . "\n";
  }

  if ($rows < $limit) {
    for ($i=$rows; $i<$limit; $i++) {
      echo '              <tr class="tableRow">' . "\n" .
           '                <td class="smallText" colspan="2">&nbsp;</td>' . "\n" .
           '              </tr>' . "\n";
    }
  }
?>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator(); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
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