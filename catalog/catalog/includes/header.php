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
  if ($HTTP_GET_VARS['category_id']) {
    $category_top = tep_db_query("select category_top_name from category_top where category_top_id = '" . $HTTP_GET_VARS['category_id'] . "'");
    $category_top_values = tep_db_fetch_array($category_top);
    echo ' : <a href="' . tep_href_link(FILENAME_DEFAULT, 'category_id=' . $HTTP_GET_VARS["category_id"], 'NONSSL') . '" class="whitelink">' . $category_top_values["category_top_name"] . '</a>';
  }
  if (($HTTP_GET_VARS['category_id']) && ($HTTP_GET_VARS['index_id'])) {
    $category_index = tep_db_query("select category_index_name from category_index where category_index_id = '" . $HTTP_GET_VARS['index_id'] . "'");
    $category_index_values = tep_db_fetch_array($category_index);
    echo ' : <a href="' . tep_href_link(FILENAME_DEFAULT, 'category_id=' . $HTTP_GET_VARS["category_id"] . '&index_id=' . $HTTP_GET_VARS["index_id"], 'NONSSL') . '" class="whitelink">' . $category_index_values["category_index_name"] . '</a>';
  }
  if (($HTTP_GET_VARS['category_id']) && ($HTTP_GET_VARS['index_id']) && ($HTTP_GET_VARS['subcategory_id'])) {
    $listby_query = tep_db_query("select sql_select from category_index where category_index_id = '" . $HTTP_GET_VARS['index_id'] . "'");
    $listby_values = tep_db_fetch_array($listby_query);
    $listby = $listby_values['sql_select'];

    $subcategory = tep_db_query("select " . $listby . "_name as name from " . $listby . " where " . $listby . "_id = '" . $HTTP_GET_VARS['subcategory_id'] . "'");
    $subcategory_values = tep_db_fetch_array($subcategory);
    echo ' : <a href="' . tep_href_link(FILENAME_PRODUCT_LIST, 'category_id=' . $HTTP_GET_VARS['category_id'] . '&index_id=' . $HTTP_GET_VARS['index_id'] . '&subcategory_id=' . $HTTP_GET_VARS['subcategory_id'], 'NONSSL') . '" class="whitelink">' . $subcategory_values['name'] . '</a>';
  }
  if ($HTTP_GET_VARS['products_id']) {
    $model = tep_db_query("select products_model from products where products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
    $model_values = tep_db_fetch_array($model);
    echo ' : <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'category_id=' . $HTTP_GET_VARS['category_id'] . '&index_id=' . $HTTP_GET_VARS['index_id'] . '&subcategory_id=' . $HTTP_GET_VARS['subcategory_id'] . '&products_id=' . $HTTP_GET_VARS['products_id'], 'NONSSL') . '" class="whitelink">' . $model_values['products_model'] . '</a>';
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
