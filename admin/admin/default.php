<? include('includes/application_top.php'); ?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body>
<!-- header //-->
<? $include_file = DIR_WS_INCLUDES . 'header.php';  include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_WS_INCLUDES . 'column_left.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td class="topBarTitle">&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right">&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'table_background_button.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
          <tr class="subBar">
            <td class="subBar">&nbsp;<? echo SUB_BAR_TITLE; ?>&nbsp;</td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td class="main"><? echo TEXT_MAIN; ?></td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td width="50%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td colspan="2"><? echo tep_black_line(); ?></td>
              </tr>
              <tr class="subBar">
                <td colspan="2" class="subBar">&nbsp;<? echo TABLE_HEADING_NEW_CUSTOMERS; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2"><? echo tep_black_line(); ?></td>
              </tr>
<?
  $customers_query = tep_db_query("select c.customers_id, c.customers_firstname, c.customers_lastname, i.customers_info_date_account_created from " . TABLE_CUSTOMERS . " c, " . TABLE_CUSTOMERS_INFO . " i where c.customers_id = i.customers_info_id order by c.customers_id DESC limit 5");
  while ($customers = tep_db_fetch_array($customers_query)) {
    echo '              <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CUSTOMERS, 'search=' . addslashes($customers['customers_lastname']) . '&origin=' . FILENAME_DEFAULT, 'NONSSL') . '\'">' . "\n";
    echo '                <td class="smallText">&nbsp;<a href="' . tep_href_link(FILENAME_CUSTOMERS, 'search=' . $customers['customers_lastname'] . '&origin=' . FILENAME_DEFAULT, 'NONSSL') . '" class="blacklink">'. $customers['customers_firstname'] . ' ' . $customers['customers_lastname'] . '</a>&nbsp;</td>' . "\n";
    echo '                <td align="right" class="smallText">&nbsp;' . tep_datetime_short($customers['customers_info_date_account_created']) . '&nbsp;</td>' . "\n";
    echo '              </tr>' . "\n";
  }
?>
              <tr>
                <td colspan="2"><? echo tep_black_line(); ?></td>
              </tr>
            </table></td>
            <td width="50%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td colspan="3"><? echo tep_black_line(); ?></td>
              </tr>
              <tr class="subBar">
                <td colspan="3" class="subBar">&nbsp;<? echo TABLE_HEADING_LAST_ORDERS; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3"><? echo tep_black_line(); ?></td>
              </tr>
<?
  $orders_query = tep_db_query("select orders_id, customers_name, date_purchased, orders_status, shipping_cost from " . TABLE_ORDERS . " order by orders_id DESC limit 5");
  while ($orders = tep_db_fetch_array($orders_query)) {
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

    echo '              <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS, 'orders_id=' . $orders['orders_id'], 'NONSSL') . '\'">' . "\n";
    echo '                <td class="smallText">&nbsp;<a href="' . tep_href_link(FILENAME_ORDERS, 'orders_id=' . $orders['orders_id'], 'NONSSL') . '" class="blacklink">' . $orders['customers_name'] . '</a>&nbsp;</td>' . "\n";
    echo '                <td class="smallText">&nbsp;' . tep_currency_format($total_cost) . '&nbsp;</td>' . "\n";
    echo '                <td align="right" class="smallText">&nbsp;' . tep_datetime_short($orders['date_purchased']) . '&nbsp;</td>' . "\n";
    echo '              </tr>' . "\n";
  }
?>
              <tr>
                <td colspan="3"><? echo tep_black_line(); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td width="50%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td colspan="2"><? echo tep_black_line(); ?></td>
              </tr>
              <tr class="subBar">
                <td colspan="2" class="subBar">&nbsp;<? echo TABLE_HEADING_NEW_PRODUCTS; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2"><? echo tep_black_line(); ?></td>
              </tr>
<?
  $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_date_added from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '$languages_id' order by p.products_id DESC limit 5");
  while ($products = tep_db_fetch_array($products_query)) {
    echo '              <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'action=new_product_preview&read=only&pID=' . $products['products_id'] . '&origin=' . FILENAME_DEFAULT, 'NONSSL') . '\'">' . "\n";
    echo '                <td class="smallText">&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=new_product_preview&read=only&pID=' . $products['products_id'] . '&origin=' . FILENAME_DEFAULT, 'NONSSL') . '" class="blacklink">' . $products['products_name'] . '</a>&nbsp;</td>' . "\n";
    echo '                <td align="right" class="smallText">&nbsp;' . tep_datetime_short($products['products_date_added']) . '&nbsp;</td>' . "\n";
    echo '              </tr>' . "\n";
  }
?>
              <tr>
                <td colspan="2"><? echo tep_black_line(); ?></td>
              </tr>
            </table></td>
            <td width="50%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td colspan="2"><? echo tep_black_line(); ?></td>
              </tr>
              <tr class="subBar">
                <td colspan="2" class="subBar">&nbsp;<? echo TABLE_HEADING_NEW_REVIEWS; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2"><? echo tep_black_line(); ?></td>
              </tr>
<?
  $reviews_query = tep_db_query("select r.reviews_id, pd.products_name, r.date_added from " . TABLE_REVIEWS . " r, " . TABLE_PRODUCTS_DESCRIPTION . " pd where r.products_id = pd.products_id and pd.language_id='$languages_id' order by reviews_id DESC limit 5");
  while ($reviews = tep_db_fetch_array($reviews_query)) {
    echo '              <tr class="tableRow" onmouseover="this.className=\'tableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'tableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_REVIEWS, 'action=preview&rID=' . $reviews['reviews_id'] . '&origin=' . FILENAME_DEFAULT, 'NONSSL') . '\'">' . "\n";
    echo '                <td class="smallText">&nbsp;<a href="' . tep_href_link(FILENAME_REVIEWS, 'action=preview&rID=' . $reviews['reviews_id'] . '&origin=' . FILENAME_DEFAULT, 'NONSSL') . '" class="blacklink">' . $reviews['products_name'] . '</a>&nbsp;</td>' . "\n";
    echo '                <td align="right" class="smallText">&nbsp;' . tep_datetime_short($reviews['date_added']) . '&nbsp;</td>' . "\n";
    echo '              </tr>' . "\n";
  }
?>
              <tr>
                <td colspan="2"><? echo tep_black_line(); ?></td>
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
<? $include_file = DIR_WS_INCLUDES . 'footer.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<? $include_file = DIR_WS_INCLUDES . 'application_bottom.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
