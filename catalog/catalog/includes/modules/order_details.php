<?php
/*
  $Id: order_details.php,v 1.1 2002/03/31 20:10:43 clescuyer Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

?>
<!-- order_details -->
<?php
//print "<pre>"; print_r($products); print "</pre>";
  echo '<tr>';
  $colspan = 3;
  if (strstr($PHP_SELF, FILENAME_SHOPPING_CART)) {
    $colspan++;
    echo '<td align="center" class="smallText"><b>' . TABLE_HEADING_REMOVE . '</b></td>';
  }
  echo '<td align="center" class="tableHeading">' . TABLE_HEADING_QUANTITY . '</td>';
  if (PRODUCT_LIST_MODEL && strstr($PHP_SELF, FILENAME_SHOPPING_CART)) {
    $colspan++;
    echo '<td class="tableHeading">' . TABLE_HEADING_MODEL . '</td>';
  }
  echo '<td class="tableHeading">' . TABLE_HEADING_PRODUCTS . '</td>';
  if (!strstr($PHP_SELF, FILENAME_SHOPPING_CART)) {
    $colspan++;
    echo '<td align="center" class="tableHeading">' . TABLE_HEADING_TAX . '</td>';
  }
  echo '<td align="right" class="tableHeading">' . TABLE_HEADING_TOTAL . '</td>';
  echo '</tr>';
  echo '<tr>';
  echo '<td colspan="' . $colspan . '">' . tep_draw_separator() . '</td>';
  echo '</tr>';

for ($i=0; $i<sizeof($products); $i++) {
  echo '  <tr>' . "\n";

// Delete box only for shopping cart
  if (strstr($PHP_SELF, FILENAME_SHOPPING_CART))
    echo '    <td align="center" valign="top"><input type="checkbox" name="cart_delete[]" value="' . $products[$i]['id'] . '"></td>' . "\n";

// Quantity box or information as an input box or text
  if (strstr($PHP_SELF, FILENAME_SHOPPING_CART)) {
    echo '    <td align="center" valign="top"><input type="text" name="cart_quantity[]" value="' . $products[$i]['quantity'] . '" maxlength="2" size="2"><input type="hidden" name="products_id[]" value="' . $products[$i]['id'] . '"></td>' . "\n";
  } else {
    echo '    <td align="center" valign="top" class ="main">' . $products[$i]['quantity'] . '</td>' . "\n";
  }

// Model
  if (PRODUCT_LIST_MODEL && strstr($PHP_SELF, FILENAME_SHOPPING_CART))
    echo '    <td valign="top" class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id'], 'NONSSL') . '">' . $products[$i]['model'] . '</a></td>' . "\n";
  
// Product name, with or without link
  if (strstr($PHP_SELF, FILENAME_SHOPPING_CART)) {
    echo '    <td valign="top" class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id'], 'NONSSL') . '"><b>' . $products[$i]['name'] . '</b></a>' . "\n";
  } else {
    echo '    <td valign="top" class="main"><b>' . $products[$i]['name'] . '</b>' . "\n";
  }
  
  
// Product options names
  $attributes_exist = 0;
  if ($products[$i]['attributes']) {
    $attributes_exist = 1;
  }

  if ($attributes_exist == 1) {
    reset($products[$i]['attributes']);
    while (list($option, $value) = each($products[$i]['attributes'])) {
      echo "\n" . '<br><small><i> - ' . $products[$i][$option]['products_options_name'] . ' ' . $products[$i][$option]['products_options_values_name'] . '</i></small>';
    }
  }
  echo '    </td>' . "\n";

// Tax (not in shopping cart, tax rate may be unknown)
  if (!strstr($PHP_SELF, FILENAME_SHOPPING_CART)) {
    echo '    <td align="center" valign="top" class="main">' . number_format($products[$i]['tax'], TAX_DECIMAL_PLACES) . '%</td>' . "\n";
  }

// Product price  
  if (!strstr($PHP_SELF, FILENAME_ACCOUNT_HISTORY_INFO)) {
    echo '    <td align="right" valign="top" class="main"><b>' . $currencies->display_price($products[$i]['price'] * $products[$i]['quantity'], $products[$i]['tax_class_id']) . '</b>' . "\n";
  } else {
    if (DISPLAY_PRICE_WITH_TAX == true) {
      echo '    <td align="right" valign="top" class="main"><b>' . $currencies->format($products[$i]['price'] * $products[$i]['quantity'] * (1 + $products[$i]['tax']/100)) . '</b>' . "\n";
    } else {
      echo '    <td align="right" valign="top" class="main"><b>' . $currencies->format($products[$i]['price'] * $products[$i]['quantity']) . '</b>' . "\n";
    }
  }    

// Product options prices
  if ($attributes_exist == 1) {
    reset($products[$i]['attributes']);
    while (list($option, $value) = each($products[$i]['attributes'])) {
      if ($products[$i][$option]['options_values_price'] != 0) {
        if (!strstr($PHP_SELF, FILENAME_ACCOUNT_HISTORY_INFO)) {
          echo '<br><small><i>' . $products[$i][$option]['price_prefix'] . $currencies->display_price($products[$i][$option]['options_values_price'] * $products[$i]['quantity'], $products[$i]['tax_class_id']) . '</i></small>';
        } else {
          if (DISPLAY_PRICE_WITH_TAX == true) {
            echo '<br><small><i>' . $products[$i][$option]['price_prefix'] . $currencies->format($products[$i][$option]['options_values_price'] * $products[$i]['quantity'] * (1 + $products[$i]['tax']/100)) . '</i></small>';
          } else {
            echo '<br><small><i>' . $products[$i][$option]['price_prefix'] . $currencies->format($products[$i][$option]['options_values_price'] * $products[$i]['quantity']) . '</i></small>';
          }
        }
      } else {
// Keep price aligned with corresponding option
        echo "\n" . '<br><small><i>&nbsp;</i></small>';
      }
    }
  }
  echo '    </td>' . "\n";
  echo '  </tr>' . "\n";
}
?>
<!-- order_details_eof -->