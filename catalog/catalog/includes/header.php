<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr class="header">
    <td valign="middle" nowrap><?php echo tep_image(DIR_WS_IMAGES . 'header_exchange_logo.gif', STORE_NAME) . tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '6', '1') . tep_image(DIR_WS_IMAGES . 'header_exchange.gif', STORE_NAME); ?></td>
    <td align="right" nowrap><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'header_account.gif', HEADER_TITLE_MY_ACCOUNT) . '</a>&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'header_cart.gif', HEADER_TITLE_CART_CONTENTS) . '</a>&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '">' . tep_image(DIR_WS_IMAGES . 'header_checkout.gif', HEADER_TITLE_CHECKOUT) . '</a>'; ?>&nbsp;&nbsp;</td>
  </tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr class="headerNavigation" height="19">
    <td nowrap class="headerNavigation">
<?php
  echo '&nbsp;&nbsp;<a href="' . HTTP_SERVER . '" class="whitelink">' . HEADER_TITLE_TOP . '</a> : <a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '" class="whitelink">' . HEADER_TITLE_CATALOG . '</a>';

  if ($cPath) {
    if (!ereg('_', $cPath)) {
      $cPath_array = array($cPath);
    }
    $cPath_new = '';
    for($i=0; $i<sizeof($cPath_array); $i++) {
      if ($cPath_new == '') {
        $cPath_new .= $cPath_array[$i];
      } else {
        $cPath_new .= '_' . $cPath_array[$i];
      }
      $categories_query = tep_db_query("select categories_name from categories_description where categories_id = '" . $cPath_array[$i] . "' and language_id='" . $languages_id . "'");
      $categories = tep_db_fetch_array($categories_query);
      echo ' : <a href="' . tep_href_link(FILENAME_DEFAULT, 'cPath=' . $cPath_new, 'NONSSL') . '" class="whitelink">' . $categories['categories_name'] . '</a>';
    }
  } elseif ($HTTP_GET_VARS['manufacturers_id']) {
    $manufacturers_query = tep_db_query("select manufacturers_name from manufacturers where manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id'] . "'");
    $manufacturers = tep_db_fetch_array($manufacturers_query);
    echo ' : <a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'], 'NONSSL') . '" class="whitelink">' . $manufacturers['manufacturers_name'] . '</a>';
  }
  if ($HTTP_GET_VARS['products_id']) {
    $model = tep_db_query("select products_model from products where products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
    $model_values = tep_db_fetch_array($model);
    echo ' : <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&products_id=' . $HTTP_GET_VARS['products_id'], 'NONSSL') . '" class="whitelink">' . $model_values['products_model'] . '</a>';
  }
  if ($location) {
    echo $location;
  }
?>
    </td>
    <td align="right" nowrap class="headerNavigation"><? if (tep_session_is_registered('customer_id')) { ?><a href="<?php echo tep_href_link(FILENAME_LOGOFF, '', 'NONSSL'); ?>" class="whitelink"><?php echo HEADER_TITLE_LOGOFF; ?></a> &nbsp;|&nbsp; <?php } ?><a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL'); ?>" class="whitelink"><?php echo HEADER_TITLE_MY_ACCOUNT; ?></a> &nbsp;|&nbsp; <a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'); ?>" class="whitelink"><?php echo HEADER_TITLE_CART_CONTENTS; ?></a> &nbsp;|&nbsp; <a href="<?php echo tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'); ?>" class="whitelink"><?php echo HEADER_TITLE_CHECKOUT; ?></a> &nbsp;&nbsp;</td>
  </tr>
</table>
<?php
  if ($HTTP_GET_VARS['error_message'] != '') {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="headerError">
    <td nowrap class="headerError"><?php echo $HTTP_GET_VARS['error_message']; ?></td>
  </tr>
</table>
<?php
  }

  if ($HTTP_GET_VARS['info_message'] != '') {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="headerInfo">
    <td nowrap class="headerInfo"><?php echo $HTTP_GET_VARS['info_message']; ?></td>
  </tr>
</table>
<?php
  }
?>

