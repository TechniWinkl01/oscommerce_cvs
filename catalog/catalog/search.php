<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_SEARCH; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ' : ' . NAVBAR_TITLE; ?>
<?
  $search_keywords = explode(' ', trim($HTTP_GET_VARS['query']));
  $search_query = "select m.manufacturers_name, m.manufacturers_id, p.products_id, p.products_model, p.products_name, p.products_price, s.specials_new_products_price, IFNULL(s.specials_new_products_price,p.products_price) as final_price from manufacturers m, products_to_manufacturers p2m, products p left join specials s on p.products_id = s.products_id where p.products_status = '1' and p.products_id = p2m.products_id and p2m.manufacturers_id = m.manufacturers_id and ";
  for ($i=0; ($i<count($search_keywords)-1); $i++ ) {
    $search_query .= "(p.products_name like '%" . $search_keywords[$i] . "%' or m.manufacturers_name like '%" . $search_keywords[$i] . "%') and ";
  }
  $search_query .= "(p.products_name like '%" . $search_keywords[$i] . "%' or m.manufacturers_name like '%" . $search_keywords[$i] . "%') order by ";

  if (!$HTTP_GET_VARS['sort'] || !ereg("[1234][ad]", $HTTP_GET_VARS['sort']))
      $HTTP_GET_VARS['sort'] = '2a';
  
  switch ($HTTP_GET_VARS['sort']) {
    case '1a':
      $search_query .= "p.products_model, p.products_name";
      break;
    case '1d':
      $search_query .= "p.products_model desc, p.products_name";
      break;
    case '2a':
      $search_query .= "p.products_name";
      break;
    case '2d':
      $search_query .= "p.products_name desc";
      break;
    case '3a':
      $search_query .= "m.manufacturers_name, p.products_name";
      break;
    case '3d':
      $search_query .= "m.manufacturers_name desc, p.products_name";
      break;
    case '4a':
      $search_query .= "final_price, p.products_name";
      break;
    case '4d':
      $search_query .= "final_price desc, p.products_name";
      break;
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
