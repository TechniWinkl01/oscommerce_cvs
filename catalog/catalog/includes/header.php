<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr class="header">
    <td align="left" valign="middle" nowrap><? echo tep_image(DIR_WS_IMAGES . 'header_exchange_logo.gif', STORE_NAME) . tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '6', '1') . tep_image(DIR_WS_IMAGES . 'header_exchange.gif', STORE_NAME); ?></td>
    <td align="right" nowrap>
<?
  if ($customer_id) {
    echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'header_account.gif', HEADER_TITLE_MY_ACCOUNT) . '</a>';
  } else {
    echo '<a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'header_account.gif', HEADER_TITLE_CREATE_ACCOUNT) . '</a>';
  }
?>
&nbsp;&nbsp;<a href="<? echo tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'); ?>"><? echo tep_image(DIR_WS_IMAGES . 'header_cart.gif', HEADER_TITLE_CART_CONTENTS); ?></a>&nbsp;&nbsp;<a href="<? echo tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'); ?>"><? echo tep_image(DIR_WS_IMAGES . 'header_checkout.gif', HEADER_TITLE_CHECKOUT); ?></a>&nbsp;&nbsp;<a href="<? echo tep_href_link(FILENAME_CONTACT_US, '', 'NONSSL'); ?>"><? echo tep_image(DIR_WS_IMAGES . 'header_contact_us.gif', HEADER_TITLE_CONTACT_US); ?></a>&nbsp;&nbsp;</td>
  </tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr class="headerNavigation" height="19">
    <td nowrap class="headerNavigation">&nbsp;&nbsp;<a href="<? echo HTTP_SERVER; ?>" class="whitelink"><? echo HEADER_TITLE_TOP; ?></a> : <a href="<? echo tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>" class="whitelink"><? echo HEADER_TITLE_CATALOG; ?></a>
<?
  if ($cPath) {
    if (!ereg('_', $cPath)) $cPath_array = array($cPath);
    $cPath_new = '';
    for($i=0;$i<sizeof($cPath_array);$i++) {
      if ($cPath_new == '') {
        $cPath_new .= $cPath_array[$i];
      } else {
        $cPath_new .= '_' . $cPath_array[$i];
      }
      $categories_query = tep_db_query("select categories_name from categories where categories_id = '" . $cPath_array[$i] . "'");
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
  echo '</td>' . "\n"; ?>
    <td align="right" nowrap class="headerNavigation"><?
  if (tep_session_is_registered('customer_id')) {
    echo '<a href="' . tep_href_link(FILENAME_LOGOFF, '', 'NONSSL') . '" class="whitelink">' . HEADER_TITLE_LOGOFF . '</a> &nbsp;|&nbsp; <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL') . '" class="whitelink">' . HEADER_TITLE_MY_ACCOUNT . '</a> &nbsp;|&nbsp; <a href="' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL') . '" class="whitelink">' . HEADER_TITLE_CART_CONTENTS . '</a> &nbsp;|&nbsp; <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '" class="whitelink">' . HEADER_TITLE_CHECKOUT . '</a> &nbsp;|&nbsp; <a href="' . tep_href_link(FILENAME_CONTACT_US, '', 'NONSSL') . '" class="whitelink">' . HEADER_TITLE_CONTACT_US . '</a>&nbsp;&nbsp;';
  } else {
    echo '<a href="' . tep_href_link(FILENAME_LOGIN, '', 'NONSSL') . '" class="whitelink">' . HEADER_TITLE_LOGIN . '</a> &nbsp;|&nbsp; <a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'NONSSL') . '" class="whitelink">' . HEADER_TITLE_CREATE_ACCOUNT . '</a> &nbsp;|&nbsp; <a href="' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL') . '" class="whitelink">' . HEADER_TITLE_CART_CONTENTS . '</a> &nbsp;|&nbsp; <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '" class="whitelink">' . HEADER_TITLE_CHECKOUT . '</a> &nbsp;|&nbsp; <a href="' . tep_href_link(FILENAME_CONTACT_US, '', 'NONSSL') . '" class="whitelink">' . HEADER_TITLE_CONTACT_US . '</a>&nbsp;&nbsp;';
  } ?></td>
  </tr>
</table>
<?
  if ($HTTP_GET_VARS['error_message'] != '') {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="headerError">
    <td nowrap class="headerError"><? echo $HTTP_GET_VARS['error_message']; ?></td>
  </tr>
</table>
<?
  }
?>
<?
  if ($HTTP_GET_VARS['info_message'] != '') {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="headerInfo">
    <td nowrap class="headerInfo"><? echo $HTTP_GET_VARS['info_message']; ?></td>
  </tr>
</table>
<?
  }
?>
