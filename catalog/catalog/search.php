<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_SEARCH; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ' : ' . NAVBAR_TITLE; ?>
<?
  // create column list
  $configuration_query = tep_db_query("select c.configuration_key from configuration_group cg, configuration c where cg.configuration_group_title = 'Product Listing' and cg.configuration_group_id = c.configuration_group_id and c.configuration_value != '0' and c.configuration_key not in ('PRODUCT_LIST_FILTER', 'PREV_NEXT_BAR_LOCATION') order by c.configuration_value");

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

  $search_keywords = explode(' ', trim($HTTP_GET_VARS['query']));
  $search_query = "select " . $select_column_list . " m.manufacturers_id, p.products_id, p.products_name, p.products_price, s.specials_new_products_price, IFNULL(s.specials_new_products_price,p.products_price) as final_price from manufacturers m, products_to_manufacturers p2m, products p left join specials s on p.products_id = s.products_id where p.products_status = '1' and p.products_id = p2m.products_id and p2m.manufacturers_id = m.manufacturers_id and ";
  for ($i=0; ($i<count($search_keywords)-1); $i++ ) {
    $search_query .= "(p.products_name like '%" . $search_keywords[$i] . "%' or m.manufacturers_name like '%" . $search_keywords[$i] . "%') and ";
  }

  $search_query .= "(p.products_name like '%" . $search_keywords[$i] . "%' or m.manufacturers_name like '%" . $search_keywords[$i] . "%') order by ";

  if (!$HTTP_GET_VARS['sort'] || !ereg("[1-8][ad]", $HTTP_GET_VARS['sort'])) {
    for ($col=0; $col<sizeof($column_list); $col++) {
      if ($column_list[$col] == 'PRODUCT_LIST_NAME') {
        $HTTP_GET_VARS['sort'] = $col+1 . 'a';
        $search_query .= "p.products_name";
      }
    }
  } else {
    $sort_col = substr($HTTP_GET_VARS['sort'], 0 , 1);
    $sort_order = substr($HTTP_GET_VARS['sort'], 1);

    if ($sort_col <= sizeof($column_list)) {
      switch ($column_list[$sort_col-1]) {
        case 'PRODUCT_LIST_MODEL':
          $search_query .= "p.products_model " . ($sort_order == 'd' ? "desc" : "") . ", p.products_name";
          break;
        case 'PRODUCT_LIST_NAME':
          $search_query .= "p.products_name " . ($sort_order == 'd' ? "desc" : "");
          break;
        case 'PRODUCT_LIST_MANUFACTURER':
          $search_query .= "m.manufacturers_name " . ($sort_order == 'd' ? "desc" : "") . ", p.products_name";
          break;
        case 'PRODUCT_LIST_QUANTITY':
          $search_query .= "p.products_quantity " . ($sort_order == 'd' ? "desc" : "") . ", p.products_name";
          break;
        case 'PRODUCT_LIST_IMAGE':
          $search_query .= "p.products_name";
          break;
        case 'PRODUCT_LIST_WEIGHT':
          $search_query .= "p.products_weight " . ($sort_order == 'd' ? "desc" : "") . ", p.products_name";
          break;
        case 'PRODUCT_LIST_PRICE':
          $search_query .= "final_price " . ($sort_order == 'd' ? "desc" : "") . ", p.products_name";
          break;
      }        
    } else {
      for ($col=0; $col<sizeof($column_list); $col++) {
        if ($column_list[$col] == 'PRODUCT_LIST_NAME') {
          $HTTP_GET_VARS['sort'] = $col . 'a';
          $search_query .= "p.products_name";
        }
      }
    }
  }
?>
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
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<? echo HEADING_FONT_FACE; ?>" size="<? echo HEADING_FONT_SIZE; ?>" color="<? echo HEADING_FONT_COLOR; ?>">&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_IMAGES . 'table_background_browse.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td>
<?
  $listing_sql = $search_query;
  $include_file = DIR_MODULES . 'product_listing.php'; include(DIR_INCLUDES . 'include_once.php');
?>
        </td>
      </tr>
    </table></td>
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
