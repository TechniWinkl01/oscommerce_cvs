<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ''; ?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_INCLUDES . 'header.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
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
            <td bgcolor="<? echo TOP_BAR_BACKGROUND_COLOR; ?>" width="100%" nowrap><font face="<? echo TOP_BAR_FONT_FACE; ?>" size="<? echo TOP_BAR_FONT_SIZE; ?>" color="<? echo TOP_BAR_FONT_COLOR; ?>">&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
<?
  $product_info = tep_db_query("select m.manufacturers_name, m.manufacturers_image, p.products_id, p.products_name, p.products_description, p.products_model, p.products_quantity, p.products_image, p.products_url, p.products_price, p.products_date_added from manufacturers m, products_to_manufacturers p2m, products p where p.products_id = '" . $HTTP_GET_VARS['products_id'] . "' and p2m.products_id = p.products_id and p2m.manufacturers_id = m.manufacturers_id");
  if (!tep_db_num_rows($product_info)) { // product not found in database
?>
      <tr>
        <td nowrap><br><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo TEXT_PRODUCT_NOT_FOUND; ?>&nbsp;</font></td>
      </tr>
      <tr>
        <td><br><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td align="right" nowrap><br><a href="<? echo tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><? echo tep_image(DIR_IMAGES . 'button_main_menu.gif', '112', '24', '0', IMAGE_MAIN_MENU); ?></a></td>
      </tr>
<?
  } else {
    tep_db_query("update products set products_viewed = products_viewed+1 where products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
    $product_info_values = tep_db_fetch_array($product_info);
    $raw_date_added = $product_info_values['products_date_added'];
    $date_added = strftime(DATE_FORMAT_LONG, mktime(0,0,0,substr($raw_date_added, 4, 2),substr($raw_date_added, -2),substr($raw_date_added, 0, 4)));

    $check_special = tep_db_query("select specials.specials_new_products_price from specials where products_id = '" . $product_info_values['products_id'] . "'");
    if (tep_db_num_rows($check_special)) {
      $check_special_values = tep_db_fetch_array($check_special);
      $new_price = $check_special_values['specials_new_products_price'];
    }
    if ($new_price) {
      $products_price = '<s>' . tep_currency_format($product_info_values['products_price']) . '</s>&nbsp;&nbsp;<font color="' . SPECIALS_PRICE_COLOR . '">' . tep_currency_format($new_price) . '</font>';
    } else {
       $products_price = tep_currency_format($product_info_values['products_price']);
    }
	$products_attributes = tep_db_query("select popt.products_options_name from products_options popt, products_attributes patrib where patrib.products_id='" . $HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id");
	if (tep_db_num_rows($products_attributes)) {
	$products_attributes = '1';
    } else {
	$products_attributes = '0';
	}
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td nowrap><font face="<? echo HEADING_FONT_FACE; ?>" size="<? echo HEADING_FONT_SIZE; ?>" color="<? echo HEADING_FONT_COLOR; ?>">&nbsp;<? echo $product_info_values['products_name'] . '<br>&nbsp;' . $products_price; ?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<? echo tep_image($product_info_values['manufacturers_image'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', $product_info_values['manufacturers_name']); ?>&nbsp;</td>
          </tr>
<?
    if (PRODUCT_LIST_MODEL) {
      echo '<tr><td nowrap><font face="' . HEADING_FONT_FACE . '" size="' . HEADING_FONT_SIZE . '" color="' . HEADING_FONT_COLOR . '">&nbsp;' . $product_info_values['products_model'] . '&nbsp;</font></td>';
    }
?>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr></table>
<?
    if ($cart->get_quantity($HTTP_GET_VARS['products_id']) > 0) {
      $product_exists_in_cart = '1';
      $product_quantity_in_cart = $cart->get_quantity($HTTP_GET_VARS['products_id']);
    } else {
      $product_exists_in_cart = '0';
    }
?>	  
    <form name="cart_quantity" method="post" action="<? echo tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_update_product', 'NONSSL'); ?>">
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%"><tr><td><br><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><? echo tep_image($product_info_values['products_image'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, '0' . '" align="right" hspace="5" vspace="5', $product_info_values['products_name']); ?><p><? echo stripslashes($product_info_values['products_description']); ?></p>
<?
    if ($products_attributes == '1') {
      $products_options_name = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from products_options popt, products_attributes patrib where patrib.products_id='" . $HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id");
      echo '<b>' . TEXT_PRODUCT_OPTIONS . '</b><br>';
      echo '<table border="0" cellpading="0" cellspacing"0">';
      while ($products_options_name_values = tep_db_fetch_array($products_options_name)) { 
        $selected = 0;
        $products_options = tep_db_query("select products_options_values.products_options_values_id, products_options_values.products_options_values_name, products_attributes.options_values_price, products_attributes.price_prefix from products_attributes, products_options_values where products_attributes.products_id = '" . $HTTP_GET_VARS['products_id'] . "' and products_attributes.options_id = '" . $products_options_name_values['products_options_id'] . "' and products_attributes.options_values_id = products_options_values.products_options_values_id");
        echo '<tr><td><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">' . $products_options_name_values['products_options_name'] . ':&nbsp;</font></td><td>' . "\n" . '<select name ="id[' . $products_options_name_values['products_options_id'] . ']">' . "\n"; 
        while ($products_options_values = tep_db_fetch_array($products_options)) {
          echo "\n" . '<option name="' . $products_options_name_values['products_options_name'] . '" value="' . $products_options_values['products_options_values_id'] . '"';
          if ( ($products_options_values['options_values_price'] == 0 && $selected == 0) || ($cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name_values['products_options_id']] == $products_options_values['products_options_values_id'])) {
            $selected = 1;
            echo ' SELECTED';
          }
          echo '>' . $products_options_values['products_options_values_name'] . '&nbsp;(' . $products_options_values['price_prefix'] . tep_currency_format($products_options_values['options_values_price']) .')&nbsp</option>';
        };
        echo '</select></td></tr>';
      }
      echo '</table>';
    }
?>		
		</td></tr></table></td>
      </tr>
<?
    $reviews = tep_db_query("select count(*) as count from reviews_extra where products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
    $reviews_values = tep_db_fetch_array($reviews);
?>
      <tr>
        <td nowrap><br><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><? echo TEXT_CURRENT_REVIEWS . ' ' . $reviews_values['count']; ?><br>&nbsp;</font></td>
      </tr>
<?
    if ($product_info_values['products_url']) {
?>
      <tr>
        <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><? echo sprintf(TEXT_MORE_INFORMATION, $product_info_values['products_url']); ?><br>&nbsp;</font></td>
      </tr>
<?
    }
?>
      <tr>
        <td align="center" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>"><? echo sprintf(TEXT_DATE_ADDED, $date_added); ?></font></td>
      </tr>
      <tr>
        <td><br><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
<?
    $get_params = substr(tep_get_all_get_params(), 0, -1);
    if ($reviews_values['count'] == '0') {
      echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, $get_params, 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_write_a_review.gif', '140', '24', '0', IMAGE_WRITE_A_REVIEW) . '</a>&nbsp;</font></td>' . "\n";
    } else {
      echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, $get_params, 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_reviews.gif', '78', '24', '0', IMAGE_REVIEWS) . '</a>&nbsp;</font></td>' . "\n";
    }

    echo '<td align="right" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<input type="hidden" name="products_id" value="' . $product_info_values['products_id'] . '">';
    if ($product_exists_in_cart == '1') {
      echo '            <input type="text" name="cart_quantity" value="' . $product_quantity_in_cart . '" maxlength="2" size="2">&nbsp;&nbsp;' . tep_image_submit(DIR_IMAGES . 'button_update_cart.gif', '116', '24', '0', IMAGE_UPDATE_CART) . '&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=remove_product', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_remove_all.gif', '113', '24', '0', IMAGE_REMOVE_ALL) . '</a>';
    } else {
      echo '            <input type="text" name="cart_quantity" value="1" maxlength="2" size="2">&nbsp;&nbsp;' . tep_image_submit(DIR_IMAGES . 'button_add_to_cart.gif', '116', '24', '0', IMAGE_ADD_TO_CART);
    }
    $get_params_back = substr(tep_get_all_get_params(array('products_id','language','currency')), 0, -1);
    if ($get_params_back != '') {
      echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, $get_params_back, 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_back.gif', '58', '24', '0', IMAGE_BACK) . '</a>';
    }
    echo '&nbsp;&nbsp;</font></td>' . "\n";
?>
          </tr>
        </table></td>
      </tr>
<?
  $include_file = DIR_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS; include(DIR_INCLUDES . 'include_once.php');
  }
?>
    </table></form></td>
<!-- body_text_eof //-->
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
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
