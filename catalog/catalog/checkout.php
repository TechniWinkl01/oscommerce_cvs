<? include('includes/application_top.php'); ?>
<?
  if (!tep_session_is_registered('customer_id')) {
    header('Location: ' . tep_href_link(FILENAME_LOGIN, 'origin=' . FILENAME_CHECKOUT, 'NONSSL'));
    tep_exit();
  }
?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_CHECKOUT; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_CHECKOUT, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_1 . '</a> : ' . NAVBAR_TITLE_2; ?>
<html>
<head>
<title><? echo TITLE;?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_INCLUDES . 'header.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<? echo BOX_WIDTH;?>" valign="top"><table border="0" width="<? echo BOX_WIDTH;?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_INCLUDES . 'column_left.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
            <td bgcolor="<? echo TOP_BAR_BACKGROUND_COLOR;?>" width="100%" nowrap><font face="<? echo TOP_BAR_FONT_FACE;?>" size="<? echo TOP_BAR_FONT_SIZE;?>" color="<? echo TOP_BAR_FONT_COLOR;?>">&nbsp;<? echo TOP_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<? echo HEADING_FONT_FACE;?>" size="<? echo HEADING_FONT_SIZE;?>" color="<? echo HEADING_FONT_COLOR;?>">&nbsp;<? echo HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_IMAGES . 'table_background_checkout.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line();?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?
  $cart_empty = ($cart->count_contents() > 0) ? 0 : 1;

  if (!$cart_empty) {
?>
          <tr>
            <td align="center" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><? echo TABLE_HEADING_QUANTITY;?></b>&nbsp;</font></td>
            <td nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><? echo TABLE_HEADING_PRODUCTS;?></b>&nbsp;</font></td>
            <td align="right" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><? echo TABLE_HEADING_TOTAL;?></b>&nbsp;</font></td>
          </tr>
          <tr>
            <td colspan="3"><? echo tep_black_line();?></td>
          </tr>
<?
    $products = $cart->get_products();
    for ($i=0; $i<sizeof($products); $i++) {
      $products_name = $products[$i]['name'];
      echo '          <tr>' . "\n";
      echo '            <td align="center" valign="top" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . $products[$i]['quantity'] . '&nbsp;</font></td>' . "\n";
      echo '            <td valign="top" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '"><b>&nbsp;' . $products_name . '&nbsp;</b>' . "\n";
//------display customer choosen option --------
      $attributes_exist = '0';
      if ($cart->contents[$products[$i]['id']]['attributes']) {
        $attributes_exist = '1';
        reset($cart->contents[$products[$i]['id']]['attributes']);
        while (list($option, $value) = each($cart->contents[$products[$i]['id']]['attributes'])) {
          $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name from products_options popt, products_options_values poval, products_attributes pa where pa.products_id = '" . $products[$i]['id'] . "' and pa.options_id = '" . $option . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $value . "' and pa.options_values_id = poval.products_options_values_id");
          $attributes_values = tep_db_fetch_array($attributes);
          echo "\n" . '<br><small><i>&nbsp;-&nbsp;' . $attributes_values['products_options_name'] . '&nbsp;:&nbsp;' . $attributes_values['products_options_values_name'] . '</i></small>';
        }
      }
//------display customer choosen option eof-----		
      echo '</font></td>' . "\n";
      echo '            <td align="right" valign="top" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<b>' . tep_currency_format($products[$i]['quantity'] * $products[$i]['price']) . '</b>&nbsp;';
//------display customer choosen option --------
      if ($attributes_exist == '1') {
        reset($cart->contents[$products[$i]['id']]['attributes']);
        while (list($option, $value) = each($cart->contents[$products[$i]['id']]['attributes'])) {
          $attributes = tep_db_query("select pa.options_values_price, pa.price_prefix from products_options popt, products_options_values poval, products_attributes pa where pa.products_id = '" . $products[$i]['id'] . "' and pa.options_id = '" . $option . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $value . "' and pa.options_values_id = poval.products_options_values_id");
          $attributes_values = tep_db_fetch_array($attributes);
          if ($attributes_values['options_values_price'] != '0') {
            echo "\n" . '<br><small><i>' . $attributes_values['price_prefix'] . tep_currency_format($products[$i]['quantity'] * $attributes_values['options_values_price']) . '</i></small>&nbsp;';
          } else {
            echo "\n" . '<br>&nbsp;';
          }
        }
      }
//------display customer choosen option eof-----
      echo '</font></td>' . "\n";
      echo '          </tr>' . "\n";
    }
?>
          <tr>
            <td colspan="3"><? echo tep_black_line();?></td>
          </tr>
          <tr>
            <td colspan="3" align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0" align="right">
              <tr>
                <td align="right" width="100%" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<? echo SUB_TITLE_SUB_TOTAL;?>&nbsp;</font></td>
                <td align="right" width="100%" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<? echo tep_currency_format($cart->show_total());?>&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="3"><? echo tep_black_line();?></td>
          </tr>
          <tr>
            <td colspan="3" align="right"><br><font face="<? echo SMALL_TEXT_FONT_FACE;?>" size="<? echo SMALL_TEXT_FONT_SIZE;?>" color="<? echo SMALL_TEXT_FONT_COLOR;?>">&nbsp;<? echo NO_SHIPPING_OR_TAX_TEXT;?>&nbsp;</font></td>
          </tr>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td align="right"><br><font face="<? echo TEXT_FONT_FACE;?>" size="<? echo TEXT_FONT_SIZE;?>" color="<? echo TEXT_FONT_COLOR;?>">&nbsp;<a href="<? echo tep_href_link(FILENAME_CHECKOUT_ADDRESS, '', 'SSL');?>"><? echo tep_image(DIR_IMAGES . 'button_next.gif', '50', '24', '0', IMAGE_NEXT);?></a>&nbsp;&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="3" align="right"><br><font face="<? echo SMALL_TEXT_FONT_FACE;?>" size="<? echo SMALL_TEXT_FONT_SIZE;?>" color="<? echo SMALL_TEXT_FONT_COLOR;?>">&nbsp;<font color="<? echo CHECKOUT_BAR_TEXT_COLOR;?>">[ <font color="<? echo CHECKOUT_BAR_TEXT_COLOR_HIGHLIGHTED;?>"><? echo CHECKOUT_BAR_CART_CONTENTS;?></font> | <? echo CHECKOUT_BAR_DELIVERY_ADDRESS;?> | <? echo CHECKOUT_BAR_PAYMENT_METHOD;?> | <? echo CHECKOUT_BAR_CONFIRMATION;?> | <? echo CHECKOUT_BAR_FINISHED;?> ]</font>&nbsp;&nbsp;</font></td>
          </tr>
<?
  } else {
    echo '          <tr>' . "\n";
    echo '            <td colspan="3" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . TEXT_CART_EMPTY . '&nbsp;</font></td>' . "\n";
    echo '          </tr>' . "\n";
    echo '          <tr>' . "\n";
    echo '            <td colspan="3">' . tep_black_line() . '</td>' . "\n";
    echo '          </tr>' . "\n";
    echo '          <tr>' . "\n";
    echo '            <td colspan="3" align="right" nowrap><br><font face="' . TABLE_HEADING_FONT_FACE . '" size="' . TABLE_HEADING_FONT_SIZE . '" color="' . TABLE_HEADING_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_main_menu.gif', '112', '24', '0', IMAGE_MAIN_MENU) . '</a>&nbsp;&nbsp;</font></td>' . "\n";
    echo '          </tr>' . "\n";
  }
?>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<? echo BOX_WIDTH;?>" valign="top"><table border="0" width="<? echo BOX_WIDTH;?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<? $include_file = DIR_INCLUDES . 'column_right.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- right_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<? $include_file = DIR_INCLUDES . 'footer.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<? $include_file = DIR_INCLUDES . 'application_bottom.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
