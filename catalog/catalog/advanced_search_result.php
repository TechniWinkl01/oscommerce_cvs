<? include('includes/application_top.php'); ?>
<? $include_file = DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADVANCED_SEARCH_RESULT; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<?
  $error = 0; // reset error flag to false
  $errorno = 0;

  if ( ($HTTP_GET_VARS['keywords'] == "" || strlen($HTTP_GET_VARS['keywords']) < 1) &&
       ($HTTP_GET_VARS['dfrom'] == ""    || $HTTP_GET_VARS['dfrom'] == DOB_FORMAT_STRING || strlen($HTTP_GET_VARS['dfrom']) < 1 ) &&
       ($HTTP_GET_VARS['dto'] == ""      || $HTTP_GET_VARS['dto']   == DOB_FORMAT_STRING || strlen($HTTP_GET_VARS['dto']) < 1) &&
       ($HTTP_GET_VARS['pfrom'] == ""    || strlen($HTTP_GET_VARS['pfrom']) < 1) &&
       ($HTTP_GET_VARS['pto'] == ""      || strlen($HTTP_GET_VARS['pto']) < 1) ) {
    $errorno += 1;
    $error = 1;
  }

  if ($HTTP_GET_VARS['dfrom'] == DOB_FORMAT_STRING)
    $dfrom_to_check = "";
  else
    $dfrom_to_check = $HTTP_GET_VARS['dfrom'];

  if ($HTTP_GET_VARS['dto'] == DOB_FORMAT_STRING)
    $dto_to_check = "";
  else
    $dto_to_check = $HTTP_GET_VARS['dto'];

  if (strlen($dfrom_to_check) > 0) {
    if (!tep_checkdate($dfrom_to_check, DOB_FORMAT_STRING, $dfrom_array)) {
      $errorno += 10;
      $error = 1;
    }
  }  

  if (strlen($dto_to_check) > 0) {
    if (!tep_checkdate($dto_to_check, DOB_FORMAT_STRING, $dto_array)) {
      $errorno += 100;
      $error = 1;
    }
  }  

  if (strlen($dfrom_to_check) > 0 && !(($errorno & 10) == 10) &&
      strlen($dto_to_check) > 0 && !(($errorno & 100) == 100)) {
    if (mktime(0, 0, 0, $dfrom_array[1], $dfrom_array[2], $dfrom_array[0]) > mktime(0, 0, 0, $dto_array[1], $dto_array[2], $dto_array[0])) {
      $errorno += 1000;
      $error = 1;
    }
  }
  
  if (strlen($HTTP_GET_VARS['pfrom']) > 0) {
    $pfrom_to_check = $HTTP_GET_VARS['pfrom'];
    if (!settype($pfrom_to_check, "double")) {
      $errorno += 10000;
      $error = 1;
    }
  }

  if (strlen($HTTP_GET_VARS['pto']) > 0) {
    $pto_to_check = $HTTP_GET_VARS['pto'];
    if (!settype($pto_to_check, "double")) {
      $errorno += 100000;
      $error = 1;
    }
  }

  if (strlen($HTTP_GET_VARS['pfrom']) > 0 && !(($errorno & 10000) == 10000) &&
      strlen($HTTP_GET_VARS['pto']) > 0 && !(($errorno & 100000) == 100000)) {
    if ($pfrom_to_check > $pto_to_check) {
      $errorno += 1000000;
      $error = 1;
    }
  }

  if (strlen($HTTP_GET_VARS['keywords']) > 0) {
    if (!tep_parse_search_string(StripSlashes($HTTP_GET_VARS['keywords']), $search_keywords)) {
      $errorno += 10000000;
      $error = 1;
    }
  }
  
  if ($error == 1) {
    tep_redirect(tep_href_link(FILENAME_ADVANCED_SEARCH, tep_get_all_get_params(array('x', 'y')) . '&errorno=' . $errorno, 'NONSSL'));
  }
  else {
?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE1 . '</a> : ' . NAVBAR_TITLE2; ?>
<html>
<head>
<base href="<? echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<title><?echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_WS_INCLUDES . 'header.php';  include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<?echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
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
            <td width="100%" class="topBarTitle">&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<?echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right">&nbsp;<?echo tep_image(DIR_WS_IMAGES . 'table_background_browse.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td>
<?
  // create column list
  $configuration_query = tep_db_query("select c.configuration_key from " . TABLE_CONFIGURATION_GROUP . " cg, " . TABLE_CONFIGURATION . " c where cg.configuration_group_title = 'Product Listing' and cg.configuration_group_id = c.configuration_group_id and c.configuration_value != '0' and c.configuration_key not in ('PRODUCT_LIST_FILTER', 'PREV_NEXT_BAR_LOCATION') order by c.configuration_value");

  while ($configuration = tep_db_fetch_array($configuration_query)) {
    $column_list[] = $configuration['configuration_key'];
  }

  $select_column_list = '';

  for ($col=0; $col<sizeof($column_list); $col++) {
    if ($column_list[$col] == 'PRODUCT_LIST_BUY_NOW' ||
        $column_list[$col] == 'PRODUCT_LIST_NAME' ||
        $column_list[$col] == 'PRODUCT_LIST_PRICE')
      continue;

    if ($select_column_list != '')
      $select_column_list .= ', ';
    switch ($column_list[$col]) {
      case 'PRODUCT_LIST_MODEL':
        $select_column_list .= 'p.products_model';
        break;
      case 'PRODUCT_LIST_MANUFACTURER':
        $select_column_list .= 'm.manufacturers_name';
        break;
      case 'PRODUCT_LIST_QUANTITY':
        $select_column_list .= 'p.products_quantity';
        break;
      case 'PRODUCT_LIST_IMAGE':
        $select_column_list .= 'p.products_image';
        break;
      case 'PRODUCT_LIST_WEIGHT':
        $select_column_list .= 'p.products_weight';
        break;
    }
  }
  if ($select_column_list != '')
    $select_column_list .= ', ';

  $select_str = "select distinct " . $select_column_list . " m.manufacturers_id, p.products_id, pd.products_name, p.products_price, s.specials_new_products_price, IFNULL(s.specials_new_products_price,p.products_price) as final_price ";
  $from_str = "from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m using(manufacturers_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id";
  $where_str = " where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' ";

  if ($HTTP_GET_VARS['categories_id']) {
    $from_str .= ", " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c ";

    if ($HTTP_GET_VARS['inc_subcat'] == "1") {
      $subcategories_array = array();
      tep_get_subcategories($subcategories_array, $HTTP_GET_VARS['categories_id']);
      $where_str .= " and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and (p2c.categories_id = '" . $HTTP_GET_VARS['categories_id'] . "'";
      for ($i=0; $i<sizeof($subcategories_array); $i++ ) {
        $where_str .= " or p2c.categories_id = '" . $subcategories_array[$i] . "'";
      }
      $where_str .= ")";
    }
    else {
      $where_str .= " and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p2c.categories_id = '" . $HTTP_GET_VARS['categories_id'] . "'";
    }
  }
  if ($HTTP_GET_VARS['manufacturers_id']) {
    $where_str .= " and m.manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id'] . "'";
  }
  if ($HTTP_GET_VARS['keywords']) {
    if (tep_parse_search_string( StripSlashes($HTTP_GET_VARS['keywords']), $search_keywords)) {
      $where_str .= " and (";
      for ($i=0; $i<sizeof($search_keywords); $i++ ) {
        switch ($search_keywords[$i]) {
          case '(':
          case ')':
          case 'and':
          case 'or':
            $where_str .= " " . $search_keywords[$i] . " ";
            break;
          default:
            $where_str .= "(pd.products_name like '%" . AddSlashes($search_keywords[$i]) . "%' or p.products_model like '%" . AddSlashes($search_keywords[$i]) . "%' or m.manufacturers_name like '%" . AddSlashes($search_keywords[$i]) . "%')";
            if ($HTTP_GET_VARS['search_in_description']) $where_str .= " or pd.products_description like '%" . AddSlashes($search_keywords[$i]) . "%'";
            break;
        }
      }
      $where_str .= " )";
    }
  }
  if ($HTTP_GET_VARS['dfrom'] && $HTTP_GET_VARS['dfrom'] != DOB_FORMAT_STRING) {
    $where_str .= " and p.products_date_added >= '" . tep_reformat_date_to_yyyymmdd($HTTP_GET_VARS['dfrom'], DOB_FORMAT_STRING) . "'";
  }
  if ($HTTP_GET_VARS['dto'] && $HTTP_GET_VARS['dto'] != DOB_FORMAT_STRING) {
    $where_str .= " and p.products_date_added <= '" . tep_reformat_date_to_yyyymmdd($HTTP_GET_VARS['dto'], DOB_FORMAT_STRING) . "'";
  }

  $pfrom = $HTTP_GET_VARS['pfrom'] / $currency_rates[$currency];
  $pto = $HTTP_GET_VARS['pto'] / $currency_rates[$currency];
  if ($pfrom && $pto) {
    $where_str .= " and (IFNULL(s.specials_new_products_price,p.products_price) >= " . $pfrom . " and IFNULL(s.specials_new_products_price,p.products_price) <= " . $pto . ")";
  }
  elseif ($pfrom && !$pto) {
    $where_str .= " and (IFNULL(s.specials_new_products_price,p.products_price) >= " . $pfrom . ")";
  }
  elseif (!$pfrom && $pto) {
    $where_str .= " and (IFNULL(s.specials_new_products_price,p.products_price) <= " . $pto . ")";
  }

  $order_str = " order by ";
  
  if (!$HTTP_GET_VARS['sort'] || !ereg("[1-8][ad]", $HTTP_GET_VARS['sort'])) {
    for ($col=0; $col<sizeof($column_list); $col++) {
      if ($column_list[$col] == 'PRODUCT_LIST_NAME') {
        $HTTP_GET_VARS['sort'] = $col+1 . 'a';
        $order_str .= "pd.products_name";
      }
    }
  } else {
    $sort_col = substr($HTTP_GET_VARS['sort'], 0 , 1);
    $sort_order = substr($HTTP_GET_VARS['sort'], 1);

    if ($sort_col <= sizeof($column_list)) {
      switch ($column_list[$sort_col-1]) {
        case 'PRODUCT_LIST_MODEL':
          $order_str .= "p.products_model " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
          break;
        case 'PRODUCT_LIST_NAME':
          $order_str .= "pd.products_name " . ($sort_order == 'd' ? "desc" : "");
          break;
        case 'PRODUCT_LIST_MANUFACTURER':
          $order_str .= "m.manufacturers_name " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
          break;
        case 'PRODUCT_LIST_QUANTITY':
          $order_str .= "p.products_quantity " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
          break;
        case 'PRODUCT_LIST_IMAGE':
          $order_str .= "pd.products_name";
          break;
        case 'PRODUCT_LIST_WEIGHT':
          $order_str .= "p.products_weight " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
          break;
        case 'PRODUCT_LIST_PRICE':
          $order_str .= "final_price " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
          break;
      }        
    } else {
      for ($col=0; $col<sizeof($column_list); $col++) {
        if ($column_list[$col] == 'PRODUCT_LIST_NAME') {
          $HTTP_GET_VARS['sort'] = $col . 'a';
          $order_str .= "pd.products_name";
        }
      }
    }
  }

  $listing_sql = $select_str . $from_str . $where_str . $order_str;
 
  $include_file = DIR_WS_MODULES . FILENAME_PRODUCT_LISTING; include(DIR_WS_INCLUDES . 'include_once.php');
?>
        </td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<?echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
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
<?
  }
?>
<? $include_file = DIR_WS_INCLUDES . 'application_bottom.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>

