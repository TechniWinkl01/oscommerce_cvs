<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr bgcolor="<?=HEADER_BACKGROUND_COLOR;?>">
    <td align="left" valign="middle" nowrap><?=tep_image(DIR_IMAGES . 'header_exchange_logo.gif', '57', '50', '0', STORE_NAME) . tep_image(DIR_IMAGES . 'pixel_trans.gif', '6', '1', '0', '') . tep_image(DIR_IMAGES . 'header_exchange.gif', '351', '50', '0', STORE_NAME);?></td>
    <td align="right" nowrap><?
  if ($customer_id) {
    echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'header_account.gif', '50', '50', '0', HEADER_TITLE_MY_ACCOUNT) . '</a>';
  } else {
    echo '<a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'NONSSL') . '">' . tep_image(DIR_IMAGES . 'header_account.gif', '50', '50', '0', HEADER_TITLE_CREATE_ACCOUNT) . '</a>';
  } ?>&nbsp;&nbsp;<a href="<?=tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL');?>"><?=tep_image(DIR_IMAGES . 'header_cart.gif', '50', '50', '0', HEADER_TITLE_CART_CONTENTS);?></a>&nbsp;&nbsp;<a href="<?=tep_href_link(FILENAME_CHECKOUT, '', 'NONSSL');?>"><?=tep_image(DIR_IMAGES . 'header_checkout.gif', '53', '50', '0', HEADER_TITLE_CHECKOUT);?></a>&nbsp;&nbsp;</td>
  </tr>
  <tr bgcolor="<?=HEADER_NAVIGATION_BAR_BACKGROUND_COLOR;?>" height="19">
    <td align="left" nowrap><font face="<?=HEADER_NAVIGATION_BAR_FONT_FACE;?>" color="<?=HEADER_NAVIGATION_BAR_FONT_COLOR;?>" size="<?=HEADER_NAVIGATION_BAR_FONT_SIZE;?>"><b>&nbsp;&nbsp;<a href="http://theexchangeproject.org" class="whitelink"><?=HEADER_TITLE_TOP;?></a> : <a href="<?=tep_href_link(FILENAME_DEFAULT, '', 'NONSSL');?>" class="whitelink"><?=HEADER_TITLE_CATALOG;?></a><?
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
  }
  if ($HTTP_GET_VARS['products_id']) {
    $model = tep_db_query("select products_model from products where products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
    $model_values = tep_db_fetch_array($model);
    echo ' : <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&products_id=' . $HTTP_GET_VARS['products_id'], 'NONSSL') . '" class="whitelink">' . $model_values['products_model'] . '</a>';
  }
  if ($location) {
    echo $location;
  }
  echo '</b></font></td>' . "\n"; ?>
    <td align="right" nowrap><font face="<?=HEADER_NAVIGATION_BAR_FONT_FACE;?>" color="<?=HEADER_NAVIGATION_BAR_FONT_COLOR;?>" size="<?=HEADER_NAVIGATION_BAR_FONT_SIZE;?>"><b><?
  if (tep_session_is_registered('customer_id')) {
    echo '<a href="' . tep_href_link(FILENAME_LOGOFF, '', 'NONSSL') . '" class="whitelink">' . HEADER_TITLE_LOGOFF . '</a> &nbsp;|&nbsp; <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL') . '" class="whitelink">' . HEADER_TITLE_MY_ACCOUNT . '</a> &nbsp;|&nbsp; <a href="' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL') . '" class="whitelink">' . HEADER_TITLE_CART_CONTENTS . '</a> &nbsp;|&nbsp; <a href="' . tep_href_link(FILENAME_CHECKOUT, '', 'NONSSL') . '" class="whitelink">' . HEADER_TITLE_CHECKOUT . '</a>&nbsp;&nbsp;';
  } else {
    echo '<a href="' . tep_href_link(FILENAME_LOGIN, '', 'NONSSL') . '" class="whitelink">' . HEADER_TITLE_LOGIN . '</a> &nbsp;|&nbsp; <a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'NONSSL') . '" class="whitelink">' . HEADER_TITLE_CREATE_ACCOUNT . '</a> &nbsp;|&nbsp; <a href="' . tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL') . '" class="whitelink">' . HEADER_TITLE_CART_CONTENTS . '</a> &nbsp;|&nbsp; <a href="' . tep_href_link(FILENAME_CHECKOUT, '', 'NONSSL') . '" class="whitelink">' . HEADER_TITLE_CHECKOUT . '</a>&nbsp;&nbsp;';
  } ?></b></font></td>
  </tr>
</table>
