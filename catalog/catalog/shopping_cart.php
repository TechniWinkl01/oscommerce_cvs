<?php
/*
  $Id: shopping_cart.php,v 1.60 2002/03/10 22:49:23 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require("includes/application_top.php");

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART);

  $location = ' &raquo; <a href="' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL') . '" class="headerNavigation">' . NAVBAR_TITLE . '</a>';
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_cart.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  if ($cart->count_contents() > 0) {
?>
      <tr>
        <td><form name="cart_quantity" method="post" action="<?php echo tep_href_link(FILENAME_SHOPPING_CART, 'action=update_product', 'NONSSL'); ?>"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td align="center" class="smallText"><b><?php echo TABLE_HEADING_REMOVE; ?></b></td>
            <td align="center" class="tableHeading"><?php echo TABLE_HEADING_QUANTITY; ?></td>
<?php
    if (PRODUCT_LIST_MODEL) {
?>
            <td class="tableHeading"><?php echo TABLE_HEADING_MODEL; ?></td>
<?php
    }
?>
            <td class="tableHeading"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
            <td align="right" class="tableHeading"><?php echo TABLE_HEADING_TOTAL; ?></td>
          </tr>
          <tr>
            <td colspan="<?php echo (PRODUCT_LIST_MODEL ? 5 : 4); ?>"><?php echo tep_draw_separator(); ?></td>
          </tr>
<?php
    $any_out_of_stock = 0;
    $products = $cart->get_products();
    for ($i=0; $i<sizeof($products); $i++) {
      $products_name = $products[$i]['name'];
      echo '          <tr>' . "\n";
      echo '            <td align="center" valign="top"><input type="checkbox" name="cart_delete[]" value="' . $products[$i]['id'] . '"></td>' . "\n";
      echo '            <td align="center" valign="top"><input type="text" name="cart_quantity[]" value="' . $products[$i]['quantity'] . '" maxlength="2" size="2"><input type="hidden" name="products_id[]" value="' . $products[$i]['id'] . '"></td>' . "\n";
      if (PRODUCT_LIST_MODEL) echo '            <td valign="top" class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id'], 'NONSSL') . '">' . $products[$i]['model'] . '</a></td>' . "\n";
      echo '            <td valign="top" class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id'], 'NONSSL') . '"><b>' . $products_name . '</b></a>' . "\n";

      if (STOCK_CHECK == 'true') {
        echo $stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity']);
        if ($stock_check) $any_out_of_stock = 1;
      }

//------display customer choosen option --------
      $attributes_exist = '0';
      if ($cart->contents[$products[$i]['id']]['attributes']) {
        $attributes_exist = '1';
        reset($cart->contents[$products[$i]['id']]['attributes']);
        while (list($option, $value) = each($cart->contents[$products[$i]['id']]['attributes'])) {
          $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $products[$i]['id'] . "' and pa.options_id = '" . $option . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $value . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
          $attributes_values = tep_db_fetch_array($attributes);
          echo "\n" . '<br><small><i> - ' . $attributes_values['products_options_name'] . ' ' . $attributes_values['products_options_values_name'] . '</i></small>';
          echo '<input type="hidden" name="id[' . $products[$i]['id'] . '][' . $option . ']" value="' . $value . '">';
        }
      }
//------display customer choosen option eof-----
      echo '</td>' . "\n";
      $products[$i]['price'] = $products[$i]['quantity'] * $products[$i]['price'];
      echo '            <td align="right" valign="top" class="main"><b>' . $currencies->display_price($products[$i]['price'], $products[$i]['tax_class_id']) . '</b>';
//------display customer choosen option --------
      if ($attributes_exist == '1') {
        reset($cart->contents[$products[$i]['id']]['attributes']);
        while (list($option, $value) = each($cart->contents[$products[$i]['id']]['attributes'])) {
          $attributes = tep_db_query("select pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $products[$i]['id'] . "' and pa.options_id = '" . $option . "' and pa.options_values_id = '" . $value . "'");
          $attributes_values = tep_db_fetch_array($attributes);
          if ($attributes_values['options_values_price'] != '0') {
            $attributes_values['options_values_price'] = $products[$i]['quantity'] * $attributes_values['options_values_price'];
            echo "\n" . '<br><small><i>' . $attributes_values['price_prefix'] . $currencies->display_price($attributes_values['options_values_price'], $products[$i]['tax_class_id']) . '</i></small>';
          }
        }
      }
//------display customer choosen option eof-----
      echo '</td>' . "\n";
      echo '          </tr>' . "\n";
    }
?>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><b><?php echo SUB_TITLE_SUB_TOTAL; ?> <?php echo $currencies->format($cart->show_total()); ?></b></td>
      </tr>
<?php
    if ($any_out_of_stock) {
      if (STOCK_ALLOW_CHECKOUT == 'true') {
?>
      <tr>
        <td class="stockWarning" align="center"><br><?php echo OUT_OF_STOCK_CAN_CHECKOUT; ?></td>
      </tr>
<?php
      } else {
?>
      <tr>
        <td class="stockWarning" align="center"><br><?php echo OUT_OF_STOCK_CANT_CHECKOUT; ?></td>
      </tr>
<?php
      }
    }
?>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo tep_image_submit('button_update_cart.gif', IMAGE_BUTTON_UPDATE_CART); ?></td>
            <td align="right" class="main"><a href="<?php echo tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'); ?>"><?php echo tep_image_button('button_checkout.gif', IMAGE_BUTTON_CHECKOUT); ?></a></td>
          </tr>
        </table></form></td>
      </tr>
<?php
  } else {
?>
      <tr>
        <td align="center" class="main"><?php echo TEXT_CART_EMPTY; ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><br><a href="<?php echo tep_href_link(FILENAME_DEFAULT); ?>"><?php echo tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></a></td>
      </tr>
<?
  }
?>
    </table></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
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