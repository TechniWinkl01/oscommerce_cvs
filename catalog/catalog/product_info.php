<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ''; ?>
<html>
<head>
<title><?=TITLE;?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_INCLUDES . 'header.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<?=BOX_WIDTH;?>" valign="top"><table border="0" width="<?=BOX_WIDTH;?>" cellspacing="0" cellpadding="0">
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
            <td bgcolor="<?=TOP_BAR_BACKGROUND_COLOR;?>" width="100%" nowrap><font face="<?=TOP_BAR_FONT_FACE;?>" size="<?=TOP_BAR_FONT_SIZE;?>" color="<?=TOP_BAR_FONT_COLOR;?>">&nbsp;<?=TOP_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
<?
  $product_info = tep_db_query("select manufacturers.manufacturers_name, manufacturers.manufacturers_location, manufacturers.manufacturers_image, products.products_id, products.products_name, products.products_description, products.products_model, products.products_quantity, products.products_image, products.products_url, products.products_price, products.products_date_added from manufacturers, products_to_manufacturers, products where products.products_id = '" . $HTTP_GET_VARS['products_id'] . "' and products_to_manufacturers.products_id = '" . $HTTP_GET_VARS['products_id'] . "' and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id");
  if (!tep_db_num_rows($product_info)) { // product not found in database
?>
      <tr>
        <td nowrap><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_PRODUCT_NOT_FOUND;?>&nbsp;</font></td>
      </tr>
      <tr>
        <td><br><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td align="right" nowrap><br><a href="<?=tep_href_link(FILENAME_DEFAULT, '', 'NONSSL');?>"><?=tep_image(DIR_IMAGES . 'button_main_menu.gif', '112', '24', '0', IMAGE_MAIN_MENU);?></a></td>
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
    $products_name = tep_products_name($product_info_values['manufacturers_location'], $product_info_values['manufacturers_name'], $product_info_values['products_name']);
    if ($new_price) {
      $products_price = '<s>$' . $product_info_values['products_price'] . '</s>&nbsp;&nbsp;<font color="' . SPECIALS_PRICE_COLOR . '">$' . $new_price . '</font>';
    } else {
       $products_price = ' $' . $product_info_values['products_price'];
    }
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=$products_name . ' @ ' . $products_price; ?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<?=tep_image($product_info_values['manufacturers_image'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', $product_info_values['manufacturers_name']);?>&nbsp;</td>
          </tr>
<?
   if (PRODUCT_LIST_MODEL) {
      echo '<td nowrap><font face="' . HEADING_FONT_FACE . '" size="' . HEADING_FONT_SIZE . '" color="' . HEADING_FONT_COLOR . '">&nbsp;' . $product_info_values['products_model'] . '&nbsp;</font></td>';
   }
?>
        </table></td>
      </tr>
      <tr>
        <td><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td wrap><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><?=tep_image($product_info_values['products_image'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, '0' . '" align="right" hspace="5" vspace="5', $products_name);?><?=$product_info_values['products_description'];?></font></td>
      </tr>
<?
    $reviews = tep_db_query("select count(*) as count from reviews_extra where products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
    $reviews_values = tep_db_fetch_array($reviews);
?>
      <tr>
        <td nowrap><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><?=TEXT_CURRENT_REVIEWS . ' ' . $reviews_values['count'];?><br>&nbsp;</font></td>
      </tr>
<?
    if ($product_info_values['products_url']) {
?>
      <tr>
        <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><?=sprintf(TEXT_MORE_INFORMATION, $product_info_values['products_url']);?><br>&nbsp;</font></td>
      </tr>
<?
    }
?>
      <tr>
        <td align="center" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><?=sprintf(TEXT_DATE_ADDED, $date_added);?></font></td>
      </tr>
      <tr>
        <td><br><?=tep_black_line();?></td>
      </tr>
<?
    if (tep_session_is_registered('customer_id')) {
      $check_products = tep_db_query("select customers_basket_quantity from customers_basket where customers_id = '" . $customer_id . "' and products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
      if (tep_db_num_rows($check_products)) {
        $check_products_values = tep_db_fetch_array($check_products);
        $product_exists_in_cart = '1';
        $product_quantity_in_cart = $check_products_values['customers_basket_quantity'];
      } else {
        $product_exists_in_cart = '0';
      }
    } elseif (tep_session_is_registered('nonsess_cart')) {
      $nonsess_cart_contents = explode('|', $nonsess_cart);
      for ($i=0;$i<sizeof($nonsess_cart_contents);$i++) {
        $product_info = explode(':', $nonsess_cart_contents[$i]);
        if ($product_info[0] == $HTTP_GET_VARS['products_id']) {
          $product_exists_in_cart = '1';
          $product_quantity_in_cart = $product_info[1];
        }
      }
    }
?>
      <tr>
<?      
// lets retrieve all $HTTP_GET_VARS keys and values..
    $keys = array_keys($HTTP_GET_VARS);
    $values = array_values($HTTP_GET_VARS);

    $get_params = '';
    $get_params_back = ''; // this one is for the back button (needs to remove the last GET parameter (products_id))
    for ($i=0;$i<sizeof($keys);$i++) {
      $get_params.=$keys[$i] . '=' . $values[$i] . '&';
      if (($i + 1) != sizeof($keys)) {
        $get_params_back.=$keys[$i] . '=' . $values[$i] . '&';
      }
    }
    $get_params = substr($get_params, 0, -1); //remove trailing &
    $get_params_back = substr($get_params_back, 0, -1); //remove trailing &
?>
        <td><br><form name="cart_quantity" method="post" action="<?=tep_href_link(FILENAME_SHOPPING_CART, 'action=add_update_product', 'NONSSL');?>"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
<?
    if ($reviews_values['count'] == '0') {
      echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, $get_params, 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_write_a_review.gif', '140', '24', '0', IMAGE_WRITE_A_REVIEW) . '</a>&nbsp;</font></td>' . "\n";
    } else {
      echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, $get_params, 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_reviews.gif', '78', '24', '0', IMAGE_REVIEWS) . '</a>&nbsp;</font></td>' . "\n";
    }

    if ($product_exists_in_cart == '1') {
      echo '            <td align="right" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<input type="text" name="new_cart_quantity[]" value="' . $product_quantity_in_cart . '" maxlength="2" size="2">&nbsp;&nbsp;<input type="hidden" name="old_cart_quantity[]" value="' . $product_quantity_in_cart . '"><input type="hidden" name="products_id[]" value="' . $product_info_values['products_id'] . '">' . tep_image_submit(DIR_IMAGES . 'button_update_cart.gif', '116', '24', '0', IMAGE_UPDATE_CART) . '&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_SHOPPING_CART, 'action=remove_product&products_id=' . $product_info_values['products_id'], 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_remove_all.gif', '113', '24', '0', IMAGE_REMOVE_ALL) . '</a>&nbsp;&nbsp;</font></td>' . "\n";
    } else {
      echo '            <td align="right" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<input type="text" name="new_cart_quantity[]" value="1" maxlength="2" size="2">&nbsp;&nbsp;<input type="hidden" name="old_cart_quantity[]" value="0"><input type="hidden" name="products_id[]" value="' . $product_info_values['products_id'] . '">' . tep_image_submit(DIR_IMAGES . 'button_add_to_cart.gif', '116', '24', '0', IMAGE_ADD_TO_CART);
      if ($get_params_back != '') {
        echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, $get_params_back, 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'button_back.gif', '58', '24', '0', IMAGE_BACK) . '</a>';
      }
      echo '&nbsp;</font></td>' . "\n";
    }
?>
          </tr>
        </table></form></td>
      </tr>
<?
  }
?>
    </table></td>
<!-- body_text_eof //-->
    <td width="<?=BOX_WIDTH;?>" valign="top"><table border="0" width="<?=BOX_WIDTH;?>" cellspacing="0" cellpadding="0">
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
