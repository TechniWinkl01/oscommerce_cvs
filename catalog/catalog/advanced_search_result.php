<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_ADVANCED_SEARCH_RESULT; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE1 . '</a> : ' . NAVBAR_TITLE2; ?>
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
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_IMAGES . 'table_background_browse.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td>
<?
  $select_str = "select distinct m.manufacturers_name, m.manufacturers_location, p.products_id, p.products_model, p.products_name, p.products_price ";

  if ($HTTP_GET_VARS['pfrom'] || $HTTP_GET_VARS['pto'])
    $select_str .= ", s.specials_new_products_price ";

  $from_str = "from manufacturers m, products_to_manufacturers p2m, products p";
  if ($HTTP_GET_VARS['pfrom'] || $HTTP_GET_VARS['pto'])
    $where_str = " left join specials s on p.products_id = s.products_id";
  else
    $where_str = "";
  $where_str .= " where p.products_status = '1' and p.products_id = p2m.products_id and p2m.manufacturers_id = m.manufacturers_id ";

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
    $search_keywords = explode(' ', trim($HTTP_GET_VARS['keywords']));
    for ($i=0; $i<sizeof($search_keywords); $i++ ) {
      $where_str .= " and (p.products_name like '%" . $search_keywords[$i] . "%' or p.products_description like '%" . $search_keywords[$i] . "%' or p.products_model like '%" . $search_keywords[$i] . "%')";
    }
  }
  if ($HTTP_GET_VARS['dfrom']) {
    $where_str .= " and p.products_date_added >= '" . tep_reformat_date_to_yyyymmdd($HTTP_GET_VARS['dfrom'], DOB_FORMAT_STRING) . "'";
  }
  if ($HTTP_GET_VARS['dto']) {
    $where_str .= " and p.products_date_added <= '" . tep_reformat_date_to_yyyymmdd($HTTP_GET_VARS['dto'], DOB_FORMAT_STRING) . "'";
  }

  if ($HTTP_GET_VARS['pfrom'] && $HTTP_GET_VARS['pto']) {
    $where_str .= " and ((s.specials_new_products_price is null and p.products_price >= " . $HTTP_GET_VARS['pfrom'] . " and p.products_price <= " . $HTTP_GET_VARS['pto'] . ") or (s.specials_new_products_price is not null and s.specials_new_products_price >= " . $HTTP_GET_VARS['pfrom'] . " and s.specials_new_products_price <= " . $HTTP_GET_VARS['pto'] . "))";
  }
  elseif ($HTTP_GET_VARS['pfrom'] && !$HTTP_GET_VARS['pto']) {
    $where_str .= " and ((s.specials_new_products_price is null and p.products_price >= " . $HTTP_GET_VARS['pfrom'] . ") or (s.specials_new_products_price is not null and s.specials_new_products_price >= " . $HTTP_GET_VARS['pfrom'] . "))";
  }
  elseif (!$HTTP_GET_VARS['pfrom'] && $HTTP_GET_VARS['pto']) {
    $where_str .= " and ((s.specials_new_products_price is null and p.products_price <= " . $HTTP_GET_VARS['pto'] . ") or (s.specials_new_products_price is not null and s.specials_new_products_price <= " . $HTTP_GET_VARS['pto'] . "))";
  }

  switch ($HTTP_GET_VARS['sortby']) {
    case 1:
      $order_str = " order by c.categries_name";
      break;
    case 2:
      $order_str = " order by m.manufacturers_name";
      break;
    case 3:
      $order_str = " order by p.products_name";
      break;
    case 4:
      $order_str = " order by p.products_price";
      break;
    default:  
      $order_str = " order by p.products_name";
  }

  $listing_sql = $select_str . $from_str . $where_str . $order_str;
  $include_file = DIR_MODULES . 'product_listing.php'; include(DIR_INCLUDES . 'include_once.php');
?>
        </td>
      </tr>
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