<? include('includes/application_top.php'); ?>
<? $include_file = DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<? $location = ''; ?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function popupImageWindow(url) {
  window.open(url,'popupImageWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_WS_INCLUDES . 'header.php';  include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_WS_INCLUDES . 'column_left.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td width="100%" class="topBarTitle" nowrap>&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
<?
  $product_info = tep_db_query("select products_id, products_name, products_description, products_model, products_quantity, products_image, products_url, products_price, products_date_added, products_date_available, manufacturers_id from products where products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
  if (!tep_db_num_rows($product_info)) { // product not found in database
?>
      <tr>
        <td class="main" nowrap><br>&nbsp;<? echo TEXT_PRODUCT_NOT_FOUND; ?>&nbsp;</td>
      </tr>
      <tr>
        <td><br><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td align="right" nowrap><br><a href="<? echo tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><? echo tep_image(DIR_WS_IMAGES . 'button_main_menu.gif', IMAGE_MAIN_MENU); ?></a></td>
      </tr>
<?
  } else {
    tep_db_query("update products set products_viewed = products_viewed+1 where products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
    $product_info_values = tep_db_fetch_array($product_info);

    $manufacturer_query = tep_db_query("select manufacturers_name, manufacturers_image from manufacturers where manufacturers_id = '" . $product_info_values['manufacturers_id'] . "'");
    if (tep_db_num_rows($manufacturer_query)) {
      $manufacturer = tep_db_fetch_array($manufacturer_query);
    }

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
            <td class="pageHeading" nowrap>&nbsp;<? echo $product_info_values['products_name'] . '<br>&nbsp;' . $products_price; ?>&nbsp;</td>
            <td align="right" nowrap>&nbsp;<? if (isset($manufacturer)) echo tep_image($manufacturer['manufacturers_image'], $manufacturer['manufacturers_name'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
<?
    if (PRODUCT_LIST_MODEL) {
      echo '          <tr>' . "\n" .
           '            <td colspan="2" class="pageHeading" nowrap>&nbsp;' . $product_info_values['products_model'] . '&nbsp;</td>' . "\n" .
           '          </tr>' . "\n";
    }
?>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr></table>
    <form name="cart_quantity" method="post" action="<? echo tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_update_product', 'NONSSL'); ?>">
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%"><tr><td class="main"><br><a href="javascript:popupImageWindow('<? echo FILENAME_POPUP_IMAGE; ?>?image=<? echo $product_info_values['products_image']; ?>&alt=<? echo addslashes($product_info_values['products_name']); ?>')"><? echo tep_image($product_info_values['products_image'], $product_info_values['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"'); ?></a><p><? echo stripslashes($product_info_values['products_description']); ?></p>
<?
    if ($products_attributes == '1') {
      $products_options_name = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from products_options popt, products_attributes patrib where patrib.products_id='" . $HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id");
      echo '<b>' . TEXT_PRODUCT_OPTIONS . '</b><br>';
      echo '<table border="0" cellpading="0" cellspacing"0">';
      while ($products_options_name_values = tep_db_fetch_array($products_options_name)) { 
        $selected = 0;
        $products_options = tep_db_query("select products_options_values.products_options_values_id, products_options_values.products_options_values_name, products_attributes.options_values_price, products_attributes.price_prefix from products_attributes, products_options_values where products_attributes.products_id = '" . $HTTP_GET_VARS['products_id'] . "' and products_attributes.options_id = '" . $products_options_name_values['products_options_id'] . "' and products_attributes.options_values_id = products_options_values.products_options_values_id");
        echo '<tr><td class="main">' . $products_options_name_values['products_options_name'] . ':&nbsp;</td><td>' . "\n" . '<select name ="id[' . $products_options_name_values['products_options_id'] . ']">' . "\n"; 
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
        <td class="main" nowrap><br><? echo TEXT_CURRENT_REVIEWS . ' ' . $reviews_values['count']; ?><br>&nbsp;</td>
      </tr>
<?
    if ($product_info_values['products_url']) {
?>
      <tr>
        <td class="main" nowrap><? echo sprintf(TEXT_MORE_INFORMATION, $product_info_values['products_url']); ?><br>&nbsp;</td>
      </tr>
<?
    }

    if ($product_info_values['products_date_available'] > date('Ymd')) {
?>
      <tr>
        <td align="center" class="smallText" nowrap><? echo sprintf(TEXT_DATE_AVAILABLE, tep_date_long($product_info_values['products_date_available'])); ?></td>
      </tr>
<?
    } else {
?>
      <tr>
        <td align="center" class="smallText" nowrap><? echo sprintf(TEXT_DATE_ADDED, tep_date_long($product_info_values['products_date_added'])); ?></td>
      </tr>
<?
    }
?>
      <tr>
        <td><br><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
<?
    $get_params = substr(tep_get_all_get_params(), 0, -1);
    if ($reviews_values['count'] == '0') {
      echo '            <td class="main" nowrap>&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, $get_params, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_write_a_review.gif', IMAGE_WRITE_A_REVIEW) . '</a>&nbsp;</td>' . "\n";
    } else {
      echo '            <td class="main" nowrap>&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, $get_params, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_reviews.gif', IMAGE_REVIEWS) . '</a>&nbsp;</td>' . "\n";
    }

    echo '            <td align="center" class="main" nowrap>&nbsp;<a href="' . tep_href_link(FILENAME_EMAILPRODUCT, 'action=where&' . $get_params, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_tellafriend.gif', IMAGE_TELLAFRIEND) . '</a>&nbsp;</td>' . "\n";
    echo '            <td align="right" class="main" nowrap>&nbsp;<input type="hidden" name="products_id" value="' . $product_info_values['products_id'] . '">&nbsp;&nbsp;' . tep_image_submit(DIR_WS_IMAGES . 'button_add_to_cart.gif', IMAGE_ADD_TO_CART);
    $get_params_back = substr(tep_get_all_get_params(array('products_id','language','currency')), 0, -1);
    if ($get_params_back != '') {
      echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, $get_params_back, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'button_back.gif', IMAGE_BACK) . '</a>';
    }
    echo '&nbsp;&nbsp;</td>' . "\n";
?>
          </tr>
        </table></td>
      </tr>
<?
  $include_file = DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS; include(DIR_WS_INCLUDES . 'include_once.php');
  }
?>
    </table></form></td>
<!-- body_text_eof //-->
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<? $include_file = DIR_WS_INCLUDES . 'column_right.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- right_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
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
