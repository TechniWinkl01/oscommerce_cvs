<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_ADVANCED_SEARCH_RESULT; include(DIR_INCLUDES . 'include_once.php'); ?>
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
    if (GregorianToJD($dfrom_array[1], $dfrom_array[2], $dfrom_array[0]) > GregorianToJD($dto_array[1], $dto_array[2], $dto_array[0])) {
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
    header('Location: ' . tep_href_link(FILENAME_ADVANCED_SEARCH, tep_get_all_get_params(array('x', 'y')) . '&errorno=' . $errorno, 'NONSSL'));
    tep_exit();
  }
  else {
?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE1 . '</a> : ' . NAVBAR_TITLE2; ?>
<html>
<head>
<title><?echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_INCLUDES . 'header.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<?echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
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
            <td bgcolor="<?echo TOP_BAR_BACKGROUND_COLOR; ?>" width="100%" nowrap><font face="<?echo TOP_BAR_FONT_FACE; ?>" size="<?echo TOP_BAR_FONT_SIZE; ?>" color="<?echo TOP_BAR_FONT_COLOR; ?>">&nbsp;<?echo TOP_BAR_TITLE; ?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<?echo HEADING_FONT_FACE; ?>" size="<?echo HEADING_FONT_SIZE; ?>" color="<?echo HEADING_FONT_COLOR; ?>">&nbsp;<?echo HEADING_TITLE; ?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<?echo tep_image(DIR_IMAGES . 'table_background_browse.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td>
<?
  $select_str = "select distinct m.manufacturers_id, m.manufacturers_name, p.products_id, p.products_model, p.products_name, p.products_price, s.specials_new_products_price, IFNULL(s.specials_new_products_price,p.products_price) as final_price ";

  $from_str = "from manufacturers m, products_to_manufacturers p2m, products p";

  $where_str = " left join specials s on p.products_id = s.products_id where p.products_status = '1' and p.products_id = p2m.products_id and p2m.manufacturers_id = m.manufacturers_id ";

  if ($HTTP_GET_VARS['categories_id']) {
    $from_str .= ", products_to_categories p2c ";

    if ($HTTP_GET_VARS['inc_subcat'] == "1") {
      $categories = array();
      tep_get_subcategories($categories, $HTTP_GET_VARS['categories_id']);
      $where_str .= " and p2c.products_id = p.products_id and (p2c.categories_id = '" . $HTTP_GET_VARS['categories_id'] . "'";
      for ($i=0; $i<sizeof($categories); $i++ ) {
        $where_str .= " or p2c.categories_id = '" . $categories[$i] . "'";
      }
      $where_str .= ")";
    }
    else {
      $where_str .= " and p2c.products_id = p.products_id and p2c.categories_id = '" . $HTTP_GET_VARS['categories_id'] . "'";
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
            $where_str .= "(p.products_name like '%" . AddSlashes($search_keywords[$i]) . "%' or p.products_description like '%" . AddSlashes($search_keywords[$i]) . "%' or p.products_model like '%" . AddSlashes($search_keywords[$i]) . "%' or m.manufacturers_name like '%" . AddSlashes($search_keywords[$i]) . "%')";
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

  if ($HTTP_GET_VARS['pfrom'] && $HTTP_GET_VARS['pto']) {
    $where_str .= " and (IFNULL(s.specials_new_products_price,p.products_price) >= " . $HTTP_GET_VARS['pfrom'] . " and IFNULL(s.specials_new_products_price,p.products_price) <= " . $HTTP_GET_VARS['pto'] . ")";
  }
  elseif ($HTTP_GET_VARS['pfrom'] && !$HTTP_GET_VARS['pto']) {
    $where_str .= " and (IFNULL(s.specials_new_products_price,p.products_price) >= " . $HTTP_GET_VARS['pfrom'] . ")";
  }
  elseif (!$HTTP_GET_VARS['pfrom'] && $HTTP_GET_VARS['pto']) {
    $where_str .= " and (IFNULL(s.specials_new_products_price,p.products_price) <= " . $HTTP_GET_VARS['pto'] . ")";
  }

  $order_str = " order by ";
  
  if (!$HTTP_GET_VARS['sort'] || !ereg("[1234][ad]", $HTTP_GET_VARS['sort']))
      $HTTP_GET_VARS['sort'] = '2a';
  
  switch ($HTTP_GET_VARS['sort']) {
    case '1a':
      $order_str .= "p.products_model, p.products_name";
      break;
    case '1d':
      $order_str .= "p.products_model desc, p.products_name";
      break;
    case '2a':
      $order_str .= "p.products_name";
      break;
    case '2d':
      $order_str .= "p.products_name desc";
      break;
    case '3a':
      $order_str .= "m.manufacturers_name, p.products_name";
      break;
    case '3d':
      $order_str .= "m.manufacturers_name desc, p.products_name";
      break;
    case '4a':
      $order_str .= "final_price, p.products_name";
      break;
    case '4d':
      $order_str .= "final_price desc, p.products_name";
      break;
  }

  $listing_sql = $select_str . $from_str . $where_str . $order_str;
 
  $include_file = DIR_MODULES . 'product_listing.php'; include(DIR_INCLUDES . 'include_once.php');
?>
        </td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<?echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
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
<?
  }
?>
<? $include_file = DIR_INCLUDES . 'application_bottom.php'; include(DIR_INCLUDES . 'include_once.php'); ?>