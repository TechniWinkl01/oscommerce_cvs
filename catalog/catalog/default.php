<? include('includes/application_top.php'); ?>
<?
// remember the following cPath references come from application_top.php
  $category_depth = 'top';
  if ($cPath) {
    $categories_products_query = tep_db_query("select count(*) as total from products_to_categories where categories_id = '" . $current_category_id . "'");
    $cateqories_products = tep_db_fetch_array($categories_products_query);
    if ($cateqories_products['total'] > 0) {
      $category_depth = 'products'; // display products
    } else {
      $category_parent_query = tep_db_query("select count(*) as total from categories where parent_id = '" . $current_category_id . "'");
      $category_parent = tep_db_fetch_array($category_parent_query);
      if ($category_parent['total'] > 0) {
        $category_depth = 'nested'; // navigate through the categories
      } else {
        $category_depth = 'products'; // category has no products, but display the 'no products' message
      }
    }
  }
?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_DEFAULT; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ''; ?>
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
<?
  if ($category_depth == 'nested') {
?>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
            <td bgcolor="<? echo TOP_BAR_BACKGROUND_COLOR; ?>" width="100%" nowrap><?php echo FONT_STYLE_TOP_BAR; ?>&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
<?
    $category_query = tep_db_query("select categories_name, categories_image from categories where categories_id = '" . $current_category_id . "'");
    $category = tep_db_fetch_array($category_query);
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><?php echo FONT_STYLE_HEADING; ?>&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<? echo tep_image($category['categories_image'], $category['categories_name'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr bgcolor="<? echo SUB_BAR_BACKGROUND_COLOR; ?>">
            <td nowrap><?php echo FONT_STYLE_SUB_BAR; ?>&nbsp;<? echo SUB_BAR_TITLE; ?>&nbsp;</font></td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
<?
    if (($HTTP_GET_VARS['cPath']) && (ereg('_', $HTTP_GET_VARS['cPath']))) {
// check to see if there are deeper categories within the current category
      $category_links = tep_array_reverse($cPath_array);
      for($i=0;$i<sizeof($category_links);$i++) {
        $categories = tep_db_query("select categories_id, categories_name, parent_id from categories where parent_id = '" . $category_links[$i] . "' order by sort_order, categories_name");
        if (tep_db_num_rows($categories) < 1) {
          // do nothing, go through the loop
        } else {
          break; // we've found the deepest category the customer is in
        }
      }
    } else {
      $categories = tep_db_query("select categories_id, categories_name, categories_image, parent_id from categories where parent_id = '" . $current_category_id . "' order by sort_order, categories_name");
    }

    $rows = 0;
    while ($categories_values = tep_db_fetch_array($categories)) {
      $rows++;
      $cPath_new = tep_get_path($categories_values['categories_id']);
      echo '                <td align="center">' . FONT_STYLE_MAIN . '<a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new, 'NONSSL') . '">' . tep_image($categories_values['categories_image'], $categories_values['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT) . '<br>' . $categories_values['categories_name'] . '</a></font></td>' . "\n";
      if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != tep_db_num_rows($categories))) {
        echo '              </tr>' . "\n";
        echo '              <tr>' . "\n";
      }
    }
?>
              </tr>
            </table></td>
          </tr>
<?
    $new_products_category_id = $current_category_id;
    $include_file = DIR_MODULES . FILENAME_NEW_PRODUCTS; include(DIR_INCLUDES . 'include_once.php');
?>
        </table></td>
      </tr>
    </table></td>
<?
  } elseif ($category_depth == 'products' || $HTTP_GET_VARS['manufacturers_id']) {
?>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
            <td bgcolor="<? echo TOP_BAR_BACKGROUND_COLOR; ?>" width="100%" nowrap><?php echo FONT_STYLE_TOP_BAR; ?>&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
<?
    // create column list
    $configuration_query = tep_db_query("select c.configuration_key from configuration_group cg, configuration c where cg.configuration_group_title = 'Product Listing' and cg.configuration_group_id = c.configuration_group_id and c.configuration_value != '0' and c.configuration_key not in ('PRODUCT_LIST_FILTER', 'PREV_NEXT_BAR_LOCATION', 'PRODUCT_LIST_USE_ROLLOVER', 'PRODUCT_LIST_BACKGROUND_COLOR', 'PRODUCT_LIST_ALTERNATE_COLOR') order by c.configuration_value");

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

    if ($HTTP_GET_VARS['manufacturers_id']) {
      if ($HTTP_GET_VARS['filter_id']) {
        $listing_sql = "select " . $select_column_list . " p.products_id, p.products_name, m.manufacturers_id, p.products_price, s.specials_new_products_price, IFNULL(s.specials_new_products_price,p.products_price) as final_price from products p, manufacturers m, products_to_manufacturers p2m, products_to_categories p2c left join specials s on p.products_id = s.products_id where p.products_status = '1' and p.products_id = p2m.products_id and p2m.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id'] . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . $HTTP_GET_VARS['filter_id'] . "' order by ";
      } else {
        $listing_sql = "select " . $select_column_list . " p.products_id, p.products_name, m.manufacturers_id, p.products_price, s.specials_new_products_price, IFNULL(s.specials_new_products_price,p.products_price) as final_price from products p, manufacturers m, products_to_manufacturers p2m left join specials s on p.products_id = s.products_id where p.products_status = '1' and p.products_id = p2m.products_id and p2m.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id'] . "' order by ";
      }
      $filterlist_sql = "select distinct c.categories_id as id, c.categories_name as name from products p, products_to_manufacturers p2m, products_to_categories p2c, categories c where p.products_status = '1' and p.products_id = p2m.products_id and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2m.manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id'] . "' order by c.categories_name";
    } else {
      if ($HTTP_GET_VARS['filter_id']) {
        $listing_sql = "select " . $select_column_list . " p.products_id, p.products_name, m.manufacturers_id, p.products_price, s.specials_new_products_price, IFNULL(s.specials_new_products_price,p.products_price) as final_price from products p, manufacturers m, products_to_manufacturers p2m, products_to_categories p2c left join specials s on p.products_id = s.products_id where p.products_status = '1' and p.products_id = p2m.products_id and p2m.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . $HTTP_GET_VARS['filter_id'] . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . $current_category_id . "' order by ";
      } else {
        $listing_sql = "select " . $select_column_list . " p.products_id, p.products_name, m.manufacturers_id, p.products_price, s.specials_new_products_price, IFNULL(s.specials_new_products_price,p.products_price) as final_price from products p, manufacturers m, products_to_manufacturers p2m, products_to_categories p2c left join specials s on p.products_id = s.products_id where p.products_status = '1' and p.products_id = p2m.products_id and p2m.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p2c.categories_id = '" . $current_category_id . "' order by ";
      }
      $filterlist_sql= "select distinct m.manufacturers_id as id, m.manufacturers_name as name from products p, products_to_manufacturers p2m, products_to_categories p2c, manufacturers m where p.products_status = '1' and p.products_id = p2m.products_id and p2m.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p2c.categories_id = '" . $current_category_id . "' order by m.manufacturers_name";
    }

    if (!$HTTP_GET_VARS['sort'] || !ereg("[1-8][ad]", $HTTP_GET_VARS['sort'])) {
      for ($col=0; $col<sizeof($column_list); $col++) {
        if ($column_list[$col] == 'PRODUCT_LIST_NAME') {
          $HTTP_GET_VARS['sort'] = $col+1 . 'a';
          $listing_sql .= "p.products_name";
        }
      }
    }
    else {
      $sort_col = substr($HTTP_GET_VARS['sort'], 0 , 1);
      $sort_order = substr($HTTP_GET_VARS['sort'], 1);

      if ($sort_col <= sizeof($column_list)) {
        switch ($column_list[$sort_col-1]) {
          case 'PRODUCT_LIST_MODEL':
            $listing_sql .= "p.products_model " . ($sort_order == 'd' ? "desc" : "") . ", p.products_name";
            break;
          case 'PRODUCT_LIST_NAME':
            $listing_sql .= "p.products_name " . ($sort_order == 'd' ? "desc" : "");
            break;
          case 'PRODUCT_LIST_MANUFACTURER':
            $listing_sql .= "m.manufacturers_name " . ($sort_order == 'd' ? "desc" : "") . ", p.products_name";
            break;
          case 'PRODUCT_LIST_QUANTITY':
            $listing_sql .= "p.products_quantity " . ($sort_order == 'd' ? "desc" : "") . ", p.products_name";
            break;
          case 'PRODUCT_LIST_IMAGE':
            $listing_sql .= "p.products_name";
            break;
          case 'PRODUCT_LIST_WEIGHT':
            $listing_sql .= "p.products_weight " . ($sort_order == 'd' ? "desc" : "") . ", p.products_name";
            break;
          case 'PRODUCT_LIST_PRICE':
            $listing_sql .= "final_price " . ($sort_order == 'd' ? "desc" : "") . ", p.products_name";
            break;
        }        
      }
      else {
        for ($col=0; $col<sizeof($column_list); $col++) {
          if ($column_list[$col] == 'PRODUCT_LIST_NAME') {
            $HTTP_GET_VARS['sort'] = $col . 'a';
            $listing_sql .= "p.products_name";
          }
        }
      }
    }
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <form>
          <tr>
            <td nowrap><?php echo FONT_STYLE_HEADING; ?>&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</font></td>
<?
// optional Product List Filter
    if (PRODUCT_LIST_FILTER) {
      $filterlist = tep_db_query($filterlist_sql);
      if (tep_db_num_rows($filterlist) > 1) {
        echo '            <td align="center">' . FONT_STYLE_MAIN . "\n";
        echo '              ' . TEXT_SHOW . "\n";
        echo '              <select size="1" onChange="if(options[selectedIndex].value) window.location.href=(options[selectedIndex].value)">' . "\n";

        if ($HTTP_GET_VARS['manufacturers_id']) {
          $arguments = 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] ;
        } else {
          $arguments = 'cPath=' . $cPath ;
        }
        $arguments .= '&sort=' . $HTTP_GET_VARS['sort'];

        $option_url = tep_href_link(FILENAME_DEFAULT, $arguments, 'NONSSL');

        if (!$HTTP_GET_VARS['filter_id']) {
          echo '                <option value="' . $option_url . '" SELECTED>All' . "\n";
        } else {
          echo '                <option value="' . $option_url . '">All' . "\n";
        }

        echo '                <option value="">---------------' . "\n";
        while ($filterlist_values = tep_db_fetch_array($filterlist)) {
          $option_url = tep_href_link(FILENAME_DEFAULT, $arguments . '&filter_id=' . $filterlist_values['id'], 'NONSSL');
          if ($HTTP_GET_VARS['filter_id'] && $HTTP_GET_VARS['filter_id'] == $filterlist_values['id']) {
            echo '              <option value="' . $option_url . '" SELECTED>' . $filterlist_values['name'] . '&nbsp;' . "\n" ;
          } else {
            echo '              <option value="' . $option_url . '">' . $filterlist_values['name'] . '&nbsp;' . "\n" ;
          }
        }
        echo '              </select>' . "\n";
        echo '            </font></td>' . "\n";
      }
    }
// Get the right image for the top-right
    $image = DIR_IMAGES . 'table_background_list.gif';
    if ($HTTP_GET_VARS['manufacturers_id']) {
      $image = tep_db_query("select manufacturers_image from manufacturers where manufacturers_id = '" . $HTTP_GET_VARS['manufacturers_id'] . "'");
      $image = tep_db_fetch_array($image);
      $image = $image['manufacturers_image'];
    } elseif ($current_category_id) {
      $image = tep_db_query("select categories_image from categories where categories_id = '" . $current_category_id . "'");
      $image = tep_db_fetch_array($image);
      $image = $image['categories_image'];
    }
?>
            <td align="right" nowrap>&nbsp;<? echo tep_image($image, HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
          </form>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td>
<? $include_file = DIR_MODULES . 'product_listing.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
        </td>
      </tr>
    </table></td>
<?
  } else { // default page
?>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
            <td bgcolor="<? echo TOP_BAR_BACKGROUND_COLOR; ?>" width="100%" nowrap><?php echo FONT_STYLE_TOP_BAR; ?>&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><?php echo FONT_STYLE_HEADING; ?>&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_IMAGES . 'table_background_default.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr bgcolor="<? echo SUB_BAR_BACKGROUND_COLOR; ?>">
            <td nowrap><?php echo FONT_STYLE_SUB_BAR; ?>&nbsp;<? echo SUB_BAR_TITLE; ?>&nbsp;</font></td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td><?php echo FONT_STYLE_MAIN; ?><? echo TEXT_MAIN; ?></font></td>
          </tr>
<?
  $new_products_category_id = '0'; $include_file = DIR_MODULES . FILENAME_NEW_PRODUCTS; include(DIR_INCLUDES . 'include_once.php');
  $include_file = DIR_MODULES . FILENAME_UPCOMING_PRODUCTS; include(DIR_INCLUDES . 'include_once.php');
?>
        </table></td>
      </tr>
    </table></td>
<?
  }
?>
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
